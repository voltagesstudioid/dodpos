<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .sl-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .sl-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .sl-hdr-l { display:flex; align-items:center; gap:1rem; }
        .sl-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            box-shadow:0 8px 24px rgba(37,99,235,0.3);
        }
        .sl-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .sl-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .sl-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s; border:none; cursor:pointer; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 6px 20px rgba(37,99,235,0.35);
        }
        .sl-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(37,99,235,0.45); }

        /* KPI Row */
        .sl-kpis { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
        .sl-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .sl-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .sl-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .sl-kpi.blue::before   { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .sl-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .sl-kpi.gray::before   { background:linear-gradient(180deg,#94a3b8,#64748b); }
        .sl-kpi.amber::before  { background:linear-gradient(180deg,#f59e0b,#d97706); }
        .sl-kpi-top { display:flex; align-items:center; justify-content:space-between; }
        .sl-kpi-left { display:flex; flex-direction:column; gap:0.25rem; }
        .sl-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .sl-kpi-val { font-size:2.5rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
        .sl-kpi-val.blue   { color:#2563eb; }
        .sl-kpi-val.green  { color:#059669; }
        .sl-kpi-val.gray   { color:#64748b; }
        .sl-kpi-val.amber  { color:#d97706; }
        .sl-kpi-unit { font-size:1rem; font-weight:600; color:#94a3b8; margin-left:0.25rem; }
        .sl-kpi-foot { font-size:0.72rem; color:#94a3b8; margin-top:0.375rem; }
        .sl-kpi-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
        .sl-kpi-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .sl-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .sl-kpi-ico.gray   { background:linear-gradient(135deg,#f8fafc,#f1f5f9); }
        .sl-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }

        /* Filter */
        .sl-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .sl-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .sl-ff-fld { min-width:200px; flex:1; }
        .sl-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .sl-finput {
            width:100%; padding:0.625rem 0.875rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .sl-finput:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .sl-finput-ico { position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }
        .sl-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .sl-fsel:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .sl-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 4px 12px rgba(37,99,235,0.25);
        }
        .sl-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(37,99,235,0.35); }
        .sl-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .sl-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        /* Regional Group */
        .rg-group { margin-bottom:1.25rem; }
        .rg-hdr {
            display:flex; align-items:center; justify-content:space-between; gap:0.75rem;
            padding:0.875rem 1.25rem; background:linear-gradient(135deg,#eff6ff,#dbeafe);
            border:1px solid #bfdbfe; border-radius:14px 14px 0 0; cursor:pointer;
            transition:all 0.2s; user-select:none;
        }
        .rg-hdr:hover { background:linear-gradient(135deg,#dbeafe,#bfdbfe); }
        .rg-hdr-l { display:flex; align-items:center; gap:0.75rem; }
        .rg-hdr-ico {
            width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; font-size:0.875rem; flex-shrink:0;
        }
        .rg-hdr-name { font-size:0.9375rem; font-weight:700; color:#1e40af; }
        .rg-hdr-meta { display:flex; align-items:center; gap:1rem; }
        .rg-hdr-badge {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.2rem 0.625rem; border-radius:20px; font-size:0.6875rem; font-weight:700;
            background:#fff; color:#2563eb; border:1px solid #bfdbfe;
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
        .sl-tbl { background:#fff; overflow:hidden; }
        .sl-tbl-head { background:linear-gradient(180deg,#eff6ff,#f0f7ff); border-bottom:2px solid #bfdbfe; }
        .sl-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#1e40af; white-space:nowrap;
        }
        .sl-tbl-body td { padding:0.9375rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .sl-tbl-body tr { transition:background 0.15s; }
        .sl-tbl-body tr:last-child td { border-bottom:none; }
        .sl-tbl-body tr:hover td { background:linear-gradient(90deg,#f8faff,#eff6ff); }

        /* Sales cell */
        .sl-sales { display:flex; align-items:center; gap:0.75rem; }
        .sl-sales-av {
            width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center;
            font-size:1rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#2563eb; border:1.5px solid #bfdbfe;
        }
        .sl-sales-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .sl-sales-email { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        /* Code cell */
        .sl-code { display:inline-flex; align-items:center; gap:0.375rem; }
        .sl-code-badge {
            width:22px; height:22px; border-radius:6px; display:flex; align-items:center; justify-content:center;
            font-size:0.5625rem; font-weight:800; background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#2563eb;
            flex-shrink:0;
        }
        .sl-code-val { font-size:0.8125rem; font-weight:600; color:#1e293b; font-family:'JetBrains Mono',monospace; letter-spacing:-0.01em; }

        /* Contact cell */
        .sl-contact { font-size:0.8125rem; font-weight:500; color:#1e293b; }

        /* Vehicle cell */
        .sl-vehicle-plate { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .sl-vehicle-type { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        /* Status Badge */
        .sl-status {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .sl-status.aktif    { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .sl-status.nonaktif { background:#f8fafc; color:#64748b; border-color:#e2e8f0; }
        .sl-status.cuti     { background:#fffbeb; color:#d97706; border-color:#fde68a; }
        .sl-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .sl-status-dot.aktif    { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.2); }
        .sl-status-dot.nonaktif { background:#94a3b8; }
        .sl-status-dot.cuti     { background:#f59e0b; box-shadow:0 0 0 2px rgba(245,158,11,0.2); animation:sl-pulse 1.5s infinite; }
        @keyframes sl-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Actions */
        .sl-acts { display:flex; align-items:center; gap:0.375rem; justify-content:center; }
        .sl-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.3125rem 0.625rem; border-radius:7px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
        }
        .sl-act.detail { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .sl-act.detail:hover { background:#dbeafe; border-color:#93c5fd; }
        .sl-act.edit { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .sl-act.edit:hover { background:#d1fae5; border-color:#6ee7b7; }
        .sl-act.hapus { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
        .sl-act.hapus:hover { background:#fee2e2; border-color:#fca5a5; }

        /* Empty */
        .sl-empty { text-align:center; padding:3.5rem 1.5rem; }
        .sl-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#eff6ff,#dbeafe); display:flex; align-items:center; justify-content:center;
        }
        .sl-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .sl-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .sl-empty-cta {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; text-decoration:none;
            box-shadow:0 6px 20px rgba(37,99,235,0.25); transition:all 0.2s; font-family:inherit;
        }
        .sl-empty-cta:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(37,99,235,0.4); }

        @media(max-width:1024px) { .sl-kpis { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:768px) { .sl-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px) { .sl-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="sl-page">

            {{-- Header --}}
            <div class="sl-hdr">
                <div class="sl-hdr-l">
                    <div class="sl-hdr-ico">👥</div>
                    <div>
                        <div class="sl-hdr-title">Data Sales</div>
                        <div class="sl-hdr-sub">Kelola data sales dan kendaraan distribusi mineral</div>
                    </div>
                </div>
                <a href="{{ route('mineral.sales.create') }}" class="sl-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Sales
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="sl-kpis">
                <div class="sl-kpi blue">
                    <div class="sl-kpi-top">
                        <div class="sl-kpi-left">
                            <span class="sl-kpi-lbl">Total Sales</span>
                            <div>
                                <span class="sl-kpi-val blue">{{ $stats['total'] }}</span>
                                <span class="sl-kpi-unit">orang</span>
                            </div>
                            <div class="sl-kpi-foot">Seluruh data sales terdaftar</div>
                        </div>
                        <div class="sl-kpi-ico blue">👥</div>
                    </div>
                </div>
                <div class="sl-kpi green">
                    <div class="sl-kpi-top">
                        <div class="sl-kpi-left">
                            <span class="sl-kpi-lbl">Aktif</span>
                            <div>
                                <span class="sl-kpi-val green">{{ $stats['aktif'] }}</span>
                                <span class="sl-kpi-unit">sales</span>
                            </div>
                            <div class="sl-kpi-foot">Sedang aktif bekerja</div>
                        </div>
                        <div class="sl-kpi-ico green">✅</div>
                    </div>
                </div>
                <div class="sl-kpi gray">
                    <div class="sl-kpi-top">
                        <div class="sl-kpi-left">
                            <span class="sl-kpi-lbl">Nonaktif</span>
                            <div>
                                <span class="sl-kpi-val gray">{{ $stats['nonaktif'] }}</span>
                                <span class="sl-kpi-unit">sales</span>
                            </div>
                            <div class="sl-kpi-foot">Status nonaktif</div>
                        </div>
                        <div class="sl-kpi-ico gray">⏸️</div>
                    </div>
                </div>
                <div class="sl-kpi amber">
                    <div class="sl-kpi-top">
                        <div class="sl-kpi-left">
                            <span class="sl-kpi-lbl">Cuti</span>
                            <div>
                                <span class="sl-kpi-val amber">{{ $stats['cuti'] }}</span>
                                <span class="sl-kpi-unit">sales</span>
                            </div>
                            <div class="sl-kpi-foot">Sedang cuti</div>
                        </div>
                        <div class="sl-kpi-ico amber">🏖️</div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="sl-filter">
                <form method="GET" class="sl-ff">
                    <div class="sl-ff-fld" style="position:relative;">
                        <label class="sl-flbl">Cari Sales</label>
                        <div style="position:relative;">
                            <svg class="sl-finput-ico" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kode, no HP..." class="sl-finput">
                        </div>
                    </div>
                    <div>
                        <label class="sl-flbl">Regional</label>
                        <select name="regional_id" class="sl-fsel">
                            <option value="">Semua Regional</option>
                            @foreach($regionals as $r)
                                <option value="{{ $r->id }}" {{ request('regional_id') == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="sl-flbl">Status</label>
                        <select name="status" class="sl-fsel">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                        </select>
                    </div>
                    <button type="submit" class="sl-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('mineral.sales.index') }}" class="sl-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- Regional Summary Cards --}}
            @if($regionalStats->count() > 0 && !request('regional_id'))
            <div style="display:grid; grid-template-columns:repeat({{ min($regionalStats->count(), 4) }},1fr); gap:0.75rem; margin-bottom:1.5rem;">
                @foreach($regionalStats as $rs)
                <a href="{{ route('mineral.sales.index', ['regional_id' => $rs->id]) }}" style="text-decoration:none;">
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1rem 1.25rem; transition:all 0.2s; cursor:pointer;"
                         onmouseover="this.style.borderColor='#93c5fd'; this.style.boxShadow='0 4px 16px rgba(37,99,235,0.1)'"
                         onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                            <span style="font-size:0.75rem; font-weight:700; color:#1e40af;">{{ $rs->nama }}</span>
                            <span style="font-size:0.625rem; color:#64748b;">{{ $rs->kode_regional }}</span>
                        </div>
                        <div style="display:flex; align-items:baseline; gap:0.5rem;">
                            <span style="font-size:1.25rem; font-weight:800; color:#2563eb;">{{ $rs->sales_count }}</span>
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
                <div class="sl-tbl" style="border:1px solid #e2e8f0; border-radius:16px;">
                    <div class="sl-empty">
                        <div class="sl-empty-ico">
                            <svg width="32" height="32" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div class="sl-empty-title">Belum Ada Data Sales</div>
                        <div class="sl-empty-sub">Tambahkan data sales untuk mulai mengelola tim distribusi</div>
                        <a href="{{ route('mineral.sales.create') }}" class="sl-empty-cta">
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
                                <thead class="sl-tbl-head">
                                    <tr>
                                        <th style="text-align:left;">Kode Sales</th>
                                        <th style="text-align:left;">Nama Lengkap</th>
                                        <th style="text-align:left;">Kontak</th>
                                        <th style="text-align:left;">Kendaraan</th>
                                        <th style="text-align:center;">Status</th>
                                        <th style="text-align:center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="sl-tbl-body">
                                    @foreach($salesList as $s)
                                    <tr>
                                        <td>
                                            <div class="sl-code">
                                                <div class="sl-code-badge">S</div>
                                                <span class="sl-code-val">{{ $s->kode_sales }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="sl-sales">
                                                <div class="sl-sales-av">{{ substr($s->nama, 0, 1) }}</div>
                                                <div>
                                                    <div class="sl-sales-name">{{ $s->nama }}</div>
                                                    <div class="sl-sales-email">{{ $s->email ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="sl-contact">{{ $s->no_hp ?? '-' }}</div>
                                        </td>
                                        <td>
                                            @php $v = $s->currentAssignment?->vehicle; @endphp
                                            @if($v)
                                                <div class="sl-vehicle-plate">{{ strtoupper($v->license_plate) }}</div>
                                                @if($v->type)
                                                    <div class="sl-vehicle-type">{{ $v->type }}</div>
                                                @endif
                                            @else
                                                <span style="color:#cbd5e1;font-style:italic;font-size:0.8125rem;">—</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="sl-status {{ $s->status }}">
                                                <span class="sl-status-dot {{ $s->status }}"></span>
                                                {{ ucfirst($s->status) }}
                                            </span>
                                        </td>
                                        <td style="text-align:center;">
                                            <div class="sl-acts">
                                                <a href="{{ route('mineral.sales.show', $s) }}" class="sl-act detail">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    Detail
                                                </a>
                                                <a href="{{ route('mineral.sales.edit', $s) }}" class="sl-act edit">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('mineral.sales.destroy', $s) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data sales ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="sl-act hapus">
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
