<x-app-layout>
<x-slot name="header">SDM / HR</x-slot>

<div class="page-container">
    <div style="max-width: 720px;">
        <div class="page-header">
            <div>
                <div class="page-header-title">Tambah Karyawan</div>
                <div class="page-header-subtitle">Data karyawan bisa dibuat walau belum punya akun login</div>
            </div>
            <a href="{{ route('sdm.karyawan.index') }}" class="btn-secondary">← Kembali</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <div style="font-weight:900;margin-bottom:0.25rem;">Terdapat kesalahan</div>
                <ul style="margin:0;padding-left:1.1rem;">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Informasi Karyawan</div>
                    <div class="panel-subtitle">Nama, jabatan, dan kontak</div>
                </div>
            </div>
            <div class="panel-body" style="padding: 1.25rem;">
                <form method="POST" action="{{ route('sdm.karyawan.store') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Nama <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input @error('name') input-error @enderror" required autofocus>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">No. HP (opsional)</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-input @error('phone') input-error @enderror" placeholder="08xx">
                            @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Jabatan (opsional)</label>
                            <input type="text" name="position" value="{{ old('position') }}" class="form-input @error('position') input-error @enderror" placeholder="Kasir, Admin Gudang, dll">
                            @error('position') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-row" style="margin-top: 1rem;">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Gaji Pokok (bulanan)</label>
                            <input type="number" name="basic_salary" value="{{ old('basic_salary', 0) }}" class="form-input @error('basic_salary') input-error @enderror" placeholder="Rp 0" min="0">
                            @error('basic_salary') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Uang Kehadiran per Hari</label>
                            <input type="number" name="daily_allowance" value="{{ old('daily_allowance', 0) }}" class="form-input @error('daily_allowance') input-error @enderror" placeholder="Rp 0" min="0">
                            @error('daily_allowance') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-row" style="margin-top: 1rem;">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Tanggal Masuk (opsional)</label>
                            <input type="date" name="join_date" value="{{ old('join_date') }}" class="form-input @error('join_date') input-error @enderror">
                            @error('join_date') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group" style="margin:0;display:flex;align-items:flex-end;">
                            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                                <input type="checkbox" name="active" value="1" {{ old('active','1')=='1' ? 'checked':'' }} style="width:16px;height:16px;accent-color:#6366f1;cursor:pointer;">
                                <span class="form-label" style="margin:0;">Status Aktif</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 1rem;">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="notes" rows="2" class="form-input @error('notes') input-error @enderror" placeholder="Keterangan tambahan...">{{ old('notes') }}</textarea>
                        @error('notes') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:1.25rem;flex-wrap:wrap;">
                        <a href="{{ route('sdm.karyawan.index') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-app-layout>

