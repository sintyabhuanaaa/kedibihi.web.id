<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Models\Product;


Route::redirect('/admin', '/admin/login');
Route::prefix('admin')->group(function () {
    // Login
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');
    });
    
    // Halaman admin (butuh login)
    Route::middleware('auth.admin')->group(function () {
        Route::get('/register', [AdminController::class, 'showRegister'])->name('admin.register');
        Route::post('/register', [AdminController::class, 'register'])->name('admin.register.submit');
        Route::get('/index', [AdminController::class, 'dashboard'])->name('admin.index');
        // Route::get('/produk', [AdminController::class, 'produk'])->name('admin.produk');
        Route::resource('products', ProductController::class);
        Route::post('/change-password', [AdminController::class, 'changePassword'])->name('admin.changePassword');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });

});
