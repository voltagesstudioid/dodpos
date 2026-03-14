<x-app-layout>
    <x-slot name="header">Tambah Kategori</x-slot>
    <div class="page-container animate-in" style="max-width:640px;">
        <div class="ph-breadcrumb">
            <a href="{{ route('master.kategori') }}">Kategori</a>
            <span class="ph-breadcrumb-sep">›</span>
            <span>Tambah Kategori</span>
        </div>
        <form method="POST" action="{{ route('master.kategori.store') }}">
            @csrf
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon indigo">🗂️</div>
                    <div>
                        <div class="form-card-title">Informasi Kategori</div>
                        <div class="form-card-subtitle">Isi detail kategori baru</div>
                    </div>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label class="form-label">Nama Kategori <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="form-input @error('name') input-error @enderror"
                            placeholder="Contoh: Makanan, Minuman, Elektronik...">
                        @error('name') <span class="form-error">⚠ {{ $message }}</span> @enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="form-input @error('description') input-error @enderror"
                            placeholder="Deskripsi singkat...">{{ old('description') }}</textarea>
                        @error('description') <span class="form-error">⚠ {{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="floating-bar">
                <span class="floating-bar-info">💡 Kategori digunakan untuk mengelompokkan produk</span>
                <div class="floating-bar-actions">
                    <a href="{{ route('master.kategori') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💾 Simpan Kategori</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
