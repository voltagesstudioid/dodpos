<x-app-layout>
    @push('styles')
    <style>
        .pj-page { max-width:80rem; margin:0 auto; padding:0 1rem; }

        /* Header */
        .pj-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .pj-hdr-l { display:flex; align-items:center; gap:1rem; }
        .pj-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#10b981,#059669);
            box-shadow:0 8px 24px rgba(5,150,105,0.3);
        }
        .pj-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .pj-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .pj-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s; border:none; cursor:pointer;
            background:linear-gradient(135deg,#10b981,#059669); color:#fff;
            box-shadow:0 6px 20px rgba(5,150,105,0.35);
        }
        .pj-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(5,150,105,0.45); }

        /* KPI Row */
        .pj-kpis { display:grid; grid-template-columns:repeat(4,1fr); gap:0.875rem; margin-bottom:1.5rem; }
        .pj-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.25rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .pj-kpi::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .pj-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .pj-kpi.amber::before  { background:linear-gradient(90deg,#f59e0b,#d97706); }
        .pj-kpi.green::before  { background:linear-gradient(90deg,#10b981,#059669); }
        .pj-kpi.purple::before { background:linear-gradient(90deg,#8b5cf6,#7c3aed); }
        .pj-kpi.red::before    { background:linear-gradient(90deg,#ef4444,#dc2626); }
        .pj-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem; }
        .pj-kpi-lbl { font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .pj-kpi-ico {
            width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.15rem;
        }
        .pj-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .pj-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .pj-kpi-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .pj-kpi-ico.red    { background:linear-gradient(135deg,#fff1f2,#ffe4e6); }
        .pj-kpi-val { font-size:1.5rem; font-weight:800; letter-spacing:-0.02em; line-height:1; }
        .pj-kpi-val.amber  { color:#d97706; }
        .pj-kpi-val.green  { color:#059669; }
        .pj-kpi-val.purple { color:#7c3aed; }
        .pj-kpi-val.red    { color:#dc2626; }
        .pj-kpi-foot { font-size:0.7rem; color:#94a3b8; margin-top:0.25rem; }
        .pj-kpi-val-sm { font-size:1.15rem; }

        /* Filter */
        .pj-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .pj-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .pj-ff-fld { flex:1; min-width:160px; }
        .pj-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .pj-fwrap { position:relative; }
        .pj-fico { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#94a3b8; pointer-events:none; }
        .pj-finput {
            width:100%; padding:0.625rem 1rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .pj-finput:focus { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,0.12); }
        .pj-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .pj-fsel:focus { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,0.12); }
        .pj-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 4px 12px rgba(5,150,105,0.25);
        }
        .pj-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(5,150,105,0.35); }
        .pj-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .pj-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        /* Table */
        .pj-tbl {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .pj-tbl-head {
            background:linear-gradient(180deg,#ecfdf5,#f0fdf4); border-bottom:2px solid #a7f3d0;
        }
        .pj-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#065f46; white-space:nowrap;
        }
        .pj-tbl-body td {
            padding:0.9375rem 1.25rem; border-bottom:1px solid #f0fdf4; font-size:0.8125rem;
            color:#374151; vertical-align:middle;
        }
        .pj-tbl-body tr { transition:background 0.15s; }
        .pj-tbl-body tr:last-child td { border-bottom:none; }
        .pj-tbl-body tr:hover td { background:linear-gradient(90deg,#fafffe,#f0fdf4); }

        /* Faktur Code */
        .pj-faktur {
            display:inline-flex; padding:0.2rem 0.5rem; border-radius:6px; font-size:0.6875rem; font-weight:700;
            background:#f0fdf4; color:#065f46; letter-spacing:0.02em; font-family:monospace; border:1px solid #a7f3d0;
        }
        .pj-date { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .pj-date-sub { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        /* Sales Avatar */
        .pj-sales { display:flex; align-items:center; gap:0.625rem; }
        .pj-sales-av {
            width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center;
            font-size:0.8125rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#065f46; border:1.5px solid #a7f3d0;
        }
        .pj-sales-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }

        /* Pelanggan */
        .pj-pel-name { font-size:0.8125rem; font-weight:500; color:#1e293b; }
        .pj-prod { font-size:0.8125rem; font-weight:500; color:#1e293b; }
        .pj-prod-qty { font-size:0.6875rem; color:#94a3b8; font-weight:500; }

        /* Total */
        .pj-total { text-align:right; }
        .pj-total-val { font-size:0.9375rem; font-weight:700; color:#0f172a; letter-spacing:-0.01em; }
        .pj-total-tipe { font-size:0.625rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-top:2px; }
        .pj-total-tipe.tunai { color:#059669; }
        .pj-total-tipe.hutang { color:#dc2626; }
        .pj-total-tipe.transfer { color:#2563eb; }

        /* Status */
        .pj-status {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .pj-status.pending { background:#fffbeb; color:#d97706; border-color:#fde68a; }
        .pj-status.terverifikasi { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .pj-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .pj-status-dot.pending { background:#f59e0b; animation:pj-pulse 1.5s infinite; }
        .pj-status-dot.terverifikasi { background:#10b981; }
        @keyframes pj-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Actions */
        .pj-acts { display:flex; gap:0.375rem; align-items:center; justify-content:center; }
        .pj-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.3125rem 0.625rem; border-radius:7px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
        }
        .pj-act-view { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .pj-act-view:hover { background:#dbeafe; border-color:#93c5fd; }
        .pj-act-ok { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .pj-act-ok:hover { background:#d1fae5; border-color:#6ee7b7; }
        .pj-act-print { background:#f5f3ff; color:#6d28d9; border-color:#ddd6fe; }
        .pj-act-print:hover { background:#ede9fe; border-color:#c4b5fd; }

        /* Empty */
        .pj-empty { text-align:center; padding:3.5rem 1.5rem; }
        .pj-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#ecfdf5,#d1fae5); display:flex; align-items:center; justify-content:center;
        }
        .pj-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .pj-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .pj-empty-cta {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            background:linear-gradient(135deg,#10b981,#059669); color:#fff; text-decoration:none;
            box-shadow:0 6px 20px rgba(5,150,105,0.25); transition:all 0.2s;
        }
        .pj-empty-cta:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(5,150,105,0.4); }

        @media(max-width:1024px) { .pj-kpis { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:640px) { .pj-kpis { grid-template-columns:1fr; } .pj-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="pj-page">

            {{-- Header --}}
            <div class="pj-hdr">
                <div class="pj-hdr-l">
                    <div class="pj-hdr-ico">💰</div>
                    <div>
                        <div class="pj-hdr-title">Penjualan</div>
                        <div class="pj-hdr-sub">Data transaksi penjualan BBM dan verifikasi</div>
                    </div>
                </div>
                @if(Route::has('minyak.penjualan.create'))
                <a href="{{ route('minyak.penjualan.create') }}" class="pj-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Penjualan
                </a>
                @endif
            </div>

            {{-- KPI Cards --}}
            <div class="pj-kpis">
                <div class="pj-kpi amber">
                    <div class="pj-kpi-top">
                        <span class="pj-kpi-lbl">Total Hari Ini</span>
                        <div class="pj-kpi-ico amber">💵</div>
                    </div>
                    <div class="pj-kpi-val amber pj-kpi-val-sm">Rp {{ number_format($stats['total_hari_ini'], 0, ',', '.') }}</div>
                    <div class="pj-kpi-foot">Pendapatan hari ini</div>
                </div>
                <div class="pj-kpi green">
                    <div class="pj-kpi-top">
                        <span class="pj-kpi-lbl">Transaksi</span>
                        <div class="pj-kpi-ico green">📋</div>
                    </div>
                    <div class="pj-kpi-val green">{{ $stats['total_transaksi'] }}</div>
                    <div class="pj-kpi-foot">Jumlah transaksi hari ini</div>
                </div>
                <div class="pj-kpi purple">
                    <div class="pj-kpi-top">
                        <span class="pj-kpi-lbl">Tunai</span>
                        <div class="pj-kpi-ico purple">💳</div>
                    </div>
                    <div class="pj-kpi-val purple pj-kpi-val-sm">Rp {{ number_format($stats['total_tunai'], 0, ',', '.') }}</div>
                    <div class="pj-kpi-foot">Pembayaran tunai</div>
                </div>
                @if(!$isSalesRole)
                <div class="pj-kpi red">
                    <div class="pj-kpi-top">
                        <span class="pj-kpi-lbl">Hutang Baru</span>
                        <div class="pj-kpi-ico red">📄</div>
                    </div>
                    <div class="pj-kpi-val red pj-kpi-val-sm">Rp {{ number_format($stats['total_hutang'], 0, ',', '.') }}</div>
                    <div class="pj-kpi-foot">Hutang baru hari ini</div>
                </div>
                @endif
            </div>

            {{-- Filter --}}
            <div class="pj-filter">
                <form method="GET" class="pj-ff">
                    <div class="pj-ff-fld">
                        <label class="pj-flbl">Pencarian</label>
                        <div class="pj-fwrap">
                            <svg class="pj-fico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no faktur..." class="pj-finput">
                        </div>
                    </div>
                    @if(! $isSalesRole)
                    <div>
                        <label class="pj-flbl">Sales</label>
                        <select name="sales_id" class="pj-fsel">
                            <option value="">Semua Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div>
                        <label class="pj-flbl">Tipe Bayar</label>
                        <select name="tipe_bayar" class="pj-fsel">
                            <option value="">Semua Tipe</option>
                            <option value="tunai" {{ request('tipe_bayar') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                            @if(!$isSalesRole)
                            <option value="hutang" {{ request('tipe_bayar') == 'hutang' ? 'selected' : '' }}>Hutang</option>
                            @endif
                            <option value="transfer" {{ request('tipe_bayar') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                    </div>
                    @if(!$isSalesRole)
                    <div>
                        <label class="pj-flbl">Status</label>
                        <select name="status" class="pj-fsel">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                    @endif
                    <button type="submit" class="pj-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('minyak.penjualan.index') }}" class="pj-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
            <div style="margin-bottom:1rem;padding:0.75rem 1rem;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:10px;font-size:0.8125rem;font-weight:600;color:#065f46;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div style="margin-bottom:1rem;padding:0.75rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;font-size:0.8125rem;font-weight:600;color:#991b1b;">{{ session('error') }}</div>
            @endif

            {{-- Pending Transfer Alert --}}
            @if(!$isSalesRole && isset($stats['transfer_pending']) && $stats['transfer_pending'] > 0)
            <div style="margin-bottom:1rem;padding:0.875rem 1.25rem;background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1.5px solid #6ee7b7;border-radius:14px;display:flex;align-items:center;gap:0.75rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:#059669;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.8125rem;font-weight:700;color:#065f46;">{{ $stats['transfer_pending'] }} pembayaran transfer menunggu persetujuan</div>
                    <div style="font-size:0.6875rem;color:#059669;margin-top:2px;">Periksa bukti transfer dan approve jika data sudah sesuai</div>
                </div>
                <a href="{{ route('minyak.penjualan.index', ['tipe_bayar' => 'transfer', 'status' => 'pending']) }}" style="padding:0.5rem 1rem;border-radius:10px;background:#059669;color:#fff;font-size:0.75rem;font-weight:700;text-decoration:none;white-space:nowrap;">Lihat Semua</a>
            </div>
            @endif

            {{-- Table --}}
            <div class="pj-tbl">
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:separate; border-spacing:0;">
                        <thead class="pj-tbl-head">
                            <tr>
                                <th style="text-align:left;">No Faktur</th>
                                <th style="text-align:left;">Tanggal</th>
                                <th style="text-align:left;">Sales</th>
                                <th style="text-align:left;">Pelanggan</th>
                                <th style="text-align:left;">Produk</th>
                                <th style="text-align:right;">Total</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="pj-tbl-body">
                            @forelse($penjualans as $p)
                                <tr>
                                    <td>
                                        <span class="pj-faktur">{{ $p->no_faktur }}</span>
                                    </td>
                                    <td>
                                        <div class="pj-date">{{ $p->tanggal_jual->format('d M Y') }}</div>
                                        <div class="pj-date-sub">{{ $p->tanggal_jual->isoFormat('dddd') }}</div>
                                    </td>
                                    <td>
                                        <div class="pj-sales">
                                            <div class="pj-sales-av">{{ substr($p->sales->nama, 0, 1) }}</div>
                                            <div class="pj-sales-name">{{ $p->sales->nama }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="pj-pel-name">{{ $p->pelanggan->nama_toko }}</div>
                                    </td>
                                    <td>
                                        <div class="pj-prod">{{ $p->produk->nama }}</div>
                                        <div class="pj-prod-qty">{{ number_format($p->jumlah) }} unit</div>
                                    </td>
                                    <td>
                                        <div class="pj-total">
                                            <div class="pj-total-val">Rp {{ number_format($p->total, 0, ',', '.') }}</div>
                                            <div class="pj-total-tipe {{ $p->tipe_bayar }}">{{ ucfirst($p->tipe_bayar) }}</div>
                                            @if($p->tipe_bayar === 'transfer' && $p->no_bukti_transfer)
                                            <div style="font-size:10px;color:#0284c7;font-family:'JetBrains Mono',monospace;font-weight:600;margin-top:2px;" title="No. Bukti Transfer">{{ Str::limit($p->no_bukti_transfer, 15) }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="pj-status {{ $p->status }}">
                                            <span class="pj-status-dot {{ $p->status }}"></span>
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td style="text-align:center;">
                                        <div class="pj-acts">
                                            <a href="{{ route('minyak.penjualan.show', $p) }}" class="pj-act pj-act-view">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Detail
                                            </a>
                                            @if(Route::has('minyak.penjualan.print'))
                                            <a href="{{ route('minyak.penjualan.print', $p) }}" target="_blank" class="pj-act pj-act-print" title="Cetak Struk">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                Cetak
                                            </a>
                                            @endif
                                            @if(! $isSalesRole && $p->status == 'pending' && Route::has('minyak.penjualan.verify'))
                                                <form action="{{ route('minyak.penjualan.verify', $p) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="pj-act pj-act-ok">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        Verifikasi
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="pj-empty">
                                            <div class="pj-empty-ico">
                                                <svg width="32" height="32" fill="none" stroke="#059669" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            </div>
                                            <div class="pj-empty-title">Belum Ada Data Penjualan</div>
                                            <div class="pj-empty-sub">Data akan muncul setelah ada transaksi penjualan</div>
                                            @if(Route::has('minyak.penjualan.create'))
                                            <a href="{{ route('minyak.penjualan.create') }}" class="pj-empty-cta">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Tambah Penjualan Pertama
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($penjualans->hasPages())
                    <div style="padding:0.875rem 1.25rem; border-top:1px solid #f0fdf4;">
                        {{ $penjualans->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
    @if(session('auto_print'))
    @push('scripts')
    <script>
    (function(){
        var id = '{{ session('auto_print') }}';
        var url = '{{ route('minyak.penjualan.print', '__ID__') }}'.replace('__ID__', id);
        setTimeout(function(){ window.open(url, '_blank'); }, 500);
    })();
    </script>
    @endpush
    @endif
</x-app-layout>
