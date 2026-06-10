@extends('layouts.app', ['title' => 'Data Sales Pasgar'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .pg-page { font-family:'Plus Jakarta Sans',sans-serif; }

    /* KPI Cards */
    .pg-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
    .pg-kpi {
        background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem 1.5rem;
        transition:all 0.3s; position:relative; overflow:hidden;
    }
    .pg-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
    .pg-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
    .pg-kpi.indigo::before { background:linear-gradient(180deg,#6366f1,#4f46e5); }
    .pg-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
    .pg-kpi.gray::before   { background:linear-gradient(180deg,#94a3b8,#64748b); }
    .pg-kpi-top { display:flex; align-items:center; justify-content:space-between; }
    .pg-kpi-left { display:flex; flex-direction:column; gap:0.25rem; }
    .pg-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
    .pg-kpi-val { font-size:2.5rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
    .pg-kpi-val.indigo { color:#4f46e5; }
    .pg-kpi-val.green  { color:#059669; }
    .pg-kpi-val.gray   { color:#64748b; }
    .pg-kpi-foot { font-size:0.72rem; color:#94a3b8; margin-top:0.375rem; }
    .pg-kpi-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
    .pg-kpi-ico.indigo { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
    .pg-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
    .pg-kpi-ico.gray   { background:linear-gradient(135deg,#f8fafc,#f1f5f9); }

    /* Filter */
    .pg-filter {
        background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
        margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
    }
    .pg-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
    .pg-ff-fld { min-width:200px; flex:1; }
    .pg-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
    .pg-finput {
        width:100%; padding:0.625rem 0.875rem 0.625rem 2.5rem; border-radius:10px; border:1.5px solid #e2e8f0;
        background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
    }
    .pg-finput:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .pg-finput-ico { position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }
    .pg-fsel {
        padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
        background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
        font-family:inherit; cursor:pointer; appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
    }
    .pg-fsel:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .pg-btn-f {
        padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
        border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; box-shadow:0 4px 12px rgba(79,70,229,0.25);
    }
    .pg-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(79,70,229,0.35); }
    .pg-btn-r {
        padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
        border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
        background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
    }
    .pg-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

    /* Table */
    .pg-tbl-wrap {
        background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
        box-shadow:0 1px 3px rgba(0,0,0,0.04);
    }
    .pg-tbl-head { background:linear-gradient(180deg,#eef2ff,#e0e7ff); border-bottom:2px solid #c7d2fe; }
    .pg-tbl-head th {
        padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
        letter-spacing:0.07em; color:#3730a3; white-space:nowrap;
    }
    .pg-tbl-body td { padding:0.9375rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
    .pg-tbl-body tr { transition:background 0.15s; }
    .pg-tbl-body tr:last-child td { border-bottom:none; }
    .pg-tbl-body tr:hover td { background:linear-gradient(90deg,#fafafe,#eef2ff); }

    /* Code cell */
    .pg-code {
        display:inline-flex; align-items:center; gap:0.375rem;
        font-family:'JetBrains Mono',monospace; font-size:0.75rem; font-weight:600; color:#475569;
        background:#f8fafc; padding:0.375rem 0.625rem; border-radius:8px; border:1px solid #e2e8f0;
    }
    .pg-code-lbl {
        width:22px; height:22px; border-radius:6px; display:flex; align-items:center; justify-content:center;
        font-size:0.625rem; font-weight:700; flex-shrink:0;
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
    }

    /* Sales cell */
    .pg-sales { display:flex; align-items:center; gap:0.75rem; }
    .pg-sales-av {
        width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center;
        font-size:1rem; font-weight:700; flex-shrink:0;
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
        box-shadow:0 4px 12px rgba(79,70,229,0.2);
    }
    .pg-sales-info { display:flex; flex-direction:column; gap:0.125rem; }
    .pg-sales-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
    .pg-sales-sub { font-size:0.6875rem; color:#94a3b8; }

    /* Vehicle plate */
    .pg-plate {
        display:inline-flex; padding:0.25rem 0.5rem; border-radius:6px; font-size:0.75rem; font-weight:700;
        background:#fffbeb; color:#92400e; border:1px solid #fde68a; letter-spacing:0.03em; font-family:'JetBrains Mono',monospace;
    }
    .pg-plate-type { font-size:0.6875rem; color:#94a3b8; margin-top:3px; }

    /* Regional badge */
    .pg-rg-badge { display:inline-flex; align-items:center; gap:0.3rem; padding:0.2rem 0.6rem; border-radius:8px; font-size:0.72rem; font-weight:700; background:#fffbeb; color:#92400e; border:1px solid #fde68a; }
    .pg-rg-badge .dot { width:5px; height:5px; border-radius:50%; background:#f59e0b; }
    .pg-rg-none { color:#94a3b8; font-size:0.8125rem; }

    /* Status badge */
    .pg-status {
        display:inline-flex; align-items:center; gap:0.3rem;
        padding:0.25rem 0.75rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
    }
    .pg-status.aktif { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
    .pg-status.nonaktif { background:#f8fafc; color:#64748b; border-color:#e2e8f0; }
    .pg-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
    .pg-status-dot.aktif { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.2); }
    .pg-status-dot.nonaktif { background:#94a3b8; }

    /* Actions */
    .pg-act-grp { display:flex; gap:0.375rem; align-items:center; justify-content:center; }
    .pg-act {
        display:inline-flex; align-items:center; gap:0.25rem;
        padding:0.375rem 0.625rem; border-radius:8px; font-size:0.6875rem; font-weight:600;
        border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
    }
    .pg-act.edit { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
    .pg-act.edit:hover { background:#d1fae5; }

    /* Empty */
    .pg-empty { text-align:center; padding:3.5rem 1.5rem; }
    .pg-empty-ico {
        width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
        background:linear-gradient(135deg,#eef2ff,#e0e7ff); display:flex; align-items:center; justify-content:center; font-size:2rem;
    }
    .pg-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
    .pg-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
    .pg-empty-cta {
        display:inline-flex; align-items:center; gap:0.5rem;
        padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; text-decoration:none;
        box-shadow:0 4px 14px rgba(79,70,229,0.25); transition:all 0.2s;
    }
    .pg-empty-cta:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(79,70,229,0.35); }

    @media(max-width:1024px) { .pg-kpis { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:768px)  { .pg-kpis { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="page-container pg-page">

    {{-- Header --}}
    <div class="ph">
        <div class="ph-left">
            <div class="ph-icon indigo">👤</div>
            <div>
                <h1 class="ph-title">Data Sales Pasgar</h1>
                <p class="ph-subtitle">Kelola agen penjualan lapangan Pasukan Garuda</p>
            </div>
        </div>
        <div class="ph-actions">
            <a href="{{ route('pasgar.sales.create') }}" class="btn-primary">➕ Tambah Sales Baru</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- KPI Cards --}}
    <div class="pg-kpis">
        <div class="pg-kpi indigo">
            <div class="pg-kpi-top">
                <div class="pg-kpi-left">
                    <span class="pg-kpi-lbl">Total Sales</span>
                    <div><span class="pg-kpi-val indigo">{{ $stats['total'] }}</span></div>
                    <div class="pg-kpi-foot">Semua data sales terdaftar</div>
                </div>
                <div class="pg-kpi-ico indigo">👥</div>
            </div>
        </div>
        <div class="pg-kpi green">
            <div class="pg-kpi-top">
                <div class="pg-kpi-left">
                    <span class="pg-kpi-lbl">Aktif</span>
                    <div><span class="pg-kpi-val green">{{ $stats['aktif'] }}</span></div>
                    <div class="pg-kpi-foot">Sales siap beroperasi</div>
                </div>
                <div class="pg-kpi-ico green">✅</div>
            </div>
        </div>
        <div class="pg-kpi gray">
            <div class="pg-kpi-top">
                <div class="pg-kpi-left">
                    <span class="pg-kpi-lbl">Nonaktif</span>
                    <div><span class="pg-kpi-val gray">{{ $stats['nonaktif'] }}</span></div>
                    <div class="pg-kpi-foot">Sales tidak aktif</div>
                </div>
                <div class="pg-kpi-ico gray">⛔</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="pg-filter">
        <form method="GET" class="pg-ff">
            <div class="pg-ff-fld" style="position:relative;">
                <label class="pg-flbl">Pencarian</label>
                <div style="position:relative;">
                    <svg class="pg-finput-ico" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kode, no HP..." class="pg-finput">
                </div>
            </div>
            <div>
                <label class="pg-flbl">Status</label>
                <select name="status" class="pg-fsel">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="pg-btn-f">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>
            <a href="{{ route('pasgar.sales.index') }}" class="pg-btn-r">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Reset
            </a>
        </form>
    </div>

    {{-- Table --}}
    @if($sales->isEmpty())
        <div class="pg-tbl-wrap">
            <div class="pg-empty">
                <div class="pg-empty-ico">👤</div>
                <div class="pg-empty-title">Belum Ada Data Sales</div>
                <div class="pg-empty-sub">Coba ubah filter atau tambah data sales baru</div>
                <a href="{{ route('pasgar.sales.create') }}" class="pg-empty-cta">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Sales Pertama
                </a>
            </div>
        </div>
    @else
    <div class="pg-tbl-wrap">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:separate; border-spacing:0;">
                <thead class="pg-tbl-head">
                    <tr>
                        <th style="text-align:left;">Kode Sales</th>
                        <th style="text-align:left;">Nama Lengkap</th>
                        <th style="text-align:left;">Kontak</th>
                        <th style="text-align:left;">Kendaraan</th>
                        <th style="text-align:left;">Target Harian</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="pg-tbl-body">
                    @foreach($sales as $s)
                    <tr>
                        <td>
                            <div class="pg-code">
                                <span class="pg-code-lbl">S</span>
                                {{ $s->kode_sales }}
                            </div>
                        </td>
                        <td>
                            <div class="pg-sales">
                                <div class="pg-sales-av">{{ substr($s->nama, 0, 1) }}</div>
                                <div class="pg-sales-info">
                                    <span class="pg-sales-name">{{ $s->nama }}</span>
                                    <span class="pg-sales-sub">{{ $s->alamat ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $s->no_hp ?? '-' }}</td>
                        <td>
                            @if($s->vehicle)
                                <div>
                                    <span class="pg-plate">{{ strtoupper($s->vehicle->license_plate) }}</span>
                                    @if($s->vehicle->type)
                                        <div class="pg-plate-type">{{ $s->vehicle->type }}</div>
                                    @endif
                                </div>
                            @else
                                <span style="color:#94a3b8; font-size:0.8125rem;">-</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-weight:600; color:#4f46e5;">Rp {{ number_format($s->target_harian, 0, ',', '.') }}</span>
                        </td>
                        <td style="text-align:center;">
                            <span class="pg-status {{ $s->status }}">
                                <span class="pg-status-dot {{ $s->status }}"></span>
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <div class="pg-act-grp">
                                <a href="{{ route('pasgar.sales.edit', $s) }}" class="pg-act edit">
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
        <div style="padding:1rem 1.25rem;">
            {{ $sales->links() }}
        </div>
    </div>
    @endif

</div>
@endsection
