<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Peta Lokasi Bioskop
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="rounded-xl overflow-hidden border border-gray-200">
                    <iframe
                        src="https://www.openstreetmap.org/export/embed.html?layer=mapnik&marker={{ session('branch_lat', -6.186) }}%2C{{ session('branch_lng', 106.822) }}"
                        style="width:100%; height:520px; border:0"
                        loading="lazy">
                    </iframe>
                </div>

                <div class="mt-3 text-sm text-gray-600">
                    Lokasi saat ini: <b>{{ session('branch_name', 'Belum dipilih') }}</b>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
