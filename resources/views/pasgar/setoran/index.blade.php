@extends('layouts.app', ['title' => 'Verifikasi Setoran - Pasgar'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .si-page { max-width:72rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

    /* Header */
    .si-hdr { background:linear-gradient(135deg,#f59e0b 0%,#d97706 50%,#b45309 100%); border-radius:20px; padding:1.75rem 2rem; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; position:relative; overflow:hidden; }
    .si-hdr::before { content:''; position:absolute; top:-30px; right:-30px; width:120px; height:120px; background:rgba(255,255,255,0.08); border-radius:50%; }
    .si-hdr::after { content:''; position:absolute; bottom:-40px; left:30%; width:180px; height:180px; background:rgba(255,255,255,0.04); border-radius:50%; }
    .si-hdr-left { position:relative; z-index:1; display:flex; align-items:center; gap:1rem; }
    .si-hdr-icon { width:52px; height:52px; border-radius:14px; background:rgba(255,255,255,0.2); backdrop-filter:blur(8px); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .si-hdr-icon svg { width:26px; height:26px; color:#fff; }
    .si-hdr h1 { font-size:1.3rem; font-weight:800; color:#fff; margin:0; }
    .si-hdr p { font-size:0.8rem; color:rgba(255,255,255,0.8); margin:3px 0 0; font-weight:500; }
    .si-hdr-right { position:relative; z-index:1; }
    .si-btn-add { display:inline-flex; align-items:center; gap:0.4rem; padding:0.65rem 1.25rem; border-radius:12px; font-size:0.82rem; font-weight:700; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; background:#fff; color:#92400e; box-shadow:0 4px 14px rgba(0,0,0,0.1); }
    .si-btn-add:hover { box-shadow:0 6px 20px rgba(0,0,0,0.15); transform:translateY(-1px); }
    .si-btn-add svg { width:16px; height:16px; }

    /* Alerts */
    .si-alert { padding:0.75rem 1rem; border-radius:12px; margin-bottom:1rem; font-size:0.82rem; font-weight:600; display:flex; align-items:center; gap:0.5rem; }
    .si-alert svg { width:18px; height:18px; flex-shrink:0; }
    .si-alert-success { background:linear-gradient(135deg,#ecfdf5,#d1fae5); border:1px solid #a7f3d0; color:#065f46; }
    .si-alert-error { background:linear-gradient(135deg,#fef2f2,#fee2e2); border:1px solid #fecaca; color:#991b1b; }

    /* Stats */
    .si-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:0.85rem; margin-bottom:1.5rem; }
    .si-stat { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.25rem; box-shadow:0 1px 4px rgba(0,0,0,0.03); transition:all 0.2s; }
    .si-stat:hover { box-shadow:0 4px 16px rgba(0,0,0,0.06); transform:translateY(-2px); }
    .si-stat-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.6rem; }
    .si-stat-label { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
    .si-stat-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; }
    .si-stat-icon svg { width:18px; height:18px; }
    .si-stat-icon.amber { background:linear-gradient(135deg,#fef3c7,#fde68a); color:#d97706; }
    .si-stat-icon.green { background:linear-gradient(135deg,#d1fae5,#a7f3d0); color:#059669; }
    .si-stat-icon.red { background:linear-gradient(135deg,#fee2e2,#fecaca); color:#dc2626; }
    .si-stat-icon.blue { background:linear-gradient(135deg,#dbeafe,#bfdbfe); color:#2563eb; }
    .si-stat-value { font-size:1.35rem; font-weight:800; color:#0f172a; line-height:1.1; }
    .si-stat-sub { font-size:0.68rem; color:#94a3b8; margin-top:0.25rem; }

    /* Filters */
    .si-filters { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1rem 1.25rem; margin-bottom:1.25rem; box-shadow:0 1px 4px rgba(0,0,0,0.03); }
    .si-filter-row { display:flex; gap:0.65rem; flex-wrap:wrap; align-items:end; }
    .si-fg { display:flex; flex-direction:column; gap:0.3rem; }
    .si-fg label { font-size:0.67rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
    .si-fi { padding:0.55rem 0.8rem; border:1.5px solid #e2e8f0; border-radius:10px; font-family:inherit; font-size:0.8rem; color:#0f172a; outline:none; background:#f8fafc; transition:all 0.2s; min-width:0; }
    .si-fi:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.1); background:#fff; }
    .si-fi-search { min-width:200px; padding-left:2.2rem; }
    .si-search-wrap { position:relative; }
    .si-search-wrap svg { position:absolute; left:0.7rem; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#94a3b8; pointer-events:none; }
    .si-fi-sel { padding:0.55rem 0.8rem; border:1.5px solid #e2e8f0; border-radius:10px; font-family:inherit; font-size:0.8rem; color:#0f172a; outline:none; background:#f8fafc; cursor:pointer; min-width:140px; }
    .si-fi-sel:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.1); background:#fff; }
    .si-filter-btns { display:flex; gap:0.5rem; margin-left:auto; }
    .si-fbtn { display:inline-flex; align-items:center; gap:0.35rem; padding:0.55rem 1rem; border-radius:10px; font-size:0.78rem; font-weight:700; border:none; cursor:pointer; transition:all 0.15s; font-family:inherit; text-decoration:none; }
    .si-fbtn svg { width:15px; height:15px; }
    .si-fbtn-primary { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
    .si-fbtn-primary:hover { box-shadow:0 3px 10px rgba(245,158,11,0.3); }
    .si-fbtn-ghost { background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; }
    .si-fbtn-ghost:hover { background:#e2e8f0; }

    /* Table */
    .si-table-wrap { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.03); }
    .si-table { width:100%; border-collapse:separate; border-spacing:0; }
    .si-table thead { background:linear-gradient(180deg,#fffbeb,#fef3c7); }
    .si-table thead th { padding:0.8rem 1rem; font-size:0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#92400e; white-space:nowrap; text-align:left; border-bottom:2px solid #fde68a; }
    .si-table tbody td { padding:0.85rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
    .si-table tbody tr { transition:background 0.15s; }
    .si-table tbody tr:hover td { background:#fffdf7; }
    .si-table tbody tr:last-child td { border-bottom:none; }
    .si-empty { text-align:center; padding:3rem 2rem; color:#94a3b8; font-size:0.85rem; }
    .si-empty svg { width:48px; height:48px; color:#cbd5e1; margin-bottom:0.75rem; }
    .si-empty p { margin:0; }

    /* Status badges */
    .si-status { display:inline-flex; align-items:center; gap:0.3rem; padding:0.25rem 0.7rem; border-radius:99px; font-size:0.7rem; font-weight:700; border:1px solid; white-space:nowrap; }
    .si-status.pending { background:#fef3c7; color:#92400e; border-color:#fde68a; }
    .si-status.terverifikasi { background:#d1fae5; color:#065f46; border-color:#a7f3d0; }
    .si-status.ditolak { background:#fee2e2; color:#dc2626; border-color:#fecaca; }
    .si-status svg { width:12px; height:12px; }

    .si-selisih { display:inline-flex; align-items:center; gap:0.25rem; padding:0.2rem 0.6rem; border-radius:8px; font-size:0.72rem; font-weight:700; }
    .si-selisih.pas { background:#d1fae5; color:#059669; }
    .si-selisih.lebih { background:#dbeafe; color:#1d4ed8; }
    .si-selisih.kurang { background:#fee2e2; color:#dc2626; }

    .si-td-main { font-weight:600; color:#1e293b; }
    .si-td-money { font-weight:800; font-family:'JetBrains Mono',monospace; font-size:0.78rem; }
    .si-td-sub { font-size:0.72rem; color:#94a3b8; margin-top:1px; }

    .si-actions { display:flex; gap:0.35rem; }
    .si-act { padding:0.3rem 0.7rem; border-radius:8px; font-size:0.7rem; font-weight:700; text-decoration:none; transition:all 0.15s; border:1px solid; display:inline-flex; align-items:center; gap:0.25rem; white-space:nowrap; }
    .si-act svg { width:13px; height:13px; }
    .si-act-view { background:#eef2ff; color:#4f46e5; border-color:#c7d2fe; }
    .si-act-view:hover { background:#e0e7ff; }
    .si-act-edit { background:#fef3c7; color:#92400e; border-color:#fde68a; }
    .si-act-edit:hover { background:#fde68a; }

    .si-pagination { padding:0.75rem 1rem; display:flex; justify-content:center; border-top:1px solid #f1f5f9; }

    @media(max-width:1024px) { .si-stats { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:768px) { .si-table-wrap { overflow-x:auto; } .si-hdr { flex-wrap:wrap; } .si-hdr-right { width:100%; } .si-filter-row { flex-direction:column; } .si-fi-search { min-width:100%; } .si-filter-btns { margin-left:0; width:100%; justify-content:flex-end; } .si-stats { grid-template-columns:1fr 1fr; } }
    @media(max-width:480px) { .si-stats { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="si-page">
    {{-- Header Banner --}}
    <div class="si-hdr">
        <div class="si-hdr-left">
            <div class="si-hdr-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div>
                <h1>Verifikasi Setoran</h1>
                <p>Monitoring dan verifikasi setoran dari sales lapangan</p>
            </div>
        </div>
        <div class="si-hdr-right">
            <a href="{{ route('pasgar.setoran.create') }}" class="si-btn-add">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Setoran
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="si-alert si-alert-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="si-alert si-alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="si-stats">
        <div class="si-stat">
            <div class="si-stat-top">
                <div class="si-stat-label">Pending Verifikasi</div>
                <div class="si-stat-icon amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
            </div>
            <div class="si-stat-value">{{ $stats['total_pending'] }}</div>
            <div class="si-stat-sub">menunggu verifikasi</div>
        </div>
        <div class="si-stat">
            <div class="si-stat-top">
                <div class="si-stat-label">Terverifikasi</div>
                <div class="si-stat-icon green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
            </div>
            <div class="si-stat-value">{{ $stats['total_terverifikasi'] }}</div>
            <div class="si-stat-sub">setoran terverifikasi</div>
        </div>
        <div class="si-stat">
            <div class="si-stat-top">
                <div class="si-stat-label">Ditolak</div>
                <div class="si-stat-icon red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
            </div>
            <div class="si-stat-value">{{ $stats['total_ditolak'] }}</div>
            <div class="si-stat-sub">setoran ditolak</div>
        </div>
        <div class="si-stat">
            <div class="si-stat-top">
                <div class="si-stat-label">Setoran Hari Ini</div>
                <div class="si-stat-icon blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
            </div>
            <div class="si-stat-value">Rp {{ number_format($stats['setoran_hari_ini'], 0, ',', '.') }}</div>
            <div class="si-stat-sub">total setor hari ini</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="si-filters">
        <form method="GET" action="{{ route('pasgar.setoran.index') }}">
            <div class="si-filter-row">
                <div class="si-fg">
                    <label>Cari Sales</label>
                    <div class="si-search-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" name="search" class="si-fi si-fi-search" placeholder="Cari nama sales..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="si-fg">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="si-fi" value="{{ request('tanggal') }}">
                </div>
                @if(!$isSalesRole)
                <div class="si-fg">
                    <label>Sales</label>
                    <select name="sales_id" class="si-fi-sel">
                        <option value="">Semua Sales</option>
                        @foreach($sales as $s)
                            <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="si-fg">
                    <label>Status</label>
                    <select name="status" class="si-fi-sel">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="terverifikasi" {{ request('status') === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="si-filter-btns">
                    <button type="submit" class="si-fbtn si-fbtn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('pasgar.setoran.index') }}" class="si-fbtn si-fbtn-ghost">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="si-table-wrap">
        <div style="overflow-x: auto; margin-bottom: 1rem;">
<table class="si-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Sales</th>
                    <th style="text-align:right;">Total Setor</th>
                    <th style="text-align:right;">Penjualan</th>
                    <th style="text-align:center;">Selisih</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($setorans as $s)
                <tr>
                    <td>
                        <div class="si-td-main">{{ $s->tanggal->format('d M Y') }}</div>
                        <div class="si-td-sub">{{ $s->nomor_setoran }}</div>
                    </td>
                    <td>
                        <div class="si-td-main">{{ $s->sales->nama ?? '-' }}</div>
                        <div class="si-td-sub">{{ $s->loading->nomor_loading ?? '-' }}</div>
                    </td>
                    <td style="text-align:right;">
                        <span class="si-td-money" style="color:#4f46e5;">Rp {{ number_format($s->total_setor, 0, ',', '.') }}</span>
                    </td>
                    <td style="text-align:right;">
                        <span class="si-td-money" style="color:#374151;">Rp {{ number_format($s->total_penjualan, 0, ',', '.') }}</span>
                    </td>
                    <td style="text-align:center;">
                        @if($s->selisih == 0)
                            <span class="si-selisih pas">Pas</span>
                        @elseif($s->selisih > 0)
                            <span class="si-selisih lebih">+Rp {{ number_format($s->selisih, 0, ',', '.') }}</span>
                        @else
                            <span class="si-selisih kurang">-Rp {{ number_format(abs($s->selisih), 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($s->status === 'pending')
                        <span class="si-status pending">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Pending
                        </span>
                        @elseif($s->status === 'terverifikasi')
                        <span class="si-status terverifikasi">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Terverifikasi
                        </span>
                        @else
                        <span class="si-status ditolak">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Ditolak
                        </span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <div class="si-actions" style="justify-content:flex-end;">
                            <a href="{{ route('pasgar.setoran.show', $s->id) }}" class="si-act si-act-view">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Detail
                            </a>
                            @if($s->status === 'pending')
                            <a href="{{ route('pasgar.setoran.edit', $s->id) }}" class="si-act si-act-edit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="si-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                        <p>Belum ada data setoran</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
</div>
        @if($setorans->hasPages())
        <div class="si-pagination">{{ $setorans->links() }}</div>
        @endif
    </div>
</div>
@endsection
