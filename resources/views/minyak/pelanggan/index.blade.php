<x-app-layout>
    @push('styles')
    <style>
        .pel-page { max-width:80rem; margin:0 auto; padding:0 1rem; }

        /* Page Header */
        .pel-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .pel-hdr-l { display:flex; align-items:center; gap:1rem; }
        .pel-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#f59e0b,#ea580c);
            box-shadow:0 8px 24px rgba(234,88,12,0.3);
        }
        .pel-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .pel-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .pel-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s cubic-bezier(0.4,0,0.2,1); border:none; cursor:pointer;
            background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff;
            box-shadow:0 6px 20px rgba(234,88,12,0.35);
        }
        .pel-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(234,88,12,0.45); }

        /* KPI Cards */
        .pel-kpis { display:grid; grid-template-columns:repeat(5,1fr); gap:0.875rem; margin-bottom:1.5rem; }
        .pel-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.25rem;
            transition:all 0.3s cubic-bezier(0.4,0,0.2,1); position:relative; overflow:hidden;
        }
        .pel-kpi::before {
            content:''; position:absolute; top:0; left:0; right:0; height:3px;
        }
        .pel-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .pel-kpi.blue::before   { background:linear-gradient(90deg,#6366f1,#3b82f6); }
        .pel-kpi.green::before  { background:linear-gradient(90deg,#10b981,#059669); }
        .pel-kpi.cyan::before   { background:linear-gradient(90deg,#06b6d4,#0891b2); }
        .pel-kpi.purple::before { background:linear-gradient(90deg,#8b5cf6,#7c3aed); }
        .pel-kpi.red::before    { background:linear-gradient(90deg,#ef4444,#dc2626); }
        .pel-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.625rem; }
        .pel-kpi-lbl { font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .pel-kpi-ico {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.05rem;
        }
        .pel-kpi-ico.blue   { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
        .pel-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .pel-kpi-ico.cyan   { background:linear-gradient(135deg,#ecfeff,#cffafe); }
        .pel-kpi-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .pel-kpi-ico.red    { background:linear-gradient(135deg,#fff1f2,#ffe4e6); }
        .pel-kpi-val { font-size:1.875rem; font-weight:800; letter-spacing:-0.03em; line-height:1; margin-bottom:0.2rem; }
        .pel-kpi-val.blue   { color:#3b82f6; }
        .pel-kpi-val.green  { color:#059669; }
        .pel-kpi-val.cyan   { color:#0891b2; }
        .pel-kpi-val.purple { color:#7c3aed; }
        .pel-kpi-val.red    { color:#dc2626; }
        .pel-kpi-foot { font-size:0.7rem; color:#94a3b8; }

        /* Filter Panel */
        .pel-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .pel-filter-form { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .pel-ff { flex:1; min-width:180px; }
        .pel-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .pel-finput {
            width:100%; padding:0.625rem 1rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .pel-finput:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .pel-finput-ico { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#94a3b8; pointer-events:none; }
        .pel-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .pel-fsel:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .pel-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff; box-shadow:0 4px 12px rgba(234,88,12,0.25);
        }
        .pel-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(234,88,12,0.35); }
        .pel-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .pel-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        /* Regional Group */
        .rg-group { margin-bottom:1.25rem; }
        .rg-hdr {
            display:flex; align-items:center; justify-content:space-between; gap:0.75rem;
            padding:0.875rem 1.25rem; background:linear-gradient(135deg,#fffbeb,#fef3c7);
            border:1px solid #fde68a; border-radius:14px 14px 0 0; cursor:pointer;
            transition:all 0.2s; user-select:none;
        }
        .rg-hdr:hover { background:linear-gradient(135deg,#fef3c7,#fde68a); }
        .rg-hdr-l { display:flex; align-items:center; gap:0.75rem; }
        .rg-hdr-ico {
            width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center;
            background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff; font-size:0.875rem; flex-shrink:0;
        }
        .rg-hdr-name { font-size:0.9375rem; font-weight:700; color:#92400e; }
        .rg-hdr-meta { display:flex; align-items:center; gap:1rem; }
        .rg-hdr-badge {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.2rem 0.625rem; border-radius:20px; font-size:0.6875rem; font-weight:700;
            background:#fff; color:#ea580c; border:1px solid #fde68a;
        }
        .rg-hdr-chevron { transition:transform 0.2s; color:#64748b; }
        .rg-group.collapsed .rg-hdr { border-radius:14px; }
        .rg-group.collapsed .rg-body { display:none; }
        .rg-group.collapsed .rg-hdr-chevron { transform:rotate(-90deg); }
        .rg-body {
            border:1px solid #e2e8f0; border-top:none; border-radius:0 0 14px 14px;
            overflow:hidden; background:#fff;
        }

        /* Table styles */
        .pel-tbl-head { background:linear-gradient(180deg,#fffbeb,#fef9e7); border-bottom:2px solid #fde68a; }
        .pel-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#92400e; white-space:nowrap;
        }
        .pel-tbl-body td {
            padding:0.9375rem 1.25rem; border-bottom:1px solid #fef9e7; font-size:0.8125rem;
            color:#374151; vertical-align:middle;
        }
        .pel-tbl-body tr { transition:background 0.15s; }
        .pel-tbl-body tr:last-child td { border-bottom:none; }
        .pel-tbl-body tr:hover td { background:linear-gradient(90deg,#fffdf8,#fffbeb); }

        /* Store Cell */
        .pel-store { display:flex; align-items:center; gap:0.75rem; }
        .pel-store-avatar {
            width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            font-size:1rem; font-weight:800; flex-shrink:0;
            background:linear-gradient(135deg,#fff7ed,#ffedd5); color:#c2410c;
            border:1.5px solid #fed7aa;
        }
        .pel-store-name { font-size:0.875rem; font-weight:600; color:#1e293b; line-height:1.2; }
        .pel-store-addr { font-size:0.6875rem; color:#94a3b8; margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:160px; }
        .pel-code {
            display:inline-flex; padding:0.2rem 0.5rem; border-radius:6px; font-size:0.6875rem; font-weight:700;
            background:#fffbeb; color:#92400e; letter-spacing:0.02em; font-family:monospace; border:1px solid #fde68a;
        }
        .pel-owner { font-size:0.8125rem; font-weight:500; color:#1e293b; }
        .pel-phone { font-size:0.8125rem; color:#475569; }

        /* Tipe Badge */
        .pel-tipe {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .pel-tipe.eceran { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .pel-tipe.grosir { background:#f5f3ff; color:#7c3aed; border-color:#ddd6fe; }
        .pel-tipe.agen   { background:#fff7ed; color:#c2410c; border-color:#fed7aa; }
        .pel-tipe-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .pel-tipe-dot.eceran { background:#3b82f6; }
        .pel-tipe-dot.grosir { background:#8b5cf6; }
        .pel-tipe-dot.agen   { background:#ea580c; }

        /* Hutang Cell */
        .pel-hutang { text-align:right; font-weight:700; font-size:0.8125rem; }
        .pel-hutang.has { color:#dc2626; }
        .pel-hutang.clean { color:#94a3b8; font-weight:500; }
        .pel-hutang-bar { height:3px; border-radius:99px; background:#e2e8f0; margin-top:4px; overflow:hidden; }
        .pel-hutang-bar-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#f59e0b,#ef4444); transition:width 0.4s ease; }

        /* Actions */
        .pel-acts { display:flex; gap:0.375rem; align-items:center; justify-content:center; }
        .pel-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.3125rem 0.625rem; border-radius:7px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
        }
        .pel-act-view  { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .pel-act-view:hover  { background:#dbeafe; border-color:#93c5fd; }
        .pel-act-edit  { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .pel-act-edit:hover  { background:#d1fae5; }

        /* Empty State */
        .pel-empty { text-align:center; padding:3.5rem 1.5rem; }
        .pel-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#fff7ed,#ffedd5); display:flex; align-items:center; justify-content:center;
        }
        .pel-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .pel-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .pel-empty-cta {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff; text-decoration:none;
            box-shadow:0 6px 20px rgba(234,88,12,0.25); transition:all 0.2s;
        }
        .pel-empty-cta:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(234,88,12,0.4); }

        /* Responsive */
        @media(max-width:1280px) { .pel-kpis { grid-template-columns:repeat(3,1fr); } }
        @media(max-width:768px)  { .pel-kpis { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:640px)  {
            .pel-kpis { grid-template-columns:1fr; }
            .pel-hdr-title { font-size:1.25rem; }
        }
    </style>
    @endpush

    <div class="py-4">
        <div class="pel-page">

            {{-- Page Header --}}
            <div class="pel-hdr">
                <div class="pel-hdr-l">
                    <div class="pel-hdr-ico">🏪</div>
                    <div>
                        <div class="pel-hdr-title">Data Pelanggan</div>
                        <div class="pel-hdr-sub">Kelola data pelanggan, tipe, dan limit hutang minyak</div>
                    </div>
                </div>
                <a href="{{ route('minyak.pelanggan.create') }}" class="pel-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pelanggan
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="pel-kpis">
                <div class="pel-kpi blue">
                    <div class="pel-kpi-top">
                        <span class="pel-kpi-lbl">Total</span>
                        <div class="pel-kpi-ico blue">🏢</div>
                    </div>
                    <div class="pel-kpi-val blue">{{ $stats['total'] }}</div>
                    <div class="pel-kpi-foot">Semua pelanggan terdaftar</div>
                </div>
                <div class="pel-kpi green">
                    <div class="pel-kpi-top">
                        <span class="pel-kpi-lbl">Aktif</span>
                        <div class="pel-kpi-ico green">✅</div>
                    </div>
                    <div class="pel-kpi-val green">{{ $stats['aktif'] }}</div>
                    <div class="pel-kpi-foot">Pelanggan aktif bertransaksi</div>
                </div>
                <div class="pel-kpi cyan">
                    <div class="pel-kpi-top">
                        <span class="pel-kpi-lbl">Eceran</span>
                        <div class="pel-kpi-ico cyan">🛒</div>
                    </div>
                    <div class="pel-kpi-val cyan">{{ $stats['eceran'] }}</div>
                    <div class="pel-kpi-foot">Pelanggan tipe eceran</div>
                </div>
                <div class="pel-kpi purple">
                    <div class="pel-kpi-top">
                        <span class="pel-kpi-lbl">Grosir</span>
                        <div class="pel-kpi-ico purple">📦</div>
                    </div>
                    <div class="pel-kpi-val purple">{{ $stats['grosir'] }}</div>
                    <div class="pel-kpi-foot">Pelanggan tipe grosir</div>
                </div>
                @if(! $isSalesRole)
                <div class="pel-kpi red">
                    <div class="pel-kpi-top">
                        <span class="pel-kpi-lbl">Total Hutang</span>
                        <div class="pel-kpi-ico red">💰</div>
                    </div>
                    <div class="pel-kpi-val red" style="font-size:{{ strlen(number_format($stats['total_hutang'] ?? 0, 0, ',', '.')) > 10 ? '1.35rem' : '1.875rem' }};">
                        Rp {{ number_format($stats['total_hutang'] ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="pel-kpi-foot">Akumulasi seluruh hutang</div>
                </div>
                @endif
            </div>

            {{-- Filter Bar --}}
            <div class="pel-filter">
                <form method="GET" class="pel-filter-form">
                    <div class="pel-ff" style="position:relative;">
                        <label class="pel-flbl">Pencarian</label>
                        <div style="position:relative;">
                            <svg class="pel-finput-ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama toko, pemilik, no HP..." class="pel-finput">
                        </div>
                    </div>
                    <div>
                        <label class="pel-flbl">Regional</label>
                        <select name="regional_id" class="pel-fsel">
                            <option value="">Semua Regional</option>
                            @foreach($regionals as $r)
                                <option value="{{ $r->id }}" {{ request('regional_id') == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="pel-flbl">Tipe</label>
                        <select name="tipe" class="pel-fsel">
                            <option value="">Semua Tipe</option>
                            <option value="eceran" {{ request('tipe') == 'eceran' ? 'selected' : '' }}>Eceran</option>
                            <option value="grosir" {{ request('tipe') == 'grosir' ? 'selected' : '' }}>Grosir</option>
                            <option value="agen" {{ request('tipe') == 'agen' ? 'selected' : '' }}>Agen</option>
                        </select>
                    </div>
                    <div>
                        <label class="pel-flbl">Status</label>
                        <select name="status" class="pel-fsel">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="blacklist" {{ request('status') == 'blacklist' ? 'selected' : '' }}>Blacklist</option>
                        </select>
                    </div>
                    <button type="submit" class="pel-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('minyak.pelanggan.index') }}" class="pel-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- Regional Summary Cards --}}
            @if($regionalStats->count() > 0 && !request('regional_id'))
            <div style="display:grid; grid-template-columns:repeat({{ min($regionalStats->count(), 4) }},1fr); gap:0.75rem; margin-bottom:1.5rem;">
                @foreach($regionalStats as $rs)
                <a href="{{ route('minyak.pelanggan.index', ['regional_id' => $rs->id]) }}" style="text-decoration:none;">
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1rem 1.25rem; transition:all 0.2s; cursor:pointer;"
                         onmouseover="this.style.borderColor='#fdba74'; this.style.boxShadow='0 4px 16px rgba(234,88,12,0.1)'"
                         onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                            <span style="font-size:0.75rem; font-weight:700; color:#92400e;">{{ $rs->nama }}</span>
                            <span style="font-size:0.625rem; color:#64748b;">{{ $rs->kode_regional }}</span>
                        </div>
                        <div style="display:flex; align-items:baseline; gap:0.5rem;">
                            <span style="font-size:1.25rem; font-weight:800; color:#ea580c;">{{ $rs->pelanggan_count }}</span>
                            <span style="font-size:0.6875rem; color:#94a3b8;">pelanggan aktif</span>
                        </div>
                        @if(!$isSalesRole && $rs->hutang_sum > 0)
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
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;">
                    <div class="pel-empty">
                        <div class="pel-empty-ico">
                            <svg width="32" height="32" fill="none" stroke="#c2410c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div class="pel-empty-title">Belum Ada Data Pelanggan</div>
                        <div class="pel-empty-sub">Coba ubah filter atau tambah pelanggan baru</div>
                        <a href="{{ route('minyak.pelanggan.create') }}" class="pel-empty-cta">
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
                                <thead class="pel-tbl-head">
                                    <tr>
                                        <th style="text-align:left;">Kode</th>
                                        <th style="text-align:left;">Nama Toko</th>
                                        <th style="text-align:left;">Pemilik</th>
                                        <th style="text-align:left;">No HP</th>
                                        <th style="text-align:center;">Tipe</th>
                                        <th style="text-align:right;">Hutang</th>
                                        <th style="text-align:center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="pel-tbl-body">
                                    @foreach($customers as $p)
                                        @php
                                            $limitHutang = $p->limit_hutang ?: 0;
                                            $hutangPct = $limitHutang > 0 ? min(round(($p->total_hutang / $limitHutang) * 100), 100) : ($p->total_hutang > 0 ? 100 : 0);
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="pel-code">{{ $p->kode_pelanggan }}</span>
                                            </td>
                                            <td>
                                                <div class="pel-store">
                                                    <div class="pel-store-avatar">{{ substr($p->nama_toko, 0, 1) }}</div>
                                                    <div>
                                                        <div class="pel-store-name">{{ $p->nama_toko }}</div>
                                                        <div class="pel-store-addr">{{ $p->alamat ?? ($p->kota ?? '-') }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="pel-owner">{{ $p->nama_pemilik }}</div>
                                            </td>
                                            <td>
                                                <div class="pel-phone">
                                                    @if($p->no_hp)
                                                        <span style="display:inline-flex;align-items:center;gap:0.3rem;">
                                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                            {{ $p->no_hp }}
                                                        </span>
                                                    @else
                                                        <span style="color:#94a3b8;">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td style="text-align:center;">
                                                <span class="pel-tipe {{ $p->tipe }}">
                                                    <span class="pel-tipe-dot {{ $p->tipe }}"></span>
                                                    {{ ucfirst($p->tipe) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="pel-hutang {{ $p->total_hutang > 0 ? 'has' : 'clean' }}">
                                                    Rp {{ number_format($p->total_hutang, 0, ',', '.') }}
                                                </div>
                                                @if($p->total_hutang > 0 && $limitHutang > 0)
                                                    <div class="pel-hutang-bar">
                                                        <div class="pel-hutang-bar-fill" style="width:{{ $hutangPct }}%; background:{{ $hutangPct >= 80 ? 'linear-gradient(90deg,#ef4444,#dc2626)' : ($hutangPct >= 50 ? 'linear-gradient(90deg,#f59e0b,#d97706)' : 'linear-gradient(90deg,#10b981,#059669)') }};"></div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td style="text-align:center;">
                                                <div class="pel-acts">
                                                    <a href="{{ route('minyak.pelanggan.show', $p) }}" class="pel-act pel-act-view">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        Detail
                                                    </a>
                                                    @if(! $isSalesRole)
                                                    <a href="{{ route('minyak.pelanggan.edit', $p) }}" class="pel-act pel-act-edit">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        Edit
                                                    </a>
                                                    @endif
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
