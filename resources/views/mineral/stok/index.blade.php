<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .sk-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .sk-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .sk-hdr-l { display:flex; align-items:center; gap:1rem; }
        .sk-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            box-shadow:0 8px 24px rgba(37,99,235,0.3);
        }
        .sk-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .sk-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        /* Filter */
        .sk-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .sk-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .sk-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .sk-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .sk-fsel:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .sk-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 4px 12px rgba(37,99,235,0.25);
        }
        .sk-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(37,99,235,0.35); }
        .sk-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .sk-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        /* Sales Card */
        .sk-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04); margin-bottom:1.5rem; transition:all 0.3s;
        }
        .sk-card:hover { box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }

        /* Card Header */
        .sk-card-hdr {
            padding:1.25rem 1.5rem;
            background:linear-gradient(180deg,#eff6ff,#f0f7ff);
            border-bottom:2px solid #bfdbfe;
            display:flex; align-items:center; justify-content:space-between; gap:1rem;
        }
        .sk-card-info { display:flex; align-items:center; gap:1rem; }
        .sk-card-av {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.25rem; font-weight:800; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 6px 16px rgba(37,99,235,0.25);
        }
        .sk-card-name { font-size:1rem; font-weight:700; color:#0f172a; letter-spacing:-0.02em; }
        .sk-card-meta { display:flex; align-items:center; gap:0.5rem; margin-top:0.25rem; }
        .sk-card-plate {
            display:inline-flex; padding:0.125rem 0.5rem; border-radius:6px;
            background:linear-gradient(135deg,#eff6ff,#dbeafe); border:1px solid #bfdbfe;
            font-size:0.6875rem; font-weight:700; color:#1e40af; font-family:'JetBrains Mono',monospace;
        }
        .sk-card-vtype { font-size:0.75rem; color:#64748b; }

        /* Total Sisa */
        .sk-card-total { text-align:right; }
        .sk-card-total-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .sk-card-total-val { font-size:2rem; font-weight:800; color:#2563eb; letter-spacing:-0.03em; line-height:1; margin-top:0.25rem; }
        .sk-card-total-unit { font-size:1rem; font-weight:600; color:#94a3b8; }

        /* Card Body */
        .sk-card-body { padding:1.5rem; }

        /* Metric Grid */
        .sk-metrics { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
        .sk-metric {
            background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1rem 1.25rem;
            text-align:center; position:relative; overflow:hidden; transition:all 0.2s;
        }
        .sk-metric::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .sk-metric:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(0,0,0,0.06); }
        .sk-metric.blue::before   { background:linear-gradient(90deg,#3b82f6,#2563eb); }
        .sk-metric.green::before  { background:linear-gradient(90deg,#10b981,#059669); }
        .sk-metric.amber::before  { background:linear-gradient(90deg,#f59e0b,#d97706); }
        .sk-metric-ico {
            width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            margin:0 auto 0.5rem; font-size:1.125rem;
        }
        .sk-metric-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .sk-metric-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .sk-metric-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .sk-metric-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.25rem; }
        .sk-metric-val { font-size:1.5rem; font-weight:800; letter-spacing:-0.02em; color:#1e293b; }
        .sk-metric-val-unit { font-size:0.875rem; font-weight:600; color:#94a3b8; }

        /* Detail Section */
        .sk-detail-title {
            font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#64748b;
            margin-bottom:0.75rem; display:flex; align-items:center; gap:0.5rem;
        }
        .sk-detail-title::before {
            content:''; width:4px; height:14px; border-radius:2px;
            background:linear-gradient(180deg,#3b82f6,#2563eb);
        }
        .sk-tbl {
            background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;
        }
        .sk-tbl-head { background:linear-gradient(180deg,#f8fafc,#f1f5f9); border-bottom:1px solid #e2e8f0; }
        .sk-tbl-head th {
            padding:0.75rem 1rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#64748b; white-space:nowrap;
        }
        .sk-tbl-body td { padding:0.75rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .sk-tbl-body tr { transition:background 0.15s; }
        .sk-tbl-body tr:last-child td { border-bottom:none; }
        .sk-tbl-body tr:hover td { background:#f8faff; }

        /* Product cell */
        .sk-prod { display:flex; align-items:center; gap:0.5rem; }
        .sk-prod-dot { width:8px; height:8px; border-radius:50%; background:linear-gradient(135deg,#3b82f6,#2563eb); flex-shrink:0; }
        .sk-prod-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }

        /* Volume cells */
        .sk-vol { font-size:0.8125rem; }
        .sk-vol.loading { color:#2563eb; font-weight:600; }
        .sk-vol.terjual { color:#059669; font-weight:600; }
        .sk-vol.sisa    { color:#d97706; font-weight:700; }

        /* Progress bar */
        .sk-progress { display:flex; align-items:center; gap:0.5rem; justify-content:flex-end; }
        .sk-progress-bar {
            width:60px; height:6px; background:#e2e8f0; border-radius:99px; overflow:hidden;
        }
        .sk-progress-fill {
            height:100%; border-radius:99px;
            background:linear-gradient(90deg,#3b82f6,#2563eb);
            transition:width 0.3s;
        }
        .sk-progress-pct { font-size:0.6875rem; font-weight:700; color:#64748b; min-width:32px; text-align:right; }

        /* Empty state inside card */
        .sk-card-empty { text-align:center; padding:2rem 1rem; }
        .sk-card-empty-ico {
            width:48px; height:48px; margin:0 auto 0.75rem; border-radius:12px;
            background:linear-gradient(135deg,#eff6ff,#dbeafe); display:flex; align-items:center; justify-content:center;
        }
        .sk-card-empty-txt { font-size:0.8125rem; color:#94a3b8; }

        /* Global empty */
        .sk-empty { text-align:center; padding:4rem 1.5rem; }
        .sk-empty-ico {
            width:80px; height:80px; margin:0 auto 1.25rem; border-radius:50%;
            background:linear-gradient(135deg,#eff6ff,#dbeafe); display:flex; align-items:center; justify-content:center;
        }
        .sk-empty-title { font-size:1.125rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .sk-empty-sub { font-size:0.8125rem; color:#94a3b8; }

        @media(max-width:768px) { .sk-metrics { grid-template-columns:1fr; } .sk-card-hdr { flex-direction:column; align-items:flex-start; } .sk-card-total { text-align:left; } }
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
                        <div class="sk-hdr-sub">Monitoring stok mineral di setiap kendaraan sales</div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="sk-filter">
                <form method="GET" class="sk-ff">
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
                    <a href="{{ route('mineral.stok.index') }}" class="sk-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- Stok Per Sales --}}
            <div class="sk-cards">
                @forelse($stokPerSales as $data)
                    <div class="sk-card">
                        {{-- Card Header --}}
                        <div class="sk-card-hdr">
                            <div class="sk-card-info">
                                <div class="sk-card-av">{{ substr($data['sales']->nama, 0, 1) }}</div>
                                <div>
                                    <div class="sk-card-name">{{ $data['sales']->nama }}</div>
                                    <div class="sk-card-meta">
                                        <span class="sk-card-plate">{{ $data['sales']->no_kendaraan ?? '-' }}</span>
                                        <span class="sk-card-vtype">{{ $data['sales']->jenis_kendaraan ?? 'Tanpa Kendaraan' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="sk-card-total">
                                <div class="sk-card-total-lbl">Total Sisa Stok</div>
                                <div class="sk-card-total-val">{{ number_format($data['total_sisa']) }} <span class="sk-card-total-unit">L</span></div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="sk-card-body">
                            {{-- Metrics --}}
                            <div class="sk-metrics">
                                <div class="sk-metric blue">
                                    <div class="sk-metric-ico blue">📦</div>
                                    <div class="sk-metric-lbl">Total Loading</div>
                                    <div class="sk-metric-val">{{ number_format($data['total_loading']) }} <span class="sk-metric-val-unit">L</span></div>
                                </div>
                                <div class="sk-metric green">
                                    <div class="sk-metric-ico green">💰</div>
                                    <div class="sk-metric-lbl">Terjual</div>
                                    <div class="sk-metric-val">{{ number_format($data['total_terjual']) }} <span class="sk-metric-val-unit">L</span></div>
                                </div>
                                <div class="sk-metric amber">
                                    <div class="sk-metric-ico amber">📊</div>
                                    <div class="sk-metric-lbl">Sisa</div>
                                    <div class="sk-metric-val">{{ number_format($data['total_sisa']) }} <span class="sk-metric-val-unit">L</span></div>
                                </div>
                            </div>

                            {{-- Detail Table --}}
                            @if($data['detail']->count() > 0)
                                <div class="sk-detail-title">Detail Produk</div>
                                <div class="sk-tbl">
                                    <div style="overflow-x:auto;">
                                        <table style="width:100%; border-collapse:separate; border-spacing:0;">
                                            <thead class="sk-tbl-head">
                                                <tr>
                                                    <th style="text-align:left;">Produk</th>
                                                    <th style="text-align:right;">Loading</th>
                                                    <th style="text-align:right;">Terjual</th>
                                                    <th style="text-align:right;">Sisa</th>
                                                    <th style="text-align:right;">%</th>
                                                </tr>
                                            </thead>
                                            <tbody class="sk-tbl-body">
                                                @foreach($data['detail'] as $item)
                                                    @php
                                                        $percentage = $item['loading'] > 0 ? round(($item['terjual'] / $item['loading']) * 100) : 0;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="sk-prod">
                                                                <span class="sk-prod-dot"></span>
                                                                <span class="sk-prod-name">{{ $item['produk']->nama }}</span>
                                                            </div>
                                                        </td>
                                                        <td style="text-align:right;">
                                                            <span class="sk-vol loading">{{ number_format($item['loading']) }} L</span>
                                                        </td>
                                                        <td style="text-align:right;">
                                                            <span class="sk-vol terjual">{{ number_format($item['terjual']) }} L</span>
                                                        </td>
                                                        <td style="text-align:right;">
                                                            <span class="sk-vol sisa">{{ number_format($item['sisa']) }} L</span>
                                                        </td>
                                                        <td style="text-align:right;">
                                                            <div class="sk-progress">
                                                                <div class="sk-progress-bar">
                                                                    <div class="sk-progress-fill" style="width:{{ $percentage }}%"></div>
                                                                </div>
                                                                <span class="sk-progress-pct">{{ $percentage }}%</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="sk-card-empty">
                                    <div class="sk-card-empty-ico">
                                        <svg width="24" height="24" fill="none" stroke="#3b82f6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <div class="sk-card-empty-txt">Tidak ada data stok untuk kendaraan ini</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="sk-empty">
                        <div class="sk-empty-ico">
                            <svg width="36" height="36" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div class="sk-empty-title">Tidak Ada Data Stok Kendaraan</div>
                        <div class="sk-empty-sub">Belum ada loading yang dicatat untuk sales manapun</div>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
