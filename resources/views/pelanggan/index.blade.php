<x-app-layout>
    <x-slot name="header">Data Pelanggan</x-slot>

    <div class="pg-page">

        {{-- ─── STAT CARDS ─── --}}
        <div class="pg-stats">
            <div class="pg-stat pg-stat-blue">
                <div class="pg-stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div class="pg-stat-label">Total Pelanggan</div>
                <div class="pg-stat-val">{{ $totalCustomers }}</div>
            </div>
            <div class="pg-stat pg-stat-green">
                <div class="pg-stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div class="pg-stat-label">Pelanggan Aktif</div>
                <div class="pg-stat-val">{{ $activeCustomers }}</div>
            </div>
            <div class="pg-stat pg-stat-red">
                <div class="pg-stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
                <div class="pg-stat-label">Total Piutang Aktif</div>
                <div class="pg-stat-val pg-mono">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
            </div>
            <div class="pg-stat pg-stat-amber">
                <div class="pg-stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                </div>
                <div class="pg-stat-label">Dengan Limit Kredit</div>
                <div class="pg-stat-val">{{ $withCredit }}</div>
            </div>
        </div>

        {{-- ─── FILTER BAR ─── --}}
        <div class="pg-filter-bar">
            <form method="GET" class="pg-filter-form">
                <div class="pg-search-box">
                    <svg class="pg-search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, telepon, atau email..." class="pg-search-input">
                </div>
                <select name="category" class="pg-select">
                    <option value="all" {{ request('category') == 'all' || !request('category') ? 'selected' : '' }}>Semua Kategori</option>
                    <option value="eceran" {{ request('category') == 'eceran' ? 'selected' : '' }}>Eceran</option>
                    <option value="grosir" {{ request('category') == 'grosir' ? 'selected' : '' }}>Grosir</option>
                    <option value="pos" {{ request('category') == 'pos' ? 'selected' : '' }}>Toko / POS</option>
                </select>
                <select name="active" class="pg-select">
                    <option value="" {{ request('active') === '' || !request()->has('active') ? 'selected' : '' }}>Semua Status</option>
                    <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="pg-btn pg-btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Filter
                </button>
                @if(request('search') || request('category') || request()->has('active'))
                    <a href="{{ route('pelanggan.index') }}" class="pg-btn pg-btn-ghost">Reset</a>
                @endif
                <div class="pg-filter-spacer"></div>
                @can('create_pelanggan')
                    <a href="{{ route('pelanggan.create') }}" class="pg-btn pg-btn-accent">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Pelanggan
                    </a>
                @endcan
            </form>
        </div>

        {{-- ─── TABLE ─── --}}
        <div class="pg-card">
            <div class="pg-table-wrap">
                <table class="pg-table">
                    <thead>
                        <tr>
                            <th class="pg-th-num">#</th>
                            <th>Profil Pelanggan</th>
                            <th>Kontak Telepon</th>
                            <th class="pg-th-r">Limit Kredit</th>
                            <th class="pg-th-r">Hutang Aktif</th>
                            <th class="pg-th-c">Status</th>
                            <th class="pg-th-c">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $i => $c)
                            <tr>
                                <td class="pg-num">{{ $customers->firstItem() + $i }}</td>
                                <td>
                                    <div class="pg-profile">
                                        <div class="pg-avatar">{{ strtoupper(substr($c->name, 0, 1)) }}</div>
                                        <div class="pg-profile-info">
                                            <div class="pg-profile-name">
                                                {{ $c->name }}
                                                @php
                                                    $catInfo = match($c->category) {
                                                        'eceran' => ['cls' => 'pg-badge-eceran', 'label' => 'Eceran'],
                                                        'grosir' => ['cls' => 'pg-badge-grosir', 'label' => 'Grosir'],
                                                        default  => ['cls' => 'pg-badge-pos', 'label' => 'Toko/POS'],
                                                    };
                                                @endphp
                                                <span class="pg-badge {{ $catInfo['cls'] }}">{{ $catInfo['label'] }}</span>
                                            </div>
                                            @if($c->email)
                                                <div class="pg-profile-email">{{ $c->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="pg-mono pg-text-main">{{ $c->phone ?: '—' }}</span>
                                </td>
                                <td class="pg-th-r pg-mono pg-text-muted">
                                    Rp {{ number_format($c->credit_limit, 0, ',', '.') }}
                                </td>
                                <td class="pg-th-r">
                                    @if($c->current_debt > 0)
                                        <span class="pg-mono pg-bold pg-text-red">Rp {{ number_format($c->current_debt, 0, ',', '.') }}</span>
                                    @else
                                        <span class="pg-bold pg-text-green">Lunas</span>
                                    @endif
                                </td>
                                <td class="pg-th-c">
                                    <span class="pg-status {{ $c->is_active ? 'pg-status-on' : 'pg-status-off' }}">
                                        {{ $c->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="pg-th-c">
                                    <div class="pg-actions">
                                        <a href="{{ route('pelanggan.show', $c) }}" class="pg-act-btn" title="Lihat Profil">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </a>
                                        @can('edit_pelanggan')
                                            <a href="{{ route('pelanggan.edit', $c) }}" class="pg-act-btn pg-act-edit" title="Edit Pelanggan">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                        @endcan
                                        @can('delete_pelanggan')
                                            <form action="{{ route('pelanggan.destroy', $c) }}" method="POST" class="pg-del-form"
                                                  onsubmit="return confirm('Yakin menghapus pelanggan {{ $c->name }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="pg-act-btn pg-act-del" title="Hapus">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="pg-empty">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    <p>Tidak ada pelanggan yang sesuai dengan filter.</p>
                                    @can('create_pelanggan')
                                        <a href="{{ route('pelanggan.create') }}" class="pg-btn pg-btn-accent" style="margin-top:0.5rem;">Tambah Pelanggan Pertama</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
                <div class="pg-pagination">{{ $customers->links() }}</div>
            @endif
        </div>

    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .pg-page { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem 4rem; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: #0f172a; }

        /* ── Stat Cards ── */
        .pg-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.25rem; }
        .pg-stat { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.15rem 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
        .pg-stat-blue { border-left: 4px solid #3b82f6; }
        .pg-stat-green { border-left: 4px solid #10b981; }
        .pg-stat-red { border-left: 4px solid #ef4444; }
        .pg-stat-amber { border-left: 4px solid #f59e0b; }
        .pg-stat-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.65rem; }
        .pg-stat-blue .pg-stat-icon { background: #dbeafe; color: #3b82f6; }
        .pg-stat-green .pg-stat-icon { background: #dcfce7; color: #10b981; }
        .pg-stat-red .pg-stat-icon { background: #fee2e2; color: #ef4444; }
        .pg-stat-amber .pg-stat-icon { background: #fef3c7; color: #f59e0b; }
        .pg-stat-label { font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 4px; }
        .pg-stat-val { font-size: 1.35rem; font-weight: 900; color: #0f172a; letter-spacing: -0.02em; }

        /* ── Filter Bar ── */
        .pg-filter-bar { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
        .pg-filter-form { display: flex; gap: 0.65rem; flex-wrap: wrap; align-items: center; }
        .pg-search-box { display: flex; align-items: center; gap: 8px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0 0.75rem; flex: 1; min-width: 200px; color: #94a3b8; }
        .pg-search-box:focus-within { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.08); }
        .pg-search-input { border: none; background: transparent; padding: 0.6rem 0; font-size: 0.85rem; font-weight: 500; color: #0f172a; outline: none; width: 100%; font-family: inherit; }
        .pg-select { border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.6rem 0.75rem; font-size: 0.85rem; font-weight: 600; color: #0f172a; background: #f8fafc; outline: none; font-family: inherit; cursor: pointer; min-width: 140px; }
        .pg-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.08); }
        .pg-filter-spacer { flex: 1; }

        .pg-btn { display: inline-flex; align-items: center; gap: 5px; padding: 0.6rem 1rem; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all 0.15s; border: 1px solid transparent; text-decoration: none; white-space: nowrap; font-family: inherit; }
        .pg-btn-primary { background: #3b82f6; color: #fff; box-shadow: 0 2px 6px rgba(59,130,246,0.15); }
        .pg-btn-primary:hover { background: #2563eb; transform: translateY(-1px); }
        .pg-btn-ghost { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }
        .pg-btn-ghost:hover { background: #f1f5f9; color: #0f172a; }
        .pg-btn-accent { background: #4f46e5; color: #fff; }
        .pg-btn-accent:hover { background: #4338ca; transform: translateY(-1px); }

        /* ── Card & Table ── */
        .pg-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); overflow: hidden; }
        .pg-table-wrap { width: 100%; overflow-x: auto; }
        .pg-table { width: 100%; border-collapse: collapse; min-width: 860px; }
        .pg-table thead th { background: #f8fafc; padding: 0.85rem 1rem; text-align: left; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; color: #64748b; border-bottom: 2px solid #e2e8f0; letter-spacing: 0.03em; }
        .pg-table tbody td { padding: 0.85rem 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.88rem; vertical-align: middle; }
        .pg-table tbody tr:last-child td { border-bottom: none; }
        .pg-table tbody tr:hover { background: #fafafa; }
        .pg-th-num { width: 40px; text-align: center; }
        .pg-th-r { text-align: right; }
        .pg-th-c { text-align: center; }

        /* Profile */
        .pg-profile { display: flex; align-items: center; gap: 10px; }
        .pg-avatar { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #eff6ff, #dbeafe); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #3b82f6; font-size: 0.85rem; flex-shrink: 0; border: 1px solid #bfdbfe; }
        .pg-profile-info { display: flex; flex-direction: column; gap: 2px; }
        .pg-profile-name { font-weight: 700; font-size: 0.88rem; color: #0f172a; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .pg-profile-email { font-size: 0.72rem; color: #64748b; }

        /* Badges */
        .pg-badge { font-size: 0.6rem; font-weight: 800; padding: 2px 7px; border-radius: 5px; letter-spacing: 0.03em; text-transform: uppercase; }
        .pg-badge-eceran { background: #ccfbf1; color: #0f766e; }
        .pg-badge-grosir { background: #f3e8ff; color: #7c3aed; }
        .pg-badge-pos { background: #e0e7ff; color: #4f46e5; }

        /* Status */
        .pg-status { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 99px; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.02em; }
        .pg-status-on { background: #dcfce7; color: #15803d; }
        .pg-status-off { background: #fee2e2; color: #991b1b; }

        /* Actions */
        .pg-actions { display: flex; gap: 4px; justify-content: center; }
        .pg-act-btn { width: 30px; height: 30px; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0; background: #fff; color: #64748b; transition: all 0.15s; cursor: pointer; text-decoration: none; }
        .pg-act-btn:hover { color: #3b82f6; border-color: #93c5fd; background: #eff6ff; }
        .pg-act-edit:hover { color: #f59e0b; border-color: #fcd34d; background: #fffbeb; }
        .pg-act-del:hover { color: #ef4444; border-color: #fca5a5; background: #fef2f2; }
        .pg-del-form { display: inline; margin: 0; }

        /* Utils */
        .pg-num { text-align: center; color: #94a3b8; font-size: 0.8rem; font-weight: 600; }
        .pg-mono { font-family: ui-monospace, 'Cascadia Code', 'Fira Code', Consolas, monospace; }
        .pg-bold { font-weight: 800; }
        .pg-text-main { color: #0f172a; }
        .pg-text-muted { color: #64748b; }
        .pg-text-green { color: #15803d; }
        .pg-text-red { color: #dc2626; }

        .pg-empty { text-align: center; padding: 3rem !important; color: #94a3b8; }
        .pg-empty svg { margin-bottom: 0.5rem; opacity: 0.35; }
        .pg-empty p { margin: 0 0 0.25rem; font-weight: 600; font-size: 0.9rem; }

        .pg-pagination { padding: 1rem 1.25rem; border-top: 1px solid #f1f5f9; }

        /* ── Responsive ── */
        @media (max-width: 992px) { .pg-stats { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) {
            .pg-filter-form { flex-direction: column; }
            .pg-search-box { min-width: 100%; }
            .pg-select { width: 100%; }
            .pg-filter-spacer { display: none; }
        }
        @media (max-width: 480px) { .pg-stats { grid-template-columns: 1fr; } }
    </style>
    @endpush
</x-app-layout>
