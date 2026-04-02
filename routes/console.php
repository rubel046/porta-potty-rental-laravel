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

