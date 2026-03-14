<x-app-layout>
    <x-slot name="header">Approval Opname</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Tugas Supervisor</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-success">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        Approval Opname Stok
                    </h1>
                    <p class="tr-subtitle">Daftar sesi opname yang sudah disubmit oleh Admin Gudang dan menunggu persetujuan Anda.</p>
                </div>
                
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.opname_sessions.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        Lihat Semua Sesi
                    </a>
                </div>
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
                
                {{-- Search Bar --}}
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('gudang.opname_approval.index') }}" class="tr-filter-form">
                        <div class="tr-search">
                            <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari ref / gudang / pembuat...">
                        </div>
                        <button type="submit" class="tr-btn tr-btn-dark">Cari Data</button>
                        
                        @if(request('q'))
                            <a href="{{ route('gudang.opname_approval.index') }}" class="tr-btn tr-btn-danger-outline">Reset Filter</a>
                        @endif
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Waktu Submit</th>
                                <th>Gudang / Lokasi</th>
                                <th>Dibuat Oleh</th>
                                <th>No. Referensi</th>
                                <th class="r" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sessions as $s)
                                <tr>
                                    <td>
                                        <div class="tr-date-main">{{ optional($s->submitted_at)->format('d M Y') ?: $s->created_at->format('d M Y') }}</div>
                                        <div class="tr-date-sub">{{ optional($s->submitted_at)->format('H:i') ?: $s->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td>
                                        <div class="tr-wh-name">{{ $s->warehouse?->name ?? '-' }}</div>
                                        <div class="tr-wh-id">ID Gudang: {{ $s->warehouse_id }}</div>
                                    </td>
                                    <td>
                                        <div class="tr-user-name">{{ $s->creator?->name ?? '-' }}</div>
                                        <div class="tr-user-role">{{ strtoupper((string) ($s->creator?->role ?? '-')) }}</div>
                                    </td>
                                    <td>
                                        <span class="tr-ref-badge">{{ $s->reference_number ?: '-' }}</span>
                                    </td>
                                    <td class="r">
                                        <a href="{{ route('gudang.opname_approval.show', $s) }}" class="tr-btn-sm tr-btn-info">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                            </div>
                                            <h6>Tidak ada tugas approval</h6>
                                            <p>Saat ini tidak ada sesi opname yang menunggu untuk di-review.</p>
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
            --tr-info-hover: #0284c7;
            --tr-success: #10b981;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            --tr-success-border: #a7f3d0;
            --tr-danger: #ef4444;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #dc2626;
            --tr-danger-border: #fecaca;
            --tr-warning: #f59e0b;
            --tr-warning-bg: #fffbeb;
            --tr-warning-text: #b45309;
            --tr-warning-border: #fde68a;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1280px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-success-text); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-success { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0.35rem 0 0 0; line-height: 1.4; }
        
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: center; gap: 10px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 500; border: 1px solid transparent; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: var(--tr-success-border); }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: var(--tr-danger-border); }

        /* ── CARD & FILTER ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-filter-bar { padding: 1rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-filter-form { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .tr-search { display: flex; align-items: center; gap: 8px; background: var(--tr-bg); border-radius: 6px; padding: 0.5rem 1rem; border: 1px solid var(--tr-border); flex: 1; min-width: 280px; transition: border-color 0.2s; }
        .tr-search:focus-within { border-color: var(--tr-primary); background: #ffffff; }
        .tr-search-icon { color: var(--tr-text-light); }
        .tr-search input { border: none; background: transparent; font-size: 0.85rem; font-family: inherit; color: var(--tr-text-main); outline: none; width: 100%; }
        .tr-search input::placeholder { color: var(--tr-text-light); }

        /* ── BUTTONS ── */
        .tr-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600;
            cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent;
        }
        .tr-btn-dark { background: var(--tr-text-main); color: #ffffff; border-color: var(--tr-text-main); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tr-btn-dark:hover { background: #000000; transform: translateY(-1px); }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); background: #f8fafc; }
        .tr-btn-danger-outline { border-color: var(--tr-danger-border); color: var(--tr-danger-text); background: transparent; }
        .tr-btn-danger-outline:hover { background: var(--tr-danger-bg); }

        /* Small Buttons (Table Actions) */
        .tr-btn-sm { display: inline-flex; align-items: center; gap: 4px; justify-content: center; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .tr-btn-info { background: var(--tr-info); color: #ffffff; border-color: var(--tr-info); }
        .tr-btn-info:hover { background: var(--tr-info-hover); transform: translateY(-1px); box-shadow: 0 2px 4px rgba(14, 165, 233, 0.2); }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* ── CELL FORMATTING ── */
        .tr-date-main { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; white-space: nowrap; }
        .tr-date-sub { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }
        
        .tr-wh-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-wh-id { font-size: 0.7rem; color: var(--tr-text-light); font-family: monospace; margin-top: 2px; }
        
        .tr-user-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-user-role { font-size: 0.7rem; color: var(--tr-text-muted); letter-spacing: 0.02em; margin-top: 2px; }
        
        .tr-ref-badge { display: inline-block; padding: 0.25rem 0.6rem; border-radius: 6px; background: var(--tr-warning-bg); color: var(--tr-warning-text); border: 1px solid var(--tr-warning-border); font-family: monospace; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.02em; }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-success-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-success-text); }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* ── PAGINATION ── */
        .tr-pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--tr-border-light); background: #ffffff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-header-actions { width: 100%; }
            .tr-btn { width: 100%; justify-content: center; }
            .tr-filter-form { flex-direction: column; align-items: stretch; }
            .tr-search { width: 100%; min-width: auto; }
        }
    </style>
    @endpush
</x-app-layout>