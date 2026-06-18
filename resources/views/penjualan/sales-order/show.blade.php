<x-app-layout>
    <x-slot name="header">Detail Sales Order {{ $salesOrder->so_number }}</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .so-pg{background:linear-gradient(135deg,#f0f4ff 0%,#f8fafc 50%,#f0fdf4 100%);min-height:calc(100vh - 64px);padding:2rem 1.5rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;}
        .so-wrap{max-width:1100px;margin:0 auto;}

        .so-back{display:inline-flex;align-items:center;gap:6px;font-size:.82rem;font-weight:600;color:#64748b;text-decoration:none;margin-bottom:1.25rem;transition:.2s;padding:6px 12px;border-radius:8px;background:rgba(255,255,255,.7);backdrop-filter:blur(4px);}
        .so-back:hover{color:#0f172a;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.06);}

        .so-title-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
        .so-title-area{display:flex;align-items:center;gap:1rem;}
        .so-title-icon{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(99,102,241,.3);}
        .so-title-text h1{font-size:1.4rem;font-weight:800;color:#0f172a;margin:0;letter-spacing:-.02em;}
        .so-title-text p{font-size:.82rem;color:#64748b;margin:2px 0 0;}
        .so-title-actions{display:flex;gap:.5rem;flex-wrap:wrap;}
        .so-btn{display:inline-flex;align-items:center;gap:6px;padding:.55rem 1rem;border-radius:10px;font-size:.8rem;font-weight:700;cursor:pointer;transition:.2s;border:none;text-decoration:none;font-family:inherit;}
        .so-btn-edit{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;box-shadow:0 2px 8px rgba(245,158,11,.25);}
        .so-btn-edit:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(245,158,11,.35);}
        .so-btn-print{background:#fff;color:#475569;border:1.5px solid #e2e8f0;}
        .so-btn-print:hover{background:#f8fafc;}
        .so-btn-del{background:#fff;color:#dc2626;border:1.5px solid #fecaca;}
        .so-btn-del:hover{background:#fef2f2;}
        .so-so-badge{background:#eef2ff;color:#4f46e5;padding:6px 14px;border-radius:8px;font-size:.82rem;font-weight:800;}

        .so-layout{display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;}

        .so-card{background:#fff;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,.04);overflow:hidden;margin-bottom:1.25rem;transition:box-shadow .2s;}
        .so-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.06);}
        .so-card-hdr{padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:.75rem;}
        .so-card-hdr-left{display:flex;align-items:center;gap:.75rem;}
        .so-card-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .so-card-icon.blue{background:#eff6ff;color:#3b82f6;}
        .so-card-icon.green{background:#f0fdf4;color:#10b981;}
        .so-card-icon.purple{background:#f5f3ff;color:#7c3aed;}
        .so-card-icon.amber{background:#fffbeb;color:#d97706;}
        .so-card-title{font-size:.95rem;font-weight:800;color:#0f172a;margin:0;}
        .so-card-sub{font-size:.75rem;color:#94a3b8;margin:1px 0 0;}
        .so-card-body{padding:1.5rem;}

        /* ── status ── */
        .so-status{font-size:.75rem;font-weight:700;padding:5px 12px;border-radius:8px;display:inline-flex;align-items:center;gap:5px;}
        .so-status::before{content:'';width:7px;height:7px;border-radius:50%;}
        .so-status-draft{background:#fef3c7;color:#92400e;}.so-status-draft::before{background:#d97706;}
        .so-status-confirmed{background:#dbeafe;color:#1e40af;}.so-status-confirmed::before{background:#2563eb;}
        .so-status-processing{background:#e0e7ff;color:#3730a3;}.so-status-processing::before{background:#6366f1;}
        .so-status-completed{background:#d1fae5;color:#065f46;}.so-status-completed::before{background:#059669;}
        .so-status-cancelled{background:#ffe4e6;color:#9f1239;}.so-status-cancelled::before{background:#e11d48;}

        /* ── info grid ── */
        .so-info{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;}
        .so-info-item{display:flex;flex-direction:column;gap:3px;}
        .so-info-lbl{font-size:.68rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;}
        .so-info-val{font-size:.9rem;font-weight:700;color:#0f172a;}
        .so-info-sub{font-size:.78rem;color:#64748b;}
        .so-divider{border:none;border-top:1px solid #f1f5f9;margin:1rem 0;}
        .so-notes-box{background:#fffbeb;border:1.5px solid #fef08a;border-radius:10px;padding:.85rem 1rem;font-size:.84rem;color:#713f12;white-space:pre-line;}
        .so-notes-lbl{font-size:.68rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;}

        /* ── items ── */
        .so-items{display:flex;flex-direction:column;gap:.65rem;}
        .so-item{padding:.85rem 1rem;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;display:flex;align-items:center;gap:.75rem;transition:.15s;}
        .so-item:hover{border-color:#e2e8f0;background:#fafbff;}
        .so-item-num{width:28px;height:28px;border-radius:8px;background:#eef2ff;color:#4f46e5;display:flex;align-items:center;justify-content:center;font-size:.73rem;font-weight:800;flex-shrink:0;}
        .so-item-info{flex:1;min-width:0;}
        .so-item-name{font-weight:700;color:#0f172a;font-size:.87rem;word-break:break-word;}
        .so-item-meta{font-size:.72rem;color:#94a3b8;margin-top:2px;}
        .so-item-qty{font-size:.8rem;color:#64748b;font-weight:600;white-space:nowrap;}
        .so-item-price{font-size:.82rem;color:#64748b;font-weight:600;text-align:right;white-space:nowrap;}
        .so-item-sub{font-weight:800;color:#4f46e5;font-size:.92rem;white-space:nowrap;min-width:100px;text-align:right;}

        /* ── sidebar ── */
        .so-summary{position:sticky;top:1rem;}
        .so-sum-card{background:#fff;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,.04);overflow:hidden;margin-bottom:1rem;}
        .so-sum-hdr{padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:.6rem;}
        .so-sum-title{font-size:.88rem;font-weight:800;color:#0f172a;}
        .so-sum-body{padding:1.25rem;}
        .so-sum-row{display:flex;justify-content:space-between;align-items:center;padding:.45rem 0;font-size:.82rem;}
        .so-sum-row.total{border-top:2px solid #e2e8f0;margin-top:.5rem;padding-top:.75rem;}
        .so-sum-lbl{color:#64748b;font-weight:600;}
        .so-sum-val{font-weight:800;color:#0f172a;}
        .so-sum-val.grand{font-size:1.15rem;color:#4f46e5;}

        /* ── delivery badge ── */
        .so-dlv{font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:6px;display:inline-flex;align-items:center;gap:4px;}
        .so-dlv-today{background:#fef3c7;color:#92400e;}
        .so-dlv-tomorrow{background:#dbeafe;color:#1e40af;}
        .so-dlv-overdue{background:#fee2e2;color:#991b1b;}
        .so-dlv-future{background:#f1f5f9;color:#475569;}
        .so-dlv-done{background:#d1fae5;color:#065f46;}

        /* ── status change ── */
        .so-status-sel{padding:.5rem .75rem;border:1.5px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:.82rem;font-weight:700;color:#0f172a;background:#f8fafc;outline:none;cursor:pointer;transition:.2s;width:100%;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;padding-right:30px;}
        .so-status-sel:focus{border-color:#6366f1;background:#fff;box-shadow:0 0 0 3px rgba(99,102,241,.08);}
        .so-status-btn{display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:.6rem;border-radius:10px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;font-weight:700;font-size:.82rem;cursor:pointer;border:none;transition:.2s;font-family:inherit;margin-top:.5rem;box-shadow:0 2px 8px rgba(99,102,241,.2);}
        .so-status-btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(99,102,241,.3);}
        .so-status-btn:disabled{opacity:.5;cursor:not-allowed;transform:none;}

        .so-cancel-lnk{display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:.65rem;border-radius:10px;background:#f8fafc;color:#64748b;font-weight:600;font-size:.82rem;cursor:pointer;border:1.5px solid #e2e8f0;transition:.2s;font-family:inherit;margin-top:.5rem;text-decoration:none;}
        .so-cancel-lnk:hover{background:#f1f5f9;color:#0f172a;}

        /* ── alert ── */
        .so-alert{padding:.85rem 1.15rem;border-radius:12px;margin-bottom:1.25rem;font-size:.84rem;display:flex;gap:.65rem;align-items:center;}
        .so-alert-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;}

        @media(max-width:900px){.so-layout{grid-template-columns:1fr;}.so-summary{position:static;}}
        @media(max-width:640px){
            .so-pg{padding:1rem;}
            .so-info{grid-template-columns:1fr;}
            .so-item{flex-wrap:wrap;}
            .so-card-body{padding:1rem;}
            .so-title-bar{flex-direction:column;align-items:flex-start;}
        }

        /* ── print: invoice style ── */
        @media print{
            *{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;}
            html,body{margin:0;padding:0;background:#fff;font-size:11pt;font-family:'Arial',sans-serif;color:#000;}
            .so-back,.so-title-actions,.so-summary,.so-alert,.so-status-sel,.so-status-btn{display:none!important;}
            .so-pg{padding:0!important;background:#fff!important;}
            .so-wrap{max-width:100%!important;}
            .so-layout{display:block!important;}
            .so-card{border:none!important;box-shadow:none!important;border-radius:0!important;}
            .so-card-hdr,.so-card:hover{box-shadow:none!important;}
            .so-item{border:none!important;background:#fff!important;padding:.4rem 0!important;border-radius:0!important;}
            .so-item:hover{background:#fff!important;}
            @page{margin:2cm 1.5cm;}
        }
    </style>
    @endpush

    <div class="so-pg">
        <div class="so-wrap">
            <a href="{{ route('sales-order.index') }}" class="so-back">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar
            </a>

            @if(session('success'))
                <div class="so-alert so-alert-success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="so-title-bar">
                <div class="so-title-area">
                    <div class="so-title-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    </div>
                    <div class="so-title-text">
                        <h1>Detail Sales Order</h1>
                        <p>Informasi lengkap pesanan dan daftar barang.</p>
                    </div>
                </div>
                <div class="so-title-actions">
                    <span class="so-so-badge">{{ $salesOrder->so_number }}</span>
                    <span class="so-status so-status-{{ $salesOrder->status }}">{{ ucfirst($salesOrder->status) }}</span>
                    <button type="button" onclick="window.print()" class="so-btn so-btn-print">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak
                    </button>
                    @if(!in_array($salesOrder->status, ['completed', 'cancelled']))
                        <a href="{{ route('sales-order.edit', $salesOrder->id) }}" class="so-btn so-btn-edit">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Edit
                        </a>
                    @endif
                </div>
            </div>

            <form action="{{ route('sales-order.update', $salesOrder->id) }}" method="POST" id="statusForm" style="display:none;">
                @csrf @method('PUT')
                <input type="hidden" name="status" id="statusInput">
                <input type="hidden" name="customer_id" value="{{ $salesOrder->customer_id }}">
                <input type="hidden" name="order_date" value="{{ $salesOrder->order_date ? \Carbon\Carbon::parse($salesOrder->order_date)->format('Y-m-d') : '' }}">
                <input type="hidden" name="delivery_date" value="{{ $salesOrder->delivery_date ? \Carbon\Carbon::parse($salesOrder->delivery_date)->format('Y-m-d') : '' }}">
                <input type="hidden" name="notes" value="{{ $salesOrder->notes }}">
                @foreach($salesOrder->items as $idx => $item)
                    <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $item->product_id }}">
                    <input type="hidden" name="items[{{ $idx }}][quantity]" value="{{ $item->quantity }}">
                    <input type="hidden" name="items[{{ $idx }}][price]" value="{{ $item->price }}">
                @endforeach
            </form>

            <div class="so-layout">
                {{-- ─── LEFT COLUMN ─── --}}
                <div class="so-main">
                    {{-- Order Info --}}
                    <div class="so-card">
                        <div class="so-card-hdr">
                            <div class="so-card-hdr-left">
                                <div class="so-card-icon blue">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                                <div>
                                    <div class="so-card-title">Informasi Order</div>
                                    <div class="so-card-sub">Detail pelanggan dan tanggal pesanan</div>
                                </div>
                            </div>
                        </div>
                        <div class="so-card-body">
                            <div class="so-info">
                                <div class="so-info-item">
                                    <div class="so-info-lbl">Pelanggan</div>
                                    <div class="so-info-val">{{ $salesOrder->customer?->name ?? '-' }}</div>
                                    @if($salesOrder->customer?->phone)
                                        <div class="so-info-sub">{{ $salesOrder->customer->phone }}</div>
                                    @endif
                                </div>
                                <div class="so-info-item">
                                    <div class="so-info-lbl">Dibuat Oleh</div>
                                    <div class="so-info-val">{{ $salesOrder->user?->name ?? '-' }}</div>
                                    <div class="so-info-sub">{{ \Carbon\Carbon::parse($salesOrder->created_at)->format('d M Y, H:i') }}</div>
                                </div>
                                <div class="so-info-item">
                                    <div class="so-info-lbl">Tanggal Order</div>
                                    <div class="so-info-val">{{ \Carbon\Carbon::parse($salesOrder->order_date)->translatedFormat('d F Y') }}</div>
                                </div>
                                <div class="so-info-item">
                                    <div class="so-info-lbl">Tanggal Kirim</div>
                                    <div class="so-info-val">
                                        @if($salesOrder->delivery_date)
                                            {{ \Carbon\Carbon::parse($salesOrder->delivery_date)->translatedFormat('d F Y') }}
                                            @php
                                                $dlvDate = \Carbon\Carbon::parse($salesOrder->delivery_date);
                                                if ($salesOrder->status === 'completed') {
                                                    echo '<span class="so-dlv so-dlv-done">Selesai</span>';
                                                } elseif ($dlvDate->isSameDay(\Carbon\Carbon::today())) {
                                                    echo '<span class="so-dlv so-dlv-today">Hari Ini</span>';
                                                } elseif ($dlvDate->isSameDay(\Carbon\Carbon::tomorrow())) {
                                                    echo '<span class="so-dlv so-dlv-tomorrow">Besok</span>';
                                                } elseif ($dlvDate->lt(\Carbon\Carbon::today())) {
                                                    echo '<span class="so-dlv so-dlv-overdue">Terlambat</span>';
                                                }
                                            @endphp
                                        @else
                                            <span style="color:#94a3b8;">Belum ditentukan</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($salesOrder->notes)
                                <hr class="so-divider">
                                <div class="so-notes-box">
                                    <div class="so-notes-lbl">Catatan</div>
                                    {{ $salesOrder->notes }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="so-card">
                        <div class="so-card-hdr">
                            <div class="so-card-hdr-left">
                                <div class="so-card-icon green">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                </div>
                                <div>
                                    <div class="so-card-title">Daftar Barang</div>
                                    <div class="so-card-sub">{{ $salesOrder->items->count() }} barang &middot; Total Qty {{ $salesOrder->items->sum('quantity') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="so-card-body">
                            @if($salesOrder->items->count() > 0)
                                <div class="so-items">
                                    @foreach($salesOrder->items as $idx => $item)
                                        <div class="so-item">
                                            <div class="so-item-num">{{ $idx + 1 }}</div>
                                            <div class="so-item-info">
                                                <div class="so-item-name">{{ $item->product?->name ?? ('Produk ID ' . $item->product_id) }}</div>
                                                @if($item->product?->sku || $item->product?->barcode)
                                                    <div class="so-item-meta">{{ $item->product?->sku ?? '' }} {{ $item->product?->barcode ? '('.$item->product->barcode.')' : '' }}</div>
                                                @endif
                                            </div>
                                            @php
                                                $unitName = $item->unit_name ?? ($item->product?->unit?->name ?? 'pcs');
                                                $displayQty = ($item->unit_factor && $item->unit_factor > 1)
                                                    ? $item->quantity / $item->unit_factor
                                                    : $item->quantity;
                                                $displayQtyStr = is_float($displayQty) && $displayQty != (int)$displayQty
                                                    ? number_format($displayQty, 1, ',', '.')
                                                    : number_format($displayQty, 0, ',', '.');
                                            @endphp
                                            <div class="so-item-qty">{{ $displayQtyStr }} {{ $unitName }}</div>
                                            <div class="so-item-price">Rp {{ number_format($item->price, 0, ',', '.') }} /{{ $unitName }}</div>
                                            <div class="so-item-sub">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div style="text-align:center;padding:2rem;color:#94a3b8;">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin:0 auto 12px;display:block;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                                    <div style="font-weight:700;color:#0f172a;">Belum ada barang</div>
                                    <div style="font-size:.82rem;">Klik Edit untuk menambahkan barang.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ─── RIGHT SIDEBAR ─── --}}
                <div class="so-summary">
                    <div class="so-sum-card">
                        <div class="so-sum-hdr">
                            <div class="so-card-icon purple" style="width:34px;height:34px;border-radius:9px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            </div>
                            <div class="so-sum-title">Ringkasan</div>
                        </div>
                        <div class="so-sum-body">
                            <div class="so-sum-row">
                                <span class="so-sum-lbl">Jumlah Barang</span>
                                <span class="so-sum-val">{{ $salesOrder->items->count() }}</span>
                            </div>
                            <div class="so-sum-row">
                                <span class="so-sum-lbl">Total Qty</span>
                                <span class="so-sum-val">{{ $salesOrder->items->sum('quantity') }}</span>
                            </div>
                            <div class="so-sum-row total">
                                <span class="so-sum-lbl" style="font-weight:700;color:#0f172a;">Grand Total</span>
                                <span class="so-sum-val grand">Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Status Change --}}
                    @if(!in_array($salesOrder->status, ['completed', 'cancelled']))
                        <div class="so-sum-card">
                            <div class="so-sum-hdr">
                                <div class="so-card-icon amber" style="width:34px;height:34px;border-radius:9px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                </div>
                                <div class="so-sum-title">Ubah Status</div>
                            </div>
                            <div class="so-sum-body">
                                <select id="quickStatus" class="so-status-sel">
                                    <option value="draft" {{ $salesOrder->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="confirmed" {{ $salesOrder->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="processing" {{ $salesOrder->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ $salesOrder->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                    <option value="cancelled" {{ $salesOrder->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
                                </select>
                                <button type="button" class="so-status-btn" id="btnChangeStatus" onclick="changeStatus()">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    Update Status
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Delete --}}
                    @if(!in_array($salesOrder->status, ['completed']))
                        <form action="{{ route('sales-order.destroy', $salesOrder->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Sales Order ini? Data tidak bisa dikembalikan.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="so-cancel-lnk" style="color:#dc2626;border-color:#fecaca;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                Hapus Sales Order
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('sales-order.index') }}" class="so-cancel-lnk">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function changeStatus() {
            var sel = document.getElementById('quickStatus');
            var newStatus = sel.value;
            var currentStatus = '{{ $salesOrder->status }}';

            if (newStatus === currentStatus) {
                alert('Status sudah sama, tidak ada perubahan.');
                return;
            }

            var labels = {
                'draft': 'Draft',
                'confirmed': 'Confirmed',
                'processing': 'Processing',
                'completed': 'Completed (Selesai)',
                'cancelled': 'Cancelled (Batal)'
            };

            if (!confirm('Ubah status dari "' + labels[currentStatus] + '" ke "' + labels[newStatus] + '"?')) {
                return;
            }

            // If changing to completed or cancelled, warn user
            if (newStatus === 'completed') {
                if (!confirm('Status akan diubah menjadi SELESAI. Pesanan dianggap sudah dikirim. Lanjutkan?')) {
                    return;
                }
            }
            if (newStatus === 'cancelled') {
                if (!confirm('Status akan diubah menjadi DIBATALKAN. Pesanan akan dibatalkan. Lanjutkan?')) {
                    return;
                }
            }

            document.getElementById('statusInput').value = newStatus;
            document.getElementById('statusForm').submit();
        }
    </script>
    @endpush
</x-app-layout>
