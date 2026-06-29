<x-app-layout>
    <x-slot name="header">Edit Pelanggan</x-slot>
    <div class="page-container animate-in" style="max-width: 980px;">
        <div class="page-header">
            <div>
                <div class="page-header-title">Edit Pelanggan</div>
                <div class="page-header-subtitle">Perbarui data pelanggan untuk transaksi dan laporan piutang</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('pelanggan.show', $pelanggan) }}" class="btn-secondary">← Kembali</a>
                <button type="submit" form="customerEditForm" class="btn-primary">💾 Simpan</button>
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

        <form id="customerEditForm" action="{{ route('pelanggan.update', $pelanggan) }}" method="POST">
            @csrf @method('PUT')

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Identitas Pelanggan</div>
                        <div class="panel-subtitle">Nama, kontak, dan alamat</div>
                    </div>
                    <span class="badge badge-indigo">ID: {{ $pelanggan->id }}</span>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="form-label">Nama Pelanggan <span class="required">*</span></label>
                        <input type="text" name="name" id="nameInput" class="form-input @error('name') input-error @enderror" value="{{ old('name', $pelanggan->name) }}" required style="text-transform:uppercase;">
                        @error('name')<div class="form-error">⚠ {{ $message }}</div>@enderror
                    </div>

                    {{-- Kategori Pelanggan --}}
                    <div class="form-group">
                        <label class="form-label">Kategori Pelanggan <span class="required">*</span></label>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem;">
                            <label style="cursor:pointer;position:relative;">
                                <input type="radio" name="category" value="eceran" {{ old('category', $pelanggan->category) === 'eceran' ? 'checked' : '' }} required style="position:absolute;opacity:0;">
                                <div style="padding:1rem;border:2px solid #e2e8f0;border-radius:8px;text-align:center;transition:all 0.2s;" class="radio-box">
                                    <div style="width:40px;height:40px;border-radius:8px;background:#ccfbf1;display:flex;align-items:center;justify-content:center;font-size:1.25rem;margin:0 auto 0.5rem;">🛒</div>
                                    <div style="font-weight:700;font-size:0.9rem;">Eceran</div>
                                    <div style="font-size:0.7rem;color:#64748b;">Pelanggan umum</div>
                                </div>
                            </label>
                            <label style="cursor:pointer;position:relative;">
                                <input type="radio" name="category" value="grosir" {{ old('category', $pelanggan->category) === 'grosir' ? 'checked' : '' }} required style="position:absolute;opacity:0;">
                                <div style="padding:1rem;border:2px solid #e2e8f0;border-radius:8px;text-align:center;transition:all 0.2s;" class="radio-box">
                                    <div style="width:40px;height:40px;border-radius:8px;background:#f3e8ff;display:flex;align-items:center;justify-content:center;font-size:1.25rem;margin:0 auto 0.5rem;">🏪</div>
                                    <div style="font-weight:700;font-size:0.9rem;">Grosir</div>
                                    <div style="font-size:0.7rem;color:#64748b;">Toko/distributor</div>
                                </div>
                            </label>
                            <label style="cursor:pointer;position:relative;">
                                <input type="radio" name="category" value="pos" {{ old('category', $pelanggan->category) === 'pos' ? 'checked' : '' }} required style="position:absolute;opacity:0;">
                                <div style="padding:1rem;border:2px solid #e2e8f0;border-radius:8px;text-align:center;transition:all 0.2s;" class="radio-box">
                                    <div style="width:40px;height:40px;border-radius:8px;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:1.25rem;margin:0 auto 0.5rem;">🅿️</div>
                                    <div style="font-weight:700;font-size:0.9rem;">Toko/POS</div>
                                    <div style="font-size:0.7rem;color:#64748b;">Dengan sistem POS</div>
                                </div>
                            </label>
                        </div>
                        <style>
                            input:checked + .radio-box { border-color:#3b82f6 !important; background:#eff6ff; }
                            label:hover .radio-box { border-color:#cbd5e1; }
                        </style>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" class="form-input" value="{{ old('phone', $pelanggan->phone) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $pelanggan->email) }}">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-input" rows="2" placeholder="Jl. ...">{{ old('address', $pelanggan->address) }}</textarea>
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
                            <label class="form-label">Limit (Rp)</label>
                            <div class="form-prefix">
                                <span class="form-prefix-text">Rp</span>
                                <input type="text" inputmode="numeric" name="credit_limit" data-currency class="form-input" value="{{ old('credit_limit', $pelanggan->credit_limit) }}" min="0">
                            </div>
                            <div class="form-hint">Isi 0 untuk tanpa batas kredit.</div>
                        </div>
                    @else
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                            <div class="alert alert-warning" role="alert" style="margin:0;flex:1;">
                                ⚠️ Limit hanya bisa diubah oleh Supervisor.
                            </div>
                            <div class="badge badge-gray">Limit: Rp {{ number_format($pelanggan->credit_limit ?? 0, 0, ',', '.') }}</div>
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
                        <textarea name="notes" class="form-input" rows="2" placeholder="Catatan khusus...">{{ old('notes', $pelanggan->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="floating-bar">
                <span class="floating-bar-info">Mengedit: <strong>{{ $pelanggan->name }}</strong></span>
                <div class="floating-bar-actions">
                    <a href="{{ route('pelanggan.show', $pelanggan) }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💾 Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var nameInput = document.getElementById('nameInput');
            if (nameInput) {
                nameInput.addEventListener('input', function() {
                    var start = this.selectionStart;
                    this.value = this.value.toUpperCase();
                    this.setSelectionRange(start, start);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
