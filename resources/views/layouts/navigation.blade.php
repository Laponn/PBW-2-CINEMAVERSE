{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT --}}
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                    <a href="{{ route('booking.index') }}" class="block text-gray-300 hover:text-white transition">
  Booking Saya
</a>

                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            {{-- RIGHT (DESKTOP) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                {{-- 📍 PILIH LOKASI (OPEN MODAL) --}}
                <button
                    type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'branch-modal' }))"
                    class="me-3 inline-flex items-center px-4 py-2 bg-gray-900 border border-gray-700 rounded-full text-xs text-white uppercase tracking-widest hover:bg-gray-800"
                >
                    <span class="me-2">📍</span>
                    <span id="branchLabel">{{ session('branch_name', 'Pilih Lokasi') }}</span>
                    <span class="ms-2 opacity-70">▾</span>
                </button>

                {{-- 🗺 LIHAT PETA (OPEN MAP MODAL) --}}
                <button
                    type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'map-modal' }))"
                    class="me-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-blue-500 rounded-full text-xs text-white uppercase tracking-widest hover:bg-blue-500"
                >
                    🗺 Lihat Peta
                </button>

                {{-- USER DROPDOWN --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 transition">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- HAMBURGER --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:bg-gray-100 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- 📍 PILIH LOKASI (MOBILE) --}}
            <div class="px-4 pt-2">
                <button
                    type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'branch-modal' }))"
                    class="w-full inline-flex items-center justify-between px-4 py-2 bg-gray-900 border border-gray-700 rounded-full text-xs text-white uppercase tracking-widest hover:bg-gray-800"
                >
                    <span class="inline-flex items-center">
                        <span class="me-2">📍</span>
                        <span id="branchLabelMobile">{{ session('branch_name', 'Pilih Lokasi') }}</span>
                    </span>
                    <span class="opacity-70">▾</span>
                </button>
            </div>

            {{-- 🗺 LIHAT PETA (MOBILE) --}}
            <div class="px-4 pt-2">
                <button
                    type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'map-modal' }))"
                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-blue-500 rounded-full text-xs text-white uppercase tracking-widest hover:bg-blue-500"
                >
                    🗺 Lihat Peta
                </button>
            </div>
        </div>
    </div>
</nav>

{{-- ================= MODAL OSM (PILIH CABANG) ================= --}}
<x-modal name="branch-modal" :show="false" focusable>
    <div class="p-6 bg-white">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Pilih Lokasi Bioskop</h2>
            <button
                type="button"
                class="text-gray-500 hover:text-black"
                onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'branch-modal' }))"
            >
                ✕
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- LIST CABANG --}}
            <div id="branchList" class="space-y-2 max-h-[360px] overflow-auto">
                @foreach(($navBranches ?? collect()) as $b)
                    @php
                        $lat = $b->latitude ?? -6.186;
                        $lng = $b->longitude ?? 106.822;
                        $embed = "https://www.openstreetmap.org/export/embed.html?layer=mapnik&marker={$lat}%2C{$lng}";
                    @endphp

                    <button
                        type="button"
                        class="w-full text-left p-3 border rounded hover:bg-gray-100"
                        data-id="{{ $b->id }}"
                        data-name="{{ $b->name }}"
                        data-embed="{{ $embed }}"
                    >
                        <div class="font-semibold">{{ $b->city }}</div>
                        <div class="text-sm text-gray-500">{{ $b->name }}</div>
                    </button>
                @endforeach
            </div>

            {{-- MAP OSM --}}
            <div class="md:col-span-2 border rounded overflow-hidden">
                <iframe
                    id="osmFrame"
                    src="https://www.openstreetmap.org/export/embed.html?layer=mapnik&marker={{ session('branch_lat', -6.186) }}%2C{{ session('branch_lng', 106.822) }}"
                    style="width:100%; height:360px; border:0"
                    loading="lazy">
                </iframe>
            </div>
        </div>

        <p class="text-sm text-gray-500 mt-3">
            Klik cabang di kiri untuk mengubah lokasi.
        </p>
    </div>
</x-modal>

{{-- ================= MODAL MAP SAJA (LIHAT PETA) ================= --}}
<x-modal name="map-modal" :show="false" focusable>
    <div class="p-6 bg-white">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Peta Lokasi Bioskop</h2>
            <button
                type="button"
                class="text-gray-500 hover:text-black"
                onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'map-modal' }))"
            >
                ✕
            </button>
        </div>

        <div class="border rounded overflow-hidden">
            <iframe
                src="https://www.openstreetmap.org/export/embed.html?layer=mapnik&marker={{ session('branch_lat', -6.186) }}%2C{{ session('branch_lng', 106.822) }}"
                style="width:100%; height:460px; border:0"
                loading="lazy">
            </iframe>
        </div>

        <p class="text-sm text-gray-500 mt-3">
            Lokasi saat ini: <b>{{ session('branch_name', 'Belum dipilih') }}</b>
        </p>
    </div>
</x-modal>

{{-- ================= SCRIPT ================= --}}
<script>
document.addEventListener('click', async function (e) {
    const btn = e.target.closest('#branchList button');
    if (!btn) return;

    const branchId = btn.dataset.id;
    const name = btn.dataset.name;
    const embed = btn.dataset.embed;

    const frame = document.getElementById('osmFrame');
    if (frame) frame.src = embed;

    const url = "{{ \Illuminate\Support\Facades\Route::has('set.branch') ? route('set.branch') : '' }}";
    if (!url) {
        alert("Route set.branch belum ada di routes/web.php");
        return;
    }

    const res = await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({ branch_id: branchId })
    });

    if (!res.ok) {
        alert("Gagal menyimpan lokasi. Cek routes/web.php & controller/route set.branch.");
        return;
    }

    const labelDesktop = document.getElementById('branchLabel');
    if (labelDesktop) labelDesktop.textContent = name;

    const labelMobile = document.getElementById('branchLabelMobile');
    if (labelMobile) labelMobile.textContent = name;

    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'branch-modal' }));
    window.location.reload();
});
</script>
