<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .ms-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .ms-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .ms-hdr-l { display:flex; align-items:center; gap:1rem; }
        .ms-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#f97316,#ea580c);
            box-shadow:0 8px 24px rgba(234,88,12,0.3);
        }
        .ms-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .ms-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .ms-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s cubic-bezier(0.4,0,0.2,1); border:none; cursor:pointer;
            background:linear-gradient(135deg,#f97316,#ea580c); color:#fff;
            box-shadow:0 4px 14px rgba(234,88,12,0.35);
        }
        .ms-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(234,88,12,0.45); }

        /* KPI Row */
        .ms-kpis { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
        .ms-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .ms-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .ms-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .ms-kpi.orange::before { background:linear-gradient(180deg,#f97316,#ea580c); }
        .ms-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .ms-kpi.gray::before   { background:linear-gradient(180deg,#94a3b8,#64748b); }
        .ms-kpi.amber::before  { background:linear-gradient(180deg,#f59e0b,#d97706); }
        .ms-kpi-top { display:flex; align-items:center; justify-content:space-between; }
        .ms-kpi-left { display:flex; flex-direction:column; gap:0.25rem; }
        .ms-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .ms-kpi-val { font-size:2.5rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
        .ms-kpi-val.orange { color:#ea580c; }
        .ms-kpi-val.green  { color:#059669; }
        .ms-kpi-val.gray   { color:#64748b; }
        .ms-kpi-val.amber  { color:#d97706; }
        .ms-kpi-foot { font-size:0.72rem; color:#94a3b8; margin-top:0.375rem; }
        .ms-kpi-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
        .ms-kpi-ico.orange { background:linear-gradient(135deg,#fff7ed,#ffedd5); }
        .ms-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .ms-kpi-ico.gray   { background:linear-gradient(135deg,#f8fafc,#f1f5f9); }
        .ms-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }

        /* Filter */
        .ms-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .ms-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .ms-ff-fld { min-width:200px; flex:1; }
        .ms-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .ms-finput {
            width:100%; padding:0.625rem 0.875rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .ms-finput:focus { border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,0.12); }
        .ms-finput-ico { position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }
        .ms-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .ms-fsel:focus { border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,0.12); }
        .ms-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; box-shadow:0 4px 12px rgba(234,88,12,0.25);
        }
        .ms-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(234,88,12,0.35); }
        .ms-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .ms-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        /* Regional Group */
        .rg-group { margin-bottom:1.25rem; }
        .rg-hdr {
            display:flex; align-items:center; justify-content:space-between; gap:0.75rem;
            padding:0.875rem 1.25rem; background:linear-gradient(135deg,#fff7ed,#ffedd5);
            border:1px solid #fed7aa; border-radius:14px 14px 0 0; cursor:pointer;
            transition:all 0.2s; user-select:none;
        }
        .rg-hdr:hover { background:linear-gradient(135deg,#ffedd5,#fed7aa); }
        .rg-hdr-l { display:flex; align-items:center; gap:0.75rem; }
        .rg-hdr-ico {
            width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center;
            background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; font-size:0.875rem; flex-shrink:0;
        }
        .rg-hdr-name { font-size:0.9375rem; font-weight:700; color:#9a3412; }
        .rg-hdr-meta { display:flex; align-items:center; gap:1rem; }
        .rg-hdr-badge {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.2rem 0.625rem; border-radius:20px; font-size:0.6875rem; font-weight:700;
            background:#fff; color:#ea580c; border:1px solid #fed7aa;
        }
        .rg-hdr-chevron { transition:transform 0.2s; color:#64748b; }
        .rg-group.collapsed .rg-hdr { border-radius:14px; }
        .rg-group.collapsed .rg-body { display:none; }
        .rg-group.collapsed .rg-hdr-chevron { transform:rotate(-90deg); }
        .rg-body {
            border:1px solid #e2e8f0; border-top:none; border-radius:0 0 14px 14px;
            overflow:hidden; background:#fff;
        }

        /* Table */
        .ms-tbl-head { background:linear-gradient(180deg,#fff7ed,#ffedd5); border-bottom:2px solid #fdba74; }
        .ms-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#9a3412; white-space:nowrap;
        }
        .ms-tbl-body td { padding:0.9375rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .ms-tbl-body tr { transition:background 0.15s; }
        .ms-tbl-body tr:last-child td { border-bottom:none; }
        .ms-tbl-body tr:hover td { background:linear-gradient(90deg,#fffbf5,#fff7ed); }

        /* Code cell */
        .ms-code {
            display:inline-flex; align-items:center; gap:0.375rem;
            font-family:'JetBrains Mono',monospace; font-size:0.75rem; font-weight:600; color:#475569;
            background:#f8fafc; padding:0.375rem 0.625rem; border-radius:8px; border:1px solid #e2e8f0;
        }
        .ms-code-lbl {
            width:22px; height:22px; border-radius:6px; display:flex; align-items:center; justify-content:center;
            font-size:0.625rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#f97316,#ea580c); color:#fff;
        }

        /* Sales cell */
        .ms-sales { display:flex; align-items:center; gap:0.75rem; }
        .ms-sales-av {
            width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center;
            font-size:1rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#f97316,#ea580c); color:#fff;
            box-shadow:0 4px 12px rgba(234,88,12,0.2);
        }
        .ms-sales-info { display:flex; flex-direction:column; gap:0.125rem; }
        .ms-sales-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .ms-sales-email { font-size:0.6875rem; color:#94a3b8; }

        /* Contact cell */
        .ms-contact { font-size:0.8125rem; font-weight:500; color:#1e293b; }

        /* Vehicle cell */
        .ms-vehicle-plate {
            display:inline-flex; padding:0.25rem 0.5rem; border-radius:6px; font-size:0.75rem; font-weight:700;
            background:#fffbeb; color:#92400e; border:1px solid #fde68a; letter-spacing:0.03em; font-family:'JetBrains Mono',monospace;
        }
        .ms-vehicle-cap { font-size:0.6875rem; color:#94a3b8; margin-top:3px; }

        /* Status badge */
        .ms-status {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.75rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .ms-status.aktif { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .ms-status.nonaktif { background:#f8fafc; color:#64748b; border-color:#e2e8f0; }
        .ms-status.cuti { background:#fffbeb; color:#d97706; border-color:#fde68a; }
        .ms-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .ms-status-dot.aktif { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.2); }
        .ms-status-dot.nonaktif { background:#94a3b8; }
        .ms-status-dot.cuti { background:#f59e0b; box-shadow:0 0 0 2px rgba(245,158,11,0.2); animation:ms-pulse 1.5s infinite; }
        @keyframes ms-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Actions */
        .ms-act-grp { display:flex; gap:0.375rem; align-items:center; justify-content:center; }
        .ms-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.375rem 0.625rem; border-radius:8px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
        }
        .ms-act.detail { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .ms-act.detail:hover { background:#dbeafe; border-color:#93c5fd; }
        .ms-act.edit { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .ms-act.edit:hover { background:#d1fae5; }
        .ms-act.del { background:#fff1f2; color:#be123c; border-color:#fecdd3; }
        .ms-act.del:hover { background:#ffe4e6; border-color:#fda4af; }

        /* Empty */
        .ms-empty { text-align:center; padding:3.5rem 1.5rem; }
        .ms-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#fff7ed,#ffedd5); display:flex; align-items:center; justify-content:center;
        }
        .ms-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .ms-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .ms-empty-cta {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; text-decoration:none;
            box-shadow:0 4px 14px rgba(234,88,12,0.25); transition:all 0.2s;
        }
        .ms-empty-cta:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(234,88,12,0.35); }

        @media(max-width:1024px) { .ms-kpis { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:768px)  { .ms-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px)  { .ms-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="ms-page">

            {{-- Header --}}
            <div class="ms-hdr">
                <div class="ms-hdr-l">
                    <div class="ms-hdr-ico">👤</div>
                    <div>
                        <div class="ms-hdr-title">Data Sales</div>
                        <div class="ms-hdr-sub">Kelola data sales dan kendaraan distribusi minyak</div>
                    </div>
                </div>
                <a href="{{ route('minyak.sales.create') }}" class="ms-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Sales
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="ms-kpis">
                <div class="ms-kpi orange">
                    <div class="ms-kpi-top">
                        <div class="ms-kpi-left">
                            <span class="ms-kpi-lbl">Total Sales</span>
                            <div>
                                <span class="ms-kpi-val orange">{{ $stats['total'] }}</span>
                            </div>
                            <div class="ms-kpi-foot">Semua data sales terdaftar</div>
                        </div>
                        <div class="ms-kpi-ico orange">👥</div>
                    </div>
                </div>
                <div class="ms-kpi green">
                    <div class="ms-kpi-top">
                        <div class="ms-kpi-left">
                            <span class="ms-kpi-lbl">Aktif</span>
                            <div>
                                <span class="ms-kpi-val green">{{ $stats['aktif'] }}</span>
                            </div>
                            <div class="ms-kpi-foot">Sales siap beroperasi</div>
                        </div>
                        <div class="ms-kpi-ico green">✅</div>
                    </div>
                </div>
                <div class="ms-kpi gray">
                    <div class="ms-kpi-top">
                        <div class="ms-kpi-left">
                            <span class="ms-kpi-lbl">Nonaktif</span>
                            <div>
                                <span class="ms-kpi-val gray">{{ $stats['nonaktif'] }}</span>
                            </div>
                            <div class="ms-kpi-foot">Sales tidak aktif</div>
                        </div>
                        <div class="ms-kpi-ico gray">⛔</div>
                    </div>
                </div>
                <div class="ms-kpi amber">
                    <div class="ms-kpi-top">
                        <div class="ms-kpi-left">
                            <span class="ms-kpi-lbl">Cuti</span>
                            <div>
                                <span class="ms-kpi-val amber">{{ $stats['cuti'] }}</span>
                            </div>
                            <div class="ms-kpi-foot">Sedang cuti / libur</div>
                        </div>
                        <div class="ms-kpi-ico amber">🏖️</div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="ms-filter">
                <form method="GET" class="ms-ff">
                    <div class="ms-ff-fld" style="position:relative;">
                        <label class="ms-flbl">Pencarian</label>
                        <div style="position:relative;">
                            <svg class="ms-finput-ico" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kode, no HP..." class="ms-finput">
                        </div>
                    </div>
                    <div>
                        <label class="ms-flbl">Regional</label>
                        <select name="regional_id" class="ms-fsel">
                            <option value="">Semua Regional</option>
                            @foreach($regionals as $r)
                                <option value="{{ $r->id }}" {{ request('regional_id') == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="ms-flbl">Status</label>
                        <select name="status" class="ms-fsel">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                        </select>
                    </div>
                    <button type="submit" class="ms-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('minyak.sales.index') }}" class="ms-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- Regional Summary Cards --}}
            @if($regionalStats->count() > 0 && !request('regional_id'))
            <div style="display:grid; grid-template-columns:repeat({{ min($regionalStats->count(), 4) }},1fr); gap:0.75rem; margin-bottom:1.5rem;">
                @foreach($regionalStats as $rs)
                <a href="{{ route('minyak.sales.index', ['regional_id' => $rs->id]) }}" style="text-decoration:none;">
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1rem 1.25rem; transition:all 0.2s; cursor:pointer;"
                         onmouseover="this.style.borderColor='#fdba74'; this.style.boxShadow='0 4px 16px rgba(234,88,12,0.1)'"
                         onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                            <span style="font-size:0.75rem; font-weight:700; color:#9a3412;">{{ $rs->nama }}</span>
                            <span style="font-size:0.625rem; color:#64748b;">{{ $rs->kode_regional }}</span>
                        </div>
                        <div style="display:flex; align-items:baseline; gap:0.5rem;">
                            <span style="font-size:1.25rem; font-weight:800; color:#ea580c;">{{ $rs->sales_count }}</span>
                            <span style="font-size:0.6875rem; color:#94a3b8;">sales aktif</span>
                        </div>
                        <div style="font-size:0.6875rem; color:#64748b; margin-top:0.25rem;">
                            Total: {{ $rs->sales_total }} sales
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            {{-- Grouped Tables --}}
            @if($grouped->isEmpty())
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;">
                    <div class="ms-empty">
                        <div class="ms-empty-ico">
                            <svg width="32" height="32" fill="none" stroke="#ea580c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div class="ms-empty-title">Belum Ada Data Sales</div>
                        <div class="ms-empty-sub">Coba ubah filter atau tambah data sales baru</div>
                        <a href="{{ route('minyak.sales.create') }}" class="ms-empty-cta">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Sales Pertama
                        </a>
                    </div>
                </div>
            @else
                @foreach($grouped as $regionalName => $salesList)
                <div class="rg-group">
                    <div class="rg-hdr" onclick="this.parentElement.classList.toggle('collapsed')">
                        <div class="rg-hdr-l">
                            <div class="rg-hdr-ico">📍</div>
                            <div class="rg-hdr-name">{{ $regionalName }}</div>
                        </div>
                        <div class="rg-hdr-meta">
                            <span class="rg-hdr-badge">{{ $salesList->count() }} sales</span>
                            <svg class="rg-hdr-chevron" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <div class="rg-body">
                        <div style="overflow-x:auto;">
                            <table style="width:100%; border-collapse:separate; border-spacing:0;">
                                <thead class="ms-tbl-head">
                                    <tr>
                                        <th style="text-align:left;">Kode Sales</th>
                                        <th style="text-align:left;">Nama Lengkap</th>
                                        <th style="text-align:left;">Kontak</th>
                                        <th style="text-align:left;">Kendaraan</th>
                                        <th style="text-align:center;">Status</th>
                                        <th style="text-align:center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="ms-tbl-body">
                                    @foreach($salesList as $s)
                                    <tr>
                                        <td>
                                            <div class="ms-code">
                                                <span class="ms-code-lbl">S</span>
                                                {{ $s->kode_sales }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="ms-sales">
                                                <div class="ms-sales-av">{{ substr($s->nama, 0, 1) }}</div>
                                                <div class="ms-sales-info">
                                                    <span class="ms-sales-name">{{ $s->nama }}</span>
                                                    <span class="ms-sales-email">{{ $s->email ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="ms-contact">{{ $s->no_hp ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @if($s->plat_nomor)
                                                <div>
                                                    <span class="ms-vehicle-plate">{{ $s->plat_nomor }}</span>
                                                    @if($s->kapasitas_tangki)
                                                        <div class="ms-vehicle-cap">{{ number_format($s->kapasitas_tangki) }} L</div>
                                                    @endif
                                                </div>
                                            @else
                                                <span style="color:#94a3b8; font-size:0.8125rem;">-</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="ms-status {{ $s->status }}">
                                                <span class="ms-status-dot {{ $s->status }}"></span>
                                                {{ ucfirst($s->status) }}
                                            </span>
                                        </td>
                                        <td style="text-align:center;">
                                            <div class="ms-act-grp">
                                                <a href="{{ route('minyak.sales.show', $s->id) }}" class="ms-act detail">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    Detail
                                                </a>
                                                <a href="{{ route('minyak.sales.edit', $s->id) }}" class="ms-act edit">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('minyak.sales.destroy', $s->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data sales ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ms-act del">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

        </div>
    </div>
</x-app-layout>
