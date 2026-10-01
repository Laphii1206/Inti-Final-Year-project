<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // If session doesn't have locale/theme but user has, set session
            if (!Session::has('locale') && $user->language) {
                Session::put('locale', $user->language);
            }
            if (!Session::has('theme') && $user->theme) {
                Session::put('theme', $user->theme);
            }
        }

        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        
        return $next($request);
    }
}
