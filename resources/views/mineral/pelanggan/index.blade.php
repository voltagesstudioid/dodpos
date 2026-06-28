<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .pl-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .pl-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .pl-hdr-l { display:flex; align-items:center; gap:1rem; }
        .pl-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            box-shadow:0 8px 24px rgba(37,99,235,0.3);
        }
        .pl-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .pl-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .pl-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s; border:none; cursor:pointer; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 6px 20px rgba(37,99,235,0.35);
        }
        .pl-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(37,99,235,0.45); }

        /* KPI Row */
        .pl-kpis { display:grid; grid-template-columns:repeat(5,1fr); gap:1rem; margin-bottom:1.5rem; }
        .pl-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .pl-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .pl-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .pl-kpi.blue::before   { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .pl-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .pl-kpi.sky::before    { background:linear-gradient(180deg,#0ea5e9,#0284c7); }
        .pl-kpi.purple::before { background:linear-gradient(180deg,#8b5cf6,#7c3aed); }
        .pl-kpi.red::before    { background:linear-gradient(180deg,#ef4444,#dc2626); }
        .pl-kpi-top { display:flex; align-items:center; justify-content:space-between; }
        .pl-kpi-left { display:flex; flex-direction:column; gap:0.25rem; }
        .pl-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .pl-kpi-val { font-size:2.5rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
        .pl-kpi-val.blue   { color:#2563eb; }
        .pl-kpi-val.green  { color:#059669; }
        .pl-kpi-val.sky    { color:#0284c7; }
        .pl-kpi-val.purple { color:#7c3aed; }
        .pl-kpi-val.red    { color:#dc2626; }
        .pl-kpi-unit { font-size:1rem; font-weight:600; color:#94a3b8; margin-left:0.25rem; }
        .pl-kpi-foot { font-size:0.72rem; color:#94a3b8; margin-top:0.375rem; }
        .pl-kpi-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
        .pl-kpi-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .pl-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .pl-kpi-ico.sky    { background:linear-gradient(135deg,#f0f9ff,#e0f2fe); }
        .pl-kpi-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .pl-kpi-ico.red    { background:linear-gradient(135deg,#fef2f2,#fee2e2); }

        /* Hutang card special */
        .pl-kpi-hutang { position:relative; }
        .pl-kpi-hutang .pl-kpi-val { font-size:1.5rem; }
        .pl-hutang-link {
            display:inline-flex; align-items:center; gap:0.25rem;
            font-size:0.72rem; font-weight:600; color:#dc2626; text-decoration:none;
            margin-top:0.375rem; transition:all 0.2s;
        }
        .pl-hutang-link:hover { color:#b91c1c; gap:0.5rem; }

        /* Filter */
        .pl-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .pl-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .pl-ff-fld { min-width:200px; flex:1; }
        .pl-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .pl-finput {
            width:100%; padding:0.625rem 0.875rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .pl-finput:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .pl-finput-ico { position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }
        .pl-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .pl-fsel:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .pl-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 4px 12px rgba(37,99,235,0.25);
        }
        .pl-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(37,99,235,0.35); }
        .pl-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .pl-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

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
        .pl-tbl { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
        .pl-tbl-head { background:linear-gradient(180deg,#eff6ff,#f0f7ff); border-bottom:2px solid #bfdbfe; }
        .pl-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#1e40af; white-space:nowrap;
        }
        .pl-tbl-body td { padding:0.9375rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .pl-tbl-body tr { transition:background 0.15s; }
        .pl-tbl-body tr:last-child td { border-bottom:none; }
        .pl-tbl-body tr:hover td { background:linear-gradient(90deg,#f8faff,#eff6ff); }

        /* Code cell */
        .pl-code { display:inline-flex; align-items:center; gap:0.375rem; }
        .pl-code-badge {
            width:22px; height:22px; border-radius:6px; display:flex; align-items:center; justify-content:center;
            font-size:0.5625rem; font-weight:800; background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#2563eb;
            flex-shrink:0;
        }
        .pl-code-val { font-size:0.8125rem; font-weight:600; color:#1e293b; font-family:'JetBrains Mono',monospace; letter-spacing:-0.01em; }

        /* Customer cell */
        .pl-cust { display:flex; align-items:center; gap:0.75rem; }
        .pl-cust-av {
            width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center;
            font-size:0.875rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 4px 12px rgba(37,99,235,0.2);
        }
        .pl-cust-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .pl-cust-owner { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        /* Contact cell */
        .pl-contact { display:flex; align-items:center; gap:0.375rem; font-size:0.8125rem; font-weight:500; color:#1e293b; }
        .pl-contact-ico { color:#10b981; flex-shrink:0; }

        /* Location cell */
        .pl-loc { display:flex; align-items:center; gap:0.25rem; font-size:0.8125rem; color:#1e293b; }
        .pl-loc-ico { color:#94a3b8; flex-shrink:0; }
        .pl-loc-gps { font-size:0.6875rem; color:#3b82f6; margin-top:1px; display:flex; align-items:center; gap:0.25rem; }

        /* Type badge */
        .pl-type {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .pl-type.eceran { background:#eff6ff; color:#2563eb; border-color:#bfdbfe; }
        .pl-type.grosir { background:#f5f3ff; color:#7c3aed; border-color:#ddd6fe; }
        .pl-type.agen   { background:#fff7ed; color:#ea580c; border-color:#fed7aa; }

        /* Status badge */
        .pl-status {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .pl-status.aktif    { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .pl-status.nonaktif { background:#f8fafc; color:#64748b; border-color:#e2e8f0; }
        .pl-status.blacklist { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
        .pl-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .pl-status-dot.aktif    { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.2); }
        .pl-status-dot.nonaktif { background:#94a3b8; }
        .pl-status-dot.blacklist { background:#ef4444; box-shadow:0 0 0 2px rgba(239,68,68,0.2); }

        /* Hutang cell */
        .pl-hutang {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.25rem 0.625rem; border-radius:8px; font-size:0.75rem; font-weight:700;
        }
        .pl-hutang.has { background:#fef2f2; color:#dc2626; }
        .pl-hutang.none { color:#94a3b8; font-weight:500; }

        /* Actions */
        .pl-acts { display:flex; align-items:center; gap:0.375rem; justify-content:center; }
        .pl-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.375rem 0.625rem; border-radius:8px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
        }
        .pl-act.detail { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .pl-act.detail:hover { background:#dbeafe; border-color:#93c5fd; }
        .pl-act.edit { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .pl-act.edit:hover { background:#d1fae5; border-color:#6ee7b7; }

        /* Empty */
        .pl-empty { text-align:center; padding:3.5rem 1.5rem; }
        .pl-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#eff6ff,#dbeafe); display:flex; align-items:center; justify-content:center;
        }
        .pl-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .pl-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .pl-empty-cta {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; text-decoration:none;
            box-shadow:0 6px 20px rgba(37,99,235,0.25); transition:all 0.2s; font-family:inherit;
        }
        .pl-empty-cta:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(37,99,235,0.4); }

        @media(max-width:1024px) { .pl-kpis { grid-template-columns:repeat(3,1fr); } }
        @media(max-width:768px)  { .pl-kpis { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:640px)  { .pl-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="pl-page">

            {{-- Header --}}
            <div class="pl-hdr">
                <div class="pl-hdr-l">
                    <div class="pl-hdr-ico">🏪</div>
                    <div>
                        <div class="pl-hdr-title">Data Pelanggan</div>
                        <div class="pl-hdr-sub">Kelola data pelanggan divisi mineral</div>
                    </div>
                </div>
                <a href="{{ route('mineral.pelanggan.create') }}" class="pl-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pelanggan
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="pl-kpis">
                <div class="pl-kpi blue">
                    <div class="pl-kpi-top">
                        <div class="pl-kpi-left">
                            <span class="pl-kpi-lbl">Total Pelanggan</span>
                            <div>
                                <span class="pl-kpi-val blue">{{ number_format($stats['total'], 0, ',', '.') }}</span>
                            </div>
                            <div class="pl-kpi-foot">Seluruh data pelanggan</div>
                        </div>
                        <div class="pl-kpi-ico blue">👥</div>
                    </div>
                </div>
                <div class="pl-kpi green">
                    <div class="pl-kpi-top">
                        <div class="pl-kpi-left">
                            <span class="pl-kpi-lbl">Aktif</span>
                            <div>
                                <span class="pl-kpi-val green">{{ number_format($stats['aktif'], 0, ',', '.') }}</span>
                            </div>
                            <div class="pl-kpi-foot">Pelanggan aktif</div>
                        </div>
                        <div class="pl-kpi-ico green">✅</div>
                    </div>
                </div>
                <div class="pl-kpi sky">
                    <div class="pl-kpi-top">
                        <div class="pl-kpi-left">
                            <span class="pl-kpi-lbl">Eceran</span>
                            <div>
                                <span class="pl-kpi-val sky">{{ number_format($stats['eceran'], 0, ',', '.') }}</span>
                            </div>
                            <div class="pl-kpi-foot">Tipe eceran</div>
                        </div>
                        <div class="pl-kpi-ico sky">🛒</div>
                    </div>
                </div>
                <div class="pl-kpi purple">
                    <div class="pl-kpi-top">
                        <div class="pl-kpi-left">
                            <span class="pl-kpi-lbl">Grosir</span>
                            <div>
                                <span class="pl-kpi-val purple">{{ number_format($stats['grosir'], 0, ',', '.') }}</span>
                            </div>
                            <div class="pl-kpi-foot">Tipe grosir</div>
                        </div>
                        <div class="pl-kpi-ico purple">🏭</div>
                    </div>
                </div>
                <div class="pl-kpi red pl-kpi-hutang">
                    <div class="pl-kpi-top">
                        <div class="pl-kpi-left">
                            <span class="pl-kpi-lbl">Total Hutang</span>
                            <div>
                                <span class="pl-kpi-val red">Rp {{ number_format($stats['total_hutang'], 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('mineral.hutang.index') }}" class="pl-hutang-link">
                                Lihat Detail
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                        <div class="pl-kpi-ico red">💰</div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="pl-filter">
                <form method="GET" class="pl-ff">
                    <div class="pl-ff-fld" style="position:relative;">
                        <label class="pl-flbl">Cari Pelanggan</label>
                        <div style="position:relative;">
                            <svg class="pl-finput-ico" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama toko, pemilik, atau no HP..." class="pl-finput">
                        </div>
                    </div>
                    <div>
                        <label class="pl-flbl">Regional</label>
                        <select name="regional_id" class="pl-fsel">
                            <option value="">Semua Regional</option>
                            @foreach($regionals as $r)
                                <option value="{{ $r->id }}" {{ request('regional_id') == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="pl-flbl">Tipe</label>
                        <select name="tipe" class="pl-fsel">
                            <option value="">Semua Tipe</option>
                            <option value="eceran" {{ request('tipe') == 'eceran' ? 'selected' : '' }}>Eceran</option>
                            <option value="grosir" {{ request('tipe') == 'grosir' ? 'selected' : '' }}>Grosir</option>
                            <option value="agen" {{ request('tipe') == 'agen' ? 'selected' : '' }}>Agen</option>
                        </select>
                    </div>
                    <div>
                        <label class="pl-flbl">Status</label>
                        <select name="status" class="pl-fsel">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="blacklist" {{ request('status') == 'blacklist' ? 'selected' : '' }}>Blacklist</option>
                        </select>
                    </div>
                    <button type="submit" class="pl-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('mineral.pelanggan.index') }}" class="pl-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- Regional Summary Cards --}}
            @if($regionalStats->count() > 0 && !request('regional_id'))
            <div style="display:grid; grid-template-columns:repeat({{ min($regionalStats->count(), 4) }},1fr); gap:0.75rem; margin-bottom:1.5rem;">
                @foreach($regionalStats as $rs)
                <a href="{{ route('mineral.pelanggan.index', ['regional_id' => $rs->id]) }}" style="text-decoration:none;">
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1rem 1.25rem; transition:all 0.2s; cursor:pointer;"
                         onmouseover="this.style.borderColor='#93c5fd'; this.style.boxShadow='0 4px 16px rgba(37,99,235,0.1)'"
                         onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                            <span style="font-size:0.75rem; font-weight:700; color:#1e40af;">{{ $rs->nama }}</span>
                            <span style="font-size:0.625rem; color:#64748b;">{{ $rs->kode_regional }}</span>
                        </div>
                        <div style="display:flex; align-items:baseline; gap:0.5rem;">
                            <span style="font-size:1.25rem; font-weight:800; color:#2563eb;">{{ $rs->pelanggan_count }}</span>
                            <span style="font-size:0.6875rem; color:#94a3b8;">pelanggan aktif</span>
                        </div>
                        @if($rs->hutang_sum > 0)
                        <div style="font-size:0.6875rem; color:#dc2626; font-weight:600; margin-top:0.25rem;">
                            Hutang: Rp {{ number_format($rs->hutang_sum, 0, ',', '.') }}
                        </div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            {{-- Grouped Tables --}}
            @if($grouped->isEmpty())
                <div class="pl-tbl">
                    <div class="pl-empty">
                        <div class="pl-empty-ico">
                            <svg width="32" height="32" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div class="pl-empty-title">Belum Ada Data Pelanggan</div>
                        <div class="pl-empty-sub">Coba ubah filter atau tambah pelanggan baru</div>
                        <a href="{{ route('mineral.pelanggan.create') }}" class="pl-empty-cta">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Pelanggan Pertama
                        </a>
                    </div>
                </div>
            @else
                @foreach($grouped as $regionalName => $customers)
                <div class="rg-group">
                    <div class="rg-hdr" onclick="this.parentElement.classList.toggle('collapsed')">
                        <div class="rg-hdr-l">
                            <div class="rg-hdr-ico">📍</div>
                            <div class="rg-hdr-name">{{ $regionalName }}</div>
                        </div>
                        <div class="rg-hdr-meta">
                            <span class="rg-hdr-badge">{{ $customers->count() }} pelanggan</span>
                            <svg class="rg-hdr-chevron" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <div class="rg-body">
                        <div style="overflow-x:auto;">
                            <table style="width:100%; border-collapse:separate; border-spacing:0;">
                                <thead class="pl-tbl-head">
                                    <tr>
                                        <th style="text-align:left;">Kode</th>
                                        <th style="text-align:left;">Pelanggan</th>
                                        <th style="text-align:left;">Kontak</th>
                                        <th style="text-align:left;">Lokasi</th>
                                        <th style="text-align:center;">Tipe</th>
                                        <th style="text-align:center;">Status</th>
                                        <th style="text-align:right;">Hutang</th>
                                        <th style="text-align:center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="pl-tbl-body">
                                    @foreach($customers as $p)
                                    <tr>
                                        <td>
                                            <div class="pl-code">
                                                <div class="pl-code-badge">P</div>
                                                <span class="pl-code-val">{{ $p->kode_pelanggan }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pl-cust">
                                                <div class="pl-cust-av">{{ strtoupper(substr($p->nama_toko, 0, 2)) }}</div>
                                                <div>
                                                    <div class="pl-cust-name">{{ $p->nama_toko }}</div>
                                                    <div class="pl-cust-owner">{{ $p->nama_pemilik }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($p->no_hp)
                                                <div class="pl-contact">
                                                    <svg class="pl-contact-ico" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                    {{ $p->no_hp }}
                                                </div>
                                            @else
                                                <span style="color:#94a3b8; font-style:italic; font-size:0.75rem;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->kecamatan || $p->kota)
                                                <div class="pl-loc">
                                                    <svg class="pl-loc-ico" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    {{ $p->kecamatan }}{{ $p->kecamatan && $p->kota ? ', ' : '' }}{{ $p->kota }}
                                                </div>
                                                @if($p->latitude && $p->longitude)
                                                    <div class="pl-loc-gps">
                                                        <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                                        GPS tersedia
                                                    </div>
                                                @endif
                                            @else
                                                <span style="color:#94a3b8; font-style:italic; font-size:0.75rem;">Belum diisi</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="pl-type {{ $p->tipe }}">
                                                {{ $p->tipe === 'eceran' ? '🛒' : ($p->tipe === 'grosir' ? '🏭' : '🤝') }}
                                                {{ ucfirst($p->tipe) }}
                                            </span>
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="pl-status {{ $p->status }}">
                                                <span class="pl-status-dot {{ $p->status }}"></span>
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        </td>
                                        <td style="text-align:right;">
                                            @if($p->total_hutang > 0)
                                                <span class="pl-hutang has">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Rp {{ number_format($p->total_hutang, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="pl-hutang none">-</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            <div class="pl-acts">
                                                <a href="{{ route('mineral.pelanggan.show', $p) }}" class="pl-act detail">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    Detail
                                                </a>
                                                <a href="{{ route('mineral.pelanggan.edit', $p) }}" class="pl-act edit">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    Edit
                                                </a>
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
