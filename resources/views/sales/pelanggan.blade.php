@extends('layouts.sales-mobile')
@section('title','Data Pelanggan')
@section('header-title','Pelanggan')
@section('header-subtitle','Data pelanggan aktif')

@section('back-button')
<a href="{{ route('sales.menu') }}" style="width:34px;height:34px;border:1px solid var(--border);background:var(--bg-elevated);border-radius:10px;display:flex;align-items:center;justify-content:center;">
    <i data-lucide="arrow-left" style="width:16px;height:16px;color:rgba(255,255,255,0.7);"></i>
</a>
@endsection

@section('content')
<div style="padding:16px 16px 110px;">

    {{-- SEARCH --}}
    <div style="position:relative;margin-bottom:14px;">
        <i data-lucide="search" style="width:16px;height:16px;color:rgba(255,255,255,0.3);position:absolute;left:16px;top:50%;transform:translateY(-50%);"></i>
        <input type="text" id="search-pelanggan" placeholder="Cari nama toko atau pemilik..."
            class="input-dark" style="padding:13px 16px 13px 44px;font-size:14px;" autocomplete="off">
    </div>

    {{-- STATS --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
        <div class="card-sm" style="padding:14px 16px;text-align:center;">
            <div id="stat-total" style="font-size:22px;font-weight:800;color:#A78BFA;">0</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:3px;">Total Pelanggan</div>
        </div>
        <div class="card-sm" style="padding:14px 16px;text-align:center;">
            <div id="stat-ecer" style="font-size:22px;font-weight:800;color:#60A5FA;">0</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:3px;">Ecer</div>
        </div>
    </div>

    {{-- LIST --}}
    <div id="pelanggan-list">
        <div class="skeleton" style="height:80px;margin-bottom:8px;"></div>
        <div class="skeleton" style="height:80px;margin-bottom:8px;"></div>
        <div class="skeleton" style="height:80px;"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let pelangganData = [];
let filteredData = [];

document.addEventListener('DOMContentLoaded', async function() {
    await loadPelanggan();
    document.getElementById('search-pelanggan').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        filteredData = pelangganData.filter(p => p.nama_toko.toLowerCase().includes(query) || p.nama_pemilik.toLowerCase().includes(query) || p.alamat.toLowerCase().includes(query) || p.kecamatan.toLowerCase().includes(query));
        renderPelangganList(filteredData);
    });
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
        filteredData = pelangganData;
        updateStats();
        renderPelangganList(filteredData);
    } catch (error) {
        console.error('Failed to load pelanggan:', error);
        if (window.salesPWA) { pelangganData = await window.salesPWA.getOfflineData('pelanggan'); filteredData = pelangganData; updateStats(); renderPelangganList(filteredData); }
        else {
            document.getElementById('pelanggan-list').innerHTML = `<div class="text-center py-8 text-gray-500"><i data-lucide="wifi-off" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i><p>Mode offline - Data tidak tersedia</p></div>`;
            lucide.createIcons();
        }
    }
}

function updateStats() {
    document.getElementById('stat-total').textContent = pelangganData.length;
    document.getElementById('stat-ecer').textContent = pelangganData.filter(p => p.tipe === 'ecer').length;
}

function renderPelangganList(data) {
    const container = document.getElementById('pelanggan-list');
    if (data.length === 0) {
        container.innerHTML = `<div style="text-align:center;padding:48px 20px;color:rgba(255,255,255,0.3);"><div style="font-size:36px;margin-bottom:12px;">👥</div><div style="font-size:13px;">${pelangganData.length===0?'Belum ada data pelanggan':'Tidak ditemukan'}</div></div>`;
        return;
    }
    container.innerHTML = data.map(p => `
        <div class="card" style="padding:14px 16px;margin-bottom:8px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:44px;height:44px;border-radius:13px;background:linear-gradient(135deg,#7C3AED,#5B21B6);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;flex-shrink:0;">${p.nama_toko.charAt(0).toUpperCase()}</div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:2px;">
                        <div style="font-size:13px;font-weight:600;color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${p.nama_toko}</div>
                        <span style="padding:2px 7px;border-radius:99px;font-size:9px;font-weight:700;background:${p.tipe==='ecer'?'rgba(52,211,153,0.12)':'rgba(96,165,250,0.12)'};color:${p.tipe==='ecer'?'#34D399':'#60A5FA'};border:1px solid ${p.tipe==='ecer'?'rgba(52,211,153,0.2)':'rgba(96,165,250,0.2)'};flex-shrink:0;">${(p.tipe||'').toUpperCase()}</span>
                    </div>
                    <div style="font-size:11px;color:rgba(255,255,255,0.4);">${p.nama_pemilik} &bull; ${p.kecamatan||''}</div>
                    ${p.total_hutang>0?`<div style="font-size:11px;color:#FB7185;margin-top:2px;">Hutang: ${formatRupiah(p.total_hutang)}</div>`:''}
                </div>
            </div>
            <div style="display:flex;gap:8px;margin-top:12px;padding-top:10px;border-top:1px solid var(--border);">
                ${p.telepon?`<a href="tel:${p.telepon}" style="flex:1;padding:9px;background:rgba(96,165,250,0.1);border:1px solid rgba(96,165,250,0.2);border-radius:10px;color:#60A5FA;font-size:12px;font-weight:600;text-align:center;text-decoration:none;">📞 Hubungi</a>`:''}
                <button onclick="buatPenjualan(${p.id})" style="flex:1;padding:9px;background:rgba(52,211,153,0.1);border:1px solid rgba(52,211,153,0.2);border-radius:10px;color:#34D399;font-size:12px;font-weight:600;cursor:pointer;">🛒 Jual</button>
            </div>
        </div>
    `).join('');
    lucide.createIcons();
}

function buatPenjualan(pelangganId) {
    const pelanggan = pelangganData.find(p => p.id === pelangganId);
    if (pelanggan) { sessionStorage.setItem('selected_pelanggan', JSON.stringify(pelanggan)); window.location.href = '{{ route('sales.penjualan.produk') }}'; }
}

function formatRupiah(amount) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount); }
</script>
@endpush
