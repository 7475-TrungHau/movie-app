<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

// User routes (cần đăng nhập)
Route::middleware('auth.jwt')->group(function () {
    Route::get('/user', [UserController::class, 'getUser'])->name('user');

    // Movie   (đánh giá, lịch sử xem)
    Route::post('/movies/{movieId}/rating', [MovieController::class, 'Rating'])->name('movie.rating');
    Route::post('/episodes/{episodeId}/history', [MovieController::class, 'setHistory'])->name('episode.history');
});

// Public movie routes ( không cần đăng nhập)
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{identifier}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/movies/{movieId}/episodes', [MovieController::class, 'getEpisodes'])->name('movies.episodes');

// Admin routes (cần quyền admin)
Route::middleware(['auth.jwt.admin'])->prefix('admin')->group(function () {
    // type = create hoặc reset là tạo mới hoặc reset lại view cho tất cả các movie 
    Route::get('/movies/generate-views/{type?}', [MovieController::class, 'genRandomViews'])->name('admin.movies.generate-views');
});
