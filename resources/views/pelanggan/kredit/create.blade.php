<x-app-layout>
    <x-slot name="header">{{ $type === 'debt' ? 'Catat Hutang Pelanggan' : 'Catat Piutang / Kredit' }}</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .cf-page { max-width:56rem; margin:0 auto; padding:1.5rem 1rem 5rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .cf-back { display:inline-flex; align-items:center; gap:0.5rem; font-size:0.8125rem; font-weight:600; color:#64748b; text-decoration:none; padding:0.5rem 0.75rem; border-radius:10px; transition:all 0.2s; margin-bottom:1.25rem; }
        .cf-back:hover { background:#f1f5f9; color:#334155; }

        .cf-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .cf-card-hdr { padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; }
        .cf-card-hdr-ico { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.125rem; flex-shrink:0; }
        .cf-card-hdr-title { font-size:0.9375rem; font-weight:700; color:#1e293b; }
        .cf-card-hdr-sub { font-size:0.75rem; color:#64748b; }
        .cf-card-body { padding:1.5rem; }

        .cf-badge { display:inline-flex; padding:0.25rem 0.65rem; border-radius:999px; font-size:0.6875rem; font-weight:700; }
        .cf-badge.debt { background:#fef2f2; color:#991b1b; }
        .cf-badge.credit { background:#eff6ff; color:#1e40af; }

        .cf-type-grid { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; }
        .cf-type-btn { display:flex; flex-direction:column; align-items:center; gap:0.35rem; padding:1rem; border:2px solid #e2e8f0; border-radius:12px; text-decoration:none; text-align:center; transition:all 0.2s; font-family:inherit; }
        .cf-type-btn:hover { border-color:#3b82f6; background:#f8fafc; }
        .cf-type-btn.active { border-color:#3b82f6; background:#eff6ff; }
        .cf-type-btn-ico { font-size:1.75rem; }
        .cf-type-btn-title { font-size:0.875rem; font-weight:700; color:#1e293b; }
        .cf-type-btn-sub { font-size:0.72rem; color:#64748b; }

        .cf-form-group { margin-bottom:1.25rem; }
        .cf-form-label { display:block; font-size:0.8125rem; font-weight:600; color:#334155; margin-bottom:0.375rem; }
        .cf-form-label .req { color:#ef4444; }
        .cf-form-input { width:100%; padding:0.625rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.8125rem; font-family:inherit; transition:all 0.2s; box-sizing:border-box; }
        .cf-form-input:focus { outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
        select.cf-form-input { cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.75rem center; padding-right:2.25rem; }
        textarea.cf-form-input { resize:vertical; }
        .cf-form-hint { font-size:0.72rem; color:#64748b; margin-top:0.375rem; }
        .cf-form-hint a { color:#2563eb; font-weight:600; text-decoration:none; }
        .cf-form-error { font-size:0.72rem; color:#dc2626; font-weight:600; margin-top:0.375rem; }

        .cf-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }

        .cf-credit-info { padding:1rem; background:#f8fafc; border-radius:12px; border-left:3px solid #6366f1; margin-top:1rem; display:none; }
        .cf-credit-info.show { display:block; }
        .cf-credit-info-title { font-size:0.75rem; font-weight:700; color:#4f46e5; margin-bottom:0.5rem; }
        .cf-credit-info-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; }
        .cf-credit-info-item { text-align:center; }
        .cf-credit-info-lbl { font-size:0.65rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; }
        .cf-credit-info-val { font-size:0.875rem; font-weight:800; margin-top:0.15rem; }

        .cf-alert { padding:0.875rem 1rem; border-radius:12px; font-size:0.8125rem; font-weight:600; margin-bottom:1rem; }
        .cf-alert-danger { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
        .cf-alert-danger ul { margin:0.35rem 0 0 1.25rem; padding:0; font-weight:500; }

        .cf-floating { position:fixed; bottom:0; left:0; right:0; background:#fff; border-top:1px solid #e2e8f0; padding:0.75rem 1.5rem; display:flex; justify-content:space-between; align-items:center; box-shadow:0 -4px 20px rgba(0,0,0,0.08); z-index:50; }
        .cf-floating-info { font-size:0.8125rem; color:#64748b; }
        .cf-floating-actions { display:flex; gap:0.5rem; }

        .cf-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.5rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:700; text-decoration:none; border:none; cursor:pointer; transition:all 0.2s; font-family:inherit; }
        .cf-btn-primary { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 2px 8px rgba(37,99,235,0.25); }
        .cf-btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(37,99,235,0.35); }
        .cf-btn-secondary { background:#f1f5f9; color:#475569; }
        .cf-btn-secondary:hover { background:#e2e8f0; }

        @media(max-width:768px) {
            .cf-type-grid, .cf-row, .cf-credit-info-grid { grid-template-columns:1fr; }
        }
    </style>
    @endpush

    <div class="cf-page">
        <a href="{{ route('pelanggan.kredit.index') }}" class="cf-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 8H1M8 15l-7-7 7-7"/></svg>
            Kembali
        </a>

        @if($errors->any())
            <div class="cf-alert cf-alert-danger">
                <div>❌ Periksa input Anda:</div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="cf-alert cf-alert-danger">❌ {{ session('error') }}</div>
        @endif

        {{-- Type Selector --}}
        <div class="cf-card">
            <div class="cf-card-hdr">
                <div class="cf-card-hdr-ico" style="background:{{ $type === 'debt' ? '#fef2f2' : '#eff6ff' }};">
                    {{ $type === 'debt' ? '💳' : '💰' }}
                </div>
                <div style="flex:1;">
                    <div class="cf-card-hdr-title">Jenis Catatan</div>
                    <div class="cf-card-hdr-sub">Pilih jenis pencatatan yang sesuai</div>
                </div>
                <span class="cf-badge {{ $type }}">{{ $type === 'debt' ? 'Hutang' : 'Piutang' }}</span>
            </div>
            <div class="cf-card-body">
                <div class="cf-type-grid">
                    <a href="{{ route('pelanggan.kredit.create', ['type'=>'debt']) }}" class="cf-type-btn {{ $type === 'debt' ? 'active' : '' }}">
                        <div class="cf-type-btn-ico">💳</div>
                        <div class="cf-type-btn-title">Hutang Pelanggan</div>
                        <div class="cf-type-btn-sub">Pelanggan beli kredit</div>
                    </a>
                    <a href="{{ route('pelanggan.kredit.create', ['type'=>'credit']) }}" class="cf-type-btn {{ $type === 'credit' ? 'active' : '' }}">
                        <div class="cf-type-btn-ico">💰</div>
                        <div class="cf-type-btn-title">Piutang / Kredit</div>
                        <div class="cf-type-btn-sub">Kelebihan bayar / retur</div>
                    </a>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="cf-card">
            <div class="cf-card-hdr">
                <div class="cf-card-hdr-ico" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#059669;">📝</div>
                <div style="flex:1;">
                    <div class="cf-card-hdr-title">Detail Catatan</div>
                    <div class="cf-card-hdr-sub">Pelanggan, tanggal, jumlah, dan keterangan</div>
                </div>
            </div>
            <div class="cf-card-body">
                <form id="creditForm" action="{{ route('pelanggan.kredit.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">

                    <div class="cf-form-group">
                        <label class="cf-form-label">Pelanggan <span class="req">*</span></label>
                        <select name="customer_id" class="cf-form-input" id="customerSelect" required>
                            <option value="">Pilih Pelanggan</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}"
                                    @selected(old('customer_id') == $c->id)
                                    data-limit="{{ (float) $c->credit_limit }}"
                                    data-debt="{{ (float) $c->current_debt }}">
                                    {{ $c->name }}
                                    @if($c->current_debt > 0) (Hutang: Rp {{ number_format((float) $c->current_debt, 0, ',', '.') }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')<div class="cf-form-error">{{ $message }}</div>@enderror
                        <div class="cf-form-hint">
                            Belum ada pelanggan? <a href="{{ route('pelanggan.create') }}">Tambah dulu</a>
                        </div>

                        <div class="cf-credit-info" id="creditInfo">
                            <div class="cf-credit-info-title">💡 Informasi Kredit Pelanggan</div>
                            <div class="cf-credit-info-grid">
                                <div class="cf-credit-info-item">
                                    <div class="cf-credit-info-lbl">Batas Kredit</div>
                                    <div class="cf-credit-info-val" id="infoLimit" style="color:#1e293b;">-</div>
                                </div>
                                <div class="cf-credit-info-item">
                                    <div class="cf-credit-info-lbl">Sisa Hutang</div>
                                    <div class="cf-credit-info-val" id="infoDebt" style="color:#dc2626;">-</div>
                                </div>
                                <div class="cf-credit-info-item">
                                    <div class="cf-credit-info-lbl">Sisa Limit</div>
                                    <div class="cf-credit-info-val" id="infoRemaining" style="color:#059669;">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cf-row">
                        <div class="cf-form-group">
                            <label class="cf-form-label">Tanggal Transaksi <span class="req">*</span></label>
                            <input type="date" name="transaction_date" class="cf-form-input" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                            @error('transaction_date')<div class="cf-form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="cf-form-group">
                            <label class="cf-form-label">Jatuh Tempo</label>
                            <input type="date" name="due_date" class="cf-form-input" value="{{ old('due_date') }}">
                            <div class="cf-form-hint">Kosongkan jika tidak ada jatuh tempo.</div>
                        </div>
                    </div>

                    <div class="cf-form-group">
                        <label class="cf-form-label">Jumlah (Rp) <span class="req">*</span></label>
                        <input type="number" name="amount" class="cf-form-input" id="amountInput" value="{{ old('amount') }}" min="1" placeholder="0" required>
                        @error('amount')<div class="cf-form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="cf-form-group">
                        <label class="cf-form-label">Keterangan <span class="req">*</span></label>
                        <input type="text" name="description" class="cf-form-input" value="{{ old('description') }}"
                               placeholder="{{ $type === 'debt' ? 'Mis: Pembelian rokok kredit' : 'Mis: Kelebihan bayar transaksi #123' }}" required>
                        @error('description')<div class="cf-form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="cf-form-group" style="margin-bottom:0;">
                        <label class="cf-form-label">Catatan Tambahan</label>
                        <textarea name="notes" class="cf-form-input" rows="3" placeholder="Opsional...">{{ old('notes') }}</textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Floating Bar --}}
    <div class="cf-floating">
        <span class="cf-floating-info">
            {{ $type === 'debt' ? '💳 Hutang akan menambah saldo hutang pelanggan.' : '💰 Piutang dicatat sebagai kelebihan bayar/retur.' }}
        </span>
        <div class="cf-floating-actions">
            <a href="{{ route('pelanggan.kredit.index') }}" class="cf-btn cf-btn-secondary">Batal</a>
            <button type="submit" form="creditForm" class="cf-btn cf-btn-primary">💾 Simpan</button>
        </div>
    </div>

    @push('scripts')
    <script>
    (function() {
        const select = document.getElementById('customerSelect');
        const info = document.getElementById('creditInfo');
        const infoLimit = document.getElementById('infoLimit');
        const infoDebt = document.getElementById('infoDebt');
        const infoRemaining = document.getElementById('infoRemaining');
        const type = '{{ $type }}';

        function formatRp(n) {
            return 'Rp ' + Math.round(n).toLocaleString('id-ID');
        }

        if (select && info) {
            select.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                if (!opt || !opt.value) {
                    info.classList.remove('show');
                    return;
                }
                const limit = parseFloat(opt.dataset.limit) || 0;
                const debt = parseFloat(opt.dataset.debt) || 0;
                const remaining = Math.max(0, limit - debt);

                infoLimit.textContent = limit > 0 ? formatRp(limit) : 'Tidak terbatas';
                infoDebt.textContent = formatRp(debt);
                infoRemaining.textContent = limit > 0 ? formatRp(remaining) : '-';
                infoRemaining.style.color = remaining > 0 ? '#059669' : '#dc2626';

                info.classList.add('show');
            });

            // Trigger on page load if customer already selected
            if (select.value) {
                select.dispatchEvent(new Event('change'));
            }
        }
    })();
    </script>
    @endpush
</x-app-layout>
