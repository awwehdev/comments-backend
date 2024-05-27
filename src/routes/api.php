<?php

use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth:sanctum',
], function ($router) {
    $router->group([
        'prefix' => 'user'
    ], function ($router) {
        $router->resource('comments', UserCommentController::class);
    });
    $router->resource('user', UserController::class);
});
