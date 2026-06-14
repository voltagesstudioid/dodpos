<x-app-layout>
    <x-slot name="header">Gudang / Persiapan Barang</x-slot>

    <div class="pk-page">
        <div class="pk-container">

            {{-- ─── HERO ─── --}}
            <div class="pk-hero">
                <div class="pk-hero-left">
                    <div class="pk-hero-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="pk-title">Persiapan Barang</h1>
                        <p class="pk-subtitle">
                            @if($role === 'supervisor')
                                Pantau persiapan dari semua gudang
                            @else
                                Siapkan barang sesuai pesanan dari POS
                            @endif
                        </p>
                    </div>
                </div>
                <div class="pk-stats">
                    <a href="{{ route('gudang.pos_pick.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}" class="pk-stat-card pk-stat-warning {{ $status === 'pending' ? 'pk-stat-active' : '' }}">
                        <div class="pk-stat-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <span class="pk-stat-num">{{ $counts['pending'] }}</span>
                        <span class="pk-stat-lbl">Menunggu</span>
                    </a>
                    <a href="{{ route('gudang.pos_pick.index', array_merge(request()->except('page'), ['status' => 'processing'])) }}" class="pk-stat-card pk-stat-info {{ $status === 'processing' ? 'pk-stat-active' : '' }}">
                        <div class="pk-stat-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg>
                        </div>
                        <span class="pk-stat-num">{{ $counts['processing'] }}</span>
                        <span class="pk-stat-lbl">Diproses</span>
                    </a>
                    <a href="{{ route('gudang.pos_pick.index', array_merge(request()->except('page'), ['status' => 'ready'])) }}" class="pk-stat-card pk-stat-success {{ $status === 'ready' ? 'pk-stat-active' : '' }}">
                        <div class="pk-stat-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <span class="pk-stat-num">{{ $counts['ready'] }}</span>
                        <span class="pk-stat-lbl">Siap</span>
                    </a>
                    <a href="{{ route('gudang.pos_pick.index', array_merge(request()->except('page'), ['status' => 'completed'])) }}" class="pk-stat-card pk-stat-done {{ $status === 'completed' ? 'pk-stat-active' : '' }}">
                        <div class="pk-stat-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        </div>
                        <span class="pk-stat-num">{{ $counts['completed'] }}</span>
                        <span class="pk-stat-lbl">Selesai</span>
                    </a>
                    @if(($counts['cancelled'] ?? 0) > 0)
                    <a href="{{ route('gudang.pos_pick.index', array_merge(request()->except('page'), ['status' => 'cancelled'])) }}" class="pk-stat-card {{ $status === 'cancelled' ? 'pk-stat-active' : '' }}" style="background:#fef2f2; border-color:#fecaca;">
                        <div class="pk-stat-icon" style="color:#dc2626;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        </div>
                        <span class="pk-stat-num" style="color:#dc2626;">{{ $counts['cancelled'] }}</span>
                        <span class="pk-stat-lbl" style="color:#dc2626;">Dibatalkan</span>
                    </a>
                    @endif
                </div>
            </div>

            {{-- ─── FILTER + SEARCH ─── --}}
            <div class="pk-toolbar">
                <div class="pk-tabs">
                    @php $tabs = [
                        'pending'    => ['label' => 'Menunggu',    'color' => '#f59e0b'],
                        'processing' => ['label' => 'Diproses',    'color' => '#3b82f6'],
                        'ready'      => ['label' => 'Siap Diambil','color' => '#10b981'],
                        'completed'  => ['label' => 'Selesai',     'color' => '#94a3b8'],
                        'cancelled'  => ['label' => 'Dibatalkan',  'color' => '#dc2626'],
                        'all'        => ['label' => 'Semua',       'color' => '#6366f1'],
                    ]; @endphp
                    @foreach($tabs as $key => $tab)
                        <a href="{{ route('gudang.pos_pick.index', array_merge(request()->except('page'), ['status' => $key])) }}"
                           class="pk-tab {{ $status === $key ? 'pk-tab-active' : '' }}"
                           @if($status === $key) style="--tab-color: {{ $tab['color'] }};" @endif>
                            <span class="pk-tab-dot" style="background:{{ $tab['color'] }};"></span>
                            {{ $tab['label'] }}
                            @if($key !== 'all' && $key !== 'completed' && ($counts[$key] ?? 0) > 0)
                                <span class="pk-tab-badge">{{ $counts[$key] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
                <div class="pk-toolbar-right">
                    @if($role === 'supervisor' && $warehouses->count() > 1)
                        <form method="GET" class="pk-wh-filter">
                            <input type="hidden" name="status" value="{{ $status }}">
                            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                            <select name="warehouse" onchange="this.form.submit()" class="pk-wh-select">
                                <option value="">Semua Gudang</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}" {{ $warehouseFilter == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                    <form class="pk-search" method="GET">
                        <input type="hidden" name="status" value="{{ $status }}">
                        @if($warehouseFilter) <input type="hidden" name="warehouse" value="{{ $warehouseFilter }}"> @endif
                        <div class="pk-search-inner">
                            <svg class="pk-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            <input type="text" name="search" placeholder="Cari nomor / invoice / nama pembeli..." value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('gudang.pos_pick.index', array_merge(request()->except(['search', 'page']), ['status' => $status])) }}" class="pk-search-clear" title="Hapus pencarian">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success'))
                <div class="pk-alert pk-alert-success">
                    <div class="pk-alert-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="pk-alert pk-alert-danger">
                    <div class="pk-alert-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- ─── PICK ORDERS LIST ─── --}}
            @if($pickOrders->count() > 0)
                <div class="pk-list">
                    @foreach($pickOrders as $pick)
                        @php
                            $statusColors = [
                                'pending'    => ['bg' => '#fef3c7', 'fg' => '#92400e', 'ring' => '#fcd34d'],
                                'processing' => ['bg' => '#dbeafe', 'fg' => '#1e40af', 'ring' => '#93c5fd'],
                                'ready'      => ['bg' => '#dcfce7', 'fg' => '#166534', 'ring' => '#86efac'],
                                'completed'  => ['bg' => '#f1f5f9', 'fg' => '#64748b', 'ring' => '#cbd5e1'],
                                'cancelled'  => ['bg' => '#fef2f2', 'fg' => '#dc2626', 'ring' => '#fca5a5'],
                            ];
                            $sc = $statusColors[$pick->status] ?? $statusColors['completed'];
                        @endphp
                        <a href="{{ route('gudang.pos_pick.show', $pick) }}" class="pk-card">
                            {{-- Left accent bar --}}
                            <div class="pk-card-accent" style="background:{{ $sc['fg'] }};"></div>

                            <div class="pk-card-body">
                                {{-- Row 1: Number + Status + Time --}}
                                <div class="pk-card-top">
                                    <div class="pk-card-id">
                                        <span class="pk-card-hash">{{ $pick->pick_number }}</span>
                                        <span class="pk-badge" style="background:{{ $sc['bg'] }};color:{{ $sc['fg'] }};">{{ $pick->status_label }}</span>
                                        @if(str_contains($pick->notes ?? '', '[TAMBAHAN]'))
                                            <span class="pk-badge" style="background:#fef3c7;color:#92400e;font-size:10px;">Ada Tambahan</span>
                                        @endif
                                    </div>
                                    <span class="pk-card-time">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        {{ $pick->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                {{-- Row 2: Meta info --}}
                                <div class="pk-card-meta">
                                    <div class="pk-meta-chip">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        <span class="pk-meta-label">Pembeli</span>
                                        <span class="pk-meta-value">{{ $pick->transaction?->customer?->name ?? 'Umum' }}</span>
                                    </div>
                                    <div class="pk-meta-chip">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        <span class="pk-meta-label">Kasir</span>
                                        <span class="pk-meta-value">{{ $pick->requester?->name ?? '-' }}</span>
                                    </div>
                                    <div class="pk-meta-chip">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                        <span class="pk-meta-label">POS</span>
                                        <span class="pk-meta-value">{{ $pick->pos_type === 'eceran' ? 'Eceran' : 'Grosir' }}</span>
                                    </div>
                                    <div class="pk-meta-chip">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                        <span class="pk-meta-label">Gudang</span>
                                        <span class="pk-meta-value">{{ $pick->warehouse?->name ?? 'Utama' }}</span>
                                    </div>
                                </div>

                                {{-- Row 3: Items preview --}}
                                <div class="pk-card-items">
                                    @foreach($pick->items->take(4) as $item)
                                        <span class="pk-item-pill">
                                            <span class="pk-item-dot"></span>
                                            {{ $item->product?->name ?? '?' }}
                                            <span class="pk-item-qty">{{ $item->unit_qty }} {{ $item->unit_name }}</span>
                                        </span>
                                    @endforeach
                                    @if($pick->items->count() > 4)
                                        <span class="pk-item-pill pk-item-more">+{{ $pick->items->count() - 4 }} lainnya</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Arrow --}}
                            <div class="pk-card-arrow">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="pk-pagination">
                    {{ $pickOrders->links() }}
                </div>
            @else
                <div class="pk-empty">
                    <div class="pk-empty-illustration">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                    </div>
                    <h3>Tidak Ada Permintaan</h3>
                    <p>Belum ada permintaan persiapan barang dengan status ini.</p>
                </div>
            @endif

        </div>
    </div>

    @push('styles')
    <style>
        .pk-page { background: #f1f5f9; min-height: 100vh; font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; padding: 1.5rem; }
        .pk-container { max-width: 1100px; margin: 0 auto; }

        /* ── Hero ── */
        .pk-hero { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1.5rem; }
        .pk-hero-left { display: flex; align-items: center; gap: 1rem; }
        .pk-hero-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 12px rgba(99,102,241,.25); }
        .pk-title { font-size: 1.6rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.02em; }
        .pk-subtitle { color: #64748b; margin: 2px 0 0; font-size: 0.9rem; }

        /* ── Stat Cards ── */
        .pk-stats { display: flex; gap: 0.75rem; }
        .pk-stat-card { display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 0.7rem 1rem; border-radius: 14px; text-decoration: none; border: 1.5px solid transparent; transition: all .2s ease; min-width: 72px; cursor: pointer; }
        .pk-stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        .pk-stat-icon { margin-bottom: 2px; opacity: .7; }
        .pk-stat-num { font-size: 1.4rem; font-weight: 800; line-height: 1; }
        .pk-stat-lbl { font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
        .pk-stat-warning { background: #fffbeb; color: #92400e; }
        .pk-stat-warning .pk-stat-icon { color: #f59e0b; }
        .pk-stat-info { background: #eff6ff; color: #1e40af; }
        .pk-stat-info .pk-stat-icon { color: #3b82f6; }
        .pk-stat-success { background: #f0fdf4; color: #166534; }
        .pk-stat-success .pk-stat-icon { color: #10b981; }
        .pk-stat-done { background: #f8fafc; color: #64748b; }
        .pk-stat-done .pk-stat-icon { color: #94a3b8; }
        .pk-stat-active { border-color: currentColor; box-shadow: 0 4px 16px rgba(0,0,0,.1); }

        /* ── Toolbar ── */
        .pk-toolbar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; }
        .pk-toolbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
        .pk-tabs { display: flex; gap: 6px; background: #fff; padding: 5px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
        .pk-tab { display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; color: #64748b; text-decoration: none; transition: all .2s ease; white-space: nowrap; }
        .pk-tab:hover { background: #f1f5f9; color: #334155; }
        .pk-tab-active { background: var(--tab-color, #0f172a); color: #fff !important; box-shadow: 0 2px 8px rgba(0,0,0,.12); }
        .pk-tab-active .pk-tab-dot { box-shadow: 0 0 0 2px rgba(255,255,255,.4); }
        .pk-tab-active .pk-tab-badge { background: rgba(255,255,255,.25); color: #fff; }
        .pk-tab-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
        .pk-tab-badge { background: #ef4444; color: #fff; font-size: 0.65rem; font-weight: 700; padding: 1px 6px; border-radius: 10px; margin-left: 2px; }

        /* ── Warehouse Filter ── */
        .pk-wh-filter { flex-shrink: 0; }
        .pk-wh-select { padding: 8px 32px 8px 12px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.82rem; font-weight: 600; font-family: inherit; color: #334155; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 10px center; appearance: none; cursor: pointer; transition: border-color .2s, box-shadow .2s; }
        .pk-wh-select:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
        .pk-wh-select:hover { border-color: #cbd5e1; }

        /* ── Search ── */
        .pk-search { flex-shrink: 0; }
        .pk-search-inner { position: relative; display: flex; align-items: center; }
        .pk-search-icon { position: absolute; left: 12px; color: #94a3b8; pointer-events: none; }
        .pk-search input { padding: 9px 36px 9px 36px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.85rem; width: 280px; background: #fff; transition: border-color .2s, box-shadow .2s; font-family: inherit; }
        .pk-search input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
        .pk-search input::placeholder { color: #94a3b8; }
        .pk-search-clear { position: absolute; right: 10px; color: #94a3b8; display: flex; padding: 2px; border-radius: 4px; transition: color .15s; }
        .pk-search-clear:hover { color: #475569; }

        /* ── Alerts ── */
        .pk-alert { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 12px; margin-bottom: 1.25rem; font-size: 0.875rem; font-weight: 500; }
        .pk-alert-icon { display: flex; flex-shrink: 0; }
        .pk-alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .pk-alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── Pick Order Cards ── */
        .pk-list { display: flex; flex-direction: column; gap: 10px; }
        .pk-card { display: flex; align-items: stretch; background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; text-decoration: none; color: inherit; transition: all .2s ease; overflow: hidden; }
        .pk-card:hover { border-color: #cbd5e1; box-shadow: 0 8px 24px rgba(0,0,0,.06); transform: translateY(-1px); }
        .pk-card-accent { width: 4px; flex-shrink: 0; border-radius: 4px 0 0 4px; }
        .pk-card-body { flex: 1; padding: 1rem 1.25rem; min-width: 0; }
        .pk-card-arrow { display: flex; align-items: center; padding: 0 1rem; color: #cbd5e1; flex-shrink: 0; transition: color .2s; }
        .pk-card:hover .pk-card-arrow { color: #6366f1; }

        .pk-card-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .pk-card-id { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .pk-card-hash { font-weight: 700; font-size: 0.95rem; color: #0f172a; white-space: nowrap; }
        .pk-badge { padding: 3px 10px; border-radius: 20px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; white-space: nowrap; }
        .pk-card-time { display: flex; align-items: center; gap: 4px; font-size: 0.78rem; color: #94a3b8; white-space: nowrap; flex-shrink: 0; }

        .pk-card-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }
        .pk-meta-chip { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; background: #f8fafc; border-radius: 8px; font-size: 0.78rem; border: 1px solid #f1f5f9; }
        .pk-meta-chip svg { color: #94a3b8; flex-shrink: 0; }
        .pk-meta-label { color: #94a3b8; }
        .pk-meta-value { font-weight: 600; color: #334155; }

        .pk-card-items { display: flex; flex-wrap: wrap; gap: 6px; }
        .pk-item-pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; background: #f0f4ff; border-radius: 20px; font-size: 0.76rem; color: #334155; font-weight: 500; }
        .pk-item-dot { width: 5px; height: 5px; border-radius: 50%; background: #6366f1; flex-shrink: 0; }
        .pk-item-qty { color: #6366f1; font-weight: 700; }
        .pk-item-more { background: #f1f5f9; color: #64748b; }
        .pk-item-more .pk-item-dot { background: #94a3b8; }

        /* ── Pagination ── */
        .pk-pagination { margin-top: 1.5rem; }
        .pk-pagination nav { display: flex; justify-content: center; }
        .pk-pagination nav ul { display: flex; gap: 4px; list-style: none; padding: 0; margin: 0; }
        .pk-pagination nav ul li a, .pk-pagination nav ul li span { padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; border: 1px solid #e2e8f0; color: #64748b; text-decoration: none; transition: all .15s; }
        .pk-pagination nav ul li a:hover { background: #f1f5f9; border-color: #cbd5e1; }
        .pk-pagination nav ul li span[aria-current="page"] { background: #6366f1; color: #fff; border-color: #6366f1; }

        /* ── Empty State ── */
        .pk-empty { text-align: center; padding: 4rem 2rem; background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; }
        .pk-empty-illustration { color: #cbd5e1; margin-bottom: 1.25rem; }
        .pk-empty h3 { font-size: 1.15rem; font-weight: 700; color: #334155; margin: 0 0 6px; }
        .pk-empty p { color: #94a3b8; margin: 0; font-size: 0.9rem; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .pk-hero { flex-direction: column; }
            .pk-stats { width: 100%; overflow-x: auto; padding-bottom: 4px; }
            .pk-stat-card { min-width: 68px; }
            .pk-toolbar { flex-direction: column; align-items: stretch; }
            .pk-toolbar-right { flex-direction: column; }
            .pk-tabs { overflow-x: auto; }
            .pk-search input { width: 100%; }
            .pk-card-meta { flex-direction: column; }
            .pk-card-items { flex-direction: column; }
            .pk-wh-select { width: 100%; }
        }
    </style>
    @endpush
</x-app-layout>
