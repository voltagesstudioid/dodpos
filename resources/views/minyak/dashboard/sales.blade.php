<x-app-layout>
    @push('styles')
    <style>
        .sales-dash { max-width: 1280px; margin: 0 auto; }

        /* Profile Banner */
        .profile-banner {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            border-radius: 16px;
            padding: 28px 32px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            position: relative;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .profile-banner::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .profile-banner::after {
            content: '';
            position: absolute;
            bottom: -60%;
            right: 20%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .profile-info { display: flex; align-items: center; gap: 20px; position: relative; z-index: 1; }
        .profile-avatar {
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px;
            backdrop-filter: blur(8px);
        }
        .profile-name { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .profile-meta { display: flex; gap: 16px; font-size: 13px; opacity: 0.85; flex-wrap: wrap; }
        .profile-meta span { display: flex; align-items: center; gap: 4px; }
        .profile-right { position: relative; z-index: 1; text-align: right; }
        .profile-date { font-size: 14px; opacity: 0.85; margin-bottom: 8px; }
        .profile-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(8px);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        /* KPI Grid */
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .kpi-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }
        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .kpi-card.blue::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
        .kpi-card.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
        .kpi-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
        .kpi-card.amber::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .kpi-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .kpi-label { font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .kpi-ico {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
        }
        .kpi-ico.blue { background: #eff6ff; }
        .kpi-ico.green { background: #ecfdf5; }
        .kpi-ico.purple { background: #f5f3ff; }
        .kpi-ico.amber { background: #fffbeb; }
        .kpi-value { font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
        .kpi-sub { font-size: 12px; color: #94a3b8; }

        /* Target Progress */
        .target-section { margin-bottom: 24px; }
        .target-card {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
            border: 1px solid #e2e8f0;
        }
        .target-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .target-title { font-size: 15px; font-weight: 700; color: #0f172a; }
        .target-amount { font-size: 14px; color: #64748b; }
        .target-amount strong { color: #1e40af; }
        .progress-bar-wrap { background: #f1f5f9; border-radius: 10px; height: 28px; position: relative; overflow: hidden; }
        .progress-bar-fill {
            height: 100%;
            border-radius: 10px;
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            transition: width 0.8s ease;
            display: flex; align-items: center; justify-content: flex-end; padding-right: 10px;
            font-size: 12px; font-weight: 700; color: #fff;
            min-width: 40px;
        }
        .progress-bar-fill.success { background: linear-gradient(90deg, #10b981, #34d399); }
        .progress-bar-fill.warning { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .progress-bar-fill.danger { background: linear-gradient(90deg, #ef4444, #f87171); }
        .target-footer { display: flex; justify-content: space-between; margin-top: 8px; font-size: 12px; color: #94a3b8; }

        /* Quick Actions */
        .qa-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px; }
        .qa-card {
            display: flex; align-items: center; gap: 14px;
            background: #fff;
            border-radius: 14px;
            padding: 18px 20px;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }
        .qa-card:hover { border-color: #3b82f6; box-shadow: 0 4px 12px rgba(59,130,246,0.12); transform: translateY(-2px); }
        .qa-ico {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .qa-ico.blue { background: linear-gradient(135deg, #eff6ff, #dbeafe); }
        .qa-ico.green { background: linear-gradient(135deg, #ecfdf5, #d1fae5); }
        .qa-ico.purple { background: linear-gradient(135deg, #f5f3ff, #ede9fe); }
        .qa-ico.amber { background: linear-gradient(135deg, #fffbeb, #fef3c7); }
        .qa-text { display: flex; flex-direction: column; }
        .qa-title { font-size: 14px; font-weight: 700; color: #0f172a; }
        .qa-sub { font-size: 11px; color: #94a3b8; margin-top: 2px; }

        /* Two-column layout */
        .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px; }

        /* Section Card */
        .section-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .section-header {
            padding: 18px 22px;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .section-title { font-size: 15px; font-weight: 700; color: #0f172a; }
        .section-badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px; font-weight: 600;
        }
        .section-badge.blue { background: #eff6ff; color: #1d4ed8; }
        .section-badge.red { background: #fef2f2; color: #dc2626; }
        .section-badge.green { background: #ecfdf5; color: #059669; }
        .section-badge.amber { background: #fffbeb; color: #d97706; }

        /* Table */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            text-align: left;
            padding: 12px 22px;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table td {
            padding: 12px 22px;
            font-size: 13px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: #f8fafc; }

        /* Status Pill */
        .status-pill {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px; font-weight: 600;
        }
        .status-pill.tunai { background: #ecfdf5; color: #059669; }
        .status-pill.hutang { background: #fef2f2; color: #dc2626; }
        .status-pill.transfer { background: #eff6ff; color: #1d4ed8; }

        /* Empty State */
        .empty-mini { padding: 40px 22px; text-align: center; }
        .empty-mini-ico { font-size: 32px; margin-bottom: 10px; opacity: 0.5; }
        .empty-mini-title { font-size: 13px; font-weight: 600; color: #94a3b8; }

        /* Stok List */
        .stok-list { padding: 8px 0; }
        .stok-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 22px;
            border-bottom: 1px solid #f1f5f9;
        }
        .stok-item:last-child { border-bottom: none; }
        .stok-item-info { display: flex; flex-direction: column; }
        .stok-item-name { font-size: 13px; font-weight: 600; color: #0f172a; }
        .stok-item-sub { font-size: 11px; color: #94a3b8; margin-top: 2px; }
        .stok-item-qty {
            font-size: 14px; font-weight: 700;
            padding: 4px 12px; border-radius: 8px;
        }
        .stok-item-qty.has { background: #ecfdf5; color: #059669; }
        .stok-item-qty.zero { background: #f1f5f9; color: #94a3b8; }

        /* Chart */
        .chart-wrap { padding: 16px 22px 22px; }
        .chart-bars { display: flex; align-items: flex-end; gap: 8px; height: 120px; }
        .chart-bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; }
        .chart-bar {
            width: 100%;
            border-radius: 6px 6px 0 0;
            background: linear-gradient(180deg, #3b82f6, #60a5fa);
            min-height: 4px;
            transition: height 0.4s ease;
        }
        .chart-bar.today { background: linear-gradient(180deg, #1e40af, #3b82f6); }
        .chart-bar-label { font-size: 10px; color: #94a3b8; font-weight: 500; }
        .chart-bar-value { font-size: 10px; color: #475569; font-weight: 600; }

        /* Setoran Status */
        .setoran-status {
            display: flex; align-items: center; gap: 12px;
            padding: 16px 22px;
            border-bottom: 1px solid #f1f5f9;
        }
        .setoran-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
        }
        .setoran-dot.pending { background: #f59e0b; animation: pulse-dot 1.5s infinite; }
        .setoran-dot.done { background: #10b981; }
        .setoran-dot.none { background: #cbd5e1; }
        @keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:0.4} }

        /* Hutang Alert Item */
        .hutang-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 22px;
            border-bottom: 1px solid #f1f5f9;
        }
        .hutang-item:last-child { border-bottom: none; }
        .hutang-customer { font-size: 13px; font-weight: 600; color: #0f172a; }
        .hutang-detail { font-size: 11px; color: #94a3b8; margin-top: 2px; }
        .hutang-amount { text-align: right; }
        .hutang-amount .amount { font-size: 14px; font-weight: 700; color: #dc2626; }
        .hutang-amount .due { font-size: 11px; color: #94a3b8; margin-top: 2px; }

        @media (max-width: 1024px) {
            .kpi-grid { grid-template-columns: repeat(2, 1fr); }
            .qa-grid { grid-template-columns: repeat(2, 1fr); }
            .content-grid { grid-template-columns: 1fr; }
            .profile-banner { flex-direction: column; text-align: center; }
            .profile-right { text-align: center; }
        }
        @media (max-width: 640px) {
            .kpi-grid { grid-template-columns: 1fr; }
            .qa-grid { grid-template-columns: 1fr; }
        }
    </style>
    @endpush

    <div class="sales-dash">

        {{-- Profile Banner --}}
        <div class="profile-banner">
            <div class="profile-info">
                <div class="profile-avatar">👤</div>
                <div>
                    <div class="profile-name">{{ $salesProfile->nama }}</div>
                    <div class="profile-meta">
                        <span>🏷️ {{ $salesProfile->kode_sales }}</span>
                        <span>🚗 {{ $salesProfile->no_kendaraan ?? '-' }}</span>
                        <span>📱 {{ $salesProfile->no_hp ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="profile-right">
                <div class="profile-date">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
                <div class="profile-badge">🛢️ Sales Minyak</div>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="kpi-grid">
            <div class="kpi-card blue">
                <div class="kpi-top">
                    <div class="kpi-label">Penjualan Hari Ini</div>
                    <div class="kpi-ico blue">💰</div>
                </div>
                <div class="kpi-value">Rp {{ number_format($stats['penjualan_hari_ini'], 0, ',', '.') }}</div>
                <div class="kpi-sub">{{ $stats['transaksi_hari_ini'] }} transaksi</div>
            </div>
            <div class="kpi-card green">
                <div class="kpi-top">
                    <div class="kpi-label">Volume Terjual</div>
                    <div class="kpi-ico green">📦</div>
                </div>
                <div class="kpi-value">{{ number_format($stats['volume_hari_ini'], 0, ',', '.') }} L</div>
                <div class="kpi-sub">Liter hari ini</div>
            </div>
            <div class="kpi-card purple">
                <div class="kpi-top">
                    <div class="kpi-label">Kunjungan</div>
                    <div class="kpi-ico purple">📍</div>
                </div>
                <div class="kpi-value">{{ $kunjunganHariIni }}</div>
                <div class="kpi-sub">Toko dikunjungi hari ini</div>
            </div>
            <div class="kpi-card amber">
                <div class="kpi-top">
                    <div class="kpi-label">Tunai Hari Ini</div>
                    <div class="kpi-ico amber">💵</div>
                </div>
                <div class="kpi-value">Rp {{ number_format($stats['tunai_hari_ini'], 0, ',', '.') }}</div>
                <div class="kpi-sub">Cash diterima</div>
            </div>
        </div>

        {{-- Target Progress --}}
        @if($targetHarian > 0)
        <div class="target-section">
            <div class="target-card">
                <div class="target-header">
                    <div class="target-title">🎯 Target Harian</div>
                    <div class="target-amount">
                        <strong>Rp {{ number_format($stats['penjualan_hari_ini'], 0, ',', '.') }}</strong>
                        / Rp {{ number_format($targetHarian, 0, ',', '.') }}
                    </div>
                </div>
                <div class="progress-bar-wrap">
                    @php
                        $barClass = $progressHarian >= 100 ? 'success' : ($progressHarian >= 50 ? '' : ($progressHarian >= 25 ? 'warning' : 'danger'));
                    @endphp
                    <div class="progress-bar-fill {{ $barClass }}" style="width: {{ min($progressHarian, 100) }}%">
                        {{ $progressHarian }}%
                    </div>
                </div>
                <div class="target-footer">
                    <span>Sisa: Rp {{ number_format(max($targetHarian - $stats['penjualan_hari_ini'], 0), 0, ',', '.') }}</span>
                    <span>Loading hari ini: {{ number_format($totalLoadingHariIni, 0, ',', '.') }} L</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Quick Actions --}}
        <div class="qa-grid">
            <a href="{{ route('minyak.penjualan.create') }}" class="qa-card">
                <div class="qa-ico blue">📝</div>
                <div class="qa-text">
                    <div class="qa-title">Input Penjualan</div>
                    <div class="qa-sub">Catat transaksi baru</div>
                </div>
            </a>
            <a href="{{ route('minyak.stok.index') }}" class="qa-card">
                <div class="qa-ico green">🚛</div>
                <div class="qa-text">
                    <div class="qa-title">Cek Stok</div>
                    <div class="qa-sub">Stok kendaraan saat ini</div>
                </div>
            </a>
            <a href="{{ route('minyak.kunjungan.index') }}" class="qa-card">
                <div class="qa-ico purple">📍</div>
                <div class="qa-text">
                    <div class="qa-title">Kunjungan</div>
                    <div class="qa-sub">Catat kunjungan toko</div>
                </div>
            </a>
            <a href="{{ route('minyak.setoran.index') }}" class="qa-card">
                <div class="qa-ico amber">💵</div>
                <div class="qa-text">
                    <div class="qa-title">Setoran</div>
                    <div class="qa-sub">
                        @if($setoranHariIni)
                            {{ $setoranHariIni->status === 'pending' ? 'Status: Pending' : 'Status: ' . ucfirst($setoranHariIni->status) }}
                        @else
                            Belum setor hari ini
                        @endif
                    </div>
                </div>
            </a>
        </div>

        {{-- Main Content: Recent Sales + Side Panel --}}
        <div class="content-grid">
            {{-- Recent Penjualan --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-title">📋 Penjualan Terakhir</div>
                    <a href="{{ route('minyak.penjualan.index') }}" class="section-badge blue">Lihat Semua →</a>
                </div>
                @if($recentPenjualan->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPenjualan as $p)
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:#0f172a;">{{ $p->pelanggan->nama_toko ?? '-' }}</div>
                                    <div style="font-size:11px;color:#94a3b8;">{{ \Carbon\Carbon::parse($p->tanggal_jual)->format('d M Y') }}</div>
                                </td>
                                <td>{{ $p->produk->nama_produk ?? '-' }}</td>
                                <td>{{ $p->jumlah }} L</td>
                                <td style="font-weight:700;">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td><span class="status-pill {{ $p->tipe_bayar }}">{{ ucfirst($p->tipe_bayar) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-mini">
                        <div class="empty-mini-ico">📝</div>
                        <div class="empty-mini-title">Belum ada penjualan</div>
                    </div>
                @endif
            </div>

            {{-- Side Panel --}}
            <div style="display:flex;flex-direction:column;gap:20px;">
                {{-- Chart 7 Hari --}}
                @php
                    $chartTotals = array_map(fn($v) => (float) $v, array_column($penjualanChart, 'total'));
                    $maxChart = max(count($chartTotals) ? max($chartTotals) : 0, 1);
                    $todayLabel = \Carbon\Carbon::now()->format('d M');
                @endphp
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title">📈 7 Hari Terakhir</div>
                    </div>
                    <div class="chart-wrap">
                        <div class="chart-bars">
                            @foreach($penjualanChart as $c)
                            <div class="chart-bar-col">
                                <div class="chart-bar-value">{{ (float) $c['total'] > 0 ? number_format((float) $c['total']/1000, 0) . 'k' : '-' }}</div>
                                <div class="chart-bar {{ $c['tanggal'] === $todayLabel ? 'today' : '' }}" style="height: {{ max(((float) $c['total'] / $maxChart) * 100, 4) }}%"></div>
                                <div class="chart-bar-label">{{ \Illuminate\Support\Str::before($c['tanggal'], ' ') }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Setoran Status --}}
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title">💵 Setoran Hari Ini</div>
                    </div>
                    <div class="setoran-status">
                        @if($setoranHariIni)
                            <div class="setoran-dot {{ $setoranHariIni->status === 'pending' ? 'pending' : 'done' }}"></div>
                            <div style="flex:1;">
                                <div style="font-size:14px;font-weight:600;color:#0f172a;">
                                    Rp {{ number_format($setoranHariIni->total_setor ?? 0, 0, ',', '.') }}
                                </div>
                                <div style="font-size:12px;color:#94a3b8;">Status: {{ ucfirst($setoranHariIni->status) }}</div>
                            </div>
                        @else
                            <div class="setoran-dot none"></div>
                            <div style="flex:1;">
                                <div style="font-size:14px;font-weight:600;color:#64748b;">Belum ada setoran</div>
                                <div style="font-size:12px;color:#94a3b8;">Submit setoran harian Anda</div>
                            </div>
                            <a href="{{ route('minyak.setoran.create') }}" class="section-badge blue" style="text-decoration:none;">Buat Setoran →</a>
                        @endif
                    </div>
                </div>

                {{-- Hutang Pending --}}
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title">💳 Piutang Pending</div>
                        @if($totalHutangPending > 0)
                            <div class="section-badge red">Rp {{ number_format($totalHutangPending, 0, ',', '.') }}</div>
                        @endif
                    </div>
                    @if($hutangPending->count() > 0)
                        @foreach($hutangPending as $h)
                        <div class="hutang-item">
                            <div>
                                <div class="hutang-customer">{{ $h->pelanggan->nama_toko ?? '-' }}</div>
                                <div class="hutang-detail">Jatuh tempo: {{ \Carbon\Carbon::parse($h->jatuh_tempo)->format('d M Y') }}</div>
                            </div>
                            <div class="hutang-amount">
                                <div class="amount">Rp {{ number_format($h->sisa, 0, ',', '.') }}</div>
                                @if(\Carbon\Carbon::parse($h->jatuh_tempo)->isPast())
                                    <div class="due" style="color:#dc2626;">⚠️ Overdue</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-mini">
                            <div class="empty-mini-ico">✅</div>
                            <div class="empty-mini-title">Tidak ada piutang pending</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Monthly Summary --}}
        <div class="section-card" style="margin-bottom:24px;">
            <div class="section-header">
                <div class="section-title">📅 Rekap Bulan Ini</div>
                <div class="section-badge green">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</div>
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);padding:20px 22px;gap:20px;">
                <div>
                    <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px;">Total Penjualan</div>
                    <div style="font-size:20px;font-weight:800;color:#0f172a;">Rp {{ number_format($statsBulan['total_penjualan'], 0, ',', '.') }}</div>
                </div>
                <div>
                    <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px;">Total Transaksi</div>
                    <div style="font-size:20px;font-weight:800;color:#0f172a;">{{ $statsBulan['total_transaksi'] }}</div>
                </div>
                <div>
                    <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px;">Piutang Baru</div>
                    <div style="font-size:20px;font-weight:800;color:#dc2626;">Rp {{ number_format($statsBulan['total_hutang_baru'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
