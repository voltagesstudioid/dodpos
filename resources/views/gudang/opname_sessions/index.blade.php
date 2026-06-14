<x-app-layout>
    <x-slot name="header">Sesi Opname Stok</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Audit Gudang</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-orange">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                        Sesi Opname Stok
                    </h1>
                    <p class="tr-subtitle">Input stok fisik oleh Admin Gudang, lalu Supervisor melakukan approval sebelum stok berubah.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.opname_sessions.create') }}" class="tr-btn tr-btn-primary" style="background: var(--tr-warning-text); border-color: var(--tr-warning-text);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Buat Sesi Opname
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

            {{-- STATS CARDS --}}
            <div class="tr-stats-grid-5" style="margin-bottom: 1.5rem;">
                <div class="tr-stat-card border-indigo">
                    <div class="tr-stat-icon bg-indigo">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($totalSessions ?? 0) }}</div>
                        <div class="tr-stat-label">Total Sesi</div>
                    </div>
                </div>
                <div class="tr-stat-card" style="border-left: 4px solid var(--tr-text-muted);">
                    <div class="tr-stat-icon" style="background: var(--tr-text-muted);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($draftCount ?? 0) }}</div>
                        <div class="tr-stat-label">Draft</div>
                    </div>
                </div>
                <div class="tr-stat-card border-purple">
                    <div class="tr-stat-icon bg-purple">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($submittedCount ?? 0) }}</div>
                        <div class="tr-stat-label">Submitted</div>
                    </div>
                </div>
                <div class="tr-stat-card border-green">
                    <div class="tr-stat-icon bg-green">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($approvedCount ?? 0) }}</div>
                        <div class="tr-stat-label">Approved</div>
                    </div>
                </div>
                <div class="tr-stat-card" style="border-left: 4px solid var(--tr-danger-text);">
                    <div class="tr-stat-icon" style="background: var(--tr-danger-text);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($rejectedCount ?? 0) }}</div>
                        <div class="tr-stat-label">Rejected</div>
                    </div>
                </div>
            </div>

            {{-- MAIN CARD --}}
            <div class="tr-card">
                
                {{-- Filter Bar --}}
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('gudang.opname_sessions.index') }}" class="tr-filter-form" id="filterForm" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                        <div class="tr-search">
                            <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari no referensi atau nama pembuat...">
                        </div>
                        
                        <select name="status" class="tr-select">
                            <option value="" {{ request('status') === null ? 'selected' : '' }}>Semua Status</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted (Menunggu)</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>

                        <button type="submit" class="tr-btn tr-btn-primary" id="filterBtn">Filter Data</button>
                        
                        @if(request()->filled('q') || request()->filled('status'))
                            <a href="{{ route('gudang.opname_sessions.index') }}" class="tr-btn tr-btn-outline" style="color:var(--tr-danger-text); border-color:var(--tr-danger-border);">Reset</a>
                        @endif
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
                                <th>Status Sesi</th>
                                <th class="c" style="width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sessions as $session)
                                <tr>
                                    <td>
                                        <div class="tr-date-main">{{ $session->created_at->format('d M Y') }}</div>
                                        <div class="tr-date-sub">{{ $session->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td>
                                        <div class="tr-route-wh">{{ $session->warehouse?->name ?? 'Gudang Dihapus' }}</div>
                                        <div style="font-size:0.75rem; color:var(--tr-text-muted); margin-top:4px;">ID: {{ $session->warehouse_id }}</div>
                                    </td>
                                    <td>
                                        <div class="tr-user-name">{{ $session->creator?->name ?? 'Sistem' }}</div>
                                        <div class="tr-user-role">{{ strtoupper((string) ($session->creator?->role ?? '-')) }}</div>
                                    </td>
                                    <td>
                                        @if($session->reference_number)
                                            <span class="tr-font-mono">{{ $session->reference_number }}</span>
                                        @else
                                            <span style="color: var(--tr-text-light);">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($session->status === 'draft')
                                            <span class="tr-badge" style="background:var(--tr-bg); color:var(--tr-text-muted); border: 1px solid var(--tr-border);">DRAFT</span>
                                        @elseif($session->status === 'submitted')
                                            <span class="tr-badge" style="background:#eff6ff; color:#3b82f6;">SUBMITTED</span>
                                        @elseif($session->status === 'approved')
                                            <span class="tr-badge tr-badge-success">APPROVED</span>
                                        @elseif($session->status === 'rejected')
                                            <span class="tr-badge" style="background:var(--tr-danger-bg); color:var(--tr-danger-text);">REJECTED</span>
                                        @else
                                            <span class="tr-badge" style="background:var(--tr-bg); color:var(--tr-text-light);">CANCELLED</span>
                                        @endif
                                    </td>
                                    <td class="c">
                                        <div style="display: flex; gap: 4px; justify-content: center;">
                                            @if(in_array($session->status, ['draft', 'rejected']))
                                                <a href="{{ route('gudang.opname_sessions.edit', $session) }}" class="tr-btn tr-btn-outline" style="padding: 0.4rem 0.6rem; height: auto;" title="Edit / Lanjut">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                                    Edit
                                                </a>
                                            @else
                                                <a href="{{ route('gudang.opname_sessions.edit', $session) }}" class="tr-btn tr-btn-outline" style="padding: 0.4rem 0.6rem; height: auto;" title="Lihat">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                    Lihat
                                                </a>
                                            @endif
                                            
                                            <a href="{{ route('gudang.opname_sessions.print', $session) }}" target="_blank" class="tr-btn tr-btn-outline" style="padding: 0.4rem; height: auto;" title="Cetak Kertas Kerja">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                            </div>
                                            <h6>Tidak ada data sesi opname</h6>
                                            <p>Belum ada sesi opname yang dibuat atau sesuai dengan kriteria filter.</p>
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
            --tr-primary-hover: #2563eb;
            --tr-primary-light: #eff6ff;
            --tr-success-bg: #ecfdf5;
            --tr-success-text: #059669;
            --tr-success-border: #a7f3d0;
            --tr-warning-bg: #fef3c7;
            --tr-warning-text: #92400e;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #dc2626;
            --tr-danger-border: #fecaca;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1280px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-warning-text); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-orange { background: #fffbeb; color: #f59e0b; }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0.35rem 0 0 0; }
        
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* ── TABS ── */
        .tr-tabs { display: flex; gap: 2rem; border-bottom: 1px solid var(--tr-border); margin-bottom: 1.5rem; overflow-x: auto; white-space: nowrap; }
        .tr-tab-item { display: inline-flex; align-items: center; gap: 8px; padding-bottom: 0.75rem; color: var(--tr-text-muted); font-size: 0.85rem; font-weight: 600; text-decoration: none; border-bottom: 2px solid transparent; transition: all 0.2s; }
        .tr-tab-item:hover { color: var(--tr-text-main); }
        .tr-tab-item.active { color: var(--tr-primary); border-bottom-color: var(--tr-primary); }

        /* ── STATS GRID 5 ── */
        .tr-stats-grid-5 { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; }
        @media (max-width: 1200px) { .tr-stats-grid-5 { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { .tr-stats-grid-5 { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 576px) { .tr-stats-grid-5 { grid-template-columns: 1fr; } }
        .tr-stat-card { background: var(--tr-surface); border-radius: 12px; padding: 1.1rem; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--tr-border); }
        .tr-stat-icon { width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
        .tr-stat-value { font-size: 1.35rem; font-weight: 800; color: var(--tr-text-main); line-height: 1; }
        .tr-stat-label { font-size: 0.7rem; color: var(--tr-text-muted); margin-top: 0.35rem; font-weight: 500; }
        .bg-indigo { background: #4f46e5; }
        .bg-purple { background: #3b82f6; }
        .bg-green { background: #10b981; }
        .border-indigo { border-left: 4px solid #4f46e5; }
        .border-purple { border-left: 4px solid #3b82f6; }
        .border-green { border-left: 4px solid #10b981; }

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
        .tr-select { padding: 0.4rem 0.8rem; border-radius: 6px; border: 1px solid var(--tr-border); font-size: 0.8rem; background: white; font-family: inherit; color: var(--tr-text-main); height: 36px; }

        /* ── BUTTONS ── */
        .tr-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600;
            cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 36px;
        }
        .tr-btn-primary { background: var(--tr-text-main); color: #ffffff; border-color: var(--tr-text-main); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tr-btn-primary:hover { filter: brightness(0.9); transform: translateY(-1px); }
        .tr-btn-primary:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); }
        .tr-btn-danger-outline { border-color: var(--tr-danger-border); color: var(--tr-danger-text); background: transparent; }
        .tr-btn-danger-outline:hover { background: var(--tr-danger-bg); }

        /* Spinner for Buttons */
        .tr-spinner { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: tr-spin 0.8s linear infinite; }
        @keyframes tr-spin { to { transform: rotate(360deg); } }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.c, .tr-table td.c { text-align: center; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* ── CELL FORMATTING ── */
        .tr-date-main { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; white-space: nowrap; }
        .tr-date-sub { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }
        
        .tr-user-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-user-role { font-size: 0.7rem; color: var(--tr-text-muted); font-weight: 600; letter-spacing: 0.05em; margin-top: 2px; }
        
        .tr-font-mono { font-family: monospace; background: #f1f5f9; padding: 4px 8px; border-radius: 4px; color: var(--tr-text-main); font-size:0.8rem; font-weight:700;}
        
        .tr-route-wh { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; }
        
        .tr-badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.05em; }
        .tr-badge-success { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-status-sub { font-size: 0.7rem; color: var(--tr-text-muted); font-weight: 500; margin-top: 4px; }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: #fffbeb; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: #f59e0b; }
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animasi Loading saat Filter
            const filterForm = document.getElementById('filterForm');
            const filterBtn = document.getElementById('filterBtn');
            if (filterForm && filterBtn) {
                filterForm.addEventListener('submit', function () {
                    filterBtn.disabled = true;
                    filterBtn.innerHTML = '<span class="tr-spinner"></span> Memuat...';
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
