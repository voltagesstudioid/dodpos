@extends('layouts.app')

@section('header', 'Edit Pengeluaran Operasional')

@section('content')
<div class="page-container animate-in">
    {{-- Header --}}
    <div class="ph">
        <div class="ph-left">
            <div class="ph-icon indigo">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            </div>
            <div>
                <h1 class="ph-title">Edit Pengeluaran</h1>
                <div class="ph-subtitle">Perbarui atau koreksi data pengeluaran kas operasional toko.</div>
            </div>
        </div>
        <div class="ph-actions">
            <a href="{{ route('operasional.riwayat.index') }}" class="btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Kembali ke Riwayat
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>Terdapat {{ $errors->count() }} kesalahan pada form. Silakan periksa kembali.</span>
        </div>
    @endif

    {{-- Form Panel --}}
    <div class="panel">
        <div class="panel-header">
            <div>
                <div class="panel-title">Detail Data Transaksi</div>
                <div class="panel-subtitle">Perbarui data transaksi pengeluaran yang sudah tercatat</div>
            </div>
            <span class="badge badge-warning">Mode Edit</span>
        </div>
        <div class="panel-body">
            <form action="{{ route('operasional.pengeluaran.update', $pengeluaran) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row-3">
                    {{-- Tanggal --}}
                    <div class="form-group">
                        <label class="form-label">Tanggal Transaksi <span class="required">*</span></label>
                        <input type="date" name="date"
                            class="form-input @error('date') input-error @enderror"
                            value="{{ old('date', $pengeluaran->date) }}" required>
                        @error('date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select name="category_id" class="form-input @error('category_id') input-error @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('category_id', $pengeluaran->category_id) == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Nominal --}}
                    <div class="form-group">
                        <label class="form-label">Nominal Terkoreksi <span class="required">*</span></label>
                        <div class="form-prefix">
                            <span class="form-prefix-text">Rp</span>
                            <input type="text" name="amount"
                                class="form-input @error('amount') input-error @enderror"
                                value="{{ old('amount', (int)$pengeluaran->amount) }}"
                                required placeholder="0"
                                data-currency>
                        </div>
                        @error('amount') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="height: 1px; background: #f1f5f9; margin: 0.5rem 0 1.25rem;"></div>

                <div class="form-row-2">
                    {{-- Kendaraan --}}
                    <div class="form-group">
                        <label class="form-label">Terkait Kendaraan <span style="color: #94a3b8; font-weight: 400;">(Opsional)</span></label>
                        <select name="vehicle_id" class="form-input">
                            <option value="">Umum (Bukan Kendaraan)</option>
                            @foreach($vehicles as $kendaraan)
                                <option value="{{ $kendaraan->id }}" {{ old('vehicle_id', $pengeluaran->vehicle_id) == $kendaraan->id ? 'selected' : '' }}>
                                    {{ strtoupper($kendaraan->license_plate) }} @if($kendaraan->type) — {{ $kendaraan->type }} @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="form-hint">Pilih jika biaya terkait bahan bakar/servis unit.</div>
                    </div>

                    {{-- Catatan --}}
                    <div class="form-group">
                        <label class="form-label">Catatan / Keterangan Lengkap</label>
                        <textarea name="notes" class="form-input @error('notes') input-error @enderror"
                            rows="3" placeholder="Contoh: Revisi pengisian bensin truk 15 liter..." style="resize: vertical;">{{ old('notes', $pengeluaran->notes) }}</textarea>
                        @error('notes') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; align-items: center; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid #f1f5f9;">
                    <a href="{{ route('operasional.riwayat.index') }}" class="btn-secondary">Batalkan Edit</a>
                    <button type="submit" class="btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-currency]').forEach(function(el) {
            if (el.value) {
                el.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    });
</script>
@endpush
