<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .sd-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .sd-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .sd-hdr-l { display:flex; align-items:center; gap:1rem; }
        .sd-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            box-shadow:0 8px 24px rgba(37,99,235,0.3);
        }
        .sd-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .sd-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .sd-hdr-name { font-size:0.875rem; font-weight:600; color:#1e293b; }
        .sd-hdr-code { font-size:0.75rem; color:#64748b; font-family:'JetBrains Mono',monospace; }

        /* Profile Card */
        .sd-profile {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.5rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
            display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap;
        }
        .sd-profile-av {
            width:72px; height:72px; border-radius:16px; display:flex; align-items:center; justify-content:center;
            font-size:2rem; font-weight:800; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 8px 24px rgba(37,99,235,0.3);
        }
        .sd-profile-info { flex:1; min-width:200px; }
        .sd-profile-name { font-size:1.25rem; font-weight:800; color:#0f172a; }
        .sd-profile-meta { display:flex; gap:1rem; margin-top:0.375rem; flex-wrap:wrap; }
        .sd-profile-meta-item { font-size:0.8125rem; color:#64748b; display:flex; align-items:center; gap:0.25rem; }
        .sd-profile-status {
            padding:0.375rem 0.875rem; border-radius:99px; font-size:0.75rem; font-weight:700;
            display:inline-flex; align-items:center; gap:0.375rem;
        }
        .sd-profile-status.aktif { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .sd-profile-status.nonaktif { background:#f8fafc; color:#64748b; border:1px solid #e2e8f0; }
        .sd-profile-status.cuti { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
        .sd-profile-dot { width:8px; height:8px; border-radius:50%; }
        .sd-profile-dot.aktif { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,0.2); animation:sd-pulse 1.5s infinite; }
        .sd-profile-dot.nonaktif { background:#94a3b8; }
        .sd-profile-dot.cuti { background:#f59e0b; }
        @keyframes sd-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Quick Actions */
        .sd-actions { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
        .sd-action {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem;
            text-align:center; transition:all 0.3s; text-decoration:none;
        }
        .sd-action:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.08); border-color:transparent; }
        .sd-action-ico {
            width:56px; height:56px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; margin:0 auto 0.75rem;
        }
        .sd-action-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .sd-action-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .sd-action-ico.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .sd-action-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .sd-action-title { font-size:0.875rem; font-weight:700; color:#1e293b; }
        .sd-action-sub { font-size:0.75rem; color:#94a3b8; margin-top:0.25rem; }

        /* KPI Row */
        .sd-kpis { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
        .sd-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.375rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .sd-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .sd-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .sd-kpi.blue::before   { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .sd-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .sd-kpi.amber::before  { background:linear-gradient(180deg,#f59e0b,#d97706); }
        .sd-kpi.purple::before { background:linear-gradient(180deg,#8b5cf6,#7c3aed); }
        .sd-kpi-top { display:flex; align-items:center; justify-content:space-between; }
        .sd-kpi-left { display:flex; flex-direction:column; gap:0.25rem; }
        .sd-kpi-lbl { font-size:0.625rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .sd-kpi-val { font-size:1.75rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
        .sd-kpi-val.blue   { color:#2563eb; }
        .sd-kpi-val.green  { color:#059669; }
        .sd-kpi-val.amber  { color:#d97706; }
        .sd-kpi-val.purple { color:#7c3aed; }
        .sd-kpi-foot { font-size:0.6875rem; color:#94a3b8; margin-top:0.375rem; }
        .sd-kpi-ico { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; }
        .sd-kpi-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .sd-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .sd-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .sd-kpi-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }

        /* Content Grid */
        .sd-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:1.5rem; }
        .sd-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .sd-card-hdr {
            background:linear-gradient(180deg,#eff6ff,#f0f7ff); border-bottom:2px solid #bfdbfe;
            padding:1rem 1.375rem;
        }
        .sd-card-title {
            font-size:0.9375rem; font-weight:700; color:#1e40af;
            display:flex; align-items:center; gap:0.5rem;
        }
        .sd-card-title::before {
            content:''; width:4px; height:16px; border-radius:2px;
            background:linear-gradient(180deg,#3b82f6,#2563eb);
        }
        .sd-card-body { padding:1rem 1.375rem; }

        /* List items */
        .sd-list { list-style:none; padding:0; margin:0; }
        .sd-list-item {
            display:flex; align-items:center; gap:0.75rem; padding:0.75rem 0;
            border-bottom:1px solid #f1f5f9;
        }
        .sd-list-item:last-child { border-bottom:none; }
        .sd-list-ico {
            width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:0.875rem; flex-shrink:0;
        }
        .sd-list-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .sd-list-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .sd-list-info { flex:1; min-width:0; }
        .sd-list-title { font-size:0.8125rem; font-weight:600; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .sd-list-sub { font-size:0.75rem; color:#94a3b8; }
        .sd-list-val { font-size:0.8125rem; font-weight:700; font-family:'JetBrains Mono',monospace; }
        .sd-list-val.blue { color:#2563eb; }
        .sd-list-val.green { color:#059669; }
        .sd-list-val.amber { color:#d97706; }

        /* Loading items */
        .sd-loading-item {
            display:flex; align-items:center; justify-content:space-between; padding:0.625rem 0;
            border-bottom:1px solid #f1f5f9;
        }
        .sd-loading-item:last-child { border-bottom:none; }
        .sd-loading-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .sd-loading-type { font-size:0.6875rem; color:#94a3b8; }
        .sd-loading-stats { display:flex; gap:0.75rem; }
        .sd-loading-badge {
            padding:0.25rem 0.625rem; border-radius:8px; font-size:0.75rem; font-weight:700;
            font-family:'JetBrains Mono',monospace;
        }
        .sd-loading-badge.load { background:#eff6ff; color:#1d4ed8; }
        .sd-loading-badge.sold { background:#ecfdf5; color:#059669; }
        .sd-loading-badge.rest { background:#fffbeb; color:#d97706; }

        /* Setoran status */
        .sd-setoran {
            display:flex; align-items:center; justify-content:space-between; padding:1rem;
            background:#f8fafc; border-radius:12px;
        }
        .sd-setoran-info { display:flex; flex-direction:column; gap:0.25rem; }
        .sd-setoran-lbl { font-size:0.75rem; color:#64748b; }
        .sd-setoran-val { font-size:1.125rem; font-weight:800; color:#1e293b; font-family:'JetBrains Mono',monospace; }
        .sd-setoran-badge {
            padding:0.375rem 0.875rem; border-radius:99px; font-size:0.75rem; font-weight:700;
        }
        .sd-setoran-badge.pending { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
        .sd-setoran-badge.terverifikasi { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }

        /* Empty */
        .sd-empty { text-align:center; padding:2rem; color:#94a3b8; font-size:0.875rem; }

        @media(max-width:1024px) { .sd-kpis { grid-template-columns:repeat(2,1fr); } .sd-grid { grid-template-columns:1fr; } }
        @media(max-width:768px) { .sd-actions { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:640px) { .sd-kpis { grid-template-columns:1fr; } .sd-actions { grid-template-columns:1fr; } .sd-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="sd-page">

            {{-- Header --}}
            <div class="sd-hdr">
                <div class="sd-hdr-l">
                    <div class="sd-hdr-ico">👤</div>
                    <div>
                        <div class="sd-hdr-title">Dashboard Sales</div>
                        <div class="sd-hdr-sub">Selamat datang di panel sales mineral</div>
                    </div>
                </div>
                <div style="text-align:right;">
                    <div class="sd-hdr-name">{{ $salesProfile->nama }}</div>
                    <div class="sd-hdr-code">{{ $salesProfile->kode_sales }}</div>
                </div>
            </div>

            {{-- Profile Card --}}
            <div class="sd-profile">
                <div class="sd-profile-av">{{ substr($salesProfile->nama, 0, 1) }}</div>
                <div class="sd-profile-info">
                    <div class="sd-profile-name">{{ $salesProfile->nama }}</div>
                    <div class="sd-profile-meta">
                        <span class="sd-profile-meta-item">📱 {{ $salesProfile->no_hp ?? '-' }}</span>
                        <span class="sd-profile-meta-item">🚗 {{ $salesProfile->plat_nomor ?? '-' }}</span>
                    </div>
                </div>
                <span class="sd-profile-status {{ $salesProfile->status }}">
                    <span class="sd-profile-dot {{ $salesProfile->status }}"></span>
                    {{ ucfirst($salesProfile->status) }}
                </span>
            </div>

            {{-- Quick Actions --}}
            <div class="sd-actions">
                <a href="{{ route('mineral.kunjungan.index') }}" class="sd-action">
                    <div class="sd-action-ico blue">📍</div>
                    <div class="sd-action-title">Riwayat Kunjungan</div>
                    <div class="sd-action-sub">Lihat kunjungan (tercatat otomatis)</div>
                </a>
                <a href="{{ route('mineral.penjualan.create') }}" class="sd-action">
                    <div class="sd-action-ico green">💰</div>
                    <div class="sd-action-title">Input Penjualan</div>
                    <div class="sd-action-sub">Catat transaksi penjualan</div>
                </a>
                <a href="{{ route('mineral.setoran.create') }}" class="sd-action">
                    <div class="sd-action-ico amber">💵</div>
                    <div class="sd-action-title">Input Setoran</div>
                    <div class="sd-action-sub">Setor hasil penjualan</div>
                </a>
                <a href="{{ route('mineral.stok.index') }}" class="sd-action">
                    <div class="sd-action-ico purple">📦</div>
                    <div class="sd-action-title">Cek Stok</div>
                    <div class="sd-action-sub">Lihat stok kendaraan</div>
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="sd-kpis">
                <div class="sd-kpi blue">
                    <div class="sd-kpi-top">
                        <div class="sd-kpi-left">
                            <span class="sd-kpi-lbl">Penjualan Hari Ini</span>
                            <div>
                                <span class="sd-kpi-val blue">Rp {{ number_format($stats['penjualan_hari_ini'], 0, ',', '.') }}</span>
                            </div>
                            <div class="sd-kpi-foot">Omzet hari ini</div>
                        </div>
                        <div class="sd-kpi-ico blue">💰</div>
                    </div>
                </div>
                <div class="sd-kpi green">
                    <div class="sd-kpi-top">
                        <div class="sd-kpi-left">
                            <span class="sd-kpi-lbl">Transaksi</span>
                            <div>
                                <span class="sd-kpi-val green">{{ $stats['transaksi_hari_ini'] }}</span>
                            </div>
                            <div class="sd-kpi-foot">Jumlah transaksi hari ini</div>
                        </div>
                        <div class="sd-kpi-ico green">📋</div>
                    </div>
                </div>
                <div class="sd-kpi amber">
                    <div class="sd-kpi-top">
                        <div class="sd-kpi-left">
                            <span class="sd-kpi-lbl">Kunjungan</span>
                            <div>
                                <span class="sd-kpi-val amber">{{ $stats['kunjungan_hari_ini'] }}</span>
                            </div>
                            <div class="sd-kpi-foot">Kunjungan hari ini</div>
                        </div>
                        <div class="sd-kpi-ico amber">📍</div>
                    </div>
                </div>
                <div class="sd-kpi purple">
                    <div class="sd-kpi-top">
                        <div class="sd-kpi-left">
                            <span class="sd-kpi-lbl">Bulan Ini</span>
                            <div>
                                <span class="sd-kpi-val purple">Rp {{ number_format($statsBulanIni['total_penjualan'], 0, ',', '.') }}</span>
                            </div>
                            <div class="sd-kpi-foot">Total omzet bulan ini</div>
                        </div>
                        <div class="sd-kpi-ico purple">📊</div>
                    </div>
                </div>
            </div>

            {{-- Content Grid --}}
            <div class="sd-grid">
                {{-- Loading Hari Ini --}}
                <div class="sd-card">
                    <div class="sd-card-hdr">
                        <div class="sd-card-title">Loading Hari Ini</div>
                    </div>
                    <div class="sd-card-body">
                        @if($loadingHariIni->count() > 0)
                            @foreach($loadingHariIni as $loading)
                            <div class="sd-loading-item">
                                <div>
                                    <div class="sd-loading-name">{{ $loading->produk->nama ?? '-' }}</div>
                                    <div class="sd-loading-type">{{ $loading->produk->jenis ?? '-' }}</div>
                                </div>
                                <div class="sd-loading-stats">
                                    <span class="sd-loading-badge load">{{ $loading->jumlah_loading }}L</span>
                                    <span class="sd-loading-badge sold">{{ $loading->terjual }}L</span>
                                    <span class="sd-loading-badge rest">{{ $loading->sisa_stok }}L</span>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="sd-empty">Belum ada loading hari ini</div>
                        @endif
                    </div>
                </div>

                {{-- Setoran Status --}}
                <div class="sd-card">
                    <div class="sd-card-hdr">
                        <div class="sd-card-title">Setoran Hari Ini</div>
                    </div>
                    <div class="sd-card-body">
                        @if($setoranHariIni)
                            <div class="sd-setoran">
                                <div class="sd-setoran-info">
                                    <span class="sd-setoran-lbl">Total Setoran</span>
                                    <span class="sd-setoran-val">Rp {{ number_format($setoranHariIni->total_setor, 0, ',', '.') }}</span>
                                </div>
                                <span class="sd-setoran-badge {{ $setoranHariIni->status }}">
                                    {{ ucfirst($setoranHariIni->status) }}
                                </span>
                            </div>
                        @else
                            <div class="sd-empty">Belum ada setoran hari ini</div>
                        @endif
                    </div>
                </div>

                {{-- Kunjungan Terakhir --}}
                <div class="sd-card">
                    <div class="sd-card-hdr">
                        <div class="sd-card-title">Kunjungan Terakhir</div>
                    </div>
                    <div class="sd-card-body">
                        @if($kunjunganTerakhir->count() > 0)
                            <ul class="sd-list">
                                @foreach($kunjunganTerakhir as $k)
                                <li class="sd-list-item">
                                    <div class="sd-list-ico {{ $k->waktu_checkout ? 'green' : 'blue' }}">
                                        {{ $k->waktu_checkout ? '✅' : '📍' }}
                                    </div>
                                    <div class="sd-list-info">
                                        <div class="sd-list-title">{{ $k->pelanggan->nama_toko ?? '-' }}</div>
                                        <div class="sd-list-sub">{{ $k->waktu_checkin->format('d M Y H:i') }}</div>
                                    </div>
                                    <span class="sd-list-val {{ $k->waktu_checkout ? 'green' : 'amber' }}">
                                        {{ $k->waktu_checkout ? 'Selesai' : 'Aktif' }}
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="sd-empty">Belum ada kunjungan</div>
                        @endif
                    </div>
                </div>

                {{-- Penjualan Terakhir --}}
                <div class="sd-card">
                    <div class="sd-card-hdr">
                        <div class="sd-card-title">Penjualan Terakhir</div>
                    </div>
                    <div class="sd-card-body">
                        @if($penjualanTerakhir->count() > 0)
                            <ul class="sd-list">
                                @foreach($penjualanTerakhir as $p)
                                <li class="sd-list-item">
                                    <div class="sd-list-ico {{ $p->tipe_bayar == 'tunai' ? 'green' : 'blue' }}">
                                        {{ $p->tipe_bayar == 'tunai' ? '💵' : '🔄' }}
                                    </div>
                                    <div class="sd-list-info">
                                        <div class="sd-list-title">{{ $p->pelanggan->nama_toko ?? '-' }}</div>
                                        <div class="sd-list-sub">{{ $p->tanggal_jual->format('d M Y') }} • {{ $p->no_faktur }}</div>
                                    </div>
                                    <span class="sd-list-val blue">Rp {{ number_format($p->total, 0, ',', '.') }}</span>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="sd-empty">Belum ada penjualan</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
