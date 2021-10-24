<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/companies', [\App\Http\Controllers\CompanyController::class, 'index']);
Route::get('/company/{company}', [\App\Http\Controllers\CompanyController::class, 'show']);

Route::apiResource('/company', \App\Http\Controllers\CompanyController::class)->only(['store', 'update', 'destroy']);

//Route::middleware(['auth:sanctum', 'verified'])->group(static function () {
//
//    Route::middleware('accesslevel:2')->group(static function () {
//        Route::apiResource('/reward', \App\Http\Controllers\Reward\RewardController::class)->only(['store', 'update', 'destroy']);
//    });
//
//
//});
