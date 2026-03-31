<x-app-layout>
    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- BACK BUTTON --}}
            <a href="{{ route('gudang.terima_transfer.index') }}" class="tr-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar
            </a>

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Cross-Check Transfer</div>
                    <h1 class="tr-title">
                        Validasi Penerimaan
                        <span class="tr-title-accent">#{{ $summary->reference_number }}</span>
                    </h1>
                    <p class="tr-subtitle">Validasi fisik barang sebelum memasukkan ke stok Gudang Cabang.</p>
                </div>
                <div class="tr-header-status">
                    <div class="tr-status-label">Status Validasi</div>
                    @if($isPending)
                        <span class="tr-badge tr-badge-pending">
                            <span class="tr-badge-dot"></span>
                            Menunggu Cross-Check
                        </span>
                    @else
                        <span class="tr-badge tr-badge-done">
                            <span class="tr-badge-dot"></span>
                            Selesai & Diterima
                        </span>
                    @endif
                </div>
            </div>

            {{-- GRID LAYOUT --}}
            <div class="tr-grid">

                {{-- INFO CARD --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <div class="tr-card-title">
                            <div class="tr-icon-box blue">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                            Informasi Transfer
                        </div>
                    </div>
                    <div class="tr-info-body">
                        <div class="tr-info-row">
                            <span class="tr-info-label">Tanggal Kirim</span>
                            <span class="tr-info-value">
                                {{ \Carbon\Carbon::parse($summary->created_at)->format('d F Y · H:i') }}
                            </span>
                        </div>
                        <div class="tr-info-row">
                            <span class="tr-info-label">Dikirim Ke</span>
                            <span class="tr-info-value">{{ optional($summary->to_warehouse)->name ?? '-' }}</span>
                        </div>
                        <div class="tr-info-row">
                            <span class="tr-info-label">Total Fisik</span>
                            <span class="tr-info-value tr-info-accent">
                                {{ number_format($summary->total_qty) }}
                                @if($summary->unit_name && $summary->unit_name !== 'satuan dasar')
                                    <span style="font-size: 0.85rem; color: var(--tr-text-muted); margin-left: 4px;">
                                        ({{ $summary->total_qty_in_unit }} {{ $summary->unit_name }})
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="tr-info-row tr-info-row-last">
                            <span class="tr-info-label">Jumlah SKU</span>
                            <span class="tr-info-value">{{ $summary->total_items }} Macam</span>
                        </div>
                    </div>
                </div>

                {{-- ITEMS CARD --}}
                <div class="tr-card tr-card-main">
                    <div class="tr-card-header">
                        <div class="tr-card-title">
                            <div class="tr-icon-box orange">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                            </div>
                            Rincian Barang Datang
                        </div>
                    </div>

                    <form action="{{ route('gudang.terima_transfer.receive', $summary->reference_number) }}"
                          method="POST" id="receiveForm">
                        @csrf
                        <div class="table-responsive">
                            <table class="tr-table">
                                <thead>
                                    <tr>
                                        <th>Nama Produk / SKU</th>
                                        <th>Batch / Exp.</th>
                                        <th class="r">Dikirim</th>
                                        <th class="r">Diterima</th>
                                        <th>Quality Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ins as $item)
                                        @php
                                            $defaultReceived = $item->status === 'completed'
                                                ? 0
                                                : (int) $item->quantity;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="tr-prod-name">
                                                    {{ optional($item->product)->name ?? 'Produk Dihapus' }}
                                                </div>
                                                <div class="tr-prod-sku">
                                                    {{ optional($item->product)->sku ?? '-' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->batch_number || $item->expired_date)
                                                    <div class="tr-batch-info">
                                                        @if($item->batch_number)
                                                            <span class="tr-batch-badge">{{ $item->batch_number }}</span>
                                                        @endif
                                                        @if($item->expired_date)
                                                            <span class="tr-batch-warn">
                                                                Exp: {{ \Carbon\Carbon::parse($item->expired_date)->format('d/m/Y') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="tr-batch-none">Tidak ada</span>
                                                @endif
                                            </td>
                                            <td class="r">
                                                <span class="tr-qty-static">{{ number_format($item->quantity) }}</span>
                                            </td>
                                            <td class="r">
                                                @if($isPending)
                                                    <input
                                                        type="number"
                                                        min="0"
                                                        max="{{ (int) $item->quantity }}"
                                                        name="items[{{ $item->id }}][received_qty]"
                                                        value="{{ old("items.{$item->id}.received_qty", $defaultReceived) }}"
                                                        class="tr-qty-input js-received"
                                                        data-expected="{{ (int) $item->quantity }}"
                                                        required
                                                    >
                                                @else
                                                    <span class="tr-qty-static tr-qty-done">
                                                        {{ number_format((int) $item->quantity) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($isPending)
                                                    <div class="tr-qc-wrap">
                                                        <div class="tr-qc-checkboxes">
                                                            <label class="tr-qc-check">
                                                                <input type="hidden" name="items[{{ $item->id }}][quality_ok]" value="0">
                                                                <input type="checkbox" name="items[{{ $item->id }}][quality_ok]" value="1" checked>
                                                                <span>Kualitas</span>
                                                            </label>
                                                            <label class="tr-qc-check">
                                                                <input type="hidden" name="items[{{ $item->id }}][spec_ok]" value="0">
                                                                <input type="checkbox" name="items[{{ $item->id }}][spec_ok]" value="1" checked>
                                                                <span>Spesifikasi</span>
                                                            </label>
                                                            <label class="tr-qc-check">
                                                                <input type="hidden" name="items[{{ $item->id }}][packaging_ok]" value="0">
                                                                <input type="checkbox" name="items[{{ $item->id }}][packaging_ok]" value="1" checked>
                                                                <span>Kemasan</span>
                                                            </label>
                                                        </div>
                                                        <input
                                                            type="text"
                                                            name="items[{{ $item->id }}][notes]"
                                                            value="{{ old("items.{$item->id}.notes") }}"
                                                            class="tr-notes-input"
                                                            placeholder="Tambahkan catatan jika ada kendala..."
                                                        >
                                                    </div>
                                                @else
                                                    <span class="tr-locked">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                                        Terkunci
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($isPending)
                            {{-- GLOBAL NOTES --}}
                            <div class="tr-global-notes">
                                <label class="tr-section-label">Catatan Cross-Check Keseluruhan</label>
                                <textarea name="notes" class="tr-textarea"
                                          placeholder="Misal: Terdapat selisih pada 2 barang, kardus sedikit basah karena hujan..."></textarea>
                            </div>

                            {{-- FOOTER ACTION --}}
                            <div class="tr-footer-action">
                                <div class="tr-warning-text">
                                    <svg class="tr-warn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    Konfirmasi bersifat permanen. Stok bertambah sesuai Qty Diterima.
                                </div>
                                <button type="submit" form="receiveForm"
                                        class="tr-submit-btn"
                                        onclick="return confirm('Simpan hasil cross-check dan proses penerimaan?')">
                                    Simpan & Proses Barang
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12l5 5L20 7"/></svg>
                                </button>
                            </div>
                        @endif
                    </form>

                    {{-- HISTORY --}}
                    @if(isset($receipts) && $receipts->isNotEmpty())
                        <div class="tr-history">
                            <div class="tr-section-label mb-2">Riwayat Cross-Check Sebelumnya</div>
                            <div class="accordion" id="receiptAccordion">
                                @foreach($receipts as $r)
                                    <div class="tr-acc-item">
                                        <button class="tr-acc-btn collapsed"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#c{{ $r->id }}">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="tr-acc-pill">{{ strtoupper($r->status) }}</span>
                                                <span class="tr-acc-date">{{ $r->created_at->format('d M Y · H:i') }}</span>
                                            </div>
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                                        </button>
                                        <div id="c{{ $r->id }}" class="accordion-collapse collapse"
                                             data-bs-parent="#receiptAccordion">
                                            <div class="tr-acc-body">
                                                @if($r->notes)
                                                    <div class="tr-acc-notes">
                                                        <strong>Catatan:</strong> {{ $r->notes }}
                                                    </div>
                                                @endif
                                                <div class="table-responsive mt-2">
                                                    <table class="tr-acc-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Produk</th>
                                                                <th class="r">Dikirim</th>
                                                                <th class="r">Diterima</th>
                                                                <th class="r">Ditolak</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($r->items as $it)
                                                                <tr>
                                                                    <td>{{ $it->product?->name ?? '-' }}</td>
                                                                    <td class="r">{{ number_format((int) $it->expected_qty) }}</td>
                                                                    <td class="r font-medium text-success">{{ number_format((int) $it->received_qty) }}</td>
                                                                    <td class="r font-medium text-danger">{{ number_format((int) $it->rejected_qty) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>{{-- end items card --}}
            </div>{{-- end grid --}}
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

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
            --tr-accent: #f97316;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            --tr-warning-bg: #fef3c7;
            --tr-warning-text: #92400e;
            --tr-danger-bg: #fee2e2;
            --tr-danger-text: #991b1b;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper {
            background-color: var(--tr-bg);
            min-height: 100vh;
        }

        .tr-page {
            padding: 1.5rem;
            max-width: 1200px; /* Lebar maksimal disamakan dengan index */
            margin: 0 auto;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--tr-text-main);
        }

        /* ── BACK BUTTON ── */
        .tr-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--tr-text-muted);
            text-decoration: none;
            margin-bottom: 1rem;
            transition: color 0.2s;
        }
        .tr-back:hover { color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 1.5rem;
            gap: 1.25rem;
            flex-wrap: wrap;
        }
        .tr-eyebrow {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--tr-primary);
            margin-bottom: 0.25rem;
        }
        .tr-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--tr-text-main);
            letter-spacing: -0.02em;
            margin: 0;
        }
        .tr-title-accent { color: var(--tr-text-light); font-weight: 500; }
        .tr-subtitle {
            font-size: 0.85rem;
            color: var(--tr-text-muted);
            margin: 0.25rem 0 0;
        }
        .tr-header-status { text-align: right; }
        .tr-status-label {
            font-size: 0.7rem;
            color: var(--tr-text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.35rem;
        }
        .tr-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .tr-badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: currentColor;
        }
        .tr-badge-pending { background: var(--tr-warning-bg); color: var(--tr-warning-text); }
        .tr-badge-done    { background: var(--tr-success-bg); color: var(--tr-success-text); }

        /* ── GRID ── */
        .tr-grid {
            display: grid;
            grid-template-columns: 280px 1fr; /* Kolom kiri sedikit lebih ramping */
            gap: 1.25rem;
            align-items: start;
        }
        @media (max-width: 992px) {
            .tr-grid { grid-template-columns: 1fr; }
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-header-status { text-align: left; margin-top: 0.5rem; }
        }

        /* ── CARD ── */
        .tr-card {
            background: var(--tr-surface);
            border-radius: var(--tr-radius-lg);
            border: 1px solid var(--tr-border);
            box-shadow: var(--tr-shadow-sm);
            overflow: hidden;
        }
        .tr-card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--tr-border-light);
            background: #ffffff;
        }
        .tr-card-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--tr-text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .tr-icon-box {
            display: flex; align-items: center; justify-content: center;
            width: 28px; height: 28px;
            border-radius: 6px;
        }
        .tr-icon-box.blue { background: #eff6ff; color: var(--tr-primary); }
        .tr-icon-box.orange { background: #fff7ed; color: var(--tr-accent); }

        /* ── INFO LIST ── */
        .tr-info-body { padding: 0.25rem 1.25rem 1.25rem; }
        .tr-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.85rem 0;
            border-bottom: 1px dashed var(--tr-border);
        }
        .tr-info-row-last { border-bottom: none; padding-bottom: 0; }
        .tr-info-label { font-size: 0.8rem; color: var(--tr-text-muted); font-weight: 500; }
        .tr-info-value { font-size: 0.85rem; font-weight: 600; color: var(--tr-text-main); text-align: right; }
        .tr-info-accent { font-size: 1rem; font-weight: 800; color: var(--tr-primary); }

        /* ── TABLE RESPONSIVE WRAPPER ── */
        .table-responsive {
            width: 100%;
            overflow-x: auto; 
            -webkit-overflow-scrolling: touch;
        }

        /* ── TABLE ── */
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 600px; }
        .tr-table thead th {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--tr-text-muted);
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid var(--tr-border);
            background: var(--tr-bg);
            white-space: nowrap;
        }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { 
            padding: 0.85rem 1.25rem; 
            font-size: 0.8rem; 
            vertical-align: top;
            border-bottom: 1px solid var(--tr-border-light);
        }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* ── CELL ELEMENTS ── */
        .tr-prod-name { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; line-height: 1.4; }
        .tr-prod-sku  { font-size: 0.75rem; color: var(--tr-text-light); margin-top: 2px; font-weight: 500; }
        
        .tr-batch-info { display: flex; flex-direction: column; gap: 4px; align-items: flex-start; }
        .tr-batch-badge { 
            background: #f1f5f9; border: 1px solid #e2e8f0; 
            padding: 1px 6px; border-radius: 4px; 
            font-size: 0.7rem; font-weight: 600; color: var(--tr-text-muted); 
        }
        .tr-batch-warn { font-size: 0.7rem; color: #ea580c; font-weight: 500; }
        .tr-batch-none { font-size: 0.75rem; color: var(--tr-text-light); }

        .tr-qty-static {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--tr-text-muted);
            background: #f1f5f9;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            display: inline-block;
        }
        .tr-qty-done { background: var(--tr-success-bg); color: var(--tr-success-text); }

        .tr-qty-input {
            width: 80px;
            padding: 0.4rem;
            border: 1px solid var(--tr-border);
            border-radius: var(--tr-radius-md);
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 700;
            text-align: right;
            outline: none;
            transition: all 0.2s;
            background: #ffffff;
            color: var(--tr-text-main);
        }
        .tr-qty-input:focus { border-color: var(--tr-primary); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }

        /* ── QC ELEMENTS ── */
        .tr-qc-wrap { display: flex; flex-direction: column; gap: 8px; }
        .tr-qc-checkboxes { display: flex; flex-wrap: wrap; gap: 8px; }
        .tr-qc-check {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--tr-text-muted);
            cursor: pointer;
            user-select: none;
        }
        .tr-qc-check input[type="checkbox"] {
            width: 14px; height: 14px;
            accent-color: var(--tr-primary);
            cursor: pointer;
            border-radius: 4px;
        }
        .tr-notes-input {
            width: 100%;
            padding: 0.4rem 0.6rem;
            border: 1px solid var(--tr-border);
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.75rem;
            color: var(--tr-text-main);
            outline: none;
            transition: border-color 0.2s;
            background: #f8fafc;
        }
        .tr-notes-input:focus { border-color: var(--tr-primary); background: #ffffff; }
        .tr-locked { display: flex; align-items: center; gap: 4px; font-size: 0.75rem; color: var(--tr-text-light); font-weight: 500; }

        /* ── GLOBAL NOTES ── */
        .tr-global-notes { padding: 1.25rem; border-top: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-section-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--tr-text-main);
            margin-bottom: 0.5rem;
        }
        .tr-textarea {
            width: 100%;
            border: 1px solid var(--tr-border);
            border-radius: var(--tr-radius-md);
            padding: 0.6rem 0.85rem;
            font-family: inherit;
            font-size: 0.8rem;
            color: var(--tr-text-main);
            outline: none;
            resize: vertical;
            background: #f8fafc;
            min-height: 70px;
            transition: all 0.2s;
        }
        .tr-textarea:focus { border-color: var(--tr-primary); background: #ffffff; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

        /* ── FOOTER ACTION ── */
        .tr-footer-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem;
            border-top: 1px solid var(--tr-border);
            background: #f8fafc;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .tr-warning-text {
            font-size: 0.8rem;
            color: var(--tr-text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }
        .tr-warn-icon { color: var(--tr-accent); }
        .tr-submit-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.6rem 1.25rem;
            background: var(--tr-text-main);
            border: none;
            border-radius: var(--tr-radius-md);
            color: #ffffff;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .tr-submit-btn:hover { background: #000000; transform: translateY(-1px); }

        /* ── HISTORY ACCORDION ── */
        .tr-history { padding: 1.25rem; border-top: 1px solid var(--tr-border); background: #ffffff; }
        .tr-acc-item {
            border: 1px solid var(--tr-border);
            border-radius: var(--tr-radius-md);
            margin-bottom: 0.5rem;
            overflow: hidden;
        }
        .tr-acc-btn {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.85rem 1rem;
            background: #f8fafc;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }
        .tr-acc-btn:not(.collapsed) svg { transform: rotate(180deg); }
        .tr-acc-btn svg { transition: transform 0.3s; color: var(--tr-text-light); }
        .tr-acc-pill {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            background: var(--tr-border);
            color: var(--tr-text-main);
        }
        .tr-acc-date { font-size: 0.8rem; color: var(--tr-text-muted); font-weight: 500; }
        .tr-acc-body { padding: 1rem; border-top: 1px solid var(--tr-border); background: #ffffff; }
        .tr-acc-notes { font-size: 0.8rem; color: var(--tr-text-main); margin-bottom: 0.85rem; background: var(--tr-warning-bg); padding: 0.6rem; border-radius: 6px; }
        .tr-acc-table { width: 100%; border-collapse: collapse; }
        .tr-acc-table th { font-size: 0.7rem; color: var(--tr-text-muted); padding: 0.4rem; text-transform: uppercase; font-weight: 700; border-bottom: 1px solid var(--tr-border); }
        .tr-acc-table td { font-size: 0.8rem; padding: 0.6rem 0.4rem; color: var(--tr-text-main); border-bottom: 1px dashed var(--tr-border-light); }
        .tr-acc-table .r { text-align: right; }
        .font-medium { font-weight: 600; }
        .text-success { color: var(--tr-success-text); }
        .text-danger { color: #dc2626; }
    </style>
    @endpush
</x-app-layout>