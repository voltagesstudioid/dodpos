<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <style>
        /* ===== DASHBOARD PREMIUM STYLES ===== */
        :root {
            --tr-bg-gradient: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
            --tr-card-bg: rgba(255, 255, 255, 0.85);
            --tr-card-border: rgba(255, 255, 255, 0.6);
            --tr-card-shadow: 0 8px 32px rgba(30, 41, 59, 0.05);
            --tr-glass-blur: blur(12px);
            --tr-primary: #4f46e5;
            --tr-primary-light: #e0e7ff;
            --tr-success: #059669;
            --tr-success-light: #d1fae5;
            --tr-warning: #d97706;
            --tr-warning-light: #fef3c7;
            --tr-danger: #e11d48;
            --tr-danger-light: #ffe4e6;
            --tr-text-main: #1e293b;
            --tr-text-muted: #64748b;
        }

        .dash-container {
            padding: 1.5rem 0;
            font-family: 'Inter', system-ui, sans-serif;
            position: relative;
            z-index: 1;
        }

        /* Abstract Background Decoration */
        .dash-container::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            z-index: -1;
            pointer-events: none;
        }

        /* Greeting Section */
        .tr-greeting {
            margin-bottom: 2rem;
            animation: tr-fade-in-up 0.6s ease-out;
        }
        .tr-greeting h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--tr-text-main);
            letter-spacing: -0.03em;
            margin-bottom: 0.25rem;
        }
        .tr-greeting h1 span.highlight {
            background: linear-gradient(90deg, #4f46e5, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .tr-greeting p {
            font-size: 0.95rem;
            color: var(--tr-text-muted);
            font-weight: 500;
        }

        /* Glass Cards */
        .tr-glass-card {
            background: var(--tr-card-bg);
            backdrop-filter: var(--tr-glass-blur);
            -webkit-backdrop-filter: var(--tr-glass-blur);
            border: 1px solid var(--tr-card-border);
            border-radius: 20px;
            box-shadow: var(--tr-card-shadow);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        .tr-glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(30, 41, 59, 0.08);
            border-color: rgba(99, 102, 241, 0.3);
        }

        /* Stats Grid */
        .tr-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .tr-stat-item {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }
        .tr-stat-item::after {
            content: '';
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.4;
            z-index: 0;
            pointer-events: none;
        }
        .tr-stat-item.indigo::after { background: radial-gradient(circle, var(--tr-primary-light) 0%, transparent 70%); }
        .tr-stat-item.emerald::after { background: radial-gradient(circle, var(--tr-success-light) 0%, transparent 70%); }
        .tr-stat-item.amber::after { background: radial-gradient(circle, var(--tr-warning-light) 0%, transparent 70%); }
        .tr-stat-item.rose::after { background: radial-gradient(circle, var(--tr-danger-light) 0%, transparent 70%); }

        .tr-stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1;
        }
        .tr-stat-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .tr-stat-icon.indigo { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; }
        .tr-stat-icon.emerald { background: linear-gradient(135deg, #10b981, #059669); color: white; }
        .tr-stat-icon.amber { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
        .tr-stat-icon.rose { background: linear-gradient(135deg, #f43f5e, #e11d48); color: white; }

        .tr-stat-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.6rem;
            border-radius: 999px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .tr-stat-badge.indigo { background: var(--tr-primary-light); color: var(--tr-primary); }
        .tr-stat-badge.emerald { background: var(--tr-success-light); color: var(--tr-success); }
        .tr-stat-badge.amber { background: var(--tr-warning-light); color: var(--tr-warning); }
        .tr-stat-badge.rose { background: var(--tr-danger-light); color: var(--tr-danger); }

        .tr-stat-body { z-index: 1; }
        .tr-stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--tr-text-main);
            letter-spacing: -0.04em;
            line-height: 1.2;
            margin-bottom: 0.2rem;
        }
        .tr-stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--tr-text-muted);
        }

        /* Alerts List */
        .tr-alerts {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
            animation: tr-fade-in-up 0.7s ease-out;
        }
        .tr-alert-item {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.25rem 1.5rem;
            border-radius: 16px;
            background: rgba(255,255,255,0.9);
            border-left: 6px solid transparent;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            text-decoration: none;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        .tr-alert-item:hover {
            transform: translateX(8px);
            background: #ffffff;
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        }
        .tr-alert-item.danger { border-left-color: var(--tr-danger); }
        .tr-alert-item.warning { border-left-color: var(--tr-warning); }
        .tr-alert-item.info { border-left-color: var(--tr-primary); }
        
        .tr-alert-icon { font-size: 1.75rem; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1)); }
        .tr-alert-content { flex: 1; }
        .tr-alert-title { font-size: 1rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.2rem; }
        .tr-alert-msg { font-size: 0.85rem; color: var(--tr-text-muted); font-weight: 500; }
        .tr-alert-action {
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .tr-alert-item.danger .tr-alert-action { color: var(--tr-danger); background: var(--tr-danger-light); }
        .tr-alert-item.danger:hover .tr-alert-action { background: #fda4af; color: #9f1239; }
        .tr-alert-item.warning .tr-alert-action { color: var(--tr-warning); background: var(--tr-warning-light); }
        .tr-alert-item.warning:hover .tr-alert-action { background: #fde68a; color: #92400e; }
        .tr-alert-item.info .tr-alert-action { color: var(--tr-primary); background: var(--tr-primary-light); }
        .tr-alert-item.info:hover .tr-alert-action { background: #c7d2fe; color: #3730a3; }

        /* Content Sections */
        .tr-content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 1024px) { .tr-content-grid { grid-template-columns: 1fr; } }

        .tr-panel {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .tr-panel-header {
            padding: 1.5rem 1.5rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .tr-panel-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--tr-text-main);
        }
        .tr-panel-subtitle {
            font-size: 0.8rem;
            color: var(--tr-text-muted);
            font-weight: 500;
            margin-top: 0.2rem;
        }
        .tr-panel-body { padding: 0 1.5rem 1.5rem; flex: 1; display: flex; flex-direction: column; }

        /* Chart */
        .tr-chart-container {
            margin-top: 1rem;
            flex: 1;
            display: flex;
            align-items: flex-end;
            gap: 8px;
            height: 180px;
            padding: 1rem 0 0;
        }
        .tr-chart-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            height: 100%;
            justify-content: flex-end;
            group: hover;
        }
        .tr-chart-bar {
            width: 100%;
            background: linear-gradient(to top, var(--tr-primary-light), #a5b4fc);
            border-radius: 6px 6px 4px 4px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            min-height: 4px;
            cursor: pointer;
        }
        .tr-chart-bar::before {
            content: attr(data-tooltip);
            position: absolute;
            top: -35px;
            left: 50%;
            transform: translateX(-50%) scale(0.8);
            background: #1e293b;
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            opacity: 0;
            pointer-events: none;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .tr-chart-bar:hover {
            background: linear-gradient(to top, #6366f1, #4f46e5);
            box-shadow: 0 0 15px rgba(79, 70, 229, 0.4);
        }
        .tr-chart-bar:hover::before {
            opacity: 1;
            transform: translateX(-50%) scale(1);
        }
        .tr-chart-bar.active { background: linear-gradient(to top, #4f46e5, #312e81); }
        .tr-chart-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--tr-text-muted);
            text-transform: uppercase;
        }

        /* Quick Menu */
        .tr-quick-menu {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }
        .tr-quick-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            background: rgba(255,255,255,0.5);
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 14px;
            text-decoration: none;
            color: var(--tr-text-main);
            font-weight: 600;
            transition: all 0.2s;
        }
        .tr-quick-btn:hover {
            background: #ffffff;
            border-color: rgba(99,102,241,0.2);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transform: translateX(4px);
        }
        .tr-quick-icon {
            font-size: 1.25rem;
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 10px;
            background: var(--tr-primary-light);
            color: var(--tr-primary);
        }

        /* Animations */
        @keyframes tr-fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-stagger > * { opacity: 0; animation: tr-fade-in-up 0.5s ease-out forwards; }
        .animate-stagger > *:nth-child(1) { animation-delay: 0.1s; }
        .animate-stagger > *:nth-child(2) { animation-delay: 0.2s; }
        .animate-stagger > *:nth-child(3) { animation-delay: 0.3s; }
        .animate-stagger > *:nth-child(4) { animation-delay: 0.4s; }
    </style>

    <div class="dash-container">
        <!-- GREETING -->
        <div class="tr-greeting">
            <h1>Selamat Datang, <span class="highlight">{{ Auth::user()->name }}</span> 👋</h1>
            <p>{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} &mdash; Pantau aktivitas bisnis Anda dalam satu pandangan.</p>
        </div>

        <!-- ALERTS -->
        @if(!empty($alerts))
            <div class="tr-alerts animate-stagger">
                @foreach($alerts as $alert)
                    <a href="{{ $alert['link'] }}" class="tr-alert-item {{ $alert['type'] }}">
                        <div class="tr-alert-icon">{{ $alert['icon'] }}</div>
                        <div class="tr-alert-content">
                            <div class="tr-alert-title">{{ $alert['title'] }}</div>
                            <div class="tr-alert-msg">{{ $alert['message'] }}</div>
                        </div>
                        <div class="tr-alert-action">Tindak Lanjut &rarr;</div>
                    </a>
                @endforeach
            </div>
        @endif

        <!-- STATS GRID -->
        <div class="tr-stats-grid animate-stagger">
            <!-- Total Produk -->
            <div class="tr-glass-card tr-stat-item indigo">
                <div class="tr-stat-header">
                    <div class="tr-stat-icon indigo">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <span class="tr-stat-badge indigo">Terdaftar</span>
                </div>
                <div class="tr-stat-body">
                    <div class="tr-stat-value">{{ number_format($stats['total_products'], 0, ',', '.') }}</div>
                    <div class="tr-stat-label">Total SKU Produk</div>
                </div>
            </div>

            <!-- Transaksi Hari Ini -->
            <div class="tr-glass-card tr-stat-item emerald">
                <div class="tr-stat-header">
                    <div class="tr-stat-icon emerald">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="tr-stat-badge emerald">Hari Ini</span>
                </div>
                <div class="tr-stat-body">
                    <div class="tr-stat-value">{{ number_format($stats['total_transactions_today'], 0, ',', '.') }}</div>
                    <div class="tr-stat-label">Transaksi Selesai</div>
                </div>
            </div>

            <!-- Omzet Hari Ini -->
            <div class="tr-glass-card tr-stat-item amber">
                <div class="tr-stat-header">
                    <div class="tr-stat-icon amber">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="tr-stat-badge amber">Omzet Kotor</span>
                </div>
                <div class="tr-stat-body">
                    <div class="tr-stat-value"><span style="font-size:1.2rem;color:#94a3b8;font-weight:600;">Rp</span> {{ number_format($stats['revenue_today'], 0, ',', '.') }}</div>
                    <div class="tr-stat-label">Pendapatan Hari Ini</div>
                </div>
            </div>

            <!-- Pelanggan Aktif -->
            <div class="tr-glass-card tr-stat-item rose">
                <div class="tr-stat-header">
                    <div class="tr-stat-icon rose">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="tr-stat-badge rose">Total Aktif</span>
                </div>
                <div class="tr-stat-body">
                    <div class="tr-stat-value">{{ number_format($stats['active_customers'], 0, ',', '.') }}</div>
                    <div class="tr-stat-label">Pelanggan Terdaftar</div>
                </div>
            </div>
        </div>

        <!-- CONTENT GRID -->
        <div class="tr-content-grid animate-stagger">
            <!-- Left: Chart -->
            <div class="tr-glass-card tr-panel">
                <div class="tr-panel-header">
                    <div>
                        <div class="tr-panel-title">Omzet Penjualan</div>
                        <div class="tr-panel-subtitle">Performa 7 hari terakhir</div>
                    </div>
                </div>
                <div class="tr-panel-body">
                    <div class="tr-chart-container">
                        @foreach($weeklySales as $sale)
                        <div class="tr-chart-col">
                            <div class="tr-chart-bar {{ $sale['date'] === now()->toDateString() ? 'active' : '' }}" 
                                 style="height: {{ max(6, (int) $sale['percentage']) }}%;"
                                 data-tooltip="Rp {{ number_format($sale['amount'], 0, ',', '.') }}"></div>
                            <span class="tr-chart-label">{{ $sale['label'] }}</span>
                        </div>
                        @endforeach
                    </div>
                    @if(collect($weeklySales)->sum('amount') == 0)
                        <div style="text-align:center; padding:1.5rem 0; color:#94a3b8; font-size:0.85rem; font-weight:500;">
                            Belum ada data transaksi yang tercatat dalam seminggu terakhir.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Quick Actions -->
            <div class="tr-glass-card tr-panel">
                <div class="tr-panel-header">
                    <div>
                        <div class="tr-panel-title">Akses Cepat</div>
                        <div class="tr-panel-subtitle">Pintasan menu penting</div>
                    </div>
                </div>
                <div class="tr-panel-body">
                    <div class="tr-quick-menu">
                        @can('view_pos_kasir')
                            <a href="{{ route('kasir.index') }}" class="tr-quick-btn">
                                <div class="tr-quick-icon">💻</div>
                                <div>Buka POS Kasir</div>
                            </a>
                        @endcan
                        <a href="{{ route('gudang.terimapo.index') }}" class="tr-quick-btn">
                            <div class="tr-quick-icon" style="background:#d1fae5; color:#059669;">📥</div>
                            <div>Terima PO Supplier</div>
                        </a>
                        <a href="{{ route('operasional.pengeluaran.create') }}" class="tr-quick-btn">
                            <div class="tr-quick-icon" style="background:#ffe4e6; color:#e11d48;">💸</div>
                            <div>Catat Pengeluaran</div>
                        </a>
                        <a href="{{ route('pembelian.hutang.index') }}" class="tr-quick-btn">
                            <div class="tr-quick-icon" style="background:#fef3c7; color:#d97706;">💳</div>
                            <div>Kelola Hutang</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
