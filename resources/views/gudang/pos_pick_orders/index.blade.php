<x-app-layout>
    <x-slot name="header">Gudang / Permintaan Dari Kasir</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-container">
            {{-- ─── HEADER ─── --}}
            <div class="tr-hero">
                <div class="tr-hero-text">
                    <h1 class="tr-title">Permintaan Barang dari Kasir</h1>
                    <p class="tr-subtitle">Kelola permintaan pengambilan barang dari kasir ke gudang</p>
                </div>
                <div class="tr-hero-stats">
                    <div class="stat-badge bg-warning">
                        <span class="stat-num">{{ $counts['pending'] }}</span>
                        <span class="stat-lbl">Menunggu</span>
                    </div>
                    <div class="stat-badge bg-info">
                        <span class="stat-num">{{ $counts['processing'] }}</span>
                        <span class="stat-lbl">Diproses</span>
                    </div>
                    <div class="stat-badge bg-success">
                        <span class="stat-num">{{ $counts['ready'] }}</span>
                        <span class="stat-lbl">Siap</span>
                    </div>
                </div>
            </div>

            {{-- ─── FILTER TABS ─── --}}
            <div class="tr-filter-bar">
                <div class="filter-tabs">
                    <a href="{{ route('gudang.pos_pick.index', ['status' => 'pending']) }}"
                       class="tab-item {{ $status === 'pending' ? 'active' : '' }}">
                        <span class="tab-dot warning"></span>
                        Menunggu
                        @if($counts['pending'] > 0)
                            <span class="tab-badge">{{ $counts['pending'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('gudang.pos_pick.index', ['status' => 'processing']) }}"
                       class="tab-item {{ $status === 'processing' ? 'active' : '' }}">
                        <span class="tab-dot info"></span>
                        Diproses
                        @if($counts['processing'] > 0)
                            <span class="tab-badge">{{ $counts['processing'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('gudang.pos_pick.index', ['status' => 'ready']) }}"
                       class="tab-item {{ $status === 'ready' ? 'active' : '' }}">
                        <span class="tab-dot success"></span>
                        Siap Diambil
                        @if($counts['ready'] > 0)
                            <span class="tab-badge">{{ $counts['ready'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('gudang.pos_pick.index', ['status' => 'completed']) }}"
                       class="tab-item {{ $status === 'completed' ? 'active' : '' }}">
                        <span class="tab-dot secondary"></span>
                        Selesai
                    </a>
                    <a href="{{ route('gudang.pos_pick.index', ['status' => 'all']) }}"
                       class="tab-item {{ $status === 'all' ? 'active' : '' }}">
                        Semua
                    </a>
                </div>

                <form class="search-box" method="GET">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="text" name="search" placeholder="Cari nomor pick order / invoice..."
                           value="{{ request('search') }}">
                    <button type="submit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success'))
                <div class="tr-alert success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="tr-alert danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ─── PICK ORDERS LIST ─── --}}
            <div class="tr-card">
                @if($pickOrders->count() > 0)
                    <div class="pick-list">
                        @foreach($pickOrders as $pick)
                            <div class="pick-item">
                                <div class="pick-main">
                                    <div class="pick-header">
                                        <span class="pick-number">{{ $pick->pick_number }}</span>
                                        <span class="status-badge status-{{ $pick->status }}">
                                            {{ $pick->status_label }}
                                        </span>
                                    </div>
                                    <div class="pick-meta">
                                        <span class="meta-item">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            {{ $pick->created_at->format('d M Y, H:i') }}
                                        </span>
                                        <span class="meta-item">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                            {{ $pick->requester?->name ?? '-' }}
                                        </span>
                                        <span class="meta-item">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                            </svg>
                                            {{ $pick->warehouse?->name ?? 'Gudang Utama' }}
                                        </span>
                                    </div>
                                    <div class="pick-items-preview">
                                        @foreach($pick->items->take(3) as $item)
                                            <span class="item-tag">{{ $item->product?->name }} ({{ $item->unit_qty }} {{ $item->unit_name }})</span>
                                        @endforeach
                                        @if($pick->items->count() > 3)
                                            <span class="item-tag more">+{{ $pick->items->count() - 3 }} lainnya</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="pick-actions">
                                    <a href="{{ route('gudang.pos_pick.show', $pick) }}" class="btn-detail">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{ $pickOrders->links() }}
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"></path>
                            </svg>
                        </div>
                        <h3>Tidak ada permintaan</h3>
                        <p>Belum ada permintaan pengambilan barang dengan status ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-text: #0f172a;
            --tr-muted: #64748b;
            --tr-border: #e2e8f0;
            --tr-warning: #f59e0b;
            --tr-info: #3b82f6;
            --tr-success: #10b981;
            --tr-danger: #ef4444;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .tr-page-wrapper { background: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; padding-bottom: 3rem; }
        .tr-container { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }

        .tr-hero { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2rem; }
        .tr-title { font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem; }
        .tr-subtitle { color: var(--tr-muted); margin: 0; }

        .tr-hero-stats { display: flex; gap: 1rem; }
        .stat-badge { padding: 0.75rem 1.25rem; border-radius: 12px; display: flex; flex-direction: column; align-items: center; min-width: 80px; }
        .stat-badge.bg-warning { background: #fef3c7; color: #92400e; }
        .stat-badge.bg-info { background: #dbeafe; color: #1e40af; }
        .stat-badge.bg-success { background: #dcfce7; color: #166534; }
        .stat-num { font-size: 1.5rem; font-weight: 800; }
        .stat-lbl { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }

        .tr-filter-bar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
        .filter-tabs { display: flex; gap: 0.5rem; background: #fff; padding: 0.5rem; border-radius: 12px; border: 1px solid var(--tr-border); }
        .tab-item { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; color: var(--tr-muted); text-decoration: none; transition: 0.2s; }
        .tab-item:hover { background: #f1f5f9; }
        .tab-item.active { background: var(--tr-text); color: #fff; }
        .tab-dot { width: 8px; height: 8px; border-radius: 50%; }
        .tab-dot.warning { background: var(--tr-warning); }
        .tab-dot.info { background: var(--tr-info); }
        .tab-dot.success { background: var(--tr-success); }
        .tab-dot.secondary { background: var(--tr-muted); }
        .tab-badge { background: var(--tr-danger); color: #fff; font-size: 0.7rem; padding: 2px 6px; border-radius: 10px; margin-left: 4px; }

        .search-box { display: flex; gap: 0.5rem; }
        .search-box input { padding: 0.6rem 1rem; border: 1px solid var(--tr-border); border-radius: 8px; font-size: 0.875rem; min-width: 250px; }
        .search-box button { padding: 0.6rem 1rem; background: var(--tr-text); color: #fff; border: none; border-radius: 8px; cursor: pointer; }

        .tr-alert { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; }
        .tr-alert.success { background: #dcfce7; color: #166534; }
        .tr-alert.danger { background: #fee2e2; color: #991b1b; }

        .tr-card { background: #fff; border-radius: 16px; border: 1px solid var(--tr-border); padding: 1.5rem; }

        .pick-list { display: flex; flex-direction: column; gap: 1rem; }
        .pick-item { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem; border: 1px solid var(--tr-border); border-radius: 12px; transition: 0.2s; }
        .pick-item:hover { box-shadow: var(--shadow-md); border-color: #cbd5e1; }
        .pick-main { flex: 1; }
        .pick-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem; }
        .pick-number { font-weight: 800; font-size: 1.1rem; color: var(--tr-text); }
        .status-badge { padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-ready { background: #dcfce7; color: #166534; }
        .status-completed { background: #f1f5f9; color: var(--tr-muted); }

        .pick-meta { display: flex; gap: 1.5rem; margin-bottom: 0.75rem; }
        .meta-item { display: flex; align-items: center; gap: 0.4rem; font-size: 0.875rem; color: var(--tr-muted); }

        .pick-items-preview { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .item-tag { background: #f1f5f9; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.8rem; color: var(--tr-text); }
        .item-tag.more { background: #e2e8f0; color: var(--tr-muted); }

        .pick-actions { display: flex; gap: 0.5rem; }
        .btn-detail { display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; background: var(--tr-text); color: #fff; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: 0.2s; }
        .btn-detail:hover { background: #000; }

        .empty-state { text-align: center; padding: 4rem 2rem; }
        .empty-icon { color: var(--tr-muted); margin-bottom: 1rem; }
        .empty-state h3 { font-size: 1.25rem; font-weight: 700; margin: 0 0 0.5rem; }
        .empty-state p { color: var(--tr-muted); margin: 0; }

        @media (max-width: 768px) {
            .tr-hero { flex-direction: column; align-items: flex-start; }
            .tr-hero-stats { width: 100%; justify-content: space-between; }
            .filter-tabs { overflow-x: auto; width: 100%; }
            .pick-item { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .pick-actions { width: 100%; }
            .btn-detail { width: 100%; justify-content: center; }
        }
    </style>
    @endpush
</x-app-layout>
