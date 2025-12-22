<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        // Admin only (boleh kamu pindah ke middleware biar lebih clean)
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Filter input
        $status = $request->query('status'); // paid / unpaid / pending / dll
        $from   = $request->query('from');   // YYYY-MM-DD
        $to     = $request->query('to');     // YYYY-MM-DD

        // Query utama
        $query = Booking::query()
            // kalau punya relasi, bisa aktifkan:
            // ->with(['user','movie','showtime','branch','studio'])
            ->when($status, fn($q) => $q->where('payment_status', $status))
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to,   fn($q) => $q->whereDate('created_at', '<=', $to));

        // Ringkasan (pakai clone supaya query tidak “ketarik”)
        $baseQuery      = clone $query;
        $paidQuery      = (clone $query)->where('payment_status', 'paid');
        $unpaidQuery    = (clone $query)->whereIn('payment_status', ['unpaid', 'pending']);

        $totalOrders    = $baseQuery->count();
        $paidCount      = $paidQuery->count();
        $unpaidCount    = $unpaidQuery->count();

        $paidRevenue    = $paidQuery->sum('total_price');
        $unpaidAmount   = $unpaidQuery->sum('total_price');

        // Data tabel (paginate)
        $bookings = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('admin.reports.ticket_sales', compact(
            'bookings',
            'totalOrders',
            'paidCount',
            'unpaidCount',
            'paidRevenue',
            'unpaidAmount',
            'status',
            'from',
            'to'
        ));
    }
}
