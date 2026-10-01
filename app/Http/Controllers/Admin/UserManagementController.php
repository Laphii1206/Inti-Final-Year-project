<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserManagementController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->when($request->search, fn($q) => $q->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            }))
            ->withTrashed()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'bookings.service', 'bookings.review', 'cars',
            'activityLogs' => fn($q) => $q->orderByDesc('created_at')->limit(50),
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function upgradeToMechanic(User $user)
    {
        if ($user->is_main_admin || $user->id === auth()->id()) {
            return back()->with('error', 'Cannot change role of Main Admin or your own account.');
        }
        $user->forceFill(['role' => User::ROLE_MECHANIC])->save();
        return back()->with('success', "{$user->name} has been upgraded to Mechanic.");
    }

    public function downgradeRole(User $user)
    {
        if ($user->is_main_admin || $user->id === auth()->id()) {
            return back()->with('error', 'Cannot change role of Main Admin or your own account.');
        }
        $user->forceFill(['role' => User::ROLE_CUSTOMER])->save();
        return back()->with('success', "Role updated to Customer.");
    }

    public function destroy(User $user)
    {
        if ($user->is_main_admin || $user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete Main Admin or your own account.');
        }
        $user->delete();
        return back()->with('success', 'User removed.');
    }

    public function restore(User $user)
    {
        $user->restore();
        return back()->with('success', 'User restored.');
    }

    public function createSubAdmin(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::forceCreate([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'],
            'password'      => bcrypt($validated['password']),
            'role'          => User::ROLE_ADMIN,
            'is_main_admin' => false,
        ]);

        return back()->with('success', 'Sub-Admin created successfully.');
    }

    /**
     * Remove WebAuthn passkeys for a specific user.
     */
    public function removePasskey(User $user)
    {
        if ($user->is_main_admin) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot modify security settings of the Main Admin.');
        }

        $user->webAuthnCredentials()->delete();

        return redirect()->route('admin.users.index')->with('success', "Face ID / Fingerprint passkeys removed for {$user->name}.");
    }
}