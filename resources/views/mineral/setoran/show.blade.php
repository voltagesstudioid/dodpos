<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .sd-page { max-width:42rem; margin:0 auto; padding:1.25rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .sd-back { display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#94a3b8; text-decoration:none; margin-bottom:1rem; transition:all .2s; }
        .sd-back:hover { color:#334155; }
        .sd-back:hover svg { transform:translateX(-3px); }
        .sd-back svg { transition:transform .2s; }
        .sd-hdr { background:linear-gradient(135deg,#2563eb,#3b82f6); border-radius:16px; padding:1.25rem 1.5rem; margin-bottom:1.25rem; color:#fff; position:relative; overflow:hidden; }
        .sd-hdr::after { content:''; position:absolute; top:-30px; right:-30px; width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,.08); }
        .sd-hdr-tag { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; opacity:.7; }
        .sd-hdr-title { font-size:1.375rem; font-weight:800; margin-top:2px; }
        .sd-hdr-meta { display:flex; gap:16px; margin-top:10px; font-size:12px; opacity:.85; }
        .sd-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; }
        .sd-badge.pending { background:#fef3c7; color:#92400e; }
        .sd-badge.terverifikasi { background:#d1fae5; color:#065f46; }
        .sd-badge.ditolak { background:#fee2e2; color:#991b1b; }
        .sd-card { background:#fff; border:1px solid #f1f5f9; border-radius:14px; margin-bottom:12px; box-shadow:0 1px 2px rgba(0,0,0,.03); }
        .sd-card-hdr { padding:.75rem 1.125rem; display:flex; align-items:center; gap:10px; border-bottom:1px solid #f8fafc; }
        .sd-card-ico { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; }
        .sd-card-ico.blue { background:#eff6ff; color:#2563eb; }
        .sd-card-ico.green { background:#ecfdf5; color:#059669; }
        .sd-card-ico.amber { background:#fffbeb; color:#d97706; }
        .sd-card-lbl { font-size:13px; font-weight:700; color:#0f172a; }
        .sd-card-body { padding:1rem 1.125rem; }
        .sd-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #f8fafc; font-size:13px; }
        .sd-row:last-child { border-bottom:none; }
        .sd-key { color:#64748b; font-weight:500; }
        .sd-val { color:#0f172a; font-weight:600; text-align:right; }
        .sd-selisih-pos { color:#059669; }
        .sd-selisih-neg { color:#dc2626; }
        .sd-actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:14px; }
        .sd-btn { display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none; border:none; cursor:pointer; transition:all .2s; font-family:inherit; }
        .sd-btn-success { background:linear-gradient(135deg,#059669,#10b981); color:#fff; box-shadow:0 3px 12px rgba(5,150,105,.2); }
        .sd-btn-success:hover { box-shadow:0 6px 20px rgba(5,150,105,.3); transform:translateY(-1px); }
        .sd-btn-danger { background:#fee2e2; color:#dc2626; }
        .sd-btn-danger:hover { background:#fecaca; }
        .sd-btn-outline { background:#fff; color:#64748b; border:1.5px solid #e2e8f0; }
        .sd-btn-outline:hover { background:#f8fafc; color:#334155; }
        .sd-verify-form { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px; margin-top:10px; }
        .sd-verify-lbl { font-size:12px; font-weight:700; color:#334155; margin-bottom:8px; }
        .sd-verify-ta { width:100%; padding:8px 12px; border-radius:8px; border:1px solid #e2e8f0; font-size:13px; font-family:inherit; resize:vertical; min-height:50px; margin-bottom:8px; }
        .sd-verify-actions { display:flex; gap:8px; }
    </style>
    @endpush

    <div class="sd-page">
        <a href="{{ route('mineral.setoran.index') }}" class="sd-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>

        {{-- Header --}}
        <div class="sd-hdr">
            <div class="sd-hdr-tag">Detail Setoran</div>
            <div class="sd-hdr-title">{{ $setoran->sales->nama ?? 'Sales' }} — {{ \Carbon\Carbon::parse($setoran->tanggal)->format('d M Y') }}</div>
            <div class="sd-hdr-meta">
                <span class="sd-badge {{ $setoran->status }}">{{ ucfirst($setoran->status) }}</span>
                <span>{{ $setoran->jumlah_transaksi }} transaksi</span>
            </div>
        </div>

        {{-- Data Penjualan --}}
        <div class="sd-card">
            <div class="sd-card-hdr">
                <div class="sd-card-ico blue">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div class="sd-card-lbl">Ringkasan Penjualan</div>
            </div>
            <div class="sd-card-body">
                <div class="sd-row"><span class="sd-key">Total Penjualan</span><span class="sd-val">Rp {{ number_format($setoran->total_penjualan, 0, ',', '.') }}</span></div>
                <div class="sd-row"><span class="sd-key">&nbsp;&nbsp;Tunai</span><span class="sd-val" style="color:#059669;">Rp {{ number_format($setoran->total_tunai ?? 0, 0, ',', '.') }}</span></div>
                <div class="sd-row"><span class="sd-key">&nbsp;&nbsp;Transfer</span><span class="sd-val" style="color:#2563eb;">Rp {{ number_format($setoran->total_transfer ?? 0, 0, ',', '.') }}</span></div>
                <div class="sd-row"><span class="sd-key">Jumlah Transaksi</span><span class="sd-val">{{ $setoran->jumlah_transaksi }} transaksi</span></div>
                <div class="sd-row"><span class="sd-key">Hutang Baru</span><span class="sd-val">Rp {{ number_format($setoran->total_hutang_baru, 0, ',', '.') }} ({{ $setoran->jumlah_hutang_baru }})</span></div>
            </div>
        </div>

        {{-- Data Setoran --}}
        <div class="sd-card">
            <div class="sd-card-hdr">
                <div class="sd-card-ico green">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="sd-card-lbl">Detail Setoran</div>
            </div>
            <div class="sd-card-body">
                <div class="sd-row"><span class="sd-key">Tanggal</span><span class="sd-val">{{ \Carbon\Carbon::parse($setoran->tanggal)->format('d M Y') }}</span></div>
                <div class="sd-row"><span class="sd-key">Sales</span><span class="sd-val">{{ $setoran->sales->nama ?? '-' }}</span></div>
                <div class="sd-row"><span class="sd-key">Total Setor</span><span class="sd-val" style="font-size:15px;">Rp {{ number_format($setoran->total_setor, 0, ',', '.') }}</span></div>
                <div class="sd-row">
                    <span class="sd-key">Selisih</span>
                    <span class="sd-val {{ $setoran->selisih >= 0 ? 'sd-selisih-pos' : 'sd-selisih-neg' }}">
                        Rp {{ number_format($setoran->selisih, 0, ',', '.') }}
                    </span>
                </div>
                <div class="sd-row" style="font-size:11px; color:#94a3b8;">
                    <span class="sd-key">Rumus Selisih</span>
                    <span class="sd-val" style="font-size:11px; font-weight:400; color:#94a3b8;">Setor - (Tunai + Bayar Hutang Tunai)</span>
                </div>
                @if($setoran->catatan_sales)
                <div class="sd-row" style="flex-direction:column; align-items:flex-start; gap:4px;">
                    <span class="sd-key">Catatan Sales</span>
                    <span class="sd-val" style="text-align:left; font-weight:400; color:#475569; font-size:12px;">{{ $setoran->catatan_sales }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Bukti Setoran --}}
        @if($setoran->bukti_setor)
        <div class="sd-card">
            <div class="sd-card-hdr">
                <div class="sd-card-ico blue">📷</div>
                <div class="sd-card-lbl">Bukti Setoran</div>
            </div>
            <div class="sd-card-body" style="text-align:center;">
                <a href="{{ asset('storage/' . $setoran->bukti_setor) }}" target="_blank">
                    <img src="{{ asset('storage/' . $setoran->bukti_setor) }}" alt="Bukti Setoran" style="max-width:100%; max-height:280px; border-radius:10px; border:1.5px solid #e2e8f0; cursor:pointer;">
                </a>
                <div style="margin-top:8px; font-size:11px; color:#94a3b8;">Klik foto untuk melihat ukuran penuh</div>
            </div>
        </div>
        @endif

        {{-- Verifikasi --}}
        @if($setoran->status !== 'pending' && $setoran->verifier)
        <div class="sd-card">
            <div class="sd-card-hdr">
                <div class="sd-card-ico {{ $setoran->status === 'terverifikasi' ? 'green' : 'amber' }}">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="sd-card-lbl">Verifikasi</div>
            </div>
            <div class="sd-card-body">
                <div class="sd-row"><span class="sd-key">Status</span><span class="sd-val">{{ ucfirst($setoran->status) }}</span></div>
                <div class="sd-row"><span class="sd-key">Diverifikasi oleh</span><span class="sd-val">{{ $setoran->verifier->name ?? '-' }}</span></div>
                @if($setoran->verified_at)
                <div class="sd-row"><span class="sd-key">Waktu</span><span class="sd-val">{{ $setoran->verified_at->format('d M Y H:i') }}</span></div>
                @endif
                @if($setoran->catatan_verifikasi)
                <div class="sd-row" style="flex-direction:column; align-items:flex-start; gap:4px;">
                    <span class="sd-key">Catatan Verifikasi</span>
                    <span class="sd-val" style="text-align:left; font-weight:400; color:#475569; font-size:12px;">{{ $setoran->catatan_verifikasi }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Verify form (supervisor only, pending only) --}}
        @if(!auth()->user()->role || (!str_starts_with(strtolower(auth()->user()->role), 'sales_') && $setoran->status === 'pending'))
        <div class="sd-card">
            <div class="sd-card-hdr">
                <div class="sd-card-ico amber">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div class="sd-card-lbl">Verifikasi Setoran</div>
            </div>
            <div class="sd-card-body">
                <form method="POST" action="{{ route('mineral.setoran.verify', $setoran) }}">
                    @csrf
                    <div class="sd-verify-form">
                        <div class="sd-verify-lbl">Catatan Verifikasi (opsional)</div>
                        <textarea name="catatan_verifikasi" class="sd-verify-ta" placeholder="Tambahkan catatan..."></textarea>
                        <div class="sd-verify-actions">
                            <button type="submit" name="status" value="terverifikasi" class="sd-btn sd-btn-success">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Setujui
                            </button>
                            <button type="submit" name="status" value="ditolak" class="sd-btn sd-btn-danger">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- Actions --}}
        <div class="sd-actions">
            <a href="{{ route('mineral.setoran.index') }}" class="sd-btn sd-btn-outline">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            @if($setoran->status === 'pending')
            @if(!auth()->user()->role || (!str_starts_with(strtolower(auth()->user()->role), 'sales_') && $setoran->status === 'pending'))
            <a href="{{ route('mineral.setoran.edit', $setoran) }}" class="sd-btn sd-btn-outline" style="color:#2563eb; border-color:#bfdbfe;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            @endif
            @endif
        </div>
    </div>
</x-app-layout>
