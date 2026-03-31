<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Verifikasi Setoran') }}
            </h2>
            <a href="{{ route('minyak.setoran.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                + Tambah Setoran
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Pending</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['total_pending'] }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Terverifikasi</div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['total_terverifikasi'] }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500">Setoran Hari Ini</div>
                    <div class="text-xl font-bold text-blue-600">
                        Rp {{ number_format($stats['total_setoran_hari_ini'], 0, ',', '.') }}
                    </div>
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
                    <select name="status" class="border rounded-lg px-4 py-2">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg">Filter</button>
                    <a href="{{ route('minyak.setoran.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">Reset</a>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Sales</th>
                            <th class="px-4 py-3 text-right">Total Setor</th>
                            <th class="px-4 py-3 text-right">Penjualan</th>
                            <th class="px-4 py-3 text-right">Selisih</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($setorans as $s)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $s->tanggal->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $s->sales->nama }}</td>
                                <td class="px-4 py-3 text-right font-medium">
                                    Rp {{ number_format($s->total_setor, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    Rp {{ number_format($s->total_penjualan, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="{{ $s->selisih != 0 ? 'text-red-600 font-medium' : '' }}">
                                        Rp {{ number_format($s->selisih, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        {{ $s->status == 'terverifikasi' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $s->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $s->status == 'ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($s->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('minyak.setoran.show', $s) }}" class="text-blue-600 hover:underline text-sm">Detail</a>
                                    @if($s->status == 'pending')
                                        <form action="{{ route('minyak.setoran.verify', $s) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="terverifikasi">
                                            <button type="submit" class="text-green-600 hover:underline text-sm ml-2">Verifikasi</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data setoran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $setorans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
