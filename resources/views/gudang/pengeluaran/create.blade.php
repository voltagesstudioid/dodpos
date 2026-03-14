<x-app-layout>
    <x-slot name="header">Keluarkan Barang</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-form-container">
            
            {{-- Navigation Back --}}
            <a href="{{ route('gudang.pengeluaran') }}" class="tr-back-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Riwayat
            </a>

            {{-- Main Paper / Document --}}
            <div class="tr-paper">
                
                {{-- Paper Header --}}
                <div class="tr-paper-header">
                    <div class="tr-header-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    </div>
                    <div class="tr-header-text">
                        <h1 class="tr-title">Keluarkan Barang</h1>
                        <p class="tr-subtitle">Catat pengeluaran barang. Sistem memotong stok otomatis (FIFO).</p>
                    </div>
                </div>

                {{-- Alerts --}}
                <div class="tr-paper-alerts">
                    @if(session('error')) 
                        <div class="tr-alert tr-alert-danger">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                            <span>{{ session('error') }}</span>
                        </div> 
                    @endif

                    @if($errors->any())
                        <div class="tr-alert tr-alert-danger tr-alert-block">
                            <div class="tr-alert-head">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <strong>Terdapat kesalahan input:</strong>
                            </div>
                            <ul>
                                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- FIFO Banner --}}
                    <div class="tr-alert tr-alert-warning">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                        <div>
                            <strong>Sistem FIFO Aktif:</strong> Pemotongan memprioritaskan batch paling awal / mendekati expired.
                        </div>
                    </div>
                </div>

                {{-- The Form --}}
                <form action="{{ route('gudang.pengeluaran.store') }}" method="POST">
                    @csrf

                    {{-- Section 1 --}}
                    <fieldset class="tr-fieldset">
                        <legend class="tr-legend">1. Data Barang & Referensi</legend>
                        <div class="tr-grid">
                            <div class="tr-col-full">
                                <label class="tr-label">No. Referensi / Nota Pengeluaran <span class="tr-req">*</span></label>
                                <input type="text" name="reference_number" value="{{ old('reference_number', 'OUT-'.date('Ymd-His')) }}" class="tr-input tr-font-mono @error('reference_number') is-invalid @enderror" required>
                                @error('reference_number') <div class="tr-error">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="tr-col-half">
                                <label class="tr-label">Barang / Produk <span class="tr-req">*</span></label>
                                <select name="product_id" class="tr-input @error('product_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} (Stok: {{ $p->stock }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id') <div class="tr-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="tr-col-half">
                                <label class="tr-label">Jumlah Dikeluarkan (Qty) <span class="tr-req">*</span></label>
                                <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" class="tr-input @error('quantity') is-invalid @enderror" required placeholder="Cth: 10">
                                @error('quantity') <div class="tr-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- Section 2 --}}
                    <fieldset class="tr-fieldset">
                        <legend class="tr-legend">2. Gudang Asal</legend>
                        <div class="tr-grid">
                            <div class="tr-col-full">
                                <label class="tr-label">Pilih Gudang <span class="tr-req">*</span></label>
                                <select name="warehouse_id" id="warehouse_id" class="tr-input @error('warehouse_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Gudang --</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}" {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id') <div class="tr-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- Section 3 --}}
                    <fieldset class="tr-fieldset tr-fieldset-last">
                        <legend class="tr-legend">3. Keterangan Tambahan</legend>
                        <div class="tr-grid">
                            <div class="tr-col-full">
                                <label class="tr-label">Alasan Pengeluaran</label>
                                <textarea name="notes" rows="3" class="tr-input tr-textarea" placeholder="Contoh: Pengiriman ke cabang, penjualan langsung, rusak/hilang...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </fieldset>

                    {{-- Footer Actions --}}
                    <div class="tr-form-footer">
                        <a href="{{ route('gudang.pengeluaran') }}" class="tr-btn tr-btn-light">Batalkan</a>
                        <button type="submit" class="tr-btn tr-btn-danger">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                            Keluarkan & Potong Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sp = new URLSearchParams(window.location.search);
            const fields = ['product_id', 'warehouse_id', 'quantity', 'reference_number', 'notes'];
            
            fields.forEach(field => {
                const el = document.querySelector(`[name="${field}"]`);
                if (el && sp.has(field)) {
                    el.value = sp.get(field);
                }
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
            --tr-primary: #3b82f6;
            --tr-danger: #ef4444;
            --tr-danger-hover: #dc2626;
            --tr-danger-light: #fef2f2;
            --tr-warning: #f59e0b;
            --tr-warning-bg: #fffbeb;
            --tr-warning-text: #b45309;
            --tr-radius-lg: 16px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 4rem; }
        .tr-form-container {
            max-width: 760px; /* Ukuran pas, tidak terlalu melebar ke samping */
            margin: 0 auto;
            padding: 2rem 1.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--tr-text-main);
        }

        /* ── BACK LINK ── */
        .tr-back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.85rem; font-weight: 600; color: var(--tr-text-muted);
            text-decoration: none; margin-bottom: 1.25rem; transition: color 0.2s;
        }
        .tr-back-link:hover { color: var(--tr-text-main); }

        /* ── PAPER (MAIN CARD) ── */
        .tr-paper {
            background: var(--tr-surface);
            border-radius: var(--tr-radius-lg);
            border: 1px solid var(--tr-border);
            box-shadow: var(--tr-shadow-sm);
            overflow: hidden;
        }

        /* HEADER */
        .tr-paper-header {
            display: flex; align-items: center; gap: 1rem;
            padding: 1.75rem 2rem;
            border-bottom: 1px solid var(--tr-border-light);
            background: #ffffff;
        }
        .tr-header-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: var(--tr-danger-light); color: var(--tr-danger);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .tr-title { font-size: 1.35rem; font-weight: 800; margin: 0; letter-spacing: -0.01em; }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0.25rem 0 0 0; font-weight: 500; line-height: 1.4; }

        /* ALERTS */
        .tr-paper-alerts { padding: 1.5rem 2rem 0 2rem; display: flex; flex-direction: column; gap: 1rem; }
        .tr-alert { 
            display: flex; align-items: flex-start; gap: 12px; 
            padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); 
            font-size: 0.85rem; line-height: 1.5; border: 1px solid transparent; 
        }
        .tr-alert-danger { background: var(--tr-danger-light); color: #b91c1c; border-color: #fecaca; }
        .tr-alert-warning { background: var(--tr-warning-bg); color: var(--tr-warning-text); border-color: #fde68a; }
        
        .tr-alert-block { flex-direction: column; gap: 8px; }
        .tr-alert-head { display: flex; align-items: center; gap: 8px; font-weight: 700; }
        .tr-alert ul { margin: 0; padding-left: 2rem; }
        .tr-alert-icon { flex-shrink: 0; margin-top: 1px; }

        /* ── FIELDSETS & GRID ── */
        .tr-fieldset {
            padding: 1.75rem 2rem;
            margin: 0; border: none;
            border-bottom: 1px dashed var(--tr-border);
        }
        .tr-fieldset-last { border-bottom: none; }
        .tr-legend {
            font-size: 1rem; font-weight: 700; color: var(--tr-text-main);
            margin-bottom: 1.25rem; width: 100%; display: block;
        }

        .tr-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .tr-col-full { grid-column: 1 / -1; }
        .tr-col-half { grid-column: span 1; }

        /* ── INPUTS ── */
        .tr-label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--tr-text-main); margin-bottom: 6px; }
        .tr-req { color: var(--tr-danger); }
        
        .tr-input {
            width: 100%; padding: 0.65rem 0.85rem;
            border: 1px solid var(--tr-border);
            border-radius: var(--tr-radius-md);
            font-family: inherit; font-size: 0.85rem; color: var(--tr-text-main);
            background: #f8fafc; outline: none; transition: all 0.2s;
            appearance: none; /* Reset native styling for select */
        }
        select.tr-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-size: 16px; background-position: right 12px center; background-repeat: no-repeat;
            padding-right: 2.5rem; cursor: pointer;
        }
        .tr-input:focus { border-color: var(--tr-danger); background: #ffffff; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1); }
        .tr-font-mono { font-family: monospace; font-weight: 600; font-size: 0.95rem; }
        .tr-textarea { resize: vertical; min-height: 80px; }
        
        .is-invalid { border-color: var(--tr-danger) !important; background: var(--tr-danger-light) !important; }
        .tr-error { font-size: 0.75rem; color: var(--tr-danger); font-weight: 600; margin-top: 4px; }

        /* ── FOOTER ACTIONS ── */
        .tr-form-footer {
            display: flex; justify-content: flex-end; align-items: center; gap: 1rem;
            padding: 1.5rem 2rem; background: #f8fafc; border-top: 1px solid var(--tr-border);
        }
        .tr-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 0.65rem 1.25rem; border-radius: var(--tr-radius-md); font-size: 0.9rem; 
            font-family: inherit; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s;
        }
        .tr-btn-light { background: transparent; color: var(--tr-text-muted); }
        .tr-btn-light:hover { color: var(--tr-text-main); background: #e2e8f0; }
        .tr-btn-danger { background: var(--tr-danger); color: #ffffff; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2); }
        .tr-btn-danger:hover { background: var(--tr-danger-hover); transform: translateY(-1px); box-shadow: 0 4px 6px rgba(239, 68, 68, 0.3); }

        /* ── RESPONSIVE ── */
        @media (max-width: 640px) {
            .tr-form-container { padding: 1rem; }
            .tr-paper-header { padding: 1.25rem 1.5rem; flex-direction: column; align-items: flex-start; }
            .tr-paper-alerts { padding: 1.25rem 1.5rem 0 1.5rem; }
            .tr-fieldset { padding: 1.5rem; }
            .tr-col-half { grid-column: 1 / -1; }
            .tr-form-footer { flex-direction: column-reverse; padding: 1.5rem; }
            .tr-form-footer .tr-btn { width: 100%; }
        }
    </style>
    @endpush
</x-app-layout>