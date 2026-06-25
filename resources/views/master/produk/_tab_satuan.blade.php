<div class="mp-panel">
    {{-- TOOLBAR --}}
    <div class="mp-toolbar">
        <div class="mp-search-wrap">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="mp-search-input" placeholder="Cari nama atau singkatan..."
                x-model="satuanSearch" @input="onSatuanSearchInput">
        </div>

        @can('create_master_satuan')
        <button type="button" class="mp-add-btn" @click="openModal('satuan-add')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Satuan
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
                    <th style="width:100px;">Singkatan</th>
                    <th>Deskripsi</th>
                    <th style="text-align:center;width:110px;">Digunakan</th>
                    <th style="text-align:center;width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- SKELETON --}}
                <template x-if="satuanLoading && satuanData.data.length === 0">
                    <tr><td colspan="6" style="padding:0;">
                        <div class="mp-skel-row"><div class="mp-skel mp-skel-cell" style="width:24px;flex-shrink:0;"></div><div class="mp-skel mp-skel-cell" style="width:28%;"></div><div class="mp-skel mp-skel-cell" style="width:10%;"></div><div class="mp-skel mp-skel-cell" style="width:32%;"></div><div class="mp-skel mp-skel-cell" style="width:10%;"></div><div class="mp-skel mp-skel-cell" style="width:14%;"></div></div>
                        <div class="mp-skel-row"><div class="mp-skel mp-skel-cell" style="width:24px;flex-shrink:0;"></div><div class="mp-skel mp-skel-cell" style="width:30%;"></div><div class="mp-skel mp-skel-cell" style="width:10%;"></div><div class="mp-skel mp-skel-cell" style="width:30%;"></div><div class="mp-skel mp-skel-cell" style="width:10%;"></div><div class="mp-skel mp-skel-cell" style="width:14%;"></div></div>
                    </td></tr>
                </template>

                {{-- EMPTY --}}
                <template x-if="!satuanLoading && satuanData.data.length === 0">
                    <tr><td colspan="6">
                        <div class="mp-empty">
                            <div class="mp-empty-icon">⚖</div>
                            <div class="mp-empty-title">Belum ada satuan</div>
                            <div class="mp-empty-desc">Tambahkan satuan seperti Dus, Karton, atau Slop</div>
                            @can('create_master_satuan')
                            <button type="button" class="mp-add-btn" style="margin:0 auto;" @click="openModal('satuan-add')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Tambah Satuan
                            </button>
                            @endcan
                        </div>
                    </td></tr>
                </template>

                {{-- DATA --}}
                <template x-for="(item, i) in satuanData.data" :key="item.id">
                    <tr>
                        <td style="text-align:center;color:#94a3b8;font-size:0.75rem;font-weight:500;"
                            x-text="((satuanData.pagination.current_page-1)*satuanData.pagination.per_page)+(i+1)"></td>
                        <td><span class="mp-prod-name" x-text="item.name"></span></td>
                        <td>
                            <span style="background:#fffbeb;color:#92400e;font-size:0.75rem;font-weight:700;padding:0.2rem 0.65rem;border-radius:99px;"
                                x-text="item.abbreviation"></span>
                        </td>
                        <td><span style="font-size:0.8rem;color:#64748b;" x-text="item.description || '—'"></span></td>
                        <td style="text-align:center;">
                            <span style="font-size:0.72rem;font-weight:700;padding:0.2rem 0.65rem;border-radius:99px;"
                                :style="item.products_count > 0 ? 'background:#dbeafe;color:#1d4ed8;' : 'background:#f1f5f9;color:#64748b;'"
                                x-text="item.products_count + ' produk'"></span>
                        </td>
                        <td>
                            <div class="mp-act-grp">
                                <button type="button" class="mp-act-btn mp-act-edit"
                                    @click="openEditSatuan(item.id, item.name, item.abbreviation, item.description||'')">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Edit
                                </button>
                                @can('delete_master_satuan')
                                <button type="button" class="mp-act-btn mp-act-del" @click="deleteSatuan(item.id)">
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
    <div x-show="satuanData.pagination.last_page > 1" class="mp-pagination">
        <span class="mp-page-info"
            x-text="'Menampilkan ' + ((satuanData.pagination.current_page-1)*satuanData.pagination.per_page + 1) + '–' + Math.min(satuanData.pagination.current_page*satuanData.pagination.per_page, satuanData.pagination.total) + ' dari ' + satuanData.pagination.total + ' satuan'">
        </span>
        <div class="mp-pages">
            <button class="mp-page-btn" :disabled="satuanData.pagination.current_page <= 1"
                @click="loadSatuan(satuanData.pagination.current_page - 1)">‹</button>
            <template x-for="pg in satuanData.pagination.last_page" :key="pg">
                <button class="mp-page-btn" :class="pg === satuanData.pagination.current_page ? 'active' : ''"
                    @click="loadSatuan(pg)" x-text="pg"></button>
            </template>
            <button class="mp-page-btn" :disabled="satuanData.pagination.current_page >= satuanData.pagination.last_page"
                @click="loadSatuan(satuanData.pagination.current_page + 1)">›</button>
        </div>
    </div>
</div>
