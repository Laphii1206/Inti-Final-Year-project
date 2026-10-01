<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleAndTheme
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            session(['locale' => $user->language ?? 'en']);
            session(['theme'  => $user->theme ?? 'light']);
        }

        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }

        // We can access session('theme') in blade templates to apply dark/light classes
        return $next($request);
    }
}
