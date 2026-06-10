<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&display=swap');
        .gs-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .gs-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.75rem; padding-bottom:1.25rem; border-bottom:2px solid #fef3c7; }
        .gs-hdr-left { display:flex; align-items:center; gap:1rem; }
        .gs-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#f59e0b,#d97706);
            box-shadow:0 8px 24px rgba(245,158,11,0.3);
        }
        .gs-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .gs-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .gs-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; border-radius:12px;
            font-size:0.8125rem; font-weight:700; text-decoration:none; transition:all 0.25s;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
            box-shadow:0 4px 14px rgba(245,158,11,0.35); border:none; cursor:pointer;
        }
        .gs-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(245,158,11,0.45); }

        /* KPI Cards */
        .gs-kpis { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
        .gs-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.375rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .gs-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .gs-kpi:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,0.08); border-color:transparent; }
        .gs-kpi.blue::before   { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .gs-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .gs-kpi.gray::before   { background:linear-gradient(180deg,#94a3b8,#64748b); }
        .gs-kpi.amber::before  { background:linear-gradient(180deg,#f59e0b,#d97706); }
        .gs-kpi-top { display:flex; align-items:flex-start; justify-content:space-between; }
        .gs-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
        .gs-kpi-val { font-size:2rem; font-weight:800; letter-spacing:-0.03em; line-height:1.1; margin-top:0.5rem; }
        .gs-kpi-val.blue   { color:#2563eb; }
        .gs-kpi-val.green  { color:#059669; }
        .gs-kpi-val.gray   { color:#475569; }
        .gs-kpi-val.amber  { color:#b45309; }
        .gs-kpi-ico {
            width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            font-size:1.25rem; flex-shrink:0;
        }
        .gs-kpi-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .gs-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .gs-kpi-ico.gray   { background:linear-gradient(135deg,#f8fafc,#f1f5f9); }
        .gs-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }

        /* Filter */
        .gs-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.5rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .gs-filter-form { display:flex; flex-wrap:wrap; align-items:flex-end; gap:1rem; }
        .gs-filter-group { flex:1; min-width:200px; }
        .gs-filter-label { display:block; font-size:0.75rem; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem; }
        .gs-filter-input {
            width:100%; border:1px solid #e2e8f0; border-radius:10px; padding:0.625rem 0.875rem 0.625rem 2.5rem;
            font-size:0.875rem; font-family:inherit; color:#1e293b; background:#f8fafc; transition:all 0.2s;
        }
        .gs-filter-input:focus { outline:none; border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
        .gs-filter-search-wrap { position:relative; }
        .gs-filter-search-icon { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; }
        .gs-filter-select {
            border:1px solid #e2e8f0; border-radius:10px; padding:0.625rem 0.875rem;
            font-size:0.875rem; font-family:inherit; color:#1e293b; background:#f8fafc; transition:all 0.2s;
        }
        .gs-filter-select:focus { outline:none; border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
        .gs-filter-btn {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:700;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
        }
        .gs-filter-btn.primary { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 4px 12px rgba(245,158,11,0.25); }
        .gs-filter-btn.primary:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(245,158,11,0.35); }
        .gs-filter-btn.ghost { background:#f8fafc; color:#64748b; border:1px solid #e2e8f0; }
        .gs-filter-btn.ghost:hover { background:#f1f5f9; }

        /* Table */
        .gs-table-wrap { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
        .gs-table-scroll { overflow-x:auto; }
        .gs-table { width:100%; border-collapse:collapse; }
        .gs-table thead th {
            background:linear-gradient(180deg,#fffbeb,#fef9ee); border-bottom:2px solid #fde68a;
            padding:0.875rem 1.25rem; font-size:0.6875rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.06em; color:#92400e;
        }
        .gs-table thead th.left { text-align:left; }
        .gs-table thead th.center { text-align:center; }
        .gs-table tbody td { padding:1rem 1.25rem; border-bottom:1px solid #f8fafc; vertical-align:middle; }
        .gs-table tbody tr { transition:background 0.15s; }
        .gs-table tbody tr:hover { background:#fffdf7; }
        .gs-table tbody tr:last-child td { border-bottom:none; }

        /* Table content */
        .gs-code { font-family:'JetBrains Mono',monospace; font-size:0.8125rem; font-weight:700; color:#b45309; }
        .gs-sales-cell { display:flex; align-items:center; gap:0.75rem; }
        .gs-sales-av {
            width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            font-size:0.9375rem; font-weight:800; flex-shrink:0;
            background:linear-gradient(135deg,#fffbeb,#fef3c7); color:#b45309; border:1.5px solid #fde68a;
        }
        .gs-sales-name { font-size:0.875rem; font-weight:600; color:#1e293b; }
        .gs-sales-email { font-size:0.75rem; color:#94a3b8; margin-top:1px; }
        .gs-contact { font-size:0.8125rem; color:#1e293b; }
        .gs-vehicle-plate {
            font-family:'JetBrains Mono',monospace; font-size:0.8125rem; font-weight:700; color:#1e293b;
            background:#f8fafc; padding:0.25rem 0.5rem; border-radius:6px; border:1px solid #e2e8f0;
            display:inline-block;
        }
        .gs-vehicle-type { font-size:0.6875rem; color:#94a3b8; margin-top:2px; }

        /* Status badges */
        .gs-status {
            display:inline-flex; align-items:center; gap:0.375rem;
            padding:0.375rem 0.75rem; border-radius:99px; font-size:0.75rem; font-weight:700;
        }
        .gs-status-dot { width:8px; height:8px; border-radius:50%; }
        .gs-status.aktif { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .gs-status.aktif .gs-status-dot { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.2); animation:gs-pulse 1.5s infinite; }
        .gs-status.nonaktif { background:#f8fafc; color:#64748b; border:1px solid #e2e8f0; }
        .gs-status.nonaktif .gs-status-dot { background:#94a3b8; }
        .gs-status.cuti { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
        .gs-status.cuti .gs-status-dot { background:#f59e0b; }
        @keyframes gs-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Action buttons */
        .gs-actions { display:flex; align-items:center; justify-content:center; gap:0.375rem; }
        .gs-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.375rem 0.75rem; border-radius:8px; font-size:0.75rem; font-weight:600;
            text-decoration:none; transition:all 0.2s; border:1px solid transparent;
        }
        .gs-act.detail { background:#eff6ff; color:#2563eb; border-color:#bfdbfe; }
        .gs-act.detail:hover { background:#dbeafe; }
        .gs-act.edit { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .gs-act.edit:hover { background:#d1fae5; }
        .gs-act.delete { background:#fff1f2; color:#e11d48; border-color:#fecdd3; }
        .gs-act.delete:hover { background:#ffe4e6; }

        /* Empty state */
        .gs-empty { text-align:center; padding:3.5rem 1rem; }
        .gs-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#fffbeb,#fef3c7); display:flex; align-items:center; justify-content:center;
        }
        .gs-empty-title { font-size:1rem; font-weight:700; color:#475569; }
        .gs-empty-sub { font-size:0.875rem; color:#94a3b8; margin-top:0.25rem; }
        .gs-empty-btn {
            display:inline-flex; align-items:center; gap:0.5rem; margin-top:1rem;
            padding:0.625rem 1.25rem; border-radius:10px;
            font-size:0.8125rem; font-weight:700; text-decoration:none;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
            box-shadow:0 4px 12px rgba(245,158,11,0.25); transition:all 0.25s;
        }
        .gs-empty-btn:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(245,158,11,0.35); }

        /* Pagination */
        .gs-pagination { padding:1rem 1.25rem; border-top:1px solid #f1f5f9; }

        @media(max-width:1024px) { .gs-kpis { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:640px) { .gs-kpis { grid-template-columns:1fr; } .gs-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="gs-page">

            {{-- Header --}}
            <div class="gs-hdr">
                <div class="gs-hdr-left">
                    <div class="gs-hdr-ico">👥</div>
                    <div>
                        <div class="gs-hdr-title">Data Sales Gula</div>
                        <div class="gs-hdr-sub">Kelola data sales dan kendaraan distribusi</div>
                    </div>
                </div>
                <a href="{{ route('gula.sales.create') }}" class="gs-hdr-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Sales
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="gs-kpis">
                <div class="gs-kpi blue">
                    <div class="gs-kpi-top">
                        <div>
                            <div class="gs-kpi-lbl">Total Sales</div>
                            <div class="gs-kpi-val blue">{{ $stats['total'] }}</div>
                        </div>
                        <div class="gs-kpi-ico blue">👥</div>
                    </div>
                </div>
                <div class="gs-kpi green">
                    <div class="gs-kpi-top">
                        <div>
                            <div class="gs-kpi-lbl">Aktif</div>
                            <div class="gs-kpi-val green">{{ $stats['aktif'] }}</div>
                        </div>
                        <div class="gs-kpi-ico green">✅</div>
                    </div>
                </div>
                <div class="gs-kpi gray">
                    <div class="gs-kpi-top">
                        <div>
                            <div class="gs-kpi-lbl">Nonaktif</div>
                            <div class="gs-kpi-val gray">{{ $stats['nonaktif'] }}</div>
                        </div>
                        <div class="gs-kpi-ico gray">⏸️</div>
                    </div>
                </div>
                <div class="gs-kpi amber">
                    <div class="gs-kpi-top">
                        <div>
                            <div class="gs-kpi-lbl">Cuti</div>
                            <div class="gs-kpi-val amber">{{ $stats['cuti'] }}</div>
                        </div>
                        <div class="gs-kpi-ico amber">🏖️</div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="gs-filter">
                <form method="GET" class="gs-filter-form">
                    <div class="gs-filter-group">
                        <label class="gs-filter-label">Cari</label>
                        <div class="gs-filter-search-wrap">
                            <svg class="gs-filter-search-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kode, no HP..." class="gs-filter-input">
                        </div>
                    </div>
                    <div>
                        <label class="gs-filter-label">Status</label>
                        <select name="status" class="gs-filter-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                        </select>
                    </div>
                    <button type="submit" class="gs-filter-btn primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('gula.sales.index') }}" class="gs-filter-btn ghost">Reset</a>
                </form>
            </div>

            {{-- Table --}}
            <div class="gs-table-wrap">
                <div class="gs-table-scroll">
                    <table class="gs-table">
                        <thead>
                            <tr>
                                <th class="left">Kode</th>
                                <th class="left">Sales</th>
                                <th class="left">Kontak</th>
                                <th class="left">Kendaraan</th>
                                <th class="center">Status</th>
                                <th class="center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $s)
                                <tr>
                                    <td>
                                        <span class="gs-code">{{ $s->kode_sales }}</span>
                                    </td>
                                    <td>
                                        <div class="gs-sales-cell">
                                            <div class="gs-sales-av">{{ substr($s->nama, 0, 1) }}</div>
                                            <div>
                                                <div class="gs-sales-name">{{ $s->nama }}</div>
                                                <div class="gs-sales-email">{{ $s->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="gs-contact">{{ $s->no_hp ?? '-' }}</div>
                                    </td>
                                    <td>
                                        @if($s->vehicle)
                                            <span class="gs-vehicle-plate">{{ strtoupper($s->vehicle->license_plate) }}</span>
                                            @if($s->vehicle->type)
                                                <div class="gs-vehicle-type">{{ $s->vehicle->type }}</div>
                                            @endif
                                        @else
                                            <span class="gs-contact" style="color:#94a3b8;">-</span>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="gs-status {{ $s->status }}">
                                            <span class="gs-status-dot"></span>
                                            {{ ucfirst($s->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="gs-actions">
                                            <a href="{{ route('gula.sales.show', $s) }}" class="gs-act detail">
                                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Detail
                                            </a>
                                            <a href="{{ route('gula.sales.edit', $s) }}" class="gs-act edit">
                                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('gula.sales.destroy', $s) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data sales ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="gs-act delete" style="border:1px solid #fecdd3;">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="gs-empty">
                                            <div class="gs-empty-ico">
                                                <svg width="32" height="32" fill="none" stroke="#b45309" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </div>
                                            <div class="gs-empty-title">Tidak ada data sales</div>
                                            <div class="gs-empty-sub">Silakan tambah data sales baru</div>
                                            <a href="{{ route('gula.sales.create') }}" class="gs-empty-btn">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Tambah Sales
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($sales->hasPages())
                    <div class="gs-pagination">
                        {{ $sales->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
