<x-app-layout>
    <x-slot name="header">Dashboard Gudang</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Gudang</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-blue">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        </div>
                        Dashboard Gudang
                    </h1>
                    <p class="tr-subtitle">Ringkasan lengkap aktivitas dan status stok gudang.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.stok') }}" class="tr-btn tr-btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                        Lihat Stok
                    </a>
                </div>
            </div>

            {{-- ALERTS --}}
            @if($lowStockCount > 0 || $expiredCount > 0)
            <div class="tr-alerts-container" style="margin-bottom: 1.5rem;">
                @if($lowStockCount > 0)
                <a href="{{ route('gudang.minstok') }}" class="tr-alert-banner tr-alert-warning">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    <span><strong>{{ $lowStockCount }}</strong> produk stok hampir habis (dibawah minimum)</span>
                    <span class="tr-alert-link">Cek Sekarang &rarr;</span>
                </a>
                @endif
                @if($expiredCount > 0)
                <a href="{{ route('gudang.expired') }}" class="tr-alert-banner tr-alert-danger" style="margin-top: 0.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <span><strong>{{ $expiredCount }}</strong> batch barang akan expired dalam 30 hari</span>
                    <span class="tr-alert-link">Cek Sekarang &rarr;</span>
                </a>
                @endif
            </div>
            @endif

            {{-- STATS GRID --}}
            <div class="tr-stats-grid-6">
                <div class="tr-stat-card border-blue">
                    <div class="tr-stat-icon bg-blue">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($totalProducts) }}</div>
                        <div class="tr-stat-label">Total Produk</div>
                    </div>
                </div>
                <div class="tr-stat-card border-green">
                    <div class="tr-stat-icon bg-green">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</div>
                        <div class="tr-stat-label">Nilai Stok</div>
                    </div>
                </div>
                <div class="tr-stat-card border-orange">
                    <div class="tr-stat-icon bg-orange">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($inboundCount) }}</div>
                        <div class="tr-stat-label">Barang Masuk</div>
                    </div>
                </div>
                <div class="tr-stat-card border-red">
                    <div class="tr-stat-icon bg-red">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($outboundCount) }}</div>
                        <div class="tr-stat-label">Barang Keluar</div>
                    </div>
                </div>
                <div class="tr-stat-card border-purple">
                    <div class="tr-stat-icon bg-purple">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5"></path><path d="M4 20L21 3"></path><path d="M21 16v5h-5"></path><path d="M15 15l6 6"></path><path d="M4 4l5 5"></path></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($transferCount) }}</div>
                        <div class="tr-stat-label">Transfer</div>
                    </div>
                </div>
                <div class="tr-stat-card border-indigo">
                    <div class="tr-stat-icon bg-indigo">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ $opnameStats['submitted'] }}</div>
                        <div class="tr-stat-label">Opname Pending</div>
                    </div>
                </div>
            </div>

            {{-- CHART & WAREHOUSES --}}
            <div class="tr-grid-2" style="margin-top: 1.5rem;">
                {{-- Chart --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <h3 class="tr-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                            Trend Masuk vs Keluar
                        </h3>
                        <form method="GET" class="tr-period-selector">
                            <select name="period" onchange="this.form.submit()" class="tr-select-sm">
                                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>30 Hari</option>
                                <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>3 Bulan</option>
                                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>1 Tahun</option>
                            </select>
                        </form>
                    </div>
                    <div class="tr-card-body">
                        <canvas id="stockChart" height="250"></canvas>
                    </div>
                </div>

                {{-- Top Warehouses --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <h3 class="tr-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                            Top Gudang (Stok Tertinggi)
                        </h3>
                    </div>
                    <div class="tr-card-body" style="padding: 0;">
                        <table class="tr-table-compact">
                            <thead>
                                <tr>
                                    <th>Gudang</th>
                                    <th class="r">Item</th>
                                    <th class="r">Total Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topWarehouses as $wh)
                                <tr>
                                    <td>
                                        <div class="tr-wh-name">{{ $wh->name }}</div>
                                    </td>
                                    <td class="r">{{ number_format($wh->total_items) }}</td>
                                    <td class="r">{{ number_format($wh->stock_value) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="tr-text-center tr-text-muted">Tidak ada data gudang</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- RECENT ACTIVITIES & OPNAME --}}
            <div class="tr-grid-2" style="margin-top: 1.5rem;">
                {{-- Recent Activities --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <h3 class="tr-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            Aktivitas Terbaru
                        </h3>
                        <a href="{{ route('gudang.inout') }}" class="tr-link-sm">Lihat Semua</a>
                    </div>
                    <div class="tr-card-body" style="padding: 0;">
                        <div class="tr-activity-list">
                            @forelse($recentMovements as $movement)
                            <div class="tr-activity-item">
                                <div class="tr-activity-icon {{ $movement->type == 'in' ? 'bg-green' : 'bg-red' }}">
                                    @if($movement->type == 'in')
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    @else
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                    @endif
                                </div>
                                <div class="tr-activity-content">
                                    <div class="tr-activity-title">
                                        {{ $movement->product?->name ?? 'Produk Dihapus' }}
                                        <span class="tr-activity-qty {{ $movement->type == 'in' ? 'text-green' : 'text-red' }}">
                                            {{ $movement->type == 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                        </span>
                                    </div>
                                    <div class="tr-activity-meta">
                                        {{ $movement->warehouse?->name ?? '-' }} • {{ $movement->user?->name ?? 'Sistem' }} • {{ $movement->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="tr-empty-state-sm">
                                <p>Belum ada aktivitas</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Opname Summary --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <h3 class="tr-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                            Status Opname Stok
                        </h3>
                        <a href="{{ route('gudang.opname_sessions.index') }}" class="tr-link-sm">Kelola Opname</a>
                    </div>
                    <div class="tr-card-body">
                        <div class="tr-opname-stats">
                            <div class="tr-opname-stat">
                                <div class="tr-opname-icon bg-gray">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </div>
                                <div class="tr-opname-info">
                                    <div class="tr-opname-value">{{ $opnameStats['draft'] }}</div>
                                    <div class="tr-opname-label">Draft</div>
                                </div>
                            </div>
                            <div class="tr-opname-stat">
                                <div class="tr-opname-icon bg-info">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                </div>
                                <div class="tr-opname-info">
                                    <div class="tr-opname-value">{{ $opnameStats['submitted'] }}</div>
                                    <div class="tr-opname-label">Menunggu Approval</div>
                                </div>
                            </div>
                            <div class="tr-opname-stat">
                                <div class="tr-opname-icon bg-success">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </div>
                                <div class="tr-opname-info">
                                    <div class="tr-opname-value">{{ $opnameStats['approved'] }}</div>
                                    <div class="tr-opname-label">Approved</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-primary: #3b82f6;
            --tr-success: #10b981;
            --tr-warning: #f59e0b;
            --tr-danger: #ef4444;
            --tr-info: #0ea5e9;
            --tr-purple: #8b5cf6;
            --tr-indigo: #6366f1;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1400px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Header */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-primary); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 10px; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 8px; border-radius: 10px; }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0; }
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* Alerts */
        .tr-alert-banner { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: 10px; text-decoration: none; transition: all 0.2s; }
        .tr-alert-warning { background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; }
        .tr-alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .tr-alert-link { margin-left: auto; font-weight: 600; text-decoration: underline; }
        .tr-alert-banner:hover { transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }

        /* Stats Grid 6 */
        .tr-stats-grid-6 { display: grid; grid-template-columns: repeat(6, 1fr); gap: 1rem; }
        @media (max-width: 1200px) { .tr-stats-grid-6 { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { .tr-stats-grid-6 { grid-template-columns: repeat(2, 1fr); } }

        .tr-stat-card { background: var(--tr-surface); border-radius: 12px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--tr-border); }
        .tr-stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
        .tr-stat-value { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); line-height: 1; }
        .tr-stat-label { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 0.25rem; }

        /* Colors */
        .bg-blue { background: #3b82f6; }
        .bg-green { background: #10b981; }
        .bg-orange { background: #f59e0b; }
        .bg-red { background: #ef4444; }
        .bg-purple { background: #8b5cf6; }
        .bg-indigo { background: #6366f1; }
        .bg-gray { background: #6b7280; }
        .bg-info { background: #0ea5e9; }
        .bg-success { background: #10b981; }
        .border-blue { border-left: 4px solid #3b82f6; }
        .border-green { border-left: 4px solid #10b981; }
        .border-orange { border-left: 4px solid #f59e0b; }
        .border-red { border-left: 4px solid #ef4444; }
        .border-purple { border-left: 4px solid #8b5cf6; }
        .border-indigo { border-left: 4px solid #6366f1; }
        .text-green { color: #10b981; }
        .text-red { color: #ef4444; }

        /* Grid & Cards */
        .tr-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        @media (max-width: 992px) { .tr-grid-2 { grid-template-columns: 1fr; } }

        .tr-card { background: var(--tr-surface); border-radius: 12px; border: 1px solid var(--tr-border); overflow: hidden; }
        .tr-card-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; border-bottom: 1px solid var(--tr-border); }
        .tr-card-title { font-size: 0.95rem; font-weight: 700; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 0.5rem; }
        .tr-card-body { padding: 1.25rem; }

        /* Buttons */
        .tr-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
        .tr-btn-primary { background: var(--tr-primary); color: white; }
        .tr-select-sm { padding: 0.4rem 0.75rem; border-radius: 6px; border: 1px solid var(--tr-border); font-size: 0.8rem; }
        .tr-link-sm { font-size: 0.8rem; color: var(--tr-primary); text-decoration: none; font-weight: 600; }

        /* Table */
        .tr-table-compact { width: 100%; border-collapse: collapse; }
        .tr-table-compact th, .tr-table-compact td { padding: 0.75rem 1rem; text-align: left; font-size: 0.85rem; border-bottom: 1px solid var(--tr-border); }
        .tr-table-compact th { font-weight: 600; color: var(--tr-text-muted); background: #f8fafc; }
        .tr-table-compact .r { text-align: right; }
        .tr-wh-name { font-weight: 600; color: var(--tr-text-main); }

        /* Activity List */
        .tr-activity-list { max-height: 320px; overflow-y: auto; }
        .tr-activity-item { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.875rem 1.25rem; border-bottom: 1px solid var(--tr-border); }
        .tr-activity-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
        .tr-activity-content { flex: 1; min-width: 0; }
        .tr-activity-title { font-weight: 600; font-size: 0.9rem; color: var(--tr-text-main); display: flex; justify-content: space-between; }
        .tr-activity-qty { font-weight: 800; }
        .tr-activity-meta { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 0.25rem; }

        /* Opname Stats */
        .tr-opname-stats { display: flex; gap: 1rem; }
        .tr-opname-stat { display: flex; align-items: center; gap: 0.75rem; flex: 1; padding: 1rem; background: #f8fafc; border-radius: 10px; }
        .tr-opname-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; }
        .tr-opname-value { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); }
        .tr-opname-label { font-size: 0.75rem; color: var(--tr-text-muted); }

        .tr-empty-state-sm { padding: 2rem; text-align: center; color: var(--tr-text-muted); }
        .tr-text-center { text-align: center; }
        .tr-text-muted { color: var(--tr-text-muted); }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('stockChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: @json($chartData['inbound']),
                        backgroundColor: '#10b981',
                        borderRadius: 4,
                    },
                    {
                        label: 'Barang Keluar',
                        data: @json($chartData['outbound']),
                        backgroundColor: '#ef4444',
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 20 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
