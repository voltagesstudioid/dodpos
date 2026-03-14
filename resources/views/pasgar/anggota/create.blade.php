<x-app-layout>
    <x-slot name="header">Tambah Anggota Pasgar</x-slot>

    <div class="page-container" style="max-width:700px;">
        <div style="margin-bottom:1rem;">
            <a href="{{ route('pasgar.anggota.index') }}" class="btn-secondary btn-sm">← Kembali</a>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0;">
                <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">👤 Tambah Anggota Baru</h2>
                <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Daftarkan pengguna dengan role Pasgar sebagai anggota tim lapangan</p>
            </div>

            <form method="POST" action="{{ route('pasgar.anggota.store') }}" style="padding:1.5rem;">
                @csrf

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:1.25rem;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Pengguna (Role: Pasgar) <span style="color:#ef4444;">*</span></label>
                    @if($users->isEmpty())
                        <div class="alert alert-danger" style="margin:0;">
                            ⚠️ Tidak ada pengguna dengan role <strong>pasgar</strong> yang tersedia. 
                            <a href="{{ route('pengguna.create') }}" style="color:#4f46e5; font-weight:600;">Buat pengguna baru</a> terlebih dahulu.
                        </div>
                    @else
                        <select name="user_id" class="form-input {{ $errors->has('user_id') ? 'input-error' : '' }}" required>
                            <option value="">-- Pilih Pengguna --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')<div class="form-error">{{ $message }}</div>@enderror
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Kendaraan / Motor</label>
                    <select name="vehicle_id" class="form-input {{ $errors->has('vehicle_id') ? 'input-error' : '' }}">
                        <option value="">-- Tidak Ada / Pilih Nanti --</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>
                                {{ $v->license_plate }} — {{ $v->warehouse?->name ?? 'Tanpa Gudang' }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')<div class="form-error">{{ $message }}</div>@enderror
                    <div style="font-size:0.75rem; color:#94a3b8; margin-top:0.25rem;">Kendaraan menentukan gudang virtual stok on-hand anggota ini.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Area / Wilayah Tugas</label>
                    <input type="text" name="area" value="{{ old('area') }}" placeholder="Contoh: Kec. Ciawi, Bogor Selatan..." class="form-input {{ $errors->has('area') ? 'input-error' : '' }}">
                    @error('area')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" rows="3" placeholder="Catatan tambahan..." class="form-input {{ $errors->has('notes') ? 'input-error' : '' }}" style="resize:vertical;">{{ old('notes') }}</textarea>
                    @error('notes')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                        <input type="checkbox" name="active" value="1" {{ old('active', '1') ? 'checked' : '' }} style="width:16px; height:16px; accent-color:#4f46e5;">
                        <span class="form-label" style="margin:0;">Anggota Aktif</span>
                    </label>
                </div>

                <div style="display:flex; gap:0.75rem; padding-top:0.5rem; border-top:1px solid #f1f5f9; margin-top:0.5rem;">
                    <button type="submit" class="btn-primary" {{ $users->isEmpty() ? 'disabled' : '' }}>💾 Simpan Anggota</button>
                    <a href="{{ route('pasgar.anggota.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
