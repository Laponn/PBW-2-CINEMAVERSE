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
                Periksa detail transaksi sebelum melanjutkan
            </p>
        </div>

        {{-- 3D WRAPPER --}}
        <div class="perspective-wrap">
            <div id="tiltCard"
                 class="tilt-card relative bg-zinc-900/60 border border-white/5 rounded-[2rem] p-8 shadow-2xl overflow-hidden">

                {{-- glow layer --}}
                <div class="glow"></div>

                {{-- DETAIL --}}
                <div class="space-y-4 text-sm font-bold uppercase tracking-widest relative z-10">
                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Total</span>
                        <span class="text-white text-lg font-black italic">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Metode</span>
                        <span class="text-white">QRIS</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Merchant</span>
                        <span class="text-white">CinemaVerse</span>
                    </div>
                </div>

                {{-- DIVIDER --}}
                <div class="my-8 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent relative z-10"></div>

                {{-- ACTION --}}
                <form method="POST" action="{{ route('booking.payment', $booking->id) }}" class="relative z-10">
                    @csrf
                    <button
                        type="submit"
                        class="w-full py-4 rounded-full
                               bg-red-600 hover:bg-red-700
                               text-white font-black text-xs
                               uppercase tracking-[0.3em]
                               transition-all transform hover:scale-[1.02]
                               shadow-xl shadow-red-600/40"
                    >
                        Bayar dengan QRIS
                    </button>
                </form>

                {{-- CANCEL --}}
                <a href="{{ route('booking.index') }}"
                   class="block mt-6 text-center text-xs font-black
                          uppercase tracking-widest
                          text-zinc-500 hover:text-white transition relative z-10">
                    Batal & kembali
                </a>
            </div>
        </div>
    </div>
</div>

{{-- 3D styles (tanpa ubah tailwind config) --}}
<style>
    .perspective-wrap {
        perspective: 1000px;
    }

    .tilt-card {
        transform-style: preserve-3d;
        transition: transform 180ms ease, box-shadow 180ms ease;
        will-change: transform;
    }

    /* Glow halus yang bergerak */
    .tilt-card .glow {
        position: absolute;
        inset: -40%;
        background: radial-gradient(circle at var(--gx, 50%) var(--gy, 50%),
            rgba(239, 68, 68, 0.30),
            rgba(239, 68, 68, 0.08) 35%,
            transparent 60%);
        transform: translateZ(-1px);
        pointer-events: none;
        filter: blur(10px);
        opacity: 0.9;
    }

    /* sedikit pop untuk konten */
    .tilt-card > *:not(.glow) {
        transform: translateZ(20px);
    }

    @media (prefers-reduced-motion: reduce) {
        .tilt-card { transition: none; }
    }
</style>

<script>
    const card = document.getElementById('tiltCard');

    // kalau device touch, skip mouse tilt (biar gak aneh di HP)
    const isTouch = window.matchMedia('(pointer: coarse)').matches;

    if (!isTouch && card) {
        const maxRotate = 10; // derajat max
        const maxLift = 6;    // px

        function handleMove(e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const px = x / rect.width;
            const py = y / rect.height;

            const rotateY = (px - 0.5) * (maxRotate * 2);
            const rotateX = -(py - 0.5) * (maxRotate * 2);

            card.style.transform =
                `translateY(-${maxLift}px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg)`;

            // glow position
            card.style.setProperty('--gx', `${(px * 100).toFixed(2)}%`);
            card.style.setProperty('--gy', `${(py * 100).toFixed(2)}%`);
        }

        function reset() {
            card.style.transform = 'translateY(0px) rotateX(0deg) rotateY(0deg)';
            card.style.setProperty('--gx', `50%`);
            card.style.setProperty('--gy', `50%`);
        }

        card.addEventListener('mousemove', handleMove);
        card.addEventListener('mouseleave', reset);
    }
</script>
@endsection
