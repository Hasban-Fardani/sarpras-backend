<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\UserController;
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
    
    Route::apiResource('users', UserController::class);
});