<x-app-layout>
    <x-slot name="header">Buat Jadwal Kunjungan</x-slot>

    <div class="page-container" style="max-width:700px;">
        <div style="margin-bottom:1rem;">
            <a href="{{ route('pasgar.jadwal.index') }}" class="btn-secondary btn-sm">← Kembali</a>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0;">
                <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">📅 Buat Jadwal Kunjungan Baru</h2>
                <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Rencanakan kunjungan anggota pasgar ke pelanggan</p>
            </div>

            <form method="POST" action="{{ route('pasgar.jadwal.store') }}" style="padding:1.5rem;">
                @csrf

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:1.25rem;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Anggota Pasgar <span style="color:#ef4444;">*</span></label>
                        <select name="pasgar_member_id" class="form-input {{ $errors->has('pasgar_member_id') ? 'input-error' : '' }}" required>
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}" {{ old('pasgar_member_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->user->name }} {{ $m->area ? '— '.$m->area : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('pasgar_member_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Kunjungan <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="scheduled_date" value="{{ old('scheduled_date', today()->format('Y-m-d')) }}" class="form-input {{ $errors->has('scheduled_date') ? 'input-error' : '' }}" required>
                        @error('scheduled_date')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Pelanggan <span style="color:#ef4444;">*</span></label>
                    <select name="customer_id" class="form-input {{ $errors->has('customer_id') ? 'input-error' : '' }}" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} {{ $c->phone ? '('.$c->phone.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" rows="3" placeholder="Tujuan kunjungan, produk yang akan ditawarkan, dll..." class="form-input {{ $errors->has('notes') ? 'input-error' : '' }}" style="resize:vertical;">{{ old('notes') }}</textarea>
                    @error('notes')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex; gap:0.75rem; padding-top:0.5rem; border-top:1px solid #f1f5f9; margin-top:0.5rem;">
                    <button type="submit" class="btn-primary">📅 Simpan Jadwal</button>
                    <a href="{{ route('pasgar.jadwal.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
