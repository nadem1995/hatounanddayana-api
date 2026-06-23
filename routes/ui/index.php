<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ui\LayoutDataController;

use App\Http\Controllers\Ui\HomePageController;
use App\Http\Controllers\Ui\ProductController;


Route::get('/layout-data', [LayoutDataController::class,'index']);
Route::get('/home', [HomePageController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products/favorites', [ProductController::class, 'getFavorite']);
