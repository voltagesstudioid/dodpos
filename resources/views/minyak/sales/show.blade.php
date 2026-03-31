<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('minyak.sales.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Detail Sales') }}
                    </h2>
                    <p class="text-sm text-gray-500">Kode: <span class="font-mono font-medium">{{ $sales->kode_sales }}</span></p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('minyak.sales.edit', $sales) }}" 
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Data
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center backdrop-blur">
                                <span class="text-2xl font-bold">{{ substr($sales->nama, 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">{{ $sales->nama }}</h3>
                                <span class="inline-flex mt-1 px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur">
                                    {{ ucfirst($sales->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-100 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">No HP</div>
                                    <div class="font-medium">{{ $sales->no_hp ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-100 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Email</div>
                                    <div class="font-medium">{{ $sales->email ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-100 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Alamat</div>
                                    <div class="font-medium">{{ $sales->alamat ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kendaraan Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            Informasi Kendaraan
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-100 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Plat Nomor</div>
                                    <div class="font-medium">{{ $sales->plat_nomor ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-100 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Kapasitas Tangki</div>
                                    <div class="font-medium">{{ $sales->kapasitas_tangki ? number_format($sales->kapasitas_tangki) . ' L' : '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistik Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Statistik Bulan Ini
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="p-4 bg-blue-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Total Loading</div>
                                        <div class="text-xl font-bold text-blue-600">
                                            {{ number_format($sales->loadings->sum('jumlah_loading')) }} L
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-green-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Total Penjualan</div>
                                        <div class="text-xl font-bold text-green-600">
                                            Rp {{ number_format($sales->penjualans->sum('total'), 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-purple-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-purple-100 rounded-lg">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Jumlah Transaksi</div>
                                        <div class="text-xl font-bold text-purple-600">
                                            {{ number_format($sales->penjualans->count()) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Setoran Terakhir -->
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Riwayat Setoran Terakhir</h3>
                </div>
                <div class="p-6">
                    @if($sales->setorans->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Setor</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Penjualan</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($sales->setorans->take(5) as $setoran)
                                        <tr>
                                            <td class="px-4 py-3">{{ $setoran->tanggal->format('d M Y') }}</td>
                                            <td class="px-4 py-3 text-right font-medium">
                                                Rp {{ number_format($setoran->total_setor, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                Rp {{ number_format($setoran->total_penjualan, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $setoran->status == 'terverifikasi' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $setoran->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                    {{ ucfirst($setoran->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="p-4 bg-gray-100 rounded-full inline-block mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500">Belum ada riwayat setoran</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
