@extends('layouts.app', ['title' => 'Penjualan Lapangan Pasgar'])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .pj-page { font-family:'Plus Jakarta Sans',sans-serif; max-width:56rem; margin:0 auto; padding:1.25rem 1rem; }
    .pj-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem; flex-wrap:wrap; }
    .pj-hdr-icon { width:48px; height:48px; border-radius:13px; background:linear-gradient(135deg,#10b981,#059669); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(16,185,129,0.25); flex-shrink:0; }
    .pj-hdr-icon svg { width:24px; height:24px; stroke:#fff; fill:none; }
    .pj-hdr h1 { font-size:1.25rem; font-weight:800; color:#1e1b4b; margin:0; }
    .pj-hdr p { font-size:0.78rem; color:#10b981; margin:2px 0 0; font-weight:600; }
    .pj-hdr-actions { margin-left:auto; display:flex; gap:0.5rem; }
    .pj-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.55rem 1rem; border-radius:10px; font-size:0.78rem; font-weight:700; border:none; cursor:pointer; text-decoration:none; transition:all 0.2s; }
    .pj-btn-primary { background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 2px 8px rgba(16,185,129,0.25); }
    .pj-btn-primary:hover { box-shadow:0 4px 16px rgba(16,185,129,0.35); }

    /* KPI Cards */
    .pj-kpis { display:grid; grid-template-columns:repeat(3, 1fr); gap:0.65rem; margin-bottom:1rem; }
    .pj-kpi { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:0.85rem 1rem; }
    .pj-kpi-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-bottom:0.5rem; }
    .pj-kpi-icon svg { width:16px; height:16px; }
    .pj-kpi-icon.green { background:#d1fae5; }
    .pj-kpi-icon.green svg { stroke:#059669; }
    .pj-kpi-icon.blue { background:#dbeafe; }
    .pj-kpi-icon.blue svg { stroke:#1d4ed8; }
    .pj-kpi-icon.red { background:#fee2e2; }
    .pj-kpi-icon.red svg { stroke:#dc2626; }
    .pj-kpi-label { font-size:0.65rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.3px; }
    .pj-kpi-value { font-size:1rem; font-weight:800; color:#1e1b4b; margin-top:0.15rem; }
    .pj-kpi-sub { font-size:0.65rem; color:#64748b; margin-top:0.1rem; }

    /* Filters */
    .pj-filters { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:0.85rem 1rem; margin-bottom:1rem; display:flex; gap:0.5rem; align-items:end; flex-wrap:wrap; }
    .pj-filter-fg { display:flex; flex-direction:column; gap:0.2rem; flex:1; min-width:120px; }
    .pj-filter-fg.small { flex:0 0 auto; min-width:100px; }
    .pj-filter-label { font-size:0.62rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.3px; }
    .pj-filter-inp { padding:0.45rem 0.65rem; border:1.5px solid #e2e8f0; border-radius:8px; font-size:0.78rem; font-family:inherit; color:#0f172a; outline:none; background:#fcfcfd; }
    .pj-filter-inp:focus { border-color:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.1); }
    .pj-filter-actions { display:flex; gap:0.35rem; }
    .pj-filter-btn { padding:0.45rem 0.85rem; border-radius:8px; font-size:0.72rem; font-weight:700; border:none; cursor:pointer; font-family:inherit; transition:all 0.15s; }
    .pj-filter-btn-search { background:#10b981; color:#fff; }
    .pj-filter-btn-search:hover { background:#059669; }
    .pj-filter-btn-reset { background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; text-decoration:none; display:inline-flex; align-items:center; }
    .pj-filter-btn-reset:hover { background:#e2e8f0; }

    /* Transaction Cards */
    .pj-list { display:flex; flex-direction:column; gap:0.5rem; }
    .pj-tx { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:0.85rem 1rem; transition:all 0.15s; display:grid; grid-template-columns:1fr auto; gap:0.5rem; align-items:center; }
    .pj-tx:hover { border-color:#10b981; box-shadow:0 2px 8px rgba(16,185,129,0.08); }
    .pj-tx-main { display:flex; flex-direction:column; gap:0.35rem; min-width:0; }
    .pj-tx-top { display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; }
    .pj-tx-no { font-family:'JetBrains Mono',monospace; font-size:0.72rem; font-weight:700; color:#059669; }
    .pj-tx-date { font-size:0.68rem; color:#94a3b8; }
    .pj-tx-mid { display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; }
    .pj-tx-cust { font-size:0.82rem; font-weight:700; color:#1e1b4b; }
    .pj-tx-sales { font-size:0.68rem; color:#64748b; }
    .pj-tx-items { font-size:0.65rem; color:#94a3b8; background:#f1f5f9; padding:0.1rem 0.45rem; border-radius:5px; font-weight:600; }
    .pj-tx-right { display:flex; flex-direction:column; align-items:flex-end; gap:0.35rem; }
    .pj-tx-total { font-size:0.95rem; font-weight:800; color:#059669; white-space:nowrap; }
    .pj-tx-actions { display:flex; gap:0.35rem; }
    .pj-tx-link { display:inline-flex; align-items:center; gap:3px; font-size:0.68rem; font-weight:700; padding:0.3rem 0.65rem; border-radius:7px; text-decoration:none; transition:all 0.15s; }
    .pj-tx-link-detail { background:#d1fae5; color:#059669; }
    .pj-tx-link-detail:hover { background:#a7f3d0; }
    .pj-tx-link-print { background:#f0fdf4; color:#059669; border:1px solid #bbf7d0; }
    .pj-tx-link-print:hover { background:#d1fae5; }

    /* Payment badges */
    .pj-pay-badge { display:inline-block; padding:0.15rem 0.5rem; border-radius:6px; font-size:0.65rem; font-weight:700; }
    .pj-pay-badge.tunai { background:#dcfce7; color:#166534; }
    .pj-pay-badge.transfer { background:#dbeafe; color:#1d4ed8; }

    /* Empty */
    .pj-empty { text-align:center; padding:3rem 1rem; }
    .pj-empty-icon { width:56px; height:56px; border-radius:14px; background:#f1f5f9; display:inline-flex; align-items:center; justify-content:center; margin-bottom:0.75rem; }
    .pj-empty-icon svg { width:28px; height:28px; stroke:#94a3b8; }
    .pj-empty-title { font-size:0.85rem; font-weight:700; color:#64748b; }
    .pj-empty-sub { font-size:0.75rem; color:#94a3b8; margin-top:0.25rem; }

    /* Pagination */
    .pj-pag { display:flex; justify-content:center; margin-top:1rem; }

    @media (max-width:640px) {
        .pj-hdr { flex-direction:column; align-items:stretch; text-align:center; gap:0.5rem; }
        .pj-hdr-icon { margin:0 auto; }
        .pj-hdr-actions { margin-left:0; width:100%; margin-top:0.5rem; }
        .pj-btn { width:100%; justify-content:center; }
        .pj-kpis { grid-template-columns:1fr 1fr; }
        .pj-filters { flex-direction:column; align-items:stretch; gap:0.75rem; }
        .pj-filter-fg, .pj-filter-fg.small { min-width:100%; width:100%; }
        .pj-filter-actions { justify-content:flex-end; width:100%; }
        .pj-tx { grid-template-columns:1fr; }
        .pj-tx-right { align-items:center; flex-direction:row; justify-content:space-between; margin-top:0.5rem; border-top:1px dashed #e2e8f0; padding-top:0.75rem; }
    }

    @media (max-width: 768px) {
        /* Optional adjustments for tablet */
    }
    @media (max-width: 480px) {
        .pj-kpis { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="pj-page">
    <div class="pj-hdr">
        <div class="pj-hdr-icon">
            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div>
            <h1>Penjualan Lapangan</h1>
            <p>Daftar transaksi penjualan Pasukan Garuda</p>
        </div>
        <div class="pj-hdr-actions">
            @if(Route::has('pasgar.penjualan.create'))
            <a href="{{ route('pasgar.penjualan.create') }}" class="pj-btn pj-btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Buat Transaksi
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;color:#059669;font-size:0.8rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;color:#991b1b;font-size:0.8rem;">{{ session('error') }}</div>
    @endif

    {{-- KPI Cards --}}
    <div class="pj-kpis">
        <div class="pj-kpi">
            <div class="pj-kpi-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </div>
            <div class="pj-kpi-label">Total Penjualan</div>
            <div class="pj-kpi-value">Rp {{ number_format($stats['total_penjualan'], 0, ',', '.') }}</div>
            <div class="pj-kpi-sub">{{ $stats['total_transaksi'] }} transaksi</div>
        </div>
        <div class="pj-kpi">
            <div class="pj-kpi-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="pj-kpi-label">Hari Ini</div>
            <div class="pj-kpi-value">Rp {{ number_format($stats['total_hari_ini'], 0, ',', '.') }}</div>
            <div class="pj-kpi-sub">{{ $stats['count_hari_ini'] }} transaksi</div>
        </div>
        <div class="pj-kpi">
            <div class="pj-kpi-icon red">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="pj-kpi-label">Rata-rata</div>
            <div class="pj-kpi-value">Rp {{ $stats['total_transaksi'] > 0 ? number_format($stats['total_penjualan'] / $stats['total_transaksi'], 0, ',', '.') : '0' }}</div>
            <div class="pj-kpi-sub">per transaksi</div>
        </div>
    </div>

    {{-- Filters --}}
    <form action="{{ route('pasgar.penjualan.index') }}" method="GET" class="pj-filters">
        <div class="pj-filter-fg">
            <label class="pj-filter-label">Cari</label>
            <input type="text" name="search" class="pj-filter-inp" placeholder="No transaksi, pelanggan, sales..." value="{{ $search ?? '' }}">
        </div>
        <div class="pj-filter-fg small">
            <label class="pj-filter-label">Bayar</label>
            <select name="metode_bayar" class="pj-filter-inp">
                <option value="">Semua</option>
                <option value="tunai" {{ $metodeBayar === 'tunai' ? 'selected' : '' }}>Tunai</option>
                <option value="transfer" {{ $metodeBayar === 'transfer' ? 'selected' : '' }}>Transfer</option>
            </select>
        </div>
        <div class="pj-filter-fg small">
            <label class="pj-filter-label">Dari</label>
            <input type="date" name="date_from" class="pj-filter-inp" value="{{ $dateFrom ?? '' }}">
        </div>
        <div class="pj-filter-fg small">
            <label class="pj-filter-label">Sampai</label>
            <input type="date" name="date_to" class="pj-filter-inp" value="{{ $dateTo ?? '' }}">
        </div>
        <div class="pj-filter-actions">
            <button type="submit" class="pj-filter-btn pj-filter-btn-search">Cari</button>
            <a href="{{ route('pasgar.penjualan.index') }}" class="pj-filter-btn pj-filter-btn-reset">Reset</a>
        </div>
    </form>

    {{-- Transaction List --}}
    <div class="pj-list">
        @forelse($penjualans as $p)
        <div class="pj-tx">
            <div class="pj-tx-main">
                <div class="pj-tx-top">
                    <span class="pj-tx-no">{{ $p->nomor_transaksi }}</span>
                    <span class="pj-tx-date">{{ $p->tanggal->format('d M Y • H:i') }}</span>
                    <span class="pj-pay-badge {{ $p->metode_bayar }}">
                        @php
                            $payIcons = ['tunai' => '💵', 'transfer' => '🏦'];
                        @endphp
                        {{ $payIcons[$p->metode_bayar] ?? '' }} {{ strtoupper($p->metode_bayar) }}
                    </span>
                </div>
                <div class="pj-tx-mid">
                    <span class="pj-tx-cust">
                        @if($p->pelanggan_id && $p->pelanggan)
                            {{ $p->pelanggan->nama_toko }}
                        @else
                            {{ $p->nama_pelanggan ?: 'Umum' }}
                        @endif
                    </span>
                    @if($p->sales)
                        <span class="pj-tx-sales">{{ $p->sales->nama }}</span>
                    @endif
                    <span class="pj-tx-items">{{ $p->items->count() }} item</span>
                </div>
            </div>
            <div class="pj-tx-right">
                <div class="pj-tx-total">Rp {{ number_format($p->total, 0, ',', '.') }}</div>
                <div class="pj-tx-actions">
                    <a href="{{ route('pasgar.penjualan.show', $p->id) }}" class="pj-tx-link pj-tx-link-detail">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Detail
                    </a>
                    @if(Route::has('pasgar.penjualan.print'))
                    <a href="{{ route('pasgar.penjualan.print', $p->id) }}" class="pj-tx-link pj-tx-link-print" title="Cetak Struk">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Cetak
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="pj-empty">
            <div class="pj-empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <div class="pj-empty-title">Belum Ada Transaksi</div>
            <div class="pj-empty-sub">
                @if($search || $metodeBayar || $dateFrom || $dateTo)
                    Tidak ada transaksi yang cocok dengan filter.
                    <a href="{{ route('pasgar.penjualan.index') }}" style="color:#059669;font-weight:700;">Reset Filter</a>
                @else
                    Buat transaksi penjualan pertama untuk mulai mencatat penjualan.
                @endif
            </div>
        </div>
        @endforelse
    </div>

    @if($penjualans->hasPages())
    <div class="pj-pag">{{ $penjualans->links() }}</div>
    @endif
</div>
@endsection
