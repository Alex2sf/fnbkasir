<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('pos', 'pos')
    ->middleware(['auth'])
    ->name('pos');

Route::view('orders', 'orders')
    ->middleware(['auth'])
    ->name('orders');

Route::view('kitchen', 'kitchen')
    ->middleware(['auth'])
    ->name('kitchen');

Route::view('profile', 'profile')->middleware(['auth'])->name('profile');
Route::view('products', 'products')->middleware(['auth'])->name('products');
Route::view('categories', 'categories')->middleware(['auth'])->name('categories');
Route::view('settings', 'settings')->middleware(['auth'])->name('settings');
Route::view('users', 'users')->middleware(['auth', \App\Http\Middleware\IsAdmin::class])->name('users');

require __DIR__.'/auth.php';
