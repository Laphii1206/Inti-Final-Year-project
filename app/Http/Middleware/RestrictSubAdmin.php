<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictSubAdmin
{
    /**
     * Route name prefixes that sub-admins are explicitly allowed to access.
     * These map 1-to-1 with the route groups defined in web.php.
     * Add new entries here deliberately — do NOT rely on accidental prefix matching.
     */
    private array $allowed = [
        'admin.bookings.',
        'admin.services.',
        'admin.reviews.',
        'admin.promo-codes.',
        'admin.memberships.',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->isMainAdmin()) {
            return $next($request);
        }

        $routeName = $request->route()?->getName() ?? '';

        foreach ($this->allowed as $prefix) {
            // Strict prefix check: the route name must start with this prefix AND
            // the character following the prefix must be a letter (not another dot
            // segment that could match a future unrelated route group).
            if (str_starts_with($routeName, $prefix)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this section.');
    }
}
