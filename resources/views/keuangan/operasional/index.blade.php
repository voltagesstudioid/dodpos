<x-app-layout>
    <x-slot name="header">Pengeluaran Operasional</x-slot>

    <div class="page-container">

            <!-- Title & Create Button -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Data Kas Keluar</h2>
                <a href="{{ route('operasional.create') }}" class="btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Catat Pengeluaran
                </a>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Filtered -->
                <div class="card p-6 bg-gradient-to-br from-indigo-500 to-purple-600 border-none">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-white/20 text-white mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-indigo-100 uppercase tracking-wider">Total Pengeluaran (Filter)</p>
                            <p class="text-3xl font-bold text-white mt-1">Rp {{ number_format($totalFiltered, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card p-6">
                <form action="{{ route('operasional.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <label for="search" class="form-label">Cari Keterangan / No. Ref</label>
                        <input type="text" name="search" id="search" value="{{ $search }}" class="form-input mt-1" placeholder="Ketik kata kunci...">
                    </div>
                    <div class="w-full md:w-48">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select name="category_id" id="category_id" class="form-input mt-1">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2 w-full md:w-64">
                        <div>
                            <label for="start_date" class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $start_date }}" class="form-input mt-1">
                        </div>
                        <div>
                            <label for="end_date" class="form-label">S/D Tanggal</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $end_date }}" class="form-input mt-1">
                        </div>
                    </div>
                    <div class="w-full md:w-auto">
                        <button type="submit" class="btn-secondary w-full md:w-auto justify-center">Filter</button>
                    </div>
                    @if($search || $category_id || $start_date || $end_date)
                        <div class="w-full md:w-auto">
                            <a href="{{ route('operasional.index') }}" class="btn-secondary w-full md:w-auto justify-center text-red-600 border-red-200 hover:bg-red-50 hover:text-red-700">Reset</a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="card table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tgl Pengeluaran</th>
                            <th>No. Referensi</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th class="text-right">Nominal</th>
                            <th>Petugas</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td class="whitespace-nowrap">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                                <td class="font-medium text-gray-900 dark:text-gray-100">{{ $expense->reference_number }}</td>
                                <td>
                                    @if($expense->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700">
                                            {{ $expense->category->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">Tanpa Kategori</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="truncate max-w-xs" title="{{ $expense->description }}">
                                        {{ $expense->description }}
                                    </div>
                                </td>
                                <td class="font-bold text-red-600 dark:text-red-400 text-right whitespace-nowrap">
                                    - Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                </td>
                                <td class="text-sm text-muted">
                                    {{ $expense->user->name ?? '-' }}
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('operasional.edit', $expense->id) }}" class="btn-sm btn-secondary text-indigo-600 hover:text-indigo-900" title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('operasional.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengeluaran ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm btn-secondary text-red-600 hover:text-red-900" title="Hapus Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-muted">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p>Belum ada daftar pengeluaran (kas keluar).</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                @if($expenses->hasPages())
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
