<x-app-layout>
    <x-slot name="header">Input Opname</x-slot>
    @php $canEdit = in_array($session->status, ['draft', 'rejected']); @endphp

    <div class="op-page">

        {{-- ══════════ TOPBAR ══════════ --}}
        <div class="op-topbar">
            <a href="{{ route('gudang.opname_sessions.index') }}" class="op-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Kembali
            </a>
            <div class="op-topbar-actions">
                <a href="{{ route('gudang.opname_sessions.print', $session) }}" class="op-btn op-btn-ghost" target="_blank">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Print
                </a>
                @if(in_array($session->status, ['draft', 'rejected']))
                    @if($session->status === 'rejected')
                    <form method="POST" action="{{ route('gudang.opname_sessions.revise', $session) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="op-btn op-btn-amber" onclick="return confirm('Kembalikan sesi ini ke draft untuk direvisi?');">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                            Revisi ke Draft
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('gudang.opname_sessions.submit', $session) }}" onsubmit="return confirm('Kirim sesi ini untuk approval Supervisor?');" style="display:inline;">
                        @csrf
                        <button type="submit" class="op-btn op-btn-primary">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Submit Approval
                        </button>
                    </form>
                    <form method="POST" action="{{ route('gudang.opname_sessions.cancel', $session) }}" onsubmit="return confirm('Batalkan sesi opname ini?');" style="display:inline;">
                        @csrf
                        <button type="submit" class="op-btn op-btn-danger-outline">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            Batalkan
                        </button>
                    </form>
                @endif
                @if($session->status === 'submitted' && strtolower(auth()->user()->role) === 'supervisor')
                <a href="{{ route('gudang.opname_approval.show', $session) }}" class="op-btn op-btn-success">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Review & Setujui
                </a>
                @endif
            </div>
        </div>

        {{-- ══════════ SESSION INFO CARD ══════════ --}}
        <div class="op-info-card">
            <div class="op-info-left">
                <div class="op-info-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                </div>
                <div>
                    <h1 class="op-info-title">Input Item Opname</h1>
                    <div class="op-info-meta">
                        <span class="op-meta"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> {{ $session->warehouse?->name ?? '-' }}</span>
                        <span class="op-meta-divider"></span>
                        <span class="op-meta">
                            <span class="op-status op-status-{{ strtolower($session->status) }}">{{ strtoupper($session->status) }}</span>
                        </span>
                        <span class="op-meta-divider"></span>
                        <span class="op-meta"><strong>{{ $session->items->count() }}</strong> item</span>
                        @if($session->deadline_at)
                        <span class="op-meta-divider"></span>
                        <span class="op-meta">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Deadline: <strong>{{ $session->deadline_at->format('d M Y') }}</strong>
                            @if($session->deadline_at->isPast() && in_array($session->status, ['draft','rejected']))
                                <span class="op-deadline-overdue">LEWAT!</span>
                            @elseif($session->deadline_at->diffInDays(now()) <= 3 && !$session->deadline_at->isPast() && in_array($session->status, ['draft','rejected']))
                                <span class="op-deadline-soon">{{ $session->deadline_at->diffInDays(now()) }} hari lagi</span>
                            @endif
                        </span>
                        @endif
                    </div>
                    @if($session->notes)
                    <div class="op-info-notes">"{{ $session->notes }}"</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ══════════ ALERTS ══════════ --}}
        @if(session('success'))
        <div class="op-alert op-alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="op-alert op-alert-danger">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if($session->status === 'rejected')
        <div class="op-alert op-alert-warning">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <div>
                <strong>Sesi ini ditolak Supervisor.</strong>
                @if($session->approval_notes)<br>Catatan: <em>{{ $session->approval_notes }}</em>@endif
                <br><small>Klik <strong>"Revisi ke Draft"</strong> untuk memperbaiki dan submit ulang.</small>
            </div>
        </div>
        @endif

        {{-- ══════════ INPUT CARD ══════════ --}}
        <div class="op-card {{ !$canEdit ? 'op-card-disabled' : '' }}">
            <div class="op-card-head">
                <div class="op-card-title-row">
                    <h2 class="op-card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Item
                    </h2>
                    @if($canEdit)
                    <div class="op-card-tools">
                        <button type="button" id="bulkPasteBtn" class="op-btn-sm" title="Paste data massal">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            Bulk Paste
                        </button>
                        <span class="op-autosave" id="autosaveIndicator">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span id="autosaveText">Draft otomatis</span>
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            @if($canEdit)
            <div class="op-card-body">
                {{-- Row 1: Scan / Product --}}
                <div class="op-row-1">
                    <div class="op-field op-field-scan">
                        <label class="op-label">Scan Barcode / SKU</label>
                        <div class="op-input-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z"/><polyline points="10 9 10 15"/><polyline points="14 9 14 15"/></svg>
                            <input type="text" id="scanInput" class="op-input" placeholder="Scan barcode..." autofocus>
                        </div>
                    </div>
                    <div class="op-field op-field-product">
                        <label class="op-label">Atau Pilih Produk</label>
                        <div class="op-select-wrap">
                            <select id="productSelect" class="op-input op-select">
                                <option value="">-- Daftar Produk --</option>
                                @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->sku }} — {{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Row 2: Qty / Unit / Notes / Add --}}
                <div class="op-row-2">
                    <div class="op-field op-field-qty">
                        <label class="op-label">Qty Fisik <span class="op-req">*</span></label>
                        <input type="number" id="physicalQty" min="0" step="any" class="op-input op-input-center op-input-bold" value="0">
                    </div>
                    <div class="op-field op-field-unit">
                        <label class="op-label">Satuan</label>
                        <select id="unitSelect" class="op-input op-select">
                            <option value="" data-factor="1">-- Satuan --</option>
                        </select>
                        <div id="unitConversionInfo" class="op-unit-hint"></div>
                    </div>
                    <div class="op-field op-field-notes">
                        <label class="op-label">Catatan</label>
                        <input type="text" id="itemNotes" class="op-input" placeholder="(Opsional)">
                    </div>
                    <div class="op-field op-field-btn">
                        <button id="addBtn" type="button" class="op-btn op-btn-add">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah
                        </button>
                    </div>
                </div>

                <form id="addForm" method="POST" action="{{ route('gudang.opname_sessions.items.add', $session) }}" style="display:none;">
                    @csrf
                    <input type="hidden" name="scan_code" id="formScan">
                    <input type="hidden" name="product_id" id="formProductId">
                    <input type="hidden" name="physical_qty" id="formPhysical">
                    <input type="hidden" name="counted_unit" id="formCountedUnit">
                    <input type="hidden" name="counted_qty" id="formCountedQty">
                    <input type="hidden" name="notes" id="formNotes">
                </form>

                <div id="systemBreakdown" class="op-breakdown" style="display:none;"></div>
            </div>
            @endif
        </div>

        {{-- ══════════ TABLE CARD ══════════ --}}
        <div class="op-card">
            <div class="op-card-head op-card-head-table">
                <h2 class="op-card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Item Terdata
                    <span class="op-count-badge">{{ $session->items->count() }}</span>
                </h2>
            </div>
            <div class="op-table-wrap">
                <table class="op-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="r">Qty Sistem</th>
                            <th class="r">Qty Fisik</th>
                            <th class="c">Satuan</th>
                            <th class="c">Selisih</th>
                            <th>Catatan</th>
                            <th class="c">Waktu</th>
                            @if($canEdit)<th class="c" style="width:60px;"></th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($session->items as $it)
                        @php $diff = (int) $it->difference_qty; @endphp
                        <tr class="{{ $diff !== 0 ? 'op-row-hl' : '' }}">
                            <td>
                                <div class="op-prod-name">{{ $it->product?->name ?? 'Produk Dihapus' }}</div>
                                <div class="op-prod-sku">{{ $it->product?->sku ?? '-' }}</div>
                            </td>
                            <td class="r op-qty op-qty-sys">{{ (int) $it->system_qty }}</td>
                            <td class="r op-qty op-qty-fis">
                                {{ (int) $it->physical_qty }}
                                @if($it->counted_unit && $it->counted_qty)
                                <div class="op-qty-sub">{{ number_format((float)$it->counted_qty, 0) }} {{ $it->counted_unit }}</div>
                                @endif
                            </td>
                            <td class="c">
                                @if($it->counted_unit)
                                <span class="op-unit-pill">{{ $it->counted_unit }}</span>
                                @else
                                <span class="op-unit-base">base</span>
                                @endif
                            </td>
                            <td class="c">
                                @if($diff > 0)<span class="op-diff op-diff-plus">+{{ $diff }}</span>
                                @elseif($diff < 0)<span class="op-diff op-diff-minus">{{ $diff }}</span>
                                @else<span class="op-diff op-diff-zero">0</span>
                                @endif
                            </td>
                            <td><span class="op-notes-cell" title="{{ $it->notes ?? '' }}">{{ $it->notes ?: '—' }}</span></td>
                            <td class="c">
                                @if($it->counted_at)<span class="op-time">{{ $it->counted_at->format('d/m H:i') }}</span>
                                @else<span class="op-time-empty">—</span>
                                @endif
                            </td>
                            @if($canEdit)
                            <td class="c">
                                <form method="POST" action="{{ route('gudang.opname_sessions.items.delete', [$session, $it]) }}" style="display:inline;" onsubmit="return confirm('Hapus item ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="op-del-btn" title="Hapus">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $canEdit ? 8 : 7 }}">
                                <div class="op-empty">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    <h3>Belum ada item</h3>
                                    <p>Scan barcode atau pilih produk di atas untuk mulai mendata stok fisik.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ══════════ UNCOUNTED PRODUCTS ══════════ --}}
        @if($canEdit && isset($uncountedProducts) && $uncountedProducts->count() > 0)
        <div class="op-card op-card-uncounted">
            <div class="op-uncounted-toggle" onclick="var el=document.getElementById('uncountedList');el.classList.toggle('op-show');">
                <div class="op-uncounted-left">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <span>Produk Belum Dihitung</span>
                    <span class="op-count-badge op-count-warn">{{ $uncountedProducts->count() }}</span>
                </div>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div id="uncountedList" class="op-uncounted-list">
                @foreach($uncountedProducts as $up)
                <div class="op-unc-item" data-product-id="{{ $up->id }}" onclick="fillProductFromUncounted(this)">
                    <div><strong>{{ $up->sku }}</strong><br><small>{{ Str::limit($up->name, 30) }}</small></div>
                    <small>Stok: {{ (int)($systemQtyMap[$up->id] ?? 0) }}</small>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ══════════ BULK PASTE MODAL ══════════ --}}
        <div id="bulkModal" class="op-modal">
            <div class="op-modal-box">
                <h3>Bulk Paste Data</h3>
                <p>Paste data format: <code>SKU [TAB] QTY</code> (satu baris per produk).</p>
                <textarea id="bulkTextarea" rows="8"></textarea>
                <div id="bulkPreview" class="op-bulk-preview"></div>
                <div class="op-modal-actions">
                    <button type="button" class="op-btn op-btn-ghost" onclick="document.getElementById('bulkModal').style.display='none'">Batal</button>
                    <button type="button" class="op-btn op-btn-primary" id="bulkSubmitBtn">Tambahkan Item</button>
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
        const bulkPasteBtn = document.getElementById('bulkPasteBtn');
        const bulkModal = document.getElementById('bulkModal');
        const bulkTextarea = document.getElementById('bulkTextarea');
        const bulkSubmitBtn = document.getElementById('bulkSubmitBtn');
        const bulkPreview = document.getElementById('bulkPreview');
        const canEdit = {{ $canEdit ? 'true' : 'false' }};
        const sessionId = {{ $session->id }};
        const unitMap = @json($unitMap);
        const systemQtyMap = @json($systemQtyMap);
        const unitSelect = document.getElementById('unitSelect');
        const unitConversionInfo = document.getElementById('unitConversionInfo');
        const systemBreakdown = document.getElementById('systemBreakdown');
        const formCountedUnit = document.getElementById('formCountedUnit');
        const formCountedQty = document.getElementById('formCountedQty');

        // ── UNIT CONVERSION LOGIC ──
        function populateUnits(productId) {
            if (!unitSelect) return;
            unitSelect.innerHTML = '<option value="" data-factor="1">-- Satuan --</option>';
            const conversions = unitMap[productId] || [];
            if (conversions.length === 0) return;
            conversions.forEach(function(c) {
                const opt = document.createElement('option');
                opt.value = c.unit_name;
                opt.textContent = c.unit_name + (c.is_base ? ' (dasar)' : '') + ' — x' + c.factor;
                opt.dataset.factor = c.factor;
                opt.dataset.isBase = c.is_base ? '1' : '0';
                unitSelect.appendChild(opt);
            });
            const baseOpt = unitSelect.querySelector('option[data-is-base="1"]');
            if (baseOpt) baseOpt.selected = true;
            updateConversionInfo();
        }
        function getSelectedFactor() {
            if (!unitSelect || !unitSelect.selectedOptions[0]) return 1;
            return parseFloat(unitSelect.selectedOptions[0].dataset.factor) || 1;
        }
        function updateConversionInfo() {
            if (!unitConversionInfo) return;
            const qty = parseFloat(physicalQty.value) || 0;
            const factor = getSelectedFactor();
            const unitName = unitSelect ? unitSelect.value : '';
            if (!unitName || factor === 1) {
                unitConversionInfo.innerHTML = unitName ? '<span>satuan dasar</span>' : '';
            } else {
                const baseQty = Math.round(qty * factor);
                unitConversionInfo.innerHTML = '<strong>' + qty + ' ' + unitName + '</strong> = <strong>' + baseQty.toLocaleString('id-ID') + '</strong> dasar';
            }
            updateSystemBreakdown();
        }
        function updateSystemBreakdown() {
            if (!systemBreakdown) return;
            const productId = productSelect ? productSelect.value : '';
            if (!productId || !systemQtyMap[productId]) { systemBreakdown.style.display = 'none'; return; }
            const sysBaseQty = parseInt(systemQtyMap[productId]) || 0;
            const conversions = (unitMap[productId] || []).sort(function(a, b) { return b.factor - a.factor; });
            let remaining = sysBaseQty; let parts = [];
            conversions.forEach(function(c) {
                if (c.factor > 1 && remaining >= c.factor) {
                    const count = Math.floor(remaining / c.factor);
                    remaining -= count * c.factor;
                    parts.push('<span class="op-sb-u">' + count + ' ' + c.unit_name + '</span>');
                }
            });
            const baseUnit = conversions.find(function(c) { return c.is_base; });
            const baseName = baseUnit ? baseUnit.unit_name : 'unit';
            if (remaining > 0) parts.push('<span class="op-sb-u">' + remaining + ' ' + baseName + '</span>');
            systemBreakdown.style.display = 'flex';
            systemBreakdown.innerHTML = '<span class="op-sb-label">Stok Sistem (' + sysBaseQty.toLocaleString('id-ID') + ' ' + baseName + '):</span> ' + parts.join(' ');
        }
        if (productSelect && canEdit) productSelect.addEventListener('change', function() { populateUnits(this.value); });
        if (physicalQty && canEdit) physicalQty.addEventListener('input', updateConversionInfo);
        if (unitSelect && canEdit) unitSelect.addEventListener('change', updateConversionInfo);

        // ── AUTOSAVE ──
        const storageKey = 'opname_draft_' + sessionId;
        function saveDraft() {
            if (!canEdit) return;
            const data = { scan: scanInput ? scanInput.value : '', productId: productSelect ? productSelect.value : '', qty: physicalQty ? physicalQty.value : '0', notes: itemNotes ? itemNotes.value : '', ts: Date.now() };
            try { localStorage.setItem(storageKey, JSON.stringify(data)); const ind = document.getElementById('autosaveText'); if (ind) ind.textContent = 'Tersimpan ' + new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'}); } catch(e) {}
        }
        function loadDraft() {
            try {
                const raw = localStorage.getItem(storageKey); if (!raw) return; const data = JSON.parse(raw);
                if (Date.now() - data.ts > 86400000) { localStorage.removeItem(storageKey); return; }
                if (scanInput && data.scan) scanInput.value = data.scan;
                if (productSelect && data.productId) { productSelect.value = data.productId; populateUnits(data.productId); }
                if (physicalQty && data.qty) physicalQty.value = data.qty;
                if (itemNotes && data.notes) itemNotes.value = data.notes;
            } catch(e) {}
        }
        if (canEdit) loadDraft();
        setInterval(saveDraft, 3000);

        // ── SUBMIT ADD ITEM ──
        function submitAdd() {
            if (!addForm || addBtn.disabled) return;
            if (!scanInput.value.trim() && !productSelect.value) { alert('Mohon scan barcode atau pilih produk terlebih dahulu.'); return; }
            addBtn.disabled = true;
            formScan.value = scanInput.value.trim();
            formProductId.value = productSelect.value;
            formPhysical.value = physicalQty.value !== '' ? (parseFloat(physicalQty.value) * getSelectedFactor()) : '0';
            if (formCountedUnit) formCountedUnit.value = unitSelect ? unitSelect.value : '';
            if (formCountedQty) formCountedQty.value = physicalQty.value || '0';
            formNotes.value = itemNotes.value;
            try { localStorage.removeItem(storageKey); } catch(e) {}
            addForm.submit();
        }
        if (addBtn) addBtn.addEventListener('click', submitAdd);

        // ── SCANNER ──
        if (scanInput && canEdit) {
            scanInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); if (physicalQty.value === '0' || physicalQty.value === '') { physicalQty.focus(); physicalQty.select(); } else { submitAdd(); } return; }
            });
            setTimeout(() => scanInput.focus(), 100);
        }
        if (physicalQty && canEdit) physicalQty.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); submitAdd(); } });

        // ── BULK PASTE ──
        if (bulkPasteBtn && canEdit) bulkPasteBtn.addEventListener('click', function() { bulkModal.style.display = 'flex'; bulkTextarea.value = ''; bulkPreview.textContent = ''; bulkTextarea.focus(); });
        if (bulkTextarea) bulkTextarea.addEventListener('input', function() { bulkPreview.textContent = bulkTextarea.value.split('\n').filter(l => l.trim()).length + ' baris terdeteksi'; });
        if (bulkSubmitBtn && canEdit) bulkSubmitBtn.addEventListener('click', function() {
            const lines = bulkTextarea.value.split('\n').filter(l => l.trim());
            if (lines.length === 0) { alert('Tidak ada data.'); return; }
            let idx = 0;
            function submitNext() {
                if (idx >= lines.length) { bulkModal.style.display = 'none'; return; }
                const parts = lines[idx].split(/[\t;|,]+/).map(s => s.trim());
                if (parts.length >= 2) { formScan.value = parts[0]; formProductId.value = ''; formPhysical.value = parseInt(parts[1]) || 0; formNotes.value = parts[2] || ''; try { localStorage.removeItem(storageKey); } catch(e) {} addForm.submit(); }
                else { idx++; submitNext(); }
            }
            submitNext();
        });
    });
    function fillProductFromUncounted(el) {
        const select = document.getElementById('productSelect');
        const scanInput = document.getElementById('scanInput');
        if (select) { select.value = el.getAttribute('data-product-id'); select.dispatchEvent(new Event('change')); }
        if (scanInput) scanInput.value = '';
        const qty = document.getElementById('physicalQty');
        if (qty) { qty.focus(); qty.select(); }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    </script>
    @endpush

    @push('styles')
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    :root {
        --op-bg: #f1f5f9; --op-surface: #fff; --op-border: #e2e8f0; --op-border-light: #f1f5f9;
        --op-text: #0f172a; --op-text2: #475569; --op-muted: #94a3b8;
        --op-primary: #3b82f6; --op-primary-hover: #2563eb;
        --op-success: #10b981; --op-success-bg: #ecfdf5; --op-success-text: #065f46; --op-success-border: #a7f3d0;
        --op-danger: #ef4444; --op-danger-bg: #fef2f2; --op-danger-text: #991b1b; --op-danger-border: #fecaca;
        --op-warning: #f59e0b; --op-warning-bg: #fffbeb; --op-warning-text: #92400e; --op-warning-border: #fde68a;
        --op-info: #0ea5e9; --op-info-bg: #e0f2fe; --op-info-text: #0369a1;
        --op-amber: #f59e0b; --op-amber-hover: #d97706;
        --op-r: 10px; --op-r-sm: 6px;
    }
    *, *::before, *::after { box-sizing: border-box; }
    .op-page { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: var(--op-text); max-width: 1200px; margin: 0 auto; padding: 1.25rem 1rem 3rem; background: var(--op-bg); min-height: 100vh; }

    /* ── TOPBAR ── */
    .op-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem; }
    .op-back { display: inline-flex; align-items: center; gap: 4px; font-size: 0.85rem; font-weight: 600; color: var(--op-muted); text-decoration: none; transition: color .15s; }
    .op-back:hover { color: var(--op-text); }
    .op-topbar-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

    /* ── BUTTONS ── */
    .op-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.5rem 1rem; border-radius: var(--op-r-sm); font-size: 0.8rem; font-weight: 600; font-family: inherit; cursor: pointer; border: 1px solid transparent; text-decoration: none; transition: all .15s; white-space: nowrap; height: 36px; }
    .op-btn:disabled { opacity: .5; cursor: not-allowed; }
    .op-btn-ghost { border-color: var(--op-border); color: var(--op-text2); background: var(--op-surface); }
    .op-btn-ghost:hover { border-color: var(--op-muted); color: var(--op-text); }
    .op-btn-primary { background: var(--op-primary); color: #fff; box-shadow: 0 1px 3px rgba(59,130,246,.25); }
    .op-btn-primary:hover { background: var(--op-primary-hover); }
    .op-btn-success { background: var(--op-success); color: #fff; box-shadow: 0 1px 3px rgba(16,185,129,.25); }
    .op-btn-success:hover { background: #059669; }
    .op-btn-amber { background: var(--op-amber); color: #fff; box-shadow: 0 1px 3px rgba(245,158,11,.25); }
    .op-btn-amber:hover { background: var(--op-amber-hover); }
    .op-btn-danger-outline { border-color: var(--op-danger-border); color: var(--op-danger-text); background: transparent; }
    .op-btn-danger-outline:hover { background: var(--op-danger-bg); }
    .op-btn-add { width: 100%; height: 42px; background: var(--op-primary); color: #fff; font-size: 0.85rem; font-weight: 700; border-radius: var(--op-r-sm); box-shadow: 0 1px 3px rgba(59,130,246,.2); }
    .op-btn-add:hover { background: var(--op-primary-hover); transform: translateY(-1px); box-shadow: 0 3px 8px rgba(59,130,246,.25); }
    .op-btn-sm { display: inline-flex; align-items: center; gap: 4px; padding: 0.25rem 0.6rem; border-radius: var(--op-r-sm); font-size: 0.7rem; font-weight: 600; font-family: inherit; cursor: pointer; border: 1px solid var(--op-border); color: var(--op-text2); background: var(--op-surface); transition: all .15s; }
    .op-btn-sm:hover { border-color: var(--op-muted); color: var(--op-text); }

    /* ── INFO CARD ── */
    .op-info-card { background: var(--op-surface); border: 1px solid var(--op-border); border-radius: var(--op-r); padding: 1.25rem 1.5rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: flex-start; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
    .op-info-left { display: flex; gap: 1rem; align-items: flex-start; }
    .op-info-icon { width: 44px; height: 44px; border-radius: 10px; background: var(--op-warning-bg); color: var(--op-amber); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .op-info-title { font-size: 1.25rem; font-weight: 800; margin: 0 0 0.35rem; letter-spacing: -0.01em; }
    .op-info-meta { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; font-size: 0.8rem; color: var(--op-text2); }
    .op-meta { display: inline-flex; align-items: center; gap: 4px; }
    .op-meta strong { color: var(--op-text); }
    .op-meta-divider { width: 3px; height: 3px; border-radius: 50%; background: var(--op-muted); flex-shrink: 0; }
    .op-status { display: inline-block; padding: 0.1rem 0.5rem; border-radius: 999px; font-size: 0.65rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
    .op-status-draft { background: #f1f5f9; color: #475569; }
    .op-status-submitted { background: var(--op-info-bg); color: var(--op-info-text); }
    .op-status-approved { background: var(--op-success-bg); color: var(--op-success-text); }
    .op-status-rejected { background: var(--op-danger-bg); color: var(--op-danger-text); }
    .op-status-cancelled { background: #e2e8f0; color: #475569; }
    .op-deadline-overdue { color: var(--op-danger); font-weight: 800; font-size: 0.75rem; margin-left: 2px; }
    .op-deadline-soon { color: var(--op-amber); font-weight: 700; font-size: 0.75rem; margin-left: 2px; }
    .op-info-notes { font-size: 0.8rem; color: var(--op-text2); margin-top: 0.4rem; font-style: italic; }

    /* ── ALERTS ── */
    .op-alert { display: flex; align-items: flex-start; gap: 10px; padding: 0.875rem 1.125rem; border-radius: var(--op-r-sm); margin-bottom: 0.75rem; font-size: 0.85rem; font-weight: 500; border: 1px solid; }
    .op-alert-success { background: var(--op-success-bg); color: var(--op-success-text); border-color: var(--op-success-border); }
    .op-alert-danger { background: var(--op-danger-bg); color: var(--op-danger-text); border-color: var(--op-danger-border); }
    .op-alert-warning { background: var(--op-warning-bg); color: var(--op-warning-text); border-color: var(--op-warning-border); }

    /* ── CARDS ── */
    .op-card { background: var(--op-surface); border: 1px solid var(--op-border); border-radius: var(--op-r); margin-bottom: 1rem; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
    .op-card-disabled { opacity: .55; pointer-events: none; }
    .op-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid var(--op-border-light); }
    .op-card-head-table { padding: 0.75rem 1.25rem; }
    .op-card-title-row { display: flex; justify-content: space-between; align-items: center; }
    .op-card-title { display: flex; align-items: center; gap: 8px; font-size: 0.9rem; font-weight: 700; color: var(--op-text); margin: 0; }
    .op-card-tools { display: flex; align-items: center; gap: 0.75rem; }
    .op-card-body { padding: 1.25rem; }
    .op-count-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 22px; height: 22px; padding: 0 6px; border-radius: 999px; font-size: 0.7rem; font-weight: 700; background: var(--op-info-bg); color: var(--op-info-text); }
    .op-count-warn { background: var(--op-warning-bg); color: var(--op-warning-text); }

    /* ── INPUT ROWS ── */
    .op-row-1 { display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; margin-bottom: 1rem; }
    .op-row-2 { display: grid; grid-template-columns: 100px 140px 1fr auto; gap: 1rem; align-items: flex-end; }
    .op-field { display: flex; flex-direction: column; gap: 5px; }
    .op-label { font-size: 0.7rem; font-weight: 700; color: var(--op-text2); text-transform: uppercase; letter-spacing: .04em; }
    .op-req { color: var(--op-danger); }
    .op-input { width: 100%; padding: 0.55rem 0.75rem; border: 1.5px solid var(--op-border); border-radius: var(--op-r-sm); font-family: inherit; font-size: 0.85rem; color: var(--op-text); background: #f8fafc; outline: none; transition: border-color .15s, background .15s; height: 42px; }
    .op-input:focus { border-color: var(--op-primary); background: #fff; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
    .op-input:disabled { opacity: .5; cursor: not-allowed; }
    .op-input-center { text-align: center; }
    .op-input-bold { font-weight: 800; font-size: 1rem; }
    .op-select { appearance: none; padding-right: 2rem; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; background-size: 14px; }
    .op-select-wrap { position: relative; }
    .op-input-icon { position: relative; }
    .op-input-icon svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--op-muted); pointer-events: none; }
    .op-input-icon .op-input { padding-left: 2.2rem; }
    .op-unit-hint { font-size: 0.7rem; color: var(--op-muted); margin-top: 2px; }
    .op-unit-hint strong { color: var(--op-text); }
    .op-autosave { display: inline-flex; align-items: center; gap: 4px; font-size: 0.7rem; color: var(--op-success); font-weight: 500; }

    /* ── BREAKDOWN ── */
    .op-breakdown { margin-top: 1rem; padding: 0.75rem 1rem; background: #f8fafc; border: 1px dashed var(--op-border); border-radius: var(--op-r-sm); display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; font-size: 0.8rem; color: var(--op-text2); }
    .op-sb-label { font-weight: 700; color: var(--op-text); }
    .op-sb-u { display: inline-flex; padding: 0.1rem 0.4rem; border-radius: 4px; background: var(--op-warning-bg); border: 1px solid var(--op-warning-border); font-weight: 600; font-size: 0.75rem; color: var(--op-warning-text); }

    /* ── TABLE ── */
    .op-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .op-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 780px; }
    .op-table thead th { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: var(--op-muted); padding: 0.7rem 1rem; background: #f8fafc; border-bottom: 1.5px solid var(--op-border); white-space: nowrap; text-align: left; user-select: none; }
    .op-table tbody tr { transition: background .1s; }
    .op-table tbody tr:hover { background: #f8fafc; }
    .op-table tbody td { padding: 0.75rem 1rem; font-size: 0.82rem; vertical-align: middle; border-bottom: 1px solid var(--op-border-light); }
    .op-table tbody tr:last-child td { border-bottom: none; }
    .op-table th.c, .op-table td.c { text-align: center; }
    .op-table th.r, .op-table td.r { text-align: right; }
    .op-row-hl td { background: #fffbeb !important; }
    .op-row-hl:hover td { background: #fef3c7 !important; }

    .op-prod-name { font-weight: 700; color: var(--op-text); font-size: 0.82rem; line-height: 1.3; }
    .op-prod-sku { font-size: 0.7rem; color: var(--op-muted); font-family: monospace; font-weight: 600; margin-top: 2px; }
    .op-qty { font-weight: 800; font-size: 0.95rem; }
    .op-qty-sys { color: var(--op-muted); }
    .op-qty-fis { color: var(--op-text); }
    .op-qty-sub { font-size: 0.65rem; color: var(--op-info); font-weight: 600; margin-top: 1px; }
    .op-unit-pill { display: inline-block; padding: 0.1rem 0.45rem; border-radius: 999px; font-size: 0.65rem; font-weight: 700; background: var(--op-info-bg); color: var(--op-info-text); border: 1px solid #bae6fd; }
    .op-unit-base { font-size: 0.65rem; color: var(--op-muted); }
    .op-diff { display: inline-flex; align-items: center; justify-content: center; padding: 0.2rem 0.55rem; border-radius: 999px; font-weight: 800; font-size: 0.8rem; min-width: 40px; }
    .op-diff-plus { background: var(--op-success-bg); color: var(--op-success-text); border: 1px solid var(--op-success-border); }
    .op-diff-minus { background: var(--op-danger-bg); color: var(--op-danger-text); border: 1px solid var(--op-danger-border); }
    .op-diff-zero { background: #f1f5f9; color: var(--op-muted); }
    .op-notes-cell { font-size: 0.78rem; color: var(--op-text2); max-width: 160px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .op-time { font-size: 0.7rem; color: var(--op-muted); font-family: monospace; font-weight: 500; }
    .op-time-empty { font-size: 0.7rem; color: var(--op-muted); }
    .op-del-btn { width: 30px; height: 30px; border: 1px solid var(--op-border); border-radius: var(--op-r-sm); background: var(--op-surface); color: var(--op-muted); cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all .15s; }
    .op-del-btn:hover { background: var(--op-danger-bg); color: var(--op-danger); border-color: var(--op-danger-border); }

    /* ── EMPTY ── */
    .op-empty { text-align: center; padding: 3rem 1rem; color: var(--op-muted); }
    .op-empty svg { opacity: .3; margin-bottom: 0.75rem; }
    .op-empty h3 { font-size: 0.95rem; font-weight: 700; color: var(--op-text); margin: 0 0 0.3rem; }
    .op-empty p { font-size: 0.8rem; margin: 0; max-width: 350px; margin: 0 auto; line-height: 1.5; }

    /* ── UNCOUNTED ── */
    .op-card-uncounted { border-color: var(--op-warning-border); }
    .op-uncounted-toggle { display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1.25rem; cursor: pointer; transition: background .1s; }
    .op-uncounted-toggle:hover { background: #fefce8; }
    .op-uncounted-left { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 0.85rem; color: var(--op-warning-text); }
    .op-uncounted-list { display: none; padding: 0.75rem 1.25rem; max-height: 280px; overflow-y: auto; border-top: 1px solid var(--op-border-light); }
        .op-uncounted-list.op-show { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 0.4rem; }
    .op-unc-item { display: flex; align-items: center; justify-content: space-between; padding: 0.4rem 0.65rem; border: 1px solid var(--op-border-light); border-radius: var(--op-r-sm); font-size: 0.78rem; cursor: pointer; transition: all .12s; }
    .op-unc-item:hover { background: var(--op-warning-bg); border-color: var(--op-amber); }
    .op-unc-item strong { font-size: 0.78rem; }
    .op-unc-item small { color: var(--op-muted); font-size: 0.7rem; }

    /* ── MODAL ── */
    .op-modal { display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,.45); align-items: center; justify-content: center; }
    .op-modal-box { background: #fff; border-radius: var(--op-r); padding: 1.75rem; max-width: 560px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,.2); }
    .op-modal-box h3 { font-size: 1.05rem; font-weight: 800; margin: 0 0 0.5rem; }
    .op-modal-box p { font-size: 0.8rem; color: var(--op-text2); margin: 0 0 0.75rem; }
    .op-modal-box textarea { width: 100%; padding: 0.75rem; border: 1.5px solid var(--op-border); border-radius: var(--op-r-sm); font-family: monospace; font-size: 0.85rem; resize: vertical; outline: none; }
    .op-modal-box textarea:focus { border-color: var(--op-primary); }
    .op-bulk-preview { margin-top: 0.5rem; font-size: 0.75rem; color: var(--op-muted); }
    .op-modal-actions { display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1rem; }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
        .op-row-1 { grid-template-columns: 1fr; }
        .op-row-2 { grid-template-columns: 1fr 1fr; }
        .op-field-btn { grid-column: span 2; }
    }
    @media (max-width: 640px) {
        .op-topbar { flex-direction: column; align-items: stretch; }
        .op-topbar-actions { flex-wrap: wrap; }
        .op-topbar-actions .op-btn { flex: 1; min-width: 120px; justify-content: center; }
        .op-info-card { flex-direction: column; }
        .op-row-2 { grid-template-columns: 1fr; }
        .op-field-btn { grid-column: span 1; }
    }
    </style>
    @endpush
</x-app-layout>
