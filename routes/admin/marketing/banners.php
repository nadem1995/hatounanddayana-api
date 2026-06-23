<?php

use App\Http\Controllers\Admin\Marketing\BannerController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('admin/marketing/banners')
    ->name('admin.marketing.banners.')
    ->controller(BannerController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
