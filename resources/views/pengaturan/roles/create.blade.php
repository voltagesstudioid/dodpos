<x-app-layout>
    <x-slot name="header">Tambah Role</x-slot>

    <div class="page-container">
        @if(session('error'))   <div class="alert alert-danger">❌ {{ session('error') }}</div>   @endif

        <div class="panel" style="max-width:760px;margin:0 auto;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">+ Tambah Role</div>
                    <div class="panel-subtitle">Role master untuk pilihan akun dan menu</div>
                </div>
                <a href="{{ route('pengaturan.roles.index') }}" class="btn-secondary">Kembali</a>
            </div>

            <div class="panel-body">
                <form method="POST" action="{{ route('pengaturan.roles.store') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Key <span class="required">*</span></label>
                        <input name="key" value="{{ old('key') }}" class="form-input" placeholder="contoh: kasir, admin_keuangan" required>
                        <div class="form-hint">Huruf kecil, angka, dan underscore saja.</div>
                        @error('key') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Label <span class="required">*</span></label>
                        <input name="label" value="{{ old('label') }}" class="form-input" placeholder="contoh: Kasir" required>
                        @error('label') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-input" rows="3" placeholder="Opsional">{{ old('description') }}</textarea>
                        @error('description') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="flex-direction:row;align-items:center;gap:0.5rem;">
                        <input type="checkbox" name="active" value="1" {{ old('active', '1') ? 'checked' : '' }}>
                        <label class="form-label" style="margin:0;">Aktif</label>
                    </div>

                    <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                        <a href="{{ route('pengaturan.roles.index') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

