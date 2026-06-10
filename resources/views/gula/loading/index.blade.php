<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&display=swap');
        .ld-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .ld-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.75rem; padding-bottom:1.25rem; border-bottom:2px solid #fef3c7; }
        .ld-hdr-left { display:flex; align-items:center; gap:1rem; }
        .ld-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#f59e0b,#d97706);
            box-shadow:0 8px 24px rgba(245,158,11,0.3);
        }
        .ld-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .ld-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .ld-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; border-radius:12px;
            font-size:0.8125rem; font-weight:700; text-decoration:none; transition:all 0.25s;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
            box-shadow:0 4px 14px rgba(245,158,11,0.35); border:none; cursor:pointer;
        }
        .ld-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(245,158,11,0.45); }

        /* KPI Cards */
        .ld-kpis { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; margin-bottom:1.5rem; }
        .ld-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .ld-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .ld-kpi:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,0.08); border-color:transparent; }
        .ld-kpi.amber::before  { background:linear-gradient(180deg,#f59e0b,#d97706); }
        .ld-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .ld-kpi-top { display:flex; align-items:flex-start; justify-content:space-between; }
        .ld-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
        .ld-kpi-val { font-size:2.25rem; font-weight:800; letter-spacing:-0.03em; line-height:1.1; margin-top:0.5rem; }
        .ld-kpi-val.amber  { color:#b45309; }
        .ld-kpi-val.green  { color:#059669; }
        .ld-kpi-val .unit { font-size:1rem; font-weight:600; color:#94a3b8; margin-left:2px; }
        .ld-kpi-ico {
            width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            font-size:1.375rem; flex-shrink:0;
        }
        .ld-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .ld-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }

        /* Filter */
        .ld-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.5rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .ld-filter-form { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .ld-filter-label { display:block; font-size:0.75rem; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem; }
        .ld-filter-date {
            border:1px solid #e2e8f0; border-radius:10px; padding:0.625rem 0.875rem;
            font-size:0.875rem; font-family:inherit; color:#1e293b; background:#f8fafc; transition:all 0.2s;
        }
        .ld-filter-date:focus { outline:none; border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
        .ld-filter-select {
            border:1px solid #e2e8f0; border-radius:10px; padding:0.625rem 0.875rem;
            font-size:0.875rem; font-family:inherit; color:#1e293b; background:#f8fafc; transition:all 0.2s;
        }
        .ld-filter-select:focus { outline:none; border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
        .ld-filter-btn {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:700;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
        }
        .ld-filter-btn.primary { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 4px 12px rgba(245,158,11,0.25); }
        .ld-filter-btn.primary:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(245,158,11,0.35); }
        .ld-filter-btn.ghost { background:#f8fafc; color:#64748b; border:1px solid #e2e8f0; }
        .ld-filter-btn.ghost:hover { background:#f1f5f9; }

        /* Table */
        .ld-table-wrap { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
        .ld-table-scroll { overflow-x:auto; }
        .ld-table { width:100%; border-collapse:collapse; }
        .ld-table thead th {
            background:linear-gradient(180deg,#fffbeb,#fef9ee); border-bottom:2px solid #fde68a;
            padding:0.875rem 1.25rem; font-size:0.6875rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.06em; color:#92400e;
        }
        .ld-table thead th.left { text-align:left; }
        .ld-table thead th.center { text-align:center; }
        .ld-table thead th.right { text-align:right; }
        .ld-table tbody td { padding:1rem 1.25rem; border-bottom:1px solid #f8fafc; vertical-align:middle; }
        .ld-table tbody tr { transition:background 0.15s; }
        .ld-table tbody tr:hover { background:#fffdf7; }
        .ld-table tbody tr:last-child td { border-bottom:none; }

        /* Cell styles */
        .ld-date { font-size:0.8125rem; color:#64748b; }
        .ld-sales-cell { display:flex; align-items:center; gap:0.625rem; }
        .ld-sales-av {
            width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:0.875rem; font-weight:800; flex-shrink:0;
            background:linear-gradient(135deg,#fffbeb,#fef3c7); color:#b45309; border:1.5px solid #fde68a;
        }
        .ld-sales-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .ld-produk { font-size:0.8125rem; color:#1e293b; font-weight:500; }
        .ld-num { font-family:'JetBrains Mono',monospace; font-size:0.875rem; font-weight:700; }
        .ld-num.loading { color:#b45309; }
        .ld-num.sold { color:#059669; }
        .ld-num.rest { color:#475569; }

        /* Status badges */
        .ld-status {
            display:inline-flex; align-items:center; gap:0.375rem;
            padding:0.375rem 0.75rem; border-radius:99px; font-size:0.75rem; font-weight:700;
        }
        .ld-status-dot { width:8px; height:8px; border-radius:50%; }
        .ld-status.loading { background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; }
        .ld-status.loading .ld-status-dot { background:#3b82f6; box-shadow:0 0 0 2px rgba(59,130,246,0.2); animation:ld-pulse 1.5s infinite; }
        .ld-status.proses { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
        .ld-status.proses .ld-status-dot { background:#f59e0b; box-shadow:0 0 0 2px rgba(245,158,11,0.2); animation:ld-pulse 1.5s infinite; }
        .ld-status.selesai { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .ld-status.selesai .ld-status-dot { background:#10b981; }
        @keyframes ld-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Action link */
        .ld-act-link {
            display:inline-flex; align-items:center; gap:0.25rem;
            font-size:0.8125rem; font-weight:600; color:#2563eb; text-decoration:none; transition:color 0.2s;
        }
        .ld-act-link:hover { color:#1d4ed8; }

        /* Empty state */
        .ld-empty { text-align:center; padding:3.5rem 1rem; }
        .ld-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#fffbeb,#fef3c7); display:flex; align-items:center; justify-content:center;
        }
        .ld-empty-title { font-size:1rem; font-weight:700; color:#475569; }
        .ld-empty-sub { font-size:0.875rem; color:#94a3b8; margin-top:0.25rem; }
        .ld-empty-btn {
            display:inline-flex; align-items:center; gap:0.5rem; margin-top:1rem;
            padding:0.625rem 1.25rem; border-radius:10px;
            font-size:0.8125rem; font-weight:700; text-decoration:none;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
            box-shadow:0 4px 12px rgba(245,158,11,0.25); transition:all 0.25s;
        }
        .ld-empty-btn:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(245,158,11,0.35); }

        /* Pagination */
        .ld-pagination { padding:1rem 1.25rem; border-top:1px solid #f1f5f9; }

        @media(max-width:1024px) { .ld-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px) { .ld-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="ld-page">

            {{-- Header --}}
            <div class="ld-hdr">
                <div class="ld-hdr-left">
                    <div class="ld-hdr-ico">📦</div>
                    <div>
                        <div class="ld-hdr-title">Loading Harian</div>
                        <div class="ld-hdr-sub">Distribusi gula ke kendaraan sales</div>
                    </div>
                </div>
                <a href="{{ route('gula.loading.create') }}" class="ld-hdr-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Loading
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="ld-kpis">
                <div class="ld-kpi amber">
                    <div class="ld-kpi-top">
                        <div>
                            <div class="ld-kpi-lbl">Total Loading Hari Ini</div>
                            <div class="ld-kpi-val amber">{{ number_format($stats['total_hari_ini']) }}</div>
                        </div>
                        <div class="ld-kpi-ico amber">📦</div>
                    </div>
                </div>
                <div class="ld-kpi green">
                    <div class="ld-kpi-top">
                        <div>
                            <div class="ld-kpi-lbl">Sales Aktif Hari Ini</div>
                            <div class="ld-kpi-val green">{{ $stats['total_sales'] }}</div>
                        </div>
                        <div class="ld-kpi-ico green">👥</div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="ld-filter">
                <form method="GET" class="ld-filter-form">
                    <div>
                        <label class="ld-filter-label">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="ld-filter-date">
                    </div>
                    <div>
                        <label class="ld-filter-label">Sales</label>
                        <select name="sales_id" class="ld-filter-select">
                            <option value="">Semua Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="ld-filter-btn primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('gula.loading.index') }}" class="ld-filter-btn ghost">Reset</a>
                </form>
            </div>

            {{-- Table --}}
            <div class="ld-table-wrap">
                <div class="ld-table-scroll">
                    <table class="ld-table">
                        <thead>
                            <tr>
                                <th class="left">Tanggal</th>
                                <th class="left">Sales</th>
                                <th class="left">Produk</th>
                                <th class="right">Loading</th>
                                <th class="right">Terjual</th>
                                <th class="right">Sisa</th>
                                <th class="center">Status</th>
                                <th class="center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loadings as $l)
                                <tr>
                                    <td>
                                        <span class="ld-date">{{ $l->tanggal->format('d M Y') }}</span>
                                    </td>
                                    <td>
                                        <div class="ld-sales-cell">
                                            <div class="ld-sales-av">{{ substr($l->sales->nama, 0, 1) }}</div>
                                            <span class="ld-sales-name">{{ $l->sales->nama }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="ld-produk">{{ $l->produk->nama }}</span>
                                    </td>
                                    <td style="text-align:right;">
                                        <span class="ld-num loading">{{ number_format($l->jumlah_loading) }} {{ $l->produk->satuan ?? 'Dus' }}</span>
                                    </td>
                                    <td style="text-align:right;">
                                        <span class="ld-num sold">{{ number_format($l->terjual) }} {{ $l->produk->satuan ?? 'Dus' }}</span>
                                    </td>
                                    <td style="text-align:right;">
                                        <span class="ld-num rest">{{ number_format($l->sisa_stok) }} {{ $l->produk->satuan ?? 'Dus' }}</span>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="ld-status {{ $l->status }}">
                                            <span class="ld-status-dot"></span>
                                            {{ ucfirst($l->status) }}
                                        </span>
                                    </td>
                                    <td style="text-align:center;">
                                        <a href="{{ route('gula.loading.show', $l) }}" class="ld-act-link">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="ld-empty">
                                            <div class="ld-empty-ico">
                                                <svg width="32" height="32" fill="none" stroke="#b45309" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                            </div>
                                            <div class="ld-empty-title">Tidak ada data loading</div>
                                            <div class="ld-empty-sub">Tambah data loading untuk memulai</div>
                                            <a href="{{ route('gula.loading.create') }}" class="ld-empty-btn">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Tambah Loading
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($loadings->hasPages())
                    <div class="ld-pagination">
                        {{ $loadings->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
