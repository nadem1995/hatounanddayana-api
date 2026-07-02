<?php

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('admin/products')
    ->name('admin.products.')
    ->controller(ProductController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create-data', 'createData')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{slug}', 'show')->name('show');
        Route::get('/edit/{slug}', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::patch('/{id}/status', 'status')->name('status');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::delete('/image-variant/{id}', 'destroyImageVariant')->name('destroy_image_variant');
    });
