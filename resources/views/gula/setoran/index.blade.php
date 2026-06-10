<x-app-layout>
    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style>
        .st-wrap{font-family:'Plus Jakarta Sans',sans-serif}
        .st-mono{font-family:'JetBrains Mono',monospace}

        /* header */
        .st-header{background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);border:1px solid #fde68a;border-radius:20px;padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px}
        .st-header-left{display:flex;align-items:center;gap:16px}
        .st-header-icon{width:52px;height:52px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:16px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(245,158,11,.35)}
        .st-header-icon svg{width:26px;height:26px;color:#fff}
        .st-header h1{font-size:1.375rem;font-weight:800;color:#1f2937;margin:0}
        .st-header p{font-size:.8rem;color:#92400e;margin:2px 0 0}
        .st-btn-add{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:11px 22px;border-radius:14px;font-size:.8125rem;font-weight:700;text-decoration:none;box-shadow:0 4px 14px rgba(245,158,11,.35);transition:opacity .2s}
        .st-btn-add:hover{opacity:.88}
        .st-btn-add svg{width:16px;height:16px}

        /* kpi */
        .st-kpi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px}
        .st-kpi{background:#fff;border:1px solid #fde68a;border-radius:16px;padding:20px;display:flex;align-items:flex-start;gap:14px;position:relative;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04);transition:box-shadow .2s}
        .st-kpi:hover{box-shadow:0 4px 14px rgba(245,158,11,.1)}
        .st-kpi::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px}
        .st-kpi.amber::before{background:linear-gradient(180deg,#f59e0b,#d97706)}
        .st-kpi.green::before{background:linear-gradient(180deg,#10b981,#059669)}
        .st-kpi.blue::before{background:linear-gradient(180deg,#3b82f6,#2563eb)}
        .st-kpi-icon{width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .st-kpi-icon svg{width:22px;height:22px}
        .st-kpi-icon.amber{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706}
        .st-kpi-icon.green{background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#059669}
        .st-kpi-icon.blue{background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#2563eb}
        .st-kpi-val{font-size:1.5rem;font-weight:800;color:#1f2937;line-height:1}
        .st-kpi-lbl{font-size:.72rem;color:#6b7280;margin-top:4px;font-weight:500}

        /* filter */
        .st-filter{background:#fff;border:1px solid #fde68a;border-radius:16px;padding:16px 20px;margin-bottom:24px;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .st-filter form{display:flex;flex-wrap:wrap;align-items:center;gap:10px}
        .st-date-wrap{position:relative}
        .st-date-wrap svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9ca3af;pointer-events:none}
        .st-date{border:1.5px solid #fde68a;border-radius:12px;padding:9px 14px 9px 34px;font-size:.8125rem;background:#fffbeb;color:#92400e;font-weight:500;outline:none;transition:border-color .2s}
        .st-date:focus{border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.1)}
        .st-filter select{border:1.5px solid #fde68a;border-radius:12px;padding:9px 14px;font-size:.8125rem;background:#fffbeb;color:#92400e;font-weight:500;outline:none;min-width:155px;transition:border-color .2s;cursor:pointer}
        .st-filter select:focus{border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.1)}
        .st-btn-filter{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;border-radius:12px;padding:9px 18px;font-size:.8125rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:7px;transition:opacity .2s}
        .st-btn-filter:hover{opacity:.88}
        .st-btn-filter svg{width:15px;height:15px}
        .st-btn-reset{background:#fffbeb;color:#92400e;border:1.5px solid #fde68a;border-radius:12px;padding:9px 16px;font-size:.8125rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:7px;text-decoration:none;transition:background .2s}
        .st-btn-reset:hover{background:#fef3c7}
        .st-btn-reset svg{width:15px;height:15px}

        /* table */
        .st-tbl-card{background:#fff;border:1px solid #fde68a;border-radius:20px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.05)}
        .st-tbl-wrap{overflow-x:auto}
        .st-tbl{width:100%;border-collapse:collapse}
        .st-tbl thead th{background:linear-gradient(180deg,#fffbeb,#fef9ee);border-bottom:2px solid #fde68a;padding:13px 16px;font-size:.7rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.5px;white-space:nowrap}
        .st-tbl tbody td{padding:14px 16px;border-bottom:1px solid #fef3c7;font-size:.8125rem;color:#374151;vertical-align:middle}
        .st-tbl tbody tr:last-child td{border-bottom:none}
        .st-tbl tbody tr{transition:background .15s}
        .st-tbl tbody tr:hover{background:#fffbeb}

        /* cells */
        .st-date-cell{font-weight:500;color:#374151;font-size:.8125rem}
        .st-sales-cell{display:flex;align-items:center;gap:10px}
        .st-sales-av{width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.72rem;flex-shrink:0}
        .st-sales-nm{font-weight:600;color:#1f2937;font-size:.8125rem}
        .st-val-setor{font-weight:700;color:#1f2937}
        .st-val-penjualan{font-weight:500;color:#6b7280}

        /* selisih */
        .st-selisih{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:8px;font-size:.72rem;font-weight:700}
        .st-selisih.pas{background:#d1fae5;color:#065f46}
        .st-selisih.kurang{background:#fee2e2;color:#991b1b}
        .st-selisih.lebih{background:#dbeafe;color:#1e40af}
        .st-selisih svg{width:13px;height:13px}

        /* status */
        .st-status{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:99px;font-size:.7rem;font-weight:700;letter-spacing:.2px}
        .st-status-dot{width:7px;height:7px;border-radius:50%}
        .st-status.pending{background:#fef3c7;color:#92400e}
        .st-status.pending .st-status-dot{background:#f59e0b;animation:st-pulse 1.8s infinite}
        .st-status.terverifikasi{background:#d1fae5;color:#065f46}
        .st-status.terverifikasi .st-status-dot{background:#10b981}
        .st-status.ditolak{background:#fee2e2;color:#991b1b}
        .st-status.ditolak .st-status-dot{background:#ef4444}
        @keyframes st-pulse{0%,100%{opacity:1}50%{opacity:.35}}

        /* actions */
        .st-actions{display:flex;align-items:center;gap:6px;justify-content:center}
        .st-act{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:10px;font-size:.72rem;font-weight:600;text-decoration:none;transition:background .2s;border:none;cursor:pointer}
        .st-act svg{width:13px;height:13px}
        .st-act.detail{background:#eff6ff;color:#2563eb}
        .st-act.detail:hover{background:#dbeafe}
        .st-act.verify{background:#ecfdf5;color:#059669}
        .st-act.verify:hover{background:#d1fae5}

        /* pagination */
        .st-pagination{padding:16px 20px;border-top:1px solid #fde68a;background:linear-gradient(180deg,#fffbeb,#fef9ee)}

        /* empty */
        .st-empty{text-align:center;padding:60px 24px}
        .st-empty-icon{width:80px;height:80px;margin:0 auto 18px;background:linear-gradient(135deg,#fef3c7,#fde68a);border-radius:24px;display:flex;align-items:center;justify-content:center}
        .st-empty-icon svg{width:38px;height:38px;color:#d97706}
        .st-empty h3{font-size:1rem;font-weight:700;color:#374151;margin:0 0 6px}
        .st-empty p{font-size:.8125rem;color:#6b7280;margin:0 0 20px}
        .st-empty-btn{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:10px 22px;border-radius:12px;font-size:.8125rem;font-weight:600;text-decoration:none;box-shadow:0 4px 12px rgba(245,158,11,.3);transition:opacity .2s}
        .st-empty-btn:hover{opacity:.88}
        .st-empty-btn svg{width:16px;height:16px}

        @media(max-width:640px){.st-kpi-grid{grid-template-columns:1fr}}
    </style>
    @endpush

    <div class="st-wrap" style="padding:24px">

        {{-- Header --}}
        <div class="st-header">
            <div class="st-header-left">
                <div class="st-header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h1>Verifikasi Setoran</h1>
                    <p>Monitoring dan verifikasi setoran dari sales</p>
                </div>
            </div>
            <a href="{{ route('gula.setoran.create') }}" class="st-btn-add">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Setoran
            </a>
        </div>

        {{-- KPI Cards --}}
        <div class="st-kpi-grid">
            <div class="st-kpi amber">
                <div class="st-kpi-icon amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="st-kpi-val">{{ $stats['total_pending'] }}</div>
                    <div class="st-kpi-lbl">Pending Verifikasi</div>
                </div>
            </div>
            <div class="st-kpi green">
                <div class="st-kpi-icon green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <div class="st-kpi-val">{{ $stats['total_terverifikasi'] }}</div>
                    <div class="st-kpi-lbl">Terverifikasi</div>
                </div>
            </div>
            <div class="st-kpi blue">
                <div class="st-kpi-icon blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="st-kpi-val st-mono">Rp {{ number_format($stats['total_setoran_hari_ini'], 0, ',', '.') }}</div>
                    <div class="st-kpi-lbl">Setoran Hari Ini</div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="st-filter">
            <form method="GET">
                <div class="st-date-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="st-date">
                </div>
                <select name="sales_id">
                    <option value="">Semua Sales</option>
                    @foreach($sales as $s)
                        <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                    @endforeach
                </select>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <button type="submit" class="st-btn-filter">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                <a href="{{ route('gula.setoran.index') }}" class="st-btn-reset">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset
                </a>
            </form>
        </div>

        {{-- Table --}}
        <div class="st-tbl-card">
            <div class="st-tbl-wrap">
                <table class="st-tbl">
                    <thead>
                        <tr>
                            <th style="text-align:left">Tanggal</th>
                            <th style="text-align:left">Sales</th>
                            <th style="text-align:right">Total Setor</th>
                            <th style="text-align:right">Penjualan</th>
                            <th style="text-align:right">Selisih</th>
                            <th style="text-align:center">Status</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($setorans as $s)
                        <tr>
                            <td>
                                <span class="st-date-cell">{{ $s->tanggal->format('d M Y') }}</span>
                            </td>
                            <td>
                                <div class="st-sales-cell">
                                    <div class="st-sales-av">{{ strtoupper(substr($s->sales->nama ?? '?', 0, 1)) }}</div>
                                    <span class="st-sales-nm">{{ $s->sales->nama ?? '-' }}</span>
                                </div>
                            </td>
                            <td style="text-align:right">
                                <span class="st-mono st-val-setor">Rp {{ number_format($s->total_setor, 0, ',', '.') }}</span>
                            </td>
                            <td style="text-align:right">
                                <span class="st-mono st-val-penjualan">Rp {{ number_format($s->total_penjualan, 0, ',', '.') }}</span>
                            </td>
                            <td style="text-align:right">
                                @if($s->selisih == 0)
                                    <span class="st-selisih pas">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Pas
                                    </span>
                                @elseif($s->selisih < 0)
                                    <span class="st-selisih kurang">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                        Kurang Rp {{ number_format(abs($s->selisih), 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="st-selisih lebih">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                        Lebih Rp {{ number_format($s->selisih, 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                            <td style="text-align:center">
                                <span class="st-status {{ $s->status }}">
                                    <span class="st-status-dot"></span>
                                    {{ ucfirst($s->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="st-actions">
                                    <a href="{{ route('gula.setoran.show', $s) }}" class="st-act detail">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </a>
                                    @if($s->status == 'pending')
                                    <form action="{{ route('gula.setoran.verify', $s) }}" method="POST" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="status" value="terverifikasi">
                                        <button type="submit" class="st-act verify">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Verifikasi
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="st-empty">
                                    <div class="st-empty-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                    </div>
                                    <h3>Tidak Ada Data Setoran</h3>
                                    <p>Belum ada setoran yang tercatat. Mulai input setoran pertama.</p>
                                    <a href="{{ route('gula.setoran.create') }}" class="st-empty-btn">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Tambah Setoran
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($setorans->hasPages())
            <div class="st-pagination">
                {{ $setorans->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
