<x-app-layout>
    <x-slot name="header">Input Opname</x-slot>
    @php
        $canEdit = in_array($session->status, ['draft', 'rejected']);
        $isSupervisor = strtolower((string) (auth()->user()?->role ?? '')) === 'supervisor';
        $blindCount = !$isSupervisor; // admin3/4 cannot see system stock while counting
    @endphp

    <div class="op-page">

        {{-- ════════ TOPBAR ════════ --}}
        <div class="op-topbar">
            <a href="{{ route('gudang.opname_sessions.index') }}" class="op-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Kembali
            </a>
            <div class="op-topbar-actions">
                <a href="{{ route('gudang.opname_sessions.print', $session) }}" class="op-btn op-btn-ghost" target="_blank" title="Cetak daftar opname">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Print
                </a>
                @if(in_array($session->status, ['draft', 'rejected']))
                    @if($session->status === 'rejected')
                    <form method="POST" action="{{ route('gudang.opname_sessions.revise', $session) }}" class="op-form-inline">
                        @csrf
                        <button type="submit" class="op-btn op-btn-amber" onclick="return confirm('Kembalikan sesi ini ke draft untuk direvisi?');">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                            Revisi
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('gudang.opname_sessions.submit', $session) }}" class="op-form-inline" onsubmit="return confirm('Kirim sesi opname ini untuk approval Supervisor? Semua data akan dikunci.');">
                        @csrf
                        <button type="submit" class="op-btn op-btn-primary">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Submit Approval
                        </button>
                    </form>
                    <form method="POST" action="{{ route('gudang.opname_sessions.cancel', $session) }}" class="op-form-inline" onsubmit="return confirm('Batalkan sesi opname ini? Data tidak bisa dikembalikan.');">
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

        {{-- ════════ SESSION INFO ════════ --}}
        <div class="op-info-card">
            <div class="op-info-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            </div>
            <div class="op-info-body">
                <h1 class="op-info-title">Hitung Fisik Stok</h1>
                <div class="op-info-meta">
                    <span class="op-meta"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> {{ $session->warehouse?->name ?? '-' }}</span>
                    <span class="op-dot"></span>
                    <span class="op-status op-status-{{ strtolower($session->status) }}">{{ strtoupper($session->status) }}</span>
                    <span class="op-dot"></span>
                    <span class="op-meta"><strong>{{ $summary->totalItems }}</strong> item terdata</span>
                    @if($session->deadline_at)
                    <span class="op-dot"></span>
                    <span class="op-meta">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Deadline: <strong>{{ $session->deadline_at->format('d M Y') }}</strong>
                        @if($session->deadline_at->isPast() && in_array($session->status, ['draft','rejected']))
                            <span class="op-badge-danger">TERLAMBAT!</span>
                        @elseif($session->deadline_at->diffInDays(now()) <= 3 && !$session->deadline_at->isPast() && in_array($session->status, ['draft','rejected']))
                            <span class="op-badge-warn">{{ $session->deadline_at->diffInDays(now()) }} hari lagi</span>
                        @endif
                    </span>
                    @endif
                </div>
                @if($session->notes)
                <div class="op-info-notes">{{ $session->notes }}</div>
                @endif
            </div>
        </div>

        {{-- ════════ WORKFLOW GUIDE ════════ --}}
        @if($canEdit)
        <div class="op-guide">
            <div class="op-guide-step {{ $summary->totalItems === 0 ? 'op-guide-active' : 'op-guide-done' }}">
                <div class="op-guide-num">{{ $summary->totalItems === 0 ? '1' : '✓' }}</div>
                <div class="op-guide-text">
                    <strong>Tarik / Input Barang</strong>
                    <small>Pakai "Tarik Penjualan" atau scan manual</small>
                </div>
            </div>
            <div class="op-guide-line {{ $summary->totalItems > 0 ? 'op-guide-line-done' : '' }}"></div>
            <div class="op-guide-step {{ $summary->totalItems > 0 && $summary->totalPhysical === 0 ? 'op-guide-active' : ($summary->totalPhysical > 0 ? 'op-guide-done' : '') }}">
                <div class="op-guide-num">{{ $summary->totalPhysical > 0 ? '✓' : '2' }}</div>
                <div class="op-guide-text">
                    <strong>Isi Qty Fisik</strong>
                    <small>Hitung barang, masukkan jumlah sebenarnya</small>
                </div>
            </div>
            <div class="op-guide-line {{ $summary->totalPhysical > 0 ? 'op-guide-line-done' : '' }}"></div>
            <div class="op-guide-step {{ $summary->totalPhysical > 0 ? 'op-guide-active' : '' }}">
                <div class="op-guide-num">3</div>
                <div class="op-guide-text">
                    <strong>Submit ke Supervisor</strong>
                    <small>Kirim data untuk diperiksa dan disetujui</small>
                </div>
            </div>
        </div>
        @endif

        {{-- ════════ ALERTS ════════ --}}
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
                <br><small>Klik <strong>"Revisi"</strong> untuk memperbaiki dan submit ulang.</small>
            </div>
        </div>
        @endif

        {{-- ════════ SUMMARY STATS ════════ --}}
        @if($summary->totalItems > 0)
        <div class="op-stats">
            <div class="op-stat op-stat-main">
                <div class="op-stat-val">{{ $summary->totalItems }}</div>
                <div class="op-stat-lbl">Total Item</div>
            </div>
            <div class="op-stat">
                <div class="op-stat-val">{{ number_format($summary->totalPhysical) }}</div>
                <div class="op-stat-lbl">Qty Fisik (dihitung)</div>
            </div>
            <div class="op-stat op-stat-plus">
                <div class="op-stat-val">+{{ $summary->diffPlus }}</div>
                <div class="op-stat-lbl">Stok Naik</div>
            </div>
            <div class="op-stat op-stat-minus">
                <div class="op-stat-val">{{ $summary->diffMinus }}</div>
                <div class="op-stat-lbl">Stok Turun</div>
            </div>
            <div class="op-stat op-stat-ok">
                <div class="op-stat-val">{{ $summary->diffZero }}</div>
                <div class="op-stat-lbl">Cocok ✓</div>
            </div>
        </div>
        @endif

        {{-- ════════ INPUT FORM ════════ --}}
        <div class="op-card {{ !$canEdit ? 'op-card-locked' : '' }}">
            <div class="op-card-head">
                <div class="op-card-head-row">
                    <h2 class="op-card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Input Barang yang Dihitung
                    </h2>
                    @if($canEdit)
                    <div class="op-card-tools">
                        <button type="button" id="bulkPasteBtn" class="op-btn-sm" title="Paste data massal format: SKU [TAB] QTY">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            Bulk Paste
                        </button>
                        <span class="op-autosave" id="autosaveIndicator">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span id="autosaveText">Draft auto</span>
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            @if($canEdit)
            {{-- ── Tarik Penjualan Banner ── --}}
            <div class="op-pull-banner">
                <div class="op-pull-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                </div>
                <div class="op-pull-info">
                    <strong>Tarik Penjualan Hari Ini</strong>
                    <span>Otomatis ambil semua produk yang terjual hari ini. Qty fisik tinggal dilengkapi.</span>
                </div>
                <form method="POST" action="{{ route('gudang.opname_sessions.generate_sales', $session) }}" class="op-form-inline" onsubmit="return confirm('Tarik produk yang terjual hari ini ke daftar opname?\n\nQty fisik akan dikosongkan untuk diisi manual.');">
                    @csrf
                    <button type="submit" class="op-btn op-btn-pull">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Tarik Penjualan
                    </button>
                </form>
            </div>

            <div class="op-card-body">
                <div class="op-form-divider-label">
                    <span class="op-form-step-badge">A</span>
                    Cari Barang
                </div>
                {{-- Row 1: Scan / Search & Select Product --}}
                <div class="op-form-row1">
                    <div class="op-field">
                        <label class="op-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z"/><polyline points="10 9 10 15"/><polyline points="14 9 14 15"/></svg>
                            Scan Barcode / SKU
                        </label>
                        <input type="text" id="scanInput" class="op-input op-input-scan" placeholder="Scan atau ketik barcode/SKU..." autofocus autocomplete="off">
                        <div class="op-field-hint">Tekan Enter untuk lanjut ke Qty</div>
                    </div>
                    <div class="op-field op-field-wide">
                        <label class="op-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            Atau Pilih Produk
                        </label>
                        <input type="text" id="productSearch" class="op-input op-input-search" placeholder="Ketik nama/SKU untuk filter..." autocomplete="off">
                        <select id="productSelect" class="op-input op-select" size="1">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}" data-name="{{ strtolower($p->name) }}" data-sku="{{ strtolower($p->sku) }}">{{ $p->sku }} — {{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Row 2: Multi-Unit Physical Count --}}
                <div class="op-form-divider-label" style="margin-top: 1.25rem;">
                    <span class="op-form-step-badge">B</span>
                    Masukkan Jumlah Fisik
                </div>

                {{-- Multi-unit input panel (generated by JS) --}}
                <div id="multiUnitPanel" class="op-multi-panel" style="display:none;">
                    <div class="op-multi-hint">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Hitung dari satuan terbesar, isi sesuai hasil hitungan
                    </div>
                    <div id="multiUnitRows"></div>
                    <div id="multiUnitTotal" class="op-multi-total" style="display:none;"></div>
                </div>

                {{-- Notes + Add button --}}
                <div class="op-form-row-bottom">
                    <div class="op-field op-field-wide">
                        <label class="op-label">Catatan</label>
                        <input type="text" id="itemNotes" class="op-input" placeholder="(Opsional)">
                    </div>
                    <div class="op-field op-field-add">
                        <button id="addBtn" type="button" class="op-btn op-btn-add">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah
                        </button>
                    </div>
                </div>

                {{-- Hidden form --}}
                <form id="addForm" method="POST" action="{{ route('gudang.opname_sessions.items.add', $session) }}" style="display:none;">
                    @csrf
                    <input type="hidden" name="scan_code" id="formScan">
                    <input type="hidden" name="product_id" id="formProductId">
                    <input type="hidden" name="physical_qty" id="formPhysical">
                    <input type="hidden" name="counted_unit" id="formCountedUnit">
                    <input type="hidden" name="counted_qty" id="formCountedQty">
                    <input type="hidden" name="notes" id="formNotes">
                </form>

                {{-- System breakdown --}}
                <div id="systemBreakdown" class="op-breakdown" style="display:none;"></div>
            </div>
            @else
            <div class="op-card-locked-msg">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span>Form input dikunci — sesi berstatus <strong>{{ strtoupper($session->status) }}</strong></span>
            </div>
            @endif
        </div>

        {{-- ════════ BLIND COUNT NOTICE ════════ --}}
        @if($blindCount && $canEdit)
        <div class="op-blind-notice">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            Stok sistem disembunyikan selama hitung fisik untuk menjaga integritas data. Supervisor akan melihat perbandingan saat review.
        </div>
        @endif

        {{-- ════════ ITEMS TABLE ════════ --}}
        <div class="op-card">
            <div class="op-card-head">
                <div class="op-card-head-row">
                    <h2 class="op-card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Item Terdata
                        <span class="op-count-badge">{{ $summary->totalItems }}</span>
                    </h2>
                    @if($summary->totalItems > 5)
                    <input type="text" id="tableSearch" class="op-input op-input-table-search" placeholder="Cari di tabel..." autocomplete="off">
                    @endif
                </div>
            </div>
            <div class="op-table-wrap">
                <table class="op-table" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width:36px;">#</th>
                            <th>Produk</th>
                            <th class="r">Qty Sistem</th>
                            <th class="r">Qty Fisik</th>
                            <th class="c">Satuan</th>
                            <th class="c">Selisih</th>
                            <th>Catatan</th>
                            <th class="c">Waktu</th>
                            @if($canEdit)<th class="c" style="width:50px;"></th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($session->items as $idx => $it)
                        @php $diff = (int) $it->difference_qty; @endphp
                        <tr class="op-item-row {{ $diff !== 0 ? 'op-row-diff' : '' }}" data-product="{{ strtolower($it->product?->name ?? '') }} {{ strtolower($it->product?->sku ?? '') }}">
                            <td class="c op-row-num">{{ $idx + 1 }}</td>
                            <td>
                                <div class="op-prod-name">{{ $it->product?->name ?? 'Produk Dihapus' }}</div>
                                <div class="op-prod-sku">{{ $it->product?->sku ?? '-' }}</div>
                            </td>
                            @if($blindCount)
                            <td class="r op-qty-sys"><span class="op-masked">***</span></td>
                            @else
                            <td class="r op-qty-sys">{{ number_format((int) $it->system_qty) }}</td>
                            @endif
                            <td class="r">
                                <span class="op-qty-fis">{{ number_format((int) $it->physical_qty) }}</span>
                                @if($it->counted_unit && str_contains($it->counted_unit, ','))
                                    <div class="op-qty-sub">{{ $it->counted_unit }}</div>
                                @elseif($it->counted_unit && $it->counted_qty && (int)$it->counted_qty !== (int)$it->physical_qty)
                                    <div class="op-qty-sub">{{ number_format((float)$it->counted_qty, 0) }} {{ $it->counted_unit }}</div>
                                @endif
                            </td>
                            <td class="c">
                                @if($it->counted_unit && str_contains($it->counted_unit, ','))
                                    @foreach(explode(', ', $it->counted_unit) as $part)
                                        <span class="op-unit-pill" style="margin:1px;">{{ trim(explode(' ', trim($part), 2)[1] ?? $part) }}</span>
                                    @endforeach
                                @elseif($it->counted_unit)
                                    <span class="op-unit-pill">{{ $it->counted_unit }}</span>
                                @else
                                    <span class="op-unit-base">{{ $it->product->unit->abbreviation ?? $it->product->unit->name ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="c">
                                @if($diff > 0)
                                    @if($blindCount)
                                    <span class="op-diff op-diff-plus">▲</span>
                                    @else
                                    <span class="op-diff op-diff-plus">+{{ $diff }}</span>
                                    @endif
                                @elseif($diff < 0)
                                    @if($blindCount)
                                    <span class="op-diff op-diff-minus">▼</span>
                                    @else
                                    <span class="op-diff op-diff-minus">{{ $diff }}</span>
                                    @endif
                                @else
                                <span class="op-diff op-diff-zero">✓</span>
                                @endif
                            </td>
                            <td><span class="op-notes-cell" title="{{ $it->notes ?? '' }}">{{ $it->notes ?: '—' }}</span></td>
                            <td class="c">
                                @if($it->counted_at)
                                <span class="op-time">{{ $it->counted_at->format('d/m H:i') }}</span>
                                @else
                                <span class="op-time-empty">—</span>
                                @endif
                            </td>
                            @if($canEdit)
                            <td class="c">
                                <form method="POST" action="{{ route('gudang.opname_sessions.items.delete', [$session, $it]) }}" class="op-form-inline" onsubmit="return confirm('Hapus item {{ addslashes($it->product?->name ?? '') }}?');">
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
                            <td colspan="{{ $canEdit ? 9 : 8 }}">
                                <div class="op-empty">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    <h3>Belum ada item</h3>
                                    <p>Scan barcode, pilih produk, atau gunakan <strong>"Tarik Penjualan"</strong> untuk mulai mendata stok fisik.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ════════ UNCOUNTED PRODUCTS ════════ --}}
        @if($canEdit && isset($uncountedProducts) && $uncountedProducts->count() > 0)
        <div class="op-uncounted-card">
            <div class="op-unc-header" onclick="toggleUncounted()">
                <div class="op-unc-header-left">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <span>Produk Belum Dihitung</span>
                    <span class="op-unc-badge">{{ $uncountedProducts->count() }}</span>
                </div>
                <svg id="uncChevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div id="uncBody" class="op-unc-body">
                <input type="text" id="uncSearch" class="op-input op-input-unc-search" placeholder="Cari produk yang belum dihitung..." autocomplete="off">
                <div id="uncGrid" class="op-unc-grid">
                    @foreach($uncountedProducts as $up)
                    <div class="op-unc-item" data-product-id="{{ $up->id }}" data-search="{{ strtolower($up->sku . ' ' . $up->name) }}" onclick="selectUncounted(this)">
                        <div class="op-unc-item-info">
                            <strong>{{ $up->sku }}</strong>
                            <small>{{ Str::limit($up->name, 35) }}</small>
                        </div>
                        @if($isSupervisor)
                        <small class="op-unc-stock">Stok: {{ number_format((int)($systemQtyMap[$up->id] ?? 0)) }}</small>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ════════ BULK PASTE MODAL ════════ --}}
        <div id="bulkModal" class="op-modal">
            <div class="op-modal-box">
                <div class="op-modal-head">
                    <h3>Bulk Paste Data</h3>
                    <button type="button" class="op-modal-close" onclick="closeBulkModal()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <p class="op-modal-desc">Paste data format: <code>SKU [TAB] QTY [TAB] CATATAN</code> — satu baris per produk.</p>
                <textarea id="bulkTextarea" rows="8" placeholder="PRD-0001&#9;10&#9;Catatan opsional&#10;PRD-0002&#9;5"></textarea>
                <div id="bulkPreview" class="op-bulk-preview"></div>
                <div class="op-modal-actions">
                    <button type="button" class="op-btn op-btn-ghost" onclick="closeBulkModal()">Batal</button>
                    <button type="button" class="op-btn op-btn-primary" id="bulkSubmitBtn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        Tambahkan Item
                    </button>
                </div>
                <div id="bulkProgress" class="op-bulk-progress" style="display:none;">
                    <div class="op-bulk-bar"><div id="bulkBarFill" class="op-bulk-bar-fill"></div></div>
                    <span id="bulkBarText">0 / 0</span>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ── ELEMENTS ──
        const $ = id => document.getElementById(id);
        const addBtn = $('addBtn'), scanInput = $('scanInput'), productSelect = $('productSelect');
        const productSearch = $('productSearch');
        const multiUnitPanel = $('multiUnitPanel'), multiUnitRows = $('multiUnitRows');
        const multiUnitTotal = $('multiUnitTotal');
        const itemNotes = $('itemNotes');
        const addForm = $('addForm'), formScan = $('formScan'), formProductId = $('formProductId');
        const formPhysical = $('formPhysical'), formNotes = $('formNotes');
        const formCountedUnit = $('formCountedUnit'), formCountedQty = $('formCountedQty');
        const systemBreakdown = $('systemBreakdown');
        const bulkPasteBtn = $('bulkPasteBtn'), bulkModal = $('bulkModal');
        const bulkTextarea = $('bulkTextarea'), bulkSubmitBtn = $('bulkSubmitBtn');
        const bulkPreview = $('bulkPreview'), bulkProgress = $('bulkProgress');
        const canEdit = {{ $canEdit ? 'true' : 'false' }};
        const sessionId = {{ $session->id }};
        const unitMap = @json($unitMap);
        const systemQtyMap = @json($systemQtyMap);

        if (!canEdit) return;

        // ── PRODUCT SEARCH FILTER ──
        if (productSearch) {
            productSearch.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                const options = productSelect.options;
                let visibleCount = 0;
                for (let i = 1; i < options.length; i++) {
                    const opt = options[i];
                    const name = opt.dataset.name || '';
                    const sku = opt.dataset.sku || '';
                    const match = !q || name.includes(q) || sku.includes(q);
                    opt.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                }
                // Auto-open if filtered
                if (visibleCount <= 10 && q) {
                    productSelect.size = Math.min(visibleCount + 1, 8);
                } else {
                    productSelect.size = 1;
                }
            });
        }

        // ── MULTI-UNIT INPUT ──
        let currentUnits = [];

        function populateUnits(productId) {
            const conversions = unitMap[productId] || [];
            currentUnits = conversions.length > 0
                ? [...conversions].sort(function(a, b) { return b.factor - a.factor; })
                : [{ unit_name: 'Pcs', factor: 1, is_base: true }];
            renderMultiUnitInputs();
            updateSystemBreakdown();
        }

        function renderMultiUnitInputs() {
            multiUnitRows.innerHTML = '';
            if (currentUnits.length === 0) { multiUnitPanel.style.display = 'none'; return; }
            multiUnitPanel.style.display = 'block';
            currentUnits.forEach(function(u, idx) {
                const row = document.createElement('div');
                row.className = 'op-multi-row';
                if (u.is_base) row.classList.add('op-multi-row-base');
                const baseEq = u.factor > 1 ? '<span class="op-multi-eq">= ' + u.factor + ' ' + (currentUnits.find(function(b){return b.is_base;}) || currentUnits[currentUnits.length-1]).unit_name + '</span>' : '';
                row.innerHTML =
                    '<div class="op-multi-label">' +
                        '<span class="op-multi-name">' + u.unit_name + '</span>' +
                        (u.is_base ? '<span class="op-multi-badge">Satuan Dasar</span>' : '<span class="op-multi-factor">&times;' + u.factor + '</span>') +
                    '</div>' +
                    '<div class="op-multi-input-wrap">' +
                        '<input type="number" min="0" step="1" class="op-input op-multi-input" value="0" data-factor="' + u.factor + '" data-unit="' + u.unit_name + '" data-is-base="' + (u.is_base ? '1' : '0') + '">' +
                        baseEq +
                    '</div>';
                multiUnitRows.appendChild(row);
            });
            // Attach listeners
            multiUnitRows.querySelectorAll('.op-multi-input').forEach(function(input) {
                input.addEventListener('input', calculateMultiUnitTotal);
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const allInputs = Array.from(multiUnitRows.querySelectorAll('.op-multi-input'));
                        const curIdx = allInputs.indexOf(input);
                        if (curIdx < allInputs.length - 1) {
                            allInputs[curIdx + 1].focus();
                            allInputs[curIdx + 1].select();
                        } else {
                            itemNotes.focus();
                        }
                    }
                });
            });
            calculateMultiUnitTotal();
            // Auto-focus first non-base input (largest)
            const firstInput = multiUnitRows.querySelector('.op-multi-input');
            if (firstInput) { firstInput.focus(); firstInput.select(); }
        }

        function calculateMultiUnitTotal() {
            let totalBase = 0;
            const parts = [];
            multiUnitRows.querySelectorAll('.op-multi-input').forEach(function(input) {
                const val = parseInt(input.value) || 0;
                const factor = parseFloat(input.dataset.factor) || 1;
                const unitName = input.dataset.unit;
                totalBase += val * factor;
                if (val > 0) parts.push(val + ' ' + unitName);
            });
            const baseUnit = currentUnits.find(function(u) { return u.is_base; }) || currentUnits[currentUnits.length - 1];
            if (currentUnits.length > 1 && parts.length > 0) {
                multiUnitTotal.style.display = 'flex';
                multiUnitTotal.innerHTML = '<span class="op-multi-total-label">Total:</span> <strong>' + totalBase.toLocaleString('id-ID') + ' ' + baseUnit.unit_name + '</strong> <span class="op-multi-total-detail">(' + parts.join(' + ') + ')</span>';
            } else if (currentUnits.length === 1) {
                multiUnitTotal.style.display = 'flex';
                multiUnitTotal.innerHTML = '<span class="op-multi-total-label">Total:</span> <strong>' + totalBase.toLocaleString('id-ID') + ' ' + baseUnit.unit_name + '</strong>';
            } else {
                multiUnitTotal.style.display = 'none';
            }
            return totalBase;
        }

        function getMultiUnitBreakdown() {
            let totalBase = 0;
            const parts = [];
            multiUnitRows.querySelectorAll('.op-multi-input').forEach(function(input) {
                const val = parseInt(input.value) || 0;
                const factor = parseFloat(input.dataset.factor) || 1;
                totalBase += val * factor;
                if (val > 0) parts.push(val + ' ' + input.dataset.unit);
            });
            return { total: totalBase, breakdown: parts.join(', ') };
        }

        function updateSystemBreakdown() {
            const productId = productSelect ? productSelect.value : '';
            if (!productId || !systemQtyMap[productId]) { systemBreakdown.style.display = 'none'; return; }
            const sysBaseQty = parseInt(systemQtyMap[productId]) || 0;
            const conversions = (unitMap[productId] || []).sort(function(a, b) { return b.factor - a.factor; });
            let remaining = sysBaseQty, parts = [];
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

        productSelect.addEventListener('change', function() {
            populateUnits(this.value);
            if (this.value) {
                if (productSearch) { productSearch.value = ''; productSearch.dispatchEvent(new Event('input')); }
            }
        });

        // ── AUTOSAVE ──
        const storageKey = 'opname_draft_' + sessionId;
        function saveDraft() {
            const unitInputs = {};
            multiUnitRows.querySelectorAll('.op-multi-input').forEach(function(input) {
                unitInputs[input.dataset.unit] = input.value;
            });
            const data = { scan: scanInput.value, productId: productSelect.value, units: unitInputs, notes: itemNotes.value, ts: Date.now() };
            try {
                localStorage.setItem(storageKey, JSON.stringify(data));
                $('autosaveText').textContent = 'Tersimpan ' + new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
            } catch(e) {}
        }
        function loadDraft() {
            try {
                const raw = localStorage.getItem(storageKey);
                if (!raw) return;
                const data = JSON.parse(raw);
                if (Date.now() - data.ts > 86400000) { localStorage.removeItem(storageKey); return; }
                if (data.scan) scanInput.value = data.scan;
                if (data.productId) {
                    productSelect.value = data.productId;
                    populateUnits(data.productId);
                    if (data.units) {
                        multiUnitRows.querySelectorAll('.op-multi-input').forEach(function(input) {
                            if (data.units[input.dataset.unit] !== undefined) input.value = data.units[input.dataset.unit];
                        });
                        calculateMultiUnitTotal();
                    }
                }
                if (data.notes) itemNotes.value = data.notes;
            } catch(e) {}
        }
        loadDraft();
        setInterval(saveDraft, 3000);

        // ── SUBMIT ADD ITEM ──
        function submitAdd() {
            if (addBtn.disabled) return;
            if (!scanInput.value.trim() && !productSelect.value) {
                showFeedback('Scan barcode atau pilih produk terlebih dahulu.', 'error');
                return;
            }
            const result = getMultiUnitBreakdown();
            if (result.total < 0) {
                showFeedback('Qty fisik harus diisi dengan angka >= 0.', 'error');
                return;
            }
            addBtn.disabled = true;
            formScan.value = scanInput.value.trim();
            formProductId.value = productSelect.value;
            formPhysical.value = result.total;
            formCountedUnit.value = result.breakdown;
            formCountedQty.value = '';
            formNotes.value = itemNotes.value;
            try { localStorage.removeItem(storageKey); } catch(e) {}
            addForm.submit();
        }
        addBtn.addEventListener('click', submitAdd);

        // ── SCANNER ──
        scanInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                // Focus first unit input in multi-unit panel
                const firstUnitInput = multiUnitRows.querySelector('.op-multi-input');
                if (firstUnitInput) {
                    firstUnitInput.focus();
                    firstUnitInput.select();
                } else {
                    submitAdd();
                }
            }
        });
        setTimeout(() => scanInput.focus(), 100);
        itemNotes.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); submitAdd(); } });

        // ── TABLE SEARCH ──
        const tableSearch = $('tableSearch');
        if (tableSearch) {
            tableSearch.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.op-item-row').forEach(function(row) {
                    const text = row.dataset.product || '';
                    row.style.display = (!q || text.includes(q)) ? '' : 'none';
                });
            });
        }

        // ── BULK PASTE ──
        bulkPasteBtn.addEventListener('click', function() {
            bulkModal.style.display = 'flex';
            bulkTextarea.value = '';
            bulkPreview.textContent = '';
            bulkProgress.style.display = 'none';
            bulkSubmitBtn.disabled = false;
            bulkTextarea.focus();
        });
        bulkTextarea.addEventListener('input', function() {
            const lines = this.value.split('\n').filter(l => l.trim());
            bulkPreview.innerHTML = '<span class="op-bp-count">' + lines.length + ' baris terdeteksi</span>';
        });
        bulkSubmitBtn.addEventListener('click', function() {
            const lines = bulkTextarea.value.split('\n').filter(l => l.trim());
            if (lines.length === 0) { alert('Tidak ada data.'); return; }
            bulkSubmitBtn.disabled = true;
            bulkProgress.style.display = 'block';
            const barFill = $('bulkBarFill'), barText = $('bulkBarText');
            let idx = 0;
            function submitNext() {
                if (idx >= lines.length) {
                    bulkProgress.querySelector('#bulkBarText').textContent = 'Selesai! ' + lines.length + ' item ditambahkan.';
                    setTimeout(() => { bulkModal.style.display = 'none'; location.reload(); }, 800);
                    return;
                }
                const parts = lines[idx].split(/[\t;|,]+/).map(s => s.trim());
                barFill.style.width = ((idx + 1) / lines.length * 100) + '%';
                barText.textContent = (idx + 1) + ' / ' + lines.length;
                if (parts.length >= 2) {
                    formScan.value = parts[0];
                    formProductId.value = '';
                    formPhysical.value = parseInt(parts[1]) || 0;
                    formCountedUnit.value = '';
                    formCountedQty.value = parts[1] || '0';
                    formNotes.value = parts[2] || '';
                    try { localStorage.removeItem(storageKey); } catch(e) {}
                    addForm.submit();
                } else {
                    idx++;
                    submitNext();
                }
            }
            submitNext();
        });

        // ── FEEDBACK HELPER ──
        function showFeedback(msg, type) {
            alert(msg);
        }
    });

    // ── UNCOUNTED PRODUCTS ──
    function toggleUncounted() {
        const body = document.getElementById('uncBody');
        const chevron = document.getElementById('uncChevron');
        if (body.classList.contains('op-show')) {
            body.classList.remove('op-show');
            chevron.style.transform = '';
        } else {
            body.classList.add('op-show');
            chevron.style.transform = 'rotate(180deg)';
        }
    }
    function selectUncounted(el) {
        const select = document.getElementById('productSelect');
        const search = document.getElementById('productSearch');
        const scanInput = document.getElementById('scanInput');
        if (select) { select.value = el.getAttribute('data-product-id'); select.dispatchEvent(new Event('change')); }
        if (search) { search.value = ''; search.dispatchEvent(new Event('input')); }
        if (scanInput) scanInput.value = '';
        // Focus first unit input in multi-unit panel
        const firstUnitInput = document.querySelector('#multiUnitRows .op-multi-input');
        if (firstUnitInput) { firstUnitInput.focus(); firstUnitInput.select(); }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    // Uncounted search filter
    (function() {
        const uncSearch = document.getElementById('uncSearch');
        if (uncSearch) {
            uncSearch.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.op-unc-item').forEach(function(item) {
                    const text = item.dataset.search || '';
                    item.style.display = (!q || text.includes(q)) ? '' : 'none';
                });
            });
        }
    })();

    function closeBulkModal() {
        document.getElementById('bulkModal').style.display = 'none';
    }
    </script>
    @endpush

    @push('styles')
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    :root {
        --bg: #f1f5f9; --surface: #ffffff; --border: #e2e8f0; --border-light: #f1f5f9;
        --text: #0f172a; --text2: #475569; --muted: #94a3b8;
        --primary: #2563eb; --primary-dark: #1d4ed8; --primary-light: #dbeafe;
        --success: #10b981; --success-bg: #ecfdf5; --success-text: #065f46; --success-border: #a7f3d0;
        --danger: #ef4444; --danger-bg: #fef2f2; --danger-text: #991b1b; --danger-border: #fecaca;
        --warning: #f59e0b; --warning-bg: #fffbeb; --warning-text: #92400e; --warning-border: #fde68a;
        --info: #0ea5e9; --info-bg: #e0f2fe; --info-text: #0369a1;
        --r: 14px; --r-sm: 8px;
        --shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.03);
        --shadow-lg: 0 4px 20px rgba(0,0,0,0.06);
    }
    *, *::before, *::after { box-sizing: border-box; }
    .op-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: var(--text); max-width: 1200px; margin: 0 auto;
        padding: 1.25rem 1rem 4rem; background: var(--bg); min-height: 100vh;
    }
    .op-form-inline { display: inline; }

    /* ── TOPBAR ── */
    .op-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: .75rem; }
    .op-back { display: inline-flex; align-items: center; gap: 4px; font-size: .9rem; font-weight: 700; color: var(--text2); text-decoration: none; transition: color .2s; }
    .op-back:hover { color: var(--primary); }
    .op-topbar-actions { display: flex; gap: .6rem; flex-wrap: wrap; }

    /* ── BUTTONS ── */
    .op-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: .55rem 1.1rem; border-radius: 10px; font-size: .82rem; font-weight: 700; font-family: inherit; cursor: pointer; border: 1px solid transparent; text-decoration: none; transition: all .2s; white-space: nowrap; height: 40px; }
    .op-btn:disabled { opacity: .5; cursor: not-allowed; }
    .op-btn:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.08); }
    .op-btn-ghost { border-color: var(--border); color: var(--text2); background: var(--surface); }
    .op-btn-ghost:hover { border-color: var(--muted); color: var(--text); }
    .op-btn-primary { background: linear-gradient(135deg, var(--primary), #1e40af); color: #fff; border: none; box-shadow: 0 2px 8px rgba(37,99,235,.2); }
    .op-btn-success { background: linear-gradient(135deg, var(--success), #047857); color: #fff; border: none; box-shadow: 0 2px 8px rgba(16,185,129,.2); }
    .op-btn-amber { background: linear-gradient(135deg, var(--warning), #b45309); color: #fff; border: none; box-shadow: 0 2px 8px rgba(245,158,11,.2); }
    .op-btn-danger-outline { border-color: var(--danger-border); color: var(--danger-text); background: rgba(254,242,242,.5); }
    .op-btn-danger-outline:hover { background: var(--danger-bg); border-color: var(--danger); }
    .op-btn-add { width: 100%; height: 48px; background: linear-gradient(135deg, var(--primary), #1e40af); color: #fff; font-size: .9rem; font-weight: 800; border-radius: 12px; border: none; box-shadow: 0 4px 15px rgba(37,99,235,.25); }
    .op-btn-add:hover { box-shadow: 0 6px 20px rgba(37,99,235,.35); }
    .op-btn-sm { display: inline-flex; align-items: center; gap: 5px; padding: .3rem .75rem; border-radius: 8px; font-size: .73rem; font-weight: 700; font-family: inherit; cursor: pointer; border: 1px solid var(--border); color: var(--text2); background: var(--surface); transition: all .2s; }
    .op-btn-sm:hover { border-color: var(--muted); color: var(--text); transform: translateY(-1px); }
    .op-btn-sm-info { color: var(--info-text); border-color: #bae6fd; background: var(--info-bg); }

    /* ── INFO CARD ── */
    .op-info-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); padding: 1.25rem 1.5rem; margin-bottom: 1.25rem; display: flex; gap: 1.25rem; align-items: center; box-shadow: var(--shadow); }
    .op-info-icon { width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #fcd34d, #f59e0b); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .op-info-body { flex: 1; min-width: 0; }
    .op-info-title { font-size: 1.25rem; font-weight: 800; margin: 0 0 .35rem; color: var(--text); }
    .op-info-meta { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; font-size: .82rem; color: var(--text2); font-weight: 500; }
    .op-meta { display: inline-flex; align-items: center; gap: 4px; }
    .op-meta strong { color: var(--text); font-weight: 800; }
    .op-dot { width: 3px; height: 3px; border-radius: 50%; background: #cbd5e1; flex-shrink: 0; }
    .op-status { display: inline-block; padding: .15rem .55rem; border-radius: 6px; font-size: .68rem; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; }
    .op-status-draft { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .op-status-submitted { background: var(--info-bg); color: var(--info-text); border: 1px solid #bae6fd; }
    .op-status-approved { background: var(--success-bg); color: var(--success-text); border: 1px solid var(--success-border); }
    .op-status-rejected { background: var(--danger-bg); color: var(--danger-text); border: 1px solid var(--danger-border); }
    .op-status-cancelled { background: #e2e8f0; color: #475569; }
    .op-badge-danger { color: var(--danger); font-weight: 800; font-size: .72rem; }
    .op-badge-warn { color: var(--warning); font-weight: 800; font-size: .72rem; }
    .op-info-notes { font-size: .82rem; color: var(--text2); margin-top: .5rem; font-style: italic; }

    /* ── WORKFLOW GUIDE ── */
    .op-guide { display: flex; align-items: center; background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); padding: 1rem 1.5rem; margin-bottom: 1.25rem; box-shadow: var(--shadow); gap: 0; }
    .op-guide-step { display: flex; align-items: center; gap: 10px; flex: 0 0 auto; }
    .op-guide-num { width: 32px; height: 32px; border-radius: 50%; background: #f1f5f9; color: var(--muted); font-size: .82rem; font-weight: 800; display: flex; align-items: center; justify-content: center; border: 2px solid #e2e8f0; transition: all .3s; flex-shrink: 0; }
    .op-guide-active .op-guide-num { background: var(--primary); color: #fff; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(37,99,235,.12); }
    .op-guide-done .op-guide-num { background: var(--success); color: #fff; border-color: var(--success); }
    .op-guide-text { display: flex; flex-direction: column; }
    .op-guide-text strong { font-size: .82rem; font-weight: 800; color: var(--muted); }
    .op-guide-active .op-guide-text strong { color: var(--primary); }
    .op-guide-done .op-guide-text strong { color: var(--success); }
    .op-guide-text small { font-size: .68rem; color: var(--muted); font-weight: 500; }
    .op-guide-line { flex: 1; height: 2px; background: #e2e8f0; margin: 0 12px; min-width: 20px; border-radius: 2px; transition: background .3s; }
    .op-guide-line-done { background: var(--success); }

    /* ── ALERTS ── */
    .op-alert { display: flex; align-items: flex-start; gap: 10px; padding: .85rem 1.1rem; border-radius: 10px; margin-bottom: .75rem; font-size: .85rem; font-weight: 600; border: 1px solid; }
    .op-alert-success { background: var(--success-bg); color: var(--success-text); border-color: var(--success-border); }
    .op-alert-danger { background: var(--danger-bg); color: var(--danger-text); border-color: var(--danger-border); }
    .op-alert-warning { background: var(--warning-bg); color: var(--warning-text); border-color: var(--warning-border); }

    /* ── SUMMARY STATS ── */
    .op-stats { display: grid; grid-template-columns: repeat(5, 1fr); gap: .6rem; margin-bottom: 1.25rem; }
    .op-stat { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: .85rem .75rem; text-align: center; box-shadow: var(--shadow); }
    .op-stat-main { background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border-color: #bae6fd; }
    .op-stat-main .op-stat-val { color: var(--primary); font-size: 1.6rem; }
    .op-stat-main .op-stat-lbl { color: var(--info-text); font-weight: 700; }
    .op-stat-val { font-size: 1.3rem; font-weight: 800; color: var(--text); line-height: 1.2; }
    .op-stat-lbl { font-size: .7rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; margin-top: .2rem; }
    .op-stat-plus .op-stat-val { color: var(--success); }
    .op-stat-minus .op-stat-val { color: var(--danger); }
    .op-stat-ok .op-stat-val { color: var(--text2); }

    /* ── CARDS ── */
    .op-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); margin-bottom: 1.25rem; overflow: hidden; box-shadow: var(--shadow); }
    .op-card-locked { opacity: .6; }
    .op-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-light); background: rgba(248,250,252,.5); }
    .op-card-head-row { display: flex; justify-content: space-between; align-items: center; gap: .75rem; flex-wrap: wrap; }
    .op-card-title { display: flex; align-items: center; gap: 8px; font-size: 1rem; font-weight: 800; color: var(--text); margin: 0; }
    .op-card-tools { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }
    .op-card-body { padding: 1.25rem; }
    .op-card-locked-msg { padding: 1.5rem; display: flex; align-items: center; gap: 10px; color: var(--muted); font-size: .9rem; font-weight: 600; }
    .op-count-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 24px; height: 22px; padding: 0 7px; border-radius: 11px; font-size: .72rem; font-weight: 800; background: var(--primary); color: #fff; }
    .op-autosave { display: inline-flex; align-items: center; gap: 4px; font-size: .72rem; color: var(--success); font-weight: 700; background: var(--success-bg); padding: .25rem .5rem; border-radius: 6px; }

    /* ── TARIK PENJUALAN BANNER ── */
    .op-pull-banner { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; margin: 1rem 1.25rem 0; background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 1.5px solid var(--warning-border); border-radius: 12px; }
    .op-pull-icon { width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, var(--warning), #b45309); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .op-pull-info { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 2px; }
    .op-pull-info strong { font-size: .9rem; font-weight: 800; color: var(--warning-text); }
    .op-pull-info span { font-size: .75rem; color: #78716c; font-weight: 500; }
    .op-btn-pull { display: inline-flex; align-items: center; gap: 6px; padding: .55rem 1.1rem; border-radius: 10px; font-size: .82rem; font-weight: 800; font-family: inherit; cursor: pointer; border: none; background: linear-gradient(135deg, var(--warning), #b45309); color: #fff; box-shadow: 0 2px 8px rgba(245,158,11,.3); transition: all .2s; white-space: nowrap; }
    .op-btn-pull:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(245,158,11,.4); }

    /* ── FORM STEP DIVIDERS ── */
    .op-form-divider-label { display: flex; align-items: center; gap: 8px; font-size: .78rem; font-weight: 800; color: var(--text2); text-transform: uppercase; letter-spacing: .04em; margin-bottom: .75rem; padding-bottom: .5rem; border-bottom: 1.5px dashed var(--border); }
    .op-form-step-badge { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 7px; background: linear-gradient(135deg, var(--primary), #1e40af); color: #fff; font-size: .72rem; font-weight: 900; flex-shrink: 0; }

    /* ── FORM LAYOUT ── */
    .op-form-row1 { display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; margin-bottom: 1rem; }
    .op-field { display: flex; flex-direction: column; gap: 5px; }
    .op-field-wide { min-width: 0; }
    .op-field-add { align-self: end; }
    .op-label { font-size: .72rem; font-weight: 800; color: var(--text2); text-transform: uppercase; letter-spacing: .04em; display: flex; align-items: center; gap: 4px; }
    .op-req { color: var(--danger); }
    .op-input { width: 100%; padding: .6rem .85rem; border: 1.5px solid var(--border); border-radius: 10px; font-family: inherit; font-size: .85rem; font-weight: 600; color: var(--text); background: #f8fafc; outline: none; transition: all .2s; height: 44px; }
    .op-input:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(37,99,235,.08); }
    .op-input-scan { font-family: 'JetBrains Mono', 'Courier New', monospace; font-size: .9rem; letter-spacing: .03em; }
    .op-input-search { height: 32px; font-size: .78rem; padding: .35rem .7rem; border-radius: 8px; margin-bottom: 2px; }
    .op-input-table-search { height: 34px; width: 180px; font-size: .8rem; padding: .3rem .7rem; border-radius: 8px; }
    .op-input-unc-search { height: 36px; font-size: .82rem; margin-bottom: .75rem; }
    .op-select { appearance: none; padding-right: 2.2rem; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; background-size: 14px; }
    .op-select[size="1"] { overflow: hidden; }
    .op-select[size]:not([size="1"]) { height: auto; max-height: 200px; overflow-y: auto; background-position: right 10px top 12px; }
    .op-field-hint { font-size: .68rem; color: var(--muted); font-weight: 500; }
    .op-unit-hint { font-size: .72rem; color: var(--muted); margin-top: 3px; font-weight: 500; }
    .op-unit-hint strong { color: var(--text); font-weight: 800; }

    /* ── BREAKDOWN ── */
    .op-breakdown { margin-top: 1rem; padding: .85rem 1rem; background: #f8fafc; border: 1.5px dashed var(--border); border-radius: 10px; display: flex; gap: .6rem; flex-wrap: wrap; align-items: center; font-size: .82rem; color: var(--text2); }
    .op-sb-label { font-weight: 800; color: var(--text); }
    .op-sb-u { display: inline-flex; padding: .15rem .45rem; border-radius: 6px; background: #fff; border: 1px solid var(--border); font-weight: 700; font-size: .78rem; color: var(--text); }

    /* ── MULTI-UNIT INPUT PANEL ── */
    .op-multi-panel { margin-bottom: 1rem; background: #f8fafc; border: 1.5px solid var(--border); border-radius: 12px; padding: 1rem 1.1rem; }
    .op-multi-hint { display: flex; align-items: center; gap: 6px; font-size: .75rem; color: var(--muted); font-weight: 600; margin-bottom: .75rem; }
    .op-multi-hint svg { flex-shrink: 0; opacity: .6; }
    .op-multi-row { display: flex; align-items: center; gap: .75rem; padding: .55rem 0; border-bottom: 1px dashed #e2e8f0; }
    .op-multi-row:last-of-type { border-bottom: none; }
    .op-multi-row-base { background: rgba(37,99,235,.03); margin: 0 -.5rem; padding: .55rem .5rem; border-radius: 8px; border-bottom: none; }
    .op-multi-label { display: flex; align-items: center; gap: 6px; min-width: 120px; flex-shrink: 0; }
    .op-multi-name { font-weight: 800; font-size: .85rem; color: var(--text); }
    .op-multi-factor { font-size: .72rem; color: var(--muted); font-weight: 700; background: #f1f5f9; padding: .1rem .4rem; border-radius: 5px; }
    .op-multi-badge { font-size: .62rem; color: var(--primary); font-weight: 800; background: var(--primary-light); padding: .15rem .4rem; border-radius: 5px; text-transform: uppercase; letter-spacing: .03em; }
    .op-multi-input-wrap { display: flex; align-items: center; gap: .5rem; flex: 1; }
    .op-multi-input { width: 110px; text-align: center; font-weight: 800; font-size: 1.05rem; height: 44px; }
    .op-multi-eq { font-size: .75rem; color: var(--muted); font-weight: 600; white-space: nowrap; }
    .op-multi-total { display: flex; align-items: center; gap: .5rem; margin-top: .75rem; padding: .75rem 1rem; background: linear-gradient(135deg, var(--primary-light), #eff6ff); border: 1.5px solid #93c5fd; border-radius: 10px; font-size: .9rem; font-weight: 700; }
    .op-multi-total-label { color: var(--text2); font-weight: 700; }
    .op-multi-total strong { color: var(--primary); font-size: 1.15rem; font-weight: 900; }
    .op-multi-total-detail { color: var(--muted); font-size: .78rem; font-weight: 600; }
    .op-form-row-bottom { display: grid; grid-template-columns: 1fr auto; gap: 1rem; align-items: end; }

    /* ── TABLE ── */
    .op-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .op-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 850px; }
    .op-table thead th { font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: #64748b; padding: .75rem 1rem; background: #f8fafc; border-bottom: 1.5px solid var(--border); white-space: nowrap; text-align: left; user-select: none; }
    .op-table tbody tr { transition: background .15s; }
    .op-table tbody tr:hover { background: #f8fafc; }
    .op-table tbody td { padding: .75rem 1rem; font-size: .82rem; vertical-align: middle; border-bottom: 1px solid var(--border-light); }
    .op-table tbody tr:last-child td { border-bottom: none; }
    .op-table th.c, .op-table td.c { text-align: center; }
    .op-table th.r, .op-table td.r { text-align: right; }
    .op-row-diff td { background: #fffef5 !important; }
    .op-row-diff:hover td { background: #fefce8 !important; }
    .op-row-num { font-size: .72rem; font-weight: 700; color: var(--muted); }

    .op-prod-name { font-weight: 800; color: var(--text); font-size: .85rem; line-height: 1.3; }
    .op-prod-sku { font-size: .72rem; color: var(--muted); font-family: 'JetBrains Mono', monospace; font-weight: 600; margin-top: 2px; }
    .op-qty-sys { font-weight: 700; font-size: .95rem; color: var(--muted); }
    .op-qty-fis { font-weight: 800; font-size: 1rem; color: var(--text); }
    .op-qty-sub { font-size: .68rem; color: var(--primary); font-weight: 700; margin-top: 2px; }
    .op-unit-pill { display: inline-block; padding: .15rem .5rem; border-radius: 6px; font-size: .68rem; font-weight: 800; background: var(--info-bg); color: var(--info-text); border: 1px solid #bae6fd; }
    .op-unit-base { font-size: .68rem; color: var(--muted); font-weight: 600; }
    .op-diff { display: inline-flex; align-items: center; justify-content: center; padding: .2rem .6rem; border-radius: 8px; font-weight: 800; font-size: .82rem; min-width: 42px; }
    .op-diff-plus { background: var(--success-bg); color: var(--success-text); border: 1.5px solid var(--success-border); }
    .op-diff-minus { background: var(--danger-bg); color: var(--danger-text); border: 1.5px solid var(--danger-border); }
    .op-diff-zero { background: #f1f5f9; color: var(--muted); font-weight: 700; border: 1.5px solid #e2e8f0; }
    .op-masked { font-size: 1.2rem; font-weight: 800; color: #cbd5e1; letter-spacing: 2px; user-select: none; }
    .op-blind-notice { display: flex; align-items: center; gap: 8px; padding: .65rem 1rem; background: linear-gradient(135deg, #eff6ff, #e0f2fe); border: 1.5px solid #93c5fd; border-radius: 10px; font-size: .78rem; font-weight: 700; color: var(--primary); margin-bottom: 1rem; }
    .op-blind-notice svg { flex-shrink: 0; }
    .op-notes-cell { font-size: .8rem; color: var(--text2); max-width: 160px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 500; }
    .op-time { font-size: .72rem; color: var(--muted); font-family: 'JetBrains Mono', monospace; font-weight: 600; }
    .op-time-empty { font-size: .72rem; color: var(--muted); }
    .op-del-btn { width: 32px; height: 32px; border: 1.5px solid var(--border); border-radius: 8px; background: var(--surface); color: var(--text2); cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all .2s; }
    .op-del-btn:hover { background: var(--danger-bg); color: var(--danger); border-color: var(--danger); }

    /* ── EMPTY ── */
    .op-empty { text-align: center; padding: 3.5rem 1rem; color: var(--muted); }
    .op-empty svg { opacity: .35; margin-bottom: .85rem; }
    .op-empty h3 { font-size: 1rem; font-weight: 800; color: var(--text); margin: 0 0 .3rem; }
    .op-empty p { font-size: .82rem; margin: 0 auto; max-width: 380px; line-height: 1.6; }

    /* ── UNCOUNTED ── */
    .op-uncounted-card { background: #fffcf5; border: 1px solid var(--warning-border); border-radius: var(--r); margin-bottom: 1.25rem; overflow: hidden; }
    .op-unc-header { display: flex; justify-content: space-between; align-items: center; padding: .85rem 1.25rem; cursor: pointer; transition: background .15s; }
    .op-unc-header:hover { background: #fefce8; }
    .op-unc-header-left { display: flex; align-items: center; gap: 8px; font-weight: 800; font-size: .9rem; color: var(--warning-text); }
    .op-unc-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 22px; height: 22px; padding: 0 6px; border-radius: 11px; font-size: .7rem; font-weight: 800; background: var(--warning); color: #fff; }
    .op-unc-body { display: none; padding: 0 1.25rem 1.25rem; }
    .op-unc-body.op-show { display: block; }
    .op-unc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: .5rem; max-height: 300px; overflow-y: auto; }
    .op-unc-item { display: flex; align-items: center; justify-content: space-between; padding: .5rem .75rem; border: 1px solid var(--border); border-radius: 8px; font-size: .8rem; cursor: pointer; transition: all .15s; background: #fff; }
    .op-unc-item:hover { background: var(--warning-bg); border-color: var(--warning); transform: translateY(-1px); box-shadow: 0 2px 6px rgba(0,0,0,.03); }
    .op-unc-item-info strong { font-size: .82rem; font-weight: 700; color: var(--text); display: block; }
    .op-unc-item-info small { color: var(--muted); font-size: .72rem; font-weight: 500; }
    .op-unc-stock { font-size: .72rem; color: var(--text2); font-weight: 700; white-space: nowrap; }

    /* ── MODAL ── */
    .op-modal { display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(15,23,42,.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; }
    .op-modal-box { background: #fff; border-radius: var(--r); padding: 1.5rem; max-width: 560px; width: 92%; box-shadow: 0 25px 50px -12px rgba(0,0,0,.2); animation: modalIn .25s ease; }
    @keyframes modalIn { from { opacity: 0; transform: translateY(16px) scale(.97); } to { opacity: 1; transform: none; } }
    .op-modal-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: .5rem; }
    .op-modal-head h3 { font-size: 1.15rem; font-weight: 800; margin: 0; color: var(--text); }
    .op-modal-close { width: 32px; height: 32px; border: none; background: none; color: var(--muted); cursor: pointer; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
    .op-modal-close:hover { background: #f1f5f9; color: var(--text); }
    .op-modal-desc { font-size: .82rem; color: var(--text2); margin: 0 0 .85rem; font-weight: 500; }
    .op-modal-desc code { background: #f1f5f9; padding: .15rem .4rem; border-radius: 4px; font-size: .78rem; }
    .op-modal-box textarea { width: 100%; padding: .85rem; border: 1.5px solid var(--border); border-radius: 10px; font-family: 'JetBrains Mono', monospace; font-size: .82rem; resize: vertical; outline: none; transition: border-color .2s; }
    .op-modal-box textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,.08); }
    .op-bulk-preview { margin-top: .5rem; }
    .op-bp-count { font-size: .78rem; font-weight: 700; color: var(--primary); }
    .op-modal-actions { display: flex; gap: .6rem; justify-content: flex-end; margin-top: 1rem; }
    .op-bulk-progress { margin-top: .75rem; display: flex; align-items: center; gap: .75rem; }
    .op-bulk-bar { flex: 1; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; }
    .op-bulk-bar-fill { height: 100%; background: var(--primary); border-radius: 4px; transition: width .3s; width: 0; }
    .op-bulk-progress span { font-size: .78rem; font-weight: 700; color: var(--text2); white-space: nowrap; }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
        .op-guide { flex-wrap: wrap; gap: 8px; padding: .85rem 1rem; }
        .op-guide-line { display: none; }
        .op-guide-step { flex: 1 1 100%; }
        .op-stats { grid-template-columns: repeat(3, 1fr); }
        .op-pull-banner { flex-wrap: wrap; margin: .75rem 1rem 0; }
        .op-pull-info { flex-basis: calc(100% - 60px); }
        .op-form-row1 { grid-template-columns: 1fr; }
        .op-form-row-bottom { grid-template-columns: 1fr; }
        .op-multi-label { min-width: 90px; }
        .op-multi-input { width: 90px; }
    }
    @media (max-width: 640px) {
        .op-topbar { flex-direction: column; align-items: stretch; }
        .op-topbar-actions { flex-wrap: wrap; }
        .op-topbar-actions .op-btn { flex: 1; min-width: 100px; }
        .op-guide-step { flex: 1 1 100%; }
        .op-stats { grid-template-columns: repeat(2, 1fr); }
        .op-pull-banner { flex-direction: column; align-items: stretch; text-align: center; gap: .75rem; margin: .75rem .75rem 0; }
        .op-pull-info { flex-basis: auto; }
        .op-form-row-bottom { grid-template-columns: 1fr; }
        .op-multi-label { min-width: 80px; }
        .op-multi-input { width: 80px; font-size: .95rem; }
        .op-multi-row { gap: .5rem; }
        .op-page { padding: .75rem .5rem 3rem; }
        .op-input-table-search { width: 100%; }
    }
    </style>
    @endpush
</x-app-layout>
