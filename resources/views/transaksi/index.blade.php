<x-app-layout>
    <x-slot name="header">Transaksi Penjualan</x-slot>

    @push('styles')
    <style>
        :root{--tx-radius:12px;--tx-radius-sm:8px;--tx-bg:#fafafa;--tx-surface:#fff;--tx-border:#e5e7eb;--tx-text:#111827;--tx-muted:#6b7280;--tx-emerald:#059669;--tx-blue:#3b82f6;--tx-amber:#d97706;--tx-red:#dc2626;--tx-indigo:#4f46e5;}
        .tx-page{max-width:82rem;margin:0 auto;padding:1.5rem 1rem 3rem;font-family:'Segoe UI',system-ui,sans-serif;}

        /* ── Header ── */
        .tx-hdr{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
        .tx-eyebrow{font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--tx-emerald);margin-bottom:0.3rem;}
        .tx-title{font-size:1.375rem;font-weight:800;color:var(--tx-text);margin:0;display:flex;align-items:center;gap:0.5rem;}
        .tx-title svg{width:20px;height:20px;color:var(--tx-emerald);}
        .tx-sub{font-size:0.8rem;color:var(--tx-muted);margin-top:0.25rem;}
        .tx-btn{display:inline-flex;align-items:center;gap:0.4rem;padding:0.55rem 1rem;border-radius:var(--tx-radius-sm);font-size:0.8rem;font-weight:600;cursor:pointer;transition:all .15s;border:none;text-decoration:none;white-space:nowrap;}
        .tx-btn svg{width:16px;height:16px;}
        .tx-btn-primary{background:var(--tx-indigo);color:#fff;}
        .tx-btn-primary:hover{background:#4338ca;}
        .tx-btn-dark{background:var(--tx-text);color:#fff;}
        .tx-btn-dark:hover{background:#000;}
        .tx-btn-ghost{background:transparent;color:var(--tx-muted);border:1px solid var(--tx-border);}
        .tx-btn-ghost:hover{background:#f3f4f6;color:var(--tx-text);}
        .tx-btn-sm{padding:0.4rem 0.7rem;font-size:0.72rem;}

        /* ── Alert ── */
        .tx-alert{padding:0.6rem 1rem;border-radius:var(--tx-radius-sm);font-size:0.78rem;font-weight:600;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;}
        .tx-alert-ok{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;}
        .tx-alert-err{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
        .tx-alert svg{width:15px;height:15px;flex-shrink:0;}

        /* ── Stat Cards ── */
        .tx-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;}
        .tx-stat{background:var(--tx-surface);border:1px solid var(--tx-border);border-radius:var(--tx-radius);padding:1rem 1.25rem;position:relative;overflow:hidden;}
        .tx-stat::before{content:'';position:absolute;top:0;left:0;width:4px;height:100%;border-radius:4px 0 0 4px;}
        .tx-stat.green::before{background:var(--tx-emerald);}
        .tx-stat.blue::before{background:var(--tx-blue);}
        .tx-stat.purple::before{background:var(--tx-indigo);}
        .tx-stat.amber::before{background:var(--tx-amber);}
        .tx-stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.65rem;}
        .tx-stat-ico{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;}
        .tx-stat-ico svg{width:18px;height:18px;}
        .tx-stat.green .tx-stat-ico{background:#f0fdf4;color:var(--tx-emerald);}
        .tx-stat.blue .tx-stat-ico{background:#eff6ff;color:var(--tx-blue);}
        .tx-stat.purple .tx-stat-ico{background:#eef2ff;color:var(--tx-indigo);}
        .tx-stat.amber .tx-stat-ico{background:#fffbeb;color:var(--tx-amber);}
        .tx-stat-badge{font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;padding:0.2rem 0.5rem;border-radius:999px;}
        .tx-stat.green .tx-stat-badge{background:#f0fdf4;color:#065f46;}
        .tx-stat.blue .tx-stat-badge{background:#f1f5f9;color:#64748b;}
        .tx-stat.purple .tx-stat-badge{background:#eef2ff;color:#4338ca;}
        .tx-stat.amber .tx-stat-badge{background:#fffbeb;color:#92400e;}
        .tx-stat-lbl{font-size:0.68rem;font-weight:600;color:var(--tx-muted);text-transform:uppercase;letter-spacing:0.04em;}
        .tx-stat-val{font-size:1.25rem;font-weight:800;font-family:'Cascadia Code','Fira Code',monospace;letter-spacing:-0.02em;margin-top:0.15rem;}
        .tx-stat.green .tx-stat-val{color:var(--tx-emerald);}
        .tx-stat.blue .tx-stat-val{color:var(--tx-blue);}
        .tx-stat.purple .tx-stat-val{color:var(--tx-indigo);}
        .tx-stat.amber .tx-stat-val{color:var(--tx-amber);}

        /* ── Card ── */
        .tx-card{background:var(--tx-surface);border:1px solid var(--tx-border);border-radius:var(--tx-radius);overflow:hidden;}
        .tx-card-hdr{padding:1rem 1.25rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.75rem;}
        .tx-card-title{font-size:0.9375rem;font-weight:700;color:var(--tx-text);}
        .tx-card-sub{font-size:0.75rem;color:var(--tx-muted);margin-top:0.15rem;}

        /* ── Filter ── */
        .tx-filter{padding:0.875rem 1.25rem;background:#fafafa;border-bottom:1px solid #f3f4f6;border-top:1px solid #f3f4f6;}
        .tx-filter-form{display:flex;flex-wrap:wrap;gap:0.65rem;align-items:flex-end;}
        .tx-fg{display:flex;flex-direction:column;gap:0.25rem;}
        .tx-fl{font-size:0.65rem;font-weight:700;text-transform:uppercase;color:var(--tx-muted);letter-spacing:0.04em;}
        .tx-fi{padding:0.45rem 0.65rem;border:1.5px solid var(--tx-border);border-radius:var(--tx-radius-sm);font-size:0.78rem;font-family:inherit;transition:all .15s;background:#fff;color:var(--tx-text);}
        .tx-fi:focus{outline:none;border-color:var(--tx-indigo);box-shadow:0 0 0 3px rgba(79,70,229,0.08);}
        select.tx-fi{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 0.5rem center;padding-right:1.5rem;cursor:pointer;}
        .tx-fi-search{min-width:160px;flex:1;max-width:220px;}

        /* ── Table ── */
        .tx-tbl-wrap{overflow-x:auto;}
        .tx-tbl{width:100%;border-collapse:collapse;font-size:0.78rem;min-width:950px;}
        .tx-tbl th{text-align:left;padding:0.65rem 0.875rem;font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#9ca3af;border-bottom:2px solid #f3f4f6;white-space:nowrap;}
        .tx-tbl td{padding:0.7rem 0.875rem;border-bottom:1px solid #f9fafb;color:#374151;vertical-align:middle;}
        .tx-tbl tr:last-child td{border-bottom:none;}
        .tx-tbl tr:hover{background:#fafafa;}
        .tx-tbl .c{text-align:center;}
        .tx-tbl .r{text-align:right;}
        .tx-mono{font-family:'Cascadia Code','Fira Code',monospace;font-size:0.73rem;}

        /* Voided rows */
        .tx-voided td{opacity:0.45;background:#fafafa;text-decoration:line-through;}
        .tx-voided td .tx-badge,.tx-voided td .tx-inv{display:inline-block;text-decoration:none;}

        /* Invoice badge */
        .tx-inv{font-family:'Cascadia Code','Fira Code',monospace;font-weight:700;font-size:0.78rem;color:var(--tx-indigo);background:#eef2ff;padding:0.15rem 0.45rem;border-radius:5px;}

        /* Badges */
        .tx-badge{display:inline-flex;align-items:center;gap:0.2rem;padding:0.15rem 0.5rem;border-radius:999px;font-size:0.63rem;font-weight:700;white-space:nowrap;}
        .tx-badge svg{width:10px;height:10px;}
        .tx-badge-success{background:#d1fae5;color:#065f46;}
        .tx-badge-danger{background:#fee2e2;color:#991b1b;}
        .tx-badge-blue{background:#dbeafe;color:#1e40af;}
        .tx-badge-purple{background:#f3e8ff;color:#6b21a8;}
        .tx-badge-teal{background:#ccfbf1;color:#0f766e;}
        .tx-badge-gray{background:#f3f4f6;color:#6b7280;}
        .tx-badge-amber{background:#fef3c7;color:#92400e;}

        /* Method label */
        .tx-method{font-weight:600;font-size:0.72rem;color:var(--tx-muted);text-transform:uppercase;letter-spacing:0.03em;}

        /* Action buttons */
        .tx-actions{display:flex;gap:0.35rem;justify-content:center;}
        .tx-action{width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--tx-border);background:#fff;color:var(--tx-muted);transition:all .15s;cursor:pointer;text-decoration:none;}
        .tx-action svg{width:13px;height:13px;}
        .tx-action.view:hover{color:var(--tx-indigo);border-color:var(--tx-indigo);background:#eef2ff;}
        .tx-action.print:hover{color:var(--tx-emerald);border-color:var(--tx-emerald);background:#f0fdf4;}
        .tx-action.retur:hover{color:var(--tx-amber);border-color:var(--tx-amber);background:#fffbeb;}
        .tx-action.void-action:hover{color:var(--tx-red);border-color:var(--tx-red);background:#fef2f2;}

        /* Date cell */
        .tx-date{font-weight:600;font-size:0.78rem;color:var(--tx-text);}
        .tx-time{font-size:0.68rem;color:var(--tx-muted);margin-top:1px;}

        /* Amount cells */
        .tx-amt{font-family:'Cascadia Code','Fira Code',monospace;font-weight:700;font-size:0.78rem;white-space:nowrap;}
        .tx-amt-main{color:var(--tx-text);}
        .tx-amt-paid{color:var(--tx-emerald);}
        .tx-amt-change{color:var(--tx-amber);}

        /* Kasir cell */
        .tx-kasir{font-weight:600;color:var(--tx-text);font-size:0.78rem;}

        /* Print badge */
        .tx-print{font-size:0.63rem;font-weight:700;}

        /* Empty state */
        .tx-empty{text-align:center;padding:3rem 1rem;}
        .tx-empty-ico{width:48px;height:48px;margin:0 auto 0.75rem;background:#f3f4f6;border-radius:14px;display:flex;align-items:center;justify-content:center;}
        .tx-empty-ico svg{width:24px;height:24px;color:#9ca3af;}
        .tx-empty-title{font-size:0.9375rem;font-weight:700;color:#6b7280;}
        .tx-empty-sub{font-size:0.8rem;color:#9ca3af;margin-top:0.2rem;}

        .tx-pagination{padding:1rem 1.25rem;display:flex;justify-content:center;}

        /* Driver info */
        .tx-driver{font-size:0.63rem;color:#9ca3af;margin-top:0.2rem;}

        @media(max-width:1024px){
            .tx-stats{grid-template-columns:1fr 1fr;}
        }
        @media(max-width:768px){
            .tx-stats{grid-template-columns:1fr;}
            .tx-filter-form{flex-direction:column;}
            .tx-fi-search{max-width:100%;}
            .tx-fg{width:100%;}
            .tx-fi,.tx-fg select{width:100%;}
            .tx-hdr{flex-direction:column;align-items:flex-start;}
        }
    </style>
    @endpush

    <div class="tx-page">
        {{-- Alerts --}}
        @if(session('success'))
            <div class="tx-alert tx-alert-ok">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="tx-alert tx-alert-err">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="tx-hdr">
            <div>
                <div class="tx-eyebrow">Manajemen Kasir</div>
                <h1 class="tx-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Transaksi Penjualan
                </h1>
                <p class="tx-sub">Pantau riwayat seluruh transaksi kasir, detail pendapatan, dan cetak ulang struk.</p>
            </div>
            <a href="{{ route('kasir.index') }}" class="tx-btn tx-btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                Buka Layar Kasir
            </a>
        </div>

        {{-- Stat Cards --}}
        <div class="tx-stats">
            <div class="tx-stat green">
                <div class="tx-stat-top">
                    <div class="tx-stat-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <span class="tx-stat-badge">Hari Ini</span>
                </div>
                <div class="tx-stat-lbl">Pendapatan Hari Ini</div>
                <div class="tx-stat-val">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="tx-stat blue">
                <div class="tx-stat-top">
                    <div class="tx-stat-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span class="tx-stat-badge">Hari Ini</span>
                </div>
                <div class="tx-stat-lbl">Transaksi Sukses</div>
                <div class="tx-stat-val">{{ $todayCount }} <span style="font-size:0.7rem;color:var(--tx-muted);font-weight:600;">Nota</span></div>
            </div>
            <div class="tx-stat purple">
                <div class="tx-stat-top">
                    <div class="tx-stat-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
                    </div>
                    <span class="tx-stat-badge">Filter Aktif</span>
                </div>
                <div class="tx-stat-lbl">Total Pendapatan</div>
                <div class="tx-stat-val">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="tx-stat amber">
                <div class="tx-stat-top">
                    <div class="tx-stat-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    </div>
                    <span class="tx-stat-badge">Filter Aktif</span>
                </div>
                <div class="tx-stat-lbl">Total Transaksi</div>
                <div class="tx-stat-val">{{ $totalCount }} <span style="font-size:0.7rem;color:var(--tx-muted);font-weight:600;">Nota</span></div>
            </div>
        </div>

        {{-- Data Card --}}
        <div class="tx-card">
            <div class="tx-card-hdr">
                <div>
                    <div class="tx-card-title">Riwayat Transaksi</div>
                    <div class="tx-card-sub">Menampilkan <strong>{{ $transactions->total() }}</strong> transaksi.</div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="tx-filter">
                <form method="GET" class="tx-filter-form">
                    <div class="tx-fg">
                        <label class="tx-fl">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice, kasir, pelanggan..." class="tx-fi tx-fi-search">
                    </div>
                    <div class="tx-fg" style="width:130px;">
                        <label class="tx-fl">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="tx-fi">
                    </div>
                    <div class="tx-fg" style="width:130px;">
                        <label class="tx-fl">Sampai</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="tx-fi">
                    </div>
                    <div class="tx-fg" style="width:120px;">
                        <label class="tx-fl">Jenis</label>
                        <select name="sale_type" class="tx-fi">
                            <option value="">Semua</option>
                            <option value="eceran" @selected(request('sale_type')=='eceran')>Eceran</option>
                            <option value="grosir" @selected(request('sale_type')=='grosir')>Grosir</option>
                        </select>
                    </div>
                    <div class="tx-fg" style="width:120px;">
                        <label class="tx-fl">Metode</label>
                        <select name="payment_method" class="tx-fi">
                            <option value="">Semua</option>
                            <option value="cash" @selected(request('payment_method')=='cash')>Tunai</option>
                            <option value="transfer" @selected(request('payment_method')=='transfer')>Transfer</option>
                            <option value="qris" @selected(request('payment_method')=='qris')>QRIS</option>
                            <option value="kredit" @selected(request('payment_method')=='kredit')>Kredit</option>
                        </select>
                    </div>
                    <div class="tx-fg" style="width:120px;">
                        <label class="tx-fl">Status</label>
                        <select name="status" class="tx-fi">
                            <option value="">Semua</option>
                            <option value="completed" @selected(request('status')=='completed')>Selesai</option>
                            <option value="voided" @selected(request('status')=='voided')>Void</option>
                        </select>
                    </div>
                    <div style="display:flex;gap:0.4rem;align-self:flex-end;">
                        <button type="submit" class="tx-btn tx-btn-dark tx-btn-sm">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            Filter
                        </button>
                        <a href="{{ route('transaksi.index') }}" class="tx-btn tx-btn-ghost tx-btn-sm">Reset</a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="tx-tbl-wrap">
                <table class="tx-tbl">
                    <thead>
                        <tr>
                            <th style="width:36px;">#</th>
                            <th>No. Transaksi</th>
                            <th>Waktu</th>
                            <th>Kasir</th>
                            <th class="c">Jenis</th>
                            <th class="c">Item</th>
                            <th>Bayar</th>
                            <th class="r">Total</th>
                            <th class="r">Diterima</th>
                            <th class="r">Kembali</th>
                            <th class="c">Status</th>
                            <th class="c">Cetak</th>
                            <th class="c" style="width:90px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $i => $trx)
                        @php
                            $isVoided = $trx->status === 'voided';
                            $grandTotal = $trx->grand_total;
                            $totalPaid = $trx->total_paid;
                            $changeAmt = $totalPaid - $grandTotal;
                            $totalItems = $trx->details->count();
                            foreach ($trx->additionalTransactions as $at) {
                                $totalItems += $at->details->count();
                            }
                            $methodLabel = match($trx->payment_method) {
                                'cash' => 'Tunai',
                                'transfer' => 'Transfer',
                                'qris' => 'QRIS',
                                'kredit' => 'Kredit',
                                default => ucfirst($trx->payment_method ?? '-'),
                            };
                        @endphp
                        <tr class="{{ $isVoided ? 'tx-voided' : '' }}">
                            <td class="tx-mono" style="color:#9ca3af;">{{ $transactions->firstItem() + $i }}</td>
                            <td>
                                <span class="tx-inv">#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</span>
                                @if($trx->hasAdditionalItems())
                                    <div style="margin-top:3px;">
                                        <span class="tx-badge tx-badge-amber">+{{ $trx->additionalTransactions->count() }} Tam</span>
                                    </div>
                                @endif
                                @if($trx->vehicle_id || $trx->driver_name)
                                    <div class="tx-driver">
                                        {{ $trx->vehicle?->license_plate ?? '' }}
                                        @if($trx->driver_name) {{ $trx->driver_name }} @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="tx-date">{{ $trx->created_at->format('d M Y') }}</div>
                                <div class="tx-time">{{ $trx->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="tx-kasir">{{ $trx->user?->name ?? '-' }}</div>
                                @if($trx->customer?->name)
                                    <span class="tx-badge {{ $trx->customer->category === 'grosir' ? 'tx-badge-purple' : 'tx-badge-teal' }}" style="margin-top:3px;">
                                        {{ $trx->customer->name }}
                                    </span>
                                @elseif($trx->customer?->category)
                                    <span class="tx-badge {{ $trx->customer->category === 'grosir' ? 'tx-badge-purple' : 'tx-badge-teal' }}" style="margin-top:3px;">
                                        {{ ucfirst($trx->customer->category) }}
                                    </span>
                                @endif
                            </td>
                            <td class="c">
                                @if($trx->sale_type === 'grosir')
                                    <span class="tx-badge tx-badge-purple">Grosir</span>
                                @elseif($trx->sale_type === 'eceran')
                                    <span class="tx-badge tx-badge-blue">Eceran</span>
                                @else
                                    <span style="color:#9ca3af;">-</span>
                                @endif
                            </td>
                            <td class="c">
                                <span class="tx-badge tx-badge-blue">{{ $totalItems }} pcs</span>
                            </td>
                            <td>
                                <span class="tx-method">{{ $methodLabel }}</span>
                            </td>
                            <td class="r">
                                <span class="tx-amt tx-amt-main">{{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </td>
                            <td class="r">
                                <span class="tx-amt tx-amt-paid">{{ number_format($totalPaid, 0, ',', '.') }}</span>
                            </td>
                            <td class="r">
                                <span class="tx-amt tx-amt-change">{{ number_format(max(0, $changeAmt), 0, ',', '.') }}</span>
                            </td>
                            <td class="c">
                                @if($trx->status === 'completed')
                                    <span class="tx-badge tx-badge-success">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        Selesai
                                    </span>
                                @elseif($isVoided)
                                    <span class="tx-badge tx-badge-danger">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        Void
                                    </span>
                                @else
                                    <span class="tx-badge tx-badge-gray">{{ $trx->status }}</span>
                                @endif
                            </td>
                            <td class="c">
                                @if($trx->print_count > 0)
                                    <span class="tx-badge tx-badge-success tx-print" title="Dicetak {{ $trx->print_count }}x, terakhir: {{ $trx->last_printed_at?->format('d/m/Y H:i') }}">
                                        {{ $trx->print_count }}x
                                    </span>
                                @else
                                    <span class="tx-badge tx-badge-gray tx-print">-</span>
                                @endif
                            </td>
                            <td class="c">
                                <div class="tx-actions">
                                    <a href="{{ route('transaksi.show', $trx) }}" class="tx-action view" title="Detail">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    @if($trx->status === 'completed')
                                        <a href="{{ route('print.receipt', $trx->id) }}" target="_blank" class="tx-action print" title="Cetak Struk">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                        </a>
                                        @can('edit_transaksi')
                                        <a href="{{ route('transaksi.retur.create', $trx) }}" class="tx-action retur" title="Retur">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                        </a>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13">
                                <div class="tx-empty">
                                    <div class="tx-empty-ico">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    </div>
                                    <div class="tx-empty-title">Tidak Ada Transaksi</div>
                                    <div class="tx-empty-sub">Belum ada data transaksi atau filter tidak cocok.</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="tx-pagination">{{ $transactions->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
