@extends('layouts.app', ['title' => 'Dashboard Sales'])

@push('styles')
<style>
    .pasgar-gradient { background: linear-gradient(135deg, #7c3aed, #a855f7); }
    .pasgar-badge { background: linear-gradient(135deg, #f3e8ff, #ede9fe); color: #6d28d9; }
    .trend-up { color: #059669; }
    .trend-down { color: #dc2626; }
    .trend-flat { color: #64748b; }

    .target-bar-wrap { background: #f1f5f9; border-radius: 999px; height: 8px; overflow: hidden; }
    .target-bar-fill { height: 100%; border-radius: 999px; transition: width 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
    .target-bar-fill.good { background: linear-gradient(90deg, #22c55e, #16a34a); }
    .target-bar-fill.warn { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .target-bar-fill.bad { background: linear-gradient(90deg, #ef4444, #dc2626); }

    .ds-profile-card {
        background: linear-gradient(135deg, #faf5ff 0%, #fdf2f8 50%, #fef9ef 100%);
        border: 1px solid #e9d5ff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .ds-profile-avatar {
        width: 52px; height: 52px;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 800; font-size: 1.25rem;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(124,58,237,0.3);
    }
    .ds-profile-info { flex: 1; min-width: 0; }
    .ds-profile-name { font-weight: 700; color: #1e1b4b; font-size: 1rem; }
    .ds-profile-code { font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; color: #7c3aed; font-weight: 600; }
    .ds-profile-meta { display: flex; gap: 1rem; font-size: 0.78rem; color: #64748b; margin-top: 2px; flex-wrap: wrap; }

    .ds-section { margin-bottom: 1.5rem; }

    .activity-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; gap: 0.75rem;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-left { flex: 1; min-width: 0; }
    .activity-title { font-size: 0.8125rem; font-weight: 600; color: #1e293b; }
    .activity-meta { font-size: 0.72rem; color: #94a3b8; margin-top: 2px; }
    .activity-right { text-align: right; flex-shrink: 0; }
    .activity-amount { font-family: 'JetBrains Mono', monospace; font-size: 0.8125rem; font-weight: 700; color: #1e1b4b; }

    .pasgar-stat-value { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.03em; line-height: 1; }

    .pasgar-action-card {
        display: flex; flex-direction: column; align-items: center; gap: 0.625rem;
        padding: 1.125rem; border-radius: 14px; text-decoration: none;
        background: #fff; border: 1px solid #e2e8f0;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .pasgar-action-card:hover {
        border-color: #c4b5fd; transform: translateY(-3px);
        box-shadow: 0 12px 28px -8px rgba(124,58,237,0.15);
    }
    .pasgar-action-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
    }
    .pasgar-action-icon.purple-light { background: #f3e8ff; }
    .pasgar-action-icon.emerald-light { background: #d1fae5; }
    .pasgar-action-icon.amber-light { background: #fef3c7; }
    .pasgar-action-icon.blue-light { background: #dbeafe; }
    .pasgar-action-label { font-size: 0.75rem; font-weight: 700; color: #334155; text-align: center; }

    /* Responsive quick actions */
    .qa-grid-custom { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; }
    @media (max-width: 640px) { .qa-grid-custom { grid-template-columns: repeat(2, 1fr); } }

    /* Stat grid override for pasgar */
    .ps-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
    @media (max-width: 900px) { .ps-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .ps-grid { grid-template-columns: 1fr; } }

    /* Two column activity grid */
    .act-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 768px) { .act-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="page-container animate-in">
    {{-- Page Header --}}
    <div class="ph">
        <div class="ph-left">
            <div class="ph-icon pasgar-gradient" style="background:linear-gradient(135deg,#7c3aed,#a855f7);box-shadow:0 4px 14px rgba(124,58,237,.35);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div>
                <h1 class="ph-title">Dashboard Sales</h1>
                <div class="ph-subtitle">{{ $salesProfile->kode_sales }} &middot; {{ $salesProfile->regional?->nama ?? 'Semua Regional' }}</div>
            </div>
        </div>
        <div class="ph-actions">
            <span class="badge badge-dot badge-success" style="padding-left:1.25rem;"><span style="position:relative;left:-0.35rem;">Aktif</span></span>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Profile Card --}}
    <div class="ds-profile-card mb-3">
        <div class="ds-profile-avatar">{{ strtoupper(substr($salesProfile->nama, 0, 1)) }}</div>
        <div class="ds-profile-info">
            <div class="ds-profile-name">{{ $salesProfile->nama }}</div>
            <div class="ds-profile-code">{{ $salesProfile->kode_sales }}</div>
            <div class="ds-profile-meta">
                @if($salesProfile->no_hp)
                <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:2px;"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                    {{ $salesProfile->no_hp }}
                </span>
                @endif
                @if($salesProfile->no_kendaraan)
                <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:2px;"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    {{ $salesProfile->no_kendaraan }}
                </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Target Harian --}}
    @if($salesProfile->target_harian > 0)
    <div class="panel mb-3">
        <div class="panel-body" style="padding:1rem 1.25rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;flex-wrap:wrap;gap:0.5rem;">
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span style="font-size:0.78rem;font-weight:600;color:#64748b;">Target Harian</span>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:0.85rem;font-weight:700;color:#4338ca;">
                        Rp {{ number_format($todayStats['penjualan'], 0, ',', '.') }}
                    </span>
                    <span style="color:#94a3b8;font-size:0.75rem;">/</span>
                    <span style="font-family:'JetBrains Mono',monospace;font-size:0.8rem;font-weight:600;color:#64748b;">
                        Rp {{ number_format($salesProfile->target_harian, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            <div class="target-bar-wrap">
                @php $barClass = $targetPercentage < 40 ? 'bad' : ($targetPercentage < 80 ? 'warn' : 'good'); @endphp
                <div class="target-bar-fill {{ $barClass }}" style="width: {{ $targetPercentage }}%"></div>
            </div>
            <div style="display:flex;justify-content:space-between;margin-top:0.35rem;">
                <span style="font-size:0.68rem;font-weight:600;color:#94a3b8;">{{ $targetPercentage }}% tercapai</span>
                @if($trend != 0)
                <span style="font-size:0.68rem;font-weight:600;{{ $trend > 0 ? 'color:#059669;' : 'color:#dc2626;' }}">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="display:inline;vertical-align:middle;">{{ $trend > 0 ? '<polyline points="18 15 12 9 6 15"/>' : '<polyline points="6 9 12 15 18 9"/>' }}</svg>
                    {{ abs($trend) }}% dari kemarin
                </span>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Actions --}}
    <div class="ds-section">
        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;margin-bottom:0.65rem;">Aksi Cepat</div>
        <div class="qa-grid-custom">
            <a href="{{ route('pasgar.loading.create') }}" class="pasgar-action-card">
                <div class="pasgar-action-icon blue-light">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <span class="pasgar-action-label">Request Loading</span>
            </a>
            <a href="{{ route('pasgar.penjualan.index') }}" class="pasgar-action-card">
                <div class="pasgar-action-icon emerald-light">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <span class="pasgar-action-label">Input Penjualan</span>
            </a>
            <a href="{{ route('pasgar.setoran.index') }}" class="pasgar-action-card">
                <div class="pasgar-action-icon amber-light">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
                <span class="pasgar-action-label">Input Setoran</span>
            </a>
            <a href="{{ route('pasgar.opname.create') }}" class="pasgar-action-card">
                <div class="pasgar-action-icon purple-light">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <span class="pasgar-action-label">Opname Barang</span>
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="ds-section">
        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;margin-bottom:0.65rem;">Ringkasan</div>
        <div class="ps-grid">
            <div class="stat-card indigo animate-in animate-in-delay-1">
                <div class="stat-card-row">
                    <div class="stat-icon indigo">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    @php $trendUp = $trend > 0; $trendDown = $trend < 0; @endphp
                    @if($trend != 0)
                    <span class="stat-trend {{ $trendUp ? 'up' : 'down' }}">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">{{ $trendUp ? '<polyline points="18 15 12 9 6 15"/>' : '<polyline points="6 9 12 15 18 9"/>' }}</svg>
                        {{ abs($trend) }}%
                    </span>
                    @endif
                </div>
                <div class="stat-label">Penjualan Hari Ini</div>
                <div class="stat-value indigo pasgar-stat-value">Rp {{ number_format($todayStats['penjualan'], 0, ',', '.') }}</div>
                <div style="font-size:0.7rem;color:#94a3b8;">{{ $todayStats['transaksi'] }} transaksi</div>
            </div>

            <div class="stat-card purple animate-in animate-in-delay-2">
                <div class="stat-card-row">
                    <div class="stat-icon purple">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                </div>
                <div class="stat-label">Penjualan Bulan Ini</div>
                <div class="stat-value purple pasgar-stat-value">Rp {{ number_format($monthlyStats['penjualan'], 0, ',', '.') }}</div>
                <div style="font-size:0.7rem;color:#94a3b8;">{{ $monthlyStats['transaksi'] }} transaksi</div>
            </div>

            <div class="stat-card emerald animate-in animate-in-delay-3">
                <div class="stat-card-row">
                    <div class="stat-icon emerald">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                </div>
                <div class="stat-label">Loading Aktif</div>
                <div class="stat-value emerald pasgar-stat-value">{{ $activeLoadingsCount }}</div>
                <div style="font-size:0.7rem;color:#94a3b8;">dalam proses</div>
            </div>

            <div class="stat-card amber animate-in animate-in-delay-3">
                <div class="stat-card-row">
                    <div class="stat-icon amber">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    </div>
                </div>
                <div class="stat-label">Setoran Pending</div>
                <div class="stat-value amber pasgar-stat-value">{{ $setoranStatus['pending'] }}</div>
                <div style="font-size:0.7rem;color:#94a3b8;">menunggu verifikasi</div>
            </div>
        </div>
    </div>

    {{-- Activity Grid --}}
    <div class="ds-section">
        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;margin-bottom:0.65rem;">Aktivitas Terbaru</div>
        <div class="act-grid">
            {{-- Loading Aktif --}}
            <div class="panel animate-in">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Loading Aktif</div>
                        <div class="panel-subtitle">Loading yang sedang berjalan</div>
                    </div>
                    @if($activeLoadings->count() > 5)
                    <a href="{{ route('pasgar.loading.index') }}" class="panel-action">Lihat Semua</a>
                    @endif
                </div>
                <div class="panel-body" style="padding:0.5rem 1.25rem;">
                    @forelse($activeLoadings->take(5) as $loading)
                    <div class="activity-item">
                        <div class="activity-left">
                            <div class="activity-title">{{ $loading->nomor_loading }}</div>
                            <div class="activity-meta">{{ $loading->items->count() }} item &middot; {{ $loading->tanggal->format('d/m/Y') }}</div>
                        </div>
                        @php
                        $badgeMap = ['pending'=>'warning','preparing'=>'blue','ready'=>'indigo','picked_up'=>'success','loaded'=>'success','completed'=>'gray','opnamed'=>'teal','rejected'=>'danger'];
                        $badgeClass = $badgeMap[$loading->status] ?? 'gray';
                    @endphp
                    <span class="badge badge-{{ $badgeClass }}">{{ $loading->status_label }}</span>
                    </div>
                    @empty
                    <div class="empty-state" style="padding:2rem 0;">
                        <div class="empty-state-icon">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        </div>
                        <div class="empty-state-title">Tidak ada loading aktif</div>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Setoran --}}
            <div class="panel animate-in animate-in-delay-1">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Setoran</div>
                        <div class="panel-subtitle">Status setoran terkini</div>
                    </div>
                    <a href="{{ route('pasgar.setoran.index') }}" class="panel-action">Lihat Semua</a>
                </div>
                <div class="panel-body" style="padding:0.5rem 1.25rem;">
                    <div style="display:flex;gap:1rem;margin-bottom:0.75rem;padding:0.5rem 0;">
                        <div style="flex:1;background:#f8fafc;border-radius:10px;padding:0.75rem;text-align:center;">
                            <div style="font-size:0.68rem;font-weight:600;color:#94a3b8;margin-bottom:0.25rem;">Menunggu</div>
                            <div style="font-family:'JetBrains Mono',monospace;font-size:1.25rem;font-weight:800;color:#d97706;">{{ $setoranStatus['pending'] }}</div>
                        </div>
                        <div style="flex:1;background:#f8fafc;border-radius:10px;padding:0.75rem;text-align:center;">
                            <div style="font-size:0.68rem;font-weight:600;color:#94a3b8;margin-bottom:0.25rem;">Terverifikasi</div>
                            <div style="font-family:'JetBrains Mono',monospace;font-size:1.25rem;font-weight:800;color:#059669;">{{ $setoranStatus['terverifikasi'] }}</div>
                        </div>
                    </div>
                    @if($recentSetoran->isNotEmpty())
                    <div style="border-top:1px solid #f1f5f9;padding-top:0.5rem;">
                        <div style="font-size:0.65rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.25rem;">Terakhir</div>
                        @foreach($recentSetoran->take(3) as $st)
                        <div class="activity-item" style="padding:0.5rem 0;">
                            <div class="activity-left">
                                <div class="activity-title" style="font-size:0.75rem;">{{ $st->nomor_setoran }}</div>
                                <div class="activity-meta">{{ $st->tanggal->format('d/m/Y') }}</div>
                            </div>
                            <div class="activity-right">
                                <div class="activity-amount" style="font-size:0.75rem;">Rp {{ number_format($st->total_setor, 0, ',', '.') }}</div>
                                <span class="badge {{ $st->status === 'terverifikasi' ? 'badge-success' : ($st->status === 'ditolak' ? 'badge-danger' : 'badge-warning') }}" style="font-size:0.6rem;">{{ $st->status_label }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Penjualan Terakhir --}}
            <div class="panel animate-in animate-in-delay-2">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Penjualan Terakhir</div>
                        <div class="panel-subtitle">5 transaksi terbaru</div>
                    </div>
                    <a href="{{ route('pasgar.penjualan.index') }}" class="panel-action">Lihat Semua</a>
                </div>
                <div class="panel-body" style="padding:0.5rem 1.25rem;">
                    @forelse($recentPenjualan as $pj)
                    <div class="activity-item">
                        <div class="activity-left">
                            <div class="activity-title">{{ $pj->nomor_transaksi }}</div>
                            <div class="activity-meta">{{ $pj->nama_pelanggan ?? 'Non-pelanggan' }} &middot; {{ \Carbon\Carbon::parse($pj->tanggal)->format('d/m/Y') }}</div>
                        </div>
                        <div class="activity-right">
                            <div class="activity-amount">Rp {{ number_format($pj->total, 0, ',', '.') }}</div>
                            <span class="badge {{ $pj->metode_bayar === 'transfer' ? 'badge-blue' : 'badge-success' }}">{{ ucfirst($pj->metode_bayar) }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state" style="padding:2rem 0;">
                        <div class="empty-state-icon">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div class="empty-state-title">Belum ada penjualan</div>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Loading Hari Ini --}}
            <div class="panel animate-in animate-in-delay-3">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Loading Hari Ini</div>
                        <div class="panel-subtitle">Riwayat loading hari ini</div>
                    </div>
                </div>
                <div class="panel-body" style="padding:0.5rem 1.25rem;">
                    @forelse($loadingHariIni as $loading)
                    <div class="activity-item">
                        <div class="activity-left">
                            <div class="activity-title">{{ $loading->nomor_loading }}</div>
                            <div class="activity-meta">{{ $loading->sumber_label }} &middot; {{ $loading->items->count() }} item</div>
                        </div>
                        @php
                        $badgeMap = ['pending'=>'warning','preparing'=>'blue','ready'=>'indigo','picked_up'=>'success','loaded'=>'success','completed'=>'gray','opnamed'=>'teal','rejected'=>'danger'];
                        $badgeClass = $badgeMap[$loading->status] ?? 'gray';
                    @endphp
                    <span class="badge badge-{{ $badgeClass }}">{{ $loading->status_label }}</span>
                    </div>
                    @empty
                    <div class="empty-state" style="padding:2rem 0;">
                        <div class="empty-state-icon">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        </div>
                        <div class="empty-state-title">Belum ada loading hari ini</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
