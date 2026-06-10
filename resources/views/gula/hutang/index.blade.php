<x-app-layout>
    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style>
        .ht-wrap{font-family:'Plus Jakarta Sans',sans-serif}
        .ht-mono{font-family:'JetBrains Mono',monospace}

        /* header */
        .ht-header{background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);border:1px solid #fde68a;border-radius:20px;padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px}
        .ht-header-left{display:flex;align-items:center;gap:16px}
        .ht-header-icon{width:52px;height:52px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:16px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(245,158,11,.35)}
        .ht-header-icon svg{width:26px;height:26px;color:#fff}
        .ht-header h1{font-size:1.375rem;font-weight:800;color:#1f2937;margin:0}
        .ht-header p{font-size:.8rem;color:#92400e;margin:2px 0 0}
        .ht-total-badge{background:#fff;border:1px solid #fde68a;padding:8px 18px;border-radius:12px;text-align:right}
        .ht-total-badge-lbl{font-size:.7rem;color:#6b7280;font-weight:500}
        .ht-total-badge-val{font-size:1.1rem;font-weight:800;color:#d97706;margin-top:1px}

        /* kpi */
        .ht-kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px}
        .ht-kpi{background:#fff;border:1px solid #fde68a;border-radius:16px;padding:20px;display:flex;align-items:flex-start;gap:14px;position:relative;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04);transition:box-shadow .2s}
        .ht-kpi:hover{box-shadow:0 4px 14px rgba(245,158,11,.1)}
        .ht-kpi::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px}
        .ht-kpi.red::before{background:linear-gradient(180deg,#f87171,#ef4444)}
        .ht-kpi.amber::before{background:linear-gradient(180deg,#f59e0b,#d97706)}
        .ht-kpi.orange::before{background:linear-gradient(180deg,#fb923c,#f97316)}
        .ht-kpi.green::before{background:linear-gradient(180deg,#10b981,#059669)}
        .ht-kpi-icon{width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .ht-kpi-icon svg{width:22px;height:22px}
        .ht-kpi-icon.red{background:linear-gradient(135deg,#fee2e2,#fecaca);color:#ef4444}
        .ht-kpi-icon.amber{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706}
        .ht-kpi-icon.orange{background:linear-gradient(135deg,#ffedd5,#fed7aa);color:#ea580c}
        .ht-kpi-icon.green{background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#059669}
        .ht-kpi-val{font-size:1.35rem;font-weight:800;color:#1f2937;line-height:1}
        .ht-kpi-lbl{font-size:.72rem;color:#6b7280;margin-top:4px;font-weight:500}

        /* filter */
        .ht-filter{background:#fff;border:1px solid #fde68a;border-radius:16px;padding:16px 20px;margin-bottom:24px;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .ht-filter form{display:flex;flex-wrap:wrap;align-items:center;gap:10px}
        .ht-search-wrap{position:relative}
        .ht-search-wrap svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9ca3af}
        .ht-search{border:1.5px solid #fde68a;border-radius:12px;padding:9px 14px 9px 34px;font-size:.8125rem;background:#fffbeb;color:#92400e;font-weight:500;outline:none;width:210px;transition:border-color .2s}
        .ht-search:focus{border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.1)}
        .ht-filter select{border:1.5px solid #fde68a;border-radius:12px;padding:9px 14px;font-size:.8125rem;background:#fffbeb;color:#92400e;font-weight:500;outline:none;min-width:155px;transition:border-color .2s;cursor:pointer}
        .ht-filter select:focus{border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.1)}
        .ht-btn-filter{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;border-radius:12px;padding:9px 18px;font-size:.8125rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:7px;transition:opacity .2s}
        .ht-btn-filter:hover{opacity:.88}
        .ht-btn-filter svg{width:15px;height:15px}
        .ht-btn-reset{background:#fffbeb;color:#92400e;border:1.5px solid #fde68a;border-radius:12px;padding:9px 16px;font-size:.8125rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:7px;text-decoration:none;transition:background .2s}
        .ht-btn-reset:hover{background:#fef3c7}
        .ht-btn-reset svg{width:15px;height:15px}

        /* table */
        .ht-tbl-card{background:#fff;border:1px solid #fde68a;border-radius:20px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.05)}
        .ht-tbl-wrap{overflow-x:auto}
        .ht-tbl{width:100%;border-collapse:collapse}
        .ht-tbl thead th{background:linear-gradient(180deg,#fffbeb,#fef9ee);border-bottom:2px solid #fde68a;padding:13px 16px;font-size:.7rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.5px;white-space:nowrap}
        .ht-tbl tbody td{padding:14px 16px;border-bottom:1px solid #fef3c7;font-size:.8125rem;color:#374151;vertical-align:middle}
        .ht-tbl tbody tr:last-child td{border-bottom:none}
        .ht-tbl tbody tr{transition:background .15s}
        .ht-tbl tbody tr:hover{background:#fffbeb}

        /* cells */
        .ht-pelanggan-cell{display:flex;align-items:center;gap:10px}
        .ht-pelanggan-av{width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.75rem;flex-shrink:0}
        .ht-pelanggan-nm{font-weight:600;color:#1f2937;font-size:.8125rem}
        .ht-pelanggan-owner{font-size:.7rem;color:#6b7280;margin-top:1px}
        .ht-faktur{font-weight:700;color:#1f2937}
        .ht-jatuh-tempo{font-weight:500;color:#374151}
        .ht-jatuh-tempo.overdue{color:#ef4444;font-weight:700}
        .ht-overdue-badge{display:inline-flex;align-items:center;gap:4px;background:#fee2e2;color:#ef4444;padding:2px 8px;border-radius:6px;font-size:.65rem;font-weight:700;margin-left:4px}
        .ht-overdue-badge svg{width:11px;height:11px}
        .ht-val-hutang{color:#ef4444;font-weight:600}
        .ht-val-dibayar{color:#059669;font-weight:600}
        .ht-val-sisa{color:#1f2937;font-weight:700}

        /* progress */
        .ht-progress-wrap{display:flex;align-items:center;gap:8px;margin-top:4px}
        .ht-progress-bar{flex:1;height:5px;background:#fde68a;border-radius:99px;overflow:hidden}
        .ht-progress-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,#10b981,#059669);transition:width .4s}
        .ht-progress-txt{font-size:.65rem;color:#6b7280;font-weight:600;min-width:28px;text-align:right}

        /* status */
        .ht-status{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:99px;font-size:.7rem;font-weight:700;letter-spacing:.2px}
        .ht-status-dot{width:7px;height:7px;border-radius:50%}
        .ht-status.belum-lunas{background:#fef3c7;color:#92400e}
        .ht-status.belum-lunas .ht-status-dot{background:#f59e0b;animation:ht-pulse 1.8s infinite}
        .ht-status.lunas{background:#d1fae5;color:#065f46}
        .ht-status.lunas .ht-status-dot{background:#10b981}
        @keyframes ht-pulse{0%,100%{opacity:1}50%{opacity:.35}}

        /* action */
        .ht-act{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:10px;font-size:.72rem;font-weight:600;text-decoration:none;transition:background .2s;border:none;cursor:pointer;background:#eff6ff;color:#2563eb}
        .ht-act:hover{background:#dbeafe}
        .ht-act svg{width:13px;height:13px}

        /* pagination */
        .ht-pagination{padding:16px 20px;border-top:1px solid #fde68a;background:linear-gradient(180deg,#fffbeb,#fef9ee)}

        /* empty */
        .ht-empty{text-align:center;padding:60px 24px}
        .ht-empty-icon{width:80px;height:80px;margin:0 auto 18px;background:linear-gradient(135deg,#fef3c7,#fde68a);border-radius:24px;display:flex;align-items:center;justify-content:center}
        .ht-empty-icon svg{width:38px;height:38px;color:#d97706}
        .ht-empty h3{font-size:1rem;font-weight:700;color:#374151;margin:0 0 6px}
        .ht-empty p{font-size:.8125rem;color:#6b7280;margin:0}

        @media(max-width:1024px){.ht-kpi-grid{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:640px){.ht-kpi-grid{grid-template-columns:1fr}}
    </style>
    @endpush

    <div class="ht-wrap" style="padding:24px">

        {{-- Header --}}
        <div class="ht-header">
            <div class="ht-header-left">
                <div class="ht-header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h1>Hutang Pelanggan</h1>
                    <p>Monitoring piutang dan pembayaran pelanggan</p>
                </div>
            </div>
            <div class="ht-total-badge">
                <div class="ht-total-badge-lbl">Total Piutang Aktif</div>
                <div class="ht-total-badge-val ht-mono">Rp {{ number_format($stats['total_hutang'], 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="ht-kpi-grid">
            <div class="ht-kpi red">
                <div class="ht-kpi-icon red">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="ht-kpi-val ht-mono">Rp {{ number_format($stats['total_hutang'], 0, ',', '.') }}</div>
                    <div class="ht-kpi-lbl">Total Hutang</div>
                </div>
            </div>
            <div class="ht-kpi amber">
                <div class="ht-kpi-icon amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="ht-kpi-val">{{ $stats['belum_lunas'] }}</div>
                    <div class="ht-kpi-lbl">Belum Lunas</div>
                </div>
            </div>
            <div class="ht-kpi orange">
                <div class="ht-kpi-icon orange">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <div class="ht-kpi-val">{{ $stats['overdue'] }}</div>
                    <div class="ht-kpi-lbl">Overdue</div>
                </div>
            </div>
            <div class="ht-kpi green">
                <div class="ht-kpi-icon green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <div class="ht-kpi-val">{{ $stats['lunas'] }}</div>
                    <div class="ht-kpi-lbl">Lunas</div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="ht-filter">
            <form method="GET">
                <div class="ht-search-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama toko, pemilik..." class="ht-search">
                </div>
                <select name="pelanggan_id">
                    <option value="">Semua Pelanggan</option>
                    @foreach($pelanggans as $p)
                        <option value="{{ $p->id }}" {{ request('pelanggan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_toko }}</option>
                    @endforeach
                </select>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
                <button type="submit" class="ht-btn-filter">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                <a href="{{ route('gula.hutang.index') }}" class="ht-btn-reset">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset
                </a>
            </form>
        </div>

        {{-- Table --}}
        <div class="ht-tbl-card">
            <div class="ht-tbl-wrap">
                <table class="ht-tbl">
                    <thead>
                        <tr>
                            <th style="text-align:left">Pelanggan</th>
                            <th style="text-align:left">No Faktur</th>
                            <th style="text-align:left">Jatuh Tempo</th>
                            <th style="text-align:right">Total Hutang</th>
                            <th style="text-align:right">Dibayar</th>
                            <th style="text-align:right">Sisa</th>
                            <th style="text-align:center">Status</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hutangs as $h)
                        @php
                            $isOverdue = $h->status === 'overdue' || ($h->status === 'belum_lunas' && $h->jatuh_tempo && $h->jatuh_tempo->isPast());
                            $pctDibayar = $h->total_hutang > 0 ? round(($h->dibayar / $h->total_hutang) * 100) : 0;
                            $initial = strtoupper(substr($h->pelanggan->nama_toko ?? '?', 0, 1));
                        @endphp
                        <tr>
                            <td>
                                <div class="ht-pelanggan-cell">
                                    <div class="ht-pelanggan-av">{{ $initial }}</div>
                                    <div>
                                        <div class="ht-pelanggan-nm">{{ $h->pelanggan->nama_toko ?? '-' }}</div>
                                        <div class="ht-pelanggan-owner">{{ $h->pelanggan->nama_pemilik ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="ht-faktur ht-mono">{{ $h->penjualan->no_faktur ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="ht-jatuh-tempo {{ $isOverdue ? 'overdue' : '' }}">
                                    {{ $h->jatuh_tempo->format('d M Y') }}
                                </span>
                                @if($isOverdue)
                                    <span class="ht-overdue-badge">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        Overdue
                                    </span>
                                @endif
                            </td>
                            <td style="text-align:right">
                                <span class="ht-mono ht-val-hutang">Rp {{ number_format($h->total_hutang, 0, ',', '.') }}</span>
                            </td>
                            <td style="text-align:right">
                                <span class="ht-mono ht-val-dibayar">Rp {{ number_format($h->dibayar, 0, ',', '.') }}</span>
                                @if($h->status !== 'lunas')
                                <div class="ht-progress-wrap">
                                    <div class="ht-progress-bar">
                                        <div class="ht-progress-fill" style="width:{{ $pctDibayar }}%"></div>
                                    </div>
                                    <span class="ht-progress-txt">{{ $pctDibayar }}%</span>
                                </div>
                                @endif
                            </td>
                            <td style="text-align:right">
                                <span class="ht-mono ht-val-sisa">Rp {{ number_format($h->sisa, 0, ',', '.') }}</span>
                            </td>
                            <td style="text-align:center">
                                @if($h->status === 'lunas')
                                    <span class="ht-status lunas">
                                        <span class="ht-status-dot"></span>
                                        Lunas
                                    </span>
                                @elseif($isOverdue)
                                    <span class="ht-status" style="background:#fee2e2;color:#991b1b;">
                                        <span class="ht-status-dot" style="background:#ef4444;"></span>
                                        Overdue
                                    </span>
                                @else
                                    <span class="ht-status belum-lunas">
                                        <span class="ht-status-dot"></span>
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td style="text-align:center">
                                <a href="{{ route('gula.hutang.show', $h) }}" class="ht-act">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="ht-empty">
                                    <div class="ht-empty-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <h3>Tidak Ada Data Hutang</h3>
                                    <p>Belum ada data hutang pelanggan yang tercatat.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($hutangs->hasPages())
            <div class="ht-pagination">
                {{ $hutangs->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
