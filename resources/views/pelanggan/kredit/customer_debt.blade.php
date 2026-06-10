<x-app-layout>
    <x-slot name="header">Detail Hutang - {{ $customer->name }}</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .cd-page { max-width:72rem; margin:0 auto; padding:1.5rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .cd-back { display:inline-flex; align-items:center; gap:0.5rem; font-size:0.8125rem; font-weight:600; color:#64748b; text-decoration:none; padding:0.5rem 0.75rem; border-radius:10px; transition:all 0.2s; margin-bottom:1.25rem; }
        .cd-back:hover { background:#f1f5f9; color:#334155; }

        .cd-header { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.5rem; display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
        .cd-header-left { display:flex; gap:1rem; align-items:center; }
        .cd-avatar { width:56px; height:56px; border-radius:16px; background:linear-gradient(135deg,#fee2e2,#fecaca); display:flex; align-items:center; justify-content:center; font-size:1.75rem; flex-shrink:0; }
        .cd-name { font-size:1.375rem; font-weight:800; color:#1e293b; }
        .cd-meta { font-size:0.8rem; color:#64748b; margin-top:0.25rem; }
        .cd-cat { display:inline-block; margin-top:0.35rem; background:#e0e7ff; color:#4338ca; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:700; }

        .cd-grid { display:grid; grid-template-columns:1fr 360px; gap:1.5rem; align-items:start; }

        .cd-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); margin-bottom:1rem; }
        .cd-card-hdr { padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:0.5rem; }
        .cd-card-title { font-size:0.9375rem; font-weight:700; color:#1e293b; }

        .cd-item { padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; }
        .cd-item:last-child { border-bottom:none; }
        .cd-item-top { display:flex; justify-content:space-between; align-items:start; margin-bottom:0.5rem; }
        .cd-item-num { font-weight:700; color:#1e293b; font-size:0.875rem; }
        .cd-item-date { font-size:0.72rem; color:#64748b; margin-top:0.15rem; }
        .cd-item-desc { font-size:0.8rem; color:#64748b; margin-bottom:0.75rem; }

        .cd-badge { display:inline-flex; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.6875rem; font-weight:700; white-space:nowrap; }
        .cd-badge.unpaid { background:#fef2f2; color:#991b1b; }
        .cd-badge.partial { background:#fef3c7; color:#92400e; }
        .cd-badge.paid { background:#d1fae5; color:#065f46; }

        .cd-amounts { display:grid; grid-template-columns:repeat(3,1fr); gap:0.5rem; margin-bottom:0.5rem; }
        .cd-amt { padding:0.5rem; border-radius:8px; text-align:center; }
        .cd-amt-lbl { font-size:0.6rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; }
        .cd-amt-val { font-size:0.85rem; font-weight:700; margin-top:0.15rem; }

        .cd-progress { background:#f1f5f9; border-radius:999px; height:6px; overflow:hidden; margin-bottom:0.25rem; }
        .cd-progress-fill { height:100%; border-radius:999px; transition:width 0.3s; }
        .cd-progress-lbl { font-size:0.65rem; color:#64748b; text-align:right; }

        /* Payment Form */
        .cd-form-card { position:sticky; top:1rem; }
        .cd-form-hdr { padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .cd-form-title { font-size:1rem; font-weight:800; color:#1e40af; }
        .cd-form-sub { font-size:0.8rem; color:#3b82f6; margin-top:0.15rem; }
        .cd-form-body { padding:1.25rem; }

        .cd-fg { margin-bottom:1rem; }
        .cd-fl { display:block; font-size:0.8125rem; font-weight:600; color:#334155; margin-bottom:0.375rem; }
        .cd-fi { width:100%; padding:0.625rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.8125rem; font-family:inherit; transition:all 0.2s; box-sizing:border-box; }
        .cd-fi:focus { outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
        .cd-fi.error { border-color:#ef4444; box-shadow:0 0 0 3px rgba(239,68,68,0.1); }
        .cd-fi.big { font-size:1.1rem; font-weight:700; }
        select.cd-fi { cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.75rem center; padding-right:2.25rem; }

        .cd-max-row { display:flex; justify-content:space-between; align-items:center; margin-top:0.375rem; font-size:0.72rem; }
        .cd-max-lbl { color:#94a3b8; }
        .cd-max-val { color:#2563eb; font-weight:700; cursor:pointer; }
        .cd-max-val:hover { text-decoration:underline; }
        .cd-error-text { color:#dc2626; font-size:0.72rem; font-weight:600; margin-top:0.375rem; display:none; }

        .cd-quick-btns { display:grid; grid-template-columns:repeat(3,1fr); gap:0.5rem; margin-bottom:1rem; }
        .cd-quick-btn { padding:0.5rem; border:1.5px solid #e2e8f0; border-radius:8px; background:#fff; font-size:0.72rem; font-weight:700; color:#475569; cursor:pointer; transition:all 0.2s; font-family:inherit; text-align:center; }
        .cd-quick-btn:hover { border-color:#3b82f6; color:#2563eb; background:#eff6ff; }

        .cd-submit { display:flex; align-items:center; justify-content:center; gap:0.5rem; width:100%; padding:0.875rem; border:none; border-radius:12px; font-size:0.9375rem; font-weight:700; cursor:pointer; transition:all 0.2s; font-family:inherit; background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 4px 16px rgba(37,99,235,0.3); }
        .cd-submit:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(37,99,235,0.4); }

        .cd-info-box { margin-top:1rem; padding:0.75rem; background:#f0fdf4; border-radius:10px; border-left:3px solid #22c55e; }
        .cd-info-title { font-size:0.72rem; color:#15803d; font-weight:700; margin-bottom:0.25rem; }
        .cd-info-text { font-size:0.72rem; color:#166534; }

        .cd-alert { padding:0.75rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:600; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; }
        .cd-alert-success { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
        .cd-alert-danger { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

        .cd-empty-form { text-align:center; padding:2.5rem 1rem; }
        .cd-empty-ico { font-size:3rem; margin-bottom:0.75rem; }
        .cd-empty-title { font-size:1rem; font-weight:700; color:#065f46; }
        .cd-empty-sub { font-size:0.8125rem; color:#64748b; margin-top:0.25rem; }

        @media(max-width:768px) {
            .cd-grid { grid-template-columns:1fr; }
            .cd-amounts { grid-template-columns:1fr; }
        }
    </style>
    @endpush

    <div class="cd-page" style="padding-top:1.5rem;">
        <a href="{{ route('pelanggan.kredit.consolidated') }}" class="cd-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 8H1M8 15l-7-7 7-7"/></svg>
            Kembali ke Konsolidasi
        </a>

        @if(session('success'))
            <div class="cd-alert cd-alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="cd-alert cd-alert-danger">❌ {{ session('error') }}</div>
        @endif

        {{-- Header --}}
        <div class="cd-header">
            <div class="cd-header-left">
                <div class="cd-avatar">👤</div>
                <div>
                    <div class="cd-name">{{ $customer->name }}</div>
                    <div class="cd-meta">
                        📞 {{ $customer->phone ?? 'Tidak ada telepon' }}
                        @if($customer->address) | 📍 {{ $customer->address }} @endif
                    </div>
                    @if($customer->category)
                        <span class="cd-cat">{{ ucfirst($customer->category) }}</span>
                    @endif
                </div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:0.8rem; color:#64748b;">Total Hutang</div>
                <div style="font-size:2rem; font-weight:800; color:#dc2626;">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem; color:#94a3b8;">{{ $totalTransactions }} transaksi belum lunas</div>
            </div>
        </div>

        <div class="cd-grid">
            {{-- LEFT: Debt List --}}
            <div>
                <div class="cd-card">
                    <div class="cd-card-hdr">
                        <span style="font-size:1.125rem;">📋</span>
                        <div class="cd-card-title">Rincian Hutang per Transaksi</div>
                    </div>
                    @forelse($debts as $debt)
                        @php
                            $pct = (float) $debt->amount > 0 ? min(100, ((float) $debt->paid_amount / (float) $debt->amount) * 100) : 0;
                            $barColor = $pct >= 100 ? '#10b981' : ($pct > 0 ? '#f59e0b' : '#ef4444');
                        @endphp
                        <div class="cd-item">
                            <div class="cd-item-top">
                                <div>
                                    <div class="cd-item-num">{{ $debt->credit_number }}</div>
                                    <div class="cd-item-date">
                                        {{ $debt->transaction_date->format('d M Y') }}
                                        @if($debt->due_date) | Jatuh tempo: {{ $debt->due_date->format('d M Y') }} @endif
                                    </div>
                                </div>
                                @if($debt->status === 'paid')
                                    <span class="cd-badge paid">✅ Lunas</span>
                                @elseif($debt->status === 'partial')
                                    <span class="cd-badge partial">🔄 Sebagian</span>
                                @else
                                    <span class="cd-badge unpaid">🕐 Belum Bayar</span>
                                @endif
                            </div>
                            <div class="cd-item-desc">{{ $debt->description }}</div>
                            <div class="cd-amounts">
                                <div class="cd-amt" style="background:#f8fafc;">
                                    <div class="cd-amt-lbl">Total</div>
                                    <div class="cd-amt-val" style="color:#1e293b;">Rp {{ number_format((float) $debt->amount, 0, ',', '.') }}</div>
                                </div>
                                <div class="cd-amt" style="background:#f0fdf4;">
                                    <div class="cd-amt-lbl">Terbayar</div>
                                    <div class="cd-amt-val" style="color:#059669;">Rp {{ number_format((float) $debt->paid_amount, 0, ',', '.') }}</div>
                                </div>
                                <div class="cd-amt" style="background:#fef2f2;">
                                    <div class="cd-amt-lbl">Sisa</div>
                                    <div class="cd-amt-val" style="color:#dc2626;">Rp {{ number_format((int) $debt->remaining_amount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="cd-progress">
                                <div class="cd-progress-fill" style="width:{{ $pct }}%; background:{{ $barColor }};"></div>
                            </div>
                            <div class="cd-progress-lbl">{{ number_format($pct, 1) }}% terbayar</div>
                        </div>
                    @empty
                        <div class="cd-empty-form">
                            <div class="cd-empty-ico">🎉</div>
                            <div class="cd-empty-title">Tidak Ada Hutang</div>
                            <div class="cd-empty-sub">Pelanggan ini tidak memiliki hutang.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- RIGHT: Payment Form --}}
            <div>
                <div class="cd-card cd-form-card">
                    <div class="cd-form-hdr">
                        <div class="cd-form-title">💰 Form Pembayaran</div>
                        <div class="cd-form-sub">Total: Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                    </div>

                    @if($totalDebt > 0)
                    <div class="cd-form-body">
                        <form action="{{ route('pelanggan.kredit.pay_consolidated', $customer) }}" method="POST" id="payForm" novalidate>
                            @csrf

                            <div class="cd-fg">
                                <label class="cd-fl">Jumlah Pembayaran (Rp) *</label>
                                <input type="number" name="amount" id="amountInput" class="cd-fi big"
                                       min="1"
                                       data-max="{{ (int) $totalDebt }}"
                                       value="{{ old('amount', $totalDebt) }}"
                                       placeholder="Maks: Rp {{ number_format((int) $totalDebt, 0, ',', '.') }}"
                                       required>
                                <div class="cd-max-row">
                                    <span class="cd-max-lbl">Maksimal:</span>
                                    <span class="cd-max-val" id="maxBtn" title="Klik untuk mengisi penuh">Rp {{ number_format((int) $totalDebt, 0, ',', '.') }}</span>
                                </div>
                                <div class="cd-error-text" id="amountError">Jumlah tidak boleh melebihi total hutang</div>
                                @error('amount')<div style="font-size:0.72rem;color:#dc2626;font-weight:600;margin-top:0.25rem;">{{ $message }}</div>@enderror
                            </div>

                            <div class="cd-quick-btns">
                                <button type="button" class="cd-quick-btn" data-pct="25">25%</button>
                                <button type="button" class="cd-quick-btn" data-pct="50">50%</button>
                                <button type="button" class="cd-quick-btn" data-pct="100">100% (Lunas)</button>
                            </div>

                            <div class="cd-fg">
                                <label class="cd-fl">Tanggal Pembayaran *</label>
                                <input type="date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required class="cd-fi">
                                @error('payment_date')<div style="font-size:0.72rem;color:#dc2626;font-weight:600;margin-top:0.25rem;">{{ $message }}</div>@enderror
                            </div>

                            <div class="cd-fg">
                                <label class="cd-fl">Metode Pembayaran *</label>
                                <select name="payment_method" required class="cd-fi">
                                    <option value="cash" @selected(old('payment_method')=='cash')>💵 Tunai (Cash)</option>
                                    <option value="transfer" @selected(old('payment_method')=='transfer')>🏦 Transfer Bank</option>
                                    <option value="qris" @selected(old('payment_method')=='qris')>📱 QRIS</option>
                                    <option value="other" @selected(old('payment_method')=='other')>📝 Lainnya</option>
                                </select>
                                @error('payment_method')<div style="font-size:0.72rem;color:#dc2626;font-weight:600;margin-top:0.25rem;">{{ $message }}</div>@enderror
                            </div>

                            <div class="cd-fg">
                                <label class="cd-fl">No. Referensi (opsional)</label>
                                <input type="text" name="reference_number" value="{{ old('reference_number') }}" class="cd-fi" placeholder="No. Transfer/Invoice">
                                @error('reference_number')<div style="font-size:0.72rem;color:#dc2626;font-weight:600;margin-top:0.25rem;">{{ $message }}</div>@enderror
                            </div>

                            <div class="cd-fg" style="margin-bottom:0;">
                                <label class="cd-fl">Catatan (opsional)</label>
                                <textarea name="notes" rows="2" class="cd-fi" placeholder="Keterangan pembayaran...">{{ old('notes') }}</textarea>
                                @error('notes')<div style="font-size:0.72rem;color:#dc2626;font-weight:600;margin-top:0.25rem;">{{ $message }}</div>@enderror
                            </div>

                            <div style="margin-top:1.25rem;">
                                <button type="submit" class="cd-submit">💰 Bayar Hutang</button>
                            </div>

                            <div class="cd-info-box">
                                <div class="cd-info-title">💡 Info Pembayaran</div>
                                <div class="cd-info-text">Pembayaran akan otomatis mengurangi hutang dari transaksi terlama (FIFO). Sisa pembayaran akan dialokasikan ke hutang berikutnya.</div>
                            </div>
                        </form>
                    </div>
                    @else
                    <div class="cd-form-body">
                        <div class="cd-empty-form">
                            <div class="cd-empty-ico">🎉</div>
                            <div class="cd-empty-title">Tidak Ada Hutang</div>
                            <div class="cd-empty-sub">Pelanggan ini sudah lunas semua hutangnya.</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    (function() {
        const amountInput = document.getElementById('amountInput');
        const maxBtn = document.getElementById('maxBtn');
        const amountError = document.getElementById('amountError');
        const form = document.getElementById('payForm');
        const maxAmount = parseInt(amountInput?.dataset?.max || '0', 10);

        if (!amountInput || maxAmount <= 0) return;

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

        if (form) {
            form.addEventListener('submit', function(e) {
                const val = parseInt(amountInput.value, 10);
                if (val > maxAmount || val < 1 || isNaN(val)) {
                    e.preventDefault();
                    amountInput.classList.add('error');
                    if (amountError) {
                        amountError.textContent = val > maxAmount
                            ? 'Jumlah tidak boleh melebihi total hutang (Rp ' + maxAmount.toLocaleString('id-ID') + ')'
                            : 'Jumlah harus lebih dari 0';
                        amountError.style.display = 'block';
                    }
                    amountInput.focus();
                    return false;
                }
            });
        }

        if (maxBtn) {
            maxBtn.addEventListener('click', function() {
                amountInput.value = maxAmount;
                amountInput.classList.remove('error');
                if (amountError) amountError.style.display = 'none';
                amountInput.dispatchEvent(new Event('input'));
            });
        }

        document.querySelectorAll('.cd-quick-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const pct = parseInt(this.dataset.pct, 10);
                const val = Math.floor(maxAmount * pct / 100);
                amountInput.value = val;
                amountInput.classList.remove('error');
                if (amountError) amountError.style.display = 'none';
                amountInput.dispatchEvent(new Event('input'));
            });
        });
    })();
    </script>
    @endpush
</x-app-layout>
