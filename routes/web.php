<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['laravel'=>app()->version()];
});

Route::post('/login', LoginController::class);
