<x-app-layout>
    @push('styles')
    <style>
        .kv { max-width:768px; margin:0 auto; padding:1.5rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .kv-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; }
        .kv-back { display:flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:10px; background:#fff; border:1px solid #e2e8f0; color:#64748b; text-decoration:none; transition:all .2s; flex-shrink:0; }
        .kv-back:hover { background:#f8fafc; color:#0f172a; transform:translateX(-2px); }
        .kv-hdr-title { font-size:1.375rem; font-weight:800; color:#0f172a; letter-spacing:-.03em; line-height:1.2; }
        .kv-hdr-sub { font-size:.8125rem; color:#64748b; margin-top:2px; }
        .kv-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; transition:box-shadow .25s; margin-bottom:1rem; }
        .kv-card-hdr { display:flex; align-items:center; gap:.75rem; padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; font-weight:700; font-size:.9375rem; color:#0f172a; }
        .kv-card-ico { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; color:#fff; flex-shrink:0; }
        .kv-card-ico svg { width:16px; height:16px; }
        .kv-card-body { padding:1.25rem; }
        .kv-info { display:flex; flex-direction:column; }
        .kv-info-row { display:flex; justify-content:space-between; align-items:flex-start; padding:.75rem 0; border-bottom:1px dashed #f1f5f9; gap:.5rem; }
        .kv-info-row:last-child { border-bottom:none; padding-bottom:0; }
        .kv-info-lbl { font-size:.75rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.04em; flex-shrink:0; }
        .kv-info-val { font-size:.875rem; font-weight:600; color:#1e293b; text-align:right; word-break:break-word; max-width:60%; }
        .kv-tx { background:#fffbeb; border:1px solid #fde68a; border-radius:12px; overflow:hidden; }
        .kv-tx-hdr { display:flex; align-items:center; gap:.75rem; padding:1rem 1.125rem; background:#fef3c7; border-bottom:1px dashed #fcd34d; }
        .kv-tx-ico { width:40px; height:40px; border-radius:10px; background:#fef08a; color:#d97706; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .kv-tx-title { font-size:.875rem; font-weight:700; color:#92400e; }
        .kv-tx-sub { font-size:.75rem; color:#b45309; margin-top:1px; }
        .kv-tx-body { padding:1rem 1.125rem; display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
        @media(max-width:640px){.kv-tx-body{grid-template-columns:1fr}}
        .kv-tx-lbl { font-size:.6875rem; font-weight:700; color:#92400e; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.25rem; }
        .kv-tx-val { font-size:.9375rem; font-weight:700; color:#1e293b; }
        .kv-tx-val.hi { color:#059669; font-size:1.0625rem; }
        .kv-note { padding:1.125rem; background:#f8fafc; border-radius:12px; border-left:4px solid #8b5cf6; font-size:.875rem; line-height:1.6; color:#334155; }
        .kv-empty { text-align:center; padding:2rem 1rem; color:#94a3b8; }
        .kv-empty-ico { font-size:2.5rem; margin-bottom:.5rem; opacity:.5; }
        .kv-empty p { font-size:.875rem; font-weight:600; }
        .kv-badge { display:inline-flex; align-items:center; gap:.3rem; padding:.2rem .625rem; border-radius:99px; font-size:.6875rem; font-weight:700; border:1px solid; }
        .kv-badge.pending { background:#fffbeb; color:#d97706; border-color:#fde68a; }
        .kv-badge.terverifikasi { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .kv-badge.batal { background:#f1f5f9; color:#94a3b8; border-color:#e2e8f0; }
        .kv-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
        .kv-dot.pending { background:#f59e0b; }
        .kv-dot.terverifikasi { background:#10b981; }
        .kv-dot.batal { background:#94a3b8; }
        .kv-photo { background:#f8fafc; border-radius:12px; padding:.875rem; border:1px solid #f1f5f9; margin-top:.75rem; }
        .kv-photo-lbl { font-size:.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#94a3b8; margin-bottom:.625rem; }
        .kv-photo-img { width:100%; height:160px; object-fit:cover; border-radius:10px; transition:transform .25s; cursor:pointer; }
        .kv-photo-img:hover { transform:scale(1.02); }
        .kv-link { color:#3b82f6; font-size:.8125rem; font-weight:600; text-decoration:none; }
        .kv-link:hover { text-decoration:underline; }

        @media(max-width:768px){.kv{padding:1rem .75rem 2rem}.kv-hdr-title{font-size:1.125rem}.kv-card-body{padding:1rem}.kv-info-row{flex-direction:column;gap:.25rem}.kv-info-val{text-align:left;max-width:100%}}
    </style>
    @endpush

    <div class="kv">
        <div class="kv-hdr">
            <a href="{{ route('minyak.kunjungan.index') }}" class="kv-back">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="kv-hdr-title">Detail Kunjungan</div>
                <div class="kv-hdr-sub">{{ $kunjungan->waktu_checkin ? $kunjungan->waktu_checkin->format('d M Y, H:i') : '-' }} WIB</div>
            </div>
        </div>

        {{-- Sales Info --}}
        <div class="kv-card">
            <div class="kv-card-hdr">
                <div class="kv-card-ico" style="background:linear-gradient(135deg,#14b8a6,#0d9488)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                Informasi Sales
            </div>
            <div class="kv-card-body">
                <div class="kv-info">
                    <div class="kv-info-row"><span class="kv-info-lbl">Nama</span><span class="kv-info-val">{{ $kunjungan->sales->nama ?? '-' }}</span></div>
                    <div class="kv-info-row"><span class="kv-info-lbl">Kode</span><span class="kv-info-val">{{ $kunjungan->sales->kode_sales ?? '-' }}</span></div>
                    <div class="kv-info-row" style="border:none;padding-bottom:0;"><span class="kv-info-lbl">Kendaraan</span><span class="kv-info-val">{{ $kunjungan->sales->no_kendaraan ?? '-' }}</span></div>
                </div>
            </div>
        </div>

        {{-- Pelanggan Info --}}
        <div class="kv-card">
            <div class="kv-card-hdr">
                <div class="kv-card-ico" style="background:linear-gradient(135deg,#3b82f6,#2563eb)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                Informasi Pelanggan
            </div>
            <div class="kv-card-body">
                <div class="kv-info">
                    <div class="kv-info-row"><span class="kv-info-lbl">Toko</span><span class="kv-info-val" style="font-weight:700;">{{ $kunjungan->pelanggan->nama_toko ?? '-' }}</span></div>
                    <div class="kv-info-row"><span class="kv-info-lbl">Pemilik</span><span class="kv-info-val">{{ $kunjungan->pelanggan->nama_pemilik ?? '-' }}</span></div>
                    <div class="kv-info-row"><span class="kv-info-lbl">No. HP</span><span class="kv-info-val">{{ $kunjungan->pelanggan->no_hp ?? '-' }}</span></div>
                    <div class="kv-info-row" style="border:none;padding-bottom:0;"><span class="kv-info-lbl">Alamat</span><span class="kv-info-val">{{ $kunjungan->pelanggan->alamat ?? '-' }}</span></div>
                </div>
            </div>
        </div>

        {{-- Waktu & Foto --}}
        <div class="kv-card">
            <div class="kv-card-hdr">
                <div class="kv-card-ico" style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                Waktu Kunjungan
            </div>
            <div class="kv-card-body">
                <div class="kv-info">
                    <div class="kv-info-row"><span class="kv-info-lbl">Check-in</span><span class="kv-info-val" style="color:#16a34a;font-weight:700;">{{ $kunjungan->waktu_checkin ? $kunjungan->waktu_checkin->format('d M Y, H:i') : '-' }} WIB</span></div>
                    @if($kunjungan->latitude_checkin && $kunjungan->longitude_checkin)
                    <div class="kv-info-row"><span class="kv-info-lbl">Koordinat</span><a href="https://maps.google.com/?q={{ $kunjungan->latitude_checkin }},{{ $kunjungan->longitude_checkin }}" target="_blank" class="kv-link">{{ number_format($kunjungan->latitude_checkin, 6) }}, {{ number_format($kunjungan->longitude_checkin, 6) }}</a></div>
                    @endif
                    <div class="kv-info-row" style="border:none;padding-bottom:0;"><span class="kv-info-lbl">Status</span><span class="kv-info-val" style="font-size:.8125rem;color:#64748b;">Tercatat otomatis dari penjualan</span></div>
                </div>
                @if($kunjungan->foto_checkin)
                <div class="kv-photo">
                    <div class="kv-photo-lbl">Foto</div>
                    <img src="{{ asset('storage/' . $kunjungan->foto_checkin) }}" alt="Foto" class="kv-photo-img" onclick="window.open(this.src,'_blank')">
                </div>
                @endif
            </div>
        </div>

        {{-- Transaksi --}}
        <div class="kv-card">
            <div class="kv-card-hdr">
                <div class="kv-card-ico" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                Informasi Transaksi
            </div>
            <div class="kv-card-body">
                @if($kunjungan->ada_penjualan && $kunjungan->penjualan)
                <div class="kv-tx">
                    <div class="kv-tx-hdr">
                        <div class="kv-tx-ico">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div class="kv-tx-title">Ada Transaksi Penjualan</div>
                            <div class="kv-tx-sub">No. Faktur: <strong>{{ $kunjungan->penjualan->no_faktur }}</strong></div>
                        </div>
                    </div>
                    <div class="kv-tx-body">
                        <div class="kv-tx-item">
                            <div class="kv-tx-lbl">Total Nominal</div>
                            <div class="kv-tx-val hi">Rp {{ number_format($kunjungan->penjualan->total, 0, ',', '.') }}</div>
                        </div>
                        <div class="kv-tx-item">
                            <div class="kv-tx-lbl">Metode Bayar</div>
                            <div class="kv-tx-val">{{ ucfirst($kunjungan->penjualan->tipe_bayar) }}</div>
                        </div>
                        <div class="kv-tx-item">
                            <div class="kv-tx-lbl">Status</div>
                            <div class="kv-tx-val">
                                <span class="kv-badge {{ $kunjungan->penjualan->status }}">
                                    <span class="kv-dot {{ $kunjungan->penjualan->status }}"></span>
                                    {{ ucfirst($kunjungan->penjualan->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="kv-empty">
                    <div class="kv-empty-ico">📝</div>
                    <p>Tidak ada transaksi pada kunjungan ini</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Catatan --}}
        @if($kunjungan->catatan)
        <div class="kv-card">
            <div class="kv-card-hdr">
                <div class="kv-card-ico" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                Catatan Kunjungan
            </div>
            <div class="kv-card-body">
                <div class="kv-note">{{ $kunjungan->catatan }}</div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
