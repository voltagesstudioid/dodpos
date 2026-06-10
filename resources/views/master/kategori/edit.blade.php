<x-app-layout>
    <x-slot name="header">Edit Kategori</x-slot>
    <style>
        .kg-form-page{max-width:640px;margin:0 auto;padding:1.5rem;}
        .kg-breadcrumb{display:flex;align-items:center;gap:0.5rem;font-size:0.8125rem;color:#94a3b8;margin-bottom:1.25rem;}
        .kg-breadcrumb a{color:#6366f1;text-decoration:none;font-weight:500;}
        .kg-breadcrumb a:hover{text-decoration:underline;}
        .kg-form-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;}
        .kg-form-header{display:flex;align-items:center;gap:0.875rem;padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;}
        .kg-form-icon{width:42px;height:42px;border-radius:11px;background:#eef2ff;color:#6366f1;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .kg-form-title{font-size:1rem;font-weight:600;color:#1e293b;margin:0;}
        .kg-form-sub{font-size:0.75rem;color:#94a3b8;margin:0.125rem 0 0;}
        .kg-form-body{padding:1.5rem;}
        .kg-field{margin-bottom:1.125rem;}
        .kg-field:last-child{margin-bottom:0;}
        .kg-label{display:block;font-size:0.8125rem;font-weight:600;color:#334155;margin-bottom:0.375rem;}
        .kg-label .req{color:#e11d48;}
        .kg-input{width:100%;height:40px;border:1px solid #e2e8f0;border-radius:8px;padding:0 0.75rem;font-size:0.8125rem;outline:none;transition:border-color .2s;box-sizing:border-box;}
        .kg-input:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1);}
        .kg-input.error{border-color:#e11d48;}
        .kg-textarea{width:100%;border:1px solid #e2e8f0;border-radius:8px;padding:0.625rem 0.75rem;font-size:0.8125rem;outline:none;resize:vertical;transition:border-color .2s;box-sizing:border-box;font-family:inherit;}
        .kg-textarea:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1);}
        .kg-error{display:block;font-size:0.75rem;color:#e11d48;margin-top:0.25rem;}
        .kg-footer{display:flex;justify-content:space-between;align-items:center;padding:1rem 1.5rem;border-top:1px solid #f1f5f9;background:#f8fafc;}
        .kg-footer-hint{font-size:0.75rem;color:#94a3b8;display:flex;align-items:center;gap:0.375rem;}
        .kg-footer-actions{display:flex;gap:0.5rem;}
    </style>

    <div class="kg-form-page">
        <div class="kg-breadcrumb">
            <a href="{{ route('master.kategori') }}">Kategori</a>
            <span>›</span>
            <span>Edit: {{ $kategori->name }}</span>
        </div>

        <form method="POST" action="{{ route('master.kategori.update', $kategori) }}">
            @csrf @method('PUT')
            <div class="kg-form-card">
                <div class="kg-form-header">
                    <div class="kg-form-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <div>
                        <div class="kg-form-title">Edit Kategori</div>
                        <div class="kg-form-sub">Perbarui informasi kategori: <strong>{{ $kategori->name }}</strong></div>
                    </div>
                </div>

                <div class="kg-form-body">
                    <div class="kg-field">
                        <label class="kg-label">Nama Kategori <span class="req">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $kategori->name) }}"
                            class="kg-input @error('name') error @enderror">
                        @error('name') <span class="kg-error">⚠ {{ $message }}</span> @enderror
                    </div>
                    <div class="kg-field">
                        <label class="kg-label">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="kg-textarea @error('description') error @enderror">{{ old('description', $kategori->description) }}</textarea>
                        @error('description') <span class="kg-error">⚠ {{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="kg-footer">
                    <div class="kg-footer-hint">
                        Mengedit: <strong style="color:#334155;">{{ $kategori->name }}</strong>
                    </div>
                    <div class="kg-footer-actions">
                        <a href="{{ route('master.kategori') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary">💾 Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
