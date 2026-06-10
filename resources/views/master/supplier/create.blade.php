<x-app-layout>
    <x-slot name="header">Tambah Supplier</x-slot>
    <style>
        .sp-cr{max-width:1100px;margin:0 auto;padding:1.5rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;}
        .sp-bc{display:flex;align-items:center;gap:0.5rem;font-size:0.8125rem;color:#94a3b8;margin-bottom:1.25rem;}
        .sp-bc a{color:#4f46e5;text-decoration:none;font-weight:600;} .sp-bc a:hover{text-decoration:underline;}
        .sp-top{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;}
        .sp-top-left h1{font-size:1.5rem;font-weight:800;color:#0f172a;margin:0;}
        .sp-top-left p{font-size:0.8125rem;color:#64748b;margin:0.25rem 0 0;}
        .sp-top-actions{display:flex;gap:0.5rem;}

        /* Cards */
        .sp-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;margin-bottom:1.25rem;}
        .sp-card-head{display:flex;align-items:center;gap:0.875rem;padding:1.125rem 1.5rem;border-bottom:1px solid #f1f5f9;background:#fafbfc;}
        .sp-card-icon{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .sp-card-icon.teal{background:#f0fdfa;color:#0d9488;}
        .sp-card-icon.blue{background:#eff6ff;color:#2563eb;}
        .sp-card-icon.amber{background:#fffbeb;color:#d97706;}
        .sp-card-title{font-size:0.9375rem;font-weight:700;color:#1e293b;margin:0;}
        .sp-card-desc{font-size:0.75rem;color:#94a3b8;margin:0.125rem 0 0;}
        .sp-card-body{padding:1.5rem;}

        /* Form */
        .sp-field{margin-bottom:1.125rem;} .sp-field:last-child{margin-bottom:0;}
        .sp-grid{display:grid;gap:1.25rem;} .sp-grid-2{grid-template-columns:1fr 1fr;} .sp-grid-3{grid-template-columns:1fr 1fr 1fr;}
        .sp-label{display:block;font-size:0.75rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.375rem;}
        .sp-req{color:#e11d48;}
        .sp-input,.sp-textarea{width:100%;height:42px;border:1.5px solid #e2e8f0;border-radius:10px;padding:0 0.875rem;font-size:0.875rem;outline:none;transition:all .2s;box-sizing:border-box;font-family:inherit;background:#fff;color:#1e293b;font-weight:500;}
        .sp-input:focus,.sp-textarea:focus{border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.1);}
        .sp-input.valid{border-color:#10b981;background:#f0fdf4;}
        .sp-input.invalid,.sp-textarea.invalid{border-color:#e11d48;background:#fef2f2;}
        .sp-input.auto{background:#f8fafc;color:#64748b;font-weight:700;font-family:ui-monospace,SFMono-Regular,monospace;cursor:not-allowed;}
        .sp-textarea{height:auto;padding:0.625rem 0.875rem;resize:vertical;min-height:72px;}
        .sp-err{font-size:0.6875rem;color:#e11d48;margin-top:0.25rem;font-weight:600;}
        .sp-hint{font-size:0.6875rem;color:#94a3b8;margin-top:0.25rem;display:flex;align-items:center;gap:0.25rem;}
        .sp-badge{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:#eef2ff;color:#4f46e5;font-size:0.625rem;font-weight:800;padding:0.2rem 0.5rem;border-radius:5px;text-transform:uppercase;letter-spacing:0.05em;pointer-events:none;}

        /* Toggle switch */
        .sp-toggle{display:flex;align-items:center;gap:0.75rem;padding:0.875rem 1.125rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;cursor:pointer;transition:.2s;user-select:none;}
        .sp-toggle:hover{background:#dcfce7;}
        .sp-toggle.off{background:#fef2f2;border-color:#fecaca;}
        .sp-toggle.off:hover{background:#fee2e2;}
        .sp-toggle-sw{position:relative;width:40px;height:22px;background:#cbd5e1;border-radius:11px;transition:.25s;flex-shrink:0;}
        .sp-toggle-sw::after{content:'';position:absolute;top:2px;left:2px;width:18px;height:18px;background:#fff;border-radius:50%;transition:.25s;box-shadow:0 1px 3px rgba(0,0,0,.15);}
        .sp-toggle.on .sp-toggle-sw{background:#10b981;}
        .sp-toggle.on .sp-toggle-sw::after{left:20px;}
        .sp-toggle-txt{font-size:0.8125rem;font-weight:700;color:#1e293b;}
        .sp-toggle-sub{font-size:0.6875rem;color:#64748b;font-weight:500;}

        /* Alert */
        .sp-alert{padding:0.875rem 1.125rem;border-radius:10px;display:flex;align-items:flex-start;gap:0.625rem;font-size:0.8125rem;font-weight:600;margin-bottom:1rem;}
        .sp-alert-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
        .sp-alert ul{margin:0.375rem 0 0;padding-left:1.25rem;font-weight:500;}

        /* Footer */
        .sp-footer{position:sticky;bottom:1rem;margin-top:1.5rem;z-index:50;}
        .sp-footer-inner{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:0.875rem 1.25rem;background:rgba(255,255,255,.95);backdrop-filter:blur(12px);border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.08);}
        .sp-footer-info{font-size:0.8125rem;color:#64748b;display:flex;align-items:center;gap:0.5rem;}
        .sp-footer-actions{display:flex;gap:0.5rem;}

        /* Buttons */
        .sp-btn{display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.625rem 1.125rem;border-radius:10px;font-size:0.8125rem;font-weight:700;cursor:pointer;transition:all .2s;border:1px solid transparent;text-decoration:none;white-space:nowrap;font-family:inherit;}
        .sp-btn-primary{background:#4f46e5;color:#fff;box-shadow:0 2px 8px rgba(79,70,229,.25);}
        .sp-btn-primary:hover{background:#4338ca;transform:translateY(-1px);}
        .sp-btn-outline{border-color:#e2e8f0;background:#fff;color:#475569;}
        .sp-btn-outline:hover{background:#f8fafc;border-color:#cbd5e1;}

        @media(max-width:640px){
            .sp-grid-2,.sp-grid-3{grid-template-columns:1fr;}
            .sp-top{flex-direction:column;}
            .sp-footer-inner{flex-direction:column;text-align:center;}
        }
    </style>

    <div class="sp-cr">
        {{-- Breadcrumb --}}
        <div class="sp-bc">
            <a href="{{ route('master.supplier') }}">Data Supplier</a>
            <span>›</span>
            <span>Tambah Supplier</span>
        </div>

        {{-- Header --}}
        <div class="sp-top">
            <div class="sp-top-left">
                <h1>Tambah Supplier</h1>
                <p>Supplier digunakan pada pembuatan Purchase Order.</p>
            </div>
            <div class="sp-top-actions">
                <a href="{{ route('master.supplier') }}" class="sp-btn sp-btn-outline">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Kembali
                </a>
                <button type="submit" form="supplierForm" class="sp-btn sp-btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Supplier
                </button>
            </div>
        </div>

        {{-- Alerts --}}
        @if($errors->any())
        <div class="sp-alert sp-alert-danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <div>
                <strong>Periksa kembali:</strong>
                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        </div>
        @endif

        <form id="supplierForm" method="POST" action="{{ route('master.supplier.store') }}" novalidate>
            @csrf

            {{-- CARD 1: INFO PERUSAHAAN --}}
            <div class="sp-card">
                <div class="sp-card-head">
                    <div class="sp-card-icon teal">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <div>
                        <div class="sp-card-title">Info Perusahaan</div>
                        <div class="sp-card-desc">Data pemasok / vendor</div>
                    </div>
                </div>
                <div class="sp-card-body">
                    <div class="sp-grid sp-grid-2">
                        <div class="sp-field">
                            <label class="sp-label">Kode Supplier</label>
                            <div style="position:relative;">
                                <input type="text" name="code" value="{{ old('code', $nextCode ?? '') }}" class="sp-input auto" readonly tabindex="-1">
                                <span class="sp-badge">AUTO</span>
                            </div>
                            <div class="sp-hint">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                Dibuat otomatis oleh sistem
                            </div>
                        </div>
                        <div class="sp-field">
                            <label class="sp-label">Nama Supplier / Perusahaan <span class="sp-req">*</span></label>
                            <input type="text" name="name" id="f-name" value="{{ old('name') }}" class="sp-input @error('name') invalid @enderror" placeholder="PT / CV / UD ..." required>
                            @error('name') <div class="sp-err">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="sp-field">
                        <label class="sp-label">Kota / Wilayah</label>
                        <input type="text" name="city" value="{{ old('city') }}" class="sp-input @error('city') invalid @enderror" placeholder="Bandung, Jakarta...">
                        @error('city') <div class="sp-err">{{ $message }}</div> @enderror
                    </div>
                    <div class="sp-field">
                        <label class="sp-label">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="sp-textarea @error('address') invalid @enderror" placeholder="Jl. ...">{{ old('address') }}</textarea>
                        @error('address') <div class="sp-err">{{ $message }}</div> @enderror
                    </div>

                    {{-- Active toggle --}}
                    <div class="sp-field" style="margin-top:0.5rem;">
                        <input type="hidden" name="active" id="active-hidden" value="{{ old('active', 1) ? '1' : '0' }}">
                        <div class="sp-toggle {{ old('active', 1) ? 'on' : 'off' }}" id="toggle-active" onclick="toggleActive()">
                            <div class="sp-toggle-sw"></div>
                            <div>
                                <div class="sp-toggle-txt">Supplier Aktif</div>
                                <div class="sp-toggle-sub">Supplier dapat dipilih saat membuat PO</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 2: KONTAK PREFERENSI --}}
            <div class="sp-card">
                <div class="sp-card-head">
                    <div class="sp-card-icon blue">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <div>
                        <div class="sp-card-title">Kontak Preferensi</div>
                        <div class="sp-card-desc">Informasi kontak penanggung jawab</div>
                    </div>
                </div>
                <div class="sp-card-body">
                    <div class="sp-field">
                        <label class="sp-label">Kontak Person (PIC)</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="sp-input @error('contact_person') invalid @enderror" placeholder="Nama PIC...">
                        @error('contact_person') <div class="sp-err">{{ $message }}</div> @enderror
                    </div>
                    <div class="sp-grid sp-grid-2">
                        <div class="sp-field">
                            <label class="sp-label">No. Telepon / WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="sp-input @error('phone') invalid @enderror" placeholder="08xx-xxxx-xxxx">
                            @error('phone') <div class="sp-err">{{ $message }}</div> @enderror
                        </div>
                        <div class="sp-field">
                            <label class="sp-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="sp-input @error('email') invalid @enderror" placeholder="nama@email.com">
                            @error('email') <div class="sp-err">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="sp-field">
                        <label class="sp-label">Catatan Tambahan</label>
                        <textarea name="notes" rows="3" class="sp-textarea @error('notes') invalid @enderror" placeholder="Catatan khusus supplier ini...">{{ old('notes') }}</textarea>
                        @error('notes') <div class="sp-err">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- CARD 3: PENAGIHAN & BANK --}}
            <div class="sp-card">
                <div class="sp-card-head">
                    <div class="sp-card-icon amber">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    <div>
                        <div class="sp-card-title">Penagihan & Bank</div>
                        <div class="sp-card-desc">Info NPWP, Rekening, dan Termin Pembayaran</div>
                    </div>
                </div>
                <div class="sp-card-body">
                    <div class="sp-field">
                        <label class="sp-label">Nomor NPWP</label>
                        <input type="text" name="npwp" id="f-npwp" value="{{ old('npwp') }}" class="sp-input @error('npwp') invalid @enderror" placeholder="00.000.000.0-000.000">
                        @error('npwp') <div class="sp-err">{{ $message }}</div> @enderror
                    </div>
                    <div class="sp-grid sp-grid-3">
                        <div class="sp-field">
                            <label class="sp-label">Nama Bank</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="sp-input @error('bank_name') invalid @enderror" placeholder="BCA / Mandiri...">
                            @error('bank_name') <div class="sp-err">{{ $message }}</div> @enderror
                        </div>
                        <div class="sp-field">
                            <label class="sp-label">No. Rekening</label>
                            <input type="text" name="bank_account" value="{{ old('bank_account') }}" class="sp-input @error('bank_account') invalid @enderror" placeholder="1234567890">
                            @error('bank_account') <div class="sp-err">{{ $message }}</div> @enderror
                        </div>
                        <div class="sp-field">
                            <label class="sp-label">Atas Nama Rekening</label>
                            <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}" class="sp-input @error('bank_account_name') invalid @enderror" placeholder="A.N ...">
                            @error('bank_account_name') <div class="sp-err">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="sp-field">
                        <label class="sp-label">Termin Pembayaran (TOP) - Hari</label>
                        <input type="number" name="term_days" value="{{ old('term_days', 0) }}" min="0" class="sp-input @error('term_days') invalid @enderror" placeholder="0">
                        @error('term_days') <div class="sp-err">{{ $message }}</div> @enderror
                        <div class="sp-hint">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            Isi <strong>0</strong> jika Cash/COD. Isi <strong>30</strong> untuk Tempo Sebulan.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sticky Footer --}}
            <div class="sp-footer">
                <div class="sp-footer-inner">
                    <div class="sp-footer-info">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Field bertanda <span class="sp-req" style="font-weight:800;">*</span> wajib diisi
                    </div>
                    <div class="sp-footer-actions">
                        <a href="{{ route('master.supplier') }}" class="sp-btn sp-btn-outline">Batal</a>
                        <button type="submit" class="sp-btn sp-btn-primary">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Supplier
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    function toggleActive() {
        const toggle = document.getElementById('toggle-active');
        const hidden = document.getElementById('active-hidden');
        const isOn = toggle.classList.contains('on');
        toggle.classList.toggle('on', !isOn);
        toggle.classList.toggle('off', isOn);
        hidden.value = isOn ? '0' : '1';
    }

    /* Live validation */
    function validateField(el) {
        if (el.hasAttribute('required') && !el.value.trim()) { el.classList.add('invalid'); el.classList.remove('valid'); return false; }
        if (el.value.trim()) { el.classList.add('valid'); el.classList.remove('invalid'); }
        else { el.classList.remove('valid','invalid'); }
        return true;
    }

    /* NPWP auto-format: 00.000.000.0-000.000 */
    document.addEventListener('DOMContentLoaded', () => {
        const npwp = document.getElementById('f-npwp');
        if (npwp) {
            npwp.addEventListener('input', function() {
                let v = this.value.replace(/[^\d]/g, '').substring(0, 15);
                let f = '';
                for (let i = 0; i < v.length; i++) {
                    if (i === 2 || i === 5 || i === 8) f += '.';
                    else if (i === 9) f += '-';
                    else if (i === 12) f += '.';
                    f += v[i];
                }
                this.value = f;
            });
        }

        /* Validate name on blur */
        const nameEl = document.getElementById('f-name');
        if (nameEl) {
            nameEl.addEventListener('blur', function() { validateField(this); });
            nameEl.addEventListener('input', function() {
                if (this.classList.contains('invalid')) validateField(this);
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
