<x-app-layout>
    <x-slot name="header">Riwayat Operasional</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER SECTION ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Keuangan & Kas</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-danger">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                        </div>
                        Riwayat Operasional
                    </h1>
                    <p class="tr-subtitle">Laporan detail riwayat pengeluaran kas operasional toko Anda.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('operasional.pengeluaran.create') }}" class="tr-btn tr-btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Catat Pengeluaran Baru
                    </a>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success'))
                <div class="tr-alert tr-alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="tr-alert tr-alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- ─── FILTER CARD ─── --}}
            <div class="tr-card tr-filter-card">
                <form method="GET" action="{{ route('operasional.riwayat.index') }}" class="tr-filter-grid">
                    <div class="tr-form-group">
                        <label class="tr-label">Dari Tanggal</label>
                        <input type="date" name="start_date" class="tr-input" value="{{ request('start_date', $startDate) }}">
                    </div>
                    <div class="tr-form-group">
                        <label class="tr-label">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="tr-input" value="{{ request('end_date', $endDate) }}">
                    </div>
                    <div class="tr-form-group tr-flex-grow">
                        <label class="tr-label">Cari Kategori</label>
                        <div class="tr-select-wrapper">
                            <select name="category_id" class="tr-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="tr-filter-actions">
                        <button type="submit" class="tr-btn tr-btn-dark">Filter Data</button>
                        <a href="{{ route('operasional.riwayat.index') }}" class="tr-btn tr-btn-outline">Reset</a>
                    </div>
                </form>
            </div>

            {{-- ─── SUMMARY KPI CARDS ─── --}}
            <div class="tr-kpi-grid">
                <div class="tr-kpi-card border-danger">
                    <div class="tr-kpi-icon bg-danger-soft text-danger">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                    <div>
                        <div class="tr-kpi-label">Total Pengeluaran</div>
                        <div class="tr-kpi-value text-danger tr-font-mono">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="tr-kpi-card border-indigo">
                    <div class="tr-kpi-icon bg-indigo-soft text-indigo">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                    <div>
                        <div class="tr-kpi-label">Jumlah Transaksi</div>
                        <div class="tr-kpi-value text-main">{{ $totalRecords }} <span style="font-size:0.85rem; font-weight:600; color:var(--tr-text-muted);">Data</span></div>
                    </div>
                </div>
                <div class="tr-kpi-card border-success" style="cursor:pointer;" onclick="window.location.href='{{ route('operasional.riwayat.export', request()->all()) }}'">
                    <div class="tr-kpi-icon bg-success-soft text-success">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </div>
                    <div>
                        <div class="tr-kpi-label">Export Data</div>
                        <div class="tr-kpi-value text-success" style="font-size:1rem;">Download CSV</div>
                    </div>
                </div>
            </div>

            {{-- ─── MAIN DATA TABLE ─── --}}
            <div class="tr-card">
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Keterangan</th>
                                <th>Unit Terkait</th>
                                <th class="r">Nominal (Rp)</th>
                                <th class="c">PIC / Diinput Oleh</th>
                                <th class="c" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expenses as $expense)
                                <tr>
                                    <td>
                                        <div class="tr-date-box">{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <span class="tr-badge tr-badge-indigo">{{ $expense->category->name }}</span>
                                    </td>
                                    <td>
                                        <div class="tr-notes-text" title="{{ $expense->notes ?? '-' }}">
                                            {{ $expense->notes ?? '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($expense->vehicle)
                                            <span class="tr-badge tr-badge-warning">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                                {{ strtoupper($expense->vehicle->license_plate) }}
                                            </span>
                                        @else
                                            <span class="tr-text-light">—</span>
                                        @endif
                                    </td>
                                    <td class="r tr-font-mono text-danger tr-font-bold">
                                        -Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="c">
                                        <span class="tr-pic-name">{{ $expense->user->name }}</span>
                                    </td>
                                    <td class="c">
                                        <div class="tr-actions-group">
                                            <a href="{{ route('operasional.pengeluaran.edit', $expense) }}" class="tr-action-btn edit" title="Edit Transaksi">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            <form action="{{ route('operasional.pengeluaran.destroy', $expense) }}" method="POST" class="tr-inline" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="tr-action-btn delete" title="Hapus Transaksi">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="6" y1="8" x2="6.01" y2="8"></line><line x1="10" y1="8" x2="10.01" y2="8"></line></svg>
                                            </div>
                                            <h6>Belum ada riwayat pengeluaran</h6>
                                            <p>Tidak ada data transaksi yang sesuai dengan filter periode ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($expenses->hasPages())
                    <div class="tr-pagination-wrapper">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-indigo: #4f46e5;
            --tr-indigo-light: #e0e7ff;
            --tr-indigo-hover: #4338ca;
            --tr-danger: #ef4444;
            --tr-danger-light: #fee2e2;
            --tr-warning: #f59e0b;
            --tr-warning-light: #fef3c7;
            --tr-success: #10b981;
            --tr-success-light: #dcfce7;
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-radius: 14px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; padding-bottom: 4rem; }
        .tr-page { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem; }
        .tr-eyebrow { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-danger); margin-bottom: 0.5rem; }
        .tr-title { font-size: 1.625rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 12px; letter-spacing: -0.02em; }
        .tr-title-icon-box { padding: 8px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .bg-danger { background: var(--tr-danger-light); color: var(--tr-danger); }
        .tr-subtitle { font-size: 0.9rem; color: var(--tr-text-muted); margin-top: 6px; }

        /* ── ALERTS ── */
        .tr-alert { padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.9rem; }
        .tr-alert-success { background: var(--tr-success-light); color: #065f46; border: 1px solid #a7f3d0; }
        .tr-alert-danger { background: var(--tr-danger-light); color: #991b1b; border: 1px solid #fecaca; }

        /* ── CARDS ── */
        .tr-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; margin-bottom: 1.5rem; }
        
        /* ── FILTER BAR ── */
        .tr-filter-card { padding: 1.25rem 1.5rem; }
        .tr-filter-grid { display: flex; gap: 1.25rem; align-items: flex-end; flex-wrap: wrap; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-flex-grow { flex-grow: 1; min-width: 200px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); letter-spacing: 0.05em; }
        .tr-input, .tr-select { padding: 0.625rem 0.85rem; border: 1px solid var(--tr-border); border-radius: 8px; font-size: 0.85rem; background: #f8fafc; transition: 0.2s; font-family: inherit; color: var(--tr-text-main); font-weight: 500; }
        .tr-input:focus, .tr-select:focus { border-color: var(--tr-indigo); outline: none; background: #fff; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        .tr-filter-actions { display: flex; gap: 0.5rem; }

        /* SELECT CUSTOM */
        .tr-select-wrapper { position: relative; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        .tr-select { appearance: none; padding-right: 2.5rem; cursor: pointer; width: 100%; }

        /* ── SUMMARY KPI ── */
        .tr-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem; }
        .tr-kpi-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border-left-width: 4px; }
        .tr-kpi-card.border-danger { border-left-color: var(--tr-danger); }
        .tr-kpi-card.border-indigo { border-left-color: var(--tr-indigo); }
        .tr-kpi-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .bg-danger-soft { background: var(--tr-danger-light); }
        .bg-indigo-soft { background: var(--tr-indigo-light); }
        .tr-kpi-label { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .tr-kpi-value { font-size: 1.5rem; font-weight: 900; line-height: 1; }

        /* ── TABLE ── */
        .table-responsive { width: 100%; overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; min-width: 900px; }
        .tr-table thead th { background: #f8fafc; padding: 1rem 1.25rem; text-align: left; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); border-bottom: 1px solid var(--tr-border); }
        .tr-table tbody td { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; vertical-align: middle; }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table tbody tr:hover { background: #fafafa; }
        .tr-table .c { text-align: center; }
        .tr-table .r { text-align: right; }

        .tr-date-box { font-weight: 600; color: var(--tr-text-main); }
        .tr-notes-text { color: var(--tr-text-muted); max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .tr-pic-name { font-size: 0.8rem; font-weight: 600; color: var(--tr-text-muted); background: #f1f5f9; padding: 4px 8px; border-radius: 6px; }

        /* ── BADGES ── */
        .tr-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.02em; }
        .tr-badge-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .tr-badge-warning { background: var(--tr-warning-light); color: #b45309; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0.625rem 1.25rem; border-radius: 10px; font-size: 0.875rem; font-weight: 700; cursor: pointer; transition: 0.2s; border: 1px solid transparent; text-decoration: none; }
        .tr-btn-primary { background: var(--tr-indigo); color: white; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }
        .tr-btn-primary:hover { background: var(--tr-indigo-hover); transform: translateY(-1px); }
        .tr-btn-dark { background: var(--tr-text-main); color: white; }
        .tr-btn-dark:hover { background: #000; }
        .tr-btn-outline { border-color: var(--tr-border); background: white; color: var(--tr-text-main); }
        .tr-btn-outline:hover { background: #f1f5f9; }

        .tr-actions-group { display: flex; gap: 6px; justify-content: center; }
        .tr-action-btn { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--tr-border); background: white; color: var(--tr-text-muted); transition: 0.2s; cursor: pointer; }
        .tr-action-btn.edit:hover { color: var(--tr-indigo); border-color: var(--tr-indigo); background: var(--tr-indigo-light); }
        .tr-action-btn.delete:hover { color: var(--tr-danger); border-color: var(--tr-danger); background: var(--tr-danger-light); }
        .tr-inline { display: inline; }

        /* ── UTILS ── */
        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
        .tr-font-bold { font-weight: 800; }
        .text-danger { color: var(--tr-danger); }
        .text-main { color: var(--tr-text-main); }
        .text-indigo { color: var(--tr-indigo); }
        .tr-text-light { color: #cbd5e1; }

        .tr-empty-state { padding: 4rem 2rem; text-align: center; }
        .tr-empty-icon { color: var(--tr-text-light); margin-bottom: 1rem; }
        .tr-empty-state h6 { font-size: 1.125rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--tr-text-main); }
        .tr-empty-state p { color: var(--tr-text-muted); font-size: 0.9rem; }
        .tr-pagination-wrapper { padding: 1.25rem 1.5rem; border-top: 1px solid #f1f5f9; }

        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: stretch; }
            .tr-btn { width: 100%; justify-content: center; }
            .tr-filter-grid { flex-direction: column; align-items: stretch; }
            .tr-filter-actions { display: grid; grid-template-columns: 1fr 1fr; }
        }
    </style>
    @endpush
</x-app-layout>