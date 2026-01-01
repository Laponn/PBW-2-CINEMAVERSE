<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\Branch;
use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Ambil data untuk dropdown filter
        $moviesList = Movie::orderBy('title')->get();
        $branchesList = Branch::orderBy('name')->get();
        $studiosList = Studio::with('branch')->orderBy('name')->get();

        // Ambil input filter
        $status    = $request->query('status');
        $from      = $request->query('from');
        $to        = $request->query('to');
        $movie_id  = $request->query('movie_id');
        $branch_id = $request->query('branch_id');
        $studio_id = $request->query('studio_id');

        // Query Utama dengan Eager Loading
        $query = Booking::query()
            ->with(['user', 'showtime.movie', 'showtime.studio.branch'])
            ->when($status, fn($q) => $q->where('payment_status', $status))
            ->when($from,   fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to,     fn($q) => $q->whereDate('created_at', '<=', $to))
            // Filter berdasarkan Film
            ->when($movie_id, fn($q) => $q->whereHas('showtime', fn($sq) => $sq->where('movie_id', $movie_id)))
            // Filter berdasarkan Cabang
            ->when($branch_id, fn($q) => $q->whereHas('showtime.studio', fn($sq) => $sq->where('branch_id', $branch_id)))
            // Filter berdasarkan Studio
            ->when($studio_id, fn($q) => $q->whereHas('showtime', fn($sq) => $sq->where('studio_id', $studio_id)));

        // Hitung Ringkasan (Clone query agar filter tetap sinkron dengan total)
        $paidQuery    = (clone $query)->where('payment_status', 'paid');
        $unpaidQuery  = (clone $query)->whereIn('payment_status', ['unpaid', 'pending', 'cancelled']);

        $totalOrders  = $query->count();
        $paidCount    = $paidQuery->count();
        $unpaidCount  = $unpaidQuery->count();
        $paidRevenue  = $paidQuery->sum('total_price');
        $unpaidAmount = $unpaidQuery->sum('total_price');

        $bookings = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.reports.ticket_sales', compact(
            'bookings', 'totalOrders', 'paidCount', 'unpaidCount', 
            'paidRevenue', 'unpaidAmount', 'status', 'from', 'to',
            'moviesList', 'branchesList', 'studiosList'
        ));
    }
}