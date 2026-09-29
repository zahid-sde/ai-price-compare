<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily 12:00 price scraper & auto-updater worker
app()->booted(function () {
    $schedule = app(Schedule::class);
    $schedule->command('ai:fetch-real-prices')->dailyAt('00:00');
});
