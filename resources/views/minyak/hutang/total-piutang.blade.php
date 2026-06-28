<x-app-layout>
    @push('styles')
    <style>
        .tp { max-width:76rem; margin:0 auto; padding:1.25rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .tp-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .tp-hdr-l { display:flex; align-items:center; gap:1rem; }
        .tp-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#ef4444,#dc2626);
            box-shadow:0 8px 24px rgba(220,38,38,0.3);
        }
        .tp-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .tp-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .tp-hdr-acts { display:flex; gap:0.5rem; flex-wrap:wrap; }
        .tp-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s; border:none; cursor:pointer; font-family:inherit;
        }
        .tp-btn-primary {
            background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff;
            box-shadow:0 6px 20px rgba(220,38,38,0.35);
        }
        .tp-btn-primary:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(220,38,38,0.45); }
        .tp-btn-secondary {
            background:#f1f5f9; color:#475569; border:1.5px solid #e2e8f0;
        }
        .tp-btn-secondary:hover { background:#e2e8f0; color:#334155; }

        .tp-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:0.875rem; margin-bottom:1.5rem; }
        .tp-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.25rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .tp-kpi::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .tp-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .tp-kpi.red::before { background:linear-gradient(90deg,#ef4444,#dc2626); }
        .tp-kpi.amber::before { background:linear-gradient(90deg,#f59e0b,#d97706); }
        .tp-kpi.blue::before { background:linear-gradient(90deg,#3b82f6,#2563eb); }
        .tp-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem; }
        .tp-kpi-lbl { font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .tp-kpi-ico { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; }
        .tp-kpi-ico.red { background:linear-gradient(135deg,#fff1f2,#ffe4e6); }
        .tp-kpi-ico.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .tp-kpi-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .tp-kpi-val { font-size:1.375rem; font-weight:800; letter-spacing:-0.02em; line-height:1; }
        .tp-kpi-val.red { color:#dc2626; }
        .tp-kpi-val.amber { color:#d97706; }
        .tp-kpi-val.blue { color:#2563eb; }
        .tp-kpi-foot { font-size:0.7rem; color:#94a3b8; margin-top:0.25rem; }

        .tp-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .tp-card-hdr {
            padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;
            display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;
        }
        .tp-card-title { font-size:1rem; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:0.5rem; }
        .tp-card-sub { font-size:0.8rem; color:#64748b; margin-top:0.25rem; }
        .tp-card-count {
            display:inline-flex; align-items:center; gap:0.375rem;
            padding:0.25rem 0.75rem; border-radius:999px; font-size:0.6875rem; font-weight:700;
            background:#f1f5f9; color:#475569; white-space:nowrap;
        }

        .tp-tblwrap { overflow-x:auto; }
        .tp-tbl { width:100%; border-collapse:separate; border-spacing:0; min-width:600px; }
        .tp-tbl thead { background:linear-gradient(180deg,#fef2f2,#fff1f2); border-bottom:2px solid #fecaca; }
        .tp-tbl th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#991b1b; white-space:nowrap; text-align:left;
        }
        .tp-tbl th:last-child { text-align:center; }
        .tp-tbl td {
            padding:0.9375rem 1.25rem; border-bottom:1px solid #fef2f2; font-size:0.8125rem;
            color:#374151; vertical-align:middle;
        }
        .tp-tbl tbody tr { transition:background 0.15s; }
        .tp-tbl tbody tr:last-child td { border-bottom:none; }
        .tp-tbl tbody tr:hover td { background:linear-gradient(90deg,#fff1f2,#ffe4e6); }

        .tp-cust-name { font-weight:600; color:#1e293b; }
        .tp-cust-sub { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        .tp-badge {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.25rem 0.625rem; border-radius:999px; font-size:0.6875rem; font-weight:700; white-space:nowrap; border:1px solid;
        }
        .tp-badge.count { background:#f1f5f9; color:#475569; border-color:#e2e8f0; }

        .tp-amt { font-size:0.9375rem; font-weight:700; color:#0f172a; letter-spacing:-0.01em; text-align:right; }
        .tp-amt-sub { font-size:0.675rem; color:#94a3b8; font-weight:500; text-align:right; margin-top:2px; }

        .tp-progress { background:#f1f5f9; border-radius:999px; height:6px; margin-top:0.5rem; overflow:hidden; max-width:200px; margin-left:auto; }
        .tp-progress-fill { height:100%; border-radius:999px; transition:width 0.5s ease; }

        .tp-acts { display:flex; gap:0.375rem; align-items:center; justify-content:center; flex-wrap:wrap; }
        .tp-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.375rem 0.75rem; border-radius:8px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
        }
        .tp-act-view { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .tp-act-view:hover { background:#dbeafe; border-color:#93c5fd; }

        .tp-empty { text-align:center; padding:3.5rem 1.5rem; }
        .tp-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#d1fae5,#a7f3d0); display:flex; align-items:center; justify-content:center;
        }
        .tp-empty-title { font-size:1rem; font-weight:700; color:#065f46; margin-bottom:0.25rem; }
        .tp-empty-sub { font-size:0.8125rem; color:#94a3b8; }

        .tp-flash {
            margin-bottom:1rem; padding:0.75rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            display:flex; align-items:center; gap:0.5rem;
        }
        .tp-flash.success { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; }
        .tp-flash.error { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; }

        @media(max-width:1024px){.tp-kpis{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:768px){
            .tp-hdr-title{font-size:1.25rem}.tp-hdr-ico{width:44px;height:44px;font-size:1.25rem}
            .tp-kpi{padding:1rem 1.125rem}.tp-kpi-val{font-size:1.125rem}
        }
        @media(max-width:640px){
            .tp{padding:1rem 0.75rem 2rem}
            .tp-kpis{grid-template-columns:1fr;gap:0.625rem}
            .tp-hdr-acts{width:100%}.tp-btn{flex:1;justify-content:center}
            .tp-tblwrap table{min-width:500px}
        }
        @media(max-width:480px){
            .tp-hdr-title{font-size:1.125rem}
            .tp-hdr{flex-direction:column;align-items:stretch}
            .tp-hdr-l{flex-direction:column;align-items:flex-start;gap:0.5rem}
            .tp-card-hdr{flex-direction:column;align-items:flex-start}
            .tp-empty{padding:2.5rem 1rem}
        }
    </style>
    @endpush

    <div class="tp">

        {{-- Header --}}
        <div class="tp-hdr">
            <div class="tp-hdr-l">
                <div class="tp-hdr-ico">💳</div>
                <div>
                    <div class="tp-hdr-title">Total Piutang</div>
                    <div class="tp-hdr-sub">Rekap seluruh piutang pelanggan yang belum lunas</div>
                </div>
            </div>
            <div class="tp-hdr-acts">
                <a href="{{ route('minyak.hutang.piutang') }}" class="tp-btn tp-btn-secondary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Daftar Piutang
                </a>
                <a href="{{ route('pelanggan.kredit.create', ['type'=>'debt']) }}" class="tp-btn tp-btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Catat Hutang Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="tp-flash success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="tp-flash error">❌ {{ session('error') }}</div>
        @endif

        {{-- KPI --}}
        @php
            $rataRata = $pelanggans->count() > 0 ? $totalDebt / $pelanggans->count() : 0;
            $pelangganTerbanyak = $pelanggans->count() > 0 ? $pelanggans->first()->calculated_debt : 0;
        @endphp
        <div class="tp-kpis">
            <div class="tp-kpi red">
                <div class="tp-kpi-top">
                    <span class="tp-kpi-lbl">Total Piutang</span>
                    <div class="tp-kpi-ico red">💳</div>
                </div>
                <div class="tp-kpi-val red">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                <div class="tp-kpi-foot">Keseluruhan piutang belum dibayar</div>
            </div>
            <div class="tp-kpi amber">
                <div class="tp-kpi-top">
                    <span class="tp-kpi-lbl">Pelanggan Berhutang</span>
                    <div class="tp-kpi-ico amber">👥</div>
                </div>
                <div class="tp-kpi-val amber">{{ $pelanggans->count() }} <span style="font-size:0.75rem;font-weight:600;color:#94a3b8;">org</span></div>
                <div class="tp-kpi-foot">Rata-rata Rp {{ number_format($rataRata, 0, ',', '.') }}/pelanggan</div>
            </div>
            <div class="tp-kpi blue">
                <div class="tp-kpi-top">
                    <span class="tp-kpi-lbl">Piutang Tertinggi</span>
                    <div class="tp-kpi-ico blue">📊</div>
                </div>
                <div class="tp-kpi-val blue">Rp {{ number_format($pelangganTerbanyak, 0, ',', '.') }}</div>
                <div class="tp-kpi-foot">Nilai piutang terbesar dari satu pelanggan</div>
            </div>
        </div>

        {{-- Customer List --}}
        <div class="tp-card">
            <div class="tp-card-hdr">
                <div>
                    <div class="tp-card-title">👥 Daftar Piutang Pelanggan</div>
                    <div class="tp-card-sub">Klik "Lihat Nota" untuk detail dan pembayaran</div>
                </div>
                <span class="tp-card-count">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    {{ $pelanggans->count() }} Pelanggan
                </span>
            </div>

            <div class="tp-tblwrap">
                <table class="tp-tbl">
                    <thead>
                        <tr>
                            <th style="width:44px;">#</th>
                            <th>Pelanggan</th>
                            <th>Nota Aktif</th>
                            <th style="text-align:right;">Total Piutang</th>
                            <th style="text-align:right;width:160px;">Proporsi</th>
                            <th style="text-align:center;width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggans as $idx => $p)
                        @php
                            $proporsi = $totalDebt > 0 ? ($p->calculated_debt / $totalDebt) * 100 : 0;
                            $warna = $proporsi > 30 ? '#dc2626' : ($proporsi > 15 ? '#d97706' : '#2563eb');
                        @endphp
                        <tr>
                            <td style="color:#94a3b8;font-weight:600;">{{ $idx + 1 }}</td>
                            <td>
                                <div class="tp-cust-name">{{ $p->nama_toko }}</div>
                                <div class="tp-cust-sub">{{ $p->nama_pemilik }}</div>
                            </td>
                            <td>
                                <span class="tp-badge count">{{ $p->hutangs->count() }} nota</span>
                            </td>
                            <td>
                                <div class="tp-amt">Rp {{ number_format($p->calculated_debt, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                <div class="tp-amt-sub" style="margin-bottom:2px;">{{ number_format($proporsi, 1) }}%</div>
                                <div class="tp-progress">
                                    <div class="tp-progress-fill" style="width:{{ min($proporsi, 100) }}%;background:{{ $warna }};"></div>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <div style="display:flex;gap:0.375rem;align-items:center;justify-content:center;flex-wrap:wrap;">
                                    <a href="{{ route('minyak.hutang.pelanggan', $p->id) }}" class="tp-act tp-act-view" style="background:#f0fdf4;color:#065f46;border-color:#a7f3d0;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Bayar
                                    </a>
                                    <a href="{{ route('minyak.hutang.piutang', ['pelanggan_id' => $p->id]) }}" class="tp-act tp-act-view">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5m-7 5h12"/></svg>
                                        Nota
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="tp-empty">
                                    <div class="tp-empty-ico">
                                        <svg width="32" height="32" fill="none" stroke="#059669" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="tp-empty-title">Semua Lunas!</div>
                                    <div class="tp-empty-sub">Tidak ada satupun pelanggan yang memiliki hutang aktif saat ini.</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>