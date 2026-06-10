<x-app-layout>
    @push('styles')
    <style>
        .rg-page { max-width:60rem; margin:0 auto; padding:0 1rem; }
        .rg-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .rg-hdr-l { display:flex; align-items:center; gap:1rem; }
        .rg-hdr-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0; background:linear-gradient(135deg,#f59e0b,#dc2626); box-shadow:0 8px 24px rgba(220,38,38,0.3); }
        .rg-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; }
        .rg-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .rg-hdr-actions { display:flex; gap:0.5rem; }
        .rg-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.625rem 1.125rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit; text-decoration:none;
        }
        .rg-btn-back { background:#f1f5f9; color:#64748b; }
        .rg-btn-back:hover { background:#e2e8f0; }
        .rg-btn-edit { background:linear-gradient(135deg,#f59e0b,#dc2626); color:#fff; box-shadow:0 6px 20px rgba(220,38,38,0.3); }
        .rg-btn-edit:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(220,38,38,0.4); }

        .rg-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.5rem; margin-bottom:1.25rem;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .rg-card-title {
            font-size:0.8125rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em;
            color:#f59e0b; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:2px solid #fef3c7;
        }

        .rg-detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .rg-detail-item { }
        .rg-detail-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; margin-bottom:0.25rem; }
        .rg-detail-val { font-size:0.9375rem; font-weight:600; color:#0f172a; }
        .rg-detail-desc { font-size:0.875rem; color:#64748b; line-height:1.5; }

        .rg-badge {
            display:inline-flex; align-items:center; padding:0.25rem 0.625rem; border-radius:8px;
            font-size:0.75rem; font-weight:700;
        }
        .rg-badge.aktif { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .rg-badge.nonaktif { background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; }

        /* Harga Table */
        .rg-harga-tbl { width:100%; border-collapse:collapse; }
        .rg-harga-tbl th {
            text-align:left; padding:0.625rem 0.75rem; font-size:0.6875rem; font-weight:700;
            text-transform:uppercase; letter-spacing:0.06em; color:#64748b; background:#f8fafc;
            border-bottom:2px solid #e2e8f0;
        }
        .rg-harga-tbl td { padding:0.625rem 0.75rem; border-bottom:1px solid #f1f5f9; }
        .rg-harga-tbl tr:hover td { background:#fffbeb; }
        .rg-harga-custom { font-weight:700; color:#059669; }
        .rg-harga-default { color:#94a3b8; }

        /* Lists */
        .rg-list { list-style:none; padding:0; margin:0; }
        .rg-list-item {
            display:flex; align-items:center; justify-content:space-between;
            padding:0.625rem 0; border-bottom:1px solid #f1f5f9;
        }
        .rg-list-item:last-child { border-bottom:none; }
        .rg-list-name { font-weight:600; color:#0f172a; font-size:0.875rem; }
        .rg-list-sub { font-size:0.75rem; color:#94a3b8; }

        .rg-empty-msg { text-align:center; padding:1.5rem; color:#94a3b8; font-size:0.875rem; }

        @media(max-width:640px) { .rg-detail-grid { grid-template-columns:1fr; } .rg-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="rg-page">

            <div class="rg-hdr">
                <div class="rg-hdr-l">
                    <div class="rg-hdr-ico">🗺️</div>
                    <div>
                        <div class="rg-hdr-title">{{ $regional->nama }}</div>
                        <div class="rg-hdr-sub">{{ $regional->kode_regional }}</div>
                    </div>
                </div>
                <div class="rg-hdr-actions">
                    <a href="{{ route('minyak.regional.index') }}" class="rg-btn rg-btn-back">← Kembali</a>
                    <a href="{{ route('minyak.regional.edit', $regional) }}" class="rg-btn rg-btn-edit">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit
                    </a>
                </div>
            </div>

            {{-- Detail Info --}}
            <div class="rg-card">
                <div class="rg-card-title">Informasi Regional</div>
                <div class="rg-detail-grid">
                    <div class="rg-detail-item">
                        <div class="rg-detail-lbl">Status</div>
                        <div><span class="rg-badge {{ $regional->status }}">{{ ucfirst($regional->status) }}</span></div>
                    </div>
                    <div class="rg-detail-item">
                        <div class="rg-detail-lbl">Dibuat</div>
                        <div class="rg-detail-val">{{ $regional->created_at?->format('d M Y') }}</div>
                    </div>
                    @if($regional->deskripsi)
                        <div class="rg-detail-item" style="grid-column:1/-1;">
                            <div class="rg-detail-lbl">Deskripsi</div>
                            <div class="rg-detail-desc">{{ $regional->deskripsi }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Harga Produk --}}
            <div class="rg-card">
                <div class="rg-card-title">Harga Jual per Produk</div>
                @php
                    $hargaMap = $regional->hargaProduk->pluck('harga_jual', 'produk_id')->toArray();
                @endphp
                @if($produks->count())
                    <table class="rg-harga-tbl">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga Default</th>
                                <th>Harga Regional</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produks as $produk)
                                <tr>
                                    <td>
                                        <div style="font-weight:600;color:#0f172a;">{{ $produk->nama }}</div>
                                        <div style="font-size:0.6875rem;color:#94a3b8;">{{ $produk->kode_produk }}</div>
                                    </td>
                                    <td class="rg-harga-default">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                                    <td>
                                        @if(isset($hargaMap[$produk->id]))
                                            <span class="rg-harga-custom">Rp {{ number_format($hargaMap[$produk->id], 0, ',', '.') }}</span>
                                        @else
                                            <span class="rg-harga-default">— (default)</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="rg-empty-msg">Belum ada produk aktif.</div>
                @endif
            </div>

            {{-- Sales --}}
            <div class="rg-card">
                <div class="rg-card-title">Sales di Regional Ini ({{ $regional->sales->count() }})</div>
                @if($regional->sales->count())
                    <ul class="rg-list">
                        @foreach($regional->sales as $sales)
                            <li class="rg-list-item">
                                <div>
                                    <div class="rg-list-name">{{ $sales->nama }}</div>
                                    <div class="rg-list-sub">{{ $sales->kode_sales }} · {{ $sales->no_kendaraan ?? '-' }}</div>
                                </div>
                                <span class="rg-badge {{ $sales->status }}">{{ ucfirst($sales->status) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="rg-empty-msg">Belum ada sales di regional ini.</div>
                @endif
            </div>

            {{-- Pelanggan --}}
            <div class="rg-card">
                <div class="rg-card-title">Pelanggan di Regional Ini ({{ $regional->pelanggans->count() }})</div>
                @if($regional->pelanggans->count())
                    <ul class="rg-list">
                        @foreach($regional->pelanggans as $pelanggan)
                            <li class="rg-list-item">
                                <div>
                                    <div class="rg-list-name">{{ $pelanggan->nama_toko }}</div>
                                    <div class="rg-list-sub">{{ $pelanggan->nama_pemilik }} · {{ $pelanggan->kota ?? '-' }}</div>
                                </div>
                                <span class="rg-badge {{ $pelanggan->status }}">{{ ucfirst($pelanggan->status) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="rg-empty-msg">Belum ada pelanggan di regional ini.</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
