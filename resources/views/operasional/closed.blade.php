<x-app-layout>
    <x-slot name="header">Sesi Operasional Terkunci</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── TOP NAVIGATION ─── --}}
            <div class="tr-header">
                <div class="tr-header-actions">
                    <a href="{{ route('dashboard') }}" class="tr-btn-ghost">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali ke Dashboard
                    </a>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('operasional.sesi.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Riwayat Laporan Sesi
                    </a>
                </div>
            </div>

            {{-- ─── MAIN CENTERED CARD ─── --}}
            <div class="tr-center-container">
                <div class="tr-lock-card">
                    
                    {{-- Lock Illustration / Icon --}}
                    <div class="tr-lock-header">
                        <div class="tr-lock-icon-wrap">
                            <svg class="tr-lock-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                        <h1 class="tr-lock-title">Sesi Belum Dibuka</h1>
                        <p class="tr-lock-subtitle">Pengeluaran kas operasional hanya dapat dicatat saat sesi aktif. Silakan buka sesi untuk memulai hari ini.</p>
                    </div>

                    <div class="tr-lock-body">
                        @can('manage_sesi_operasional')
                            <form action="{{ route('operasional.open_session') }}" method="POST" class="tr-form-main">
                                @csrf
                                
                                <div class="tr-grid-2">
                                    {{-- Modal Awal --}}
                                    <div class="tr-form-group">
                                        <label class="tr-label">Modal Awal (Kas) <span class="tr-req">*</span></label>
                                        <div class="tr-input-prefix-group">
                                            <span class="prefix">Rp</span>
                                            <input type="number" name="opening_amount" 
                                                class="tr-input tr-font-mono @error('opening_amount') is-invalid @enderror" 
                                                placeholder="0" required min="0" 
                                                value="{{ old('opening_amount', 0) }}" autofocus>
                                        </div>
                                        @error('opening_amount') <div class="tr-error-msg">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Metode --}}
                                    <div class="tr-form-group">
                                        <label class="tr-label">Metode Saldo <span class="tr-req">*</span></label>
                                        <div class="tr-select-wrapper">
                                            <select name="payment_method" class="tr-select" required>
                                                @php $pm = old('payment_method', 'Tunai'); @endphp
                                                <option value="Tunai" {{ $pm === 'Tunai' ? 'selected' : '' }}>Tunai / Laci Kas</option>
                                                <option value="Transfer" {{ $pm === 'Transfer' ? 'selected' : '' }}>Transfer / Bank</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Catatan --}}
                                <div class="tr-form-group" style="margin-top: 1.25rem;">
                                    <label class="tr-label">Catatan Tambahan <span class="tr-optional">(Opsional)</span></label>
                                    <textarea name="notes" rows="2" class="tr-textarea" placeholder="Contoh: Saldo bawaan dari shift sebelumnya...">{{ old('notes') }}</textarea>
                                </div>

                                {{-- Action --}}
                                <div class="tr-form-actions">
                                    <button type="submit" class="tr-btn tr-btn-success tr-btn-block">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        Mulai Sesi Operasional
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="tr-no-access-box">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <h3>Akses Terbatas</h3>
                                <p>Anda tidak memiliki izin untuk membuka sesi operasional. Silakan hubungi Supervisor atau Admin untuk membukakan sesi.</p>
                            </div>
                        @endcan
                    </div>
                </div>

                {{-- Alert Bawah --}}
                @if(session('success') || session('error'))
                    <div class="tr-alert-stack">
                        @if(session('success')) <div class="tr-alert tr-alert-success">✅ {{ session('success') }}</div> @endif
                        @if(session('error')) <div class="tr-alert tr-alert-danger">❌ {{ session('error') }}</div> @endif
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-indigo: #4f46e5;
            --tr-success: #10b981;
            --tr-success-hover: #059669;
            --tr-danger: #ef4444;
            --tr-warning: #f59e0b;
            --tr-warning-light: #fffbeb;
            --tr-warning-border: #fde68a;
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-radius: 16px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; display: flex; flex-direction: column; }
        .tr-page { flex: 1; padding: 2rem 1.5rem; display: flex; flex-direction: column; }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: center; width: 100%; max-width: 1000px; margin: 0 auto 3rem; }
        .tr-btn-ghost { display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 700; color: var(--tr-text-muted); text-decoration: none; transition: 0.2s; }
        .tr-btn-ghost:hover { color: var(--tr-text-main); }
        .tr-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0.6rem 1.25rem; border-radius: 10px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: 0.2s; border: 1px solid transparent; text-decoration: none; }
        .tr-btn-outline { border-color: var(--tr-border); background: white; color: var(--tr-text-main); box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .tr-btn-outline:hover { background: #f1f5f9; }

        /* ── CENTER LAYOUT ── */
        .tr-center-container { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; max-width: 580px; margin: 0 auto; }
        
        .tr-lock-card { background: var(--tr-surface); width: 100%; border-radius: var(--tr-radius); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01); border: 1px solid var(--tr-border); overflow: hidden; margin-bottom: 1.5rem; }
        
        .tr-lock-header { background: var(--tr-warning-light); padding: 2.5rem 2rem 2rem; display: flex; flex-direction: column; align-items: center; text-align: center; border-bottom: 1px solid var(--tr-warning-border); }
        .tr-lock-icon-wrap { width: 64px; height: 64px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--tr-warning); margin-bottom: 1rem; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.15); }
        .tr-lock-icon { width: 28px; height: 28px; }
        .tr-lock-title { font-size: 1.5rem; font-weight: 900; color: #92400e; margin: 0 0 0.5rem 0; letter-spacing: -0.02em; }
        .tr-lock-subtitle { font-size: 0.9rem; color: #b45309; margin: 0; line-height: 1.5; font-weight: 500; }

        .tr-lock-body { padding: 2rem; background: #fff; }

        /* ── FORM ELEMENTS ── */
        .tr-grid-2 { display: grid; grid-template-columns: 1.2fr 1fr; gap: 1rem; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-label { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--tr-text-muted); letter-spacing: 0.05em; }
        .tr-req { color: var(--tr-danger); }
        .tr-optional { font-weight: 500; text-transform: none; letter-spacing: 0; }

        .tr-input, .tr-select, .tr-textarea { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--tr-border); border-radius: 10px; font-size: 0.95rem; background: #fcfcfd; transition: 0.2s; font-family: inherit; color: var(--tr-text-main); font-weight: 600; outline: none; }
        .tr-input:focus, .tr-select:focus, .tr-textarea:focus { border-color: var(--tr-indigo); background: #fff; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
        .tr-textarea { resize: vertical; min-height: 80px; font-weight: 500; }
        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-weight: 800; font-size: 1rem;}

        /* PREFIX */
        .tr-input-prefix-group { display: flex; align-items: stretch; }
        .tr-input-prefix-group .prefix { display: flex; align-items: center; padding: 0 0.85rem; background: #f1f5f9; border: 1.5px solid var(--tr-border); border-right: none; border-radius: 10px 0 0 10px; font-size: 0.85rem; font-weight: 800; color: var(--tr-text-muted); }
        .tr-input-prefix-group .tr-input { border-radius: 0 10px 10px 0; }

        .tr-error-msg { color: var(--tr-danger); font-size: 0.75rem; font-weight: 700; margin-top: 4px; }

        /* SELECT CUSTOM */
        .tr-select-wrapper { position: relative; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        .tr-select { appearance: none; padding-right: 2.5rem; cursor: pointer; }

        /* ── ACTIONS ── */
        .tr-form-actions { margin-top: 2rem; }
        .tr-btn-success { background: var(--tr-success); color: white; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.25); }
        .tr-btn-success:hover { background: var(--tr-success-hover); transform: translateY(-1px); }
        .tr-btn-block { width: 100%; justify-content: center; padding: 0.85rem; font-size: 0.95rem; }

        /* ── NO ACCESS BOX ── */
        .tr-no-access-box { text-align: center; padding: 1.5rem; background: #f8fafc; border-radius: 12px; border: 1px dashed var(--tr-border); color: var(--tr-text-muted); }
        .tr-no-access-box svg { margin-bottom: 0.75rem; color: #94a3b8; }
        .tr-no-access-box h3 { font-size: 1.1rem; font-weight: 800; margin: 0 0 0.5rem 0; color: var(--tr-text-main); }
        .tr-no-access-box p { font-size: 0.85rem; line-height: 1.5; margin: 0; }

        /* ── ALERTS ── */
        .tr-alert-stack { width: 100%; display: flex; flex-direction: column; gap: 0.5rem; }
        .tr-alert { padding: 1rem 1.25rem; border-radius: 10px; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.85rem; width: 100%; justify-content: center; }
        .tr-alert-success { background: #dcfce7; color: #065f46; border: 1px solid #a7f3d0; }
        .tr-alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        @media (max-width: 640px) {
            .tr-header { flex-direction: column; gap: 1rem; align-items: stretch; margin-bottom: 2rem;}
            .tr-header-actions .tr-btn { width: 100%; justify-content: center; }
            .tr-grid-2 { grid-template-columns: 1fr; gap: 1.25rem; }
            .tr-lock-header { padding: 2rem 1.5rem 1.5rem; }
            .tr-lock-body { padding: 1.5rem; }
        }
    </style>
    @endpush
</x-app-layout>