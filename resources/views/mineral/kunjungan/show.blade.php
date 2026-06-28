<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('mineral.kunjungan.index') }}" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-900">Detail Kunjungan</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $kunjungan->waktu_checkin->format('d M Y, H:i') }} WIB</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Sales --}}
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
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-500">No. Kendaraan</span>
                            <span class="text-sm font-medium text-gray-900">{{ $kunjungan->sales->no_kendaraan ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Pelanggan --}}
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

                {{-- Waktu --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Waktu Kunjungan
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Check-in</span>
                            <span class="text-sm font-medium text-gray-900">{{ $kunjungan->waktu_checkin->format('d M Y, H:i') }} WIB</span>
                        </div>
                        @if($kunjungan->latitude_checkin && $kunjungan->longitude_checkin)
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Koordinat</span>
                            <a href="https://maps.google.com/?q={{ $kunjungan->latitude_checkin }},{{ $kunjungan->longitude_checkin }}"
                               target="_blank" class="text-sm font-medium text-blue-600 hover:underline">
                                {{ number_format((float)$kunjungan->latitude_checkin, 6) }}, {{ number_format((float)$kunjungan->longitude_checkin, 6) }}
                            </a>
                        </div>
                        @endif
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-500">Status</span>
                            <span class="text-sm font-medium text-gray-500">Tercatat otomatis dari penjualan</span>
                        </div>
                    </div>
                    @if($kunjungan->foto_checkin)
                    <div class="mt-3">
                        <span class="text-sm text-gray-500 block mb-2">Foto</span>
                        <img src="{{ asset('storage/' . $kunjungan->foto_checkin) }}"
                             alt="Foto" class="w-full h-48 object-cover rounded-xl cursor-pointer"
                             onclick="window.open(this.src,'_blank')">
                    </div>
                    @endif
                </div>

                {{-- Transaksi --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
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
                                <div class="text-xs text-green-600">Produk</div>
                                <div class="font-semibold text-green-800">{{ $kunjungan->penjualan->produk->nama_produk ?? '-' }} ({{ $kunjungan->penjualan->jumlah }})</div>
                            </div>
                            <div>
                                <div class="text-xs text-green-600">Total</div>
                                <div class="font-semibold text-green-800">Rp {{ number_format($kunjungan->penjualan->total, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-green-600">Pembayaran</div>
                                <div class="font-semibold text-green-800">{{ ucfirst($kunjungan->penjualan->tipe_bayar) }}</div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                        <p class="text-gray-500">Tidak ada transaksi pada kunjungan ini</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Catatan --}}
            @if($kunjungan->catatan)
            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Catatan
                </h3>
                <p class="text-gray-700">{{ $kunjungan->catatan }}</p>
            </div>
            @endif

            {{-- Back --}}
            <div class="mt-6">
                <a href="{{ route('mineral.kunjungan.index') }}"
                   class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 px-4 py-3 rounded-xl font-medium transition-colors border border-gray-200 hover:border-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
