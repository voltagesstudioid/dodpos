<x-app-layout>
    <x-slot name="header">Dashboard Gudang Masuk</x-slot>

    <style>
        .so-page{padding:1.5rem;display:flex;flex-direction:column;gap:1.25rem;font-family:'Plus Jakarta Sans',system-ui,-apple-system,sans-serif;}
        .so-page-header{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;}
        .so-page-title{font-size:1.35rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;}
        .so-page-sub{font-size:.82rem;color:#64748b;margin-top:.15rem;}

        /* ── stat cards ── */
        .so-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(185px,1fr));gap:.85rem;}
        .so-stat{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:1.1rem 1.15rem;display:flex;align-items:center;gap:.9rem;transition:all .2s;}
        .so-stat:hover{border-color:#cbd5e1;box-shadow:0 2px 10px rgba(0,0,0,.04);}
        .so-stat-ico{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .so-stat-ico svg{width:20px;height:20px;}
        .so-stat-ico.indigo{background:#eef2ff;color:#6366f1;}
        .so-stat-ico.amber{background:#fffbeb;color:#d97706;}
        .so-stat-ico.blue{background:#eff6ff;color:#2563eb;}
        .so-stat-ico.emerald{background:#ecfdf5;color:#059669;}
        .so-stat-ico.rose{background:#fff1f2;color:#e11d48;}
        .so-stat-info{display:flex;flex-direction:column;gap:.1rem;min-width:0;}
        .so-stat-label{font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;}
        .so-stat-val{font-size:1.45rem;font-weight:800;line-height:1.1;}
        .so-stat-val.indigo{color:#4f46e5;}
        .so-stat-val.amber{color:#d97706;}
        .so-stat-val.blue{color:#2563eb;}
        .so-stat-val.emerald{color:#059669;}
        .so-stat-val.rose{color:#e11d48;}
        .so-stat-pill{font-size:.65rem;font-weight:600;padding:.15rem .5rem;border-radius:6px;display:inline-block;width:fit-content;margin-top:.15rem;}
        .so-pill-gray{background:#f1f5f9;color:#64748b;}
        .so-pill-amber{background:#fef3c7;color:#92400e;}
        .so-pill-blue{background:#dbeafe;color:#1e40af;}
        .so-pill-emerald{background:#d1fae5;color:#065f46;}
        .so-pill-rose{background:#ffe4e6;color:#9f1239;}

        /* ── table card ── */
        .so-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;}
        .so-card-hdr{padding:1rem 1.15rem;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;}
        .so-card-title{font-size:.88rem;font-weight:800;color:#0f172a;}
        .so-card-sub{font-size:.75rem;color:#94a3b8;margin-top:.1rem;}
        .so-tbl{width:100%;border-collapse:collapse;}
        .so-tbl thead{background:#f8fafc;}
        .so-tbl th{padding:.65rem 1rem;font-size:.7rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;text-align:left;border-bottom:1px solid #e2e8f0;}
        .so-tbl td{padding:.75rem 1rem;font-size:.83rem;color:#334155;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
        .so-tbl tbody tr{transition:background .12s;}
        .so-tbl tbody tr:hover{background:#f8fafc;}
        .so-tbl tbody tr:last-child td{border-bottom:none;}
        .so-item-name{font-weight:700;color:#0f172a;}
        .so-item-meta{font-size:.73rem;color:#94a3b8;margin-top:.1rem;}

        /* ── status pills ── */
        .so-status{font-size:.7rem;font-weight:700;padding:.22rem .65rem;border-radius:7px;display:inline-flex;align-items:center;gap:.3rem;}
        .so-status::before{content:'';width:6px;height:6px;border-radius:50%;flex-shrink:0;}
        .so-status-success{background:#d1fae5;color:#065f46;}.so-status-success::before{background:#059669;}
        .so-status-info{background:#dbeafe;color:#1e40af;}.so-status-info::before{background:#2563eb;}
        .so-status-warning{background:#fef3c7;color:#92400e;}.so-status-warning::before{background:#d97706;}

        /* ── dashboard layout ── */
        .so-grid{display:grid;grid-template-columns:1.4fr 0.6fr;gap:1.25rem;}
        .so-col-left{display:flex;flex-direction:column;gap:1.25rem;}
        .so-col-right{display:flex;flex-direction:column;gap:1.25rem;}
        .so-inner-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;}

        /* ── chart ── */
        .so-chart-container{display:flex;align-items:flex-end;gap:8px;height:200px;margin:1rem 1rem 0 1rem;padding:0 0.5rem;border-bottom:2px solid #f1f5f9;}
        .so-bar-group{flex:1;display:flex;flex-direction:column;justify-content:flex-end;min-width:10px;border-radius:6px 6px 0 0;overflow:hidden;background:rgba(148,163,184,0.1);cursor:pointer;transition:all 0.2s;}
        .so-bar-group:hover{filter:brightness(0.9);}
        .so-bar-group.is-today{background:#e0e7ff;box-shadow:inset 0 -4px 0 #6366f1;}
        .so-bar-seg{width:100%;transition:height 0.5s ease;}
        .so-bar-seg.po{background:#2563eb;}
        .so-bar-seg.nonpo{background:#10b981;}
        .so-chart-legend{display:flex;gap:1.25rem;padding:1rem 1.5rem;font-size:0.8rem;color:#64748b;font-weight:600;align-items:center;flex-wrap:wrap;}
        .so-legend-item{display:flex;align-items:center;gap:6px;}
        .so-legend-dot{width:10px;height:10px;border-radius:3px;display:inline-block;}
        .so-legend-hint{margin-left:auto;font-size:0.75rem;font-weight:500;font-style:italic;color:#94a3b8;}

        /* ── activity list ── */
        .so-activity-list{display:flex;flex-direction:column;padding:0;margin:0;}
        .so-activity-item{display:flex;gap:1rem;padding:1.1rem 1.15rem;border-bottom:1px dashed #f1f5f9;align-items:center;transition:background 0.2s;}
        .so-activity-item:hover{background:#f8fafc;}
        .so-activity-item:last-child{border-bottom:none;}
        .so-act-time{display:flex;flex-direction:column;align-items:center;justify-content:center;width:44px;flex-shrink:0;}
        .so-act-time .date{font-size:0.8rem;font-weight:800;color:#0f172a;}
        .so-act-time .time{font-size:0.7rem;color:#64748b;font-weight:600;}
        .so-act-content{flex:1;min-width:0;}
        .so-act-head{display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap;}
        .so-act-prod{font-size:0.85rem;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;}
        .so-act-badge{padding:0.15rem 0.4rem;border-radius:4px;font-size:0.65rem;font-weight:900;letter-spacing:0.05em;}
        .so-act-in{background:#d1fae5;color:#065f46;}
        .so-act-out{background:#fee2e2;color:#991b1b;}
        .so-act-other{background:#dbeafe;color:#1e40af;}
        .so-act-meta{font-size:0.75rem;color:#64748b;}
        .so-mono{font-family:monospace;color:#94a3b8;}
        .so-act-qty{font-size:1.1rem;font-weight:900;font-family:monospace;letter-spacing:-0.05em;padding-left:0.5rem;text-align:right;}
        .text-success{color:#10b981;}
        .text-danger{color:#ef4444;}

        /* ── empty state ── */
        .so-empty{padding:2rem 1.5rem;text-align:center;color:#94a3b8;font-size:.85rem;font-weight:500;background:#f8fafc;border-radius:8px;border:1px dashed #e2e8f0;margin:1.25rem;}

        /* ── responsive ── */
        @media(max-width:1100px){
            .so-grid{grid-template-columns:1fr;}
            .so-inner-grid{grid-template-columns:1fr 1fr;}
        }
        @media(max-width:768px){
            .so-page{padding:1rem;}
            .so-stats{grid-template-columns:repeat(2,1fr);}
            .so-inner-grid{grid-template-columns:1fr;}
            .so-card-hdr{flex-direction:column;align-items:flex-start;gap:.5rem;}
        }
        @media(max-width:480px){
            .so-stats{grid-template-columns:1fr;}
        }
    </style>

    <div class="so-page">

        {{-- ─── HEADER ─── --}}
        <div class="so-page-header">
            <div>
                <div class="so-page-title">Dashboard Gudang Masuk</div>
                <div class="so-page-sub">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-3px;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} • Gudang ID: {{ (int) $warehouseId }}
                </div>
            </div>
            <div style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
                <div style="display:inline-flex;align-items:center;gap:8px;padding:0.35rem 1rem 0.35rem 0.35rem;background:rgba(255,255,255,0.8);border:1px solid rgba(255,255,255,0.9);border-radius:999px;box-shadow:0 2px 4px rgba(0,0,0,0.02);">
                    <div style="width:28px;height:28px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#6366f1;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <span style="font-size:0.7rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;border-right:1px solid #e2e8f0;padding-right:8px;">Admin 3</span>
                    <span style="font-size:0.85rem;font-weight:700;color:#0f172a;">{{ auth()->user()->name }}</span>
                </div>

                @php
                    $opStatus = (string) ($opnameToday['status'] ?? 'missing');
                    $opClass = match($opStatus) {
                        'approved' => 'so-status-success',
                        'submitted' => 'so-status-info',
                        default => 'so-status-warning',
                    };
                    $opLabel = match($opStatus) {
                        'approved' => 'APPROVED',
                        'submitted' => 'SUBMITTED',
                        default => 'BELUM DIBUAT',
                    };
                @endphp
                <div class="so-status {{ $opClass }}">
                    Opname Hari Ini: <strong>{{ $opLabel }}</strong>
                </div>
            </div>
        </div>

        {{-- ─── STAT CARDS ─── --}}
        <div class="so-stats">
            <div class="so-stat">
                <div class="so-stat-ico indigo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">Penerimaan Non-PO</div>
                    <div class="so-stat-val indigo">{{ number_format((int) $penerimaanHariIni) }}</div>
                    <span class="so-stat-pill so-pill-blue">Hari Ini</span>
                </div>
            </div>
            <div class="so-stat">
                <div class="so-stat-ico blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">PO Masuk</div>
                    <div class="so-stat-val blue">{{ number_format((int) $poHariIni) }}</div>
                    <span class="so-stat-pill so-pill-blue">Hari Ini</span>
                </div>
            </div>
            <div class="so-stat">
                <div class="so-stat-ico amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">Alert Min Stok</div>
                    <div class="so-stat-val amber">{{ number_format((int) $produkMinStok) }}</div>
                    <span class="so-stat-pill so-pill-amber">Perlu perhatian</span>
                </div>
            </div>
            <div class="so-stat">
                <div class="so-stat-ico rose">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">Expired / Mendekati</div>
                    <div class="so-stat-val rose">{{ number_format((int) $produkExpired) }}</div>
                    <span class="so-stat-pill so-pill-rose">≤ 30 Hari</span>
                </div>
            </div>
        </div>

        {{-- ─── MAIN GRID ─── --}}
        <div class="so-grid">
            {{-- ─── LEFT COLUMN ─── --}}
            <div class="so-col-left">
                {{-- ─── TREND CHART CARD ─── --}}
                <div class="so-card">
                    <div class="so-card-hdr">
                        <div>
                            <div class="so-card-title">Tren Penerimaan 14 Hari Terakhir</div>
                            <div class="so-card-sub">Perbandingan dokumen Non-PO vs PO Receipt</div>
                        </div>
                    </div>
                    <div class="so-chart-container" aria-label="Grafik tren penerimaan">
                        @foreach($warehouseInboundTrend as $row)
                            @php
                                $pctTotal = max(2, (int) round($row['pct_total'] ?? 0));
                                $pctNonPo = (float) ($row['pct_non_po'] ?? 0);
                                $pctPo = (float) ($row['pct_po'] ?? 0);
                                $isToday = ($row['date'] ?? '') === now()->toDateString();
                            @endphp
                            <div class="so-bar-group {{ $isToday ? 'is-today' : '' }}" title="{{ $row['label'] }}: Total {{ (int) $row['total'] }} (Non-PO {{ (int) $row['non_po'] }}, PO {{ (int) $row['po'] }})" style="height: {{ $pctTotal }}%;">
                                <div class="so-bar-seg po" style="height: {{ $pctPo }}%;"></div>
                                <div class="so-bar-seg nonpo" style="height: {{ $pctNonPo }}%;"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="so-chart-legend">
                        <div class="so-legend-item"><span class="so-legend-dot" style="background:#10b981;"></span> Non-PO</div>
                        <div class="so-legend-item"><span class="so-legend-dot" style="background:#2563eb;"></span> PO Receipt</div>
                        <div class="so-legend-hint">Hover batang untuk melihat detail angka</div>
                    </div>
                </div>

                {{-- ─── MIN STOCK & EXPIRED GRID ─── --}}
                <div class="so-inner-grid">
                    {{-- ─── MIN STOCK CARD ─── --}}
                    <div class="so-card">
                        <div class="so-card-hdr">
                            <div>
                                <div class="so-card-title">Produk Min Stok</div>
                            </div>
                        </div>
                        @if($topMinStockProducts->count() === 0)
                            <div class="so-empty">Stok terpantau aman</div>
                        @else
                            <div style="overflow-x:auto;">
                                <table class="so-tbl">
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
                                                    <div class="so-item-name">{{ $p->name }}</div>
                                                    <div class="so-item-meta">{{ $p->sku ?: '-' }}</div>
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
                    <div class="so-card">
                        <div class="so-card-hdr">
                            <div>
                                <div class="so-card-title">Mendekati Expired</div>
                            </div>
                        </div>
                        @if($expiringSoon->count() === 0)
                            <div class="so-empty">Tidak ada stok rawan expired</div>
                        @else
                            <div style="overflow-x:auto;">
                                <table class="so-tbl">
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
                                                    <div class="so-item-name">{{ $ps->product?->name ?? '-' }}</div>
                                                    <div class="so-item-meta">{{ $ps->product?->sku ?? '-' }}</div>
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
            <div class="so-col-right">
                {{-- ─── ACTIVITY CARD ─── --}}
                <div class="so-card">
                    <div class="so-card-hdr">
                        <div>
                            <div class="so-card-title">Aktivitas Gudang Terbaru</div>
                            <div class="so-card-sub">Pergerakan fisik (In/Out) Gudang {{ (int) $warehouseId }}</div>
                        </div>
                    </div>
                    @if($recentMovements->count() === 0)
                        <div class="so-empty">Belum ada aktivitas terekam</div>
                    @else
                        <div class="so-activity-list">
                            @foreach($recentMovements as $m)
                                @php
                                    $type = (string) $m->type;
                                    $typeLabel = $type === 'in' ? 'IN' : ($type === 'out' ? 'OUT' : strtoupper($type));
                                    $pillClass = $type === 'in' ? 'so-act-in' : ($type === 'out' ? 'so-act-out' : 'so-act-other');
                                    $qtySign = $type === 'out' ? '-' : '+';
                                    $qtyClass = $type === 'out' ? 'text-danger' : 'text-success';
                                @endphp
                                <div class="so-activity-item">
                                    <div class="so-act-time">
                                        <div class="date">{{ $m->created_at->format('d/m') }}</div>
                                        <div class="time">{{ $m->created_at->format('H:i') }}</div>
                                    </div>
                                    <div class="so-act-content">
                                        <div class="so-act-head">
                                            <span class="so-act-badge {{ $pillClass }}">{{ $typeLabel }}</span>
                                            <span class="so-act-prod">{{ $m->product?->name ?? '-' }}</span>
                                        </div>
                                        <div class="so-act-meta">
                                            Ref: <span class="so-mono">{{ $m->reference_number ?: '-' }}</span>
                                            @if($m->user?->name)
                                                • {{ $m->user->name }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="so-act-qty {{ $qtyClass }}">
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
