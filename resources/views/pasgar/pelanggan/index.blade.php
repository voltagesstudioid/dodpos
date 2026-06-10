@extends('layouts.app', ['title' => 'Data Pelanggan Pasgar'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .pl-page { font-family:'Plus Jakarta Sans',sans-serif; }

    /* KPI Cards */
    .pl-kpis { display:grid; grid-template-columns:repeat(5,1fr); gap:0.75rem; margin-bottom:1.5rem; }
    .pl-kpi {
        background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:1.125rem 1.25rem;
        transition:all 0.3s; position:relative; overflow:hidden;
    }
    .pl-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:3px; }
    .pl-kpi:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,0.06); border-color:transparent; }
    .pl-kpi.indigo::before { background:linear-gradient(180deg,#6366f1,#4f46e5); }
    .pl-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
    .pl-kpi.blue::before   { background:linear-gradient(180deg,#3b82f6,#2563eb); }
    .pl-kpi.purple::before { background:linear-gradient(180deg,#8b5cf6,#7c3aed); }
    .pl-kpi.amber::before  { background:linear-gradient(180deg,#f59e0b,#d97706); }
    .pl-kpi-top { display:flex; align-items:center; justify-content:space-between; }
    .pl-kpi-left { display:flex; flex-direction:column; gap:0.125rem; }
    .pl-kpi-lbl { font-size:0.625rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
    .pl-kpi-val { font-size:1.75rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
    .pl-kpi-val.indigo { color:#4f46e5; }
    .pl-kpi-val.green  { color:#059669; }
    .pl-kpi-val.blue   { color:#2563eb; }
    .pl-kpi-val.purple { color:#7c3aed; }
    .pl-kpi-val.amber  { color:#d97706; }
    .pl-kpi-ico { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; }
    .pl-kpi-ico.indigo { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
    .pl-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
    .pl-kpi-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
    .pl-kpi-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
    .pl-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }

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
    .pl-finput:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .pl-finput-ico { position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }
    .pl-fsel {
        padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
        background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
        font-family:inherit; cursor:pointer; appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
    }
    .pl-fsel:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .pl-btn-f {
        padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
        border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; box-shadow:0 4px 12px rgba(79,70,229,0.25);
    }
    .pl-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(79,70,229,0.35); }
    .pl-btn-r {
        padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
        border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
        background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
    }
    .pl-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

    /* Table */
    .pl-tbl-wrap {
        background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
        box-shadow:0 1px 3px rgba(0,0,0,0.04);
    }
    .pl-tbl-head { background:linear-gradient(180deg,#eef2ff,#e0e7ff); border-bottom:2px solid #c7d2fe; }
    .pl-tbl-head th {
        padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
        letter-spacing:0.07em; color:#3730a3; white-space:nowrap;
    }
    .pl-tbl-body td { padding:0.9375rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
    .pl-tbl-body tr { transition:background 0.15s; }
    .pl-tbl-body tr:last-child td { border-bottom:none; }
    .pl-tbl-body tr:hover td { background:linear-gradient(90deg,#fafafe,#eef2ff); }

    /* Code cell */
    .pl-code {
        display:inline-flex; align-items:center; gap:0.375rem;
        font-family:'JetBrains Mono',monospace; font-size:0.75rem; font-weight:600; color:#475569;
        background:#f8fafc; padding:0.375rem 0.625rem; border-radius:8px; border:1px solid #e2e8f0;
    }
    .pl-code-lbl {
        width:22px; height:22px; border-radius:6px; display:flex; align-items:center; justify-content:center;
        font-size:0.625rem; font-weight:700; flex-shrink:0;
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
    }

    /* Store cell */
    .pl-store { display:flex; align-items:center; gap:0.75rem; }
    .pl-store-av {
        width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center;
        font-size:0.875rem; flex-shrink:0;
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
        box-shadow:0 3px 10px rgba(79,70,229,0.2);
    }
    .pl-store-info { display:flex; flex-direction:column; gap:0.125rem; }
    .pl-store-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
    .pl-store-sub { font-size:0.6875rem; color:#94a3b8; }

    /* Tipe badge */
    .pl-tipe {
        display:inline-flex; align-items:center; gap:0.25rem;
        padding:0.2rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
    }
    .pl-tipe.warung { background:#fffbeb; color:#92400e; border-color:#fde68a; }
    .pl-tipe.toko   { background:#eff6ff; color:#1e40af; border-color:#bfdbfe; }
    .pl-tipe.kios   { background:#f5f3ff; color:#6d28d9; border-color:#ddd6fe; }

    /* Status badge */
    .pl-status {
        display:inline-flex; align-items:center; gap:0.3rem;
        padding:0.25rem 0.75rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
    }
    .pl-status.aktif    { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
    .pl-status.nonaktif { background:#f8fafc; color:#64748b; border-color:#e2e8f0; }
    .pl-status.blacklist{ background:#fff1f2; color:#be123c; border-color:#fecdd3; }
    .pl-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
    .pl-status-dot.aktif    { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.2); }
    .pl-status-dot.nonaktif { background:#94a3b8; }
    .pl-status-dot.blacklist{ background:#ef4444; box-shadow:0 0 0 2px rgba(239,68,68,0.2); }

    /* Actions */
    .pl-act-grp { display:flex; gap:0.375rem; align-items:center; justify-content:center; }
    .pl-act {
        display:inline-flex; align-items:center; gap:0.25rem;
        padding:0.375rem 0.625rem; border-radius:8px; font-size:0.6875rem; font-weight:600;
        border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
    }
    .pl-act.detail { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
    .pl-act.detail:hover { background:#dbeafe; border-color:#93c5fd; }
    .pl-act.edit { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
    .pl-act.edit:hover { background:#d1fae5; }
    .pl-act.del { background:#fff1f2; color:#be123c; border-color:#fecdd3; }
    .pl-act.del:hover { background:#ffe4e6; border-color:#fda4af; }

    /* Empty */
    .pl-empty { text-align:center; padding:3.5rem 1.5rem; }
    .pl-empty-ico {
        width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
        background:linear-gradient(135deg,#eef2ff,#e0e7ff); display:flex; align-items:center; justify-content:center; font-size:2rem;
    }
    .pl-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
    .pl-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
    .pl-empty-cta {
        display:inline-flex; align-items:center; gap:0.5rem;
        padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; text-decoration:none;
        box-shadow:0 4px 14px rgba(79,70,229,0.25); transition:all 0.2s;
    }
    .pl-empty-cta:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(79,70,229,0.35); }

    @media(max-width:1024px) { .pl-kpis { grid-template-columns:repeat(3,1fr); } }
    @media(max-width:768px)  { .pl-kpis { grid-template-columns:repeat(2,1fr); } }
</style>
@endpush

@section('content')
<div class="page-container pl-page">

    {{-- Header --}}
    <div class="ph">
        <div class="ph-left">
            <div class="ph-icon indigo">🏪</div>
            <div>
                <h1 class="ph-title">Data Pelanggan</h1>
                <p class="ph-subtitle">Kelola data pelanggan Pasukan Garuda</p>
            </div>
        </div>
        <div class="ph-actions">
            <a href="{{ route('pasgar.pelanggan.create') }}" class="btn-primary">➕ Tambah Pelanggan</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- KPI Cards --}}
    <div class="pl-kpis">
        <div class="pl-kpi indigo">
            <div class="pl-kpi-top">
                <div class="pl-kpi-left">
                    <span class="pl-kpi-lbl">Total</span>
                    <div><span class="pl-kpi-val indigo">{{ $stats['total'] }}</span></div>
                </div>
                <div class="pl-kpi-ico indigo">🏪</div>
            </div>
        </div>
        <div class="pl-kpi green">
            <div class="pl-kpi-top">
                <div class="pl-kpi-left">
                    <span class="pl-kpi-lbl">Aktif</span>
                    <div><span class="pl-kpi-val green">{{ $stats['aktif'] }}</span></div>
                </div>
                <div class="pl-kpi-ico green">✅</div>
            </div>
        </div>
        <div class="pl-kpi blue">
            <div class="pl-kpi-top">
                <div class="pl-kpi-left">
                    <span class="pl-kpi-lbl">Warung</span>
                    <div><span class="pl-kpi-val blue">{{ $stats['warung'] }}</span></div>
                </div>
                <div class="pl-kpi-ico blue">🏠</div>
            </div>
        </div>
        <div class="pl-kpi purple">
            <div class="pl-kpi-top">
                <div class="pl-kpi-left">
                    <span class="pl-kpi-lbl">Toko</span>
                    <div><span class="pl-kpi-val purple">{{ $stats['toko'] }}</span></div>
                </div>
                <div class="pl-kpi-ico purple">🏬</div>
            </div>
        </div>
        <div class="pl-kpi amber">
            <div class="pl-kpi-top">
                <div class="pl-kpi-left">
                    <span class="pl-kpi-lbl">Kios</span>
                    <div><span class="pl-kpi-val amber">{{ $stats['kios'] }}</span></div>
                </div>
                <div class="pl-kpi-ico amber">🏪</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="pl-filter">
        <form method="GET" class="pl-ff">
            <div class="pl-ff-fld" style="position:relative;">
                <label class="pl-flbl">Pencarian</label>
                <div style="position:relative;">
                    <svg class="pl-finput-ico" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode, nama toko, pemilik, HP..." class="pl-finput">
                </div>
            </div>
            <div>
                <label class="pl-flbl">Tipe</label>
                <select name="tipe" class="pl-fsel">
                    <option value="">Semua Tipe</option>
                    <option value="warung" {{ request('tipe') === 'warung' ? 'selected' : '' }}>Warung</option>
                    <option value="toko" {{ request('tipe') === 'toko' ? 'selected' : '' }}>Toko</option>
                    <option value="kios" {{ request('tipe') === 'kios' ? 'selected' : '' }}>Kios</option>
                </select>
            </div>
            <div>
                <label class="pl-flbl">Status</label>
                <select name="status" class="pl-fsel">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="blacklist" {{ request('status') === 'blacklist' ? 'selected' : '' }}>Blacklist</option>
                </select>
            </div>
            <button type="submit" class="pl-btn-f">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>
            <a href="{{ route('pasgar.pelanggan.index') }}" class="pl-btn-r">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Reset
            </a>
        </form>
    </div>

    {{-- Table --}}
    @if($pelanggans->isEmpty())
        <div class="pl-tbl-wrap">
            <div class="pl-empty">
                <div class="pl-empty-ico">🏪</div>
                <div class="pl-empty-title">Belum Ada Data Pelanggan</div>
                <div class="pl-empty-sub">Coba ubah filter atau tambah pelanggan baru</div>
                <a href="{{ route('pasgar.pelanggan.create') }}" class="pl-empty-cta">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pelanggan Pertama
                </a>
            </div>
        </div>
    @else
        <div class="pl-tbl-wrap">
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:separate; border-spacing:0;">
                    <thead class="pl-tbl-head">
                        <tr>
                            <th style="text-align:left;">Kode</th>
                            <th style="text-align:left;">Nama Toko / Pemilik</th>
                            <th style="text-align:left;">Kontak</th>
                            <th style="text-align:center;">Tipe</th>
                            <th style="text-align:left;">Kota</th>
                            <th style="text-align:center;">Status</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="pl-tbl-body">
                        @foreach($pelanggans as $p)
                        <tr>
                            <td>
                                <div class="pl-code">
                                    <span class="pl-code-lbl">P</span>
                                    {{ $p->kode_pelanggan }}
                                </div>
                            </td>
                            <td>
                                <div class="pl-store">
                                    <div class="pl-store-av">{{ substr($p->nama_toko, 0, 1) }}</div>
                                    <div class="pl-store-info">
                                        <span class="pl-store-name">{{ $p->nama_toko }}</span>
                                        <span class="pl-store-sub">{{ $p->nama_pemilik }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $p->no_hp ?? '-' }}</td>
                            <td style="text-align:center;">
                                <span class="pl-tipe {{ $p->tipe }}">{{ ucfirst($p->tipe) }}</span>
                            </td>
                            <td>{{ $p->kota ?? '-' }}</td>
                            <td style="text-align:center;">
                                <span class="pl-status {{ $p->status }}">
                                    <span class="pl-status-dot {{ $p->status }}"></span>
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <div class="pl-act-grp">
                                    <a href="{{ route('pasgar.pelanggan.show', $p->id) }}" class="pl-act detail">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('pasgar.pelanggan.edit', $p->id) }}" class="pl-act edit">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    @if(!$isSalesRole)
                                    <form action="{{ route('pasgar.pelanggan.destroy', $p->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus pelanggan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="pl-act del">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">
                {{ $pelanggans->links() }}
            </div>
        </div>
    @endif

</div>
@endsection
