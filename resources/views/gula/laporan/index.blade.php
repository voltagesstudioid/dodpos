<x-app-layout>
    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style>
        .lp-wrap{font-family:'Plus Jakarta Sans',sans-serif}
        .lp-mono{font-family:'JetBrains Mono',monospace}

        /* header */
        .lp-header{background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);border:1px solid #fde68a;border-radius:20px;padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px}
        .lp-header-left{display:flex;align-items:center;gap:16px}
        .lp-header-icon{width:52px;height:52px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:16px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(245,158,11,.35)}
        .lp-header-icon svg{width:26px;height:26px;color:#fff}
        .lp-header h1{font-size:1.375rem;font-weight:800;color:#1f2937;margin:0}
        .lp-header p{font-size:.8rem;color:#92400e;margin:2px 0 0}
        .lp-date-range{font-size:.75rem;font-weight:600;color:#92400e;background:#fff;border:1px solid #fde68a;padding:6px 14px;border-radius:10px;display:flex;align-items:center;gap:6px}
        .lp-date-range svg{width:14px;height:14px;color:#d97706}

        /* filter */
        .lp-filter{background:#fff;border:1px solid #fde68a;border-radius:16px;padding:16px 20px;margin-bottom:24px;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .lp-filter form{display:flex;flex-wrap:wrap;align-items:center;gap:10px}
        .lp-date-group{display:flex;align-items:center;gap:6px}
        .lp-date-lbl{font-size:.78rem;color:#92400e;font-weight:600;white-space:nowrap}
        .lp-date-input{border:1.5px solid #fde68a;border-radius:12px;padding:9px 12px;font-size:.8125rem;background:#fffbeb;color:#92400e;font-weight:500;outline:none;transition:border-color .2s}
        .lp-date-input:focus{border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.1)}
        .lp-filter select{border:1.5px solid #fde68a;border-radius:12px;padding:9px 14px;font-size:.8125rem;background:#fffbeb;color:#92400e;font-weight:500;outline:none;min-width:155px;transition:border-color .2s;cursor:pointer}
        .lp-filter select:focus{border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.1)}
        .lp-btn{border:none;border-radius:12px;padding:9px 18px;font-size:.8125rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:all .2s;text-decoration:none}
        .lp-btn svg{width:15px;height:15px}
        .lp-btn.amber{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff}
        .lp-btn.amber:hover{opacity:.88}
        .lp-btn.reset{background:#fffbeb;color:#92400e;border:1.5px solid #fde68a}
        .lp-btn.reset:hover{background:#fef3c7}
        .lp-btn.print{background:linear-gradient(135deg,#10b981,#059669);color:#fff;margin-left:auto}
        .lp-btn.print:hover{opacity:.88;transform:translateY(-1px);box-shadow:0 4px 12px rgba(16,185,129,.25)}

        /* kpi */
        .lp-kpi-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:24px}
        .lp-kpi{background:#fff;border:1px solid #fde68a;border-radius:16px;padding:18px;display:flex;align-items:flex-start;gap:12px;position:relative;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04);transition:box-shadow .2s}
        .lp-kpi:hover{box-shadow:0 4px 14px rgba(245,158,11,.12)}
        .lp-kpi::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px}
        .lp-kpi.purple::before{background:linear-gradient(180deg,#8b5cf6,#7c3aed)}
        .lp-kpi.blue::before{background:linear-gradient(180deg,#3b82f6,#2563eb)}
        .lp-kpi.green::before{background:linear-gradient(180deg,#10b981,#059669)}
        .lp-kpi.amber::before{background:linear-gradient(180deg,#f59e0b,#d97706)}
        .lp-kpi.red::before{background:linear-gradient(180deg,#f87171,#ef4444)}
        .lp-kpi-icon{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .lp-kpi-icon svg{width:20px;height:20px}
        .lp-kpi-icon.purple{background:linear-gradient(135deg,#ede9fe,#ddd6fe);color:#7c3aed}
        .lp-kpi-icon.blue{background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#2563eb}
        .lp-kpi-icon.green{background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#059669}
        .lp-kpi-icon.amber{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706}
        .lp-kpi-icon.red{background:linear-gradient(135deg,#fee2e2,#fecaca);color:#ef4444}
        .lp-kpi-val{font-size:1.1rem;font-weight:800;color:#1f2937;line-height:1}
        .lp-kpi-lbl{font-size:.68rem;color:#6b7280;margin-top:3px;font-weight:500}

        /* cards & grid */
        .lp-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:24px}
        .lp-card{background:#fff;border:1px solid #fde68a;border-radius:20px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.05)}
        .lp-card-head{background:linear-gradient(180deg,#fffbeb,#fef9ee);border-bottom:2px solid #fde68a;padding:16px 22px;display:flex;align-items:center;gap:10px}
        .lp-card-head svg{width:18px;height:18px;color:#d97706}
        .lp-card-head h3{font-size:.875rem;font-weight:700;color:#1f2937;margin:0}
        .lp-card-head .lp-badge-count{margin-left:auto;font-size:.68rem;font-weight:700;padding:3px 10px;border-radius:8px;background:#fef3c7;color:#92400e}
        .lp-card-body{padding:20px 22px}

        /* bar chart */
        .lp-bar-row{display:flex;align-items:center;gap:10px;margin-bottom:8px}
        .lp-bar-label{width:52px;font-size:.72rem;color:#6b7280;font-weight:500;text-align:right;flex-shrink:0}
        .lp-bar-track{flex:1;height:22px;background:#fef3c7;border-radius:8px;overflow:hidden}
        .lp-bar-fill{height:100%;border-radius:8px;transition:width .4s}
        .lp-bar-fill.amber{background:linear-gradient(90deg,#f59e0b,#d97706)}
        .lp-bar-fill.blue{background:linear-gradient(90deg,#3b82f6,#2563eb)}
        .lp-bar-fill.zero{background:#f1f5f9}
        .lp-bar-val{min-width:90px;text-align:right;flex-shrink:0}
        .lp-bar-val-main{font-size:.78rem;font-weight:700;color:#1f2937}
        .lp-bar-val-sub{font-size:.65rem;color:#6b7280}

        /* sales perf */
        .lp-sales-row{display:flex;align-items:center;gap:10px;margin-bottom:10px}
        .lp-sales-av{width:30px;height:30px;border-radius:9px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.7rem;flex-shrink:0}
        .lp-sales-name{width:80px;font-size:.78rem;font-weight:600;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;flex-shrink:0}

        /* tables */
        .lp-tbl-wrap{overflow-x:auto}
        .lp-tbl{width:100%;border-collapse:collapse}
        .lp-tbl thead th{background:linear-gradient(180deg,#fffbeb,#fef9ee);border-bottom:2px solid #fde68a;padding:11px 14px;font-size:.7rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.5px;white-space:nowrap}
        .lp-tbl tbody td{padding:12px 14px;border-bottom:1px solid #fef3c7;font-size:.8125rem;color:#374151;vertical-align:middle}
        .lp-tbl tbody tr:last-child td{border-bottom:none}
        .lp-tbl tbody tr:hover{background:#fffbeb}
        .lp-tbl tfoot td{padding:11px 14px;font-weight:700;border-top:2px solid #fde68a;background:#fef9ee}
        .lp-prod-name{font-weight:600;color:#1f2937}
        .lp-prod-sub{font-size:.7rem;color:#6b7280;margin-top:1px}
        .lp-badge{display:inline-flex;padding:3px 8px;border-radius:8px;font-size:.72rem;font-weight:600}
        .lp-badge.amber{background:#fef3c7;color:#92400e}
        .lp-badge.green{background:#d1fae5;color:#065f46}
        .lp-badge.blue{background:#dbeafe;color:#1e40af}
        .lp-progress{height:6px;background:#fde68a;border-radius:99px;overflow:hidden}
        .lp-progress-fill{height:100%;background:linear-gradient(90deg,#f59e0b,#d97706);border-radius:99px;transition:width .4s}

        /* method badges */
        .lp-method{display:inline-flex;align-items:center;gap:3px;padding:3px 8px;border-radius:6px;font-size:.68rem;font-weight:700}
        .lp-method.tunai{background:#d1fae5;color:#065f46}
        .lp-method.transfer{background:#dbeafe;color:#1d4ed8}
        .lp-method.hutang{background:#fef3c7;color:#92400e}

        /* setoran */
        .lp-setoran-item{display:flex;align-items:center;justify-content:space-between;padding:12px 14px;background:#fffbeb;border:1px solid #fde68a;border-radius:12px;margin-bottom:8px}
        .lp-setoran-left{display:flex;align-items:center;gap:10px}
        .lp-setoran-status{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:8px;font-size:.72rem;font-weight:700}
        .lp-setoran-status.pending{background:#fef3c7;color:#92400e}
        .lp-setoran-status.terverifikasi{background:#d1fae5;color:#065f46}
        .lp-setoran-status.ditolak{background:#fee2e2;color:#991b1b}
        .lp-setoran-status.default{background:#f3f4f6;color:#6b7280}
        .lp-setoran-count{font-size:.78rem;color:#6b7280}
        .lp-setoran-total{font-weight:700;color:#1f2937;font-size:.8125rem}

        /* hutang */
        .lp-hutang-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
        .lp-hutang-box{text-align:center;padding:18px 12px;border-radius:14px;border:1px solid transparent}
        .lp-hutang-box.red{background:linear-gradient(135deg,#fee2e2,#fecaca);border-color:#fca5a5}
        .lp-hutang-box.amber{background:linear-gradient(135deg,#fef3c7,#fde68a);border-color:#fcd34d}
        .lp-hutang-box.blue{background:linear-gradient(135deg,#dbeafe,#bfdbfe);border-color:#93c5fd}
        .lp-hutang-val{font-size:1.1rem;font-weight:800;line-height:1}
        .lp-hutang-val.red{color:#991b1b}
        .lp-hutang-val.amber{color:#92400e}
        .lp-hutang-val.blue{color:#1e40af}
        .lp-hutang-lbl{font-size:.68rem;margin-top:4px;font-weight:500}
        .lp-hutang-lbl.red{color:#b91c1c}
        .lp-hutang-lbl.amber{color:#b45309}
        .lp-hutang-lbl.blue{color:#1d4ed8}

        .lp-empty-msg{text-align:center;padding:24px;color:#9ca3af;font-size:.8125rem}

        @media(max-width:1024px){.lp-kpi-grid{grid-template-columns:repeat(3,1fr)}.lp-grid-2{grid-template-columns:1fr}}
        @media(max-width:640px){.lp-kpi-grid{grid-template-columns:1fr 1fr}.lp-hutang-grid{grid-template-columns:1fr}}
    </style>
    @endpush

    <div class="lp-wrap" style="padding:24px">

        {{-- Header --}}
        <div class="lp-header">
            <div class="lp-header-left">
                <div class="lp-header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h1>Laporan Penjualan</h1>
                    <p>Analisis penjualan gula</p>
                </div>
            </div>
            <div class="lp-date-range">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} — {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d M Y') }}
            </div>
        </div>

        {{-- Filter --}}
        <div class="lp-filter">
            <form method="GET">
                <div class="lp-date-group">
                    <span class="lp-date-lbl">Dari:</span>
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="lp-date-input">
                </div>
                <div class="lp-date-group">
                    <span class="lp-date-lbl">Sampai:</span>
                    <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" class="lp-date-input">
                </div>
                <select name="sales_id">
                    <option value="">Semua Sales</option>
                    @foreach($salesList as $s)
                        <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                    @endforeach
                </select>
                <button type="submit" class="lp-btn amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                <a href="{{ route('gula.laporan') }}" class="lp-btn reset">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset
                </a>
                @php
                    $printParams = ['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai];
                    if (request('sales_id')) $printParams['sales_id'] = request('sales_id');
                @endphp
                <a href="{{ route('gula.laporan.print', $printParams) }}" target="_blank" class="lp-btn print">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Laporan
                </a>
            </form>
        </div>

        {{-- KPI Cards --}}
        <div class="lp-kpi-grid">
            <div class="lp-kpi purple">
                <div class="lp-kpi-icon purple">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="lp-kpi-val lp-mono">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</div>
                    <div class="lp-kpi-lbl">Total Penjualan</div>
                </div>
            </div>
            <div class="lp-kpi blue">
                <div class="lp-kpi-icon blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <div class="lp-kpi-val">{{ $summary['jumlah_transaksi'] }}</div>
                    <div class="lp-kpi-lbl">Transaksi</div>
                </div>
            </div>
            <div class="lp-kpi green">
                <div class="lp-kpi-icon green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <div class="lp-kpi-val lp-mono">Rp {{ number_format($summary['total_tunai'], 0, ',', '.') }}</div>
                    <div class="lp-kpi-lbl">Tunai</div>
                </div>
            </div>
            <div class="lp-kpi amber">
                <div class="lp-kpi-icon amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <div>
                    <div class="lp-kpi-val lp-mono">Rp {{ number_format($summary['total_transfer'], 0, ',', '.') }}</div>
                    <div class="lp-kpi-lbl">Transfer</div>
                </div>
            </div>
            <div class="lp-kpi red">
                <div class="lp-kpi-icon red">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="lp-kpi-val lp-mono">Rp {{ number_format($summary['penjualan_hutang'], 0, ',', '.') }}</div>
                    <div class="lp-kpi-lbl">Penjualan Hutang</div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="lp-grid-2">
            {{-- Daily Chart --}}
            <div class="lp-card">
                <div class="lp-card-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    <h3>Penjualan Harian</h3>
                    <span class="lp-badge-count">{{ $dailyData->where('total', '>', 0)->count() }} hari</span>
                </div>
                <div class="lp-card-body">
                    @php $maxDaily = $dailyData->max('total') ?: 1; @endphp
                    @foreach($dailyData as $d)
                    @php $pct = $maxDaily > 0 ? ($d->total / $maxDaily) * 100 : 0; @endphp
                    <div class="lp-bar-row">
                        <div class="lp-bar-label">{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m') }}</div>
                        <div class="lp-bar-track">
                            <div class="lp-bar-fill {{ $d->total > 0 ? 'amber' : 'zero' }}" style="width:{{ max($pct, $d->total > 0 ? 3 : 100) }}%"></div>
                        </div>
                        <div class="lp-bar-val">
                            @if($d->total > 0)
                                <div class="lp-bar-val-main lp-mono">Rp {{ number_format($d->total/1000, 0) }}k</div>
                                <div class="lp-bar-val-sub">{{ $d->jumlah }} trx</div>
                            @else
                                <div class="lp-bar-val-sub" style="color:#cbd5e1">—</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Sales Performance --}}
            <div class="lp-card">
                <div class="lp-card-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <h3>Performa Sales</h3>
                </div>
                <div class="lp-card-body">
                    @if($salesPerformance->where('omzet', '>', 0)->count() > 0)
                        @php $maxOmzet = $salesPerformance->max('omzet') ?: 1; @endphp
                        @foreach($salesPerformance as $sp)
                        @php $pct = $maxOmzet > 0 ? (($sp->omzet ?? 0) / $maxOmzet) * 100 : 0; @endphp
                        <div class="lp-sales-row">
                            <div class="lp-sales-av">{{ strtoupper(substr($sp->nama, 0, 1)) }}</div>
                            <div class="lp-sales-name">{{ $sp->nama }}</div>
                            <div class="lp-bar-track">
                                <div class="lp-bar-fill blue" style="width:{{ max($pct,3) }}%"></div>
                            </div>
                            <div class="lp-bar-val">
                                <div class="lp-bar-val-main lp-mono">Rp {{ number_format(($sp->omzet ?? 0)/1000, 0) }}k</div>
                                <div class="lp-bar-val-sub">{{ $sp->total_penjualan }} trx</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="lp-empty-msg">Tidak ada data penjualan</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Product Performance --}}
        <div class="lp-card" style="margin-bottom:24px">
            <div class="lp-card-head">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <h3>Performa Produk</h3>
                <span class="lp-badge-count">{{ $productPerformance->where('terjual', '>', 0)->count() }} produk</span>
            </div>
            <div class="lp-tbl-wrap">
                <table class="lp-tbl">
                    <thead>
                        <tr>
                            <th style="text-align:left">Produk</th>
                            <th style="text-align:center">Terjual</th>
                            <th style="text-align:right">Omzet</th>
                            <th style="width:180px">Grafik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $maxTerjual = $productPerformance->max('terjual') ?: 1; @endphp
                        @forelse($productPerformance->where('terjual', '>', 0) as $pp)
                        @php $pct = $maxTerjual > 0 ? ($pp->terjual / $maxTerjual) * 100 : 0; @endphp
                        <tr>
                            <td>
                                <div class="lp-prod-name">{{ $pp->nama }}</div>
                                <div class="lp-prod-sub">{{ $pp->jenis }}</div>
                            </td>
                            <td style="text-align:center"><span class="lp-badge amber lp-mono">{{ $pp->terjual }} {{ $pp->satuan }}</span></td>
                            <td style="text-align:right"><span class="lp-mono" style="font-weight:700;color:#1f2937">Rp {{ number_format($pp->omzet ?? 0, 0, ',', '.') }}</span></td>
                            <td>
                                <div class="lp-progress"><div class="lp-progress-fill" style="width:{{ max($pct,2) }}%"></div></div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4"><div class="lp-empty-msg">Tidak ada data produk terjual</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Transaction Detail --}}
        <div class="lp-card" style="margin-bottom:24px">
            <div class="lp-card-head">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <h3>Detail Transaksi</h3>
                <span class="lp-badge-count">{{ $topTransactions->count() }} transaksi</span>
            </div>
            <div class="lp-tbl-wrap">
                <table class="lp-tbl">
                    <thead>
                        <tr>
                            <th style="text-align:left">No. Faktur</th>
                            <th style="text-align:left">Tanggal</th>
                            <th style="text-align:left">Sales</th>
                            <th style="text-align:left">Pelanggan</th>
                            <th style="text-align:center">Tipe</th>
                            <th style="text-align:right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topTransactions as $t)
                        <tr>
                            <td><span class="lp-mono" style="font-weight:600">{{ $t->no_faktur }}</span></td>
                            <td style="font-size:.78rem">{{ $t->tanggal_jual->format('d/m/Y H:i') }}</td>
                            <td style="font-size:.78rem">{{ $t->sales->nama ?? '-' }}</td>
                            <td style="font-weight:600">{{ $t->pelanggan->nama ?? '-' }}</td>
                            <td style="text-align:center"><span class="lp-method {{ $t->tipe_bayar }}">{{ strtoupper($t->tipe_bayar) }}</span></td>
                            <td style="text-align:right"><span class="lp-mono" style="font-weight:700">Rp {{ number_format($t->total, 0, ',', '.') }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="6"><div class="lp-empty-msg">Tidak ada transaksi</div></td></tr>
                        @endforelse
                    </tbody>
                    @if($topTransactions->isNotEmpty())
                    <tfoot>
                        <tr>
                            <td colspan="5">Total</td>
                            <td style="text-align:right"><span class="lp-mono">Rp {{ number_format($topTransactions->sum('total'), 0, ',', '.') }}</span></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- Setoran & Hutang --}}
        <div class="lp-grid-2">
            {{-- Setoran --}}
            <div class="lp-card">
                <div class="lp-card-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3>Status Setoran</h3>
                </div>
                <div class="lp-card-body">
                    @if($setoranSummary->count() > 0)
                        @foreach($setoranSummary as $ss)
                        @php $sClass = in_array($ss->status, ['pending','terverifikasi','ditolak']) ? $ss->status : 'default'; @endphp
                        <div class="lp-setoran-item">
                            <div class="lp-setoran-left">
                                <span class="lp-setoran-status {{ $sClass }}">{{ ucfirst($ss->status) }}</span>
                                <span class="lp-setoran-count">{{ $ss->jumlah }} setoran</span>
                            </div>
                            <span class="lp-setoran-total lp-mono">Rp {{ number_format($ss->total_setor, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="lp-empty-msg">Tidak ada data setoran</div>
                    @endif
                </div>
            </div>

            {{-- Hutang --}}
            <div class="lp-card">
                <div class="lp-card-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <h3>Ringkasan Hutang</h3>
                </div>
                <div class="lp-card-body">
                    <div class="lp-hutang-grid">
                        <div class="lp-hutang-box red">
                            <div class="lp-hutang-val red lp-mono">Rp {{ number_format($hutangSummary['total_hutang']/1000000, 1) }}M</div>
                            <div class="lp-hutang-lbl red">Total Piutang</div>
                        </div>
                        <div class="lp-hutang-box amber">
                            <div class="lp-hutang-val amber">{{ $hutangSummary['jumlah_pelanggan'] }}</div>
                            <div class="lp-hutang-lbl amber">Pelanggan Hutang</div>
                        </div>
                        <div class="lp-hutang-box blue">
                            <div class="lp-hutang-val blue lp-mono">Rp {{ number_format($hutangSummary['hutang_baru']/1000000, 1) }}M</div>
                            <div class="lp-hutang-lbl blue">Hutang Baru</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
