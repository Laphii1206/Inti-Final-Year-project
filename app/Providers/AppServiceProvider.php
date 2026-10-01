<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
class AppServiceProvider extends ServiceProvider
{
    /*Register any application services.*/
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\URL::forceScheme('https');

    if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        \Illuminate\Pagination\Paginator::useBootstrapFive();



        if (!file_exists(public_path('storage')) && !is_link(public_path('storage'))) {
            try {
                \Illuminate\Support\Facades\Artisan::call('storage:link');
            } catch (\Throwable $e) {
                // ignore if symlink cannot be created
            }
        }
    }
}
