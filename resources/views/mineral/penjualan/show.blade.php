<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .sd-page { max-width:48rem; margin:0 auto; padding:1.5rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .sd-back { display:inline-flex; align-items:center; gap:0.375rem; font-size:0.8125rem; font-weight:600; color:#64748b; text-decoration:none; margin-bottom:1.25rem; transition:color 0.2s; }
        .sd-back:hover { color:#334155; }
        .sd-hdr { background:linear-gradient(135deg,#1e40af 0%,#2563eb 50%,#3b82f6 100%); border-radius:20px; padding:1.5rem 1.75rem; margin-bottom:1.5rem; box-shadow:0 12px 40px rgba(37,99,235,0.2); position:relative; overflow:hidden; }
        .sd-hdr::after { content:''; position:absolute; top:-40px; right:-40px; width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,0.07); }
        .sd-hdr-top { display:flex; justify-content:space-between; align-items:flex-start; position:relative; z-index:1; }
        .sd-hdr-title { font-size:1.25rem; font-weight:800; color:#fff; }
        .sd-hdr-sub { font-size:0.8125rem; color:rgba(255,255,255,0.75); margin-top:0.25rem; }
        .sd-badge { padding:0.375rem 0.875rem; border-radius:20px; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; }
        .sd-badge.pending { background:rgba(251,191,36,0.2); color:#fbbf24; }
        .sd-badge.terverifikasi { background:rgba(52,211,153,0.2); color:#34d399; }
        .sd-badge.batal { background:rgba(239,68,68,0.15); color:#f87171; }
        .sd-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; margin-bottom:1.25rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
        .sd-card-hdr { padding:0.875rem 1.25rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:0.5rem; }
        .sd-card-ico { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:0.85rem; flex-shrink:0; }
        .sd-card-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .sd-card-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .sd-card-ico.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .sd-card-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .sd-card-lbl { font-size:0.8125rem; font-weight:700; color:#0f172a; }
        .sd-card-body { padding:1rem 1.25rem; }
        .sd-row { display:flex; justify-content:space-between; align-items:center; padding:0.5rem 0; border-bottom:1px solid #f8fafc; }
        .sd-row:last-child { border-bottom:none; }
        .sd-key { font-size:0.8125rem; color:#64748b; }
        .sd-val { font-size:0.8125rem; font-weight:600; color:#0f172a; text-align:right; }
        .sd-total-box { background:linear-gradient(135deg,#eff6ff,#dbeafe); border:1px solid #93c5fd; border-radius:12px; padding:1rem 1.25rem; margin-top:0.75rem; display:flex; justify-content:space-between; align-items:center; }
        .sd-total-lbl { font-size:0.875rem; font-weight:700; color:#1e40af; }
        .sd-total-val { font-size:1.25rem; font-weight:800; color:#1e40af; }
        .sd-foto-box { border-radius:12px; overflow:hidden; border:1px solid #e2e8f0; }
        .sd-foto-box img { width:100%; display:block; }
        .sd-actions { display:flex; gap:0.75rem; flex-wrap:wrap; margin-top:1.5rem; }
        .sd-btn { display:inline-flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.75rem 1.25rem; border-radius:12px; font-size:0.875rem; font-weight:700; border:none; cursor:pointer; transition:all 0.2s; font-family:inherit; text-decoration:none; }
        .sd-btn-edit { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 6px 20px rgba(217,119,6,0.25); flex:1; }
        .sd-btn-edit:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(217,119,6,0.35); }
        .sd-btn-print { background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; box-shadow:0 6px 20px rgba(79,70,229,0.25); flex:1; }
        .sd-btn-print:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(79,70,229,0.35); }
        .sd-btn-verify { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 6px 20px rgba(37,99,235,0.25); flex:1; }
        .sd-btn-verify:hover { transform:translateY(-1px); }
        .sd-btn-back { background:#fff; color:#64748b; border:1.5px solid #e2e8f0; }
        .sd-btn-back:hover { background:#f8fafc; color:#475569; border-color:#cbd5e1; }
        .sd-btn-delete { background:#fff; color:#ef4444; border:1.5px solid #fecaca; }
        .sd-btn-delete:hover { background:#fef2f2; }
        @media(max-width:640px) { .sd-actions { flex-direction:column; } }
    </style>
    @endpush

    <div class="sd-page">
        <a href="{{ route('mineral.penjualan.index') }}" class="sd-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Penjualan
        </a>

        {{-- Header --}}
        <div class="sd-hdr">
            <div class="sd-hdr-top">
                <div>
                    <div class="sd-hdr-title">{{ $penjualan->no_faktur }}</div>
                    <div class="sd-hdr-sub">{{ $penjualan->tanggal_jual->format('d M Y, H:i') }} WIB</div>
                </div>
                <span class="sd-badge {{ $penjualan->status }}">{{ ucfirst($penjualan->status) }}</span>
            </div>
        </div>

        {{-- Info Pelanggan & Sales --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div class="sd-card">
                <div class="sd-card-hdr">
                    <div class="sd-card-ico blue">🏪</div>
                    <div class="sd-card-lbl">Pelanggan</div>
                </div>
                <div class="sd-card-body">
                    <div class="sd-row"><span class="sd-key">Nama Toko</span><span class="sd-val">{{ $penjualan->pelanggan->nama_toko ?? '-' }}</span></div>
                    <div class="sd-row"><span class="sd-key">Pemilik</span><span class="sd-val">{{ $penjualan->pelanggan->nama_pemilik ?? '-' }}</span></div>
                    <div class="sd-row"><span class="sd-key">No. HP</span><span class="sd-val">{{ $penjualan->pelanggan->no_hp ?? '-' }}</span></div>
                </div>
            </div>
            <div class="sd-card">
                <div class="sd-card-hdr">
                    <div class="sd-card-ico green">👤</div>
                    <div class="sd-card-lbl">Sales</div>
                </div>
                <div class="sd-card-body">
                    <div class="sd-row"><span class="sd-key">Nama</span><span class="sd-val">{{ $penjualan->sales->nama ?? '-' }}</span></div>
                    <div class="sd-row"><span class="sd-key">Kode</span><span class="sd-val">{{ $penjualan->sales->kode_sales ?? '-' }}</span></div>
                    <div class="sd-row"><span class="sd-key">Kendaraan</span><span class="sd-val">{{ $penjualan->sales->no_kendaraan ?? '-' }}</span></div>
                </div>
            </div>
        </div>

        {{-- Detail Produk --}}
        <div class="sd-card">
            <div class="sd-card-hdr">
                <div class="sd-card-ico amber">🛒</div>
                <div class="sd-card-lbl">Detail Penjualan</div>
            </div>
            <div class="sd-card-body">
                <div class="sd-row"><span class="sd-key">Produk</span><span class="sd-val">{{ $penjualan->produk->nama ?? '-' }}</span></div>
                <div class="sd-row"><span class="sd-key">Jumlah</span><span class="sd-val">{{ $penjualan->jumlah }} {{ $penjualan->produk->satuan ?? '' }}</span></div>
                <div class="sd-row"><span class="sd-key">Harga Satuan</span><span class="sd-val">Rp {{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</span></div>
                <div class="sd-row"><span class="sd-key">Tipe Bayar</span><span class="sd-val">{{ ucfirst($penjualan->tipe_bayar) }}</span></div>
                @if($penjualan->tipe_bayar === 'transfer' && $penjualan->no_bukti_transfer)
                <div class="sd-row"><span class="sd-key">No. Bukti Transfer</span><span class="sd-val" style="font-family:monospace;font-weight:600;color:#2563eb;">{{ $penjualan->no_bukti_transfer }}</span></div>
                @endif
                @if($penjualan->tipe_bayar === 'hutang' && $penjualan->bayar > 0)
                <div class="sd-row"><span class="sd-key">Dibayar (DP)</span><span class="sd-val">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</span></div>
                @endif
                @if($penjualan->hutang > 0)
                <div class="sd-row"><span class="sd-key">Sisa Hutang</span><span class="sd-val" style="color:#dc2626;">Rp {{ number_format($penjualan->hutang, 0, ',', '.') }}</span></div>
                @endif
                <div class="sd-total-box">
                    <span class="sd-total-lbl">Total</span>
                    <span class="sd-total-val">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Keterangan --}}
        @if($penjualan->keterangan)
        <div class="sd-card">
            <div class="sd-card-hdr">
                <div class="sd-card-ico purple">📝</div>
                <div class="sd-card-lbl">Keterangan</div>
            </div>
            <div class="sd-card-body">
                <p style="font-size:0.8125rem; color:#475569; margin:0;">{{ $penjualan->keterangan }}</p>
            </div>
        </div>
        @endif

        {{-- Foto Nota --}}
        @if($penjualan->foto_nota)
        <div class="sd-card">
            <div class="sd-card-hdr">
                <div class="sd-card-ico green">📷</div>
                <div class="sd-card-lbl">Bukti Penjualan</div>
            </div>
            <div class="sd-card-body">
                <div class="sd-foto-box">
                    <img src="{{ asset('storage/' . $penjualan->foto_nota) }}" alt="Bukti Nota">
                </div>
            </div>
        </div>
        @endif

        {{-- Bukti Transfer (Supervisor Review) --}}
        @if($penjualan->tipe_bayar === 'transfer')
        <div class="sd-card">
            <div class="sd-card-hdr">
                <div class="sd-card-ico blue">🛡️</div>
                <div class="sd-card-lbl">Verifikasi Bukti Transfer</div>
            </div>
            <div class="sd-card-body">
                @if($penjualan->bukti_transfer)
                    <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.75rem;">
                        <span style="display:inline-flex;align-items:center;gap:0.25rem;padding:0.25rem 0.625rem;border-radius:20px;font-size:0.6875rem;font-weight:700;background:#dbeafe;color:#1e40af;">✅ Bukti Terunggah</span>
                        @if($penjualan->no_bukti_transfer)
                        <span style="display:inline-flex;align-items:center;padding:0.25rem 0.625rem;border-radius:20px;font-size:0.6875rem;font-weight:700;background:#f1f5f9;color:#475569;font-family:monospace;">ID: {{ $penjualan->no_bukti_transfer }}</span>
                        @endif
                    </div>
                    <div class="sd-foto-box">
                        <a href="{{ asset('storage/' . $penjualan->bukti_transfer) }}" target="_blank">
                            <img src="{{ asset('storage/' . $penjualan->bukti_transfer) }}" alt="Bukti Transfer" style="cursor:zoom-in;">
                        </a>
                    </div>
                    <p style="font-size:0.6875rem;color:#94a3b8;margin-top:0.5rem;">Klik gambar untuk melihat ukuran penuh. Supervisor: periksa kesesuaian nominal dan rekening sebelum approve.</p>
                @else
                    <div style="display:flex;align-items:center;gap:0.5rem;padding:0.75rem 1rem;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;">
                        <svg width="20" height="20" fill="none" stroke="#d97706" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        <span style="font-size:0.8125rem;font-weight:600;color:#92400e;">Foto bukti transfer belum diunggah</span>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Lokasi --}}
        @if($penjualan->latitude && $penjualan->longitude)
        <div class="sd-card">
            <div class="sd-card-hdr">
                <div class="sd-card-ico blue">📍</div>
                <div class="sd-card-lbl">Lokasi Transaksi</div>
            </div>
            <div class="sd-card-body">
                <div class="sd-row">
                    <span class="sd-key">Koordinat</span>
                    <a href="https://maps.google.com/?q={{ $penjualan->latitude }},{{ $penjualan->longitude }}" target="_blank" class="sd-val" style="color:#2563eb;">
                        {{ number_format($penjualan->latitude, 6) }}, {{ number_format($penjualan->longitude, 6) }}
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Actions --}}
        <div class="sd-actions">
            <a href="{{ route('mineral.penjualan.index') }}" class="sd-btn sd-btn-back">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            @if(!$isSalesRole && $penjualan->status === 'pending' && Route::has('mineral.penjualan.edit'))
            <a href="{{ route('mineral.penjualan.edit', $penjualan) }}" class="sd-btn sd-btn-edit">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            @endif
            @if(Route::has('mineral.penjualan.print'))
            <a href="{{ route('mineral.penjualan.print', $penjualan) }}" target="_blank" class="sd-btn sd-btn-print">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Struk
            </a>
            @endif
            @if(!$isSalesRole && $penjualan->status !== 'terverifikasi' && $penjualan->status !== 'batal' && Route::has('mineral.penjualan.verify'))
            <form method="POST" action="{{ route('mineral.penjualan.verify', $penjualan) }}" style="flex:1;">
                @csrf
                <button type="submit" class="sd-btn sd-btn-verify" style="width:100%;"
                    @if($penjualan->tipe_bayar === 'transfer' && !$penjualan->bukti_transfer) disabled style="width:100%;opacity:0.5;cursor:not-allowed;" title="Foto bukti transfer belum diunggah" @endif>
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @if($penjualan->tipe_bayar === 'transfer') Approve Transfer @else Verifikasi @endif
                </button>
            </form>
            @endif
            @if(!$isSalesRole && $penjualan->status === 'pending' && Route::has('mineral.penjualan.destroy'))
            <form method="POST" action="{{ route('mineral.penjualan.destroy', $penjualan) }}" onsubmit="return confirm('Yakin batalkan transaksi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="sd-btn sd-btn-delete">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    Batalkan
                </button>
            </form>
            @endif
        </div>
    </div>
</x-app-layout>
