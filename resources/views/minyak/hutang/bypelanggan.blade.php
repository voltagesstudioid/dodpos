<x-app-layout>
    <x-slot name="header">Hutang -- {{ $pelanggan->nama_toko ?? 'N/A' }}</x-slot>

    @push('styles')
    <style>
        .bp { max-width:76rem; margin:0 auto; padding:1.25rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .bp-hdr { display:flex; align-items:center; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .bp-hdr-l { display:flex; align-items:center; gap:1rem; flex:1; }
        .bp-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#ef4444,#dc2626);
            box-shadow:0 8px 24px rgba(220,38,38,0.3);
        }
        .bp-hdr-title { font-size:1.375rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .bp-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .bp-hdr-acts { display:flex; gap:0.5rem; flex-wrap:wrap; }
        .bp-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.65rem 1.25rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s; border:none; cursor:pointer; font-family:inherit;
        }
        .bp-btn-secondary { background:#f1f5f9; color:#475569; border:1.5px solid #e2e8f0; }
        .bp-btn-secondary:hover { background:#e2e8f0; color:#334155; }

        .bp-kpis { display:grid; grid-template-columns:repeat(4,1fr); gap:0.875rem; margin-bottom:1.5rem; }
        .bp-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.25rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .bp-kpi::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .bp-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .bp-kpi.red::before { background:linear-gradient(90deg,#ef4444,#dc2626); }
        .bp-kpi.amber::before { background:linear-gradient(90deg,#f59e0b,#d97706); }
        .bp-kpi.green::before { background:linear-gradient(90deg,#10b981,#059669); }
        .bp-kpi.blue::before { background:linear-gradient(90deg,#3b82f6,#2563eb); }
        .bp-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem; }
        .bp-kpi-lbl { font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .bp-kpi-ico { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; }
        .bp-kpi-ico.red { background:linear-gradient(135deg,#fff1f2,#ffe4e6); }
        .bp-kpi-ico.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .bp-kpi-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .bp-kpi-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .bp-kpi-val { font-size:1.375rem; font-weight:800; letter-spacing:-0.02em; line-height:1; }
        .bp-kpi-val.red { color:#dc2626; }
        .bp-kpi-val.amber { color:#d97706; }
        .bp-kpi-val.green { color:#059669; }
        .bp-kpi-val.blue { color:#2563eb; }
        .bp-kpi-foot { font-size:0.7rem; color:#94a3b8; margin-top:0.25rem; }

        .bp-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04); margin-bottom:1.25rem;
        }
        .bp-card-hdr {
            padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;
            display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;
        }
        .bp-card-title { font-size:1rem; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:0.5rem; }
        .bp-card-sub { font-size:0.8rem; color:#64748b; margin-top:0.25rem; }
        .bp-card-count {
            display:inline-flex; align-items:center; gap:0.375rem;
            padding:0.25rem 0.75rem; border-radius:999px; font-size:0.6875rem; font-weight:700;
            background:#f1f5f9; color:#475569; white-space:nowrap;
        }

        .bp-tblwrap { overflow-x:auto; }
        .bp-tbl { width:100%; border-collapse:separate; border-spacing:0; min-width:650px; }
        .bp-tbl thead { background:linear-gradient(180deg,#fef2f2,#fff1f2); border-bottom:2px solid #fecaca; }
        .bp-tbl th {
            padding:0.875rem 1rem; font-size:0.65rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#991b1b; white-space:nowrap; text-align:left;
        }
        .bp-tbl td {
            padding:0.875rem 1rem; border-bottom:1px solid #fef2f2; font-size:0.8rem;
            color:#374151; vertical-align:middle;
        }
        .bp-tbl tbody tr { transition:background 0.15s; }
        .bp-tbl tbody tr:last-child td { border-bottom:none; }
        .bp-tbl tbody tr:hover td { background:linear-gradient(90deg,#fff1f2,#ffe4e6); }

        .bp-badge {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.25rem 0.625rem; border-radius:999px; font-size:0.65rem; font-weight:700; white-space:nowrap;
        }
        .bp-badge.unpaid { background:#fef3c7; color:#92400e; }
        .bp-badge.overdue { background:#fef2f2; color:#991b1b; }
        .bp-badge.paid { background:#d1fae5; color:#065f46; }

        .bp-amt { font-size:0.875rem; font-weight:700; color:#0f172a; letter-spacing:-0.01em; }
        .bp-amt.green { color:#059669; }
        .bp-amt.red { color:#dc2626; }

        .bp-progress { background:#f1f5f9; border-radius:999px; height:6px; overflow:hidden; min-width:80px; }
        .bp-progress-fill { height:100%; border-radius:999px; transition:width 0.5s ease; }

        .bp-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.375rem 0.75rem; border-radius:8px; font-size:0.65rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
        }
        .bp-act-view { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .bp-act-view:hover { background:#dbeafe; border-color:#93c5fd; }

        .bp-empty { text-align:center; padding:3rem 1.5rem; }
        .bp-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#d1fae5,#a7f3d0); display:flex; align-items:center; justify-content:center;
        }
        .bp-empty-title { font-size:1rem; font-weight:700; color:#065f46; margin-bottom:0.25rem; }
        .bp-empty-sub { font-size:0.8125rem; color:#94a3b8; }

        .bp-flash {
            margin-bottom:1rem; padding:0.75rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            display:flex; align-items:center; gap:0.5rem;
        }
        .bp-flash.success { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; }
        .bp-flash.error { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; }

        .bp-form { padding:1.5rem; }
        .bp-form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .bp-fld { margin-bottom:1rem; }
        .bp-fld-full { grid-column:1/-1; }
        .bp-lbl { display:block; font-size:0.8125rem; font-weight:600; color:#334155; margin-bottom:0.375rem; }
        .bp-req { color:#dc2626; }
        .bp-money {
            display:flex; align-items:stretch; border:1.5px solid #e2e8f0; border-radius:10px;
            overflow:hidden; transition:all 0.2s; background:#fff;
        }
        .bp-money:focus-within { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
        .bp-money-pfx {
            display:flex; align-items:center; padding:0 0.875rem;
            background:#f8fafc; color:#64748b; font-weight:700; font-size:0.8125rem;
            border-right:1px solid #e2e8f0; white-space:nowrap;
        }
        .bp-money-inp {
            flex:1; padding:0.625rem 0.875rem; border:none; font-size:0.875rem;
            font-weight:700; font-family:inherit; outline:none; min-width:0;
        }

        .bp-inp {
            width:100%; padding:0.625rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px;
            font-size:0.8125rem; font-family:inherit; transition:all 0.2s; box-sizing:border-box;
        }
        .bp-inp:focus { outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
        select.bp-inp { cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.75rem center; padding-right:2.25rem; }

        .bp-btn-primary {
            display:inline-flex; align-items:center; justify-content:center; gap:0.5rem;
            padding:0.75rem 2rem; border:none; border-radius:12px;
            font-size:0.875rem; font-weight:700; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 4px 16px rgba(37,99,235,0.3);
        }
        .bp-btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(37,99,235,0.4); }
        .bp-btn-primary:disabled { opacity:0.5; cursor:not-allowed; transform:none; }

        .bp-photo-box {
            border:2px dashed #e2e8f0; border-radius:12px; padding:1.5rem;
            text-align:center; cursor:pointer; transition:all 0.2s; margin-bottom:0.75rem;
        }
        .bp-photo-box:hover { border-color:#3b82f6; background:#f8fafc; }
        .bp-photo-box img { max-width:100%; max-height:200px; border-radius:8px; margin:0 auto; display:none; }
        .bp-photo-empty { color:#94a3b8; font-size:0.75rem; }
        .bp-photo-empty-ico { width:48px; height:48px; margin:0 auto 0.5rem; border-radius:50%; display:flex; align-items:center; justify-content:center; }

        .bp-pending-note {
            margin-top:0.75rem; padding:0.75rem 1rem; background:#fffbeb; border:1px solid #fde68a;
            border-radius:10px; font-size:0.75rem; color:#92400e; line-height:1.5;
        }

        @media(max-width:1024px){.bp-kpis{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:768px){
            .bp-hdr-title{font-size:1.125rem}.bp-hdr-ico{width:44px;height:44px;font-size:1.25rem}
            .bp-kpi{padding:1rem 1.125rem}.bp-kpi-val{font-size:1.125rem}
            .bp-form-grid{grid-template-columns:1fr}
        }
        @media(max-width:640px){
            .bp{padding:1rem 0.75rem 2rem}
            .bp-kpis{grid-template-columns:1fr;gap:0.625rem}
            .bp-hdr-acts{width:100%}.bp-btn{flex:1;justify-content:center}
        }
    </style>
    @endpush

    <div class="bp">

        {{-- Header --}}
        <div class="bp-hdr">
            <div class="bp-hdr-l">
                <div class="bp-hdr-ico">💳</div>
                <div>
                    <div class="bp-hdr-title">{{ $pelanggan->nama_toko }}</div>
                    <div class="bp-hdr-sub">{{ $pelanggan->nama_pemilik }} &middot; {{ $pelanggan->alamat ?? '-' }}</div>
                </div>
            </div>
            <div class="bp-hdr-acts">
                <a href="{{ route('minyak.hutang.total') }}" class="bp-btn bp-btn-secondary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Total Piutang
                </a>
                <a href="{{ route('minyak.hutang.piutang', ['pelanggan_id' => $pelanggan->id]) }}" class="bp-btn bp-btn-secondary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Daftar Piutang
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bp-flash success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bp-flash error">❌ {{ session('error') }}</div>
        @endif

        {{-- KPI --}}
        <div class="bp-kpis">
            <div class="bp-kpi red">
                <div class="bp-kpi-top">
                    <span class="bp-kpi-lbl">Total Hutang</span>
                    <div class="bp-kpi-ico red">💳</div>
                </div>
                <div class="bp-kpi-val red">Rp {{ number_format($totalRaw, 0, ',', '.') }}</div>
                <div class="bp-kpi-foot">{{ $hutangs->count() }} nota hutang aktif</div>
            </div>
            <div class="bp-kpi amber">
                <div class="bp-kpi-top">
                    <span class="bp-kpi-lbl">Sisa Hutang</span>
                    <div class="bp-kpi-ico amber">📊</div>
                </div>
                <div class="bp-kpi-val amber">Rp {{ number_format($totalSisa, 0, ',', '.') }}</div>
                <div class="bp-kpi-foot">Total yang belum dibayar</div>
            </div>
            <div class="bp-kpi green">
                <div class="bp-kpi-top">
                    <span class="bp-kpi-lbl">Sisa Efektif</span>
                    <div class="bp-kpi-ico green">✅</div>
                </div>
                <div class="bp-kpi-val green">Rp {{ number_format($totalEffectiveSisa, 0, ',', '.') }}</div>
                <div class="bp-kpi-foot">Sisa dikurangi pending</div>
            </div>
            <div class="bp-kpi blue">
                <div class="bp-kpi-top">
                    <span class="bp-kpi-lbl">Total Nota</span>
                    <div class="bp-kpi-ico blue">📋</div>
                </div>
                <div class="bp-kpi-val blue">{{ $hutangs->count() }} <span style="font-size:0.75rem;font-weight:600;color:#94a3b8;">nota</span></div>
                <div class="bp-kpi-foot">{{ $hutangs->where('status', 'overdue')->count() }} overdue</div>
            </div>
        </div>

        {{-- Debt List --}}
        <div class="bp-card">
            <div class="bp-card-hdr">
                <div>
                    <div class="bp-card-title">📋 Daftar Hutang</div>
                    <div class="bp-card-sub">Semua nota hutang yang masih aktif</div>
                </div>
                <span class="bp-card-count">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    {{ $hutangs->count() }} Nota
                </span>
            </div>

            @if($hutangs->isEmpty())
                <div class="bp-empty">
                    <div class="bp-empty-ico">
                        <svg width="32" height="32" fill="none" stroke="#059669" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="bp-empty-title">Semua Lunas!</div>
                    <div class="bp-empty-sub">Pelanggan ini tidak memiliki hutang aktif saat ini.</div>
                </div>
            @else
                <div class="bp-tblwrap">
                    <table class="bp-tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No. Faktur</th>
                                <th>Tanggal</th>
                                <th>Jatuh Tempo</th>
                                <th>Sales</th>
                                <th style="text-align:right;">Total</th>
                                <th style="text-align:right;">Dibayar</th>
                                <th style="text-align:right;">Sisa</th>
                                <th style="width:110px;">Progress</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:center;width:80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hutangs as $idx => $h)
                            @php
                                $pct = (float) $h->total_hutang > 0 ? min(100, ((float) $h->dibayar / (float) $h->total_hutang) * 100) : 0;
                                $barColor = $pct >= 100 ? '#10b981' : ($pct > 0 ? '#f59e0b' : '#ef4444');
                            @endphp
                            <tr>
                                <td style="color:#94a3b8;font-weight:600;">{{ $idx + 1 }}</td>
                                <td><span style="font-weight:600;">{{ $h->penjualan->no_faktur ?? '-' }}</span></td>
                                <td>{{ $h->penjualan->tanggal_jual ? \Carbon\Carbon::parse($h->penjualan->tanggal_jual)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $h->jatuh_tempo ? $h->jatuh_tempo->format('d/m/Y') : '-' }}</td>
                                <td>{{ $h->penjualan->sales->nama ?? '-' }}</td>
                                <td style="text-align:right;"><span class="bp-amt">Rp {{ number_format((float) $h->total_hutang, 0, ',', '.') }}</span></td>
                                <td style="text-align:right;"><span class="bp-amt green">Rp {{ number_format((float) $h->dibayar, 0, ',', '.') }}</span></td>
                                <td style="text-align:right;"><span class="bp-amt red">Rp {{ number_format((float) $h->sisa, 0, ',', '.') }}</span></td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:0.5rem;">
                                        <div class="bp-progress" style="flex:1;">
                                            <div class="bp-progress-fill" style="width:{{ (int) $pct }}%;background:{{ $barColor }};"></div>
                                        </div>
                                        <span style="font-size:0.625rem;font-weight:700;color:#64748b;min-width:2.2rem;text-align:right;">{{ number_format($pct, 0) }}%</span>
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    @if($h->status === 'overdue')
                                        <span class="bp-badge overdue">⚠️ Overdue</span>
                                    @else
                                        <span class="bp-badge unpaid">🕐 Belum Lunas</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <a href="{{ route('minyak.hutang.show', $h) }}" class="bp-act bp-act-view">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5m-7 5h12"/></svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Payment Form --}}
        @if($hutangs->isNotEmpty() && $totalEffectiveSisa > 0)
        <div class="bp-card">
            <div class="bp-card-hdr">
                <div>
                    <div class="bp-card-title">💳 Bayar Hutang</div>
                    <div class="bp-card-sub">Lakukan pembayaran untuk satu atau beberapa hutang sekaligus</div>
                </div>
            </div>

            <form action="{{ route('minyak.hutang.bayar-semua', $pelanggan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bp-form">
                    <div class="bp-form-grid">
                        <div class="bp-fld">
                            <label class="bp-lbl">Jumlah Bayar <span class="bp-req">*</span></label>
                            <div class="bp-money">
                                <span class="bp-money-pfx">Rp</span>
                                <input type="text" inputmode="numeric" name="jumlah" id="bp-jumlah"
                                    value="{{ old('jumlah', $totalEffectiveSisa) }}" min="1"
                                    class="bp-money-inp" required data-currency>
                            </div>
                            <div style="font-size:0.7rem;color:#64748b;margin-top:0.375rem;">
                                Sisa efektif: <strong>Rp {{ number_format($totalEffectiveSisa, 0, ',', '.') }}</strong>
                                @if($totalPending > 0)
                                    &middot; {{ number_format($totalPending, 0, ',', '.') }} pending
                                @endif
                            </div>
                            @error('jumlah')<div style="color:#dc2626;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</div>@enderror
                        </div>

                        <div class="bp-fld">
                            <label class="bp-lbl">Cara Bayar <span class="bp-req">*</span></label>
                            <select name="cara_bayar" id="bp-cara-bayar" class="bp-inp" required>
                                <option value="tunai" {{ old('cara_bayar') === 'tunai' ? 'selected' : '' }}>💵 Tunai</option>
                                <option value="transfer" {{ old('cara_bayar') === 'transfer' ? 'selected' : '' }}>🏦 Transfer</option>
                            </select>
                            @error('cara_bayar')<div style="color:#dc2626;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</div>@enderror
                        </div>

                        <div class="bp-fld" id="bp-fld-transfer" style="display:none;">
                            <label class="bp-lbl">ID / No. Referensi Transfer <span class="bp-req">*</span></label>
                            <input type="text" name="id_transaksi" id="bp-id-transaksi" value="{{ old('id_transaksi') }}" class="bp-inp" placeholder="Contoh: TRX123456">
                            @error('id_transaksi')<div style="color:#dc2626;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</div>@enderror
                        </div>

                        <div class="bp-fld" id="bp-fld-bukti" style="display:none;">
                            <label class="bp-lbl">Upload Bukti Transfer <span class="bp-req">*</span></label>
                            <div class="bp-photo-box" id="bp-bukti-box">
                                <img id="bp-bukti-preview" src="" alt="">
                                <div class="bp-photo-empty" id="bp-bukti-empty">
                                    <div class="bp-photo-empty-ico" style="background:#dbeafe;">
                                        <svg width="22" height="22" fill="none" stroke="#3b82f6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <p>Klik untuk upload foto bukti transfer</p>
                                </div>
                            </div>
                            <input type="file" name="bukti_transfer" id="bp-bukti-input" accept="image/*" style="display:none;">
                            @error('bukti_transfer')<div style="color:#dc2626;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</div>@enderror
                        </div>

                        <div class="bp-fld bp-fld-full">
                            <label class="bp-lbl">Keterangan <span style="font-size:0.7rem;color:#94a3b8;font-weight:400;">(opsional)</span></label>
                            <textarea name="keterangan" class="bp-inp" rows="2" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    @if($totalPending > 0)
                    <div class="bp-pending-note">
                        ⏳ Terdapat <strong>Rp {{ number_format($totalPending, 0, ',', '.') }}</strong> pembayaran pending.
                        Sisa efektif (yang bisa dibayar sekarang): <strong>Rp {{ number_format($totalEffectiveSisa, 0, ',', '.') }}</strong>
                    </div>
                    @endif

                    <div style="margin-top:1.25rem;display:flex;gap:0.75rem;flex-wrap:wrap;">
                        <button type="submit" class="bp-btn-primary" id="bp-submit-btn">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Bayar Rp {{ number_format($totalEffectiveSisa, 0, ',', '.') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var caraBayar = document.getElementById('bp-cara-bayar');
        var fldTransfer = document.getElementById('bp-fld-transfer');
        var fldBukti = document.getElementById('bp-fld-bukti');
        var inpBukti = document.getElementById('bp-bukti-input');
        var buktiBox = document.getElementById('bp-bukti-box');
        var buktiPreview = document.getElementById('bp-bukti-preview');
        var buktiEmpty = document.getElementById('bp-bukti-empty');

        function toggleTransfer() {
            var isTransfer = caraBayar.value === 'transfer';
            fldTransfer.style.display = isTransfer ? 'block' : 'none';
            fldBukti.style.display = isTransfer ? 'block' : 'none';
            document.getElementById('bp-id-transaksi').required = isTransfer;
            inpBukti.required = isTransfer;
        }
        caraBayar.addEventListener('change', toggleTransfer);
        toggleTransfer();

        buktiBox.addEventListener('click', function() { inpBukti.click(); });
        inpBukti.addEventListener('change', function() {
            var f = this.files && this.files[0];
            if (!f) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                buktiPreview.src = e.target.result;
                buktiPreview.style.display = 'block';
                buktiEmpty.style.display = 'none';
            };
            reader.readAsDataURL(f);
        });

        // Update button text on jumlah change
        var inpJumlah = document.getElementById('bp-jumlah');
        var submitBtn = document.getElementById('bp-submit-btn');
        function updateBtn() {
            var raw = String(inpJumlah.value).replace(/\./g, '').replace(/[^0-9]/g, '');
            var n = parseInt(raw) || 0;
            if (n > 0) {
                submitBtn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Bayar Rp ' + Number(n).toLocaleString('id-ID');
            } else {
                submitBtn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Bayar';
            }
        }
        inpJumlah.addEventListener('input', updateBtn);
    });
    </script>
    @endpush
</x-app-layout>
