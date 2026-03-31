<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <style>
        /* ===== DASHBOARD LIGHT STYLES ===== */
        .dash-greeting { margin-bottom: 1.75rem; }
        .dash-greeting h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.025em;
            margin-bottom: 0.25rem;
        }
        .dash-greeting h1 span { color: #6366f1; }
        .dash-greeting p { font-size: 0.875rem; color: #94a3b8; }

        /* Stat Cards */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.375rem 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: all 0.25s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            border-color: #c7d2fe;
        }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .stat-icon.indigo  { background: #eef2ff; }
        .stat-icon.emerald { background: #ecfdf5; }
        .stat-icon.amber   { background: #fffbeb; }
        .stat-icon.rose    { background: #fff1f2; }

        .stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 0.375rem;
        }
        .stat-value {
            font-size: 1.875rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        .stat-value.indigo  { color: #4f46e5; }
        .stat-value.emerald { color: #059669; }
        .stat-value.amber   { color: #d97706; }
        .stat-value.rose    { color: #e11d48; }

        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
        }
        .stat-badge.neutral { background: #f1f5f9; color: #94a3b8; }
        .stat-badge.up      { background: #ecfdf5; color: #059669; }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.25rem;
        }
        @media (max-width: 1280px) { .content-grid { grid-template-columns: 1fr; } }

        /* Panel */
        .panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.125rem 1.375rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .panel-title { font-size: 0.9375rem; font-weight: 700; color: #1e293b; }
        .panel-subtitle { font-size: 0.7rem; color: #94a3b8; margin-top: 1px; }
        .panel-action {
            font-size: 0.75rem;
            color: #6366f1;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        .panel-action:hover { color: #4f46e5; }
        .panel-body { padding: 1.25rem 1.375rem; }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
        .qa-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            padding: 1.125rem 1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 13px;
            text-decoration: none;
            color: #475569;
            font-size: 0.8125rem;
            font-weight: 600;
            transition: all 0.25s ease;
            text-align: center;
        }
        .qa-btn:hover {
            background: #eef2ff;
            border-color: #c7d2fe;
            color: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99,102,241,0.12);
        }
        .qa-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.25s;
        }
        .qa-btn:hover .qa-icon { background: #e0e7ff; border-color: #c7d2fe; }

        /* Activity */
        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            padding: 0.875rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .activity-item:last-child { border-bottom: none; padding-bottom: 0; }
        .activity-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            margin-top: 5px;
            flex-shrink: 0;
        }
        .activity-text { font-size: 0.825rem; color: #475569; line-height: 1.5; }
        .activity-time  { font-size: 0.7rem; color: #94a3b8; margin-top: 2px; }

        /* Mini chart */
        .mini-chart {
            display: flex;
            align-items: flex-end;
            gap: 5px;
            height: 70px;
            padding: 0 0.375rem;
        }
        .mini-bar {
            flex: 1; height: var(--bar-height, 8%);
            border-radius: 5px 5px 0 0;
            background: #e0e7ff;
            transition: background 0.2s;
            cursor: pointer;
        }
        .mini-bar:hover  { background: #a5b4fc; }
        .mini-bar.active { background: #6366f1; }

        .chart-label {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
        }
        .chart-label span { font-size: 0.65rem; color: #94a3b8; flex: 1; text-align: center; }

        /* Info rows */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.625rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .info-key { font-size: 0.8rem; color: #64748b; }
        .info-val { font-size: 0.8rem; font-weight: 600; color: #1e293b; }

        .badge-green  { background: #ecfdf5; color: #059669; padding: 0.2rem 0.625rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .badge-yellow { background: #fffbeb; color: #d97706; padding: 0.2rem 0.625rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }

        .empty-note { font-size: 0.78rem; color: #cbd5e1; text-align: center; padding: 1rem 0 0; }
    </style>

    <!-- Greeting -->
    <div class="dash-greeting">
        <h1>Selamat Datang, <span>{{ Auth::user()->name }}</span> 👋</h1>
        <p>{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} &mdash; Berikut ringkasan bisnis Anda hari ini.</p>
    </div>

    <!-- Alerts / Notifications -->
    @if(!empty($alerts))
    <div style="display:flex; flex-direction:column; gap:0.75rem; margin-bottom:1.5rem;">
        @foreach($alerts as $alert)
        <a href="{{ $alert['link'] }}" style="text-decoration:none;">
            <div style="display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border-radius:12px; background:{{ $alert['type'] === 'danger' ? '#fef2f2' : ($alert['type'] === 'warning' ? '#fffbeb' : '#eff6ff') }}; border:1px solid {{ $alert['type'] === 'danger' ? '#fecaca' : ($alert['type'] === 'warning' ? '#fcd34d' : '#bfdbfe') }}; transition:all 0.2s;" onmouseover="this.style.transform='translateX(4px)';" onmouseout="this.style.transform='translateX(0)';">
                <div style="font-size:1.5rem;">{{ $alert['icon'] }}</div>
                <div style="flex:1;">
                    <div style="font-weight:700; color:{{ $alert['type'] === 'danger' ? '#dc2626' : ($alert['type'] === 'warning' ? '#d97706' : '#2563eb') }};">{{ $alert['title'] }}</div>
                    <div style="font-size:0.8125rem; color:#64748b;">{{ $alert['message'] }}</div>
                </div>
                <div style="font-size:0.75rem; color:{{ $alert['type'] === 'danger' ? '#dc2626' : ($alert['type'] === 'warning' ? '#d97706' : '#2563eb') }}; font-weight:600;">Lihat →</div>
            </div>
        </a>
        @endforeach
    </div>
    @endif

    <!-- Stat Cards -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon indigo">📦</div>
            <div style="flex:1;min-width:0;">
                <div class="stat-label">Total Produk</div>
                <div class="stat-value indigo">{{ \App\Models\Product::count() }}</div>
                <span class="stat-badge neutral">SKU terdaftar</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">🧾</div>
            <div style="flex:1;min-width:0;">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value emerald">{{ \App\Models\Transaction::count() }}</div>
                <span class="stat-badge up">↑ Semua waktu</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">💰</div>
            <div style="flex:1;min-width:0;">
                <div class="stat-label">Pendapatan Hari Ini</div>
                <div class="stat-value amber">Rp {{ number_format(\App\Models\Transaction::whereDate('created_at', now()->toDateString())->where('status', 'completed')->sum('total_amount'), 0, ',', '.') }}</div>
                <span class="stat-badge neutral">Hari ini</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon rose">👥</div>
            <div style="flex:1;min-width:0;">
                <div class="stat-label">Pelanggan Aktif</div>
                <div class="stat-value rose">{{ \App\Models\Customer::where('is_active', true)->count() }}</div>
                <span class="stat-badge neutral">Total aktif</span>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Left -->
        <div style="display:flex;flex-direction:column;gap:1.25rem;">
            <!-- Chart -->
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Penjualan Mingguan</div>
                        <div class="panel-subtitle">7 hari terakhir</div>
                    </div>
                    <a href="#" class="panel-action">Lihat detail →</a>
                </div>
                <div class="panel-body">
                    <div class="mini-chart">
                        @foreach($weeklySales as $sale)
                        <div class="mini-bar {{ $sale['date'] === now()->toDateString() ? 'active' : '' }}" 
                             style="--bar-height: {{ max(8, (int) $sale['percentage']) }}%;" 
                             title="{{ $sale['label'] }}: Rp {{ number_format($sale['amount'], 0, ',', '.') }}"></div>
                        @endforeach
                    </div>
                    <div class="chart-label">
                        @foreach($weeklySales as $sale)
                            <span>{{ $sale['label'] }}</span>
                        @endforeach
                    </div>
                    @if(collect($weeklySales)->sum('amount') == 0)
                        <p class="empty-note">Data penjualan akan muncul setelah ada transaksi</p>
                    @endif
                </div>
            </div>

            <!-- Aktivitas -->
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Aktivitas Terbaru</div>
                        <div class="panel-subtitle">Log sistem hari ini</div>
                    </div>
                    <a href="#" class="panel-action">Semua log →</a>
                </div>
                <div class="panel-body">
                    <div class="activity-item">
                        <div class="activity-dot" style="background:#6366f1;"></div>
                        <div>
                            <div class="activity-text">Sistem berhasil dimulai</div>
                            <div class="activity-time">{{ now()->format('H:i') }} — Hari ini</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot" style="background:#10b981;"></div>
                        <div>
                            <div class="activity-text">{{ Auth::user()->name }} masuk ke sistem</div>
                            <div class="activity-time">{{ now()->format('H:i') }} — Hari ini</div>
                        </div>
                    </div>
                    <p class="empty-note">Belum ada transaksi hari ini</p>
                </div>
            </div>
        </div>

        <!-- Right -->
        <div style="display:flex;flex-direction:column;gap:1.25rem;">
            <!-- Menu Cepat -->
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Menu Cepat</div>
                        <div class="panel-subtitle">Aksi yang sering digunakan</div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="quick-actions">
                        @can('view_pos_kasir')
                            <a href="{{ route('kasir.index') }}" class="qa-btn">
                                <div class="qa-icon" style="background:#eef2ff;color:#4f46e5;border-color:#c7d2fe;">🛒</div>
                                Buka Kasir
                            </a>
                        @endcan
                        <a href="{{ route('gudang.terimapo.index') }}" class="qa-btn">
                            <div class="qa-icon" style="background:#ecfdf5;color:#059669;border-color:#a7f3d0;">📥</div>
                            Terima PO (Supplier)
                        </a>
                        <a href="{{ route('operasional.pengeluaran.create') }}" class="qa-btn">
                            <div class="qa-icon" style="background:#fff1f2;color:#e11d48;border-color:#fecdd3;">💸</div>
                            Catat Biaya
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info Sistem -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Info Sistem</div>
                </div>
                <div class="panel-body">
                    <div class="info-row">
                        <span class="info-key">Versi App</span>
                        <span class="info-val" style="color:#6366f1;">v1.0.0</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Database</span>
                        <span class="badge-green">Terhubung</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Mode</span>
                        <span class="badge-yellow">Development</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Waktu Server</span>
                        <span class="info-val">{{ now()->format('H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
