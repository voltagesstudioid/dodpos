<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Pelanggan') }}
            </h2>
            <a href="{{ route('minyak.pelanggan.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                + Tambah Pelanggan
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Total</div>
                    <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Aktif</div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['aktif'] }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Eceran</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['eceran'] }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Grosir</div>
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['grosir'] }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Total Hutang</div>
                    <div class="text-2xl font-bold text-red-600">
                        Rp {{ number_format($stats['total_hutang'], 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nama toko, pemilik..." 
                        class="border rounded-lg px-4 py-2 w-64">
                    <select name="tipe" class="border rounded-lg px-4 py-2">
                        <option value="">Semua Tipe</option>
                        <option value="eceran" {{ request('tipe') == 'eceran' ? 'selected' : '' }}>Eceran</option>
                        <option value="grosir" {{ request('tipe') == 'grosir' ? 'selected' : '' }}>Grosir</option>
                        <option value="agen" {{ request('tipe') == 'agen' ? 'selected' : '' }}>Agen</option>
                    </select>
                    <select name="status" class="border rounded-lg px-4 py-2">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="blacklist" {{ request('status') == 'blacklist' ? 'selected' : '' }}>Blacklist</option>
                    </select>
                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg">Filter</button>
                    <a href="{{ route('minyak.pelanggan.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">Reset</a>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Kode</th>
                            <th class="px-4 py-3 text-left">Nama Toko</th>
                            <th class="px-4 py-3 text-left">Pemilik</th>
                            <th class="px-4 py-3 text-left">No HP</th>
                            <th class="px-4 py-3 text-left">Tipe</th>
                            <th class="px-4 py-3 text-right">Hutang</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggans as $p)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">{{ $p->kode_pelanggan }}</td>
                                <td class="px-4 py-3">{{ $p->nama_toko }}</td>
                                <td class="px-4 py-3">{{ $p->nama_pemilik }}</td>
                                <td class="px-4 py-3">{{ $p->no_hp }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        {{ $p->tipe == 'eceran' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $p->tipe == 'grosir' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $p->tipe == 'agen' ? 'bg-orange-100 text-orange-800' : '' }}">
                                        {{ ucfirst($p->tipe) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="{{ $p->total_hutang > 0 ? 'text-red-600 font-medium' : '' }}">
                                        Rp {{ number_format($p->total_hutang, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('minyak.pelanggan.show', $p) }}" class="text-blue-600 hover:underline text-sm">Detail</a>
                                    <a href="{{ route('minyak.pelanggan.edit', $p) }}" class="text-green-600 hover:underline text-sm ml-2">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data pelanggan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $pelanggans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
