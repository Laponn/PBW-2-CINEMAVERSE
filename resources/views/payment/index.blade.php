@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black px-6 py-16">
    <div class="max-w-xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-black uppercase italic tracking-tight text-white">
                Konfirmasi <span class="text-red-600">Pembayaran</span>
            </h1>
            <p class="mt-3 text-sm uppercase tracking-widest text-zinc-500 font-semibold">
                Selesaikan pembayaran via Midtrans Snap
            </p>
        </div>

        {{-- 3D WRAPPER (Style Dipertahankan) --}}
        <div class="perspective-wrap">
            <div id="tiltCard" class="tilt-card relative bg-zinc-900/60 border border-white/5 rounded-[2rem] p-8 shadow-2xl overflow-hidden">
                <div class="glow"></div>

                {{-- DETAIL --}}
                <div class="space-y-4 text-sm font-bold uppercase tracking-widest relative z-10">
                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Total Tagihan</span>
                        <span class="text-white text-lg font-black italic">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Metode</span>
                        <span class="text-white">Midtrans Snap</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Merchant</span>
                        <span class="text-white">CinemaVerse</span>
                    </div>
                </div>

                <div class="my-8 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent relative z-10"></div>

                {{-- TOMBOL PEMBAYARAN --}}
                <div class="relative z-10">
                    <button id="pay-button" class="w-full py-4 rounded-full bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-[0.3em] transition-all transform hover:scale-[1.02] shadow-xl shadow-red-600/40">
                        Bayar Sekarang
                    </button>
                </div>

                <a href="{{ route('booking.index') }}" class="block mt-6 text-center text-xs font-black uppercase tracking-widest text-zinc-500 hover:text-white transition relative z-10">
                    Batal & kembali
                </a>
            </div>
        </div>
    </div>
</div>

{{-- CSS Style 3D Milikmu (Tidak Diubah) --}}
<style>
    .perspective-wrap { perspective: 1000px; }
    .tilt-card { transform-style: preserve-3d; transition: transform 180ms ease, box-shadow 180ms ease; will-change: transform; }
    .tilt-card .glow {
        position: absolute; inset: -40%;
        background: radial-gradient(circle at var(--gx, 50%) var(--gy, 50%), rgba(239, 68, 68, 0.30), rgba(239, 68, 68, 0.08) 35%, transparent 60%);
        transform: translateZ(-1px); pointer-events: none; filter: blur(10px); opacity: 0.9;
    }
    .tilt-card > *:not(.glow) { transform: translateZ(20px); }
</style>

{{-- Script Midtrans Snap --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    // --- Logika Pemicu Snap ---
    const payButton = document.getElementById('pay-button');
    payButton.onclick = function() {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) { window.location.href = "{{ route('booking.index', $booking->id) }}"; },
            onPending: function(result) { alert("Menunggu pembayaran!"); },
            onError: function(result) { alert("Pembayaran gagal!"); },
            onClose: function() { alert('Anda menutup jendela tanpa membayar'); }
        });
    };

    // --- Logika Tilt Card Milikmu ---
    const card = document.getElementById('tiltCard');
    const isTouch = window.matchMedia('(pointer: coarse)').matches;
    if (!isTouch && card) {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const px = (e.clientX - rect.left) / rect.width;
            const py = (e.clientY - rect.top) / rect.height;
            card.style.transform = `translateY(-6px) rotateX(${-(py - 0.5) * 20}deg) rotateY(${(px - 0.5) * 20}deg)`;
            card.style.setProperty('--gx', `${px * 100}%`);
            card.style.setProperty('--gy', `${py * 100}%`);
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0px) rotateX(0deg) rotateY(0deg)';
        });
    }
</script>
@endsection