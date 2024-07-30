<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ItemInController;
use App\Http\Controllers\Admin\ItemInDetailController;
use App\Http\Controllers\Admin\ItemOutController;
use App\Http\Controllers\Admin\ItemOutDetailController;
use App\Http\Controllers\Admin\RequestItemController;
use App\Http\Controllers\Admin\RequestItemDetailController;
use App\Http\Controllers\Admin\SubmissionItemController;
use App\Http\Controllers\Admin\SubmissionItemDetailController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Supervisor\RequestCheckController;
use App\Http\Controllers\Supervisor\SubmissionCheckController;
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
    Route::get('/check', function () {
        return auth()->check();
    })->middleware('auth:sanctum');
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

    Route::apiResource('users', UsersController::class);

    Route::apiResource('category', CategoryController::class);

    Route::apiResource('item', ItemController::class);

    Route::apiResource('supplier', SupplierController::class);

    Route::apiResource('item-in', ItemInController::class)
        ->except('update');
    Route::apiResource('item-in.detail', ItemInDetailController::class)
        ->except('show');
    
    Route::apiResource('item-out', ItemOutController::class)
        ->except('update');
    Route::apiResource('item-out.detail', ItemOutDetailController::class)
        ->except('show');

    Route::post('/submission/{submission}/accept', [SubmissionCheckController::class, 'accept']);
    Route::post('/submission/{submission}/decline', [SubmissionCheckController::class, 'decline']);
    Route::apiResource('submission', SubmissionItemController::class)
        ->except('update');
    Route::apiResource('submission.detail', SubmissionItemDetailController::class)
        ->except('show');

    Route::post('/request/{request}/accept', [RequestCheckController::class, 'accept']);
    Route::post('/request/{request}/decline', [RequestCheckController::class, 'decline']);
    Route::apiResource('request-item', RequestItemController::class)
        ->except('update');
    Route::apiResource('request-item.detail', RequestItemDetailController::class)
        ->except('show')
        ->parameter('detail', 'requestItemDetail');
});