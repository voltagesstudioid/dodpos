<x-app-layout>
    @push('styles')
    <style>
        .rk-page { max-width:80rem; margin:0 auto; padding:0 1rem; }

        /* Header */
        .rk-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; }
        .rk-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#8b5cf6,#7c3aed);
            box-shadow:0 8px 24px rgba(124,58,237,0.3);
        }
        .rk-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .rk-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        /* KPI Row */
        .rk-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:0.875rem; margin-bottom:1.5rem; }
        .rk-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .rk-kpi::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .rk-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .rk-kpi.purple::before  { background:linear-gradient(90deg,#8b5cf6,#7c3aed); }
        .rk-kpi.indigo::before  { background:linear-gradient(90deg,#6366f1,#4f46e5); }
        .rk-kpi.fuchsia::before { background:linear-gradient(90deg,#d946ef,#c026d3); }
        .rk-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.625rem; }
        .rk-kpi-lbl { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .rk-kpi-ico {
            width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem;
        }
        .rk-kpi-ico.purple  { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .rk-kpi-ico.indigo  { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
        .rk-kpi-ico.fuchsia { background:linear-gradient(135deg,#fdf4ff,#fae8ff); }
        .rk-kpi-val { font-size:1.75rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
        .rk-kpi-val.purple  { color:#7c3aed; }
        .rk-kpi-val.indigo  { color:#4f46e5; }
        .rk-kpi-val.fuchsia { color:#c026d3; }
        .rk-kpi-foot { font-size:0.7rem; color:#94a3b8; margin-top:0.375rem; }

        /* Filter */
        .rk-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .rk-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .rk-ff-fld { min-width:140px; }
        .rk-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .rk-finput {
            width:100%; padding:0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .rk-finput:focus { border-color:#8b5cf6; box-shadow:0 0 0 3px rgba(139,92,246,0.12); }
        .rk-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .rk-fsel:focus { border-color:#8b5cf6; box-shadow:0 0 0 3px rgba(139,92,246,0.12); }
        .rk-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; box-shadow:0 4px 12px rgba(124,58,237,0.25);
        }
        .rk-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(124,58,237,0.35); }
        .rk-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .rk-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        /* Table */
        .rk-tbl {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .rk-tbl-head {
            background:linear-gradient(180deg,#f5f3ff,#ede9fe); border-bottom:2px solid #ddd6fe;
        }
        .rk-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#5b21b6; white-space:nowrap;
        }
        .rk-tbl-body td {
            padding:0.9375rem 1.25rem; border-bottom:1px solid #f5f3ff; font-size:0.8125rem;
            color:#374151; vertical-align:middle;
        }
        .rk-tbl-body tr { transition:background 0.15s; }
        .rk-tbl-body tr:last-child td { border-bottom:none; }
        .rk-tbl-body tr:hover td { background:linear-gradient(90deg,#faf8ff,#f5f3ff); }

        /* Produk */
        .rk-prod { display:flex; align-items:center; gap:0.75rem; }
        .rk-prod-av {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:1.1rem; flex-shrink:0;
            background:linear-gradient(135deg,#f5f3ff,#ede9fe); border:1.5px solid #ddd6fe;
        }
        .rk-prod-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .rk-prod-type { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        /* Money/Numbers */
        .rk-num { text-align:right; font-weight:700; letter-spacing:-0.01em; }
        .rk-num.purple  { color:#7c3aed; font-size:0.875rem; }
        .rk-num.indigo  { color:#4f46e5; }
        .rk-num.gray    { color:#475569; }
        .rk-num.green   { color:#059669; }
        .rk-num.red     { color:#dc2626; }
        .rk-num.amber   { color:#d97706; }
        .rk-num-sub { font-size:0.6875rem; color:#94a3b8; margin-top:2px; text-align:right; }

        /* Status */
        .rk-status {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .rk-status.sesuai { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .rk-status.selisih { background:#fff1f2; color:#e11d48; border-color:#fecdd3; }
        .rk-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .rk-status-dot.sesuai { background:#10b981; }
        .rk-status-dot.selisih { background:#e11d48; animation:rk-pulse 1.5s infinite; }
        @keyframes rk-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Progress Bar */
        .rk-pbar { height:5px; border-radius:99px; background:#e2e8f0; margin-top:5px; overflow:hidden; min-width:60px; }
        .rk-pbar-fill { height:100%; border-radius:99px; transition:width 0.4s ease; }

        /* Empty */
        .rk-empty { text-align:center; padding:3.5rem 1.5rem; }
        .rk-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#f5f3ff,#ede9fe); display:flex; align-items:center; justify-content:center;
        }
        .rk-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .rk-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .rk-empty-hint {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.5rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:500;
            background:#f5f3ff; color:#5b21b6; border:1px solid #ddd6fe;
        }

        @media(max-width:1024px) { .rk-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px) { .rk-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="rk-page">

            {{-- Header --}}
            <div class="rk-hdr">
                <div class="rk-hdr-ico">🔄</div>
                <div>
                    <div class="rk-hdr-title">Rekonsiliasi Stok</div>
                    <div class="rk-hdr-sub">Perbandingan loading, penjualan, dan sisa stok per produk</div>
                </div>
            </div>

            {{-- KPI Cards --}}
            <div class="rk-kpis">
                <div class="rk-kpi purple">
                    <div class="rk-kpi-top">
                        <span class="rk-kpi-lbl">Total Loading</span>
                        <div class="rk-kpi-ico purple">📦</div>
                    </div>
                    <div class="rk-kpi-val purple">{{ $stats['total_loading'] }}</div>
                    <div class="rk-kpi-foot">Jumlah loading pada tanggal dipilih</div>
                </div>
                <div class="rk-kpi indigo">
                    <div class="rk-kpi-top">
                        <span class="rk-kpi-lbl">Sales Terlibat</span>
                        <div class="rk-kpi-ico indigo">👤</div>
                    </div>
                    <div class="rk-kpi-val indigo">{{ $stats['total_sales'] }}</div>
                    <div class="rk-kpi-foot">Sales yang memiliki loading</div>
                </div>
                <div class="rk-kpi fuchsia">
                    <div class="rk-kpi-top">
                        <span class="rk-kpi-lbl">Produk Direkonsiliasi</span>
                        <div class="rk-kpi-ico fuchsia">🛢️</div>
                    </div>
                    <div class="rk-kpi-val fuchsia">{{ $stats['total_produk'] }}</div>
                    <div class="rk-kpi-foot">Jumlah produk yang dibandingkan</div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="rk-filter">
                <form method="GET" class="rk-ff">
                    <div class="rk-ff-fld">
                        <label class="rk-flbl">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="rk-finput">
                    </div>
                    <div>
                        <label class="rk-flbl">Sales</label>
                        <select name="sales_id" class="rk-fsel">
                            <option value="">Semua Sales</option>
                            @foreach($salesList as $s)
                                <option value="{{ $s->id }}" {{ $salesId == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="rk-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('minyak.rekonsiliasi.index') }}" class="rk-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- Table --}}
            <div class="rk-tbl">
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:separate; border-spacing:0;">
                        <thead class="rk-tbl-head">
                            <tr>
                                <th style="text-align:left;">Produk</th>
                                <th style="text-align:right;">Loading</th>
                                <th style="text-align:right;">Terjual</th>
                                <th style="text-align:right;">Sisa Sistem</th>
                                <th style="text-align:right;">Persentase Terjual</th>
                                <th style="text-align:center;">Status</th>
                            </tr>
                        </thead>
                        <tbody class="rk-tbl-body">
                            @forelse($rekonsiliasi as $r)
                                @php
                                    $pct = $r['jumlah_loading'] > 0 ? round(($r['terjual'] / $r['jumlah_loading']) * 100) : 0;
                                    $pctColor = $pct >= 75 ? '#059669' : ($pct >= 40 ? '#d97706' : '#7c3aed');
                                    $prodName = $r['produk']->nama ?? 'Produk';
                                    $prodType = $r['produk']->tipe ?? '';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="rk-prod">
                                            <div class="rk-prod-av">🛢️</div>
                                            <div>
                                                <div class="rk-prod-name">{{ $prodName }}</div>
                                                <div class="rk-prod-type">{{ $prodType }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="rk-num purple">{{ number_format($r['jumlah_loading'], 0, ',', '.') }} L</div>
                                    </td>
                                    <td>
                                        <div class="rk-num indigo">{{ number_format($r['terjual'], 0, ',', '.') }} L</div>
                                    </td>
                                    <td>
                                        <div class="rk-num {{ $r['sisa_sistem'] > 0 ? 'amber' : 'green' }}">{{ number_format($r['sisa_sistem'], 0, ',', '.') }} L</div>
                                        <div class="rk-num-sub">sisa stok</div>
                                    </td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:0.5rem;justify-content:flex-end;">
                                            <span class="rk-num gray" style="min-width:35px;">{{ $pct }}%</span>
                                            <div class="rk-pbar" style="width:80px;">
                                                <div class="rk-pbar-fill" style="width:{{ min($pct, 100) }}%; background:linear-gradient(90deg,{{ $pctColor }},{{ $pctColor }}dd);"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="rk-status {{ $r['status'] }}">
                                            <span class="rk-status-dot {{ $r['status'] }}"></span>
                                            {{ $r['status'] == 'sesuai' ? 'Sesuai' : 'Ada Selisih' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="rk-empty">
                                            <div class="rk-empty-ico">
                                                <svg width="32" height="32" fill="none" stroke="#7c3aed" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <div class="rk-empty-title">Belum Ada Data Rekonsiliasi</div>
                                            <div class="rk-empty-sub">Pilih tanggal yang memiliki data loading untuk melihat rekonsiliasi</div>
                                            <div class="rk-empty-hint">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Pastikan ada data loading pada tanggal yang dipilih
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
