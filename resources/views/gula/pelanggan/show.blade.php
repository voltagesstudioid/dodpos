@extends('layouts.app')

@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap');
    .gps-page { max-width:56rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

    .gps-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.5rem; }
    .gps-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
    .gps-back:hover { color:#d97706; }
    .gps-sep { color:#cbd5e1; font-size:0.8125rem; }
    .gps-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

    .gps-top { background:#fff; border:1px solid #e2e8f0; border-radius:18px; padding:1.5rem; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
    .gps-top-left { display:flex; align-items:center; gap:1rem; }
    .gps-avatar { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; font-weight:800; color:#fff; flex-shrink:0; background:linear-gradient(135deg,#f59e0b,#d97706); box-shadow:0 4px 14px rgba(245,158,11,0.25); }
    .gps-top-name { font-size:1.125rem; font-weight:800; color:#0f172a; }
    .gps-top-meta { display:flex; align-items:center; gap:6px; margin-top:3px; flex-wrap:wrap; }
    .gps-code { font-family:'JetBrains Mono',monospace; font-size:0.6875rem; font-weight:600; color:#b45309; background:#fffbeb; padding:2px 7px; border-radius:5px; border:1px solid #fde68a; }
    .gps-badge { font-size:0.6875rem; font-weight:700; padding:3px 10px; border-radius:20px; }
    .gps-badge.aktif { color:#059669; background:#ecfdf5; border:1px solid #a7f3d0; }
    .gps-badge.nonaktif { color:#64748b; background:#f1f5f9; border:1px solid #e2e8f0; }
    .gps-badge.blacklist { color:#dc2626; background:#fef2f2; border:1px solid #fecaca; }
    .gps-badge.eceran { color:#2563eb; background:#eff6ff; border:1px solid #bfdbfe; }
    .gps-badge.grosir { color:#7c3aed; background:#f5f3ff; border:1px solid #ddd6fe; }
    .gps-badge.agen { color:#d97706; background:#fffbeb; border:1px solid #fde68a; }
    .gps-top-actions { display:flex; gap:8px; }
    .gps-btn { display:inline-flex; align-items:center; gap:6px; padding:0.5rem 1rem; border-radius:10px; font-size:0.75rem; font-weight:700; cursor:pointer; transition:all 0.2s; border:1px solid transparent; text-decoration:none; font-family:inherit; }
    .gps-btn-amber { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 2px 8px rgba(245,158,11,0.25); }
    .gps-btn-amber:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(245,158,11,0.35); }
    .gps-btn-red { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
    .gps-btn-red:hover { background:#fee2e2; }

    .gps-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:0.75rem; margin-bottom:1.25rem; }
    .gps-stat { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:1rem 1.125rem; text-align:center; position:relative; overflow:hidden; }
    .gps-stat::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
    .gps-stat.blue::before { background:linear-gradient(90deg,#3b82f6,#2563eb); }
    .gps-stat.green::before { background:linear-gradient(90deg,#10b981,#059669); }
    .gps-stat.red::before { background:linear-gradient(90deg,#ef4444,#dc2626); }
    .gps-stat.amber::before { background:linear-gradient(90deg,#f59e0b,#d97706); }
    .gps-stat-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#94a3b8; }
    .gps-stat-val { font-size:1.25rem; font-weight:800; margin-top:4px; }
    .gps-stat-val.blue { color:#2563eb; }
    .gps-stat-val.green { color:#059669; }
    .gps-stat-val.amber { color:#b45309; }
    .gps-stat-val.red { color:#dc2626; }

    .gps-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
    .gps-card-hdr { padding:1rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
    .gps-card-ico { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .gps-card-ico svg { width:16px; height:16px; }
    .gps-card-title { font-size:0.8125rem; font-weight:700; color:#0f172a; }
    .gps-card-body { padding:1.25rem 1.375rem; }

    .gps-card.amber .gps-card-hdr { background:linear-gradient(135deg,#fffbeb,#fef9ee); }
    .gps-card.amber .gps-card-ico { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
    .gps-card.green .gps-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
    .gps-card.green .gps-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
    .gps-card.blue .gps-card-hdr { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
    .gps-card.blue .gps-card-ico { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
    .gps-card.purple .gps-card-hdr { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
    .gps-card.purple .gps-card-ico { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }
    .gps-card.red .gps-card-hdr { background:linear-gradient(135deg,#fef2f2,#fee2e2); }
    .gps-card.red .gps-card-ico { background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; }

    .gps-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:0.875rem; }
    .gps-info { display:flex; flex-direction:column; gap:2px; }
    .gps-info-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#94a3b8; }
    .gps-info-val { font-size:0.875rem; font-weight:600; color:#0f172a; }
    .gps-info-val.empty { color:#cbd5e1; font-style:italic; font-weight:400; }
    .gps-info-val.mono { font-family:'JetBrains Mono',monospace; font-size:0.8125rem; }
    .gps-full { grid-column:1 / -1; }

    .gps-tbl { width:100%; border-collapse:collapse; font-size:0.8125rem; }
    .gps-tbl th { text-align:left; padding:0.625rem 0.75rem; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#94a3b8; border-bottom:1px solid #f1f5f9; }
    .gps-tbl td { padding:0.625rem 0.75rem; border-bottom:1px solid #f8fafc; color:#334155; }
    .gps-tbl tr:last-child td { border-bottom:none; }
    .gps-tbl .mono { font-family:'JetBrains Mono',monospace; font-size:0.75rem; font-weight:600; }
    .gps-tbl .empty-row { text-align:center; color:#94a3b8; padding:1.5rem; font-style:italic; }

    .gps-tbl-badge { font-size:0.6875rem; font-weight:700; padding:2px 8px; border-radius:20px; display:inline-block; }
    .gps-tbl-badge.lunas { color:#059669; background:#ecfdf5; }
    .gps-tbl-badge.belum_lunas { color:#d97706; background:#fffbeb; }
    .gps-tbl-badge.tunai { color:#059669; background:#ecfdf5; }
    .gps-tbl-badge.hutang { color:#d97706; background:#fffbeb; }
    .gps-tbl-badge.transfer { color:#2563eb; background:#eff6ff; }
    .gps-tbl-badge.visited { color:#059669; background:#ecfdf5; }
    .gps-tbl-badge.planned { color:#64748b; background:#f1f5f9; }

    .gps-hutang-alert { background:linear-gradient(135deg,#fef2f2,#fee2e2); border:1px solid #fecaca; border-radius:12px; padding:0.875rem 1.125rem; margin-bottom:1rem; display:flex; align-items:center; gap:0.75rem; }
    .gps-hutang-alert svg { flex-shrink:0; color:#dc2626; }
    .gps-hutang-alert-text { font-size:0.8125rem; font-weight:600; color:#991b1b; }
    .gps-hutang-alert-val { font-family:'JetBrains Mono',monospace; font-weight:700; }

    @media(max-width:640px) { .gps-grid2 { grid-template-columns:1fr; } .gps-stats { grid-template-columns:1fr 1fr; } }
</style>
@endpush

@section('content')
<div class="gps-page">

    {{-- Breadcrumb --}}
    <nav class="gps-nav">
        <a href="{{ route('gula.pelanggan.index') }}" class="gps-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Daftar Pelanggan
        </a>
        <span class="gps-sep">/</span>
        <span class="gps-crumb">Detail</span>
    </nav>

    {{-- Top Profile --}}
    <div class="gps-top">
        <div class="gps-top-left">
            <div class="gps-avatar">{{ strtoupper(substr($pelanggan->nama_toko, 0, 1)) }}</div>
            <div>
                <div class="gps-top-name">{{ $pelanggan->nama_toko }}</div>
                <div class="gps-top-meta">
                    <span class="gps-code">{{ $pelanggan->kode_pelanggan }}</span>
                    <span class="gps-badge {{ $pelanggan->tipe }}">{{ ucfirst($pelanggan->tipe) }}</span>
                    <span class="gps-badge {{ $pelanggan->status }}">{{ ucfirst($pelanggan->status) }}</span>
                </div>
            </div>
        </div>
        <div class="gps-top-actions">
            <a href="{{ route('gula.pelanggan.edit', $pelanggan->id) }}" class="gps-btn gps-btn-amber">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit
            </a>
            <form method="POST" action="{{ route('gula.pelanggan.destroy', $pelanggan->id) }}" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="gps-btn gps-btn-red">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- Stats --}}
    <div class="gps-stats">
        <div class="gps-stat blue">
            <div class="gps-stat-lbl">Total Transaksi</div>
            <div class="gps-stat-val blue">{{ $pelanggan->penjualans->count() }}</div>
        </div>
        <div class="gps-stat green">
            <div class="gps-stat-lbl">Total Belanja</div>
            <div class="gps-stat-val green">Rp {{ number_format($pelanggan->penjualans->sum('total'), 0, ',', '.') }}</div>
        </div>
        <div class="gps-stat red">
            <div class="gps-stat-lbl">Total Hutang</div>
            <div class="gps-stat-val red">Rp {{ number_format($pelanggan->total_hutang, 0, ',', '.') }}</div>
        </div>
        <div class="gps-stat amber">
            <div class="gps-stat-lbl">Kunjungan</div>
            <div class="gps-stat-val amber">{{ $pelanggan->kunjungans->count() }}</div>
        </div>
    </div>

    {{-- Hutang Alert --}}
    @if($pelanggan->total_hutang > 0)
    <div class="gps-hutang-alert">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div class="gps-hutang-alert-text">
            Pelanggan memiliki hutang sebesar <span class="gps-hutang-alert-val">Rp {{ number_format($pelanggan->total_hutang, 0, ',', '.') }}</span>
            @if($pelanggan->limit_hutang)
                &middot; Limit: Rp {{ number_format($pelanggan->limit_hutang, 0, ',', '.') }}
            @endif
        </div>
    </div>
    @endif

    {{-- Card 1: Informasi Pelanggan --}}
    <div class="gps-card amber">
        <div class="gps-card-hdr">
            <div class="gps-card-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div class="gps-card-title">Informasi Pelanggan</div>
        </div>
        <div class="gps-card-body">
            <div class="gps-grid2">
                <div class="gps-info">
                    <span class="gps-info-lbl">Nama Toko</span>
                    <span class="gps-info-val">{{ $pelanggan->nama_toko }}</span>
                </div>
                <div class="gps-info">
                    <span class="gps-info-lbl">Nama Pemilik</span>
                    <span class="gps-info-val">{{ $pelanggan->nama_pemilik }}</span>
                </div>
                <div class="gps-info">
                    <span class="gps-info-lbl">No HP</span>
                    <span class="gps-info-val mono {{ !$pelanggan->no_hp ? 'empty' : '' }}">{{ $pelanggan->no_hp ?: '—' }}</span>
                </div>
                <div class="gps-info">
                    <span class="gps-info-lbl">Email</span>
                    <span class="gps-info-val {{ !$pelanggan->email ? 'empty' : '' }}">{{ $pelanggan->email ?: '—' }}</span>
                </div>
                <div class="gps-info gps-full">
                    <span class="gps-info-lbl">Alamat</span>
                    <span class="gps-info-val {{ !$pelanggan->alamat ? 'empty' : '' }}">
                        {{ $pelanggan->alamat ?: '—' }}
                        @if($pelanggan->kecamatan || $pelanggan->kota)
                            <br><span style="font-size:0.75rem;color:#64748b;">
                                {{ collect([$pelanggan->kecamatan, $pelanggan->kota])->filter()->implode(', ') }}
                            </span>
                        @endif
                        @if($pelanggan->latitude && $pelanggan->longitude)
                            <br><span style="font-size:0.6875rem;color:#2563eb;font-weight:600;font-family:'JetBrains Mono',monospace;">
                                GPS: {{ $pelanggan->latitude }}, {{ $pelanggan->longitude }}
                            </span>
                        @endif
                    </span>
                </div>
                <div class="gps-info">
                    <span class="gps-info-lbl">Limit Hutang</span>
                    <span class="gps-info-val mono">{{ $pelanggan->limit_hutang ? 'Rp ' . number_format($pelanggan->limit_hutang, 0, ',', '.') : '—' }}</span>
                </div>
                <div class="gps-info">
                    <span class="gps-info-lbl">Terdaftar</span>
                    <span class="gps-info-val">{{ $pelanggan->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Riwayat Transaksi --}}
    <div class="gps-card green">
        <div class="gps-card-hdr">
            <div class="gps-card-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </div>
            <div class="gps-card-title">Riwayat Transaksi ({{ $pelanggan->penjualans->count() }})</div>
        </div>
        <div class="gps-card-body" style="padding:0;">
            @if($pelanggan->penjualans->count())
            <div style="overflow-x:auto;">
                <table class="gps-tbl">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No Faktur</th>
                            <th>Sales</th>
                            <th style="text-align:right;">Total</th>
                            <th>Bayar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pelanggan->penjualans->sortByDesc('tanggal_jual')->take(20) as $p)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_jual)->format('d/m/Y') }}</td>
                            <td class="mono">{{ $p->no_faktur }}</td>
                            <td>{{ $p->sales->nama ?? '—' }}</td>
                            <td style="text-align:right;" class="mono">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td><span class="gps-tbl-badge {{ $p->tipe_bayar }}">{{ ucfirst($p->tipe_bayar) }}</span></td>
                            <td>
                                @if($p->hutang > 0)
                                    <span class="gps-tbl-badge belum_lunas">Hutang</span>
                                @else
                                    <span class="gps-tbl-badge lunas">Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="gps-tbl"><div class="empty-row">Belum ada transaksi</div></div>
            @endif
        </div>
    </div>

    {{-- Card 3: Hutang --}}
    @if($pelanggan->hutangs->count())
    <div class="gps-card red">
        <div class="gps-card-hdr">
            <div class="gps-card-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div class="gps-card-title">Daftar Hutang ({{ $pelanggan->hutangs->count() }})</div>
        </div>
        <div class="gps-card-body" style="padding:0;">
            <div style="overflow-x:auto;">
                <table class="gps-tbl">
                    <thead>
                        <tr>
                            <th>Jatuh Tempo</th>
                            <th>No Faktur</th>
                            <th style="text-align:right;">Total</th>
                            <th style="text-align:right;">Dibayar</th>
                            <th style="text-align:right;">Sisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pelanggan->hutangs->sortByDesc('created_at') as $h)
                        <tr>
                            <td>{{ $h->jatuh_tempo ? $h->jatuh_tempo->format('d/m/Y') : '—' }}</td>
                            <td class="mono">{{ $h->penjualan->no_faktur ?? '—' }}</td>
                            <td style="text-align:right;" class="mono">Rp {{ number_format($h->total_hutang, 0, ',', '.') }}</td>
                            <td style="text-align:right;" class="mono">Rp {{ number_format($h->dibayar, 0, ',', '.') }}</td>
                            <td style="text-align:right;" class="mono">Rp {{ number_format($h->sisa, 0, ',', '.') }}</td>
                            <td><span class="gps-tbl-badge {{ $h->status }}">{{ str_replace('_', ' ', ucfirst($h->status)) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Card 4: Riwayat Kunjungan --}}
    <div class="gps-card purple">
        <div class="gps-card-hdr">
            <div class="gps-card-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div class="gps-card-title">Riwayat Kunjungan ({{ $pelanggan->kunjungans->count() }})</div>
        </div>
        <div class="gps-card-body" style="padding:0;">
            @if($pelanggan->kunjungans->count())
            <div style="overflow-x:auto;">
                <table class="gps-tbl">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Sales</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pelanggan->kunjungans->sortByDesc('waktu_checkin')->take(20) as $k)
                        <tr>
                            <td>{{ $k->waktu_checkin ? $k->waktu_checkin->format('d/m/Y') : '—' }}</td>
                            <td>{{ $k->sales->nama ?? '—' }}</td>
                            <td class="mono">{{ $k->waktu_checkin ? $k->waktu_checkin->format('H:i') : '—' }}</td>
                            <td class="mono">{{ $k->waktu_checkout ? $k->waktu_checkout->format('H:i') : '—' }}</td>
                            <td><span class="gps-tbl-badge {{ $k->status }}">{{ ucfirst($k->status ?? 'planned') }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="gps-tbl"><div class="empty-row">Belum ada kunjungan</div></div>
            @endif
        </div>
    </div>

</div>
@endsection
