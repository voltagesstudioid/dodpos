<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Loading Harian') }}
            </h2>
            <a href="{{ route('minyak.loading.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                + Tambah Loading
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Total Loading Hari Ini</div>
                    <div class="text-2xl font-bold text-blue-600">
                        {{ number_format($stats['total_hari_ini']) }} L
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Sales Aktif Hari Ini</div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['total_sales'] }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                        class="border rounded-lg px-4 py-2">
                    <select name="sales_id" class="border rounded-lg px-4 py-2">
                        <option value="">Semua Sales</option>
                        @foreach($sales as $s)
                            <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg">Filter</button>
                    <a href="{{ route('minyak.loading.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">Reset</a>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Sales</th>
                            <th class="px-4 py-3 text-left">Produk</th>
                            <th class="px-4 py-3 text-right">Loading</th>
                            <th class="px-4 py-3 text-right">Terjual</th>
                            <th class="px-4 py-3 text-right">Sisa</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loadings as $l)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $l->tanggal->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $l->sales->nama }}</td>
                                <td class="px-4 py-3">{{ $l->produk->nama }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($l->jumlah_loading) }} L</td>
                                <td class="px-4 py-3 text-right">{{ number_format($l->terjual) }} L</td>
                                <td class="px-4 py-3 text-right font-medium">{{ number_format($l->sisa_stok) }} L</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        {{ $l->status == 'loading' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $l->status == 'proses' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $l->status == 'selesai' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ ucfirst($l->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('minyak.loading.show', $l) }}" class="text-blue-600 hover:underline text-sm">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada data loading</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $loadings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
