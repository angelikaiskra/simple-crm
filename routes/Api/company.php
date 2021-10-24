<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Protected routes
Route::middleware('auth:sanctum')->group(static function () {

    Route::get('/companies', [\App\Http\Controllers\CompanyController::class, 'index']);

//    Route::middleware('accesslevel:2')->group(static function () {
        Route::apiResource('/company', \App\Http\Controllers\CompanyController::class)->only(['store', 'show', 'update', 'destroy']);
//    });

});
