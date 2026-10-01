<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'restrict.subadmin' => \App\Http\Middleware\RestrictSubAdmin::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (\Symfony\Component\Mailer\Exception\TransportException $e, \Illuminate\Http\Request $request) {
            if (str_contains($e->getMessage(), 'Too many emails') || str_contains($e->getMessage(), '550 5.7.0')) {
                \Illuminate\Support\Facades\Log::warning('Mailtrap rate limit hit. Email dropped.');
                if ($request->wantsJson()) {
                    return response()->json(['message' => 'Action successful, but email blocked by Mailtrap limit.'], 200);
                }
                return back()->with('warning', 'Action completed successfully, but the email notification was blocked by Mailtrap free testing limits.');
            }
        });
    })->create();
