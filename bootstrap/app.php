<?php

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'tenant' => \App\Http\Middleware\EnsureTenantAccess::class,
            'permission' => \App\Http\Middleware\EnsurePermission::class,
            'feature' => \App\Http\Middleware\EnsureFeatureAccess::class,
            'access' => \App\Http\Middleware\EnsureAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
