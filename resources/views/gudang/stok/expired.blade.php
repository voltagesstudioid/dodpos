<x-app-layout>
    <x-slot name="header">Barang Expired & Segera Expired</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Peringatan Sistem</div>
                    <h1 class="tr-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="tr-title-icon-danger"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Barang Kedaluwarsa
                    </h1>
                    <p class="tr-subtitle">Daftar barang yang sudah expired atau akan expired dalam <strong>{{ $daysThreshold }} hari</strong> ke depan.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.stok') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        Lihat Semua Stok
                    </a>
                </div>
            </div>

            {{-- MAIN CARD (TABLE) --}}
            <div class="tr-card">
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Barang (SKU)</th>
                                <th>Gudang & Lokasi</th>
                                <th class="r">Sisa Stok</th>
                                <th>Tanggal Expired</th>
                                <th>Status Peringatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expiredStocks as $stock)
                                @php
                                    $expDate = \Carbon\Carbon::parse($stock->expired_date);
                                    $isExpired = $expDate->isPast() && !$expDate->isToday();
                                    $daysLeft = round(now()->diffInDays($expDate, false));
                                @endphp
                                <tr>
                                    <td>
                                        <div class="tr-prod-name">{{ $stock->product->name }}</div>
                                        <div class="tr-prod-batch">Batch: <span class="tr-font-mono">{{ $stock->batch_number ?? '-' }}</span></div>
                                    </td>
                                    <td>
                                        <span class="tr-wh-pill">{{ $stock->warehouse->name }}</span>
                                        @if($stock->location)
                                            <div class="tr-loc-text">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                                Rak: {{ $stock->location->name }}
                                            </div>
                                        @else
                                            <div class="tr-loc-text text-muted">Area Umum</div>
                                        @endif
                                    </td>
                                    <td class="r">
                                        <div class="tr-stock-qty">{{ $stock->stock }}</div>
                                        <div class="tr-stock-unit">{{ $stock->product->unit->abbreviation ?? '' }}</div>
                                    </td>
                                    <td>
                                        <div class="tr-date-text">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                            {{ $expDate->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($isExpired)
                                            <span class="tr-badge-alert tr-alert-danger">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                                Sudah Expired ({{ abs($daysLeft) }} hari lalu)
                                            </span>
                                        @else
                                            <span class="tr-badge-alert tr-alert-warning">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                                Segera Expired ({{ $daysLeft }} hari lagi)
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon tr-icon-success">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                            </div>
                                            <h6>Stok Barang Aman!</h6>
                                            <p>Tidak ada stok yang kadaluwarsa atau mendekati masa kadaluwarsa dalam <strong>{{ $daysThreshold }} hari</strong> ke depan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($expiredStocks->hasPages())
                    <div class="tr-pagination">
                        {{ $expiredStocks->links() }}
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
            --tr-primary: #3b82f6;
            --tr-primary-light: #eff6ff;
            --tr-indigo: #6366f1;
            --tr-indigo-light: #e0e7ff;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            --tr-warning-bg: #fffbeb;
            --tr-warning-border: #fde68a;
            --tr-warning-text: #b45309;
            --tr-danger-bg: #fef2f2;
            --tr-danger-border: #fecaca;
            --tr-danger-text: #b91c1c;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; }
        .tr-page {
            padding: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--tr-text-main);
        }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-danger-text); margin-bottom: 0.25rem; }
        .tr-title { font-size: 1.4rem; font-weight: 800; color: var(--tr-text-main); letter-spacing: -0.02em; margin: 0; display: flex; align-items: center; gap: 8px; }
        .tr-title-icon-danger { color: var(--tr-danger-text); background: var(--tr-danger-bg); padding: 4px; border-radius: 6px; }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0.25rem 0 0; font-weight: 500; }
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* ── CARD ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }

        /* ── BUTTONS ── */
        .tr-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.8rem; font-family: inherit; font-weight: 600;
            cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s;
        }
        .tr-btn-outline { background: transparent; border: 1px solid var(--tr-border); color: var(--tr-text-muted); }
        .tr-btn-outline:hover { border-color: var(--tr-text-main); color: var(--tr-text-main); background: #f8fafc; }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 750px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* ── CELLS FORMATTING ── */
        .tr-prod-name { font-weight: 700; font-size: 0.9rem; color: var(--tr-text-main); line-height: 1.3; }
        .tr-prod-batch { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 3px; }
        .tr-font-mono { font-family: monospace; font-weight: 600; color: var(--tr-text-main); background: var(--tr-border-light); padding: 1px 4px; border-radius: 4px; }
        
        .tr-wh-pill { background: var(--tr-indigo-light); color: var(--tr-indigo); font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 99px; display: inline-block; }
        .tr-loc-text { font-size: 0.7rem; color: var(--tr-text-muted); margin-top: 5px; display: flex; align-items: center; gap: 4px; font-weight: 500; }
        .tr-loc-text.text-muted { color: var(--tr-text-light); }
        
        .tr-stock-qty { font-weight: 800; font-size: 1.1rem; color: var(--tr-text-main); }
        .tr-stock-unit { font-size: 0.7rem; color: var(--tr-text-muted); font-weight: 500; margin-top: 1px; }

        .tr-date-text { font-weight: 600; color: var(--tr-text-main); display: flex; align-items: center; gap: 6px; }
        .tr-date-text svg { color: var(--tr-text-light); }

        /* ── CUSTOM ALERT BADGES ── */
        .tr-badge-alert {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.35rem 0.75rem;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
            border: 1px solid transparent;
        }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: var(--tr-danger-border); }
        .tr-alert-warning { background: var(--tr-warning-bg); color: var(--tr-warning-text); border-color: var(--tr-warning-border); }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; }
        .tr-icon-success { background: #ecfdf5; color: #10b981; }
        .tr-empty-state h6 { font-size: 1.1rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* ── PAGINATION ── */
        .tr-pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--tr-border-light); background: #ffffff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-header-actions { width: 100%; }
            .tr-btn { width: 100%; justify-content: center; }
        }
    </style>
    @endpush
</x-app-layout>