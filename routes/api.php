<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ItemInController;
use App\Http\Controllers\Admin\ItemInDetailController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

Route::get('/', function () {
    return [
        "message" => "hello, please visit /docs for documentation",
        "docs" =>  URL::to('/docs')
    ];
});

Route::prefix('/auth')->group(function () {
    Route::post('/login', LoginController::class);
    Route::middleware('auth:sanctum')->post('/logout', LogoutController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    // Admin Routes
    Route::prefix('/admin')->group(function () {
        // Dashboard Routes
        Route::prefix('/dashboard')->group(function () {
            Route::get('/counts', [DashboardController::class, 'getCounts']);
            Route::get('/stats/request', [DashboardController::class, 'getStatsRequest']);
            Route::get('/stats/item', [DashboardController::class, 'getStatsItem']);
        }); 
    });

    Route::apiResource('/category', CategoryController::class);

    Route::apiResource('/item', ItemController::class);

    Route::apiResource('/item-in', ItemInController::class)
        ->except('update');
    Route::apiResource('item-in.detail', ItemInDetailController::class)
        ->except('show');
    
});