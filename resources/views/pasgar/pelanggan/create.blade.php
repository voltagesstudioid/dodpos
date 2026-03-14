<x-app-layout>
    <x-slot name="header">Tambah Pelanggan Pasgar</x-slot>

    <div class="page-container animate-in" style="max-width: 980px;">
        <div class="page-header">
            <div>
                <div class="page-header-title">Tambah Pelanggan Pasgar</div>
                <div class="page-header-subtitle">Lengkapi identitas toko agar bisa dipilih saat Penjualan Kanvas</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('pasgar.pelanggan.index') }}" class="btn-secondary">← Kembali</a>
                <button type="submit" form="pasgarCustomerForm" class="btn-primary">💾 Simpan</button>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <div>❌ Periksa input Anda:</div>
                <div style="margin-top:0.35rem;">
                    <ul style="margin:0;padding-left:1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form id="pasgarCustomerForm" action="{{ route('pasgar.pelanggan.store') }}" method="POST">
            @csrf

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Identitas Pelanggan/Toko</div>
                        <div class="panel-subtitle">Data utama pelanggan Pasukan Garuda</div>
                    </div>
                    <span class="badge badge-indigo">Kategori: Pasgar</span>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="form-label">Nama Toko / Pelanggan <span class="required">*</span></label>
                        <input type="text" name="name" class="form-input @error('name') input-error @enderror"
                            value="{{ old('name') }}" placeholder="Contoh: Toko Maju Jaya" required>
                        @error('name')<div class="form-error">⚠ {{ $message }}</div>@enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email (Opsional)</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="email@contoh.com">
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-top: 1rem;">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Alamat & Catatan</div>
                        <div class="panel-subtitle">Opsional, tapi membantu untuk tim lapangan</div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-input" rows="3" placeholder="Contoh: Jl. ...">{{ old('address') }}</textarea>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="notes" class="form-input" rows="2" placeholder="Contoh: Jam buka, titik patokan, dsb.">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="floating-bar">
                <span class="floating-bar-info">Pelanggan ini akan muncul saat Penjualan Kanvas.</span>
                <div class="floating-bar-actions">
                    <a href="{{ route('pasgar.pelanggan.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💾 Simpan Pelanggan</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
