<x-app-layout>
    @push('styles')
    <style>
        .rg-page { max-width:80rem; margin:0 auto; padding:0 1rem; }

        /* Header */
        .rg-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .rg-hdr-l { display:flex; align-items:center; gap:1rem; }
        .rg-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#f59e0b,#dc2626);
            box-shadow:0 8px 24px rgba(220,38,38,0.3);
        }
        .rg-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .rg-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .rg-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s; border:none; cursor:pointer;
            background:linear-gradient(135deg,#f59e0b,#dc2626); color:#fff;
            box-shadow:0 6px 20px rgba(220,38,38,0.3);
        }
        .rg-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(220,38,38,0.4); }

        /* KPI */
        .rg-kpis { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; margin-bottom:1.5rem; }
        .rg-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.375rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .rg-kpi::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; }
        .rg-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .rg-kpi.amber::after { background:linear-gradient(90deg,#f59e0b,#d97706); }
        .rg-kpi.green::after { background:linear-gradient(90deg,#10b981,#059669); }
        .rg-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.625rem; }
        .rg-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .rg-kpi-ico { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; }
        .rg-kpi-ico.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .rg-kpi-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .rg-kpi-val { font-size:2.25rem; font-weight:800; letter-spacing:-0.03em; line-height:1; margin-bottom:0.25rem; }
        .rg-kpi-val.amber { color:#d97706; }
        .rg-kpi-val.green { color:#059669; }
        .rg-kpi-foot { font-size:0.72rem; color:#94a3b8; }

        /* Filter */
        .rg-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .rg-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .rg-ff-fld { flex:1; min-width:180px; }
        .rg-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .rg-fwrap { position:relative; }
        .rg-fico { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#94a3b8; pointer-events:none; }
        .rg-finput {
            width:100%; padding:0.625rem 1rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .rg-finput:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .rg-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .rg-fsel:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .rg-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
        }
        .rg-btn-search { background:#f59e0b; color:#fff; }
        .rg-btn-search:hover { background:#d97706; }
        .rg-btn-reset { background:#f1f5f9; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; }
        .rg-btn-reset:hover { background:#e2e8f0; }

        /* Card Grid */
        .rg-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:1rem; }
        .rg-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .rg-card:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.08); border-color:transparent; }
        .rg-card-top { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1rem; }
        .rg-card-info { flex:1; }
        .rg-card-name { font-size:1.125rem; font-weight:700; color:#0f172a; margin-bottom:0.25rem; }
        .rg-card-code { font-size:0.75rem; color:#94a3b8; font-weight:600; }
        .rg-card-badge {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.25rem 0.625rem; border-radius:8px; font-size:0.6875rem; font-weight:700;
        }
        .rg-card-badge.aktif { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .rg-card-badge.nonaktif { background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; }
        .rg-card-stats { display:flex; gap:1rem; margin-bottom:1rem; }
        .rg-card-stat { flex:1; background:#f8fafc; border-radius:10px; padding:0.75rem; text-align:center; }
        .rg-card-stat-val { font-size:1.25rem; font-weight:800; color:#0f172a; }
        .rg-card-stat-lbl { font-size:0.6875rem; color:#94a3b8; margin-top:2px; }
        .rg-card-desc { font-size:0.8125rem; color:#64748b; margin-bottom:1rem; line-height:1.5; }
        .rg-card-actions { display:flex; gap:0.375rem; }
        .rg-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.375rem 0.75rem; border-radius:8px; font-size:0.75rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; font-family:inherit;
        }
        .rg-act-view { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .rg-act-view:hover { background:#dbeafe; border-color:#93c5fd; }
        .rg-act-edit { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .rg-act-edit:hover { background:#d1fae5; }
        .rg-act-del { background:#fff1f2; color:#dc2626; border-color:#fecdd3; }
        .rg-act-del:hover { background:#ffe4e6; }

        /* Empty */
        .rg-empty { text-align:center; padding:3.5rem 1.5rem; background:#fff; border:1px solid #e2e8f0; border-radius:16px; }
        .rg-empty-ico { width:72px; height:72px; margin:0 auto 1rem; border-radius:50%; background:linear-gradient(135deg,#fff7ed,#ffedd5); display:flex; align-items:center; justify-content:center; }
        .rg-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .rg-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }

        /* Pagination */
        .rg-pag { margin-top:1.5rem; display:flex; justify-content:center; }
        .rg-pag nav { display:flex; gap:0.25rem; }
        .rg-pag span, .rg-pag a {
            padding:0.5rem 0.875rem; border-radius:8px; font-size:0.8125rem; font-weight:500;
        }
        .rg-pag span { background:#f59e0b; color:#fff; }
        .rg-pag a { background:#fff; color:#64748b; border:1px solid #e2e8f0; text-decoration:none; }
        .rg-pag a:hover { background:#f8fafc; border-color:#f59e0b; color:#f59e0b; }

        @media(max-width:768px) { .rg-kpis { grid-template-columns:1fr; } .rg-grid { grid-template-columns:1fr; } }
        @media(max-width:640px) { .rg-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="rg-page">

            {{-- Header --}}
            <div class="rg-hdr">
                <div class="rg-hdr-l">
                    <div class="rg-hdr-ico">🗺️</div>
                    <div>
                        <div class="rg-hdr-title">Regional Kerja</div>
                        <div class="rg-hdr-sub">Kelola area kerja sales & harga per regional</div>
                    </div>
                </div>
                <a href="{{ route('minyak.regional.create') }}" class="rg-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 5v14m-7-7h14"/></svg>
                    Tambah Regional
                </a>
            </div>

            {{-- KPI --}}
            <div class="rg-kpis">
                <div class="rg-kpi amber">
                    <div class="rg-kpi-top">
                        <span class="rg-kpi-lbl">Total Regional</span>
                        <div class="rg-kpi-ico amber">🗺️</div>
                    </div>
                    <div class="rg-kpi-val amber">{{ $stats['total'] }}</div>
                    <div class="rg-kpi-foot">Semua area kerja</div>
                </div>
                <div class="rg-kpi green">
                    <div class="rg-kpi-top">
                        <span class="rg-kpi-lbl">Regional Aktif</span>
                        <div class="rg-kpi-ico green">✅</div>
                    </div>
                    <div class="rg-kpi-val green">{{ $stats['aktif'] }}</div>
                    <div class="rg-kpi-foot">Sedang beroperasi</div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="rg-filter">
                <form method="GET" action="{{ route('minyak.regional.index') }}" class="rg-ff">
                    <div class="rg-ff-fld">
                        <label class="rg-flbl">Cari Regional</label>
                        <div class="rg-fwrap">
                            <svg class="rg-fico" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/></svg>
                            <input type="text" name="search" class="rg-finput" placeholder="Cari kode atau nama regional..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div>
                        <label class="rg-flbl">Status</label>
                        <select name="status" class="rg-fsel">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div style="display:flex;gap:0.375rem;">
                        <button type="submit" class="rg-btn-f rg-btn-search">Cari</button>
                        <a href="{{ route('minyak.regional.index') }}" class="rg-btn-f rg-btn-reset">Reset</a>
                    </div>
                </form>
            </div>

            {{-- Alert --}}
            @if(session('success'))
                <div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:12px;padding:0.875rem 1.125rem;margin-bottom:1rem;font-size:0.8125rem;color:#065f46;font-weight:500;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:12px;padding:0.875rem 1.125rem;margin-bottom:1rem;font-size:0.8125rem;color:#dc2626;font-weight:500;">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Grid --}}
            @if($regionals->count())
                <div class="rg-grid">
                    @foreach($regionals as $regional)
                        <div class="rg-card">
                            <div class="rg-card-top">
                                <div class="rg-card-info">
                                    <div class="rg-card-name">{{ $regional->nama }}</div>
                                    <div class="rg-card-code">{{ $regional->kode_regional }}</div>
                                </div>
                                <span class="rg-card-badge {{ $regional->status }}">{{ ucfirst($regional->status) }}</span>
                            </div>

                            <div class="rg-card-stats">
                                <div class="rg-card-stat">
                                    <div class="rg-card-stat-val">{{ $regional->sales_count }}</div>
                                    <div class="rg-card-stat-lbl">Sales</div>
                                </div>
                                <div class="rg-card-stat">
                                    <div class="rg-card-stat-val">{{ $regional->pelanggans_count }}</div>
                                    <div class="rg-card-stat-lbl">Pelanggan</div>
                                </div>
                            </div>

                            @if($regional->deskripsi)
                                <div class="rg-card-desc">{{ Str::limit($regional->deskripsi, 80) }}</div>
                            @endif

                            <div class="rg-card-actions">
                                <a href="{{ route('minyak.regional.show', $regional) }}" class="rg-act rg-act-view">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Detail
                                </a>
                                <a href="{{ route('minyak.regional.edit', $regional) }}" class="rg-act rg-act-edit">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('minyak.regional.destroy', $regional) }}" onsubmit="return confirm('Yakin hapus regional ini?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rg-act rg-act-del">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="rg-pag">
                    {{ $regionals->links('pagination::simple-tailwind') }}
                </div>
            @else
                <div class="rg-empty">
                    <div class="rg-empty-ico">🗺️</div>
                    <div class="rg-empty-title">Belum ada regional</div>
                    <div class="rg-empty-sub">Tambahkan regional kerja untuk mengatur area sales dan harga.</div>
                    <a href="{{ route('minyak.regional.create') }}" class="rg-hdr-btn">Tambah Regional</a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
