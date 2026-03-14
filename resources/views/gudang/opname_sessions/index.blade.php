<x-app-layout>
    <x-slot name="header">Sesi Opname Stok</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Audit Gudang</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-warning">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14h6"></path><path d="M9 18h6"></path><path d="M9 10h6"></path></svg>
                        </div>
                        Sesi Opname Stok
                    </h1>
                    <p class="tr-subtitle">Input stok fisik oleh Admin Gudang, lalu Supervisor melakukan approval sebelum stok berubah.</p>
                </div>
                
                @can('create_opname_stok')
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.opname_sessions.create') }}" class="tr-btn tr-btn-warning">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Buat Sesi Opname
                    </a>
                </div>
                @endcan
            </div>

            {{-- ALERTS --}}
            @if(session('success'))
                <div class="tr-alert tr-alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error')) 
                <div class="tr-alert tr-alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    {{ session('error') }}
                </div> 
            @endif

            {{-- MAIN CARD --}}
            <div class="tr-card">
                
                {{-- Filter Bar --}}
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('gudang.opname_sessions.index') }}" class="tr-filter-grid">
                        <div class="tr-form-group">
                            <label class="tr-label">Pencarian</label>
                            <div class="tr-search">
                                <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari ref / pembuat...">
                            </div>
                        </div>
                        
                        <div class="tr-form-group">
                            <label class="tr-label">Status Sesi</label>
                            @php $st = request('status'); @endphp
                            <div class="tr-select-wrapper">
                                <select name="status" class="tr-select">
                                    <option value="" {{ $st === null || $st === '' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="draft" {{ $st === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="submitted" {{ $st === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="approved" {{ $st === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $st === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="cancelled" {{ $st === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="tr-filter-actions">
                            <button type="submit" class="tr-btn tr-btn-dark">Filter Data</button>
                            @if(request()->filled('q') || request()->filled('status'))
                                <a href="{{ route('gudang.opname_sessions.index') }}" class="tr-btn tr-btn-danger-outline">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Waktu Dibuat</th>
                                <th>Gudang / Area</th>
                                <th>Pembuat</th>
                                <th>No. Referensi</th>
                                <th>Status</th>
                                <th class="r" style="width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sessions as $s)
                                @php
                                    // Mapping Status to CSS Classes
                                    $badgeClass = match($s->status) {
                                        'draft' => 'tr-badge-gray',
                                        'submitted' => 'tr-badge-info',
                                        'approved' => 'tr-badge-success',
                                        'rejected' => 'tr-badge-danger',
                                        'cancelled' => 'tr-badge-dark',
                                        default => 'tr-badge-gray',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <div class="tr-date-main">{{ $s->created_at->format('d M Y') }}</div>
                                        <div class="tr-date-sub">{{ $s->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td>
                                        <div class="tr-wh-name">{{ $s->warehouse?->name ?? '-' }}</div>
                                        <div class="tr-wh-id">ID: {{ $s->warehouse_id }}</div>
                                    </td>
                                    <td>
                                        <div class="tr-user-name">{{ $s->creator?->name ?? '-' }}</div>
                                        <div class="tr-user-role">{{ strtoupper((string) ($s->creator?->role ?? '-')) }}</div>
                                    </td>
                                    <td>
                                        <span class="tr-ref-badge">{{ $s->reference_number ?: '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="tr-badge {{ $badgeClass }}">{{ strtoupper($s->status) }}</span>
                                        @if($s->status === 'submitted' && $role === 'supervisor')
                                            <div class="tr-status-sub">Menunggu approval</div>
                                        @endif
                                    </td>
                                    <td class="r">
                                        <div class="tr-actions-group">
                                            <a href="{{ route('gudang.opname_sessions.edit', $s) }}" class="tr-btn-sm tr-btn-outline">
                                                Buka
                                            </a>
                                            @if($role === 'supervisor' && $s->status === 'submitted')
                                                <a href="{{ route('gudang.opname_approval.show', $s) }}" class="tr-btn-sm tr-btn-info">
                                                    Review
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                                            </div>
                                            <h6>Belum ada sesi opname</h6>
                                            <p>Laporan penyesuaian/perhitungan stok fisik gudang akan muncul di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($sessions->hasPages())
                    <div class="tr-pagination">
                        {{ $sessions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            --tr-primary: #3b82f6;
            --tr-info: #0ea5e9;
            --tr-info-bg: #e0f2fe;
            --tr-success: #10b981;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            --tr-success-border: #a7f3d0;
            --tr-danger: #ef4444;
            --tr-danger-bg: #fee2e2;
            --tr-danger-text: #991b1b;
            --tr-danger-border: #fecaca;
            --tr-warning: #f59e0b;
            --tr-warning-hover: #d97706;
            --tr-warning-bg: #fffbeb;
            --tr-warning-text: #b45309;
            --tr-gray-bg: #f1f5f9;
            --tr-gray-text: #334155;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1280px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-warning); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-warning { background: var(--tr-warning-bg); color: var(--tr-warning); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0.35rem 0 0 0; line-height: 1.4; max-width: 600px; }
        
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: center; gap: 10px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 500; border: 1px solid transparent; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: var(--tr-success-border); }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: var(--tr-danger-border); }

        /* ── CARD & FILTER ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-filter-bar { padding: 1rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        
        .tr-filter-grid { display: grid; grid-template-columns: 1fr 200px auto; gap: 1rem; align-items: flex-end; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-label { font-size: 0.8rem; font-weight: 600; color: var(--tr-text-main); }
        
        .tr-search { display: flex; align-items: center; gap: 8px; background: var(--tr-bg); border-radius: 6px; padding: 0.5rem 0.85rem; border: 1px solid var(--tr-border); transition: border-color 0.2s; }
        .tr-search:focus-within { border-color: var(--tr-warning); background: #ffffff; }
        .tr-search-icon { color: var(--tr-text-light); }
        .tr-search input { border: none; background: transparent; font-size: 0.85rem; font-family: inherit; color: var(--tr-text-main); outline: none; width: 100%; }
        .tr-search input::placeholder { color: var(--tr-text-light); }

        .tr-select-wrapper { position: relative; }
        .tr-select { width: 100%; padding: 0.5rem 0.85rem; padding-right: 2rem; border: 1px solid var(--tr-border); border-radius: 6px; font-family: inherit; font-size: 0.85rem; color: var(--tr-text-main); background: var(--tr-bg); appearance: none; outline: none; transition: border-color 0.2s; cursor: pointer; }
        .tr-select:focus { border-color: var(--tr-warning); background: #ffffff; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }

        .tr-filter-actions { display: flex; gap: 6px; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 38px; }
        .tr-btn-warning { background: var(--tr-warning); color: #ffffff; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2); }
        .tr-btn-warning:hover { background: var(--tr-warning-hover); transform: translateY(-1px); box-shadow: 0 4px 6px rgba(245, 158, 11, 0.3); }
        .tr-btn-dark { background: var(--tr-text-main); color: #ffffff; border-color: var(--tr-text-main); }
        .tr-btn-dark:hover { background: #000000; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tr-btn-danger-outline { border-color: var(--tr-danger-border); color: var(--tr-danger-text); background: transparent; }
        .tr-btn-danger-outline:hover { background: var(--tr-danger-bg); }

        /* Small Buttons (Table Actions) */
        .tr-btn-sm { display: inline-flex; align-items: center; justify-content: center; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; }
        .tr-btn-outline { background: var(--tr-bg); border-color: var(--tr-border); color: var(--tr-text-muted); }
        .tr-btn-outline:hover { background: var(--tr-surface); border-color: var(--tr-text-main); color: var(--tr-text-main); }
        .tr-btn-info { background: var(--tr-info); color: #ffffff; }
        .tr-btn-info:hover { background: #0284c7; }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 900px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* ── CELL FORMATTING ── */
        .tr-date-main { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; white-space: nowrap; }
        .tr-date-sub { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }
        
        .tr-wh-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-wh-id { font-size: 0.7rem; color: var(--tr-text-light); font-family: monospace; margin-top: 2px; }
        
        .tr-user-name { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-user-role { font-size: 0.7rem; color: var(--tr-text-muted); letter-spacing: 0.02em; margin-top: 2px; }
        
        .tr-ref-badge { display: inline-block; padding: 0.25rem 0.6rem; border-radius: 6px; background: var(--tr-warning-bg); color: var(--tr-warning-text); border: 1px solid var(--tr-warning-border); font-family: monospace; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.02em; }

        /* Status Badges */
        .tr-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-badge-gray { background: var(--tr-gray-bg); color: var(--tr-gray-text); }
        .tr-badge-info { background: var(--tr-info-bg); color: #0284c7; }
        .tr-badge-success { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-badge-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); }
        .tr-badge-dark { background: #e2e8f0; color: #475569; }
        .tr-status-sub { font-size: 0.7rem; color: var(--tr-warning-text); font-weight: 500; margin-top: 6px; font-style: italic; }

        .tr-actions-group { display: flex; gap: 6px; justify-content: flex-end; }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-warning-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-warning); }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto 1.25rem; max-width: 400px; line-height: 1.5; }

        /* ── PAGINATION ── */
        .tr-pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--tr-border-light); background: #ffffff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-header-actions { width: 100%; }
            .tr-btn { width: 100%; justify-content: center; }
            .tr-filter-grid { grid-template-columns: 1fr; gap: 1rem; align-items: stretch; }
            .tr-filter-actions { display: grid; grid-template-columns: 1fr 1fr; }
        }
    </style>
    @endpush
</x-app-layout>