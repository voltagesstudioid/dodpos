<x-app-layout>
    <x-slot name="header">Sesi Opname Stok</x-slot>
    @push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --os-primary: #4f46e5; --os-primary-bg: #eef2ff; --os-primary-hover: #4338ca;
            --os-success: #10b981; --os-success-bg: #ecfdf5; --os-success-text: #166534; --os-success-border: #a7f3d0;
            --os-danger: #ef4444; --os-danger-bg: #fef2f2; --os-danger-text: #991b1b; --os-danger-border: #fecaca;
            --os-warning: #f59e0b; --os-warning-bg: #fffbeb; --os-warning-text: #b45309; --os-warning-border: #fde68a; --os-warning-hover: #d97706;
            --os-info: #0ea5e9; --os-info-bg: #e0f2fe; --os-info-text: #0369a1;
            --os-gray-bg: #f1f5f9; --os-gray-text: #475569;
            --os-bg: #f8fafc; --os-surface: #ffffff; --os-border: #e2e8f0; --os-border-light: #f1f5f9;
            --os-text: #0f172a; --os-text-secondary: #64748b; --os-text-muted: #94a3b8;
            --os-radius: 10px; --os-radius-lg: 14px;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        .os-wrap { max-width: 1280px; margin: 0 auto; padding: 1.5rem 1rem; }

        /* Header */
        .os-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .os-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--os-warning); margin-bottom: 0.25rem; }
        .os-title { font-size: 1.5rem; font-weight: 800; color: var(--os-text); margin: 0; display: flex; align-items: center; gap: 0.625rem; }
        .os-title-icon { width: 36px; height: 36px; background: var(--os-warning-bg); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--os-warning); }
        .os-subtitle { font-size: 0.85rem; color: var(--os-text-secondary); margin: 0.3rem 0 0; line-height: 1.4; max-width: 560px; }
        .os-btn-create { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1.125rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; background: var(--os-warning); color: #fff; text-decoration: none; box-shadow: 0 2px 6px rgba(245,158,11,0.2); transition: all 0.2s; border: none; cursor: pointer; font-family: inherit; }
        .os-btn-create:hover { background: var(--os-warning-hover); transform: translateY(-1px); box-shadow: 0 4px 10px rgba(245,158,11,0.3); }

        /* Alerts */
        .os-alert { display: flex; align-items: center; gap: 0.625rem; padding: 0.875rem 1.125rem; border-radius: var(--os-radius); margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 500; border: 1px solid; }
        .os-alert-success { background: var(--os-success-bg); color: var(--os-success-text); border-color: var(--os-success-border); }
        .os-alert-danger { background: var(--os-danger-bg); color: var(--os-danger-text); border-color: var(--os-danger-border); }

        /* Stats */
        .os-stats { display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.875rem; margin-bottom: 1.5rem; }
        .os-stat { background: var(--os-surface); border: 1px solid var(--os-border); border-radius: 12px; padding: 1rem 1.125rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 1px 2px rgba(0,0,0,0.04); border-left: 4px solid; transition: transform 0.15s; }
        .os-stat:hover { transform: translateY(-1px); }
        .os-stat.total { border-left-color: var(--os-primary); }
        .os-stat.draft { border-left-color: var(--os-text-muted); }
        .os-stat.submitted { border-left-color: var(--os-info); }
        .os-stat.approved { border-left-color: var(--os-success); }
        .os-stat.rejected { border-left-color: var(--os-danger); }
        .os-stat-ico { width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .os-stat-ico.total { background: var(--os-primary-bg); color: var(--os-primary); }
        .os-stat-ico.draft { background: var(--os-gray-bg); color: var(--os-gray-text); }
        .os-stat-ico.submitted { background: var(--os-info-bg); color: var(--os-info); }
        .os-stat-ico.approved { background: var(--os-success-bg); color: var(--os-success); }
        .os-stat-ico.rejected { background: var(--os-danger-bg); color: var(--os-danger); }
        .os-stat-val { font-size: 1.25rem; font-weight: 800; color: var(--os-text); line-height: 1.1; }
        .os-stat-lbl { font-size: 0.7rem; color: var(--os-text-secondary); font-weight: 500; margin-top: 2px; }

        /* Card */
        .os-card { background: var(--os-surface); border: 1px solid var(--os-border); border-radius: var(--os-radius-lg); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .os-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid var(--os-border-light); }

        /* Filters */
        .os-filters { display: grid; grid-template-columns: 1fr 180px auto; gap: 0.75rem; align-items: flex-end; }
        .os-label { font-size: 0.75rem; font-weight: 600; color: var(--os-text); display: block; margin-bottom: 0.25rem; }
        .os-search { display: flex; align-items: center; gap: 0.5rem; background: var(--os-bg); border: 1px solid var(--os-border); border-radius: 8px; padding: 0 0.75rem; transition: border-color 0.2s; }
        .os-search:focus-within { border-color: var(--os-warning); background: #fff; }
        .os-search svg { color: var(--os-text-muted); flex-shrink: 0; }
        .os-search input { border: none; background: transparent; padding: 0.5rem 0; font-size: 0.85rem; font-family: inherit; color: var(--os-text); outline: none; width: 100%; }
        .os-search input::placeholder { color: var(--os-text-muted); }
        .os-select { width: 100%; padding: 0.5rem 2rem 0.5rem 0.75rem; border: 1px solid var(--os-border); border-radius: 8px; font-size: 0.85rem; font-family: inherit; color: var(--os-text); background: var(--os-bg); appearance: none; outline: none; cursor: pointer; transition: border-color 0.2s; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; background-size: 14px; }
        .os-select:focus { border-color: var(--os-warning); background-color: #fff; }
        .os-filter-btns { display: flex; gap: 0.375rem; }
        .os-btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.35rem; padding: 0.5rem 0.875rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600; font-family: inherit; cursor: pointer; border: 1px solid transparent; text-decoration: none; transition: all 0.2s; height: 38px; white-space: nowrap; }
        .os-btn-dark { background: var(--os-text); color: #fff; }
        .os-btn-dark:hover { background: #000; transform: translateY(-1px); }
        .os-btn-ghost { border-color: var(--os-danger-border); color: var(--os-danger-text); background: transparent; }
        .os-btn-ghost:hover { background: var(--os-danger-bg); }

        /* Filter badges */
        .os-active-filters { display: flex; gap: 0.375rem; flex-wrap: wrap; padding: 0.625rem 1.25rem 0; }
        .os-filter-badge { display: inline-flex; align-items: center; gap: 0.25rem; background: var(--os-warning-bg); color: var(--os-warning-text); font-size: 0.6875rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 6px; border: 1px solid var(--os-warning-border); }
        .os-filter-badge a { color: inherit; text-decoration: none; font-weight: 800; margin-left: 2px; }
        .os-filter-badge a:hover { color: var(--os-danger); }

        /* Table */
        .os-twrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .os-tbl { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 850px; }
        .os-tbl thead th { font-size: 0.6875rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--os-text-secondary); padding: 0.75rem 1.125rem; border-bottom: 1px solid var(--os-border); background: var(--os-bg); white-space: nowrap; text-align: left; user-select: none; }
        .os-tbl thead th.sortable { cursor: pointer; transition: color 0.2s; }
        .os-tbl thead th.sortable:hover { color: var(--os-primary); }
        .os-tbl thead th .sort-arr { display: inline-block; margin-left: 3px; font-size: 0.6rem; opacity: 0.35; }
        .os-tbl thead th.active .sort-arr { opacity: 1; color: var(--os-primary); }
        .os-tbl thead th.r, .os-tbl tbody td.r { text-align: right; }
        .os-tbl tbody tr { transition: background 0.15s; }
        .os-tbl tbody tr:hover { background: #fafbfc; }
        .os-tbl tbody td { padding: 0.875rem 1.125rem; font-size: 0.8125rem; vertical-align: middle; border-bottom: 1px solid var(--os-border-light); }
        .os-tbl tbody tr:last-child td { border-bottom: none; }

        /* Cells */
        .os-date { font-weight: 600; color: var(--os-text); font-size: 0.8125rem; }
        .os-date-sub { font-size: 0.72rem; color: var(--os-text-muted); margin-top: 2px; }
        .os-wh { font-weight: 700; color: var(--os-text); font-size: 0.8125rem; }
        .os-wh-id { font-size: 0.6875rem; color: var(--os-text-muted); font-family: monospace; margin-top: 2px; }
        .os-user { font-weight: 600; color: var(--os-text); font-size: 0.8125rem; }
        .os-user-role { font-size: 0.6875rem; color: var(--os-text-muted); margin-top: 2px; letter-spacing: 0.02em; }
        .os-ref { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 6px; background: var(--os-warning-bg); color: var(--os-warning-text); border: 1px solid var(--os-warning-border); font-family: monospace; font-size: 0.75rem; font-weight: 700; }
        .os-ref-empty { color: var(--os-text-muted); }

        /* Badges */
        .os-badge { display: inline-flex; align-items: center; padding: 0.2rem 0.5rem; border-radius: 999px; font-size: 0.6875rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; }
        .os-badge-draft { background: var(--os-gray-bg); color: var(--os-gray-text); }
        .os-badge-submitted { background: var(--os-info-bg); color: var(--os-info-text); }
        .os-badge-approved { background: var(--os-success-bg); color: var(--os-success-text); }
        .os-badge-rejected { background: var(--os-danger-bg); color: var(--os-danger-text); }
        .os-badge-cancelled { background: #e2e8f0; color: #475569; }
        .os-badge-sub { font-size: 0.6875rem; color: var(--os-warning-text); font-weight: 500; font-style: italic; margin-top: 4px; }

        /* Action buttons */
        .os-actions { display: flex; gap: 0.375rem; justify-content: flex-end; }
        .os-act-btn { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; }
        .os-act-btn.open { background: var(--os-bg); border-color: var(--os-border); color: var(--os-text-secondary); }
        .os-act-btn.open:hover { background: var(--os-surface); border-color: var(--os-text); color: var(--os-text); }
        .os-act-btn.review { background: var(--os-info); color: #fff; }
        .os-act-btn.review:hover { background: #0284c7; }
        .os-act-btn.approved { background: var(--os-success-bg); border-color: var(--os-success-border); color: var(--os-success-text); }
        .os-act-btn.rejected { background: var(--os-danger-bg); border-color: var(--os-danger-border); color: var(--os-danger-text); }

        /* Empty */
        .os-empty { text-align: center; padding: 3.5rem 1.5rem; }
        .os-empty-ico { width: 52px; height: 52px; border-radius: 50%; background: var(--os-warning-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--os-warning); }
        .os-empty h6 { font-size: 0.95rem; font-weight: 700; color: var(--os-text); margin: 0 0 0.25rem; }
        .os-empty p { font-size: 0.8125rem; color: var(--os-text-secondary); margin: 0 auto; max-width: 380px; line-height: 1.5; }

        /* Pagination */
        .os-pag { padding: 0.875rem 1.25rem; border-top: 1px solid var(--os-border-light); background: var(--os-surface); display: flex; align-items: center; justify-content: space-between; }
        .os-pag-info { font-size: 0.75rem; color: var(--os-text-muted); }

        /* Responsive */
        @media (max-width: 1024px) { .os-stats { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) {
            .os-stats { grid-template-columns: repeat(2, 1fr); }
            .os-header { flex-direction: column; align-items: flex-start; }
            .os-btn-create { width: 100%; justify-content: center; }
            .os-filters { grid-template-columns: 1fr; }
            .os-filter-btns { display: grid; grid-template-columns: 1fr 1fr; }
            .os-pag { flex-direction: column; gap: 0.5rem; text-align: center; }
        }
        @media (max-width: 480px) { .os-stats { grid-template-columns: 1fr; } }
    </style>
    @endpush

    <div class="os-wrap">
        {{-- Header --}}
        <div class="os-header">
            <div>
                <div class="os-eyebrow">Audit Gudang</div>
                <h1 class="os-title">
                    <span class="os-title-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </span>
                    Sesi Opname Stok
                </h1>
                <p class="os-subtitle">Input stok fisik oleh Admin Gudang, lalu Supervisor melakukan approval sebelum stok berubah.</p>
            </div>
            @can('create_opname_stok')
            <a href="{{ route('gudang.opname_sessions.create') }}" class="os-btn-create">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Buat Sesi Opname
            </a>
            @endcan
        </div>

        {{-- Alerts --}}
        @if(session('success'))
        <div class="os-alert os-alert-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="os-alert os-alert-danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Stats --}}
        <div class="os-stats">
            <div class="os-stat total">
                <div class="os-stat-ico total"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg></div>
                <div><div class="os-stat-val">{{ $totalSessions }}</div><div class="os-stat-lbl">Total Sesi</div></div>
            </div>
            <div class="os-stat draft">
                <div class="os-stat-ico draft"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
                <div><div class="os-stat-val">{{ $draftCount }}</div><div class="os-stat-lbl">Draft</div></div>
            </div>
            <div class="os-stat submitted">
                <div class="os-stat-ico submitted"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg></div>
                <div><div class="os-stat-val">{{ $submittedCount }}</div><div class="os-stat-lbl">Submitted</div></div>
            </div>
            <div class="os-stat approved">
                <div class="os-stat-ico approved"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                <div><div class="os-stat-val">{{ $approvedCount }}</div><div class="os-stat-lbl">Approved</div></div>
            </div>
            <div class="os-stat rejected">
                <div class="os-stat-ico rejected"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                <div><div class="os-stat-val">{{ $rejectedCount }}</div><div class="os-stat-lbl">Rejected</div></div>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="os-card">
            <div class="os-card-head">
                <form method="GET" action="{{ route('gudang.opname_sessions.index') }}" class="os-filters" id="os-filter-form">
                    <div>
                        <label class="os-label">Pencarian</label>
                        <div class="os-search">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari no. referensi / pembuat..." id="os-search-input">
                        </div>
                    </div>
                    <div>
                        <label class="os-label">Status Sesi</label>
                        @php $st = request('status'); @endphp
                        <select name="status" class="os-select" onchange="this.form.submit()">
                            <option value="" {{ $st === null || $st === '' ? 'selected' : '' }}>Semua Status</option>
                            <option value="draft" {{ $st === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ $st === 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="approved" {{ $st === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $st === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="cancelled" {{ $st === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="os-filter-btns">
                        <button type="submit" class="os-btn os-btn-dark">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            Filter
                        </button>
                        @if(request()->filled('q') || request()->filled('status'))
                        <a href="{{ route('gudang.opname_sessions.index') }}" class="os-btn os-btn-ghost">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Active filter badges --}}
            @if(request()->filled('q') || request()->filled('status'))
            <div class="os-active-filters">
                @if(request()->filled('q'))
                <span class="os-filter-badge">
                    Search: "{{ request('q') }}"
                    <a href="{{ route('gudang.opname_sessions.index', request()->except(['q'])) }}" title="Hapus">&times;</a>
                </span>
                @endif
                @if(request()->filled('status'))
                <span class="os-filter-badge">
                    Status: {{ ucfirst(request('status')) }}
                    <a href="{{ route('gudang.opname_sessions.index', request()->except(['status'])) }}" title="Hapus">&times;</a>
                </span>
                @endif
            </div>
            @endif

            {{-- Table --}}
            <div class="os-twrap">
                @php
                    $sortUrl = function($col) use ($sort, $dir) {
                        $newDir = ($sort === $col && $dir === 'desc') ? 'asc' : 'desc';
                        return route('gudang.opname_sessions.index', array_merge(request()->except(['sort','dir','page']), ['sort' => $col, 'dir' => $newDir]));
                    };
                    $thClass = function($col) use ($sort) {
                        return 'sortable' . ($sort === $col ? ' active' : '');
                    };
                    $arrow = function($col) use ($sort, $dir) {
                        if ($sort !== $col) return '<span class="sort-arr">⇅</span>';
                        return '<span class="sort-arr">' . ($dir === 'asc' ? '↑' : '↓') . '</span>';
                    };
                @endphp
                <table class="os-tbl">
                    <thead>
                        <tr>
                            <th class="{{ $thClass('created_at') }}" onclick="location.href='{{ $sortUrl('created_at') }}'">Waktu Dibuat {!! $arrow('created_at') !!}</th>
                            <th class="{{ $thClass('warehouse_id') }}" onclick="location.href='{{ $sortUrl('warehouse_id') }}'">Gudang / Area {!! $arrow('warehouse_id') !!}</th>
                            <th>Pembuat</th>
                            <th class="{{ $thClass('reference_number') }}" onclick="location.href='{{ $sortUrl('reference_number') }}'">No. Referensi {!! $arrow('reference_number') !!}</th>
                            <th class="{{ $thClass('status') }}" onclick="location.href='{{ $sortUrl('status') }}'">Status {!! $arrow('status') !!}</th>
                            <th class="r" style="width:150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $s)
                        @php
                            $badgeClass = match($s->status) {
                                'draft' => 'os-badge-draft',
                                'submitted' => 'os-badge-submitted',
                                'approved' => 'os-badge-approved',
                                'rejected' => 'os-badge-rejected',
                                'cancelled' => 'os-badge-cancelled',
                                default => 'os-badge-draft',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="os-date">{{ $s->created_at->format('d M Y') }}</div>
                                <div class="os-date-sub">{{ $s->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="os-wh">{{ $s->warehouse?->name ?? '-' }}</div>
                                <div class="os-wh-id">ID: {{ $s->warehouse_id }}</div>
                            </td>
                            <td>
                                <div class="os-user">{{ $s->creator?->name ?? '-' }}</div>
                                <div class="os-user-role">{{ strtoupper((string) ($s->creator?->role ?? '-')) }}</div>
                            </td>
                            <td>
                                @if($s->reference_number)
                                <span class="os-ref">{{ $s->reference_number }}</span>
                                @else
                                <span class="os-ref-empty">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="os-badge {{ $badgeClass }}">{{ strtoupper($s->status) }}</span>
                                @if($s->status === 'submitted' && $role === 'supervisor')
                                <div class="os-badge-sub">Menunggu approval</div>
                                @endif
                                @if($s->status === 'approved' && $s->approver)
                                <div class="os-badge-sub" style="font-style:normal;">oleh {{ $s->approver->name }}</div>
                                @endif
                                @if($s->reversed_at)
                                <div class="os-badge-sub" style="color:var(--os-danger-text);font-style:normal;">⟲ Reversed</div>
                                @endif
                                @if($s->deadline_at && in_array($s->status, ['draft','rejected']))
                                    @if($s->deadline_at->isPast())
                                    <div class="os-badge-sub" style="color:var(--os-danger);font-weight:700;font-style:normal;">⚠ Deadline lewat!</div>
                                    @elseif($s->deadline_at->diffInDays(now()) <= 3)
                                    <div class="os-badge-sub" style="color:var(--os-warning);font-style:normal;">⏰ {{ $s->deadline_at->diffInDays(now()) }} hari lagi</div>
                                    @endif
                                @endif
                            </td>
                            <td class="r">
                                <div class="os-actions">
                                    @if(in_array($s->status, ['draft', 'rejected']))
                                    <a href="{{ route('gudang.opname_sessions.edit', $s) }}" class="os-act-btn open">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        {{ $s->status === 'rejected' ? 'Revisi' : 'Edit' }}
                                    </a>
                                    <a href="{{ route('gudang.opname_sessions.print', $s) }}" class="os-act-btn open" target="_blank" title="Cetak">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                    </a>
                                    @elseif($s->status === 'submitted' && $role === 'supervisor')
                                    <a href="{{ route('gudang.opname_approval.show', $s) }}" class="os-act-btn review">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Review
                                    </a>
                                    @else
                                    <a href="{{ route('gudang.opname_sessions.edit', $s) }}" class="os-act-btn {{ $s->status }}">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('gudang.opname_sessions.print', $s) }}" class="os-act-btn open" target="_blank" title="Cetak">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="os-empty">
                                    <div class="os-empty-ico">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
                                    </div>
                                    <h6>Belum ada sesi opname</h6>
                                    <p>Laporan penyesuaian / perhitungan stok fisik gudang akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sessions->hasPages())
            <div class="os-pag">
                <div class="os-pag-info">
                    Menampilkan {{ $sessions->firstItem() ?? 0 }}-{{ $sessions->lastItem() ?? 0 }} dari {{ $sessions->total() }} sesi
                </div>
                <div>{{ $sessions->withQueryString()->links() }}</div>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
    // Debounced auto-search
    (function() {
        var timer;
        var input = document.getElementById('os-search-input');
        if (!input) return;
        input.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                document.getElementById('os-filter-form').submit();
            }, 400);
        });
    })();
    </script>
    @endpush
</x-app-layout>
