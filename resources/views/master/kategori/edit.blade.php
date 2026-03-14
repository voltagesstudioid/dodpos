<x-app-layout>
    <x-slot name="header">Edit Kategori</x-slot>
    <div class="page-container animate-in" style="max-width:640px;">
        <div class="ph-breadcrumb">
            <a href="{{ route('master.kategori') }}">Kategori</a>
            <span class="ph-breadcrumb-sep">›</span>
            <span>Edit: {{ $kategori->name }}</span>
        </div>
        <form method="POST" action="{{ route('master.kategori.update', $kategori) }}">
            @csrf @method('PUT')
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon indigo">🗂️</div>
                    <div>
                        <div class="form-card-title">Edit Kategori</div>
                        <div class="form-card-subtitle">Perbarui informasi kategori</div>
                    </div>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label class="form-label">Nama Kategori <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $kategori->name) }}"
                            class="form-input @error('name') input-error @enderror">
                        @error('name') <span class="form-error">⚠ {{ $message }}</span> @enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="form-input @error('description') input-error @enderror"
                            >{{ old('description', $kategori->description) }}</textarea>
                        @error('description') <span class="form-error">⚠ {{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="floating-bar">
                <span class="floating-bar-info">Mengedit: <strong>{{ $kategori->name }}</strong></span>
                <div class="floating-bar-actions">
                    <a href="{{ route('master.kategori') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💾 Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
