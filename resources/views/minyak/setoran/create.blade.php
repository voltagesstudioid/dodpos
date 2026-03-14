<x-app-layout>
<x-slot name="header">Setoran & Retur (Minyak)</x-slot>

<div class="page-container animate-in" style="max-width:980px;margin:0 auto;">
    <div class="page-header">
        <div>
            <div class="page-header-title">Tambah Setoran & Retur Minyak</div>
            <div class="page-header-subtitle">Rekap setoran uang tunai dan retur barang dari sales</div>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('minyak.setoran.index') }}" class="btn-secondary">← Kembali</a>
        </div>
    </div>

    <div class="ph-breadcrumb">
        <a href="{{ route('minyak.setoran.index') }}">Setoran & Retur</a>
        <span class="ph-breadcrumb-sep">›</span>
        <span>Tambah Baru</span>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <div style="font-weight:900;margin-bottom:0.25rem;">Ada input yang belum valid</div>
            <ul style="margin:0;padding-left:1.1rem;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <div class="panel-header">
            <div>
                <div class="panel-title">Form Setoran</div>
                <div class="panel-subtitle">Isi data sesuai bukti setor dari sales</div>
            </div>
        </div>
        <div class="panel-body">
            <form action="{{ route('minyak.setoran.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Sales <span style="color:#ef4444;">*</span></label>
                        <select name="user_id" required class="form-input">
                            <option value="">-- Pilih Sales --</option>
                            @foreach($salesList as $s)
                                <option value="{{ $s->id }}" {{ old('user_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Kendaraan</label>
                        <select name="vehicle_id" class="form-input">
                            <option value="">-- Pilih Kendaraan (opsional) --</option>
                            @foreach($kendaraan as $k)
                                <option value="{{ $k->id }}" {{ old('vehicle_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->license_plate }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', today()->format('Y-m-d')) }}" required class="form-input">
                    @error('tanggal') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Jumlah Setoran (Rp) <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="jumlah_setoran" value="{{ old('jumlah_setoran', 0) }}" min="0" step="1000" required class="form-input" placeholder="0">
                        @error('jumlah_setoran') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Jumlah Retur (Rp)</label>
                        <input type="number" name="jumlah_retur" value="{{ old('jumlah_retur', 0) }}" min="0" step="1000" class="form-input" placeholder="0">
                        <div style="font-size:0.75rem;color:#94a3b8;margin-top:4px;">Nilai barang yang dikembalikan</div>
                        @error('jumlah_retur') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-input" rows="3" placeholder="Keterangan tambahan...">{{ old('catatan') }}</textarea>
                    @error('catatan') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:1rem 1.25rem;margin-top:0.25rem;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
                        <div>
                            <div style="font-size:0.72rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.25rem;">
                                Preview Setoran Bersih
                            </div>
                            <div id="previewBersih" style="font-size:1.5rem;font-weight:900;color:#0f172a;letter-spacing:-0.02em;line-height:1.1;">
                                Rp 0
                            </div>
                            <div style="font-size:0.75rem;color:#94a3b8;margin-top:0.25rem;">
                                Setoran - Retur
                            </div>
                        </div>
                        <span id="previewBadge" class="badge badge-success">Bersih</span>
                    </div>
                </div>

                <div style="display:flex;gap:0.75rem;justify-content:flex-end;flex-wrap:wrap;margin-top:1rem;">
                    <a href="{{ route('minyak.setoran.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💾 Simpan Setoran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    const setoran = parseFloat(document.querySelector('[name=jumlah_setoran]').value) || 0;
    const retur   = parseFloat(document.querySelector('[name=jumlah_retur]').value)   || 0;
    const bersih  = setoran - retur;
    const el = document.getElementById('previewBersih');
    const badge = document.getElementById('previewBadge');

    el.textContent = 'Rp ' + bersih.toLocaleString('id-ID');
    el.style.color = bersih < 0 ? '#b91c1c' : '#0f172a';

    badge.classList.remove('badge-success', 'badge-warning', 'badge-danger');
    if (bersih < 0) {
        badge.classList.add('badge-danger');
        badge.textContent = 'Minus';
    } else if (bersih === 0) {
        badge.classList.add('badge-warning');
        badge.textContent = 'Nol';
    } else {
        badge.classList.add('badge-success');
        badge.textContent = 'Bersih';
    }
}
document.querySelector('[name=jumlah_setoran]').addEventListener('input', updatePreview);
document.querySelector('[name=jumlah_retur]').addEventListener('input', updatePreview);
updatePreview();
</script>
</x-app-layout>
