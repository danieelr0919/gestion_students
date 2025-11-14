<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::resource('students', App\Http\Controllers\StudentController::class);
Route::resource('products', App\Http\Controllers\ProductController::class);
Route::resource('orders', App\Http\Controllers\OrderController::class);

