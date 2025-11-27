<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use Illuminate\Support\Facades\Auth;

// =========================
// PUBLIC ROUTES
// =========================
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movie.show');

// =========================
// AUTH USER ROUTES
// =========================
Route::middleware('auth')->group(function () {
    Route::get('/booking/seats/{id}', [ShowtimeController::class, 'show'])->name('booking.seat');
    Route::post('/booking/process', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/success/{id}', [BookingController::class, 'success'])->name('booking.success');
});

// =========================
// ADMIN ROUTES
// =========================
Route::prefix('admin')
    ->middleware(['auth'])   // ⬅ HAPUS 'role:admin'
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('movies', AdminMovieController::class);
    });

// =========================
// FIX: ROUTE DASHBOARD (BREEZE DEFAULT)
// =========================
Route::get('/dashboard', function () {

    $user = Auth::user();

    // Jika admin → masuk ke panel admin
    if ($user && $user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // Jika user biasa → kembali ke home
    return redirect()->route('home');

})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';
