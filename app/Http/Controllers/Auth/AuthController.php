<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginField => $request->login,
            'password'  => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectUser(Auth::user());
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'phone'         => 'required|string|max:20|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
        ]);

        $user = User::forceCreate([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'],
            'password'      => Hash::make($validated['password']),
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'role'          => User::ROLE_CUSTOMER,
        ]);

        $user->notify(new \App\Notifications\WelcomeNotification());

        Auth::login($user);

        if ($request->has('register_face_id')) {
            $request->session()->flash('auto_prompt_webauthn', true);
        }

        return redirect()->route('dashboard')->with('success', 'Account registered successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }

        $existingUser = User::where('email', $googleUser->email)->first();
        $avatarToSave = ($existingUser && $existingUser->avatar && !str_starts_with($existingUser->avatar, 'http'))
            ? $existingUser->avatar
            : $googleUser->avatar;

        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'name'      => $googleUser->name,
                'google_id' => $googleUser->id,
                'avatar'    => $avatarToSave,
            ]
        );

        Auth::login($user);

        return $this->redirectUser($user);
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Facebook login failed. Please try again.');
        }

        if (!$facebookUser->email) {
            return redirect()->route('login')->with('error', 'Your Facebook account has no email address, which is required to log in.');
        }

        $existingUser = User::where('email', $facebookUser->email)->first();
        $avatarToSave = ($existingUser && $existingUser->avatar && !str_starts_with($existingUser->avatar, 'http'))
            ? $existingUser->avatar
            : $facebookUser->avatar;

        $user = User::updateOrCreate(
            ['email' => $facebookUser->email],
            [
                'name'        => $facebookUser->name,
                'facebook_id' => $facebookUser->id,
                'avatar'      => $avatarToSave,
            ]
        );

        Auth::login($user);

        return $this->redirectUser($user);
    }

    protected function redirectUser(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route($user->isMainAdmin() ? 'admin.statistics.index' : 'admin.bookings.index');
        } elseif ($user->isMechanic()) {
            return redirect()->route('mechanic.dashboard');
        }

        return redirect()->route('dashboard');
    }
}
