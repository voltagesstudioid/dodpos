<x-app-layout>
    <x-slot name="header">Tambah Pelanggan</x-slot>
    <div class="page-container animate-in" style="max-width: 980px;">
        <div class="page-header">
            <div>
                <div class="page-header-title">Tambah Pelanggan</div>
                <div class="page-header-subtitle">Data pelanggan digunakan pada transaksi dan laporan piutang</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('pelanggan.index') }}" class="btn-secondary">← Kembali</a>
                <button type="submit" form="customerForm" class="btn-primary">💾 Simpan</button>
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

        <form id="customerForm" action="{{ route('pelanggan.store') }}" method="POST">
            @csrf

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Identitas Pelanggan</div>
                        <div class="panel-subtitle">Nama, kontak, dan alamat</div>
                    </div>
                    <span class="badge badge-indigo">Kategori: POS</span>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="form-label">Nama Pelanggan <span class="required">*</span></label>
                        <input type="text" name="name" class="form-input @error('name') input-error @enderror"
                            value="{{ old('name') }}" placeholder="Nama lengkap..." required>
                        @error('name')<div class="form-error">⚠ {{ $message }}</div>@enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="email@contoh.com">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-input" rows="2" placeholder="Jl. ...">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-top: 1rem;">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Pengaturan Kredit</div>
                        <div class="panel-subtitle">Batas maksimal hutang pelanggan yang diperbolehkan</div>
                    </div>
                    <span class="badge badge-gray">Opsional</span>
                </div>
                <div class="panel-body">
                    @if(auth()->user()->role === 'supervisor')
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Limit Kredit (Rp)</label>
                            <div class="form-prefix">
                                <span class="form-prefix-text">Rp</span>
                                <input type="number" name="credit_limit" class="form-input" value="{{ old('credit_limit', 0) }}" min="0">
                            </div>
                            <div class="form-hint">Isi 0 untuk tanpa batas kredit.</div>
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert" style="margin:0;">
                            ⚠️ Pengaturan limit kredit hanya bisa dilakukan oleh Supervisor.
                        </div>
                    @endif
                </div>
            </div>

            <div class="panel" style="margin-top: 1rem;">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Catatan</div>
                        <div class="panel-subtitle">Opsional, untuk informasi tambahan</div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-input" rows="2" placeholder="Catatan khusus...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="floating-bar">
                <span class="floating-bar-info">Pastikan nama & kontak benar sebelum disimpan.</span>
                <div class="floating-bar-actions">
                    <a href="{{ route('pelanggan.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💾 Simpan Pelanggan</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
