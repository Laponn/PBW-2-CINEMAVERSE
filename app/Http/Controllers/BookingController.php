<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    // Menyimpan Transaksi (POST)
    public function store(Request $request)
    {
        // Nanti kita isi logika simpan ke database di sini
        dd($request->all()); // Sementara kita dump dulu datanya buat ngecek
    }
}