<x-app-layout>
    <x-slot name="header">Kategori Barang</x-slot>
    <style>
        .kg-page{max-width:1100px;margin:0 auto;padding:1.5rem;}
        .kg-header{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;}
        .kg-header-left{display:flex;align-items:center;gap:0.875rem;}
        .kg-icon-box{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;flex-shrink:0;}
        .kg-title{font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;line-height:1.3;}
        .kg-subtitle{font-size:0.8125rem;color:#64748b;margin:0.125rem 0 0;}

        .kg-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:0.75rem;margin-bottom:1.5rem;}
        .kg-stat{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1rem 1.125rem;display:flex;align-items:center;gap:0.75rem;transition:box-shadow .2s;}
        .kg-stat:hover{box-shadow:0 2px 8px rgba(0,0,0,.06);}
        .kg-stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .kg-stat-icon.bg-indigo{background:#eef2ff;color:#6366f1;}
        .kg-stat-icon.bg-emerald{background:#ecfdf5;color:#059669;}
        .kg-stat-icon.bg-amber{background:#fffbeb;color:#d97706;}
        .kg-stat-icon.bg-rose{background:#fff1f2;color:#e11d48;}
        .kg-stat-value{font-size:1.25rem;font-weight:700;color:#1e293b;line-height:1.2;font-family:'JetBrains Mono',monospace;}
        .kg-stat-label{font-size:0.6875rem;color:#94a3b8;margin-top:0.0625rem;}

        .kg-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;}
        .kg-filter{padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;}
        .kg-filter form{display:flex;gap:0.625rem;align-items:center;flex-wrap:wrap;}
        .kg-filter input{height:38px;border:1px solid #e2e8f0;border-radius:8px;padding:0 0.75rem;font-size:0.8125rem;outline:none;flex:1;min-width:200px;max-width:320px;transition:border-color .2s;}
        .kg-filter input:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1);}

        .kg-table-header{display:flex;justify-content:space-between;align-items:center;padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;}
        .kg-table-title{font-size:0.9375rem;font-weight:600;color:#1e293b;display:flex;align-items:center;gap:0.5rem;}
        .kg-table-meta{font-size:0.75rem;color:#94a3b8;}

        .kg-table{width:100%;border-collapse:collapse;}
        .kg-table thead th{padding:0.75rem 1.25rem;font-size:0.6875rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#94a3b8;background:#f8fafc;border-bottom:1px solid #e2e8f0;white-space:nowrap;}
        .kg-table tbody tr{border-bottom:1px solid #f1f5f9;transition:background .15s;}
        .kg-table tbody tr:hover{background:#f8fafc;}
        .kg-table tbody tr:last-child{border-bottom:none;}
        .kg-table tbody td{padding:0.75rem 1.25rem;font-size:0.8125rem;color:#475569;vertical-align:middle;}
        .kg-table .kg-num{width:44px;font-size:0.6875rem;color:#cbd5e1;text-align:center;}

        .kg-name{font-weight:600;color:#1e293b;font-size:0.875rem;}
        .kg-desc{font-size:0.75rem;color:#94a3b8;margin-top:0.125rem;}
        .kg-count-badge{display:inline-flex;align-items:center;gap:0.25rem;background:#eef2ff;color:#4f46e5;font-size:0.75rem;font-weight:600;padding:0.25rem 0.625rem;border-radius:6px;font-family:'JetBrains Mono',monospace;}
        .kg-count-empty{display:inline-flex;align-items:center;gap:0.25rem;background:#f1f5f9;color:#94a3b8;font-size:0.75rem;font-weight:500;padding:0.25rem 0.625rem;border-radius:6px;}

        .kg-act{display:flex;gap:0.375rem;justify-content:center;}
        .kg-act-btn{display:inline-flex;align-items:center;gap:0.25rem;padding:0.375rem 0.75rem;border-radius:7px;font-size:0.75rem;font-weight:500;text-decoration:none;border:1px solid;transition:all .15s;cursor:pointer;}
        .kg-act-edit{background:#eef2ff;color:#4f46e5;border-color:#c7d2fe;}
        .kg-act-edit:hover{background:#e0e7ff;}
        .kg-act-del{background:#fff1f2;color:#e11d48;border-color:#fecdd3;}
        .kg-act-del:hover{background:#ffe4e6;}

        .kg-empty{text-align:center;padding:3rem 1rem;}
        .kg-empty-icon{font-size:2.5rem;margin-bottom:0.75rem;opacity:.5;}
        .kg-empty-title{font-size:0.9375rem;font-weight:600;color:#64748b;margin-bottom:0.375rem;}
        .kg-pagination{padding:1rem 1.25rem;border-top:1px solid #f1f5f9;}
    </style>

    <div class="kg-page">
        {{-- Header --}}
        <div class="kg-header">
            <div class="kg-header-left">
                <div class="kg-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div>
                    <h1 class="kg-title">Kategori Barang</h1>
                    <p class="kg-subtitle">Kelola pengelompokan produk untuk memudahkan pencarian</p>
                </div>
            </div>
            @can('create_master_kategori')
            <a href="{{ route('master.kategori.create') }}" class="btn-primary" style="display:inline-flex;align-items:center;gap:0.375rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Kategori
            </a>
            @endcan
        </div>

        {{-- Stats --}}
        <div class="kg-stats">
            <div class="kg-stat">
                <div class="kg-stat-icon bg-indigo">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div>
                    <div class="kg-stat-value">{{ $stats['totalKategori'] }}</div>
                    <div class="kg-stat-label">Total Kategori</div>
                </div>
            </div>
            <div class="kg-stat">
                <div class="kg-stat-icon bg-emerald">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <div class="kg-stat-value">{{ $stats['kategoriTerpakai'] }}</div>
                    <div class="kg-stat-label">Terpakai</div>
                </div>
            </div>
            <div class="kg-stat">
                <div class="kg-stat-icon bg-amber">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div>
                    <div class="kg-stat-value">{{ $stats['kategoriKosong'] }}</div>
                    <div class="kg-stat-label">Belum Terpakai</div>
                </div>
            </div>
            <div class="kg-stat">
                <div class="kg-stat-icon bg-rose">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                </div>
                <div>
                    <div class="kg-stat-value">{{ number_format($stats['totalProduk']) }}</div>
                    <div class="kg-stat-label">Total Produk</div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="kg-card">
            <div class="kg-filter">
                <form method="GET">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍  Cari kategori...">
                    <button type="submit" class="btn-primary btn-sm">Cari</button>
                    @if(request('search'))<a href="{{ route('master.kategori') }}" class="btn-secondary btn-sm">× Reset</a>@endif
                </form>
            </div>

            <div class="kg-table-header">
                <div class="kg-table-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Daftar Kategori
                </div>
                <div class="kg-table-meta">{{ $kategoris->total() }} kategori</div>
            </div>

            <div class="table-wrapper">
                <table class="kg-table">
                    <thead>
                        <tr>
                            <th class="kg-num">#</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th style="text-align:center;">Jumlah Produk</th>
                            <th style="text-align:center;width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $i => $kategori)
                        <tr>
                            <td class="kg-num">{{ $kategoris->firstItem() + $i }}</td>
                            <td>
                                <div class="kg-name">{{ $kategori->name }}</div>
                            </td>
                            <td>
                                <div class="kg-desc">{{ $kategori->description ?: '—' }}</div>
                            </td>
                            <td style="text-align:center;">
                                @if($kategori->products_count > 0)
                                    <span class="kg-count-badge">{{ $kategori->products_count }} produk</span>
                                @else
                                    <span class="kg-count-empty">0 produk</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <div class="kg-act">
                                    @can('edit_master_kategori')
                                    <a href="{{ route('master.kategori.edit', $kategori) }}" class="kg-act-btn kg-act-edit">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit
                                    </a>
                                    @endcan
                                    @can('delete_master_kategori')
                                    <form action="{{ route('master.kategori.destroy', $kategori) }}" method="POST"
                                        onsubmit="return confirm('Hapus kategori \'{{ $kategori->name }}\'?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="kg-act-btn kg-act-del">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="kg-empty">
                                    <div class="kg-empty-icon">🗂️</div>
                                    <div class="kg-empty-title">Belum ada kategori</div>
                                    @can('create_master_kategori')
                                    <a href="{{ route('master.kategori.create') }}" class="btn-primary btn-sm" style="margin-top:0.5rem;">＋ Tambah Kategori</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kategoris->hasPages())
            <div class="kg-pagination">{{ $kategoris->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
