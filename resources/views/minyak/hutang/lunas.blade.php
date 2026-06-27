<x-app-layout>
    @push('styles')
    <style>
        .ht-page { max-width:80rem; margin:0 auto; padding:0 1rem; }

        /* Header */
        .ht-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; }
        .ht-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#ef4444,#dc2626);
            box-shadow:0 8px 24px rgba(220,38,38,0.3);
        }
        .ht-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .ht-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        /* KPI Row */
        .ht-kpis { display:grid; grid-template-columns:repeat(4,1fr); gap:0.875rem; margin-bottom:1.5rem; }
        .ht-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.25rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .ht-kpi::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .ht-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .ht-kpi.red::before    { background:linear-gradient(90deg,#ef4444,#dc2626); }
        .ht-kpi.orange::before { background:linear-gradient(90deg,#f59e0b,#d97706); }
        .ht-kpi.pink::before   { background:linear-gradient(90deg,#ec4899,#db2777); }
        .ht-kpi.green::before  { background:linear-gradient(90deg,#10b981,#059669); }
        .ht-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem; }
        .ht-kpi-lbl { font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .ht-kpi-ico {
            width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.15rem;
        }
        .ht-kpi-ico.red    { background:linear-gradient(135deg,#fff1f2,#ffe4e6); }
        .ht-kpi-ico.orange { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .ht-kpi-ico.pink   { background:linear-gradient(135deg,#fdf2f8,#fce7f3); }
        .ht-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .ht-kpi-val { font-size:1.5rem; font-weight:800; letter-spacing:-0.02em; line-height:1; }
        .ht-kpi-val.red    { color:#dc2626; }
        .ht-kpi-val.orange { color:#d97706; }
        .ht-kpi-val.pink   { color:#db2777; }
        .ht-kpi-val.green  { color:#059669; }
        .ht-kpi-val-sm { font-size:1.15rem; }
        .ht-kpi-foot { font-size:0.7rem; color:#94a3b8; margin-top:0.25rem; }

        /* Filter */
        .ht-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .ht-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .ht-ff-fld { flex:1; min-width:160px; }
        .ht-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .ht-fwrap { position:relative; }
        .ht-fico { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#94a3b8; pointer-events:none; }
        .ht-finput {
            width:100%; padding:0.625rem 1rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
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
        .ht-tbl-head {
            background:linear-gradient(180deg,#fff1f2,#fef2f2); border-bottom:2px solid #fecdd3;
        }
        .ht-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#9f1239; white-space:nowrap;
        }
        .ht-tbl-body td {
            padding:0.9375rem 1.25rem; border-bottom:1px solid #fef2f2; font-size:0.8125rem;
            color:#374151; vertical-align:middle;
        }
        .ht-tbl-body tr { transition:background 0.15s; }
        .ht-tbl-body tr:last-child td { border-bottom:none; }
        .ht-tbl-body tr:hover td { background:linear-gradient(90deg,#fffafa,#fff5f5); }

        /* Pelanggan */
        .ht-pel { display:flex; align-items:center; gap:0.75rem; }
        .ht-pel-av {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:0.875rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#fff1f2,#ffe4e6); color:#be123c; border:1.5px solid #fecdd3;
        }
        .ht-pel-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }

        /* Faktur */
        .ht-faktur {
            display:inline-flex; padding:0.2rem 0.5rem; border-radius:6px; font-size:0.6875rem; font-weight:700;
            background:#f8fafc; color:#475569; letter-spacing:0.02em; font-family:monospace; border:1px solid #e2e8f0;
        }

        /* Jatuh Tempo */
        .ht-tempo { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .ht-tempo.overdue { color:#dc2626; }
        .ht-overdue-badge {
            display:inline-flex; align-items:center; gap:0.2rem;
            padding:0.15rem 0.4rem; border-radius:5px; font-size:0.625rem; font-weight:700;
            background:#fff1f2; color:#dc2626; border:1px solid #fecdd3; margin-left:0.375rem;
        }
        .ht-tempo-sub { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        /* Money */
        .ht-money { text-align:right; font-weight:700; letter-spacing:-0.01em; }
        .ht-money.total { color:#0f172a; font-size:0.875rem; }
        .ht-money.paid  { color:#059669; }
        .ht-money.sisa  { color:#dc2626; }
        .ht-money.clean { color:#059669; }

        /* Payment Progress */
        .ht-pay-bar { height:4px; border-radius:99px; background:#e2e8f0; margin-top:5px; overflow:hidden; min-width:50px; }
        .ht-pay-bar-fill { height:100%; border-radius:99px; transition:width 0.4s ease; }

        /* Status */
        .ht-status {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .ht-status.belum_lunas { background:#fffbeb; color:#d97706; border-color:#fde68a; }
        .ht-status.lunas { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .ht-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .ht-status-dot.belum_lunas { background:#f59e0b; animation:ht-pulse 1.5s infinite; }
        .ht-status-dot.lunas { background:#10b981; }
        @keyframes ht-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Actions */
        .ht-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.3125rem 0.625rem; border-radius:7px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
            background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe;
        }
        .ht-act:hover { background:#dbeafe; border-color:#93c5fd; }

        /* Empty */
        .ht-empty { text-align:center; padding:3.5rem 1.5rem; }
        .ht-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#fff1f2,#ffe4e6); display:flex; align-items:center; justify-content:center;
        }
        .ht-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .ht-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .ht-empty-hint {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.5rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:500;
            background:#f0fdf4; color:#065f46; border:1px solid #bbf7d0;
        }

        @media(max-width:1024px) { .ht-kpis { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:640px) { .ht-kpis { grid-template-columns:1fr; } .ht-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="ht-page">

            {{-- Header --}}
            <div class="ht-hdr">
                <div class="ht-hdr-ico">✅</div>
                <div>
                    <div class="ht-hdr-title">Riwayat Hutang Lunas</div>
                    <div class="ht-hdr-sub">Arsip dan riwayat pembayaran pelanggan yang sudah lunas</div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="ht-filter">
                <form method="GET" action="{{ route('minyak.hutang.lunas') }}" class="ht-ff">
                    <div class="ht-ff-fld">
                        <label class="ht-flbl">Pencarian</label>
                        <div class="ht-fwrap">
                            <svg class="ht-fico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama toko, pemilik..." class="ht-finput">
                        </div>
                    </div>
                    <div>
                        <label class="ht-flbl">Pelanggan</label>
                        <select name="pelanggan_id" class="ht-fsel">
                            <option value="">Semua Pelanggan</option>
                            @foreach($pelanggans as $p)
                                <option value="{{ $p->id }}" {{ request('pelanggan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_toko }}</option>
                            @endforeach
                        </select>
                    <div style="display:flex;align-items:flex-end;gap:0.5rem;flex:1;">
                        <button type="submit" class="ht-fbtn ht-fbtn-primary" style="padding:0.45rem 1rem;">Cari</button>
                        @if(request()->anyFilled(['search','pelanggan_id']))
                        <a href="{{ route('minyak.hutang.lunas') }}" class="ht-fbtn ht-fbtn-ghost" style="padding:0.45rem 1rem;">Reset</a>
                        @endif
                    </div>
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
                                    $payPct = $h->total_hutang > 0 ? round(($h->dibayar / $h->total_hutang) * 100) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="ht-pel">
                                            <div class="ht-pel-av">{{ substr($h->pelanggan->nama_toko, 0, 1) }}</div>
                                            <div class="ht-pel-name">{{ $h->pelanggan->nama_toko }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="ht-faktur">{{ $h->penjualan->no_faktur ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="ht-tempo {{ $isOverdue ? 'overdue' : '' }}">
                                            {{ $h->jatuh_tempo->format('d M Y') }}
                                            @if($isOverdue)
                                                <span class="ht-overdue-badge">
                                                    <svg width="8" height="8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01"/></svg>
                                                    Overdue
                                                </span>
                                            @endif
                                        </div>
                                        <div class="ht-tempo-sub">{{ $h->jatuh_tempo->isoFormat('dddd') }}</div>
                                    </td>
                                    <td>
                                        <div class="ht-money total">Rp {{ number_format($h->total_hutang, 0, ',', '.') }}</div>
                                    </td>
                                    <td>
                                        <div class="ht-money paid">Rp {{ number_format($h->dibayar, 0, ',', '.') }}</div>
                                        <div class="ht-pay-bar">
                                            <div class="ht-pay-bar-fill" style="width:{{ $payPct }}%; background:linear-gradient(90deg,#10b981,#059669);"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ht-money {{ $h->sisa > 0 ? 'sisa' : 'clean' }}">Rp {{ number_format($h->sisa, 0, ',', '.') }}</div>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="ht-status {{ $h->status }}">
                                            <span class="ht-status-dot {{ $h->status }}"></span>
                                            {{ $h->status == 'belum_lunas' ? 'Belum Lunas' : 'Lunas' }}
                                        </span>
                                    </td>
                                    <td style="text-align:center;">
                                        <a href="{{ route('minyak.hutang.show', $h) }}" class="ht-act">
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
                                                <svg width="32" height="32" fill="none" stroke="#be123c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <div class="ht-empty-title">Belum Ada Data Hutang</div>
                                            <div class="ht-empty-sub">Data hutang akan muncul setelah ada transaksi dengan tipe bayar hutang</div>
                                            <div class="ht-empty-hint">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Buat penjualan dengan tipe bayar "Hutang" untuk memulai
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($hutangs->hasPages())
                    <div style="padding:0.875rem 1.25rem; border-top:1px solid #fef2f2;">
                        {{ $hutangs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
