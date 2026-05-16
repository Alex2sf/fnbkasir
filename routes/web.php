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

// Route bantuan untuk memperbaiki error 403 logo di cPanel (Broken Symlink)
Route::get('/fix-storage', function () {
    $publicStorage = public_path('storage');
    
    // Hapus symlink lama jika ada dan broken (biasanya karena path dari Windows terbawa ke Linux cPanel)
    if (file_exists($publicStorage) || is_link($publicStorage)) {
        // Di Windows is_link bisa false untuk symlink, tapi hapus saja
        @unlink($publicStorage);
    }
    
    // Buat symlink baru yang sesuai dengan path cPanel Linux
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    
    return 'Storage link berhasil diperbaiki! Silakan cek web Anda lagi.';
});
