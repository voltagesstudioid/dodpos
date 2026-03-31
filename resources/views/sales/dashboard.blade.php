@extends('layouts.sales-mobile')
@section('title','DODPOS Sales')
@section('header-title','Dashboard')
@section('header-subtitle')
{{ date('D, d M Y') }}
@endsection

@section('content')
<div style="padding:20px 16px 110px;">
    {{-- HERO BANNER --}}
    <div class="card-glow anim-up" style="padding:20px;margin-bottom:16px;position:relative;overflow:hidden;">
        <div class="orb orb-violet" style="width:200px;height:200px;top:-80px;right:-60px;"></div>
        <div style="display:flex;align-items:center;gap:14px;position:relative;">
            <div class="grad-violet" style="width:52px;height:52px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;flex-shrink:0;box-shadow:0 4px 20px rgba(124,58,237,0.4);">
                {{ substr(auth()->user()->name ?? 'S', 0, 1) }}
            </div>
            <div style="flex:1;">
                <div style="font-size:18px;font-weight:700;color:#fff;">{{ auth()->user()->name ?? 'Sales' }}</div>
                <div style="font-size:12px;color:rgba(255,255,255,0.45);margin-top:2px;display:flex;align-items:center;gap:5px;">
                    <i data-lucide="truck" style="width:12px;height:12px;"></i>
                    {{ auth()->user()->salesProfile->no_kendaraan ?? 'Belum set kendaraan' }}
                </div>
            </div>
            <div class="badge-green" style="padding:4px 10px;font-size:11px;font-weight:600;display:flex;align-items:center;gap:4px;">
                <span style="width:6px;height:6px;background:#34D399;border-radius:50%;display:inline-block;"></span>
                Online
            </div>
        </div>
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid rgba(255,255,255,0.08);display:flex;gap:20px;">
            <div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-bottom:3px;">Hari</div>
                <div style="font-size:14px;font-weight:600;color:#A78BFA;">{{ date('D') }}</div>
            </div>
            <div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-bottom:3px;">Tanggal</div>
                <div style="font-size:14px;font-weight:600;color:#fff;">{{ date('d M Y') }}</div>
            </div>
        </div>
    </div>

    {{-- STATS ROW --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
        <div class="card anim-up-1" style="padding:18px 16px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <div class="grad-emerald" style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-lucide="trending-up" style="width:18px;height:18px;color:#fff;"></i>
                </div>
                <span style="font-size:11px;color:rgba(255,255,255,0.4);font-weight:500;">Penjualan</span>
            </div>
            <div id="stat-penjualan" style="font-size:20px;font-weight:800;color:#fff;letter-spacing:-0.5px;">Rp 0</div>
            <div id="stat-transaksi" style="font-size:11px;color:#34D399;margin-top:4px;font-weight:500;">0 transaksi</div>
        </div>

        <div class="card anim-up-1" style="padding:18px 16px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <div class="grad-cyan" style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-lucide="package" style="width:18px;height:18px;color:#fff;"></i>
                </div>
                <span style="font-size:11px;color:rgba(255,255,255,0.4);font-weight:500;">Sisa Stok</span>
            </div>
            <div id="stat-stok" style="font-size:20px;font-weight:800;color:#fff;letter-spacing:-0.5px;">0</div>
            <div style="font-size:11px;color:#67E8F9;margin-top:4px;font-weight:500;">unit tersedia</div>
        </div>
    </div>

    {{-- TARGET CARD --}}
    <div class="card anim-up-2" style="padding:20px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="grad-amber" style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i data-lucide="target" style="width:18px;height:18px;color:#fff;"></i>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:600;color:#fff;">Target Harian</div>
                    <div id="target-amount" style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:1px;">Rp 0 / Rp 0</div>
                </div>
            </div>
            <div id="target-percent" style="font-size:28px;font-weight:800;color:#FCD34D;">0%</div>
        </div>
        <div class="progress-track" style="height:8px;">
            <div id="target-bar" class="progress-fill" style="height:100%;width:0%;"></div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:8px;">
            <span style="font-size:11px;color:rgba(255,255,255,0.3);">Mulai</span>
            <span style="font-size:11px;color:rgba(255,255,255,0.3);">Target</span>
        </div>
    </div>

    {{-- HUTANG CARD --}}
    <a href="{{ route('sales.hutang') }}" class="card tap-sm anim-up-3" style="display:flex;align-items:center;gap:14px;padding:18px 20px;margin-bottom:16px;text-decoration:none;">
        <div class="grad-rose" style="width:44px;height:44px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i data-lucide="credit-card" style="width:22px;height:22px;color:#fff;"></i>
        </div>
        <div style="flex:1;">
            <div style="font-size:13px;font-weight:600;color:#fff;">Total Piutang</div>
            <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;"><span id="stat-hutang-count">0</span> pelanggan belum lunas</div>
        </div>
        <div style="text-align:right;">
            <div id="stat-hutang" style="font-size:16px;font-weight:700;color:#FB7185;">Rp 0</div>
            <i data-lucide="chevron-right" style="width:16px;height:16px;color:rgba(255,255,255,0.2);margin-top:4px;"></i>
        </div>
    </a>

    {{-- QUICK ACTIONS --}}
    <div style="margin-bottom:8px;">
        <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:12px;">Menu Cepat</div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;">
            <a href="{{ route('sales.penjualan.create') }}" class="tap" style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:14px 8px;background:var(--bg-elevated);border:1px solid var(--border);border-radius:16px;text-decoration:none;">
                <div class="grad-emerald" style="width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;">
                    <i data-lucide="plus" style="width:22px;height:22px;color:#fff;"></i>
                </div>
                <span style="font-size:10px;font-weight:600;color:rgba(255,255,255,0.6);">Jual</span>
            </a>
            <a href="{{ route('sales.loading') }}" class="tap" style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:14px 8px;background:var(--bg-elevated);border:1px solid var(--border);border-radius:16px;text-decoration:none;">
                <div class="grad-cyan" style="width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;">
                    <i data-lucide="truck" style="width:22px;height:22px;color:#fff;"></i>
                </div>
                <span style="font-size:10px;font-weight:600;color:rgba(255,255,255,0.6);">Stok</span>
            </a>
            <a href="{{ route('sales.hutang') }}" class="tap" style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:14px 8px;background:var(--bg-elevated);border:1px solid var(--border);border-radius:16px;text-decoration:none;">
                <div class="grad-rose" style="width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;">
                    <i data-lucide="credit-card" style="width:22px;height:22px;color:#fff;"></i>
                </div>
                <span style="font-size:10px;font-weight:600;color:rgba(255,255,255,0.6);">Hutang</span>
            </a>
            <a href="{{ route('sales.setoran') }}" class="tap" style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:14px 8px;background:var(--bg-elevated);border:1px solid var(--border);border-radius:16px;text-decoration:none;">
                <div class="grad-amber" style="width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;">
                    <i data-lucide="wallet" style="width:22px;height:22px;color:#fff;"></i>
                </div>
                <span style="font-size:10px;font-weight:600;color:rgba(255,255,255,0.6);">Setor</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const fmt = v => new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(v||0);

document.addEventListener('DOMContentLoaded', async () => {
    lucide.createIcons();
    try {
        const div = '{{ auth()->user()->division ?? "minyak" }}';
        const r = await fetch(`/api/v1/${div}/dashboard`);
        if(r.ok){ const {data} = await r.json(); render(data); }
    } catch(e){ console.warn('Offline mode',e); }
});

function render(d){
    if(!d) return;
    if(d.hariIni){
        document.getElementById('stat-penjualan').textContent = fmt(d.hariIni.penjualanTotal);
        document.getElementById('stat-transaksi').textContent = (d.hariIni.jumlahTransaksi||0)+' transaksi';
    }
    if(d.target){
        const p = Math.min(d.target.persen||0, 100);
        document.getElementById('target-percent').textContent = p+'%';
        document.getElementById('target-bar').style.width = p+'%';
        document.getElementById('target-amount').textContent = fmt(d.target.terpenuhi)+' / '+fmt(d.target.total);
        if(p>=100) document.getElementById('target-percent').style.color='#34D399';
    }
    if(d.ringkasanHutang){
        document.getElementById('stat-hutang').textContent = fmt(d.ringkasanHutang.totalPiutang);
        document.getElementById('stat-hutang-count').textContent = d.ringkasanHutang.jumlahPelanggan||0;
    }
    if(d.loadingHariIni?.detail){
        const sisa = d.loadingHariIni.detail.reduce((s,i)=>s+(i.sisa||0),0);
        document.getElementById('stat-stok').textContent = sisa;
    }
}
</script>
@endpush
