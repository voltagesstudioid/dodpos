@extends('layouts.app', ['title' => 'Buat Setoran - Pasgar'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .cf-page { max-width:52rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }
    .cf-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.5rem; }
    .cf-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; }
    .cf-back:hover { color:#d97706; }
    .cf-sep { color:#cbd5e1; font-size:0.8125rem; }
    .cf-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

    .cf-hdr { background:linear-gradient(135deg,#f59e0b 0%,#d97706 50%,#b45309 100%); border-radius:18px; padding:1.5rem 1.75rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:1rem; position:relative; overflow:hidden; }
    .cf-hdr::before { content:''; position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%; }
    .cf-hdr-icon { width:48px; height:48px; border-radius:14px; background:rgba(255,255,255,0.2); backdrop-filter:blur(8px); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .cf-hdr-icon svg { width:24px; height:24px; color:#fff; }
    .cf-hdr h1 { font-size:1.15rem; font-weight:800; color:#fff; margin:0; }
    .cf-hdr p { font-size:0.78rem; color:rgba(255,255,255,0.8); margin:3px 0 0; font-weight:500; }

    /* Alerts */
    .cf-alert { padding:0.75rem 1rem; border-radius:12px; margin-bottom:1rem; font-size:0.82rem; font-weight:600; display:flex; align-items:center; gap:0.5rem; }
    .cf-alert svg { width:18px; height:18px; flex-shrink:0; }
    .cf-alert-error { background:linear-gradient(135deg,#fef2f2,#fee2e2); border:1px solid #fecaca; color:#991b1b; }

    /* Cards */
    .cf-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; margin-bottom:1rem; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.03); }
    .cf-card-hdr { padding:0.85rem 1.25rem; display:flex; align-items:center; gap:0.6rem; border-bottom:1px solid #f1f5f9; }
    .cf-card-ico { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .cf-card-ico svg { width:16px; height:16px; color:#fff; }
    .cf-card-title { font-size:0.8125rem; font-weight:700; color:#0f172a; }
    .cf-card-body { padding:1.125rem 1.25rem; }
    .cf-card.amber .cf-card-hdr { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
    .cf-card.amber .cf-card-ico { background:linear-gradient(135deg,#f59e0b,#d97706); }
    .cf-card.indigo .cf-card-hdr { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
    .cf-card.indigo .cf-card-ico { background:linear-gradient(135deg,#6366f1,#4f46e5); }
    .cf-card.green .cf-card-hdr { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
    .cf-card.green .cf-card-ico { background:linear-gradient(135deg,#10b981,#059669); }

    /* Form Elements */
    .cf-fg { display:flex; flex-direction:column; gap:0.35rem; margin-bottom:0.85rem; }
    .cf-lbl { display:flex; align-items:center; gap:5px; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
    .cf-req { color:#ef4444; }
    .cf-inp, .cf-sel, .cf-txt { width:100%; padding:0.6rem 0.8rem; border:1.5px solid #e2e8f0; border-radius:10px; background:#f8fafc; font-family:inherit; font-size:0.82rem; color:#0f172a; outline:none; box-sizing:border-box; transition:all 0.2s; }
    .cf-inp:focus, .cf-sel:focus, .cf-txt:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.1); background:#fff; }
    .cf-txt { resize:vertical; min-height:70px; }
    .cf-hint { font-size:0.68rem; color:#94a3b8; margin-top:0.2rem; }

    /* Summary Grid */
    .cf-summary { display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; }
    .cf-sum-item { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:0.65rem 0.85rem; }
    .cf-sum-label { font-size:0.65rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em; color:#94a3b8; }
    .cf-sum-value { font-size:0.85rem; font-weight:800; color:#1e293b; margin-top:3px; }
    .cf-sum-value.highlight { color:#4f46e5; }

    .cf-info-box { margin-top:0.75rem; padding:0.65rem 0.85rem; background:linear-gradient(135deg,#fef3c7,#fde68a); border:1px solid #fde68a; border-radius:10px; font-size:0.78rem; color:#92400e; display:flex; align-items:flex-start; gap:0.5rem; }
    .cf-info-box svg { width:16px; height:16px; flex-shrink:0; margin-top:1px; }

    /* Upload Area */
    .cf-upload { border:2px dashed #e2e8f0; border-radius:14px; padding:1.5rem; text-align:center; cursor:pointer; transition:all 0.2s; }
    .cf-upload:hover { border-color:#f59e0b; background:#fffbeb; }
    .cf-upload-icon { width:48px; height:48px; border-radius:12px; background:linear-gradient(135deg,#fef3c7,#fde68a); display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem; }
    .cf-upload-icon svg { width:22px; height:22px; color:#d97706; }
    .cf-upload-text { font-size:0.82rem; color:#475569; font-weight:600; }
    .cf-upload-hint { font-size:0.7rem; color:#94a3b8; margin-top:0.3rem; }
    .cf-upload input[type="file"] { display:none; }
    .cf-preview { max-width:100%; max-height:200px; border-radius:10px; margin-top:0.75rem; display:none; border:2px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,0.06); }

    /* Actions */
    .cf-actions { display:flex; gap:0.75rem; justify-content:flex-end; margin-top:0.5rem; }
    .cf-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.65rem 1.25rem; border-radius:12px; font-size:0.82rem; font-weight:700; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; font-family:inherit; }
    .cf-btn svg { width:16px; height:16px; }
    .cf-btn-ghost { background:#f1f5f9; color:#64748b; border:1.5px solid #e2e8f0; }
    .cf-btn-ghost:hover { background:#e2e8f0; }
    .cf-btn-primary { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 2px 8px rgba(245,158,11,0.25); }
    .cf-btn-primary:hover { box-shadow:0 4px 16px rgba(245,158,11,0.35); transform:translateY(-1px); }

    @media(max-width:640px) { .cf-summary { grid-template-columns:1fr; } .cf-actions { flex-direction:column-reverse; } .cf-btn { justify-content:center; } }
</style>
@endpush

@section('content')
<div class="cf-page">
    <nav class="cf-nav">
        <a href="{{ route('pasgar.setoran.index') }}" class="cf-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Daftar Setoran
        </a>
        <span class="cf-sep">/</span>
        <span class="cf-crumb">Buat Setoran</span>
    </nav>

    @if(session('error'))
    <div class="cf-alert cf-alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="cf-alert cf-alert-error" style="flex-direction:column;align-items:flex-start;">
        <div style="display:flex;align-items:center;gap:0.5rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <span>Perbaiki kesalahan berikut:</span>
        </div>
        <ul style="margin:0.5rem 0 0 1.5rem;padding:0;">
            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="cf-hdr">
        <div class="cf-hdr-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        </div>
        <div>
            <h1>Buat Setoran</h1>
            <p>Setoran penjualan dari loading barang</p>
        </div>
    </div>

    <form action="{{ route('pasgar.setoran.store') }}" method="POST" enctype="multipart/form-data" id="setoranForm">
        @csrf

        {{-- Loading Selection --}}
        <div class="cf-card amber">
            <div class="cf-card-hdr">
                <div class="cf-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </div>
                <div class="cf-card-title">Pilih Loading</div>
            </div>
            <div class="cf-card-body">
                <div class="cf-fg">
                    <label class="cf-lbl">Loading <span class="cf-req">*</span></label>
                    <select name="loading_id" class="cf-sel" id="loadingSelect" required>
                        <option value="">-- Pilih Loading --</option>
                        @foreach($loadings as $ld)
                            <option value="{{ $ld->id }}" {{ $selectedLoading && $selectedLoading->id === $ld->id ? 'selected' : '' }}>
                                {{ $ld->nomor_loading }} — {{ $ld->sales->nama ?? '-' }} ({{ $ld->loaded_at ? $ld->loaded_at->format('d/m/Y') : $ld->tanggal->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="cf-fg" style="margin-bottom:0;">
                    <label class="cf-lbl">Tanggal Setoran <span class="cf-req">*</span></label>
                    <input type="date" name="tanggal" class="cf-inp" value="{{ old('tanggal', today()->format('Y-m-d')) }}" required>
                </div>
            </div>
        </div>

        {{-- Auto Summary --}}
        <div class="cf-card indigo" id="summaryCard">
            <div class="cf-card-hdr">
                <div class="cf-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
                <div class="cf-card-title">Ringkasan Penjualan</div>
            </div>
            <div class="cf-card-body">
                @if($summary)
                <div class="cf-summary" id="summaryGrid">
                    <div class="cf-sum-item">
                        <div class="cf-sum-label">Total Penjualan</div>
                        <div class="cf-sum-value highlight">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</div>
                    </div>
                    <div class="cf-sum-item">
                        <div class="cf-sum-label">Jumlah Transaksi</div>
                        <div class="cf-sum-value">{{ $summary['jumlah_transaksi'] }}</div>
                    </div>
                    <div class="cf-sum-item">
                        <div class="cf-sum-label">Total Tunai</div>
                        <div class="cf-sum-value">Rp {{ number_format($summary['total_tunai'], 0, ',', '.') }}</div>
                    </div>
                    <div class="cf-sum-item">
                        <div class="cf-sum-label">Total Transfer/QRIS</div>
                        <div class="cf-sum-value">Rp {{ number_format($summary['total_transfer'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="cf-info-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <div>
                        <strong>Yang seharusnya disetor:</strong> <strong>Rp {{ number_format($summary['total_tunai'], 0, ',', '.') }}</strong> (total tunai)
                    </div>
                </div>
                @else
                <div style="text-align:center;color:#94a3b8;padding:1.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" style="width:40px;height:40px;color:#cbd5e1;margin:0 auto 0.5rem;display:block;"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                    Pilih loading untuk melihat ringkasan
                </div>
                @endif
            </div>
        </div>

        {{-- Deposit Input --}}
        <div class="cf-card green">
            <div class="cf-card-hdr">
                <div class="cf-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="cf-card-title">Setoran</div>
            </div>
            <div class="cf-card-body">
                <div class="cf-fg">
                    <label class="cf-lbl">Total Setor <span class="cf-req">*</span></label>
                    <input type="number" name="total_setor" class="cf-inp" value="{{ old('total_setor', $seharusnyaSetor ?? 0) }}" min="0" step="1" required placeholder="Masukkan jumlah uang yang disetor">
                    <span class="cf-hint">Jumlah uang tunai yang disetorkan</span>
                </div>

                <div class="cf-fg">
                    <label class="cf-lbl">Bukti Setor <span class="cf-req">*</span></label>
                    <div class="cf-upload" onclick="document.getElementById('buktiInput').click()">
                        <div class="cf-upload-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        </div>
                        <div class="cf-upload-text" id="uploadText">Klik untuk upload foto bukti setor</div>
                        <div class="cf-upload-hint">JPG/PNG/WEBP, maks 4MB</div>
                        <input type="file" name="bukti_setor" id="buktiInput" accept="image/jpeg,image/jpg,image/png,image/webp" onchange="previewImage(this)">
                    </div>
                    <img id="preview" class="cf-preview" alt="Preview">
                </div>

                <div class="cf-fg" style="margin-bottom:0;">
                    <label class="cf-lbl">Catatan</label>
                    <textarea name="catatan_sales" class="cf-txt" placeholder="Catatan tambahan (opsional)...">{{ old('catatan_sales') }}</textarea>
                </div>
            </div>
        </div>

        <div class="cf-actions">
            <a href="{{ route('pasgar.setoran.index') }}" class="cf-btn cf-btn-ghost">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Batal
            </a>
            <button type="submit" class="cf-btn cf-btn-primary" id="submitBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Setoran
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.previewImage = function(input) {
        var preview = document.getElementById('preview');
        var text = document.getElementById('uploadText');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                text.textContent = input.files[0].name;
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    // Prevent double submit
    var form = document.getElementById('setoranForm');
    if (form) {
        form.addEventListener('submit', function() {
            var btn = document.getElementById('submitBtn');
            if (btn) {
                btn.disabled = true;
                btn.style.opacity = '0.7';
                btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="width:16px;height:16px;animation:spin 1s linear infinite;"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Menyimpan...';
            }
        });
    }
});
</script>
<style>@keyframes spin { to { transform: rotate(360deg); } }</style>
@endpush
@endsection
