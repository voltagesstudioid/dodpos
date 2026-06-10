@extends('layouts.app', ['title' => 'Detail Setoran - Gula'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600;700&display=swap');
    .sd-page { max-width:68rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }
    .sd-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.25rem; }
    .sd-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; }
    .sd-back:hover { color:#d97706; }
    .sd-sep { color:#cbd5e1; }
    .sd-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

    .sd-alert { padding:0.75rem 1rem; border-radius:12px; margin-bottom:1rem; font-size:0.82rem; font-weight:600; display:flex; align-items:center; gap:0.5rem; }
    .sd-alert svg { width:18px; height:18px; flex-shrink:0; }
    .sd-alert-success { background:linear-gradient(135deg,#ecfdf5,#d1fae5); border:1px solid #a7f3d0; color:#065f46; }
    .sd-alert-error { background:linear-gradient(135deg,#fef2f2,#fee2e2); border:1px solid #fecaca; color:#991b1b; }
    .sd-alert-warn { background:linear-gradient(135deg,#fffbeb,#fef3c7); border:1px solid #fde68a; color:#92400e; }

    /* Status Header */
    @php
        $hg = ['pending'=>'linear-gradient(135deg,#fffbeb,#fef3c7,#fde68a)','terverifikasi'=>'linear-gradient(135deg,#ecfdf5,#d1fae5,#a7f3d0)','ditolak'=>'linear-gradient(135deg,#fef2f2,#fee2e2,#fecaca)'];
        $hb = ['pending'=>'#f59e0b','terverifikasi'=>'#10b981','ditolak'=>'#ef4444'];
    @endphp
    .sd-hdr { border-radius:18px; padding:1.5rem 1.75rem; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; border:1.5px solid; overflow:hidden; }
    .sd-hdr.pending { background:{{ $hg['pending'] }}; border-color:{{ $hb['pending'] }}; }
    .sd-hdr.terverifikasi { background:{{ $hg['terverifikasi'] }}; border-color:{{ $hb['terverifikasi'] }}; }
    .sd-hdr.ditolak { background:{{ $hg['ditolak'] }}; border-color:{{ $hb['ditolak'] }}; }
    .sd-hdr-left { display:flex; align-items:center; gap:1rem; }
    .sd-hdr-icon { width:48px; height:48px; border-radius:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .sd-hdr-icon svg { width:24px; height:24px; color:#fff; }
    .sd-hdr-icon.pending { background:linear-gradient(135deg,#f59e0b,#d97706); }
    .sd-hdr-icon.terverifikasi { background:linear-gradient(135deg,#10b981,#059669); }
    .sd-hdr-icon.ditolak { background:linear-gradient(135deg,#ef4444,#dc2626); }
    .sd-hdr-title { font-size:1.15rem; font-weight:800; color:#0f172a; margin:0; }
    .sd-hdr-sub { font-size:0.78rem; color:#64748b; margin-top:0.25rem; display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; }
    .sd-hdr-sub svg { width:13px; height:13px; color:#94a3b8; }
    .sd-st { display:inline-flex; align-items:center; gap:0.3rem; padding:0.25rem 0.7rem; border-radius:99px; font-size:0.72rem; font-weight:700; border:1px solid; }
    .sd-st.pending { background:#fef3c7; color:#92400e; border-color:#fde68a; }
    .sd-st.terverifikasi { background:#d1fae5; color:#065f46; border-color:#a7f3d0; }
    .sd-st.ditolak { background:#fee2e2; color:#dc2626; border-color:#fecaca; }
    .sd-st svg { width:13px; height:13px; }
    .sd-hdr-actions { display:flex; gap:0.5rem; }
    .sd-btn { display:inline-flex; align-items:center; gap:0.35rem; padding:0.55rem 1rem; border-radius:10px; font-size:0.78rem; font-weight:700; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; }
    .sd-btn svg { width:15px; height:15px; }
    .sd-btn-edit { background:#fff; color:#92400e; border:1.5px solid #fde68a; }
    .sd-btn-edit:hover { background:#fef3c7; }
    .sd-btn-del { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
    .sd-btn-del:hover { background:#fee2e2; }

    /* Cards */
    .sd-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.03); margin-bottom:1.25rem; }
    .sd-card-hdr { padding:0.85rem 1.25rem; display:flex; align-items:center; gap:0.6rem; border-bottom:1px solid #f1f5f9; }
    .sd-card-ico { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .sd-card-ico svg { width:16px; height:16px; color:#fff; }
    .sd-card-title { font-size:0.8125rem; font-weight:700; color:#0f172a; }
    .sd-card-badge { margin-left:auto; font-size:0.68rem; font-weight:700; padding:0.15rem 0.5rem; border-radius:6px; }
    .sd-card-body { padding:1rem 1.25rem; }
    .sd-card.amber .sd-card-hdr { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
    .sd-card.amber .sd-card-ico { background:linear-gradient(135deg,#f59e0b,#d97706); }
    .sd-card.green .sd-card-hdr { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
    .sd-card.green .sd-card-ico { background:linear-gradient(135deg,#10b981,#059669); }
    .sd-card.indigo .sd-card-hdr { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
    .sd-card.indigo .sd-card-ico { background:linear-gradient(135deg,#6366f1,#4f46e5); }
    .sd-card.pink .sd-card-hdr { background:linear-gradient(135deg,#fdf2f8,#fce7f3); }
    .sd-card.pink .sd-card-ico { background:linear-gradient(135deg,#ec4899,#db2777); }
    .sd-card.blue .sd-card-hdr { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
    .sd-card.blue .sd-card-ico { background:linear-gradient(135deg,#3b82f6,#2563eb); }
    .sd-card.red .sd-card-hdr { background:linear-gradient(135deg,#fef2f2,#fee2e2); }
    .sd-card.red .sd-card-ico { background:linear-gradient(135deg,#ef4444,#dc2626); }

    /* Cash Flow */
    .cf-step { display:flex; align-items:center; gap:0.75rem; padding:0.6rem 0; border-bottom:1px solid #f1f5f9; }
    .cf-step:last-child { border-bottom:none; }
    .cf-step-num { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:800; flex-shrink:0; }
    .cf-step-num.add { background:#dbeafe; color:#1d4ed8; }
    .cf-step-num.total { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
    .cf-step-num.result { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
    .cf-step-label { font-size:0.8rem; color:#475569; flex:1; }
    .cf-step-label strong { color:#1e293b; }
    .cf-step-value { font-family:'JetBrains Mono',monospace; font-size:0.82rem; font-weight:700; text-align:right; min-width:120px; }
    .cf-step-value.green { color:#059669; }
    .cf-step-value.blue { color:#1d4ed8; }
    .cf-step-value.red { color:#dc2626; }
    .cf-step-value.amber { color:#d97706; }
    .cf-step-sub { font-size:0.68rem; color:#94a3b8; margin-top:1px; }
    .cf-divider { border:none; border-top:2px dashed #e2e8f0; margin:0.5rem 0; }

    /* Selisih */
    .sd-selisih { text-align:center; padding:1rem; border-radius:12px; margin-top:0.5rem; }
    .sd-selisih.pas { background:#d1fae5; border:1.5px solid #a7f3d0; }
    .sd-selisih.lebih { background:#dbeafe; border:1.5px solid #bfdbfe; }
    .sd-selisih.kurang { background:#fee2e2; border:1.5px solid #fecaca; }
    .sd-selisih-label { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; }
    .sd-selisih-value { font-size:1.5rem; font-weight:800; margin-top:0.25rem; font-family:'JetBrains Mono',monospace; }

    /* Tables */
    .sd-tbl { width:100%; border-collapse:separate; border-spacing:0; font-size:0.78rem; }
    .sd-tbl thead th { background:#f8fafc; padding:0.65rem 0.85rem; font-size:0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; text-align:left; border-bottom:2px solid #e2e8f0; white-space:nowrap; }
    .sd-tbl tbody td { padding:0.65rem 0.85rem; border-bottom:1px solid #f1f5f9; color:#374151; vertical-align:middle; }
    .sd-tbl tbody tr:hover td { background:#fafbfc; }
    .sd-tbl tbody tr:last-child td { border-bottom:none; }
    .sd-tbl tfoot td { padding:0.65rem 0.85rem; font-weight:700; border-top:2px solid #e2e8f0; background:#f8fafc; }
    .sd-mono { font-family:'JetBrains Mono',monospace; font-size:0.75rem; }
    .sd-right { text-align:right; }
    .sd-center { text-align:center; }
    .sd-bold { font-weight:700; }
    .sd-method { display:inline-flex; align-items:center; gap:0.25rem; padding:0.15rem 0.5rem; border-radius:6px; font-size:0.68rem; font-weight:700; }
    .sd-method.tunai { background:#d1fae5; color:#065f46; }
    .sd-method.transfer { background:#dbeafe; color:#1d4ed8; }
    .sd-method.hutang { background:#fef3c7; color:#92400e; }

    /* Photo & modal */
    .sd-foto { max-width:100%; max-height:350px; border-radius:12px; border:2px solid #e2e8f0; box-shadow:0 4px 16px rgba(0,0,0,0.08); cursor:zoom-in; }
    .sd-foto:hover { transform:scale(1.01); }
    .sd-foto-hint { font-size:0.68rem; color:#94a3b8; margin-top:0.5rem; display:flex; align-items:center; justify-content:center; gap:0.3rem; }
    .sd-modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); z-index:9999; justify-content:center; align-items:center; cursor:zoom-out; }
    .sd-modal.active { display:flex; }
    .sd-modal img { max-width:92%; max-height:92%; border-radius:8px; }
    .sd-modal-close { position:absolute; top:16px; right:20px; width:40px; height:40px; border-radius:50%; background:rgba(255,255,255,0.15); border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; }
    .sd-modal-close svg { width:20px; height:20px; color:#fff; }

    .sd-info { width:100%; }
    .sd-info td { padding:0.4rem 0; font-size:0.8125rem; vertical-align:top; }
    .sd-info td:first-child { color:#94a3b8; font-weight:600; width:42%; font-size:0.73rem; text-transform:uppercase; letter-spacing:0.04em; }
    .sd-info td:last-child { color:#1e293b; font-weight:500; }

    .sd-catatan { margin-top:0.75rem; padding:0.65rem 0.85rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; font-size:0.78rem; color:#475569; }
    .sd-catatan strong { color:#1e293b; }

    .sd-empty { text-align:center; padding:1.5rem; color:#94a3b8; font-size:0.82rem; }

    /* Verify */
    .sd-verify { background:linear-gradient(135deg,#f8fafc,#f1f5f9); border:1px solid #e2e8f0; border-radius:16px; padding:1.5rem; margin-bottom:1.25rem; }
    .sd-verify-hdr { display:flex; align-items:center; gap:0.6rem; margin-bottom:1rem; }
    .sd-verify-ico { width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,#f59e0b,#d97706); display:flex; align-items:center; justify-content:center; }
    .sd-verify-ico svg { width:18px; height:18px; color:#fff; }
    .sd-verify h3 { font-size:0.88rem; font-weight:700; color:#1e1b4b; margin:0; }
    .sd-verify p { font-size:0.75rem; color:#64748b; margin:2px 0 0; }
    .sd-verify-fg { display:flex; flex-direction:column; gap:0.35rem; margin-bottom:1rem; }
    .sd-verify-fg label { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
    .sd-verify-fg textarea { width:100%; padding:0.6rem 0.8rem; border:1.5px solid #e2e8f0; border-radius:10px; font-family:inherit; font-size:0.82rem; min-height:70px; box-sizing:border-box; outline:none; background:#fff; resize:vertical; }
    .sd-verify-fg textarea:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
    .sd-verify-actions { display:flex; gap:0.5rem; }
    .sd-verify-btn { padding:0.65rem 1.5rem; border-radius:12px; font-size:0.82rem; font-weight:700; border:none; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem; transition:all 0.2s; font-family:inherit; }
    .sd-verify-btn svg { width:16px; height:16px; }
    .sd-verify-btn.approve { background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 2px 8px rgba(16,185,129,0.25); }
    .sd-verify-btn.approve:hover { box-shadow:0 4px 16px rgba(16,185,129,0.35); }
    .sd-verify-btn.reject { background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; box-shadow:0 2px 8px rgba(239,68,68,0.25); }
    .sd-verify-btn.reject:hover { box-shadow:0 4px 16px rgba(239,68,68,0.35); }

    .sd-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
    @media(max-width:900px) { .sd-grid2 { grid-template-columns:1fr; } }
    @media(max-width:768px) { .sd-hdr { flex-direction:column; align-items:flex-start; } .sd-hdr-actions { width:100%; } .sd-verify-actions { flex-direction:column; } }
</style>
@endpush

@section('content')
<div class="sd-page">
    <nav class="sd-nav">
        <a href="{{ route('gula.setoran.index') }}" class="sd-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Daftar Setoran
        </a>
        <span class="sd-sep">/</span>
        <span class="sd-crumb">Detail Setoran</span>
    </nav>

    @if(session('success'))
    <div class="sd-alert sd-alert-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="sd-alert sd-alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        {{ session('error') }}
    </div>
    @endif
    @if($driftDetected)
    <div class="sd-alert sd-alert-warn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <div><strong>Perhatian:</strong> Data penjualan berubah sejak setoran dibuat. Periksa rekonsiliasi di bawah.</div>
    </div>
    @endif

    {{-- Status Header --}}
    <div class="sd-hdr {{ $setoran->status }}">
        <div class="sd-hdr-left">
            <div class="sd-hdr-icon {{ $setoran->status }}">
                @if($setoran->status === 'pending')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                @elseif($setoran->status === 'terverifikasi')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                @else<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                @endif
            </div>
            <div>
                <div class="sd-hdr-title">Setoran {{ $setoran->tanggal->format('d M Y') }}</div>
                <div class="sd-hdr-sub">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    {{ $setoran->sales->nama ?? '-' }}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ $setoran->tanggal->format('d M Y') }}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    {{ $penjualans->count() }} transaksi
                </div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
            @if($setoran->status === 'pending')<span class="sd-st pending"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Pending Verifikasi</span>
            @elseif($setoran->status === 'terverifikasi')<span class="sd-st terverifikasi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Terverifikasi</span>
            @else<span class="sd-st ditolak"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>Ditolak</span>
            @endif
            <div class="sd-hdr-actions">
                @if($setoran->status === 'pending')
                <a href="{{ route('gula.setoran.edit', $setoran->id) }}" class="sd-btn sd-btn-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
                <form action="{{ route('gula.setoran.destroy', $setoran->id) }}" method="POST" onsubmit="return confirm('Yakin hapus setoran ini?')" style="display:inline;">@csrf @method('DELETE')
                    <button type="submit" class="sd-btn sd-btn-del"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>Hapus</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- CASH FLOW RECONCILIATION --}}
    <div class="sd-card blue">
        <div class="sd-card-hdr">
            <div class="sd-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
            <div class="sd-card-title">Rekonsiliasi Arus Kas</div>
        </div>
        <div class="sd-card-body">
            <div class="cf-step">
                <div class="cf-step-num add">1</div>
                <div class="cf-step-label"><strong>Penjualan Tunai</strong> — {{ $penjualans->where('tipe_bayar','tunai')->count() }} transaksi<div class="cf-step-sub">Uang tunai diterima langsung dari pelanggan</div></div>
                <div class="cf-step-value green">Rp {{ number_format($tunaiFromCash, 0, ',', '.') }}</div>
            </div>
            <div class="cf-step">
                <div class="cf-step-num add">2</div>
                <div class="cf-step-label"><strong>Bayar di Tempat (Hutang)</strong> — {{ $penjualans->where('tipe_bayar','hutang')->count() }} transaksi<div class="cf-step-sub">Uang tunai dibayar saat transaksi hutang</div></div>
                <div class="cf-step-value green">Rp {{ number_format($tunaiFromHutang, 0, ',', '.') }}</div>
            </div>
            <div class="cf-step">
                <div class="cf-step-num add">3</div>
                <div class="cf-step-label"><strong>Pembayaran Hutang Lama (Tunai)</strong> — {{ $hutangPayments->count() }} pembayaran<div class="cf-step-sub">Cicilan tunai dari hutang penjualan sebelumnya</div></div>
                <div class="cf-step-value green">Rp {{ number_format($hutangDibayar, 0, ',', '.') }}</div>
            </div>
            <div class="cf-step" style="padding-top:0.5rem;">
                <div class="cf-step-num total">4</div>
                <div class="cf-step-label"><strong>Seharusnya Disetor</strong><div class="cf-step-sub">(1) + (2) + (3) = Total uang tunai yang wajib disetor</div></div>
                <div class="cf-step-value amber" style="font-size:0.95rem;">Rp {{ number_format($seharusnyaSetor, 0, ',', '.') }}</div>
            </div>
            <hr class="cf-divider">
            <div class="cf-step">
                <div class="cf-step-num result">5</div>
                <div class="cf-step-label"><strong>Total Disetor (Aktual)</strong><div class="cf-step-sub">Uang tunai yang benar-benar disetorkan sales</div></div>
                <div class="cf-step-value" style="font-size:0.95rem; color:#0f172a;">Rp {{ number_format($setoran->total_setor, 0, ',', '.') }}</div>
            </div>
            @php
                $selisihType = $setoran->selisih == 0 ? 'pas' : ($setoran->selisih > 0 ? 'lebih' : 'kurang');
                $selisihColor = $setoran->selisih == 0 ? '#059669' : ($setoran->selisih > 0 ? '#1d4ed8' : '#dc2626');
                $selisihLabel = $setoran->selisih == 0 ? 'PAS' : ($setoran->selisih > 0 ? 'LEBIH' : 'KURANG');
            @endphp
            <div class="cf-step">
                <div class="cf-step-num" style="background:{{ $selisihColor }}20; color:{{ $selisihColor }};">6</div>
                <div class="cf-step-label"><strong>Selisih</strong> = (5) - (4)<div class="cf-step-sub">{{ $setoran->selisih == 0 ? 'Setoran pas, tidak ada selisih' : ($setoran->selisih > 0 ? 'Sales setor LEBIH dari seharusnya' : 'Sales setor KURANG dari seharusnya') }}</div></div>
                <div class="cf-step-value" style="color:{{ $selisihColor }};font-size:0.95rem;">{{ $setoran->selisih > 0 ? '+' : ($setoran->selisih < 0 ? '-' : '') }}Rp {{ number_format(abs($setoran->selisih), 0, ',', '.') }}</div>
            </div>
            <div class="sd-selisih {{ $selisihType }}">
                <div class="sd-selisih-label" style="color:{{ $selisihColor }};">Status: {{ $selisihLabel }}</div>
                <div class="sd-selisih-value" style="color:{{ $selisihColor }};">{{ $setoran->selisih > 0 ? '+' : ($setoran->selisih < 0 ? '-' : '') }}Rp {{ number_format(abs($setoran->selisih), 0, ',', '.') }}</div>
            </div>
            {{-- Info: Transfer tidak masuk setoran tunai --}}
            @if($summary['total_transfer'] > 0)
            <div style="margin-top:0.75rem;padding:0.55rem 0.85rem;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;font-size:0.75rem;color:#1d4ed8;display:flex;align-items:flex-start;gap:0.4rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="width:15px;height:15px;flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <div>Transfer sebesar <strong>Rp {{ number_format($summary['total_transfer'], 0, ',', '.') }}</strong> <u>tidak termasuk</u> dalam setoran tunai (sudah masuk rekening bank).</div>
            </div>
            @endif
            @if($setoran->catatan_sales)
            <div class="sd-catatan"><strong>Catatan Sales:</strong> {{ $setoran->catatan_sales }}</div>
            @endif
        </div>
    </div>

    {{-- DAFTAR TRANSAKSI PENJUALAN --}}
    <div class="sd-card amber">
        <div class="sd-card-hdr">
            <div class="sd-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div>
            <div class="sd-card-title">Daftar Transaksi Penjualan</div>
            <span class="sd-card-badge" style="background:#fef3c7;color:#92400e;">{{ $penjualans->count() }} transaksi</span>
        </div>
        <div class="sd-card-body" style="padding:0;">
            @if($penjualans->isNotEmpty())
            <div style="overflow-x:auto;">
            <table class="sd-tbl">
                <thead>
                    <tr>
                        <th style="width:3%">#</th>
                        <th>No. Faktur</th>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th class="sd-center">Tipe Bayar</th>
                        <th class="sd-right">Total</th>
                        <th class="sd-right">Bayar</th>
                        <th class="sd-right">Kas Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualans as $i => $p)
                    @php
                        $kasMasuk = 0;
                        if ($p->tipe_bayar === 'tunai') { $kasMasuk = (float) $p->total; }
                        elseif ($p->tipe_bayar === 'hutang') { $kasMasuk = (float) ($p->bayar ?? 0); }
                    @endphp
                    <tr>
                        <td class="sd-mono">{{ $i + 1 }}</td>
                        <td><div class="sd-mono sd-bold">{{ $p->no_faktur }}</div><div style="font-size:0.68rem;color:#94a3b8;">{{ $p->tanggal_jual->format('d/m/Y H:i') }}</div></td>
                        <td class="sd-bold">{{ $p->pelanggan->nama ?? '-' }}</td>
                        <td style="font-size:0.75rem;">{{ $p->produk->nama ?? '-' }} <span class="sd-mono" style="color:#94a3b8;">{{ $p->jumlah }}</span></td>
                        <td class="sd-center"><span class="sd-method {{ $p->tipe_bayar }}">{{ strtoupper($p->tipe_bayar) }}</span></td>
                        <td class="sd-right sd-mono sd-bold">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                        <td class="sd-right sd-mono" style="color:#64748b;">{{ $p->tipe_bayar === 'hutang' ? 'Rp ' . number_format($p->bayar ?? 0, 0, ',', '.') : '-' }}</td>
                        <td class="sd-right sd-mono" style="color:{{ $kasMasuk > 0 ? '#059669' : '#94a3b8' }};font-weight:700;">Rp {{ number_format($kasMasuk, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="sd-bold">Total</td>
                        <td class="sd-right sd-mono sd-bold">Rp {{ number_format($penjualans->sum('total'), 0, ',', '.') }}</td>
                        <td class="sd-right sd-mono" style="color:#64748b;">Rp {{ number_format($penjualans->where('tipe_bayar','hutang')->sum('bayar'), 0, ',', '.') }}</td>
                        <td class="sd-right sd-mono sd-bold" style="color:#059669;">Rp {{ number_format($tunaiFromCash + $tunaiFromHutang, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
            </div>
            @else
            <div class="sd-empty">Tidak ada data penjualan</div>
            @endif
        </div>
    </div>

    {{-- HUTANG DETAILS --}}
    @if($hutangs->isNotEmpty())
    <div class="sd-card red">
        <div class="sd-card-hdr">
            <div class="sd-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
            <div class="sd-card-title">Hutang Pelanggan dari Tanggal Ini</div>
            <span class="sd-card-badge" style="background:#fee2e2;color:#991b1b;">{{ $hutangs->count() }} hutang</span>
        </div>
        <div class="sd-card-body" style="padding:0;">
            <div style="overflow-x:auto;">
            <table class="sd-tbl">
                <thead>
                    <tr>
                        <th style="width:3%">#</th>
                        <th>Pelanggan</th>
                        <th>No. Faktur</th>
                        <th class="sd-right">Total Hutang</th>
                        <th class="sd-right">Sisa Hutang</th>
                        <th class="sd-center">Jatuh Tempo</th>
                        <th class="sd-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hutangs as $i => $h)
                    <tr>
                        <td class="sd-mono">{{ $i + 1 }}</td>
                        <td class="sd-bold">{{ $h->pelanggan->nama ?? '-' }}</td>
                        <td class="sd-mono" style="font-size:0.72rem;">{{ $h->penjualan->no_faktur ?? '-' }}</td>
                        <td class="sd-right sd-mono sd-bold">Rp {{ number_format($h->total_hutang, 0, ',', '.') }}</td>
                        <td class="sd-right sd-mono" style="color:#dc2626;font-weight:700;">Rp {{ number_format($h->sisa, 0, ',', '.') }}</td>
                        <td class="sd-center" style="font-size:0.75rem;">{{ $h->jatuh_tempo ? $h->jatuh_tempo->format('d/m/Y') : '-' }}</td>
                        <td class="sd-center">
                            @if($h->status === 'lunas')<span class="sd-method" style="background:#d1fae5;color:#065f46;">Lunas</span>
                            @elseif($h->status === 'overdue')<span class="sd-method" style="background:#fee2e2;color:#dc2626;">Overdue</span>
                            @else<span class="sd-method hutang">Belum Lunas</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="sd-bold">Total</td>
                        <td class="sd-right sd-mono sd-bold">Rp {{ number_format($hutangs->sum('total_hutang'), 0, ',', '.') }}</td>
                        <td class="sd-right sd-mono sd-bold" style="color:#dc2626;">Rp {{ number_format($hutangs->sum('sisa'), 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
    </div>
    @endif

    {{-- HUTANG PAYMENTS COLLECTED --}}
    @if($hutangPayments->isNotEmpty())
    <div class="sd-card green">
        <div class="sd-card-hdr">
            <div class="sd-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
            <div class="sd-card-title">Pembayaran Hutang Tunai yang Diterima</div>
            <span class="sd-card-badge" style="background:#d1fae5;color:#065f46;">Rp {{ number_format($hutangDibayar, 0, ',', '.') }}</span>
        </div>
        <div class="sd-card-body" style="padding:0;">
            <div style="overflow-x:auto;">
            <table class="sd-tbl">
                <thead>
                    <tr>
                        <th style="width:3%">#</th>
                        <th>Tanggal Bayar</th>
                        <th>Pelanggan</th>
                        <th>No. Faktur Asal</th>
                        <th class="sd-right">Jumlah Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hutangPayments as $i => $hp)
                    <tr>
                        <td class="sd-mono">{{ $i + 1 }}</td>
                        <td class="sd-bold">{{ $hp->tanggal_bayar->format('d/m/Y') }}</td>
                        <td>{{ $hp->hutang->pelanggan->nama ?? '-' }}</td>
                        <td class="sd-mono" style="font-size:0.72rem;">{{ $hp->hutang->penjualan->no_faktur ?? '-' }}</td>
                        <td class="sd-right sd-mono sd-bold" style="color:#059669;">Rp {{ number_format($hp->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="sd-bold">Total Pembayaran Hutang Tunai</td>
                        <td class="sd-right sd-mono sd-bold" style="color:#059669;">Rp {{ number_format($hutangPayments->sum('jumlah'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
    </div>
    @endif

    {{-- RINGKASAN DISIMPAN vs LIVE --}}
    @if($driftDetected)
    <div class="sd-card red">
        <div class="sd-card-hdr">
            <div class="sd-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
            <div class="sd-card-title">Perbandingan Data Tersimpan vs Aktual</div>
        </div>
        <div class="sd-card-body" style="padding:0;">
            <table class="sd-tbl">
                <thead><tr><th>Komponen</th><th class="sd-right">Saat Setoran Dibuat</th><th class="sd-right">Data Saat Ini</th><th class="sd-center">Status</th></tr></thead>
                <tbody>
                    @php $rows = [
                        ['Total Penjualan', $setoran->total_penjualan, $summary['total_penjualan']],
                        ['Total Tunai', $setoran->total_tunai, $summary['total_tunai']],
                        ['Total Transfer', $setoran->total_transfer, $summary['total_transfer']],
                    ]; @endphp
                    @foreach($rows as $r)
                    @php $diff = abs((float)$r[1] - (float)$r[2]) > 0.01; @endphp
                    <tr>
                        <td class="sd-bold">{{ $r[0] }}</td>
                        <td class="sd-right sd-mono">Rp {{ number_format($r[1], 0, ',', '.') }}</td>
                        <td class="sd-right sd-mono">Rp {{ number_format($r[2], 0, ',', '.') }}</td>
                        <td class="sd-center">
                            @if($diff)<span class="sd-method" style="background:#fee2e2;color:#dc2626;">Berubah</span>
                            @else<span class="sd-method" style="background:#d1fae5;color:#065f46;">Sama</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- BUKTI SETOR --}}
    @if($setoran->bukti_setor)
    <div class="sd-card pink">
        <div class="sd-card-hdr">
            <div class="sd-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
            <div class="sd-card-title">Bukti Setor</div>
        </div>
        <div class="sd-card-body" style="text-align:center;">
            <img src="{{ asset('storage/' . $setoran->bukti_setor) }}" alt="Bukti Setor" class="sd-foto" id="buktiFoto" onclick="openModal()">
            <div class="sd-foto-hint"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="width:13px;height:13px;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>Klik foto untuk melihat ukuran penuh</div>
        </div>
    </div>
    @endif

    {{-- VERIFICATION INFO --}}
    @if($setoran->status !== 'pending')
    <div class="sd-card indigo">
        <div class="sd-card-hdr">
            <div class="sd-card-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg></div>
            <div class="sd-card-title">Informasi Verifikasi</div>
        </div>
        <div class="sd-card-body">
            <table class="sd-info">
                <tr><td>Status</td><td><span class="sd-st {{ $setoran->status }}">@if($setoran->status==='terverifikasi')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>@else<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>@endif{{ ucfirst($setoran->status) }}</span></td></tr>
                <tr><td>Diverifikasi Oleh</td><td style="font-weight:700;">{{ $setoran->verifier->name ?? '-' }}</td></tr>
                <tr><td>Tanggal Verifikasi</td><td>{{ $setoran->verified_at ? $setoran->verified_at->format('d M Y, H:i') : '-' }}</td></tr>
                @if($setoran->catatan_verifikasi)<tr><td>Catatan</td><td>{{ $setoran->catatan_verifikasi }}</td></tr>@endif
            </table>
        </div>
    </div>
    @endif

    {{-- SUPERVISOR VERIFY FORM --}}
    @if($setoran->status === 'pending' && !$isSalesRole)
    <div class="sd-verify">
        <div class="sd-verify-hdr">
            <div class="sd-verify-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg></div>
            <div><h3>Verifikasi Setoran</h3><p>Periksa rekonsiliasi arus kas, bukti setor, dan detail transaksi di atas sebelum memverifikasi.</p></div>
        </div>
        <form action="{{ route('gula.setoran.verify', $setoran->id) }}" method="POST">
            @csrf
            <div class="sd-verify-fg">
                <label>Catatan Verifikasi</label>
                <textarea name="catatan_verifikasi" placeholder="Tambahkan catatan verifikasi (opsional)..."></textarea>
            </div>
            <div class="sd-verify-actions">
                <button type="submit" name="status" value="terverifikasi" class="sd-verify-btn approve"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Verifikasi</button>
                <button type="submit" name="status" value="ditolak" class="sd-verify-btn reject" onclick="return confirm('Yakin tolak setoran ini?')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>Tolak</button>
            </div>
        </form>
    </div>
    @endif
</div>

{{-- Image Modal --}}
<div class="sd-modal" id="imageModal" onclick="closeModal()">
    <button class="sd-modal-close" onclick="closeModal()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    <img id="modalImg" src="" alt="Bukti Setor">
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.openModal = function() {
        var img = document.getElementById('buktiFoto');
        var modal = document.getElementById('imageModal');
        var modalImg = document.getElementById('modalImg');
        if (img && modal && modalImg) { modalImg.src = img.src; modal.classList.add('active'); document.body.style.overflow = 'hidden'; }
    };
    window.closeModal = function() {
        var modal = document.getElementById('imageModal');
        if (modal) { modal.classList.remove('active'); document.body.style.overflow = ''; }
    };
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });
});
</script>
@endpush
@endsection
