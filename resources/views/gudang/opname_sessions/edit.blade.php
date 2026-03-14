<x-app-layout>
    <x-slot name="header">Input Opname</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Sesi Aktif</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-warning">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        </div>
                        Input Item Opname
                    </h1>
                    <div class="tr-session-meta">
                        <span class="tr-meta-item">Gudang: <strong>{{ $session->warehouse?->name }}</strong></span>
                        <span class="tr-dot-divider">•</span>
                        <span class="tr-meta-item">
                            Status: 
                            <span class="tr-status-text {{ 'status-' . strtolower($session->status) }}">{{ strtoupper($session->status) }}</span>
                        </span>
                    </div>
                    @if($session->notes)
                        <div class="tr-session-notes">"{{ $session->notes }}"</div>
                    @endif
                </div>
                
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.opname_sessions.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali
                    </a>

                    @if($session->status === 'draft')
                        <form method="POST" action="{{ route('gudang.opname_sessions.submit', $session) }}" onsubmit="return confirm('Kirim sesi ini untuk approval Supervisor? Setelah disubmit, data tidak bisa diubah lagi.');" style="display:inline;">
                            @csrf
                            <button type="submit" class="tr-btn tr-btn-info">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                Submit Approval
                            </button>
                        </form>
                    @endif

                    @if($session->status === 'submitted' && strtolower(auth()->user()->role) === 'supervisor')
                        <a href="{{ route('gudang.opname_approval.show', $session) }}" class="tr-btn tr-btn-success">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            Review & Setujui
                        </a>
                    @endif
                </div>
            </div>

            {{-- ALERTS --}}
            @if(session('success'))
                <div class="tr-alert tr-alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error')) 
                <div class="tr-alert tr-alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    {{ session('error') }}
                </div> 
            @endif

            {{-- ACTION BAR (INPUT AREA) --}}
            <div class="tr-card">
                <div class="tr-card-header tr-action-bar {{ $session->status !== 'draft' ? 'is-disabled' : '' }}">
                    <div class="tr-input-grid">
                        
                        <div class="tr-form-group">
                            <label class="tr-label">Scan Barcode / SKU</label>
                            <div class="tr-input-with-icon">
                                <svg class="tr-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z"></path><polyline points="10 9 10 15"></polyline><polyline points="14 9 14 15"></polyline></svg>
                                <input type="text" id="scanInput" class="tr-input" placeholder="Scan barcode..." {{ $session->status !== 'draft' ? 'disabled' : '' }} autofocus>
                            </div>
                        </div>

                        <div class="tr-form-group tr-col-product">
                            <label class="tr-label">Atau Pilih Manual Produk</label>
                            <div class="tr-select-wrapper">
                                <select id="productSelect" class="tr-select" {{ $session->status !== 'draft' ? 'disabled' : '' }}>
                                    <option value="">-- Daftar Produk --</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}">{{ $p->sku }} — {{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="tr-form-group tr-col-qty">
                            <label class="tr-label">Qty Fisik <span class="tr-req">*</span></label>
                            <input type="number" id="physicalQty" min="0" class="tr-input tr-input-center tr-font-bold" value="0" {{ $session->status !== 'draft' ? 'disabled' : '' }}>
                        </div>

                        <div class="tr-form-group tr-col-notes">
                            <label class="tr-label">Catatan</label>
                            <input type="text" id="itemNotes" class="tr-input" placeholder="(Opsional)" {{ $session->status !== 'draft' ? 'disabled' : '' }}>
                        </div>

                        <div class="tr-form-group tr-col-btn">
                            <label class="tr-label">&nbsp;</label>
                            <button id="addBtn" type="button" class="tr-btn tr-btn-warning" style="width:100%;" {{ $session->status !== 'draft' ? 'disabled' : '' }}>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span class="tr-hide-mobile">Tambah</span>
                            </button>
                        </div>
                    </div>

                    <form id="addForm" method="POST" action="{{ route('gudang.opname_sessions.items.add', $session) }}" style="display:none;">
                        @csrf
                        <input type="hidden" name="scan_code" id="formScan">
                        <input type="hidden" name="product_id" id="formProductId">
                        <input type="hidden" name="physical_qty" id="formPhysical">
                        <input type="hidden" name="notes" id="formNotes">
                    </form>
                </div>

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Produk Terdata</th>
                                <th class="r">Qty Sistem</th>
                                <th class="r">Qty Fisik</th>
                                <th class="c">Selisih</th>
                                <th>Catatan Item</th>
                                <th class="c" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($session->items as $it)
                                @php
                                    $diff = (int) $it->difference_qty;
                                @endphp
                                <tr class="{{ $diff !== 0 ? 'tr-row-highlight' : '' }}">
                                    <td>
                                        <div class="tr-prod-name">{{ $it->product?->name ?? 'Produk Dihapus' }}</div>
                                        <div class="tr-prod-sku">SKU: <span class="tr-font-mono">{{ $it->product?->sku ?? '-' }}</span></div>
                                    </td>
                                    <td class="r tr-qty-col tr-text-muted">
                                        {{ (int) $it->system_qty }}
                                    </td>
                                    <td class="r tr-qty-col tr-text-main">
                                        {{ (int) $it->physical_qty }}
                                    </td>
                                    <td class="c">
                                        @if($diff > 0)
                                            <span class="tr-diff-badge plus">+{{ $diff }}</span>
                                        @elseif($diff < 0)
                                            <span class="tr-diff-badge minus">{{ $diff }}</span>
                                        @else
                                            <span class="tr-diff-badge zero">±0</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="tr-notes-text" title="{{ $it->notes ?? '' }}">
                                            {{ $it->notes ?: '-' }}
                                        </div>
                                    </td>
                                    <td class="c">
                                        @if($session->status === 'draft')
                                            <form method="POST" action="{{ route('gudang.opname_sessions.items.delete', [$session, $it]) }}" style="display:inline;" onsubmit="return confirm('Hapus item ini dari sesi opname?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="tr-action-btn delete" title="Hapus Item">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </form>
                                        @else
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="tr-icon-locked" title="Terkunci"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                            </div>
                                            <h6>Belum ada item yang di-opname</h6>
                                            <p>Silakan <em>scan barcode</em> atau pilih produk secara manual di atas untuk mulai mendata.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addBtn = document.getElementById('addBtn');
            const scanInput = document.getElementById('scanInput');
            const productSelect = document.getElementById('productSelect');
            const physicalQty = document.getElementById('physicalQty');
            const itemNotes = document.getElementById('itemNotes');
            const addForm = document.getElementById('addForm');
            const formScan = document.getElementById('formScan');
            const formProductId = document.getElementById('formProductId');
            const formPhysical = document.getElementById('formPhysical');
            const formNotes = document.getElementById('formNotes');

            function submitAdd() {
                if (!addForm || addBtn.disabled) return;
                
                // Validasi minimal ada produk/scan yang diisi
                if (!scanInput.value.trim() && !productSelect.value) {
                    alert('Mohon scan barcode atau pilih produk terlebih dahulu.');
                    return;
                }

                // UI Feedback
                addBtn.disabled = true;
                addBtn.innerHTML = '<span class="tr-spinner"></span>';

                formScan.value = (scanInput && scanInput.value) ? scanInput.value.trim() : '';
                formProductId.value = (productSelect && productSelect.value) ? productSelect.value : '';
                formPhysical.value = (physicalQty && physicalQty.value !== '') ? physicalQty.value : '0';
                formNotes.value = (itemNotes && itemNotes.value) ? itemNotes.value : '';

                addForm.submit();
            }

            if (addBtn) {
                addBtn.addEventListener('click', submitAdd);
            }

            if (scanInput) {
                scanInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        submitAdd();
                    }
                });
                
                // Hanya auto-focus jika status masih draft
                if (!scanInput.disabled) {
                    setTimeout(() => scanInput.focus(), 100);
                }
            }
        });
    </script>
    @endpush

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            --tr-primary: #3b82f6;
            --tr-primary-hover: #2563eb;
            --tr-info: #0ea5e9;
            --tr-info-hover: #0284c7;
            --tr-success: #10b981;
            --tr-success-hover: #059669;
            --tr-success-bg: #ecfdf5;
            --tr-success-text: #059669;
            --tr-danger: #ef4444;
            --tr-danger-hover: #dc2626;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #b91c1c;
            --tr-warning: #f59e0b;
            --tr-warning-hover: #d97706;
            --tr-warning-bg: #fffbeb;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 4rem; }
        .tr-page { padding: 1.5rem; max-width: 1280px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-warning); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-warning { background: var(--tr-warning-bg); color: var(--tr-warning); }
        
        .tr-session-meta { font-size: 0.85rem; color: var(--tr-text-muted); display: flex; align-items: center; flex-wrap: wrap; gap: 6px; }
        .tr-meta-item strong { color: var(--tr-text-main); font-weight: 700; }
        .tr-dot-divider { color: var(--tr-border); }
        .tr-session-notes { font-size: 0.85rem; color: #475569; margin-top: 0.5rem; font-style: italic; background: #f8fafc; padding: 4px 8px; border-radius: 4px; display: inline-block; border: 1px solid var(--tr-border-light); }

        /* Status Colors */
        .tr-status-text { font-weight: 800; font-size: 0.8rem; letter-spacing: 0.05em; padding: 2px 6px; border-radius: 4px; }
        .status-draft { background: #f1f5f9; color: #334155; }
        .status-submitted { background: #e0f2fe; color: #0284c7; }
        .status-approved { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .status-rejected { background: var(--tr-danger-bg); color: var(--tr-danger-text); }
        
        .tr-header-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: center; gap: 10px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 500; border: 1px solid transparent; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: #a7f3d0; }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: #fecaca; }

        /* ── CARD & ACTION BAR ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-action-bar { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-action-bar.is-disabled { opacity: 0.6; pointer-events: none; background: #f8fafc; }
        
        .tr-input-grid { display: grid; grid-template-columns: 1.5fr 2fr 100px 1.5fr auto; gap: 1rem; align-items: flex-end; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-req { color: var(--tr-danger); }

        /* Inputs */
        .tr-input-with-icon { position: relative; }
        .tr-input-with-icon .tr-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--tr-text-light); }
        .tr-input-with-icon .tr-input { padding-left: 2.25rem; }

        .tr-input, .tr-select {
            width: 100%; padding: 0.55rem 0.85rem;
            border: 1px solid var(--tr-border); border-radius: 6px;
            font-family: inherit; font-size: 0.85rem; color: var(--tr-text-main);
            background: #f8fafc; outline: none; transition: border-color 0.2s;
            height: 38px;
        }
        .tr-input:focus, .tr-select:focus { border-color: var(--tr-warning); background: #ffffff; }
        .tr-input-center { text-align: center; }
        .tr-font-bold { font-weight: 800; font-size: 0.95rem; }
        
        .tr-select-wrapper { position: relative; }
        .tr-select { appearance: none; padding-right: 2rem; cursor: pointer; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }

        /* ── BUTTONS ── */
        .tr-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 0.55rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600;
            cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 38px;
        }
        .tr-btn:disabled { opacity: 0.7; cursor: not-allowed; }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); box-shadow: var(--tr-shadow-sm); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); }
        
        .tr-btn-warning { background: var(--tr-warning); color: #ffffff; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2); }
        .tr-btn-warning:hover:not(:disabled) { background: var(--tr-warning-hover); transform: translateY(-1px); }
        
        .tr-btn-info { background: var(--tr-info); color: #ffffff; box-shadow: 0 2px 4px rgba(14, 165, 233, 0.2); }
        .tr-btn-info:hover { background: var(--tr-info-hover); transform: translateY(-1px); }

        .tr-btn-success { background: var(--tr-success); color: #ffffff; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2); }
        .tr-btn-success:hover { background: var(--tr-success-hover); transform: translateY(-1px); }

        .tr-spinner { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: tr-spin 0.8s linear infinite; }
        @keyframes tr-spin { to { transform: rotate(360deg); } }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody tr.tr-row-highlight td { background: #fffbeb; } /* Highlight rows with difference */
        .tr-table tbody tr.tr-row-highlight:hover td { background: #fef3c7; }
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        
        .tr-table th.c, .tr-table td.c { text-align: center; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* ── CELL FORMATTING ── */
        .tr-prod-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; line-height: 1.3; }
        .tr-prod-sku { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 4px; }
        .tr-font-mono { font-family: monospace; font-weight: 600; color: var(--tr-text-main); }
        
        .tr-qty-col { font-weight: 800; font-size: 1rem; }
        .tr-text-muted { color: var(--tr-text-light); }
        .tr-text-main { color: var(--tr-text-main); }

        .tr-diff-badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.25rem 0.6rem; border-radius: 999px; font-weight: 800; font-size: 0.85rem; border: 1px solid transparent; min-width: 44px;}
        .tr-diff-badge.plus { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: #a7f3d0; }
        .tr-diff-badge.minus { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: #fecaca; }
        .tr-diff-badge.zero { background: var(--tr-bg); color: var(--tr-text-muted); border-color: var(--tr-border); }
        
        .tr-notes-text { font-size: 0.8rem; color: var(--tr-text-muted); max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .tr-action-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; border: 1px solid transparent; background: var(--tr-bg); color: var(--tr-text-muted); transition: all 0.2s; cursor: pointer; }
        .tr-action-btn.delete:hover { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: var(--tr-danger-border); }
        .tr-icon-locked { color: var(--tr-text-light); }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-text-light); }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* ── RESPONSIVE ── */
        @media (max-width: 992px) {
            .tr-input-grid { grid-template-columns: 1fr 1fr; }
            .tr-col-notes { grid-column: span 2; }
            .tr-col-btn { grid-column: span 2; }
            .tr-hide-mobile { display: inline; }
        }
        @media (max-width: 640px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-header-actions { width: 100%; }
            .tr-header-actions form, .tr-btn { width: 100%; justify-content: center; }
            .tr-input-grid { grid-template-columns: 1fr; }
            .tr-col-notes, .tr-col-btn { grid-column: span 1; }
        }
    </style>
    @endpush
</x-app-layout>