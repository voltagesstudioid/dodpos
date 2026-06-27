@extends('layouts.app')

@section('header', 'Riwayat Operasional')

@section('content')
<div class="page-container animate-in">
    {{-- Header --}}
    <div class="ph">
        <div class="ph-left">
            <div class="ph-icon indigo">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
            </div>
            <div>
                <h1 class="ph-title">Riwayat Operasional</h1>
                <div class="ph-subtitle">Laporan detail pengeluaran kas operasional toko</div>
            </div>
        </div>
        <div class="ph-actions">
            @can('create_pengeluaran_operasional')
            <a href="{{ route('operasional.pengeluaran.create') }}" class="btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Catat Pengeluaran Baru
            </a>
            @endcan
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success animate-in">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger animate-in">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Active Session Info Bar --}}
    @if(isset($activeSession))
    <div class="panel" style="margin-bottom:1.25rem;border-left:4px solid #059669;">
        <div class="panel-body" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;padding:0.875rem 1.25rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div style="width:34px;height:34px;border-radius:10px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;color:#059669;flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
                <div>
                    <div style="font-size:0.8125rem;font-weight:600;color:#0f172a;">
                        Sesi Aktif: {{ $activeSession->created_at->format('d M Y H:i') }}
                    </div>
                    <div style="font-size:0.75rem;color:#64748b;">
                        Modal: Rp {{ number_format($activeSession->opening_amount, 0, ',', '.') }}
                        @if(isset($activeSession->expenses_sum_amount) && $activeSession->expenses_sum_amount > 0)
                            · Terpakai: Rp {{ number_format($activeSession->expenses_sum_amount, 0, ',', '.') }}
                            · Sisa: Rp {{ number_format(max(0, $activeSession->opening_amount - $activeSession->expenses_sum_amount), 0, ',', '.') }}
                        @endif
                        · Petugas: {{ $activeSession->user->name ?? '-' }}
                    </div>
                </div>
            </div>
            <span class="badge badge-success">● Sedang Berjalan</span>
        </div>
    </div>
    @endif

    {{-- Filter Section --}}
    <div class="card-premium" style="margin-bottom:1.25rem;">
        <div style="padding:0.875rem 1.25rem;background:#f8fafc;border-bottom:1px solid #f1f5f9;">
            <form method="GET" action="{{ route('operasional.riwayat.index') }}">
                <div class="filter-bar" style="padding:0;background:transparent;border:none;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-input" value="{{ $startDate }}">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-input" value="{{ $endDate }}">
                    </div>
                    <div class="form-group" style="margin-bottom:0;flex:1;min-width:160px;">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-input">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $selectedCategoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex;gap:0.5rem;align-items:flex-end;padding-top:1.25rem;">
                        <button type="submit" class="btn-primary" style="padding:0.5rem 1rem;font-size:0.8125rem;">Filter</button>
                        <a href="{{ route('operasional.riwayat.index') }}" class="btn-secondary" style="padding:0.5rem 1rem;font-size:0.8125rem;">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Stat Cards --}}
        <div style="padding:1.25rem;">
            <div class="stat-grid">
                <div class="stat-card animate-in animate-in-delay-1">
                    <div class="stat-card-row">
                        <div>
                            <div class="stat-label">Total Pengeluaran</div>
                            <div class="stat-value indigo">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat-icon indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card animate-in animate-in-delay-2">
                    <div class="stat-card-row">
                        <div>
                            <div class="stat-label">Jumlah Transaksi</div>
                            <div class="stat-value" style="color:#0f172a;">{{ $totalRecords }} <span style="font-size:0.85rem;font-weight:600;color:#94a3b8;">Data</span></div>
                        </div>
                        <div class="stat-icon" style="background:linear-gradient(135deg,#f1f5f9,#e2e8f0);color:#64748b;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card animate-in animate-in-delay-3" style="cursor:pointer;" onclick="window.location.href='{{ route('operasional.riwayat.export', request()->all()) }}'">
                    <div class="stat-card-row">
                        <div>
                            <div class="stat-label">Export PDF</div>
                            <div style="font-size:1rem;font-weight:700;color:#059669;">Download Laporan</div>
                        </div>
                        <div class="stat-icon" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);color:#059669;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="panel">
        <div class="tbl-header">
            <div>
                <div class="panel-title">Daftar Transaksi Pengeluaran</div>
                <div class="panel-subtitle">
                    @if($expenses->total() > 0)
                        Menampilkan {{ $expenses->firstItem() }}-{{ $expenses->lastItem() }} dari {{ $expenses->total() }} transaksi
                    @else
                        Belum ada data transaksi
                    @endif
                </div>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Unit Terkait</th>
                        <th class="r">Nominal (Rp)</th>
                        <th class="c">PIC</th>
                        <th class="c" style="width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>
                                <div class="td-main">{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</div>
                            </td>
                            <td>
                                <span class="badge badge-indigo">{{ $expense->category?->name ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="td-sub" title="{{ $expense->notes ?? '-' }}" style="max-width:250px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $expense->notes ?? '-' }}
                                </div>
                            </td>
                            <td>
                                @if($expense->vehicle)
                                    <span class="badge badge-warning" style="gap:4px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                        {{ strtoupper($expense->vehicle->license_plate) }}
                                    </span>
                                @else
                                    <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>
                            <td class="r" style="font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace;font-weight:800;color:#ef4444;">
                                -Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                            <td class="c">
                                <span style="font-size:0.75rem;font-weight:600;color:#64748b;background:#f1f5f9;padding:3px 8px;border-radius:5px;">
                                    {{ $expense->user?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="c">
                                <div style="display:flex;gap:4px;justify-content:center;">
                                    @can('edit_pengeluaran_operasional')
                                    <a href="{{ route('operasional.pengeluaran.edit', $expense) }}" class="act-btn-edit"
                                       style="width:32px;height:32px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;border:1px solid;text-decoration:none;"
                                       title="Edit Transaksi">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    @endcan
                                    @can('delete_pengeluaran_operasional')
                                    <form action="{{ route('operasional.pengeluaran.destroy', $expense) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn-del" style="width:32px;height:32px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;border:1px solid;cursor:pointer;"
                                                title="Hapus Transaksi">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="6" y1="8" x2="6.01" y2="8"></line><line x1="10" y1="8" x2="10.01" y2="8"></line></svg>
                                    </div>
                                    <div class="empty-state-title">Belum ada riwayat pengeluaran</div>
                                    <div class="empty-state-desc">Tidak ada data transaksi yang sesuai dengan filter periode ini.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
            <div style="padding:1rem 1.375rem;border-top:1px solid #f1f5f9;">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
