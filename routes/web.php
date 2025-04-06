<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\EpisodeController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

        // Category Management
        Route::get('/category', [CategoryController::class, 'index'])->name('admin.category.index');
        Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
        Route::post('/category/store', [CategoryController::class, 'store'])->name('admin.category.store');
        Route::get('/category/edit/{category}', [CategoryController::class, 'edit'])->name('admin.category.edit');
        Route::put('/category/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
        Route::delete('/category/delete/{category}', [CategoryController::class, 'destroy'])->name('admin.category.delete');
        Route::get('/category/{category}', [CategoryController::class, 'show'])->name('admin.category.show');

        // Movie Management
        Route::get('/movie', [MovieController::class, 'index'])->name('admin.movie.index');
        Route::get('/movie/create', [MovieController::class, 'create'])->name('admin.movie.create');
        Route::post('/movie/store', [MovieController::class, 'store'])->name('admin.movie.store');
        Route::get('/movie/edit/{movie}', [MovieController::class, 'edit'])->name('admin.movie.edit');
        Route::put('/movie/{id}', [MovieController::class, 'update'])->name('admin.movie.update');
        Route::delete('/movie/delete/{movie}', [MovieController::class, 'destroy'])->name('admin.movie.delete');
        Route::get('/movie/{movie}', [MovieController::class, 'show'])->name('admin.movie.show');
        Route::get('/movie/{movie}/episodes', [EpisodeController::class, 'index'])->name('admin.movie.episodes');
        Route::get('/movie/{movie}/episodes/create', [EpisodeController::class, 'create'])->name('admin.movie.episodes.create');
        Route::post('/movie/{movie}/episodes/store', [EpisodeController::class, 'store'])->name('admin.movie.episodes.store');
        Route::get('/movie/{movie}/episodes/edit/{episode}', [EpisodeController::class, 'edit'])->name('admin.movie.episodes.edit');
        Route::put('/movie/{movie}/episodes/{episode}', [EpisodeController::class, 'update'])->name('admin.movie.episodes.update');
        Route::delete('/movie/{movie}/episodes/delete/{episode}', [EpisodeController::class, 'destroy'])->name('admin.movie.episodes.delete');
        Route::get('/movie/{movie}/episodes/{episode}', [EpisodeController::class, 'show'])->name('admin.movie.episodes.show');
        Route::get('/movie/{movie}/episodes/{episode}/watch', [EpisodeController::class, 'watch'])->name('admin.movie.episodes.watch');

        // Package Management
        Route::get('/package', [PackageController::class, 'index'])->name('admin.package.index');
        Route::get('/package/create', [PackageController::class, 'create'])->name('admin.package.create');
        Route::post('/package/store', [PackageController::class, 'store'])->name('admin.package.store');
        Route::get('/package/edit/{package}', [PackageController::class, 'edit'])->name('admin.package.edit');
        Route::put('/package/{package}', [PackageController::class, 'update'])->name('admin.package.update');
        Route::delete('/package/delete/{package}', [PackageController::class, 'destroy'])->name('admin.package.destroy');
        Route::get('/package/{package}', [PackageController::class, 'showMovies'])->name('admin.package.movies');
        // Thêm vào group middleware admin
        Route::get('/package/get-movies', [PackageController::class, 'getMovies'])->name('admin.package.get-movies');

        // Subscription Routes (Individual)
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('admin.subscriptions.index');
        Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('admin.subscriptions.store');
        Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('admin.subscriptions.show');
        Route::get('/subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('admin.subscriptions.edit');
        Route::put('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('admin.subscriptions.update');
        Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('admin.subscriptions.destroy');

        // Subscription Routes (Group)
        Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('admin.payments.show');
        Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('admin.payments.edit');
        Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('admin.payments.update');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('admin.payments.destroy');
    });
});
