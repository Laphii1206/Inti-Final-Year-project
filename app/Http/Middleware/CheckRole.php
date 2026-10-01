<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /*Handle an incoming request.*/
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403, 'Unauthorized. Administrators only.');
        }

        if ($role === 'mechanic' && !$user->isMechanic() && !$user->isAdmin()) {
            abort(403, 'Unauthorized. Mechanics only.');
        }

        if ($role === 'customer' && !$user->isCustomer()) {
            if ($user->isAdmin()) {
                return redirect()->route('admin.statistics.index');
            }
            if ($user->isMechanic()) {
                return redirect()->route('mechanic.dashboard');
            }
            abort(403, 'Unauthorized. Customers only.');
        }

        return $next($request);
    }
}
