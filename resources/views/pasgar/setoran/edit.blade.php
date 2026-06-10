@extends('layouts.app', ['title' => 'Edit Setoran - Pasgar'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .ef-page { max-width:52rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }
    .ef-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.5rem; }
    .ef-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; }
    .ef-back:hover { color:#d97706; }
    .ef-sep { color:#cbd5e1; font-size:0.8125rem; }
    .ef-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

    .ef-hdr { background:linear-gradient(135deg,#f59e0b 0%,#d97706 50%,#b45309 100%); border-radius:18px; padding:1.5rem 1.75rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:1rem; position:relative; overflow:hidden; }
    .ef-hdr::before { content:''; position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%; }
    .ef-hdr-icon { width:48px; height:48px; border-radius:14px; background:rgba(255,255,255,0.2); backdrop-filter:blur(8px); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .ef-hdr-icon svg { width:24px; height:24px; color:#fff; }
    .ef-hdr h1 { font-size:1.15rem; font-weight:800; color:#fff; margin:0; }
    .ef-hdr p { font-size:0.78rem; color:rgba(255,255,255,0.8); margin:3px 0 0; font-weight:500; }

    .ef-alert { padding:0.75rem 1rem; border-radius:12px; margin-bottom:1rem; font-size:0.82rem; font-weight:600; display:flex; align-items:center; gap:0.5rem; }
    .ef-alert svg { width:18px; height:18px; flex-shrink:0; }
    .ef-alert-error { background:linear-gradient(135deg,#fef2f2,#fee2e2); border:1px solid #fecaca; color:#991b1b; }

    .ef-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; margin-bottom:1rem; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.03); }
    .ef-card-hdr { padding:0.85rem 1.25rem; display:flex; align-items:center; gap:0.6rem; border-bottom:1px solid #f1f5f9; }
    .ef-card-ico { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .ef-card-ico svg { width:16px; height:16px; color:#fff; }
    .ef-card-title { font-size:0.8125rem; font-weight:700; color:#0f172a; }
    .ef-card-body { padding:1.125rem 1.25rem; }
    .ef-card.amber .ef-card-hdr { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
    .ef-card.amber .ef-card-ico { background:linear-gradient(135deg,#f59e0b,#d97706); }
    .ef-card.indigo .ef-card-hdr { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
    .ef-card.indigo .ef-card-ico { background:linear-gradient(135deg,#6366f1,#4f46e5); }
    .ef-card.green .ef-card-hdr { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
    .ef-card.green .ef-card-ico { background:linear-gradient(135deg,#10b981,#059669); }

    .ef-fg { display:flex; flex-direction:column; gap:0.35rem; margin-bottom:0.85rem; }
    .ef-lbl { display:flex; align-items:center; gap:5px; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
    .ef-inp, .ef-txt { width:100%; padding:0.6rem 0.8rem; border:1.5px solid #e2e8f0; border-radius:10px; background:#f8fafc; font-family:inherit; font-size:0.82rem; color:#0f172a; outline:none; box-sizing:border-box; transition:all 0.2s; }
    .ef-inp:focus, .ef-txt:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.1); background:#fff; }
    .ef-inp.readonly { background:#f1f5f9; color:#64748b; cursor:not-allowed; }
    .ef-txt { resize:vertical; min-height:70px; }
    .ef-hint { font-size:0.68rem; color:#94a3b8; margin-top:0.2rem; }

    .ef-summary { display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; }
    .ef-sum-item { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:0.65rem 0.85rem; }
    .ef-sum-label { font-size:0.65rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em; color:#94a3b8; }
    .ef-sum-value { font-size:0.85rem; font-weight:800; color:#1e293b; margin-top:3px; }
    .ef-sum-value.highlight { color:#4f46e5; }

    .ef-info-box { margin-top:0.75rem; padding:0.65rem 0.85rem; background:linear-gradient(135deg,#fef3c7,#fde68a); border:1px solid #fde68a; border-radius:10px; font-size:0.78rem; color:#92400e; display:flex; align-items:flex-start; gap:0.5rem; }
    .ef-info-box svg { width:16px; height:16px; flex-shrink:0; margin-top:1px; }

    .ef-upload { border:2px dashed #e2e8f0; border-radius:14px; padding:1.5rem; text-align:center; cursor:pointer; transition:all 0.2s; }
    .ef-upload:hover { border-color:#f59e0b; background:#fffbeb; }
    .ef-upload-icon { width:48px; height:48px; border-radius:12px; background:linear-gradient(135deg,#fef3c7,#fde68a); display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem; }
    .ef-upload-icon svg { width:22px; height:22px; color:#d97706; }
    .ef-upload-text { font-size:0.82rem; color:#475569; font-weight:600; }
    .ef-upload-hint { font-size:0.7rem; color:#94a3b8; margin-top:0.3rem; }
    .ef-upload input[type="file"] { display:none; }
    .ef-preview { max-width:100%; max-height:200px; border-radius:10px; margin-top:0.75rem; border:2px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,0.06); }
    .ef-existing-foto { margin-bottom:0.75rem; }
    .ef-existing-foto img { max-width:100%; max-height:180px; border-radius:10px; border:2px solid #e2e8f0; }
    .ef-existing-foto span { font-size:0.7rem; color:#94a3b8; margin-top:0.3rem; display:block; }

    .ef-actions { display:flex; gap:0.75rem; justify-content:flex-end; margin-top:0.5rem; }
    .ef-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.65rem 1.25rem; border-radius:12px; font-size:0.82rem; font-weight:700; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; font-family:inherit; }
    .ef-btn svg { width:16px; height:16px; }
    .ef-btn-ghost { background:#f1f5f9; color:#64748b; border:1.5px solid #e2e8f0; }
    .ef-btn-ghost:hover { background:#e2e8f0; }
    .ef-btn-primary { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 2px 8px rgba(245,158,11,0.25); }
    .ef-btn-primary:hover { box-shadow:0 4px 16px rgba(245,158,11,0.35); transform:translateY(-1px); }

    @media(max-width:640px) { .ef-summary { grid-template-columns:1fr; } .ef-actions { flex-direction:column-reverse; } .ef-btn { justify-content:center; } }
</style>
@endpush

@section('content')
<div class="ef-page">
    <nav class="ef-nav">
        <a href="{{ route('pasgar.setoran.show', $setoran->id) }}" class="ef-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Detail Setoran
        </a>
        <span class="ef-sep">/</span>
        <span class="ef-crumb">Edit</span>
    </nav>

    @if(session('error'))
    <div class="ef-alert ef-alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="ef-alert ef-alert-error" style="flex-direction:column;align-items:flex-start;">
        <div style="display:flex;align-items:center;gap:0.5rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <span>Perbaiki kesalahan berikut:</span>
        </div>
        <ul style="margin:0.5rem 0 0 1.5rem;padding:0;">
            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="ef-hdr">
        <div class="ef-hdr-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </div>
        <div>
            <h1>Edit Setoran</h1>
            <p>{{ $setoran->nomor_setoran }} &middot; {{ $setoran->loading->nomor_loading ?? '-' }}</p>
        </div>
    </div>

    <form action="{{ route('pasgar.setoran.update', $setoran->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
        @csrf
        @method('PUT')

        {{-- Loading Info (read-only) --}}
        <div class="ef-card amber">
            <div class="ef-card-hdr">
                <div class="ef-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </div>
                <div class="ef-card-title">Loading</div>
            </div>
            <div class="ef-card-body">
                <div class="ef-fg">
                    <label class="ef-lbl">Loading</label>
                    <input type="text" class="ef-inp readonly" value="{{ $setoran->loading->nomor_loading ?? '-' }} — {{ $setoran->sales->nama ?? '-' }}" readonly>
                </div>
                <div class="ef-fg" style="margin-bottom:0;">
                    <label class="ef-lbl">Tanggal Setoran</label>
                    <input type="date" name="tanggal" class="ef-inp" value="{{ old('tanggal', $setoran->tanggal->format('Y-m-d')) }}">
                </div>
            </div>
        </div>

        {{-- Summary (read-only) --}}
        @if($summary)
        <div class="ef-card indigo">
            <div class="ef-card-hdr">
                <div class="ef-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
                <div class="ef-card-title">Ringkasan Penjualan</div>
            </div>
            <div class="ef-card-body">
                <div class="ef-summary">
                    <div class="ef-sum-item">
                        <div class="ef-sum-label">Total Penjualan</div>
                        <div class="ef-sum-value highlight">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</div>
                    </div>
                    <div class="ef-sum-item">
                        <div class="ef-sum-label">Jumlah Transaksi</div>
                        <div class="ef-sum-value">{{ $summary['jumlah_transaksi'] }}</div>
                    </div>
                    <div class="ef-sum-item">
                        <div class="ef-sum-label">Total Tunai</div>
                        <div class="ef-sum-value">Rp {{ number_format($summary['total_tunai'], 0, ',', '.') }}</div>
                    </div>
                    <div class="ef-sum-item">
                        <div class="ef-sum-label">Total Transfer/QRIS</div>
                        <div class="ef-sum-value">Rp {{ number_format($summary['total_transfer'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="ef-info-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <div><strong>Yang seharusnya disetor:</strong> Rp {{ number_format($summary['total_tunai'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Deposit Input --}}
        <div class="ef-card green">
            <div class="ef-card-hdr">
                <div class="ef-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="ef-card-title">Setoran</div>
            </div>
            <div class="ef-card-body">
                <div class="ef-fg">
                    <label class="ef-lbl">Total Setor</label>
                    <input type="number" name="total_setor" class="ef-inp" value="{{ old('total_setor', $summary['total_tunai']) }}" min="0" step="1" required>
                    <span class="ef-hint">Jumlah uang tunai yang disetorkan</span>
                </div>

                <div class="ef-fg">
                    <label class="ef-lbl">Bukti Setor</label>
                    @if($setoran->bukti_setor)
                    <div class="ef-existing-foto">
                        <a href="{{ asset('storage/' . $setoran->bukti_setor) }}" target="_blank">
                            <img src="{{ asset('storage/' . $setoran->bukti_setor) }}" alt="Bukti Setor">
                        </a>
                        <span>Bukti saat ini. Upload baru untuk mengganti.</span>
                    </div>
                    @endif
                    <div class="ef-upload" onclick="document.getElementById('buktiInput').click()">
                        <div class="ef-upload-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        </div>
                        <div class="ef-upload-text" id="uploadText">Klik untuk upload bukti baru</div>
                        <div class="ef-upload-hint">JPG/PNG/WEBP, maks 4MB</div>
                        <input type="file" name="bukti_setor" id="buktiInput" accept="image/jpeg,image/jpg,image/png,image/webp" onchange="previewImage(this)">
                    </div>
                    <img id="preview" class="ef-preview" style="display:none;" alt="Preview">
                </div>

                <div class="ef-fg" style="margin-bottom:0;">
                    <label class="ef-lbl">Catatan</label>
                    <textarea name="catatan_sales" class="ef-txt">{{ old('catatan_sales', $setoran->catatan_sales) }}</textarea>
                </div>
            </div>
        </div>

        <div class="ef-actions">
            <a href="{{ route('pasgar.setoran.show', $setoran->id) }}" class="ef-btn ef-btn-ghost">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Batal
            </a>
            <button type="submit" class="ef-btn ef-btn-primary" id="submitBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Perubahan
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
    var form = document.getElementById('editForm');
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
