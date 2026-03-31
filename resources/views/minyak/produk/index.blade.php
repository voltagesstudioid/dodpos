<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Produk') }}
            </h2>
            <a href="{{ route('minyak.produk.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                + Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Total Produk</div>
                    <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Aktif</div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['aktif'] }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Stok Rendah</div>
                    <div class="text-2xl font-bold text-red-600">{{ $stats['stok_rendah'] }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nama, kode, jenis..." 
                        class="border rounded-lg px-4 py-2 w-64">
                    <select name="jenis" class="border rounded-lg px-4 py-2">
                        <option value="">Semua Jenis</option>
                        <option value="Pertalite" {{ request('jenis') == 'Pertalite' ? 'selected' : '' }}>Pertalite</option>
                        <option value="Pertamax" {{ request('jenis') == 'Pertamax' ? 'selected' : '' }}>Pertamax</option>
                        <option value="Solar" {{ request('jenis') == 'Solar' ? 'selected' : '' }}>Solar</option>
                    </select>
                    <select name="status" class="border rounded-lg px-4 py-2">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg">Filter</button>
                    <a href="{{ route('minyak.produk.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">Reset</a>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Kode</th>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Jenis</th>
                            <th class="px-4 py-3 text-left">Satuan</th>
                            <th class="px-4 py-3 text-right">Harga Jual</th>
                            <th class="px-4 py-3 text-right">Stok Gudang</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produks as $p)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">{{ $p->kode_produk }}</td>
                                <td class="px-4 py-3">{{ $p->nama }}</td>
                                <td class="px-4 py-3">{{ $p->jenis ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $p->satuan }}</td>
                                <td class="px-4 py-3 text-right">
                                    Rp {{ number_format($p->harga_jual, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="{{ $p->stok_gudang <= $p->stok_minimum ? 'text-red-600 font-bold' : '' }}">
                                        {{ number_format($p->stok_gudang) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('minyak.produk.show', $p) }}" class="text-blue-600 hover:underline text-sm">Detail</a>
                                    <a href="{{ route('minyak.produk.edit', $p) }}" class="text-green-600 hover:underline text-sm ml-2">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data produk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $produks->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
