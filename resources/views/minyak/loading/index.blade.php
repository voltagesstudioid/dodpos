<x-app-layout>
    @push('styles')
    <style>
        .ld-page { max-width:80rem; margin:0 auto; padding:0 1rem; }

        /* Header */
        .ld-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .ld-hdr-l { display:flex; align-items:center; gap:1rem; }
        .ld-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#f59e0b,#ea580c);
            box-shadow:0 8px 24px rgba(234,88,12,0.3);
        }
        .ld-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .ld-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .ld-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s; border:none; cursor:pointer;
            background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff;
            box-shadow:0 6px 20px rgba(234,88,12,0.35);
        }
        .ld-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(234,88,12,0.45); }

        /* KPI Row */
        .ld-kpis { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; margin-bottom:1.5rem; }
        .ld-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .ld-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .ld-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .ld-kpi.amber::before  { background:linear-gradient(180deg,#f59e0b,#ea580c); }
        .ld-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .ld-kpi-top { display:flex; align-items:center; justify-content:space-between; }
        .ld-kpi-left { display:flex; flex-direction:column; gap:0.25rem; }
        .ld-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .ld-kpi-val { font-size:2.5rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
        .ld-kpi-val.amber  { color:#d97706; }
        .ld-kpi-val.green  { color:#059669; }
        .ld-kpi-unit { font-size:1rem; font-weight:600; color:#94a3b8; margin-left:0.25rem; }
        .ld-kpi-foot { font-size:0.72rem; color:#94a3b8; margin-top:0.375rem; }
        .ld-kpi-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem;
        }
        .ld-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .ld-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }

        /* Filter */
        .ld-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .ld-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .ld-ff-fld { min-width:160px; }
        .ld-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .ld-finput {
            padding:0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .ld-finput:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .ld-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .ld-fsel:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .ld-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff; box-shadow:0 4px 12px rgba(234,88,12,0.25);
        }
        .ld-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(234,88,12,0.35); }
        .ld-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .ld-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        /* Table */
        .ld-tbl {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .ld-tbl-head {
            background:linear-gradient(180deg,#fffbeb,#fef9e7); border-bottom:2px solid #fde68a;
        }
        .ld-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#92400e; white-space:nowrap;
        }
        .ld-tbl-body td {
            padding:0.9375rem 1.25rem; border-bottom:1px solid #fef9e7; font-size:0.8125rem;
            color:#374151; vertical-align:middle;
        }
        .ld-tbl-body tr { transition:background 0.15s; }
        .ld-tbl-body tr:last-child td { border-bottom:none; }
        .ld-tbl-body tr:hover td { background:linear-gradient(90deg,#fffdf8,#fffbeb); }

        /* Date Cell */
        .ld-date { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .ld-date-sub { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        /* Sales Cell */
        .ld-sales { display:flex; align-items:center; gap:0.75rem; }
        .ld-sales-av {
            width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:0.9375rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#fff7ed,#ffedd5); color:#c2410c; border:1.5px solid #fed7aa;
        }
        .ld-sales-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }

        /* Product */
        .ld-prod-name { font-size:0.8125rem; font-weight:500; color:#1e293b; }

        /* Volume Cells */
        .ld-vol { text-align:right; }
        .ld-vol-val { font-size:0.9375rem; font-weight:700; letter-spacing:-0.01em; }
        .ld-vol-unit { font-size:0.6875rem; font-weight:500; color:#94a3b8; margin-left:2px; }
        .ld-vol.loading .ld-vol-val { color:#2563eb; }
        .ld-vol.terjual .ld-vol-val { color:#059669; }
        .ld-vol.sisa .ld-vol-val { color:#0f172a; }
        .ld-vol-bar { height:4px; border-radius:99px; background:#e2e8f0; margin-top:5px; overflow:hidden; min-width:50px; }
        .ld-vol-bar-fill { height:100%; border-radius:99px; transition:width 0.4s ease; }

        /* Status Badge */
        .ld-status {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .ld-status.loading { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .ld-status.proses  { background:#fffbeb; color:#d97706; border-color:#fde68a; }
        .ld-status.selesai { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .ld-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .ld-status-dot.loading { background:#3b82f6; box-shadow:0 0 0 2px rgba(59,130,246,0.2); }
        .ld-status-dot.proses  { background:#f59e0b; box-shadow:0 0 0 2px rgba(245,158,11,0.2); animation:ld-pulse 1.5s infinite; }
        .ld-status-dot.selesai { background:#10b981; }
        @keyframes ld-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Actions */
        .ld-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.3125rem 0.625rem; border-radius:7px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
            background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe;
        }
        .ld-act:hover { background:#dbeafe; border-color:#93c5fd; }

        /* Empty */
        .ld-empty { text-align:center; padding:3.5rem 1.5rem; }
        .ld-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#fff7ed,#ffedd5); display:flex; align-items:center; justify-content:center;
        }
        .ld-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .ld-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .ld-empty-cta {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff; text-decoration:none;
            box-shadow:0 6px 20px rgba(234,88,12,0.25); transition:all 0.2s;
        }
        .ld-empty-cta:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(234,88,12,0.4); }

        @media(max-width:768px) { .ld-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px) { .ld-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="ld-page">

            {{-- Header --}}
            <div class="ld-hdr">
                <div class="ld-hdr-l">
                    <div class="ld-hdr-ico">🚛</div>
                    <div>
                        <div class="ld-hdr-title">Loading Harian</div>
                        <div class="ld-hdr-sub">Monitoring muatan BBM per sales per hari</div>
                    </div>
                </div>
                <a href="{{ route('minyak.loading.distribusi') }}" class="ld-hdr-btn" style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 6px 20px rgba(5,150,105,.3);margin-right:8px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Distribusi Stok
                </a>
                <a href="{{ route('minyak.loading.create') }}" class="ld-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Loading
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="ld-kpis">
                <div class="ld-kpi amber">
                    <div class="ld-kpi-top">
                        <div class="ld-kpi-left">
                            <span class="ld-kpi-lbl">Total Loading Hari Ini</span>
                            <div>
                                <span class="ld-kpi-val amber">{{ number_format($stats['total_hari_ini']) }}</span>
                                <span class="ld-kpi-unit">Liter</span>
                            </div>
                            <div class="ld-kpi-foot">Volume BBM dimuat hari ini</div>
                        </div>
                        <div class="ld-kpi-ico amber">⛽</div>
                    </div>
                </div>
                <div class="ld-kpi green">
                    <div class="ld-kpi-top">
                        <div class="ld-kpi-left">
                            <span class="ld-kpi-lbl">Sales Aktif Hari Ini</span>
                            <div>
                                <span class="ld-kpi-val green">{{ $stats['total_sales'] }}</span>
                                <span class="ld-kpi-unit">Sales</span>
                            </div>
                            <div class="ld-kpi-foot">Jumlah sales yang melakukan loading</div>
                        </div>
                        <div class="ld-kpi-ico green">👥</div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="ld-filter">
                <form method="GET" class="ld-ff">
                    <div class="ld-ff-fld">
                        <label class="ld-flbl">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="ld-finput">
                    </div>
                    <div>
                        <label class="ld-flbl">Sales</label>
                        <select name="sales_id" class="ld-fsel">
                            <option value="">Semua Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="ld-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('minyak.loading.index') }}" class="ld-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- Table --}}
            <div class="ld-tbl">
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:separate; border-spacing:0;">
                        <thead class="ld-tbl-head">
                            <tr>
                                <th style="text-align:left;">Tanggal</th>
                                <th style="text-align:left;">Sales</th>
                                <th style="text-align:left;">Produk</th>
                                <th style="text-align:right;">Loading</th>
                                <th style="text-align:right;">Terjual</th>
                                <th style="text-align:right;">Sisa</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="ld-tbl-body">
                            @forelse($loadings as $l)
                                @php
                                    $terjualPct = $l->jumlah_loading > 0 ? round(($l->terjual / $l->jumlah_loading) * 100) : 0;
                                    $sisaPct = $l->jumlah_loading > 0 ? round(($l->sisa_stok / $l->jumlah_loading) * 100) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="ld-date">{{ $l->tanggal->format('d M Y') }}</div>
                                        <div class="ld-date-sub">{{ $l->tanggal->isoFormat('dddd') }}</div>
                                    </td>
                                    <td>
                                        <div class="ld-sales">
                                            <div class="ld-sales-av">{{ substr($l->sales->nama, 0, 1) }}</div>
                                            <div class="ld-sales-name">{{ $l->sales->nama }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ld-prod-name">{{ $l->produk->nama }}</div>
                                    </td>
                                    <td>
                                        <div class="ld-vol loading">
                                            <span class="ld-vol-val">{{ number_format($l->jumlah_loading) }}</span>
                                            <span class="ld-vol-unit">L</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ld-vol terjual">
                                            <span class="ld-vol-val">{{ number_format($l->terjual) }}</span>
                                            <span class="ld-vol-unit">L</span>
                                        </div>
                                        <div class="ld-vol-bar">
                                            <div class="ld-vol-bar-fill" style="width:{{ $terjualPct }}%; background:linear-gradient(90deg,#10b981,#059669);"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ld-vol sisa">
                                            <span class="ld-vol-val">{{ number_format($l->sisa_stok) }}</span>
                                            <span class="ld-vol-unit">L</span>
                                        </div>
                                        @if($l->jumlah_loading > 0)
                                            <div class="ld-vol-bar">
                                                <div class="ld-vol-bar-fill" style="width:{{ $sisaPct }}%; background:{{ $sisaPct > 50 ? 'linear-gradient(90deg,#f59e0b,#d97706)' : 'linear-gradient(90deg,#ef4444,#dc2626)' }};"></div>
                                            </div>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="ld-status {{ $l->status }}">
                                            <span class="ld-status-dot {{ $l->status }}"></span>
                                            {{ ucfirst($l->status) }}
                                        </span>
                                    </td>
                                    <td style="text-align:center;">
                                        <a href="{{ route('minyak.loading.show', $l) }}" class="ld-act">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="ld-empty">
                                            <div class="ld-empty-ico">
                                                <svg width="32" height="32" fill="none" stroke="#c2410c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                            </div>
                                            <div class="ld-empty-title">Belum Ada Data Loading</div>
                                            <div class="ld-empty-sub">Tambahkan loading harian untuk mulai memantau distribusi BBM</div>
                                            <a href="{{ route('minyak.loading.create') }}" class="ld-empty-cta">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Tambah Loading Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($loadings->hasPages())
                    <div style="padding:0.875rem 1.25rem; border-top:1px solid #fef9e7;">
                        {{ $loadings->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
