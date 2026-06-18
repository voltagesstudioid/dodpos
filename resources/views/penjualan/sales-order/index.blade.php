<x-app-layout>
    <x-slot name="header">Sales Order</x-slot>

    <style>
        .so-page{padding:1.5rem;display:flex;flex-direction:column;gap:1.25rem;font-family:'Plus Jakarta Sans',system-ui,-apple-system,sans-serif;}
        .so-page-header{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;}
        .so-page-title{font-size:1.35rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;}
        .so-page-sub{font-size:.82rem;color:#64748b;margin-top:.15rem;}
        .so-create-btn{display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-radius:10px;font-size:.82rem;font-weight:700;text-decoration:none;transition:all .2s;box-shadow:0 2px 8px rgba(99,102,241,.25);}
        .so-create-btn:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(99,102,241,.35);}

        /* ── stat cards ── */
        .so-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(185px,1fr));gap:.85rem;}
        .so-stat{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:1.1rem 1.15rem;display:flex;align-items:center;gap:.9rem;transition:all .2s;}
        .so-stat:hover{border-color:#cbd5e1;box-shadow:0 2px 10px rgba(0,0,0,.04);}
        .so-stat-ico{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .so-stat-ico svg{width:20px;height:20px;}
        .so-stat-ico.indigo{background:#eef2ff;color:#6366f1;}
        .so-stat-ico.amber{background:#fffbeb;color:#d97706;}
        .so-stat-ico.blue{background:#eff6ff;color:#2563eb;}
        .so-stat-ico.emerald{background:#ecfdf5;color:#059669;}
        .so-stat-ico.rose{background:#fff1f2;color:#e11d48;}
        .so-stat-ico.orange{background:#fff7ed;color:#ea580c;}
        .so-stat-ico.red{background:#fef2f2;color:#dc2626;}
        .so-stat-info{display:flex;flex-direction:column;gap:.1rem;min-width:0;}
        .so-stat-label{font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;}
        .so-stat-val{font-size:1.45rem;font-weight:800;line-height:1.1;}
        .so-stat-val.indigo{color:#4f46e5;}
        .so-stat-val.amber{color:#d97706;}
        .so-stat-val.blue{color:#2563eb;}
        .so-stat-val.emerald{color:#059669;}
        .so-stat-val.rose{color:#e11d48;}
        .so-stat-val.orange{color:#ea580c;}
        .so-stat-val.red{color:#dc2626;}
        .so-stat-pill{font-size:.65rem;font-weight:600;padding:.15rem .5rem;border-radius:6px;display:inline-block;width:fit-content;margin-top:.15rem;}
        .so-pill-gray{background:#f1f5f9;color:#64748b;}
        .so-pill-amber{background:#fef3c7;color:#92400e;}
        .so-pill-blue{background:#dbeafe;color:#1e40af;}
        .so-pill-emerald{background:#d1fae5;color:#065f46;}
        .so-pill-rose{background:#ffe4e6;color:#9f1239;}
        .so-pill-orange{background:#fff7ed;color:#9a3412;}
        .so-pill-green{background:#dcfce7;color:#166534;}

        /* ── delivery date badge ── */
        .so-dlv{font-size:.72rem;font-weight:700;padding:.18rem .55rem;border-radius:6px;display:inline-flex;align-items:center;gap:.25rem;}
        .so-dlv-today{background:#fef3c7;color:#92400e;}
        .so-dlv-tomorrow{background:#dbeafe;color:#1e40af;}
        .so-dlv-future{background:#f1f5f9;color:#475569;}
        .so-dlv-overdue{background:#fee2e2;color:#991b1b;}
        .so-dlv-done{background:#d1fae5;color:#065f46;}

        /* ── filter bar ── */
        .so-filter{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:1rem 1.15rem;}
        .so-filter-form{display:flex;gap:.65rem;align-items:flex-end;flex-wrap:wrap;}
        .so-field{display:flex;flex-direction:column;gap:.3rem;flex:1;min-width:160px;}
        .so-field-label{font-size:.7rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.04em;}
        .so-field-input{padding:.5rem .7rem;border:1px solid #e2e8f0;border-radius:9px;font-size:.82rem;color:#0f172a;background:#f8fafc;transition:all .15s;outline:none;font-family:inherit;}
        .so-field-input:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1);background:#fff;}
        .so-search-wrap{position:relative;flex:2;min-width:220px;}
        .so-search-wrap .so-field-input{padding-left:2.1rem;width:100%;}
        .so-search-wrap svg{position:absolute;left:.65rem;bottom:.55rem;width:15px;height:15px;color:#94a3b8;pointer-events:none;}
        .so-btn-apply{padding:.5rem 1rem;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border:none;border-radius:9px;font-size:.8rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:.35rem;transition:all .2s;white-space:nowrap;font-family:inherit;}
        .so-btn-apply:hover{box-shadow:0 2px 10px rgba(99,102,241,.3);}
        .so-btn-reset{padding:.5rem .85rem;background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:9px;font-size:.8rem;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;transition:all .15s;font-family:inherit;}
        .so-btn-reset:hover{background:#e2e8f0;}

        /* ── table card ── */
        .so-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;}
        .so-card-hdr{padding:1rem 1.15rem;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;}
        .so-card-title{font-size:.88rem;font-weight:800;color:#0f172a;}
        .so-card-sub{font-size:.75rem;color:#94a3b8;margin-top:.1rem;}
        .so-tbl{width:100%;border-collapse:collapse;}
        .so-tbl thead{background:#f8fafc;}
        .so-tbl th{padding:.65rem 1rem;font-size:.7rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;text-align:left;border-bottom:1px solid #e2e8f0;}
        .so-tbl td{padding:.75rem 1rem;font-size:.83rem;color:#334155;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
        .so-tbl tbody tr{transition:background .12s;}
        .so-tbl tbody tr:hover{background:#f8fafc;}
        .so-tbl tbody tr:last-child td{border-bottom:none;}
        .so-number{display:inline-flex;align-items:center;gap:.35rem;font-weight:700;color:#4f46e5;font-size:.82rem;text-decoration:none;background:#eef2ff;padding:.2rem .6rem;border-radius:7px;transition:all .15s;}
        .so-number:hover{background:#e0e7ff;}
        .so-by{font-size:.73rem;color:#94a3b8;margin-top:.2rem;}
        .so-cust-name{font-weight:700;color:#0f172a;}
        .so-cust-phone{font-size:.73rem;color:#94a3b8;margin-top:.1rem;}
        .so-amount{font-weight:800;color:#0f172a;font-variant-numeric:tabular-nums;}

        /* ── status pills ── */
        .so-status{font-size:.7rem;font-weight:700;padding:.22rem .65rem;border-radius:7px;display:inline-flex;align-items:center;gap:.3rem;}
        .so-status::before{content:'';width:6px;height:6px;border-radius:50%;flex-shrink:0;}
        .so-status-draft{background:#fef3c7;color:#92400e;}.so-status-draft::before{background:#d97706;}
        .so-status-confirmed{background:#dbeafe;color:#1e40af;}.so-status-confirmed::before{background:#2563eb;}
        .so-status-processing{background:#e0e7ff;color:#3730a3;}.so-status-processing::before{background:#6366f1;}
        .so-status-completed{background:#d1fae5;color:#065f46;}.so-status-completed::before{background:#059669;}
        .so-status-cancelled{background:#ffe4e6;color:#9f1239;}.so-status-cancelled::before{background:#e11d48;}

        /* ── action buttons ── */
        .so-actions{display:inline-flex;gap:.4rem;flex-wrap:wrap;justify-content:flex-end;}
        .so-act{font-size:.75rem;font-weight:600;padding:.32rem .7rem;border-radius:7px;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;transition:all .15s;border:1px solid transparent;}
        .so-act-view{background:#eff6ff;color:#2563eb;border-color:#bfdbfe;}
        .so-act-view:hover{background:#dbeafe;}
        .so-act-edit{background:#eef2ff;color:#4f46e5;border-color:#c7d2fe;}
        .so-act-edit:hover{background:#e0e7ff;}

        /* ── empty state ── */
        .so-empty{padding:3rem 1.5rem;text-align:center;}
        .so-empty-ico{width:56px;height:56px;border-radius:16px;background:#f1f5f9;display:inline-flex;align-items:center;justify-content:center;margin-bottom:.85rem;}
        .so-empty-ico svg{width:26px;height:26px;color:#94a3b8;}
        .so-empty-title{font-size:.95rem;font-weight:800;color:#0f172a;}
        .so-empty-sub{font-size:.82rem;color:#64748b;margin-top:.3rem;max-width:420px;margin-left:auto;margin-right:auto;}
        .so-empty-actions{display:flex;gap:.65rem;justify-content:center;margin-top:1rem;flex-wrap:wrap;}

        /* ── pagination ── */
        .so-pagination{padding:.85rem 1rem;border-top:1px solid #f1f5f9;display:flex;justify-content:center;}
        .so-pagination nav span,.so-pagination nav a{font-size:.8rem;padding:.3rem .65rem;border-radius:7px;margin:0 .15rem;}

        @@media(max-width:768px){
            .so-page{padding:1rem;}
            .so-stats{grid-template-columns:repeat(2,1fr);}
            .so-filter-form{flex-direction:column;}
            .so-field,.so-search-wrap{min-width:100%;}
            .so-tbl th,.so-tbl td{padding:.55rem .65rem;}
            .so-card-hdr{flex-direction:column;align-items:flex-start;gap:.5rem;}
        }
        @@media print{.so-filter,.so-create-btn,.so-actions,.so-pagination{display:none!important;}}
    </style>

    <div class="so-page">

        {{-- ─── HEADER ─── --}}
        <div class="so-page-header">
            <div>
                <div class="so-page-title">Daftar Sales Order</div>
                <div class="so-page-sub">Kelola dan pantau pesanan pelanggan dengan mudah</div>
            </div>
            @can('create_sales_order')
            <a href="{{ route('sales-order.create') }}" class="so-create-btn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Buat SO Baru
            </a>
            @endcan
        </div>

        {{-- ─── STAT CARDS ─── --}}
        <div class="so-stats">
            <div class="so-stat">
                <div class="so-stat-ico indigo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">Total</div>
                    <div class="so-stat-val indigo">{{ $totalCount ?? $salesOrders->total() }}</div>
                    <span class="so-stat-pill so-pill-gray">Sesuai filter</span>
                </div>
            </div>
            <div class="so-stat">
                <div class="so-stat-ico amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">Draft</div>
                    <div class="so-stat-val amber">{{ $draftCount ?? 0 }}</div>
                    <span class="so-stat-pill so-pill-amber">Draft</span>
                </div>
            </div>
            <div class="so-stat">
                <div class="so-stat-ico blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">Proses</div>
                    <div class="so-stat-val blue">{{ ($confirmedCount ?? 0) + ($processingCount ?? 0) }}</div>
                    <span class="so-stat-pill so-pill-blue">Confirmed / Processing</span>
                </div>
            </div>
            <div class="so-stat">
                <div class="so-stat-ico emerald">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">Selesai</div>
                    <div class="so-stat-val emerald">{{ $completedCount ?? 0 }}</div>
                    <span class="so-stat-pill so-pill-emerald">Completed</span>
                </div>
            </div>
            @if(($cancelledCount ?? 0) > 0)
            <div class="so-stat">
                <div class="so-stat-ico rose">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">Dibatalkan</div>
                    <div class="so-stat-val rose">{{ $cancelledCount }}</div>
                    <span class="so-stat-pill so-pill-rose">Cancelled</span>
                </div>
            </div>
            @endif
        </div>

        {{-- ─── DELIVERY STAT CARDS ─── --}}
        <div class="so-stats">
            <a href="{{ route('sales-order.index', ['delivery' => 'today']) }}" class="so-stat" style="text-decoration:none;cursor:pointer;{{ ($deliveryFilter ?? '') === 'today' ? 'border-color:#d97706;box-shadow:0 0 0 2px rgba(217,119,6,0.2);' : '' }}">
                <div class="so-stat-ico amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">Kirim Hari Ini</div>
                    <div class="so-stat-val amber">{{ $kirimHariIniCount ?? 0 }}</div>
                    <span class="so-stat-pill so-pill-amber">{{ now()->format('d M') }}</span>
                </div>
            </a>
            <a href="{{ route('sales-order.index', ['delivery' => 'tomorrow']) }}" class="so-stat" style="text-decoration:none;cursor:pointer;{{ ($deliveryFilter ?? '') === 'tomorrow' ? 'border-color:#2563eb;box-shadow:0 0 0 2px rgba(37,99,235,0.2);' : '' }}">
                <div class="so-stat-ico blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">Siap Kirim Besok</div>
                    <div class="so-stat-val blue">{{ $siapKirimCount ?? 0 }}</div>
                    <span class="so-stat-pill so-pill-blue">{{ now()->addDay()->format('d M') }}</span>
                </div>
            </a>
            <a href="{{ route('sales-order.index', ['delivery' => 'week']) }}" class="so-stat" style="text-decoration:none;cursor:pointer;{{ ($deliveryFilter ?? '') === 'week' ? 'border-color:#059669;box-shadow:0 0 0 2px rgba(5,150,105,0.2);' : '' }}">
                <div class="so-stat-ico emerald">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">Minggu Ini</div>
                    <div class="so-stat-val emerald">{{ ($kirimHariIniCount ?? 0) + ($siapKirimCount ?? 0) }}</div>
                    <span class="so-stat-pill so-pill-emerald">7 hari ke depan</span>
                </div>
            </a>
            @if(($overdueCount ?? 0) > 0)
            <a href="{{ route('sales-order.index', ['delivery' => 'overdue']) }}" class="so-stat" style="text-decoration:none;cursor:pointer;{{ ($deliveryFilter ?? '') === 'overdue' ? 'border-color:#dc2626;box-shadow:0 0 0 2px rgba(220,38,38,0.2);' : '' }}">
                <div class="so-stat-ico red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div class="so-stat-info">
                    <div class="so-stat-label">Terlambat</div>
                    <div class="so-stat-val red">{{ $overdueCount }}</div>
                    <span class="so-stat-pill" style="background:#fee2e2;color:#991b1b;">Perlu dikirim</span>
                </div>
            </a>
            @endif
        </div>

        {{-- ─── FILTER BAR ─── --}}
        <div class="so-filter">
            <form method="GET" action="{{ route('sales-order.index') }}" class="so-filter-form">
                <div class="so-search-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor SO, nama pelanggan, atau pembuat..." class="so-field-input">
                </div>
                <div class="so-field" style="flex:0 0 155px;min-width:140px;">
                    <label class="so-field-label">Status</label>
                    <select name="status" class="so-field-input">
                        <option value="">Semua</option>
                        <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="so-field" style="flex:0 0 155px;min-width:140px;">
                    <label class="so-field-label">Tanggal Order</label>
                    <input type="date" name="date" value="{{ $date }}" class="so-field-input">
                </div>
                <div class="so-field" style="flex:0 0 165px;min-width:140px;">
                    <label class="so-field-label">Pengiriman</label>
                    <select name="delivery" class="so-field-input">
                        <option value="">Semua</option>
                        <option value="today" {{ ($deliveryFilter ?? '') === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="tomorrow" {{ ($deliveryFilter ?? '') === 'tomorrow' ? 'selected' : '' }}>Besok</option>
                        <option value="week" {{ ($deliveryFilter ?? '') === 'week' ? 'selected' : '' }}>7 Hari Ke Depan</option>
                        <option value="overdue" {{ ($deliveryFilter ?? '') === 'overdue' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
                <button type="submit" class="so-btn-apply">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Terapkan
                </button>
                @if($search || $status || $date || ($deliveryFilter ?? ''))
                <a href="{{ route('sales-order.index') }}" class="so-btn-reset">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                    Reset
                </a>
                @endif
            </form>
        </div>

        {{-- ─── TABLE ─── --}}
        <div class="so-card">
            <div class="so-card-hdr">
                <div>
                    <div class="so-card-title">Data Sales Order</div>
                    <div class="so-card-sub">{{ $salesOrders->total() }} data ditemukan</div>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="so-tbl">
                    <thead>
                        <tr>
                            <th>No. SO</th>
                            <th>Tgl Order</th>
                            <th>Tgl Kirim</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesOrders as $so)
                        <tr>
                            <td>
                                <a href="{{ route('sales-order.show', $so->id) }}" class="so-number">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    {{ $so->so_number }}
                                </a>
                                <div class="so-by">Oleh: {{ $so->user->name ?? '-' }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($so->order_date)->format('d M Y') }}</td>
                            <td>
                                @if($so->delivery_date)
                                    @php
                                        $dlvDate = \Carbon\Carbon::parse($so->delivery_date);
                                        $today = \Carbon\Carbon::today();
                                        $tomorrow = \Carbon\Carbon::tomorrow();
                                        if ($so->status === 'completed') {
                                            $dlvClass = 'so-dlv-done';
                                            $dlvLabel = 'Selesai';
                                        } elseif ($dlvDate->isSameDay($today)) {
                                            $dlvClass = 'so-dlv-today';
                                            $dlvLabel = 'Hari Ini';
                                        } elseif ($dlvDate->isSameDay($tomorrow)) {
                                            $dlvClass = 'so-dlv-tomorrow';
                                            $dlvLabel = 'Besok';
                                        } elseif ($dlvDate->lt($today)) {
                                            $dlvClass = 'so-dlv-overdue';
                                            $dlvLabel = 'Terlambat ' . $dlvDate->diffInDays($today) . ' hari';
                                        } else {
                                            $dlvClass = 'so-dlv-future';
                                            $dlvLabel = $dlvDate->format('d M Y');
                                        }
                                    @endphp
                                    <span class="so-dlv {{ $dlvClass }}">{{ $dlvDate->format('d M') }} &middot; {{ $dlvLabel }}</span>
                                @else
                                    <span class="so-dlv so-dlv-future">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="so-cust-name">{{ $so->customer->name ?? '-' }}</div>
                                @if($so->customer && $so->customer->phone)
                                <div class="so-cust-phone">{{ $so->customer->phone }}</div>
                                @endif
                            </td>
                            <td class="so-amount">Rp {{ number_format($so->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="so-status so-status-{{ $so->status }}">
                                    {{ ucfirst($so->status) }}
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <div class="so-actions">
                                    <a href="{{ route('sales-order.show', $so->id) }}" class="so-act so-act-view">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Detail
                                    </a>
                                    @can('edit_sales_order')
                                    @if(!in_array($so->status, ['completed', 'cancelled']))
                                    <a href="{{ route('sales-order.edit', $so->id) }}" class="so-act so-act-edit">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit
                                    </a>
                                    @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="so-empty">
                                    <div class="so-empty-ico">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    </div>
                                    <div class="so-empty-title">Belum ada Sales Order</div>
                                    <div class="so-empty-sub">
                                        @if($search || $status || $date || ($deliveryFilter ?? ''))
                                            Tidak ada data yang cocok dengan filter Anda. Coba ubah kata kunci atau reset filter.
                                        @else
                                            Buat Sales Order baru untuk mulai mengelola pesanan pelanggan.
                                        @endif
                                    </div>
                                    <div class="so-empty-actions">
                                        @if($search || $status || $date || ($deliveryFilter ?? ''))
                                            <a href="{{ route('sales-order.index') }}" class="so-btn-reset">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                                Hapus Filter
                                            </a>
                                        @else
                                            @can('create_sales_order')
                                            <a href="{{ route('sales-order.create') }}" class="so-create-btn">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                                Buat SO Baru
                                            </a>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($salesOrders->hasPages())
            <div class="so-pagination">
                {{ $salesOrders->links() }}
            </div>
            @endif
        </div>

    </div>
</x-app-layout>
