<x-app-layout>
    <x-slot name="header">Opname Stok Baru</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page tr-page-narrow">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <h1 class="tr-title">
                        <span class="tr-title-icon-box bg-warning">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14h6"></path><path d="M9 18h6"></path><path d="M9 10h6"></path></svg>
                        </span>
                        Buat Opname Stok
                    </h1>
                    <p class="tr-subtitle">Masukkan jumlah fisik aktual di lapangan, sistem akan menghitung selisih otomatis.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.opname') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali ke Histori
                    </a>
                </div>
            </div>

            {{-- ALERTS --}}
            @if(session('error')) 
                <div class="tr-alert tr-alert-danger">
                    <svg class="tr-alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    {{ session('error') }}
                </div> 
            @endif

            @if($errors->any())
                <div class="tr-alert tr-alert-danger">
                    <div class="tr-alert-header">
                        <svg class="tr-alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <strong>Terdapat kesalahan input:</strong>
                    </div>
                    <ul class="tr-alert-list">
                        @foreach($errors->all() as $err) 
                            <li>{{ $err }}</li> 
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- INFO BOX --}}
            <div class="tr-info-box tr-box-blue">
                <svg class="tr-info-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                <div>
                    <div class="tr-info-title">Cara Kerja Sistem</div>
                    <div class="tr-info-desc">Masukkan jumlah stok yang Anda <strong>hitung secara fisik</strong> di rak/area terkait. Sistem akan otomatis membandingkannya dengan database dan membuat jurnal penyesuaian selisih (+ atau -).</div>
                </div>
            </div>

            <form action="{{ route('gudang.opname.store') }}" method="POST">
                @csrf

                {{-- SECTION 1: Referensi & Produk --}}
                <div class="tr-card tr-form-section">
                    <div class="tr-section-header">
                        <div class="tr-step-number bg-warning">1</div>
                        <h2 class="tr-section-title">Referensi & Produk yang Di-Opname</h2>
                    </div>
                    <div class="tr-form-grid">
                        <div class="tr-form-group">
                            <label class="tr-label">No. Dokumen Opname <span class="tr-required">*</span></label>
                            <input type="text" name="reference_number" value="{{ old('reference_number', 'OPN-'.date('Ymd-His')) }}" class="tr-input tr-font-mono @error('reference_number') is-invalid @enderror" required>
                            @error('reference_number') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>
                        <div class="tr-form-group">
                            <label class="tr-label">Nama Barang / Produk <span class="tr-required">*</span></label>
                            <div class="tr-select-wrapper">
                                <select name="product_id" id="product_id" class="tr-select @error('product_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ $p->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('product_id') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Lokasi --}}
                <div class="tr-card tr-form-section">
                    <div class="tr-section-header">
                        <div class="tr-step-number bg-warning">2</div>
                        <h2 class="tr-section-title">Lokasi Pengecekan</h2>
                    </div>
                    <div class="tr-form-grid">
                        <div class="tr-form-group tr-col-span-2">
                            <label class="tr-label">Gudang / Cabang <span class="tr-required">*</span></label>
                            <div class="tr-select-wrapper">
                                <select name="warehouse_id" id="warehouse_id" class="tr-select @error('warehouse_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Gudang --</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}" {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('warehouse_id') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: Hasil Fisik --}}
                <div class="tr-card tr-form-section">
                    <div class="tr-section-header">
                        <div class="tr-step-number bg-warning">3</div>
                        <h2 class="tr-section-title">Hasil Penghitungan Fisik</h2>
                    </div>
                    <div class="tr-form-grid">
                        {{-- Stok Sistem (Readonly) --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Stok Sistem (Gudang Terpilih)</label>
                            <input type="text" id="system_qty" value="—" class="tr-input tr-input-readonly" readonly>
                            <div class="tr-input-hint">Terisi otomatis setelah pilih Produk & Gudang</div>
                        </div>

                        {{-- Jumlah Aktual (Primary Focus) --}}
                        <div class="tr-form-group">
                            <label class="tr-label tr-label-lg">Jumlah Aktual Ditemukan <span class="tr-required">*</span></label>
                            <input type="number" name="actual_qty" value="{{ old('actual_qty') }}" min="0" class="tr-input tr-input-large @error('actual_qty') is-invalid @enderror" placeholder="0">
                            <div class="tr-input-hint">Total Qty yang dihitung fisik di lapangan</div>
                            @error('actual_qty') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>

                        {{-- Input Alternatif Selisih --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Atau input Selisih (+ / -)</label>
                            <input type="number" name="difference" id="difference" value="{{ old('difference') }}" class="tr-input tr-input-center @error('difference') is-invalid @enderror" placeholder="Cth: -5 atau 2">
                            <div class="tr-input-hint">Jika diisi, sistem hitung: Aktual = Sistem + Selisih</div>
                            @error('difference') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>

                        {{-- Alasan / Keterangan --}}
                        <div class="tr-form-group tr-col-span-2">
                            <label class="tr-label">Alasan / Keterangan Selisih</label>
                            <textarea name="notes" rows="3" class="tr-textarea" placeholder="Contoh: Stok usang dibuang, barang hilang, kesalahan pencatatan di shift sebelumnya...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- FOOTER ACTIONS --}}
                <div class="tr-form-actions">
                    <a href="{{ route('gudang.opname') }}" class="tr-btn tr-btn-light">Batal</a>
                    <button type="submit" class="tr-btn tr-btn-warning">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Simpan Penyesuaian Stok
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const systemQtyUrl = "{{ route('gudang.opname.system_qty') }}";
            const product = document.getElementById('product_id');
            const warehouse = document.getElementById('warehouse_id');
            const systemQty = document.getElementById('system_qty');
            const actual = document.querySelector('input[name="actual_qty"]');
            const diff = document.getElementById('difference');

            async function refreshSystemQty() {
                const pid = product?.value;
                const wid = warehouse?.value;
                if (!pid || !wid) {
                    systemQty.value = '—';
                    return;
                }
                
                // Tambahkan efek loading visual pada input readonly
                systemQty.value = 'Memuat...';
                systemQty.style.opacity = '0.5';

                try {
                    const url = new URL(systemQtyUrl, window.location.origin);
                    url.searchParams.set('product_id', pid);
                    url.searchParams.set('warehouse_id', wid);
                    const res = await fetch(url.toString(), { headers: { Accept: 'application/json' } });
                    const data = await res.json();
                    systemQty.value = String(data?.system_qty ?? 0);
                } catch (_) {
                    systemQty.value = 'Gagal memuat';
                } finally {
                    systemQty.style.opacity = '1';
                }
            }

            function syncInputs() {
                if (diff && diff.value !== '' && diff.value !== null && diff.value !== undefined && String(diff.value).length) {
                    if (actual) actual.required = false;
                } else {
                    if (actual) actual.required = true;
                }
            }

            product?.addEventListener('change', () => { refreshSystemQty(); syncInputs(); });
            warehouse?.addEventListener('change', () => { refreshSystemQty(); syncInputs(); });
            diff?.addEventListener('input', syncInputs);
            actual?.addEventListener('input', () => { if (diff) diff.value = ''; syncInputs(); });

            // Initialize on load
            refreshSystemQty();
            syncInputs();
            
            // Setup URL Params bindings
            const sp = new URLSearchParams(window.location.search);
            const pid = sp.get('product_id');
            const wid = sp.get('warehouse_id');
            const qty = sp.get('quantity');
            const ref = sp.get('reference_number');
            const note= sp.get('notes');
            
            function setVal(sel, val){ 
                const el = document.querySelector(sel); 
                if(el && val){ el.value = val; } 
            }
            
            setVal('select[name="product_id"]', pid);
            setVal('select[name="warehouse_id"]', wid);
            setVal('input[name="actual_qty"]', qty); // assuming qty parameter maps to actual_qty
            setVal('input[name="reference_number"]', ref);
            setVal('textarea[name="notes"]', note);
        })();
    </script>

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
            --tr-danger: #ef4444;
            --tr-danger-light: #fef2f2;
            --tr-warning: #f59e0b;
            --tr-warning-hover: #d97706;
            --tr-warning-bg: #fffbeb;
            --tr-warning-border: #fde68a;
            --tr-warning-text: #b45309;
            --tr-info-bg: #eff6ff;
            --tr-info-border: #bfdbfe;
            --tr-info-text: #1e40af;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page {
            padding: 2rem 1.5rem;
            margin: 0 auto;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--tr-text-main);
        }
        .tr-page-narrow { max-width: 820px; }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-warning { background: var(--tr-warning-bg); color: var(--tr-warning-text); }
        .tr-subtitle { font-size: 0.9rem; color: var(--tr-text-muted); margin: 0.35rem 0 0 0; font-weight: 500; }
        
        /* ── ALERTS ── */
        .tr-alert { padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.25rem; border: 1px solid transparent; font-size: 0.85rem; }
        .tr-alert-danger { background: var(--tr-danger-light); border-color: #fecaca; color: #b91c1c; display: flex; flex-direction: column; gap: 0.5rem; }
        .tr-alert-header { display: flex; align-items: center; gap: 8px; font-size: 0.9rem; }
        .tr-alert-icon { flex-shrink: 0; }
        .tr-alert-list { margin: 0; padding-left: 2rem; line-height: 1.6; }

        /* ── INFO BOX ── */
        .tr-info-box { display: flex; align-items: flex-start; gap: 12px; padding: 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.5rem; border: 1px solid transparent; }
        .tr-box-blue { background: var(--tr-info-bg); border-color: var(--tr-info-border); }
        .tr-info-icon { color: var(--tr-primary); flex-shrink: 0; margin-top: 2px; }
        .tr-info-title { font-weight: 700; color: var(--tr-info-text); font-size: 0.9rem; margin-bottom: 2px; }
        .tr-info-desc { color: var(--tr-info-text); font-size: 0.85rem; line-height: 1.5; opacity: 0.9; }

        /* ── CARD & FORM SECTIONS ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-form-section { padding: 1.5rem; margin-bottom: 1.5rem; }
        .tr-section-header { display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--tr-border-light); }
        .tr-step-number { width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; color: #fff; flex-shrink: 0; }
        .tr-step-number.bg-warning { background: var(--tr-warning); box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3); }
        .tr-section-title { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin: 0; }

        /* ── FORM ELEMENTS ── */
        .tr-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .tr-col-span-2 { grid-column: span 2; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-label { font-size: 0.85rem; font-weight: 600; color: var(--tr-text-main); }
        .tr-label-lg { font-size: 0.95rem; color: var(--tr-text-main); }
        .tr-required { color: var(--tr-danger); }
        
        .tr-input, .tr-select, .tr-textarea {
            width: 100%;
            padding: 0.6rem 0.85rem;
            border: 1px solid var(--tr-border);
            border-radius: var(--tr-radius-md);
            font-family: inherit;
            font-size: 0.85rem;
            color: var(--tr-text-main);
            background: #f8fafc;
            transition: all 0.2s;
            outline: none;
        }
        .tr-input:focus, .tr-select:focus, .tr-textarea:focus { border-color: var(--tr-warning); background: #ffffff; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15); }
        
        /* Specialized Inputs */
        .tr-font-mono { font-family: monospace; font-size: 0.95rem; font-weight: 600; }
        .tr-input-readonly { background: #f1f5f9; border-color: #e2e8f0; color: var(--tr-text-muted); font-weight: 800; text-align: center; font-size: 1.1rem; cursor: not-allowed; }
        .tr-input-readonly:focus { border-color: #e2e8f0; box-shadow: none; }
        .tr-input-large { font-size: 1.5rem; font-weight: 800; text-align: center; padding: 0.8rem; border-color: var(--tr-warning); box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .tr-input-large:focus { box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.2); }
        .tr-input-center { text-align: center; font-weight: 700; font-size: 1rem; }
        
        .tr-textarea { resize: vertical; min-height: 80px; }
        .tr-input-hint { font-size: 0.75rem; color: var(--tr-text-muted); text-align: center; margin-top: 2px; }
        
        .is-invalid { border-color: var(--tr-danger) !important; background: var(--tr-danger-light) !important; }
        .tr-error-msg { font-size: 0.75rem; color: var(--tr-danger); font-weight: 600; margin-top: 2px; text-align: center; }

        /* Custom Select Wrapper */
        .tr-select-wrapper { position: relative; }
        .tr-select { appearance: none; padding-right: 2rem; cursor: pointer; }
        .tr-select-wrapper::after {
            content: ''; position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            width: 10px; height: 10px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-size: contain; background-repeat: no-space; pointer-events: none;
        }

        /* ── BUTTONS & ACTIONS ── */
        .tr-form-actions { display: flex; justify-content: flex-end; align-items: center; gap: 1rem; margin-top: 1rem; }
        .tr-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 0.65rem 1.25rem; border-radius: var(--tr-radius-md); font-size: 0.9rem; font-family: inherit; font-weight: 600;
            cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent;
        }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); box-shadow: var(--tr-shadow-sm); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); }
        .tr-btn-light { color: var(--tr-text-muted); }
        .tr-btn-light:hover { color: var(--tr-text-main); }
        
        .tr-btn-warning { background: var(--tr-warning); color: #ffffff; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2); }
        .tr-btn-warning:hover { background: var(--tr-warning-hover); transform: translateY(-1px); box-shadow: 0 6px 8px -1px rgba(245, 158, 11, 0.3); }

        /* ── RESPONSIVE ── */
        @media (max-width: 640px) {
            .tr-header { flex-direction: column; }
            .tr-form-grid { grid-template-columns: 1fr; gap: 1rem; }
            .tr-col-span-2 { grid-column: span 1; }
            .tr-form-actions { flex-direction: column-reverse; width: 100%; }
            .tr-form-actions .tr-btn { width: 100%; }
        }
    </style>
    @endpush
</x-app-layout>