<x-app-layout>
    <x-slot name="header">Edit Pelanggan Minyak</x-slot>
    <div class="page-container animate-in" style="max-width:700px;">
        <div class="ph-breadcrumb">
            <a href="{{ route('minyak.pelanggan.index') }}">Pelanggan Minyak</a>
            <span class="ph-breadcrumb-sep">›</span>
            <span>Edit</span>
        </div>
        @if($errors->any())
            <div class="alert alert-danger">❌ {{ $errors->first() }}</div>
        @endif
        <form action="{{ route('minyak.pelanggan.update', $pelanggan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon blue">👤</div>
                    <div>
                        <div class="form-card-title">Identitas Pelanggan/Warung</div>
                        <div class="form-card-subtitle">Ubah data pelanggan minyak</div>
                    </div>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label class="form-label">Nama Warung / Pelanggan <span class="required">*</span></label>
                        <input type="text" name="name" class="form-input @error('name') input-error @enderror"
                            value="{{ old('name', $pelanggan->name) }}" placeholder="Contoh: Toko Sembako Budi">
                        @error('name')<div class="form-error">⚠ {{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" class="form-input" value="{{ old('phone', $pelanggan->phone) }}" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email (Opsional)</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $pelanggan->email) }}" placeholder="email@contoh.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-input" rows="3" placeholder="Jl. ...">{{ old('address', $pelanggan->address) }}</textarea>
                    </div>
                    
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="notes" class="form-input" rows="2" placeholder="Posisi patokan, jam buka, dll...">{{ old('notes', $pelanggan->notes) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="floating-bar">
                <span class="floating-bar-info">Perubahan ini akan otomatis terupdate di aplikasi Android sales.</span>
                <div class="floating-bar-actions">
                    <a href="{{ route('minyak.pelanggan.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">✓ Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
