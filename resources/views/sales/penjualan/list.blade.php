@extends('layouts.sales-mobile')
@section('title','Riwayat Penjualan')
@section('header-title','Penjualan')
@section('header-subtitle','Riwayat transaksi')

@section('back-button')
<a href="{{ route('sales.menu') }}" style="width:34px;height:34px;border:1px solid var(--border);background:var(--bg-elevated);border-radius:10px;display:flex;align-items:center;justify-content:center;">
    <i data-lucide="arrow-left" style="width:16px;height:16px;color:rgba(255,255,255,0.7);"></i>
</a>
@endsection

@section('content')
<div style="padding:16px 16px 110px;">

    {{-- FILTER --}}
    <div style="display:flex;gap:8px;margin-bottom:16px;overflow-x:auto;padding-bottom:4px;">
        <button onclick="setFilter('all')" id="filter-all"
            style="padding:8px 16px;border-radius:99px;font-size:13px;font-weight:600;white-space:nowrap;cursor:pointer;border:none;background:#7C3AED;color:#fff;">Semua</button>
        <button onclick="setFilter('today')" id="filter-today"
            style="padding:8px 16px;border-radius:99px;font-size:13px;font-weight:600;white-space:nowrap;cursor:pointer;border:1px solid var(--border);background:transparent;color:rgba(255,255,255,0.4);">Hari Ini</button>
        <button onclick="setFilter('week')" id="filter-week"
            style="padding:8px 16px;border-radius:99px;font-size:13px;font-weight:600;white-space:nowrap;cursor:pointer;border:1px solid var(--border);background:transparent;color:rgba(255,255,255,0.4);">Minggu Ini</button>
    </div>

    {{-- STATS --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;">
        <div class="card-sm" style="padding:14px 16px;text-align:center;">
            <div id="stat-total" style="font-size:22px;font-weight:800;color:#A78BFA;">0</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:3px;">Transaksi</div>
        </div>
        <div class="card-sm" style="padding:14px 16px;text-align:center;">
            <div id="stat-amount" style="font-size:16px;font-weight:800;color:#34D399;">Rp 0</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:3px;">Total</div>
        </div>
    </div>

    {{-- LIST --}}
    <div id="penjualan-list">
        <div class="skeleton" style="height:80px;margin-bottom:8px;"></div>
        <div class="skeleton" style="height:80px;margin-bottom:8px;"></div>
        <div class="skeleton" style="height:80px;"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let penjualanData = [];
let currentFilter = 'all';

document.addEventListener('DOMContentLoaded', async function() {
    await loadPenjualan();
    lucide.createIcons();
});

async function loadPenjualan() {
    try {
        const division = '{{ auth()->user()->division ?? "minyak" }}';
        const response = await fetch(`/api/v1/${division}/penjualan`);
        if (response.ok) {
            const result = await response.json();
            penjualanData = result.data?.data || [];
        }
        renderPenjualanList();
    } catch (error) {
        console.error('Failed to load penjualan:', error);
        document.getElementById('penjualan-list').innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i data-lucide="wifi-off" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                <p>Mode offline - Data tidak tersedia</p>
            </div>
        `;
        lucide.createIcons();
    }
}

function setFilter(filter) {
    currentFilter = filter;
    ['all','today','week'].forEach(f => {
        const b = document.getElementById(`filter-${f}`);
        if(f===filter){ b.style.background='#7C3AED'; b.style.color='#fff'; b.style.border='none'; }
        else { b.style.background='transparent'; b.style.color='rgba(255,255,255,0.4)'; b.style.border='1px solid var(--border)'; }
    });
    renderPenjualanList();
}

function getFilteredData() {
    if (currentFilter === 'all') return penjualanData;
    const today = new Date().toISOString().split('T')[0];
    const weekAgo = new Date(); weekAgo.setDate(weekAgo.getDate() - 7);
    return penjualanData.filter(p => {
        const date = p.tanggal_jual || p.created_at;
        if (currentFilter === 'today') return date?.startsWith(today);
        if (currentFilter === 'week') return new Date(date) >= weekAgo;
        return true;
    });
}

function renderPenjualanList() {
    const container = document.getElementById('penjualan-list');
    const filtered = getFilteredData();
    const totalCount = filtered.length;
    const totalAmount = filtered.reduce((sum, p) => sum + (p.total || 0), 0);
    document.getElementById('stat-total').textContent = totalCount;
    document.getElementById('stat-amount').textContent = formatRupiah(totalAmount);

    if (filtered.length === 0) {
        container.innerHTML = `<div style="text-align:center;padding:48px 20px;color:rgba(255,255,255,0.3);"><div style="font-size:36px;margin-bottom:12px;">🛒</div><div style="font-size:13px;">Tidak ada penjualan</div></div>`;
        return;
    }

    const sorted = [...filtered].sort((a, b) => new Date(b.tanggal_jual||b.created_at) - new Date(a.tanggal_jual||a.created_at));
    const tipeColor = { tunai:'rgba(5,150,105,0.15)', hutang:'rgba(225,29,72,0.15)', transfer:'rgba(37,99,235,0.15)' };
    const tipeText = { tunai:'#34D399', hutang:'#FB7185', transfer:'#60A5FA' };
    const tipeBorder = { tunai:'rgba(52,211,153,0.2)', hutang:'rgba(251,113,133,0.2)', transfer:'rgba(96,165,250,0.2)' };

    container.innerHTML = sorted.map(p => {
        const t = p.tipe_bayar || 'tunai';
        return `<div class="card" style="padding:16px 18px;margin-bottom:8px;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;">
                <div><div style="font-size:13px;font-weight:600;color:#fff;">${p.invoice||'#'+p.id}</div><div style="font-size:11px;color:rgba(255,255,255,0.35);margin-top:2px;">${formatDate(p.tanggal_jual||p.created_at)}</div></div>
                <span style="padding:3px 10px;border-radius:99px;font-size:10px;font-weight:700;background:${tipeColor[t]||'rgba(255,255,255,0.08)'};color:${tipeText[t]||'#fff'};border:1px solid ${tipeBorder[t]||'var(--border)'}">${t.toUpperCase()}</span>
            </div>
            <div style="height:1px;background:var(--border);margin-bottom:10px;"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div><div style="font-size:12px;color:rgba(255,255,255,0.5);">${p.pelanggan?.nama_toko||'-'}</div><div style="font-size:11px;color:rgba(255,255,255,0.3);margin-top:2px;">${p.produk?.nama||'-'} &times; ${p.jumlah}</div></div>
                <div style="font-size:16px;font-weight:800;color:#fff;">${formatRupiah(p.total||0)}</div>
            </div>
            ${p.status==='pending'?`<div style="margin-top:8px;padding:6px 10px;background:rgba(217,119,6,0.1);border-radius:8px;display:inline-flex;align-items:center;gap:5px;"><span style="font-size:10px;color:#FCD34D;font-weight:600;">⏳ Menunggu Sinkronisasi</span></div>`:''}
        </div>`;
    }).join('');
    lucide.createIcons();
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
}
</script>
@endpush
