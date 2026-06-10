<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap');
        .sm-page { max-width:44rem; margin:0 auto; padding:1.5rem 1rem 4rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .sm-back { display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#94a3b8; text-decoration:none; margin-bottom:1rem; transition:all .2s; }
        .sm-back:hover { color:#334155; }
        .sm-hdr { border-radius:16px; padding:1.5rem; margin-bottom:1.25rem; color:#fff; position:relative; overflow:hidden; }
        .sm-hdr.penerimaan { background:linear-gradient(135deg,#10b981 0%,#059669 100%); }
        .sm-hdr.koreksi { background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%); }
        .sm-hdr.batal { background:linear-gradient(135deg,#94a3b8 0%,#64748b 100%); }
        .sm-hdr::after { content:''; position:absolute; right:-30px; bottom:-30px; width:120px; height:120px; background:rgba(255,255,255,.08); border-radius:50%; }
        .sm-hdr-tag { display:inline-block; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; background:rgba(255,255,255,.2); padding:3px 10px; border-radius:20px; margin-bottom:8px; }
        .sm-hdr-ref { font-size:1.5rem; font-weight:800; letter-spacing:-.03em; font-family:'JetBrains Mono',monospace; }
        .sm-hdr-meta { display:flex; gap:16px; margin-top:8px; font-size:12px; opacity:.9; }
        .sm-hdr-meta span { display:flex; align-items:center; gap:4px; }
        .sm-badge { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px; }
        .sm-badge.aktif { background:#dcfce7; color:#065f46; }
        .sm-badge.batal { background:#fee2e2; color:#991b1b; }
        .sm-sec { background:#fff; border:1px solid #f1f5f9; border-radius:14px; margin-bottom:14px; box-shadow:0 1px 2px rgba(0,0,0,.03); }
        .sm-sec-hdr { padding:.875rem 1.25rem; display:flex; align-items:center; gap:10px; border-bottom:1px solid #f8fafc; }
        .sm-sec-ico { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
        .sm-sec-ico.emerald { background:#ecfdf5; color:#059669; }
        .sm-sec-ico.sky { background:#f0f9ff; color:#0284c7; }
        .sm-sec-ico.violet { background:#f5f3ff; color:#7c3aed; }
        .sm-sec-title { font-size:14px; font-weight:700; color:#0f172a; }
        .sm-sec-body { padding:1.125rem 1.25rem; }
        .sm-row { display:flex; justify-content:space-between; align-items:flex-start; padding:8px 0; border-bottom:1px solid #f8fafc; }
        .sm-row:last-child { border-bottom:none; }
        .sm-row-lbl { font-size:12px; color:#64748b; font-weight:600; flex-shrink:0; }
        .sm-row-val { font-size:13px; color:#0f172a; font-weight:600; text-align:right; max-width:60%; word-break:break-word; }
        .sm-row-val.mono { font-family:'JetBrains Mono',monospace; }
        .sm-flow { display:flex; align-items:center; gap:8px; justify-content:center; padding:16px 0; }
        .sm-flow-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px; text-align:center; min-width:100px; }
        .sm-flow-lbl { font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.06em; }
        .sm-flow-val { font-size:1.125rem; font-weight:800; font-family:'JetBrains Mono',monospace; margin-top:2px; }
        .sm-flow-val.green { color:#059669; }
        .sm-flow-val.red { color:#dc2626; }
        .sm-flow-val.blue { color:#0284c7; }
        .sm-flow-arrow { font-size:18px; color:#94a3b8; flex-shrink:0; }
        .sm-flow-diff { font-size:11px; font-weight:700; margin-top:2px; }
        .sm-flow-diff.plus { color:#059669; }
        .sm-flow-diff.minus { color:#dc2626; }
        .sm-actions { display:flex; gap:10px; margin-top:8px; }
        .sm-btn { display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:10px 18px; border-radius:11px; font-size:13px; font-weight:600; border:none; cursor:pointer; transition:all .2s; font-family:inherit; text-decoration:none; }
        .sm-btn-outline { background:#fff; border:1.5px solid #e2e8f0; color:#64748b; }
        .sm-btn-outline:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }
        .sm-btn-danger { background:#fef2f2; border:1.5px solid #fecaca; color:#dc2626; }
        .sm-btn-danger:hover { background:#fee2e2; }
        @media(max-width:480px) { .sm-flow { flex-direction:column; gap:4px; } .sm-flow-box { min-width:auto; width:100%; } }
    </style>
    @endpush
    <div class="sm-page">
        <a href="{{ route('minyak.stok-masuk.index') }}" class="sm-back"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Kembali ke Daftar</a>

        {{-- Header --}}
        @php
            $hdrClass = $stokMasuk->status === 'batal' ? 'batal' : $stokMasuk->tipe;
            $tipeLabel = $stokMasuk->tipe === 'penerimaan' ? 'Penerimaan Stok' : 'Koreksi Stok';
            $tipeIcon = $stokMasuk->tipe === 'penerimaan' ? '📥' : '🔧';
        @endphp
        <div class="sm-hdr {{ $hdrClass }}">
            <div class="sm-hdr-tag">{{ $tipeIcon }} {{ $tipeLabel }}</div>
            <div class="sm-hdr-ref">{{ $stokMasuk->no_referensi }}</div>
            <div class="sm-hdr-meta">
                <span>📅 {{ $stokMasuk->created_at->format('d M Y, H:i') }}</span>
                <span>👤 {{ $stokMasuk->creator?->name ?? 'Unknown' }}</span>
                <span class="sm-badge {{ $stokMasuk->status }}">{{ strtoupper($stokMasuk->status) }}</span>
            </div>
        </div>

        {{-- Produk Info --}}
        <div class="sm-sec">
            <div class="sm-sec-hdr">
                <div class="sm-sec-ico emerald"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                <div class="sm-sec-title">Informasi Produk</div>
            </div>
            <div class="sm-sec-body">
                <div class="sm-row"><span class="sm-row-lbl">Produk</span><span class="sm-row-val">{{ $stokMasuk->produk?->nama ?? '-' }}</span></div>
                <div class="sm-row"><span class="sm-row-lbl">Sumber / Supplier</span><span class="sm-row-val">{{ $stokMasuk->sumber ?? '-' }}</span></div>
                <div class="sm-row"><span class="sm-row-lbl">Keterangan</span><span class="sm-row-val">{{ $stokMasuk->keterangan ?? '-' }}</span></div>
            </div>
        </div>

        {{-- Perubahan Stok --}}
        <div class="sm-sec">
            <div class="sm-sec-hdr">
                <div class="sm-sec-ico sky"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg></div>
                <div class="sm-sec-title">Perubahan Stok Gudang</div>
            </div>
            <div class="sm-sec-body">
                @php
                    $diff = (float) $stokMasuk->jumlah;
                    $diffSign = $diff >= 0 ? '+' : '';
                    $diffClass = $diff >= 0 ? 'plus' : 'minus';
                    $satuan = $stokMasuk->produk?->satuan ?? 'Liter';
                @endphp
                <div class="sm-flow">
                    <div class="sm-flow-box">
                        <div class="sm-flow-lbl">Stok Sebelum</div>
                        <div class="sm-flow-val blue">{{ number_format((float) $stokMasuk->stok_sebelum, 2, ',', '.') }}</div>
                        <div style="font-size:10px;color:#94a3b8;">{{ $satuan }}</div>
                    </div>
                    <div class="sm-flow-arrow">→</div>
                    <div class="sm-flow-box" style="border-color:{{ $diff >= 0 ? '#bbf7d0' : '#fecaca' }}; background:{{ $diff >= 0 ? '#f0fdf4' : '#fef2f2' }};">
                        <div class="sm-flow-lbl">Perubahan</div>
                        <div class="sm-flow-val {{ $diff >= 0 ? 'green' : 'red' }}">{{ $diffSign }}{{ number_format($diff, 2, ',', '.') }}</div>
                        <div class="sm-flow-diff {{ $diffClass }}">{{ $stokMasuk->tipe === 'penerimaan' ? 'Penerimaan' : 'Selisih Koreksi' }}</div>
                    </div>
                    <div class="sm-flow-arrow">→</div>
                    <div class="sm-flow-box">
                        <div class="sm-flow-lbl">Stok Sesudah</div>
                        <div class="sm-flow-val green">{{ number_format((float) $stokMasuk->stok_sesudah, 2, ',', '.') }}</div>
                        <div style="font-size:10px;color:#94a3b8;">{{ $satuan }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Transaksi --}}
        <div class="sm-sec">
            <div class="sm-sec-hdr">
                <div class="sm-sec-ico violet"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                <div class="sm-sec-title">Detail Transaksi</div>
            </div>
            <div class="sm-sec-body">
                <div class="sm-row"><span class="sm-row-lbl">No. Referensi</span><span class="sm-row-val mono">{{ $stokMasuk->no_referensi }}</span></div>
                <div class="sm-row"><span class="sm-row-lbl">Tipe</span><span class="sm-row-val">{{ $tipeLabel }}</span></div>
                <div class="sm-row"><span class="sm-row-lbl">Status</span><span class="sm-row-val"><span class="sm-badge {{ $stokMasuk->status }}">{{ strtoupper($stokMasuk->status) }}</span></span></div>
                <div class="sm-row"><span class="sm-row-lbl">Dibuat Oleh</span><span class="sm-row-val">{{ $stokMasuk->creator?->name ?? '-' }}</span></div>
                <div class="sm-row"><span class="sm-row-lbl">Tanggal</span><span class="sm-row-val">{{ $stokMasuk->created_at->format('d F Y, H:i:s') }} WIB</span></div>
            </div>
        </div>

        {{-- Actions --}}
        @if($stokMasuk->status === 'aktif')
        <div class="sm-actions">
            <a href="{{ route('minyak.stok-masuk.index') }}" class="sm-btn sm-btn-outline">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Kembali
            </a>
            <form method="POST" action="{{ route('minyak.stok-masuk.destroy', $stokMasuk) }}" onsubmit="return confirm('Yakin ingin membatalkan data ini? Stok akan disesuaikan kembali.');" style="flex:1;">
                @csrf @method('DELETE')
                <button type="submit" class="sm-btn sm-btn-danger" style="width:100%;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Batalkan Data Ini
                </button>
            </form>
        </div>
        @else
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;text-align:center;margin-top:8px;">
            <span style="font-size:13px;color:#64748b;font-weight:600;">Data ini sudah dibatalkan.</span>
        </div>
        @endif
    </div>
</x-app-layout>
