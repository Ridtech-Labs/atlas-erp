<?php

use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Http\Middleware\ResolveTenant;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            ResolveTenant::class,
        ]);

        $middleware->alias([
            'tenant' => ResolveTenant::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (BusinessException $exception): Response {
            return response()->view('errors.business', [
                'message' => $exception->getMessage(),
            ], $exception->status());
        });
    })->create();
