@extends('layouts.app')

@section('title', 'Pesanan Gudang - DODPOS')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --wh-bg: #f8fafc;
        --wh-surface: #ffffff;
        --wh-border: #e2e8f0;
        --wh-border-light: #f1f5f9;
        --wh-text-main: #0f172a;
        --wh-text-muted: #64748b;
        --wh-primary: #3b82f6;
        --wh-primary-hover: #2563eb;
        --wh-radius-lg: 20px;
        --wh-radius-md: 12px;
        --wh-shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        --wh-shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    /* Core Layout */
    .wh-page-wrapper {
        background-color: var(--wh-bg);
        background-image: radial-gradient(circle at top right, #eff6ff 0%, transparent 40%), radial-gradient(circle at bottom left, #f8fafc 0%, transparent 40%);
        min-height: calc(100vh - 64px);
        padding: 2rem 1.5rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--wh-text-main);
    }
    
    .wh-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Header */
    .wh-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .wh-header-content {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .wh-header-icon {
        width: 56px; height: 56px;
        border-radius: 16px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.4);
    }

    .wh-title { font-size: 1.75rem; font-weight: 800; margin: 0; display:flex; align-items:center; gap: 0.75rem; }
    .wh-subtitle { font-size: 0.9rem; color: var(--wh-text-muted); margin-top: 0.25rem; }
    
    .wh-live-badge {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        background: #dcfce7; color: #166534; padding: 0.2rem 0.6rem; border-radius: 99px;
        display: inline-flex; align-items: center; gap: 4px; border: 1px solid #bbf7d0;
    }
    .wh-live-dot { width: 6px; height: 6px; background: #22c55e; border-radius: 50%; animation: pulse 2s infinite; }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
        100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }

    /* Metrics Grid */
    .wh-metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .wh-metric-card {
        background: var(--wh-surface);
        border: 2px solid transparent;
        border-radius: var(--wh-radius-lg);
        padding: 1.25rem 1.5rem;
        display: flex; flex-direction: column;
        transition: all 0.3s ease;
        text-decoration: none;
        position: relative;
        overflow: hidden;
        box-shadow: var(--wh-shadow-sm);
    }

    .wh-metric-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--wh-shadow-md);
    }

    .wh-metric-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
    .wh-metric-icon { 
        width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease;
    }
    .wh-metric-count { font-size: 1.75rem; font-weight: 800; color: var(--wh-text-main); }
    .wh-metric-title { font-size: 0.85rem; font-weight: 700; color: var(--wh-text-main); margin-bottom: 2px; }
    .wh-metric-subtitle { font-size: 0.75rem; color: var(--wh-text-muted); display:flex; align-items:center; gap:4px; }
    .wh-metric-dot { width: 4px; height: 4px; border-radius: 50%; }

    /* Metric Colors & States */
    .wh-card-pending .wh-metric-icon { background: #fef9c3; color: #a16207; }
    .wh-card-pending .wh-metric-dot { background: #eab308; }
    .wh-card-pending:hover { border-color: #fde047; }
    .wh-card-pending.active { border-color: #eab308; box-shadow: 0 10px 20px -5px rgba(234, 179, 8, 0.2); }
    .wh-card-pending.active .wh-metric-icon { background: #eab308; color: white; }

    .wh-card-packing .wh-metric-icon { background: #e0f2fe; color: #0369a1; }
    .wh-card-packing .wh-metric-dot { background: #0ea5e9; }
    .wh-card-packing:hover { border-color: #7dd3fc; }
    .wh-card-packing.active { border-color: #0ea5e9; box-shadow: 0 10px 20px -5px rgba(14, 165, 233, 0.2); }
    .wh-card-packing.active .wh-metric-icon { background: #0ea5e9; color: white; }

    .wh-card-packed .wh-metric-icon { background: #f3e8ff; color: #7e22ce; }
    .wh-card-packed .wh-metric-dot { background: #a855f7; }
    .wh-card-packed:hover { border-color: #d8b4fe; }
    .wh-card-packed.active { border-color: #a855f7; box-shadow: 0 10px 20px -5px rgba(168, 85, 247, 0.2); }
    .wh-card-packed.active .wh-metric-icon { background: #a855f7; color: white; }

    .wh-card-transit .wh-metric-icon { background: #ffedd5; color: #c2410c; }
    .wh-card-transit .wh-metric-dot { background: #f97316; }
    .wh-card-transit:hover { border-color: #fdba74; }
    .wh-card-transit.active { border-color: #f97316; box-shadow: 0 10px 20px -5px rgba(249, 115, 22, 0.2); }
    .wh-card-transit.active .wh-metric-icon { background: #f97316; color: white; }

    .wh-card-delivered .wh-metric-icon { background: #dcfce7; color: #15803d; }
    .wh-card-delivered .wh-metric-dot { background: #22c55e; }
    .wh-card-delivered:hover { border-color: #86efac; }
    .wh-card-delivered.active { border-color: #22c55e; box-shadow: 0 10px 20px -5px rgba(34, 197, 94, 0.2); }
    .wh-card-delivered.active .wh-metric-icon { background: #22c55e; color: white; }

    /* Active Ping Indicator */
    .wh-active-ping { position: absolute; top: 1rem; right: 1rem; width: 10px; height: 10px; }
    .wh-active-ping .ping-circle { animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite; position: absolute; width: 100%; height: 100%; border-radius: 50%; opacity: 0.75; }
    .wh-active-ping .ping-dot { position: relative; width: 10px; height: 10px; border-radius: 50%; display: inline-flex; }
    
    @keyframes ping {
        75%, 100% { transform: scale(2); opacity: 0; }
    }

    /* Content Area */
    .wh-content-box {
        background: var(--wh-surface);
        border-radius: var(--wh-radius-lg);
        border: 1px solid var(--wh-border);
        box-shadow: var(--wh-shadow-sm);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    /* Filter Toolbar */
    .wh-toolbar {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--wh-border-light);
        display: flex; gap: 1rem; align-items: center; justify-content: space-between;
        flex-wrap: wrap; background: #fafafa;
    }
    
    .wh-filter-group { display: flex; gap: 1rem; flex-wrap: wrap; flex: 1; }
    
    .wh-input-wrapper { position: relative; min-width: 220px; }
    .wh-input-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--wh-text-muted); pointer-events: none; }
    .wh-select {
        width: 100%; padding: 0.7rem 1rem 0.7rem 2.8rem;
        border: 1px solid var(--wh-border); border-radius: var(--wh-radius-md);
        font-family: inherit; font-size: 0.85rem; font-weight: 500; color: var(--wh-text-main);
        background-color: #fff; cursor: pointer; appearance: none;
        transition: all 0.2s ease; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-size: 16px; background-position: right 1rem center; background-repeat: no-repeat;
    }
    .wh-select:focus { border-color: var(--wh-primary); outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

    .wh-actions-group { display: flex; gap: 0.75rem; }
    .wh-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 0.7rem 1.25rem; border-radius: var(--wh-radius-md); font-size: 0.85rem; 
        font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; font-family: inherit;
    }
    .wh-btn-outline { background: #fff; border: 1px solid var(--wh-border); color: var(--wh-text-main); box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
    .wh-btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; }
    .wh-btn-primary { background: var(--wh-primary); color: #fff; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3); }
    .wh-btn-primary:hover { background: var(--wh-primary-hover); transform: translateY(-1px); }

    /* Table */
    .wh-table-container { width: 100%; overflow-x: auto; }
    .wh-table { width: 100%; border-collapse: collapse; text-align: left; }
    .wh-table th { 
        padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 700; 
        color: var(--wh-text-muted); text-transform: uppercase; letter-spacing: 0.05em;
        border-bottom: 2px solid var(--wh-border-light); background: #fbfbfc;
    }
    .wh-table td { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--wh-border-light); vertical-align: middle; transition: background 0.2s; }
    .wh-table tr:last-child td { border-bottom: none; }
    .wh-table tbody tr:hover td { background-color: #f8fafc; }

    /* Table Elements */
    .wh-invoice-id { font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace; font-weight: 700; color: var(--wh-text-main); font-size: 0.9rem; }
    .wh-datetime { display: flex; flex-direction: column; gap: 2px; }
    .wh-date { font-weight: 600; color: var(--wh-text-main); font-size: 0.85rem; }
    .wh-time { font-size: 0.75rem; color: var(--wh-text-muted); display: flex; align-items: center; gap: 4px; }
    
    .wh-user-block { display: flex; align-items: center; gap: 0.75rem; }
    .wh-avatar { width: 36px; height: 36px; border-radius: 10px; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; }
    .wh-user-name { font-weight: 600; font-size: 0.85rem; color: var(--wh-text-main); }
    
    .wh-warehouse-tag { display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 500; color: var(--wh-text-main); }
    
    .wh-item-count { background: #f1f5f9; color: var(--wh-text-muted); padding: 0.25rem 0.6rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; border: 1px solid #e2e8f0; }
    
    .wh-total { font-weight: 800; font-size: 0.95rem; color: var(--wh-text-main); font-family: 'SFMono-Regular', monospace; }

    /* Status Badges */
    .wh-status { padding: 0.4rem 0.85rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; border: 1px solid transparent; }
    .wh-status-pending { background: #fef9c3; color: #854d0e; border-color: #fef08a; }
    .wh-status-packing { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
    .wh-status-packed { background: #f3e8ff; color: #6b21a8; border-color: #e9d5ff; }
    .wh-status-transit { background: #ffedd5; color: #c2410c; border-color: #fed7aa; }
    .wh-status-delivered { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }

    .wh-action-btn { 
        display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 10px;
        font-size: 0.8rem; font-weight: 700; color: white; text-decoration: none;
        background: linear-gradient(135deg, #3b82f6 0%, #4f46e5 100%); transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
    }
    .wh-action-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 10px -1px rgba(59, 130, 246, 0.4); filter: brightness(1.1); }

    /* Empty State */
    .wh-empty { padding: 5rem 2rem; text-align: center; }
    .wh-empty-icon { width: 80px; height: 80px; margin: 0 auto 1.5rem; background: #f1f5f9; border-radius: 20px; display: flex; align-items: center; justify-content: center; color: #94a3b8; }
    .wh-empty-title { font-size: 1.25rem; font-weight: 800; color: var(--wh-text-main); margin-bottom: 0.5rem; }
    .wh-empty-desc { font-size: 0.9rem; color: var(--wh-text-muted); max-width: 400px; margin: 0 auto; line-height: 1.5; }

    /* Pagination Override */
    .wh-pagination { padding: 1.25rem 1.5rem; border-top: 1px solid var(--wh-border-light); background: #fff; }

    @media (max-width: 1024px) {
        .wh-header { flex-direction: column; align-items: flex-start; }
    }
    @media (max-width: 768px) {
        .wh-toolbar { flex-direction: column; align-items: stretch; }
        .wh-input-wrapper { min-width: 100%; }
        .wh-actions-group { width: 100%; }
        .wh-actions-group .wh-btn { flex: 1; }
    }
</style>
@endpush

@section('content')
<div class="wh-page-wrapper">
    <div class="wh-container">
        
        {{-- Header Section --}}
        <div class="wh-header">
            <div class="wh-header-content">
                <div class="wh-header-icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="wh-title">
                        Pesanan Gudang
                        <span class="wh-live-badge">
                            <span class="wh-live-dot"></span> LIVE
                        </span>
                    </h1>
                    <p class="wh-subtitle">Kelola daftar pesanan yang perlu dikemas dan diantar ke toko/grosir.</p>
                </div>
            </div>
        </div>

        {{-- Metrics Grid --}}
        <div class="wh-metrics-grid">
            {{-- Pending --}}
            <a href="{{ route('warehouse.orders.index', ['status' => 'pending']) }}" class="wh-metric-card wh-card-pending {{ $status === 'pending' ? 'active' : '' }}">
                <div class="wh-metric-top">
                    <div class="wh-metric-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="wh-metric-count">{{ $counts['pending'] }}</span>
                </div>
                <div class="wh-metric-title">Menunggu</div>
                <div class="wh-metric-subtitle"><span class="wh-metric-dot"></span> Perlu dikemas</div>
                @if($status === 'pending')
                    <div class="wh-active-ping"><span class="ping-circle" style="background:#eab308;"></span><span class="ping-dot" style="background:#eab308;"></span></div>
                @endif
            </a>

            {{-- Packing --}}
            <a href="{{ route('warehouse.orders.index', ['status' => 'packing']) }}" class="wh-metric-card wh-card-packing {{ $status === 'packing' ? 'active' : '' }}">
                <div class="wh-metric-top">
                    <div class="wh-metric-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="wh-metric-count">{{ $counts['packing'] }}</span>
                </div>
                <div class="wh-metric-title">Dikemas</div>
                <div class="wh-metric-subtitle"><span class="wh-metric-dot"></span> Sedang proses</div>
                @if($status === 'packing')
                    <div class="wh-active-ping"><span class="ping-circle" style="background:#0ea5e9;"></span><span class="ping-dot" style="background:#0ea5e9;"></span></div>
                @endif
            </a>

            {{-- Packed --}}
            <a href="{{ route('warehouse.orders.index', ['status' => 'packed']) }}" class="wh-metric-card wh-card-packed {{ $status === 'packed' ? 'active' : '' }}">
                <div class="wh-metric-top">
                    <div class="wh-metric-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="wh-metric-count">{{ $counts['packed'] }}</span>
                </div>
                <div class="wh-metric-title">Selesai Kemas</div>
                <div class="wh-metric-subtitle"><span class="wh-metric-dot"></span> Siap cross-check</div>
                @if($status === 'packed')
                    <div class="wh-active-ping"><span class="ping-circle" style="background:#a855f7;"></span><span class="ping-dot" style="background:#a855f7;"></span></div>
                @endif
            </a>

            {{-- In Transit --}}
            <a href="{{ route('warehouse.orders.index', ['status' => 'in_transit']) }}" class="wh-metric-card wh-card-transit {{ $status === 'in_transit' ? 'active' : '' }}">
                <div class="wh-metric-top">
                    <div class="wh-metric-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    </div>
                    <span class="wh-metric-count">{{ $counts['in_transit'] }}</span>
                </div>
                <div class="wh-metric-title">Dalam Perjalanan</div>
                <div class="wh-metric-subtitle"><span class="wh-metric-dot"></span> Sedang dikirim</div>
                @if($status === 'in_transit')
                    <div class="wh-active-ping"><span class="ping-circle" style="background:#f97316;"></span><span class="ping-dot" style="background:#f97316;"></span></div>
                @endif
            </a>

            {{-- Delivered --}}
            <a href="{{ route('warehouse.orders.index', ['status' => 'delivered']) }}" class="wh-metric-card wh-card-delivered {{ $status === 'delivered' ? 'active' : '' }}">
                <div class="wh-metric-top">
                    <div class="wh-metric-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="wh-metric-count">{{ $counts['delivered'] }}</span>
                </div>
                <div class="wh-metric-title">Terkirim</div>
                <div class="wh-metric-subtitle"><span class="wh-metric-dot"></span> Sampai tujuan</div>
                @if($status === 'delivered')
                    <div class="wh-active-ping"><span class="ping-circle" style="background:#22c55e;"></span><span class="ping-dot" style="background:#22c55e;"></span></div>
                @endif
            </a>
        </div>

        {{-- Content Box --}}
        <div class="wh-content-box">
            
            {{-- Toolbar --}}
            <div class="wh-toolbar">
                <form method="GET" action="{{ route('warehouse.orders.index') }}" class="wh-filter-group" id="filterForm">
                    <div class="wh-input-wrapper">
                        <svg class="wh-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <select name="status" class="wh-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="packing" {{ $status === 'packing' ? 'selected' : '' }}>Dikemas</option>
                            <option value="packed" {{ $status === 'packed' ? 'selected' : '' }}>Selesai Kemas</option>
                            <option value="in_transit" {{ $status === 'in_transit' ? 'selected' : '' }}>Dalam Perjalanan</option>
                            <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>Terdeliver</option>
                        </select>
                    </div>

                    @if(auth()->user()->role === 'supervisor' || auth()->user()->role === 'admin')
                    <div class="wh-input-wrapper">
                        <svg class="wh-input-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <select name="warehouse_id" class="wh-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Gudang</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ $warehouseId == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="wh-actions-group">
                        <a href="{{ route('warehouse.orders.index') }}" class="wh-btn wh-btn-outline">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Reset
                        </a>
                        <button type="submit" class="wh-btn wh-btn-primary">
                            Terapkan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table View --}}
            <div class="wh-table-container">
                <table class="wh-table">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Tanggal & Waktu</th>
                            <th>Kasir / Petugas</th>
                            <th>Gudang Asal</th>
                            <th style="text-align:center;">Item</th>
                            <th style="text-align:center;">Status</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $conf = match($order->delivery_status) {
                                    'pending' => ['cls' => 'wh-status-pending', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Menunggu'],
                                    'packing' => ['cls' => 'wh-status-packing', 'icon' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z', 'label' => 'Dikemas'],
                                    'packed' => ['cls' => 'wh-status-packed', 'icon' => 'M5 13l4 4L19 7', 'label' => 'Selesai Kemas'],
                                    'in_transit' => ['cls' => 'wh-status-transit', 'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0', 'label' => 'Perjalanan'],
                                    'delivered' => ['cls' => 'wh-status-delivered', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Terkirim'],
                                    default => ['cls' => '', 'icon' => 'M13 16h-1v-4l-5-1 5-1V6h1l5 5-5 5z', 'label' => $order->delivery_status],
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="wh-invoice-id">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                                </td>
                                <td>
                                    <div class="wh-datetime">
                                        <span class="wh-date">{{ $order->created_at->format('d M Y') }}</span>
                                        <span class="wh-time">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $order->created_at->format('H:i') }} WIB
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="wh-user-block">
                                        <div class="wh-avatar">{{ strtoupper(substr($order->user?->name ?? 'U', 0, 1)) }}</div>
                                        <div class="wh-user-name">{{ $order->user?->name ?? '-' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="wh-warehouse-tag">
                                        <svg width="16" height="16" fill="none" stroke="#94a3b8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        {{ $order->sourceWarehouse?->name ?? '-' }}
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    <span class="wh-item-count">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        {{ $order->details->count() }}
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <span class="wh-status {{ $conf['cls'] }}">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $conf['icon'] }}"/></svg>
                                        {{ $conf['label'] }}
                                    </span>
                                </td>
                                <td style="text-align:right;">
                                    <a href="{{ route('warehouse.orders.show', $order) }}" class="wh-action-btn">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="wh-empty">
                                        <div class="wh-empty-icon">
                                            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                        </div>
                                        <div class="wh-empty-title">Tidak ada data pesanan</div>
                                        <div class="wh-empty-desc">Belum ada pesanan yang sesuai dengan filter saat ini, atau belum ada pesanan yang masuk.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($orders->hasPages())
                <div class="wh-pagination">
                    {{ $orders->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection  