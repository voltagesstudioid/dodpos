<div class="mp-panel">
    {{-- TOOLBAR --}}
    <div class="mp-toolbar">
        <div class="mp-search-wrap">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="mp-search-input" placeholder="Cari kategori..."
                x-model="kategoriSearch" @input="onKategoriSearchInput">
        </div>

        @can('create_master_kategori')
        <button type="button" class="mp-add-btn" @click="openModal('kategori-add')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Kategori
        </button>
        @endcan
    </div>

    {{-- TABLE --}}
    <div class="table-wrapper">
        <table class="mp-table">
            <thead>
                <tr>
                    <th style="width:44px;text-align:center;">#</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th style="text-align:center;width:110px;">Produk</th>
                    <th style="text-align:center;width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- SKELETON --}}
                <template x-if="kategoriLoading && kategoriData.data.length === 0">
                    <tr><td colspan="5" style="padding:0;">
                        <div class="mp-skel-row"><div class="mp-skel mp-skel-cell" style="width:24px;flex-shrink:0;"></div><div class="mp-skel mp-skel-cell" style="width:30%;"></div><div class="mp-skel mp-skel-cell" style="width:40%;"></div><div class="mp-skel mp-skel-cell" style="width:10%;"></div><div class="mp-skel mp-skel-cell" style="width:14%;"></div></div>
                        <div class="mp-skel-row"><div class="mp-skel mp-skel-cell" style="width:24px;flex-shrink:0;"></div><div class="mp-skel mp-skel-cell" style="width:28%;"></div><div class="mp-skel mp-skel-cell" style="width:42%;"></div><div class="mp-skel mp-skel-cell" style="width:10%;"></div><div class="mp-skel mp-skel-cell" style="width:14%;"></div></div>
                        <div class="mp-skel-row"><div class="mp-skel mp-skel-cell" style="width:24px;flex-shrink:0;"></div><div class="mp-skel mp-skel-cell" style="width:32%;"></div><div class="mp-skel mp-skel-cell" style="width:38%;"></div><div class="mp-skel mp-skel-cell" style="width:10%;"></div><div class="mp-skel mp-skel-cell" style="width:14%;"></div></div>
                    </td></tr>
                </template>

                {{-- EMPTY --}}
                <template x-if="!kategoriLoading && kategoriData.data.length === 0">
                    <tr><td colspan="5">
                        <div class="mp-empty">
                            <div class="mp-empty-icon">📂</div>
                            <div class="mp-empty-title">Belum ada kategori</div>
                            <div class="mp-empty-desc">Buat kategori untuk mengelompokkan produk</div>
                            @can('create_master_kategori')
                            <button type="button" class="mp-add-btn" style="margin:0 auto;" @click="openModal('kategori-add')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Tambah Kategori
                            </button>
                            @endcan
                        </div>
                    </td></tr>
                </template>

                {{-- DATA --}}
                <template x-for="(item, i) in kategoriData.data" :key="item.id">
                    <tr>
                        <td style="text-align:center;color:#94a3b8;font-size:0.75rem;font-weight:500;"
                            x-text="((kategoriData.pagination.current_page-1)*kategoriData.pagination.per_page)+(i+1)"></td>
                        <td>
                            <span class="mp-prod-name" x-text="item.name"></span>
                        </td>
                        <td>
                            <span style="font-size:0.8rem;color:#64748b;" x-text="item.description || '—'"></span>
                        </td>
                        <td style="text-align:center;">
                            <span style="padding:0.2rem 0.65rem;border-radius:99px;font-size:0.72rem;font-weight:700;"
                                :style="item.products_count > 0 ? 'background:#eef2ff;color:#4338ca;' : 'background:#f1f5f9;color:#64748b;'"
                                x-text="item.products_count + ' produk'"></span>
                        </td>
                        <td>
                            <div class="mp-act-grp">
                                <button type="button" class="mp-act-btn mp-act-edit"
                                    @click="openEditKategori(item.id, item.name, item.description||'')">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Edit
                                </button>
                                @can('delete_master_kategori')
                                <button type="button" class="mp-act-btn mp-act-del" @click="deleteKategori(item.id)">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                                        <path d="M10 11v6"/><path d="M14 11v6"/>
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
    <div x-show="kategoriData.pagination.last_page > 1" class="mp-pagination">
        <span class="mp-page-info"
            x-text="'Menampilkan ' + ((kategoriData.pagination.current_page-1)*kategoriData.pagination.per_page + 1) + '–' + Math.min(kategoriData.pagination.current_page*kategoriData.pagination.per_page, kategoriData.pagination.total) + ' dari ' + kategoriData.pagination.total + ' kategori'">
        </span>
        <div class="mp-pages">
            <button class="mp-page-btn" :disabled="kategoriData.pagination.current_page <= 1"
                @click="loadKategori(kategoriData.pagination.current_page - 1)">‹</button>
            <template x-for="pg in kategoriData.pagination.last_page" :key="pg">
                <button class="mp-page-btn" :class="pg === kategoriData.pagination.current_page ? 'active' : ''"
                    @click="loadKategori(pg)" x-text="pg"></button>
            </template>
            <button class="mp-page-btn" :disabled="kategoriData.pagination.current_page >= kategoriData.pagination.last_page"
                @click="loadKategori(kategoriData.pagination.current_page + 1)">›</button>
        </div>
    </div>
</div>
