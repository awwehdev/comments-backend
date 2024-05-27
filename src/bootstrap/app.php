<?php

use App\Http\Middleware\AccessTokenViaQueryParameter;
use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        return $middleware->api([
            AccessTokenViaQueryParameter::class,
        ])->alias([
            'auth' => Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        return $exceptions
            ->render(function (ValidationException $exception, Request $request) {
                return response([
                    'status' => false,
                    'message' => $exception->validator->getMessageBag()->first(),
                ])->setStatusCode(400);
            })
            ->render(function (Exception $exception, Request $request) {
                return [
                    'status' => false,
                    'message' => $exception->getMessage(),
                ];
            });
    })->create();
