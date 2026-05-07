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
    ->dailyAt('11:30')
    ->timezone('America/New_York')
    ->appendOutputTo('storage/logs/city-page-generation.log');

// Sync indexing URLs daily (fetch from sitemap)
Schedule::command('indexing:sync')
    ->dailyAt('00:30')
    ->timezone('America/New_York')
    ->appendOutputTo('storage/logs/indexing-sync.log');

// Google Indexing API - submit URLs every 3 days (URLs must be 3+ days old)
Schedule::command('google:index')
    ->cron('0 3 * * *') // Every 3 days at 3 AM EST
    ->timezone('America/New_York')
    ->appendOutputTo('storage/logs/google-indexing.log');

// Check indexing status daily at 4 AM
Schedule::command('google:index --check')
    ->dailyAt('04:00')
    ->timezone('America/New_York')
    ->appendOutputTo('storage/logs/google-indexing-check.log');
