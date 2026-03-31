@extends('layouts.sales-mobile')
@section('title','Hutang Pelanggan')
@section('header-title','Hutang')
@section('header-subtitle','Piutang yang belum dibayar')

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
                <div style="font-size:11px;color:rgba(255,255,255,0.4);font-weight:500;margin-bottom:6px;">Total Piutang</div>
                <div id="total-hutang" style="font-size:28px;font-weight:800;color:#FB7185;letter-spacing:-1px;">Rp 0</div>
            </div>
            <div style="text-align:right;">
                <div class="grad-rose" style="width:52px;height:52px;border-radius:16px;display:flex;align-items:center;justify-content:center;margin-left:auto;">
                    <i data-lucide="credit-card" style="width:24px;height:24px;color:#fff;"></i>
                </div>
                <div style="margin-top:8px;">
                    <div id="count-hutang" style="font-size:20px;font-weight:800;color:#fff;">0</div>
                    <div style="font-size:11px;color:rgba(255,255,255,0.4);">pelanggan</div>
                </div>
            </div>
        </div>
    </div>

    {{-- LIST --}}
    <div id="hutang-list">
        <div class="skeleton" style="height:90px;margin-bottom:8px;"></div>
        <div class="skeleton" style="height:90px;"></div>
    </div>
</div>

{{-- BAYAR MODAL --}}
<div id="bayar-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);backdrop-filter:blur(8px);z-index:100;align-items:flex-end;">
    <div style="background:var(--bg-surface);border:1px solid var(--border);border-radius:24px 24px 0 0;width:100%;padding:24px;padding-bottom:max(24px,env(safe-area-inset-bottom));">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <div style="font-size:16px;font-weight:700;color:#fff;">Bayar Hutang</div>
            <button onclick="closeModal()" style="width:32px;height:32px;background:rgba(255,255,255,0.07);border:1px solid var(--border);border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                <i data-lucide="x" style="width:14px;height:14px;color:rgba(255,255,255,0.5);"></i>
            </button>
        </div>
        <div style="background:rgba(251,113,133,0.08);border:1px solid rgba(251,113,133,0.15);border-radius:14px;padding:14px 16px;margin-bottom:16px;">
            <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-bottom:2px;">Pelanggan</div>
            <div id="modal-pelanggan" style="font-size:15px;font-weight:700;color:#fff;">-</div>
            <div style="height:1px;background:rgba(255,255,255,0.06);margin:10px 0;"></div>
            <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-bottom:2px;">Sisa Hutang</div>
            <div id="modal-sisa" style="font-size:20px;font-weight:800;color:#FB7185;">Rp 0</div>
        </div>
        <div style="margin-bottom:16px;">
            <div style="font-size:12px;color:rgba(255,255,255,0.5);font-weight:500;margin-bottom:8px;">Jumlah Bayar</div>
            <div style="position:relative;">
                <span style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.4);font-weight:600;">Rp</span>
                <input type="number" id="bayar-input" placeholder="0"
                    style="width:100%;padding:14px 16px 14px 44px;background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:14px;color:#fff;font-size:20px;font-weight:700;outline:none;">
            </div>
        </div>
        <button onclick="submitBayar()" class="tap"
            style="width:100%;padding:15px;border:none;border-radius:14px;font-size:15px;font-weight:700;color:#fff;cursor:pointer;background:linear-gradient(135deg,#059669,#065F46);">
            Simpan Pembayaran
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
let hutangData = [];
let selectedHutang = null;

document.addEventListener('DOMContentLoaded', async function() {
    await loadHutang();
    lucide.createIcons();
});

async function loadHutang() {
    try {
        const division = '{{ auth()->user()->division ?? "minyak" }}';
        const response = await fetch(`/api/v1/${division}/hutang`);
        if (response.ok) {
            const result = await response.json();
            hutangData = result.data?.data || [];
            renderHutangList();
        }
    } catch (error) {
        console.error('Failed to load hutang:', error);
        document.getElementById('hutang-list').innerHTML = `<div style="text-align:center;padding:40px 20px;color:rgba(255,255,255,0.3);"><div style="font-size:32px;margin-bottom:10px;">📡</div><div style="font-size:13px;">Mode offline - Data tidak tersedia</div></div>`;
    }
}

function renderHutangList() {
    const container = document.getElementById('hutang-list');
    const totalHutang = hutangData.reduce((sum, h) => sum + (h.sisa_hutang || 0), 0);
    document.getElementById('total-hutang').textContent = formatRupiah(totalHutang);
    document.getElementById('count-hutang').textContent = hutangData.length;

    if (hutangData.length === 0) {
        container.innerHTML = `<div style="text-align:center;padding:48px 20px;"><div style="font-size:40px;margin-bottom:12px;">🎉</div><div style="font-size:15px;font-weight:600;color:#34D399;">Semua Lunas!</div><div style="font-size:12px;color:rgba(255,255,255,0.3);margin-top:4px;">Tidak ada hutang pelanggan</div></div>`;
        return;
    }

    container.innerHTML = hutangData.map(h => {
        const lunas = h.status === 'lunas';
        return `<div class="card tap-sm" style="padding:16px 18px;margin-bottom:10px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <div style="flex:1;min-width:0;">
                    <div style="font-size:14px;font-weight:700;color:#fff;">${h.pelanggan?.nama_toko||'-'}</div>
                    <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">${h.pelanggan?.nama_pemilik||'-'}</div>
                </div>
                <span style="padding:3px 10px;border-radius:99px;font-size:10px;font-weight:700;background:${lunas?'rgba(5,150,105,0.15)':'rgba(225,29,72,0.15)'};color:${lunas?'#34D399':'#FB7185'};border:1px solid ${lunas?'rgba(52,211,153,0.2)':'rgba(251,113,133,0.2)'};">${lunas?'LUNAS':'BELUM LUNAS'}</span>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:${h.sisa_hutang>0?'12px':'0'};">
                <div style="background:rgba(255,255,255,0.04);border-radius:10px;padding:10px 12px;">
                    <div style="font-size:10px;color:rgba(255,255,255,0.35);margin-bottom:3px;">Total Hutang</div>
                    <div style="font-size:13px;font-weight:600;color:#fff;">${formatRupiah(h.total_hutang)}</div>
                </div>
                <div style="background:rgba(225,29,72,0.08);border-radius:10px;padding:10px 12px;">
                    <div style="font-size:10px;color:rgba(255,255,255,0.35);margin-bottom:3px;">Sisa</div>
                    <div style="font-size:13px;font-weight:600;color:#FB7185;">${formatRupiah(h.sisa_hutang)}</div>
                </div>
            </div>
            ${h.sisa_hutang>0?`<button onclick="openBayarModal(${h.id})" class="tap" style="width:100%;padding:10px;background:rgba(5,150,105,0.12);border:1px solid rgba(52,211,153,0.2);border-radius:12px;color:#34D399;font-size:13px;font-weight:600;cursor:pointer;">💳 Bayar Sekarang</button>`:''}
        </div>`;
    }).join('');
    lucide.createIcons();
}

function openBayarModal(hutangId) {
    selectedHutang = hutangData.find(h => h.id === hutangId);
    if (!selectedHutang) return;
    document.getElementById('modal-pelanggan').textContent = selectedHutang.pelanggan?.nama_toko || '-';
    document.getElementById('modal-sisa').textContent = formatRupiah(selectedHutang.sisa_hutang);
    document.getElementById('bayar-input').value = selectedHutang.sisa_hutang;
    const m = document.getElementById('bayar-modal');
    m.style.display = 'flex';
}

function closeModal() { document.getElementById('bayar-modal').style.display = 'none'; selectedHutang = null; }

async function submitBayar() {
    const jumlahBayar = parseInt(document.getElementById('bayar-input').value) || 0;
    if (jumlahBayar <= 0 || jumlahBayar > selectedHutang.sisa_hutang) { showToast('Jumlah bayar tidak valid', 'error'); return; }
    showLoading(true);
    try {
        const division = '{{ auth()->user()->division ?? "minyak" }}';
        const response = await fetch(`/api/v1/${division}/hutang/${selectedHutang.id}/bayar`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ jumlah_bayar: jumlahBayar }) });
        if (response.ok) { showToast('Pembayaran berhasil', 'success'); closeModal(); await loadHutang(); }
        else throw new Error('Server error');
    } catch (error) { showToast('Gagal memproses pembayaran', 'error'); }
    finally { showLoading(false); }
}

function formatRupiah(amount) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount); }
</script>
<style>.animate-slide-up { animation: slideUp 0.3s ease-out; } @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }</style>
@endpush
