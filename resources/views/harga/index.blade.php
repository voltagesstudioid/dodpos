<x-app-layout>
    <x-slot name="header">Daftar Harga</x-slot>

    <div class="page-container">

        {{-- Filter + Print --}}
        <div class="card" style="padding:1.25rem 1.5rem; margin-bottom:1.5rem;">
            <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
                <div class="form-group" style="margin-bottom:0; flex:1; min-width:200px;">
                    <label class="form-label" style="font-size:0.7rem;">Cari Produk / SKU</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Ketik nama produk atau SKU...">
                </div>
                <div class="form-group" style="margin-bottom:0; min-width:180px;">
                    <label class="form-label" style="font-size:0.7rem;">Kategori</label>
                    <select name="category_id" class="form-input">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">🔍 Filter</button>
                <a href="{{ route('harga.index') }}" class="btn-secondary">Reset</a>
                <button type="button" onclick="window.print()" class="btn-secondary" style="margin-right:auto;">🖨️ Cetak</button>
                @can('create_master_produk')
                <a href="{{ route('products.create') }}" class="btn-primary" style="background:#4f46e5;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:0.4rem;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Harga Baru
                </a>
                @endcan
            </form>
        </div>

        {{-- Summary --}}
        <div style="display:flex; gap:1rem; margin-bottom:1rem; align-items:center;">
            <div style="font-size:0.875rem;font-weight:600;color:#1e293b;">💲 Daftar Harga Produk</div>
            <div style="font-size:0.75rem;color:#64748b;background:#f1f5f9;padding:0.25rem 0.75rem;border-radius:999px;">
                {{ $products->count() }} produk ditampilkan
            </div>
            @if(request('search') || request('category_id'))
                <div style="font-size:0.75rem;color:#4f46e5;background:#e0e7ff;padding:0.25rem 0.75rem;border-radius:999px;">Filter aktif</div>
            @endif
        </div>

        {{-- Price List Table --}}
        <div class="card">
            <div class="table-wrapper">
                <table class="data-table" id="price-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align:middle; border-right:2px solid #e2e8f0;">#</th>
                            <th rowspan="2" style="vertical-align:middle;">Produk</th>
                            <th rowspan="2" style="vertical-align:middle; border-right:2px solid #e2e8f0;">Kategori</th>
                            <th colspan="3" style="text-align:center; background:#f0fdf4; color:#15803d; border-bottom:1px solid #bbf7d0;">Harga Eceran</th>
                            <th colspan="3" style="text-align:center; background:#eff6ff; color:#1d4ed8; border-bottom:1px solid #bfdbfe; border-right:2px solid #e2e8f0;">Harga Grosir</th>
                            <th rowspan="2" style="vertical-align:middle; text-align:center; width:90px;">Aksi</th>
                        </tr>
                        <tr>
                            <th style="background:#f0fdf4; color:#15803d; font-size:0.7rem;">Satuan</th>
                            <th style="background:#f0fdf4; color:#15803d; font-size:0.7rem;">Isi</th>
                            <th style="background:#f0fdf4; color:#15803d; font-size:0.7rem; border-right:2px solid #e2e8f0;">Harga</th>
                            <th style="background:#eff6ff; color:#1d4ed8; font-size:0.7rem;">Satuan</th>
                            <th style="background:#eff6ff; color:#1d4ed8; font-size:0.7rem;">Isi</th>
                            <th style="background:#eff6ff; color:#1d4ed8; font-size:0.7rem; border-right:2px solid #e2e8f0;">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $i => $product)
                        @php
                            $units     = $product->unitConversions->sortBy('conversion_factor');
                            $baseUnit  = $units->firstWhere('is_base_unit', true) ?? $units->first();
                            $rowCount  = max(1, $units->count());
                        @endphp

                        @if($units->isEmpty())
                        {{-- Product with no unit conversions --}}
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div style="font-weight:600;color:#1e293b;">{{ $product->name }}</div>
                                <div style="font-size:0.7rem;color:#94a3b8;">{{ $product->sku }}</div>
                            </td>
                            <td style="font-size:0.8rem;color:#64748b; border-right:2px solid #f1f5f9;">{{ $product->category?->name ?? '-' }}</td>
                            <td colspan="6" style="text-align:center;color:#94a3b8;font-size:0.8rem; border-right:2px solid #f1f5f9;">— Belum ada data satuan & harga —</td>
                            <td style="text-align:center; vertical-align:middle;">
                                <div style="display:flex; flex-direction:column; gap:0.35rem; align-items:center; justify-content:center;">
                                    @can('edit_master_produk')
                                    <a href="{{ route('products.edit', $product) }}" class="btn-secondary" style="padding:0.25rem 0.6rem; font-size:0.75rem; width:100%; text-align:center;" title="Edit Harga">✏️ Edit</a>
                                    @endcan
                                    @can('delete_master_produk')
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline; width:100%;" onsubmit="return confirm('Yakin menghapus produk beserta harganya?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger" style="padding:0.25rem 0.6rem; font-size:0.75rem; width:100%; text-align:center;" title="Hapus">🗑️ Hapus</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @else
                        @foreach($units as $j => $uc)
                        <tr @if($uc->is_base_unit) style="background:#fefce8;" @endif>
                            @if($j === 0)
                            <td rowspan="{{ $rowCount }}" style="vertical-align:middle; border-right:2px solid #f1f5f9; font-size:0.8rem; color:#94a3b8;">{{ $i + 1 }}</td>
                            <td rowspan="{{ $rowCount }}" style="vertical-align:middle;">
                                <div style="font-weight:700;color:#1e293b;">{{ $product->name }}</div>
                                <div style="font-size:0.7rem;color:#94a3b8;">{{ $product->sku }}</div>
                            </td>
                            <td rowspan="{{ $rowCount }}" style="vertical-align:middle; font-size:0.8rem; color:#64748b; border-right:2px solid #f1f5f9;">
                                {{ $product->category?->name ?? '-' }}
                            </td>
                            @endif

                            {{-- Eceran --}}
                            <td style="background:#fafffe; font-weight:600;">
                                {{ $uc->unit->name }}
                                @if($uc->is_base_unit) <span style="font-size:0.6rem;background:#fef9c3;color:#a16207;padding:1px 4px;border-radius:4px;margin-left:2px;">Dasar</span> @endif
                            </td>
                            <td style="background:#fafffe; text-align:center; color:#64748b; font-size:0.8rem;">
                                {{ $uc->conversion_factor }}x
                            </td>
                            <td style="background:#fafffe; font-weight:800; color:#15803d; border-right:2px solid #e2e8f0;">
                                @if($uc->sell_price_ecer > 0)
                                    Rp {{ number_format($uc->sell_price_ecer, 0, ',', '.') }}
                                @else
                                    <span style="color:#d1d5db;">-</span>
                                @endif
                            </td>

                            {{-- Grosir --}}
                            <td style="background:#f8faff; font-weight:600; color:#1d4ed8;">
                                {{ $uc->unit->name }}
                            </td>
                            <td style="background:#f8faff; text-align:center; color:#64748b; font-size:0.8rem;">
                                {{ $uc->conversion_factor }}x
                            </td>
                            <td style="background:#f8faff; font-weight:800; color:#1d4ed8; border-right:2px solid #e2e8f0;">
                                @if($uc->sell_price_grosir > 0)
                                    Rp {{ number_format($uc->sell_price_grosir, 0, ',', '.') }}
                                @else
                                    <span style="color:#d1d5db;">-</span>
                                @endif
                            </td>

                            @if($j === 0)
                            <td rowspan="{{ $rowCount }}" style="vertical-align:middle; text-align:center;">
                                <div style="display:flex; flex-direction:column; gap:0.35rem; align-items:center; justify-content:center; padding:0 0.25rem;">
                                    @can('edit_master_produk')
                                    <a href="{{ route('products.edit', $product) }}" class="btn-secondary" style="padding:0.25rem 0.6rem; font-size:0.75rem; width:100%; text-align:center;" title="Edit Harga">✏️ Edit</a>
                                    @endcan
                                    @can('delete_master_produk')
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline; width:100%;" onsubmit="return confirm('Yakin menghapus produk beserta harganya?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger" style="padding:0.25rem 0.6rem; font-size:0.75rem; width:100%; text-align:center;" title="Hapus">🗑️ Hapus</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        @endif

                        {{-- Row separator --}}
                        <tr style="height:2px; background:#f1f5f9;"><td colspan="9" style="padding:0;border:none;"></td></tr>

                        @empty
                        <tr>
                            <td colspan="10" style="text-align:center; padding:3rem; color:#94a3b8;">
                                Tidak ada produk yang sesuai dengan filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer note --}}
        <div style="margin-top:0.75rem; font-size:0.75rem; color:#94a3b8; text-align:right;">
            🟡 Baris kuning = satuan dasar (terkecil) &nbsp;|&nbsp; Harga per: {{ now()->format('d M Y, H:i') }}
        </div>
    </div>

    <style>
        /* Print styles */
        @media print {
            .sidebar, .topbar, form, button, .page-container > div:first-child { display: none !important; }
            .main-wrapper { margin-left: 0 !important; padding: 0 !important; }
            .card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
            #price-table th, #price-table td { font-size: 0.7rem !important; padding: 0.3rem 0.5rem !important; }
            body { font-size: 12px; }
        }
    </style>
</x-app-layout>
