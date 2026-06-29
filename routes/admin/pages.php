<?php

use App\Http\Controllers\Admin\PageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('admin/pages')
    ->name('admin.pages.')
    ->controller(PageController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{slug}', 'show')->name('show');
        Route::put('/{id}', 'update')->name('update');
        Route::patch('/{id}/status', 'status')->name('status');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
