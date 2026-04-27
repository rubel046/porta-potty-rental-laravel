<?php

// bootstrap/app.php

use App\Http\Middleware\DomainMiddleware;
use App\Http\Middleware\RedirectToCity;
use App\Http\Middleware\TrackTrafficSource;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'webhook/*',
            'api/*',
        ]);

        $middleware->web(append: [
            DomainMiddleware::class,
            TrackTrafficSource::class,
            RedirectToCity::class,
        ]);

        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, $request) {
            if ($e instanceof NotFoundHttpException) {
                return response()->view('errors.404', [], 404);
            }
        });
    })->create();
