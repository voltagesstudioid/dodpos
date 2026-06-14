<x-app-layout>
    <x-slot name="header">Terima PO (Supplier)</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Inbound Logistics</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        </div>
                        Penerimaan Purchase Order
                    </h1>
                    <p class="tr-subtitle">Daftar pesanan barang dari Supplier yang bersiap untuk masuk gudang.</p>
                </div>
            </div>

            {{-- TABBED NAVIGATION --}}
            <div class="tr-tabs">
                <a href="{{ route('gudang.terimapo.index') }}" class="tr-tab-item active-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    Terima PO dari Supplier
                </a>
                <a href="{{ route('gudang.penerimaan') }}" class="tr-tab-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                    Penerimaan Lainnya (Non-PO)
                </a>
            </div>

            {{-- STATS CARDS --}}
            <div class="tr-stats-grid-4">
                <div class="tr-stat-card border-indigo">
                    <div class="tr-stat-icon bg-indigo">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($totalCount ?? 0) }}</div>
                        <div class="tr-stat-label">Total Dokumen PO</div>
                    </div>
                </div>
                <div class="tr-stat-card border-orange">
                    <div class="tr-stat-icon bg-orange">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($pendingCount ?? 0) }}</div>
                        <div class="tr-stat-label">PO Menunggu</div>
                    </div>
                </div>
                <div class="tr-stat-card border-purple">
                    <div class="tr-stat-icon bg-purple">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($partialCount ?? 0) }}</div>
                        <div class="tr-stat-label">Diterima Sebagian</div>
                    </div>
                </div>
                <div class="tr-stat-card border-green">
                    <div class="tr-stat-icon bg-green">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($receivedCount ?? 0) }}</div>
                        <div class="tr-stat-label">Diterima Penuh</div>
                    </div>
                </div>
            </div>

            {{-- MAIN CARD --}}
            <div class="tr-card" style="margin-top: 1.5rem;">
                
                {{-- Filter Bar --}}
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('gudang.terimapo.index') }}" class="tr-filter-form">
                        <div class="tr-search">
                            <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. PO atau nama Supplier...">
                        </div>

                        <select name="status" class="tr-select">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="pending" {{ request('status', 'pending') === 'pending' ? 'selected' : '' }}>PO Menunggu / Sebagian</option>
                            <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Selesai / Diterima Penuh</option>
                        </select>

                        <button type="submit" class="tr-btn tr-btn-dark">Cari Data</button>
                        
                        @if(request('search') || (request('status') && request('status') != 'pending'))
                            <a href="{{ route('gudang.terimapo.index') }}" class="tr-btn tr-btn-danger-outline">Reset Filter</a>
                        @endif
                    </form>
                </div>

                {{-- Table & Content --}}
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="tr-table">
                            <thead>
                                <tr>
                                    <th>No. PO</th>
                                    <th>Supplier</th>
                                    <th>Tanggal Pesan</th>
                                    <th>Estimasi Tiba</th>
                                    <th>Status</th>
                                    <th class="r" style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    @php 
                                        $sl = $order->statusLabel; 
                                        $late = $order->expected_date && $order->expected_date->isPast();
                                        $isReceived = $order->status === 'received';
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="tr-po-badge">{{ $order->po_number }}</span>
                                        </td>
                                        <td>
                                            <div class="tr-supplier-name">{{ $order->supplier->name ?? 'Terhapus' }}</div>
                                        </td>
                                        <td>
                                            <div class="tr-date-text">{{ $order->order_date->format('d M Y') }}</div>
                                        </td>
                                        <td>
                                            @if($order->expected_date)
                                                <div class="tr-date-text {{ $late && !$isReceived ? 'tr-text-danger tr-font-bold' : '' }}">
                                                    @if($late && !$isReceived)
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:2px; margin-bottom:-2px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                                    @endif
                                                    {{ $order->expected_date->format('d M Y') }}
                                                </div>
                                            @else
                                                <span class="tr-text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="tr-dynamic-badge" style="background-color: {{ $sl['bg'] }}; color: {{ $sl['color'] }}; border: 1px solid currentColor;">
                                                {{ $sl['label'] }}
                                            </span>
                                        </td>
                                        <td class="r">
                                            @if(!$isReceived)
                                            <a href="{{ route('gudang.terimapo.show', $order) }}" class="tr-btn-sm tr-btn-primary">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                                Proses Terima
                                            </a>
                                            @else
                                            <span class="tr-text-muted" style="font-size:0.8rem; font-style:italic">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($orders->hasPages())
                        <div class="tr-pagination">
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                @else
                    <div class="tr-empty-state">
                        <div class="tr-empty-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <h6>Belum Ada Data PO</h6>
                        <p>Saat ini tidak ada dokumen Purchase Order yang sesuai dengan kriteria / mengantri untuk diproses masuk ke gudang.</p>
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
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            
            --tr-primary: #4f46e5; /* Indigo Accent */
            --tr-primary-hover: #4338ca;
            --tr-primary-light: #e0e7ff;
            
            --tr-danger: #ef4444;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #b91c1c;
            --tr-danger-border: #fecaca;
            
            --tr-success-bg: #ecfdf5;
            --tr-success-text: #059669;
            
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --tr-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1200px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-primary); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-indigo { background: var(--tr-primary-light); color: var(--tr-primary); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0; line-height: 1.4; }

        /* ── TABS ── */
        .tr-tabs { display: flex; gap: 2rem; border-bottom: 1px solid var(--tr-border); margin-bottom: 1.5rem; overflow-x: auto; white-space: nowrap; }
        .tr-tab-item { display: inline-flex; align-items: center; gap: 8px; padding-bottom: 0.75rem; color: var(--tr-text-muted); font-size: 0.85rem; font-weight: 600; text-decoration: none; border-bottom: 2px solid transparent; transition: all 0.2s; }
        .tr-tab-item:hover { color: var(--tr-text-main); }
        .tr-tab-item.active-primary { color: var(--tr-primary); border-bottom-color: var(--tr-primary); }

        /* Stats Grid 4 */
        .tr-stats-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
        @media (max-width: 992px) { .tr-stats-grid-4 { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 576px) { .tr-stats-grid-4 { grid-template-columns: 1fr; } }

        .tr-stat-card { background: var(--tr-surface); border-radius: 12px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--tr-border); }
        .tr-stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
        .tr-stat-value { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); line-height: 1; }
        .tr-stat-label { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 0.25rem; }

        /* Colors */
        .bg-green { background: #10b981; }
        .bg-indigo { background: #4f46e5; }
        .bg-purple { background: #8b5cf6; }
        .bg-orange { background: #f59e0b; }
        .border-green { border-left: 4px solid #10b981; }
        .border-indigo { border-left: 4px solid #4f46e5; }
        .border-purple { border-left: 4px solid #8b5cf6; }
        .border-orange { border-left: 4px solid #f59e0b; }

        /* ── CARD & FILTER ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-filter-bar { padding: 1rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-filter-form { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .tr-search { display: flex; align-items: center; gap: 8px; background: var(--tr-bg); border-radius: 6px; padding: 0.5rem 1rem; border: 1px solid var(--tr-border); flex: 1; min-width: 280px; transition: border-color 0.2s; }
        .tr-search:focus-within { border-color: var(--tr-primary); background: #ffffff; }
        .tr-search-icon { color: var(--tr-text-light); }
        .tr-search input { border: none; background: transparent; font-size: 0.85rem; font-family: inherit; color: var(--tr-text-main); outline: none; width: 100%; }
        .tr-search input::placeholder { color: var(--tr-text-light); }
        .tr-select { padding: 0.5rem 1rem; border-radius: 6px; border: 1px solid var(--tr-border); font-size: 0.85rem; background: white; font-family: inherit; color: var(--tr-text-main); }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 38px; }
        .tr-btn-dark { background: var(--tr-text-main); color: #ffffff; border-color: var(--tr-text-main); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tr-btn-dark:hover { background: #000000; transform: translateY(-1px); }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); background: #f8fafc; }
        .tr-btn-danger-outline { border-color: var(--tr-danger-border); color: var(--tr-danger-text); background: transparent; }
        .tr-btn-danger-outline:hover { background: var(--tr-danger-bg); }

        /* Small Actions */
        .tr-btn-sm { display: inline-flex; align-items: center; justify-content: center; gap: 4px; padding: 0.4rem 0.85rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; }
        .tr-btn-primary { background: var(--tr-primary); color: #ffffff; border-color: var(--tr-primary); box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2); }
        .tr-btn-primary:hover { background: var(--tr-primary-hover); border-color: var(--tr-primary-hover); transform: translateY(-1px); }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* ── CELL FORMATTING ── */
        .tr-po-badge { display: inline-block; padding: 0.25rem 0.6rem; border-radius: 6px; background: var(--tr-bg); border: 1px solid var(--tr-border); font-family: monospace; font-size: 0.85rem; font-weight: 700; color: var(--tr-text-main); }
        .tr-supplier-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-date-text { font-size: 0.85rem; color: var(--tr-text-muted); }
        
        .tr-text-danger { color: var(--tr-danger-text); }
        .tr-font-bold { font-weight: 700; }
        .tr-text-muted { color: var(--tr-text-light); }
        
        .tr-dynamic-badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.9; }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 5rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-bg); border: 1px solid var(--tr-border); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-text-muted); }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* ── PAGINATION ── */
        .tr-pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--tr-border-light); background: #ffffff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .tr-filter-form { flex-direction: column; align-items: stretch; }
            .tr-search, .tr-select { width: 100%; min-width: auto; }
            .tr-btn { width: 100%; justify-content: center; }
        }
    </style>
    @endpush
</x-app-layout>