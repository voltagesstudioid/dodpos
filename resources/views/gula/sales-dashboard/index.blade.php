<x-app-layout>
    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style>
        .sg-page{max-width:82rem;margin:0 auto;padding:0 1rem;font-family:'Plus Jakarta Sans',sans-serif}
        .sg-mono{font-family:'JetBrains Mono',monospace}

        /* Header */
        .sg-hdr{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem}
        .sg-hdr-l{display:flex;align-items:center;gap:1rem}
        .sg-hdr-ico{width:52px;height:52px;border-radius:16px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f59e0b,#d97706);box-shadow:0 8px 24px rgba(217,119,6,.3)}
        .sg-hdr-ico svg{width:26px;height:26px;color:#fff}
        .sg-hdr-title{font-size:1.5rem;font-weight:800;color:#1f2937;letter-spacing:-.03em;line-height:1.2}
        .sg-hdr-sub{font-size:.8125rem;color:#92400e;margin-top:2px}
        .sg-hdr-right{text-align:right}
        .sg-hdr-name{font-size:.875rem;font-weight:700;color:#1f2937}
        .sg-hdr-code{font-size:.75rem;color:#6b7280;font-family:'JetBrains Mono',monospace}

        /* Profile Card */
        .sg-profile{background:#fff;border:1px solid #fde68a;border-radius:20px;padding:1.5rem;margin-bottom:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.05);display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap}
        .sg-profile-av{width:72px;height:72px;border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;flex-shrink:0;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;box-shadow:0 8px 24px rgba(217,119,6,.3)}
        .sg-profile-info{flex:1;min-width:200px}
        .sg-profile-name{font-size:1.25rem;font-weight:800;color:#1f2937}
        .sg-profile-meta{display:flex;gap:1rem;margin-top:.375rem;flex-wrap:wrap}
        .sg-profile-meta-item{font-size:.8125rem;color:#6b7280;display:flex;align-items:center;gap:.35rem}
        .sg-profile-meta-item svg{width:15px;height:15px;color:#d97706}
        .sg-profile-status{padding:.375rem .875rem;border-radius:99px;font-size:.75rem;font-weight:700;display:inline-flex;align-items:center;gap:.375rem}
        .sg-profile-status.aktif{background:#fef3c7;color:#92400e;border:1px solid #fcd34d}
        .sg-profile-status.nonaktif{background:#f9fafb;color:#6b7280;border:1px solid #e5e7eb}
        .sg-profile-status.cuti{background:#fef9c3;color:#a16207;border:1px solid #fde68a}
        .sg-dot{width:8px;height:8px;border-radius:50%}
        .sg-dot.aktif{background:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.2);animation:sg-pulse 1.5s infinite}
        .sg-dot.nonaktif{background:#9ca3af}
        .sg-dot.cuti{background:#eab308}
        @keyframes sg-pulse{0%,100%{opacity:1}50%{opacity:.4}}

        /* Quick Actions */
        .sg-actions{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem}
        .sg-action{background:#fff;border:1px solid #fde68a;border-radius:20px;padding:1.375rem 1rem;text-align:center;transition:all .3s;text-decoration:none;position:relative;overflow:hidden}
        .sg-action::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;border-radius:4px 0 0 4px}
        .sg-action:nth-child(1)::before{background:linear-gradient(180deg,#3b82f6,#2563eb)}
        .sg-action:nth-child(2)::before{background:linear-gradient(180deg,#10b981,#059669)}
        .sg-action:nth-child(3)::before{background:linear-gradient(180deg,#f59e0b,#d97706)}
        .sg-action:nth-child(4)::before{background:linear-gradient(180deg,#8b5cf6,#7c3aed)}
        .sg-action:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(245,158,11,.12);border-color:#fcd34d}
        .sg-action-ico{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem}
        .sg-action-ico svg{width:24px;height:24px}
        .sg-action-ico.blue{background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#2563eb}
        .sg-action-ico.green{background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#059669}
        .sg-action-ico.amber{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706}
        .sg-action-ico.purple{background:linear-gradient(135deg,#ede9fe,#ddd6fe);color:#7c3aed}
        .sg-action-title{font-size:.875rem;font-weight:700;color:#1f2937}
        .sg-action-sub{font-size:.72rem;color:#9ca3af;margin-top:.25rem}

        /* KPI Cards */
        .sg-kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem}
        .sg-kpi{background:#fff;border:1px solid #fde68a;border-radius:18px;padding:1.25rem 1.375rem;position:relative;overflow:hidden;transition:all .3s;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .sg-kpi::before{content:'';position:absolute;top:0;left:0;bottom:0;width:4px;border-radius:4px 0 0 4px}
        .sg-kpi:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(245,158,11,.1);border-color:#fcd34d}
        .sg-kpi.amber::before{background:linear-gradient(180deg,#f59e0b,#d97706)}
        .sg-kpi.green::before{background:linear-gradient(180deg,#10b981,#059669)}
        .sg-kpi.blue::before{background:linear-gradient(180deg,#3b82f6,#2563eb)}
        .sg-kpi.purple::before{background:linear-gradient(180deg,#8b5cf6,#7c3aed)}
        .sg-kpi-top{display:flex;align-items:flex-start;justify-content:space-between}
        .sg-kpi-lbl{font-size:.625rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af}
        .sg-kpi-val{font-size:1.5rem;font-weight:800;line-height:1;margin-top:6px}
        .sg-kpi-val.amber{color:#d97706}
        .sg-kpi-val.green{color:#059669}
        .sg-kpi-val.blue{color:#2563eb}
        .sg-kpi-val.purple{color:#7c3aed}
        .sg-kpi-foot{font-size:.6875rem;color:#9ca3af;margin-top:.375rem}
        .sg-kpi-ico{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .sg-kpi-ico svg{width:22px;height:22px}
        .sg-kpi-ico.amber{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706}
        .sg-kpi-ico.green{background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#059669}
        .sg-kpi-ico.blue{background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#2563eb}
        .sg-kpi-ico.purple{background:linear-gradient(135deg,#ede9fe,#ddd6fe);color:#7c3aed}

        /* Content Grid */
        .sg-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem}
        .sg-card{background:#fff;border:1px solid #fde68a;border-radius:20px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.04)}
        .sg-card-hdr{background:linear-gradient(180deg,#fffbeb,#fef9ee);border-bottom:2px solid #fde68a;padding:1rem 1.375rem;display:flex;align-items:center;gap:.625rem}
        .sg-card-hdr svg{width:18px;height:18px;color:#d97706}
        .sg-card-title{font-size:.9375rem;font-weight:700;color:#92400e;display:flex;align-items:center;gap:.5rem}
        .sg-card-title::before{content:'';width:4px;height:18px;border-radius:2px;background:linear-gradient(180deg,#f59e0b,#d97706)}
        .sg-card-body{padding:1rem 1.375rem}

        /* List */
        .sg-list{list-style:none;padding:0;margin:0}
        .sg-list-item{display:flex;align-items:center;gap:.75rem;padding:.75rem 0;border-bottom:1px solid #fef3c7}
        .sg-list-item:last-child{border-bottom:none}
        .sg-list-ico{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .sg-list-ico svg{width:18px;height:18px}
        .sg-list-ico.green{background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#059669}
        .sg-list-ico.blue{background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#2563eb}
        .sg-list-ico.amber{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706}
        .sg-list-info{flex:1;min-width:0}
        .sg-list-title{font-size:.8125rem;font-weight:600;color:#1f2937;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .sg-list-sub{font-size:.72rem;color:#9ca3af;margin-top:1px}
        .sg-list-val{font-size:.8125rem;font-weight:700;font-family:'JetBrains Mono',monospace;flex-shrink:0}
        .sg-list-val.green{color:#059669}
        .sg-list-val.amber{color:#d97706}
        .sg-list-val.blue{color:#2563eb}

        /* Loading */
        .sg-load-item{display:flex;align-items:center;justify-content:space-between;padding:.75rem 0;border-bottom:1px solid #fef3c7}
        .sg-load-item:last-child{border-bottom:none}
        .sg-load-name{font-size:.8125rem;font-weight:600;color:#1f2937}
        .sg-load-type{font-size:.6875rem;color:#9ca3af;margin-top:1px}
        .sg-load-stats{display:flex;gap:.5rem}
        .sg-load-badge{padding:.25rem .625rem;border-radius:8px;font-size:.72rem;font-weight:700;font-family:'JetBrains Mono',monospace}
        .sg-load-badge.load{background:#fef3c7;color:#92400e}
        .sg-load-badge.sold{background:#d1fae5;color:#065f46}
        .sg-load-badge.rest{background:#fee2e2;color:#991b1b}

        /* Setoran */
        .sg-setoran{display:flex;align-items:center;justify-content:space-between;padding:1.125rem 1.25rem;background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1px solid #fde68a;border-radius:14px}
        .sg-setoran-info{display:flex;flex-direction:column;gap:.25rem}
        .sg-setoran-lbl{font-size:.72rem;color:#92400e;font-weight:600}
        .sg-setoran-val{font-size:1.25rem;font-weight:800;color:#1f2937;font-family:'JetBrains Mono',monospace}
        .sg-setoran-badge{padding:.375rem .875rem;border-radius:99px;font-size:.75rem;font-weight:700}
        .sg-setoran-badge.pending{background:#fff;color:#92400e;border:1.5px solid #fcd34d}
        .sg-setoran-badge.terverifikasi{background:#d1fae5;color:#065f46;border:1.5px solid #6ee7b7}
        .sg-setoran-badge.ditolak{background:#fee2e2;color:#991b1b;border:1.5px solid #fca5a5}

        /* Empty */
        .sg-empty{text-align:center;padding:2rem;color:#9ca3af;font-size:.8125rem}
        .sg-empty svg{width:36px;height:36px;color:#d1d5db;margin:0 auto .5rem}

        /* Target bar */
        .sg-target{background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1px solid #fde68a;border-radius:16px;padding:1.25rem 1.5rem;margin-bottom:1.5rem}
        .sg-target-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem}
        .sg-target-lbl{font-size:.8125rem;font-weight:700;color:#92400e;display:flex;align-items:center;gap:.5rem}
        .sg-target-lbl svg{width:16px;height:16px;color:#d97706}
        .sg-target-vals{display:flex;gap:.75rem;align-items:baseline}
        .sg-target-current{font-size:1.125rem;font-weight:800;color:#d97706;font-family:'JetBrains Mono',monospace}
        .sg-target-goal{font-size:.8125rem;color:#9ca3af;font-family:'JetBrains Mono',monospace}
        .sg-target-track{height:8px;background:#fde68a;border-radius:99px;overflow:hidden;margin-top:.5rem}
        .sg-target-fill{height:100%;border-radius:99px;transition:width .5s}
        .sg-target-fill.low{background:linear-gradient(90deg,#f87171,#ef4444)}
        .sg-target-fill.mid{background:linear-gradient(90deg,#f59e0b,#d97706)}
        .sg-target-fill.high{background:linear-gradient(90deg,#10b981,#059669)}
        .sg-target-pct{font-size:.72rem;font-weight:700;margin-top:.375rem}
        .sg-target-pct.low{color:#ef4444}
        .sg-target-pct.mid{color:#d97706}
        .sg-target-pct.high{color:#059669}

        @media(max-width:1024px){.sg-kpis{grid-template-columns:repeat(2,1fr)}.sg-grid{grid-template-columns:1fr}}
        @media(max-width:768px){.sg-actions{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:640px){.sg-kpis{grid-template-columns:1fr}.sg-actions{grid-template-columns:1fr}.sg-hdr-title{font-size:1.25rem}}
    </style>
    @endpush

    <div class="py-4">
        <div class="sg-page">

            {{-- Header --}}
            <div class="sg-hdr">
                <div class="sg-hdr-l">
                    <div class="sg-hdr-ico">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <div class="sg-hdr-title">Dashboard Sales Gula</div>
                        <div class="sg-hdr-sub">Selamat datang di panel sales gula</div>
                    </div>
                </div>
                <div class="sg-hdr-right">
                    <div class="sg-hdr-name">{{ $salesProfile->nama }}</div>
                    <div class="sg-hdr-code">{{ $salesProfile->kode_sales }}</div>
                </div>
            </div>

            {{-- Profile Card --}}
            <div class="sg-profile">
                <div class="sg-profile-av">{{ strtoupper(substr($salesProfile->nama, 0, 1)) }}</div>
                <div class="sg-profile-info">
                    <div class="sg-profile-name">{{ $salesProfile->nama }}</div>
                    <div class="sg-profile-meta">
                        <span class="sg-profile-meta-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $salesProfile->no_hp ?? '-' }}
                        </span>
                        <span class="sg-profile-meta-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 4h4m-2 8H5a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ $salesProfile->no_kendaraan ?? '-' }}
                        </span>
                        <span class="sg-profile-meta-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $salesProfile->alamat ?? '-' }}
                        </span>
                    </div>
                </div>
                <span class="sg-profile-status {{ $salesProfile->status }}">
                    <span class="sg-dot {{ $salesProfile->status }}"></span>
                    {{ ucfirst($salesProfile->status) }}
                </span>
            </div>

            {{-- Target Penjualan --}}
            @if($salesProfile->target_harian > 0)
            @php
                $targetPct = $stats['penjualan_hari_ini'] > 0 ? min(($stats['penjualan_hari_ini'] / $salesProfile->target_harian) * 100, 100) : 0;
                $targetClass = $targetPct >= 80 ? 'high' : ($targetPct >= 40 ? 'mid' : 'low');
            @endphp
            <div class="sg-target">
                <div class="sg-target-head">
                    <span class="sg-target-lbl">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Target Penjualan Hari Ini
                    </span>
                    <div class="sg-target-vals">
                        <span class="sg-target-current sg-mono">Rp {{ number_format($stats['penjualan_hari_ini'], 0, ',', '.') }}</span>
                        <span class="sg-target-goal sg-mono">/ Rp {{ number_format($salesProfile->target_harian, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="sg-target-track">
                    <div class="sg-target-fill {{ $targetClass }}" style="width:{{ max($targetPct, 2) }}%"></div>
                </div>
                <div class="sg-target-pct {{ $targetClass }}">{{ number_format($targetPct, 1) }}% tercapai</div>
            </div>
            @endif

            {{-- Quick Actions --}}
            <div class="sg-actions">
                <a href="{{ route('gula.kunjungan.index') }}" class="sg-action">
                    <div class="sg-action-ico blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="sg-action-title">Check-in Kunjungan</div>
                    <div class="sg-action-sub">Catat kunjungan ke pelanggan</div>
                </a>
                <a href="{{ route('gula.penjualan.create') }}" class="sg-action">
                    <div class="sg-action-ico green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="sg-action-title">Input Penjualan</div>
                    <div class="sg-action-sub">Catat transaksi penjualan</div>
                </a>
                <a href="{{ route('gula.setoran.create') }}" class="sg-action">
                    <div class="sg-action-ico amber">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="sg-action-title">Input Setoran</div>
                    <div class="sg-action-sub">Setor hasil penjualan</div>
                </a>
                <a href="{{ route('gula.stok.index') }}" class="sg-action">
                    <div class="sg-action-ico purple">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div class="sg-action-title">Cek Stok</div>
                    <div class="sg-action-sub">Lihat stok kendaraan</div>
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="sg-kpis">
                <div class="sg-kpi amber">
                    <div class="sg-kpi-top">
                        <div>
                            <span class="sg-kpi-lbl">Penjualan Hari Ini</span>
                            <div class="sg-kpi-val amber sg-mono">Rp {{ number_format($stats['penjualan_hari_ini'], 0, ',', '.') }}</div>
                            <div class="sg-kpi-foot">Omzet hari ini</div>
                        </div>
                        <div class="sg-kpi-ico amber">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="sg-kpi green">
                    <div class="sg-kpi-top">
                        <div>
                            <span class="sg-kpi-lbl">Transaksi</span>
                            <div class="sg-kpi-val green">{{ $stats['transaksi_hari_ini'] }}</div>
                            <div class="sg-kpi-foot">Jumlah transaksi hari ini</div>
                        </div>
                        <div class="sg-kpi-ico green">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                    </div>
                </div>
                <div class="sg-kpi blue">
                    <div class="sg-kpi-top">
                        <div>
                            <span class="sg-kpi-lbl">Kunjungan</span>
                            <div class="sg-kpi-val blue">{{ $stats['kunjungan_hari_ini'] }}</div>
                            <div class="sg-kpi-foot">Kunjungan hari ini</div>
                        </div>
                        <div class="sg-kpi-ico blue">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="sg-kpi purple">
                    <div class="sg-kpi-top">
                        <div>
                            <span class="sg-kpi-lbl">Bulan Ini</span>
                            <div class="sg-kpi-val purple sg-mono">Rp {{ number_format($statsBulanIni['total_penjualan'], 0, ',', '.') }}</div>
                            <div class="sg-kpi-foot">{{ $statsBulanIni['total_transaksi'] }} transaksi bulan ini</div>
                        </div>
                        <div class="sg-kpi-ico purple">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content Grid --}}
            <div class="sg-grid">
                {{-- Loading Hari Ini --}}
                <div class="sg-card">
                    <div class="sg-card-hdr">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <div class="sg-card-title">Loading Hari Ini</div>
                    </div>
                    <div class="sg-card-body">
                        @if($loadingHariIni->count() > 0)
                            @foreach($loadingHariIni as $loading)
                            <div class="sg-load-item">
                                <div>
                                    <div class="sg-load-name">{{ $loading->produk->nama ?? '-' }}</div>
                                    <div class="sg-load-type">{{ $loading->produk->jenis ?? '-' }}</div>
                                </div>
                                <div class="sg-load-stats">
                                    <span class="sg-load-badge load sg-mono">{{ $loading->jumlah_loading }}L</span>
                                    <span class="sg-load-badge sold sg-mono">{{ $loading->terjual }}L</span>
                                    <span class="sg-load-badge rest sg-mono">{{ $loading->sisa_stok }}L</span>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="sg-empty">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <div>Belum ada loading hari ini</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Setoran Status --}}
                <div class="sg-card">
                    <div class="sg-card-hdr">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <div class="sg-card-title">Setoran Hari Ini</div>
                    </div>
                    <div class="sg-card-body">
                        @if($setoranHariIni)
                            <div class="sg-setoran">
                                <div class="sg-setoran-info">
                                    <span class="sg-setoran-lbl">Total Setoran</span>
                                    <span class="sg-setoran-val sg-mono">Rp {{ number_format($setoranHariIni->total_setor, 0, ',', '.') }}</span>
                                </div>
                                <span class="sg-setoran-badge {{ $setoranHariIni->status }}">
                                    {{ ucfirst($setoranHariIni->status) }}
                                </span>
                            </div>
                        @else
                            <div class="sg-empty">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <div>Belum ada setoran hari ini</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Kunjungan Terakhir --}}
                <div class="sg-card">
                    <div class="sg-card-hdr">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div class="sg-card-title">Kunjungan Terakhir</div>
                    </div>
                    <div class="sg-card-body">
                        @if($kunjunganTerakhir->count() > 0)
                            <ul class="sg-list">
                                @foreach($kunjunganTerakhir as $k)
                                <li class="sg-list-item">
                                    <div class="sg-list-ico {{ $k->waktu_checkout ? 'green' : 'amber' }}">
                                        @if($k->waktu_checkout)
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </div>
                                    <div class="sg-list-info">
                                        <div class="sg-list-title">{{ $k->pelanggan->nama_toko ?? '-' }}</div>
                                        <div class="sg-list-sub">{{ $k->waktu_checkin->format('d M Y H:i') }}</div>
                                    </div>
                                    <span class="sg-list-val {{ $k->waktu_checkout ? 'green' : 'amber' }}">
                                        {{ $k->waktu_checkout ? 'Selesai' : 'Aktif' }}
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="sg-empty">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <div>Belum ada kunjungan</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Penjualan Terakhir --}}
                <div class="sg-card">
                    <div class="sg-card-hdr">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <div class="sg-card-title">Penjualan Terakhir</div>
                    </div>
                    <div class="sg-card-body">
                        @if($penjualanTerakhir->count() > 0)
                            <ul class="sg-list">
                                @foreach($penjualanTerakhir as $p)
                                <li class="sg-list-item">
                                    <div class="sg-list-ico {{ $p->tipe_bayar == 'tunai' ? 'green' : 'blue' }}">
                                        @if($p->tipe_bayar == 'tunai')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        @else
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        @endif
                                    </div>
                                    <div class="sg-list-info">
                                        <div class="sg-list-title">{{ $p->pelanggan->nama_toko ?? '-' }}</div>
                                        <div class="sg-list-sub">{{ $p->tanggal_jual->format('d M Y') }} &bull; {{ $p->no_faktur }}</div>
                                    </div>
                                    <span class="sg-list-val amber sg-mono">Rp {{ number_format($p->total, 0, ',', '.') }}</span>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="sg-empty">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <div>Belum ada penjualan</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
