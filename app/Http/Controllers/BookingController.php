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
            'showtime_id' => 'required|exists:showtimes,id',
            'seat_ids'    => 'required|array|min:1',
            'seat_ids.*'  => 'exists:seats,id',
        ]);

        $showtime = Showtime::findOrFail($request->showtime_id);

        return DB::transaction(function () use ($request, $showtime) {

            $alreadyBooked = Ticket::whereIn('seat_id', $request->seat_ids)
                ->whereHas('booking', fn ($q) =>
                    $q->where('showtime_id', $showtime->id)
                      ->whereIn('payment_status', ['pending', 'paid'])
                )->exists();

            if ($alreadyBooked) {
                return back()->with('error', 'Kursi sudah dibooking.');
            }

            $total = count($request->seat_ids) * $showtime->price;

            $booking = Booking::create([
                'user_id'        => Auth::id(),
                'showtime_id'    => $showtime->id,
                'booking_code'   => 'CNV-' . strtoupper(uniqid()),
                'total_price'    => $total,
                'payment_status' => 'pending',
            ]);

            foreach ($request->seat_ids as $seatId) {
                Ticket::create([
                    'booking_id' => $booking->id,
                    'seat_id'    => $seatId,
                    'price'      => $showtime->price,
                ]);
            }

            // ⬅️ WAJIB KE GET PAYMENT PAGE
            return redirect()->route('booking.payment.page', $booking->id);
        });
    }

    public function ticket(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        if ($booking->payment_status !== 'paid') {
            return redirect()->route('booking.payment.page', $booking->id);
        }

        $booking->load(['tickets.seat', 'showtime.movie', 'showtime.studio.branch']);
        return view('booking.ticket', compact('booking'));
    }

    public function show($id)
    {
        $booking = Booking::with([
            'showtime.movie',
            'showtime.studio.branch',
            'tickets.seat'
        ])->where('user_id', Auth::id())->findOrFail($id);

        return view('user.booking-details', compact('booking'));
    }

    public function index(Request $request)
    {
        $bookings = Booking::with(['tickets.seat', 'showtime.movie'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('booking.index', compact('bookings'));
    }
}
