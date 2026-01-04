<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap; // Gunakan Snap

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        if ($booking->payment_status === 'paid') {
            return redirect()->route('booking.ticket', $booking->id);
        }

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Siapkan parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => 'CNV-' . $booking->id . '-' . time(),
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        // Buat Snap Token
        $snapToken = Snap::getSnapToken($params);

        return view('payment.index', compact('booking', 'snapToken'));
    }
    
    // Fungsi callback tetap sama untuk memproses notifikasi otomatis
    public function callback(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $order_id = $notif->order_id;

        $parts = explode('-', $order_id);
        $bookingId = $parts[1];
        $booking = Booking::find($bookingId);

        if (!$booking) return response()->json(['message' => 'Not found'], 404);

        if ($transaction == 'settlement' || $transaction == 'capture') {
            $booking->update(['payment_status' => 'paid']);
        } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
            $booking->update(['payment_status' => 'cancelled']);
        }

        return response()->json(['status' => 'ok']);
    }
}