<x-app-layout>
    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .kn-wrap{font-family:'Plus Jakarta Sans',sans-serif}

        /* header */
        .kn-header{background:linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%);border:1px solid #bfdbfe;border-radius:20px;padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px}
        .kn-header-left{display:flex;align-items:center;gap:16px}
        .kn-header-icon{width:52px;height:52px;background:linear-gradient(135deg,#3b82f6,#2563eb);border-radius:16px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(37,99,235,.35)}
        .kn-header-icon svg{width:26px;height:26px;color:#fff}
        .kn-header h1{font-size:1.375rem;font-weight:800;color:#1f2937;margin:0}
        .kn-header p{font-size:.8rem;color:#1d4ed8;margin:2px 0 0}
        .kn-date-badge{font-size:.75rem;font-weight:600;color:#1d4ed8;background:#fff;border:1px solid #bfdbfe;padding:6px 14px;border-radius:10px;display:flex;align-items:center;gap:6px}
        .kn-date-badge svg{width:14px;height:14px;color:#3b82f6}
        .kn-hdr-btn{display:inline-flex;align-items:center;gap:.5rem;padding:.7rem 1.375rem;border-radius:12px;font-size:.8125rem;font-weight:600;text-decoration:none;transition:all .25s;border:none;cursor:pointer;background:linear-gradient(135deg,#10b981,#059669);color:#fff;box-shadow:0 6px 20px rgba(5,150,105,.35)}
        .kn-hdr-btn:hover{transform:translateY(-2px);box-shadow:0 10px 32px rgba(5,150,105,.45)}

        /* kpi */
        .kn-kpi-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px}
        .kn-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:20px;display:flex;align-items:flex-start;gap:14px;position:relative;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04);transition:box-shadow .2s}
        .kn-kpi:hover{box-shadow:0 4px 14px rgba(37,99,235,.1)}
        .kn-kpi::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px}
        .kn-kpi.blue::before{background:linear-gradient(180deg,#3b82f6,#2563eb)}
        .kn-kpi.green::before{background:linear-gradient(180deg,#10b981,#059669)}
        .kn-kpi.amber::before{background:linear-gradient(180deg,#f59e0b,#d97706)}
        .kn-kpi.purple::before{background:linear-gradient(180deg,#8b5cf6,#7c3aed)}
        .kn-kpi.teal::before{background:linear-gradient(180deg,#14b8a6,#0d9488)}
        .kn-kpi-icon{width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .kn-kpi-icon svg{width:22px;height:22px}
        .kn-kpi-icon.blue{background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#2563eb}
        .kn-kpi-icon.green{background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#059669}
        .kn-kpi-icon.amber{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706}
        .kn-kpi-icon.purple{background:linear-gradient(135deg,#ede9fe,#ddd6fe);color:#7c3aed}
        .kn-kpi-icon.teal{background:linear-gradient(135deg,#ccfbf1,#99f6e4);color:#0d9488}
        .kn-kpi-val{font-size:1.5rem;font-weight:800;color:#1f2937;line-height:1}
        .kn-kpi-lbl{font-size:.72rem;color:#6b7280;margin-top:4px;font-weight:500}

        /* filter */
        .kn-filter{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:16px 20px;margin-bottom:24px;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .kn-filter form{display:flex;flex-wrap:wrap;align-items:center;gap:10px}
        .kn-date-group{display:flex;align-items:center;gap:6px}
        .kn-date-lbl{font-size:.78rem;color:#1d4ed8;font-weight:600;white-space:nowrap}
        .kn-date-input{border:1.5px solid #bfdbfe;border-radius:12px;padding:9px 12px;font-size:.8125rem;background:#eff6ff;color:#1d4ed8;font-weight:500;outline:none;transition:border-color .2s;font-family:inherit}
        .kn-date-input:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
        .kn-filter select{border:1.5px solid #bfdbfe;border-radius:12px;padding:9px 14px;font-size:.8125rem;background:#eff6ff;color:#1d4ed8;font-weight:500;outline:none;min-width:150px;transition:border-color .2s;cursor:pointer;font-family:inherit}
        .kn-filter select:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
        .kn-btn-filter{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border:none;border-radius:12px;padding:9px 18px;font-size:.8125rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:7px;transition:opacity .2s;font-family:inherit}
        .kn-btn-filter:hover{opacity:.88}
        .kn-btn-filter svg{width:15px;height:15px}
        .kn-btn-reset{background:#eff6ff;color:#1d4ed8;border:1.5px solid #bfdbfe;border-radius:12px;padding:9px 16px;font-size:.8125rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:7px;text-decoration:none;transition:background .2s;font-family:inherit}
        .kn-btn-reset:hover{background:#dbeafe}
        .kn-btn-reset svg{width:15px;height:15px}

        /* chart */
        .kn-chart-card{background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:24px;margin-bottom:24px;box-shadow:0 2px 8px rgba(0,0,0,.05)}
        .kn-chart-title{font-size:.9rem;font-weight:700;color:#1f2937;margin-bottom:16px;display:flex;align-items:center;gap:8px}
        .kn-chart-title svg{width:18px;height:18px;color:#3b82f6}
        .kn-bar-row{display:flex;align-items:center;gap:12px;margin-bottom:10px}
        .kn-bar-name{width:120px;font-size:.78rem;font-weight:600;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;flex-shrink:0}
        .kn-bar-track{flex:1;height:28px;background:#eff6ff;border-radius:10px;overflow:hidden;position:relative}
        .kn-bar-fill{height:100%;background:linear-gradient(90deg,#3b82f6,#2563eb);border-radius:10px;display:flex;align-items:center;justify-content:flex-end;padding-right:10px;min-width:32px;transition:width .4s}
        .kn-bar-val{font-size:.72rem;font-weight:700;color:#fff}

        /* table */
        .kn-tbl-card{background:#fff;border:1px solid #e2e8f0;border-radius:20px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.05)}
        .kn-tbl-wrap{overflow-x:auto}
        .kn-tbl{width:100%;border-collapse:collapse}
        .kn-tbl thead th{background:linear-gradient(180deg,#eff6ff,#f0f7ff);border-bottom:2px solid #bfdbfe;padding:13px 16px;font-size:.7rem;font-weight:700;color:#1e40af;text-transform:uppercase;letter-spacing:.5px;white-space:nowrap}
        .kn-tbl tbody td{padding:14px 16px;border-bottom:1px solid #f1f5f9;font-size:.8125rem;color:#374151;vertical-align:middle}
        .kn-tbl tbody tr:last-child td{border-bottom:none}
        .kn-tbl tbody tr{transition:background .15s}
        .kn-tbl tbody tr:hover{background:#eff6ff}

        /* cells */
        .kn-time-main{font-weight:600;color:#1f2937;font-size:.8125rem}
        .kn-time-sub{font-size:.72rem;color:#6b7280;margin-top:2px}
        .kn-sales-cell{display:flex;align-items:center;gap:10px}
        .kn-sales-av{width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.72rem;flex-shrink:0}
        .kn-sales-nm{font-weight:600;color:#1f2937;font-size:.8125rem}
        .kn-toko-name{font-weight:600;color:#1f2937}
        .kn-toko-owner{font-size:.72rem;color:#6b7280;margin-top:1px}
        .kn-durasi{font-weight:600;color:#1f2937;font-size:.8125rem}
        .kn-durasi.ongoing{color:#d97706;font-size:.72rem;font-style:italic}

        /* status */
        .kn-status{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:99px;font-size:.7rem;font-weight:700;letter-spacing:.2px}
        .kn-status-dot{width:7px;height:7px;border-radius:50%}
        .kn-status.selesai{background:#d1fae5;color:#065f46}
        .kn-status.selesai .kn-status-dot{background:#10b981}
        .kn-status.berlangsung{background:#fef3c7;color:#92400e}
        .kn-status.berlangsung .kn-status-dot{background:#f59e0b;animation:kn-pulse 1.8s infinite}
        .kn-status.cancel{background:#fee2e2;color:#991b1b}
        .kn-status.cancel .kn-status-dot{background:#ef4444}
        @keyframes kn-pulse{0%,100%{opacity:1}50%{opacity:.35}}

        /* transaksi */
        .kn-trx{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:8px;font-size:.7rem;font-weight:600}
        .kn-trx.ada{background:#ecfdf5;color:#059669}
        .kn-trx.tidak{background:#f3f4f6;color:#6b7280}

        /* action */
        .kn-act{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:10px;font-size:.72rem;font-weight:600;text-decoration:none;transition:background .2s;border:none;cursor:pointer;background:#eff6ff;color:#2563eb}
        .kn-act:hover{background:#dbeafe}
        .kn-act svg{width:13px;height:13px}

        /* pagination */
        .kn-pagination{padding:16px 20px;border-top:1px solid #e2e8f0;background:linear-gradient(180deg,#eff6ff,#f0f7ff)}

        /* empty */
        .kn-empty{text-align:center;padding:60px 24px}
        .kn-empty-icon{width:80px;height:80px;margin:0 auto 18px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:24px;display:flex;align-items:center;justify-content:center}
        .kn-empty-icon svg{width:38px;height:38px;color:#3b82f6}
        .kn-empty h3{font-size:1rem;font-weight:700;color:#374151;margin:0 0 6px}
        .kn-empty p{font-size:.8125rem;color:#6b7280;margin:0}

        /* alerts */
        .kn-alert{display:flex;align-items:center;gap:.625rem;padding:.875rem 1.25rem;border-radius:12px;margin-bottom:1.25rem;font-size:.8125rem;font-weight:600}
        .kn-alert-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
        .kn-alert-error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}

        @media(max-width:1024px){.kn-kpi-grid{grid-template-columns:repeat(3,1fr)}}
        @media(max-width:640px){.kn-kpi-grid{grid-template-columns:repeat(2,1fr)}.kn-date-group{flex-direction:column;align-items:flex-start}}
    </style>
    @endpush

    <div class="kn-wrap" style="padding:24px">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="kn-alert kn-alert-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="kn-alert kn-alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="kn-header">
            <div class="kn-header-left">
                <div class="kn-header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h1>Monitoring Kunjungan</h1>
                    <p>Tracking kunjungan sales mineral</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="kn-date-badge">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} — {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d M Y') }}
                </div>
                @if($isSalesRole)
                <a href="{{ route('mineral.kunjungan.checkin') }}" class="kn-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Mulai Kunjungan
                </a>
                @endif
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="kn-kpi-grid">
            <div class="kn-kpi blue">
                <div class="kn-kpi-icon blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <div class="kn-kpi-val">{{ $stats['total_kunjungan'] }}</div>
                    <div class="kn-kpi-lbl">Total Kunjungan</div>
                </div>
            </div>
            <div class="kn-kpi green">
                <div class="kn-kpi-icon green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="kn-kpi-val">{{ $stats['kunjungan_selesai'] }}</div>
                    <div class="kn-kpi-lbl">Kunjungan Selesai</div>
                </div>
            </div>
            <div class="kn-kpi amber">
                <div class="kn-kpi-icon amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="kn-kpi-val">{{ $stats['kunjungan_bertransaksi'] }}</div>
                    <div class="kn-kpi-lbl">Ada Transaksi</div>
                </div>
            </div>
            <div class="kn-kpi purple">
                <div class="kn-kpi-icon purple">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div>
                    <div class="kn-kpi-val">{{ $stats['total_kunjungan'] > 0 ? round(($stats['kunjungan_bertransaksi'] / $stats['total_kunjungan']) * 100, 1) : 0 }}%</div>
                    <div class="kn-kpi-lbl">Conversion Rate</div>
                </div>
            </div>
            <div class="kn-kpi teal">
                <div class="kn-kpi-icon teal">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="kn-kpi-val">{{ $stats['durasi_rata_rata'] }}<span style="font-size:.7em;font-weight:600;"> mnt</span></div>
                    <div class="kn-kpi-lbl">Rata-rata Durasi</div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="kn-filter">
            <form method="GET">
                <div class="kn-date-group">
                    <span class="kn-date-lbl">Dari:</span>
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="kn-date-input">
                </div>
                <div class="kn-date-group">
                    <span class="kn-date-lbl">Sampai:</span>
                    <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" class="kn-date-input">
                </div>
                <select name="sales_id">
                    <option value="">Semua Sales</option>
                    @foreach($salesList as $s)
                        <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                    @endforeach
                </select>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="checkout" {{ request('status') == 'checkout' ? 'selected' : '' }}>Selesai</option>
                    <option value="checkin" {{ request('status') == 'checkin' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="kn-btn-filter">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                <a href="{{ route('mineral.kunjungan.index') }}" class="kn-btn-reset">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset
                </a>
            </form>
        </div>

        {{-- Chart --}}
        @if($kunjunganBySales->count() > 0)
        <div class="kn-chart-card">
            <div class="kn-chart-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Kunjungan per Sales
            </div>
            @php $maxVal = $kunjunganBySales->max('total'); @endphp
            @foreach($kunjunganBySales as $ks)
            @php $pct = $maxVal > 0 ? ($ks->total / $maxVal) * 100 : 0; @endphp
            <div class="kn-bar-row">
                <div class="kn-bar-name">{{ $ks->sales->nama ?? 'Unknown' }}</div>
                <div class="kn-bar-track">
                    <div class="kn-bar-fill" style="width:{{ max($pct, 8) }}%">
                        <span class="kn-bar-val">{{ $ks->total }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Table --}}
        <div class="kn-tbl-card">
            <div class="kn-tbl-wrap">
                <table class="kn-tbl">
                    <thead>
                        <tr>
                            <th style="text-align:left">Waktu Kunjungan</th>
                            <th style="text-align:left">Sales</th>
                            <th style="text-align:left">Pelanggan / Toko</th>
                            <th style="text-align:center">Durasi</th>
                            <th style="text-align:center">Status</th>
                            <th style="text-align:center">Transaksi</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjungans as $k)
                        @php
                            $durasi = $k->waktu_checkout ? $k->waktu_checkin->diffInMinutes($k->waktu_checkout) : null;
                            $isSelesai = (bool)$k->waktu_checkout;
                            $isCancelled = $k->status === 'cancel';
                        @endphp
                        <tr>
                            <td>
                                <div class="kn-time-main">{{ $k->waktu_checkin->format('d M Y') }}</div>
                                <div class="kn-time-sub">{{ $k->waktu_checkin->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="kn-sales-cell">
                                    <div class="kn-sales-av">{{ strtoupper(substr($k->sales->nama ?? '?', 0, 1)) }}</div>
                                    <span class="kn-sales-nm">{{ $k->sales->nama ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="kn-toko-name">{{ $k->pelanggan->nama_toko ?? '-' }}</div>
                                <div class="kn-toko-owner">Pemilik: {{ $k->pelanggan->nama_pemilik ?? '-' }}</div>
                            </td>
                            <td style="text-align:center">
                                @if($isCancelled)
                                    <span class="kn-durasi ongoing">Dibatalkan</span>
                                @elseif($durasi)
                                    <span class="kn-durasi">{{ $durasi }} menit</span>
                                @else
                                    <span class="kn-durasi ongoing">Sedang berlangsung</span>
                                @endif
                            </td>
                            <td style="text-align:center">
                                @if($isCancelled)
                                    <span class="kn-status cancel">
                                        <span class="kn-status-dot"></span>
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="kn-status {{ $isSelesai ? 'selesai' : 'berlangsung' }}">
                                        <span class="kn-status-dot"></span>
                                        {{ $isSelesai ? 'Selesai' : 'Berlangsung' }}
                                    </span>
                                @endif
                            </td>
                            <td style="text-align:center">
                                @if($k->ada_penjualan && !$isCancelled)
                                    <span class="kn-trx ada">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Ada
                                    </span>
                                @else
                                    <span class="kn-trx tidak">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Tidak
                                    </span>
                                @endif
                            </td>
                            <td style="text-align:center">
                                <a href="{{ route('mineral.kunjungan.show', $k) }}" class="kn-act">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="kn-empty">
                                    <div class="kn-empty-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <h3>Tidak Ada Data Kunjungan</h3>
                                    <p>Belum ada data kunjungan pada rentang tanggal yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($kunjungans->hasPages())
            <div class="kn-pagination">
                {{ $kunjungans->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
