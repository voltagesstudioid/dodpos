<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .ht-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .ht-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .ht-hdr-l { display:flex; align-items:center; gap:1rem; }
        .ht-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#ef4444,#dc2626);
            box-shadow:0 8px 24px rgba(220,38,38,0.3);
        }
        .ht-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .ht-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        /* KPI Row */
        .ht-kpis { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
        .ht-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .ht-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .ht-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .ht-kpi.red::before    { background:linear-gradient(180deg,#ef4444,#dc2626); }
        .ht-kpi.orange::before { background:linear-gradient(180deg,#f97316,#ea580c); }
        .ht-kpi.pink::before   { background:linear-gradient(180deg,#ec4899,#db2777); }
        .ht-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .ht-kpi-top { display:flex; align-items:center; justify-content:space-between; }
        .ht-kpi-left { display:flex; flex-direction:column; gap:0.25rem; }
        .ht-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .ht-kpi-val { font-size:2.5rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
        .ht-kpi-val.red    { color:#dc2626; }
        .ht-kpi-val.orange { color:#ea580c; }
        .ht-kpi-val.pink   { color:#db2777; }
        .ht-kpi-val.green  { color:#059669; }
        .ht-kpi-foot { font-size:0.72rem; color:#94a3b8; margin-top:0.375rem; }
        .ht-kpi-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
        .ht-kpi-ico.red    { background:linear-gradient(135deg,#fef2f2,#fee2e2); }
        .ht-kpi-ico.orange { background:linear-gradient(135deg,#fff7ed,#ffedd5); }
        .ht-kpi-ico.pink   { background:linear-gradient(135deg,#fdf2f8,#fce7f3); }
        .ht-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }

        /* Filter */
        .ht-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .ht-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .ht-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .ht-finput {
            width:100%; padding:0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .ht-finput:focus { border-color:#ef4444; box-shadow:0 0 0 3px rgba(239,68,68,0.12); }
        .ht-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .ht-fsel:focus { border-color:#ef4444; box-shadow:0 0 0 3px rgba(239,68,68,0.12); }
        .ht-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; box-shadow:0 4px 12px rgba(220,38,38,0.25);
        }
        .ht-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(220,38,38,0.35); }
        .ht-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .ht-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        /* Table */
        .ht-tbl {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .ht-tbl-head { background:linear-gradient(180deg,#fef2f2,#fff1f2); border-bottom:2px solid #fecaca; }
        .ht-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#991b1b; white-space:nowrap;
        }
        .ht-tbl-body td { padding:0.9375rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .ht-tbl-body tr { transition:background 0.15s; }
        .ht-tbl-body tr:last-child td { border-bottom:none; }
        .ht-tbl-body tr:hover td { background:linear-gradient(90deg,#fff5f5,#fef2f2); }

        /* Customer cell */
        .ht-cust { display:flex; align-items:center; gap:0.75rem; }
        .ht-cust-av {
            width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center;
            font-size:1rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 4px 12px rgba(37,99,235,0.2);
        }
        .ht-cust-info { display:flex; flex-direction:column; gap:0.125rem; }
        .ht-cust-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .ht-cust-owner { font-size:0.6875rem; color:#94a3b8; }

        /* Faktur cell */
        .ht-faktur {
            display:inline-flex; align-items:center; gap:0.375rem;
            font-family:'JetBrains Mono',monospace; font-size:0.75rem; font-weight:600; color:#475569;
            background:#f8fafc; padding:0.375rem 0.625rem; border-radius:8px; border:1px solid #e2e8f0;
        }
        .ht-faktur-lbl { color:#94a3b8; }

        /* Jatuh Tempo cell */
        .ht-tempo { display:flex; flex-direction:column; gap:0.125rem; }
        .ht-tempo-date { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .ht-tempo-overdue { font-size:0.6875rem; font-weight:600; color:#dc2626; display:flex; align-items:center; gap:0.25rem; }

        /* Money cells */
        .ht-money { font-family:'JetBrains Mono',monospace; font-size:0.8125rem; }
        .ht-money.debt { color:#64748b; }
        .ht-money.paid { color:#059669; font-weight:600; }
        .ht-money.remaining { color:#dc2626; font-weight:700; }

        /* Status badge */
        .ht-status {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .ht-status.lunas { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .ht-status.belum { background:#fffbeb; color:#d97706; border-color:#fde68a; }
        .ht-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .ht-status-dot.lunas { background:#10b981; }
        .ht-status-dot.belum { background:#f59e0b; box-shadow:0 0 0 2px rgba(245,158,11,0.2); animation:ht-pulse 1.5s infinite; }
        @keyframes ht-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Actions */
        .ht-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.375rem 0.625rem; border-radius:8px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
        }
        .ht-act.detail { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .ht-act.detail:hover { background:#dbeafe; border-color:#93c5fd; }

        /* Empty */
        .ht-empty { text-align:center; padding:3.5rem 1.5rem; }
        .ht-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#fef2f2,#fee2e2); display:flex; align-items:center; justify-content:center;
        }
        .ht-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .ht-empty-sub { font-size:0.8125rem; color:#94a3b8; }

        @media(max-width:1024px) { .ht-kpis { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:768px)  { .ht-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px)  { .ht-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="ht-page">

            {{-- Header --}}
            <div class="ht-hdr">
                <div class="ht-hdr-l">
                    <div class="ht-hdr-ico">
                        <svg width="24" height="24" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="ht-hdr-title">Hutang Pelanggan</div>
                        <div class="ht-hdr-sub">Monitoring piutang dan pembayaran pelanggan</div>
                    </div>
                </div>
            </div>

            {{-- KPI Cards --}}
            <div class="ht-kpis">
                <div class="ht-kpi red">
                    <div class="ht-kpi-top">
                        <div class="ht-kpi-left">
                            <span class="ht-kpi-lbl">Total Hutang</span>
                            <div>
                                <span class="ht-kpi-val red">Rp {{ number_format($stats['total_hutang'], 0, ',', '.') }}</span>
                            </div>
                            <div class="ht-kpi-foot">Sisa piutang belum lunas</div>
                        </div>
                        <div class="ht-kpi-ico red">
                            <svg width="24" height="24" fill="none" stroke="#dc2626" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="ht-kpi orange">
                    <div class="ht-kpi-top">
                        <div class="ht-kpi-left">
                            <span class="ht-kpi-lbl">Belum Lunas</span>
                            <div>
                                <span class="ht-kpi-val orange">{{ $stats['belum_lunas'] }}</span>
                            </div>
                            <div class="ht-kpi-foot">Tagihan masih berjalan</div>
                        </div>
                        <div class="ht-kpi-ico orange">
                            <svg width="24" height="24" fill="none" stroke="#ea580c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="ht-kpi pink">
                    <div class="ht-kpi-top">
                        <div class="ht-kpi-left">
                            <span class="ht-kpi-lbl">Overdue</span>
                            <div>
                                <span class="ht-kpi-val pink">{{ $stats['overdue'] }}</span>
                            </div>
                            <div class="ht-kpi-foot">Melewati jatuh tempo</div>
                        </div>
                        <div class="ht-kpi-ico pink">
                            <svg width="24" height="24" fill="none" stroke="#db2777" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="ht-kpi green">
                    <div class="ht-kpi-top">
                        <div class="ht-kpi-left">
                            <span class="ht-kpi-lbl">Lunas</span>
                            <div>
                                <span class="ht-kpi-val green">{{ $stats['lunas'] }}</span>
                            </div>
                            <div class="ht-kpi-foot">Tagihan sudah dibayar</div>
                        </div>
                        <div class="ht-kpi-ico green">
                            <svg width="24" height="24" fill="none" stroke="#059669" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="ht-filter">
                <form method="GET" class="ht-ff">
                    <div>
                        <label class="ht-flbl">Cari Pelanggan</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama toko atau pemilik..." class="ht-finput">
                    </div>
                    <div>
                        <label class="ht-flbl">Pelanggan</label>
                        <select name="pelanggan_id" class="ht-fsel">
                            <option value="">Semua Pelanggan</option>
                            @foreach($pelanggans as $p)
                                <option value="{{ $p->id }}" {{ request('pelanggan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_toko }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="ht-flbl">Status</label>
                        <select name="status" class="ht-fsel">
                            <option value="">Semua Status</option>
                            <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                    <button type="submit" class="ht-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('mineral.hutang.index') }}" class="ht-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- Table --}}
            <div class="ht-tbl">
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:separate; border-spacing:0;">
                        <thead class="ht-tbl-head">
                            <tr>
                                <th style="text-align:left;">Pelanggan</th>
                                <th style="text-align:left;">No Faktur</th>
                                <th style="text-align:left;">Jatuh Tempo</th>
                                <th style="text-align:right;">Total Hutang</th>
                                <th style="text-align:right;">Dibayar</th>
                                <th style="text-align:right;">Sisa</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="ht-tbl-body">
                            @forelse($hutangs as $h)
                            @php
                                $isOverdue = $h->jatuh_tempo < now() && $h->status == 'belum_lunas';
                            @endphp
                            <tr>
                                <td>
                                    <div class="ht-cust">
                                        <div class="ht-cust-av">{{ substr($h->pelanggan->nama_toko ?? 'U', 0, 1) }}</div>
                                        <div class="ht-cust-info">
                                            <span class="ht-cust-name">{{ $h->pelanggan->nama_toko }}</span>
                                            <span class="ht-cust-owner">{{ $h->pelanggan->nama_pemilik ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ht-faktur">
                                        <span class="ht-faktur-lbl">F</span>
                                        {{ $h->penjualan->no_faktur ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="ht-tempo">
                                        <span class="ht-tempo-date">{{ $h->jatuh_tempo->format('d M Y') }}</span>
                                        @if($isOverdue)
                                            <span class="ht-tempo-overdue">
                                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                Overdue
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td style="text-align:right;">
                                    <span class="ht-money debt">Rp {{ number_format($h->total_hutang, 0, ',', '.') }}</span>
                                </td>
                                <td style="text-align:right;">
                                    <span class="ht-money paid">Rp {{ number_format($h->dibayar, 0, ',', '.') }}</span>
                                </td>
                                <td style="text-align:right;">
                                    <span class="ht-money remaining">Rp {{ number_format($h->sisa, 0, ',', '.') }}</span>
                                </td>
                                <td style="text-align:center;">
                                    @php
                                        $isOverdue = $h->status === 'overdue' || ($h->status === 'belum_lunas' && $h->jatuh_tempo && $h->jatuh_tempo->isPast());
                                    @endphp
                                    @if($h->status === 'lunas')
                                        <span class="ht-status lunas">
                                            <span class="ht-status-dot lunas"></span>
                                            Lunas
                                        </span>
                                    @elseif($isOverdue)
                                        <span class="ht-status" style="background:#fef2f2;color:#991b1b;border-color:#fecaca;">
                                            <span class="ht-status-dot" style="background:#ef4444;"></span>
                                            Overdue
                                        </span>
                                    @else
                                        <span class="ht-status belum">
                                            <span class="ht-status-dot belum"></span>
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <a href="{{ route('mineral.hutang.show', $h) }}" class="ht-act detail">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="ht-empty">
                                        <div class="ht-empty-ico">
                                            <svg width="32" height="32" fill="none" stroke="#dc2626" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="ht-empty-title">Tidak Ada Data Hutang</div>
                                        <div class="ht-empty-sub">Belum ada piutang pelanggan tercatat</div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($hutangs->hasPages())
                    <div style="padding:0.875rem 1.25rem; border-top:1px solid #f1f5f9;">
                        {{ $hutangs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
