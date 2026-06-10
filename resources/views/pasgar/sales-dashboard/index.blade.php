@extends('layouts.app', ['title' => 'Dashboard Sales Pasgar'])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .sd-wrap { font-family: 'Plus Jakarta Sans', sans-serif; max-width: 1100px; margin: 0 auto; padding: 1.25rem; }
    .sd-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
    .sd-header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #6366f1, #4338ca); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(79,70,229,0.25); }
    .sd-header-icon svg { width: 26px; height: 26px; stroke: #fff; }
    .sd-header h1 { font-size: 1.35rem; font-weight: 800; color: #1e1b4b; margin: 0; }
    .sd-header p { font-size: 0.8rem; color: #6366f1; margin: 0; font-weight: 600; }

    .sd-profile { display: flex; align-items: center; gap: 1rem; background: #fff; border: 1px solid #e0e7ff; border-radius: 14px; padding: 1rem 1.25rem; margin-bottom: 1.25rem; }
    .sd-avatar { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #4338ca); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; flex-shrink: 0; }
    .sd-profile-info { flex: 1; }
    .sd-profile-name { font-weight: 700; color: #1e1b4b; font-size: 1rem; }
    .sd-profile-code { font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; color: #6366f1; font-weight: 600; }
    .sd-profile-meta { display: flex; gap: 1rem; font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; }
    .sd-status-pill { display: inline-flex; align-items: center; gap: 0.35rem; background: #dcfce7; color: #166534; padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
    .sd-status-dot { width: 7px; height: 7px; border-radius: 50%; background: #22c55e; animation: sd-pulse 1.5s infinite; }
    @keyframes sd-pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

    .sd-target { background: #fff; border: 1px solid #e0e7ff; border-radius: 14px; padding: 1rem 1.25rem; margin-bottom: 1.25rem; }
    .sd-target-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem; }
    .sd-target-label { font-size: 0.78rem; color: #64748b; font-weight: 600; }
    .sd-target-value { font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; font-weight: 700; color: #4338ca; }
    .sd-target-bar { height: 10px; background: #f1f5f9; border-radius: 5px; overflow: hidden; }
    .sd-target-fill { height: 100%; border-radius: 5px; transition: width 0.5s ease; }
    .sd-target-fill.red { background: linear-gradient(90deg, #ef4444, #dc2626); }
    .sd-target-fill.amber { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .sd-target-fill.green { background: linear-gradient(90deg, #22c55e, #16a34a); }

    .sd-section-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 0.6rem; }

    .sd-actions { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 1.5rem; }
    .sd-action { background: #fff; border: 1px solid #e0e7ff; border-radius: 12px; padding: 0.85rem; text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 0.5rem; transition: all 0.2s; }
    .sd-action:hover { border-color: #6366f1; box-shadow: 0 4px 12px rgba(99,102,241,0.12); transform: translateY(-2px); }
    .sd-action-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .sd-action-icon svg { width: 20px; height: 20px; }
    .sd-action-icon.blue { background: #eff6ff; }
    .sd-action-icon.blue svg { stroke: #2563eb; }
    .sd-action-icon.green { background: #f0fdf4; }
    .sd-action-icon.green svg { stroke: #16a34a; }
    .sd-action-icon.amber { background: #fffbeb; }
    .sd-action-icon.amber svg { stroke: #d97706; }
    .sd-action-icon.purple { background: #faf5ff; }
    .sd-action-icon.purple svg { stroke: #7c3aed; }
    .sd-action span { font-size: 0.72rem; font-weight: 700; color: #334155; text-align: center; }

    .sd-kpis { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 1.5rem; }
    .sd-kpi { background: #fff; border: 1px solid #e0e7ff; border-radius: 12px; padding: 1rem; position: relative; overflow: hidden; }
    .sd-kpi::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 4px 0 0 4px; }
    .sd-kpi.indigo::before { background: #6366f1; }
    .sd-kpi.green::before { background: #22c55e; }
    .sd-kpi.blue::before { background: #3b82f6; }
    .sd-kpi.purple::before { background: #7c3aed; }
    .sd-kpi-label { font-size: 0.72rem; color: #64748b; font-weight: 600; margin-bottom: 0.35rem; }
    .sd-kpi-value { font-family: 'JetBrains Mono', monospace; font-size: 1.15rem; font-weight: 800; color: #1e1b4b; }
    .sd-kpi-sub { font-size: 0.7rem; color: #94a3b8; margin-top: 0.2rem; }

    .sd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .sd-card { background: #fff; border: 1px solid #e0e7ff; border-radius: 14px; padding: 1.1rem; }
    .sd-card-title { font-size: 0.8rem; font-weight: 700; color: #1e1b4b; margin-bottom: 0.85rem; display: flex; align-items: center; gap: 0.5rem; }
    .sd-card-title .dot { width: 8px; height: 8px; border-radius: 50%; }
    .sd-card-title .dot.indigo { background: #6366f1; }
    .sd-card-title .dot.green { background: #22c55e; }
    .sd-card-title .dot.blue { background: #3b82f6; }
    .sd-card-title .dot.amber { background: #f59e0b; }
    .sd-empty { text-align: center; padding: 1.5rem; color: #94a3b8; font-size: 0.78rem; }
    .sd-list-item { display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid #f1f5f9; }
    .sd-list-item:last-child { border-bottom: none; }
    .sd-list-label { font-size: 0.78rem; color: #334155; font-weight: 600; }
    .sd-list-sub { font-size: 0.68rem; color: #94a3b8; }
    .sd-list-value { font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; font-weight: 700; color: #1e1b4b; }
    .sd-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 6px; font-size: 0.65rem; font-weight: 700; }
    .sd-badge.pending { background: #fef3c7; color: #92400e; }
    .sd-badge.preparing { background: #dbeafe; color: #1e40af; }
    .sd-badge.ready { background: #d1fae5; color: #065f46; }
    .sd-badge.picked_up { background: #e0e7ff; color: #3730a3; }
    .sd-badge.loaded { background: #ede9fe; color: #5b21b6; }
    .sd-badge.completed { background: #f1f5f9; color: #475569; }
    .sd-badge.opnamed { background: #f0fdf4; color: #166534; }
    .sd-badge.rejected { background: #fee2e2; color: #991b1b; }
    .sd-badge.verified { background: #dcfce7; color: #166534; }

    @media (max-width: 768px) {
        .sd-actions, .sd-kpis { grid-template-columns: repeat(2, 1fr); }
        .sd-grid { grid-template-columns: 1fr; }
        .sd-profile-meta { flex-direction: column; gap: 0.25rem; }
    }
</style>
@endpush

@section('content')
<div class="sd-wrap">
    {{-- Header --}}
    <div class="sd-header">
        <div class="sd-header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div>
            <h1>Dashboard Sales</h1>
            <p>{{ $salesProfile->nama }} &middot; {{ $salesProfile->kode_sales }}</p>
        </div>
    </div>

    @if(session('error'))
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;color:#991b1b;font-size:0.8rem;">{{ session('error') }}</div>
    @endif

    {{-- Profile Card --}}
    <div class="sd-profile">
        <div class="sd-avatar">{{ strtoupper(substr($salesProfile->nama, 0, 1)) }}</div>
        <div class="sd-profile-info">
            <div class="sd-profile-name">{{ $salesProfile->nama }}</div>
            <div class="sd-profile-code">{{ $salesProfile->kode_sales }}</div>
            <div class="sd-profile-meta">
                @if($salesProfile->no_hp)<span>📱 {{ $salesProfile->no_hp }}</span>@endif
                @if($salesProfile->no_kendaraan)<span>🚗 {{ $salesProfile->no_kendaraan }}</span>@endif
            </div>
        </div>
        <div class="sd-status-pill"><div class="sd-status-dot"></div> Aktif</div>
    </div>

    {{-- Target Bar --}}
    @if($salesProfile->target_harian > 0)
    <div class="sd-target">
        <div class="sd-target-header">
            <span class="sd-target-label">Target Harian</span>
            <span class="sd-target-value">Rp {{ number_format($todayStats['penjualan'], 0, ',', '.') }} / Rp {{ number_format($salesProfile->target_harian, 0, ',', '.') }}</span>
        </div>
        <div class="sd-target-bar">
            @php $pct = $salesProfile->target_harian > 0 ? min(100, ($todayStats['penjualan'] / $salesProfile->target_harian) * 100) : 0; @endphp
            <div class="sd-target-fill {{ $pct < 40 ? 'red' : ($pct < 80 ? 'amber' : 'green') }}" style="width: {{ $pct }}%"></div>
        </div>
    </div>
    @endif

    {{-- Quick Actions --}}
    <div class="sd-section-label">Aksi Cepat</div>
    <div class="sd-actions">
        <a href="{{ route('pasgar.loading.create') }}" class="sd-action">
            <div class="sd-action-icon blue"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
            <span>Request Loading</span>
        </a>
        <a href="{{ route('pasgar.penjualan.index') }}" class="sd-action">
            <div class="sd-action-icon green"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
            <span>Input Penjualan</span>
        </a>
        <a href="{{ route('pasgar.setoran.index') }}" class="sd-action">
            <div class="sd-action-icon amber"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg></div>
            <span>Input Setoran</span>
        </a>
        <a href="{{ route('pasgar.opname.create') }}" class="sd-action">
            <div class="sd-action-icon purple"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div>
            <span>Opname Barang</span>
        </a>
    </div>

    {{-- KPI Cards --}}
    <div class="sd-section-label">Ringkasan</div>
    <div class="sd-kpis">
        <div class="sd-kpi indigo">
            <div class="sd-kpi-label">Penjualan Hari Ini</div>
            <div class="sd-kpi-value">Rp {{ number_format($todayStats['penjualan'], 0, ',', '.') }}</div>
            <div class="sd-kpi-sub">{{ $todayStats['transaksi'] }} transaksi</div>
        </div>
        <div class="sd-kpi purple">
            <div class="sd-kpi-label">Penjualan Bulan Ini</div>
            <div class="sd-kpi-value">Rp {{ number_format($monthlyStats['penjualan'], 0, ',', '.') }}</div>
            <div class="sd-kpi-sub">{{ $monthlyStats['transaksi'] }} transaksi</div>
        </div>
        <div class="sd-kpi green">
            <div class="sd-kpi-label">Loading Aktif</div>
            <div class="sd-kpi-value">{{ $activeLoadings->count() }}</div>
            <div class="sd-kpi-sub">dalam proses</div>
        </div>
        <div class="sd-kpi blue">
            <div class="sd-kpi-label">Setoran Pending</div>
            <div class="sd-kpi-value">{{ $setoranStatus['pending'] }}</div>
            <div class="sd-kpi-sub">menunggu verifikasi</div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="sd-section-label">Aktivitas Terbaru</div>
    <div class="sd-grid">
        {{-- Loading Aktif --}}
        <div class="sd-card">
            <div class="sd-card-title"><div class="dot indigo"></div> Loading Aktif</div>
            @forelse($activeLoadings->take(5) as $loading)
            <div class="sd-list-item">
                <div>
                    <div class="sd-list-label">{{ $loading->nomor_loading }}</div>
                    <div class="sd-list-sub">{{ $loading->items->count() }} item &middot; {{ $loading->tanggal->format('d/m/Y') }}</div>
                </div>
                <span class="sd-badge {{ $loading->status }}">{{ $loading->status_label }}</span>
            </div>
            @empty
            <div class="sd-empty">Tidak ada loading aktif</div>
            @endforelse
        </div>

        {{-- Setoran Status --}}
        <div class="sd-card">
            <div class="sd-card-title"><div class="dot amber"></div> Setoran</div>
            <div class="sd-list-item">
                <div class="sd-list-label">Menunggu Verifikasi</div>
                <div class="sd-list-value">{{ $setoranStatus['pending'] }}</div>
            </div>
            <div class="sd-list-item">
                <div class="sd-list-label">Terverifikasi (Bulan Ini)</div>
                <div class="sd-list-value">{{ $setoranStatus['terverifikasi'] }}</div>
            </div>
            @if($recentSetoran->isNotEmpty())
            <div style="margin-top:0.5rem;padding-top:0.5rem;border-top:2px solid #f1f5f9;">
                <div style="font-size:0.68rem;color:#94a3b8;font-weight:600;margin-bottom:0.35rem;">TERAKHIR</div>
                @foreach($recentSetoran->take(3) as $st)
                <div class="sd-list-item">
                    <div>
                        <div class="sd-list-label" style="font-size:0.72rem">{{ $st->nomor_setoran }}</div>
                        <div class="sd-list-sub">{{ $st->tanggal->format('d/m/Y') }}</div>
                    </div>
                    <div style="text-align:right">
                        <div class="sd-list-value">Rp {{ number_format($st->total_setor, 0, ',', '.') }}</div>
                        <span class="sd-badge {{ $st->status === 'terverifikasi' ? 'verified' : ($st->status === 'ditolak' ? 'rejected' : 'pending') }}">{{ $st->status_label }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Penjualan Terakhir --}}
        <div class="sd-card">
            <div class="sd-card-title"><div class="dot green"></div> Penjualan Terakhir</div>
            @forelse($recentPenjualan as $pj)
            <div class="sd-list-item">
                <div>
                    <div class="sd-list-label">{{ $pj->nomor_transaksi }}</div>
                    <div class="sd-list-sub">{{ $pj->nama_pelanggan ?? 'Non-pelanggan' }} &middot; {{ \Carbon\Carbon::parse($pj->tanggal)->format('d/m/Y') }}</div>
                </div>
                <div style="text-align:right">
                    <div class="sd-list-value">Rp {{ number_format($pj->total, 0, ',', '.') }}</div>
                    <span class="sd-badge {{ $pj->metode_bayar === 'transfer' ? 'preparing' : 'verified' }}">{{ ucfirst($pj->metode_bayar) }}</span>
                </div>
            </div>
            @empty
            <div class="sd-empty">Belum ada penjualan</div>
            @endforelse
        </div>

        {{-- Loading Hari Ini --}}
        <div class="sd-card">
            <div class="sd-card-title"><div class="dot blue"></div> Loading Hari Ini</div>
            @forelse($loadingHariIni as $loading)
            <div class="sd-list-item">
                <div>
                    <div class="sd-list-label">{{ $loading->nomor_loading }}</div>
                    <div class="sd-list-sub">{{ $loading->sumber_label }} &middot; {{ $loading->items->count() }} item</div>
                </div>
                <span class="sd-badge {{ $loading->status }}">{{ $loading->status_label }}</span>
            </div>
            @empty
            <div class="sd-empty">Belum ada loading hari ini</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
