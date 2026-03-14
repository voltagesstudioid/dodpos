<x-app-layout>
    <x-slot name="header">Edit Anggota Pasgar</x-slot>

    <div class="page-container" style="max-width:700px;">
        <div style="margin-bottom:1rem;">
            <a href="{{ route('pasgar.anggota.index') }}" class="btn-secondary btn-sm">← Kembali</a>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0;">
                <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">✏️ Edit Anggota: {{ $anggota->user->name }}</h2>
                <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Perbarui data anggota tim lapangan</p>
            </div>

            <form method="POST" action="{{ route('pasgar.anggota.update', $anggota) }}" style="padding:1.5rem;">
                @csrf @method('PUT')

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:1.25rem;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Pengguna</label>
                    <input type="text" value="{{ $anggota->user->name }} ({{ $anggota->user->email }})" class="form-input" disabled style="background:#f1f5f9; color:#64748b;">
                    <div style="font-size:0.75rem; color:#94a3b8; margin-top:0.25rem;">Pengguna tidak dapat diubah setelah dibuat.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kendaraan / Motor</label>
                    <select name="vehicle_id" class="form-input {{ $errors->has('vehicle_id') ? 'input-error' : '' }}">
                        <option value="">-- Tidak Ada --</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" {{ old('vehicle_id', $anggota->vehicle_id) == $v->id ? 'selected' : '' }}>
                                {{ $v->license_plate }} — {{ $v->warehouse?->name ?? 'Tanpa Gudang' }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Area / Wilayah Tugas</label>
                    <input type="text" name="area" value="{{ old('area', $anggota->area) }}" placeholder="Contoh: Kec. Ciawi, Bogor Selatan..." class="form-input {{ $errors->has('area') ? 'input-error' : '' }}">
                    @error('area')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" rows="3" placeholder="Catatan tambahan..." class="form-input {{ $errors->has('notes') ? 'input-error' : '' }}" style="resize:vertical;">{{ old('notes', $anggota->notes) }}</textarea>
                    @error('notes')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                        <input type="checkbox" name="active" value="1" {{ old('active', $anggota->active) ? 'checked' : '' }} style="width:16px; height:16px; accent-color:#4f46e5;">
                        <span class="form-label" style="margin:0;">Anggota Aktif</span>
                    </label>
                </div>

                <div style="display:flex; gap:0.75rem; padding-top:0.5rem; border-top:1px solid #f1f5f9; margin-top:0.5rem;">
                    <button type="submit" class="btn-primary">💾 Simpan Perubahan</button>
                    <a href="{{ route('pasgar.anggota.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
