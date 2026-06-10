<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('gula.kunjungan.index') }}" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-900">📍 Detail Kunjungan</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $kunjungan->waktu_checkin->format('d M Y, H:i') }} WIB</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Banner -->
            @php
                $isCompleted = $kunjungan->waktu_checkout !== null;
                $durasi = $isCompleted ? $kunjungan->waktu_checkin->diffInMinutes($kunjungan->waktu_checkout) : null;
            @endphp
            <div class="mb-6 p-4 rounded-2xl {{ $isCompleted ? 'bg-green-50 border border-green-200' : 'bg-amber-50 border border-amber-200' }}">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl {{ $isCompleted ? 'bg-green-100' : 'bg-amber-100' }} flex items-center justify-center">
                        <svg class="w-6 h-6 {{ $isCompleted ? 'text-green-600' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($isCompleted)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold {{ $isCompleted ? 'text-green-800' : 'text-amber-800' }}">
                            {{ $isCompleted ? 'Kunjungan Selesai' : 'Sedang Berlangsung' }}
                        </div>
                        @if($durasi)
                            <div class="text-sm {{ $isCompleted ? 'text-green-600' : 'text-amber-600' }}">
                                Durasi: {{ $durasi }} menit
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Peta Kunjungan -->
            @if(($kunjungan->latitude_checkin && $kunjungan->longitude_checkin) || ($kunjungan->latitude_checkout && $kunjungan->longitude_checkout))
            <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Peta Lokasi Kunjungan
                </h3>
                <div id="map" style="height: 350px; border-radius: 14px;" class="border border-gray-100"></div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Info Sales -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Informasi Sales
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Nama</span>
                            <span class="text-sm font-medium text-gray-900">{{ $kunjungan->sales->nama ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Kode Sales</span>
                            <span class="text-sm font-medium text-gray-900">{{ $kunjungan->sales->kode_sales ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">No. Kendaraan</span>
                            <span class="text-sm font-medium text-gray-900">{{ $kunjungan->sales->no_kendaraan ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-500">Jenis Kendaraan</span>
                            <span class="text-sm font-medium text-gray-900">{{ $kunjungan->sales->jenis_kendaraan ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Pelanggan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Informasi Pelanggan
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Nama Toko</span>
                            <span class="text-sm font-medium text-gray-900">{{ $kunjungan->pelanggan->nama_toko ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Pemilik</span>
                            <span class="text-sm font-medium text-gray-900">{{ $kunjungan->pelanggan->nama_pemilik ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">No. HP</span>
                            <span class="text-sm font-medium text-gray-900">{{ $kunjungan->pelanggan->no_hp ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-500">Alamat</span>
                            <span class="text-sm font-medium text-gray-900 text-right max-w-xs">{{ $kunjungan->pelanggan->alamat ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Check-in Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Check-in
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Waktu</span>
                            <span class="text-sm font-medium text-gray-900">{{ $kunjungan->waktu_checkin->format('H:i:s') }} WIB</span>
                        </div>
                        @if($kunjungan->latitude_checkin && $kunjungan->longitude_checkin)
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Koordinat</span>
                            <a href="https://maps.google.com/?q={{ $kunjungan->latitude_checkin }},{{ $kunjungan->longitude_checkin }}" 
                               target="_blank" class="text-sm font-medium text-blue-600 hover:underline">
                                {{ number_format($kunjungan->latitude_checkin, 6) }}, {{ number_format($kunjungan->longitude_checkin, 6) }}
                            </a>
                        </div>
                        @endif
                        @if($kunjungan->foto_checkin)
                        <div class="mt-3">
                            <span class="text-sm text-gray-500 block mb-2">Foto Check-in</span>
                            <img src="{{ asset('storage/' . $kunjungan->foto_checkin) }}" 
                                 alt="Foto Check-in" 
                                 class="w-full h-48 object-cover rounded-xl">
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Check-out Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Check-out
                    </h3>
                    @if($kunjungan->waktu_checkout)
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Waktu</span>
                            <span class="text-sm font-medium text-gray-900">{{ $kunjungan->waktu_checkout->format('H:i:s') }} WIB</span>
                        </div>
                        @if($kunjungan->latitude_checkout && $kunjungan->longitude_checkout)
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Koordinat</span>
                            <a href="https://maps.google.com/?q={{ $kunjungan->latitude_checkout }},{{ $kunjungan->longitude_checkout }}" 
                               target="_blank" class="text-sm font-medium text-blue-600 hover:underline">
                                {{ number_format($kunjungan->latitude_checkout, 6) }}, {{ number_format($kunjungan->longitude_checkout, 6) }}
                            </a>
                        </div>
                        @endif
                        @if($kunjungan->foto_checkout)
                        <div class="mt-3">
                            <span class="text-sm text-gray-500 block mb-2">Foto Check-out</span>
                            <img src="{{ asset('storage/' . $kunjungan->foto_checkout) }}" 
                                 alt="Foto Check-out" 
                                 class="w-full h-48 object-cover rounded-xl">
                        </div>
                        @endif
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-4">Belum melakukan check-out</p>
                    @endif
                </div>
            </div>

            <!-- Transaksi Info -->
            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Informasi Transaksi
                </h3>
                @if($kunjungan->ada_penjualan && $kunjungan->penjualan)
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div class="font-medium text-green-800">Ada Transaksi</div>
                            <div class="text-sm text-green-600">No. Faktur: {{ $kunjungan->penjualan->no_faktur }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-green-200">
                        <div>
                            <div class="text-xs text-green-600">Total</div>
                            <div class="font-semibold text-green-800">Rp {{ number_format($kunjungan->penjualan->total, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-green-600">Pembayaran</div>
                            <div class="font-semibold text-green-800">{{ ucfirst($kunjungan->penjualan->tipe_bayar) }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-green-600">Status</div>
                            <div class="font-semibold text-green-800">{{ ucfirst($kunjungan->penjualan->status) }}</div>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-gray-500">Tidak ada transaksi pada kunjungan ini</p>
                </div>
                @endif
            </div>

            <!-- Catatan -->
            @if($kunjungan->catatan)
            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">📝 Catatan</h3>
                <p class="text-gray-700">{{ $kunjungan->catatan }}</p>
            </div>
            @endif

            <!-- Back Button -->
            <div class="mt-6 flex items-center gap-4">
                <a href="{{ route('gula.kunjungan.index') }}" 
                   class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 px-4 py-3 rounded-xl font-medium transition-colors border border-gray-200 hover:border-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .leaflet-container { font-family: inherit; z-index: 1; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($kunjungan->latitude_checkin && $kunjungan->longitude_checkin)
            const checkinLat = {{ $kunjungan->latitude_checkin }};
            const checkinLng = {{ $kunjungan->longitude_checkin }};
            
            const map = L.map('map').setView([checkinLat, checkinLng], 16);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([checkinLat, checkinLng]).addTo(map)
                .bindPopup('<b>📍 Check-in Lokasi</b><br>Toko: {{ $kunjungan->pelanggan->nama_toko ?? "-" }}<br>Waktu: {{ $kunjungan->waktu_checkin->format("H:i:s") }} WIB')
                .openPopup();

            @if($kunjungan->latitude_checkout && $kunjungan->longitude_checkout)
                const checkoutLat = {{ $kunjungan->latitude_checkout }};
                const checkoutLng = {{ $kunjungan->longitude_checkout }};
                
                L.marker([checkoutLat, checkoutLng]).addTo(map)
                    .bindPopup('<b>🏁 Check-out Lokasi</b><br>Waktu: {{ $kunjungan->waktu_checkout->format("H:i:s") }} WIB');

                const bounds = L.latLngBounds([[checkinLat, checkinLng], [checkoutLat, checkoutLng]]);
                map.fitBounds(bounds, { padding: [50, 50] });
            @endif
        @endif
    });
</script>
@endpush
