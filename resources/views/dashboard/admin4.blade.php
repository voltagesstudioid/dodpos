<x-app-layout>
    <x-slot name="header">Dashboard Gudang Keluar</x-slot>

    <style>
        .da4-page{padding:1.5rem;display:flex;flex-direction:column;gap:1.25rem;font-family:'Plus Jakarta Sans',system-ui,-apple-system,sans-serif;}
        .da4-page-header{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;}
        .da4-page-title{font-size:1.35rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;}
        .da4-page-sub{font-size:.82rem;color:#64748b;margin-top:.15rem;}

        /* ── stat cards ── */
        .da4-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(175px,1fr));gap:.85rem;}
        .da4-stat{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:1.1rem 1.15rem;display:flex;align-items:center;gap:.9rem;transition:all .2s;}
        .da4-stat:hover{border-color:#cbd5e1;box-shadow:0 2px 10px rgba(0,0,0,.04);}
        .da4-stat-ico{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .da4-stat-ico svg{width:20px;height:20px;}
        .da4-stat-ico.orange{background:#fff7ed;color:#ea580c;}
        .da4-stat-ico.violet{background:#f5f3ff;color:#7c3aed;}
        .da4-stat-ico.slate{background:#f1f5f9;color:#475569;}
        .da4-stat-ico.amber{background:#fffbeb;color:#d97706;}
        .da4-stat-ico.rose{background:#fff1f2;color:#e11d48;}
        .da4-stat-ico.teal{background:#f0fdfa;color:#0d9488;}
        .da4-stat-info{display:flex;flex-direction:column;gap:.1rem;min-width:0;}
        .da4-stat-label{font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;}
        .da4-stat-val{font-size:1.45rem;font-weight:800;line-height:1.1;}
        .da4-stat-val.orange{color:#ea580c;}
        .da4-stat-val.violet{color:#7c3aed;}
        .da4-stat-val.slate{color:#475569;}
        .da4-stat-val.amber{color:#d97706;}
        .da4-stat-val.rose{color:#e11d48;}
        .da4-stat-val.teal{color:#0d9488;}
        .da4-stat-pill{font-size:.65rem;font-weight:600;padding:.15rem .5rem;border-radius:6px;display:inline-block;width:fit-content;margin-top:.15rem;}
        .da4-pill-orange{background:#fff7ed;color:#9a3412;}
        .da4-pill-violet{background:#ede9fe;color:#5b21b6;}
        .da4-pill-gray{background:#f1f5f9;color:#64748b;}
        .da4-pill-amber{background:#fef3c7;color:#92400e;}
        .da4-pill-rose{background:#ffe4e6;color:#9f1239;}
        .da4-pill-teal{background:#ccfbf1;color:#065f46;}

        /* ── status pills ── */
        .da4-status{font-size:.7rem;font-weight:700;padding:.22rem .65rem;border-radius:7px;display:inline-flex;align-items:center;gap:.3rem;}
        .da4-status::before{content:'';width:6px;height:6px;border-radius:50%;flex-shrink:0;}
        .da4-status-success{background:#d1fae5;color:#065f46;}.da4-status-success::before{background:#059669;}
        .da4-status-info{background:#dbeafe;color:#1e40af;}.da4-status-info::before{background:#2563eb;}
        .da4-status-warning{background:#fef3c7;color:#92400e;}.da4-status-warning::before{background:#d97706;}

        /* ── alerts ── */
        .da4-alerts{display:flex;flex-direction:column;gap:.5rem;}
        .da4-alert{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;border-radius:12px;text-decoration:none;font-size:.82rem;font-weight:600;transition:all .2s;}
        .da4-alert:hover{filter:brightness(.97);transform:translateX(2px);}
        .da4-alert-warning{background:#fffbeb;border:1px solid #fde68a;color:#92400e;}
        .da4-alert-danger{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;}
        .da4-alert svg{flex-shrink:0;}
        .da4-alert-link{margin-left:auto;font-size:.73rem;font-weight:700;opacity:.7;}

        /* ── table card ── */
        .da4-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;}
        .da4-card-hdr{padding:1rem 1.15rem;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;}
        .da4-card-title{font-size:.88rem;font-weight:800;color:#0f172a;}
        .da4-card-sub{font-size:.75rem;color:#94a3b8;margin-top:.1rem;}
        .da4-tbl{width:100%;border-collapse:collapse;}
        .da4-tbl thead{background:#f8fafc;}
        .da4-tbl th{padding:.65rem 1rem;font-size:.7rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;text-align:left;border-bottom:1px solid #e2e8f0;}
        .da4-tbl td{padding:.75rem 1rem;font-size:.83rem;color:#334155;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
        .da4-tbl tbody tr{transition:background .12s;}
        .da4-tbl tbody tr:hover{background:#f8fafc;}
        .da4-tbl tbody tr:last-child td{border-bottom:none;}
        .da4-item-name{font-weight:700;color:#0f172a;}
        .da4-item-meta{font-size:.73rem;color:#94a3b8;margin-top:.1rem;}

        /* ── layout ── */
        .da4-grid{display:grid;grid-template-columns:1.4fr 0.6fr;gap:1.25rem;}
        .da4-col-left{display:flex;flex-direction:column;gap:1.25rem;}
        .da4-col-right{display:flex;flex-direction:column;gap:1.25rem;}
        .da4-inner-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;}

        /* ── chart ── */
        .da4-chart-container{display:flex;align-items:flex-end;gap:8px;height:200px;margin:1rem 1rem 0 1rem;padding:0 0.5rem;border-bottom:2px solid #f1f5f9;}
        .da4-bar-group{flex:1;display:flex;flex-direction:column;justify-content:flex-end;min-width:10px;border-radius:6px 6px 0 0;overflow:hidden;background:rgba(148,163,184,0.1);cursor:pointer;transition:all 0.2s;}
        .da4-bar-group:hover{filter:brightness(0.9);}
        .da4-bar-group.is-today{background:#fef3c7;box-shadow:inset 0 -4px 0 #ea580c;}
        .da4-bar-seg{width:100%;transition:height 0.5s ease;}
        .da4-bar-seg.out{background:#ea580c;}
        .da4-bar-seg.transfer{background:#7c3aed;}
        .da4-chart-legend{display:flex;gap:1.25rem;padding:1rem 1.5rem;font-size:0.8rem;color:#64748b;font-weight:600;align-items:center;flex-wrap:wrap;}
        .da4-legend-item{display:flex;align-items:center;gap:6px;}
        .da4-legend-dot{width:10px;height:10px;border-radius:3px;display:inline-block;}
        .da4-legend-hint{margin-left:auto;font-size:0.75rem;font-weight:500;font-style:italic;color:#94a3b8;}

        /* ── activity list ── */
        .da4-activity-list{display:flex;flex-direction:column;padding:0;margin:0;}
        .da4-activity-item{display:flex;gap:1rem;padding:1.1rem 1.15rem;border-bottom:1px dashed #f1f5f9;align-items:center;transition:background 0.2s;}
        .da4-activity-item:hover{background:#f8fafc;}
        .da4-activity-item:last-child{border-bottom:none;}
        .da4-act-time{display:flex;flex-direction:column;align-items:center;justify-content:center;width:44px;flex-shrink:0;}
        .da4-act-time .date{font-size:0.8rem;font-weight:800;color:#0f172a;}
        .da4-act-time .time{font-size:0.7rem;color:#64748b;font-weight:600;}
        .da4-act-content{flex:1;min-width:0;}
        .da4-act-head{display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap;}
        .da4-act-prod{font-size:0.85rem;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;}
        .da4-act-badge{padding:0.15rem 0.4rem;border-radius:4px;font-size:0.65rem;font-weight:900;letter-spacing:0.05em;}
        .da4-act-in{background:#d1fae5;color:#065f46;}
        .da4-act-out{background:#fee2e2;color:#991b1b;}
        .da4-act-other{background:#dbeafe;color:#1e40af;}
        .da4-act-meta{font-size:0.75rem;color:#64748b;}
        .da4-mono{font-family:monospace;color:#94a3b8;}
        .da4-act-qty{font-size:1.1rem;font-weight:900;font-family:monospace;letter-spacing:-0.05em;padding-left:0.5rem;text-align:right;}
        .da4-text-success{color:#10b981;}
        .da4-text-danger{color:#ef4444;}

        /* ── quick actions ── */
        .da4-actions{display:flex;gap:.75rem;flex-wrap:wrap;margin-top:.25rem;}
        .da4-action-btn{display:inline-flex;align-items:center;gap:.5rem;padding:.7rem 1.15rem;border-radius:10px;font-size:.85rem;font-weight:700;text-decoration:none;border:none;cursor:pointer;transition:all .2s;}
        .da4-action-btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.1);}
        .da4-action-btn.primary{background:linear-gradient(135deg,#ea580c,#c2410c);color:#fff;}
        .da4-action-btn.violet{background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;}
        .da4-action-btn.secondary{background:#fff;color:#475569;border:1px solid #e2e8f0;}
        .da4-action-btn.secondary:hover{background:#f8fafc;}

        /* ── empty state ── */
        .da4-empty{padding:2rem 1.5rem;text-align:center;color:#94a3b8;font-size:.85rem;font-weight:500;background:#f8fafc;border-radius:8px;border:1px dashed #e2e8f0;margin:1.25rem;}

        /* ── responsive ── */
        @media(max-width:1100px){
            .da4-grid{grid-template-columns:1fr;}
            .da4-inner-grid{grid-template-columns:1fr 1fr;}
        }
        @media(max-width:768px){
            .da4-page{padding:1rem;}
            .da4-stats{grid-template-columns:repeat(2,1fr);}
            .da4-inner-grid{grid-template-columns:1fr;}
            .da4-card-hdr{flex-direction:column;align-items:flex-start;gap:.5rem;}
        }
        @media(max-width:480px){
            .da4-stats{grid-template-columns:1fr;}
        }
    </style>

    <div class="da4-page">

        {{-- ─── HEADER ─── --}}
        <div class="da4-page-header">
            <div>
                <div class="da4-page-title">Dashboard Gudang Keluar & Distribusi</div>
                <div class="da4-page-sub">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-3px;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} • Gudang ID: {{ (int) $warehouseId }}
                </div>
            </div>
            <div style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
                <div style="display:inline-flex;align-items:center;gap:8px;padding:0.35rem 1rem 0.35rem 0.35rem;background:rgba(255,255,255,0.8);border:1px solid rgba(255,255,255,0.9);border-radius:999px;box-shadow:0 2px 4px rgba(0,0,0,0.02);">
                    <div style="width:28px;height:28px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#ea580c;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <span style="font-size:0.7rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;border-right:1px solid #e2e8f0;padding-right:8px;">Admin 4</span>
                    <span style="font-size:0.85rem;font-weight:700;color:#0f172a;">{{ auth()->user()->name }}</span>
                </div>

                @php
                    $opStatus = (string) ($opnameToday['status'] ?? 'missing');
                    $opClass = match($opStatus) {
                        'approved' => 'da4-status-success',
                        'submitted' => 'da4-status-info',
                        default => 'da4-status-warning',
                    };
                    $opLabel = match($opStatus) {
                        'approved' => 'APPROVED',
                        'submitted' => 'SUBMITTED',
                        default => 'BELUM DIBUAT',
                    };
                @endphp
                <div class="da4-status {{ $opClass }}">
                    Opname Hari Ini: <strong>{{ $opLabel }}</strong>
                </div>
            </div>
        </div>

        {{-- ─── ALERTS ─── --}}
        @if($produkMinStok > 0 || $produkExpired > 0)
        <div class="da4-alerts">
            @if($produkMinStok > 0)
            <a href="{{ route('gudang.minstok') }}" class="da4-alert da4-alert-warning">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                <span><strong>{{ $produkMinStok }}</strong> produk stok hampir habis (di bawah minimum)</span>
                <span class="da4-alert-link">Cek Sekarang &rarr;</span>
            </a>
            @endif
            @if($produkExpired > 0)
            <a href="{{ route('gudang.expired') }}" class="da4-alert da4-alert-danger">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                <span><strong>{{ $produkExpired }}</strong> batch barang akan expired dalam 30 hari</span>
                <span class="da4-alert-link">Cek Sekarang &rarr;</span>
            </a>
            @endif
        </div>
        @endif

        {{-- ─── STAT CARDS ─── --}}
        <div class="da4-stats">
            <div class="da4-stat">
                <div class="da4-stat-ico orange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                </div>
                <div class="da4-stat-info">
                    <div class="da4-stat-label">Pengeluaran</div>
                    <div class="da4-stat-val orange">{{ number_format((int) $pengeluaranHariIni) }}</div>
                    <span class="da4-stat-pill da4-pill-orange">Hari Ini</span>
                </div>
            </div>
            <div class="da4-stat">
                <div class="da4-stat-ico violet">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                </div>
                <div class="da4-stat-info">
                    <div class="da4-stat-label">Transfer Gudang</div>
                    <div class="da4-stat-val violet">{{ number_format((int) $transferGudangHariIni) }}</div>
                    <span class="da4-stat-pill da4-pill-violet">Hari Ini</span>
                </div>
            </div>
            <div class="da4-stat">
                <div class="da4-stat-ico slate">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                </div>
                <div class="da4-stat-info">
                    <div class="da4-stat-label">Opname Stok</div>
                    <div class="da4-stat-val slate">{{ number_format((int) $opnameHariIni) }}</div>
                    <span class="da4-stat-pill da4-pill-gray">Adjustment</span>
                </div>
            </div>
            <div class="da4-stat">
                <div class="da4-stat-ico amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                </div>
                <div class="da4-stat-info">
                    <div class="da4-stat-label">Alert Min Stok</div>
                    <div class="da4-stat-val amber">{{ number_format((int) $produkMinStok) }}</div>
                    <span class="da4-stat-pill da4-pill-amber">Perlu perhatian</span>
                </div>
            </div>
            <div class="da4-stat">
                <div class="da4-stat-ico rose">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
                <div class="da4-stat-info">
                    <div class="da4-stat-label">Expired / Mendekati</div>
                    <div class="da4-stat-val rose">{{ number_format((int) $produkExpired) }}</div>
                    <span class="da4-stat-pill da4-pill-rose">&le; 30 Hari</span>
                </div>
            </div>
        </div>

        {{-- ─── QUICK ACTIONS ─── --}}
        <div class="da4-actions">
            @can('view_stok_gudang')
            <a href="{{ route('gudang.stok') }}" class="da4-action-btn primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                Cek Stok Gudang
            </a>
            @endcan
            @can('view_pengeluaran_barang')
            <a href="{{ route('gudang.pengeluaran') }}" class="da4-action-btn violet">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                Input Pengeluaran / Mutasi
            </a>
            @endcan
            @can('view_stok_gudang')
            <a href="{{ route('gudang.transfer') }}" class="da4-action-btn secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                Transfer Cabang
            </a>
            @endcan
        </div>

        {{-- ─── MAIN GRID ─── --}}
        <div class="da4-grid">
            {{-- ─── LEFT COLUMN ─── --}}
            <div class="da4-col-left">
                {{-- ─── TREND CHART CARD ─── --}}
                <div class="da4-card">
                    <div class="da4-card-hdr">
                        <div>
                            <div class="da4-card-title">Tren Pengeluaran 14 Hari Terakhir</div>
                            <div class="da4-card-sub">Perbandingan Barang Keluar vs Transfer Antar Gudang</div>
                        </div>
                    </div>
                    <div class="da4-chart-container" aria-label="Grafik tren pengeluaran">
                        @foreach($warehouseOutboundTrend as $row)
                            @php
                                $pctTotal = max(2, (int) round($row['pct_total'] ?? 0));
                                $pctOut = (float) ($row['pct_out'] ?? 0);
                                $pctTransfer = (float) ($row['pct_transfer'] ?? 0);
                                $isToday = ($row['date'] ?? '') === now()->toDateString();
                            @endphp
                            <div class="da4-bar-group {{ $isToday ? 'is-today' : '' }}" title="{{ $row['label'] }}: Total {{ (int) $row['total'] }} (Keluar {{ (int) $row['out'] }}, Transfer {{ (int) $row['transfer'] }})" style="height: {{ $pctTotal }}%;">
                                <div class="da4-bar-seg out" style="height: {{ $pctOut }}%;"></div>
                                <div class="da4-bar-seg transfer" style="height: {{ $pctTransfer }}%;"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="da4-chart-legend">
                        <div class="da4-legend-item"><span class="da4-legend-dot" style="background:#ea580c;"></span> Barang Keluar</div>
                        <div class="da4-legend-item"><span class="da4-legend-dot" style="background:#7c3aed;"></span> Transfer</div>
                        <div class="da4-legend-hint">Hover batang untuk melihat detail angka</div>
                    </div>
                </div>

                {{-- ─── MIN STOCK & EXPIRED GRID ─── --}}
                <div class="da4-inner-grid">
                    {{-- ─── MIN STOCK CARD ─── --}}
                    <div class="da4-card">
                        <div class="da4-card-hdr">
                            <div>
                                <div class="da4-card-title">Produk Min Stok</div>
                            </div>
                        </div>
                        @if($topMinStockProducts->count() === 0)
                            <div class="da4-empty">Stok terpantau aman</div>
                        @else
                            <div style="overflow-x:auto;">
                                <table class="da4-tbl">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th style="text-align:right;">Stok / Min</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topMinStockProducts as $p)
                                            <tr>
                                                <td>
                                                    <div class="da4-item-name">{{ $p->name }}</div>
                                                    <div class="da4-item-meta">{{ $p->sku ?: '-' }}</div>
                                                </td>
                                                <td style="text-align:right;">
                                                    <span style="display:inline-flex;align-items:center;gap:4px;padding:0.25rem 0.6rem;border-radius:6px;font-size:0.75rem;font-weight:600;background:#fffbeb;color:#92400e;">
                                                        @if(($maskStock ?? false) === true)
                                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> / {{ (int) $p->min_stock }}
                                                        @else
                                                            <strong>{{ (int) $p->stock }}</strong> / {{ (int) $p->min_stock }}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- ─── EXPIRED CARD ─── --}}
                    <div class="da4-card">
                        <div class="da4-card-hdr">
                            <div>
                                <div class="da4-card-title">Mendekati Expired</div>
                            </div>
                        </div>
                        @if($expiringSoon->count() === 0)
                            <div class="da4-empty">Tidak ada stok rawan expired</div>
                        @else
                            <div style="overflow-x:auto;">
                                <table class="da4-tbl">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th style="text-align:right;">Tgl Expired</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expiringSoon as $ps)
                                            <tr>
                                                <td>
                                                    <div class="da4-item-name">{{ $ps->product?->name ?? '-' }}</div>
                                                    <div class="da4-item-meta">{{ $ps->product?->sku ?? '-' }}</div>
                                                </td>
                                                <td style="text-align:right;">
                                                    <span style="display:inline-flex;align-items:center;gap:4px;padding:0.25rem 0.6rem;border-radius:6px;font-size:0.75rem;font-weight:800;background:#fee2e2;color:#991b1b;font-family:monospace;">
                                                        {{ optional($ps->expired_date)->format('d/m/Y') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ─── RIGHT COLUMN ─── --}}
            <div class="da4-col-right">
                {{-- ─── ACTIVITY CARD ─── --}}
                <div class="da4-card">
                    <div class="da4-card-hdr">
                        <div>
                            <div class="da4-card-title">Aktivitas Gudang Terbaru</div>
                            <div class="da4-card-sub">Pergerakan fisik (In/Out) Gudang {{ (int) $warehouseId }}</div>
                        </div>
                    </div>
                    @if($recentMovements->count() === 0)
                        <div class="da4-empty">Belum ada aktivitas terekam</div>
                    @else
                        <div class="da4-activity-list">
                            @foreach($recentMovements as $m)
                                @php
                                    $type = (string) $m->type;
                                    $typeLabel = $type === 'in' ? 'IN' : ($type === 'out' ? 'OUT' : strtoupper($type));
                                    $pillClass = $type === 'in' ? 'da4-act-in' : ($type === 'out' ? 'da4-act-out' : 'da4-act-other');
                                    $qtySign = $type === 'out' ? '-' : '+';
                                    $qtyClass = $type === 'out' ? 'da4-text-danger' : 'da4-text-success';
                                @endphp
                                <div class="da4-activity-item">
                                    <div class="da4-act-time">
                                        <div class="date">{{ $m->created_at->format('d/m') }}</div>
                                        <div class="time">{{ $m->created_at->format('H:i') }}</div>
                                    </div>
                                    <div class="da4-act-content">
                                        <div class="da4-act-head">
                                            <span class="da4-act-badge {{ $pillClass }}">{{ $typeLabel }}</span>
                                            <span class="da4-act-prod">{{ $m->product?->name ?? '-' }}</span>
                                        </div>
                                        <div class="da4-act-meta">
                                            Ref: <span class="da4-mono">{{ $m->reference_number ?: '-' }}</span>
                                            @if($m->user?->name)
                                                &bull; {{ $m->user->name }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="da4-act-qty {{ $qtyClass }}">
                                        {{ $qtySign }}{{ (int) $m->quantity }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
