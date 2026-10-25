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
    ->withMiddleware(function (Middleware $middleware) {
        // Redirect users AFTER login based on role
        $middleware->redirectUsersTo(function () {
            if (auth()->check()) {
                return auth()->user()->is_admin ? '/admin/dashboard' : '/staff';
            }
            return '/login';
        });

        // Redirect guests to login, BUT don't loop if they are already there
        $middleware->redirectGuestsTo(function () {
            // If the current page is already login, don't redirect
            if (request()->is('login')) {
                return null; 
            }
            return '/login';
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();