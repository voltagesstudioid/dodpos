<x-app-layout>
    <x-slot name="header">Dashboard Admin 4</x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        * { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }

        .dashboard-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .greeting-section {
            margin-bottom: 2rem;
        }

        .greeting-section h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.5rem 0;
        }

        .greeting-section h1 span {
            color: #f97316;
        }

        .greeting-meta {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
            font-size: 0.875rem;
            color: #64748b;
        }

        .opname-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            font-weight: 900;
            font-size: 0.75rem;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-icon.amber { background: linear-gradient(135deg, #fbbf24, #f59e0b); }
        .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .stat-icon.slate { background: linear-gradient(135deg, #64748b, #475569); }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 0.8125rem;
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-value.amber { color: #f59e0b; }
        .stat-value.purple { color: #7c3aed; }
        .stat-value.slate { color: #475569; }

        .panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .panel-title {
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 1rem 0;
        }

        .action-grid {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-size: 0.9375rem;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .action-btn.primary {
            background: linear-gradient(135deg, #0891b2, #0e7490);
            color: white;
        }

        .action-btn.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .action-btn.secondary {
            background: #ffffff;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .action-btn.secondary:hover {
            background: #f8fafc;
        }

        @media (max-width: 768px) {
            .stat-grid {
                grid-template-columns: 1fr;
            }

            .greeting-section h1 {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="dashboard-wrapper">
        <!-- Greeting Section -->
        <div class="greeting-section">
            <h1>Selamat Datang, <span>{{ Auth::user()->name }}</span> 👋</h1>
            @php
                $opStatus = (string) ($opnameToday['status'] ?? 'missing');
                $opBadge = match($opStatus) {
                    'approved' => ['text' => 'APPROVED', 'bg' => '#dcfce7', 'color' => '#166534'],
                    'submitted' => ['text' => 'SUBMITTED', 'bg' => '#e0f2fe', 'color' => '#075985'],
                    default => ['text' => 'BELUM', 'bg' => '#fef3c7', 'color' => '#92400e'],
                };
            @endphp
            <div class="greeting-meta">
                <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} &mdash; <strong style="color:#0f172a;">Dashboard Gudang Keluar & Distribusi</strong></span>
                <span class="opname-badge" style="background: {{ $opBadge['bg'] }}; color: {{ $opBadge['color'] }};">
                    Opname Hari Ini: {{ $opBadge['text'] }}
                </span>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon amber">📤</div>
                <div class="stat-content">
                    <div class="stat-label">Pengeluaran Gudang Hari Ini</div>
                    <div class="stat-value amber">{{ $pengeluaranHariIni }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">🔄</div>
                <div class="stat-content">
                    <div class="stat-label">Transfer Gudang Hari Ini</div>
                    <div class="stat-value purple">{{ $transferGudangHariIni }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon slate">🔍</div>
                <div class="stat-content">
                    <div class="stat-label">Opname Stok Hari Ini</div>
                    <div class="stat-value slate">{{ $opnameHariIni }}</div>
                </div>
            </div>
        </div>

        <!-- Action Shortcuts Panel -->
        <div class="panel">
            <h3 class="panel-title">Action Shortcuts (Akses Cepat Admin 4)</h3>
            <div class="action-grid">
                <a href="{{ route('gudang.stok') }}" class="action-btn primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <path d="M3 9h18"></path>
                        <path d="M9 21V9"></path>
                    </svg>
                    Cek Stok Gudang
                </a>
                <a href="{{ route('gudang.pengeluaran') }}" class="action-btn warning">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Input Pengeluaran / Mutasi Manual
                </a>
                <a href="{{ route('gudang.transfer') }}" class="action-btn secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="17 1 21 5 17 9"></polyline>
                        <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                        <polyline points="7 23 3 19 7 15"></polyline>
                        <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                    </svg>
                    Transfer Cabang
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
