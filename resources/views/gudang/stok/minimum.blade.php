<x-app-layout>
    <x-slot name="header">Alert Minimum Stok</x-slot>

    <div class="page-container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <div>
                <h1 style="font-size:1.375rem; font-weight:700; color:#1e293b; margin:0;">⚠️ Alert Minimum Stok</h1>
                <p style="color:#64748b; font-size:0.875rem; margin:0.25rem 0 0;">Daftar barang yang stok globalnya sudah mencapai atau di bawah batas minimum</p>
            </div>
            <a href="{{ route('gudang.stok') }}" class="btn-secondary">Lihat Semua Stok</a>
        </div>

        <div class="card">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Barang (SKU)</th>
                            <th>Kategori</th>
                            <th style="text-align:center;">Batas Minimum</th>
                            <th style="text-align:center;">Sisa Stok Global</th>
                            <th>Status Re-order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStockProducts as $product)
                            @php
                                $isCritical = $product->stock <= ($product->min_stock / 2);
                            @endphp
                        <tr>
                            <td>
                                <div style="font-weight:600; color:#1e293b;">{{ $product->name }}</div>
                                <div style="font-size:0.75rem; color:#64748b;">SKU: {{ $product->sku }}</div>
                            </td>
                            <td>
                                <div>{{ $product->category->name ?? '-' }}</div>
                            </td>
                            <td style="text-align:center;">
                                <span style="font-weight:600; color:#64748b;">{{ $product->min_stock }}</span>
                                <span style="font-size:0.75rem;">{{ $product->unit->abbreviation ?? '' }}</span>
                            </td>
                            <td style="text-align:center;">
                                @if(($maskStock ?? false) === true)
                                    <span style="display:inline-flex; align-items:center; padding:0.2rem 0.55rem; border-radius:999px; background:#fef3c7; color:#92400e; font-weight:900; font-size:0.78rem;">Terkunci</span>
                                @else
                                    @if($product->stock <= ($product->min_stock / 2))
                                    <span style="font-weight:800; color:#b91c1c; font-size:1.25rem;">{{ $product->stock }}</span>
                                    @else
                                    <span style="font-weight:800; color:#b45309; font-size:1.25rem;">{{ $product->stock }}</span>
                                    @endif
                                    <span style="font-size:0.75rem; color:#64748b;">{{ $product->unit->abbreviation ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->stock == 0)
                                    <span class="badge-error" style="background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; font-weight:700;">
                                        Habis Total (Out of Stock) !
                                    </span>
                                @elseif($isCritical)
                                    <span class="badge-error" style="background:#fef2f2; color:#b91c1c; border:1px solid #fecaca;">
                                        Sangat Kritis ⚠️
                                    </span>
                                @else
                                    <span class="badge-warning" style="background:#fffbeb; color:#b45309; border:1px solid #fde68a;">
                                        Segera Re-order
                                    </span>
                                @endif
                                <div style="margin-top:0.5rem;">
                                    <a href="#" class="text-sm" style="color:#6366f1; text-decoration:none;">🛒 Buat PO Baru →</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:3rem; color:#64748b;">
                                <div style="font-size:3rem; margin-bottom:1rem;">🎉</div>
                                <div style="font-size:1.1rem; font-weight:600; color:#1e293b;">Stok Aman!</div>
                                Saat ini tidak ada produk yang jumlahnya berada di bawah batas minimum.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($lowStockProducts->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $lowStockProducts->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
