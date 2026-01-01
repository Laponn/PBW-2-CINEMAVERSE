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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

// --- PUBLIC ROUTES ---
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/search', [MovieController::class, 'search'])->name('movie.search');

// --- AUTH USER ROUTES ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Halaman Pemesanan Utama (ticket page)
 Route::get('/movie/{movie}/ticket', [ShowtimeController::class, 'ticket'])->name('movies.ticket');
Route::get('/api/showtimes/{id}/details', [ShowtimeController::class, 'getDetails']);

    // Proses Booking (create booking pending + tickets)
    Route::post('/booking/process', [BookingController::class, 'store'])->name('booking.store');

    // Payment page + Simulasi bayar
    Route::get('/booking/{booking}/payment', [BookingController::class, 'payment'])->name('booking.payment');
    Route::post('/booking/{booking}/pay', [BookingController::class, 'markPaid'])->name('booking.pay');

    // E-ticket page (hanya kalau paid)
    Route::get('/booking/{booking}/ticket', [BookingController::class, 'ticket'])->name('booking.ticket');
Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    // Backward compatibility (kalau masih ada link lama)
    Route::get('/booking/success/{booking}', [BookingController::class, 'success'])->name('booking.success');

  

    Route::get('/bookings', [BookingController::class, 'index'])->name('booking.index');

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
