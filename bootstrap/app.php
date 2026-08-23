<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\UsuarioActivo::class);
        $middleware->alias([
            'solo.admin' => \App\Http\Middleware\SoloAdmin::class,
            'access' => \App\Http\Middleware\AccessControl::class,
            'admin.o.control' => \App\Http\Middleware\AdminOControl::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
