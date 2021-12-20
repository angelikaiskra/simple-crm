<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/password/reset', [UserController::class, 'reset']);

Route::middleware(['auth:sanctum'])->group(static function () {

    Route::get('/user/me', [UserController::class, 'me']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user/{user}', [UserController::class, 'show']);

    Route::group(['middleware' => ['role:moderator|admin']], function () {
        Route::apiResource('/user', UserController::class)->only(['update']);
    });

    Route::group(['middleware' => ['role:admin']], function () {
        Route::apiResource('/user', UserController::class)->only(['destroy']);
    });
});
