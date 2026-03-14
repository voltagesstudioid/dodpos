<x-app-layout>
    <x-slot name="header">Edit Satuan</x-slot>
    <div class="page-container animate-in" style="max-width:640px;">
        <div class="ph-breadcrumb">
            <a href="{{ route('master.satuan') }}">Satuan Barang</a>
            <span class="ph-breadcrumb-sep">›</span>
            <span>Edit: {{ $satuan->name }}</span>
        </div>
        <form method="POST" action="{{ route('master.satuan.update', $satuan) }}">
            @csrf @method('PUT')
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon amber">⚖️</div>
                    <div>
                        <div class="form-card-title">Edit Satuan</div>
                        <div class="form-card-subtitle">Perbarui informasi satuan ukuran</div>
                    </div>
                </div>
                <div class="form-card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Satuan <span class="required">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $satuan->name) }}"
                                class="form-input @error('name') input-error @enderror">
                            @error('name') <span class="form-error">⚠ {{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Singkatan <span class="required">*</span></label>
                            <input type="text" name="abbreviation" value="{{ old('abbreviation', $satuan->abbreviation) }}"
                                class="form-input @error('abbreviation') input-error @enderror">
                            @error('abbreviation') <span class="form-error">⚠ {{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="2" class="form-input"
                            >{{ old('description', $satuan->description) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="floating-bar">
                <span class="floating-bar-info">Mengedit: <strong>{{ $satuan->name }}</strong></span>
                <div class="floating-bar-actions">
                    <a href="{{ route('master.satuan') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💾 Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
