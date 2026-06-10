<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .pj-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .pj-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .pj-hdr-l { display:flex; align-items:center; gap:1rem; }
        .pj-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            box-shadow:0 8px 24px rgba(37,99,235,0.3);
        }
        .pj-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .pj-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .pj-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s; border:none; cursor:pointer; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 6px 20px rgba(37,99,235,0.35);
        }
        .pj-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(37,99,235,0.45); }

        /* KPI Row */
        .pj-kpis { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
        .pj-kpis.cols-3 { grid-template-columns:repeat(3,1fr); }
        .pj-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .pj-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .pj-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .pj-kpi.blue::before   { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .pj-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .pj-kpi.purple::before { background:linear-gradient(180deg,#8b5cf6,#7c3aed); }
        .pj-kpi.red::before    { background:linear-gradient(180deg,#ef4444,#dc2626); }
        .pj-kpi-top { display:flex; align-items:center; justify-content:space-between; }
        .pj-kpi-left { display:flex; flex-direction:column; gap:0.25rem; }
        .pj-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .pj-kpi-val { font-size:1.75rem; font-weight:800; letter-spacing:-0.02em; line-height:1; }
        .pj-kpi-val.blue   { color:#2563eb; }
        .pj-kpi-val.green  { color:#059669; }
        .pj-kpi-val.purple { color:#7c3aed; }
        .pj-kpi-val.red    { color:#dc2626; }
        .pj-kpi-unit { font-size:0.875rem; font-weight:600; color:#94a3b8; margin-left:0.125rem; }
        .pj-kpi-foot { font-size:0.72rem; color:#94a3b8; margin-top:0.375rem; }
        .pj-kpi-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
        .pj-kpi-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .pj-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .pj-kpi-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .pj-kpi-ico.red    { background:linear-gradient(135deg,#fef2f2,#fee2e2); }

        /* Filter */
        .pj-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .pj-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .pj-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .pj-finput {
            width:100%; padding:0.625rem 0.875rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .pj-finput:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .pj-finput-ico { position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }
        .pj-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .pj-fsel:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .pj-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 4px 12px rgba(37,99,235,0.25);
        }
        .pj-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(37,99,235,0.35); }
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
        .pj-tbl-head { background:linear-gradient(180deg,#eff6ff,#f0f7ff); border-bottom:2px solid #bfdbfe; }
        .pj-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#1e40af; white-space:nowrap;
        }
        .pj-tbl-body td { padding:0.9375rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .pj-tbl-body tr { transition:background 0.15s; }
        .pj-tbl-body tr:last-child td { border-bottom:none; }
        .pj-tbl-body tr:hover td { background:linear-gradient(90deg,#f8faff,#eff6ff); }

        /* Faktur cell */
        .pj-faktur { display:inline-flex; align-items:center; gap:0.375rem; }
        .pj-faktur-badge {
            width:22px; height:22px; border-radius:6px; display:flex; align-items:center; justify-content:center;
            font-size:0.5625rem; font-weight:800; background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#2563eb;
            flex-shrink:0;
        }
        .pj-faktur-val { font-size:0.8125rem; font-weight:600; color:#1e293b; font-family:'JetBrains Mono',monospace; letter-spacing:-0.01em; }

        /* Date cell */
        .pj-date { font-size:0.8125rem; color:#475569; display:flex; align-items:center; gap:0.375rem; }
        .pj-date-ico { color:#94a3b8; flex-shrink:0; }

        /* Sales cell */
        .pj-sales { display:flex; align-items:center; gap:0.75rem; }
        .pj-sales-av {
            width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center;
            font-size:1rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 4px 12px rgba(37,99,235,0.2);
        }
        .pj-sales-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }

        /* Customer cell */
        .pj-cust { font-size:0.8125rem; font-weight:500; color:#1e293b; }

        /* Product cell */
        .pj-prod { font-size:0.8125rem; color:#475569; }
        .pj-prod-qty { font-size:0.6875rem; font-weight:600; color:#2563eb; background:#eff6ff; padding:0.125rem 0.375rem; border-radius:4px; margin-left:0.25rem; }

        /* Total cell */
        .pj-total { font-size:0.875rem; font-weight:700; color:#1e293b; text-align:right; }

        /* Status badge */
        .pj-status {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .pj-status.terverifikasi { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .pj-status.pending       { background:#fffbeb; color:#d97706; border-color:#fde68a; }
        .pj-status.batal         { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
        .pj-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .pj-status-dot.terverifikasi { background:#10b981; }
        .pj-status-dot.pending       { background:#f59e0b; box-shadow:0 0 0 2px rgba(245,158,11,0.2); animation:pj-pulse 1.5s infinite; }
        .pj-status-dot.batal         { background:#dc2626; }

        /* Transfer badge */
        .pj-transfer-badge {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.125rem 0.375rem; border-radius:4px; font-size:0.5625rem; font-weight:700;
            background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; margin-top:3px;
            letter-spacing:0.02em;
        }
        @keyframes pj-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Actions */
        .pj-acts { display:flex; align-items:center; gap:0.375rem; justify-content:center; }
        .pj-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.375rem 0.625rem; border-radius:8px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
        }
        .pj-act.detail { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .pj-act.detail:hover { background:#dbeafe; border-color:#93c5fd; }
        .pj-act.verify { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .pj-act.verify:hover { background:#d1fae5; border-color:#6ee7b7; }
        .pj-act.print { background:#f0f9ff; color:#0369a1; border-color:#bae6fd; }
        .pj-act.print:hover { background:#e0f2fe; border-color:#7dd3fc; }

        /* Empty */
        .pj-empty { text-align:center; padding:3.5rem 1.5rem; }
        .pj-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#eff6ff,#dbeafe); display:flex; align-items:center; justify-content:center;
        }
        .pj-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .pj-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .pj-empty-cta {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; text-decoration:none;
            box-shadow:0 6px 20px rgba(37,99,235,0.25); transition:all 0.2s; font-family:inherit;
        }
        .pj-empty-cta:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(37,99,235,0.4); }

        @media(max-width:1024px) { .pj-kpis { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:768px)  { .pj-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px)  { .pj-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="pj-page">

            {{-- Header --}}
            {{-- Flash Messages --}}
            @if(session('success'))
            <div style="margin-bottom:1rem;padding:0.75rem 1rem;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:10px;font-size:0.8125rem;font-weight:600;color:#065f46;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div style="margin-bottom:1rem;padding:0.75rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;font-size:0.8125rem;font-weight:600;color:#991b1b;">{{ session('error') }}</div>
            @endif
            <div class="pj-hdr">
                <div class="pj-hdr-l">
                    <div class="pj-hdr-ico">📊</div>
                    <div>
                        <div class="pj-hdr-title">Data Penjualan</div>
                        <div class="pj-hdr-sub">Monitoring transaksi penjualan mineral</div>
                    </div>
                </div>
                @if(Route::has('mineral.penjualan.create'))
                <a href="{{ route('mineral.penjualan.create') }}" class="pj-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Penjualan
                </a>
                @endif
            </div>

            {{-- KPI Cards --}}
            <div class="pj-kpis {{ $isSalesRole ? 'cols-3' : '' }}">
                <div class="pj-kpi blue">
                    <div class="pj-kpi-top">
                        <div class="pj-kpi-left">
                            <span class="pj-kpi-lbl">Total Hari Ini</span>
                            <div>
                                <span class="pj-kpi-val blue">Rp {{ number_format($stats['total_hari_ini'], 0, ',', '.') }}</span>
                            </div>
                            <div class="pj-kpi-foot">Pendapatan penjualan hari ini</div>
                        </div>
                        <div class="pj-kpi-ico blue">💰</div>
                    </div>
                </div>
                <div class="pj-kpi green">
                    <div class="pj-kpi-top">
                        <div class="pj-kpi-left">
                            <span class="pj-kpi-lbl">Transaksi</span>
                            <div>
                                <span class="pj-kpi-val green">{{ $stats['total_transaksi'] }}</span>
                                <span class="pj-kpi-unit">transaksi</span>
                            </div>
                            <div class="pj-kpi-foot">Jumlah transaksi hari ini</div>
                        </div>
                        <div class="pj-kpi-ico green">📋</div>
                    </div>
                </div>
                <div class="pj-kpi purple">
                    <div class="pj-kpi-top">
                        <div class="pj-kpi-left">
                            <span class="pj-kpi-lbl">Tunai</span>
                            <div>
                                <span class="pj-kpi-val purple">Rp {{ number_format($stats['total_tunai'], 0, ',', '.') }}</span>
                            </div>
                            <div class="pj-kpi-foot">Pembayaran tunai hari ini</div>
                        </div>
                        <div class="pj-kpi-ico purple">💵</div>
                    </div>
                </div>
                @if(!$isSalesRole)
                <div class="pj-kpi red">
                    <div class="pj-kpi-top">
                        <div class="pj-kpi-left">
                            <span class="pj-kpi-lbl">Hutang Baru</span>
                            <div>
                                <span class="pj-kpi-val red">Rp {{ number_format($stats['total_hutang'], 0, ',', '.') }}</span>
                            </div>
                            <div class="pj-kpi-foot">Hutang baru hari ini</div>
                        </div>
                        <div class="pj-kpi-ico red">⚠️</div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Filter --}}
            <div class="pj-filter">
                <form method="GET" class="pj-ff">
                    <div style="position:relative;">
                        <label class="pj-flbl">Cari Faktur</label>
                        <div style="position:relative;">
                            <svg class="pj-finput-ico" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no faktur..." class="pj-finput" style="width:12rem;">
                        </div>
                    </div>
                    <div>
                        <label class="pj-flbl">Sales</label>
                        <select name="sales_id" class="pj-fsel">
                            <option value="">Semua Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
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
                    <a href="{{ route('mineral.penjualan.index') }}" class="pj-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- Pending Transfer Alert for Supervisors --}}
            @if(!$isSalesRole && $stats['transfer_pending'] > 0)
            <div style="margin-bottom:1rem;padding:0.875rem 1.25rem;background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1.5px solid #93c5fd;border-radius:14px;display:flex;align-items:center;gap:0.75rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:#3b82f6;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.8125rem;font-weight:700;color:#1e40af;">{{ $stats['transfer_pending'] }} pembayaran transfer menunggu persetujuan</div>
                    <div style="font-size:0.6875rem;color:#3b82f6;margin-top:2px;">Periksa bukti transfer dan approve jika data sudah sesuai</div>
                </div>
                <a href="{{ route('mineral.penjualan.index', ['tipe_bayar' => 'transfer', 'status' => 'pending']) }}" style="padding:0.5rem 1rem;border-radius:10px;background:#2563eb;color:#fff;font-size:0.75rem;font-weight:700;text-decoration:none;white-space:nowrap;">Lihat Semua</a>
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
                                <tr style="{{ $p->status === 'batal' ? 'opacity:0.5;' : '' }}">
                                    <td>
                                        <div class="pj-faktur">
                                            <div class="pj-faktur-badge">F</div>
                                            <span class="pj-faktur-val">{{ $p->no_faktur }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="pj-date">
                                            <svg class="pj-date-ico" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $p->tanggal_jual->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="pj-sales">
                                            <div class="pj-sales-av">{{ substr($p->sales->nama, 0, 1) }}</div>
                                            <div class="pj-sales-name">{{ $p->sales->nama }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="pj-cust">{{ $p->pelanggan->nama_toko }}</div>
                                    </td>
                                    <td>
                                        <div class="pj-prod">
                                            {{ $p->produk->nama }}
                                            <span class="pj-prod-qty">×{{ $p->jumlah }}</span>
                                        </div>
                                    </td>
                                    <td style="text-align:right;">
                                        <div class="pj-total">Rp {{ number_format($p->total, 0, ',', '.') }}</div>
                                        @if($p->tipe_bayar === 'transfer')
                                        <div class="pj-transfer-badge">
                                            🏦 Transfer
                                            @if($p->no_bukti_transfer)
                                            <span style="font-family:monospace;">{{ Str::limit($p->no_bukti_transfer, 12) }}</span>
                                            @endif
                                        </div>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="pj-status {{ $p->status }}">
                                            <span class="pj-status-dot {{ $p->status }}"></span>
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td style="text-align:center;">
                                        <div class="pj-acts">
                                            <a href="{{ route('mineral.penjualan.show', $p) }}" class="pj-act detail">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Detail
                                            </a>
                                            @if(Route::has('mineral.penjualan.print'))
                                            <a href="{{ route('mineral.penjualan.print', $p) }}" target="_blank" class="pj-act print" title="Cetak Struk">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                Cetak
                                            </a>
                                            @endif
                                            @if(!$isSalesRole && $p->status == 'pending' && Route::has('mineral.penjualan.verify'))
                                                <form action="{{ route('mineral.penjualan.verify', $p) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="pj-act verify">
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
                                                <svg width="32" height="32" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <div class="pj-empty-title">Belum Ada Data Penjualan</div>
                                            <div class="pj-empty-sub">Tambahkan transaksi penjualan untuk mulai mencatat data</div>
                                            @if(Route::has('mineral.penjualan.create'))
                                            <a href="{{ route('mineral.penjualan.create') }}" class="pj-empty-cta">
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
                    <div style="padding:0.875rem 1.25rem; border-top:1px solid #f1f5f9;">
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
        var url = '{{ route('mineral.penjualan.print', '__ID__') }}'.replace('__ID__', id);
        setTimeout(function(){ window.open(url, '_blank'); }, 500);
    })();
    </script>
    @endpush
    @endif
</x-app-layout>
