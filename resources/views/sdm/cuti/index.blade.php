<x-hr-layout>
    <x-slot name="eyebrow">Manajemen Kehadiran</x-slot>
    <x-slot name="title">Pengajuan Cuti & Izin</x-slot>
    <x-slot name="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><rect x="18" y="8" width="5" height="5" rx="1"></rect><path d="M18 13v6"></path></svg>
    </x-slot>
    <x-slot name="iconBg">bg-teal</x-slot>
    <x-slot name="description">Kelola persetujuan cuti, izin, dan sakit karyawan secara terpusat.</x-slot>
    <x-slot name="actions">
        @can('create_absensi')
            <button type="button" class="hr-btn hr-btn-primary" onclick="openAddModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Ajukan Cuti Baru
            </button>
        @endcan
        <a href="{{ route('sdm.libur.index') }}" class="hr-btn hr-btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Kalender Libur
        </a>
        <a href="{{ route('sdm.absensi.index') }}" class="hr-btn hr-btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            Data Absensi
        </a>
    </x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap');

        .hc-wrap { font-family:'Plus Jakarta Sans',sans-serif; }

        /* ── STATS ROW ── */
        .hc-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
        .hc-stat {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem;
            display:flex; flex-direction:column; box-shadow:0 4px 6px -1px rgba(0,0,0,0.03);
            transition:transform 0.2s, box-shadow 0.2s; position:relative; overflow:hidden;
        }
        .hc-stat:hover { transform:translateY(-2px); box-shadow:0 10px 15px -3px rgba(0,0,0,0.05); }
        .hc-stat::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; border-radius:4px 0 0 4px; }
        .hc-stat.total::before { background:#3b82f6; }
        .hc-stat.pending::before { background:#f59e0b; }
        .hc-stat.approved::before { background:#10b981; }
        .hc-stat.rejected::before { background:#ef4444; }
        .hc-stat-label { font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem; }
        .hc-stat-value { font-size:2rem; font-weight:800; color:#0f172a; font-family:'JetBrains Mono',monospace; line-height:1; }
        .hc-stat-change { font-size:0.8125rem; font-weight:600; color:#94a3b8; margin-top:0.5rem; }

        /* ── FILTER CARD ── */
        .hc-filter-card { background:linear-gradient(to right, #f8fafc, #fff); border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.5rem; margin-bottom:1.5rem; display:flex; align-items:flex-end; gap:1.25rem; flex-wrap:wrap; box-shadow:0 2px 4px rgba(0,0,0,0.02); }
        .hc-filter-group { display:flex; flex-direction:column; gap:0.5rem; flex:1; min-width:200px; }
        .hc-filter-label { font-size:0.75rem; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.05em; }
        .hc-filter-inp {
            width:100%; padding:0.625rem 1rem; border:1px solid #cbd5e1; border-radius:10px;
            background:#fff; font-family:inherit; font-size:0.875rem; color:#0f172a; font-weight:600;
            transition:all 0.2s; outline:none; box-shadow:0 1px 2px rgba(0,0,0,0.02);
        }
        .hc-filter-inp:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.1); }
        .hc-filter-actions { display:flex; gap:0.75rem; margin-left:auto; }
        .hc-btn {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.875rem; font-weight:700;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit; display:inline-flex; align-items:center; justify-content:center; gap:0.5rem;
        }
        .hc-btn-primary { background:#4f46e5; color:#fff; box-shadow:0 4px 6px -1px rgba(79,70,229,0.2); }
        .hc-btn-primary:hover { background:#4338ca; box-shadow:0 6px 8px -2px rgba(79,70,229,0.3); transform:translateY(-1px); }
        .hc-btn-ghost { background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; }
        .hc-btn-ghost:hover { background:#e2e8f0; color:#0f172a; }

        /* ── TABLE ── */
        .hc-table-card { background:#fff; border:1px solid #e2e8f0; border-radius:20px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.03); }
        .hc-table-head { padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; background:#f8fafc; }
        .hc-table-title { font-size:1.125rem; font-weight:800; color:#0f172a; margin:0; }
        .hc-table-wrap { overflow-x:auto; }
        .hc-table { width:100%; border-collapse:collapse; min-width:800px; }
        .hc-table th { padding:1rem 1.5rem; text-align:left; font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #f1f5f9; background:#fff; white-space:nowrap; }
        .hc-table td { padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; vertical-align:middle; background:#fff; transition:background 0.2s; }
        .hc-table tbody tr:hover td { background:#f8fafc; }
        .hc-table tbody tr:last-child td { border-bottom:none; }

        .hc-user-info { display:flex; align-items:center; gap:1rem; }
        .hc-avatar { width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg, #e0e7ff, #c7d2fe); color:#4338ca; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1rem; flex-shrink:0; }
        .hc-user-details { display:flex; flex-direction:column; }
        .hc-user-name { font-weight:700; color:#0f172a; font-size:0.875rem; }
        .hc-user-email { font-weight:500; color:#64748b; font-size:0.75rem; }

        .hc-badge { display:inline-flex; align-items:center; padding:4px 10px; border-radius:8px; font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; }
        .hc-badge.type-cuti { background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; }
        .hc-badge.type-izin { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
        .hc-badge.type-sakit { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
        
        .hc-badge.status-pending { background:#fefce8; color:#ca8a04; border:1px solid #fef08a; }
        .hc-badge.status-approved { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .hc-badge.status-rejected { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }

        .hc-date-range { display:flex; flex-direction:column; gap:0.25rem; }
        .hc-date-val { font-size:0.875rem; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:0.5rem; }
        .hc-date-dur { font-size:0.75rem; font-weight:700; color:#6366f1; background:#e0e7ff; padding:2px 8px; border-radius:6px; display:inline-block; align-self:flex-start; }

        .hc-notes { font-size:0.8125rem; color:#475569; max-width:250px; line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
        
        .hc-paid-yes { color:#059669; font-weight:800; font-size:0.875rem; display:flex; align-items:center; gap:4px; justify-content:center; }
        .hc-paid-no { color:#ef4444; font-weight:800; font-size:0.875rem; display:flex; align-items:center; gap:4px; justify-content:center; }

        .hc-act-btns { display:flex; gap:0.5rem; justify-content:flex-end; }
        .hc-icon-btn {
            width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center;
            border:1px solid transparent; cursor:pointer; transition:all 0.2s; background:#f1f5f9;
        }
        .hc-icon-btn.approve { color:#059669; }
        .hc-icon-btn.approve:hover { background:#ecfdf5; border-color:#a7f3d0; transform:scale(1.05); }
        .hc-icon-btn.reject { color:#dc2626; }
        .hc-icon-btn.reject:hover { background:#fef2f2; border-color:#fecaca; transform:scale(1.05); }
        .hc-icon-btn.del { color:#64748b; }
        .hc-icon-btn.del:hover { background:#fef2f2; color:#dc2626; border-color:#fecaca; }

        .hc-empty { padding:4rem 2rem; text-align:center; display:flex; flex-direction:column; align-items:center; }
        .hc-empty-ico { width:64px; height:64px; border-radius:16px; background:#f1f5f9; color:#94a3b8; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; }
        .hc-empty-title { font-size:1.125rem; font-weight:800; color:#1e293b; margin-bottom:0.5rem; }
        .hc-empty-desc { font-size:0.875rem; color:#64748b; max-width:300px; }

        /* ── MODAL STYLES (Glassmorphism) ── */
        .hc-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,0.4); z-index:9999; align-items:center; justify-content:center; padding:1.5rem; backdrop-filter:blur(8px); opacity:0; transition:opacity 0.3s ease; }
        .hc-overlay.show { opacity:1; }
        .hc-modal { background:#fff; border-radius:24px; width:100%; max-width:540px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); transform:scale(0.95); transition:transform 0.3s cubic-bezier(0.34,1.56,0.64,1); }
        .hc-overlay.show .hc-modal { transform:scale(1); }
        .hc-modal-head { display:flex; align-items:center; justify-content:space-between; padding:1.5rem; border-bottom:1px solid #f1f5f9; background:#fff; }
        .hc-modal-title { font-size:1.125rem; font-weight:800; color:#0f172a; }
        .hc-modal-close { background:#f1f5f9; border:none; cursor:pointer; width:32px; height:32px; border-radius:50%; color:#64748b; transition:all 0.2s; display:flex; align-items:center; justify-content:center; }
        .hc-modal-close:hover { color:#0f172a; background:#e2e8f0; }
        .hc-modal-body { padding:1.5rem; }
        .hc-modal-foot { padding:1.25rem 1.5rem; border-top:1px solid #f1f5f9; background:#f8fafc; display:flex; justify-content:flex-end; gap:0.75rem; }
        .hc-form-row { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
        .hc-form-group { display:flex; flex-direction:column; gap:0.5rem; }
        .hc-form-group.full { grid-column:1/-1; }
        .hc-form-label { font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; }
        .hc-form-label .req { color:#ef4444; }
        .hc-form-inp {
            width:100%; padding:0.75rem 1rem; border:1px solid #e2e8f0; border-radius:12px;
            background:#f8fafc; font-family:inherit; font-size:0.875rem; color:#0f172a; font-weight:500;
            transition:all 0.2s; outline:none;
        }
        .hc-form-inp:focus { border-color:#6366f1; background:#fff; box-shadow:0 0 0 4px rgba(99,102,241,0.1); }
        .hc-form-txt { resize:vertical; min-height:80px; line-height:1.5; }

        .hc-checkbox-card { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1rem; border:1px solid #e2e8f0; border-radius:12px; background:#fff; cursor:pointer; transition:all 0.2s; }
        .hc-checkbox-card:hover { border-color:#6366f1; }
        .hc-checkbox-card input { width:18px; height:18px; accent-color:#4f46e5; cursor:pointer; }
        .hc-checkbox-card span { font-size:0.875rem; font-weight:700; color:#1e293b; }

        @media(max-width:768px) {
            .hc-stats { grid-template-columns:1fr 1fr; }
            .hc-filter-card { flex-direction:column; align-items:stretch; }
            .hc-filter-actions { justify-content:flex-end; width:100%; }
            .hc-form-row { grid-template-columns:1fr; }
        }
    </style>
    @endpush

    <div class="hc-wrap">

        {{-- ─── STATS ─── --}}
        <div class="hc-stats">
            <div class="hc-stat total">
                <div class="hc-stat-label">Total Pengajuan</div>
                <div class="hc-stat-value">{{ $requests->count() }}</div>
                <div class="hc-stat-change">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</div>
            </div>
            <div class="hc-stat pending">
                <div class="hc-stat-label">Menunggu Approval</div>
                <div class="hc-stat-value">{{ $requests->where('status', 'pending')->count() }}</div>
                <div class="hc-stat-change">Pending Review</div>
            </div>
            <div class="hc-stat approved">
                <div class="hc-stat-label">Disetujui</div>
                <div class="hc-stat-value">{{ $requests->where('status', 'approved')->count() }}</div>
                <div class="hc-stat-change">Approved</div>
            </div>
            <div class="hc-stat rejected">
                <div class="hc-stat-label">Ditolak</div>
                <div class="hc-stat-value">{{ $requests->where('status', 'rejected')->count() }}</div>
                <div class="hc-stat-change">Rejected</div>
            </div>
        </div>

        {{-- ─── FILTER ─── --}}
        <form method="GET" action="{{ route('sdm.cuti.index') }}" class="hc-filter-card">
            <div class="hc-filter-group">
                <label class="hc-filter-label">Periode Bulan</label>
                <input type="month" name="month" value="{{ $month }}" class="hc-filter-inp">
            </div>
            <div class="hc-filter-group">
                <label class="hc-filter-label">Status</label>
                <select name="status" class="hc-filter-inp">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="hc-filter-group">
                <label class="hc-filter-label">Karyawan</label>
                <select name="user_id" class="hc-filter-inp">
                    <option value="">-- Semua Karyawan --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="hc-filter-actions">
                <a href="{{ route('sdm.cuti.index') }}" class="hc-btn hc-btn-ghost">Reset</a>
                <button type="submit" class="hc-btn hc-btn-primary">Terapkan Filter</button>
            </div>
        </form>

        {{-- ─── TABLE ─── --}}
        <div class="hc-table-card">
            <div class="hc-table-head">
                <h2 class="hc-table-title">Log Pengajuan: {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</h2>
            </div>
            <div class="hc-table-wrap">
                <table class="hc-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Tipe Izin</th>
                            <th>Periode Rentang</th>
                            <th style="text-align:center;">Dibayar</th>
                            <th style="text-align:center;">Status</th>
                            <th>Catatan</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $r)
                            <tr>
                                <td>
                                    <div class="hc-user-info">
                                        <div class="hc-avatar">{{ strtoupper(substr($r->user?->name ?? '?', 0, 1)) }}</div>
                                        <div class="hc-user-details">
                                            <div class="hc-user-name">{{ $r->user?->name ?? 'Data Karyawan Terhapus' }}</div>
                                            <div class="hc-user-email">{{ $r->user?->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="hc-badge type-{{ strtolower($r->type) }}">{{ strtoupper($r->type) }}</span>
                                </td>
                                <td>
                                    <div class="hc-date-range">
                                        <div class="hc-date-val">
                                            {{ $r->start_date->format('d/m/Y') }}
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#94a3b8;"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                            {{ $r->end_date->format('d/m/Y') }}
                                        </div>
                                        <div class="hc-date-dur">{{ $r->start_date->diffInDays($r->end_date) + 1 }} Hari Penuh</div>
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    @if($r->paid)
                                        <div class="hc-paid-yes">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> Ya
                                        </div>
                                    @else
                                        <div class="hc-paid-no">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Tidak
                                        </div>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <span class="hc-badge status-{{ $r->status }}">{{ strtoupper($r->status) }}</span>
                                </td>
                                <td>
                                    <div class="hc-notes" title="{{ $r->notes }}">{{ $r->notes ?: '-' }}</div>
                                </td>
                                <td style="text-align:right;">
                                    <div class="hc-act-btns">
                                        @can('edit_absensi')
                                            @if($r->status === 'pending')
                                                <form method="POST" action="{{ route('sdm.cuti.approve', $r) }}" style="margin:0;">
                                                    @csrf
                                                    <button type="submit" class="hc-icon-btn approve" title="Setujui Cuti">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('sdm.cuti.reject', $r) }}" style="margin:0;">
                                                    @csrf
                                                    <button type="submit" class="hc-icon-btn reject" title="Tolak Cuti">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                        @can('delete_absensi')
                                            @if($r->status !== 'approved')
                                                <form method="POST" action="{{ route('sdm.cuti.destroy', $r) }}" style="margin:0;" onsubmit="return confirm('Hapus pengajuan ini dari log?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="hc-icon-btn del" title="Hapus Permanen">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="hc-empty">
                                        <div class="hc-empty-ico">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle></svg>
                                        </div>
                                        <div class="hc-empty-title">Tidak ada pengajuan ditemukan</div>
                                        <div class="hc-empty-desc">Belum ada karyawan yang mengajukan cuti pada filter bulan dan status ini.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ─── MODAL ADD ─── --}}
    <div id="addModal" class="hc-overlay">
        <div class="hc-modal">
            <div class="hc-modal-head">
                <div class="hc-modal-title">Form Pengajuan Baru</div>
                <button type="button" class="hc-modal-close" onclick="closeAddModal()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form action="{{ route('sdm.cuti.store') }}" method="POST">
                @csrf
                <div class="hc-modal-body">
                    <div class="hc-form-group" style="margin-bottom:1.25rem;">
                        <label class="hc-form-label">Karyawan Pemohon <span class="req">*</span></label>
                        <select name="user_id" class="hc-form-inp" required>
                            <option value="">-- Pilih Karyawan Aktif --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="hc-form-row">
                        <div class="hc-form-group">
                            <label class="hc-form-label">Tipe Izin <span class="req">*</span></label>
                            <select name="type" class="hc-form-inp" required>
                                <option value="cuti">Cuti Tahunan</option>
                                <option value="izin">Izin (Keperluan Pribadi)</option>
                                <option value="sakit">Sakit</option>
                            </select>
                        </div>
                        <div class="hc-form-group" style="justify-content:flex-end;">
                            <label class="hc-checkbox-card">
                                <input type="checkbox" name="paid" value="1" checked>
                                <span>Izin Berbayar (Paid)</span>
                            </label>
                        </div>
                    </div>
                    <div class="hc-form-row">
                        <div class="hc-form-group">
                            <label class="hc-form-label">Mulai Tanggal <span class="req">*</span></label>
                            <input type="date" name="start_date" class="hc-form-inp" required>
                        </div>
                        <div class="hc-form-group">
                            <label class="hc-form-label">Sampai Tanggal <span class="req">*</span></label>
                            <input type="date" name="end_date" class="hc-form-inp" required>
                        </div>
                    </div>
                    <div class="hc-form-group">
                        <label class="hc-form-label">Keterangan / Alasan Khusus</label>
                        <textarea name="notes" class="hc-form-inp hc-form-txt" placeholder="Deskripsikan alasan pengajuan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="hc-modal-foot">
                    <button type="button" class="hc-btn hc-btn-ghost" onclick="closeAddModal()">Batalkan</button>
                    <button type="submit" class="hc-btn hc-btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Simpan & Ajukan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openAddModal() { 
            const overlay = document.getElementById('addModal');
            overlay.style.display = 'flex'; 
            setTimeout(() => overlay.classList.add('show'), 10);
        }
        function closeAddModal() { 
            const overlay = document.getElementById('addModal');
            overlay.classList.remove('show');
            setTimeout(() => overlay.style.display = 'none', 300);
        }
        
        // Close on overlay click
        document.querySelectorAll('.hc-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddModal();
                }
            });
        });

        document.addEventListener('keydown', function(e) { 
            if(e.key === 'Escape') { 
                closeAddModal(); 
            } 
        });
    </script>
    @endpush
</x-hr-layout>
