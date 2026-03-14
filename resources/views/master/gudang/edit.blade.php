<x-app-layout>
    <x-slot name="header">Edit Gudang</x-slot>
    <style>
        .warehouse-form-grid { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem; }
        .warehouse-actions { display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1.5rem; padding-top:1.25rem; border-top:1px solid #e2e8f0; flex-wrap:wrap; }
        .warehouse-toggle { display:flex; align-items:center; gap:0.75rem; padding:0.9rem 1rem; border:1px solid #e2e8f0; border-radius:14px; background:#f8fafc; }
        .warehouse-toggle input { width:1.15rem; height:1.15rem; border-radius:0.25rem; }
        @media (max-width: 820px) {
            .warehouse-form-grid { grid-template-columns:1fr; }
            .warehouse-card { padding:1.25rem !important; }
        }
    </style>

    <div class="page-container" style="max-width:980px;">
        <div class="page-header">
            <div>
                <div class="page-header-title">Edit Gudang</div>
                <div class="page-header-subtitle">Perbarui informasi gudang untuk pengelolaan stok.</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('master.gudang') }}" class="btn-secondary">← Kembali</a>
                <button type="submit" form="warehouseEditForm" class="btn-primary">💾 Simpan Perubahan</button>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" role="alert" style="margin-bottom:1rem;">
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

        <div class="card warehouse-card" style="padding:1.75rem;">
            <form id="warehouseEditForm" method="POST" action="{{ route('master.gudang.update', $gudang) }}">
                @csrf @method('PUT')

                <div class="warehouse-form-grid">
                    <div>
                        <div class="form-group">
                            <label class="form-label">Nama Gudang <span class="required">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $gudang->name) }}" class="form-input @error('name') input-error @enderror" required>
                            @error('name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kode Gudang</label>
                            <input type="text" name="code" value="{{ old('code', $gudang->code) }}" class="form-input @error('code') input-error @enderror">
                            @error('code') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="form-input @error('address') input-error @enderror">{{ old('address', $gudang->address) }}</textarea>
                            @error('address') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label class="form-label">Penanggung Jawab (PIC)</label>
                            <input type="text" name="pic" value="{{ old('pic', $gudang->pic) }}" class="form-input @error('pic') input-error @enderror">
                            @error('pic') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. Telepon Gudang</label>
                            <input type="text" name="phone" value="{{ old('phone', $gudang->phone) }}" class="form-input @error('phone') input-error @enderror">
                            @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Keterangan / Deskripsi</label>
                            <textarea name="description" rows="2" class="form-input @error('description') input-error @enderror">{{ old('description', $gudang->description) }}</textarea>
                            @error('description') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="warehouse-toggle">
                            <input type="checkbox" name="active" id="active" value="1" {{ old('active', $gudang->active) ? 'checked' : '' }}>
                            <label for="active" style="font-size:0.875rem;font-weight:700;cursor:pointer;color:#0f172a;">
                                Gudang Aktif beroperasi
                            </label>
                        </div>
                    </div>
                </div>

                <div class="warehouse-actions">
                    <a href="{{ route('master.gudang') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
