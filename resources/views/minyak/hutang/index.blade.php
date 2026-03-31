<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hutang Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Total Hutang</div>
                    <div class="text-xl font-bold text-red-600">
                        Rp {{ number_format($stats['total_hutang'], 0, ',', '.') }}
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Belum Lunas</div>
                    <div class="text-2xl font-bold text-orange-600">{{ $stats['belum_lunas'] }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Overdue</div>
                    <div class="text-2xl font-bold text-red-600">{{ $stats['overdue'] }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Lunas</div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['lunas'] }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nama toko, pemilik..." 
                        class="border rounded-lg px-4 py-2 w-64">
                    <select name="pelanggan_id" class="border rounded-lg px-4 py-2">
                        <option value="">Semua Pelanggan</option>
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}" {{ request('pelanggan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_toko }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="border rounded-lg px-4 py-2">
                        <option value="">Semua Status</option>
                        <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg">Filter</button>
                    <a href="{{ route('minyak.hutang.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">Reset</a>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Pelanggan</th>
                            <th class="px-4 py-3 text-left">No Faktur</th>
                            <th class="px-4 py-3 text-left">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-right">Total Hutang</th>
                            <th class="px-4 py-3 text-right">Dibayar</th>
                            <th class="px-4 py-3 text-right">Sisa</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hutangs as $h)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $h->pelanggan->nama_toko }}</td>
                                <td class="px-4 py-3 font-medium">{{ $h->penjualan->no_faktur ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="{{ $h->jatuh_tempo < now() && $h->status == 'belum_lunas' ? 'text-red-600 font-medium' : '' }}">
                                        {{ $h->jatuh_tempo->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    Rp {{ number_format($h->total_hutang, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    Rp {{ number_format($h->dibayar, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-medium">
                                    Rp {{ number_format($h->sisa, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        {{ $h->status == 'lunas' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $h->status == 'belum_lunas' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ $h->status == 'belum_lunas' ? 'Belum Lunas' : 'Lunas' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('minyak.hutang.show', $h) }}" class="text-blue-600 hover:underline text-sm">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada data hutang</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $hutangs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
