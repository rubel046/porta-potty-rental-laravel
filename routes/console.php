<?php

use Illuminate\Support\Facades\Schedule;

// Daily AI blog post generation (runs daily at random time between 6-9 AM EST)
// Use scheduler:random() in Laravel 11+ or use external script
Schedule::command('blog:generate-daily')
    ->dailyAt('07:00')
    ->timezone('America/New_York')
    ->appendOutputTo('storage/logs/blog-generation.log');

// Daily city service page generation (runs after blog generation)
// Controlled by DAILY_CITY_PAGE_GENERATION env variable (default: 5 cities per day)
Schedule::command('city:generate-daily-pages')
//    ->dailyAt('11:30')
    ->everyFiveMinutes()
    ->timezone('America/New_York')
    ->appendOutputTo('storage/logs/city-page-generation.log');

// Google Indexing API - submit URLs every 6 hours (max 200 URLs per batch)
// Also checks and marks indexed status
//Schedule::command('google:index')
//    ->everySixHours()
//    ->timezone('America/New_York')
//    ->appendOutputTo('storage/logs/google-indexing.log');
//
//Schedule::command('google:index --check')
//    ->dailyAt('02:00')
//    ->timezone('America/New_York')
//    ->appendOutputTo('storage/logs/google-indexing-check.log');
