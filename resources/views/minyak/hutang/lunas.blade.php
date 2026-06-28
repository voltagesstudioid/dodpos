<x-app-layout>
    @push('styles')
    <style>
        .lp { max-width:80rem; margin:0 auto; padding:1.25rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .lp-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .lp-hdr-l { display:flex; align-items:center; gap:1rem; }
        .lp-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#10b981,#059669);
            box-shadow:0 8px 24px rgba(5,150,105,0.3);
        }
        .lp-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .lp-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        .lp-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:0.875rem; margin-bottom:1.5rem; }
        .lp-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.25rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .lp-kpi::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .lp-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .lp-kpi.green::before { background:linear-gradient(90deg,#10b981,#059669); }
        .lp-kpi.blue::before { background:linear-gradient(90deg,#3b82f6,#2563eb); }
        .lp-kpi.purple::before { background:linear-gradient(90deg,#8b5cf6,#7c3aed); }
        .lp-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem; }
        .lp-kpi-lbl { font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .lp-kpi-ico { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; }
        .lp-kpi-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .lp-kpi-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .lp-kpi-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .lp-kpi-val { font-size:1.375rem; font-weight:800; letter-spacing:-0.02em; line-height:1; }
        .lp-kpi-val.green { color:#059669; }
        .lp-kpi-val.blue { color:#2563eb; }
        .lp-kpi-val.purple { color:#7c3aed; }
        .lp-kpi-foot { font-size:0.7rem; color:#94a3b8; margin-top:0.25rem; }

        .lp-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .lp-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .lp-ff-fld { flex:1; min-width:160px; }
        .lp-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .lp-fwrap { position:relative; }
        .lp-fico { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#94a3b8; pointer-events:none; }
        .lp-finput {
            width:100%; padding:0.625rem 1rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit; box-sizing:border-box;
        }
        .lp-finput:focus { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,0.12); }
        .lp-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none; width:100%; box-sizing:border-box;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .lp-fsel:focus { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,0.12); }
        .lp-ff-acts { display:flex; gap:0.5rem; align-items:flex-end; }
        .lp-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 4px 12px rgba(5,150,105,0.25);
        }
        .lp-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(5,150,105,0.35); }
        .lp-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .lp-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        .lp-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .lp-card-hdr {
            padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;
            display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;
        }
        .lp-card-title { font-size:1rem; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:0.5rem; }
        .lp-card-sub { font-size:0.8rem; color:#64748b; margin-top:0.25rem; }
        .lp-card-count {
            display:inline-flex; align-items:center; gap:0.375rem;
            padding:0.25rem 0.75rem; border-radius:999px; font-size:0.6875rem; font-weight:700;
            background:#f1f5f9; color:#475569; white-space:nowrap;
        }

        .lp-tblwrap { overflow-x:auto; }
        .lp-tbl { width:100%; border-collapse:separate; border-spacing:0; min-width:650px; }
        .lp-tbl thead { background:linear-gradient(180deg,#ecfdf5,#f0fdf4); border-bottom:2px solid #a7f3d0; }
        .lp-tbl th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#065f46; white-space:nowrap; text-align:left;
        }
        .lp-tbl th:last-child { text-align:center; }
        .lp-tbl td {
            padding:0.9375rem 1.25rem; border-bottom:1px solid #f0fdf4; font-size:0.8125rem;
            color:#374151; vertical-align:middle;
        }
        .lp-tbl tbody tr { transition:background 0.15s; }
        .lp-tbl tbody tr:last-child td { border-bottom:none; }
        .lp-tbl tbody tr:hover td { background:linear-gradient(90deg,#fafffe,#f0fdf4); }

        .lp-pel { display:flex; align-items:center; gap:0.75rem; }
        .lp-pel-av {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:0.875rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#065f46; border:1.5px solid #a7f3d0;
        }
        .lp-pel-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .lp-pel-sub { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        .lp-faktur {
            display:inline-flex; padding:0.2rem 0.5rem; border-radius:6px; font-size:0.6875rem; font-weight:700;
            background:#f0fdf4; color:#065f46; letter-spacing:0.02em; font-family:monospace; border:1px solid #a7f3d0;
        }
        .lp-date { font-size:0.75rem; font-weight:600; color:#1e293b; }
        .lp-date-sub { font-size:0.625rem; color:#94a3b8; margin-top:1px; }

        .lp-money { text-align:right; font-weight:700; letter-spacing:-0.01em; }
        .lp-money.total { color:#0f172a; font-size:0.875rem; }
        .lp-money.paid { color:#059669; font-size:0.875rem; }

        .lp-pay-bar { height:4px; border-radius:999px; background:#e2e8f0; margin-top:4px; overflow:hidden; min-width:60px; }
        .lp-pay-bar-fill { height:100%; border-radius:999px; transition:width 0.4s ease; }

        .lp-badge {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:999px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .lp-badge.lunas { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .lp-badge-dot { width:6px; height:6px; border-radius:50%; background:#10b981; }

        .lp-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.3125rem 0.625rem; border-radius:7px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
            background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe;
        }
        .lp-act:hover { background:#dbeafe; border-color:#93c5fd; }

        .lp-empty { text-align:center; padding:3.5rem 1.5rem; }
        .lp-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#d1fae5,#a7f3d0); display:flex; align-items:center; justify-content:center;
        }
        .lp-empty-title { font-size:1rem; font-weight:700; color:#065f46; margin-bottom:0.25rem; }
        .lp-empty-sub { font-size:0.8125rem; color:#94a3b8; }
        .lp-empty-hint {
            display:inline-flex; align-items:center; gap:0.5rem; margin-top:1rem;
            padding:0.5rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:500;
            background:#f0fdf4; color:#065f46; border:1px solid #bbf7d0;
        }

        .lp-pag { padding:0.875rem 1.25rem; border-top:1px solid #f0fdf4; }

        .lp-flash {
            margin-bottom:1rem; padding:0.75rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            display:flex; align-items:center; gap:0.5rem;
        }
        .lp-flash.success { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; }
        .lp-flash.error { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; }

        @media(max-width:1024px){.lp-kpis{grid-template-columns:repeat(2,1fr)}.lp-ff-fld{min-width:140px}}
        @media(max-width:768px){.lp-hdr-title{font-size:1.25rem}.lp-hdr-ico{width:44px;height:44px;font-size:1.25rem}.lp-kpi{padding:1rem 1.125rem}.lp-kpi-val{font-size:1.125rem}}
        @media(max-width:640px){
            .lp{padding:1rem 0.75rem 2rem}
            .lp-kpis{grid-template-columns:1fr;gap:0.625rem}
            .lp-ff-fld{min-width:100%}.lp-ff-acts{width:100%}.lp-btn-f,.lp-btn-r{flex:1;justify-content:center}
            .lp-tblwrap table{min-width:550px}
        }
        @media(max-width:480px){
            .lp-hdr-title{font-size:1.125rem}
            .lp-hdr{flex-direction:column;align-items:stretch}
            .lp-hdr-l{flex-direction:column;align-items:flex-start;gap:0.5rem}
            .lp-card-hdr{flex-direction:column;align-items:flex-start}
            .lp-empty{padding:2.5rem 1rem}
        }
    </style>
    @endpush

    <div class="lp">
        @php
            $totalLunas = $hutangs->total();
            $totalNominal = $hutangs->sum('total_hutang');
            $rataNominal = $totalLunas > 0 ? $totalNominal / $totalLunas : 0;
        @endphp

        {{-- Header --}}
        <div class="lp-hdr">
            <div class="lp-hdr-l">
                <div class="lp-hdr-ico">✅</div>
                <div>
                    <div class="lp-hdr-title">Riwayat Hutang Lunas</div>
                    <div class="lp-hdr-sub">Arsip dan riwayat pembayaran pelanggan yang sudah lunas</div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="lp-flash success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="lp-flash error">❌ {{ session('error') }}</div>
        @endif

        {{-- KPI --}}
        <div class="lp-kpis">
            <div class="lp-kpi green">
                <div class="lp-kpi-top">
                    <span class="lp-kpi-lbl">Total Lunas</span>
                    <div class="lp-kpi-ico green">✅</div>
                </div>
                <div class="lp-kpi-val green">{{ $totalLunas }} <span style="font-size:0.75rem;font-weight:600;color:#94a3b8;">transaksi</span></div>
                <div class="lp-kpi-foot">Jumlah hutang yang sudah dibayar lunas</div>
            </div>
            <div class="lp-kpi blue">
                <div class="lp-kpi-top">
                    <span class="lp-kpi-lbl">Total Nominal</span>
                    <div class="lp-kpi-ico blue">💰</div>
                </div>
                <div class="lp-kpi-val blue">Rp {{ number_format($totalNominal, 0, ',', '.') }}</div>
                <div class="lp-kpi-foot">Nilai keseluruhan hutang yang sudah lunas</div>
            </div>
            <div class="lp-kpi purple">
                <div class="lp-kpi-top">
                    <span class="lp-kpi-lbl">Rata-rata</span>
                    <div class="lp-kpi-ico purple">📊</div>
                </div>
                <div class="lp-kpi-val purple">Rp {{ number_format($rataNominal, 0, ',', '.') }}</div>
                <div class="lp-kpi-foot">Rata-rata nominal per transaksi</div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="lp-filter">
            <form method="GET" class="lp-ff">
                <div class="lp-ff-fld">
                    <label class="lp-flbl">Pencarian</label>
                    <div class="lp-fwrap">
                        <svg class="lp-fico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama toko, pemilik..." class="lp-finput">
                    </div>
                </div>
                <div class="lp-ff-fld">
                    <label class="lp-flbl">Pelanggan</label>
                    <select name="pelanggan_id" class="lp-fsel">
                        <option value="">Semua Pelanggan</option>
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}" {{ request('pelanggan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_toko }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lp-ff-acts">
                    <button type="submit" class="lp-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('minyak.hutang.lunas') }}" class="lp-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="lp-card">
            <div class="lp-card-hdr">
                <div>
                    <div class="lp-card-title">📋 Daftar Hutang Lunas</div>
                    <div class="lp-card-sub">Data hutang yang telah diselesaikan oleh pelanggan</div>
                </div>
                <span class="lp-card-count">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    {{ $totalLunas }} Transaksi
                </span>
            </div>

            <div class="lp-tblwrap">
                <table class="lp-tbl">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>No Faktur</th>
                            <th>Jatuh Tempo</th>
                            <th style="text-align:right;">Total Hutang</th>
                            <th style="text-align:right;">Dibayar</th>
                            <th style="text-align:center;width:100px;">Status</th>
                            <th style="text-align:center;width:100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hutangs as $h)
                            @php
                                $payPct = $h->total_hutang > 0 ? round(($h->dibayar / $h->total_hutang) * 100) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="lp-pel">
                                        <div class="lp-pel-av">{{ substr($h->pelanggan->nama_toko, 0, 1) }}</div>
                                        <div>
                                            <div class="lp-pel-name">{{ $h->pelanggan->nama_toko }}</div>
                                            <div class="lp-pel-sub">{{ $h->pelanggan->nama_pemilik }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="lp-faktur">{{ $h->penjualan->no_faktur ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="lp-date">{{ $h->jatuh_tempo->format('d M Y') }}</div>
                                    <div class="lp-date-sub">Lunas {{ $h->updated_at->format('d M Y') }}</div>
                                </td>
                                <td>
                                    <div class="lp-money total">Rp {{ number_format($h->total_hutang, 0, ',', '.') }}</div>
                                </td>
                                <td>
                                    <div class="lp-money paid">Rp {{ number_format($h->dibayar, 0, ',', '.') }}</div>
                                    <div class="lp-pay-bar">
                                        <div class="lp-pay-bar-fill" style="width:{{ $payPct }}%; background:linear-gradient(90deg,#10b981,#059669);"></div>
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    <span class="lp-badge lunas">
                                        <span class="lp-badge-dot"></span>
                                        Lunas
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <a href="{{ route('minyak.hutang.show', $h) }}" class="lp-act">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="lp-empty">
                                        <div class="lp-empty-ico">
                                            <svg width="32" height="32" fill="none" stroke="#059669" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="lp-empty-title">Belum Ada Data Lunas</div>
                                        <div class="lp-empty-sub">Data hutang lunas akan muncul setelah ada pembayaran</div>
                                        <div class="lp-empty-hint">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Lakukan pembayaran dari menu Piutang untuk mencatat pelunasan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($hutangs->hasPages())
                <div class="lp-pag">
                    {{ $hutangs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>