<?php

// bootstrap/app.php

use App\Http\Middleware\TrackTrafficSource;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // CSRF token exempt for webhooks and API
        $middleware->validateCsrfTokens(except: [
            'webhook/*',
            'api/*',
        ]);

        // Add traffic source tracking to all web requests
        $middleware->web(append: [
            TrackTrafficSource::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
