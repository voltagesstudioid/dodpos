<x-app-layout>
    <x-slot name="header">Terima PO: {{ $order->po_number }}</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">
        
            {{-- ALERTS --}}
            @if(session('error'))
                <div class="tr-alert tr-alert-danger">
                    <svg class="tr-alert-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <div>
                        <strong>Gagal Menyimpan Penerimaan:</strong><br>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="tr-alert tr-alert-danger">
                    <svg class="tr-alert-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <div>
                        <strong>Terdapat Kesalahan:</strong>
                        <ul class="tr-alert-list">
                            @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- HEADER SECTION --}}
            <div class="tr-doc-header">
                <div class="tr-doc-info">
                    <a href="{{ route('gudang.terimapo.index') }}" class="tr-back-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali ke Antrian
                    </a>
                    <h1 class="tr-title">
                        <span class="tr-title-icon-box bg-indigo">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        </span>
                        Terima Barang PO
                    </h1>
                    <div class="tr-doc-meta">
                        Surat Jalan / PO No. <strong>{{ $order->po_number }}</strong>
                    </div>
                </div>
                <div class="tr-supplier-card">
                    <div class="tr-supplier-label">Supplier</div>
                    <div class="tr-supplier-name">{{ $order->supplier->name }}</div>
                    <div class="tr-supplier-date">Tgl Pesan: {{ $order->order_date->format('d M Y') }}</div>
                </div>
            </div>

            <form action="{{ route('gudang.terimapo.store', $order) }}" method="POST" id="terima-form" enctype="multipart/form-data">
                @csrf
                
                {{-- STEP 1: PILIH GUDANG & CATATAN UMUM --}}
                <div class="tr-card" style="margin-bottom:1.5rem;">
                    <div class="tr-card-header tr-header-highlight">
                        <h2 class="tr-section-title">
                            <span class="tr-step-number">1</span> Simpan di Gudang Mana? <span class="tr-req">*</span>
                        </h2>
                    </div>
                    <div class="tr-card-body">
                        <div class="tr-grid-general">
                            <div class="tr-form-group tr-col-main">
                                <div class="tr-select-wrapper">
                                    <select name="warehouse_id" class="tr-select" required>
                                        <option value="">-- Pilih Gudang Tujuan --</option>
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            @if($order->notes)
                                <div class="tr-po-notes-box">
                                    <span class="tr-notes-label">CATATAN PEMBELIAN:</span>
                                    <div class="tr-notes-content">{{ $order->notes }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="tr-grid-2">
                            <div class="tr-form-group">
                                <label class="tr-label">Catatan Kekurangan (Opsional)</label>
                                <textarea name="shortage_notes" rows="2" class="tr-textarea" placeholder="Misal: Supplier kirim sebagian, sisanya menyusul / barang rusak">{{ old('shortage_notes') }}</textarea>
                            </div>
                            <div class="tr-form-group">
                                <label class="tr-label">Catatan Penerimaan (Opsional)</label>
                                <textarea name="receipt_notes" rows="2" class="tr-textarea" placeholder="Misal: Kondisi kardus baik, suhu ruang sesuai standar">{{ old('receipt_notes') }}</textarea>
                            </div>
                            <div class="tr-form-group tr-col-full">
                                <label class="tr-label">Foto Bukti Surat Jalan / Barang (Maks 6 foto)</label>
                                <input type="file" name="receipt_photos[]" accept="image/png,image/jpeg" multiple class="tr-file-input">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 2: TABEL CEK FISIK --}}
                <div class="tr-card">
                    <div class="tr-card-header tr-header-highlight-green">
                        <h2 class="tr-section-title">
                            <span class="tr-step-number bg-green">2</span> Ceklis & Masukkan Qty Fisik
                        </h2>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="tr-input-table">
                            <thead>
                                <tr>
                                    <th class="c tr-col-check">CEK</th>
                                    <th>NAMA PRODUK</th>
                                    <th class="c tr-col-sisa">SISA ANTRIAN</th>
                                    <th class="c tr-col-qty">QTY TERIMA</th>
                                    <th class="tr-col-status">STATUS</th>
                                    <th class="tr-col-qc">QC CHECK</th>
                                    <th class="tr-col-batch">BATCH / EXPIRED</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $i => $item)
                                    @php 
                                        $remaining = $item->qty_ordered - $item->qty_received; 
                                        $isDone = $remaining <= 0;
                                        $unitAbbr = $item->unit->abbreviation ?? $item->product->unit->abbreviation ?? '-';
                                    @endphp
                                    
                                    @if(!$isDone)
                                    <tr class="item-row">
                                        <td class="c tr-cell-bg">
                                            <label class="tr-custom-checkbox">
                                                <input type="checkbox" class="validation-check" id="check_{{$i}}" name="items[{{$i}}][checked]" value="1">
                                                <span class="checkmark"></span>
                                            </label>
                                            <input type="hidden" name="items[{{$i}}][item_id]" value="{{ $item->id }}">
                                        </td>
                                        
                                        <td>
                                            <div class="tr-prod-name">{{ $item->product->name }}</div>
                                            <div class="tr-prod-meta">
                                                <span class="tr-sku-box">{{ $item->product->sku }}</span>
                                                @if($item->qty_received > 0)
                                                    <span class="tr-badge-received">Telah diterima: {{ $item->qty_received }} {{ $unitAbbr }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        
                                        <td class="c tr-cell-bg tr-cell-border">
                                            <div class="tr-remaining-val">{{ $remaining }}</div>
                                            <div class="tr-unit-text">{{ $unitAbbr }}</div>
                                        </td>
                                        
                                        <td>
                                            <div class="qty-wrapper">
                                                <input type="number" name="items[{{$i}}][qty]" class="qty-input" min="0" max="{{ $remaining }}" placeholder="0">
                                                <span class="tr-unit-label">{{ $unitAbbr }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="tr-select-wrapper">
                                                <select name="items[{{$i}}][result]" class="status-select tr-select-sm">
                                                    <option value="accepted">Diterima</option>
                                                    <option value="rejected">Ditolak</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="tr-qc-group">
                                                <label class="tr-qc-label">
                                                    <input type="hidden" name="items[{{$i}}][quality_ok]" value="0">
                                                    <input type="checkbox" name="items[{{$i}}][quality_ok]" value="1" checked class="tr-qc-check"> Kualitas
                                                </label>
                                                <label class="tr-qc-label">
                                                    <input type="hidden" name="items[{{$i}}][spec_ok]" value="0">
                                                    <input type="checkbox" name="items[{{$i}}][spec_ok]" value="1" checked class="tr-qc-check"> Spesifikasi
                                                </label>
                                                <label class="tr-qc-label">
                                                    <input type="hidden" name="items[{{$i}}][packaging_ok]" value="0">
                                                    <input type="checkbox" name="items[{{$i}}][packaging_ok]" value="1" checked class="tr-qc-check"> Kemasan
                                                </label>
                                            </div>
                                            <input type="text" name="items[{{$i}}][qc_notes]" class="tr-input tr-input-sm" placeholder="Catatan QC (opsional)">
                                        </td>

                                        <td>
                                            <div class="tr-batch-group">
                                                <input type="text" name="items[{{$i}}][batch_number]" class="tr-input tr-input-sm" placeholder="No. Batch (Ops)">
                                                <input type="date" name="items[{{$i}}][expired_date]" class="tr-input tr-input-sm text-muted">
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                
                                {{-- SEPARATOR BARANG LENGKAP --}}
                                @php $hasCompleted = false; @endphp
                                @foreach($order->items as $item)
                                    @if($item->qty_ordered - $item->qty_received <= 0)
                                        @if(!$hasCompleted)
                                            <tr>
                                                <td colspan="7" class="tr-completed-separator">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                                    Barang yang sudah berstatus LENGKAP
                                                </td>
                                            </tr>
                                            @php $hasCompleted = true; @endphp
                                        @endif
                                        <tr class="tr-row-completed">
                                            <td class="c"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></td>
                                            <td>
                                                <div class="tr-prod-name">{{ $item->product->name }}</div>
                                                <div class="tr-sku-box">{{ $item->product->sku }}</div>
                                            </td>
                                            <td class="c tr-cell-bg tr-cell-border">
                                                <div class="tr-completed-text">LENGKAP</div>
                                                <div class="tr-completed-qty">{{ $item->qty_ordered }} {{ $item->unit->abbreviation ?? $item->product->unit->abbreviation ?? '' }}</div>
                                            </td>
                                            <td colspan="4" class="c tr-muted-italic">Tidak perlu dihitung lagi</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- FLOATING ACTION BAR --}}
                <div class="tr-floating-action-bar">
                    <div class="tr-validation-alert">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <div>
                            <strong>Validasi Penerimaan</strong>
                            <p>Pastikan Anda mencentang kotak <span>[ CEK ]</span> pada barang yang fisiknya telah diinput.</p>
                        </div>
                    </div>
                    <div class="tr-action-buttons">
                        <a href="{{ route('gudang.terimapo.index') }}" class="tr-btn tr-btn-light">Batal</a>
                        <button type="submit" id="btn-submit-terima" class="tr-btn tr-btn-success tr-btn-massive" disabled>
                            Simpan Penerimaan
                        </button>
                    </div>
                </div>
            </form>

            {{-- RIWAYAT PENERIMAAN SEBELUMNYA --}}
            @if(\Illuminate\Support\Facades\Schema::hasTable('purchase_order_receipts') && $order->relationLoaded('receipts') && $order->receipts->count() > 0)
                <div class="tr-card" style="margin-top: 2rem;">
                    <div class="tr-card-header">
                        <h2 class="tr-section-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            Riwayat Penerimaan (QC)
                        </h2>
                        <p class="tr-card-subtitle">Audit penerimaan sebelumnya untuk PO ini (termasuk foto & hasil QC).</p>
                    </div>
                    <div class="tr-history-wrapper">
                        @foreach($order->receipts as $r)
                            @php
                                $rc = $r->status === 'partial' ? 'tr-badge-danger' : 'tr-badge-success';
                                $items = $r->items ?? collect();
                                $rejectedCount = $items->where('result', 'rejected')->count();
                                $partialCount = $items->where('result', 'partial')->count();
                                $acceptedCount = $items->where('result', 'accepted')->count();
                                $followup = $r->needs_followup ? ($r->followup_status ?: 'open') : null;
                            @endphp
                            
                            <details class="tr-history-details">
                                <summary class="tr-history-summary">
                                    <div class="tr-summary-left">
                                        <span class="tr-badge {{ $rc }}">{{ strtoupper($r->status) }}</span>
                                        @if($followup)
                                            <span class="tr-badge {{ $followup === 'resolved' ? 'tr-badge-success' : 'tr-badge-warning' }}">
                                                {{ $followup === 'resolved' ? 'FOLLOW-UP RESOLVED' : 'FOLLOW-UP OPEN' }}
                                            </span>
                                        @endif
                                        <div class="tr-history-date">{{ $r->created_at->format('d/m/Y H:i') }}</div>
                                        <div class="tr-history-user">Oleh: {{ $r->receiver?->name ?? 'Admin Gudang' }}</div>
                                    </div>
                                    <div class="tr-summary-right">
                                        <span class="tr-qc-stat"><span class="icon">✅</span> {{ $acceptedCount }}</span>
                                        <span class="tr-qc-stat"><span class="icon">⚠️</span> {{ $partialCount }}</span>
                                        <span class="tr-qc-stat"><span class="icon">❌</span> {{ $rejectedCount }}</span>
                                    </div>
                                </summary>
                                
                                <div class="tr-history-content">
                                    @if($r->notes)
                                        <div class="tr-history-notes">"{{ $r->notes }}"</div>
                                    @endif
                                    
                                    @if(is_array($r->photos) && count($r->photos) > 0)
                                        <div class="tr-photo-grid">
                                            @foreach($r->photos as $p)
                                                <a href="{{ asset('storage/'.$p) }}" target="_blank" class="tr-photo-item">
                                                    <img src="{{ asset('storage/'.$p) }}" alt="Bukti Foto">
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <div class="table-responsive" style="margin-top: 1rem;">
                                        <table class="tr-table-simple">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th class="c">Sisa Sblm.</th>
                                                    <th class="c">Diterima</th>
                                                    <th class="c">Hasil</th>
                                                    <th>Catatan QC</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(($r->items ?? []) as $it)
                                                    @php
                                                        $badge = match($it->result) {
                                                            'rejected' => ['tr-badge-danger', 'REJECTED'],
                                                            'partial' => ['tr-badge-warning', 'PARTIAL'],
                                                            default => ['tr-badge-success', 'ACCEPTED'],
                                                        };
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="tr-prod-name">{{ $it->product?->name ?? '-' }}</div>
                                                            <div class="tr-sku-box">{{ $it->product?->sku ?? '-' }}</div>
                                                        </td>
                                                        <td class="c tr-font-bold">{{ (int) $it->qty_remaining_before }}</td>
                                                        <td class="c tr-font-bold">{{ (int) $it->qty_received_po_unit }}</td>
                                                        <td class="c"><span class="tr-badge {{ $badge[0] }}">{{ $badge[1] }}</span></td>
                                                        <td class="tr-text-muted">{{ $it->notes ?: '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    @if(strtolower(auth()->user()->role) === 'supervisor' && $followup === 'open')
                                        <div class="tr-history-actions">
                                            @if($r->purchase_return_id)
                                                <a href="{{ route('pembelian.retur.show', $r->purchase_return_id) }}" class="tr-btn-sm tr-btn-outline">Lihat Draft Retur</a>
                                            @endif
                                            @if($r->reorder_purchase_order_id)
                                                <a href="{{ route('pembelian.order.edit', $r->reorder_purchase_order_id) }}" class="tr-btn-sm tr-btn-outline">Lihat Draft Reorder</a>
                                            @endif
                                            <a href="{{ route('pembelian.receipts_followup.show', $r) }}" class="tr-btn-sm tr-btn-info">Tindak Lanjut</a>
                                        </div>
                                    @endif
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.item-row');
            const submitBtn = document.getElementById('btn-submit-terima');
            const form = document.getElementById('terima-form');
            
            function validateForm() {
                let hasValidEntry = false;
                let hasUncheckedEntry = false;

                rows.forEach(row => {
                    const checkbox = row.querySelector('.validation-check');
                    const qtyInput = row.querySelector('.qty-input');
                    const qtyWrapper = row.querySelector('.qty-wrapper');
                    const statusSelect = row.querySelector('.status-select');
                    const status = statusSelect ? statusSelect.value : 'accepted';
                    const qtyVal = parseInt(qtyInput.value) || 0;

                    // Visual Feedback pada Baris
                    if (checkbox.checked) {
                        row.classList.add('is-checked');
                    } else {
                        row.classList.remove('is-checked');
                    }

                    // Visual Feedback pada Input Box
                    if (status === 'rejected') {
                        qtyWrapper.classList.add('is-rejected');
                        qtyWrapper.classList.remove('is-filled');
                    } else if (qtyVal > 0) {
                        qtyWrapper.classList.add('is-filled');
                        qtyWrapper.classList.remove('is-rejected');
                    } else {
                        qtyWrapper.classList.remove('is-filled', 'is-rejected');
                    }

                    // Logika Validasi Utama
                    if (status === 'rejected') {
                        if (checkbox.checked) hasValidEntry = true;
                        else hasUncheckedEntry = true;
                    } else if (qtyVal > 0) {
                        if (checkbox.checked) hasValidEntry = true;
                        else hasUncheckedEntry = true;
                    } else if (checkbox.checked) {
                        hasUncheckedEntry = true; // Tercentang tapi qty 0 dan tidak direject
                    }
                });

                if (hasValidEntry && !hasUncheckedEntry) {
                    submitBtn.disabled = false;
                    submitBtn.classList.add('is-active');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.remove('is-active');
                }
            }

            rows.forEach(row => {
                const checkbox = row.querySelector('.validation-check');
                const qtyInput = row.querySelector('.qty-input');
                const statusSelect = row.querySelector('.status-select');

                // Klik area baris (td) untuk checklist otomatis
                row.addEventListener('click', function(e) {
                    const t = (e.target && e.target.tagName) ? e.target.tagName.toUpperCase() : '';
                    // Abaikan jika user mengklik elemen input langsung
                    if(!['INPUT', 'SELECT', 'TEXTAREA', 'BUTTON', 'A', 'LABEL', 'SPAN'].includes(t)) {
                        checkbox.checked = !checkbox.checked;
                        validateForm();
                        if(checkbox.checked && !qtyInput.value && statusSelect.value !== 'rejected') {
                            qtyInput.focus();
                        }
                    }
                });

                checkbox.addEventListener('change', validateForm);
                
                qtyInput.addEventListener('input', function() {
                    if (parseInt(this.value) > 0 && !checkbox.checked) {
                        checkbox.checked = true;
                    } else if ((parseInt(this.value) <= 0 || !this.value) && statusSelect.value !== 'rejected') {
                        checkbox.checked = false;
                    }
                    validateForm();
                });

                if (statusSelect) {
                    statusSelect.addEventListener('change', function () {
                        if (this.value === 'rejected') {
                            qtyInput.value = '0';
                            qtyInput.disabled = true;
                            checkbox.checked = true;
                        } else {
                            qtyInput.disabled = false;
                            if (parseInt(qtyInput.value || 0) <= 0) {
                                checkbox.checked = false;
                            }
                        }
                        validateForm();
                    });
                }
            });

            form.addEventListener('submit', function(e) {
                if (submitBtn.disabled) {
                    e.preventDefault();
                    return false;
                }
                
                // Cleanup: Hapus nilai QTY pada baris yang tidak dicentang agar tidak dikirim ke server
                rows.forEach(row => {
                    const checkbox = row.querySelector('.validation-check');
                    const qtyInput = row.querySelector('.qty-input');
                    const statusSelect = row.querySelector('.status-select');
                    const status = statusSelect ? statusSelect.value : 'accepted';
                    const qtyVal = parseInt(qtyInput.value || 0);
                    
                    const shouldKeep = checkbox.checked && (status === 'rejected' || qtyVal > 0);
                    if (!shouldKeep) {
                        qtyInput.value = ''; 
                    }
                });

                submitBtn.innerHTML = 'Menyimpan Data...';
                submitBtn.classList.add('is-processing');
                submitBtn.disabled = true;
            });
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
            
            --tr-primary: #4f46e5;
            --tr-primary-hover: #4338ca;
            --tr-primary-light: #e0e7ff;
            
            --tr-success: #10b981;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            
            --tr-danger: #ef4444;
            --tr-danger-bg: #fee2e2;
            --tr-danger-text: #991b1b;
            
            --tr-warning: #f59e0b;
            --tr-warning-bg: #fffbeb;
            --tr-warning-text: #b45309;

            --tr-info: #0ea5e9;
            
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --tr-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --tr-shadow-floating: 0 -4px 12px rgba(0,0,0,0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 2rem; }
        .tr-page { padding: 2rem 1.5rem; max-width: 1200px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: flex-start; gap: 12px; padding: 1.25rem 1.5rem; border-radius: var(--tr-radius-md); margin-bottom: 1.5rem; font-size: 0.9rem; border: 1px solid transparent; line-height: 1.5; }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: #fecaca; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: #bbf7d0; }
        .tr-alert-icon { flex-shrink: 0; margin-top: 2px; }
        .tr-alert-list { margin: 0.5rem 0 0 0; padding-left: 1.5rem; }

        /* ── DOC HEADER ── */
        .tr-doc-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 2px solid var(--tr-border); padding-bottom: 1.5rem; flex-wrap: wrap; gap: 1.5rem; }
        .tr-back-link { display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 600; color: var(--tr-text-muted); text-decoration: none; margin-bottom: 0.75rem; transition: color 0.2s; }
        .tr-back-link:hover { color: var(--tr-text-main); }
        
        .tr-title { font-size: 1.6rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-indigo { background: var(--tr-primary-light); color: var(--tr-primary); }
        .tr-doc-meta { font-size: 0.9rem; color: var(--tr-text-muted); margin-top: 0.5rem; }
        
        .tr-supplier-card { text-align: right; background: #ffffff; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); }
        .tr-supplier-label { font-size: 0.7rem; font-weight: 700; color: var(--tr-text-light); text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-supplier-name { font-size: 1.1rem; font-weight: 800; color: var(--tr-text-main); margin: 2px 0; }
        .tr-supplier-date { font-size: 0.8rem; color: var(--tr-text-muted); font-weight: 500; }

        /* ── CARD SECTIONS ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); }
        .tr-header-highlight { border-left: 4px solid var(--tr-primary); }
        .tr-header-highlight-green { border-left: 4px solid var(--tr-success); }
        .tr-card-body { padding: 1.5rem; }
        
        .tr-section-title { font-size: 1.05rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; }
        .tr-step-number { width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.8rem; font-weight: 700; color: #fff; background: var(--tr-primary); flex-shrink: 0; }
        .tr-step-number.bg-green { background: var(--tr-success); }
        .tr-req { color: var(--tr-danger); }

        /* ── FORM INPUTS ── */
        .tr-grid-general { display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1.5rem; align-items: center; }
        .tr-col-main { flex: 1; min-width: 250px; }
        .tr-po-notes-box { flex: 1.5; min-width: 300px; background: var(--tr-warning-bg); border: 1px solid var(--tr-warning-border); padding: 1rem; border-radius: var(--tr-radius-md); }
        .tr-notes-label { font-size: 0.7rem; font-weight: 800; color: var(--tr-warning-text); text-transform: uppercase; margin-bottom: 4px; display: block; }
        .tr-notes-content { font-size: 0.85rem; color: #78350f; line-height: 1.4; }

        .tr-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .tr-col-full { grid-column: 1 / -1; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-label { font-size: 0.85rem; font-weight: 700; color: var(--tr-text-main); }
        
        .tr-input, .tr-select, .tr-textarea { width: 100%; padding: 0.6rem 0.85rem; font-family: inherit; font-size: 0.85rem; color: var(--tr-text-main); background: #f8fafc; border: 1px solid var(--tr-border); border-radius: 6px; outline: none; transition: border-color 0.2s; }
        .tr-input:focus, .tr-select:focus, .tr-textarea:focus { border-color: var(--tr-primary); background: #ffffff; }
        .tr-input-sm { padding: 0.4rem 0.6rem; font-size: 0.8rem; }
        .tr-textarea { resize: vertical; min-height: 60px; }
        .tr-file-input { padding: 0.4rem; background: #ffffff; cursor: pointer; }
        
        .tr-select-wrapper { position: relative; max-width: 400px; }
        .tr-select { appearance: none; padding-right: 2rem; cursor: pointer; font-weight: 600; }
        .tr-select-sm { padding: 0.4rem 0.6rem; padding-right: 2rem; font-size: 0.8rem; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }

        /* ── TABLE INPUT (COMPLEX) ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 0.5rem; }
        .tr-input-table { width: 100%; border-collapse: collapse; min-width: 900px; }
        .tr-input-table th { padding: 0.85rem 1rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); background: #ffffff; border-bottom: 1px solid var(--tr-border); text-align: left; white-space: nowrap; }
        .tr-input-table th.c { text-align: center; }
        
        .item-row { transition: background-color 0.2s; border-bottom: 1px solid var(--tr-border-light); cursor: pointer; }
        .item-row:hover { background-color: #f8fafc; }
        .item-row.is-checked { background-color: var(--tr-success-bg); }
        .item-row td { padding: 1rem; vertical-align: middle; }

        /* Specific Table Columns */
        .tr-col-check { width: 50px; }
        .tr-col-sisa { width: 110px; }
        .tr-col-qty { width: 160px; }
        .tr-col-status { width: 140px; }
        .tr-col-qc { width: 220px; }
        .tr-col-batch { width: 180px; }

        .tr-cell-bg { background: #f8fafc; }
        .tr-cell-border { border-left: 1px solid var(--tr-border); border-right: 1px solid var(--tr-border); }
        .item-row.is-checked .tr-cell-bg { background: transparent; } /* Removes grey bg when selected */

        /* Custom Checkbox */
        .tr-custom-checkbox { display: inline-flex; align-items: center; justify-content: center; position: relative; cursor: pointer; }
        .validation-check { width: 20px; height: 20px; accent-color: var(--tr-success); cursor: pointer; }

        /* Product Info */
        .tr-prod-name { font-size: 0.95rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 4px; }
        .tr-prod-meta { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; font-size: 0.75rem; }
        .tr-sku-box { background: var(--tr-border-light); padding: 2px 6px; border-radius: 4px; font-family: monospace; color: var(--tr-text-muted); font-weight: 600; }
        .tr-badge-received { background: var(--tr-success-bg); color: var(--tr-success-text); padding: 2px 6px; border-radius: 4px; font-weight: 700; }

        /* Remaining Val */
        .tr-remaining-val { font-size: 1.25rem; font-weight: 800; color: var(--tr-warning-text); line-height: 1; }
        .tr-unit-text { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-muted); margin-top: 2px; }

        /* Interactive QTY Input */
        .qty-wrapper { display: flex; align-items: center; gap: 0.5rem; background: #ffffff; border: 2px solid var(--tr-border); border-radius: 8px; padding: 0.4rem 0.6rem; transition: all 0.2s; }
        .qty-wrapper:focus-within { border-color: var(--tr-primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        .qty-wrapper.is-filled { border-color: var(--tr-primary); background: var(--tr-primary-light); }
        .qty-wrapper.is-rejected { border-color: var(--tr-danger); background: var(--tr-danger-light); }
        .qty-input { width: 100%; min-width: 50px; text-align: center; font-weight: 800; font-size: 1.1rem; border: none; outline: none; background: transparent; color: var(--tr-text-main); }
        .tr-unit-label { font-weight: 700; color: var(--tr-text-muted); font-size: 0.8rem; }

        /* QC Checklist */
        .tr-qc-group { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 0.5rem; }
        .tr-qc-label { display: flex; align-items: center; gap: 4px; font-size: 0.75rem; font-weight: 600; color: var(--tr-text-muted); cursor: pointer; user-select: none; }
        .tr-qc-check { accent-color: var(--tr-primary); width: 14px; height: 14px; }

        /* Batch Inputs */
        .tr-batch-group { display: flex; flex-direction: column; gap: 0.4rem; }

        /* Completed Rows */
        .tr-completed-separator { padding: 1rem; background: var(--tr-bg); font-size: 0.8rem; font-weight: 800; color: var(--tr-text-muted); text-transform: uppercase; border-top: 2px dashed var(--tr-border); letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px; }
        .tr-row-completed { background: #f8fafc; opacity: 0.7; }
        .tr-completed-text { font-size: 0.85rem; font-weight: 800; color: var(--tr-success); }
        .tr-completed-qty { font-size: 0.75rem; font-weight: 700; color: var(--tr-success); }
        .tr-muted-italic { color: var(--tr-text-light); font-size: 0.85rem; font-style: italic; }

        /* ── FLOATING ACTION BAR ── */
        .tr-floating-action-bar { position: sticky; bottom: 20px; z-index: 50; display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); border: 1px solid var(--tr-border); border-radius: var(--tr-radius-lg); box-shadow: var(--tr-shadow-floating); margin-top: 1rem; flex-wrap: wrap; gap: 1rem; }
        .tr-validation-alert { display: flex; align-items: flex-start; gap: 12px; color: var(--tr-warning-text); max-width: 400px; }
        .tr-validation-alert svg { flex-shrink: 0; }
        .tr-validation-alert strong { display: block; font-size: 0.95rem; font-weight: 800; margin-bottom: 2px; }
        .tr-validation-alert p { font-size: 0.8rem; margin: 0; line-height: 1.4; opacity: 0.9; }
        .tr-validation-alert span { font-weight: 800; font-family: monospace; background: rgba(0,0,0,0.05); padding: 0 4px; border-radius: 4px; }
        
        .tr-action-buttons { display: flex; gap: 1rem; align-items: center; }
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.65rem 1.25rem; border-radius: var(--tr-radius-md); font-size: 0.9rem; font-weight: 700; font-family: inherit; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; }
        .tr-btn-light { background: transparent; color: var(--tr-text-muted); }
        .tr-btn-light:hover { color: var(--tr-text-main); background: var(--tr-border-light); }
        
        .tr-btn-success { background: var(--tr-success); color: #ffffff; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2); }
        .tr-btn-massive { padding: 0.85rem 2rem; font-size: 1rem; }
        
        .tr-btn-success:disabled { opacity: 0.5; cursor: not-allowed; background: var(--tr-text-light); box-shadow: none; }
        .tr-btn-success.is-active:not(:disabled) { background: var(--tr-success); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4); transform: translateY(-2px); }
        .tr-btn-success.is-processing { opacity: 0.8; cursor: wait; transform: none; }

        /* ── HISTORY DETAILS (BOTTOM) ── */
        .tr-history-wrapper { padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; }
        .tr-history-details { border: 1px solid var(--tr-border); border-radius: var(--tr-radius-md); background: #ffffff; overflow: hidden; }
        .tr-history-summary { cursor: pointer; padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; background: var(--tr-bg); user-select: none; list-style: none; }
        .tr-history-summary::-webkit-details-marker { display: none; }
        .tr-history-details[open] .tr-history-summary { border-bottom: 1px solid var(--tr-border-light); }
        
        .tr-summary-left { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .tr-badge { padding: 0.25rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.05em; }
        .tr-badge-success { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-badge-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); }
        .tr-badge-warning { background: var(--tr-warning-bg); color: var(--tr-warning-text); }
        
        .tr-history-date { font-weight: 800; color: var(--tr-text-main); font-size: 0.9rem; }
        .tr-history-user { font-size: 0.85rem; color: var(--tr-text-muted); }
        
        .tr-summary-right { display: flex; gap: 1rem; font-size: 0.85rem; font-weight: 600; color: var(--tr-text-muted); }
        
        .tr-history-content { padding: 1.25rem; }
        .tr-history-notes { font-size: 0.9rem; color: var(--tr-text-main); font-style: italic; background: var(--tr-bg); padding: 0.75rem 1rem; border-radius: 6px; border-left: 3px solid var(--tr-border); }
        
        .tr-photo-grid { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1rem; }
        .tr-photo-item { width: 100px; height: 80px; border-radius: 8px; overflow: hidden; border: 1px solid var(--tr-border); display: block; }
        .tr-photo-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
        .tr-photo-item:hover img { transform: scale(1.05); }

        .tr-table-simple { width: 100%; border-collapse: collapse; min-width: 600px; }
        .tr-table-simple th { text-align: left; padding: 0.6rem 0.8rem; font-size: 0.75rem; color: var(--tr-text-muted); background: var(--tr-bg); text-transform: uppercase; }
        .tr-table-simple td { padding: 0.8rem; font-size: 0.85rem; border-bottom: 1px dashed var(--tr-border-light); }
        .tr-table-simple th.c, .tr-table-simple td.c { text-align: center; }

        .tr-history-actions { display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--tr-border-light); }
        .tr-btn-sm { font-size: 0.8rem; padding: 0.4rem 0.85rem; border-radius: 6px; font-weight: 700; text-decoration: none; transition: background 0.2s; }
        .tr-btn-info { background: var(--tr-info); color: white; }
        .tr-btn-info:hover { background: #0284c7; }

        /* ── RESPONSIVE ── */
        @media (max-width: 992px) {
            .tr-doc-header { flex-direction: column; align-items: flex-start; }
            .tr-supplier-card { width: 100%; text-align: left; }
            .tr-grid-2 { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .tr-floating-action-bar { flex-direction: column; align-items: stretch; text-align: center; padding: 1rem; }
            .tr-validation-alert { max-width: 100%; justify-content: center; text-align: left; margin-bottom: 1rem; }
            .tr-action-buttons { flex-direction: column-reverse; width: 100%; }
            .tr-action-buttons .tr-btn { width: 100%; }
        }
    </style>
    @endpush
</x-app-layout>