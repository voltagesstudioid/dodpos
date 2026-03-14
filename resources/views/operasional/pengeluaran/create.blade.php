<x-app-layout>
    <x-slot name="header">Catat Pengeluaran Operasional</x-slot>

    <div class="page-container" style="max-width: 980px;">
        <div class="page-header">
            <div>
                <div class="page-header-title">💸 Catat Pengeluaran Operasional</div>
                <div class="page-header-subtitle">Rekam setiap pengeluaran kas untuk operasional toko</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('operasional.riwayat.index') }}" class="btn-secondary">📋 Riwayat</a>
                <button type="submit" form="expenseForm" class="btn-primary">💾 Simpan</button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">❌ {{ session('error') }}</div>
        @endif

        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Detail Pengeluaran</div>
                    <div class="panel-subtitle">Tanggal, kategori, nominal, kendaraan (opsional), dan keterangan</div>
                </div>
                <span class="badge badge-gray">Input baru</span>
            </div>
            <div class="panel-body">
            <form id="expenseForm" action="{{ route('operasional.pengeluaran.store') }}" method="POST">
                @csrf

                <!-- Row 1: Tanggal & Kategori -->
                <div class="form-row-3">
                    <div>
                        <label class="form-label">Tanggal Pengeluaran <span class="required">*</span></label>
                        <input type="date" name="date"
                            class="form-input @error('date') input-error @enderror"
                            value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="form-label">Kategori Pengeluaran <span class="required">*</span></label>
                        <select name="category_id" class="form-input @error('category_id') input-error @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('category_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="form-label">Nominal (Rp) <span class="required">*</span></label>
                        <input type="number" name="amount"
                            class="form-input @error('amount') input-error @enderror"
                            value="{{ old('amount') }}" required min="0" placeholder="0">
                        <div class="form-hint">Isi angka tanpa titik/koma. Contoh: 50000</div>
                        @error('amount') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row" style="margin-top: 1rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Kendaraan (Opsional)</label>
                        <select name="vehicle_id" class="form-input">
                            <option value="">Tidak Terkait Kendaraan</option>
                            @foreach($vehicles as $kendaraan)
                                <option value="{{ $kendaraan->id }}" {{ old('vehicle_id') == $kendaraan->id ? 'selected' : '' }}>
                                    {{ strtoupper($kendaraan->license_plate) }}@if($kendaraan->type) — {{ $kendaraan->type }} @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="form-hint">Isi hanya jika terkait bensin/servis/perbaikan kendaraan.</div>
                    </div>
                </div>

                <!-- Row 3: Keterangan -->
                <div style="margin-top: 1rem;">
                    <label class="form-label">Catatan Lengkap</label>
                    <textarea name="notes" class="form-input" rows="4"
                        placeholder="Contoh: Isi bensin truk pengiriman 10 liter, Token listrik kantor bulan Februari, Servis motor delivery...">{{ old('notes') }}</textarea>
                    @error('notes') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <!-- Actions -->
                <div style="display:flex; justify-content:flex-end; gap:0.75rem; flex-wrap:wrap; margin-top: 1.25rem;">
                    <a href="{{ route('operasional.riwayat.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💰 Simpan Pengeluaran</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</x-app-layout>
