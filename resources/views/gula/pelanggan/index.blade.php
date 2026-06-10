@extends('layouts.app')

@section('title', 'Data Pelanggan')
@section('page-title', 'Pelanggan Gula')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&display=swap');
    .gp-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

    .gp-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.75rem; padding-bottom:1.25rem; border-bottom:2px solid #fef3c7; }
    .gp-hdr-left { display:flex; align-items:center; gap:1rem; }
    .gp-hdr-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:linear-gradient(135deg,#f59e0b,#d97706); box-shadow:0 8px 24px rgba(245,158,11,0.3); }
    .gp-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
    .gp-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
    .gp-hdr-btn { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; border-radius:12px; font-size:0.8125rem; font-weight:700; text-decoration:none; transition:all 0.25s; background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 4px 14px rgba(245,158,11,0.35); border:none; cursor:pointer; }
    .gp-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(245,158,11,0.45); }

    .gp-kpis { display:grid; grid-template-columns:repeat(5,1fr); gap:1rem; margin-bottom:1rem; }
    .gp-kpi { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.375rem; transition:all 0.3s; position:relative; overflow:hidden; }
    .gp-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
    .gp-kpi:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,0.08); border-color:transparent; }
    .gp-kpi.blue::before { background:linear-gradient(180deg,#3b82f6,#2563eb); }
    .gp-kpi.green::before { background:linear-gradient(180deg,#10b981,#059669); }
    .gp-kpi.purple::before { background:linear-gradient(180deg,#8b5cf6,#7c3aed); }
    .gp-kpi.amber::before { background:linear-gradient(180deg,#f59e0b,#d97706); }
    .gp-kpi.red::before { background:linear-gradient(180deg,#ef4444,#dc2626); }
    .gp-kpi-top { display:flex; align-items:flex-start; justify-content:space-between; }
    .gp-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
    .gp-kpi-val { font-size:1.75rem; font-weight:800; letter-spacing:-0.03em; line-height:1.1; margin-top:0.5rem; }
    .gp-kpi-val.blue { color:#2563eb; }
    .gp-kpi-val.green { color:#059669; }
    .gp-kpi-val.purple { color:#7c3aed; }
    .gp-kpi-val.amber { color:#b45309; }
    .gp-kpi-val.red { color:#dc2626; }
    .gp-kpi-ico { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .gp-kpi-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
    .gp-kpi-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
    .gp-kpi-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
    .gp-kpi-ico.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
    .gp-kpi-ico.red { background:linear-gradient(135deg,#fef2f2,#fee2e2); }

    .gp-hutang { background:linear-gradient(135deg,#fef2f2,#fee2e2); border:1px solid #fecaca; border-radius:14px; padding:1rem 1.5rem; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
    .gp-hutang-left { display:flex; align-items:center; gap:1rem; }
    .gp-hutang-ico { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:linear-gradient(135deg,#fecaca,#fca5a5); }
    .gp-hutang-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#b91c1c; }
    .gp-hutang-val { font-size:1.5rem; font-weight:800; color:#991b1b; letter-spacing:-0.02em; font-family:'JetBrains Mono',monospace; }
    .gp-hutang-link { font-size:0.8125rem; font-weight:700; color:#dc2626; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem; transition:color 0.2s; }
    .gp-hutang-link:hover { color:#991b1b; }

    .gp-filter { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.5rem; margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
    .gp-filter-form { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
    .gp-filter-group { flex:1; min-width:220px; }
    .gp-filter-label { display:block; font-size:0.75rem; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem; }
    .gp-filter-input { width:100%; border:1px solid #e2e8f0; border-radius:10px; padding:0.625rem 0.875rem 0.625rem 2.5rem; font-size:0.875rem; font-family:inherit; color:#1e293b; background:#f8fafc; transition:all 0.2s; }
    .gp-filter-input:focus { outline:none; border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
    .gp-filter-search-wrap { position:relative; }
    .gp-filter-search-icon { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; }
    .gp-filter-select { border:1px solid #e2e8f0; border-radius:10px; padding:0.625rem 0.875rem; font-size:0.875rem; font-family:inherit; color:#1e293b; background:#f8fafc; transition:all 0.2s; }
    .gp-filter-select:focus { outline:none; border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
    .gp-filter-btn { padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:700; border:none; cursor:pointer; transition:all 0.2s; font-family:inherit; }
    .gp-filter-btn.primary { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 4px 12px rgba(245,158,11,0.25); }
    .gp-filter-btn.primary:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(245,158,11,0.35); }
    .gp-filter-btn.ghost { background:#f8fafc; color:#64748b; border:1px solid #e2e8f0; }
    .gp-filter-btn.ghost:hover { background:#f1f5f9; }

    .gp-table-wrap { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
    .gp-table-scroll { overflow-x:auto; }
    .gp-table { width:100%; border-collapse:collapse; }
    .gp-table thead th { background:linear-gradient(180deg,#fffbeb,#fef9ee); border-bottom:2px solid #fde68a; padding:0.875rem 1.25rem; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#92400e; }
    .gp-table thead th.left { text-align:left; }
    .gp-table thead th.center { text-align:center; }
    .gp-table thead th.right { text-align:right; }
    .gp-table tbody td { padding:1rem 1.25rem; border-bottom:1px solid #f8fafc; vertical-align:middle; }
    .gp-table tbody tr { transition:background 0.15s; }
    .gp-table tbody tr:hover { background:#fffdf7; }
    .gp-table tbody tr:last-child td { border-bottom:none; }

    .gp-code { font-family:'JetBrains Mono',monospace; font-size:0.8125rem; font-weight:700; color:#b45309; background:#fffbeb; padding:0.25rem 0.5rem; border-radius:6px; border:1px solid #fde68a; display:inline-block; }
    .gp-pelanggan-cell { display:flex; align-items:center; gap:0.75rem; }
    .gp-pelanggan-av { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:800; flex-shrink:0; background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
    .gp-pelanggan-name { font-size:0.875rem; font-weight:600; color:#1e293b; }
    .gp-pelanggan-owner { font-size:0.75rem; color:#94a3b8; margin-top:1px; }
    .gp-contact { font-size:0.8125rem; color:#1e293b; display:flex; align-items:center; gap:0.375rem; }
    .gp-contact-icon { color:#10b981; flex-shrink:0; }
    .gp-location { font-size:0.8125rem; color:#1e293b; display:flex; align-items:center; gap:0.375rem; }
    .gp-location-icon { color:#94a3b8; flex-shrink:0; }
    .gp-gps-tag { font-size:0.6875rem; color:#2563eb; font-weight:600; margin-top:2px; display:flex; align-items:center; gap:3px; }

    .gp-tipe { display:inline-flex; align-items:center; gap:0.375rem; padding:0.375rem 0.75rem; border-radius:8px; font-size:0.75rem; font-weight:700; }
    .gp-tipe.eceran { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
    .gp-tipe.grosir { background:#f5f3ff; color:#6d28d9; border:1px solid #ddd6fe; }
    .gp-tipe.agen { background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; }

    .gp-status { display:inline-flex; align-items:center; gap:0.375rem; padding:0.375rem 0.75rem; border-radius:99px; font-size:0.75rem; font-weight:700; }
    .gp-status-dot { width:8px; height:8px; border-radius:50%; }
    .gp-status.aktif { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
    .gp-status.aktif .gp-status-dot { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.2); animation:gp-pulse 1.5s infinite; }
    .gp-status.nonaktif { background:#f8fafc; color:#64748b; border:1px solid #e2e8f0; }
    .gp-status.nonaktif .gp-status-dot { background:#94a3b8; }
    .gp-status.blacklist { background:#fff1f2; color:#e11d48; border:1px solid #fecdd3; }
    .gp-status.blacklist .gp-status-dot { background:#e11d48; }
    @keyframes gp-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

    .gp-hutang-cell { font-family:'JetBrains Mono',monospace; font-size:0.8125rem; font-weight:700; }
    .gp-hutang-cell.has-debt { color:#dc2626; background:#fef2f2; padding:0.25rem 0.625rem; border-radius:8px; display:inline-block; }
    .gp-hutang-cell.no-debt { color:#94a3b8; }

    .gp-actions { display:flex; align-items:center; justify-content:center; gap:0.375rem; }
    .gp-act { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; transition:all 0.2s; border:1px solid transparent; }
    .gp-act.detail { background:#eff6ff; color:#2563eb; border-color:#bfdbfe; }
    .gp-act.detail:hover { background:#dbeafe; }
    .gp-act.edit { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
    .gp-act.edit:hover { background:#d1fae5; }
    .gp-act.delete { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
    .gp-act.delete:hover { background:#fee2e2; }

    .gp-empty { text-align:center; padding:3.5rem 1rem; }
    .gp-empty-ico { width:72px; height:72px; margin:0 auto 1rem; border-radius:50%; background:linear-gradient(135deg,#fffbeb,#fef3c7); display:flex; align-items:center; justify-content:center; }
    .gp-empty-title { font-size:1rem; font-weight:700; color:#475569; }
    .gp-empty-sub { font-size:0.875rem; color:#94a3b8; margin-top:0.25rem; }
    .gp-empty-btn { display:inline-flex; align-items:center; gap:0.5rem; margin-top:1rem; padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:700; text-decoration:none; background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 4px 12px rgba(245,158,11,0.25); transition:all 0.25s; }
    .gp-empty-btn:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(245,158,11,0.35); }

    .gp-pagination { padding:1rem 1.25rem; border-top:1px solid #f1f5f9; }

    @media(max-width:1024px) { .gp-kpis { grid-template-columns:repeat(3,1fr); } }
    @media(max-width:640px) { .gp-kpis { grid-template-columns:1fr 1fr; } .gp-hdr-title { font-size:1.25rem; } }
</style>
@endpush

@section('content')
<div class="gp-page">

    {{-- Header --}}
    <div class="gp-hdr">
        <div class="gp-hdr-left">
            <div class="gp-hdr-ico">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div class="gp-hdr-title">Data Pelanggan Gula</div>
                <div class="gp-hdr-sub">Kelola data pelanggan divisi gula</div>
            </div>
        </div>
        <a href="{{ route('gula.pelanggan.create') }}" class="gp-hdr-btn">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pelanggan
        </a>
    </div>

    {{-- KPI Cards --}}
    <div class="gp-kpis">
        <div class="gp-kpi blue">
            <div class="gp-kpi-top">
                <div>
                    <div class="gp-kpi-lbl">Total Pelanggan</div>
                    <div class="gp-kpi-val blue">{{ number_format($stats['total']) }}</div>
                </div>
                <div class="gp-kpi-ico blue">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
            </div>
        </div>
        <div class="gp-kpi green">
            <div class="gp-kpi-top">
                <div>
                    <div class="gp-kpi-lbl">Aktif</div>
                    <div class="gp-kpi-val green">{{ number_format($stats['aktif']) }}</div>
                </div>
                <div class="gp-kpi-ico green">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
            </div>
        </div>
        <div class="gp-kpi purple">
            <div class="gp-kpi-top">
                <div>
                    <div class="gp-kpi-lbl">Eceran</div>
                    <div class="gp-kpi-val purple">{{ number_format($stats['eceran']) }}</div>
                </div>
                <div class="gp-kpi-ico purple">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </div>
            </div>
        </div>
        <div class="gp-kpi amber">
            <div class="gp-kpi-top">
                <div>
                    <div class="gp-kpi-lbl">Grosir</div>
                    <div class="gp-kpi-val amber">{{ number_format($stats['grosir']) }}</div>
                </div>
                <div class="gp-kpi-ico amber">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
            </div>
        </div>
        <div class="gp-kpi red">
            <div class="gp-kpi-top">
                <div>
                    <div class="gp-kpi-lbl">Total Hutang</div>
                    <div class="gp-kpi-val red" style="font-family:'JetBrains Mono',monospace; font-size:1.125rem;">Rp {{ number_format($stats['total_hutang'], 0, ',', '.') }}</div>
                </div>
                <div class="gp-kpi-ico red">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Hutang Banner --}}
    @if($stats['total_hutang'] > 0)
    <div class="gp-hutang">
        <div class="gp-hutang-left">
            <div class="gp-hutang-ico">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div>
                <div class="gp-hutang-lbl">Total Hutang Pelanggan</div>
                <div class="gp-hutang-val">Rp {{ number_format($stats['total_hutang'], 0, ',', '.') }}</div>
            </div>
        </div>
        <a href="{{ route('gula.hutang.index') }}" class="gp-hutang-link">
            Lihat Detail
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    @endif

    {{-- Filter --}}
    <div class="gp-filter">
        <form method="GET" class="gp-filter-form">
            <div class="gp-filter-group">
                <label class="gp-filter-label">Cari</label>
                <div class="gp-filter-search-wrap">
                    <svg class="gp-filter-search-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama toko, pemilik, atau no HP..." class="gp-filter-input">
                </div>
            </div>
            <div>
                <label class="gp-filter-label">Tipe</label>
                <select name="tipe" class="gp-filter-select">
                    <option value="">Semua Tipe</option>
                    <option value="eceran" {{ request('tipe') == 'eceran' ? 'selected' : '' }}>Eceran</option>
                    <option value="grosir" {{ request('tipe') == 'grosir' ? 'selected' : '' }}>Grosir</option>
                    <option value="agen" {{ request('tipe') == 'agen' ? 'selected' : '' }}>Agen</option>
                </select>
            </div>
            <div>
                <label class="gp-filter-label">Status</label>
                <select name="status" class="gp-filter-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="blacklist" {{ request('status') == 'blacklist' ? 'selected' : '' }}>Blacklist</option>
                </select>
            </div>
            <button type="submit" class="gp-filter-btn primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>
            <a href="{{ route('gula.pelanggan.index') }}" class="gp-filter-btn ghost">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="gp-table-wrap">
        <div class="gp-table-scroll">
            <table class="gp-table">
                <thead>
                    <tr>
                        <th class="left">Kode</th>
                        <th class="left">Pelanggan</th>
                        <th class="left">Kontak</th>
                        <th class="left">Lokasi</th>
                        <th class="center">Tipe</th>
                        <th class="center">Status</th>
                        <th class="right">Hutang</th>
                        <th class="center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggans as $p)
                        <tr>
                            <td><span class="gp-code">{{ $p->kode_pelanggan }}</span></td>
                            <td>
                                <div class="gp-pelanggan-cell">
                                    <div class="gp-pelanggan-av">{{ strtoupper(substr($p->nama_toko, 0, 2)) }}</div>
                                    <div>
                                        <div class="gp-pelanggan-name">{{ $p->nama_toko }}</div>
                                        <div class="gp-pelanggan-owner">{{ $p->nama_pemilik }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($p->no_hp)
                                    <span class="gp-contact">
                                        <svg class="gp-contact-icon" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        {{ $p->no_hp }}
                                    </span>
                                @else
                                    <span style="color:#94a3b8; font-size:0.8125rem;">-</span>
                                @endif
                            </td>
                            <td>
                                @if($p->kecamatan || $p->kota)
                                    <span class="gp-location">
                                        <svg class="gp-location-icon" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $p->kecamatan }}{{ ($p->kecamatan && $p->kota) ? ', ' : '' }}{{ $p->kota }}
                                    </span>
                                    @if($p->latitude && $p->longitude)
                                        <div class="gp-gps-tag">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                            GPS tersedia
                                        </div>
                                    @endif
                                @else
                                    <span style="color:#94a3b8; font-size:0.8125rem;">Belum diisi</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <span class="gp-tipe {{ $p->tipe }}">
                                    @if($p->tipe == 'eceran')
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                    @elseif($p->tipe == 'grosir')
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                    @elseif($p->tipe == 'agen')
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    @endif
                                    {{ ucfirst($p->tipe) }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <span class="gp-status {{ $p->status }}">
                                    <span class="gp-status-dot"></span>
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td style="text-align:right;">
                                @if($p->total_hutang > 0)
                                    <span class="gp-hutang-cell has-debt">Rp {{ number_format($p->total_hutang, 0, ',', '.') }}</span>
                                @else
                                    <span class="gp-hutang-cell no-debt">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="gp-actions">
                                    <a href="{{ route('gula.pelanggan.show', $p) }}" class="gp-act detail" title="Detail">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('gula.pelanggan.edit', $p) }}" class="gp-act edit" title="Edit">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('gula.pelanggan.destroy', $p) }}" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="gp-act delete" title="Hapus">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="gp-empty">
                                    <div class="gp-empty-ico">
                                        <svg width="32" height="32" fill="none" stroke="#b45309" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <div class="gp-empty-title">Tidak ada data pelanggan</div>
                                    <div class="gp-empty-sub">Coba ubah filter atau tambah pelanggan baru</div>
                                    <a href="{{ route('gula.pelanggan.create') }}" class="gp-empty-btn">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Tambah Pelanggan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pelanggans->hasPages())
            <div class="gp-pagination">
                {{ $pelanggans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
