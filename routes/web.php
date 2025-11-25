<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;

// 1. Homepage & Detail Film -> Masuk ke MovieController
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movie.show');

// 2. Pilih Kursi -> Masuk ke ShowtimeController (Karena pilih kursi itu lihat jadwal)
Route::get('/booking/seats/{id}', [ShowtimeController::class, 'show'])->name('booking.seat');

// 3. Proses Bayar -> Masuk ke BookingController
Route::post('/booking/process', [BookingController::class, 'store'])->name('booking.store');
require __DIR__.'/auth.php';
