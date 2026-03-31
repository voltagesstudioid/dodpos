<x-app-layout>
    <x-slot name="header">Dashboard Operasional</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Keuangan & Kas</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                        </div>
                        Dashboard Operasional
                    </h1>
                    <p class="tr-subtitle">Ringkasan lengkap pengeluaran operasional dan sesi kas.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('operasional.pengeluaran.create') }}" class="tr-btn tr-btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Catat Pengeluaran
                    </a>
                </div>
            </div>

            {{-- ALERT SESI AKTIF --}}
            @if($activeSession)
            <div class="tr-alert-banner tr-alert-success" style="margin-bottom: 1.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>
                    <strong>Sesi Aktif</strong> — Modal: Rp {{ number_format($activeSession->opening_amount, 0, ',', '.') }} 
                    | Terpakai: Rp {{ number_format($activeSession->expenses_sum_amount ?? 0, 0, ',', '.') }}
                    | Sisa: Rp {{ number_format($activeSession->opening_amount - ($activeSession->expenses_sum_amount ?? 0), 0, ',', '.') }}
                </span>
                <span class="tr-alert-meta">{{ $activeSession->created_at->diffForHumans() }}</span>
            </div>
            @else
            <div class="tr-alert-banner tr-alert-warning" style="margin-bottom: 1.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span><strong>Tidak Ada Sesi Aktif</strong> — Buka sesi operasional untuk mulai mencatat pengeluaran</span>
                <a href="{{ route('operasional.sesi.index') }}" class="tr-alert-link">Buka Sesi &rarr;</a>
            </div>
            @endif

            {{-- STATS GRID --}}
            <div class="tr-stats-grid-6">
                <div class="tr-stat-card border-indigo">
                    <div class="tr-stat-icon bg-indigo">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($totalSessions) }}</div>
                        <div class="tr-stat-label">Total Sesi</div>
                    </div>
                </div>
                <div class="tr-stat-card border-success">
                    <div class="tr-stat-icon bg-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($openSessions) }}</div>
                        <div class="tr-stat-label">Sesi Aktif</div>
                    </div>
                </div>
                <div class="tr-stat-card border-blue">
                    <div class="tr-stat-icon bg-blue">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">Rp {{ number_format($periodExpenses, 0, ',', '.') }}</div>
                        <div class="tr-stat-label">Pengeluaran Periode</div>
                    </div>
                </div>
                <div class="tr-stat-card border-orange">
                    <div class="tr-stat-icon bg-orange">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($periodCount) }}</div>
                        <div class="tr-stat-label">Transaksi Periode</div>
                    </div>
                </div>
                <div class="tr-stat-card border-purple">
                    <div class="tr-stat-icon bg-purple">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">Rp {{ number_format($totalModal, 0, ',', '.') }}</div>
                        <div class="tr-stat-label">Total Modal</div>
                    </div>
                </div>
                <div class="tr-stat-card border-danger">
                    <div class="tr-stat-icon bg-danger">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">Rp {{ number_format($totalTerpakai, 0, ',', '.') }}</div>
                        <div class="tr-stat-label">Total Terpakai</div>
                    </div>
                </div>
            </div>

            {{-- CHART & TOP CATEGORIES --}}
            <div class="tr-grid-2" style="margin-top: 1.5rem;">
                {{-- Chart --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <h3 class="tr-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            Trend Pengeluaran
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
                        <canvas id="expenseChart" height="250"></canvas>
                    </div>
                </div>

                {{-- Top Categories --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <h3 class="tr-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                            Top Kategori Pengeluaran
                        </h3>
                    </div>
                    <div class="tr-card-body" style="padding: 0;">
                        <table class="tr-table-compact">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th class="r">Jumlah</th>
                                    <th class="r">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalTop = $topCategories->sum('total_amount'); @endphp
                                @forelse($topCategories as $cat)
                                <tr>
                                    <td>
                                        <div class="tr-cat-name">{{ $cat->name }}</div>
                                    </td>
                                    <td class="r">Rp {{ number_format($cat->total_amount, 0, ',', '.') }}</td>
                                    <td class="r">
                                        <span class="tr-percent-badge">
                                            {{ $totalTop > 0 ? round(($cat->total_amount / $totalTop) * 100, 1) : 0 }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="tr-text-center tr-text-muted">Belum ada data pengeluaran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- RECENT EXPENSES & SESSION STATS --}}
            <div class="tr-grid-2" style="margin-top: 1.5rem;">
                {{-- Recent Expenses --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <h3 class="tr-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Pengeluaran Terbaru
                        </h3>
                        <a href="{{ route('operasional.riwayat.index') }}" class="tr-link-sm">Lihat Semua</a>
                    </div>
                    <div class="tr-card-body" style="padding: 0;">
                        <div class="tr-activity-list">
                            @forelse($recentExpenses as $expense)
                            <div class="tr-activity-item">
                                <div class="tr-activity-icon bg-danger">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                </div>
                                <div class="tr-activity-content">
                                    <div class="tr-activity-title">
                                        {{ $expense->category?->name ?? 'Tidak ada kategori' }}
                                        <span class="tr-activity-amount">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="tr-activity-meta">
                                        {{ $expense->date->format('d M Y') }} • {{ $expense->user?->name ?? '-' }}
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="tr-empty-state-sm">
                                <p>Belum ada pengeluaran</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Session Stats --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <h3 class="tr-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Status Sesi
                        </h3>
                        <a href="{{ route('operasional.sesi.index') }}" class="tr-link-sm">Kelola Sesi</a>
                    </div>
                    <div class="tr-card-body">
                        <div class="tr-opname-stats">
                            <div class="tr-opname-stat">
                                <div class="tr-opname-icon bg-success">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                </div>
                                <div class="tr-opname-info">
                                    <div class="tr-opname-value">{{ $sessionStats['open'] }}</div>
                                    <div class="tr-opname-label">Sesi Aktif</div>
                                </div>
                            </div>
                            <div class="tr-opname-stat">
                                <div class="tr-opname-icon bg-gray">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                </div>
                                <div class="tr-opname-info">
                                    <div class="tr-opname-value">{{ $sessionStats['closed'] }}</div>
                                    <div class="tr-opname-label">Sesi Ditutup</div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0;">
                            <div class="tr-progress-label">
                                <span>Efisiensi Penggunaan Modal</span>
                                <span>{{ $totalModal > 0 ? round(($totalTerpakai / $totalModal) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="tr-progress-bar">
                                <div class="tr-progress-fill" style="width: {{ $totalModal > 0 ? min(100, ($totalTerpakai / $totalModal) * 100) : 0 }}%"></div>
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
            --tr-primary: #6366f1;
            --tr-success: #10b981;
            --tr-warning: #f59e0b;
            --tr-danger: #ef4444;
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
        .tr-alert-banner { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: 10px; text-decoration: none; }
        .tr-alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .tr-alert-warning { background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; }
        .tr-alert-meta { margin-left: auto; font-size: 0.75rem; color: #059669; }
        .tr-alert-link { margin-left: auto; font-weight: 600; text-decoration: underline; }

        /* Stats Grid 6 */
        .tr-stats-grid-6 { display: grid; grid-template-columns: repeat(6, 1fr); gap: 1rem; }
        @media (max-width: 1200px) { .tr-stats-grid-6 { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { .tr-stats-grid-6 { grid-template-columns: repeat(2, 1fr); } }

        .tr-stat-card { background: var(--tr-surface); border-radius: 12px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--tr-border); }
        .tr-stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
        .tr-stat-value { font-size: 1.25rem; font-weight: 800; color: var(--tr-text-main); line-height: 1; }
        .tr-stat-label { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 0.25rem; }

        /* Colors */
        .bg-indigo { background: #6366f1; }
        .bg-success { background: #10b981; }
        .bg-blue { background: #3b82f6; }
        .bg-orange { background: #f59e0b; }
        .bg-purple { background: #8b5cf6; }
        .bg-danger { background: #ef4444; }
        .bg-gray { background: #6b7280; }
        .border-indigo { border-left: 4px solid #6366f1; }
        .border-success { border-left: 4px solid #10b981; }
        .border-blue { border-left: 4px solid #3b82f6; }
        .border-orange { border-left: 4px solid #f59e0b; }
        .border-purple { border-left: 4px solid #8b5cf6; }
        .border-danger { border-left: 4px solid #ef4444; }

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
        .tr-cat-name { font-weight: 600; color: var(--tr-text-main); }
        .tr-percent-badge { background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; font-weight: 700; }

        /* Activity List */
        .tr-activity-list { max-height: 320px; overflow-y: auto; }
        .tr-activity-item { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.875rem 1.25rem; border-bottom: 1px solid var(--tr-border); }
        .tr-activity-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
        .tr-activity-content { flex: 1; min-width: 0; }
        .tr-activity-title { font-weight: 600; font-size: 0.9rem; color: var(--tr-text-main); display: flex; justify-content: space-between; }
        .tr-activity-amount { font-weight: 800; color: #ef4444; }
        .tr-activity-meta { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 0.25rem; }

        /* Opname Stats */
        .tr-opname-stats { display: flex; gap: 1rem; }
        .tr-opname-stat { display: flex; align-items: center; gap: 0.75rem; flex: 1; padding: 1rem; background: #f8fafc; border-radius: 10px; }
        .tr-opname-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; }
        .tr-opname-value { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); }
        .tr-opname-label { font-size: 0.75rem; color: var(--tr-text-muted); }

        /* Progress */
        .tr-progress-label { display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 600; color: var(--tr-text-main); margin-bottom: 0.5rem; }
        .tr-progress-bar { height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; }
        .tr-progress-fill { height: 100%; background: linear-gradient(90deg, #6366f1, #8b5cf6); border-radius: 4px; transition: width 0.5s ease; }

        .tr-empty-state-sm { padding: 2rem; text-align: center; color: var(--tr-text-muted); }
        .tr-text-center { text-align: center; }
        .tr-text-muted { color: var(--tr-text-muted); }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('expenseChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Pengeluaran (Rp)',
                    data: @json($chartData),
                    backgroundColor: '#6366f1',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
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
