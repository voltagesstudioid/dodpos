<x-app-layout>
    <x-slot name="header">Operasional Tertutup</x-slot>

    <div class="page-container" style="max-width: 980px;">
        <div class="page-header">
            <div>
                <div class="page-header-title">🔒 Operasional Belum Dibuka</div>
                <div class="page-header-subtitle">Pengeluaran operasional hanya bisa digunakan saat sesi operasional dibuka</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('operasional.sesi.index') }}" class="btn-secondary">📊 Buka/Tutup Sesi</a>
                <a href="{{ route('dashboard') }}" class="btn-secondary">🏠 Dashboard</a>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Langkah Selanjutnya</div>
                    <div class="panel-subtitle">Buka sesi dengan modal awal supaya pengeluaran bisa dicatat</div>
                </div>
                <span class="badge badge-warning">Perlu sesi aktif</span>
            </div>
            <div class="panel-body">
                <div class="alert alert-warning" role="alert">
                    ⚠️ Sesi operasional untuk hari ini belum dibuka. Silakan buka sesi dengan memasukkan modal awal (petty cash).
                </div>

                @can('manage_sesi_operasional')
                    <form action="{{ route('operasional.open_session') }}" method="POST" style="margin-top: 1rem;">
                        @csrf

                        <div class="form-row">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="opening_amount" class="form-label">Modal Awal (Rp) <span class="required">*</span></label>
                                <input type="number" name="opening_amount" id="opening_amount" class="form-input @error('opening_amount') input-error @enderror" placeholder="0" required min="0" value="{{ old('opening_amount', 0) }}">
                                @error('opening_amount') <div class="form-error">⚠ {{ $message }}</div> @enderror
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="payment_method" class="form-label">Metode Modal <span class="required">*</span></label>
                                @php $pm = old('payment_method', 'Tunai'); @endphp
                                <select id="payment_method" name="payment_method" class="form-input" required>
                                    <option value="Tunai" {{ $pm === 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Transfer" {{ $pm === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                            </div>
                        </div>

                        <div style="margin-top: 1rem;">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea id="notes" name="notes" rows="2" class="form-input" placeholder="Contoh: Modal operasional hari ini">{{ old('notes') }}</textarea>
                        </div>

                        <div style="display:flex;justify-content:flex-end;gap:0.75rem;flex-wrap:wrap;margin-top:1rem;">
                            <a href="{{ route('operasional.sesi.index') }}" class="btn-secondary">📊 Lihat Sesi</a>
                            <button type="submit" class="btn-primary">✅ Simpan &amp; Buka Operasional</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-info" role="alert" style="margin-top: 1rem;">
                        ℹ️ Hubungi supervisor untuk membuka sesi operasional.
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
