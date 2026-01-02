<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Showtime;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class BookingController extends Controller
{
    /**
     * Durasi pembayaran berlaku 15 menit.
     */
    private int $paymentTtlMinutes = 15;

    private function isBookingExpired(Booking $booking): bool
    {
        $now = Carbon::now();

        // Kalau punya kolom payment_expires_at (opsional)
        if (!empty($booking->payment_expires_at)) {
            return $now->greaterThan(Carbon::parse($booking->payment_expires_at));
        }

        // Fallback: created_at + 15 menit
        return $now->greaterThan(Carbon::parse($booking->created_at)->addMinutes($this->paymentTtlMinutes));
    }

    private function expireBooking(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $booking->refresh();

            if ($booking->payment_status !== 'pending') return;

            $booking->update([
                'payment_status' => 'expired',
            ]);

            // Lepas kursi (hapus ticket)
            Ticket::where('booking_id', $booking->id)->delete();
        });
    }

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
                ->whereHas(
                    'booking',
                    fn($q) =>
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

                // Kalau kamu punya kolom ini (opsional):
                // 'payment_expires_at' => now()->addMinutes($this->paymentTtlMinutes),
            ]);

            foreach ($request->seat_ids as $seatId) {
                Ticket::create([
                    'booking_id' => $booking->id,
                    'seat_id'    => $seatId,
                    'price'      => $showtime->price,
                ]);
            }

            return redirect()->route('booking.payment.page', $booking->id);
        });
    }

    public function ticket(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        // Pending tapi expired -> expire & balik ke payment
        if ($booking->payment_status === 'pending' && $this->isBookingExpired($booking)) {
            $this->expireBooking($booking);

            return redirect()
                ->route('booking.payment.page', $booking->id)
                ->with('warning', 'Waktu pembayaran habis (15 menit). Silakan lakukan pembayaran ulang.');
        }

        // Belum paid -> balik ke payment
        if ($booking->payment_status !== 'paid') {
            return redirect()
                ->route('booking.payment.page', $booking->id)
                ->with('warning', 'Silakan selesaikan pembayaran dulu untuk melihat e-ticket.');
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

        // Auto-expire di halaman detail (opsional)
        if ($booking->payment_status === 'pending' && $this->isBookingExpired($booking)) {
            $this->expireBooking($booking);
            $booking->refresh();
        }

        return view('user.booking-details', compact('booking'));
    }

    public function index(Request $request)
    {
        /**
         * INI YANG BENERIN ERROR $status DI BLADE.
         * Blade kamu memang pakai $status untuk highlight tab.
         */
        $status = $request->query('status'); // null/pending/paid/(expired)

        $query = Booking::with(['tickets.seat', 'showtime.movie', 'showtime.studio'])
            ->where('user_id', Auth::id());

        // Auto-expire semua booking pending yang sudah lewat 15 menit (opsional tapi bagus)
        $pendingBookings = (clone $query)->where('payment_status', 'pending')->get();
        foreach ($pendingBookings as $b) {
            if ($this->isBookingExpired($b)) {
                $this->expireBooking($b);
            }
        }

        // filter status jika ada
        if ($status && $status !== 'all') {
            $query->where('payment_status', $status);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        // WAJIB: kirim $status biar blade kamu tidak error
        return view('booking.index', compact('bookings', 'status'));
    }

    public function downloadTicket(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        // hanya boleh download kalau paid
        if ($booking->payment_status !== 'paid') {
            return redirect()
                ->route('booking.payment.page', $booking->id)
                ->with('warning', 'Selesaikan pembayaran dulu untuk download tiket.');
        }

        $booking->load(['tickets.seat', 'showtime.movie', 'showtime.studio.branch']);

        $pdf = Pdf::loadView('booking.ticket-pdf', [
            'booking' => $booking,
        ])->setPaper('a4', 'portrait');

        $filename = 'E-TICKET-' . $booking->booking_code . '.pdf';

        return $pdf->download($filename);
    }
}
