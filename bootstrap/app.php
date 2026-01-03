<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: [
            'prefix' => 'api',
            'middleware' => 'api',
            'path' => __DIR__.'/../routes/api.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'subscription.required' => \App\Http\Middleware\EnsureActiveSubscription::class,
        ]);
    })
    ->withBroadcasting(__DIR__.'/../routes/channels.php')
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
