<x-app-layout>
    <x-slot name="header">Tambah Satuan</x-slot>
    <div class="page-container animate-in" style="max-width:640px;">
        <div class="ph-breadcrumb">
            <a href="{{ route('master.satuan') }}">Satuan Barang</a>
            <span class="ph-breadcrumb-sep">›</span>
            <span>Tambah Satuan</span>
        </div>
        <form method="POST" action="{{ route('master.satuan.store') }}">
            @csrf
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon amber">⚖️</div>
                    <div>
                        <div class="form-card-title">Informasi Satuan</div>
                        <div class="form-card-subtitle">Isi detail satuan baru (pcs, dus, karton, dll)</div>
                    </div>
                </div>
                <div class="form-card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Satuan <span class="required">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-input @error('name') input-error @enderror"
                                placeholder="Contoh: Pieces, Dus, Karton">
                            @error('name') <span class="form-error">⚠ {{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Singkatan <span class="required">*</span></label>
                            <input type="text" name="abbreviation" value="{{ old('abbreviation') }}"
                                class="form-input @error('abbreviation') input-error @enderror"
                                placeholder="Contoh: pcs, dus, krt">
                            @error('abbreviation') <span class="form-error">⚠ {{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="2" class="form-input"
                            placeholder="Keterangan tambahan...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="floating-bar">
                <span class="floating-bar-info">💡 Satuan digunakan untuk unit ukuran produk</span>
                <div class="floating-bar-actions">
                    <a href="{{ route('master.satuan') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💾 Simpan Satuan</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
