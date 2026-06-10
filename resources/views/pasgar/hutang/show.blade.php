@extends('layouts.app', ['title' => 'Detail Hutang - Pasgar'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .htd-page { max-width:56rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }
    .htd-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.5rem; }
    .htd-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; }
    .htd-back:hover { color:#4f46e5; }
    .htd-sep { color:#cbd5e1; font-size:0.8125rem; }
    .htd-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

    /* Header */
    .htd-hdr { background:#fff; border:1px solid #e2e8f0; border-radius:18px; padding:1.5rem 1.75rem; margin-bottom:1.25rem; box-shadow:0 1px 4px rgba(0,0,0,0.04); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
    .htd-hdr-l {}
    .htd-hdr-title { font-size:1.1rem; font-weight:800; color:#0f172a; }
    .htd-hdr-sub { font-size:0.78rem; color:#94a3b8; margin-top:0.25rem; }
    .htd-hdr-actions { display:flex; gap:0.5rem; }
    .htd-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.6rem 1.15rem; border-radius:10px; font-size:0.8125rem; font-weight:700; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; }
    .htd-btn-primary { background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 2px 8px rgba(16,185,129,0.25); }
    .htd-btn-primary:hover { box-shadow:0 4px 14px rgba(16,185,129,0.35); }
    .htd-btn-outline { background:#fff; color:#6366f1; border:1.5px solid #c7d2fe; }
    .htd-btn-outline:hover { background:#eef2ff; }

    /* Status badge */
    .htd-status { display:inline-flex; align-items:center; gap:0.3rem; padding:0.3rem 0.85rem; border-radius:99px; font-size:0.78rem; font-weight:700; border:1px solid; }
    .htd-status.belum_lunas { background:#fef3c7; color:#92400e; border-color:#fde68a; }
    .htd-status.lunas { background:#d1fae5; color:#059669; border-color:#a7f3d0; }
    .htd-status.overdue { background:#fef2f2; color:#dc2626; border-color:#fecaca; }

    /* Cards */
    .htd-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
    .htd-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
    .htd-card-hdr { padding:0.85rem 1.25rem; display:flex; align-items:center; gap:0.6rem; border-bottom:1px solid #f1f5f9; }
    .htd-card-ico { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.95rem; }
    .htd-card-title { font-size:0.8125rem; font-weight:700; color:#0f172a; }
    .htd-card-body { padding:1.125rem 1.25rem; }
    .htd-card.indigo .htd-card-hdr { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
    .htd-card.indigo .htd-card-ico { background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; }
    .htd-card.green .htd-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
    .htd-card.green .htd-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
    .htd-card.amber .htd-card-hdr { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
    .htd-card.amber .htd-card-ico { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }

    /* Info table */
    .htd-info { width:100%; }
    .htd-info td { padding:0.375rem 0; font-size:0.8125rem; vertical-align:top; }
    .htd-info td:first-child { color:#94a3b8; font-weight:600; width:40%; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; }
    .htd-info td:last-child { color:#1e293b; font-weight:500; }

    /* Progress bar */
    .htd-progress { margin:0.75rem 0 0.5rem; }
    .htd-progress-bar { height:8px; background:#f1f5f9; border-radius:99px; overflow:hidden; }
    .htd-progress-fill { height:100%; border-radius:99px; transition:width 0.3s; }
    .htd-progress-fill.green { background:linear-gradient(135deg,#10b981,#059669); }
    .htd-progress-fill.amber { background:linear-gradient(135deg,#f59e0b,#d97706); }
    .htd-progress-labels { display:flex; justify-content:space-between; margin-top:0.4rem; font-size:0.72rem; color:#94a3b8; font-weight:600; }

    /* Payment table */
    .htd-tbl-head { background:linear-gradient(180deg,#eef2ff,#e0e7ff); border-bottom:2px solid #c7d2fe; }
    .htd-tbl-head th { padding:0.75rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#4338ca; white-space:nowrap; }
    .htd-tbl-body td { padding:0.75rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; }
    .htd-tbl-body tr:hover td { background:#f8fafc; }
    .htd-tbl-body tr:last-child td { border-bottom:none; }
    .htd-tbl-empty { text-align:center; padding:2rem; color:#94a3b8; font-size:0.8125rem; }

    .htd-pay-status { display:inline-flex; padding:0.2rem 0.6rem; border-radius:6px; font-size:0.7rem; font-weight:700; }
    .htd-pay-status.pending { background:#fef3c7; color:#92400e; }
    .htd-pay-status.confirmed { background:#d1fae5; color:#059669; }
    .htd-pay-status.rejected { background:#fef2f2; color:#dc2626; }

    /* Confirm form */
    .htd-confirm-form { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1rem 1.25rem; margin-top:0.75rem; }
    .htd-confirm-form h4 { font-size:0.8125rem; font-weight:700; color:#1e1b4b; margin:0 0 0.75rem; }
    .htd-confirm-actions { display:flex; gap:0.5rem; margin-top:0.75rem; }

    @media(max-width:768px) { .htd-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="htd-page">
    {{-- Breadcrumb --}}
    <nav class="htd-nav">
        <a href="{{ route('pasgar.hutang.index') }}" class="htd-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Daftar Hutang
        </a>
        <span class="htd-sep">/</span>
        <span class="htd-crumb">{{ $hutang->penjualan->nomor_transaksi ?? 'Detail' }}</span>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div style="background:#d1fae5; border:1px solid #a7f3d0; color:#065f46; padding:0.75rem 1rem; border-radius:10px; margin-bottom:1rem; font-size:0.82rem; font-weight:600;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:0.75rem 1rem; border-radius:10px; margin-bottom:1rem; font-size:0.82rem; font-weight:600;">{{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="htd-hdr">
        <div class="htd-hdr-l">
            <div class="htd-hdr-title">{{ $hutang->penjualan->nomor_transaksi ?? '-' }}</div>
            <div class="htd-hdr-sub">
                {{ $hutang->pelanggan->nama_toko ?? 'Tanpa Pelanggan' }}
                &middot; {{ $hutang->created_at->format('d M Y') }}
                &nbsp;
                <span class="htd-status {{ $hutang->status }}">{{ ucfirst(str_replace('_', ' ', $hutang->status)) }}</span>
            </div>
        </div>
        <div class="htd-hdr-actions">
            @if($hutang->sisa > 0)
            <a href="{{ route('pasgar.hutang.bayar', $hutang->id) }}" class="htd-btn htd-btn-primary">
                💰 Bayar
            </a>
            @endif
        </div>
    </div>

    {{-- Info Grid --}}
    <div class="htd-grid">
        {{-- Pelanggan Info --}}
        <div class="htd-card indigo">
            <div class="htd-card-hdr">
                <div class="htd-card-ico">🏪</div>
                <div class="htd-card-title">Pelanggan</div>
            </div>
            <div class="htd-card-body">
                <table class="htd-info">
                    <tr><td>Nama Toko</td><td style="font-weight:700;">{{ $hutang->pelanggan->nama_toko ?? '-' }}</td></tr>
                    <tr><td>Pemilik</td><td>{{ $hutang->pelanggan->nama_pemilik ?? '-' }}</td></tr>
                    <tr><td>No HP</td><td>{{ $hutang->pelanggan->no_hp ?? '-' }}</td></tr>
                    <tr><td>Regional</td><td>{{ $hutang->pelanggan->regional->nama ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Hutang Summary --}}
        <div class="htd-card green">
            <div class="htd-card-hdr">
                <div class="htd-card-ico">💰</div>
                <div class="htd-card-title">Ringkasan Hutang</div>
            </div>
            <div class="htd-card-body">
                <table class="htd-info">
                    <tr><td>Total Hutang</td><td style="font-weight:800; color:#4f46e5;">Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</td></tr>
                    <tr><td>Dibayar</td><td style="font-weight:700; color:#059669;">Rp {{ number_format($hutang->dibayar, 0, ',', '.') }}</td></tr>
                    <tr><td>Sisa</td><td style="font-weight:800; color:#dc2626;">Rp {{ number_format($hutang->sisa, 0, ',', '.') }}</td></tr>
                    <tr><td>Jatuh Tempo</td><td>{{ $hutang->jatuh_tempo ? $hutang->jatuh_tempo->format('d M Y') : '-' }}</td></tr>
                </table>
                @php
                    $pct = $hutang->total_hutang > 0 ? min(100, round(($hutang->dibayar / $hutang->total_hutang) * 100)) : 0;
                @endphp
                <div class="htd-progress">
                    <div class="htd-progress-bar">
                        <div class="htd-progress-fill {{ $pct >= 100 ? 'green' : 'amber' }}" style="width:{{ $pct }}%;"></div>
                    </div>
                    <div class="htd-progress-labels">
                        <span>{{ $pct }}% terbayar</span>
                        <span>Rp {{ number_format($hutang->sisa, 0, ',', '.') }} sisa</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Penjualan Info --}}
    @if($hutang->penjualan)
    <div class="htd-card amber" style="margin-bottom:1.25rem;">
        <div class="htd-card-hdr">
            <div class="htd-card-ico">📋</div>
            <div class="htd-card-title">Detail Penjualan</div>
        </div>
        <div class="htd-card-body">
            <table class="htd-info">
                <tr><td>No Transaksi</td><td style="font-weight:700;">{{ $hutang->penjualan->nomor_transaksi }}</td></tr>
                <tr><td>Tanggal</td><td>{{ $hutang->penjualan->tanggal->format('d M Y') }}</td></tr>
                <tr><td>Total Transaksi</td><td>Rp {{ number_format($hutang->penjualan->total, 0, ',', '.') }}</td></tr>
                <tr><td>Uang Muka</td><td>Rp {{ number_format($hutang->penjualan->uang_muka ?? 0, 0, ',', '.') }}</td></tr>
            </table>
            @if($hutang->penjualan->items->count() > 0)
            <div style="margin-top:0.75rem; font-size:0.75rem; color:#64748b; font-weight:600;">ITEM:</div>
            @foreach($hutang->penjualan->items as $item)
            <div style="font-size:0.8125rem; padding:0.3rem 0; border-bottom:1px solid #f1f5f9;">
                {{ $item->product->name ?? '-' }} &times; {{ $item->qty }} = <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
            </div>
            @endforeach
            @endif
        </div>
    </div>
    @endif

    {{-- Payment History --}}
    <div class="htd-card indigo">
        <div class="htd-card-hdr">
            <div class="htd-card-ico">📝</div>
            <div class="htd-card-title">Riwayat Pembayaran</div>
        </div>
        @if($hutang->pembayarans->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:separate; border-spacing:0;">
                <thead class="htd-tbl-head">
                    <tr>
                        <th style="text-align:left;">Tanggal</th>
                        <th style="text-align:right;">Jumlah</th>
                        <th style="text-align:left;">Cara</th>
                        <th style="text-align:left;">Oleh</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="htd-tbl-body">
                    @foreach($hutang->pembayarans as $bayar)
                    <tr>
                        <td style="font-weight:600;">{{ $bayar->tanggal_bayar->format('d M Y H:i') }}</td>
                        <td style="text-align:right; font-weight:800; color:#059669;">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($bayar->cara_bayar) }}</td>
                        <td>{{ $bayar->creator->name ?? '-' }}</td>
                        <td style="text-align:center;">
                            <span class="htd-pay-status {{ $bayar->status }}">{{ ucfirst($bayar->status) }}</span>
                        </td>
                        <td>
                            @if($bayar->status === 'pending' && !$isSalesRole)
                            <form action="{{ route('pasgar.hutang.confirm', $bayar->id) }}" method="POST" style="display:inline-flex; gap:0.3rem;">
                                @csrf
                                <input type="hidden" name="action" value="confirm">
                                <button type="submit" class="htd-btn htd-btn-primary" style="padding:0.25rem 0.6rem; font-size:0.7rem;">✓ Konfirmasi</button>
                            </form>
                            <button type="button" onclick="showReject({{ $bayar->id }})" class="htd-btn" style="padding:0.25rem 0.6rem; font-size:0.7rem; background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">✗ Tolak</button>
                            @elseif($bayar->status === 'rejected')
                                <span style="font-size:0.72rem; color:#dc2626;">{{ $bayar->reject_reason ? 'Alasan: '.$bayar->reject_reason : 'Ditolak' }}</span>
                            @elseif($bayar->status === 'confirmed')
                                <span style="font-size:0.72rem; color:#059669;">✓ {{ $bayar->confirmedBy->name ?? '' }} - {{ $bayar->confirmed_at?->format('d/m/Y H:i') }}</span>
                            @endif
                        </td>
                    </tr>
                    @if($bayar->bukti_transfer)
                    <tr>
                        <td colspan="6" style="padding:0.5rem 1.25rem;">
                            <a href="{{ asset('storage/' . $bayar->bukti_transfer) }}" target="_blank" style="font-size:0.75rem; color:#4f46e5; font-weight:600;">📎 Lihat Bukti Transfer</a>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Reject forms (hidden) --}}
        @if(!$isSalesRole)
        @foreach($hutang->pembayarans->where('status', 'pending') as $bayar)
        <div id="reject-{{ $bayar->id }}" style="display:none;" class="htd-confirm-form">
            <form action="{{ route('pasgar.hutang.confirm', $bayar->id) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="reject">
                <h4>Tolak Pembayaran #{{ $bayar->id }}</h4>
                <textarea name="reject_reason" class="htd-inp" placeholder="Alasan penolakan..." style="width:100%; padding:0.5rem; border:1.5px solid #e2e8f0; border-radius:8px; font-family:inherit; font-size:0.82rem; min-height:60px;"></textarea>
                <div class="htd-confirm-actions">
                    <button type="submit" class="htd-btn" style="background:#ef4444; color:#fff; padding:0.4rem 0.85rem;">Tolak</button>
                    <button type="button" onclick="showReject({{ $bayar->id }})" class="htd-btn" style="background:#f1f5f9; color:#64748b; padding:0.4rem 0.85rem;">Batal</button>
                </div>
            </form>
        </div>
        @endforeach
        @endif
        @else
        <div class="htd-tbl-empty">Belum ada pembayaran</div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function showReject(id) {
    const el = document.getElementById('reject-' + id);
    if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>
@endpush
@endsection
