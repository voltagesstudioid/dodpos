@extends('layouts.sales-mobile')
@section('title','Input Penjualan')
@section('header-title','Input Penjualan')
@section('header-subtitle','Step 1 dari 2 — Pilih Pelanggan')

@section('back-button')
<a href="{{ route('sales.dashboard') }}" style="width:34px;height:34px;border:1px solid var(--border);background:var(--bg-elevated);border-radius:10px;display:flex;align-items:center;justify-content:center;">
    <i data-lucide="arrow-left" style="width:16px;height:16px;color:rgba(255,255,255,0.7);"></i>
</a>
@endsection

@section('content')
<div style="padding:16px 16px 110px;">

    {{-- STEP INDICATOR --}}
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
        <div style="flex:1;height:3px;background:#7C3AED;border-radius:99px;"></div>
        <div style="flex:1;height:3px;background:rgba(255,255,255,0.1);border-radius:99px;"></div>
        <span style="font-size:11px;color:rgba(255,255,255,0.4);font-weight:500;white-space:nowrap;">1 / 2</span>
    </div>

    {{-- SEARCH --}}
    <div style="position:relative;margin-bottom:16px;">
        <i data-lucide="search" style="width:16px;height:16px;color:rgba(255,255,255,0.3);position:absolute;left:16px;top:50%;transform:translateY(-50%);"></i>
        <input id="search-pelanggan" type="text" placeholder="Cari nama toko atau pemilik..."
            class="input-dark" style="padding:14px 16px 14px 44px;font-size:14px;" autocomplete="off">
    </div>

    {{-- SELECTED BADGE --}}
    <div id="selected-pelanggan" style="display:none;background:rgba(124,58,237,0.12);border:1px solid rgba(124,58,237,0.3);border-radius:16px;padding:14px 16px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="font-size:10px;color:#A78BFA;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Terpilih</div>
                <div id="selected-nama" style="font-size:15px;font-weight:700;color:#fff;">-</div>
                <div id="selected-alamat" style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">-</div>
            </div>
            <button onclick="clearSelection()" style="width:32px;height:32px;background:rgba(251,113,133,0.15);border:1px solid rgba(251,113,133,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                <i data-lucide="x" style="width:14px;height:14px;color:#FB7185;"></i>
            </button>
        </div>
    </div>

    {{-- LIST --}}
    <div id="pelanggan-list">
        <div class="skeleton" style="height:72px;margin-bottom:8px;"></div>
        <div class="skeleton" style="height:72px;margin-bottom:8px;"></div>
        <div class="skeleton" style="height:72px;"></div>
    </div>
</div>

{{-- BOTTOM CTA --}}
<div style="position:fixed;bottom:0;left:0;right:0;padding:16px;background:linear-gradient(to top,var(--bg-base) 60%,transparent);z-index:45;">
    <button id="btn-lanjut" onclick="lanjutKeProduk()" disabled
        style="width:100%;padding:16px;border:none;border-radius:16px;font-size:15px;font-weight:700;color:#fff;cursor:not-allowed;opacity:0.4;display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#7C3AED,#5B21B6);transition:opacity 0.2s;">
        Pilih Produk
        <i data-lucide="arrow-right" style="width:18px;height:18px;"></i>
    </button>
</div>
@endsection

@push('scripts')
<script>
let pelangganData = [];
let filteredData = [];
let selectedPelanggan = null;

document.addEventListener('DOMContentLoaded', async function() {
    await loadPelanggan();
    
    // Search handler
    document.getElementById('search-pelanggan').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        filteredData = pelangganData.filter(p => 
            p.nama_toko.toLowerCase().includes(query) || 
            p.nama_pemilik.toLowerCase().includes(query) ||
            p.alamat.toLowerCase().includes(query)
        );
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
            filteredData = pelangganData;
        } else {
            // Fallback to cache
            if (window.salesPWA) {
                pelangganData = await window.salesPWA.getOfflineData('pelanggan');
                filteredData = pelangganData;
            }
        }
        renderPelangganList(filteredData);
    } catch (error) {
        console.error('Failed to load pelanggan:', error);
        // Try cache
        if (window.salesPWA) {
            pelangganData = await window.salesPWA.getOfflineData('pelanggan');
            filteredData = pelangganData;
            renderPelangganList(filteredData);
        }
    }
}

function renderPelangganList(data) {
    const container = document.getElementById('pelanggan-list');
    if (data.length === 0) {
        container.innerHTML = `<div style="text-align:center;padding:40px 20px;color:rgba(255,255,255,0.3);"><i data-lucide="users" style="width:40px;height:40px;margin:0 auto 12px;"></i><p style="font-size:13px;">${pelangganData.length===0?'Belum ada data pelanggan':'Tidak ditemukan'}</p></div>`;
        lucide.createIcons(); return;
    }
    container.innerHTML = data.map(p => {
        const sel = selectedPelanggan?.id === p.id;
        const border = sel ? 'rgba(124,58,237,0.5)' : 'var(--border)';
        const bg = sel ? 'rgba(124,58,237,0.1)' : 'var(--bg-elevated)';
        return `<div onclick="selectPelanggan(${p.id})" class="tap-sm" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:${bg};border:1px solid ${border};border-radius:16px;margin-bottom:8px;cursor:pointer;transition:all 0.15s;">
            <div style="width:44px;height:44px;border-radius:13px;background:linear-gradient(135deg,#7C3AED,#5B21B6);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;flex-shrink:0;">${p.nama_toko.charAt(0).toUpperCase()}</div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:14px;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${p.nama_toko}</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">${p.nama_pemilik} &bull; ${p.kecamatan||''}</div>
                ${p.total_hutang>0?`<div style="font-size:11px;color:#FB7185;margin-top:3px;">Hutang: ${formatRupiah(p.total_hutang)}</div>`:''}
            </div>
            ${sel?'<i data-lucide="check-circle" style="width:18px;height:18px;color:#A78BFA;flex-shrink:0;"></i>':''}
        </div>`;
    }).join('');
    lucide.createIcons();
}

function selectPelanggan(id) {
    selectedPelanggan = pelangganData.find(p => p.id === id);
    if (selectedPelanggan) {
        document.getElementById('selected-pelanggan').style.display = 'block';
        document.getElementById('selected-nama').textContent = selectedPelanggan.nama_toko;
        document.getElementById('selected-alamat').textContent = selectedPelanggan.alamat;
        const btn = document.getElementById('btn-lanjut');
        btn.disabled = false; btn.style.opacity = '1'; btn.style.cursor = 'pointer';
        renderPelangganList(filteredData);
    }
}

function clearSelection() {
    selectedPelanggan = null;
    document.getElementById('selected-pelanggan').style.display = 'none';
    const btn = document.getElementById('btn-lanjut');
    btn.disabled = true; btn.style.opacity = '0.4'; btn.style.cursor = 'not-allowed';
    renderPelangganList(filteredData);
}

function lanjutKeProduk() {
    if (selectedPelanggan) {
        // Save to session storage
        sessionStorage.setItem('selected_pelanggan', JSON.stringify(selectedPelanggan));
        window.location.href = '{{ route('sales.penjualan.produk') }}';
    }
}

function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}
</script>
@endpush
