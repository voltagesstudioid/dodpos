<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .pr-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .pr-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .pr-hdr-l { display:flex; align-items:center; gap:1rem; }
        .pr-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            box-shadow:0 8px 24px rgba(37,99,235,0.3);
        }
        .pr-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .pr-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .pr-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s; border:none; cursor:pointer; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 6px 20px rgba(37,99,235,0.35);
        }
        .pr-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(37,99,235,0.45); }

        /* KPI Row */
        .pr-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
        .pr-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .pr-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .pr-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .pr-kpi.blue::before   { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .pr-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .pr-kpi.red::before    { background:linear-gradient(180deg,#ef4444,#dc2626); }
        .pr-kpi-top { display:flex; align-items:center; justify-content:space-between; }
        .pr-kpi-left { display:flex; flex-direction:column; gap:0.25rem; }
        .pr-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .pr-kpi-val { font-size:2.5rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
        .pr-kpi-val.blue   { color:#2563eb; }
        .pr-kpi-val.green  { color:#059669; }
        .pr-kpi-val.red    { color:#dc2626; }
        .pr-kpi-unit { font-size:1rem; font-weight:600; color:#94a3b8; margin-left:0.25rem; }
        .pr-kpi-foot { font-size:0.72rem; color:#94a3b8; margin-top:0.375rem; }
        .pr-kpi-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
        .pr-kpi-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .pr-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .pr-kpi-ico.red    { background:linear-gradient(135deg,#fef2f2,#fee2e2); }

        /* Filter */
        .pr-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .pr-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .pr-ff-fld { min-width:200px; flex:1; }
        .pr-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .pr-finput {
            width:100%; padding:0.625rem 0.875rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .pr-finput:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .pr-finput-ico { position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }
        .pr-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .pr-fsel:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .pr-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 4px 12px rgba(37,99,235,0.25);
        }
        .pr-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(37,99,235,0.35); }
        .pr-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .pr-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        /* Table */
        .pr-tbl {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .pr-tbl-head { background:linear-gradient(180deg,#eff6ff,#f0f7ff); border-bottom:2px solid #bfdbfe; }
        .pr-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#1e40af; white-space:nowrap;
        }
        .pr-tbl-body td { padding:0.9375rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .pr-tbl-body tr { transition:background 0.15s; }
        .pr-tbl-body tr:last-child td { border-bottom:none; }
        .pr-tbl-body tr:hover td { background:linear-gradient(90deg,#f8faff,#eff6ff); }

        /* Product cell */
        .pr-prod { display:flex; align-items:center; gap:0.75rem; }
        .pr-prod-ico {
            width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center;
            font-size:1.25rem; flex-shrink:0;
            background:linear-gradient(135deg,#eff6ff,#dbeafe); border:1.5px solid #bfdbfe;
        }
        .pr-prod-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .pr-prod-meta { font-size:0.6875rem; color:#94a3b8; margin-top:1px; font-family:'JetBrains Mono',monospace; }

        /* Type badge */
        .pr-type {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .pr-type.galon  { background:#eff6ff; color:#2563eb; border-color:#bfdbfe; }
        .pr-type.dus    { background:#f0f9ff; color:#0284c7; border-color:#bae6fd; }
        .pr-type.botol  { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .pr-type.gelas  { background:#fef3c7; color:#d97706; border-color:#fde68a; }
        .pr-type.other  { background:#f8fafc; color:#64748b; border-color:#e2e8f0; }

        /* Price cell */
        .pr-price { font-size:0.875rem; font-weight:700; color:#1e293b; text-align:right; }
        .pr-price-hpp { font-size:0.6875rem; color:#94a3b8; text-align:right; margin-top:1px; }

        /* Stock cell */
        .pr-stock { display:flex; align-items:center; gap:0.375rem; justify-content:center; }
        .pr-stock-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
        .pr-stock-dot.ok   { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.2); }
        .pr-stock-dot.low  { background:#ef4444; box-shadow:0 0 0 2px rgba(239,68,68,0.2); animation:pr-pulse 1.5s infinite; }
        @keyframes pr-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }
        .pr-stock-val { font-size:0.875rem; font-weight:700; }
        .pr-stock-val.ok  { color:#1e293b; }
        .pr-stock-val.low { color:#dc2626; }
        .pr-stock-sub { font-size:0.6875rem; color:#94a3b8; text-align:center; margin-top:1px; }
        .pr-stock-warn { font-size:0.6875rem; color:#dc2626; text-align:center; margin-top:1px; font-weight:600; display:flex; align-items:center; gap:0.25rem; justify-content:center; }

        /* Status badge */
        .pr-status {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .pr-status.aktif    { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .pr-status.nonaktif { background:#f8fafc; color:#64748b; border-color:#e2e8f0; }
        .pr-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .pr-status-dot.aktif    { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.2); }
        .pr-status-dot.nonaktif { background:#94a3b8; }

        /* Actions */
        .pr-acts { display:flex; align-items:center; gap:0.375rem; justify-content:center; }
        .pr-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.375rem 0.625rem; border-radius:8px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
        }
        .pr-act.detail { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .pr-act.detail:hover { background:#dbeafe; border-color:#93c5fd; }
        .pr-act.edit { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .pr-act.edit:hover { background:#d1fae5; border-color:#6ee7b7; }
        .pr-act.hapus { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
        .pr-act.hapus:hover { background:#fee2e2; border-color:#fca5a5; }

        /* Empty */
        .pr-empty { text-align:center; padding:3.5rem 1.5rem; }
        .pr-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#eff6ff,#dbeafe); display:flex; align-items:center; justify-content:center;
        }
        .pr-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .pr-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .pr-empty-cta {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; text-decoration:none;
            box-shadow:0 6px 20px rgba(37,99,235,0.25); transition:all 0.2s; font-family:inherit;
        }
        .pr-empty-cta:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(37,99,235,0.4); }

        @media(max-width:768px) { .pr-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px) { .pr-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="pr-page">

            {{-- Header --}}
            <div class="pr-hdr">
                <div class="pr-hdr-l">
                    <div class="pr-hdr-ico">📦</div>
                    <div>
                        <div class="pr-hdr-title">Data Produk</div>
                        <div class="pr-hdr-sub">Kelola produk air mineral</div>
                    </div>
                </div>
                <a href="{{ route('mineral.produk.create') }}" class="pr-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Produk
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="pr-kpis">
                <div class="pr-kpi blue">
                    <div class="pr-kpi-top">
                        <div class="pr-kpi-left">
                            <span class="pr-kpi-lbl">Total Produk</span>
                            <div>
                                <span class="pr-kpi-val blue">{{ $stats['total'] }}</span>
                                <span class="pr-kpi-unit">item</span>
                            </div>
                            <div class="pr-kpi-foot">Seluruh produk terdaftar</div>
                        </div>
                        <div class="pr-kpi-ico blue">📦</div>
                    </div>
                </div>
                <div class="pr-kpi green">
                    <div class="pr-kpi-top">
                        <div class="pr-kpi-left">
                            <span class="pr-kpi-lbl">Produk Aktif</span>
                            <div>
                                <span class="pr-kpi-val green">{{ $stats['aktif'] }}</span>
                                <span class="pr-kpi-unit">item</span>
                            </div>
                            <div class="pr-kpi-foot">Produk aktif dijual</div>
                        </div>
                        <div class="pr-kpi-ico green">✅</div>
                    </div>
                </div>
                <div class="pr-kpi red">
                    <div class="pr-kpi-top">
                        <div class="pr-kpi-left">
                            <span class="pr-kpi-lbl">Stok Rendah</span>
                            <div>
                                <span class="pr-kpi-val red">{{ $stats['stok_rendah'] }}</span>
                                <span class="pr-kpi-unit">item</span>
                            </div>
                            <div class="pr-kpi-foot">Perlu restok segera</div>
                        </div>
                        <div class="pr-kpi-ico red">⚠️</div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="pr-filter">
                <form method="GET" class="pr-ff">
                    <div class="pr-ff-fld" style="position:relative;">
                        <label class="pr-flbl">Cari Produk</label>
                        <div style="position:relative;">
                            <svg class="pr-finput-ico" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kode, jenis..." class="pr-finput">
                        </div>
                    </div>
                    <div>
                        <label class="pr-flbl">Jenis</label>
                        <select name="jenis" class="pr-fsel">
                            <option value="">Semua Jenis</option>
                            <option value="Galon" {{ request('jenis') == 'Galon' ? 'selected' : '' }}>Galon</option>
                            <option value="Dus" {{ request('jenis') == 'Dus' ? 'selected' : '' }}>Dus</option>
                            <option value="Botol" {{ request('jenis') == 'Botol' ? 'selected' : '' }}>Botol</option>
                            <option value="Gelas" {{ request('jenis') == 'Gelas' ? 'selected' : '' }}>Gelas</option>
                        </select>
                    </div>
                    <div>
                        <label class="pr-flbl">Status</label>
                        <select name="status" class="pr-fsel">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <button type="submit" class="pr-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('mineral.produk.index') }}" class="pr-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- Table --}}
            <div class="pr-tbl">
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:separate; border-spacing:0;">
                        <thead class="pr-tbl-head">
                            <tr>
                                <th style="text-align:left;">Produk</th>
                                <th style="text-align:center;">Jenis</th>
                                <th style="text-align:right;">Harga Jual</th>
                                <th style="text-align:center;">Stok</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="pr-tbl-body">
                            @forelse($produks as $p)
                                @php
                                    $isStokRendah = $p->stok_gudang <= $p->stok_minimum && $p->stok_minimum > 0;
                                    $jenisLower = strtolower($p->jenis ?? '');
                                    $jenisClass = in_array($jenisLower, ['galon','dus','botol','gelas']) ? $jenisLower : 'other';
                                    $jenisEmoji = ['galon'=>'💧','dus'=>'📦','botol'=>'🍶','gelas'=>'🥤','other'=>'📦'][$jenisClass] ?? '📦';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="pr-prod">
                                            <div class="pr-prod-ico">{{ $jenisEmoji }}</div>
                                            <div>
                                                <div class="pr-prod-name">{{ $p->nama }}</div>
                                                <div class="pr-prod-meta">{{ $p->kode_produk }} · {{ $p->satuan }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="pr-type {{ $jenisClass }}">
                                            {{ ucfirst($p->jenis ?? '-') }}
                                        </span>
                                    </td>
                                    <td style="text-align:right;">
                                        <div class="pr-price">Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</div>
                                        @if($p->harga_modal)
                                            <div class="pr-price-hpp">HPP: Rp {{ number_format($p->harga_modal, 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <div class="pr-stock">
                                            <span class="pr-stock-dot {{ $isStokRendah ? 'low' : 'ok' }}"></span>
                                            <span class="pr-stock-val {{ $isStokRendah ? 'low' : 'ok' }}">{{ number_format($p->stok_gudang, 0, ',', '.') }}</span>
                                        </div>
                                        @if($isStokRendah)
                                            <div class="pr-stock-warn">
                                                <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                Stok kritikal
                                            </div>
                                        @else
                                            <div class="pr-stock-sub">Min: {{ number_format($p->stok_minimum, 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="pr-status {{ $p->status }}">
                                            <span class="pr-status-dot {{ $p->status }}"></span>
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td style="text-align:center;">
                                        <div class="pr-acts">
                                            <a href="{{ route('mineral.produk.show', $p) }}" class="pr-act detail">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Detail
                                            </a>
                                            <a href="{{ route('mineral.produk.edit', $p) }}" class="pr-act edit">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('mineral.produk.destroy', $p) }}" style="display:inline;" onsubmit="return confirm('Yakin hapus {{ $p->nama }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="pr-act hapus">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="pr-empty">
                                            <div class="pr-empty-ico">
                                                <svg width="32" height="32" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            </div>
                                            <div class="pr-empty-title">Belum Ada Data Produk</div>
                                            <div class="pr-empty-sub">Tambahkan produk air mineral untuk mulai berjualan</div>
                                            <a href="{{ route('mineral.produk.create') }}" class="pr-empty-cta">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Tambah Produk Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($produks->hasPages())
                    <div style="padding:0.875rem 1.25rem; border-top:1px solid #f1f5f9;">
                        {{ $produks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
