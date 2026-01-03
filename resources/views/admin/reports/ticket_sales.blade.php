@extends('layouts.admin')

@section('content')
<div class="space-y-6 pb-12">

    {{-- HEADER --}}
    <div class="flex items-center justify-between bg-gray-50 p-6 rounded-2xl border border-gray-200">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white border border-gray-200 rounded-xl text-red-600 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-black uppercase italic tracking-tighter text-gray-900">
                    Sales <span class="text-red-600">Report</span>
                </h2>
                <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest">
                    Laporan Lengkap Penjualan Tiket
                </p>
            </div>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="px-5 py-2 bg-white border border-gray-300 text-gray-700 text-[10px] font-black uppercase rounded-full hover:bg-gray-50 transition">
            Kembali
        </a>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white p-6 rounded-2xl border border-gray-200">
            <h3 class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Total Transaksi</h3>
            <p class="text-2xl font-black text-gray-900 italic tracking-tighter mt-1">
                {{ $totalOrders }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl border-2 border-green-200">
            <h3 class="text-[10px] font-black uppercase text-green-600 tracking-widest">
                Paid ({{ $paidCount }})
            </h3>
            <p class="text-2xl font-black text-green-600 italic tracking-tighter mt-1">
                Rp {{ number_format($paidRevenue, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl border-2 border-yellow-200">
            <h3 class="text-[10px] font-black uppercase text-yellow-600 tracking-widest">
                Unpaid ({{ $unpaidCount }})
            </h3>
            <p class="text-2xl font-black text-yellow-600 italic tracking-tighter mt-1">
                Rp {{ number_format($unpaidAmount, 0, ',', '.') }}
            </p>
        </div>

        {{-- EXPORT --}}
        <a href="{{ route('admin.reports.ticket_sales.export', request()->query()) }}"
           class="bg-green-900 p-6 rounded-2xl border border-green-900 text-white flex flex-col justify-between hover:bg-green-800 transition">
            <div>
                <h3 class="text-[10px] font-black uppercase tracking-widest">Export Data</h3>
                <p class="text-xs opacity-70 mt-1">Excel (.xlsx)</p>
            </div>
            <div class="mt-4 text-sm font-black uppercase tracking-widest">
                Export 
            </div>
        </a>

    </div>

    {{-- FILTER --}}
    <form method="GET" class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">

            {{-- DATE --}}
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}"
                       class="w-full mt-1 border-gray-200 rounded-xl text-sm font-bold bg-gray-50">
            </div>

            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="w-full mt-1 border-gray-200 rounded-xl text-sm font-bold bg-gray-50">
            </div>

            {{-- STATUS --}}
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Status Pembayaran</label>
                <select name="status"
                        class="w-full mt-1 border-gray-200 rounded-xl text-sm font-bold bg-white">
                    <option value="">Semua Status</option>
                    <option value="paid" @selected(request('status') === 'paid')>PAID</option>
                    <option value="pending" @selected(request('status') === 'pending')>PENDING</option>
                </select>
            </div>

            {{-- MOVIE --}}
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Filter Film</label>
                <select name="movie_id"
                        class="w-full mt-1 border-gray-200 rounded-xl text-sm font-bold bg-white">
                    <option value="">Semua Film</option>
                    @foreach($moviesList as $m)
                        <option value="{{ $m->id }}" @selected(request('movie_id') == $m->id)>
                            {{ $m->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- BRANCH --}}
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Filter Cabang</label>
                <select name="branch_id"
                        class="w-full mt-1 border-gray-200 rounded-xl text-sm font-bold bg-white">
                    <option value="">Semua Cabang</option>
                    @foreach($branchesList as $b)
                        <option value="{{ $b->id }}" @selected(request('branch_id') == $b->id)>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- STUDIO --}}
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Filter Studio</label>
                <select name="studio_id"
                        class="w-full mt-1 border-gray-200 rounded-xl text-sm font-bold bg-white">
                    <option value="">Semua Studio</option>
                    @foreach($studiosList as $s)
                        <option value="{{ $s->id }}" @selected(request('studio_id') == $s->id)>
                            {{ $s->branch->name }} - {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- BUTTON --}}
            <div class="lg:col-span-2 flex items-end gap-2">
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase hover:bg-gray-800 transition">
                    Terapkan Filter
                </button>

                <a href="{{ route('admin.reports.ticket_sales') }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-500 rounded-xl text-[10px] font-black uppercase hover:bg-gray-200 text-center">
                    Reset
                </a>
            </div>

        </div>
    </form>

    {{-- TABLE --}}
    <div class="bg-white rounded-[2rem] border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-400 uppercase text-[9px] font-black tracking-widest border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Booking Info</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Movie & Studio</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Total Price</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $b)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold italic">#{{ $b->booking_code }}</div>
                            <div class="text-[10px] text-gray-400 font-black uppercase">
                                {{ $b->created_at->format('d/m/Y H:i') }}
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="font-black uppercase italic">{{ $b->user->name }}</div>
                            <div class="text-[10px] text-gray-500">{{ $b->user->email }}</div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="font-black text-red-600 uppercase italic">
                                {{ $b->showtime->movie->title }}
                            </div>
                            <div class="text-[10px] text-gray-400 uppercase font-bold mt-1">
                                {{ $b->showtime->studio->name }} - {{ $b->showtime->studio->branch->name }}
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full border text-[8px] font-black uppercase
                                {{ $b->payment_status === 'paid'
                                    ? 'bg-green-50 text-green-600 border-green-200'
                                    : 'bg-yellow-50 text-yellow-600 border-yellow-200' }}">
                                {{ $b->payment_status }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="font-black italic tracking-tighter">
                                Rp {{ number_format($b->total_price, 0, ',', '.') }}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="px-6 py-20 text-center text-gray-400 italic text-xs uppercase font-black tracking-widest">
                            No transaction data matched your filters
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-6 bg-gray-50 border-t border-gray-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
