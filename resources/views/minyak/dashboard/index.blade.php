<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Minyak') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('minyak.penjualan.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    + Tambah Penjualan
                </a>
                <a href="{{ route('minyak.loading.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                    + Loading Harian
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Hari Ini -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-500 mb-1">Penjualan Hari Ini</div>
                    <div class="text-2xl font-bold text-blue-600">
                        Rp {{ number_format($stats['penjualan_hari_ini'], 0, ',', '.') }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-500 mb-1">Transaksi Hari Ini</div>
                    <div class="text-2xl font-bold text-green-600">
                        {{ number_format($stats['transaksi_hari_ini']) }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-500 mb-1">Setoran Terverifikasi</div>
                    <div class="text-2xl font-bold text-purple-600">
                        Rp {{ number_format($stats['setoran_hari_ini'], 0, ',', '.') }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-500 mb-1">Loading Hari Ini</div>
                    <div class="text-2xl font-bold text-orange-600">
                        {{ number_format($stats['loading_hari_ini']) }} L
                    </div>
                </div>
            </div>

            <!-- Data Master & Alert -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Data Master -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Data Master</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span>Total Sales Aktif</span>
                            <span class="font-bold text-blue-600">{{ $master['total_sales'] }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span>Total Pelanggan</span>
                            <span class="font-bold text-green-600">{{ $master['total_pelanggan'] }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span>Total Produk</span>
                            <span class="font-bold text-purple-600">{{ $master['total_produk'] }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded">
                            <span>Stok Rendah</span>
                            <span class="font-bold text-red-600">{{ $master['stok_rendah'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Hutang -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Status Hutang</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span>Total Hutang</span>
                            <span class="font-bold text-red-600">
                                Rp {{ number_format($totalHutang, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded">
                            <span>Overdue</span>
                            <span class="font-bold text-red-600">{{ $hutangOverdue }}</span>
                        </div>
                    </div>
                    <a href="{{ route('minyak.hutang.index') }}" class="mt-4 block text-center text-blue-600 hover:underline text-sm">
                        Lihat Detail Hutang →
                    </a>
                </div>

                <!-- Setoran Pending -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Setoran Pending</h3>
                    @if($setoranPending->count() > 0)
                        <div class="space-y-2">
                            @foreach($setoranPending as $setoran)
                                <div class="flex justify-between items-center p-2 bg-yellow-50 rounded text-sm">
                                    <div>
                                        <div class="font-medium">{{ $setoran->sales->nama }}</div>
                                        <div class="text-gray-500">{{ $setoran->tanggal->format('d M Y') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold">
                                            Rp {{ number_format($setoran->total_setor, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Tidak ada setoran pending</p>
                    @endif
                    <a href="{{ route('minyak.setoran.index') }}" class="mt-4 block text-center text-blue-600 hover:underline text-sm">
                        Lihat Semua Setoran →
                    </a>
                </div>
            </div>

            <!-- Top Sales -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Top Sales Bulan Ini</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Rank</th>
                                <th class="px-4 py-2 text-left">Nama Sales</th>
                                <th class="px-4 py-2 text-right">Total Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSales as $index => $sales)
                                <tr class="border-t">
                                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $sales->nama }}</td>
                                    <td class="px-4 py-3 text-right">
                                        Rp {{ number_format($sales->penjualans_sum_total ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada data penjualan bulan ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
