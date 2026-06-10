<x-app-layout>
    <x-slot name="header">Satuan Barang</x-slot>
    <style>
        .st-page{max-width:1100px;margin:0 auto;padding:1.5rem;}
        .st-header{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;}
        .st-header-left{display:flex;align-items:center;gap:0.875rem;}
        .st-icon-box{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;flex-shrink:0;}
        .st-title{font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;line-height:1.3;}
        .st-subtitle{font-size:0.8125rem;color:#64748b;margin:0.125rem 0 0;}

        .st-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(155px,1fr));gap:0.75rem;margin-bottom:1.5rem;}
        .st-stat{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1rem 1.125rem;display:flex;align-items:center;gap:0.75rem;transition:box-shadow .2s;}
        .st-stat:hover{box-shadow:0 2px 8px rgba(0,0,0,.06);}
        .st-stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .st-stat-icon.bg-amber{background:#fffbeb;color:#d97706;}
        .st-stat-icon.bg-emerald{background:#ecfdf5;color:#059669;}
        .st-stat-icon.bg-blue{background:#eff6ff;color:#2563eb;}
        .st-stat-icon.bg-rose{background:#fff1f2;color:#e11d48;}
        .st-stat-value{font-size:1.25rem;font-weight:700;color:#1e293b;line-height:1.2;font-family:'JetBrains Mono',monospace;}
        .st-stat-label{font-size:0.6875rem;color:#94a3b8;margin-top:0.0625rem;}

        .st-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;}
        .st-filter{padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;}
        .st-filter form{display:flex;gap:0.625rem;align-items:center;flex-wrap:wrap;}
        .st-filter input{height:38px;border:1px solid #e2e8f0;border-radius:8px;padding:0 0.75rem;font-size:0.8125rem;outline:none;flex:1;min-width:200px;max-width:320px;transition:border-color .2s;}
        .st-filter input:focus{border-color:#d97706;box-shadow:0 0 0 3px rgba(217,119,6,.1);}

        .st-table-header{display:flex;justify-content:space-between;align-items:center;padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;}
        .st-table-title{font-size:0.9375rem;font-weight:600;color:#1e293b;display:flex;align-items:center;gap:0.5rem;}
        .st-table-meta{font-size:0.75rem;color:#94a3b8;}

        .st-table{width:100%;border-collapse:collapse;}
        .st-table thead th{padding:0.75rem 1.25rem;font-size:0.6875rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#94a3b8;background:#f8fafc;border-bottom:1px solid #e2e8f0;white-space:nowrap;}
        .st-table tbody tr{border-bottom:1px solid #f1f5f9;transition:background .15s;}
        .st-table tbody tr:hover{background:#f8fafc;}
        .st-table tbody tr:last-child{border-bottom:none;}
        .st-table tbody td{padding:0.75rem 1.25rem;font-size:0.8125rem;color:#475569;vertical-align:middle;}
        .st-table .st-num{width:44px;font-size:0.6875rem;color:#cbd5e1;text-align:center;}

        .st-name{font-weight:600;color:#1e293b;font-size:0.875rem;}
        .st-abbr{display:inline-block;background:#fffbeb;color:#92400e;font-size:0.75rem;font-weight:700;font-family:'JetBrains Mono',monospace;padding:0.1875rem 0.5rem;border-radius:5px;border:1px solid #fde68a;letter-spacing:0.02em;}
        .st-desc{font-size:0.75rem;color:#94a3b8;}
        .st-count-badge{display:inline-flex;align-items:center;gap:0.25rem;background:#eff6ff;color:#2563eb;font-size:0.75rem;font-weight:600;padding:0.25rem 0.625rem;border-radius:6px;font-family:'JetBrains Mono',monospace;}
        .st-count-empty{display:inline-flex;align-items:center;gap:0.25rem;background:#f1f5f9;color:#94a3b8;font-size:0.75rem;font-weight:500;padding:0.25rem 0.625rem;border-radius:6px;}
        .st-conv-badge{display:inline-flex;align-items:center;gap:0.25rem;background:#ecfdf5;color:#059669;font-size:0.6875rem;font-weight:500;padding:0.125rem 0.5rem;border-radius:4px;}

        .st-act{display:flex;gap:0.375rem;justify-content:center;}
        .st-act-btn{display:inline-flex;align-items:center;gap:0.25rem;padding:0.375rem 0.75rem;border-radius:7px;font-size:0.75rem;font-weight:500;text-decoration:none;border:1px solid;transition:all .15s;cursor:pointer;}
        .st-act-edit{background:#fffbeb;color:#92400e;border-color:#fde68a;}
        .st-act-edit:hover{background:#fef3c7;}
        .st-act-del{background:#fff1f2;color:#e11d48;border-color:#fecdd3;}
        .st-act-del:hover{background:#ffe4e6;}

        .st-empty{text-align:center;padding:3rem 1rem;}
        .st-empty-icon{font-size:2.5rem;margin-bottom:0.75rem;opacity:.5;}
        .st-empty-title{font-size:0.9375rem;font-weight:600;color:#64748b;margin-bottom:0.375rem;}
        .st-pagination{padding:1rem 1.25rem;border-top:1px solid #f1f5f9;}
    </style>

    <div class="st-page">
        {{-- Header --}}
        <div class="st-header">
            <div class="st-header-left">
                <div class="st-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                </div>
                <div>
                    <h1 class="st-title">Satuan Barang</h1>
                    <p class="st-subtitle">Kelola satuan ukuran produk (pcs, dus, karton, dll)</p>
                </div>
            </div>
            @can('create_master_satuan')
            <a href="{{ route('master.satuan.create') }}" class="btn-primary" style="display:inline-flex;align-items:center;gap:0.375rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Satuan
            </a>
            @endcan
        </div>

        {{-- Stats --}}
        <div class="st-stats">
            <div class="st-stat">
                <div class="st-stat-icon bg-amber">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                </div>
                <div>
                    <div class="st-stat-value">{{ $stats['totalSatuan'] }}</div>
                    <div class="st-stat-label">Total Satuan</div>
                </div>
            </div>
            <div class="st-stat">
                <div class="st-stat-icon bg-emerald">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <div class="st-stat-value">{{ $stats['satuanTerpakai'] }}</div>
                    <div class="st-stat-label">Terpakai Produk</div>
                </div>
            </div>
            <div class="st-stat">
                <div class="st-stat-icon bg-blue">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                </div>
                <div>
                    <div class="st-stat-value">{{ $stats['satuanKonversi'] }}</div>
                    <div class="st-stat-label">Dalam Konversi</div>
                </div>
            </div>
            <div class="st-stat">
                <div class="st-stat-icon bg-rose">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div>
                    <div class="st-stat-value">{{ $stats['satuanKosong'] }}</div>
                    <div class="st-stat-label">Belum Terpakai</div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="st-card">
            <div class="st-filter">
                <form method="GET">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍  Cari nama atau singkatan...">
                    <button type="submit" class="btn-primary btn-sm">Cari</button>
                    @if(request('search'))<a href="{{ route('master.satuan') }}" class="btn-secondary btn-sm">× Reset</a>@endif
                </form>
            </div>

            <div class="st-table-header">
                <div class="st-table-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Daftar Satuan
                </div>
                <div class="st-table-meta">{{ $units->total() }} satuan</div>
            </div>

            <div class="table-wrapper">
                <table class="st-table">
                    <thead>
                        <tr>
                            <th class="st-num">#</th>
                            <th>Nama Satuan</th>
                            <th>Singkatan</th>
                            <th>Deskripsi</th>
                            <th style="text-align:center;">Digunakan Oleh</th>
                            <th style="text-align:center;width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($units as $i => $unit)
                        <tr>
                            <td class="st-num">{{ $units->firstItem() + $i }}</td>
                            <td>
                                <div class="st-name">{{ $unit->name }}</div>
                            </td>
                            <td>
                                <span class="st-abbr">{{ $unit->abbreviation }}</span>
                            </td>
                            <td>
                                <div class="st-desc">{{ $unit->description ?: '—' }}</div>
                            </td>
                            <td style="text-align:center;">
                                @if($unit->products_count > 0)
                                    <span class="st-count-badge">{{ $unit->products_count }} produk</span>
                                @else
                                    <span class="st-count-empty">0 produk</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <div class="st-act">
                                    @can('edit_master_satuan')
                                    <a href="{{ route('master.satuan.edit', $unit) }}" class="st-act-btn st-act-edit">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit
                                    </a>
                                    @endcan
                                    @can('delete_master_satuan')
                                    <form action="{{ route('master.satuan.destroy', $unit) }}" method="POST"
                                        onsubmit="return confirm('Hapus satuan \'{{ $unit->name }}\'?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="st-act-btn st-act-del">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="st-empty">
                                    <div class="st-empty-icon">⚖️</div>
                                    <div class="st-empty-title">Belum ada satuan</div>
                                    @can('create_master_satuan')
                                    <a href="{{ route('master.satuan.create') }}" class="btn-primary btn-sm" style="margin-top:0.5rem;">＋ Tambah Satuan</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($units->hasPages())
            <div class="st-pagination">{{ $units->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
