@extends('layouts.sales-mobile')
@section('title','Riwayat Kunjungan')
@section('header-title','Kunjungan')
@section('header-subtitle','Histori kunjungan pelanggan')

@section('back-button')
<a href="{{ route('sales.menu') }}" style="width:34px;height:34px;border:1px solid var(--border);background:var(--bg-elevated);border-radius:10px;display:flex;align-items:center;justify-content:center;">
    <i data-lucide="arrow-left" style="width:16px;height:16px;color:rgba(255,255,255,0.7);"></i>
</a>
@endsection

@section('content')
<div style="padding:16px 16px 110px;">

    {{-- STATS --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;">
        <div class="card-sm" style="padding:14px 16px;text-align:center;">
            <div id="stat-total" style="font-size:22px;font-weight:800;color:#2DD4BF;">0</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:3px;">Total Kunjungan</div>
        </div>
        <div class="card-sm" style="padding:14px 16px;text-align:center;">
            <div id="stat-today" style="font-size:22px;font-weight:800;color:#34D399;">0</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:3px;">Hari Ini</div>
        </div>
    </div>

    {{-- LIST --}}
    <div id="kunjungan-list">
        <div class="skeleton" style="height:90px;margin-bottom:8px;"></div>
        <div class="skeleton" style="height:90px;"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let kunjunganData = [];

document.addEventListener('DOMContentLoaded', async function() {
    await loadKunjungan();
    lucide.createIcons();
});

async function loadKunjungan() {
    try {
        const division = '{{ auth()->user()->division ?? "minyak" }}';
        const response = await fetch(`/api/v1/${division}/kunjungan`);
        if (response.ok) {
            const result = await response.json();
            kunjunganData = result.data?.data || [];
        }
        renderKunjunganList();
    } catch (error) {
        console.error('Failed to load kunjungan:', error);
        document.getElementById('kunjungan-list').innerHTML = `<div style="text-align:center;padding:40px 20px;color:rgba(255,255,255,0.3);"><div style="font-size:32px;margin-bottom:10px;">📡</div><div style="font-size:13px;">Mode offline - Data tidak tersedia</div></div>`;
    }
}

function renderKunjunganList() {
    const container = document.getElementById('kunjungan-list');
    const today = new Date().toISOString().split('T')[0];
    const todayCount = kunjunganData.filter(k => k.tanggal?.startsWith(today)).length;
    document.getElementById('stat-total').textContent = kunjunganData.length;
    document.getElementById('stat-today').textContent = todayCount;

    if (kunjunganData.length === 0) {
        container.innerHTML = `<div style="text-align:center;padding:48px 20px;color:rgba(255,255,255,0.3);"><div style="font-size:36px;margin-bottom:12px;">📍</div><div style="font-size:13px;">Belum ada kunjungan tercatat</div></div>`;
        return;
    }

    const sorted = [...kunjunganData].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    container.innerHTML = sorted.map(k => `
        <div class="card" style="padding:16px 18px;margin-bottom:8px;">
            <div style="display:flex;align-items:flex-start;gap:12px;">
                <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#0D9488,#0F766E);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-lucide="map-pin" style="width:18px;height:18px;color:#fff;"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:14px;font-weight:600;color:#fff;">${k.pelanggan?.nama_toko||'-'}</div>
                    <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">${k.pelanggan?.nama_pemilik||'-'}</div>
                    <div style="font-size:11px;color:#2DD4BF;margin-top:4px;">${formatDate(k.created_at)}</div>
                    ${k.keterangan?`<div style="font-size:12px;color:rgba(255,255,255,0.5);margin-top:8px;padding:8px 12px;background:rgba(255,255,255,0.04);border-radius:10px;">${k.keterangan}</div>`:''}
                </div>
            </div>
            ${k.foto?`<div style="margin-top:12px;"><img src="${k.foto}" alt="Foto" style="width:100%;height:120px;object-fit:cover;border-radius:12px;"></div>`:''}
        </div>
    `).join('');
    lucide.createIcons();
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}
</script>
@endpush
