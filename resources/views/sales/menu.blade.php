@extends('layouts.sales-mobile')
@section('title','Menu')
@section('header-title','Menu')
@section('header-subtitle','Semua fitur tersedia')

@section('content')
<div style="padding:20px 16px 110px;">

    {{-- SECTION: TRANSAKSI --}}
    <div style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:10px;">Transaksi</div>
    <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:24px;">

        <a href="{{ route('sales.penjualan.create') }}" class="tap-sm" style="display:flex;align-items:center;gap:14px;padding:16px 18px;background:var(--bg-elevated);border:1px solid var(--border);border-radius:18px;text-decoration:none;">
            <div class="grad-emerald" style="width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="shopping-cart" style="width:22px;height:22px;color:#fff;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:600;color:#fff;">Input Penjualan</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">Catat transaksi baru</div>
            </div>
            <i data-lucide="chevron-right" style="width:18px;height:18px;color:rgba(255,255,255,0.2);"></i>
        </a>

        <a href="{{ route('sales.penjualan.list') }}" class="tap-sm" style="display:flex;align-items:center;gap:14px;padding:16px 18px;background:var(--bg-elevated);border:1px solid var(--border);border-radius:18px;text-decoration:none;">
            <div class="grad-violet" style="width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="receipt" style="width:22px;height:22px;color:#fff;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:600;color:#fff;">Riwayat Penjualan</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">Histori transaksi</div>
            </div>
            <i data-lucide="chevron-right" style="width:18px;height:18px;color:rgba(255,255,255,0.2);"></i>
        </a>

    </div>

    {{-- SECTION: KEUANGAN --}}
    <div style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:10px;">Keuangan</div>
    <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:24px;">

        <a href="{{ route('sales.hutang') }}" class="tap-sm" style="display:flex;align-items:center;gap:14px;padding:16px 18px;background:var(--bg-elevated);border:1px solid var(--border);border-radius:18px;text-decoration:none;">
            <div class="grad-rose" style="width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="credit-card" style="width:22px;height:22px;color:#fff;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:600;color:#fff;">Daftar Hutang</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">Piutang pelanggan</div>
            </div>
            <i data-lucide="chevron-right" style="width:18px;height:18px;color:rgba(255,255,255,0.2);"></i>
        </a>

        <a href="{{ route('sales.setoran') }}" class="tap-sm" style="display:flex;align-items:center;gap:14px;padding:16px 18px;background:var(--bg-elevated);border:1px solid var(--border);border-radius:18px;text-decoration:none;">
            <div class="grad-amber" style="width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="wallet" style="width:22px;height:22px;color:#fff;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:600;color:#fff;">Setoran Harian</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">Input setoran ke kasir</div>
            </div>
            <i data-lucide="chevron-right" style="width:18px;height:18px;color:rgba(255,255,255,0.2);"></i>
        </a>

    </div>

    {{-- SECTION: OPERASIONAL --}}
    <div style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:10px;">Operasional</div>
    <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:24px;">

        <a href="{{ route('sales.loading') }}" class="tap-sm" style="display:flex;align-items:center;gap:14px;padding:16px 18px;background:var(--bg-elevated);border:1px solid var(--border);border-radius:18px;text-decoration:none;">
            <div class="grad-cyan" style="width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="truck" style="width:22px;height:22px;color:#fff;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:600;color:#fff;">Stok Kendaraan</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">Cek loading hari ini</div>
            </div>
            <i data-lucide="chevron-right" style="width:18px;height:18px;color:rgba(255,255,255,0.2);"></i>
        </a>

        <a href="{{ route('sales.kunjungan.create') }}" class="tap-sm" style="display:flex;align-items:center;gap:14px;padding:16px 18px;background:var(--bg-elevated);border:1px solid var(--border);border-radius:18px;text-decoration:none;">
            <div style="width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:linear-gradient(135deg,#0D9488,#0F766E);">
                <i data-lucide="map-pin" style="width:22px;height:22px;color:#fff;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:600;color:#fff;">Catat Kunjungan</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">Check-in ke pelanggan</div>
            </div>
            <i data-lucide="chevron-right" style="width:18px;height:18px;color:rgba(255,255,255,0.2);"></i>
        </a>

        <a href="{{ route('sales.kunjungan.list') }}" class="tap-sm" style="display:flex;align-items:center;gap:14px;padding:16px 18px;background:var(--bg-elevated);border:1px solid var(--border);border-radius:18px;text-decoration:none;">
            <div style="width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:linear-gradient(135deg,#0284C7,#0369A1);">
                <i data-lucide="map" style="width:22px;height:22px;color:#fff;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:600;color:#fff;">Riwayat Kunjungan</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">Histori kunjungan</div>
            </div>
            <i data-lucide="chevron-right" style="width:18px;height:18px;color:rgba(255,255,255,0.2);"></i>
        </a>

        <a href="{{ route('sales.pelanggan') }}" class="tap-sm" style="display:flex;align-items:center;gap:14px;padding:16px 18px;background:var(--bg-elevated);border:1px solid var(--border);border-radius:18px;text-decoration:none;">
            <div class="grad-blue" style="width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="users" style="width:22px;height:22px;color:#fff;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:600;color:#fff;">Data Pelanggan</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">Daftar pelanggan aktif</div>
            </div>
            <i data-lucide="chevron-right" style="width:18px;height:18px;color:rgba(255,255,255,0.2);"></i>
        </a>

    </div>

    {{-- LOGOUT --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="tap-sm" style="width:100%;display:flex;align-items:center;justify-content:center;gap:10px;padding:16px;background:rgba(225,29,72,0.1);border:1px solid rgba(225,29,72,0.2);border-radius:18px;color:#FB7185;font-size:14px;font-weight:600;cursor:pointer;">
            <i data-lucide="log-out" style="width:18px;height:18px;"></i>
            Keluar dari Akun
        </button>
    </form>

</div>
@endsection
