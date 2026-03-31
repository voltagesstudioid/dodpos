@extends('layouts.sales-mobile')
@section('title','Setoran Harian')
@section('header-title','Setoran')
@section('header-subtitle','Input setoran penjualan')

@section('back-button')
<a href="{{ route('sales.menu') }}" style="width:34px;height:34px;border:1px solid var(--border);background:var(--bg-elevated);border-radius:10px;display:flex;align-items:center;justify-content:center;">
    <i data-lucide="arrow-left" style="width:16px;height:16px;color:rgba(255,255,255,0.7);"></i>
</a>
@endsection

@section('content')
<div style="padding:16px 16px 110px;">

    {{-- SUMMARY --}}
    <div class="card-glow anim-up" style="padding:20px;margin-bottom:16px;position:relative;overflow:hidden;">
        <div class="orb orb-violet" style="width:160px;height:160px;top:-60px;right:-40px;"></div>
        <div style="position:relative;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);font-weight:500;margin-bottom:6px;">Total Penjualan Hari Ini</div>
                <div id="total-penjualan" style="font-size:28px;font-weight:800;color:#fff;letter-spacing:-1px;">Rp 0</div>
            </div>
            <div class="grad-amber" style="width:52px;height:52px;border-radius:16px;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="wallet" style="width:24px;height:24px;color:#fff;"></i>
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <div class="card anim-up-1" style="padding:20px;margin-bottom:12px;">
        <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:16px;">Detail Setoran</div>

        <div style="margin-bottom:16px;">
            <div style="font-size:12px;color:rgba(255,255,255,0.5);font-weight:500;margin-bottom:8px;">Jumlah Setor</div>
            <div style="position:relative;">
                <span style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.4);font-weight:600;">Rp</span>
                <input type="number" id="jumlah-setor" placeholder="0" oninput="calculateSelisih()"
                    style="width:100%;padding:14px 16px 14px 44px;background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:14px;color:#fff;font-size:20px;font-weight:700;outline:none;">
            </div>
        </div>

        <div id="selisih-section" style="display:none;padding:12px 16px;border-radius:12px;margin-bottom:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span id="selisih-label" style="font-size:13px;font-weight:500;">Selisih</span>
                <span id="selisih-amount" style="font-size:16px;font-weight:700;">Rp 0</span>
            </div>
        </div>

        <div style="margin-bottom:16px;">
            <div style="font-size:12px;color:rgba(255,255,255,0.5);font-weight:500;margin-bottom:8px;">Keterangan</div>
            <textarea id="keterangan" rows="3" placeholder="Catatan tambahan..."
                style="width:100%;padding:14px 16px;background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:14px;color:#fff;font-size:14px;outline:none;resize:none;font-family:inherit;"></textarea>
        </div>

        <div>
            <div style="font-size:12px;color:rgba(255,255,255,0.5);font-weight:500;margin-bottom:8px;">Bukti Foto</div>
            <div onclick="document.getElementById('foto-input').click()" style="border:2px dashed rgba(255,255,255,0.1);border-radius:14px;padding:24px;text-align:center;cursor:pointer;" id="foto-zone">
                <i data-lucide="camera" style="width:32px;height:32px;color:rgba(255,255,255,0.25);margin:0 auto 8px;"></i>
                <div style="font-size:13px;color:rgba(255,255,255,0.3);">Tap untuk ambil foto</div>
                <input type="file" id="foto-input" accept="image/*" capture="environment" style="display:none;" onchange="previewFoto(this)">
            </div>
            <div id="foto-preview" style="display:none;margin-top:12px;">
                <img src="" alt="Preview" style="width:100%;height:160px;object-fit:cover;border-radius:14px;">
                <button onclick="clearFoto()" style="margin-top:8px;background:rgba(251,113,133,0.1);border:1px solid rgba(251,113,133,0.2);color:#FB7185;padding:8px 16px;border-radius:10px;font-size:12px;cursor:pointer;">Hapus foto</button>
            </div>
        </div>
    </div>

    <div style="display:flex;align-items:flex-start;gap:10px;padding:14px 16px;background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.2);border-radius:14px;">
        <i data-lucide="info" style="width:16px;height:16px;color:#A78BFA;flex-shrink:0;margin-top:1px;"></i>
        <span style="font-size:12px;color:rgba(255,255,255,0.5);">Pastikan jumlah setoran sesuai dengan penjualan hari ini.</span>
    </div>
</div>

<div style="position:fixed;bottom:0;left:0;right:0;padding:16px;background:linear-gradient(to top,var(--bg-base) 60%,transparent);z-index:45;">
    <button onclick="saveSetoran()" class="tap"
        style="width:100%;padding:16px;border:none;border-radius:16px;font-size:15px;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#D97706,#92400E);box-shadow:0 4px 20px rgba(217,119,6,0.3);">
        <i data-lucide="save" style="width:20px;height:20px;"></i>
        Simpan Setoran
    </button>
</div>
@endsection

@push('scripts')
<script>
let penjualanTotal = 0;
let fotoBase64 = null;

document.addEventListener('DOMContentLoaded', async function() {
    await loadPenjualanTotal();
    lucide.createIcons();
});

async function loadPenjualanTotal() {
    try {
        const division = '{{ auth()->user()->division ?? "minyak" }}';
        const response = await fetch(`/api/v1/${division}/dashboard`);
        if (response.ok) {
            const data = await response.json();
            penjualanTotal = data.data?.hariIni?.penjualanTotal || 0;
            document.getElementById('total-penjualan').textContent = formatRupiah(penjualanTotal);
        }
    } catch (error) { console.error('Failed to load penjualan:', error); }
}

function calculateSelisih() {
    const jumlahSetor = parseInt(document.getElementById('jumlah-setor').value) || 0;
    const selisih = jumlahSetor - penjualanTotal;
    const section = document.getElementById('selisih-section');
    const label = document.getElementById('selisih-label');
    const amount = document.getElementById('selisih-amount');
    if (jumlahSetor > 0) {
        section.style.display = 'block';
        if (selisih > 0) { section.style.background='rgba(5,150,105,0.1)'; section.style.border='1px solid rgba(52,211,153,0.2)'; label.textContent='Lebih'; label.style.color='#34D399'; amount.style.color='#34D399'; }
        else if (selisih < 0) { section.style.background='rgba(225,29,72,0.1)'; section.style.border='1px solid rgba(251,113,133,0.2)'; label.textContent='Kurang'; label.style.color='#FB7185'; amount.style.color='#FB7185'; }
        else { section.style.background='rgba(5,150,105,0.1)'; section.style.border='1px solid rgba(52,211,153,0.2)'; label.textContent='Pas ✓'; label.style.color='#34D399'; amount.style.color='#34D399'; }
        amount.textContent = formatRupiah(Math.abs(selisih));
    } else { section.style.display = 'none'; }
}

function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { fotoBase64 = e.target.result; document.getElementById('foto-preview').style.display='block'; document.getElementById('foto-preview').querySelector('img').src = fotoBase64; document.getElementById('foto-zone').style.display='none'; };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearFoto() { fotoBase64=null; document.getElementById('foto-input').value=''; document.getElementById('foto-preview').style.display='none'; document.getElementById('foto-zone').style.display='block'; }

async function saveSetoran() {
    const jumlahSetor = parseInt(document.getElementById('jumlah-setor').value) || 0;
    if (jumlahSetor <= 0) { showToast('Masukkan jumlah setoran', 'error'); return; }
    
    showLoading(true);
    const data = { localId: `setor_${Date.now()}`, tanggal: new Date().toISOString().split('T')[0], totalPenjualan: penjualanTotal, totalSetor: jumlahSetor, selisih: jumlahSetor - penjualanTotal, keterangan: document.getElementById('keterangan').value, fotoBukti: fotoBase64, syncStatus: 'pending', createdAt: Date.now() };
    
    try {
        const division = '{{ auth()->user()->division ?? "minyak" }}';
        const response = await fetch(`/api/v1/${division}/setoran`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify(data) });
        if (response.ok) { showToast('Setoran berhasil disimpan', 'success'); window.location.href = '{{ route('sales.dashboard') }}'; }
        else throw new Error('Server error');
    } catch (error) {
        if (window.salesPWA) { await window.salesPWA.saveOffline(data, 'setoran_pending'); showToast('Setoran tersimpan (offline mode)', 'info'); window.location.href = '{{ route('sales.dashboard') }}'; }
        else showToast('Gagal menyimpan setoran', 'error');
    } finally { showLoading(false); }
}

function formatRupiah(amount) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount); }
</script>
@endpush
