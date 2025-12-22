@extends('layouts.admin')

@section('content')
    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-start gap-3">
            <div class="mt-1 text-purple-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
            </div>

            <div>
                <h2 class="text-3xl font-bold text-gray-800">Laporan Penjualan Tiket</h2>
                <p class="text-gray-500 text-sm mt-1">Berdasarkan data checkout booking (paid / unpaid).</p>
            </div>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-xs font-semibold text-gray-600">Dari Tanggal</label>
                <input type="date" name="from" value="{{ $from }}"
                       class="w-full mt-1 border-gray-200 rounded-lg"/>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ $to }}"
                       class="w-full mt-1 border-gray-200 rounded-lg"/>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600">Status Pembayaran</label>
                <select name="status" class="w-full mt-1 border-gray-200 rounded-lg">
                    <option value="">Semua</option>
                    <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="unpaid" {{ $status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                    Terapkan
                </button>
                <a href="{{ route('admin.reports.ticket_sales') }}"
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Reset
                </a>
            </div>
        </div>
    </form>

    {{-- RINGKASAN (style sama kayak Dashboard cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-gray-800 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Transaksi</h3>
                <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $totalOrders }}</p>
            </div>
            <div class="absolute right-4 top-4 text-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6M7 8h10M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-green-500 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Paid</h3>
                <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $paidCount }}</p>
            </div>
            <div class="absolute right-4 top-4 text-green-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-yellow-500 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Belum Dibayar</h3>
                <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $unpaidCount }}</p>
            </div>
            <div class="absolute right-4 top-4 text-yellow-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-purple-500 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Pendapatan (Paid)</h3>
                <p class="text-2xl font-extrabold text-gray-800 mt-2">Rp {{ number_format($paidRevenue) }}</p>
                <p class="text-xs text-gray-500 mt-1">Outstanding: Rp {{ number_format($unpaidAmount) }}</p>
            </div>
            <div class="absolute right-4 top-4 text-purple-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-800">Daftar Booking</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold">
                    <tr>
                        <th class="text-left p-3">Tanggal</th>
                        <th class="text-left p-3">ID Booking</th>
                        <th class="text-left p-3">Status</th>
                        <th class="text-left p-3">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3">{{ $b->created_at->format('d M Y H:i') }}</td>
                            <td class="p-3 font-semibold text-gray-800">#{{ $b->id }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded-full text-xs font-bold
                                    {{ $b->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ strtoupper($b->payment_status) }}
                                </span>
                            </td>
                            <td class="p-3 font-semibold">Rp {{ number_format($b->total_price) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">
                                Tidak ada data booking sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $bookings->links() }}
        </div>
    </div>
@endsection
