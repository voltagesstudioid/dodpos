@extends('layouts.app', ['title' => 'Dashboard Pasgar'])

@section('content')
<div class="page-container">
    <div class="ph">
        <div class="ph-left">
            <div class="ph-icon indigo">
                🦅
            </div>
            <div>
                <h1 class="ph-title">Dashboard Pasukan Garuda (Pasgar)</h1>
                <p class="ph-subtitle">Ringkasan aktivitas operasional penjualan lapangan.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="stat-grid">
        <div class="stat-card indigo">
            <div class="stat-card-row">
                <div class="stat-icon indigo">👤</div>
            </div>
            <div>
                <div class="stat-label">Total Sales Aktif</div>
                <div class="stat-value indigo">{{ $salesCount }}</div>
            </div>
        </div>
        <div class="stat-card blue">
            <div class="stat-card-row">
                <div class="stat-icon blue">📦</div>
            </div>
            <div>
                <div class="stat-label">Loading Hari Ini</div>
                <div class="stat-value blue">{{ $loadingTodayCount }}</div>
            </div>
        </div>
        <div class="stat-card amber">
            <div class="stat-card-row">
                <div class="stat-icon amber">⏳</div>
            </div>
            <div>
                <div class="stat-label">Setoran Menunggu</div>
                <div class="stat-value amber">{{ $setoranPendingCount }}</div>
            </div>
        </div>
    </div>

    <div class="card p-3">
        <p class="text-muted">Gunakan menu di sidebar untuk mengelola data sales, memproses loading barang, mencatat penjualan, dan memverifikasi setoran.</p>
    </div>

    <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:20px 0 12px;">📊 Laporan</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;">
        <a href="{{ route('pasgar.laporan.penjualan') }}" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;text-decoration:none;transition:border-color .2s;" onmouseover="this.style.borderColor='#065f46'" onmouseout="this.style.borderColor='#e2e8f0'">
            <div style="width:40px;height:40px;border-radius:8px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;font-size:18px;">📈</div>
            <div>
                <div style="font-weight:700;font-size:13px;color:#1e293b;">Laporan Penjualan</div>
                <div style="font-size:11px;color:#94a3b8;">Rekap penjualan per sales</div>
            </div>
        </a>
        <a href="{{ route('pasgar.laporan.setoran') }}" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;text-decoration:none;transition:border-color .2s;" onmouseover="this.style.borderColor='#065f46'" onmouseout="this.style.borderColor='#e2e8f0'">
            <div style="width:40px;height:40px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:18px;">💵</div>
            <div>
                <div style="font-weight:700;font-size:13px;color:#1e293b;">Laporan Setoran</div>
                <div style="font-size:11px;color:#94a3b8;">Verifikasi & selisih setoran</div>
            </div>
        </a>
    </div>
</div>
@endsection
