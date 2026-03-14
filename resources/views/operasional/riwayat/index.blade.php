<x-app-layout>
    <x-slot name="header">Riwayat Operasional</x-slot>

    <div class="page-container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
            <div>
                <h1 style="font-size:1.375rem; font-weight:700; color:#1e293b; margin:0;">📉 Riwayat Operasional</h1>
                <p style="color:#64748b; font-size:0.875rem; margin:0.25rem 0 0;">Laporan riwayat pengeluaran kas operasional.</p>
            </div>
            <a href="{{ route('operasional.pengeluaran.create') }}" class="btn-primary" style="display:inline-flex; align-items:center; gap:0.5rem;">
                + Catat Pengeluaran Baru
            </a>
        </div>

        <!-- Filter Form -->
        <div class="card" style="padding:1.25rem; margin-bottom:1.25rem;">
            <form method="GET" action="{{ route('operasional.riwayat.index') }}" style="display:flex; gap:1rem; align-items:flex-end; flex-wrap:wrap;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="font-size:0.8rem;">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-input" value="{{ request('start_date', $startDate) }}" style="width:160px;">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="font-size:0.8rem;">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-input" value="{{ request('end_date', $endDate) }}" style="width:160px;">
                </div>
                
                <div class="form-group" style="margin-bottom:0; flex-grow:1; min-width:200px;">
                    <label class="form-label" style="font-size:0.8rem;">Cari Kategori</label>
                    <select name="category_id" class="form-input">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display:flex; gap:0.5rem; margin-bottom:0;">
                    <button type="submit" class="btn-primary" style="padding:0.6rem 1rem;">Filter Data</button>
                    <a href="{{ route('operasional.riwayat.index') }}" class="btn-secondary" style="padding:0.6rem 1rem;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Summary Totals -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1.25rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.5rem; border-left:4px solid #ef4444;">
                <span style="font-size:0.8rem; font-weight:600; color:#64748b; text-transform:uppercase;">Total Nominal Pengeluaran</span>
                <div style="font-size:1.75rem; font-weight:800; color:#ef4444; margin-top:0.25rem;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
            </div>
            <div class="card" style="padding:1.5rem; border-left:4px solid #3b82f6;">
                <span style="font-size:0.8rem; font-weight:600; color:#64748b; text-transform:uppercase;">Jumlah Data Transaksi</span>
                <div style="font-size:1.75rem; font-weight:800; color:#1e293b; margin-top:0.25rem;">{{ $totalRecords }} Data</div>
            </div>
        </div>

        <!-- Table Data -->
        <div class="card">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:120px;">Tanggal</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th>Kendaraan</th>
                            <th style="text-align:right;">Nominal (Rp)</th>
                            <th style="text-align:center;">Diinput Oleh</th>
                            <th style="text-align:center; width:100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                        <tr>
                            <td style="color:#64748b;">{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</td>
                            <td>
                                <span style="background:rgba(99, 102, 241, 0.1); color:#4f46e5; padding:0.25rem 0.6rem; border-radius:6px; font-weight:600; font-size:0.8rem;">
                                    {{ $expense->category->name }}
                                </span>
                            </td>
                            <td style="color:#334155;">{{ $expense->notes ?? '-' }}</td>
                            <td>
                                @if($expense->vehicle)
                                    <span style="background:rgba(245, 158, 11, 0.1); color:#d97706; padding:0.2rem 0.6rem; border-radius:6px; font-weight:600; font-size:0.75rem; display:inline-flex; align-items:center; gap:0.25rem;">
                                        🚙 {{ strtoupper($expense->vehicle->license_plate) }}
                                    </span>
                                @else
                                    <span style="color:#94a3b8;">-</span>
                                @endif
                            </td>
                            <td style="text-align:right; font-weight:700; color:#ef4444;">-Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            <td style="text-align:center; color:#64748b; font-size:0.85rem;">{{ $expense->user->name }}</td>
                            <td style="text-align:center;">
                                <div style="display:flex; gap:0.25rem; justify-content:center;">
                                    <a href="{{ route('operasional.pengeluaran.edit', $expense) }}" class="btn-secondary" style="padding:0.25rem 0.5rem; font-size:0.75rem;" title="Edit">
                                        ✏️
                                    </a>
                                    <form action="{{ route('operasional.pengeluaran.destroy', $expense) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" style="padding:0.25rem 0.5rem; font-size:0.75rem;" title="Hapus">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:3rem 1rem; color:#94a3b8;">
                                Belum ada riwayat pengeluaran yang ditemukan pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($expenses->hasPages())
                <div style="padding:1.25rem; border-top:1px solid #f1f5f9;">{{ $expenses->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
