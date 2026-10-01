<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $deleteRequest = \App\Models\DeleteAccountRequest::where('user_id', auth()->id())
            ->where('status', 'pending')->latest()->first();
            
        return view('settings.index', compact('deleteRequest'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $validated = [];

        if ($request->has('language')) {
            $validated = $request->validate([
                'language' => 'required|in:en,ms,zh,ta',
            ]);
        }

        if ($request->filled('password')) {
            $request->validate([
                'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                    if (! \Illuminate\Support\Facades\Hash::check($value, $user->password)) {
                        $fail(__('account.settings_current_pwd_wrong'));
                    }
                }],
                'password' => 'required|string|min:8|confirmed',
            ]);
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if (!empty($validated)) {
            $user->update($validated);
            if (isset($validated['language'])) {
                session(['locale' => $user->language]);
                app()->setLocale($user->language);
            }
        }

        return back()->with('success', __('account.settings_saved') ?? 'Settings saved successfully.');
    }
}
