<div class="mp-panel">
    {{-- TOOLBAR --}}
    <div class="mp-toolbar">
        <div class="mp-search-wrap">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="mp-search-input" placeholder="Cari ref atau produk..."
                x-model="stokSearch" @input="onStokSearchInput">
        </div>

        <select class="mp-filter-select" style="min-width:130px;" x-model="stokTipe" @change="loadStok(1)">
            <option value="">Semua Tipe</option>
            <option value="in">Stok Masuk</option>
            <option value="out">Koreksi Stok</option>
        </select>

        <select class="mp-filter-select" style="min-width:140px;" x-model="stokWarehouse" @change="loadStok(1)">
            <option value="">Semua Gudang</option>
            @foreach($warehouses as $w)
                <option value="{{ $w->id }}">{{ $w->name }}</option>
            @endforeach
        </select>

        @can('create_stok_masuk')
        <button type="button" class="mp-add-btn" @click="openModal('stok-add')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Penyesuaian Stok
        </button>
        @endcan
    </div>

    {{-- TABLE --}}
    <div class="table-wrapper">
        <table class="mp-table">
            <thead>
                <tr>
                    <th style="width:44px;text-align:center;">#</th>
                    <th style="width:130px;">Referensi</th>
                    <th>Produk</th>
                    <th style="width:120px;">Gudang</th>
                    <th style="text-align:center;width:90px;">Tipe</th>
                    <th style="text-align:right;width:110px;">Jumlah</th>
                    <th style="text-align:center;width:120px;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                {{-- SKELETON --}}
                <template x-if="stokLoading && stokData.data.length === 0">
                    <tr><td colspan="7" style="padding:0;">
                        <div class="mp-skel-row"><div class="mp-skel mp-skel-cell" style="width:24px;flex-shrink:0;"></div><div class="mp-skel mp-skel-cell" style="width:14%;"></div><div class="mp-skel mp-skel-cell" style="width:25%;"></div><div class="mp-skel mp-skel-cell" style="width:12%;"></div><div class="mp-skel mp-skel-cell" style="width:8%;"></div><div class="mp-skel mp-skel-cell" style="width:10%;"></div><div class="mp-skel mp-skel-cell" style="width:12%;"></div></div>
                        <div class="mp-skel-row"><div class="mp-skel mp-skel-cell" style="width:24px;flex-shrink:0;"></div><div class="mp-skel mp-skel-cell" style="width:12%;"></div><div class="mp-skel mp-skel-cell" style="width:28%;"></div><div class="mp-skel mp-skel-cell" style="width:12%;"></div><div class="mp-skel mp-skel-cell" style="width:8%;"></div><div class="mp-skel mp-skel-cell" style="width:10%;"></div><div class="mp-skel mp-skel-cell" style="width:12%;"></div></div>
                        <div class="mp-skel-row"><div class="mp-skel mp-skel-cell" style="width:24px;flex-shrink:0;"></div><div class="mp-skel mp-skel-cell" style="width:13%;"></div><div class="mp-skel mp-skel-cell" style="width:26%;"></div><div class="mp-skel mp-skel-cell" style="width:12%;"></div><div class="mp-skel mp-skel-cell" style="width:8%;"></div><div class="mp-skel mp-skel-cell" style="width:10%;"></div><div class="mp-skel mp-skel-cell" style="width:12%;"></div></div>
                    </td></tr>
                </template>

                {{-- EMPTY --}}
                <template x-if="!stokLoading && stokData.data.length === 0">
                    <tr><td colspan="7">
                        <div class="mp-empty">
                            <div class="mp-empty-icon">📊</div>
                            <div class="mp-empty-title">Belum ada penyesuaian stok</div>
                            <div class="mp-empty-desc">Catat stok masuk atau koreksi stok di sini</div>
                            @can('create_stok_masuk')
                            <button type="button" class="mp-add-btn" style="margin:0 auto;" @click="openModal('stok-add')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Penyesuaian Stok
                            </button>
                            @endcan
                        </div>
                    </td></tr>
                </template>

                {{-- DATA --}}
                <template x-for="(item, i) in stokData.data" :key="item.id">
                    <tr>
                        <td style="text-align:center;color:#94a3b8;font-size:0.75rem;font-weight:500;"
                            x-text="((stokData.pagination.current_page-1)*stokData.pagination.per_page)+(i+1)"></td>
                        <td>
                            <span style="font-family:monospace;font-weight:700;font-size:0.75rem;color:#334155;background:#f8fafc;padding:2px 7px;border-radius:5px;border:1px solid #e2e8f0;"
                                x-text="item.reference_number"></span>
                        </td>
                        <td><span class="mp-prod-name" x-text="item.product_name"></span></td>
                        <td>
                            <span style="background:#f1f5f9;color:#475569;font-size:0.72rem;font-weight:600;padding:0.2rem 0.65rem;border-radius:99px;"
                                x-text="item.warehouse_name"></span>
                        </td>
                        <td style="text-align:center;">
                            <span style="font-size:0.72rem;font-weight:700;padding:0.2rem 0.7rem;border-radius:99px;"
                                :style="item.type === 'in' ? 'background:#dcfce7;color:#15803d;' : 'background:#fee2e2;color:#b91c1c;'"
                                x-text="item.type === 'in' ? 'Masuk' : 'Koreksi'"></span>
                        </td>
                        <td style="text-align:right;font-weight:800;font-family:monospace;color:#1e293b;"
                            x-text="Number(item.quantity).toLocaleString('id-ID')"></td>
                        <td style="text-align:center;font-size:0.75rem;color:#64748b;" x-text="item.created_at"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div x-show="stokData.pagination.last_page > 1" class="mp-pagination">
        <span class="mp-page-info"
            x-text="'Menampilkan ' + ((stokData.pagination.current_page-1)*stokData.pagination.per_page + 1) + '–' + Math.min(stokData.pagination.current_page*stokData.pagination.per_page, stokData.pagination.total) + ' dari ' + stokData.pagination.total + ' transaksi'">
        </span>
        <div class="mp-pages">
            <button class="mp-page-btn" :disabled="stokData.pagination.current_page <= 1"
                @click="loadStok(stokData.pagination.current_page - 1)">‹</button>
            <template x-for="pg in stokData.pagination.last_page" :key="pg">
                <button class="mp-page-btn" :class="pg === stokData.pagination.current_page ? 'active' : ''"
                    @click="loadStok(pg)" x-text="pg"></button>
            </template>
            <button class="mp-page-btn" :disabled="stokData.pagination.current_page >= stokData.pagination.last_page"
                @click="loadStok(stokData.pagination.current_page + 1)">›</button>
        </div>
    </div>
</div>
