<x-app-layout>
    <x-slot name="header">Data Pelanggan (Minyak)</x-slot>

    <div class="page-container animate-in" style="max-width: 980px; margin: 0 auto;">
        <div class="page-header">
            <div>
                <div class="page-header-title">Tambah Pelanggan Minyak</div>
                <div class="page-header-subtitle">Data pelanggan ini otomatis muncul di Rute Kunjungan aplikasi Android</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('minyak.pelanggan.index') }}" class="btn-secondary">← Kembali</a>
            </div>
        </div>

        <div class="ph-breadcrumb">
            <a href="{{ route('minyak.pelanggan.index') }}">Pelanggan Minyak</a>
            <span class="ph-breadcrumb-sep">›</span>
            <span>Tambah</span>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <div style="font-weight:900;margin-bottom:0.25rem;">Ada input yang belum valid</div>
                <ul style="margin:0;padding-left:1.1rem;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('minyak.pelanggan.store') }}" method="POST">
            @csrf
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Identitas Pelanggan / Warung</div>
                        <div class="panel-subtitle">Lengkapi nama, kontak, dan alamat untuk rute kunjungan</div>
                    </div>
                </div>
                <div class="panel-body" style="padding: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label">Nama Warung / Pelanggan <span class="required">*</span></label>
                        <input
                            type="text"
                            name="name"
                            class="form-input @error('name') input-error @enderror"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Toko Sembako Budi"
                            required
                        >
                        <div class="form-hint">Gunakan nama yang mudah dikenali sales saat kunjungan.</div>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" class="form-input @error('phone') input-error @enderror" value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx">
                            @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Email (Opsional)</label>
                            <input type="email" name="email" class="form-input @error('email') input-error @enderror" value="{{ old('email') }}" placeholder="email@contoh.com">
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-input @error('address') input-error @enderror" rows="3" placeholder="Jl. ...">{{ old('address') }}</textarea>
                        <div class="form-hint">Tulis patokan agar sales lebih mudah menemukan lokasi.</div>
                        @error('address')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="notes" class="form-input @error('notes') input-error @enderror" rows="2" placeholder="Posisi patokan, jam buka, dll...">{{ old('notes') }}</textarea>
                        @error('notes')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="floating-bar">
                <span class="floating-bar-info">Pastikan data sudah benar sebelum disimpan.</span>
                <div class="floating-bar-actions">
                    <a href="{{ route('minyak.pelanggan.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">✓ Simpan Pelanggan</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
