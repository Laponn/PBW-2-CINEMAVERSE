{{-- resources/views/user/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard ' . $user->name)

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 py-8 space-y-10">
    
    {{-- Welcome Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-r from-red-900/20 to-transparent p-8 rounded-[2rem] border border-red-500/10">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-red-600 flex items-center justify-center text-3xl font-black shadow-[0_0_30px_rgba(220,38,38,0.3)]">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-4xl font-black tracking-tight text-white uppercase italic">
                    Selamat Datang, <span class="text-red-600">{{ $user->name }}</span>!
                </h1>
                <p class="text-zinc-400 mt-1 uppercase tracking-widest text-[10px] font-bold">
                    @if(Auth::user()->role === 'admin' && Auth::id() !== $user->id)
                        <span class="bg-blue-600/20 text-blue-500 px-2 py-1 rounded mr-2">Mode Admin</span>
                        Sedang melihat profil aktivitas user.
                    @else
                        Siap untuk menonton film favoritmu hari ini?
                    @endif
                </p>
            </div>
        </div>
        
        <div class="flex gap-3">
            <div class="bg-zinc-900/50 border border-zinc-800 px-6 py-3 rounded-2xl text-center">
                <p class="text-[10px] text-zinc-500 uppercase font-black tracking-widest">Role</p>
                <p class="text-sm font-bold text-red-500 uppercase italic">{{ $user->role }}</p>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-zinc-900 p-8 rounded-[2rem] border border-white/5 flex items-center gap-6 shadow-xl">
            <div class="w-14 h-14 rounded-2xl bg-red-600/10 flex items-center justify-center text-red-500 border border-red-500/20 text-2xl">
                🎟️
            </div>
            <div>
                <p class="text-[10px] text-zinc-500 uppercase font-black tracking-widest">Total Pesanan</p>
                <p class="text-3xl font-black text-white italic leading-none">{{ $user->bookings->count() }}</p>
            </div>
        </div>
        
        {{-- Total Pengeluaran (Contoh Stats Tambahan) --}}
        <div class="bg-zinc-900 p-8 rounded-[2rem] border border-white/5 flex items-center gap-6 shadow-xl">
            <div class="w-14 h-14 rounded-2xl bg-green-600/10 flex items-center justify-center text-green-500 border border-green-500/20 text-2xl">
                💰
            </div>
            <div>
                <p class="text-[10px] text-zinc-500 uppercase font-black tracking-widest">Total Belanja</p>
                <p class="text-3xl font-black text-white italic leading-none">
                    Rp {{ number_format($user->bookings->where('payment_status', 'paid')->sum('total_price'), 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Riwayat Booking --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-zinc-800 pb-6">
            <h3 class="font-black text-2xl uppercase tracking-tighter italic">Riwayat <span class="text-red-600">Pemesanan</span></h3>
        </div>

        <div class="overflow-hidden rounded-[2.5rem] border border-white/5 bg-zinc-950/50 shadow-2xl">
            <table class="w-full text-left">
                <thead class="bg-zinc-900 text-zinc-500 uppercase text-[10px] font-black tracking-[0.2em]">
                    <tr>
                        <th class="px-8 py-5">Movie & Branch</th>
                        <th class="px-8 py-5">Date & Time</th>
                        <th class="px-8 py-5 text-center">Status Pembayaran</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-900">
                    @forelse($user->bookings as $booking)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="font-black text-white uppercase italic text-lg tracking-tight group-hover:text-red-500 transition-colors">
                                        {{ $booking->showtime->movie->title }}
                                    </span>
                                    <span class="text-[10px] text-zinc-500 uppercase font-bold tracking-widest mt-1">
                                        {{ $booking->showtime->studio->branch->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-bold text-zinc-300">
                                    {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('d M Y') }}
                                </div>
                                <div class="text-[10px] text-zinc-500 uppercase font-black">
                                    {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }} WIB
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php
                                    $colors = [
                                        'paid' => 'bg-green-600/10 text-green-500 border-green-600/20',
                                        'pending' => 'bg-yellow-600/10 text-yellow-500 border-yellow-600/20',
                                        'cancelled' => 'bg-red-600/10 text-red-500 border-red-600/20'
                                    ];
                                    $statusClass = $colors[$booking->payment_status] ?? 'bg-zinc-600/10 text-zinc-500 border-zinc-600/20';
                                @endphp
                                <span class="px-4 py-1.5 rounded-full border {{ $statusClass }} text-[9px] font-black uppercase tracking-widest">
                                    {{ $booking->payment_status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('booking.show', $booking->id) }}" 
                                   class="inline-flex items-center justify-center px-6 py-2 bg-white/5 hover:bg-red-600 border border-white/10 hover:border-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full transition shadow-lg">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-32 text-center border-2 border-dashed border-zinc-900 rounded-[2rem]">
                                <div class="flex flex-col items-center gap-4">
                                    <span class="text-4xl opacity-20">🎬</span>
                                    <p class="text-zinc-600 font-black uppercase tracking-[0.2em] italic">Belum ada riwayat aktivitas tiket.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection