<div class="mp-panel">
    {{-- TOOLBAR --}}
    <div class="mp-toolbar">
        <div class="mp-search-wrap">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="mp-search-input" placeholder="Cari nama, SKU, atau barcode..."
                x-model="produkSearch" @input="onProdukSearchInput">
        </div>

        <select class="mp-filter-select" x-model="produkCategory" @change="loadProduk(1)">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <label class="mp-check-label">
            <input type="checkbox" x-model="produkLowStock" @change="loadProduk(1)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            </svg>
            Stok Menipis
        </label>

        @can('create_master_produk')
        <button type="button" class="mp-add-btn" @click="openModal('produk-add')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Produk
        </button>
        @endcan
    </div>

    {{-- TABLE --}}
    <div class="table-wrapper">
        <table class="mp-table">
            <thead>
                <tr>
                    <th style="width:44px;text-align:center;">#</th>
                    <th>Produk</th>
                    <th style="width:130px;">Kategori</th>
                    <th style="width:80px;text-align:center;">Satuan</th>
                    <th style="text-align:right;width:130px;">Harga Jual</th>
                    <th style="text-align:center;width:140px;">Stok</th>
                    <th style="text-align:center;width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- SKELETON --}}
                <template x-if="produkLoading && produkData.data.length === 0">
                    <tr>
                        <td colspan="7" style="padding:0;">
                            <div class="mp-skel-row"><div class="mp-skel mp-skel-cell" style="width:24px;flex-shrink:0;"></div><div class="mp-skel mp-skel-cell" style="width:38%;"></div><div class="mp-skel mp-skel-cell" style="width:14%;"></div><div class="mp-skel mp-skel-cell" style="width:9%;"></div><div class="mp-skel mp-skel-cell" style="width:12%;"></div><div class="mp-skel mp-skel-cell" style="width:13%;"></div><div class="mp-skel mp-skel-cell" style="width:11%;"></div></div>
                            <div class="mp-skel-row"><div class="mp-skel mp-skel-cell" style="width:24px;flex-shrink:0;"></div><div class="mp-skel mp-skel-cell" style="width:42%;"></div><div class="mp-skel mp-skel-cell" style="width:12%;"></div><div class="mp-skel mp-skel-cell" style="width:9%;"></div><div class="mp-skel mp-skel-cell" style="width:13%;"></div><div class="mp-skel mp-skel-cell" style="width:13%;"></div><div class="mp-skel mp-skel-cell" style="width:11%;"></div></div>
                            <div class="mp-skel-row"><div class="mp-skel mp-skel-cell" style="width:24px;flex-shrink:0;"></div><div class="mp-skel mp-skel-cell" style="width:35%;"></div><div class="mp-skel mp-skel-cell" style="width:16%;"></div><div class="mp-skel mp-skel-cell" style="width:9%;"></div><div class="mp-skel mp-skel-cell" style="width:11%;"></div><div class="mp-skel mp-skel-cell" style="width:13%;"></div><div class="mp-skel mp-skel-cell" style="width:11%;"></div></div>
                            <div class="mp-skel-row"><div class="mp-skel mp-skel-cell" style="width:24px;flex-shrink:0;"></div><div class="mp-skel mp-skel-cell" style="width:40%;"></div><div class="mp-skel mp-skel-cell" style="width:13%;"></div><div class="mp-skel mp-skel-cell" style="width:9%;"></div><div class="mp-skel mp-skel-cell" style="width:12%;"></div><div class="mp-skel mp-skel-cell" style="width:13%;"></div><div class="mp-skel mp-skel-cell" style="width:11%;"></div></div>
                        </td>
                    </tr>
                </template>

                {{-- EMPTY --}}
                <template x-if="!produkLoading && produkData.data.length === 0">
                    <tr><td colspan="7">
                        <div class="mp-empty">
                            <div class="mp-empty-icon">📦</div>
                            <div class="mp-empty-title">Belum ada produk</div>
                            <div class="mp-empty-desc">Tambahkan produk pertama ke sistem untuk mulai berjualan</div>
                            @can('create_master_produk')
                            <button type="button" class="mp-add-btn" style="margin:0 auto;" @click="openModal('produk-add')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Tambah Produk
                            </button>
                            @endcan
                        </div>
                    </td></tr>
                </template>

                {{-- DATA ROWS --}}
                <template x-for="(p, i) in produkData.data" :key="p.id">
                    <tr>
                        <td style="text-align:center;color:#94a3b8;font-size:0.75rem;font-weight:500;"
                            x-text="((produkData.pagination.current_page-1)*produkData.pagination.per_page)+(i+1)"></td>

                        <td>
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <span class="mp-prod-name" x-text="p.name"></span>
                                <span x-show="p.units && p.units.length > 0"
                                    style="background:#f3e8ff;color:#7c3aed;font-size:0.6rem;font-weight:700;padding:1px 6px;border-radius:4px;"
                                    x-text="p.units.length+'×'"></span>
                            </div>
                            <div style="display:flex;align-items:center;gap:0.375rem;margin-top:4px;flex-wrap:wrap;">
                                <span class="mp-prod-sku" x-text="p.sku"></span>
                                <span x-show="p.barcode" class="mp-prod-barcode" x-text="p.barcode"></span>
                            </div>
                        </td>

                        <td>
                            <span style="background:#f1f5f9;color:#475569;font-size:0.72rem;font-weight:600;padding:0.2rem 0.65rem;border-radius:99px;"
                                x-text="p.category || '—'"></span>
                        </td>

                        <td style="text-align:center;">
                            <span style="background:#fffbeb;color:#92400e;font-size:0.72rem;font-weight:600;padding:0.2rem 0.6rem;border-radius:99px;"
                                x-text="p.unit_name || 'pcs'"></span>
                        </td>

                        <td style="text-align:right;font-weight:800;color:#059669;font-size:0.875rem;"
                            x-text="formatRp(p.price)"></td>

                        <td style="text-align:center;">
                            <div class="mp-stock-wrap">
                                <span class="mp-stock-badge"
                                    :class="stockBadgeClass(p.stock, p.min_stock)"
                                    x-text="Number(p.stock).toLocaleString()+' '+(p.unit_name||'pcs')"></span>
                                <div class="mp-stock-bar">
                                    <div class="mp-stock-bar-fill"
                                        :class="stockBarClass(p.stock, p.min_stock)"
                                        :style="'width:'+stockBarWidth(p.stock, p.min_stock)+'%'"></div>
                                </div>
                                <div x-show="p.min_stock > 0" class="mp-stock-min">
                                    Min: <span x-text="p.min_stock"></span>
                                </div>
                                <template x-if="p.stock_breakdown && p.stock_breakdown.length > 1">
                                    <div style="font-size:0.6rem;color:#94a3b8;display:flex;gap:3px;flex-wrap:wrap;justify-content:center;">
                                        <template x-for="sb in p.stock_breakdown" :key="sb.warehouse_id">
                                            <span style="background:#f8fafc;padding:0 5px;border-radius:3px;" x-text="sb.warehouse+': '+sb.qty"></span>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </td>

                        <td>
                            <div class="mp-act-grp">
                                <button type="button" class="mp-act-btn mp-act-edit" @click="openEditProduk(p.id)">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Edit
                                </button>
                                @can('delete_master_produk')
                                <button type="button" class="mp-act-btn mp-act-del" @click="deleteProduk(p.id, p.name)">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                                        <path d="M10 11v6"/><path d="M14 11v6"/>
                                        <path d="M9 6V4h6v2"/>
                                    </svg>
                                    Hapus
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div x-show="produkData.pagination.last_page > 1" class="mp-pagination">
        <span class="mp-page-info"
            x-text="'Menampilkan ' + ((produkData.pagination.current_page-1)*produkData.pagination.per_page + 1) + '–' + Math.min(produkData.pagination.current_page*produkData.pagination.per_page, produkData.pagination.total) + ' dari ' + produkData.pagination.total + ' produk'">
        </span>
        <div class="mp-pages">
            <button class="mp-page-btn" :disabled="produkData.pagination.current_page <= 1"
                @click="loadProduk(produkData.pagination.current_page - 1)">‹</button>
            <template x-for="pg in produkData.pagination.last_page" :key="pg">
                <button class="mp-page-btn" :class="pg === produkData.pagination.current_page ? 'active' : ''"
                    @click="loadProduk(pg)" x-text="pg"></button>
            </template>
            <button class="mp-page-btn" :disabled="produkData.pagination.current_page >= produkData.pagination.last_page"
                @click="loadProduk(produkData.pagination.current_page + 1)">›</button>
        </div>
    </div>
</div>
