<?php

use App\Http\Middleware\EnsureAccess;
use App\Http\Middleware\EnsureFeatureAccess;
use App\Http\Middleware\EnsurePermission;
use App\Http\Middleware\EnsureTenantAccess;
use App\Http\Middleware\EnsureTenantUser;
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
            'tenant' => EnsureTenantAccess::class,
            'tenant.user' => EnsureTenantUser::class,
            'permission' => EnsurePermission::class,
            'feature' => EnsureFeatureAccess::class,
            'access' => EnsureAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
