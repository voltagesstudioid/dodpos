<x-app-layout>
    @push('styles')
    <style>
        .pr-page { max-width:80rem; margin:0 auto; padding:0 1rem; }

        /* Header */
        .pr-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .pr-hdr-l { display:flex; align-items:center; gap:1rem; }
        .pr-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#f59e0b,#dc2626);
            box-shadow:0 8px 24px rgba(220,38,38,0.3);
        }
        .pr-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .pr-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .pr-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s; border:none; cursor:pointer;
            background:linear-gradient(135deg,#f59e0b,#dc2626); color:#fff;
            box-shadow:0 6px 20px rgba(220,38,38,0.3);
        }
        .pr-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(220,38,38,0.4); }

        /* KPI Row */
        .pr-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
        .pr-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.375rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .pr-kpi::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; }
        .pr-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .pr-kpi.amber::after  { background:linear-gradient(90deg,#f59e0b,#d97706); }
        .pr-kpi.green::after  { background:linear-gradient(90deg,#10b981,#059669); }
        .pr-kpi.red::after    { background:linear-gradient(90deg,#ef4444,#dc2626); }
        .pr-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.625rem; }
        .pr-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .pr-kpi-ico {
            width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem;
        }
        .pr-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .pr-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .pr-kpi-ico.red    { background:linear-gradient(135deg,#fff1f2,#ffe4e6); }
        .pr-kpi-val { font-size:2.25rem; font-weight:800; letter-spacing:-0.03em; line-height:1; margin-bottom:0.25rem; }
        .pr-kpi-val.amber  { color:#d97706; }
        .pr-kpi-val.green  { color:#059669; }
        .pr-kpi-val.red    { color:#dc2626; }
        .pr-kpi-foot { font-size:0.72rem; color:#94a3b8; }
        .pr-kpi-alert {
            display:inline-flex; align-items:center; gap:0.25rem; padding:0.2rem 0.5rem; border-radius:6px;
            font-size:0.6875rem; font-weight:700; background:#fff1f2; color:#dc2626; border:1px solid #fecdd3;
            margin-top:0.375rem;
        }

        /* Filter */
        .pr-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .pr-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .pr-ff-fld { flex:1; min-width:180px; }
        .pr-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .pr-fwrap { position:relative; }
        .pr-fico { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#94a3b8; pointer-events:none; }
        .pr-finput {
            width:100%; padding:0.625rem 1rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .pr-finput:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .pr-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .pr-fsel:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .pr-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#f59e0b,#dc2626); color:#fff; box-shadow:0 4px 12px rgba(220,38,38,0.25);
        }
        .pr-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(220,38,38,0.35); }
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
        .pr-tbl-head {
            background:linear-gradient(180deg,#fffbeb,#fef9e7); border-bottom:2px solid #fde68a;
        }
        .pr-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#92400e; white-space:nowrap;
        }
        .pr-tbl-body td {
            padding:0.9375rem 1.25rem; border-bottom:1px solid #fef9e7; font-size:0.8125rem;
            color:#374151; vertical-align:middle;
        }
        .pr-tbl-body tr { transition:background 0.15s; }
        .pr-tbl-body tr:last-child td { border-bottom:none; }
        .pr-tbl-body tr:hover td { background:linear-gradient(90deg,#fffdf8,#fffbeb); }

        /* Code */
        .pr-code {
            display:inline-flex; padding:0.2rem 0.5rem; border-radius:6px; font-size:0.6875rem; font-weight:700;
            background:#fffbeb; color:#92400e; letter-spacing:0.02em; font-family:monospace; border:1px solid #fde68a;
        }

        /* Product Cell */
        .pr-prod { display:flex; align-items:center; gap:0.75rem; }
        .pr-prod-avatar {
            width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            font-size:1rem; font-weight:800; flex-shrink:0;
            background:linear-gradient(135deg,#fff7ed,#ffedd5); color:#c2410c; border:1.5px solid #fed7aa;
        }
        .pr-prod-name { font-size:0.875rem; font-weight:600; color:#1e293b; line-height:1.2; }
        .pr-prod-jenis { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        /* Jenis Badge */
        .pr-jenis {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .pr-jenis.default { background:#f8fafc; color:#64748b; border-color:#e2e8f0; }
        .pr-jenis.pertalite { background:#dbeafe; color:#1d4ed8; border-color:#bfdbfe; }
        .pr-jenis.pertamax { background:#f5f3ff; color:#7c3aed; border-color:#ddd6fe; }
        .pr-jenis.solar { background:#fff7ed; color:#c2410c; border-color:#fed7aa; }
        .pr-jenis-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .pr-jenis-dot.default { background:#94a3b8; }
        .pr-jenis-dot.pertalite { background:#3b82f6; }
        .pr-jenis-dot.pertamax { background:#8b5cf6; }
        .pr-jenis-dot.solar { background:#ea580c; }

        /* Satuan */
        .pr-satuan { font-size:0.8125rem; font-weight:500; color:#1e293b; }

        /* Harga */
        .pr-harga { text-align:right; }
        .pr-harga-val { font-size:0.875rem; font-weight:700; color:#0f172a; letter-spacing:-0.01em; }
        .pr-harga-sub { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }



        /* Actions */
        .pr-acts { display:flex; gap:0.375rem; align-items:center; justify-content:center; }
        .pr-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.3125rem 0.625rem; border-radius:7px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
        }
        .pr-act-view  { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .pr-act-view:hover  { background:#dbeafe; border-color:#93c5fd; }
        .pr-act-edit  { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .pr-act-edit:hover  { background:#d1fae5; }

        /* Empty */
        .pr-empty { text-align:center; padding:3.5rem 1.5rem; }
        .pr-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#fff7ed,#ffedd5); display:flex; align-items:center; justify-content:center;
        }
        .pr-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .pr-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .pr-empty-cta {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            background:linear-gradient(135deg,#f59e0b,#dc2626); color:#fff; text-decoration:none;
            box-shadow:0 6px 20px rgba(220,38,38,0.25); transition:all 0.2s;
        }
        .pr-empty-cta:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(220,38,38,0.4); }

        @media(max-width:768px) { .pr-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px) { .pr-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="pr-page">

            {{-- Header --}}
            <div class="pr-hdr">
                <div class="pr-hdr-l">
                    <div class="pr-hdr-ico">🛢️</div>
                    <div>
                        <div class="pr-hdr-title">Data Produk</div>
                        <div class="pr-hdr-sub">Kelola produk Minyak</div>
                    </div>
                </div>
                @if(! $isSalesRole)
                <a href="{{ route('minyak.produk.create') }}" class="pr-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Produk
                </a>
                @endif
            </div>

            {{-- KPI Cards --}}
            <div class="pr-kpis">
                <div class="pr-kpi amber">
                    <div class="pr-kpi-top">
                        <span class="pr-kpi-lbl">Total Produk</span>
                        <div class="pr-kpi-ico amber">📦</div>
                    </div>
                    <div class="pr-kpi-val amber">{{ $stats['total'] }}</div>
                    <div class="pr-kpi-foot">Semua produk terdaftar</div>
                </div>
                <div class="pr-kpi green">
                    <div class="pr-kpi-top">
                        <span class="pr-kpi-lbl">Produk Aktif</span>
                        <div class="pr-kpi-ico green">✅</div>
                    </div>
                    <div class="pr-kpi-val green">{{ $stats['aktif'] }}</div>
                    <div class="pr-kpi-foot">Produk aktif diperdagangkan</div>
                </div>

            </div>

            {{-- Filter Bar --}}
            <div class="pr-filter">
                <form method="GET" class="pr-ff">
                    <div class="pr-ff-fld">
                        <label class="pr-flbl">Pencarian</label>
                        <div class="pr-fwrap">
                            <svg class="pr-fico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kode, jenis..." class="pr-finput">
                        </div>
                    </div>
                    <div>
                        <label class="pr-flbl">Jenis</label>
                        <select name="jenis" class="pr-fsel">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisList as $j)
                                <option value="{{ $j->nama }}" {{ request('jenis') == $j->nama ? 'selected' : '' }}>{{ $j->nama }}</option>
                            @endforeach
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
                    <a href="{{ route('minyak.produk.index') }}" class="pr-btn-r">
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
                                <th style="text-align:left;">Kode</th>
                                <th style="text-align:left;">Produk</th>
                                <th style="text-align:center;">Jenis</th>
                                <th style="text-align:center;">Satuan</th>
                                <th style="text-align:right;">Harga Jual</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="pr-tbl-body">
                            @forelse($produks as $p)
                                @php
                                    $jenisKey = strtolower($p->jenis ?? '');
                                    $jenisClass = in_array($jenisKey, ['pertalite','pertamax','solar']) ? $jenisKey : 'default';
                                @endphp
                                <tr>
                                    <td>
                                        <span class="pr-code">{{ $p->kode_produk }}</span>
                                    </td>
                                    <td>
                                        <div class="pr-prod">
                                            <div class="pr-prod-avatar">{{ substr($p->nama, 0, 1) }}</div>
                                            <div>
                                                <div class="pr-prod-name">{{ $p->nama }}</div>
                                                <div class="pr-prod-jenis">{{ $p->jenis ?? 'Tanpa jenis' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="pr-jenis {{ $jenisClass }}">
                                            <span class="pr-jenis-dot {{ $jenisClass }}"></span>
                                            {{ $p->jenis ?? '-' }}
                                        </span>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="pr-satuan">{{ $p->satuan }}</span>
                                    </td>
                                    <td>
                                        <div class="pr-harga">
                                            <div class="pr-harga-val">Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</div>
                                            @if($p->harga_modal)
                                                <div class="pr-harga-sub">Modal: Rp {{ number_format($p->harga_modal, 0, ',', '.') }}</div>
                                            @endif
                                        </div>
                                    </td>

                                    <td style="text-align:center;">
                                        <div class="pr-acts">
                                            <a href="{{ route('minyak.produk.show', $p) }}" class="pr-act pr-act-view">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Detail
                                            </a>
                                            @if(! $isSalesRole)
                                            <a href="{{ route('minyak.produk.edit', $p) }}" class="pr-act pr-act-edit">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="pr-empty">
                                            <div class="pr-empty-ico">
                                                <svg width="32" height="32" fill="none" stroke="#c2410c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                            </div>
                                            <div class="pr-empty-title">Belum Ada Data Produk</div>
                                            <div class="pr-empty-sub">Tambahkan produk pertama Anda untuk memulai transaksi</div>
                                            @if(! $isSalesRole)
                                            <a href="{{ route('minyak.produk.create') }}" class="pr-empty-cta">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Tambah Produk Pertama
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($produks->hasPages())
                    <div style="padding:0.875rem 1.25rem; border-top:1px solid #fef9e7;">
                        {{ $produks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
