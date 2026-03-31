@extends('layouts.sales-mobile')
@section('title','Catat Kunjungan')
@section('header-title','Kunjungan')
@section('header-subtitle','Catat kunjungan ke pelanggan')

@section('back-button')
<a href="{{ route('sales.menu') }}" style="width:34px;height:34px;border:1px solid var(--border);background:var(--bg-elevated);border-radius:10px;display:flex;align-items:center;justify-content:center;">
    <i data-lucide="arrow-left" style="width:16px;height:16px;color:rgba(255,255,255,0.7);"></i>
</a>
@endsection

@section('content')
<div style="padding:16px 16px 110px;">

    {{-- GPS STATUS --}}
    <div id="gps-status" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:rgba(96,165,250,0.08);border:1px solid rgba(96,165,250,0.2);border-radius:16px;margin-bottom:14px;">
        <div style="width:38px;height:38px;background:rgba(96,165,250,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i data-lucide="map-pin" id="gps-icon" style="width:18px;height:18px;color:#60A5FA;"></i>
        </div>
        <div style="flex:1;">
            <div style="font-size:12px;font-weight:600;color:#fff;">Lokasi GPS</div>
            <div id="gps-text" style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:1px;">Mencari lokasi...</div>
        </div>
        <button onclick="getLocation()" style="width:32px;height:32px;background:rgba(255,255,255,0.06);border:1px solid var(--border);border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;" class="tap">
            <i data-lucide="refresh-cw" style="width:14px;height:14px;color:rgba(255,255,255,0.4);"></i>
        </button>
    </div>

    {{-- FORM --}}
    <div class="card" style="padding:20px;margin-bottom:12px;">
        <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:16px;">Detail Kunjungan</div>

        <div style="margin-bottom:16px;">
            <div style="font-size:12px;color:rgba(255,255,255,0.5);font-weight:500;margin-bottom:8px;">Pelanggan <span style="color:#FB7185;">*</span></div>
            <select id="pelanggan-select" class="input-dark" style="font-size:14px;padding:13px 16px;">
                <option value="">Pilih pelanggan...</option>
            </select>
        </div>

        <div style="margin-bottom:16px;">
            <div style="font-size:12px;color:rgba(255,255,255,0.5);font-weight:500;margin-bottom:8px;">Keterangan</div>
            <textarea id="keterangan" rows="3" placeholder="Contoh: Menawarkan produk baru, cek stok..."
                style="width:100%;padding:14px 16px;background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:14px;color:#fff;font-size:14px;outline:none;resize:none;font-family:inherit;"></textarea>
        </div>

        <div>
            <div style="font-size:12px;color:rgba(255,255,255,0.5);font-weight:500;margin-bottom:8px;">Foto Kunjungan</div>
            <div onclick="document.getElementById('foto-input').click()" id="foto-zone"
                style="border:2px dashed rgba(255,255,255,0.1);border-radius:14px;padding:24px;text-align:center;cursor:pointer;">
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

    <div style="display:flex;align-items:flex-start;gap:10px;padding:14px 16px;background:rgba(13,148,136,0.08);border:1px solid rgba(13,148,136,0.2);border-radius:14px;">
        <i data-lucide="info" style="width:16px;height:16px;color:#2DD4BF;flex-shrink:0;margin-top:1px;"></i>
        <span style="font-size:12px;color:rgba(255,255,255,0.5);">Pastikan GPS aktif untuk mencatat lokasi kunjungan dengan akurat.</span>
    </div>
</div>

<div style="position:fixed;bottom:0;left:0;right:0;padding:16px;background:linear-gradient(to top,var(--bg-base) 60%,transparent);z-index:45;">
    <button onclick="saveKunjungan()" class="tap"
        style="width:100%;padding:16px;border:none;border-radius:16px;font-size:15px;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#0D9488,#0F766E);box-shadow:0 4px 20px rgba(13,148,136,0.3);">
        <i data-lucide="map-pin" style="width:20px;height:20px;"></i>
        Simpan Kunjungan
    </button>
</div>
@endsection

@push('scripts')
<script>
let pelangganData = [];
let fotoBase64 = null;
let currentPosition = null;

document.addEventListener('DOMContentLoaded', async function() {
    await loadPelanggan();
    getLocation();
    lucide.createIcons();
});

async function loadPelanggan() {
    try {
        const division = '{{ auth()->user()->division ?? "minyak" }}';
        const response = await fetch(`/api/v1/${division}/pelanggan?per_page=1000`);
        if (response.ok) {
            const result = await response.json();
            pelangganData = result.data?.data || [];
        } else if (window.salesPWA) {
            pelangganData = await window.salesPWA.getOfflineData('pelanggan');
        }
        renderPelangganOptions();
    } catch (error) {
        console.error('Failed to load pelanggan:', error);
        if (window.salesPWA) { pelangganData = await window.salesPWA.getOfflineData('pelanggan'); renderPelangganOptions(); }
    }
}

function renderPelangganOptions() {
    const select = document.getElementById('pelanggan-select');
    select.innerHTML = '<option value="">Pilih pelanggan...</option>' + pelangganData.map(p => `<option value="${p.id}">${p.nama_toko} - ${p.nama_pemilik}</option>`).join('');
}

function getLocation() {
    const gpsText = document.getElementById('gps-text');
    const gpsStatus = document.getElementById('gps-status');
    gpsText.textContent = 'Mencari lokasi...';
    if (!navigator.geolocation) { gpsText.textContent = 'GPS tidak didukung'; gpsStatus.style.background='rgba(225,29,72,0.08)'; gpsStatus.style.borderColor='rgba(251,113,133,0.2)'; return; }
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            currentPosition = { lat: pos.coords.latitude, lng: pos.coords.longitude };
            gpsText.textContent = `${currentPosition.lat.toFixed(4)}, ${currentPosition.lng.toFixed(4)}`;
            gpsStatus.style.background='rgba(5,150,105,0.08)'; gpsStatus.style.borderColor='rgba(52,211,153,0.2)';
        },
        (err) => { gpsText.textContent = 'Gagal mendapatkan lokasi'; gpsStatus.style.background='rgba(225,29,72,0.08)'; gpsStatus.style.borderColor='rgba(251,113,133,0.2)'; console.error(err); },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
    );
}

function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { fotoBase64 = e.target.result; document.getElementById('foto-preview').style.display='block'; document.getElementById('foto-preview').querySelector('img').src = fotoBase64; document.getElementById('foto-zone').style.display='none'; };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearFoto() { fotoBase64=null; document.getElementById('foto-input').value=''; document.getElementById('foto-preview').style.display='none'; document.getElementById('foto-zone').style.display='block'; }

async function saveKunjungan() {
    const pelangganId = document.getElementById('pelanggan-select').value;
    if (!pelangganId) { showToast('Pilih pelanggan terlebih dahulu', 'error'); return; }
    showLoading(true);
    const selectedPelanggan = pelangganData.find(p => p.id == pelangganId);
    const data = { localId: `kunjungan_${Date.now()}`, pelangganId: pelangganId, namaPelanggan: selectedPelanggan?.nama_toko || '-', keterangan: document.getElementById('keterangan').value, latitude: currentPosition?.lat, longitude: currentPosition?.lng, fotoKunjungan: fotoBase64, tanggal: new Date().toISOString(), syncStatus: 'pending', createdAt: Date.now() };
    try {
        const division = '{{ auth()->user()->division ?? "minyak" }}';
        const response = await fetch(`/api/v1/${division}/kunjungan`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify(data) });
        if (response.ok) { showToast('Kunjungan berhasil tercatat', 'success'); window.location.href = '{{ route('sales.dashboard') }}'; }
        else throw new Error('Server error');
    } catch (error) {
        if (window.salesPWA) { await window.salesPWA.saveOffline(data, 'kunjungan_pending'); showToast('Kunjungan tersimpan (offline mode)', 'info'); window.location.href = '{{ route('sales.dashboard') }}'; }
        else showToast('Gagal menyimpan kunjungan', 'error');
    } finally { showLoading(false); }
}
</script>
@endpush
