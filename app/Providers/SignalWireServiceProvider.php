<?php

namespace App\Providers;

use App\Services\SignalWireService;
use Illuminate\Support\ServiceProvider;

class SignalWireServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SignalWireService::class, function ($app) {
            return new SignalWireService;
        });
    }

    public function boot(): void
    {
        //
    }
}
