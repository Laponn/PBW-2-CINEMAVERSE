<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Branch;

// User Controllers
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ShowtimeController as AdminShowtimeController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\Admin\StudioController as AdminStudioController;
use App\Http\Controllers\Admin\SalesReportController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/search', [MovieController::class, 'search'])->name('movie.search');

/*
|--------------------------------------------------------------------------

| MIDTRANS AUTH TEST (DEBUG) - LOCAL ONLY
|--------------------------------------------------------------------------
| Hasil yang diharapkan:
| - 404 / transaction doesn't exist  => key VALID (auth sukses)
| - 401 Unknown Merchant             => key SALAH / bukan sandbox merchant tsb
|
| Hapus route ini setelah beres.
*/
if (app()->environment('local')) {
    Route::get('/midtrans-auth-test', function () {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;

        try {
            // order id palsu, cuma buat ngetes AUTH
            return \Midtrans\Transaction::status('ORDER-DOES-NOT-EXIST');
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    });
}

/*
|--------------------------------------------------------------------------
| AUTH USER
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ticket & Showtime
    Route::get('/movie/{movie}/ticket', [ShowtimeController::class, 'ticket'])->name('movies.ticket');
    Route::get('/api/showtimes/{id}/details', [ShowtimeController::class, 'getDetails'])->name('showtimes.details');

    // BOOKING
    Route::post('/booking/process', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/bookings', [BookingController::class, 'index'])->name('booking.index');

    // PAYMENT (MIDTRANS QRIS)
    Route::get('/booking/{booking}/payment', [PaymentController::class, 'show'])
        ->name('booking.payment.page');

    Route::post('/booking/{booking}/payment', [PaymentController::class, 'pay'])
        ->name('booking.payment');

    // E-Ticket (HANYA SETELAH PAID)
    Route::get('/booking/{booking}/ticket', [BookingController::class, 'ticket'])
        ->name('booking.ticket');

    Route::get('/booking/{booking}/ticket/download', [BookingController::class, 'downloadTicket'])
        ->name('booking.ticket.download');
});

/*
|--------------------------------------------------------------------------

| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware('auth')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('movies', AdminMovieController::class);
        Route::resource('branches', AdminBranchController::class);
        Route::resource('studios', AdminStudioController::class);
        Route::resource('showtimes', AdminShowtimeController::class);

        Route::get('/reports/ticket-sales', [SalesReportController::class, 'index'])
            ->name('reports.ticket_sales');

     Route::get('branches-export', [AdminBranchController::class, 'export'])
    ->name('branches.export');

    Route::post('branches-import', [AdminBranchController::class, 'import'])
    ->name('branches.import');

    Route::get('/reports/ticket-sales.export',[SalesReportController::class, 'export'])
    ->name('reports.ticket_sales.export');

    Route::get('movies-export', [AdminMovieController::class, 'export'])
    ->name('movies.export');

    Route::post('movies-import', [AdminMovieController::class, 'import'])
    ->name('movies.import');

    Route::get('studios-export', [AdminStudioController::class, 'export'])
    ->name('studios.export');

    Route::post('studios-import', [AdminStudioController::class, 'import'])
    ->name('studios.import');



    });

/*
|--------------------------------------------------------------------------

| BRANCH SESSION
|--------------------------------------------------------------------------
*/
Route::post('/change-branch', function (Request $request) {
    session(['selected_branch_id' => $request->branch_id]);
    return redirect()->back();
})->name('branch.change');

Route::post('/set-branch', function (Request $request) {
    $branch = Branch::findOrFail($request->branch_id);

    session([
        'branch_id'   => $branch->id,
        'branch_name' => $branch->name,
        'branch_city' => $branch->city,
        'branch_lat'  => (string) ($branch->latitude ?? ''),
        'branch_lng'  => (string) ($branch->longitude ?? ''),
    ]);

    return response()->json(['ok' => true]);
})->name('set.branch');

require __DIR__ . '/auth.php';