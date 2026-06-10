<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&display=swap');
        .pk-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .pk-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.75rem; padding-bottom:1.25rem; border-bottom:2px solid #fef3c7; }
        .pk-hdr-left { display:flex; align-items:center; gap:1rem; }
        .pk-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#f59e0b,#d97706);
            box-shadow:0 8px 24px rgba(245,158,11,0.3);
        }
        .pk-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .pk-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .pk-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; border-radius:12px;
            font-size:0.8125rem; font-weight:700; text-decoration:none; transition:all 0.25s;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
            box-shadow:0 4px 14px rgba(245,158,11,0.35); border:none; cursor:pointer;
        }
        .pk-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(245,158,11,0.45); }

        /* KPI Cards */
        .pk-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
        .pk-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .pk-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .pk-kpi:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,0.08); border-color:transparent; }
        .pk-kpi.blue::before   { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .pk-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .pk-kpi.red::before    { background:linear-gradient(180deg,#ef4444,#dc2626); }
        .pk-kpi-top { display:flex; align-items:flex-start; justify-content:space-between; }
        .pk-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
        .pk-kpi-val { font-size:2.25rem; font-weight:800; letter-spacing:-0.03em; line-height:1.1; margin-top:0.5rem; }
        .pk-kpi-val.blue   { color:#2563eb; }
        .pk-kpi-val.green  { color:#059669; }
        .pk-kpi-val.red    { color:#dc2626; }
        .pk-kpi-ico {
            width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            font-size:1.375rem; flex-shrink:0;
        }
        .pk-kpi-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .pk-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .pk-kpi-ico.red    { background:linear-gradient(135deg,#fff1f2,#ffe4e6); }

        /* Filter */
        .pk-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.5rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .pk-filter-form { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .pk-filter-group { flex:1; min-width:220px; }
        .pk-filter-label { display:block; font-size:0.75rem; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem; }
        .pk-filter-input {
            width:100%; border:1px solid #e2e8f0; border-radius:10px; padding:0.625rem 0.875rem 0.625rem 2.5rem;
            font-size:0.875rem; font-family:inherit; color:#1e293b; background:#f8fafc; transition:all 0.2s;
        }
        .pk-filter-input:focus { outline:none; border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
        .pk-filter-search-wrap { position:relative; }
        .pk-filter-search-icon { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; }
        .pk-filter-select {
            border:1px solid #e2e8f0; border-radius:10px; padding:0.625rem 0.875rem;
            font-size:0.875rem; font-family:inherit; color:#1e293b; background:#f8fafc; transition:all 0.2s;
        }
        .pk-filter-select:focus { outline:none; border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
        .pk-filter-btn {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:700;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
        }
        .pk-filter-btn.primary { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 4px 12px rgba(245,158,11,0.25); }
        .pk-filter-btn.primary:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(245,158,11,0.35); }
        .pk-filter-btn.ghost { background:#f8fafc; color:#64748b; border:1px solid #e2e8f0; }
        .pk-filter-btn.ghost:hover { background:#f1f5f9; }

        /* Table */
        .pk-table-wrap { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
        .pk-table-scroll { overflow-x:auto; }
        .pk-table { width:100%; border-collapse:collapse; }
        .pk-table thead th {
            background:linear-gradient(180deg,#fffbeb,#fef9ee); border-bottom:2px solid #fde68a;
            padding:0.875rem 1.25rem; font-size:0.6875rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.06em; color:#92400e;
        }
        .pk-table thead th.left { text-align:left; }
        .pk-table thead th.center { text-align:center; }
        .pk-table thead th.right { text-align:right; }
        .pk-table tbody td { padding:1rem 1.25rem; border-bottom:1px solid #f8fafc; vertical-align:middle; }
        .pk-table tbody tr { transition:background 0.15s; }
        .pk-table tbody tr:hover { background:#fffdf7; }
        .pk-table tbody tr:last-child td { border-bottom:none; }

        /* Cell styles */
        .pk-prod-cell { display:flex; align-items:center; gap:0.75rem; }
        .pk-prod-av {
            width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            font-size:1.25rem; flex-shrink:0;
            background:linear-gradient(135deg,#fffbeb,#fef3c7); border:1.5px solid #fde68a;
        }
        .pk-prod-name { font-size:0.875rem; font-weight:600; color:#1e293b; }
        .pk-prod-meta { font-size:0.6875rem; color:#94a3b8; margin-top:1px; font-family:'JetBrains Mono',monospace; }
        .pk-jenis-badge {
            display:inline-flex; align-items:center; gap:0.375rem;
            padding:0.375rem 0.75rem; border-radius:8px; font-size:0.75rem; font-weight:700;
            background:#f8fafc; color:#475569; border:1px solid #e2e8f0;
        }
        .pk-harga { font-family:'JetBrains Mono',monospace; font-size:0.875rem; font-weight:700; color:#1e293b; }
        .pk-harga-sub { font-size:0.6875rem; color:#94a3b8; margin-top:2px; }
        .pk-stok-cell { display:flex; align-items:center; justify-content:center; gap:0.5rem; }
        .pk-stok-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
        .pk-stok-dot.ok { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.2); }
        .pk-stok-dot.low { background:#ef4444; box-shadow:0 0 0 2px rgba(239,68,68,0.2); animation:pk-pulse 1.5s infinite; }
        @keyframes pk-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }
        .pk-stok-val { font-family:'JetBrains Mono',monospace; font-size:0.875rem; font-weight:700; }
        .pk-stok-val.ok { color:#1e293b; }
        .pk-stok-val.low { color:#dc2626; }
        .pk-stok-sub { font-size:0.6875rem; color:#94a3b8; text-align:center; margin-top:2px; }
        .pk-stok-sub.warn { color:#dc2626; font-weight:600; }

        /* Status badges */
        .pk-status {
            display:inline-flex; align-items:center; gap:0.375rem;
            padding:0.375rem 0.75rem; border-radius:99px; font-size:0.75rem; font-weight:700;
        }
        .pk-status-dot { width:8px; height:8px; border-radius:50%; }
        .pk-status.aktif { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .pk-status.aktif .pk-status-dot { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.2); animation:pk-pulse 1.5s infinite; }
        .pk-status.nonaktif { background:#f8fafc; color:#64748b; border:1px solid #e2e8f0; }
        .pk-status.nonaktif .pk-status-dot { background:#94a3b8; }

        /* Action buttons */
        .pk-actions { display:flex; align-items:center; justify-content:center; gap:0.375rem; }
        .pk-act {
            display:inline-flex; align-items:center; justify-content:center;
            width:32px; height:32px; border-radius:8px;
            transition:all 0.2s; border:1px solid transparent;
        }
        .pk-act.detail { background:#eff6ff; color:#2563eb; border-color:#bfdbfe; }
        .pk-act.detail:hover { background:#dbeafe; }
        .pk-act.edit { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .pk-act.edit:hover { background:#d1fae5; }
        .pk-act.delete { background:#fff1f2; color:#e11d48; border-color:#fecdd3; }
        .pk-act.delete:hover { background:#ffe4e6; }

        /* Empty state */
        .pk-empty { text-align:center; padding:3.5rem 1rem; }
        .pk-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#fffbeb,#fef3c7); display:flex; align-items:center; justify-content:center;
        }
        .pk-empty-title { font-size:1rem; font-weight:700; color:#475569; }
        .pk-empty-sub { font-size:0.875rem; color:#94a3b8; margin-top:0.25rem; }
        .pk-empty-btn {
            display:inline-flex; align-items:center; gap:0.5rem; margin-top:1rem;
            padding:0.625rem 1.25rem; border-radius:10px;
            font-size:0.8125rem; font-weight:700; text-decoration:none;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
            box-shadow:0 4px 12px rgba(245,158,11,0.25); transition:all 0.25s;
        }
        .pk-empty-btn:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(245,158,11,0.35); }

        /* Pagination */
        .pk-pagination { padding:1rem 1.25rem; border-top:1px solid #f1f5f9; }

        @media(max-width:1024px) { .pk-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px) { .pk-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="pk-page">

            {{-- Header --}}
            <div class="pk-hdr">
                <div class="pk-hdr-left">
                    <div class="pk-hdr-ico">📦</div>
                    <div>
                        <div class="pk-hdr-title">Data Produk Gula</div>
                        <div class="pk-hdr-sub">Kelola produk gula dan stok gudang</div>
                    </div>
                </div>
                <a href="{{ route('gula.produk.create') }}" class="pk-hdr-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Produk
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="pk-kpis">
                <div class="pk-kpi blue">
                    <div class="pk-kpi-top">
                        <div>
                            <div class="pk-kpi-lbl">Total Produk</div>
                            <div class="pk-kpi-val blue">{{ $stats['total'] }}</div>
                        </div>
                        <div class="pk-kpi-ico blue">📦</div>
                    </div>
                </div>
                <div class="pk-kpi green">
                    <div class="pk-kpi-top">
                        <div>
                            <div class="pk-kpi-lbl">Produk Aktif</div>
                            <div class="pk-kpi-val green">{{ $stats['aktif'] }}</div>
                        </div>
                        <div class="pk-kpi-ico green">✅</div>
                    </div>
                </div>
                <div class="pk-kpi red">
                    <div class="pk-kpi-top">
                        <div>
                            <div class="pk-kpi-lbl">Stok Rendah</div>
                            <div class="pk-kpi-val red">{{ $stats['stok_rendah'] }}</div>
                        </div>
                        <div class="pk-kpi-ico red">⚠️</div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="pk-filter">
                <form method="GET" class="pk-filter-form">
                    <div class="pk-filter-group">
                        <label class="pk-filter-label">Cari</label>
                        <div class="pk-filter-search-wrap">
                            <svg class="pk-filter-search-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kode, jenis..." class="pk-filter-input">
                        </div>
                    </div>
                    <div>
                        <label class="pk-filter-label">Jenis</label>
                        <select name="jenis" class="pk-filter-select">
                            <option value="">Semua Jenis</option>
                            <option value="Galon" {{ request('jenis') == 'Galon' ? 'selected' : '' }}>Galon</option>
                            <option value="Dus" {{ request('jenis') == 'Dus' ? 'selected' : '' }}>Dus</option>
                            <option value="Botol" {{ request('jenis') == 'Botol' ? 'selected' : '' }}>Botol</option>
                            <option value="Gelas" {{ request('jenis') == 'Gelas' ? 'selected' : '' }}>Gelas</option>
                        </select>
                    </div>
                    <div>
                        <label class="pk-filter-label">Status</label>
                        <select name="status" class="pk-filter-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <button type="submit" class="pk-filter-btn primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('gula.produk.index') }}" class="pk-filter-btn ghost">Reset</a>
                </form>
            </div>

            {{-- Table --}}
            <div class="pk-table-wrap">
                <div class="pk-table-scroll">
                    <table class="pk-table">
                        <thead>
                            <tr>
                                <th class="left">Produk</th>
                                <th class="center">Jenis</th>
                                <th class="right">Harga Jual</th>
                                <th class="center">Stok</th>
                                <th class="center">Status</th>
                                <th class="center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produks as $p)
                                @php
                                    $isLowStock = $p->stok_gudang <= $p->stok_minimum && $p->stok_minimum > 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="pk-prod-cell">
                                            <div class="pk-prod-av">📦</div>
                                            <div>
                                                <div class="pk-prod-name">{{ $p->nama }}</div>
                                                <div class="pk-prod-meta">{{ $p->kode_produk }} · {{ $p->satuan }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="pk-jenis-badge">{{ $p->jenis ?? '-' }}</span>
                                    </td>
                                    <td style="text-align:right;">
                                        <div class="pk-harga">Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</div>
                                        @if($p->harga_modal)
                                            <div class="pk-harga-sub">HPP: Rp {{ number_format($p->harga_modal, 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <div class="pk-stok-cell">
                                            <span class="pk-stok-dot {{ $isLowStock ? 'low' : 'ok' }}"></span>
                                            <span class="pk-stok-val {{ $isLowStock ? 'low' : 'ok' }}">{{ number_format($p->stok_gudang) }}</span>
                                        </div>
                                        @if($isLowStock)
                                            <div class="pk-stok-sub warn">Stok kritikal</div>
                                        @else
                                            <div class="pk-stok-sub">Min: {{ number_format($p->stok_minimum) }}</div>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="pk-status {{ $p->status }}">
                                            <span class="pk-status-dot"></span>
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="pk-actions">
                                            <a href="{{ route('gula.produk.show', $p) }}" class="pk-act detail" title="Detail">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="{{ route('gula.produk.edit', $p) }}" class="pk-act edit" title="Edit">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form method="POST" action="{{ route('gula.produk.destroy', $p) }}" style="display:inline;" onsubmit="return confirm('Yakin hapus {{ $p->nama }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="pk-act delete" title="Hapus" style="border:1px solid #fecdd3;">
                                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="pk-empty">
                                            <div class="pk-empty-ico">
                                                <svg width="32" height="32" fill="none" stroke="#b45309" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            </div>
                                            <div class="pk-empty-title">Tidak ada data produk</div>
                                            <div class="pk-empty-sub">Tambah produk pertama untuk memulai</div>
                                            <a href="{{ route('gula.produk.create') }}" class="pk-empty-btn">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Tambah Produk
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($produks->hasPages())
                    <div class="pk-pagination">
                        {{ $produks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
