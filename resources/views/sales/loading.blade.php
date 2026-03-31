@extends('layouts.sales-mobile')
@section('title','Stok Kendaraan')
@section('header-title','Stok Loading')
@section('header-subtitle','Produk di kendaraan hari ini')

@section('back-button')
<a href="{{ route('sales.menu') }}" style="width:34px;height:34px;border:1px solid var(--border);background:var(--bg-elevated);border-radius:10px;display:flex;align-items:center;justify-content:center;">
    <i data-lucide="arrow-left" style="width:16px;height:16px;color:rgba(255,255,255,0.7);"></i>
</a>
@endsection

@section('content')
<div style="padding:16px 16px 110px;">

    {{-- INFO --}}
    <div class="card-glow anim-up" style="padding:20px;margin-bottom:16px;position:relative;overflow:hidden;">
        <div class="orb orb-violet" style="width:160px;height:160px;top:-60px;right:-40px;"></div>
        <div style="position:relative;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-bottom:4px;">Tanggal Loading</div>
                <div id="loading-tanggal" style="font-size:17px;font-weight:700;color:#fff;">-</div>
                <div style="display:flex;align-items:center;gap:5px;margin-top:6px;">
                    <i data-lucide="map-pin" style="width:12px;height:12px;color:#A78BFA;"></i>
                    <span id="loading-tujuan" style="font-size:12px;color:rgba(255,255,255,0.45);">-</span>
                </div>
            </div>
            <div class="grad-cyan" style="width:52px;height:52px;border-radius:16px;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="truck" style="width:24px;height:24px;color:#fff;"></i>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:16px;">
        <div class="card-sm" style="padding:14px 12px;text-align:center;">
            <div id="stat-total" style="font-size:22px;font-weight:800;color:#fff;">0</div>
            <div style="font-size:10px;color:rgba(255,255,255,0.4);margin-top:3px;">Total Item</div>
        </div>
        <div class="card-sm" style="padding:14px 12px;text-align:center;">
            <div id="stat-terjual" style="font-size:22px;font-weight:800;color:#34D399;">0</div>
            <div style="font-size:10px;color:rgba(255,255,255,0.4);margin-top:3px;">Terjual</div>
        </div>
        <div class="card-sm" style="padding:14px 12px;text-align:center;">
            <div id="stat-sisa" style="font-size:22px;font-weight:800;color:#67E8F9;">0</div>
            <div style="font-size:10px;color:rgba(255,255,255,0.4);margin-top:3px;">Sisa</div>
        </div>
    </div>

    {{-- PRODUK LIST --}}
    <div class="card" style="overflow:hidden;">
        <div style="padding:14px 18px;border-bottom:1px solid var(--border);">
            <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.8px;">Detail Produk</div>
        </div>
        <div id="produk-list">
            <div style="padding:20px 18px;">
                <div class="skeleton" style="height:60px;margin-bottom:8px;"></div>
                <div class="skeleton" style="height:60px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    await loadLoading();
    lucide.createIcons();
});

async function loadLoading() {
    try {
        const division = '{{ auth()->user()->division ?? "minyak" }}';
        const response = await fetch(`/api/v1/${division}/dashboard`);
        if (response.ok) {
            const data = await response.json();
            const loading = data.data?.loadingHariIni;
            if (loading) displayLoading(loading);
            else {
                document.getElementById('produk-list').innerHTML = `<div class="p-8 text-center text-gray-500"><i data-lucide="truck" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i><p>Belum ada loading hari ini</p></div>`;
                lucide.createIcons();
            }
        }
    } catch (error) {
        console.error('Failed to load loading:', error);
        document.getElementById('produk-list').innerHTML = `<div class="p-8 text-center text-gray-500"><i data-lucide="wifi-off" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i><p>Mode offline - Data tidak tersedia</p></div>`;
        lucide.createIcons();
    }
}

function displayLoading(loading) {
    document.getElementById('loading-tanggal').textContent = formatDate(loading.tanggal_loading);
    document.getElementById('loading-tujuan').textContent = loading.wilayah?.nama || loading.tujuan || '-';
    
    const details = loading.detail || [];
    const totalItem = details.length;
    const totalTerjual = details.reduce((sum, d) => sum + (d.terjual || 0), 0);
    const totalSisa = details.reduce((sum, d) => sum + (d.sisa || 0), 0);
    
    document.getElementById('stat-total').textContent = totalItem;
    document.getElementById('stat-terjual').textContent = totalTerjual;
    document.getElementById('stat-sisa').textContent = totalSisa;
    
    const container = document.getElementById('produk-list');
    if (details.length === 0) {
        container.innerHTML = `<div class="p-8 text-center text-gray-500"><p>Tidak ada produk dalam loading</p></div>`;
        return;
    }
    
    container.innerHTML = details.map((item, idx) => {
        const progress = item.jumlahLoading > 0 ? Math.round((item.terjual/item.jumlahLoading)*100) : 0;
        const isActive = item.status === 'open';
        return `<div style="padding:16px 18px;${idx>0?'border-top:1px solid var(--border)':''};">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#0891B2,#164E63);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="package" style="width:20px;height:20px;color:#fff;"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#fff;">${item.produk?.nama||'-'}</div>
                        <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:1px;">${item.produk?.satuan||'-'}</div>
                    </div>
                </div>
                <span style="padding:3px 10px;border-radius:99px;font-size:10px;font-weight:700;background:${isActive?'rgba(5,150,105,0.15)':'rgba(255,255,255,0.06)'};color:${isActive?'#34D399':'rgba(255,255,255,0.3)'};border:1px solid ${isActive?'rgba(52,211,153,0.2)':'var(--border)'}">${isActive?'AKTIF':'CLOSED'}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="font-size:11px;color:rgba(255,255,255,0.4);">Loading: <b style="color:#fff;">${item.jumlahLoading}</b></span>
                <span style="font-size:11px;color:#34D399;">Terjual: <b>${item.terjual||0}</b></span>
                <span style="font-size:11px;color:#67E8F9;">Sisa: <b>${item.sisa||item.jumlahLoading}</b></span>
            </div>
            <div class="progress-track" style="height:6px;">
                <div class="progress-fill" style="width:${progress}%;height:100%;"></div>
            </div>
            <div style="text-align:right;margin-top:4px;font-size:10px;color:rgba(255,255,255,0.3);">${progress}% terjual</div>
        </div>`;
    }).join('');
    lucide.createIcons();
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}
</script>
@endpush
