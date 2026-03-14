<x-app-layout>
    <x-slot name="header">Tambah Supplier</x-slot>
    <style>
        .supplier-grid { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem; }
        .supplier-card { padding:1.5rem; }
        .supplier-card-title { display:flex; align-items:center; gap:0.5rem; font-size:1rem; font-weight:900; color:#0f172a; margin:0; }
        .supplier-card-sub { margin-top:0.25rem; font-size:0.85rem; color:#64748b; }
        .supplier-card-head { display:flex; gap:0.75rem; align-items:flex-start; padding-bottom:1rem; border-bottom:1px solid #f1f5f9; margin-bottom:1rem; }
        .supplier-ico { width:42px; height:42px; display:flex; align-items:center; justify-content:center; border-radius:12px; border:1px solid #e2e8f0; font-size:1.1rem; flex-shrink:0; }
        .supplier-actions { display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1.5rem; padding-top:1.25rem; border-top:1px solid #e2e8f0; flex-wrap:wrap; }
        .supplier-toggle { display:flex; align-items:center; gap:0.75rem; padding:0.9rem 1rem; border:1px solid #e2e8f0; border-radius:14px; background:#f8fafc; }
        .supplier-toggle input { width:1.15rem; height:1.15rem; border-radius:0.25rem; }
        @media (max-width: 980px) {
            .supplier-grid { grid-template-columns:1fr; }
            .supplier-card { padding:1.25rem; }
        }
        @media (min-width: 1200px) {
            .supplier-grid { grid-template-columns:repeat(3, minmax(0, 1fr)); }
        }
    </style>

    <div class="page-container animate-in" style="max-width:1100px;">
        <div class="page-header">
            <div>
                <div class="page-header-title">Tambah Supplier</div>
                <div class="page-header-subtitle">Supplier digunakan pada pembuatan Purchase Order.</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('master.supplier') }}" class="btn-secondary">← Kembali</a>
                <button type="submit" form="supplierForm" class="btn-primary">💾 Simpan Supplier</button>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" role="alert" style="margin-bottom:1rem;">
                <div>❌ Periksa input Anda:</div>
                <div style="margin-top:0.35rem;">
                    <ul style="margin:0;padding-left:1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form id="supplierForm" method="POST" action="{{ route('master.supplier.store') }}">
            @csrf

            <div class="supplier-grid">
                <div class="card supplier-card">
                    <div class="supplier-card-head">
                        <div class="supplier-ico" style="background:#f0fdfa;border-color:#99f6e4;">🏢</div>
                        <div style="min-width:0;">
                            <div class="supplier-card-title">Info Perusahaan</div>
                            <div class="supplier-card-sub">Data pemasok / vendor</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kode Supplier</label>
                        <input type="text" name="code" value="{{ old('code', $nextCode ?? '') }}" class="form-input" style="background-color: #f1f5f9; cursor: not-allowed; color: #64748b; font-weight: 600;" readonly>
                        <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 0.35rem;">Dibuat otomatis oleh sistem</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Supplier / Perusahaan <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="form-input @error('name') input-error @enderror"
                            placeholder="PT / CV / UD ..." required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kota / Wilayah</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            class="form-input @error('city') input-error @enderror" placeholder="Bandung, Jakarta...">
                        @error('city') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="form-input @error('address') input-error @enderror"
                            placeholder="Jl. ...">{{ old('address') }}</textarea>
                        @error('address') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="supplier-toggle">
                        <input type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
                        <label for="active" style="font-size:0.875rem;font-weight:800;cursor:pointer;color:#0f172a;">
                            Supplier Aktif
                        </label>
                    </div>
                </div>

                <div class="card supplier-card">
                    <div class="supplier-card-head">
                        <div class="supplier-ico" style="background:#eef2ff;border-color:#c7d2fe;">📞</div>
                        <div style="min-width:0;">
                            <div class="supplier-card-title">Kontak Preferensi</div>
                            <div class="supplier-card-sub">Informasi kontak penanggung jawab</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kontak Person (PIC)</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                            class="form-input @error('contact_person') input-error @enderror" placeholder="Nama PIC...">
                        @error('contact_person') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. Telepon / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="form-input @error('phone') input-error @enderror" placeholder="08xx-xxxx-xxxx">
                        @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-input @error('email') input-error @enderror" placeholder="nama@email.com">
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="notes" rows="3" class="form-input @error('notes') input-error @enderror"
                            placeholder="Catatan khusus supplier ini...">{{ old('notes') }}</textarea>
                        @error('notes') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="card supplier-card">
                    <div class="supplier-card-head">
                        <div class="supplier-ico" style="background:#fefce8;border-color:#fef08a;">💳</div>
                        <div style="min-width:0;">
                            <div class="supplier-card-title">Penagihan & Bank</div>
                            <div class="supplier-card-sub">Info NPWP, Rekening, dan Termin</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor NPWP</label>
                        <input type="text" name="npwp" value="{{ old('npwp') }}"
                            class="form-input @error('npwp') input-error @enderror" placeholder="00.000.000.0-000.000">
                        @error('npwp') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1.5fr; gap:0.75rem;">
                        <div class="form-group">
                            <label class="form-label">Nama Bank</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                                class="form-input @error('bank_name') input-error @enderror" placeholder="BCA/Mandiri...">
                            @error('bank_name') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Rekening</label>
                            <input type="text" name="bank_account" value="{{ old('bank_account') }}"
                                class="form-input @error('bank_account') input-error @enderror" placeholder="1234567890">
                            @error('bank_account') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Atas Nama Rekening</label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}"
                            class="form-input @error('bank_account_name') input-error @enderror" placeholder="A.N ...">
                        @error('bank_account_name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Termin Pembayaran (TOP) - Hari</label>
                        <input type="number" name="term_days" value="{{ old('term_days', 0) }}" min="0"
                            class="form-input @error('term_days') input-error @enderror" placeholder="0 = Cash/COD">
                        @error('term_days') <div class="form-error">{{ $message }}</div> @enderror
                        <p style="font-size:0.75rem; color:#64748b; margin-top:0.25rem;">Isi 0 jika Cash/COD. Isi 30 untuk Tempo Sebulan.</p>
                    </div>
                </div>
            </div>

            <div class="supplier-actions">
                <a href="{{ route('master.supplier') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">💾 Simpan Supplier</button>
            </div>
        </form>
    </div>
</x-app-layout>
