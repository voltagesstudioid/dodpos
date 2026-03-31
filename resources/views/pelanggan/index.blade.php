<x-app-layout>
    <x-slot name="header">Data Pelanggan</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER SECTION ─── --}}
            <div class="tr-header animate-fade-in">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Kontak</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-blue">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        Data Pelanggan
                    </h1>
                    <p class="tr-subtitle">Terdapat <strong>{{ $customers->total() }}</strong> pelanggan terdaftar dalam sistem.</p>
                </div>
                <div class="tr-header-actions">
                    @can('create_pelanggan')
                        <a href="{{ route('pelanggan.create') }}" class="tr-btn tr-btn-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Tambah Pelanggan
                        </a>
                    @endcan
                </div>
            </div>

            {{-- ─── FILTER & DATA TABLE ─── --}}
            <div class="tr-card animate-fade-in-up">
                
                {{-- Filter Bar --}}
                <div class="tr-filter-wrap">
                    <form method="GET" class="tr-filter-form">
                        <div class="tr-form-group select-grp">
                            <div class="tr-select-wrapper">
                                <select name="category" class="tr-select">
                                    <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                                    <option value="pos" {{ request('category') == 'pos' || request('category') == null ? 'selected' : '' }}>Toko / POS</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="tr-form-group search-grp">
                            <div class="tr-search-wrapper">
                                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau nomor telepon..." class="tr-input tr-input-search">
                            </div>
                        </div>
                        
                        <div class="tr-filter-actions">
                            <button type="submit" class="tr-btn tr-btn-dark">Filter</button>
                            @if(request('search') || (request('category') && request('category') !== 'pos'))
                                <a href="{{ route('pelanggan.index') }}" class="tr-btn tr-btn-light">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th class="c" style="width: 50px;">#</th>
                                <th>Profil Pelanggan</th>
                                <th>Kontak Telepon</th>
                                <th class="r">Limit Kredit</th>
                                <th class="r">Hutang Aktif</th>
                                <th class="c">Status</th>
                                <th class="c" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $i => $c)
                                <tr>
                                    <td class="c tr-text-muted tr-font-mono">{{ $customers->firstItem() + $i }}</td>
                                    <td>
                                        <div class="tr-user-profile">
                                            <div class="tr-avatar">
                                                {{ strtoupper(substr($c->name, 0, 1)) }}
                                            </div>
                                            <div class="tr-user-details">
                                                <div class="tr-user-name">
                                                    {{ $c->name }}
                                                    <span class="tr-cat-badge badge-pos">TOKO / POS</span>
                                                </div>
                                                @if($c->email)
                                                    <div class="tr-user-email">{{ $c->email }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="tr-font-mono tr-text-main">{{ $c->phone ?: '—' }}</span>
                                    </td>
                                    <td class="r tr-font-mono tr-text-muted">
                                        Rp {{ number_format($c->credit_limit, 0, ',', '.') }}
                                    </td>
                                    <td class="r">
                                        @if($c->current_debt > 0)
                                            <span class="tr-font-mono tr-font-bold text-danger">Rp {{ number_format($c->current_debt, 0, ',', '.') }}</span>
                                        @else
                                            <span class="tr-font-bold text-emerald">Lunas</span>
                                        @endif
                                    </td>
                                    <td class="c">
                                        <span class="tr-status-pill {{ $c->is_active ? 'active' : 'inactive' }}">
                                            {{ $c->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="c">
                                        <div class="tr-actions-group">
                                            <a href="{{ route('pelanggan.show', $c) }}" class="tr-action-btn view" title="Lihat Profil">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </a>
                                            @can('edit_pelanggan')
                                                <a href="{{ route('pelanggan.edit', $c) }}" class="tr-action-btn edit" title="Edit Pelanggan">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                            </div>
                                            <h6>Belum ada pelanggan ditemukan</h6>
                                            <p>Data pelanggan belum tersedia atau tidak ada yang cocok dengan pencarian Anda.</p>
                                            @can('create_pelanggan')
                                                <a href="{{ route('pelanggan.create') }}" class="tr-btn tr-btn-primary" style="margin-top: 0.5rem;">
                                                    Tambah Pelanggan Pertama
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($customers->hasPages())
                    <div class="tr-pagination-wrapper">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-blue: #3b82f6; --tr-blue-light: #dbeafe; --tr-blue-hover: #2563eb;
            --tr-emerald: #10b981; --tr-emerald-light: #dcfce7;
            --tr-danger: #ef4444; --tr-danger-light: #fee2e2;
            --tr-amber: #f59e0b; --tr-amber-light: #fef3c7;
            --tr-indigo: #4f46e5; --tr-indigo-light: #e0e7ff;
            --tr-bg: #f8fafc; --tr-surface: #ffffff; --tr-border: #e2e8f0;
            --tr-text-main: #0f172a; --tr-text-muted: #64748b;
            --tr-radius: 16px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); padding-bottom: 4rem; }
        .tr-page { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* ── ANIMATIONS ── */
        .animate-fade-in { animation: fadeIn 0.4s ease forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.4s ease forwards; opacity: 0; transform: translateY(10px); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem; }
        .tr-eyebrow { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-blue); margin-bottom: 0.5rem; }
        .tr-title { font-size: 1.625rem; font-weight: 900; margin: 0; display: flex; align-items: center; gap: 12px; letter-spacing: -0.02em; }
        .tr-title-icon-box { padding: 8px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .bg-blue { background: var(--tr-blue-light); color: var(--tr-blue); }
        .tr-subtitle { font-size: 0.9rem; color: var(--tr-text-muted); margin-top: 6px; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0.65rem 1.25rem; border-radius: 10px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: 0.2s; border: 1px solid transparent; text-decoration: none; white-space: nowrap; }
        .tr-btn-primary { background: var(--tr-blue); color: white; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2); }
        .tr-btn-primary:hover { background: var(--tr-blue-hover); transform: translateY(-1px); }
        .tr-btn-dark { background: var(--tr-text-main); color: white; }
        .tr-btn-dark:hover { background: #000; }
        .tr-btn-light { color: var(--tr-text-muted); background: transparent; }
        .tr-btn-light:hover { background: #f1f5f9; color: var(--tr-text-main); }

        /* ── CARD & FILTER ── */
        .tr-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; }
        
        .tr-filter-wrap { padding: 1.25rem 1.5rem; background: #fafafa; border-bottom: 1px solid var(--tr-border); }
        .tr-filter-form { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; }
        
        .tr-input, .tr-select { padding: 0.6rem 0.85rem; border: 1px solid var(--tr-border); border-radius: 8px; font-size: 0.85rem; background: #fff; transition: 0.2s; font-family: inherit; color: var(--tr-text-main); font-weight: 500; outline: none; }
        .tr-input:focus, .tr-select:focus { border-color: var(--tr-blue); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        
        .select-grp { width: 180px; }
        .search-grp { flex-grow: 1; min-width: 250px; }
        
        .tr-search-wrapper { position: relative; display: flex; align-items: center; }
        .search-icon { position: absolute; left: 12px; color: #94a3b8; }
        .tr-input-search { padding-left: 2.25rem; width: 100%; }

        .tr-select-wrapper { position: relative; width: 100%; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        .tr-select { appearance: none; padding-right: 2.5rem; cursor: pointer; width: 100%; }

        .tr-filter-actions { display: flex; gap: 0.5rem; }

        /* ── TABLE ── */
        .table-responsive { width: 100%; overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; min-width: 900px; }
        .tr-table thead th { background: #ffffff; padding: 1rem 1.25rem; text-align: left; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); border-bottom: 1px solid var(--tr-border); }
        .tr-table tbody td { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; vertical-align: middle; }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table tbody tr:hover { background: #fafafa; }
        .tr-table .c { text-align: center; } .tr-table .r { text-align: right; }

        /* User Profile in Table */
        .tr-user-profile { display: flex; align-items: center; gap: 12px; }
        .tr-avatar { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #eff6ff, #dbeafe); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--tr-blue); font-size: 0.9rem; flex-shrink: 0; border: 1px solid #bfdbfe; }
        .tr-user-details { display: flex; flex-direction: column; gap: 2px; }
        .tr-user-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.9rem; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;}
        .tr-user-email { font-size: 0.75rem; color: var(--tr-text-muted); }

        /* Category Badges */
        .tr-cat-badge { font-size: 0.65rem; font-weight: 800; padding: 2px 8px; border-radius: 6px; letter-spacing: 0.02em; }
        
        .badge-pos { background: var(--tr-indigo-light); color: var(--tr-indigo); }

        /* Status Pills */
        .tr-status-pill { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 99px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.02em; }
        .tr-status-pill.active { background: var(--tr-emerald-light); color: #065f46; }
        .tr-status-pill.inactive { background: var(--tr-danger-light); color: #991b1b; }

        /* Action Buttons */
        .tr-actions-group { display: flex; gap: 6px; justify-content: center; }
        .tr-action-btn { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--tr-border); background: white; color: var(--tr-text-muted); transition: 0.2s; cursor: pointer; text-decoration: none; }
        .tr-action-btn.view:hover { color: var(--tr-blue); border-color: var(--tr-blue); background: var(--tr-blue-light); }
        .tr-action-btn.edit:hover { color: var(--tr-amber); border-color: var(--tr-amber); background: var(--tr-amber-light); }

        /* ── UTILS ── */
        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
        .tr-font-bold { font-weight: 800; }
        .text-main { color: var(--tr-text-main); }
        .text-muted { color: var(--tr-text-muted); }
        .text-emerald { color: var(--tr-emerald); }
        .text-danger { color: var(--tr-danger); }

        .tr-empty-state { padding: 4rem 2rem; text-align: center; }
        .tr-empty-icon { color: #cbd5e1; margin-bottom: 1rem; }
        .tr-empty-state h6 { font-size: 1.125rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--tr-text-main); }
        .tr-empty-state p { color: var(--tr-text-muted); font-size: 0.9rem; }
        
        .tr-pagination-wrapper { padding: 1.25rem 1.5rem; border-top: 1px solid #f1f5f9; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .tr-filter-form { flex-direction: column; align-items: stretch; }
            .select-grp, .search-grp { width: 100%; }
            .tr-filter-actions { display: grid; grid-template-columns: 1fr 1fr; }
            .tr-header { flex-direction: column; align-items: stretch; }
            .tr-btn { justify-content: center; }
        }
    </style>
    @endpush
</x-app-layout>