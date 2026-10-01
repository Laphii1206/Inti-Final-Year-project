//frontend for WebAuthnController.php and Face ID/Windows Hello Login
const WebAuthnHelper = {

    //Base64URL Utilities 

    base64urlToBuffer(base64url) {
        const base64 = base64url.replace(/-/g, '+').replace(/_/g, '/');
        const padLen = (4 - base64.length % 4) % 4;
        const padded = base64 + '='.repeat(padLen);
        const binary = atob(padded);
        const buffer = new Uint8Array(binary.length);
        for (let i = 0; i < binary.length; i++) {
            buffer[i] = binary.charCodeAt(i);
        }
        return buffer.buffer;
    },

    bufferToBase64url(buffer) {
        const bytes = new Uint8Array(buffer);
        let binary = '';
        for (let i = 0; i < bytes.length; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
    },

    // CSRF Token
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    },

    // Feature Detection
    isSupported() {
        return window.PublicKeyCredential !== undefined &&
               typeof window.PublicKeyCredential === 'function';
    },

    async isPlatformAuthenticatorAvailable() {
        if (!this.isSupported()) return false;
        try {
            return await PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable();
        } catch {
            return false;
        }
    },

    // REGISTER PASSKEY (Authenticated User) 
    async registerPasskey(statusCallback) {
        statusCallback = statusCallback || function() {};

        try {
            statusCallback('info', 'Preparing registration...');

            // Get registration 
            const optionsResponse = await fetch('/webauthn/register/options', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json',
                },
            });

            if (!optionsResponse.ok) {
                throw new Error('Failed to get registration options.');
            }

            const options = await optionsResponse.json();

            // Convert base64url fields to ArrayBuffers for the browser API
            const publicKeyOptions = {
                challenge: this.base64urlToBuffer(options.challenge),
                rp: options.rp,
                user: {
                    id: this.base64urlToBuffer(options.user.id),
                    name: options.user.name,
                    displayName: options.user.displayName,
                },
                pubKeyCredParams: options.pubKeyCredParams,
                authenticatorSelection: options.authenticatorSelection || {},
                timeout: options.timeout || 60000,
                excludeCredentials: (options.excludeCredentials || []).map(c => ({
                    id: this.base64urlToBuffer(c.id),
                    type: c.type,
                })),
            };

            statusCallback('info', 'Please complete the biometric verification...');

            // Call WebAuthn API
            const credential = await navigator.credentials.create({
                publicKey: publicKeyOptions,
            });

            statusCallback('info', 'Verifying with server...');

            // Send the credential to our server for storage
            const body = {
                id: credential.id,
                rawId: this.bufferToBase64url(credential.rawId),
                type: credential.type,
                response: {
                    attestationObject: this.bufferToBase64url(credential.response.attestationObject),
                    clientDataJSON: this.bufferToBase64url(credential.response.clientDataJSON),
                },
            };

            const verifyResponse = await fetch('/webauthn/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json',
                },
                body: JSON.stringify(body),
            });

            const result = await verifyResponse.json();

            if (result.success) {
                statusCallback('success', result.message || 'Passkey registered successfully!');
                return true;
            } else {
                statusCallback('error', result.message || 'Registration failed.');
                return false;
            }

        } catch (error) {
            if (error.name === 'NotAllowedError') {
                statusCallback('error', 'Registration was cancelled or timed out.');
            } else if (error.name === 'InvalidStateError') {
                statusCallback('error', 'This device is already registered.');
            } else {
                statusCallback('error', 'Registration error: ' + error.message);
            }
            console.error('WebAuthn registration error:', error);
            return false;
        }
    },

    //LOGIN WITH PASSKEY (Guest User)
    async loginWithPasskey(statusCallback) {
        statusCallback = statusCallback || function() {};

        try {
            statusCallback('info', 'Preparing authentication...');

            // Get login challenge options from server
            const optionsResponse = await fetch('/webauthn/login/options', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json',
                },
            });

            if (!optionsResponse.ok) {
                throw new Error('Failed to get login options.');
            }

            const options = await optionsResponse.json();

            // Convert base64url fields to ArrayBuffers
            const publicKeyOptions = {
                challenge: this.base64urlToBuffer(options.challenge),
                rpId: options.rpId,
                userVerification: options.userVerification || 'preferred',
                timeout: options.timeout || 60000,
                allowCredentials: (options.allowCredentials || []).map(c => ({
                    id: this.base64urlToBuffer(c.id),
                    type: c.type,
                    transports: c.transports || ['internal'],
                })),
            };

            statusCallback('info', 'Please verify your identity...');

            //  Call browser WebAuthn API
            const assertion = await navigator.credentials.get({
                publicKey: publicKeyOptions,
            });

            statusCallback('info', 'Verifying with server...');

            // Send the assertion to our server for verification
            const body = {
                id: assertion.id,
                rawId: this.bufferToBase64url(assertion.rawId),
                type: assertion.type,
                response: {
                    authenticatorData: this.bufferToBase64url(assertion.response.authenticatorData),
                    clientDataJSON: this.bufferToBase64url(assertion.response.clientDataJSON),
                    signature: this.bufferToBase64url(assertion.response.signature),
                    userHandle: assertion.response.userHandle
                        ? this.bufferToBase64url(assertion.response.userHandle)
                        : null,
                },
            };

            const verifyResponse = await fetch('/webauthn/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json',
                },
                body: JSON.stringify(body),
            });

            const result = await verifyResponse.json();

            if (result.success) {
                statusCallback('success', result.message || 'Login successful! Redirecting...');
                // Redirect after a brief delay to show the success message
                setTimeout(() => {
                    window.location.href = result.redirect_url || '/dashboard';
                }, 1000);
                return true;
            } else {
                statusCallback('error', result.message || 'Login failed.');
                return false;
            }

        } catch (error) {
            if (error.name === 'NotAllowedError') {
                statusCallback('error', 'Authentication was cancelled or timed out.');
            } else {
                statusCallback('error', 'Login error: ' + error.message);
            }
            console.error('WebAuthn login error:', error);
            return false;
        }
    },

    //Initialize: Show/Hide Buttons Based on Support 

    async init() {
        // We only require the browser to support WebAuthn (window.PublicKeyCredential).
        // The browser only needs to support WebAuthn, allowing users to authenticate using a phone (QR code), USB security key, or built-in biometrics
        const available = this.isSupported();

        document.querySelectorAll('.webauthn-btn').forEach(el => {
            if (available) {
                el.style.display = '';
                el.style.opacity = '0';
                setTimeout(() => { el.style.transition = 'opacity 0.5s'; el.style.opacity = '1'; }, 100);
            } else {
                el.style.display = 'none';
            }
        });

        // Show fallback messages if completely unsupported 
        document.querySelectorAll('.webauthn-unsupported').forEach(el => {
            el.style.display = available ? 'none' : '';
        });
    },
};

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => WebAuthnHelper.init());
