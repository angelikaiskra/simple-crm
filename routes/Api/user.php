<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/password/reset', [\App\Http\Controllers\UserController::class, 'reset']);

Route::middleware(['auth:sanctum'])->group(static function () {

    Route::get('/user/me', [\App\Http\Controllers\UserController::class, 'me']);
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
    Route::get('/user/{user}', [\App\Http\Controllers\UserController::class, 'show']);

    Route::middleware('accesslevel:3')->group(static function () {
        Route::put('/user/{user}/accesslevel', [\App\Http\Controllers\User\UserController::class, 'changeAccessLevel']);
        Route::apiResource('/user', \App\Http\Controllers\UserController::class)->only(['update', 'destroy']);
    });

});
