<x-app-layout>
    <x-slot name="header">Edit Kendaraan</x-slot>
    <div class="page-container" style="max-width:800px;">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.5rem;">
            <a href="{{ route('operasional.kendaraan.index') }}" style="color:#64748b; text-decoration:none;">← Kembali</a>
            <span style="color:#cbd5e1;">/</span>
            <h1 style="font-size:1.25rem; font-weight:700; color:#1e293b; margin:0;">Edit Kendaraan</h1>
        </div>

        <div class="card" style="padding:1.75rem;">
            <form action="{{ route('operasional.kendaraan.update', $kendaraan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Plat Nomor <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="license_plate" class="form-input @error('license_plate') input-error @enderror" value="{{ old('license_plate', $kendaraan->license_plate) }}" required autofocus style="text-transform: uppercase;">
                    @error('license_plate') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis / Tipe Kendaraan</label>
                    <input type="text" name="type" class="form-input @error('type') input-error @enderror" value="{{ old('type', $kendaraan->type) }}">
                    @error('type') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan / Deskripsi</label>
                    <textarea name="description" class="form-input" rows="3">{{ old('description', $kendaraan->description) }}</textarea>
                    @error('description') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div style="margin-top:2rem; padding-top:1.25rem; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
