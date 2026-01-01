<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\CoreApi;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        if ($booking->payment_status === 'paid') {
            return redirect()->route('booking.ticket', $booking->id);
        }

        return view('payment.index', compact('booking'));
    }

    public function pay(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized  = true;

        $orderId = 'CNV-' . $booking->id . '-' . time();

        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->total_price,
            ],
            'item_details' => [[
                'id' => 'booking-' . $booking->id,
                'price' => (int) $booking->total_price,
                'quantity' => 1,
                'name' => 'Tiket CinemaVerse',
            ]],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'qris' => [
                'acquirer' => 'gopay',
            ],
        ];

        $response = CoreApi::charge($params);

        return view('payment.qris', [
            'booking'  => $booking,
            'orderId'  => $orderId,
            'qrString' => $response->qr_string,
        ]);
    }
}
