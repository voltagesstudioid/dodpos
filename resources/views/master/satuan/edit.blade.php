<x-app-layout>
    <x-slot name="header">Edit Satuan</x-slot>
    <style>
        .st-form-page{max-width:640px;margin:0 auto;padding:1.5rem;}
        .st-breadcrumb{display:flex;align-items:center;gap:0.5rem;font-size:0.8125rem;color:#94a3b8;margin-bottom:1.25rem;}
        .st-breadcrumb a{color:#d97706;text-decoration:none;font-weight:500;}
        .st-breadcrumb a:hover{text-decoration:underline;}
        .st-form-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;}
        .st-form-header{display:flex;align-items:center;gap:0.875rem;padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;}
        .st-form-icon{width:42px;height:42px;border-radius:11px;background:#fffbeb;color:#d97706;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .st-form-title{font-size:1rem;font-weight:600;color:#1e293b;margin:0;}
        .st-form-sub{font-size:0.75rem;color:#94a3b8;margin:0.125rem 0 0;}
        .st-form-body{padding:1.5rem;}
        .st-field{margin-bottom:1.125rem;}
        .st-field:last-child{margin-bottom:0;}
        .st-label{display:block;font-size:0.8125rem;font-weight:600;color:#334155;margin-bottom:0.375rem;}
        .st-label .req{color:#e11d48;}
        .st-input{width:100%;height:40px;border:1px solid #e2e8f0;border-radius:8px;padding:0 0.75rem;font-size:0.8125rem;outline:none;transition:border-color .2s;box-sizing:border-box;}
        .st-input:focus{border-color:#d97706;box-shadow:0 0 0 3px rgba(217,119,6,.1);}
        .st-input.error{border-color:#e11d48;}
        .st-textarea{width:100%;border:1px solid #e2e8f0;border-radius:8px;padding:0.625rem 0.75rem;font-size:0.8125rem;outline:none;resize:vertical;transition:border-color .2s;box-sizing:border-box;font-family:inherit;}
        .st-textarea:focus{border-color:#d97706;box-shadow:0 0 0 3px rgba(217,119,6,.1);}
        .st-error{display:block;font-size:0.75rem;color:#e11d48;margin-top:0.25rem;}
        .st-row{display:grid;grid-template-columns:1fr 140px;gap:1rem;}
        .st-hint{display:flex;align-items:center;gap:0.375rem;font-size:0.75rem;color:#94a3b8;margin-top:0.375rem;}
        .st-badge{display:inline-flex;align-items:center;gap:0.25rem;background:#fef3c7;color:#92400e;font-size:0.6875rem;font-weight:600;padding:0.125rem 0.5rem;border-radius:6px;margin-left:0.5rem;}
        .st-footer{display:flex;justify-content:space-between;align-items:center;padding:1rem 1.5rem;border-top:1px solid #f1f5f9;background:#f8fafc;}
        .st-footer-hint{font-size:0.75rem;color:#94a3b8;display:flex;align-items:center;gap:0.375rem;}
        .st-footer-actions{display:flex;gap:0.5rem;}
    </style>

    <div class="st-form-page">
        <div class="st-breadcrumb">
            <a href="{{ route('master.satuan') }}">Satuan Barang</a>
            <span>›</span>
            <span>Edit: {{ $satuan->name }}</span>
        </div>

        <form method="POST" action="{{ route('master.satuan.update', $satuan) }}">
            @csrf @method('PUT')
            <div class="st-form-card">
                <div class="st-form-header">
                    <div class="st-form-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <div>
                        <div class="st-form-title">Edit Satuan <span class="st-badge">{{ $satuan->abbreviation }}</span></div>
                        <div class="st-form-sub">Perbarui informasi satuan ukuran</div>
                    </div>
                </div>

                <div class="st-form-body">
                    <div class="st-field">
                        <div class="st-row">
                            <div>
                                <label class="st-label">Nama Satuan <span class="req">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $satuan->name) }}"
                                    class="st-input @error('name') error @enderror"
                                    placeholder="Contoh: Pieces, Dus, Karton">
                                @error('name') <span class="st-error">⚠ {{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="st-label">Singkatan <span class="req">*</span></label>
                                <input type="text" name="abbreviation" value="{{ old('abbreviation', $satuan->abbreviation) }}"
                                    class="st-input @error('abbreviation') error @enderror"
                                    placeholder="pcs">
                                @error('abbreviation') <span class="st-error">⚠ {{ $message }}</span> @enderror
                                <div class="st-hint">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                    Max 20 karakter
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="st-field">
                        <label class="st-label">Deskripsi</label>
                        <textarea name="description" rows="2"
                            class="st-textarea @error('description') error @enderror"
                            placeholder="Keterangan tambahan...">{{ old('description', $satuan->description) }}</textarea>
                        @error('description') <span class="st-error">⚠ {{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="st-footer">
                    <div class="st-footer-hint">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Mengedit: <strong style="color:#1e293b;margin-left:0.25rem;">{{ $satuan->name }}</strong>
                    </div>
                    <div class="st-footer-actions">
                        <a href="{{ route('master.satuan') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary">💾 Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
