<?php

use App\Http\Controllers\Admin\Settings\SocialMediaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('admin/settings/social-media')
    ->name('admin.settings.social-media.')
    ->controller(SocialMediaController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/{id}', 'update')->name('update');
        Route::patch('/{id}/status', 'status')->name('status');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
