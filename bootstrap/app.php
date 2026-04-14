<?php

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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'tenant' => \App\Http\Middleware\TenantMiddleware::class,
            'tenant.scope' => \App\Http\Middleware\TenantScopeMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'super.admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'branch.scope' => \App\Http\Middleware\BranchScopeMiddleware::class,
        ]);

        $middleware->throttle('login', function ($request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->ip());
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
