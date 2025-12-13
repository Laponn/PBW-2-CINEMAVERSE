<x-guest-layout>
    {{-- CDN Tailwind (Wajib ada agar tampilan bagus) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="min-h-screen flex items-center justify-center bg-[#050509] text-white p-4">
        <div class="w-full max-w-md bg-[#1a1a1a] p-8 rounded-xl shadow-2xl border border-zinc-800 relative overflow-hidden">
            
            {{-- Efek Glow Merah Samar di Background --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-2 bg-gradient-to-r from-transparent via-red-600 to-transparent opacity-75"></div>

            {{-- Header (Ikon Film & Judul - Sama seperti Login) --}}
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center mb-3">
                    {{-- SVG Ikon Film (Disamakan dengan Login) --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="#dc2626" class="bi bi-film drop-shadow-[0_0_10px_rgba(220,38,38,0.5)]" viewBox="0 0 16 16">
                        <path d="M0 1a1 1 0 0 1 1-1h1v2H1v1h1v2H1v1h1v2H1v1h1v2H1a1 1 0 0 1-1-1V1zm4-1h8v2H4V0zm9 0h1a1 1 0 0 1 1 1v1h-2V0zm2 3h-2v2h2V3zm0 3h-2v2h2V6zm0 3h-2v2h2V9zm0 3h-2v2h2v-1a1 1 0 0 1-1-1zm-3 2H4v-2h8v2zM3 3V1h1v2H3zm0 3V4h1v2H3zm0 3V7h1v2H3zm0 3v-2h1v2H3z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white tracking-wide">CINEMAVERSE</h3>
                <p class="text-zinc-500 text-sm mt-1">Buat akun baru Anda</p>
            </div>

            {{-- Error Validation --}}
            @if ($errors->any())
                <div class="bg-red-900/30 border border-red-800 text-red-300 px-4 py-3 rounded-lg mb-4 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Register --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-400 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full bg-[#0f0f0f] border border-zinc-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600 placeholder-zinc-600 transition"
                           placeholder="John Doe">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-400 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full bg-[#0f0f0f] border border-zinc-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600 placeholder-zinc-600 transition"
                           placeholder="nama@email.com">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-400 mb-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full bg-[#0f0f0f] border border-zinc-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600 placeholder-zinc-600 transition"
                           placeholder="••••••••">
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-400 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full bg-[#0f0f0f] border border-zinc-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600 placeholder-zinc-600 transition"
                           placeholder="••••••••">
                </div>

                {{-- Tombol & Link --}}
                <div class="pt-2">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-lg shadow-lg shadow-red-900/20 transition duration-200 transform hover:-translate-y-0.5">
                        DAFTAR SEKARANG
                    </button>
                </div>

                <div class="text-center mt-4">
                    <span class="text-zinc-500 text-sm">Sudah punya akun?</span>
                    <a href="{{ route('login') }}" class="text-red-500 hover:text-red-400 text-sm font-semibold ml-1 hover:underline">
                        Login di sini
                    </a>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>