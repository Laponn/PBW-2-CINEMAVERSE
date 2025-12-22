<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ShowtimeController as AdminShowtimeController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\Admin\StudioController as AdminStudioController;
use App\Http\Controllers\Admin\SalesReportController;
use Illuminate\Http\Request;
use App\Models\Branch;

// --- PUBLIC ROUTES ---
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/search', [MovieController::class, 'search'])->name('movie.search');

// --- AUTH USER ROUTES ---
Route::middleware('auth')->group(function () {
    // Halaman Pemesanan Utama
Route::get('/movie/{id}/ticket', [ShowtimeController::class, 'ticket'])->name('movies.ticket');
    
    // API untuk ambil kursi & detail secara otomatis
    Route::get('/api/showtimes/{id}/details', [ShowtimeController::class, 'getDetails']);

    // Proses Booking
    Route::post('/booking/process', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/success/{id}', [BookingController::class, 'success'])->name('booking.success');
    
    Route::get('/dashboard', fn() => redirect()->route('home'))->name('dashboard');
});

// --- ADMIN ROUTES ---
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('movies', AdminMovieController::class);
    Route::resource('branches', AdminBranchController::class);
    Route::resource('studios', AdminStudioController::class);
    Route::resource('showtimes', AdminShowtimeController::class);
    Route::get('/reports/ticket-sales', [SalesReportController::class, 'index'])->name('reports.ticket_sales');
});

// Session Ganti Cabang
Route::post('/change-branch', function (\Illuminate\Http\Request $request) {
    session(['selected_branch_id' => $request->branch_id]);
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