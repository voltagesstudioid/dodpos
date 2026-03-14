<x-app-layout>
    <x-slot name="header">Dashboard Admin 2</x-slot>

    <style>
        .dash-greeting { margin-bottom: 1.75rem; }
        .dash-greeting h1 { font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.25rem; }
        .dash-greeting h1 span { color: #0d9488; }
        .dash-greeting p { font-size: 0.875rem; color: #94a3b8; }
        .panel { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .focus-box { background: #f0fdfa; border: 1px dashed #14b8a6; padding: 1.25rem; border-radius: 12px; margin-top: 1.5rem; }
    </style>

    <div class="dash-greeting">
        <h1>Selamat Datang, <span>{{ Auth::user()->name }}</span> 👋</h1>
        <p>{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} &mdash; <strong>Dashboard Kasir & Operasional</strong></p>
    </div>

    <div class="stat-grid">
        <!-- 1. Omzet POS Pribadi -->
        <div class="stat-card">
            <div class="stat-icon emerald">🖥️</div>
            <div>
                <div class="stat-label">Omzet POS (Shift Anda)</div>
                <div class="stat-value emerald">Rp {{ number_format($omzetPOS, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- 2. Pengeluaran Operasional -->
        <div class="stat-card">
            <div class="stat-icon rose">💸</div>
            <div>
                <div class="stat-label">Total Operasional Hari Ini</div>
                <div class="stat-value rose">Rp {{ number_format($pengeluaranOperasional, 0, ',', '.') }}</div>
            </div>
        </div>

    </div>

    <div class="panel" style="margin-top: 1.5rem;">
        <h3 class="panel-title" style="margin-bottom: 1rem;">Action Shortcuts (Akses Cepat Admin 2)</h3>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            @can('manage_sesi_operasional')
                <a href="{{ route('operasional.sesi.index') }}" class="btn-primary" style="background: #0f766e;">
                    Buka/Tutup Kas Operasional
                </a>
            @endcan
            <a href="{{ route('operasional.pengeluaran.create') }}" class="btn-danger">
                Input Pengeluaran Baru
            </a>
                @can('view_pos_kasir')
                    <a href="{{ route('kasir.index') }}" class="btn-primary">
                        Masuk ke Laci Kasir
                    </a>
                @endcan
        </div>
    </div>
</x-app-layout>
