<x-app-layout>
    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style>
        .sk-wrap{font-family:'Plus Jakarta Sans',sans-serif}
        .sk-mono{font-family:'JetBrains Mono',monospace}

        /* header */
        .sk-header{background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);border:1px solid #fde68a;border-radius:20px;padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px}
        .sk-header-left{display:flex;align-items:center;gap:16px}
        .sk-header-icon{width:52px;height:52px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:16px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(245,158,11,.35)}
        .sk-header-icon svg{width:26px;height:26px;color:#fff}
        .sk-header h1{font-size:1.375rem;font-weight:800;color:#1f2937;margin:0}
        .sk-header p{font-size:.8rem;color:#92400e;margin:2px 0 0}

        /* filter */
        .sk-filter{background:#fff;border:1px solid #fde68a;border-radius:16px;padding:16px 20px;margin-bottom:24px;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .sk-filter form{display:flex;flex-wrap:wrap;align-items:center;gap:10px}
        .sk-filter select{border:1.5px solid #fde68a;border-radius:12px;padding:9px 14px 9px 36px;font-size:.8125rem;background:#fffbeb url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23d97706' viewBox='0 0 24 24'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E") no-repeat 12px center;color:#92400e;font-weight:500;outline:none;min-width:200px;transition:border-color .2s}
        .sk-filter select:focus{border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.12)}
        .sk-btn-filter{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;border-radius:12px;padding:9px 18px;font-size:.8125rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:7px;transition:opacity .2s}
        .sk-btn-filter:hover{opacity:.88}
        .sk-btn-filter svg{width:15px;height:15px}
        .sk-btn-reset{background:#fffbeb;color:#92400e;border:1.5px solid #fde68a;border-radius:12px;padding:9px 16px;font-size:.8125rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:7px;text-decoration:none;transition:background .2s}
        .sk-btn-reset:hover{background:#fef3c7}
        .sk-btn-reset svg{width:15px;height:15px}

        /* summary strip */
        .sk-summary{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:24px}
        .sk-summary-card{background:#fff;border:1px solid #fde68a;border-radius:14px;padding:16px 18px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .sk-summary-icon{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .sk-summary-icon svg{width:20px;height:20px}
        .sk-summary-icon.amber{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706}
        .sk-summary-icon.green{background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#059669}
        .sk-summary-icon.blue{background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#2563eb}
        .sk-summary-icon.orange{background:linear-gradient(135deg,#ffedd5,#fed7aa);color:#ea580c}
        .sk-summary-val{font-size:1.25rem;font-weight:800;color:#1f2937;line-height:1}
        .sk-summary-lbl{font-size:.72rem;color:#6b7280;margin-top:3px;font-weight:500}

        /* card */
        .sk-card{background:#fff;border:1px solid #fde68a;border-radius:20px;overflow:hidden;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,.05);transition:box-shadow .2s}
        .sk-card:hover{box-shadow:0 4px 16px rgba(245,158,11,.12)}
        .sk-card-head{background:linear-gradient(180deg,#fffbeb,#fef9ee);border-bottom:2px solid #fde68a;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
        .sk-card-left{display:flex;align-items:center;gap:14px}
        .sk-avatar{width:50px;height:50px;border-radius:16px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem;flex-shrink:0;box-shadow:0 4px 10px rgba(245,158,11,.28)}
        .sk-sales-name{font-size:1rem;font-weight:700;color:#1f2937;margin:0}
        .sk-sales-meta{display:flex;align-items:center;gap:8px;margin-top:4px;flex-wrap:wrap}
        .sk-plate{display:inline-flex;align-items:center;gap:5px;background:#1f2937;color:#fbbf24;padding:2px 9px;border-radius:6px;font-size:.7rem;font-weight:600;letter-spacing:.5px}
        .sk-plate svg{width:12px;height:12px}
        .sk-vehicle-type{font-size:.75rem;color:#6b7280;font-weight:500}
        .sk-card-right{text-align:right}
        .sk-card-right-lbl{font-size:.72rem;color:#6b7280;font-weight:500;margin-bottom:2px}
        .sk-card-right-val{font-size:1.75rem;font-weight:800;color:#d97706;line-height:1}
        .sk-card-right-unit{font-size:.85rem;color:#92400e;font-weight:600}

        /* stats row */
        .sk-stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;padding:20px 24px;border-bottom:1px solid #fde68a}
        .sk-stat-box{text-align:center;padding:14px 10px;border-radius:14px;border:1px solid transparent}
        .sk-stat-box.loading-bg{background:linear-gradient(135deg,#eff6ff,#dbeafe);border-color:#bfdbfe}
        .sk-stat-box.terjual-bg{background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-color:#a7f3d0}
        .sk-stat-box.sisa-bg{background:linear-gradient(135deg,#fff7ed,#ffedd5);border-color:#fed7aa}
        .sk-stat-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 8px}
        .sk-stat-icon svg{width:18px;height:18px}
        .sk-stat-icon.blue-i{background:#bfdbfe;color:#2563eb}
        .sk-stat-icon.green-i{background:#a7f3d0;color:#059669}
        .sk-stat-icon.orange-i{background:#fed7aa;color:#ea580c}
        .sk-stat-val{font-size:1.1rem;font-weight:800;color:#1f2937}
        .sk-stat-lbl{font-size:.7rem;color:#6b7280;margin-top:2px;font-weight:500}

        /* detail table */
        .sk-detail-wrap{padding:20px 24px}
        .sk-detail-title{font-size:.8125rem;font-weight:700;color:#92400e;margin-bottom:12px;display:flex;align-items:center;gap:7px}
        .sk-detail-title svg{width:16px;height:16px;color:#d97706}
        .sk-tbl{width:100%;border-collapse:separate;border-spacing:0;border:1px solid #fde68a;border-radius:12px;overflow:hidden}
        .sk-tbl thead th{background:linear-gradient(180deg,#fffbeb,#fef9ee);border-bottom:2px solid #fde68a;padding:11px 16px;font-size:.7rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.5px}
        .sk-tbl thead th:first-child{text-align:left}
        .sk-tbl thead th:not(:first-child){text-align:right}
        .sk-tbl tbody td{padding:12px 16px;border-bottom:1px solid #fef3c7;font-size:.8125rem;color:#374151}
        .sk-tbl tbody td:first-child{text-align:left}
        .sk-tbl tbody td:not(:first-child){text-align:right}
        .sk-tbl tbody tr:last-child td{border-bottom:none}
        .sk-tbl tbody tr:hover{background:#fffbeb}
        .sk-prod-dot{display:inline-block;width:8px;height:8px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);margin-right:8px;flex-shrink:0}
        .sk-prod-name{font-weight:600;color:#1f2937}
        .sk-val-loading{color:#2563eb;font-weight:600}
        .sk-val-terjual{color:#059669;font-weight:600}
        .sk-val-sisa{color:#1f2937;font-weight:700}

        /* progress bar */
        .sk-pct-wrap{display:flex;align-items:center;gap:8px;justify-content:flex-end}
        .sk-pct-bar{width:64px;height:6px;background:#fde68a;border-radius:99px;overflow:hidden}
        .sk-pct-fill{height:100%;border-radius:99px;transition:width .4s}
        .sk-pct-fill.low{background:linear-gradient(90deg,#f87171,#ef4444)}
        .sk-pct-fill.mid{background:linear-gradient(90deg,#fbbf24,#f59e0b)}
        .sk-pct-fill.high{background:linear-gradient(90deg,#34d399,#059669)}
        .sk-pct-txt{font-size:.72rem;color:#6b7280;font-weight:600;min-width:32px;text-align:right}

        /* empty */
        .sk-empty{text-align:center;padding:60px 24px}
        .sk-empty-icon{width:80px;height:80px;margin:0 auto 18px;background:linear-gradient(135deg,#fef3c7,#fde68a);border-radius:24px;display:flex;align-items:center;justify-content:center}
        .sk-empty-icon svg{width:38px;height:38px;color:#d97706}
        .sk-empty h3{font-size:1rem;font-weight:700;color:#374151;margin:0 0 6px}
        .sk-empty p{font-size:.8125rem;color:#6b7280;margin:0 0 20px}
        .sk-empty-btn{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:10px 22px;border-radius:12px;font-size:.8125rem;font-weight:600;text-decoration:none;box-shadow:0 4px 12px rgba(245,158,11,.3);transition:opacity .2s}
        .sk-empty-btn:hover{opacity:.88}
        .sk-empty-btn svg{width:16px;height:16px}

        /* card empty detail */
        .sk-detail-empty{text-align:center;padding:28px 16px}
        .sk-detail-empty svg{width:40px;height:40px;color:#d1d5db;margin-bottom:8px}
        .sk-detail-empty p{font-size:.8125rem;color:#9ca3af;margin:0}

        @media(max-width:640px){
            .sk-stats-row{grid-template-columns:1fr}
            .sk-summary{grid-template-columns:1fr 1fr}
            .sk-card-head{flex-direction:column;align-items:flex-start}
            .sk-card-right{text-align:left}
        }
    </style>
    @endpush

    <div class="sk-wrap" style="padding:24px">

        {{-- Header --}}
        <div class="sk-header">
            <div class="sk-header-left">
                <div class="sk-header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12l-2 9H6L4 3H2m6 4V3m4 4V3m-6 7h8m-8 0l-2 6h12"/></svg>
                </div>
                <div>
                    <h1>Stok Kendaraan</h1>
                    <p>Monitoring stok gula di setiap kendaraan sales</p>
                </div>
            </div>
            <div style="font-size:.75rem;color:#92400e;font-weight:600;background:#fff;border:1px solid #fde68a;padding:6px 14px;border-radius:10px">
                {{ count($stokPerSales) }} Sales Aktif
            </div>
        </div>

        {{-- Summary Strip --}}
        @if(count($stokPerSales) > 0)
        @php
            $grandLoading = collect($stokPerSales)->sum('total_loading');
            $grandTerjual = collect($stokPerSales)->sum('total_terjual');
            $grandSisa    = collect($stokPerSales)->sum('total_sisa');
            $grandPct     = $grandLoading > 0 ? round(($grandTerjual / $grandLoading) * 100) : 0;
            // Use the most common primary satuan across all sales
            $grandSatuan = collect($stokPerSales)->sortByDesc('total_loading')->first()['primary_satuan'] ?? 'Unit';
        @endphp
        <div class="sk-summary">
            <div class="sk-summary-card">
                <div class="sk-summary-icon amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <div class="sk-summary-val">{{ count($stokPerSales) }}</div>
                    <div class="sk-summary-lbl">Total Sales</div>
                </div>
            </div>
            <div class="sk-summary-card">
                <div class="sk-summary-icon blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div>
                    <div class="sk-summary-val sk-mono">{{ number_format($grandLoading) }}</div>
                    <div class="sk-summary-lbl">Total Loading ({{ $grandSatuan }})</div>
                </div>
            </div>
            <div class="sk-summary-card">
                <div class="sk-summary-icon green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="sk-summary-val sk-mono">{{ number_format($grandTerjual) }}</div>
                    <div class="sk-summary-lbl">Total Terjual ({{ $grandSatuan }})</div>
                </div>
            </div>
            <div class="sk-summary-card">
                <div class="sk-summary-icon orange">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <div class="sk-summary-val sk-mono">{{ number_format($grandSisa) }}</div>
                    <div class="sk-summary-lbl">Sisa Stok ({{ $grandSatuan }}) · {{ $grandPct }}% Terjual</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Filter --}}
        <div class="sk-filter">
            <form method="GET">
                <select name="sales_id">
                    <option value="">Semua Sales</option>
                    @foreach($allSales as $s)
                        <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                    @endforeach
                </select>
                <button type="submit" class="sk-btn-filter">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                <a href="{{ route('gula.stok.index') }}" class="sk-btn-reset">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset
                </a>
            </form>
        </div>

        {{-- Stok Per Sales Cards --}}
        <div>
            @forelse($stokPerSales as $data)
            @php
                $pct = $data['total_loading'] > 0 ? round(($data['total_terjual'] / $data['total_loading']) * 100) : 0;
                $initial = strtoupper(substr($data['sales']->nama, 0, 1));
            @endphp
            <div class="sk-card">
                {{-- Card Header --}}
                <div class="sk-card-head">
                    <div class="sk-card-left">
                        <div class="sk-avatar">{{ $initial }}</div>
                        <div>
                            <p class="sk-sales-name">{{ $data['sales']->nama }}</p>
                            <div class="sk-sales-meta">
                                @if($data['sales']->no_kendaraan)
                                <span class="sk-plate">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12l-2 9H6L4 3H2"/></svg>
                                    {{ $data['sales']->no_kendaraan }}
                                </span>
                                @endif
                                <span class="sk-vehicle-type">{{ $data['sales']->jenis_kendaraan ?? 'Tanpa Kendaraan' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="sk-card-right">
                        <div class="sk-card-right-lbl">Total Sisa Stok</div>
                        <div class="sk-card-right-val sk-mono">{{ number_format($data['total_sisa']) }}</div>
                        <div class="sk-card-right-unit">{{ $data['primary_satuan'] }} · {{ $pct }}% terjual</div>
                    </div>
                </div>

                {{-- Stats Row --}}
                <div class="sk-stats-row">
                    <div class="sk-stat-box loading-bg">
                        <div class="sk-stat-icon blue-i">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div class="sk-stat-val sk-mono">{{ number_format($data['total_loading']) }}</div>
                        <div class="sk-stat-lbl">Total Loading ({{ $data['primary_satuan'] }})</div>
                    </div>
                    <div class="sk-stat-box terjual-bg">
                        <div class="sk-stat-icon green-i">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="sk-stat-val sk-mono">{{ number_format($data['total_terjual']) }}</div>
                        <div class="sk-stat-lbl">Terjual ({{ $data['primary_satuan'] }})</div>
                    </div>
                    <div class="sk-stat-box sisa-bg">
                        <div class="sk-stat-icon orange-i">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div class="sk-stat-val sk-mono">{{ number_format($data['total_sisa']) }}</div>
                        <div class="sk-stat-lbl">Sisa Stok ({{ $data['primary_satuan'] }})</div>
                    </div>
                </div>

                {{-- Detail Produk --}}
                <div class="sk-detail-wrap">
                    @if($data['detail']->count() > 0)
                    <div class="sk-detail-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Detail Produk
                    </div>
                    <table class="sk-tbl">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Loading</th>
                                <th>Terjual</th>
                                <th>Sisa</th>
                                <th>Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['detail'] as $item)
                            @php
                                $itemPct = $item['loading'] > 0 ? round(($item['terjual'] / $item['loading']) * 100) : 0;
                                $barClass = $itemPct >= 70 ? 'high' : ($itemPct >= 40 ? 'mid' : 'low');
                            @endphp
                            <tr>
                                <td>
                                    <span class="sk-prod-dot"></span>
                                    <span class="sk-prod-name">{{ $item['produk']->nama }}</span>
                                </td>
                                <td class="sk-mono sk-val-loading">{{ number_format($item['loading']) }} {{ $item['satuan'] }}</td>
                                <td class="sk-mono sk-val-terjual">{{ number_format($item['terjual']) }} {{ $item['satuan'] }}</td>
                                <td class="sk-mono sk-val-sisa">{{ number_format($item['sisa']) }} {{ $item['satuan'] }}</td>
                                <td>
                                    <div class="sk-pct-wrap">
                                        <div class="sk-pct-bar">
                                            <div class="sk-pct-fill {{ $barClass }}" style="width:{{ $itemPct }}%"></div>
                                        </div>
                                        <span class="sk-pct-txt">{{ $itemPct }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="sk-detail-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <p>Tidak ada data stok untuk kendaraan ini</p>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            {{-- Global Empty State --}}
            <div class="sk-card">
                <div class="sk-empty">
                    <div class="sk-empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12l-2 9H6L4 3H2m6 4V3m4 4V3m-6 7h8m-8 0l-2 6h12"/></svg>
                    </div>
                    <h3>Tidak Ada Data Stok Kendaraan</h3>
                    <p>Belum ada data stok kendaraan yang tercatat. Pastikan loading harian sudah diinput.</p>
                    <a href="{{ route('gula.loading.index') }}" class="sk-empty-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Input Loading Harian
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
