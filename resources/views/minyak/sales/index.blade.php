<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Data Sales Minyak') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola data sales dan kendaraan distribusi</p>
            </div>
            <a href="{{ route('minyak.sales.create') }}" 
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Sales
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Total Sales</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Aktif</div>
                            <div class="text-2xl font-bold text-green-600">{{ $stats['aktif'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Nonaktif</div>
                            <div class="text-2xl font-bold text-gray-600">{{ $stats['nonaktif'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-yellow-50 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Cuti</div>
                            <div class="text-2xl font-bold text-yellow-600">{{ $stats['cuti'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <div class="relative">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Cari nama, kode, no HP..." 
                                class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('minyak.sales.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg font-medium transition-colors">
                        Reset
                    </a>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Sales</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kendaraan</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($sales as $s)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ $s->kode_sales }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-medium">{{ substr($s->nama, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $s->nama }}</div>
                                                <div class="text-sm text-gray-500">{{ $s->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $s->no_hp ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $s->plat_nomor ?? '-' }}</div>
                                        @if($s->kapasitas_tangki)
                                            <div class="text-xs text-gray-500">{{ number_format($s->kapasitas_tangki) }} L</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium
                                            {{ $s->status == 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $s->status == 'nonaktif' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $s->status == 'cuti' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                            {{ ucfirst($s->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('minyak.sales.show', $s) }}" 
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors">
                                                Detail
                                            </a>
                                            <a href="{{ route('minyak.sales.edit', $s) }}" 
                                                class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-600 rounded-lg text-sm font-medium hover:bg-green-100 transition-colors">
                                                Edit
                                            </a>
                                            <form action="{{ route('minyak.sales.destroy', $s) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data sales ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="p-4 bg-gray-100 rounded-full mb-3">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            </div>
                                            <p class="text-gray-500 text-lg font-medium">Tidak ada data sales</p>
                                            <p class="text-gray-400 text-sm mt-1">Silakan tambah data sales baru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
