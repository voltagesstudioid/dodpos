<x-app-layout>
    @push('styles')
    <style>
        .sk-page { max-width:80rem; margin:0 auto; padding:0 1rem; }

        /* Header */
        .sk-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .sk-hdr-l { display:flex; align-items:center; gap:1rem; }
        .sk-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#0ea5e9,#0369a1);
            box-shadow:0 8px 24px rgba(3,105,161,0.3);
        }
        .sk-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .sk-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        /* Filter */
        .sk-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
            display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem;
        }
        .sk-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .sk-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none; min-width:180px;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .sk-fsel:focus { border-color:#0ea5e9; box-shadow:0 0 0 3px rgba(14,165,233,0.12); }
        .sk-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#0ea5e9,#0369a1); color:#fff; box-shadow:0 4px 12px rgba(3,105,161,0.25);
        }
        .sk-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(3,105,161,0.35); }
        .sk-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .sk-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        /* Sales Card */
        .sk-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:20px; overflow:hidden;
            box-shadow:0 2px 8px rgba(0,0,0,0.04); margin-bottom:1.25rem; transition:all 0.3s;
        }
        .sk-card:hover { box-shadow:0 10px 36px rgba(0,0,0,0.08); transform:translateY(-2px); }

        /* Card Header */
        .sk-card-hdr {
            position:relative; padding:1.25rem 1.5rem; overflow:hidden;
            background:linear-gradient(135deg,#0c4a6e 0%,#0369a1 40%,#0ea5e9 100%);
        }
        .sk-card-hdr::before {
            content:''; position:absolute; top:-30%; right:-10%; width:200px; height:200px; border-radius:50%;
            background:radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        }
        .sk-card-hdr::after {
            content:''; position:absolute; bottom:-40%; left:10%; width:250px; height:250px; border-radius:50%;
            background:radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        }
        .sk-card-hdr-inner { position:relative; z-index:1; display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; }
        .sk-card-hdr-left { display:flex; align-items:center; gap:1rem; }
        .sk-card-avatar {
            width:50px; height:50px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.25rem; font-weight:800; flex-shrink:0;
            background:rgba(255,255,255,0.15); backdrop-filter:blur(8px); color:#fff;
            border:1.5px solid rgba(255,255,255,0.25);
        }
        .sk-card-name { font-size:1.125rem; font-weight:700; color:#fff; line-height:1.2; }
        .sk-card-plate {
            display:inline-flex; margin-top:3px; padding:0.15rem 0.5rem; border-radius:5px;
            font-size:0.6875rem; font-weight:700; background:rgba(255,255,255,0.15); color:rgba(255,255,255,0.85);
            letter-spacing:0.03em; font-family:monospace;
        }
        .sk-card-hdr-right { text-align:right; }
        .sk-card-hdr-lbl { font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.07em; color:rgba(255,255,255,0.6); }
        .sk-card-hdr-val { font-size:2.25rem; font-weight:800; color:#fff; letter-spacing:-0.03em; line-height:1; margin-top:2px; }
        .sk-card-hdr-unit { font-size:0.9375rem; font-weight:600; color:rgba(255,255,255,0.7); margin-left:2px; }

        /* Summary Row */
        .sk-summary {
            display:grid; grid-template-columns:repeat(3,1fr); gap:0; border-bottom:1px solid #f1f5f9;
        }
        .sk-sum-item {
            padding:1rem 1.25rem; text-align:center; position:relative;
        }
        .sk-sum-item:not(:last-child)::after {
            content:''; position:absolute; right:0; top:20%; height:60%; width:1px; background:#f1f5f9;
        }
        .sk-sum-lbl { font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; margin-bottom:0.375rem; }
        .sk-sum-lbl.blue { color:#3b82f6; }
        .sk-sum-lbl.green { color:#059669; }
        .sk-sum-lbl.orange { color:#d97706; }
        .sk-sum-val { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.02em; line-height:1; }
        .sk-sum-unit { font-size:0.75rem; font-weight:500; color:#94a3b8; margin-left:2px; }
        .sk-sum-bar { height:4px; border-radius:99px; background:#f1f5f9; margin-top:0.5rem; overflow:hidden; }
        .sk-sum-bar-fill { height:100%; border-radius:99px; transition:width 0.4s ease; }

        /* Detail Table */
        .sk-detail { padding:0; }
        .sk-detail-head {
            background:#f8fafc; border-bottom:1px solid #f1f5f9;
        }
        .sk-detail-head th {
            padding:0.75rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#64748b; white-space:nowrap;
        }
        .sk-detail-body td {
            padding:0.75rem 1.25rem; font-size:0.8125rem; color:#374151; border-bottom:1px solid #fafafa;
        }
        .sk-detail-body tr:last-child td { border-bottom:none; }
        .sk-detail-body tr { transition:background 0.15s; }
        .sk-detail-body tr:hover td { background:#fafcff; }
        .sk-detail-prod { font-weight:600; color:#1e293b; }
        .sk-detail-vol { font-weight:700; letter-spacing:-0.01em; }
        .sk-detail-vol.blue { color:#2563eb; }
        .sk-detail-vol.green { color:#059669; }
        .sk-detail-vol.dark { color:#0f172a; }
        .sk-detail-unit { font-weight:400; font-size:0.6875rem; color:#94a3b8; margin-left:2px; }
        .sk-detail-bar { height:3px; border-radius:99px; background:#f1f5f9; margin-top:4px; overflow:hidden; min-width:40px; }
        .sk-detail-bar-fill { height:100%; border-radius:99px; transition:width 0.3s ease; }

        /* No Detail */
        .sk-no-detail { padding:1.5rem; text-align:center; font-size:0.8125rem; color:#94a3b8; }

        /* Empty State */
        .sk-empty { text-align:center; padding:3.5rem 1.5rem; }
        .sk-empty-ico {
            width:80px; height:80px; margin:0 auto 1.25rem; border-radius:50%;
            background:linear-gradient(135deg,#f0f9ff,#e0f2fe); display:flex; align-items:center; justify-content:center;
            box-shadow:0 4px 16px rgba(14,165,233,0.1);
        }
        .sk-empty-title { font-size:1.0625rem; font-weight:700; color:#475569; margin-bottom:0.375rem; }
        .sk-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.5rem; line-height:1.5; }
        .sk-empty-hint {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.5rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:500;
            background:#f0f9ff; color:#0369a1; border:1px solid #bae6fd;
        }

        @media(max-width:768px) {
            .sk-summary { grid-template-columns:1fr; }
            .sk-sum-item::after { display:none; }
            .sk-sum-item { border-bottom:1px solid #f1f5f9; }
            .sk-sum-item:last-child { border-bottom:none; }
        }
        @media(max-width:640px) { .sk-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="sk-page">

            {{-- Header --}}
            <div class="sk-hdr">
                <div class="sk-hdr-l">
                    <div class="sk-hdr-ico">🚛</div>
                    <div>
                        <div class="sk-hdr-title">Stok Kendaraan</div>
                        <div class="sk-hdr-sub">Monitoring stok BBM di kendaraan per sales</div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            @if(! $isSalesRole)
            <div class="sk-filter">
                <form method="GET" style="display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; width:100%;">
                    <div>
                        <label class="sk-flbl">Sales</label>
                        <select name="sales_id" class="sk-fsel">
                            <option value="">Semua Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="sk-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('minyak.stok.index') }}" class="sk-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>
            @endif

            {{-- Sales Cards --}}
            @forelse($stokPerSales as $data)
                @php
                    $totalLoading = $data['total_loading'];
                    $totalTerjual = $data['total_terjual'];
                    $totalSisa    = $data['total_sisa'];
                    $terjualPct   = $totalLoading > 0 ? round(($totalTerjual / $totalLoading) * 100) : 0;
                    $sisaPct      = $totalLoading > 0 ? round(($totalSisa / $totalLoading) * 100) : 0;
                @endphp
                <div class="sk-card">
                    {{-- Card Header --}}
                    <div class="sk-card-hdr">
                        <div class="sk-card-hdr-inner">
                            <div class="sk-card-hdr-left">
                                <div class="sk-card-avatar">{{ substr($data['sales']->nama, 0, 1) }}</div>
                                <div>
                                    <div class="sk-card-name">{{ $data['sales']->nama }}</div>
                                    @php
                                        $vehicleInfo = '';
                                        if ($data['sales']->vehicle) {
                                            $vehicleInfo = strtoupper($data['sales']->vehicle->license_plate);
                                            if ($data['sales']->vehicle->type) $vehicleInfo .= ' · ' . $data['sales']->vehicle->type;
                                        } elseif ($data['sales']->no_kendaraan) {
                                            $vehicleInfo = strtoupper($data['sales']->no_kendaraan);
                                            if ($data['sales']->jenis_kendaraan) $vehicleInfo .= ' · ' . $data['sales']->jenis_kendaraan;
                                        }
                                    @endphp
                                    @if($vehicleInfo)
                                        <span class="sk-card-plate">{{ $vehicleInfo }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="sk-card-hdr-right">
                                <div class="sk-card-hdr-lbl">Total Sisa Stok</div>
                                <div class="sk-card-hdr-val">
                                    {{ number_format($totalSisa) }}<span class="sk-card-hdr-unit">L</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div class="sk-summary">
                        <div class="sk-sum-item">
                            <div class="sk-sum-lbl blue">Total Loading</div>
                            <div class="sk-sum-val">{{ number_format($totalLoading) }}<span class="sk-sum-unit">L</span></div>
                            <div class="sk-sum-bar">
                                <div class="sk-sum-bar-fill" style="width:100%; background:linear-gradient(90deg,#3b82f6,#2563eb);"></div>
                            </div>
                        </div>
                        <div class="sk-sum-item">
                            <div class="sk-sum-lbl green">Terjual</div>
                            <div class="sk-sum-val">{{ number_format($totalTerjual) }}<span class="sk-sum-unit">L</span></div>
                            <div class="sk-sum-bar">
                                <div class="sk-sum-bar-fill" style="width:{{ $terjualPct }}%; background:linear-gradient(90deg,#10b981,#059669);"></div>
                            </div>
                        </div>
                        <div class="sk-sum-item">
                            <div class="sk-sum-lbl orange">Sisa</div>
                            <div class="sk-sum-val">{{ number_format($totalSisa) }}<span class="sk-sum-unit">L</span></div>
                            <div class="sk-sum-bar">
                                <div class="sk-sum-bar-fill" style="width:{{ $sisaPct }}%; background:linear-gradient(90deg,#f59e0b,#d97706);"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Table --}}
                    @if($data['detail']->count() > 0)
                        <div class="sk-detail">
                            <div style="overflow-x:auto;">
                                <table style="width:100%; border-collapse:separate; border-spacing:0;">
                                    <thead class="sk-detail-head">
                                        <tr>
                                            <th style="text-align:left;">Produk</th>
                                            <th style="text-align:right;">Loading</th>
                                            <th style="text-align:right;">Terjual</th>
                                            <th style="text-align:right;">Sisa</th>
                                        </tr>
                                    </thead>
                                    <tbody class="sk-detail-body">
                                        @foreach($data['detail'] as $item)
                                            @php
                                                $dLoadPct = $item['loading'] > 0 ? 100 : 0;
                                                $dSellPct = $item['loading'] > 0 ? round(($item['terjual'] / $item['loading']) * 100) : 0;
                                                $dSisaPct = $item['loading'] > 0 ? round(($item['sisa'] / $item['loading']) * 100) : 0;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="sk-detail-prod">{{ $item['produk']->nama }}</div>
                                                </td>
                                                <td style="text-align:right;">
                                                    <span class="sk-detail-vol blue">{{ number_format($item['loading']) }}<span class="sk-detail-unit">L</span></span>
                                                </td>
                                                <td style="text-align:right;">
                                                    <span class="sk-detail-vol green">{{ number_format($item['terjual']) }}<span class="sk-detail-unit">L</span></span>
                                                    <div class="sk-detail-bar">
                                                        <div class="sk-detail-bar-fill" style="width:{{ $dSellPct }}%; background:linear-gradient(90deg,#10b981,#059669);"></div>
                                                    </div>
                                                </td>
                                                <td style="text-align:right;">
                                                    <span class="sk-detail-vol dark">{{ number_format($item['sisa']) }}<span class="sk-detail-unit">L</span></span>
                                                    <div class="sk-detail-bar">
                                                        <div class="sk-detail-bar-fill" style="width:{{ $dSisaPct }}%; background:linear-gradient(90deg,#f59e0b,#d97706);"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="sk-no-detail">Tidak ada data stok untuk sales ini</div>
                    @endif
                </div>
            @empty
                {{-- Empty State --}}
                <div class="sk-card">
                    <div class="sk-empty">
                        <div class="sk-empty-ico">
                            <svg width="36" height="36" fill="none" stroke="#0ea5e9" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div class="sk-empty-title">Tidak Ada Data Stok Kendaraan</div>
                        <div class="sk-empty-sub">Data stok akan muncul otomatis setelah ada loading harian yang dilakukan</div>
                        <div class="sk-empty-hint">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Buat loading harian terlebih dahulu di menu Loading Harian
                        </div>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
