<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('/login', LoginController::class);
});
