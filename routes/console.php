<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Daily database backup reminder (log only)
Schedule::command('report:calls --period=today')
    ->dailyAt('23:55')
    ->timezone('Asia/Dhaka');

// Daily AI blog post generation (runs daily at random time between 6-9 AM EST)
// Use scheduler:random() in Laravel 11+ or use external script
Schedule::command('blog:generate-daily')
    ->dailyAt('07:30')
    ->timezone('America/New_York')
    ->appendOutputTo('storage/logs/blog-generation.log');

// Daily city service page generation (runs after blog generation)
// Controlled by DAILY_CITY_PAGE_GENERATION env variable (default: 5 cities per day)
Schedule::command('city:generate-daily-pages')
    ->dailyAt('08:00')
    ->timezone('America/New_York')
    ->appendOutputTo('storage/logs/city-page-generation.log');
