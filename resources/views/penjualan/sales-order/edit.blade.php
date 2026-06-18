<x-app-layout>
    <x-slot name="header">Edit Sales Order #{{ $salesOrder->so_number }}</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .so-pg{background:linear-gradient(135deg,#f0f4ff 0%,#f8fafc 50%,#f0fdf4 100%);min-height:calc(100vh - 64px);padding:2rem 1.5rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;}
        .so-wrap{max-width:1100px;margin:0 auto;}

        .so-back{display:inline-flex;align-items:center;gap:6px;font-size:.82rem;font-weight:600;color:#64748b;text-decoration:none;margin-bottom:1.25rem;transition:.2s;padding:6px 12px;border-radius:8px;background:rgba(255,255,255,.7);backdrop-filter:blur(4px);}
        .so-back:hover{color:#0f172a;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.06);}

        .so-title-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
        .so-title-area{display:flex;align-items:center;gap:1rem;}
        .so-title-icon{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(245,158,11,.3);}
        .so-title-text h1{font-size:1.4rem;font-weight:800;color:#0f172a;margin:0;letter-spacing:-.02em;}
        .so-title-text p{font-size:.82rem;color:#64748b;margin:2px 0 0;}
        .so-so-badge{background:#eef2ff;color:#4f46e5;padding:6px 14px;border-radius:8px;font-size:.82rem;font-weight:800;letter-spacing:.01em;}

        .so-layout{display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;}

        .so-card{background:#fff;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,.04);overflow:hidden;margin-bottom:1.25rem;transition:box-shadow .2s;}
        .so-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.06);}
        .so-card-hdr{padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:.75rem;}
        .so-card-hdr-left{display:flex;align-items:center;gap:.75rem;}
        .so-card-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .so-card-icon.blue{background:#eff6ff;color:#3b82f6;}
        .so-card-icon.green{background:#f0fdf4;color:#10b981;}
        .so-card-icon.purple{background:#f5f3ff;color:#7c3aed;}
        .so-card-title{font-size:.95rem;font-weight:800;color:#0f172a;margin:0;}
        .so-card-sub{font-size:.75rem;color:#94a3b8;margin:1px 0 0;}
        .so-card-body{padding:1.5rem;}

        .so-g2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
        .so-g2-1{display:grid;grid-template-columns:2fr 1fr;gap:1rem;}

        .so-fld{display:flex;flex-direction:column;gap:5px;}
        .so-lbl{font-size:.78rem;font-weight:700;color:#475569;letter-spacing:.02em;display:flex;align-items:center;gap:4px;}
        .so-lbl .req{color:#ef4444;}
        .so-inp{width:100%;padding:.6rem .85rem;border:1.5px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:.84rem;color:#0f172a;background:#f8fafc;outline:none;transition:.2s;}
        .so-inp:focus{border-color:#6366f1;background:#fff;box-shadow:0 0 0 3px rgba(99,102,241,.08);}
        .so-inp:hover:not(:focus){border-color:#cbd5e1;}
        select.so-inp{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:32px;}
        textarea.so-inp{resize:vertical;min-height:80px;}
        .so-err-msg{font-size:.72rem;color:#ef4444;margin-top:2px;}

        /* ── item rows (2-line layout) ── */
        .so-items{display:flex;flex-direction:column;gap:.75rem;}
        .so-item{padding:1rem;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;transition:.2s;}
        .so-item:hover{border-color:#c7d2fe;background:#fafbff;}
        .so-item-top{display:flex;align-items:center;gap:.75rem;}
        .so-item-num{width:28px;height:28px;border-radius:8px;background:#eef2ff;color:#4f46e5;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;flex-shrink:0;}
        .so-item-info{flex:1;min-width:0;}
        .so-item-name{font-weight:700;color:#0f172a;font-size:.88rem;word-break:break-word;}
        .so-item-actions{display:flex;align-items:center;gap:.75rem;flex-shrink:0;}
        .so-item-sub{font-weight:800;color:#4f46e5;font-size:.95rem;white-space:nowrap;}
        .so-item-del{width:32px;height:32px;border-radius:8px;border:1.5px solid #fecaca;background:#fff;color:#ef4444;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:.2s;flex-shrink:0;}
        .so-item-del:hover{background:#ef4444;color:#fff;border-color:#ef4444;transform:scale(1.05);}
        .so-item-bottom{display:flex;align-items:center;gap:.5rem;margin-top:.65rem;padding-top:.65rem;border-top:1px dashed #e2e8f0;flex-wrap:wrap;}
        .so-item-field{display:flex;flex-direction:column;gap:2px;}
        .so-item-field-label{font-size:.65rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;}
        .so-item-field .so-inp{padding:.4rem .6rem;font-size:.8rem;}
        .so-item-qty{width:72px;text-align:center;}
        .so-item-price{width:120px;text-align:right;}
        .so-item-x{color:#cbd5e1;font-size:.8rem;font-weight:800;padding-top:16px;}

        .so-empty{text-align:center;padding:3rem 1.5rem;}
        .so-empty-icon{width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,#eff6ff,#f0fdf4);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;}
        .so-empty-title{font-size:.95rem;font-weight:800;color:#0f172a;margin-bottom:4px;}
        .so-empty-sub{font-size:.82rem;color:#64748b;max-width:300px;margin:0 auto;}
        .so-empty-hint{display:inline-flex;align-items:center;gap:6px;margin-top:.75rem;font-size:.72rem;font-weight:700;color:#6366f1;background:#eef2ff;padding:6px 14px;border-radius:8px;}

        .so-add-btn{display:flex;align-items:center;justify-content:center;gap:8px;padding:.85rem;border:2px dashed #c7d2fe;border-radius:12px;background:transparent;color:#6366f1;font-weight:700;font-size:.85rem;cursor:pointer;transition:.2s;font-family:inherit;width:100%;}
        .so-add-btn:hover{background:#eef2ff;border-color:#6366f1;}
        .so-add-btn kbd{background:#e0e7ff;padding:2px 6px;border-radius:4px;font-size:.7rem;font-weight:800;}

        /* ── sidebar summary ── */
        .so-summary{position:sticky;top:1rem;}
        .so-sum-card{background:#fff;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,.04);overflow:hidden;}
        .so-sum-hdr{padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:.6rem;}
        .so-sum-title{font-size:.88rem;font-weight:800;color:#0f172a;}
        .so-sum-body{padding:1.25rem;}
        .so-sum-row{display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;font-size:.82rem;}
        .so-sum-row.total{border-top:2px solid #e2e8f0;margin-top:.5rem;padding-top:.85rem;}
        .so-sum-lbl{color:#64748b;font-weight:600;}
        .so-sum-val{font-weight:800;color:#0f172a;}
        .so-sum-val.grand{font-size:1.2rem;color:#4f46e5;}

        .so-submit-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:.85rem;border-radius:12px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;font-weight:800;font-size:.92rem;cursor:pointer;border:none;transition:.2s;font-family:inherit;margin-top:1rem;box-shadow:0 4px 14px rgba(245,158,11,.3);}
        .so-submit-btn:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(245,158,11,.4);}
        .so-submit-btn:active{transform:translateY(0);}

        .so-cancel-btn{display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:.7rem;border-radius:10px;background:#f8fafc;color:#64748b;font-weight:600;font-size:.82rem;cursor:pointer;border:1.5px solid #e2e8f0;transition:.2s;font-family:inherit;margin-top:.5rem;text-decoration:none;}
        .so-cancel-btn:hover{background:#f1f5f9;color:#0f172a;}

        /* ── status badge ── */
        .so-status-badge{display:inline-flex;align-items:center;gap:5px;font-size:.72rem;font-weight:700;padding:4px 10px;border-radius:7px;}
        .so-status-badge::before{content:'';width:6px;height:6px;border-radius:50%;}
        .so-status-draft{background:#fef3c7;color:#92400e;}.so-status-draft::before{background:#d97706;}
        .so-status-confirmed{background:#dbeafe;color:#1e40af;}.so-status-confirmed::before{background:#2563eb;}
        .so-status-processing{background:#e0e7ff;color:#3730a3;}.so-status-processing::before{background:#6366f1;}
        .so-status-completed{background:#d1fae5;color:#065f46;}.so-status-completed::before{background:#059669;}
        .so-status-cancelled{background:#ffe4e6;color:#9f1239;}.so-status-cancelled::before{background:#e11d48;}

        .so-alert{padding:1rem 1.25rem;border-radius:12px;margin-bottom:1.25rem;font-size:.84rem;display:flex;gap:.75rem;align-items:flex-start;}
        .so-alert-danger{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;}
        .so-alert ul{margin:.5rem 0 0;padding-left:1.25rem;}

        /* ── modal ── */
        .so-modal{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:1rem;z-index:1200;backdrop-filter:blur(6px);}
        .so-modal.open{display:flex;}
        .so-modal-card{width:min(640px,100%);background:#fff;border-radius:20px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 24px 80px rgba(15,23,42,.3);display:flex;flex-direction:column;max-height:80vh;animation:modalIn .2s ease;}
        @keyframes modalIn{from{opacity:0;transform:scale(.96) translateY(8px);}to{opacity:1;transform:scale(1) translateY(0);}}
        .so-modal-hdr{padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(135deg,#f8fafc,#f0f4ff);}
        .so-modal-body{padding:1.25rem 1.5rem;overflow-y:auto;flex:1;}
        .so-modal-close{width:34px;height:34px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:.2s;color:#64748b;}
        .so-modal-close:hover{background:#f1f5f9;color:#0f172a;}

        .so-search-inp{width:100%;padding:.75rem 1rem .75rem 2.5rem;border:1.5px solid #e2e8f0;border-radius:12px;font-size:.88rem;color:#0f172a;background:#f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E") no-repeat 12px center;outline:none;transition:.2s;font-family:inherit;}
        .so-search-inp:focus{border-color:#6366f1;background-color:#fff;box-shadow:0 0 0 3px rgba(99,102,241,.08);}

        .so-results{margin-top:1rem;border:1.5px solid #e2e8f0;border-radius:12px;max-height:320px;overflow-y:auto;}
        .so-result{padding:.85rem 1rem;border-bottom:1px solid #f1f5f9;cursor:pointer;display:flex;justify-content:space-between;align-items:center;transition:.15s;}
        .so-result:last-child{border-bottom:none;}
        .so-result:hover{background:#f8fafc;}
        .so-result-left{min-width:0;flex:1;}
        .so-result-name{font-weight:700;color:#0f172a;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .so-result-meta{font-size:.72rem;color:#94a3b8;margin-top:2px;display:flex;gap:.5rem;align-items:center;}
        .so-result-right{text-align:right;flex-shrink:0;margin-left:.75rem;}
        .so-result-price{font-weight:800;color:#4f46e5;font-size:.88rem;}
        .so-badge{padding:2px 8px;border-radius:6px;font-size:.68rem;font-weight:700;}
        .so-badge-green{background:#dcfce7;color:#166534;}
        .so-badge-red{background:#fee2e2;color:#b91c1c;}
        .so-search-empty{padding:2.5rem;text-align:center;color:#94a3b8;font-size:.85rem;}

        @media(max-width:900px){.so-layout{grid-template-columns:1fr;}.so-summary{position:static;}}
        @media(max-width:640px){
            .so-pg{padding:1rem;}
            .so-g2,.so-g2-1{grid-template-columns:1fr;}
            .so-item-top{flex-wrap:wrap;}
            .so-item-bottom{flex-direction:column;align-items:stretch;}
            .so-item-field .so-inp{width:100%;}
            .so-item-qty,.so-item-price{width:100%;}
            .so-item-x{display:none;}
            .so-card-body{padding:1rem;}
        }
    </style>
    @endpush

    <div class="so-pg">
        <div class="so-wrap">
            <a href="{{ route('sales-order.index') }}" class="so-back">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar
            </a>

            <div class="so-title-bar">
                <div class="so-title-area">
                    <div class="so-title-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <div class="so-title-text">
                        <h1>Edit Sales Order</h1>
                        <p>Perbarui informasi pesanan dan daftar barang.</p>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:.75rem;">
                    <span class="so-so-badge">{{ $salesOrder->so_number }}</span>
                    <span class="so-status-badge so-status-{{ $salesOrder->status }}">{{ ucfirst($salesOrder->status) }}</span>
                </div>
            </div>

            @if($errors->any())
                <div class="so-alert so-alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        <strong>Terdapat kesalahan input:</strong>
                        <ul>@foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('sales-order.update', $salesOrder->id) }}" method="POST" id="soForm">
                @csrf
                @method('PUT')
                <div class="so-layout">
                    {{-- ─── LEFT COLUMN ─── --}}
                    <div class="so-main">
                        {{-- Info Card --}}
                        <div class="so-card">
                            <div class="so-card-hdr">
                                <div class="so-card-hdr-left">
                                    <div class="so-card-icon blue">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                    <div>
                                        <div class="so-card-title">Informasi Order</div>
                                        <div class="so-card-sub">Pelanggan, tanggal, dan status pesanan</div>
                                    </div>
                                </div>
                            </div>
                            <div class="so-card-body">
                                <div class="so-g2-1" style="margin-bottom:1rem;">
                                    <div class="so-fld">
                                        <label class="so-lbl" for="customer_id">Pelanggan <span class="req">*</span></label>
                                        <select name="customer_id" id="customer_id" class="so-inp" required>
                                            <option value="">-- Pilih Pelanggan --</option>
                                            @foreach($customers as $c)
                                                <option value="{{ $c->id }}" {{ old('customer_id', $salesOrder->customer_id) == $c->id ? 'selected' : '' }}>
                                                    {{ $c->name }} {{ $c->phone ? '('.$c->phone.')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id') <span class="so-err-msg">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="so-fld">
                                        <label class="so-lbl" for="status">Status <span class="req">*</span></label>
                                        <select name="status" id="status" class="so-inp" required>
                                            <option value="draft" {{ old('status', $salesOrder->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="confirmed" {{ old('status', $salesOrder->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="processing" {{ old('status', $salesOrder->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="completed" {{ old('status', $salesOrder->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ old('status', $salesOrder->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        @error('status') <span class="so-err-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="so-g2" style="margin-bottom:1rem;">
                                    <div class="so-fld">
                                        <label class="so-lbl" for="order_date">Tanggal Order <span class="req">*</span></label>
                                        <input type="date" name="order_date" id="order_date" value="{{ old('order_date', $salesOrder->order_date ? \Carbon\Carbon::parse($salesOrder->order_date)->format('Y-m-d') : '') }}" class="so-inp" required>
                                        @error('order_date') <span class="so-err-msg">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="so-fld">
                                        <label class="so-lbl" for="delivery_date">Tanggal Kirim</label>
                                        <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date', $salesOrder->delivery_date ? \Carbon\Carbon::parse($salesOrder->delivery_date)->format('Y-m-d') : '') }}" class="so-inp">
                                        @error('delivery_date') <span class="so-err-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="so-fld">
                                    <label class="so-lbl" for="notes">Catatan</label>
                                    <textarea name="notes" id="notes" class="so-inp" placeholder="Tambahkan catatan untuk pesanan ini...">{{ old('notes', $salesOrder->notes) }}</textarea>
                                    @error('notes') <span class="so-err-msg">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Items Card --}}
                        <div class="so-card">
                            <div class="so-card-hdr">
                                <div class="so-card-hdr-left">
                                    <div class="so-card-icon green">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                    </div>
                                    <div>
                                        <div class="so-card-title">Daftar Barang</div>
                                        <div class="so-card-sub"><span id="itemCountLabel">0</span> barang ditambahkan</div>
                                    </div>
                                </div>
                                <button type="button" class="so-add-btn" style="width:auto;padding:.5rem 1rem;border:1.5px solid #c7d2fe;border-radius:10px;" onclick="openProductModal()">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    Tambah Barang <kbd>/</kbd>
                                </button>
                            </div>
                            <div class="so-card-body">
                                <div class="so-items" id="itemsContainer">
                                    <div id="emptyState" class="so-empty">
                                        <div class="so-empty-icon">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                        </div>
                                        <div class="so-empty-title">Belum ada barang</div>
                                        <div class="so-empty-sub">Klik tombol di atas atau gunakan shortcut keyboard untuk menambah barang.</div>
                                        <div class="so-empty-hint">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M12 12h.01"/></svg>
                                            Tekan <kbd style="background:#c7d2fe;padding:1px 5px;border-radius:3px;margin:0 2px;">/</kbd> untuk cari barang
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="so-add-btn" id="addItemBtn" onclick="openProductModal()" style="display:none;margin-top:.75rem;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    Tambah Barang Lagi
                                </button>
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
                                <div class="so-sum-title">Ringkasan Order</div>
                            </div>
                            <div class="so-sum-body">
                                <div class="so-sum-row">
                                    <span class="so-sum-lbl">Jumlah Barang</span>
                                    <span class="so-sum-val" id="sumItems">0</span>
                                </div>
                                <div class="so-sum-row">
                                    <span class="so-sum-lbl">Total Qty</span>
                                    <span class="so-sum-val" id="sumQty">0</span>
                                </div>
                                <div class="so-sum-row total">
                                    <span class="so-sum-lbl" style="font-size:.85rem;font-weight:700;color:#0f172a;">Grand Total</span>
                                    <span class="so-sum-val grand" id="sumGrand">Rp 0</span>
                                </div>
                                <input type="hidden" name="total_amount" id="total_amount" value="0">

                                <button type="submit" class="so-submit-btn">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Simpan Perubahan
                                </button>
                                <a href="{{ route('sales-order.index') }}" class="so-cancel-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                                    Batal & Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── PRODUCT SEARCH MODAL ─── --}}
    <div id="productModal" class="so-modal" role="dialog" aria-modal="true">
        <div class="so-modal-card">
            <div class="so-modal-hdr">
                <div>
                    <div style="font-size:1rem;font-weight:800;color:#0f172a;">Cari & Tambah Barang</div>
                    <div style="font-size:.75rem;color:#64748b;margin-top:2px;">Ketik minimal 2 karakter (Nama / SKU / Barcode)</div>
                </div>
                <button type="button" class="so-modal-close" onclick="closeProductModal()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="so-modal-body">
                <input type="text" id="searchInput" class="so-search-inp" placeholder="Cari barang..." autocomplete="off">
                <div class="so-results" id="searchResults">
                    <div class="so-search-empty">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin-bottom:8px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <div>Mulai ketik untuk mencari barang...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="application/json" id="so-existing-items-json">{{ json_encode($existingItemsForJs ?? [], JSON_UNESCAPED_UNICODE) }}</script>
    <script type="application/json" id="so-old-items-json">{{ json_encode($oldItemsForJs ?? [], JSON_UNESCAPED_UNICODE) }}</script>

    @push('scripts')
    <script>
        let orderItems = [];

        (function(){
            try {
                var exEl = document.getElementById('so-existing-items-json');
                var ex = JSON.parse(exEl ? (exEl.textContent || '[]') : '[]');
                orderItems = (ex || []).map(function(it){
                    var price = Number(it.price || 0);
                    var qty = Number(it.quantity || 1);
                    return {
                        id: Number(it.product_id),
                        name: String(it.name || ('Barang (ID: '+it.product_id+')')),
                        price: price,
                        qty: qty,
                        conversions: Array.isArray(it.conversions) ? it.conversions : [],
                        subtotal: price * qty
                    };
                });
                var oldEl = document.getElementById('so-old-items-json');
                var oldItems = JSON.parse(oldEl ? (oldEl.textContent || '[]') : '[]');
                if (Array.isArray(oldItems) && oldItems.length) {
                    orderItems = [];
                    oldItems.forEach(function(item){
                        var price = Number(item.price || 0);
                        var qty = Number(item.quantity || 1);
                        orderItems.push({
                            id: Number(item.product_id),
                            name: String(item.name || ("Barang (ID: "+item.product_id+")")),
                            price: price,
                            qty: qty,
                            conversions: Array.isArray(item.conversions) ? item.conversions : [],
                            subtotal: price * qty
                        });
                    });
                }
            } catch(e) {}
        })();

        function fmt(n) { return new Intl.NumberFormat('id-ID').format(Math.round(n)); }

        function openProductModal() {
            document.getElementById('productModal').classList.add('open');
            setTimeout(function(){ document.getElementById('searchInput').focus(); }, 150);
        }
        window.openProductModal = openProductModal;

        function closeProductModal() {
            document.getElementById('productModal').classList.remove('open');
        }

        let searchTimeout = null;
        document.getElementById('searchInput').addEventListener('input', function(e) {
            var q = e.target.value.trim();
            var rc = document.getElementById('searchResults');
            clearTimeout(searchTimeout);

            if (q.length < 2) {
                rc.innerHTML = '<div class="so-search-empty"><div>Mulai ketik untuk mencari...</div></div>';
                return;
            }

            rc.innerHTML = '<div class="so-search-empty" style="color:#6366f1;font-weight:600;">Mencari...</div>';

            searchTimeout = setTimeout(function() {
                fetch('{{ route("sales-order.products.search") }}?q=' + encodeURIComponent(q))
                    .then(function(r){ return r.json(); })
                    .then(function(data) {
                        window.latestSoSearchResults = Array.isArray(data) ? data : [];
                        if (!Array.isArray(data) || data.length === 0) {
                            rc.innerHTML = '<div class="so-search-empty" style="color:#ef4444;">Barang tidak ditemukan.</div>';
                            return;
                        }
                        var html = '';
                        data.forEach(function(item) {
                            var sn = String(item.name || '').replace(/'/g, "\\'");
                            var st = item.stock || 0;
                            var badge = st > 0
                                ? '<span class="so-badge so-badge-green">Stok: '+st+'</span>'
                                : '<span class="so-badge so-badge-red">Habis</span>';
                            html += '<div class="so-result" onclick="selectProduct('+item.id+', \''+sn+'\', '+(item.price||0)+')">'
                                + '<div class="so-result-left">'
                                + '<div class="so-result-name">'+(item.name||'-')+'</div>'
                                + '<div class="so-result-meta"><span>'+(item.sku||item.barcode||'-')+'</span></div>'
                                + '</div>'
                                + '<div class="so-result-right">'
                                + '<div class="so-result-price">Rp '+fmt(item.price||0)+'</div>'
                                + badge
                                + '</div></div>';
                        });
                        rc.innerHTML = html;
                    })
                    .catch(function(){
                        rc.innerHTML = '<div class="so-search-empty" style="color:#ef4444;">Gagal mengambil data.</div>';
                    });
            }, 300);
        });

        window.selectProduct = function(id, name, defaultPrice) {
            var existing = orderItems.find(function(i){ return i.id === id; });
            if (existing) {
                existing.qty += 1;
                existing.subtotal = existing.qty * existing.price;
            } else {
                var convs = [];
                try {
                    var found = (window.latestSoSearchResults || []).find(function(x){ return x.id === id; });
                    convs = Array.isArray(found && found.conversions) ? found.conversions : [];
                } catch (e) {}
                orderItems.push({ id:id, name:name, price:defaultPrice, qty:1, conversions:convs, subtotal:defaultPrice });
            }
            closeProductModal();
            document.getElementById('searchInput').value = '';
            document.getElementById('searchResults').innerHTML = '<div class="so-search-empty"><div>Mulai ketik untuk mencari barang...</div></div>';
            renderTable();
        };

        function updateQty(i, v) {
            var val = parseInt(v) || 1;
            orderItems[i].qty = val < 1 ? 1 : val;
            orderItems[i].subtotal = orderItems[i].qty * orderItems[i].price;
            renderTable();
        }

        function updatePrice(i, v) {
            var val = parseFloat(v) || 0;
            orderItems[i].price = val < 0 ? 0 : val;
            orderItems[i].subtotal = orderItems[i].qty * orderItems[i].price;
            renderTable();
        }

        function onUnitChange(i) {
            var sel = document.getElementById('unit-'+i);
            var factor = parseInt(sel.value) || 1;
            updateQty(i, factor);
        }

        window.removeItem = function(i) {
            orderItems.splice(i, 1);
            renderTable();
        };

        function renderTable() {
            var container = document.getElementById('itemsContainer');
            var empty = document.getElementById('emptyState');
            var addBtn = document.getElementById('addItemBtn');

            container.querySelectorAll('.so-item').forEach(function(r){ r.remove(); });

            if (orderItems.length === 0) {
                empty.style.display = '';
                addBtn.style.display = 'none';
                document.getElementById('itemCountLabel').textContent = '0';
                document.getElementById('sumItems').textContent = '0';
                document.getElementById('sumQty').textContent = '0';
                document.getElementById('sumGrand').textContent = 'Rp 0';
                document.getElementById('total_amount').value = 0;
                return;
            }

            empty.style.display = 'none';
            addBtn.style.display = '';

            var grandTotal = 0;
            var totalQty = 0;
            var html = '';

            orderItems.forEach(function(item, i) {
                grandTotal += item.subtotal;
                totalQty += item.qty;

                var unitHtml = '';
                if (item.conversions && item.conversions.length > 1) {
                    unitHtml = '<div class="so-item-field">'
                        + '<span class="so-item-field-label">Satuan</span>'
                        + '<select id="unit-'+i+'" class="so-inp" onchange="onUnitChange('+i+')" style="width:110px;">'
                        + item.conversions.slice(0,5).map(function(c){ return '<option value="'+c.factor+'">'+c.label+'</option>'; }).join('')
                        + '</select></div>';
                }

                html += '<div class="so-item">'
                    + '<div class="so-item-top">'
                    + '<div class="so-item-num">'+(i+1)+'</div>'
                    + '<div class="so-item-info">'
                    + '<div class="so-item-name">'+item.name+'</div>'
                    + '<input type="hidden" name="items['+i+'][product_id]" value="'+item.id+'">'
                    + '</div>'
                    + '<div class="so-item-actions">'
                    + '<div class="so-item-sub">Rp '+fmt(item.subtotal)+'</div>'
                    + '<button type="button" onclick="removeItem('+i+')" class="so-item-del" title="Hapus">'
                    + '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>'
                    + '</button>'
                    + '</div>'
                    + '</div>'
                    + '<div class="so-item-bottom">'
                    + '<div class="so-item-field">'
                    + '<span class="so-item-field-label">Harga</span>'
                    + '<input type="number" name="items['+i+'][price]" value="'+item.price+'" onchange="updatePrice('+i+',this.value)" class="so-inp so-item-price" min="0" step="1">'
                    + '</div>'
                    + '<span class="so-item-x">×</span>'
                    + '<div class="so-item-field">'
                    + '<span class="so-item-field-label">Qty</span>'
                    + '<input type="number" name="items['+i+'][quantity]" value="'+item.qty+'" min="1" onchange="updateQty('+i+',this.value)" class="so-inp so-item-qty">'
                    + '</div>'
                    + unitHtml
                    + '</div>'
                    + '</div>';
            });

            empty.insertAdjacentHTML('beforebegin', html);
            document.getElementById('itemCountLabel').textContent = orderItems.length;
            document.getElementById('sumItems').textContent = orderItems.length;
            document.getElementById('sumQty').textContent = totalQty;
            document.getElementById('sumGrand').textContent = 'Rp ' + fmt(grandTotal);
            document.getElementById('total_amount').value = grandTotal;
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && !e.ctrlKey && !e.metaKey && !e.altKey) {
                var tag = (e.target && e.target.tagName || '').toLowerCase();
                if (['input','textarea','select'].indexOf(tag) === -1) {
                    e.preventDefault();
                    openProductModal();
                }
            }
            if (e.key === 'Escape') { closeProductModal(); }
        });

        document.getElementById('searchInput').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                var arr = window.latestSoSearchResults || [];
                if (arr.length) {
                    var first = arr[0];
                    window.selectProduct(first.id, first.name || ('Barang '+first.id), first.price || 0);
                }
            }
        });

        document.getElementById('productModal').addEventListener('click', function(e) {
            if (e.target === this) closeProductModal();
        });

        document.getElementById('soForm').addEventListener('submit', function(e) {
            if (orderItems.length === 0) {
                e.preventDefault();
                alert('Silakan tambahkan minimal satu barang ke dalam Sales Order.');
            }
        });

        // Initial render
        renderTable();
    </script>
    @endpush
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        Edit Sales Order #{{ $salesOrder->so_number }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-6">
                <!-- Header Component for styling -->
                <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Edit Sales Order: {{ $salesOrder->so_number }}</h2>
                    <p class="text-sm text-gray-500">Perbarui informasi pesanan atau daftar barang.</p>
                </div>

                <form action="{{ route('sales-order.update', $salesOrder->id) }}" method="POST" id="soForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Left Col: Customer & Dates -->
                        <div class="space-y-4">
                            <div>
                                <label for="customer_id" class="form-label">Pelanggan <span class="text-red-500">*</span></label>
                                <select name="customer_id" id="customer_id" class="form-input mt-1" required>
                                    <option value="">Pilih Pelanggan...</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $salesOrder->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} {{ $customer->phone ? ' - '.$customer->phone : '' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('customer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="order_date" class="form-label">Tanggal Order <span class="text-red-500">*</span></label>
                                    <input type="date" name="order_date" id="order_date" value="{{ old('order_date', $salesOrder->order_date) }}" class="form-input mt-1" required>
                                    @error('order_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="delivery_date" class="form-label">Tanggal Kirim</label>
                                    <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date', $salesOrder->delivery_date) }}" class="form-input mt-1">
                                    @error('delivery_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Right Col: Status & Notes -->
                        <div class="space-y-4">
                            <div>
                                <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" class="form-input mt-1" required>
                                    <option value="draft" {{ old('status', $salesOrder->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="confirmed" {{ old('status', $salesOrder->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="processing" {{ old('status', $salesOrder->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ old('status', $salesOrder->status) == 'completed' ? 'selected' : '' }}>Completed (Selesai/Dikirim)</option>
                                    <option value="cancelled" {{ old('status', $salesOrder->status) == 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
                                </select>
                                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea name="notes" id="notes" rows="3" class="form-input mt-1">{{ old('notes', $salesOrder->notes) }}</textarea>
                                @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Daftar Barang</h3>
                            <button type="button" class="btn-sm btn-primary" onclick="window.openProductModal()">+ Tambah Barang</button>
                        </div>
                        
                        <div class="table-wrapper border border-gray-200 dark:border-gray-700 rounded-lg">
                            <table class="data-table" id="itemsTable">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="w-10">#</th>
                                        <th>Nama Barang</th>
                                        <th class="w-32">Harga (Rp)</th>
                                        <th class="w-24">QTY</th>
                                        <th class="w-32">Subtotal</th>
                                        <th class="w-16 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="emptyRow" style="display: none;">
                                        <td colspan="6" class="text-center py-6 text-gray-500 bg-white dark:bg-gray-900 border-b-0">
                                            Belum ada barang yang ditambahkan. Klik tombol "Tambah Barang".
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="border-t-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <td colspan="4" class="font-bold text-right py-3 pr-4">TOTAL KESELURUHAN:</td>
                                        <td class="font-bold text-lg text-indigo-600 dark:text-indigo-400">
                                            Rp <span id="grandTotalLabel">0</span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold mt-1" id="itemsSummaryLabel">0 item • Qty 0</div>
                                            <input type="hidden" name="total_amount" id="total_amount" value="0">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @error('items') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>
                    

                    <!-- Submit Actions -->
                    <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('sales-order.index') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary" id="btnSubmit">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Search Modal (Reused) -->
    <div id="productModal" class="fixed inset-0 z-[100] hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden transition-all transform scale-95 opacity-0" id="productModalContent">
            
            <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Cari Barang</h3>
                <button type="button" onclick="closeProductModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="Ketik nama atau kode barang..." autocomplete="off">
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto p-2" id="searchResults">
                <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    Mulai ketik di atas untuk mencari barang...
                </div>
            </div>
            
        </div>
    </div>

    <script type="application/json" id="so-existing-items-json">{{ json_encode($existingItemsForJs ?? [], JSON_UNESCAPED_UNICODE) }}</script>
    <script type="application/json" id="so-old-items-json">{{ json_encode($oldItemsForJs ?? [], JSON_UNESCAPED_UNICODE) }}</script>
    <!-- Scripts for SO Form -->
    <script>
        let orderItems = [];

        (function(){
            try {
                var exEl = document.getElementById('so-existing-items-json');
                var ex = JSON.parse(exEl ? (exEl.textContent || '[]') : '[]');
                orderItems = (ex || []).map(function(it){
                    var price = Number(it.price || 0);
                    var qty = Number(it.quantity || 1);
                    return {
                        id: Number(it.product_id),
                        name: String(it.name || ('Barang (ID: '+it.product_id+')')),
                        price: price,
                        qty: qty,
                        conversions: Array.isArray(it.conversions) ? it.conversions : [],
                        subtotal: price * qty
                    };
                });
                var oldEl = document.getElementById('so-old-items-json');
                var oldItems = JSON.parse(oldEl ? (oldEl.textContent || '[]') : '[]');
                if (Array.isArray(oldItems) && oldItems.length) {
                    orderItems = [];
                    oldItems.forEach(function(item){
                        var price = Number(item.price || 0);
                        var qty = Number(item.quantity || 1);
                        orderItems.push({
                            id: Number(item.product_id),
                            name: String(item.name || ("Barang (ID: "+item.product_id+")")),
                            price: price,
                            qty: qty,
                            conversions: Array.isArray(item.conversions) ? item.conversions : [],
                            subtotal: price * qty
                        });
                    });
                }
            } catch(e) {}
        })();

        function formatCurrency(num) {
            return new Intl.NumberFormat('id-ID').format(Math.round(num));
        }

        // --- Modal Logic ---
        function window_openProductModal() {
            const modal = document.getElementById('productModal');
            const content = document.getElementById('productModalContent');
            const input = document.getElementById('searchInput');
            
            modal.classList.remove('hidden');
            void modal.offsetWidth; // force reflow
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
            
            setTimeout(() => input.focus(), 100);
        }
        window.openProductModal = window_openProductModal;

        function closeProductModal() {
            const modal = document.getElementById('productModal');
            const content = document.getElementById('productModalContent');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('searchInput').value = '';
                document.getElementById('searchResults').innerHTML = '<div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">Mulai ketik di atas untuk mencari barang...</div>';
            }, 200);
        }

        // --- Search Logic ---
        let searchTimeout = null;
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const query = e.target.value.trim();
            const resultsContainer = document.getElementById('searchResults');
            
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                resultsContainer.innerHTML = '<div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">Ketik minimal 2 karakter...</div>';
                return;
            }
            
            resultsContainer.innerHTML = '<div class="p-8 text-center text-indigo-500 text-sm font-medium">Mencari Data...</div>';
            
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('sales-order.products.search') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        window.latestSoSearchResults = Array.isArray(data) ? data : [];
                        if(data.length === 0) {
                            resultsContainer.innerHTML = '<div class="p-8 text-center text-red-500 text-sm">Barang tidak ditemukan.</div>';
                            return;
                        }
                        
                        let html = '<ul class="divide-y divide-gray-100 dark:divide-gray-700">';
                        data.forEach(item => {
                            const byWh = Array.isArray(item.stocks_by_warehouse) ? item.stocks_by_warehouse.slice(0,3) : [];
                            const whNote = byWh.length ? ' • ' + byWh.map(r => `${r.warehouse}: ${r.stock}`).join(' | ') : '';
                            html += `
                                <li>
                                    <button type="button" onclick="selectProduct(${item.id}, '${item.name.replace(/'/g, "\\'")}', ${item.price})" 
                                        class="w-full text-left p-4 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:bg-indigo-50 dark:focus:bg-indigo-900 transition-colors flex justify-between items-center group">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">${item.name}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${item.barcode || '-'} • Stok: ${item.stock || 0}${whNote}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                                                Rp ${formatCurrency(item.price)}
                                            </span>
                                            <div class="mt-1 text-xs text-indigo-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity">Pilih ➔</div>
                                        </div>
                                    </button>
                                </li>
                            `;
                        });
                        html += '</ul>';
                        resultsContainer.innerHTML = html;
                    })
                    .catch(err => {
                        resultsContainer.innerHTML = '<div class="p-8 text-center text-red-500 text-sm">Gagal mengambil data. Error: '+err.message+'</div>';
                    });
            }, 300);
        });

        window.selectProduct = function(id, name, defaultPrice) {
            const existing = orderItems.find(item => item.id === id);
            if (existing) {
                existing.qty += 1;
                existing.subtotal = existing.qty * existing.price;
            } else {
                let convs = [];
                try {
                    const arr = window.latestSoSearchResults || [];
                    const found = arr.find(x => x.id === id);
                    convs = Array.isArray(found?.conversions) ? found.conversions : [];
                    try {
                        const pref = JSON.parse(localStorage.getItem('so_pref_unit_' + id) || 'null');
                        if (pref && Array.isArray(convs) && convs.length) {
                            convs.sort(function(a,b){
                                const af = (a.factor===pref.factor && a.label===pref.label)?-1:0;
                                const bf = (b.factor===pref.factor && b.label===pref.label)?-1:0;
                                return af - bf;
                            });
                        }
                    } catch(_) {}
                } catch(e){}
                orderItems.push({
                    id: id,
                    name: name,
                    price: defaultPrice,
                    qty: 1,
                    conversions: convs,
                    subtotal: defaultPrice * 1
                });
            }
            closeProductModal();
            renderTable();
        };
        function rememberPreferredUnit(productId, factor, label){
            try { localStorage.setItem('so_pref_unit_'+productId, JSON.stringify({factor: Number(factor)||1, label: String(label||'')})); } catch(_) {}
        }
        function updateQtyWithUnit(index, factor, label){
            var multEl = document.getElementById('mult-'+index);
            var k = parseInt(multEl ? multEl.value : '1');
            if (!Number.isFinite(k) || k < 1) k = 1;
            updateQty(index, Number(factor||1) * k);
            var item = orderItems[index];
            if (item && item.id) rememberPreferredUnit(item.id, factor, label);
        }
        function onUnitChange(index){
            var unitSel = document.getElementById('unit-'+index);
            if(!unitSel) return;
            var opt = unitSel.options[unitSel.selectedIndex];
            var factor = parseInt(unitSel.value || '1');
            var label = opt ? (opt.getAttribute('data-label') || opt.textContent || '') : '';
            updateQtyWithUnit(index, factor, label);
        }

        window.updateQty = function(index, newQty) {
            const val = parseInt(newQty);
            if (isNaN(val) || val < 1) {
                orderItems[index].qty = 1;
            } else {
                orderItems[index].qty = val;
            }
            orderItems[index].subtotal = orderItems[index].qty * orderItems[index].price;
            renderTable();
        }

        window.updatePrice = function(index, newPrice) {
            let val = parseFloat(newPrice);
            if (isNaN(val) || val < 0) val = 0;
            orderItems[index].price = val;
            orderItems[index].subtotal = orderItems[index].qty * orderItems[index].price;
            renderTable();
        }

        window.removeItem = function(index) {
            orderItems.splice(index, 1);
            renderTable();
        }

        function renderTable() {
            const emptyRow = document.getElementById('emptyRow');
            document.querySelectorAll('.item-row').forEach(row => row.remove());
            
            if (orderItems.length === 0) {
                emptyRow.style.display = 'table-row';
                document.getElementById('grandTotalLabel').innerText = '0';
                document.getElementById('total_amount').value = 0;
                return;
            }
            
            emptyRow.style.display = 'none';
            let grandTotal = 0;
            let html = '';
            
            orderItems.forEach((item, index) => {
                grandTotal += item.subtotal;
                html += `
                    <tr class="item-row bg-white border-b dark:bg-gray-900 dark:border-gray-800">
                        <td class="text-center text-sm font-medium border-b-0">${index + 1}</td>
                        <td class="border-b-0 font-medium text-gray-800 dark:text-gray-200">
                            ${item.name}
                            <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                        </td>
                        <td class="border-b-0">
                            <input type="number" name="items[${index}][price]" value="${item.price}" onchange="updatePrice(${index}, this.value)" class="form-input py-1 text-sm bg-gray-50 dark:bg-gray-800" style="width:120px">
                        </td>
                        <td class="border-b-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <input type="number" name="items[${index}][quantity]" value="${item.qty}" min="1" onchange="updateQty(${index}, this.value)" class="w-24 form-input py-1 text-sm text-center">
                                <select id="mult-${index}" class="form-input w-18" style="width:64px;height:30px;padding:0 6px;font-size:12px;">
                                    <option value="1">×1</option>
                                    <option value="2">×2</option>
                                    <option value="3">×3</option>
                                    <option value="5">×5</option>
                                </select>
                                ${Array.isArray(item.conversions) && item.conversions.length ? `
                                <select id="unit-${index}" class="form-input" onchange="onUnitChange(${index})" style="width:150px;height:30px;padding:0 6px;font-size:12px;">
                                    ${item.conversions.slice(0,6).map(c => `
                                        <option value="${c.factor}" data-label="${c.label.replace(/"/g, '&quot;')}">${c.label} (x${c.factor})</option>
                                    `).join('')}
                                </select>
                                ` : ``}
                            </div>
                        </td>
                        <td class="border-b-0 text-right font-medium">
                            Rp ${formatCurrency(item.subtotal)}
                        </td>
                        <td class="text-center border-b-0">
                            <button type="button" onclick="removeItem(${index})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            emptyRow.insertAdjacentHTML('beforebegin', html);
            let totalQty = 0;
            orderItems.forEach(it => totalQty += Number(it.qty||0));
            document.getElementById('grandTotalLabel').innerText = formatCurrency(grandTotal);
            const sumEl = document.getElementById('itemsSummaryLabel');
            if (sumEl) sumEl.textContent = `${orderItems.length} item • Qty ${totalQty}`;
            document.getElementById('total_amount').value = grandTotal;
        }

        document.getElementById('searchInput').addEventListener('keydown', function(e) {
            if(e.key === 'Enter') {
                e.preventDefault();
                var arr = window.latestSoSearchResults || [];
                if (arr.length) {
                    var first = arr[0];
                    window.selectProduct(first.id, first.name || ('Barang '+first.id), first.price || 0);
                }
            }
        });
        document.addEventListener('keydown', function(e){
            if (e.key === '/' && !e.ctrlKey && !e.metaKey && !e.altKey) {
                var tag = (e.target && e.target.tagName || '').toLowerCase();
                if (['input', 'textarea', 'select'].indexOf(tag) === -1) {
                    e.preventDefault();
                    window.openProductModal();
                }
            }
        });

        document.getElementById('soForm').addEventListener('submit', function(e) {
            if (orderItems.length === 0) {
                e.preventDefault();
                alert('Silakan tambahkan minimal satu barang ke dalam Sales Order.');
            }
        });

        // initial render
        renderTable();
    </script>
</x-app-layout>
