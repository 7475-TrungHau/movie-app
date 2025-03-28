<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->middleware('web')->group(function () {
    // Route đăng nhập (không cần middleware)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Các route admin khác (yêu cầu đã đăng nhập và là admin)
    Route::middleware('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/category', [CategoryController::class, 'index'])->name('admin.category.index');
        Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
        Route::post('/category/store', [CategoryController::class, 'store'])->name('admin.category.store');
        Route::get('/category/edit/{category}', [CategoryController::class, 'edit'])->name('admin.category.edit');
        Route::put('/category/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
        Route::delete('/category/delete/{category}', [CategoryController::class, 'destroy'])->name('admin.category.delete');
        Route::get('/category/{category}', [CategoryController::class, 'show'])->name('admin.category.show');
        // Users
        // Route::resource('users', UserController::class);

        // // Movies
        // Route::resource('movies', MovieController::class);

        // // Episodes
        // Route::resource('episodes', EpisodeController::class)->parameters(['episodes' => 'episode']);

        // // Billing (Packages, Subscriptions, Payments)
        // Route::prefix('billing')->group(function () {
        //     Route::get('/packages', [BillingController::class, 'packagesIndex'])->name('admin.billing.packages.index');
        //     Route::get('/packages/create', [BillingController::class, 'packagesCreate'])->name('admin.billing.packages.create');
        //     Route::post('/packages', [BillingController::class, 'packagesStore'])->name('admin.billing.packages.store');
        //     Route::get('/packages/{package}/edit', [BillingController::class, 'packagesEdit'])->name('admin.billing.packages.edit');
        //     Route::put('/packages/{package}', [BillingController::class, 'packagesUpdate'])->name('admin.billing.packages.update');
        //     Route::delete('/packages/{package}', [BillingController::class, 'packagesDestroy'])->name('admin.billing.packages.destroy');

        //     Route::get('/subscriptions', [BillingController::class, 'subscriptionsIndex'])->name('admin.billing.subscriptions.index');
        //     Route::delete('/subscriptions/{subscription}', [BillingController::class, 'subscriptionsDestroy'])->name('admin.billing.subscriptions.destroy');

        //     Route::get('/payments', [BillingController::class, 'paymentsIndex'])->name('admin.billing.payments.index');
        // });
    });
});
