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

// PERBAIKAN: Tambahkan 's' pada name agar menjadi 'movies.show' sesuai HTML
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movies.show');

// 2) Beli Tiket (dari Home) -> tetap ke detail, tapi paksa buka jadwal
Route::get('/movie/{id}/buy', function ($id) {
    return redirect()->route('movies.show', ['id' => $id, 'open' => 'jadwal']);
})->name('movies.buy');

Route::get('/search', [MovieController::class, 'search'])->name('movie.search');


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
    ->middleware(['auth'])   // ⬅ HAPUS 'role:admin' sementara untuk debugging jika perlu
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('movies', AdminMovieController::class);
    });

// =========================
// FIX: ROUTE DASHBOARD (BREEZE DEFAULT)
// =========================
Route::get('/dashboard', function () {

    
    return redirect()->route('home');

})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';