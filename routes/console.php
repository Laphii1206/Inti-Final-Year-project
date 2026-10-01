<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:send-maintenance-reminders')->daily();
Schedule::command('app:send-birthday-notifications')->dailyAt('08:00');
Schedule::command('app:mark-no-show-bookings')->everyFifteenMinutes();
Schedule::command('app:cleanup-unpaid-bookings')->everyFiveMinutes();

