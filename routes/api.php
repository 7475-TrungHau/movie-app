<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SearchController;

// Auth routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/verify-code', [AuthController::class, 'verifyCode'])->name('verify-code');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

// User routes (cần đăng nhập)
Route::middleware('auth.jwt')->group(function () {
    Route::get('/user', [UserController::class, 'getUser'])->name('user');
    Route::get('/user/ratings/{movieId}', [UserController::class, 'getRatingByMovie'])->name('user.rating.movie');
    Route::get('/user/favorites/{movieId}', [UserController::class, 'getFavoriteMovies'])->name('user.favorites.movie');
    Route::get('/user/favorites', [UserController::class, 'getFavorites'])->name('user.favorites');
    Route::post('/user/update', [UserController::class, 'update'])->name('user.update');

    // History routes
    Route::get('/user/histories', [UserController::class, 'getHistory'])->name('user.histories');
    // Route::get('/user/all-histories', [UserController::class, 'getHistories'])->name('user.all.histories');
    // Route::delete('/user/histories/{episodeId?}', [UserController::class, 'deleteHistory'])->name('user.history.delete');

    // Movie   (đánh giá, lịch sử xem)
    Route::post('/movies/{movieId}/rating', [MovieController::class, 'Rating'])->name('movie.rating');
    Route::post('/movies/{movieId}/favorite', [MovieController::class, 'Favorite'])->name('movie.favorite');
    Route::post('/movies/{episodeId}/histories', [MovieController::class, 'updateHistory'])->name('movie.history.update');
    Route::post('/episodes/{episodeId}/history', [MovieController::class, 'setHistory'])->name('episode.history');
    Route::get('/payment/vnpay/{package_id}', [PaymentController::class, 'createVnpayPayment'])->name('payment.vnpay');

    Route::get('/payment/getPayment/{subscription_id}', [PaymentController::class, 'getPayment'])->name('payment.getPayment');
    Route::get('/payment/packages', [PaymentController::class, 'getPackages'])->name('payment.packages');
});

// Public movie routes ( không cần đăng nhập)
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{identifier}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/movies/{movieId}/episodes', [MovieController::class, 'getEpisodes'])->name('movies.episodes');
Route::get('/payment/return', [PaymentController::class, 'vnpayReturn'])->name('payment.return');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/search/full-text', [SearchController::class, 'searchFullText'])->name('search.full-text');
Route::get('/categories', [SearchController::class, 'getCategory'])->name('search.categories');

// Admin routes (cần quyền admin)
Route::middleware(['auth.jwt.admin'])->prefix('admin')->group(function () {
    // type = create hoặc reset là tạo mới hoặc reset lại view cho tất cả các movie 
    Route::get('/movies/generate-views/{type?}', [MovieController::class, 'genRandomViews'])->name('admin.movies.generate-views');
});
