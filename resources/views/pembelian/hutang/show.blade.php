<x-app-layout>
    <x-slot name="header">Detail Hutang #{{ $hutang->invoice_number }}</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .sd-page { max-width:68rem; margin:0 auto; padding:0 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .sd-back { display:inline-flex; align-items:center; gap:0.5rem; font-size:0.8125rem; font-weight:600; color:#64748b; text-decoration:none; padding:0.5rem 0.75rem; border-radius:10px; transition:all 0.2s; margin-bottom:1.25rem; }
        .sd-back:hover { background:#f1f5f9; color:#334155; }

        .sd-grid { display:grid; grid-template-columns:1fr 360px; gap:1.5rem; align-items:start; }

        /* Card */
        .sd-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .sd-card-hdr { padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; }
        .sd-card-hdr-ico { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.125rem; flex-shrink:0; }
        .sd-card-hdr-title { font-size:0.9375rem; font-weight:700; color:#1e293b; }
        .sd-card-hdr-sub { font-size:0.75rem; color:#64748b; }
        .sd-card-body { padding:1.5rem; }

        /* Badges */
        .sd-badge { display:inline-flex; align-items:center; gap:0.3rem; padding:0.3rem 0.7rem; border-radius:999px; font-size:0.72rem; font-weight:700; }
        .sd-badge.unpaid { background:#fef2f2; color:#991b1b; }
        .sd-badge.partial { background:#fef3c7; color:#92400e; }
        .sd-badge.paid { background:#d1fae5; color:#065f46; }
        .sd-badge.overdue { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

        /* Info Grid */
        .sd-info { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; }
        .sd-info-lbl { font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; margin-bottom:0.25rem; }
        .sd-info-val { font-size:0.875rem; font-weight:700; color:#1e293b; }
        .sd-info-val.danger { color:#dc2626; }

        /* Progress */
        .sd-progress-wrap { margin-top:1.5rem; }
        .sd-progress-labels { display:flex; justify-content:space-between; font-size:0.75rem; color:#64748b; margin-bottom:0.5rem; }
        .sd-progress-bar { background:#f1f5f9; border-radius:999px; height:12px; overflow:hidden; }
        .sd-progress-fill { height:100%; border-radius:999px; transition:width 0.6s ease; }
        .sd-progress-amounts { display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; margin-top:1rem; }
        .sd-progress-amt { padding:1rem; border-radius:12px; text-align:center; }
        .sd-progress-amt-lbl { font-size:0.6875rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; }
        .sd-progress-amt-val { font-size:1.125rem; font-weight:800; margin-top:0.25rem; letter-spacing:-0.02em; }

        /* Overdue Alert */
        .sd-overdue-alert { display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; background:#fef2f2; border:1px solid #fecaca; border-radius:12px; margin-top:1rem; }
        .sd-overdue-alert-ico { font-size:1.25rem; flex-shrink:0; }
        .sd-overdue-alert-text { font-size:0.8125rem; color:#991b1b; font-weight:600; }
        .sd-overdue-alert-sub { font-size:0.72rem; color:#b91c1c; margin-top:2px; }

        /* Table */
        .sd-table-wrap { overflow-x:auto; }
        .sd-table { width:100%; border-collapse:collapse; font-size:0.8125rem; }
        .sd-table th { text-align:left; padding:0.75rem 1rem; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; border-bottom:2px solid #f1f5f9; }
        .sd-table td { padding:0.875rem 1rem; border-bottom:1px solid #f8fafc; color:#334155; }
        .sd-table tr:last-child td { border-bottom:none; }
        .sd-table .amount { font-weight:700; color:#059669; }

        /* Payment Form */
        .sd-form-group { margin-bottom:1rem; }
        .sd-form-label { display:block; font-size:0.8125rem; font-weight:600; color:#334155; margin-bottom:0.375rem; }
        .sd-form-input { width:100%; padding:0.625rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.8125rem; font-family:inherit; transition:all 0.2s; box-sizing:border-box; }
        .sd-form-input:focus { outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
        .sd-form-input.error { border-color:#ef4444; box-shadow:0 0 0 3px rgba(239,68,68,0.1); }
        select.sd-form-input { cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.75rem center; padding-right:2.25rem; }

        .sd-max-hint { display:flex; align-items:center; justify-content:space-between; margin-top:0.375rem; font-size:0.72rem; }
        .sd-max-hint-lbl { color:#94a3b8; }
        .sd-max-hint-val { color:#2563eb; font-weight:700; cursor:pointer; }
        .sd-max-hint-val:hover { text-decoration:underline; }
        .sd-error-text { color:#dc2626; font-size:0.72rem; font-weight:600; margin-top:0.375rem; display:none; }

        /* Quick Fill Buttons */
        .sd-quick-btns { display:grid; grid-template-columns:repeat(3,1fr); gap:0.5rem; margin-bottom:1rem; }
        .sd-quick-btn { padding:0.5rem; border:1.5px solid #e2e8f0; border-radius:8px; background:#fff; font-size:0.72rem; font-weight:700; color:#475569; cursor:pointer; transition:all 0.2s; font-family:inherit; text-align:center; }
        .sd-quick-btn:hover { border-color:#3b82f6; color:#2563eb; background:#eff6ff; }

        .sd-btn { display:flex; align-items:center; justify-content:center; gap:0.5rem; width:100%; padding:0.75rem 1rem; border:none; border-radius:12px; font-size:0.8125rem; font-weight:700; cursor:pointer; transition:all 0.2s; font-family:inherit; }
        .sd-btn-primary { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 4px 16px rgba(37,99,235,0.3); }
        .sd-btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(37,99,235,0.4); }
        .sd-btn-primary:disabled { opacity:0.5; cursor:not-allowed; transform:none; }

        .sd-lunas-card { text-align:center; padding:2.5rem 1.5rem; }
        .sd-lunas-ico { font-size:3rem; margin-bottom:0.75rem; }
        .sd-lunas-title { font-size:1.125rem; font-weight:800; color:#065f46; }
        .sd-lunas-sub { font-size:0.8125rem; color:#64748b; margin-top:0.25rem; }

        .sd-empty { text-align:center; padding:2.5rem 1rem; color:#94a3b8; font-size:0.8125rem; }

        @media(max-width:768px) {
            .sd-grid { grid-template-columns:1fr; }
            .sd-info { grid-template-columns:repeat(2,1fr); }
            .sd-progress-amounts { grid-template-columns:1fr; }
        }
    </style>
    @endpush

    <div class="sd-page" style="padding-top:1.5rem;">
        <a href="{{ route('pembelian.hutang.index') }}" class="sd-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 8H1M8 15l-7-7 7-7"/></svg>
            Kembali ke Daftar Hutang
        </a>

        <div class="sd-grid">
            {{-- LEFT COLUMN --}}
            <div>
                {{-- Header Card --}}
                <div class="sd-card">
                    <div class="sd-card-hdr" style="background:linear-gradient(135deg,#fef2f2,#fff1f2);">
                        <div class="sd-card-hdr-ico" style="background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff;">💳</div>
                        <div style="flex:1;">
                            <div class="sd-card-hdr-title">{{ $hutang->invoice_number }}</div>
                            <div class="sd-card-hdr-sub">{{ $hutang->supplier->name ?? '-' }}</div>
                        </div>
                        @if($hutang->status === 'paid')
                            <span class="sd-badge paid">✅ Lunas</span>
                        @elseif($hutang->status === 'partial')
                            <span class="sd-badge partial">🔄 Sebagian</span>
                        @else
                            <span class="sd-badge unpaid">🕐 Belum Bayar</span>
                        @endif
                        @if($hutang->isOverdue())
                            <span class="sd-badge overdue">⚠️ Jatuh Tempo</span>
                        @endif
                    </div>
                    <div class="sd-card-body">
                        <div class="sd-info">
                            <div>
                                <div class="sd-info-lbl">Tgl. Transaksi</div>
                                <div class="sd-info-val">{{ $hutang->transaction_date->format('d M Y') }}</div>
                            </div>
                            <div>
                                <div class="sd-info-lbl">Jatuh Tempo</div>
                                <div class="sd-info-val {{ $hutang->isOverdue() ? 'danger' : '' }}">
                                    {{ $hutang->due_date ? $hutang->due_date->format('d M Y') : '-' }}
                                </div>
                            </div>
                            <div>
                                <div class="sd-info-lbl">Referensi PO</div>
                                <div class="sd-info-val">{{ $hutang->purchaseOrder->po_number ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="sd-info-lbl">Dibuat</div>
                                <div class="sd-info-val">{{ $hutang->created_at->format('d M Y') }}</div>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        @php
                            $pct = (float) $hutang->total_amount > 0 ? min(100, ((float) $hutang->paid_amount / (float) $hutang->total_amount) * 100) : 0;
                            $barColor = $pct >= 100 ? '#10b981' : ($pct > 0 ? '#f59e0b' : '#ef4444');
                        @endphp
                        <div class="sd-progress-wrap">
                            <div class="sd-progress-labels">
                                <span>Progress Pembayaran</span>
                                <span style="font-weight:700;">{{ number_format($pct, 1) }}%</span>
                            </div>
                            <div class="sd-progress-bar">
                                <div class="sd-progress-fill" id="progressFill" style="width:0%; background:{{ $barColor }};" data-pct="{{ (int) $pct }}"></div>
                            </div>
                            <div class="sd-progress-amounts">
                                <div class="sd-progress-amt" style="background:#f8fafc;">
                                    <div class="sd-progress-amt-lbl">Total</div>
                                    <div class="sd-progress-amt-val" style="color:#1e293b;">Rp {{ number_format((float) $hutang->total_amount, 0, ',', '.') }}</div>
                                </div>
                                <div class="sd-progress-amt" style="background:#f0fdf4;">
                                    <div class="sd-progress-amt-lbl">Terbayar</div>
                                    <div class="sd-progress-amt-val" style="color:#059669;">Rp {{ number_format((float) $hutang->paid_amount, 0, ',', '.') }}</div>
                                </div>
                                <div class="sd-progress-amt" style="background:#fef2f2;">
                                    <div class="sd-progress-amt-lbl">Sisa</div>
                                    <div class="sd-progress-amt-val" style="color:#dc2626;" id="remainingDisplay">Rp {{ number_format((int) $hutang->remaining_amount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>

                        @if($hutang->isOverdue())
                            @php
                                $daysOverdue = $hutang->due_date->diffInDays(now());
                            @endphp
                            <div class="sd-overdue-alert">
                                <div class="sd-overdue-alert-ico">⚠️</div>
                                <div>
                                    <div class="sd-overdue-alert-text">Sudah jatuh tempo {{ $daysOverdue }} hari</div>
                                    <div class="sd-overdue-alert-sub">Segera lakukan pembayaran untuk menghindari masalah dengan supplier.</div>
                                </div>
                            </div>
                        @endif

                        @if($hutang->notes)
                            <div style="font-size:0.8125rem; color:#64748b; padding:0.75rem 1rem; background:#f8fafc; border-radius:10px; margin-top:1rem;">📝 {{ $hutang->notes }}</div>
                        @endif
                    </div>
                </div>

                {{-- Payment History --}}
                <div class="sd-card">
                    <div class="sd-card-hdr">
                        <div class="sd-card-hdr-ico" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#059669;">💳</div>
                        <div class="sd-card-hdr-title">Riwayat Pembayaran</div>
                    </div>
                    @if($hutang->payments->isEmpty())
                        <div class="sd-empty">Belum ada pembayaran yang tercatat.</div>
                    @else
                        <div class="sd-table-wrap">
                            <table class="sd-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>No. Referensi</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hutang->payments as $p)
                                    <tr>
                                        <td>{{ $p->payment_date->format('d M Y') }}</td>
                                        <td class="amount">Rp {{ number_format((float) $p->amount, 0, ',', '.') }}</td>
                                        <td>{{ $p->payment_method_label }}</td>
                                        <td style="color:#64748b; font-size:0.75rem;">{{ $p->reference_number ?: '-' }}</td>
                                        <td style="color:#64748b; font-size:0.75rem;">{{ $p->notes ?: '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- RIGHT COLUMN: Payment Form --}}
            <div id="bayar">
                @if($hutang->status !== 'paid' && (int) $hutang->remaining_amount > 0)
                    @can('edit_hutang_supplier')
                    <div class="sd-card">
                        <div class="sd-card-hdr" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                            <div class="sd-card-hdr-ico" style="background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;">💵</div>
                            <div class="sd-card-hdr-title">Catat Pembayaran Hutang</div>
                        </div>
                        <div class="sd-card-body">
                            <form action="{{ route('pembelian.hutang.pay', $hutang) }}" method="POST" id="paymentForm">
                                @csrf
                                <div class="sd-form-group">
                                    <label class="sd-form-label">Tanggal Bayar</label>
                                    <input type="date" name="payment_date" class="sd-form-input" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="sd-form-group">
                                    <label class="sd-form-label">Jumlah (Rp)</label>
                                    <input type="number" name="amount" id="amountInput" class="sd-form-input"
                                           min="1"
                                           data-max="{{ (int) $hutang->remaining_amount }}"
                                           value="{{ old('amount') }}"
                                           placeholder="Maks: Rp {{ number_format((int) $hutang->remaining_amount, 0, ',', '.') }}"
                                           required>
                                    <div class="sd-max-hint">
                                        <span class="sd-max-hint-lbl">Maksimal:</span>
                                        <span class="sd-max-hint-val" id="maxBtn" title="Klik untuk mengisi penuh">
                                            Rp {{ number_format((int) $hutang->remaining_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="sd-error-text" id="amountError">
                                        Jumlah tidak boleh melebihi sisa hutang
                                    </div>
                                </div>

                                {{-- Quick Fill Buttons --}}
                                <div class="sd-quick-btns">
                                    <button type="button" class="sd-quick-btn" data-pct="25">25%</button>
                                    <button type="button" class="sd-quick-btn" data-pct="50">50%</button>
                                    <button type="button" class="sd-quick-btn" data-pct="100">100% (Lunas)</button>
                                </div>

                                <div class="sd-form-group">
                                    <label class="sd-form-label">Metode Pembayaran</label>
                                    <select name="payment_method" class="sd-form-input" required>
                                        <option value="cash">💵 Tunai</option>
                                        <option value="transfer">🏦 Transfer Bank</option>
                                        <option value="check">📄 Cek / Giro</option>
                                        <option value="other">Lainnya</option>
                                    </select>
                                </div>
                                <div class="sd-form-group">
                                    <label class="sd-form-label">No. Referensi</label>
                                    <input type="text" name="reference_number" class="sd-form-input" placeholder="No. transfer / cek...">
                                </div>
                                <div class="sd-form-group">
                                    <label class="sd-form-label">Catatan</label>
                                    <textarea name="notes" class="sd-form-input" rows="2" placeholder="Opsional..."></textarea>
                                </div>
                                <button type="submit" class="sd-btn sd-btn-primary" id="submitBtn">💾 Simpan Pembayaran</button>
                            </form>
                        </div>
                    </div>
                    @endcan
                @else
                    <div class="sd-card">
                        <div class="sd-card-body sd-lunas-card">
                            <div class="sd-lunas-ico">✅</div>
                            <div class="sd-lunas-title">Hutang Lunas!</div>
                            <div class="sd-lunas-sub">Semua pembayaran telah selesai.</div>
                        </div>
                    </div>
                @endif

                <a href="{{ route('pembelian.hutang.index') }}" class="sd-btn" style="background:#f1f5f9; color:#475569; margin-top:0.75rem;">← Kembali</a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            // Progress bar animation
            const progressEl = document.getElementById('progressFill');
            if (progressEl) {
                const pct = Math.max(0, Math.min(100, parseInt(progressEl.dataset.pct || '0', 10)));
                requestAnimationFrame(() => { progressEl.style.width = pct + '%'; });
            }

            // Payment form validation
            const amountInput = document.getElementById('amountInput');
            const maxBtn = document.getElementById('maxBtn');
            const amountError = document.getElementById('amountError');
            const submitBtn = document.getElementById('submitBtn');
            const maxAmount = parseInt(amountInput?.dataset?.max || '0', 10);

            if (amountInput && maxAmount > 0) {
                // Disable native validation popup
                amountInput.setAttribute('novalidate', '');

                amountInput.addEventListener('input', function() {
                    const val = parseInt(this.value, 10);
                    if (val > maxAmount) {
                        this.classList.add('error');
                        if (amountError) amountError.style.display = 'block';
                    } else {
                        this.classList.remove('error');
                        if (amountError) amountError.style.display = 'none';
                    }
                });

                // Also disable native validation on the form
                const form = document.getElementById('paymentForm');
                if (form) form.setAttribute('novalidate', 'true');

                // Custom form submit validation
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const val = parseInt(amountInput.value, 10);
                        if (val > maxAmount || val < 1 || isNaN(val)) {
                            e.preventDefault();
                            amountInput.classList.add('error');
                            if (amountError) {
                                amountError.textContent = val > maxAmount
                                    ? 'Jumlah tidak boleh melebihi sisa hutang (Rp ' + maxAmount.toLocaleString('id-ID') + ')'
                                    : 'Jumlah harus lebih dari 0';
                                amountError.style.display = 'block';
                            }
                            amountInput.focus();
                            return false;
                        }
                    });
                }
            }

            // Max button click
            if (maxBtn && amountInput) {
                maxBtn.addEventListener('click', function() {
                    amountInput.value = maxAmount;
                    amountInput.classList.remove('error');
                    if (amountError) amountError.style.display = 'none';
                    amountInput.dispatchEvent(new Event('input'));
                });
            }

            // Quick fill buttons
            document.querySelectorAll('.sd-quick-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const pct = parseInt(this.dataset.pct, 10);
                    const val = Math.floor(maxAmount * pct / 100);
                    if (amountInput) {
                        amountInput.value = val;
                        amountInput.classList.remove('error');
                        if (amountError) amountError.style.display = 'none';
                        amountInput.dispatchEvent(new Event('input'));
                    }
                });
            });
        })();
    </script>
    @endpush
</x-app-layout>
