<x-app-layout>
    <x-slot name="header">Dashboard Admin 4</x-slot>

    <style>
        .dash-greeting { margin-bottom: 1.75rem; }
        .dash-greeting h1 { font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.25rem; }
        .dash-greeting h1 span { color: #f97316; }
        .dash-greeting p { font-size: 0.875rem; color: #94a3b8; }
        .panel { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .op-badge { display:inline-flex; align-items:center; gap:0.4rem; padding:0.25rem 0.65rem; border-radius:999px; font-weight:900; font-size:0.78rem; border:1px solid transparent; }
    </style>

    <div class="dash-greeting">
        <h1>Selamat Datang, <span>{{ Auth::user()->name }}</span> 👋</h1>
        @php
            $opStatus = (string) ($opnameToday['status'] ?? 'missing');
            $opBadge = match($opStatus) {
                'approved' => ['APPROVED', '#dcfce7', '#166534'],
                'submitted' => ['SUBMITTED', '#e0f2fe', '#075985'],
                default => ['BELUM', '#fef3c7', '#92400e'],
            };
        @endphp
        <p style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
            <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} &mdash; <strong>Dashboard Gudang Keluar & Distribusi</strong></span>
            <span class="op-badge" style="background:{{ $opBadge[1] }}; color:{{ $opBadge[2] }};">Opname Hari Ini: {{ $opBadge[0] }}</span>
        </p>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon amber">📤</div>
            <div>
                <div class="stat-label">Pengeluaran Gudang Hari Ini</div>
                <div class="stat-value amber">{{ $pengeluaranHariIni }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">🔄</div>
            <div>
                <div class="stat-label">Transfer Gudang Hari Ini</div>
                <div class="stat-value purple">{{ $transferGudangHariIni }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon slate">🔍</div>
            <div>
                <div class="stat-label">Opname Stok Hari Ini</div>
                <div class="stat-value slate">{{ $opnameHariIni }}</div>
            </div>
        </div>
    </div>

    <div class="panel" style="margin-top: 1.5rem;">
        <h3 class="panel-title" style="margin-bottom: 1rem;">Action Shortcuts (Akses Cepat Admin 4)</h3>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="{{ route('gudang.stok') }}" class="btn-primary" style="background: #0891b2;">
                Cek Stok Gudang
            </a>
            <a href="{{ route('gudang.pengeluaran') }}" class="btn-warning">
                Input Pengeluaran / Mutasi Manual
            </a>
            <a href="{{ route('gudang.transfer') }}" class="btn-secondary">
                Transfer Cabang
            </a>
        </div>
    </div>
</x-app-layout>
