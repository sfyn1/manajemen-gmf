<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo(function (Request $request) {
            $user = auth()->user();
            if ($user) {
                return match ($user->role) {
                    'owner'  => route('owner.dashboard'),
                    'admin'  => route('admin.dashboard'),
                    'coach'  => route('coach.dashboard'),
                    'member' => route('member.dashboard'),
                    default  => '/',
                };
            }
            return '/';
        });

        $middleware->validateCsrfTokens(except: [
            'api/midtrans/notification',
            'midtrans/notification',
        ]);

        $middleware->alias([
            'role'           => \App\Http\Middleware\CheckRole::class,
            'member.active'  => \App\Http\Middleware\CheckMemberActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
