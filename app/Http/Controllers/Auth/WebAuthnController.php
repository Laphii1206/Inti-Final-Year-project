<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WebAuthnCredential;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WebAuthnController extends Controller
{

    //  REGISTER PASSKEY (Authenticated Users Only)
    /*Generate registration challenge options.The browser uses these to call navigator.credentials.create()*/
    public function registerOptions(Request $request): JsonResponse
    {
        $user = $request->user();

        // Generate a random challenge
        $challenge = random_bytes(32);
        $request->session()->put('webauthn.register_challenge', base64_encode($challenge));

        // Get existing credential IDs to exclude (prevent duplicate registrations)
        $excludeCredentials = $user->webAuthnCredentials->map(function ($cred) {
            return [
                'id'   => $cred->credential_id, 
                'type' => 'public-key',
            ];
        })->toArray();

        $options = [
            'challenge' => $this->base64urlEncode($challenge),
            'rp' => [
                'name' => config('app.name', 'TRB Auto Care'),
                'id'   => $request->getHost(), 
            ],
            'user' => [
                'id'          => $this->base64urlEncode($user->id . '|' . $user->email),
                'name'        => $user->email,
                'displayName' => $user->name,
            ],
            'pubKeyCredParams' => [
                ['alg' => -7,   'type' => 'public-key'], // ES256
                ['alg' => -257, 'type' => 'public-key'], // RS256
            ],
            'authenticatorSelection' => [
                'authenticatorAttachment' => 'platform', 
                'residentKey'             => 'preferred',
                'userVerification'        => 'preferred',
            ],
            'excludeCredentials' => $excludeCredentials,
            'timeout' => 60000, // 60 seconds
        ];

        return response()->json($options);
    }

    /*Verify and store the new credential from the browser.*/
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'id'       => 'required|string',
            'rawId'    => 'required|string',
            'type'     => 'required|string|in:public-key',
            'response' => 'required|array',
            'response.attestationObject' => 'required|string',
            'response.clientDataJSON'    => 'required|string',
        ]);

        $user = $request->user();
        $sessionChallenge = $request->session()->pull('webauthn.register_challenge');

        if (!$sessionChallenge) {
            return response()->json(['success' => false, 'message' => 'Registration session expired. Please try again.'], 422);
        }

        // Decode clientDataJSON to verify the challenge
        $clientDataJSON = $this->base64urlDecode($request->input('response.clientDataJSON'));
        $clientData = json_decode($clientDataJSON, true);

        if (!$clientData || ($clientData['type'] ?? '') !== 'webauthn.create') {
            return response()->json(['success' => false, 'message' => 'Invalid credential type.'], 422);
        }

        // Verify the challenge matches
        $receivedChallenge = $this->base64urlDecode($clientData['challenge']);
        if (!hash_equals(base64_decode($sessionChallenge), $receivedChallenge)) {
            return response()->json(['success' => false, 'message' => 'Challenge mismatch.'], 422);
        }

        // Verify origin matches
        $expectedOrigin = $request->getSchemeAndHttpHost();
        if (($clientData['origin'] ?? '') !== $expectedOrigin) {
            return response()->json(['success' => false, 'message' => 'Origin mismatch.'], 422);
        }

        // Parse the attestationObject to extract the public key
        $attestationObject = $this->base64urlDecode($request->input('response.attestationObject'));
        $publicKeyData = $this->extractPublicKeyFromAttestation($attestationObject);

        if (!$publicKeyData) {
            return response()->json(['success' => false, 'message' => 'Failed to extract public key from attestation.'], 422);
        }

        // Store the credential
        WebAuthnCredential::create([
            'user_id'       => $user->id,
            'credential_id' => $request->input('id'), // base64url credential ID from browser
            'public_key'    => $publicKeyData['public_key'],
            'sign_count'    => $publicKeyData['sign_count'],
            'name'          => $request->input('device_name', 'My Device'),
        ]);

        return response()->json(['success' => true, 'message' => 'Passkey registered successfully!']);
    }

    /**
     * Delete all WebAuthn credentials for the authenticated user.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        
        // Delete all WebAuthn credentials for the user
        $user->webAuthnCredentials()->delete();
        
        return back()->with('success', 'Passkeys/Face ID removed successfully.');
    }

    
    
    //  LOGIN WITH PASSKEY (Guest Users)
    /*Generate authentication challenge options.The browser uses these to call navigator.credentials.get()*/
    public function loginOptions(Request $request): JsonResponse
    {
        $challenge = random_bytes(32);
        $request->session()->put('webauthn.login_challenge', base64_encode($challenge));

        // Use an empty allowCredentials list so the browser uses its built-in
        // discoverable-credential (resident key) picker to find the passkey locally.
        // Sending ALL credential IDs to unauthenticated users would expose a
        // list of registered users via credential ID enumeration.
        $options = [
            'challenge'        => $this->base64urlEncode($challenge),
            'rpId'             => $request->getHost(),
            'allowCredentials' => [],   // browser will use resident/discoverable credentials
            'userVerification' => 'preferred',
            'timeout'          => 60000,
        ];

        return response()->json($options);
    }

    /*Step 2: Verify the credential assertion and log the user in.*/
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'id'       => 'required|string',
            'rawId'    => 'required|string',
            'type'     => 'required|string|in:public-key',
            'response' => 'required|array',
            'response.authenticatorData' => 'required|string',
            'response.clientDataJSON'    => 'required|string',
            'response.signature'         => 'required|string',
        ]);

        $sessionChallenge = $request->session()->pull('webauthn.login_challenge');

        if (!$sessionChallenge) {
            return response()->json(['success' => false, 'message' => 'Login session expired. Please try again.'], 422);
        }

        // Find the credential
        $credential = WebAuthnCredential::where('credential_id', $request->input('id'))->first();

        if (!$credential) {
            return response()->json(['success' => false, 'message' => 'Passkey not recognized. Please use email/password login.'], 401);
        }

        // Decode clientDataJSON and verify challenge
        $clientDataJSON = $this->base64urlDecode($request->input('response.clientDataJSON'));
        $clientData = json_decode($clientDataJSON, true);

        if (!$clientData || ($clientData['type'] ?? '') !== 'webauthn.get') {
            return response()->json(['success' => false, 'message' => 'Invalid assertion type.'], 422);
        }

        $receivedChallenge = $this->base64urlDecode($clientData['challenge']);
        if (!hash_equals(base64_decode($sessionChallenge), $receivedChallenge)) {
            return response()->json(['success' => false, 'message' => 'Challenge mismatch.'], 422);
        }

        // Verify origin
        $expectedOrigin = $request->getSchemeAndHttpHost();
        if (($clientData['origin'] ?? '') !== $expectedOrigin) {
            return response()->json(['success' => false, 'message' => 'Origin mismatch.'], 422);
        }

        // Verify the signature using the stored public key
        $authenticatorData = $this->base64urlDecode($request->input('response.authenticatorData'));
        $signature = $this->base64urlDecode($request->input('response.signature'));
        $publicKeyPem = $this->coseKeyToPem($credential->public_key);

        if (!$publicKeyPem) {
            return response()->json(['success' => false, 'message' => 'Invalid stored credential.'], 500);
        }

        // The data that was signed = authenticatorData + SHA-256(clientDataJSON)
        $clientDataHash = hash('sha256', $clientDataJSON, true);
        $signedData = $authenticatorData . $clientDataHash;

        $isValid = openssl_verify($signedData, $signature, $publicKeyPem, OPENSSL_ALGO_SHA256);

        if ($isValid !== 1) {
            return response()->json(['success' => false, 'message' => 'Signature verification failed.'], 401);
        }

        // Verify and update sign count (replay attack protection)
        $authDataFlags = unpack('C', $authenticatorData[32])[1] ?? 0;
        $newSignCount = unpack('N', substr($authenticatorData, 33, 4))[1] ?? 0;

        if ($newSignCount > 0 && $newSignCount <= $credential->sign_count) {
            return response()->json(['success' => false, 'message' => 'Possible cloned authenticator detected.'], 401);
        }

        $credential->update(['sign_count' => $newSignCount]);

        // Log the user in
        $user = $credential->user;

        if ($user->trashed()) {
            return response()->json(['success' => false, 'message' => 'This account has been deactivated.'], 401);
        }

        Auth::login($user, true);   // Remember the user/for login 
        $request->session()->regenerate();

        // Determine redirect URL based on role
        $redirectUrl = route('dashboard');     // default customer
        if ($user->isAdmin()) {
            $redirectUrl = route($user->isMainAdmin() ? 'admin.statistics.index' : 'admin.bookings.index');
        } elseif ($user->isMechanic()) {
            $redirectUrl = route('mechanic.dashboard');
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Welcome back, ' . $user->name . '!',
            'redirect_url' => $redirectUrl,
        ]);
    }


    //  HELPER METHODS
    private function base64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64urlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', (4 - strlen($data) % 4) % 4));
    }

    /*Extract the public key and sign count from CBOR-encoded attestationObject.*/
    private function extractPublicKeyFromAttestation(string $attestationObject): ?array
    {
        // CBOR explore it unpack the outer packaging 
        $decoded = $this->decodeCborMap($attestationObject);

        // check authData
        if (!isset($decoded['authData'])) {
            \Illuminate\Support\Facades\Log::error('WebAuthn: "authData" string not found in attestationObject.');
            return null;
        }

        $authData = $decoded['authData'];

        // authData structure:
        // [32 bytes rpIdHash] [1 byte flags] [4 bytes signCount] [variable attestedCredentialData]
        if (strlen($authData) < 37) {
            \Illuminate\Support\Facades\Log::error("WebAuthn: authData too short (" . strlen($authData) . " bytes).");
            return null;
        }

        $flags = ord($authData[32]);
        $signCount = unpack('N', substr($authData, 33, 4))[1];

        // Check AT (Attested Credential Data present) flag (bit 6)
        if (!($flags & 0x40)) {
            \Illuminate\Support\Facades\Log::error("WebAuthn: AT flag not set in authData.");
            return null;
        }

        // Attested credential data starts at offset 37
        // [16 bytes AAGUID] [2 bytes credIdLength] [credIdLength bytes credentialId] [CBOR publicKey]
        $offset = 37;
        $offset += 16; // skip AAGUID
        $credIdLength = unpack('n', substr($authData, $offset, 2))[1];
        $offset += 2;
        $offset += $credIdLength; // skip credential ID

        // The rest is the CBOR-encoded COSE public key
        $coseKeyBytes = substr($authData, $offset);

        return [
            'public_key' => base64_encode($coseKeyBytes),
            'sign_count' => $signCount,
        ];
    }

    /*CBOR Map decoder solve WebAuthn structure */
    private function decodeCborMap(string $data): array
    {
        $pos = 0;
        $result = $this->parseCborItem($data, $pos);
        
        return is_array($result) ? $result : [];
    }

    /*Main method for parsing CBOR data recursively*/
    private function parseCborItem(string $data, int &$pos)
    {
        if ($pos >= strlen($data)) return null;

        $initial = ord($data[$pos]);
        $majorType = ($initial >> 5) & 0x07;
        $addInfo = $initial & 0x1F;
        $pos++;

        $length = $addInfo;
        if ($addInfo === 24) {
            $length = ord($data[$pos++]);
        } elseif ($addInfo === 25) {
            $length = unpack('n', substr($data, $pos, 2))[1];
            $pos += 2;
        } elseif ($addInfo === 26) {
            $length = unpack('N', substr($data, $pos, 4))[1];
            $pos += 4;
        }

        switch ($majorType) {
            case 0: // if positif 
                return $length;
            case 1: // if negatif 
                return -1 - $length;
            case 2: 
            case 3: 
                $str = substr($data, $pos, $length);
                $pos += $length;
                return $str;
            case 4: // list 
                $arr = [];
                for ($i = 0; $i < $length; $i++) {
                    $arr[] = $this->parseCborItem($data, $pos);
                }
                return $arr;
            case 5: 
                $map = [];
                for ($i = 0; $i < $length; $i++) {
                    $key = $this->parseCborItem($data, $pos);
                    $val = $this->parseCborItem($data, $pos);
                    if ($key !== null) {
                        $map[$key] = $val;
                    }
                }
                return $map;
            default:
                return null;
        }
    }

    /*Convert a COSE key (stored as base64) to PEM format for signature verification.Supports ES256 (ECDSA with P-256 curve).*/
    private function coseKeyToPem(string $base64CoseKey): ?string
    {
        $coseKeyBytes = base64_decode($base64CoseKey);
        if (!$coseKeyBytes) return null;

        // Parse the COSE key CBOR map to extract x and y coordinates
        $coseKey = $this->parseCoseKey($coseKeyBytes);

        if (!$coseKey || !isset($coseKey['x'], $coseKey['y'])) {
            return null;
        }

        // Build an uncompressed EC point: 0x04 || x || y
        $point = "\x04" . $coseKey['x'] . $coseKey['y'];

        // Wrap in ASN.1 SubjectPublicKeyInfo for P-256
        $ecPublicKeyOid = hex2bin('06072a8648ce3d0201');   // 1.2.840.10045.2.1
        $prime256v1Oid   = hex2bin('06082a8648ce3d030107'); // 1.2.840.10045.3.1.7

        $algorithmIdentifier = $this->asn1Sequence($ecPublicKeyOid . $prime256v1Oid);
        $bitString = "\x03" . $this->asn1Length(strlen($point) + 1) . "\x00" . $point;
        $spki = $this->asn1Sequence($algorithmIdentifier . $bitString);

        $pem = "-----BEGIN PUBLIC KEY-----\n" .
               chunk_split(base64_encode($spki), 64, "\n") .
               "-----END PUBLIC KEY-----";

        return $pem;
    }

    /*Parse a CBOR-encoded COSE key to extract x and y coordinates (for EC2 keys).*/
    private function parseCoseKey(string $data): ?array
    {
        $result = [];
        $pos = 0;
        $len = strlen($data);

        $initial = ord($data[$pos]);
        $majorType = ($initial >> 5) & 0x07;
        $additionalInfo = $initial & 0x1F;
        $pos++;

        if ($majorType !== 5) return null; // Must be a map

        $mapSize = $additionalInfo;
        if ($additionalInfo === 24) {
            $mapSize = ord($data[$pos++]);
        }

        for ($i = 0; $i < $mapSize && $pos < $len; $i++) {
            // Keys in COSE are integers (negative or positive)
            $keyInitial = ord($data[$pos]);
            $keyMajor = ($keyInitial >> 5) & 0x07;
            $keyAddInfo = $keyInitial & 0x1F;
            $pos++;

            $intKey = null;
            if ($keyMajor === 0) {
                $intKey = $keyAddInfo; // positive integer
            } elseif ($keyMajor === 1) {
                $intKey = -1 - $keyAddInfo; // negative integer
            } else {
                break;
            }

            // Decode value
            $valInitial = ord($data[$pos]);
            $valMajor = ($valInitial >> 5) & 0x07;
            $valAddInfo = $valInitial & 0x1F;
            $pos++;

            if ($valMajor === 2) {
                // Byte string
                $valLen = $valAddInfo;
                if ($valAddInfo === 24) {
                    $valLen = ord($data[$pos++]);
                }
                $value = substr($data, $pos, $valLen);
                $pos += $valLen;

                // COSE key labels: -2 = x coordinate, -3 = y coordinate
                if ($intKey === -2) $result['x'] = $value;
                if ($intKey === -3) $result['y'] = $value;
            } elseif ($valMajor === 0) {
                $pos += 0; // small integer, already consumed
                if ($intKey === 1) $result['kty'] = $valAddInfo;
                if ($intKey === 3) $result['alg'] = $valAddInfo;
            } elseif ($valMajor === 1) {
                $pos += 0; // negative integer, already consumed
                if ($intKey === 3) $result['alg'] = -1 - $valAddInfo;
            } else {
                break;
            }
        }

        return $result;
    }

    private function asn1Sequence(string $data): string
    {
        return "\x30" . $this->asn1Length(strlen($data)) . $data;
    }

    private function asn1Length(int $length): string
    {
        if ($length < 128) {
            return chr($length);
        } elseif ($length < 256) {
            return "\x81" . chr($length);
        }
        return "\x82" . pack('n', $length);
    }
}