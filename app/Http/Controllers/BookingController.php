<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Showtime;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|integer|exists:showtimes,id',
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'integer|exists:seats,id',
        ]);

        $showtime = Showtime::with('studio')->findOrFail($request->showtime_id);

        return DB::transaction(function () use ($request, $showtime) {

            // 1) cek kursi sudah diambil orang lain untuk showtime ini (pending/paid)
            $alreadyBooked = Ticket::whereIn('seat_id', $request->seat_ids)
                ->whereHas('booking', function ($q) use ($showtime) {
                    $q->where('showtime_id', $showtime->id)
                      ->whereIn('payment_status', ['pending', 'paid']);
                })
                ->exists();

            if ($alreadyBooked) {
                return back()->with('error', 'Ada kursi yang sudah dibooking. Pilih kursi lain.');
            }

            // 2) buat booking
            $qty = count($request->seat_ids);
            $total = (float) $showtime->price * $qty;

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'showtime_id' => $showtime->id,
                'booking_code' => 'CNV-' . strtoupper(uniqid()),
                'total_price' => $total,
                'payment_status' => 'pending',
            ]);

            // 3) buat tickets (history harga per kursi)
            foreach ($request->seat_ids as $seatId) {
                Ticket::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                    'price' => $showtime->price,
                ]);
            }

            // sementara: anggap sukses dulu (nanti redirect ke Midtrans)
            return redirect()->route('booking.success', $booking->id)
                ->with('success', 'Booking berhasil dibuat (status: pending).');
        });
    }

    public function success($id)
    {
        $booking = Booking::with(['tickets', 'showtime.movie', 'showtime.studio'])
            ->findOrFail($id);

        // optional: pastikan user yg akses adalah pemilik booking (kecuali admin)
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('booking.success', compact('booking'));
    }
}
