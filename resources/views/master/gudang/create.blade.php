<x-app-layout>
    <x-slot name="header">Tambah Gudang</x-slot>
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
                <div class="page-header-title">Tambah Gudang</div>
                <div class="page-header-subtitle">Buat gudang baru untuk pengelolaan stok dan lokasi.</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('master.gudang') }}" class="btn-secondary">← Kembali</a>
                <button type="submit" form="warehouseForm" class="btn-primary">💾 Simpan Gudang</button>
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
            <form id="warehouseForm" method="POST" action="{{ route('master.gudang.store') }}">
                @csrf

                <div class="warehouse-form-grid">
                    <div>
                        <div class="form-group">
                            <label class="form-label">Nama Gudang <span class="required">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-input @error('name') input-error @enderror" placeholder="Contoh: Gudang Utama" required>
                            @error('name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kode Gudang</label>
                            <input type="text" name="code" value="{{ old('code', $nextCode ?? '') }}" class="form-input" style="background-color: #f1f5f9; cursor: not-allowed; color: #64748b; font-weight: 600;" readonly>
                            <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 0.35rem;">Dibuat otomatis oleh sistem</div>
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="form-input @error('address') input-error @enderror" placeholder="Alamat gudang...">{{ old('address') }}</textarea>
                            @error('address') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label class="form-label">Penanggung Jawab (PIC)</label>
                            <input type="text" name="pic" value="{{ old('pic') }}" class="form-input @error('pic') input-error @enderror" placeholder="Nama PIC...">
                            @error('pic') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. Telepon Gudang</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-input @error('phone') input-error @enderror" placeholder="08xx...">
                            @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Keterangan / Deskripsi</label>
                            <textarea name="description" rows="2" class="form-input @error('description') input-error @enderror" placeholder="Opsional...">{{ old('description') }}</textarea>
                            @error('description') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="warehouse-toggle">
                            <input type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
                            <label for="active" style="font-size:0.875rem;font-weight:700;cursor:pointer;color:#0f172a;">
                                Gudang Aktif beroperasi
                            </label>
                        </div>
                    </div>
                </div>

                <div class="warehouse-actions">
                    <a href="{{ route('master.gudang') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💾 Simpan Gudang</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
