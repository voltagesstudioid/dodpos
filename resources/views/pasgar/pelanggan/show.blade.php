@extends('layouts.app', ['title' => 'Detail Pelanggan - ' . $pelanggan->nama_toko])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .pls-page { max-width:56rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

    .pls-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.75rem; }
    .pls-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
    .pls-back:hover { color:#4f46e5; }
    .pls-sep { color:#cbd5e1; font-size:0.8125rem; }
    .pls-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

    /* Header card */
    .pls-hdr {
        background:#fff; border:1px solid #e2e8f0; border-radius:18px; padding:1.5rem 1.75rem;
        display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;
        margin-bottom:1.25rem; box-shadow:0 1px 4px rgba(0,0,0,0.04);
    }
    .pls-hdr-l { display:flex; align-items:center; gap:1rem; }
    .pls-hdr-av {
        width:56px; height:56px; border-radius:14px; display:flex; align-items:center; justify-content:center;
        font-size:1.5rem; font-weight:700; flex-shrink:0;
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
        box-shadow:0 6px 18px rgba(79,70,229,0.25);
    }
    .pls-hdr-title { font-size:1.25rem; font-weight:800; color:#0f172a; letter-spacing:-0.02em; }
    .pls-hdr-sub { display:flex; align-items:center; gap:0.5rem; margin-top:0.25rem; }
    .pls-hdr-code {
        font-family:'JetBrains Mono',monospace; font-size:0.75rem; font-weight:600; color:#475569;
        background:#f8fafc; padding:0.2rem 0.5rem; border-radius:6px; border:1px solid #e2e8f0;
    }
    .pls-hdr-tipe {
        display:inline-flex; padding:0.15rem 0.5rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
    }
    .pls-hdr-tipe.warung { background:#fffbeb; color:#92400e; border-color:#fde68a; }
    .pls-hdr-tipe.toko   { background:#eff6ff; color:#1e40af; border-color:#bfdbfe; }
    .pls-hdr-tipe.kios   { background:#f5f3ff; color:#6d28d9; border-color:#ddd6fe; }
    .pls-hdr-btn {
        display:inline-flex; align-items:center; gap:0.5rem;
        padding:0.6rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
        text-decoration:none; transition:all 0.2s; border:none; cursor:pointer;
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
        box-shadow:0 4px 14px rgba(79,70,229,0.3);
    }
    .pls-hdr-btn:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(79,70,229,0.4); }

    /* Detail cards */
    .pls-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
    .pls-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
    .pls-card-hdr { padding:1rem 1.25rem; display:flex; align-items:center; gap:0.625rem; border-bottom:1px solid #f1f5f9; }
    .pls-card-ico { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.95rem; }
    .pls-card-title { font-size:0.8125rem; font-weight:700; color:#0f172a; }
    .pls-card-body { padding:1.125rem 1.25rem; }

    .pls-card.indigo .pls-card-hdr { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
    .pls-card.indigo .pls-card-ico { background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; }
    .pls-card.green .pls-card-hdr  { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
    .pls-card.green .pls-card-ico  { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
    .pls-card.blue .pls-card-hdr   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
    .pls-card.blue .pls-card-ico   { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
    .pls-card.pink .pls-card-hdr   { background:linear-gradient(135deg,#fdf2f8,#fce7f3); }
    .pls-card.pink .pls-card-ico   { background:linear-gradient(135deg,#ec4899,#db2777); color:#fff; }
    .pls-card.amber .pls-card-hdr  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
    .pls-card.amber .pls-card-ico  { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
    .pls-card.red .pls-card-hdr   { background:linear-gradient(135deg,#fef2f2,#fee2e2); }
    .pls-card.red .pls-card-ico   { background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; }

    /* Info table */
    .pls-info { width:100%; }
    .pls-info td { padding:0.375rem 0; font-size:0.8125rem; vertical-align:top; }
    .pls-info td:first-child { color:#94a3b8; font-weight:600; width:35%; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; }
    .pls-info td:last-child { color:#1e293b; font-weight:500; }

    /* Status */
    .pls-status {
        display:inline-flex; align-items:center; gap:0.3rem;
        padding:0.25rem 0.75rem; border-radius:99px; font-size:0.75rem; font-weight:700; border:1px solid;
    }
    .pls-status.aktif    { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
    .pls-status.nonaktif { background:#f8fafc; color:#64748b; border-color:#e2e8f0; }
    .pls-status.blacklist{ background:#fff1f2; color:#be123c; border-color:#fecdd3; }
    .pls-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
    .pls-status-dot.aktif    { background:#10b981; }
    .pls-status-dot.nonaktif { background:#94a3b8; }
    .pls-status-dot.blacklist{ background:#ef4444; }

    /* Map button */
    .pls-map-btn {
        display:inline-flex; align-items:center; gap:0.5rem; margin-top:0.5rem;
        padding:0.5rem 1rem; border-radius:8px; font-size:0.8125rem; font-weight:600;
        background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; text-decoration:none; transition:all 0.2s;
    }
    .pls-map-btn:hover { background:#dbeafe; }

    /* Foto */
    .pls-foto { max-width:100%; max-height:400px; border-radius:14px; border:2px solid #e2e8f0; box-shadow:0 4px 16px rgba(0,0,0,0.08); cursor:zoom-in; }
    .pls-foto-hint { font-size:0.6875rem; color:#94a3b8; margin-top:0.5rem; }

    /* Transaction table */
    .pls-tbl-head { background:linear-gradient(180deg,#fffbeb,#fef3c7); border-bottom:2px solid #fde68a; }
    .pls-tbl-head th { padding:0.75rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#92400e; white-space:nowrap; }
    .pls-tbl-body td { padding:0.75rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; }
    .pls-tbl-body tr:hover td { background:#fffdf7; }
    .pls-tbl-body tr:last-child td { border-bottom:none; }
    .pls-tbl-empty { text-align:center; padding:2rem; color:#94a3b8; font-size:0.8125rem; }

    @media(max-width:768px) { .pls-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="pls-page">

    @if(session('success'))
    <div style="background:#d1fae5;border:1px solid #a7f3d0;color:#065f46;padding:0.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;font-weight:600;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:0.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;font-weight:600;">{{ session('error') }}</div>
    @endif

    {{-- Breadcrumb --}}
    <nav class="pls-nav">
        <a href="{{ route('pasgar.pelanggan.index') }}" class="pls-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Daftar Pelanggan
        </a>
        <span class="pls-sep">/</span>
        <span class="pls-crumb">{{ $pelanggan->nama_toko }}</span>
    </nav>

    {{-- Header Card --}}
    <div class="pls-hdr">
        <div class="pls-hdr-l">
            <div class="pls-hdr-av">{{ substr($pelanggan->nama_toko, 0, 1) }}</div>
            <div>
                <div class="pls-hdr-title">{{ $pelanggan->nama_toko }}</div>
                <div class="pls-hdr-sub">
                    <span class="pls-hdr-code">{{ $pelanggan->kode_pelanggan }}</span>
                    <span class="pls-hdr-tipe {{ $pelanggan->tipe }}">{{ ucfirst($pelanggan->tipe) }}</span>
                    <span class="pls-status {{ $pelanggan->status }}">
                        <span class="pls-status-dot {{ $pelanggan->status }}"></span>
                        {{ ucfirst($pelanggan->status) }}
                    </span>
                </div>
            </div>
        </div>
        @if(!$isSalesRole)
        <a href="{{ route('pasgar.pelanggan.edit', $pelanggan->id) }}" class="pls-hdr-btn">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Pelanggan
        </a>
        @endif
    </div>

    {{-- Detail Grid --}}
    <div class="pls-grid">
        {{-- Info Toko --}}
        <div class="pls-card indigo">
            <div class="pls-card-hdr">
                <div class="pls-card-ico">🏪</div>
                <div class="pls-card-title">Informasi Toko</div>
            </div>
            <div class="pls-card-body">
                <div style="overflow-x: auto; margin-bottom: 1rem;">
<table class="pls-info">
                    <tr><td>Kode</td><td style="font-weight:700;">{{ $pelanggan->kode_pelanggan }}</td></tr>
                    <tr><td>Nama Toko</td><td style="font-weight:600;">{{ $pelanggan->nama_toko }}</td></tr>
                    <tr><td>Pemilik</td><td>{{ $pelanggan->nama_pemilik }}</td></tr>
                    <tr><td>Tipe</td><td>{{ ucfirst($pelanggan->tipe) }}</td></tr>
                </table>
            </div>
</div>
        </div>

        {{-- Kontak & Alamat --}}
        <div class="pls-card green">
            <div class="pls-card-hdr">
                <div class="pls-card-ico">📞</div>
                <div class="pls-card-title">Kontak & Alamat</div>
            </div>
            <div class="pls-card-body">
                <div style="overflow-x: auto; margin-bottom: 1rem;">
<table class="pls-info">
                    <tr><td>No HP</td><td>{{ $pelanggan->no_hp ?? '-' }}</td></tr>
                    <tr><td>Email</td><td>{{ $pelanggan->email ?? '-' }}</td></tr>
                    <tr><td>Alamat</td><td>{{ $pelanggan->alamat ?? '-' }}</td></tr>
                    <tr><td>Kecamatan</td><td>{{ $pelanggan->kecamatan ?? '-' }}</td></tr>
                    <tr><td>Kota</td><td>{{ $pelanggan->kota ?? '-' }}</td></tr>
                </table>
            </div>
</div>
        </div>
    </div>

    {{-- Lokasi --}}
    @if($pelanggan->latitude && $pelanggan->longitude)
    <div class="pls-card blue" style="margin-bottom:1.25rem;">
        <div class="pls-card-hdr">
            <div class="pls-card-ico">📍</div>
            <div class="pls-card-title">Lokasi</div>
        </div>
        <div class="pls-card-body">
            <p style="font-size:0.8125rem; color:#64748b; margin:0 0 0.5rem;">
                {{ $pelanggan->latitude }}, {{ $pelanggan->longitude }}
            </p>
            <a href="https://www.google.com/maps?q={{ $pelanggan->latitude }},{{ $pelanggan->longitude }}" target="_blank" class="pls-map-btn">
                🗺️ Buka di Google Maps
            </a>
        </div>
    </div>
    @endif

    {{-- Foto Toko --}}
    @if($pelanggan->foto_toko)
    <div class="pls-card pink" style="margin-bottom:1.25rem;">
        <div class="pls-card-hdr">
            <div class="pls-card-ico">📷</div>
            <div class="pls-card-title">Foto Toko</div>
        </div>
        <div class="pls-card-body" style="text-align:center;">
            <a href="{{ asset('storage/' . $pelanggan->foto_toko) }}" target="_blank">
                <img src="{{ asset('storage/' . $pelanggan->foto_toko) }}" alt="Foto Toko {{ $pelanggan->nama_toko }}" class="pls-foto">
            </a>
            <p class="pls-foto-hint">Klik gambar untuk melihat ukuran penuh</p>
        </div>
    </div>
    @endif

    {{-- Riwayat Transaksi --}}
    <div class="pls-card amber">
        <div class="pls-card-hdr">
            <div class="pls-card-ico">📋</div>
            <div class="pls-card-title">Riwayat Transaksi</div>
        </div>
        @if($pelanggan->penjualans->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:separate; border-spacing:0;">
                <thead class="pls-tbl-head">
                    <tr>
                        <th style="text-align:left;">Tanggal</th>
                        <th style="text-align:left;">No Transaksi</th>
                        <th style="text-align:left;">Sales</th>
                        <th style="text-align:left;">Item</th>
                        <th style="text-align:right;">Total</th>
                    </tr>
                </thead>
                <tbody class="pls-tbl-body">
                    @foreach($pelanggan->penjualans as $trx)
                    <tr>
                        <td style="font-weight:600;">{{ $trx->tanggal->format('d M Y') }}</td>
                        <td>{{ $trx->nomor_transaksi }}</td>
                        <td>{{ $trx->sales->nama ?? '-' }}</td>
                        <td>{{ $trx->items->count() }} item</td>
                        <td style="text-align:right; font-weight:700; color:#059669;">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="pls-tbl-empty">Belum ada riwayat transaksi</div>
        @endif
    </div>

</div>
@endsection
