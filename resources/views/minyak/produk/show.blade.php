<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap');

        .ps-page { max-width:52rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .ps-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.75rem; }
        .ps-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
        .ps-back:hover { color:#ea580c; }
        .ps-sep { color:#cbd5e1; font-size:0.8125rem; }
        .ps-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }
        .ps-code { margin-left:auto; font-size:0.75rem; font-weight:700; font-family:'JetBrains Mono',monospace; color:#64748b; background:#f1f5f9; padding:0.375rem 0.75rem; border-radius:8px; border:1px solid #e2e8f0; }

        .ps-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .ps-card-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
        .ps-card-ico { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .ps-card-ico svg { width:17px; height:17px; }
        .ps-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .ps-card-body { padding:1.375rem; }

        .ps-card.orange .ps-card-hdr { background:linear-gradient(135deg,#fff7ed,#ffedd5); }
        .ps-card.orange .ps-card-ico { background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; }
        .ps-card.green .ps-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
        .ps-card.green .ps-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .ps-card.blue .ps-card-hdr { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .ps-card.blue .ps-card-ico { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
        .ps-card.purple .ps-card-hdr { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .ps-card.purple .ps-card-ico { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }

        .ps-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .ps-grid3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; }

        .ps-item { display:flex; flex-direction:column; gap:0.25rem; }
        .ps-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
        .ps-val { font-size:0.875rem; font-weight:600; color:#0f172a; }
        .ps-val.mono { font-family:'JetBrains Mono',monospace; letter-spacing:0.02em; }
        .ps-val.money { color:#059669; }
        .ps-val.muted { color:#94a3b8; font-weight:500; }

        .ps-status { display:inline-flex; align-items:center; gap:6px; padding:0.3rem 0.875rem; border-radius:99px; font-size:0.75rem; font-weight:700; border:1px solid; }
        .ps-status.aktif { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .ps-status.nonaktif { background:#f8fafc; color:#64748b; border-color:#e2e8f0; }
        .ps-status-dot { width:7px; height:7px; border-radius:50%; }
        .ps-status-dot.aktif { background:#10b981; }
        .ps-status-dot.nonaktif { background:#94a3b8; }

        .ps-desc { font-size:0.8125rem; color:#475569; line-height:1.65; background:#f8fafc; padding:0.875rem 1rem; border-radius:10px; border:1px solid #f1f5f9; }

        .ps-tbl { width:100%; border-collapse:separate; border-spacing:0; font-size:0.8125rem; }
        .ps-tbl th { padding:0.625rem 0.875rem; text-align:left; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; background:#f8fafc; border-bottom:1px solid #e2e8f0; }
        .ps-tbl td { padding:0.625rem 0.875rem; border-bottom:1px solid #f1f5f9; color:#374151; }
        .ps-tbl tr:last-child td { border-bottom:none; }

        .ps-empty { text-align:center; padding:1.5rem; color:#94a3b8; font-size:0.8125rem; }

        .ps-actions { display:flex; gap:0.75rem; justify-content:flex-end; padding-top:0.5rem; }
        .ps-btn { display:inline-flex; align-items:center; gap:6px; padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600; text-decoration:none; transition:all 0.2s; border:1px solid transparent; font-family:inherit; cursor:pointer; }
        .ps-btn-edit { background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; box-shadow:0 4px 14px rgba(234,88,12,0.25); }
        .ps-btn-edit:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(234,88,12,0.35); }
        .ps-btn-back { background:#fff; border-color:#e2e8f0; color:#64748b; }
        .ps-btn-back:hover { background:#f8fafc; color:#0f172a; }

        @media(max-width:640px) { .ps-grid2 { grid-template-columns:1fr; } .ps-grid3 { grid-template-columns:1fr; } }
    </style>
    @endpush

    <div class="ps-page">

        {{-- Breadcrumb --}}
        <nav class="ps-nav">
            <a href="{{ route('minyak.produk.index') }}" class="ps-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Daftar Produk
            </a>
            <span class="ps-sep">/</span>
            <span class="ps-crumb">Detail Produk</span>
            <span class="ps-code">{{ $produk->kode_produk }}</span>
        </nav>

        {{-- CARD 1: Informasi Dasar --}}
        <div class="ps-card orange">
            <div class="ps-card-hdr">
                <div class="ps-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                </div>
                <div class="ps-card-title">Informasi Dasar</div>
            </div>
            <div class="ps-card-body">
                <div class="ps-grid2">
                    <div class="ps-item">
                        <span class="ps-lbl">Nama Produk</span>
                        <span class="ps-val">{{ $produk->nama }}</span>
                    </div>
                    <div class="ps-item">
                        <span class="ps-lbl">Jenis</span>
                        <span class="ps-val">{{ $produk->jenis ?? '-' }}</span>
                    </div>
                    <div class="ps-item">
                        <span class="ps-lbl">Satuan</span>
                        <span class="ps-val">{{ $produk->satuan }}</span>
                    </div>
                    <div class="ps-item">
                        <span class="ps-lbl">Status</span>
                        <span class="ps-status {{ $produk->status }}">
                            <span class="ps-status-dot {{ $produk->status }}"></span>
                            {{ ucfirst($produk->status) }}
                        </span>
                    </div>
                </div>
                @if($produk->keterangan)
                <div style="margin-top:1rem;">
                    <span class="ps-lbl" style="display:block;margin-bottom:0.375rem;">Keterangan</span>
                    <div class="ps-desc">{{ $produk->keterangan }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- CARD 2: Harga & Stok --}}
        <div class="ps-card green">
            <div class="ps-card-hdr">
                <div class="ps-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
                <div class="ps-card-title">Harga & Stok</div>
            </div>
            <div class="ps-card-body">
                <div class="ps-grid3">
                    <div class="ps-item">
                        <span class="ps-lbl">Harga Jual</span>
                        <span class="ps-val money">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                    </div>
                    <div class="ps-item">
                        <span class="ps-lbl">Harga Modal (HPP)</span>
                        <span class="ps-val">Rp {{ number_format($produk->harga_modal ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="ps-item">
                        <span class="ps-lbl">Margin</span>
                        @php $margin = (float)$produk->harga_jual - (float)($produk->harga_modal ?? 0); @endphp
                        <span class="ps-val" style="color:{{ $margin > 0 ? '#059669' : ($margin < 0 ? '#ef4444' : '#94a3b8') }}">
                            Rp {{ number_format($margin, 0, ',', '.') }}
                            @if($produk->harga_modal > 0)
                                ({{ number_format(($margin / $produk->harga_modal) * 100, 1) }}%)
                            @endif
                        </span>
                    </div>
                    <div class="ps-item">
                        <span class="ps-lbl">Stok Gudang</span>
                        <span class="ps-val mono">{{ number_format($produk->stok_gudang ?? 0, 0, ',', '.') }} {{ $produk->satuan }}</span>
                    </div>
                    <div class="ps-item">
                        <span class="ps-lbl">Stok Minimum</span>
                        <span class="ps-val mono" style="color:{{ ($produk->stok_gudang ?? 0) <= ($produk->stok_minimum ?? 0) && $produk->stok_minimum > 0 ? '#ef4444' : '#0f172a' }}">
                            {{ number_format($produk->stok_minimum ?? 0, 0, ',', '.') }} {{ $produk->satuan }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 3: Harga Regional --}}
        @if($produk->regionalHarga->count() > 0)
        <div class="ps-card blue">
            <div class="ps-card-hdr">
                <div class="ps-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                </div>
                <div class="ps-card-title">Harga Regional</div>
            </div>
            <div class="ps-card-body" style="padding:0;">
                <table class="ps-tbl">
                    <thead>
                        <tr>
                            <th>Regional</th>
                            <th style="text-align:right;">Harga Jual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produk->regionalHarga as $rh)
                        <tr>
                            <td>{{ $rh->regional->nama ?? '-' }}</td>
                            <td style="text-align:right;">
                                <span class="ps-val money">Rp {{ number_format($rh->harga_jual, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- CARD 4: Riwayat Loading --}}
        @if($produk->loadings->count() > 0)
        <div class="ps-card blue">
            <div class="ps-card-hdr">
                <div class="ps-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="ps-card-title">Riwayat Loading (Muat Barang)</div>
            </div>
            <div class="ps-card-body" style="padding:0;">
                <table class="ps-tbl">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Sales</th>
                            <th style="text-align:right;">Jumlah</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produk->loadings->sortByDesc('tanggal')->take(10) as $l)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($l->tanggal)->format('d M Y') }}</td>
                            <td>{{ $l->sales->nama ?? '-' }}</td>
                            <td style="text-align:right;"><span class="ps-val mono">{{ number_format($l->jumlah_loading, 0, ',', '.') }} {{ $produk->satuan }}</span></td>
                            <td style="text-align:center;">
                                <span class="ps-status {{ $l->status === 'selesai' ? 'aktif' : 'nonaktif' }}" style="font-size:0.6875rem;">
                                    <span class="ps-status-dot {{ $l->status === 'selesai' ? 'aktif' : 'nonaktif' }}"></span>
                                    {{ ucfirst($l->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- CARD 5: Riwayat Penjualan --}}
        @if($produk->penjualans->count() > 0)
        <div class="ps-card purple">
            <div class="ps-card-hdr">
                <div class="ps-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                </div>
                <div class="ps-card-title">Riwayat Penjualan Terakhir</div>
            </div>
            <div class="ps-card-body" style="padding:0;">
                <table class="ps-tbl">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th style="text-align:right;">Jumlah</th>
                            <th style="text-align:right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produk->penjualans->sortByDesc('tanggal_jual')->take(10) as $pj)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($pj->tanggal_jual)->format('d M Y') }}</td>
                            <td>{{ $pj->pelanggan->nama_toko ?? '-' }}</td>
                            <td style="text-align:right;"><span class="ps-val mono">{{ number_format($pj->jumlah, 0, ',', '.') }} {{ $produk->satuan }}</span></td>
                            <td style="text-align:right;"><span class="ps-val money">Rp {{ number_format($pj->total, 0, ',', '.') }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Actions --}}
        <div class="ps-actions">
            <a href="{{ route('minyak.produk.index') }}" class="ps-btn ps-btn-back">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali
            </a>
            <a href="{{ route('minyak.produk.edit', $produk->id) }}" class="ps-btn ps-btn-edit">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Produk
            </a>
        </div>

    </div>
</x-app-layout>
