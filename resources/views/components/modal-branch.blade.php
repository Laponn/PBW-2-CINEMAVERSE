{{-- resources/views/components/modal-branch.blade.php --}}
<x-modal name="branch-modal" maxWidth="5xl" focusable>
    <div x-data="{ 
            selectedId: '{{ session('branch_id') }}', 
            previewId: null,
            previewName: '{{ session('branch_name', 'Pilih Lokasi') }}',
            previewLat: {{ (float) session('branch_lat', -6.186) }},
            previewLng: {{ (float) session('branch_lng', 106.822) }},
            map: null,
            marker: null,

            {{-- Fungsi Inisialisasi Peta --}}
            initMap() {
                if (this.map) return; 

                this.map = L.map('map-container', {
                    zoomControl: false 
                }).setView([this.previewLat, this.previewLng], 15);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(this.map);

                L.control.zoom({ position: 'bottomright' }).addTo(this.map);
                
                this.updateMarker(this.previewLat, this.previewLng, this.previewName);

                {{-- FIX MAP KEPOTONG: Gunakan ResizeObserver --}}
                const observer = new ResizeObserver(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                    }
                });
                observer.observe(document.getElementById('map-container'));
            },

            {{-- Fungsi Update Marker dan Label Nama --}}
            updateMarker(lat, lng, name) {
                this.previewLat = lat;
                this.previewLng = lng;
                this.previewName = name; {{-- Update Nama Cabang untuk UI --}}

                if (this.marker) this.map.removeLayer(this.marker);
                
                this.marker = L.marker([lat, lng]).addTo(this.map);
                
                {{-- Menampilkan Nama Cabang di Atas Titik --}}
                this.marker.bindTooltip(name, { 
                    permanent: true, 
                    direction: 'top',
                    className: 'bg-red-600 text-white font-black px-3 py-1.5 rounded-lg shadow-xl border-0 uppercase text-[10px] tracking-widest'
                }).openTooltip();

                this.map.flyTo([lat, lng], 16, { duration: 1.5 });
            },

            {{-- Fungsi Tombol Fokus Lokasi --}}
            centerToMarker() {
                if (this.map) {
                    this.map.flyTo([this.previewLat, this.previewLng], 18, {
                        animate: true,
                        duration: 1.0
                    });
                }
            }
         }" 
         {{-- Jalankan Inisialisasi saat Modal Terbuka Sempurna --}}
         x-on:open-modal.window="if($event.detail == 'branch-modal') { setTimeout(() => { initMap(); }, 400) }"
         class="bg-zinc-900 text-white border border-white/10 rounded-2xl overflow-hidden flex flex-col lg:flex-row h-[600px] lg:h-[550px]">
        
        {{-- SISI KIRI: Daftar Cabang --}}
        <div class="w-full lg:w-1/3 flex flex-col border-b lg:border-b-0 lg:border-r border-white/10 bg-zinc-900/50">
            <div class="p-6 border-b border-white/5">
                <h2 class="text-xl font-bold tracking-tight text-white">Pilih Lokasi</h2>
                <p class="text-[10px] text-zinc-500 uppercase tracking-[0.2em] mt-1 text-zinc-400">Bioskop Terdekat</p>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2 nice-scroll">
                @foreach(($navBranches ?? collect()) as $branch)
                    <button type="button" 
                        @click="previewId = '{{ $branch->id }}'; 
                                previewName = '{{ $branch->name }}'; 
                                updateMarker({{ (float)$branch->latitude }}, {{ (float)$branch->longitude }}, '{{ $branch->name }}')"
                        :class="previewId == '{{ $branch->id }}' ? 'border-red-600 bg-red-600/10' : 'border-white/5 bg-white/5'"
                        class="w-full text-left p-4 rounded-xl border transition-all hover:border-red-500 group">
                        <div class="font-bold text-white group-hover:text-red-500" :class="previewId == '{{ $branch->id }}' ? 'text-red-500' : ''">
                            {{ $branch->city }}
                        </div>
                        <div class="text-xs text-zinc-500">{{ $branch->name }}</div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- SISI KANAN: Peta Leaflet --}}
        <div class="flex-1 relative bg-zinc-800 flex flex-col min-h-0">
            <div class="flex-1 relative z-0">
                {{-- Kontainer Peta --}}
                <div id="map-container" class="absolute inset-0 w-full h-full"></div>

                {{-- TOMBOL FOKUS LOKASI --}}
                <div class="absolute top-4 left-4 z-[1000]">
                    <button type="button" 
                            @click.stop="centerToMarker()" 
                            class="p-3 bg-white text-zinc-900 rounded-xl shadow-2xl hover:bg-red-600 hover:text-white transition-all transform active:scale-95 flex items-center gap-2 pointer-events-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-[10px] font-black uppercase tracking-widest pr-1">Fokus Lokasi</span>
                    </button>
                </div>
            </div>

            {{-- Footer: Menampilkan Nama Cabang Dinamis --}}
            <div class="p-6 bg-zinc-900 border-t border-white/5 flex items-center justify-between z-10">
                <div class="max-w-[60%]">
                    <p class="text-[10px] text-zinc-500 uppercase tracking-widest leading-none mb-1 text-zinc-400">Preview Lokasi:</p>
                    <p class="text-sm font-bold text-white truncate" x-text="previewName"></p>
                </div>
                
                <button type="button"
                    x-show="previewId && previewId != selectedId"
                    @click="confirmBranch(previewId)"
                    class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white text-xs font-black uppercase tracking-widest rounded-full transition-all shadow-lg shadow-red-600/30">
                    Pilih Lokasi Ini
                </button>
            </div>
        </div>
    </div>
</x-modal>

<script>
    function confirmBranch(branchId) {
        fetch("{{ route('set.branch') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({ branch_id: branchId })
        })
        .then(res => { 
            if (res.ok) window.location.reload(); 
        });
    }
</script>

<style>
    /* CSS Styling untuk Label Nama di Atas Marker */
    .leaflet-tooltip-top:before {
        border-top-color: #dc2626; 
    }
    .leaflet-container {
        font-family: inherit;
    }
</style>