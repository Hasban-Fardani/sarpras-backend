<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/docs');

Route::prefix('/v1')->group(function () {
    Route::post('/login', LoginController::class);
    
    Route::apiResources([
        'users' => UserController::class,
    ]);
});