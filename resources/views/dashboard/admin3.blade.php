<x-app-layout>
    <x-slot name="header">Dashboard Admin 3</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HERO BANNER --}}
            <div class="tr-hero-banner">
                <div class="tr-hero-content">
                    <h1 class="tr-hero-title">Dashboard Gudang Masuk</h1>
                    <p class="tr-hero-subtitle">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} <span class="tr-dot-divider">•</span> Gudang ID: {{ (int) $warehouseId }}
                    </p>
                </div>
                
                @php
                    $opStatus = (string) ($opnameToday['status'] ?? 'missing');
                    $opClass = match($opStatus) {
                        'approved' => 'tr-badge-success',
                        'submitted' => 'tr-badge-info',
                        default => 'tr-badge-warning',
                    };
                    $opLabel = match($opStatus) {
                        'approved' => 'APPROVED',
                        'submitted' => 'SUBMITTED',
                        default => 'BELUM DIBUAT',
                    };
                @endphp
                
                <div class="tr-hero-badges">
                    <div class="tr-user-chip">
                        <div class="tr-avatar-circle">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <span class="tr-role">Admin 3</span>
                        <span class="tr-name">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="tr-opname-chip {{ $opClass }}">
                        <span class="tr-label">Opname Hari Ini:</span>
                        <span class="tr-value">{{ $opLabel }}</span>
                    </div>
                </div>
            </div>

            {{-- KPI STATS GRID --}}
            <div class="tr-kpi-grid">
                <div class="tr-kpi-card">
                    <div class="tr-kpi-info">
                        <div class="tr-kpi-value">{{ number_format((int) $penerimaanHariIni) }}</div>
                        <div class="tr-kpi-label">Penerimaan Non-PO <span class="tr-time-tag">(Hari Ini)</span></div>
                    </div>
                    <div class="tr-kpi-icon bg-indigo">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    </div>
                </div>
                <div class="tr-kpi-card">
                    <div class="tr-kpi-info">
                        <div class="tr-kpi-value">{{ number_format((int) $poHariIni) }}</div>
                        <div class="tr-kpi-label">PO Masuk / Baru <span class="tr-time-tag">(Hari Ini)</span></div>
                    </div>
                    <div class="tr-kpi-icon bg-blue">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                </div>
                <div class="tr-kpi-card">
                    <div class="tr-kpi-info">
                        <div class="tr-kpi-value text-warning">{{ number_format((int) $produkMinStok) }}</div>
                        <div class="tr-kpi-label">Alert Min. Stok</div>
                    </div>
                    <div class="tr-kpi-icon bg-warning">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                    </div>
                </div>
                <div class="tr-kpi-card">
                    <div class="tr-kpi-info">
                        <div class="tr-kpi-value text-danger">{{ number_format((int) $produkExpired) }}</div>
                        <div class="tr-kpi-label">Expired / Mendekati <span class="tr-time-tag">(≤ 30 Hr)</span></div>
                    </div>
                    <div class="tr-kpi-icon bg-danger">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                </div>
            </div>

            {{-- MAIN DASHBOARD LAYOUT --}}
            <div class="tr-dashboard-layout">
                
                {{-- LEFT COLUMN (KIRI) --}}
                <div class="tr-col-left">
                    
                    {{-- TREND CHART --}}
                    <div class="tr-panel">
                        <div class="tr-panel-header">
                            <div>
                                <h3 class="tr-panel-title">Tren Penerimaan 14 Hari Terakhir</h3>
                                <p class="tr-panel-desc">Perbandingan dokumen Non-PO vs PO Receipt</p>
                            </div>
                        </div>
                        
                        <div class="tr-chart-container" aria-label="Grafik tren penerimaan">
                            @foreach($warehouseInboundTrend as $row)
                                @php
                                    $pctTotal = max(2, (int) round($row['pct_total'] ?? 0));
                                    $pctNonPo = (float) ($row['pct_non_po'] ?? 0);
                                    $pctPo = (float) ($row['pct_po'] ?? 0);
                                    $isToday = ($row['date'] ?? '') === now()->toDateString();
                                @endphp
                                <div class="tr-chart-bar-group {{ $isToday ? 'is-today' : '' }}" title="{{ $row['label'] }}: Total {{ (int) $row['total'] }} (Non-PO {{ (int) $row['non_po'] }}, PO {{ (int) $row['po'] }})" style="height: {{ $pctTotal }}%;">
                                    <div class="tr-seg tr-seg-po" style="height: {{ $pctPo }}%;"></div>
                                    <div class="tr-seg tr-seg-nonpo" style="height: {{ $pctNonPo }}%;"></div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="tr-chart-legend">
                            <div class="tr-legend-item"><span class="tr-legend-dot bg-nonpo"></span> Non-PO</div>
                            <div class="tr-legend-item"><span class="tr-legend-dot bg-po"></span> PO Receipt</div>
                            <div class="tr-legend-hint">Hover batang untuk melihat detail angka.</div>
                        </div>
                    </div>

                    {{-- ALERTS TABLES (MIN STOK & EXPIRED) --}}
                    <div class="tr-alert-tables-grid">
                        
                        {{-- Min Stok --}}
                        <div class="tr-panel tr-panel-compact">
                            <div class="tr-panel-header">
                                <h3 class="tr-panel-title">Produk Min. Stok</h3>
                            </div>
                            @if($topMinStockProducts->count() === 0)
                                <div class="tr-empty-box">Stok terpantau aman.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="tr-table-widget">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th class="r">Stok / Min</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topMinStockProducts as $p)
                                                <tr>
                                                    <td>
                                                        <div class="tr-item-name">{{ $p->name }}</div>
                                                        <div class="tr-item-sku">{{ $p->sku ?: '-' }}</div>
                                                    </td>
                                                    <td class="r">
                                                        <div class="tr-stock-pill tr-bg-warning-soft">
                                                            @if(($maskStock ?? false) === true)
                                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> / {{ (int) $p->min_stock }}
                                                            @else
                                                                <strong>{{ (int) $p->stock }}</strong> / {{ (int) $p->min_stock }}
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        {{-- Expired --}}
                        <div class="tr-panel tr-panel-compact">
                            <div class="tr-panel-header">
                                <h3 class="tr-panel-title">Mendekati Expired (≤ 30 Hr)</h3>
                            </div>
                            @if($expiringSoon->count() === 0)
                                <div class="tr-empty-box">Tidak ada stok rawan expired.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="tr-table-widget">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th class="r">Tgl Expired</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($expiringSoon as $ps)
                                                <tr>
                                                    <td>
                                                        <div class="tr-item-name">{{ $ps->product?->name ?? '-' }}</div>
                                                        <div class="tr-item-sku">{{ $ps->product?->sku ?? '-' }}</div>
                                                    </td>
                                                    <td class="r">
                                                        <span class="tr-stock-pill tr-bg-danger-soft">
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

                {{-- RIGHT COLUMN (KANAN): AKTIVITAS --}}
                <div class="tr-col-right">
                    <div class="tr-panel tr-h-full">
                        <div class="tr-panel-header">
                            <div>
                                <h3 class="tr-panel-title">Aktivitas Gudang Terbaru</h3>
                                <p class="tr-panel-desc">Pergerakan fisik (In/Out) Gudang {{ (int) $warehouseId }}</p>
                            </div>
                        </div>

                        @if($recentMovements->count() === 0)
                            <div class="tr-empty-box" style="margin: 1.5rem;">Belum ada aktivitas terekam.</div>
                        @else
                            <div class="tr-activity-list">
                                @foreach($recentMovements as $m)
                                    @php
                                        $type = (string) $m->type;
                                        $typeLabel = $type === 'in' ? 'IN' : ($type === 'out' ? 'OUT' : strtoupper($type));
                                        $pillClass = $type === 'in' ? 'tr-act-in' : ($type === 'out' ? 'tr-act-out' : 'tr-act-other');
                                        $qtySign = $type === 'out' ? '-' : '+';
                                        $qtyClass = $type === 'out' ? 'text-danger' : 'text-success';
                                    @endphp
                                    <div class="tr-activity-item">
                                        <div class="tr-act-time">
                                            <div class="date">{{ $m->created_at->format('d/m') }}</div>
                                            <div class="time">{{ $m->created_at->format('H:i') }}</div>
                                        </div>
                                        <div class="tr-act-content">
                                            <div class="tr-act-head">
                                                <span class="tr-act-badge {{ $pillClass }}">{{ $typeLabel }}</span>
                                                <span class="tr-act-prod">{{ $m->product?->name ?? '-' }}</span>
                                            </div>
                                            <div class="tr-act-meta">
                                                Ref: <span class="tr-font-mono">{{ $m->reference_number ?: '-' }}</span>
                                                @if($m->user?->name)
                                                    <span class="tr-dot-divider">•</span> {{ $m->user->name }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tr-act-qty {{ $qtyClass }}">
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
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            
            --tr-primary: #3b82f6; /* Blue */
            --tr-primary-light: #eff6ff;
            --tr-indigo: #6366f1; /* Indigo */
            --tr-indigo-light: #e0e7ff;
            
            --tr-success: #10b981;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            
            --tr-warning: #f59e0b;
            --tr-warning-bg: #fffbeb;
            --tr-warning-text: #b45309;
            
            --tr-danger: #ef4444;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #991b1b;
            
            --tr-radius-xl: 18px;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --tr-shadow-card: 0 10px 30px -5px rgba(2, 6, 23, 0.04);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1360px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HERO BANNER ── */
        .tr-hero-banner {
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;
            padding: 1.5rem 2rem; margin-bottom: 1.5rem; border-radius: var(--tr-radius-xl);
            background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 50%, #ecfdf5 100%);
            border: 1px solid #e2e8f0; box-shadow: var(--tr-shadow-sm);
        }
        .tr-hero-title { font-size: 1.5rem; font-weight: 900; color: var(--tr-text-main); margin: 0 0 0.4rem 0; letter-spacing: -0.02em; }
        .tr-hero-subtitle { display: flex; align-items: center; gap: 6px; font-size: 0.85rem; color: #475569; margin: 0; font-weight: 500; }
        
        .tr-hero-badges { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .tr-user-chip { display: inline-flex; align-items: center; gap: 8px; padding: 0.35rem 1rem 0.35rem 0.35rem; background: rgba(255,255,255,0.8); border: 1px solid rgba(255,255,255,0.9); border-radius: 999px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .tr-avatar-circle { width: 28px; height: 28px; background: var(--tr-surface); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--tr-indigo); }
        .tr-role { font-size: 0.7rem; font-weight: 800; color: var(--tr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-right: 1px solid var(--tr-border); padding-right: 8px; }
        .tr-name { font-size: 0.85rem; font-weight: 700; color: var(--tr-text-main); }
        
        .tr-opname-chip { display: inline-flex; align-items: center; gap: 6px; padding: 0.4rem 1rem; border-radius: 999px; font-size: 0.75rem; font-weight: 800; }
        .tr-opname-chip .tr-label { opacity: 0.8; font-weight: 600; }
        .tr-badge-success { background: var(--tr-success-bg); color: var(--tr-success-text); border: 1px solid #bbf7d0; }
        .tr-badge-info { background: var(--tr-info-bg); color: #0284c7; border: 1px solid #bae6fd; }
        .tr-badge-warning { background: var(--tr-warning-bg); color: var(--tr-warning-text); border: 1px solid #fde68a; }

        /* ── KPI STATS GRID ── */
        .tr-kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .tr-kpi-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); padding: 1.25rem; display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-card); transition: transform 0.2s; }
        .tr-kpi-card:hover { transform: translateY(-2px); }
        .tr-kpi-info { display: flex; flex-direction: column; gap: 4px; }
        .tr-kpi-value { font-size: 1.75rem; font-weight: 900; color: var(--tr-text-main); line-height: 1; }
        .tr-kpi-label { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; letter-spacing: 0.02em; }
        .tr-time-tag { font-size: 0.65rem; font-weight: 600; color: var(--tr-text-light); text-transform: none; }
        
        .tr-kpi-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .bg-blue { background: var(--tr-primary-light); color: var(--tr-primary); }
        .bg-warning { background: var(--tr-warning-bg); color: var(--tr-warning); }
        .bg-danger { background: var(--tr-danger-bg); color: var(--tr-danger); }
        
        .text-warning { color: var(--tr-warning); }
        .text-danger { color: var(--tr-danger); }

        /* ── DASHBOARD LAYOUT GRID ── */
        .tr-dashboard-layout { display: grid; grid-template-columns: 1.4fr 0.6fr; gap: 1.5rem; }
        .tr-col-left { display: flex; flex-direction: column; gap: 1.5rem; }
        .tr-alert-tables-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .tr-h-full { height: 100%; }

        /* ── PANELS & CARDS ── */
        .tr-panel { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-card); display: flex; flex-direction: column; }
        .tr-panel-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); }
        .tr-panel-title { font-size: 1.05rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.2rem 0; }
        .tr-panel-desc { font-size: 0.8rem; color: var(--tr-text-muted); margin: 0; }
        .tr-panel-compact .tr-panel-header { padding: 1rem 1.25rem; }
        .tr-panel-compact .tr-panel-title { font-size: 0.95rem; }

        /* ── CUSTOM BAR CHART ── */
        .tr-chart-container { 
            display: flex; align-items: flex-end; gap: 8px; height: 180px; 
            margin: 1.5rem 1.5rem 0 1.5rem; padding: 0 0.5rem;
            border-bottom: 2px solid var(--tr-border-light); 
        }
        .tr-chart-bar-group {
            flex: 1; display: flex; flex-direction: column; justify-content: flex-end;
            min-width: 10px; border-radius: 6px 6px 0 0; overflow: hidden;
            background: rgba(148,163,184,0.1); cursor: pointer; transition: all 0.2s;
        }
        .tr-chart-bar-group:hover { filter: brightness(0.9); }
        .tr-chart-bar-group.is-today { background: var(--tr-indigo-light); box-shadow: inset 0 -4px 0 var(--tr-indigo); }
        .tr-seg { width: 100%; transition: height 0.5s ease; }
        .tr-seg-po { background: var(--tr-primary); }
        .tr-seg-nonpo { background: var(--tr-success); }
        
        .tr-chart-legend { display: flex; gap: 1.25rem; padding: 1rem 1.5rem; font-size: 0.8rem; color: var(--tr-text-muted); font-weight: 600; align-items: center; flex-wrap: wrap; }
        .tr-legend-item { display: flex; align-items: center; gap: 6px; }
        .tr-legend-dot { width: 10px; height: 10px; border-radius: 3px; display: inline-block; }
        .bg-nonpo { background: var(--tr-success); }
        .bg-po { background: var(--tr-primary); }
        .tr-legend-hint { margin-left: auto; font-size: 0.75rem; font-weight: 500; font-style: italic; color: var(--tr-text-light); }

        /* ── MINI TABLES (WIDGETS) ── */
        .table-responsive { overflow-x: auto; }
        .tr-table-widget { width: 100%; border-collapse: collapse; }
        .tr-table-widget th { text-align: left; font-size: 0.7rem; color: var(--tr-text-muted); letter-spacing: 0.05em; text-transform: uppercase; padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); background: #f8fafc; font-weight: 700; }
        .tr-table-widget td { padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); vertical-align: middle; }
        .tr-table-widget tbody tr:last-child td { border-bottom: none; }
        .tr-table-widget th.r, .tr-table-widget td.r { text-align: right; }
        
        .tr-item-name { font-size: 0.85rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 2px; }
        .tr-item-sku { font-family: monospace; font-size: 0.7rem; color: var(--tr-text-muted); font-weight: 600; }
        
        .tr-stock-pill { display: inline-flex; align-items: center; gap: 4px; padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
        .tr-stock-pill strong { font-weight: 900; font-size: 0.85rem; }
        .tr-bg-warning-soft { background: var(--tr-warning-bg); color: var(--tr-warning-text); }
        .tr-bg-danger-soft { background: var(--tr-danger-bg); color: var(--tr-danger-text); font-weight: 800; font-family: monospace; letter-spacing: 0.02em; }

        /* ── RECENT ACTIVITY LIST ── */
        .tr-activity-list { display: flex; flex-direction: column; padding: 0; margin: 0; }
        .tr-activity-item { display: flex; gap: 1rem; padding: 1.25rem 1.5rem; border-bottom: 1px dashed var(--tr-border-light); align-items: center; transition: background 0.2s; }
        .tr-activity-item:hover { background: #f8fafc; }
        .tr-activity-item:last-child { border-bottom: none; }
        
        .tr-act-time { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 44px; flex-shrink: 0; }
        .tr-act-time .date { font-size: 0.8rem; font-weight: 800; color: var(--tr-text-main); }
        .tr-act-time .time { font-size: 0.7rem; color: var(--tr-text-muted); font-weight: 600; }
        
        .tr-act-content { flex: 1; min-width: 0; }
        .tr-act-head { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; flex-wrap: wrap; }
        .tr-act-prod { font-size: 0.85rem; font-weight: 700; color: var(--tr-text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }
        .tr-act-badge { padding: 0.15rem 0.4rem; border-radius: 4px; font-size: 0.65rem; font-weight: 900; letter-spacing: 0.05em; }
        .tr-act-in { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-act-out { background: var(--tr-danger-bg); color: var(--tr-danger-text); }
        .tr-act-other { background: var(--tr-info-bg); color: #0284c7; }
        
        .tr-act-meta { font-size: 0.75rem; color: var(--tr-text-muted); }
        .tr-font-mono { font-family: monospace; color: var(--tr-text-light); }
        .tr-dot-divider { color: var(--tr-border); margin: 0 2px; }
        
        .tr-act-qty { font-size: 1.1rem; font-weight: 900; font-family: monospace; letter-spacing: -0.05em; padding-left: 0.5rem; text-align: right; }
        .text-success { color: var(--tr-success); }
        .text-danger { color: var(--tr-danger); }

        .tr-empty-box { padding: 2rem; text-align: center; color: var(--tr-text-light); font-size: 0.85rem; font-weight: 500; background: #f8fafc; border-radius: 8px; border: 1px dashed var(--tr-border); margin: 1.25rem; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1100px) {
            .tr-dashboard-layout { grid-template-columns: 1fr; }
            .tr-alert-tables-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .tr-kpi-grid { grid-template-columns: 1fr 1fr; }
            .tr-hero-banner { flex-direction: column; align-items: flex-start; }
            .tr-alert-tables-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 480px) {
            .tr-kpi-grid { grid-template-columns: 1fr; }
            .tr-chart-legend { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
        }
    </style>
    @endpush
</x-app-layout>