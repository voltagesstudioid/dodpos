<x-app-layout>
    <x-slot name="header">Permintaan Barang</x-slot>

    @push('styles')
    <style>
        .gr-page { max-width: 1280px; margin: 0 auto; padding: 0 0 3rem; animation: fadeSlideIn 0.35s ease both; }

        .gr-hero {
            background: linear-gradient(135deg, #06090f 0%, #0d1322 35%, #111827 70%, #0a0e1a 100%);
            border-radius: 20px; padding: 2rem 2.25rem 3.25rem;
            margin-bottom: -1.75rem; position: relative; overflow: hidden;
        }
        .gr-hero::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse at 85% 20%, rgba(99,102,241,0.22) 0%, transparent 60%),
                        radial-gradient(ellipse at 15% 80%, rgba(245,158,11,0.1) 0%, transparent 50%);
        }
        .gr-hero::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.5), rgba(245,158,11,0.3), transparent);
        }
        .gr-hero-inner { position: relative; z-index: 1; }
        .gr-hero-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem; }
        .gr-hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3);
            padding: 0.3rem 0.875rem; border-radius: 99px;
            font-size: 0.65rem; font-weight: 700; color: rgba(165,180,252,0.9);
            text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.875rem;
        }
        .gr-hero-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: #818cf8; animation: gr-pulse 2s infinite; }
        .gr-hero-title { font-size: 2rem; font-weight: 900; color: #fff; letter-spacing: -0.04em; line-height: 1.1; margin: 0 0 0.4rem; }
        .gr-hero-subtitle { font-size: 0.8125rem; color: rgba(255,255,255,0.45); margin: 0; }
        .gr-hero-actions { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
        .gr-btn-create {
            display: inline-flex; align-items: center; gap: 7px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff; padding: 0.65rem 1.375rem; border-radius: 10px;
            font-weight: 700; font-size: 0.8125rem; text-decoration: none;
            transition: all 0.2s; box-shadow: 0 4px 14px rgba(99,102,241,0.35);
        }
        .gr-btn-create:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(99,102,241,0.45); }

        .gr-stats-row { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .gr-stat-pill {
            background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px; padding: 0.875rem 1.25rem; min-width: 130px;
            backdrop-filter: blur(8px); transition: all 0.2s; text-decoration: none;
            position: relative; overflow: hidden;
        }
        .gr-stat-pill:hover { background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.2); transform: translateY(-2px); }
        .gr-stat-pill.active { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.3); }
        .gr-stat-pill.active::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
            background: var(--accent); border-radius: 0 0 3px 3px;
        }
        .gr-stat-pill-label { font-size: 0.6rem; font-weight: 700; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.35rem; }
        .gr-stat-pill-value { font-size: 1.375rem; font-weight: 900; color: #fff; font-family: ui-monospace, monospace; letter-spacing: -0.02em; line-height: 1; }
        .gr-stat-pill-dot { position: absolute; top: 0.75rem; right: 0.75rem; width: 8px; height: 8px; border-radius: 50%; background: var(--accent); }

        .gr-content { position: relative; z-index: 2; padding-top: 2rem; }

        .gr-alert {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.875rem 1.25rem; border-radius: 12px;
            margin-bottom: 1.25rem; font-size: 0.875rem; font-weight: 500;
            animation: fadeSlideIn 0.3s ease;
        }
        .gr-alert-ok { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .gr-alert-err { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
        .gr-alert svg { flex-shrink: 0; }

        .gr-panel {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
            overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .gr-panel-header {
            padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(180deg, #f8fafc, #fff);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem;
        }
        .gr-panel-title { font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
        .gr-panel-title-icon {
            width: 28px; height: 28px; border-radius: 8px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            display: flex; align-items: center; justify-content: center;
        }
        .gr-panel-title-icon svg { width: 14px; height: 14px; color: #4f46e5; }
        .gr-panel-meta { font-size: 0.75rem; color: #64748b; margin-top: 2px; }

        .gr-filter {
            padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9;
            background: #fafbfc;
        }
        .gr-filter-form { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .gr-search {
            display: flex; align-items: center; gap: 8px;
            background: #fff; border: 1.5px solid #e2e8f0; border-radius: 10px;
            padding: 0 1rem; flex: 1; min-width: 200px; transition: all 0.2s;
        }
        .gr-search:focus-within { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }
        .gr-search svg { color: #94a3b8; flex-shrink: 0; }
        .gr-search input {
            border: none; background: transparent; font-size: 0.875rem;
            outline: none; width: 100%; padding: 0.625rem 0; font-family: inherit;
        }
        .gr-select {
            padding: 0.625rem 2rem 0.625rem 0.875rem; border-radius: 10px;
            border: 1.5px solid #e2e8f0; font-size: 0.875rem; background: #fff;
            font-family: inherit; outline: none; transition: all 0.2s; cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 0.75rem center;
        }
        .gr-select:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }
        .gr-dates { display: flex; align-items: center; gap: 0.375rem; }
        .gr-date-sep { color: #94a3b8; font-size: 0.75rem; }
        .gr-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.625rem 1.125rem; border-radius: 10px;
            font-size: 0.8125rem; font-weight: 700; text-decoration: none;
            border: none; cursor: pointer; transition: all 0.2s; font-family: inherit;
        }
        .gr-btn-dark { background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff; box-shadow: 0 4px 12px rgba(15,23,42,0.2); }
        .gr-btn-dark:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(15,23,42,0.3); }
        .gr-btn-reset {
            background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;
            padding: 0.625rem 0.875rem; border-radius: 10px;
            font-size: 0.8125rem; font-weight: 600; text-decoration: none;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.375rem;
        }
        .gr-btn-reset:hover { background: #fee2e2; border-color: #fca5a5; }
        .gr-filter-info {
            padding: 0.625rem 1.5rem; font-size: 0.8rem; color: #64748b;
            background: #fafbfc; border-bottom: 1px solid #f1f5f9;
        }
        .gr-filter-info strong { color: #0f172a; }

        .gr-table-wrap { overflow-x: auto; }
        .gr-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .gr-table th {
            background: linear-gradient(180deg, #f8fafc, #f4f8fc);
            padding: 0.75rem 1.25rem; text-align: left;
            font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
            color: #64748b; border-bottom: 2px solid #e2e8f0;
            letter-spacing: 0.06em; white-space: nowrap;
        }
        .gr-table td {
            padding: 0.875rem 1.25rem; border-bottom: 1px solid #f1f5f9;
            font-size: 0.8125rem; color: #374151; vertical-align: middle;
        }
        .gr-table tbody tr { transition: background 0.15s; }
        .gr-table tbody tr:hover td { background: linear-gradient(90deg, #fafbff, #f8f9ff); }
        .gr-table tbody tr:last-child td { border-bottom: none; }
        .gr-table .tc { text-align: center; }

        .gr-date { display: flex; flex-direction: column; gap: 2px; }
        .gr-date-main { font-weight: 600; color: #0f172a; font-size: 0.8125rem; }
        .gr-date-sub { font-size: 0.7rem; color: #94a3b8; }

        .gr-user { display: flex; align-items: center; gap: 0.625rem; }
        .gr-avatar {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 0.75rem; font-weight: 700; flex-shrink: 0;
        }
        .gr-user-info { display: flex; flex-direction: column; gap: 1px; }
        .gr-user-name { font-weight: 600; color: #0f172a; font-size: 0.8125rem; }
        .gr-user-role { font-size: 0.65rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }

        .gr-prod { display: flex; flex-direction: column; gap: 2px; }
        .gr-prod-name { font-weight: 600; color: #0f172a; font-size: 0.8125rem; }
        .gr-prod-sku { font-size: 0.7rem; color: #94a3b8; font-family: ui-monospace, monospace; }

        .gr-qty { font-weight: 800; font-size: 1.125rem; color: #0f172a; font-family: ui-monospace, monospace; }
        .gr-qty-unit { font-size: 0.7rem; color: #94a3b8; }

        .gr-tag {
            display: inline-flex; align-items: center; padding: 0.25rem 0.625rem;
            border-radius: 6px; font-size: 0.7rem; font-weight: 700;
        }
        .gr-tag-po { background: #dbeafe; color: #1e40af; }
        .gr-tag-transfer { background: #fef3c7; color: #92400e; }
        .gr-route { font-size: 0.7rem; color: #94a3b8; margin-top: 3px; }

        .gr-note { font-size: 0.8rem; color: #475569; font-style: italic; }

        .gr-badge {
            display: inline-flex; align-items: center; padding: 0.25rem 0.625rem;
            border-radius: 6px; font-size: 0.7rem; font-weight: 700;
        }
        .gr-badge-pending { background: #fef3c7; color: #92400e; }
        .gr-badge-approved { background: #dcfce7; color: #166534; }
        .gr-badge-rejected { background: #fee2e2; color: #991b1b; }
        .gr-badge-completed { background: #f1f5f9; color: #64748b; }

        .gr-del {
            width: 32px; height: 32px; border-radius: 8px;
            display: inline-flex; align-items: center; justify-content: center;
            border: none; cursor: pointer; background: transparent;
            color: #cbd5e1; transition: all 0.2s;
        }
        .gr-del:hover { background: #fee2e2; color: #ef4444; }

        .gr-empty {
            text-align: center; padding: 3.5rem 1.5rem; color: #94a3b8;
        }
        .gr-empty-icon {
            width: 56px; height: 56px; border-radius: 14px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
        }
        .gr-empty-title { font-size: 0.9375rem; font-weight: 700; color: #64748b; margin: 0 0 0.375rem; }
        .gr-empty-sub { font-size: 0.8125rem; margin: 0; }

        .gr-pagination { padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; }

        @keyframes gr-pulse { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.3); } }

        @media (max-width: 1024px) {
            .gr-stats-row { flex-wrap: wrap; }
            .gr-stat-pill { min-width: 110px; flex: 1; }
        }
        @media (max-width: 768px) {
            .gr-hero { padding: 1.5rem 1.25rem 2.5rem; border-radius: 16px; }
            .gr-hero-title { font-size: 1.5rem; }
            .gr-filter-form { flex-direction: column; align-items: stretch; }
            .gr-search { min-width: 0; }
            .gr-dates { flex-wrap: wrap; }
            .gr-table { font-size: 0.75rem; }
            .gr-table th, .gr-table td { padding: 0.625rem 0.75rem; }
            .gr-avatar { width: 28px; height: 28px; font-size: 0.65rem; }
        }
        @media (max-width: 480px) {
            .gr-stats-row { flex-direction: column; }
            .gr-stat-pill { min-width: 0; }
        }
    </style>
    @endpush

    <div class="gr-page">

        <div class="gr-hero">
            <div class="gr-hero-inner">
                <div class="gr-hero-top">
                    <div>
                        <div class="gr-hero-badge"><span class="gr-hero-badge-dot"></span> Manajemen Permintaan</div>
                        <h1 class="gr-hero-title">Permintaan Barang</h1>
                        <p class="gr-hero-subtitle">Kelola permintaan Purchase Order dan Transfer antar gudang.</p>
                    </div>
                    <div class="gr-hero-actions">
                        @if($role !== 'supervisor')
                        <a href="{{ route('gudang.request.create') }}" class="gr-btn-create">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Buat Permintaan
                        </a>
                        @endif
                    </div>
                </div>

                <div class="gr-stats-row">
                    @php
                        $cur = request('status');
                        $items = [
                            [null,      $totalCount,     'Total',     '#818cf8'],
                            ['pending',  $pendingCount,   'Menunggu',  '#f59e0b'],
                            ['approved', $approvedCount,  'Disetujui', '#10b981'],
                            ['rejected', $rejectedCount,  'Ditolak',   '#ef4444'],
                            ['completed',$completedCount, 'Selesai',   '#64748b'],
                        ];
                    @endphp
                    @foreach($items as $i)
                        @php
                            $active = $cur === $i[0] || (is_null($i[0]) && !$cur);
                            $url = route('gudang.request.index', array_merge(request()->except('status','page'), $i[0] ? ['status'=>$i[0]] : []));
                        @endphp
                        <a href="{{ $url }}" class="gr-stat-pill {{ $active ? 'active' : '' }}" style="--accent: {{ $i[3] }};">
                            <div class="gr-stat-pill-label">{{ $i[2] }}</div>
                            <div class="gr-stat-pill-value">{{ number_format($i[1]) }}</div>
                            @if($active && !$i[0])
                                <span class="gr-stat-pill-dot"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="gr-content">

            @if(session('success'))
                <div class="gr-alert gr-alert-ok">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="gr-alert gr-alert-err">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="gr-panel">
                <div class="gr-panel-header">
                    <div>
                        <h3 class="gr-panel-title">
                            <span class="gr-panel-title-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </span>
                            Daftar Permintaan
                        </h3>
                        <p class="gr-panel-meta">{{ number_format($totalCount) }} total permintaan</p>
                    </div>
                </div>

                <div class="gr-filter">
                    <form method="GET" action="{{ route('gudang.request.index') }}" class="gr-filter-form">
                        <div class="gr-search">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk, SKU, pemohon...">
                        </div>
                        <select name="type" class="gr-select" onchange="this.form.submit()">
                            <option value="">Semua Tipe</option>
                            <option value="po" {{ request('type')==='po'?'selected':'' }}>PO Baru</option>
                            <option value="transfer" {{ request('type')==='transfer'?'selected':'' }}>Transfer</option>
                        </select>
                        <div class="gr-dates">
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="gr-select" title="Dari">
                            <span class="gr-date-sep">→</span>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="gr-select" title="Sampai">
                        </div>
                        <button type="submit" class="gr-btn gr-btn-dark">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                            Filter
                        </button>
                        @if(request()->hasAny(['q','status','type','date_from','date_to']) && (request('q') || request('status') || request('type') || request('date_from') || request('date_to')))
                            <a href="{{ route('gudang.request.index') }}" class="gr-btn-reset">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                @php $hasFilter = request()->filled('q') || request()->filled('status') || request()->filled('type') || request()->filled('date_from') || request()->filled('date_to'); @endphp
                @if($hasFilter)
                    <div class="gr-filter-info">
                        <strong>{{ number_format($filteredCount) }}</strong> dari <strong>{{ number_format($totalCount) }}</strong> permintaan
                    </div>
                @endif

                <div class="gr-table-wrap">
                    <table class="gr-table">
                        <thead>
                            <tr>
                                <th style="width:110px">Tanggal</th>
                                <th>Pemohon</th>
                                <th>Produk</th>
                                <th class="tc" style="width:80px">Qty</th>
                                <th>Tipe</th>
                                <th>Catatan</th>
                                <th style="width:100px">Status</th>
                                <th class="tc" style="width:60px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                            <tr>
                                <td>
                                    <div class="gr-date">
                                        <span class="gr-date-main">{{ $req->created_at->format('d M Y') }}</span>
                                        <span class="gr-date-sub">{{ $req->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="gr-user">
                                        @php
                                            $name = $req->user->name ?? '?';
                                            $initial = strtoupper(substr($name, 0, 1));
                                            $colors = ['#6366f1','#ec4899','#f59e0b','#10b981','#3b82f6','#8b5cf6'];
                                            $colorIdx = crc32($name) % count($colors);
                                            $avatarBg = $colors[$colorIdx];
                                        @endphp
                                        <span class="gr-avatar" style="background:{{ $avatarBg }}">{{ $initial }}</span>
                                        <div class="gr-user-info">
                                            <span class="gr-user-name">{{ $name }}</span>
                                            <span class="gr-user-role">{{ strtoupper((string)($req->user->role ?? '')) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="gr-prod">
                                        <span class="gr-prod-name">{{ $req->product->name ?? '-' }}</span>
                                        <span class="gr-prod-sku">{{ $req->product->sku ?? '' }}</span>
                                    </div>
                                </td>
                                <td class="tc">
                                    @php $u = $req->unit?->abbreviation ?? $req->unit?->name ?? $req->product?->unit?->abbreviation ?? ''; @endphp
                                    <span class="gr-qty">{{ number_format((float)$req->quantity, 0) }}</span>
                                    @if($u)<div class="gr-qty-unit">{{ $u }}</div>@endif
                                </td>
                                <td>
                                    @if($req->type === 'po')
                                        <span class="gr-tag gr-tag-po">PO</span>
                                    @else
                                        <span class="gr-tag gr-tag-transfer">Transfer</span>
                                        <div class="gr-route">{{ $req->fromWarehouse?->name ?? 'Utama' }} → {{ $req->toWarehouse?->name ?? 'Cabang' }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($req->notes)
                                        <span class="gr-note" title="{{ $req->notes }}">{{ \Illuminate\Support\Str::limit($req->notes, 30) }}</span>
                                    @else
                                        <span class="gr-date-sub">—</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $b = match($req->status) {
                                            'pending'   => ['gr-badge-pending','Menunggu'],
                                            'approved'  => ['gr-badge-approved','Disetujui'],
                                            'rejected'  => ['gr-badge-rejected','Ditolak'],
                                            'completed' => ['gr-badge-completed','Selesai'],
                                            default     => ['gr-badge-completed',strtoupper($req->status)],
                                        };
                                    @endphp
                                    <span class="gr-badge {{ $b[0] }}">{{ $b[1] }}</span>
                                </td>
                                <td class="tc">
                                    @if($req->user_id === auth()->id() || $role === 'supervisor')
                                        <form method="POST" action="{{ route('gudang.request.destroy', $req->id) }}" onsubmit="return confirm('Hapus permintaan ini?');" style="display:inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="gr-del" title="Hapus">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="gr-empty">
                                        <div class="gr-empty-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        </div>
                                        <h6 class="gr-empty-title">Belum ada permintaan</h6>
                                        <p class="gr-empty-sub">Buat permintaan baru untuk memulai.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                    <div class="gr-pagination">
                        {{ $requests->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-app-layout>
