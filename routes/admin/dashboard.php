<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('admin')
    ->controller(DashboardController::class)
    ->group(function () {
        Route::get('/', 'index')->name('admin.index');
    });
