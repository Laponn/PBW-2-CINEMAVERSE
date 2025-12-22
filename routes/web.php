<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ShowtimeController as AdminShowtimeController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\SalesReportController;
use Illuminate\Http\Request;
use App\Models\Branch;

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
    Route::get('/tickets/{movie}', [ShowtimeController::class, 'ticket'])->name('tickets.show');
});

// =========================
// ADMIN ROUTES
// =========================
Route::prefix('admin')
    ->middleware(['auth'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('movies', AdminMovieController::class);
        Route::resource('branches', \App\Http\Controllers\Admin\BranchController::class);
        Route::resource('studios', \App\Http\Controllers\Admin\StudioController::class);
        Route::resource('showtimes', AdminShowtimeController::class);
        Route::get('/reports/ticket-sales', [SalesReportController::class, 'index'])
        ->name('reports.ticket_sales');
    });

    
// =========================
// AUTH USER ROUTES
// =========================
// Route::middleware('auth')->group(function () {
//     // pilih kursi (per showtime)
//     Route::get('/booking/seats/{id}', [ShowtimeController::class, 'show'])
//         ->name('booking.seat');

//     Route::post('/booking/process', [BookingController::class, 'store'])->name('booking.store');
//     Route::get('/booking/success/{id}', [BookingController::class, 'success'])->name('booking.success');
// });

// =========================
// TICKET ROUTES
// =========================
Route::middleware('auth')->group(function () {

    // Halaman tiket (pilih jadwal per film)
    Route::get('/tickets/{movie}', [ShowtimeController::class, 'ticket'])
        ->name('tickets.show');

});

// =========================
// FIX: ROUTE DASHBOARD (BREEZE DEFAULT)
// =========================
Route::get('/dashboard', function () {

    
    return redirect()->route('home');

})->middleware('auth')->name('dashboard');

Route::post('/change-branch', function (\Illuminate\Http\Request $request) {
    // Simpan ID cabang ke session
    session(['selected_branch_id' => $request->branch_id]);
    
    // Kembali ke halaman sebelumnya
    return redirect()->back();
})->name('branch.change');

Route::post('/set-branch', function (Request $request) {
    $request->validate([
        'branch_id' => ['required', 'exists:branches,id'],
    ]);

    $branch = Branch::findOrFail($request->branch_id);

    session([
        'branch_id'   => $branch->id,
        'branch_name' => $branch->name,
        'branch_city' => $branch->city,
        'branch_lat'  => (string)($branch->latitude ?? ''),
        'branch_lng'  => (string)($branch->longitude ?? ''),
    ]);

    return response()->json(['ok' => true, 'branch' => $branch]);
})->name('set.branch');


require __DIR__.'/auth.php';