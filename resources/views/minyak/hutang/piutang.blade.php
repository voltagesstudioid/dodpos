<x-app-layout>
    @push('styles')
    <style>
        .pp { max-width:80rem; margin:0 auto; padding:1.25rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .pp-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .pp-hdr-l { display:flex; align-items:center; gap:1rem; }
        .pp-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#ef4444,#dc2626);
            box-shadow:0 8px 24px rgba(220,38,38,0.3);
        }
        .pp-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .pp-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        .pp-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:0.875rem; margin-bottom:1.5rem; }
        .pp-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.25rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .pp-kpi::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .pp-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .pp-kpi.red::before { background:linear-gradient(90deg,#ef4444,#dc2626); }
        .pp-kpi.amber::before { background:linear-gradient(90deg,#f59e0b,#d97706); }
        .pp-kpi.pink::before { background:linear-gradient(90deg,#ec4899,#db2777); }
        .pp-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem; }
        .pp-kpi-lbl { font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .pp-kpi-ico { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; }
        .pp-kpi-ico.red { background:linear-gradient(135deg,#fff1f2,#ffe4e6); }
        .pp-kpi-ico.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .pp-kpi-ico.pink { background:linear-gradient(135deg,#fdf2f8,#fce7f3); }
        .pp-kpi-val { font-size:1.375rem; font-weight:800; letter-spacing:-0.02em; line-height:1; }
        .pp-kpi-val.red { color:#dc2626; }
        .pp-kpi-val.amber { color:#d97706; }
        .pp-kpi-val.pink { color:#db2777; }
        .pp-kpi-val.sm { font-size:1.125rem; }
        .pp-kpi-foot { font-size:0.7rem; color:#94a3b8; margin-top:0.25rem; }

        .pp-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .pp-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .pp-ff-fld { flex:1; min-width:160px; }
        .pp-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .pp-fwrap { position:relative; }
        .pp-fico { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#94a3b8; pointer-events:none; }
        .pp-finput {
            width:100%; padding:0.625rem 1rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit; box-sizing:border-box;
        }
        .pp-finput:focus { border-color:#ef4444; box-shadow:0 0 0 3px rgba(239,68,68,0.12); }
        .pp-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none; width:100%; box-sizing:border-box;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .pp-fsel:focus { border-color:#ef4444; box-shadow:0 0 0 3px rgba(239,68,68,0.12); }
        .pp-ff-acts { display:flex; gap:0.5rem; align-items:flex-end; }
        .pp-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; box-shadow:0 4px 12px rgba(220,38,38,0.25);
        }
        .pp-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(220,38,38,0.35); }
        .pp-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .pp-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        .pp-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .pp-card-hdr {
            padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;
            display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;
        }
        .pp-card-title { font-size:1rem; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:0.5rem; }
        .pp-card-sub { font-size:0.8rem; color:#64748b; margin-top:0.25rem; }
        .pp-card-count {
            display:inline-flex; align-items:center; gap:0.375rem;
            padding:0.25rem 0.75rem; border-radius:999px; font-size:0.6875rem; font-weight:700;
            background:#f1f5f9; color:#475569; white-space:nowrap;
        }

        .pp-tblwrap { overflow-x:auto; }
        .pp-tbl { width:100%; border-collapse:separate; border-spacing:0; min-width:700px; }
        .pp-tbl thead { background:linear-gradient(180deg,#fff1f2,#fef2f2); border-bottom:2px solid #fecdd3; }
        .pp-tbl th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#9f1239; white-space:nowrap; text-align:left;
        }
        .pp-tbl th:last-child { text-align:center; }
        .pp-tbl td {
            padding:0.9375rem 1.25rem; border-bottom:1px solid #fef2f2; font-size:0.8125rem;
            color:#374151; vertical-align:middle;
        }
        .pp-tbl tbody tr { transition:background 0.15s; }
        .pp-tbl tbody tr:last-child td { border-bottom:none; }
        .pp-tbl tbody tr:hover td { background:linear-gradient(90deg,#fffafa,#fff5f5); }

        .pp-pel { display:flex; align-items:center; gap:0.75rem; }
        .pp-pel-av {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:0.875rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#fff1f2,#ffe4e6); color:#be123c; border:1.5px solid #fecdd3;
        }
        .pp-pel-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .pp-pel-sub { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        .pp-faktur {
            display:inline-flex; padding:0.2rem 0.5rem; border-radius:6px; font-size:0.6875rem; font-weight:700;
            background:#f8fafc; color:#475569; letter-spacing:0.02em; font-family:monospace; border:1px solid #e2e8f0;
        }

        .pp-tempo { font-size:0.75rem; font-weight:600; color:#1e293b; }
        .pp-tempo.overdue { color:#dc2626; }
        .pp-overdue-badge {
            display:inline-flex; align-items:center; gap:0.2rem;
            padding:0.1rem 0.375rem; border-radius:5px; font-size:0.6rem; font-weight:700;
            background:#fff1f2; color:#dc2626; border:1px solid #fecdd3; margin-left:0.375rem;
        }
        .pp-tempo-sub { font-size:0.625rem; color:#94a3b8; margin-top:1px; }

        .pp-money { text-align:right; font-weight:700; letter-spacing:-0.01em; }
        .pp-money.total { color:#0f172a; font-size:0.875rem; }
        .pp-money.paid { color:#059669; font-size:0.8125rem; }
        .pp-money.sisa { color:#dc2626; font-size:0.875rem; }

        .pp-pay-bar { height:4px; border-radius:999px; background:#e2e8f0; margin-top:4px; overflow:hidden; min-width:50px; }
        .pp-pay-bar-fill { height:100%; border-radius:999px; transition:width 0.4s ease; }

        .pp-badge {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.2rem 0.5rem; border-radius:999px; font-size:0.625rem; font-weight:700; border:1px solid; white-space:nowrap;
        }
        .pp-badge.belum_lunas { background:#fffbeb; color:#d97706; border-color:#fde68a; }
        .pp-badge-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
        .pp-badge-dot.belum_lunas { background:#f59e0b; animation:pp-pulse 1.5s infinite; }
        @keyframes pp-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        .pp-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.3125rem 0.625rem; border-radius:7px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
            background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe;
        }
        .pp-act:hover { background:#dbeafe; border-color:#93c5fd; }

        .pp-empty { text-align:center; padding:3.5rem 1.5rem; }
        .pp-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#fff1f2,#ffe4e6); display:flex; align-items:center; justify-content:center;
        }
        .pp-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .pp-empty-sub { font-size:0.8125rem; color:#94a3b8; }
        .pp-empty-hint {
            display:inline-flex; align-items:center; gap:0.5rem; margin-top:1rem;
            padding:0.5rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:500;
            background:#f0fdf4; color:#065f46; border:1px solid #bbf7d0;
        }

        .pp-pag { padding:0.875rem 1.25rem; border-top:1px solid #fef2f2; }

        @media(max-width:1024px){.pp-kpis{grid-template-columns:repeat(2,1fr)}.pp-ff-fld{min-width:140px}}
        @media(max-width:768px){.pp-hdr-title{font-size:1.25rem}.pp-hdr-ico{width:44px;height:44px;font-size:1.25rem}.pp-kpi{padding:1rem 1.125rem}.pp-kpi-val{font-size:1.125rem}}
        @media(max-width:640px){
            .pp{padding:1rem 0.75rem 2rem}
            .pp-kpis{grid-template-columns:1fr;gap:0.625rem}
            .pp-ff-fld{min-width:100%}.pp-ff-acts{width:100%}.pp-btn-f,.pp-btn-r{flex:1;justify-content:center}
            .pp-tblwrap table{min-width:600px}
        }
        @media(max-width:480px){
            .pp-hdr-title{font-size:1.125rem}
            .pp-hdr{flex-direction:column;align-items:stretch}
            .pp-hdr-l{flex-direction:column;align-items:flex-start;gap:0.5rem}
            .pp-card-hdr{flex-direction:column;align-items:flex-start}
            .pp-empty{padding:2.5rem 1rem}
        }
    </style>
    @endpush

    <div class="pp">

        {{-- Header --}}
        <div class="pp-hdr">
            <div class="pp-hdr-l">
                <div class="pp-hdr-ico">📋</div>
                <div>
                    <div class="pp-hdr-title">Daftar Piutang Aktif</div>
                    <div class="pp-hdr-sub">Monitoring piutang pelanggan yang belum lunas</div>
                </div>
            </div>
        </div>

        {{-- KPI --}}
        <div class="pp-kpis">
            <div class="pp-kpi red">
                <div class="pp-kpi-top">
                    <span class="pp-kpi-lbl">Total Piutang</span>
                    <div class="pp-kpi-ico red">💰</div>
                </div>
                <div class="pp-kpi-val red sm">Rp {{ number_format($stats['total_hutang'], 0, ',', '.') }}</div>
                <div class="pp-kpi-foot">Akumulasi sisa hutang seluruh pelanggan</div>
            </div>
            <div class="pp-kpi amber">
                <div class="pp-kpi-top">
                    <span class="pp-kpi-lbl">Belum Lunas</span>
                    <div class="pp-kpi-ico amber">⏳</div>
                </div>
                <div class="pp-kpi-val amber">{{ $stats['belum_lunas'] }} <span style="font-size:0.75rem;font-weight:600;color:#94a3b8;">transaksi</span></div>
                <div class="pp-kpi-foot">Piutang yang masih berjalan</div>
            </div>
            <div class="pp-kpi pink">
                <div class="pp-kpi-top">
                    <span class="pp-kpi-lbl">Overdue</span>
                    <div class="pp-kpi-ico pink">⚠️</div>
                </div>
                <div class="pp-kpi-val pink">{{ $stats['overdue'] }} <span style="font-size:0.75rem;font-weight:600;color:#94a3b8;">transaksi</span></div>
                <div class="pp-kpi-foot">Melewati batas jatuh tempo</div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="pp-filter">
            <form method="GET" class="pp-ff">
                <div class="pp-ff-fld">
                    <label class="pp-flbl">Pencarian</label>
                    <div class="pp-fwrap">
                        <svg class="pp-fico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama toko, pemilik..." class="pp-finput">
                    </div>
                </div>
                <div class="pp-ff-fld">
                    <label class="pp-flbl">Pelanggan</label>
                    <select name="pelanggan_id" class="pp-fsel">
                        <option value="">Semua Pelanggan</option>
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}" {{ request('pelanggan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_toko }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pp-ff-acts">
                    <button type="submit" class="pp-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('minyak.hutang.piutang') }}" class="pp-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="pp-card">
            <div class="pp-card-hdr">
                <div>
                    <div class="pp-card-title">📋 Data Piutang Aktif</div>
                    <div class="pp-card-sub">Diurutkan berdasarkan jatuh tempo terdekat</div>
                </div>
                <span class="pp-card-count">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    {{ $hutangs->total() }} Transaksi
                </span>
            </div>

            <div class="pp-tblwrap">
                <table class="pp-tbl">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>No Faktur</th>
                            <th>Jatuh Tempo</th>
                            <th style="text-align:right;">Total Hutang</th>
                            <th style="text-align:right;">Dibayar</th>
                            <th style="text-align:right;">Sisa</th>
                            <th style="text-align:center;width:100px;">Status</th>
                            <th style="text-align:center;width:90px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hutangs as $h)
                            @php
                                $isOverdue = $h->jatuh_tempo < now() && $h->status == 'belum_lunas';
                                $payPct = $h->total_hutang > 0 ? round(($h->dibayar / $h->total_hutang) * 100) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="pp-pel">
                                        <div class="pp-pel-av">{{ substr($h->pelanggan->nama_toko, 0, 1) }}</div>
                                        <div>
                                            <div class="pp-pel-name">{{ $h->pelanggan->nama_toko }}</div>
                                            <div class="pp-pel-sub">{{ $h->pelanggan->nama_pemilik }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="pp-faktur">{{ $h->penjualan->no_faktur ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="pp-tempo {{ $isOverdue ? 'overdue' : '' }}">
                                        {{ $h->jatuh_tempo->format('d M Y') }}
                                        @if($isOverdue)
                                            <span class="pp-overdue-badge">
                                                <svg width="8" height="8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01"/></svg>
                                                Overdue
                                            </span>
                                        @endif
                                    </div>
                                    <div class="pp-tempo-sub">{{ $h->jatuh_tempo->isoFormat('dddd') }}</div>
                                </td>
                                <td>
                                    <div class="pp-money total">Rp {{ number_format($h->total_hutang, 0, ',', '.') }}</div>
                                </td>
                                <td>
                                    <div class="pp-money paid">Rp {{ number_format($h->dibayar, 0, ',', '.') }}</div>
                                    <div class="pp-pay-bar">
                                        <div class="pp-pay-bar-fill" style="width:{{ $payPct }}%; background:linear-gradient(90deg,#10b981,#059669);"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="pp-money sisa">Rp {{ number_format($h->sisa, 0, ',', '.') }}</div>
                                </td>
                                <td style="text-align:center;">
                                    <span class="pp-badge {{ $h->status }}">
                                        <span class="pp-badge-dot {{ $h->status }}"></span>
                                        {{ $h->status == 'belum_lunas' ? 'Belum Lunas' : 'Lunas' }}
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <a href="{{ route('minyak.hutang.show', $h) }}" class="pp-act">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="pp-empty">
                                        <div class="pp-empty-ico">
                                            <svg width="32" height="32" fill="none" stroke="#be123c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="pp-empty-title">Belum Ada Data Hutang</div>
                                        <div class="pp-empty-sub">Data hutang akan muncul setelah ada transaksi dengan tipe bayar hutang</div>
                                        <div class="pp-empty-hint">
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
                <div class="pp-pag">
                    {{ $hutangs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>