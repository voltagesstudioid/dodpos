<x-app-layout>
    <x-slot name="header">Kasir / Status Pengambilan Barang</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-container">
            {{-- ─── HEADER ─── --}}
            <div class="tr-hero">
                <div class="tr-hero-text">
                    <h1 class="tr-title">Status Pengambilan Barang</h1>
                    <p class="tr-subtitle">Lacak status barang yang Anda pesan dari gudang</p>
                </div>
                <div class="tr-hero-stats">
                    @if($counts['ready'] > 0)
                    <div class="stat-badge bg-success pulse">
                        <span class="stat-num">{{ $counts['ready'] }}</span>
                        <span class="stat-lbl">Siap Diambil!</span>
                    </div>
                    @endif
                    <div class="stat-badge bg-warning">
                        <span class="stat-num">{{ $counts['pending'] }}</span>
                        <span class="stat-lbl">Menunggu</span>
                    </div>
                    <div class="stat-badge bg-info">
                        <span class="stat-num">{{ $counts['processing'] }}</span>
                        <span class="stat-lbl">Diproses</span>
                    </div>
                </div>
            </div>

            {{-- ─── FILTER TABS ─── --}}
            <div class="tr-filter-bar">
                <div class="filter-tabs">
                    <a href="{{ route('kasir.pick_orders') }}"
                       class="tab-item {{ $status === 'all' ? 'active' : '' }}">
                        Semua
                    </a>
                    <a href="{{ route('kasir.pick_orders', ['status' => 'pending']) }}"
                       class="tab-item {{ $status === 'pending' ? 'active' : '' }}">
                        <span class="tab-dot warning"></span>
                        Menunggu
                        @if($counts['pending'] > 0)
                            <span class="tab-badge">{{ $counts['pending'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('kasir.pick_orders', ['status' => 'processing']) }}"
                       class="tab-item {{ $status === 'processing' ? 'active' : '' }}">
                        <span class="tab-dot info"></span>
                        Diproses
                    </a>
                    <a href="{{ route('kasir.pick_orders', ['status' => 'ready']) }}"
                       class="tab-item {{ $status === 'ready' ? 'active' : '' }}">
                        <span class="tab-dot success"></span>
                        Siap
                        @if($counts['ready'] > 0)
                            <span class="tab-badge">{{ $counts['ready'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('kasir.pick_orders', ['status' => 'completed']) }}"
                       class="tab-item {{ $status === 'completed' ? 'active' : '' }}">
                        <span class="tab-dot secondary"></span>
                        Selesai
                    </a>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success'))
                <div class="tr-alert success">{{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div class="tr-alert info">{{ session('info') }}</div>
            @endif

            {{-- ─── READY NOTIFICATION ─── --}}
            @if($counts['ready'] > 0)
                <div class="ready-banner">
                    <div class="ready-icon">🎉</div>
                    <div class="ready-text">
                        <h4>{{ $counts['ready'] }} barang siap diambil!</h4>
                        <p>Silakan ke gudang untuk mengambil barang yang sudah disiapkan.</p>
                    </div>
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
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                            </svg>
                                            {{ $pick->warehouse?->name ?? 'Gudang Utama' }}
                                        </span>
                                        @if($pick->transaction)
                                            <span class="meta-item">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                </svg>
                                                {{ $pick->transaction->invoice_number }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="pick-items-preview">
                                        @foreach($pick->items->take(3) as $item)
                                            <span class="item-tag">{{ $item->product?->name }} ({{ $item->unit_qty }} {{ $item->unit_name }})</span>
                                        @endforeach
                                        @if($pick->items->count() > 3)
                                            <span class="item-tag more">+{{ $pick->items->count() - 3 }} lainnya</span>
                                        @endif
                                    </div>
                                    @if($pick->status === 'ready' && $pick->ready_at)
                                        <div class="ready-since">
                                            <span class="ready-dot"></span>
                                            Siap sejak {{ $pick->ready_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>
                                <div class="pick-actions">
                                    <a href="{{ route('kasir.pick_order.show', $pick) }}" class="btn-detail">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        Detail
                                    </a>
                                    @if($pick->status === 'ready')
                                        <span class="status-hint">Ambil di gudang →</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{ $pickOrders->links() }}
                @else
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <h3>Belum ada permintaan</h3>
                        <p>Transaksi POS Anda akan otomatis membuat permintaan pengambilan barang ke gudang.</p>
                        <a href="{{ route('kasir.index') }}" class="btn-primary">Buka Kasir</a>
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
        .tr-container { max-width: 1000px; margin: 0 auto; padding: 2rem 1.5rem; }

        .tr-hero { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2rem; }
        .tr-title { font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem; }
        .tr-subtitle { color: var(--tr-muted); margin: 0; }

        .tr-hero-stats { display: flex; gap: 1rem; }
        .stat-badge { padding: 0.75rem 1.25rem; border-radius: 12px; display: flex; flex-direction: column; align-items: center; min-width: 80px; }
        .stat-badge.bg-warning { background: #fef3c7; color: #92400e; }
        .stat-badge.bg-info { background: #dbeafe; color: #1e40af; }
        .stat-badge.bg-success { background: #dcfce7; color: #166534; }
        .stat-badge.pulse { animation: pulse 2s infinite; }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .stat-num { font-size: 1.5rem; font-weight: 800; }
        .stat-lbl { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }

        .tr-filter-bar { margin-bottom: 1.5rem; }
        .filter-tabs { display: flex; gap: 0.5rem; background: #fff; padding: 0.5rem; border-radius: 12px; border: 1px solid var(--tr-border); flex-wrap: wrap; }
        .tab-item { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; color: var(--tr-muted); text-decoration: none; transition: 0.2s; }
        .tab-item:hover { background: #f1f5f9; }
        .tab-item.active { background: var(--tr-text); color: #fff; }
        .tab-dot { width: 8px; height: 8px; border-radius: 50%; }
        .tab-dot.warning { background: var(--tr-warning); }
        .tab-dot.info { background: var(--tr-info); }
        .tab-dot.success { background: var(--tr-success); }
        .tab-dot.secondary { background: var(--tr-muted); }
        .tab-badge { background: var(--tr-danger); color: #fff; font-size: 0.7rem; padding: 2px 6px; border-radius: 10px; margin-left: 4px; }

        .tr-alert { padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; }
        .tr-alert.success { background: #dcfce7; color: #166534; }
        .tr-alert.info { background: #dbeafe; color: #1e40af; }

        .ready-banner { display: flex; align-items: center; gap: 1rem; background: linear-gradient(135deg, #dcfce7, #d1fae5); border: 1px solid #86efac; border-radius: 16px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
        .ready-icon { font-size: 2.5rem; }
        .ready-text h4 { margin: 0 0 0.25rem; font-size: 1.1rem; color: #166534; }
        .ready-text p { margin: 0; color: #15803d; font-size: 0.9rem; }

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

        .pick-meta { display: flex; gap: 1.5rem; margin-bottom: 0.75rem; flex-wrap: wrap; }
        .meta-item { display: flex; align-items: center; gap: 0.4rem; font-size: 0.875rem; color: var(--tr-muted); }

        .pick-items-preview { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.5rem; }
        .item-tag { background: #f1f5f9; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.8rem; color: var(--tr-text); }
        .item-tag.more { background: #e2e8f0; color: var(--tr-muted); }

        .ready-since { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #16a34a; font-weight: 600; margin-top: 0.5rem; }
        .ready-dot { width: 8px; height: 8px; background: #22c55e; border-radius: 50%; animation: blink 1.5s infinite; }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

        .pick-actions { display: flex; align-items: center; gap: 1rem; }
        .btn-detail { display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; background: var(--tr-text); color: #fff; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: 0.2s; }
        .btn-detail:hover { background: #000; }
        .status-hint { font-size: 0.8rem; color: var(--tr-success); font-weight: 600; }

        .empty-state { text-align: center; padding: 4rem 2rem; }
        .empty-icon { font-size: 3rem; margin-bottom: 1rem; }
        .empty-state h3 { font-size: 1.25rem; font-weight: 700; margin: 0 0 0.5rem; }
        .empty-state p { color: var(--tr-muted); margin: 0 0 1.5rem; }
        .btn-primary { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #4f46e5; color: #fff; border-radius: 8px; text-decoration: none; font-weight: 600; }

        @media (max-width: 768px) {
            .tr-hero { flex-direction: column; align-items: flex-start; }
            .tr-hero-stats { width: 100%; justify-content: space-between; }
            .pick-item { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .pick-actions { width: 100%; justify-content: space-between; }
            .btn-detail { flex: 1; justify-content: center; }
        }
    </style>
    @endpush
</x-app-layout>
