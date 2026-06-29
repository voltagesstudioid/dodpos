@extends('layouts.app', ['title' => 'Detail Opname ' . $opname->nomor_opname . ' - Pasgar'])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .os-wrap{font-family:'Plus Jakarta Sans',sans-serif;max-width:900px;margin:0 auto;padding:1.25rem}
    .os-hero{background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:16px;padding:1.5rem;color:#fff;margin-bottom:1.5rem;position:relative;overflow:hidden}
    .os-hero::after{content:'';position:absolute;top:-30px;right:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.08)}
    .os-hero-top{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem}
    .os-hero h1{font-size:1.3rem;font-weight:800;margin:0}
    .os-hero p{font-size:.8rem;opacity:.85;margin:.25rem 0 0}
    .os-hero-badge{display:inline-block;padding:.3rem .75rem;border-radius:99px;font-size:.72rem;font-weight:700}
    .os-hero-badge.pending{background:rgba(255,255,255,.2);color:#fff}
    .os-hero-badge.confirmed{background:#d1fae5;color:#065f46}
    .os-hero-meta{display:flex;gap:1.5rem;margin-top:1rem;flex-wrap:wrap}
    .os-hero-meta-item{font-size:.78rem;opacity:.9}
    .os-hero-meta-item strong{display:block;font-size:.95rem;font-weight:800;opacity:1}

    .os-card{background:#fff;border:1px solid #fef3c7;border-radius:14px;padding:1.5rem;margin-bottom:1.25rem}
    .os-card-title{font-size:.85rem;font-weight:800;color:#92400e;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
    .os-card-title svg{width:18px;height:18px;stroke:#d97706}

    /* Items table */
    .os-table{width:100%;border-collapse:collapse}
    .os-table th{padding:.6rem .5rem;font-size:.68rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.3px;text-align:left;border-bottom:2px solid #fef3c7;background:#fffbeb}
    .os-table td{padding:.6rem .5rem;font-size:.8rem;color:#374151;border-bottom:1px solid #f3f4f6}
    .os-selisih{display:inline-block;padding:.2rem .55rem;border-radius:8px;font-size:.72rem;font-weight:700}
    .os-selisih.pas{background:#d1fae5;color:#065f46}
    .os-selisih.lebih{background:#fef3c7;color:#92400e}
    .os-selisih.kurang{background:#fee2e2;color:#991b1b}

    /* Summary */
    .os-summary{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:.75rem;margin-bottom:1rem}
    .os-summary-card{background:#fffbeb;border:1px solid #fef3c7;border-radius:10px;padding:.85rem;text-align:center}
    .os-summary-val{font-size:1.3rem;font-weight:800;color:#78350f}
    .os-summary-lbl{font-size:.68rem;color:#92400e;font-weight:600;margin-top:2px}

    /* Movements */
    .os-movement{display:flex;align-items:center;gap:.75rem;padding:.65rem 0;border-bottom:1px solid #f3f4f6}
    .os-movement:last-child{border-bottom:none}
    .os-movement-ico{width:36px;height:36px;border-radius:10px;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .os-movement-ico svg{width:18px;height:18px;stroke:#059669}
    .os-movement-info{flex:1;min-width:0}
    .os-movement-info h4{margin:0;font-size:.82rem;font-weight:700;color:#1f2937}
    .os-movement-info p{margin:2px 0 0;font-size:.72rem;color:#6b7280}
    .os-movement-qty{font-size:.9rem;font-weight:800;color:#059669}

    /* Verify form */
    .os-verify{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;padding:1.5rem}
    .os-verify h3{font-size:.9rem;font-weight:800;color:#065f46;margin:0 0 1rem}
    .os-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.6rem 1.1rem;border-radius:10px;font-size:.78rem;font-weight:700;text-decoration:none;border:none;cursor:pointer;transition:all .2s}
    .os-btn-confirm{background:linear-gradient(135deg,#10b981,#059669);color:#fff;box-shadow:0 3px 10px rgba(16,185,129,.2)}
    .os-btn-confirm:hover{box-shadow:0 5px 16px rgba(16,185,129,.3);transform:translateY(-1px)}
    .os-btn-back{background:#f3f4f6;color:#374151}
    .os-btn-back:hover{background:#e5e7eb}
    .os-textarea{width:100%;padding:.6rem .85rem;border:1px solid #d1d5db;border-radius:10px;font-size:.82rem;font-family:inherit;resize:vertical;min-height:60px;box-sizing:border-box;margin-bottom:.75rem}
    .os-textarea:focus{outline:none;border-color:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.12)}

    .os-actions{display:flex;gap:.75rem;margin-top:1rem}

    /* Verification info */
    .os-verif-info{display:flex;gap:1rem;flex-wrap:wrap}
    .os-verif-item{flex:1;min-width:150px}
    .os-verif-item label{font-size:.68rem;font-weight:600;color:#6b7280;display:block;margin-bottom:.15rem}
    .os-verif-item span{font-size:.85rem;font-weight:700;color:#1f2937}

    @media(max-width:640px){.os-summary{grid-template-columns:repeat(2,1fr)}.os-table{font-size:.75rem}}

    @media (max-width: 768px) {
        [class$="-grid"], [class*="-grid "] { grid-template-columns: repeat(2, 1fr) !important; }
    }
    @media (max-width: 480px) {
        [class$="-grid"], [class*="-grid "] { grid-template-columns: 1fr !important; }
    }
</style>
@endpush

@section('content')
<div class="os-wrap">
    @if(session('success'))
    <div style="background:#d1fae5;border:1px solid #a7f3d0;color:#065f46;padding:0.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;font-weight:600;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:0.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;font-weight:600;">{{ session('error') }}</div>
    @endif
    {{-- Hero Header --}}
    <div class="os-hero">
        <div class="os-hero-top">
            <div>
                <h1>{{ $opname->nomor_opname }}</h1>
                <p>Opname Barang Sisa — {{ $opname->loading->nomor_loading ?? '-' }}</p>
            </div>
            <span class="os-hero-badge {{ $opname->status }}">{{ $opname->status_label }}</span>
        </div>
        <div class="os-hero-meta">
            <div class="os-hero-meta-item"><strong>{{ $opname->sales->nama ?? '-' }}</strong>Sales</div>
            <div class="os-hero-meta-item"><strong>{{ $opname->tanggal->format('d M Y') }}</strong>Tanggal</div>
            <div class="os-hero-meta-item"><strong>{{ $opname->items->count() }}</strong>Item</div>
            <div class="os-hero-meta-item"><strong>{{ number_format($opname->items->sum('qty_fisik')) }}</strong>Total Kembali</div>
        </div>
    </div>

    {{-- Summary --}}
    <div class="os-summary">
        <div class="os-summary-card">
            <div class="os-summary-val">{{ $opname->items->count() }}</div>
            <div class="os-summary-lbl">Total Item</div>
        </div>
        <div class="os-summary-card">
            <div class="os-summary-val">{{ number_format($opname->items->sum('qty_sisa_sistem')) }}</div>
            <div class="os-summary-lbl">Sisa Sistem</div>
        </div>
        <div class="os-summary-card">
            <div class="os-summary-val">{{ number_format($opname->items->sum('qty_fisik')) }}</div>
            <div class="os-summary-lbl">Total Fisik</div>
        </div>
        <div class="os-summary-card">
            @php $totalSelisih = $opname->items->sum('qty_selisih'); @endphp
            <div class="os-summary-val" style="color:{{ $totalSelisih === 0 ? '#065f46' : ($totalSelisih > 0 ? '#92400e' : '#991b1b') }}">
                {{ $totalSelisih > 0 ? '+' : '' }}{{ $totalSelisih }}
            </div>
            <div class="os-summary-lbl">Total Selisih</div>
        </div>
    </div>

    {{-- Item Breakdown --}}
    <div class="os-card">
        <div class="os-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            Detail Item
        </div>
        <div style="overflow-x:auto">
            <table class="os-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th style="text-align:center">Satuan</th>
                        <th style="text-align:center">Sisa Sistem</th>
                        <th style="text-align:center">Qty Fisik</th>
                        <th style="text-align:center">Selisih</th>
                        <th>Gudang Tujuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opname->items as $item)
                    <tr>
                        <td>
                            <div style="font-weight:700;color:#1f2937">{{ $item->product->name ?? '-' }}</div>
                            <div style="font-size:.68rem;color:#9ca3af">{{ $item->product->sku ?? '' }}</div>
                        </td>
                        <td style="text-align:center;font-weight:600;color:#6b7280;font-size:.78rem">{{ $item->loadingItem?->unitConversion?->unit?->name ?? 'pcs' }}</td>
                        <td style="text-align:center;font-weight:600">{{ $item->qty_sisa_sistem }}</td>
                        <td style="text-align:center;font-weight:700">{{ $item->qty_fisik }}</td>
                        <td style="text-align:center">
                            @if($item->qty_selisih === 0)
                                <span class="os-selisih pas">Pas</span>
                            @elseif($item->qty_selisih > 0)
                                <span class="os-selisih lebih">+{{ $item->qty_selisih }} Lebih</span>
                            @else
                                <span class="os-selisih kurang">{{ $item->qty_selisih }} Kurang</span>
                            @endif
                        </td>
                        <td style="font-size:.78rem">{{ $item->warehouse->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Stock Movements --}}
    @if($stockMovements->isNotEmpty())
    <div class="os-card">
        <div class="os-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            Stok Dikembalikan ke Gudang
        </div>
        @foreach($stockMovements as $mv)
        <div class="os-movement">
            <div class="os-movement-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
            </div>
            <div class="os-movement-info">
                <h4>{{ $mv->product->name ?? '-' }}</h4>
                <p>{{ $mv->warehouse->name ?? '-' }} — Balance: {{ $mv->balance }}</p>
            </div>
            <div class="os-movement-qty">+{{ $mv->quantity }}</div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Catatan --}}
    @if($opname->catatan)
    <div class="os-card">
        <div class="os-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Catatan
        </div>
        <p style="font-size:.85rem;color:#374151;margin:0;white-space:pre-line">{{ $opname->catatan }}</p>
    </div>
    @endif

    {{-- Verification Info --}}
    @if($opname->status === 'confirmed')
    <div class="os-card">
        <div class="os-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Informasi Verifikasi
        </div>
        <div class="os-verif-info">
            <div class="os-verif-item">
                <label>Dikonfirmasi Oleh</label>
                <span>{{ $opname->confirmer->name ?? '-' }}</span>
            </div>
            <div class="os-verif-item">
                <label>Tanggal Konfirmasi</label>
                <span>{{ $opname->confirmed_at?->format('d M Y H:i') ?? '-' }}</span>
            </div>
        </div>
    </div>
    @endif

    {{-- Supervisor Confirm Form --}}
    @if($opname->status === 'pending')
    @php
        $user = Auth::user();
        $role = strtolower($user->role ?? '');
        $isSupervisor = in_array($role, ['supervisor', 'admin1', 'admin2']);
    @endphp
    @if($isSupervisor)
    <div class="os-verify">
        <h3>Konfirmasi Opname</h3>
        <p style="font-size:.82rem;color:#374151;margin:0 0 1rem">
            Dengan mengkonfirmasi, Anda menyetujui data opname ini. Stok telah dikembalikan ke gudang.
        </p>
        <form action="{{ route('pasgar.opname.confirm', $opname->id) }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="confirm">
            <textarea name="catatan" class="os-textarea" placeholder="Catatan verifikasi (opsional)..."></textarea>
            <div style="display:flex;gap:.75rem">
                <button type="submit" class="os-btn os-btn-confirm" onclick="return confirm('Yakin mengkonfirmasi opname ini?')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Konfirmasi Opname
                </button>
            </div>
        </form>
    </div>
    @endif
    @endif

    {{-- Back --}}
    <div class="os-actions">
        <a href="{{ route('pasgar.opname.index') }}" class="os-btn os-btn-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali
        </a>
    </div>
</div>
@endsection
