@extends('layouts.app', ['title' => 'Detail Penjualan ' . $penjualan->no_faktur])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<style>
    .rx-page { font-family:'Plus Jakarta Sans',sans-serif; max-width:40rem; margin:0 auto; padding:1.25rem 1rem; }
    .rx-back { font-size:0.78rem; color:#d97706; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:4px; margin-bottom:1rem; }
    .rx-back:hover { text-decoration:underline; }
    .rx-header { text-align:center; margin-bottom:1.5rem; }
    .rx-header h1 { font-size:1.1rem; font-weight:800; color:#1e1b4b; margin:0; }
    .rx-header p { font-size:0.78rem; color:#64748b; margin:0.25rem 0 0; }
    .rx-number { font-family:'JetBrains Mono',monospace; font-size:0.85rem; font-weight:700; color:#d97706; background:#fef3c7; display:inline-block; padding:3px 10px; border-radius:8px; margin-top:0.5rem; }
    .rx-status-pending { display:inline-block; padding:0.3rem 0.85rem; border-radius:10px; font-size:0.75rem; font-weight:700; background:#fef3c7; color:#92400e; margin-top:0.5rem; }
    .rx-status-verified { display:inline-block; padding:0.3rem 0.85rem; border-radius:10px; font-size:0.75rem; font-weight:700; background:#d1fae5; color:#059669; margin-top:0.5rem; }

    .rx-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:1.25rem; margin-bottom:1rem; }
    .rx-info-grid { display:grid; grid-template-columns:1fr 1fr; gap:0.65rem; }
    .rx-info-label { font-size:0.65rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.3px; }
    .rx-info-value { font-size:0.82rem; color:#1e1b4b; font-weight:700; margin-top:0.1rem; }

    .rx-table { width:100%; border-collapse:collapse; margin-top:0.5rem; }
    .rx-table th { padding:0.5rem 0.5rem; text-align:left; font-size:0.65rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.4px; border-bottom:2px solid #e2e8f0; }
    .rx-table td { padding:0.6rem 0.5rem; font-size:0.8rem; color:#334155; border-bottom:1px solid #f1f5f9; }
    .rx-table tr:last-child td { border-bottom:none; }
    .rx-table .num { text-align:right; }
    .rx-table .mono { font-family:'JetBrains Mono',monospace; font-weight:700; color:#d97706; }

    .rx-total { display:flex; justify-content:space-between; align-items:center; padding:0.85rem 0; border-top:2px solid #e2e8f0; margin-top:0.5rem; }
    .rx-total-label { font-size:0.85rem; font-weight:800; color:#1e1b4b; }
    .rx-total-value { font-size:1.2rem; font-weight:800; color:#d97706; }

    .rx-pay { display:inline-block; padding:0.25rem 0.6rem; border-radius:8px; font-size:0.72rem; font-weight:700; }
    .rx-pay.tunai { background:#dcfce7; color:#166534; }
    .rx-pay.transfer { background:#dbeafe; color:#1d4ed8; }
    .rx-pay.hutang { background:#fee2e2; color:#991b1b; }
    .rx-transfer-box { background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:0.85rem 1rem; margin-top:0.75rem; }
    .rx-transfer-box .rx-tf-label { font-size:0.68rem; font-weight:600; color:#1d4ed8; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.5rem; display:flex; align-items:center; gap:0.35rem; }
    .rx-transfer-box .rx-tf-row { display:flex; flex-direction:column; gap:0.3rem; }
    .rx-transfer-box .rx-tf-item { font-size:0.8rem; }
    .rx-transfer-box .rx-tf-item span { font-weight:700; color:#1e3a5f; }
    .rx-transfer-box .rx-tf-img { margin-top:0.65rem; }
    .rx-transfer-box .rx-tf-img img { max-width:200px; border-radius:8px; border:1px solid #bfdbfe; cursor:pointer; }

    .rx-hutang-box { background:#fff7ed; border:1px solid #fed7aa; border-radius:10px; padding:0.85rem 1rem; margin-top:0.75rem; }
    .rx-hutang-title { font-size:0.68rem; font-weight:700; color:#9a3412; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem; }
    .rx-hutang-row { display:flex; justify-content:space-between; padding:0.2rem 0; }
    .rx-hutang-key { font-size:0.78rem; color:#c2410c; }
    .rx-hutang-val { font-size:0.78rem; font-weight:700; color:#9a3412; }

    .rx-actions { display:flex; gap:0.5rem; justify-content:center; margin-top:1rem; }
    .rx-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.6rem 1.1rem; border-radius:10px; font-size:0.78rem; font-weight:700; border:none; cursor:pointer; text-decoration:none; transition:all 0.2s; }
    .rx-btn-outline { background:#fff; color:#d97706; border:1.5px solid #f59e0b; }
    .rx-btn-outline:hover { background:#fffbeb; }
    .rx-btn-print { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 2px 8px rgba(245,158,11,0.2); }
    .rx-btn-print:hover { box-shadow:0 4px 16px rgba(245,158,11,0.3); }
    .rx-btn-verify { background:#10b981; color:#fff; box-shadow:0 2px 8px rgba(16,185,129,0.2); }
    .rx-btn-verify:hover { box-shadow:0 4px 16px rgba(16,185,129,0.3); }
    .rx-btn-del { background:#fff; color:#dc2626; border:1.5px solid #fca5a5; }
    .rx-btn-del:hover { background:#fef2f2; }
</style>
@endpush

@section('content')
<div class="rx-page">
    <a href="{{ route('gula.penjualan.index') }}" class="rx-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kembali ke Daftar Penjualan
    </a>

    <div class="rx-header">
        <h1>Detail Penjualan</h1>
        <p>{{ $penjualan->tanggal_jual->format('d/m/Y') }} &bull; {{ $penjualan->created_at->format('H:i') }}</p>
        <div class="rx-number">{{ $penjualan->no_faktur }}</div>
        <br>
        @if($penjualan->status === 'terverifikasi')
            <span class="rx-status-verified">&#10003; Terverifikasi</span>
        @else
            <span class="rx-status-pending">&#9679; Pending</span>
        @endif
    </div>

    @if(session('success'))
        <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;color:#059669;font-size:0.8rem;text-align:center;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;color:#991b1b;font-size:0.8rem;text-align:center;">{{ session('error') }}</div>
    @endif

    {{-- Info --}}
    <div class="rx-card">
        <div class="rx-info-grid">
            <div>
                <div class="rx-info-label">Sales</div>
                <div class="rx-info-value">{{ $penjualan->sales->nama ?? '-' }}</div>
                @if($penjualan->sales && $penjualan->sales->no_kendaraan)
                    <div style="font-size:0.68rem;color:#94a3b8;">{{ $penjualan->sales->no_kendaraan }}</div>
                @endif
            </div>
            <div>
                <div class="rx-info-label">Pembayaran</div>
                <div class="rx-info-value"><span class="rx-pay {{ $penjualan->tipe_bayar }}">{{ strtoupper($penjualan->tipe_bayar) }}</span></div>
            </div>
            <div>
                <div class="rx-info-label">Pelanggan</div>
                <div class="rx-info-value">{{ $penjualan->pelanggan->nama_toko ?? '-' }}</div>
                @if($penjualan->pelanggan)
                    <div style="font-size:0.68rem;color:#94a3b8;">{{ $penjualan->pelanggan->nama_pemilik ?? '' }}</div>
                @endif
            </div>
            <div>
                <div class="rx-info-label">Status</div>
                <div class="rx-info-value">{{ ucfirst($penjualan->status) }}</div>
            </div>
        </div>

        @if($penjualan->tipe_bayar === 'transfer' && ($penjualan->transfer_ref || $penjualan->foto_bukti_transfer))
        <div class="rx-transfer-box">
            <div class="rx-tf-label">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Detail Transfer
            </div>
            <div class="rx-tf-row">
                @if($penjualan->transfer_ref)
                <div class="rx-tf-item">ID Transaksi: <span>{{ $penjualan->transfer_ref }}</span></div>
                @endif
            </div>
            @if($penjualan->foto_bukti_transfer)
            <div class="rx-tf-img">
                <a href="{{ asset('storage/' . $penjualan->foto_bukti_transfer) }}" target="_blank">
                    <img src="{{ asset('storage/' . $penjualan->foto_bukti_transfer) }}" alt="Bukti Transfer">
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- Items --}}
    <div class="rx-card">
        <table class="rx-table">
            <thead>
                <tr>
                    <th style="width:45%">Produk</th>
                    <th class="num">Qty</th>
                    <th class="num">Harga</th>
                    <th class="num">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $penjualan->produk->nama ?? '-' }}</div>
                        <div style="font-size:0.68rem;color:#94a3b8;">{{ $penjualan->produk->satuan ?? '' }}</div>
                    </td>
                    <td class="num">{{ $penjualan->jumlah }}</td>
                    <td class="num mono">{{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</td>
                    <td class="num mono">{{ number_format($penjualan->total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        <div class="rx-total">
            <div class="rx-total-label">TOTAL</div>
            <div class="rx-total-value">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Pembayaran detail --}}
    <div class="rx-card">
        <div class="rx-info-grid" style="grid-template-columns:1fr 1fr 1fr;">
            <div>
                <div class="rx-info-label">Dibayar</div>
                <div class="rx-info-value">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</div>
            </div>
            @if($penjualan->kembali > 0)
            <div>
                <div class="rx-info-label">Kembalian</div>
                <div class="rx-info-value" style="color:#059669;">Rp {{ number_format($penjualan->kembali, 0, ',', '.') }}</div>
            </div>
            @endif
            @if($penjualan->hutang > 0)
            <div>
                <div class="rx-info-label">Sisa Hutang</div>
                <div class="rx-info-value" style="color:#dc2626;">Rp {{ number_format($penjualan->hutang, 0, ',', '.') }}</div>
            </div>
            @endif
        </div>

        @if($penjualan->tipe_bayar === 'hutang' && $penjualan->hutangRecord)
        <div class="rx-hutang-box">
            <div class="rx-hutang-title">Info Hutang</div>
            <div class="rx-hutang-row">
                <span class="rx-hutang-key">Jatuh Tempo</span>
                <span class="rx-hutang-val">{{ $penjualan->hutangRecord->jatuh_tempo ? $penjualan->hutangRecord->jatuh_tempo->format('d M Y') : '-' }}</span>
            </div>
            <div class="rx-hutang-row">
                <span class="rx-hutang-key">Status</span>
                <span class="rx-hutang-val">{{ ucfirst(str_replace('_', ' ', $penjualan->hutangRecord->status ?? '-')) }}</span>
            </div>
        </div>
        @endif
    </div>

    @if($penjualan->keterangan)
    <div class="rx-card">
        <div class="rx-info-label">Catatan</div>
        <div style="font-size:0.8rem;color:#475569;margin-top:0.25rem;">{{ $penjualan->keterangan }}</div>
    </div>
    @endif

    @if($penjualan->latitude && $penjualan->longitude)
    <div style="margin-bottom:1rem;">
        <a href="https://maps.google.com/?q={{ $penjualan->latitude }},{{ $penjualan->longitude }}" target="_blank"
           style="font-size:0.78rem;color:#d97706;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Lihat di Google Maps
        </a>
    </div>
    @endif

    <div class="rx-actions">
        <a href="{{ route('gula.penjualan.index') }}" class="rx-btn rx-btn-outline">&#8592; Daftar Penjualan</a>
        @if(Route::has('gula.penjualan.print'))
        <a href="{{ route('gula.penjualan.print', $penjualan->id) }}" class="rx-btn rx-btn-print" target="_blank">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Cetak Struk
        </a>
        @endif
        @if($penjualan->status == 'pending' && Route::has('gula.penjualan.verify'))
        <form action="{{ route('gula.penjualan.verify', $penjualan) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="rx-btn rx-btn-verify">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Verifikasi
            </button>
        </form>
        @endif
        @if($penjualan->status == 'pending' && Route::has('gula.penjualan.destroy'))
        <form action="{{ route('gula.penjualan.destroy', $penjualan) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus transaksi ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="rx-btn rx-btn-del">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Hapus
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
