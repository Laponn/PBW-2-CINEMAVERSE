{{-- resources/views/components/modal-map.blade.php --}}

{{-- Kita gunakan component x-modal yang sudah ada sebagai pembungkus --}}
<x-modal name="map-modal" maxWidth="4xl" focusable>
    {{-- Container Utama dengan tema Gelap --}}
    <div class="bg-zinc-900 text-white border border-white/10 rounded-lg overflow-hidden relative">
        
        {{-- Header Modal --}}
        <div class="flex justify-between items-center p-5 border-b border-white/5 bg-black/20">
            <div>
                <h2 class="text-xl font-bold tracking-tight">Peta Lokasi Bioskop</h2>
                <p class="text-xs text-zinc-500 uppercase tracking-widest mt-1">Lihat posisi cabang terpilih</p>
            </div>
            {{-- Tombol Close (Menggunakan Alpine.js $dispatch) --}}
            <button 
                type="button"
                x-on:click="$dispatch('close-modal', 'map-modal')"
                class="p-2 text-zinc-500 hover:text-white bg-white/5 hover:bg-red-600/80 rounded-full transition-colors duration-200"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Konten Peta --}}
        <div class="relative h-[500px] bg-black">
            {{-- 
                 IFRAME OPENSTREETMAP
                 - Menggunakan koordinat dari session (latitude, longitude).
                 - Default ke Jakarta jika session kosong.
                 - Class CSS: grayscale invert dll digunakan untuk membuat peta jadi mode gelap.
            --}}
            <iframe 
                id="mainMapFrame"
                class="w-full h-full grayscale invert contrast-125 opacity-80"
                src="https://www.openstreetmap.org/export/embed.html?layer=mapnik&marker={{ session('branch_lat', -6.186) }}%2C{{ session('branch_lng', 106.822) }}"
                loading="lazy"
                style="border:0;"
            ></iframe>

            {{-- Overlay gradient halus di atas peta --}}
            <div class="absolute inset-0 pointer-events-none shadow-[inset_0_0_60px_rgba(0,0,0,0.8)]"></div>
        </div>

        {{-- Footer Info Lokasi --}}
        <div class="px-6 py-4 bg-zinc-950 border-t border-white/5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-red-600/10 flex items-center justify-center border border-red-500/20">
                <span class="text-2xl">📍</span>
            </div>
            <div>
                <p class="text-[10px] text-zinc-500 uppercase tracking-widest leading-none mb-1">Lokasi Aktif Saat Ini</p>
                <p class="text-lg font-bold text-red-500">
                    {{ session('branch_name', 'Belum Ada Lokasi Terpilih') }}
                </p>
            </div>
        </div>
    </div>
</x-modal>