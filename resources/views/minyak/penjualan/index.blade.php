<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Penjualan') }}
            </h2>
            <a href="{{ route('minyak.penjualan.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                + Tambah Penjualan
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Total Hari Ini</div>
                    <div class="text-xl font-bold text-blue-600">
                        Rp {{ number_format($stats['total_hari_ini'], 0, ',', '.') }}
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Transaksi</div>
                    <div class="text-xl font-bold text-green-600">{{ $stats['total_transaksi'] }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Tunai</div>
                    <div class="text-xl font-bold text-purple-600">
                        Rp {{ number_format($stats['total_tunai'], 0, ',', '.') }}
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Hutang Baru</div>
                    <div class="text-xl font-bold text-red-600">
                        Rp {{ number_format($stats['total_hutang'], 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari no faktur..." 
                        class="border rounded-lg px-4 py-2 w-48">
                    <select name="sales_id" class="border rounded-lg px-4 py-2">
                        <option value="">Semua Sales</option>
                        @foreach($sales as $s)
                            <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                    <select name="tipe_bayar" class="border rounded-lg px-4 py-2">
                        <option value="">Semua Tipe</option>
                        <option value="tunai" {{ request('tipe_bayar') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="hutang" {{ request('tipe_bayar') == 'hutang' ? 'selected' : '' }}>Hutang</option>
                        <option value="transfer" {{ request('tipe_bayar') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg">Filter</button>
                    <a href="{{ route('minyak.penjualan.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">Reset</a>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">No Faktur</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Sales</th>
                            <th class="px-4 py-3 text-left">Pelanggan</th>
                            <th class="px-4 py-3 text-left">Produk</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualans as $p)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">{{ $p->no_faktur }}</td>
                                <td class="px-4 py-3">{{ $p->tanggal_jual->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $p->sales->nama }}</td>
                                <td class="px-4 py-3">{{ $p->pelanggan->nama_toko }}</td>
                                <td class="px-4 py-3">{{ $p->produk->nama }} ({{ $p->jumlah }})</td>
                                <td class="px-4 py-3 text-right font-medium">
                                    Rp {{ number_format($p->total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        {{ $p->status == 'terverifikasi' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $p->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('minyak.penjualan.show', $p) }}" class="text-blue-600 hover:underline text-sm">Detail</a>
                                    @if($p->status == 'pending')
                                        <form action="{{ route('minyak.penjualan.verify', $p) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:underline text-sm ml-2">Verifikasi</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada data penjualan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $penjualans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
