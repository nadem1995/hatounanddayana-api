<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('/login', 'login')->name('login');
        Route::post('/register', 'register')->name('register');
    });

Route::middleware('auth:sanctum')
    ->prefix('admin')
    ->name('admin.')
    ->controller(AuthController::class)
    ->group(function () {
        Route::get('/logout', 'logout')->name('logout');
        Route::post('/change-password', 'changePassword')->name('change_password');
    });
