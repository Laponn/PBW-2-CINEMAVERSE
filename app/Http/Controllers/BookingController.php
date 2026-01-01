<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'showtime_id'   => 'required|integer|exists:showtimes,id',
            'seat_ids'      => 'required|array|min:1',
            'seat_ids.*'    => 'integer|exists:seats,id',
        ]);

        $showtime = Showtime::with('studio')->findOrFail($request->showtime_id);

        // Pastikan seat yang dipilih memang milik studio showtime
        $validSeatCount = Seat::where('studio_id', $showtime->studio_id)
            ->whereIn('id', $request->seat_ids)
            ->count();

        if ($validSeatCount !== count($request->seat_ids)) {
            return back()->with('error', 'Kursi tidak valid untuk studio ini.');
        }

        return DB::transaction(function () use ($request, $showtime) {

            // cek kursi sudah dibooking orang lain (pending/paid)
            $alreadyBooked = Ticket::whereIn('seat_id', $request->seat_ids)
                ->whereHas('booking', function ($q) use ($showtime) {
                    $q->where('showtime_id', $showtime->id)
                      ->whereIn('payment_status', ['pending', 'paid']);
                })
                ->exists();

            if ($alreadyBooked) {
                return back()->with('error', 'Ada kursi yang sudah dibooking. Pilih kursi lain.');
            }

            // buat booking
            $qty   = count($request->seat_ids);
            $total = (float) $showtime->price * $qty;

            $booking = Booking::create([
                'user_id'        => Auth::id(),
                'showtime_id'    => $showtime->id,
                'booking_code'   => 'CNV-' . strtoupper(uniqid()),
                'total_price'    => $total,
                'payment_status' => 'pending',
            ]);

            // buat tickets detail per kursi
            foreach ($request->seat_ids as $seatId) {
                Ticket::create([
                    'booking_id' => $booking->id,
                    'seat_id'    => $seatId,
                    'price'      => $showtime->price,
                ]);
            }

            // lanjut ke halaman pembayaran (QR)
            return redirect()->route('booking.payment', $booking->id)
                ->with('success', 'Booking dibuat (status: pending). Silakan bayar.');
        });
    }

    public function payment(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        $booking->load(['tickets.seat', 'showtime.movie', 'showtime.studio.branch']);

        return view('booking.payment', compact('booking'));
    }

    // simulasi: user klik "sudah bayar"
    public function markPaid(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        $booking->update(['payment_status' => 'paid']);

        return redirect()->route('booking.ticket', $booking->id)
            ->with('success', 'Pembayaran berhasil. Ini e-ticket kamu.');
    }

    public function ticket(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        $booking->load(['tickets.seat', 'showtime.movie', 'showtime.studio.branch']);

        if ($booking->payment_status !== 'paid') {
            return redirect()->route('booking.payment', $booking->id)
                ->with('error', 'Pembayaran belum selesai.');
        }

        return view('booking.ticket', compact('booking'));
    }

    // route lama: /booking/success/{id}
    public function success(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        return $booking->payment_status === 'paid'
            ? redirect()->route('booking.ticket', $booking->id)
            : redirect()->route('booking.payment', $booking->id);
    }
    public function index(Request $request)
    {
    $status = $request->query('status'); // optional: pending / paid

    $bookings = Booking::with(['tickets.seat', 'showtime.movie', 'showtime.studio.branch'])
        ->where('user_id', Auth::id())
        ->when($status, fn($q) => $q->where('payment_status', $status))
        ->latest()
        ->paginate(10);

    return view('booking.index', compact('bookings', 'status'));
    }
    public function show($id)
{
    $booking = \App\Models\Booking::with([
        'showtime.movie', 
        'showtime.studio.branch', 
        'tickets.seat'
    ])->where('user_id', auth()->id())->findOrFail($id);

    return view('user.booking-details', compact('booking'));
}
}

