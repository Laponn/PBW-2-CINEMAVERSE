<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController; // Controller Public (Home & Detail)
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;

// PENTING: Panggil Controller Admin & kasih nama Alias biar gak bentrok
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController; 

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (User Biasa)
|--------------------------------------------------------------------------
*/

Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movie.show');
Route::get('/search', [MovieController::class, 'search'])->name('movie.search');


// Pilih Kursi & Booking
Route::get('/booking/seats/{id}', [ShowtimeController::class, 'show'])->name('booking.seat');
Route::post('/booking/process', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/success/{id}', [BookingController::class, 'success'])->name('booking.success');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Mode Bebas / Tanpa Login)
|--------------------------------------------------------------------------
*/

// Perhatikan: Saya HAPUS ->middleware(['auth'])
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Movies 
    // PENTING: Pakai 'AdminMovieController', bukan 'MovieController' biasa
    Route::resource('movies', AdminMovieController::class);
    
});

require __DIR__.'/auth.php';