@extends('layouts.app', ['title' => 'Bayar Hutang - Pasgar'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .htb-page { max-width:42rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }
    .htb-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.5rem; }
    .htb-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; }
    .htb-back:hover { color:#4f46e5; }
    .htb-sep { color:#cbd5e1; font-size:0.8125rem; }
    .htb-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

    .htb-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem; }
    .htb-hdr-icon { width:48px; height:48px; border-radius:13px; background:linear-gradient(135deg,#10b981,#059669); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(16,185,129,0.25); flex-shrink:0; font-size:1.25rem; }
    .htb-hdr h1 { font-size:1.15rem; font-weight:800; color:#1e1b4b; margin:0; }
    .htb-hdr p { font-size:0.78rem; color:#10b981; margin:2px 0 0; font-weight:600; }

    .htb-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; margin-bottom:1rem; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
    .htb-card-hdr { padding:0.85rem 1.25rem; display:flex; align-items:center; gap:0.6rem; border-bottom:1px solid #f1f5f9; }
    .htb-card-ico { width:28px; height:28px; border-radius:7px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.85rem; }
    .htb-card-title { font-size:0.8125rem; font-weight:700; color:#0f172a; }
    .htb-card-body { padding:1.125rem 1.25rem; }
    .htb-card.green .htb-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
    .htb-card.green .htb-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
    .htb-card.indigo .htb-card-hdr { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
    .htb-card.indigo .htb-card-ico { background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; }

    .htb-info { width:100%; }
    .htb-info td { padding:0.375rem 0; font-size:0.8125rem; vertical-align:top; }
    .htb-info td:first-child { color:#94a3b8; font-weight:600; width:40%; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; }
    .htb-info td:last-child { color:#1e293b; font-weight:500; }

    .htb-fg { display:flex; flex-direction:column; gap:0.35rem; margin-bottom:0.85rem; }
    .htb-lbl { display:flex; align-items:center; gap:5px; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
    .htb-req { color:#ef4444; }
    .htb-inp, .htb-sel, .htb-txt { width:100%; padding:0.6rem 0.8rem; border:1.5px solid #e2e8f0; border-radius:10px; background:#fcfcfd; font-family:inherit; font-size:0.82rem; color:#0f172a; outline:none; box-sizing:border-box; }
    .htb-inp:focus, .htb-sel:focus, .htb-txt:focus { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,0.1); }
    .htb-txt { resize:vertical; min-height:60px; }
    .htb-hint { font-size:0.68rem; color:#94a3b8; margin-top:0.2rem; }

    .htb-remaining { background:#fef3c7; border:1px solid #fde68a; border-radius:10px; padding:0.75rem 1rem; margin-bottom:1rem; font-size:0.82rem; color:#92400e; }
    .htb-remaining strong { font-weight:800; font-size:1rem; }

    .htb-row2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }

    .htb-actions { display:flex; gap:0.75rem; justify-content:flex-end; margin-top:0.5rem; }
    .htb-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.65rem 1.25rem; border-radius:12px; font-size:0.82rem; font-weight:700; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; }
    .htb-btn-ghost { background:#f1f5f9; color:#64748b; border:1.5px solid #e2e8f0; }
    .htb-btn-ghost:hover { background:#e2e8f0; }
    .htb-btn-primary { background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 2px 8px rgba(16,185,129,0.25); }
    .htb-btn-primary:hover { box-shadow:0 4px 16px rgba(16,185,129,0.35); }

    @media(max-width:640px) { .htb-row2 { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="htb-page">
    <nav class="htb-nav">
        <a href="{{ route('pasgar.hutang.show', $hutang->id) }}" class="htb-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Detail Hutang
        </a>
        <span class="htb-sep">/</span>
        <span class="htb-crumb">Bayar</span>
    </nav>

    {{-- Flash messages --}}
    @if(session('error'))
        <div style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:0.75rem 1rem; border-radius:10px; margin-bottom:1rem; font-size:0.82rem; font-weight:600;">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:0.75rem 1rem; border-radius:10px; margin-bottom:1rem; font-size:0.82rem;">
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
    @endif

    <div class="htb-hdr">
        <div class="htb-hdr-icon">💰</div>
        <div>
            <h1>Bayar Hutang</h1>
            <p>{{ $hutang->pelanggan->nama_toko ?? '-' }} &middot; {{ $hutang->penjualan->nomor_transaksi ?? '-' }}</p>
        </div>
    </div>

    {{-- Hutang Info --}}
    <div class="htb-card green">
        <div class="htb-card-hdr">
            <div class="htb-card-ico">📋</div>
            <div class="htb-card-title">Informasi Hutang</div>
        </div>
        <div class="htb-card-body">
            <table class="htb-info">
                <tr><td>Total Hutang</td><td style="font-weight:700; color:#4f46e5;">Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</td></tr>
                <tr><td>Sudah Dibayar</td><td style="color:#059669;">Rp {{ number_format($hutang->dibayar, 0, ',', '.') }}</td></tr>
                <tr><td>Jatuh Tempo</td><td>{{ $hutang->jatuh_tempo ? $hutang->jatuh_tempo->format('d M Y') : '-' }}</td></tr>
            </table>
            <div class="htb-remaining" style="margin-top:0.75rem;">
                Sisa yang harus dibayar: <br><strong>Rp {{ number_format($hutang->sisa, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    {{-- Payment Form --}}
    <form action="{{ route('pasgar.hutang.storeBayar', $hutang->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="htb-card indigo">
            <div class="htb-card-hdr">
                <div class="htb-card-ico">💳</div>
                <div class="htb-card-title">Form Pembayaran</div>
            </div>
            <div class="htb-card-body">
                <div class="htb-fg">
                    <label class="htb-lbl">Jumlah Bayar <span class="htb-req">*</span></label>
                    <input type="text" inputmode="numeric" data-currency name="jumlah" class="htb-inp" value="{{ old('jumlah', (int) $hutang->sisa) }}" required>
                    <span class="htb-hint">Maksimal Rp {{ number_format($hutang->sisa, 0, ',', '.') }}</span>
                </div>

                <div class="htb-row2">
                    <div class="htb-fg">
                        <label class="htb-lbl">Cara Bayar <span class="htb-req">*</span></label>
                        <select name="cara_bayar" class="htb-sel" id="caraBayarSelect" required>
                            <option value="tunai" {{ old('cara_bayar') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ old('cara_bayar') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="qris" {{ old('cara_bayar') === 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                    </div>
                    <div class="htb-fg" id="buktiField" style="display:none;">
                        <label class="htb-lbl">Bukti Transfer</label>
                        <input type="file" name="bukti_transfer" class="htb-inp" accept="image/jpeg,image/jpg,image/png,image/webp">
                        <span class="htb-hint">JPG/PNG/WEBP, maks 4MB</span>
                    </div>
                </div>

                <div class="htb-fg" style="margin-bottom:0;">
                    <label class="htb-lbl">Keterangan</label>
                    <textarea name="keterangan" class="htb-txt" placeholder="Keterangan tambahan (opsional)...">{{ old('keterangan') }}</textarea>
                </div>
            </div>
        </div>

        @if($isSalesRole)
        <div style="background:#eef2ff; border:1px solid #c7d2fe; border-radius:10px; padding:0.75rem 1rem; margin-bottom:1rem; font-size:0.78rem; color:#4338ca; font-weight:600;">
            ℹ️ Pembayaran yang Anda kirim akan menunggu verifikasi supervisor.
        </div>
        @endif

        <div class="htb-actions">
            <a href="{{ route('pasgar.hutang.show', $hutang->id) }}" class="htb-btn htb-btn-ghost">Batal</a>
            <button type="submit" class="htb-btn htb-btn-primary">
                💰 Simpan Pembayaran
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('caraBayarSelect');
    const bukti = document.getElementById('buktiField');
    function toggleBukti() {
        bukti.style.display = (sel.value === 'transfer' || sel.value === 'qris') ? 'block' : 'none';
    }
    sel.addEventListener('change', toggleBukti);
    toggleBukti();
});
</script>
@endpush
@endsection
