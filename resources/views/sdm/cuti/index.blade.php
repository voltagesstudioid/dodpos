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
            <button type="button" class="hr-btn hr-btn-success" onclick="openAddModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Ajukan Cuti
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

    {{-- Stats --}}
    <div class="hr-stats" style="margin-bottom: 1.5rem;">
        <div class="hr-stat">
            <div class="hr-stat-label">Total Pengajuan</div>
            <div class="hr-stat-value">{{ $requests->count() }}</div>
            <div class="hr-stat-change">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Menunggu Approval</div>
            <div class="hr-stat-value" style="color: #ea580c;">{{ $requests->where('status', 'pending')->count() }}</div>
            <div class="hr-stat-change">Pending</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Disetujui</div>
            <div class="hr-stat-value" style="color: #16a34a;">{{ $requests->where('status', 'approved')->count() }}</div>
            <div class="hr-stat-change positive">Approved</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Ditolak</div>
            <div class="hr-stat-value" style="color: #dc2626;">{{ $requests->where('status', 'rejected')->count() }}</div>
            <div class="hr-stat-change negative">Rejected</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="hr-card" style="margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('sdm.cuti.index') }}" class="hr-filter">
            <div class="hr-filter-group">
                <label class="hr-filter-label">Periode Bulan</label>
                <input type="month" name="month" value="{{ $month }}" class="hr-input">
            </div>
            <div class="hr-filter-group">
                <label class="hr-filter-label">Status</label>
                <select name="status" class="hr-select">
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="hr-filter-group">
                <label class="hr-filter-label">Karyawan</label>
                <select name="user_id" class="hr-select">
                    <option value="">Semua Karyawan</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="hr-filter-group" style="flex: 0; min-width: auto;">
                <button type="submit" class="hr-btn hr-btn-primary">Filter</button>
            </div>
            <div class="hr-filter-group" style="flex: 0; min-width: auto;">
                <a href="{{ route('sdm.cuti.index') }}" class="hr-btn hr-btn-ghost">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="hr-card">
        <div class="hr-card-header">
            <h2 class="hr-card-title">Log Pengajuan: {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</h2>
        </div>
        <div class="hr-table-wrapper">
            <table class="hr-table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Tipe</th>
                        <th>Periode</th>
                        <th style="text-align: center;">Berbayar</th>
                        <th style="text-align: center;">Status</th>
                        <th>Catatan</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $r)
                        <tr>
                            <td>
                                <div class="hr-user">
                                    <div class="hr-avatar">{{ strtoupper(substr($r->user?->name ?? '?', 0, 1)) }}</div>
                                    <div class="hr-user-info">
                                        <div class="hr-user-name">{{ $r->user?->name ?? '-' }}</div>
                                        <div class="hr-user-meta">{{ $r->user?->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="hr-badge {{ match($r->type) { 'cuti' => 'hr-badge-blue', 'izin' => 'hr-badge-yellow', 'sakit' => 'hr-badge-gray', default => 'hr-badge-gray' } }}">{{ strtoupper($r->type) }}</span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                    <span>{{ $r->start_date->format('d/m/Y') }}</span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #9ca3af;"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                    <span>{{ $r->end_date->format('d/m/Y') }}</span>
                                </div>
                                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">
                                    {{ $r->start_date->diffInDays($r->end_date) + 1 }} hari
                                </div>
                            </td>
                            <td style="text-align: center;">
                                @if($r->paid)
                                    <span style="color: #16a34a; font-weight: 600;">Ya</span>
                                @else
                                    <span style="color: #dc2626; font-weight: 600;">Tidak</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @php
                                    $stCls = match($r->status) {
                                        'approved' => 'hr-badge-green',
                                        'rejected' => 'hr-badge-red',
                                        default => 'hr-badge-gray'
                                    };
                                @endphp
                                <span class="hr-badge {{ $stCls }}">{{ strtoupper($r->status) }}</span>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem; color: #4b5563; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $r->notes }}">
                                    {{ $r->notes ?: '-' }}
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div class="hr-actions">
                                    @can('edit_absensi')
                                        @if($r->status === 'pending')
                                            <form method="POST" action="{{ route('sdm.cuti.approve', $r) }}" style="margin:0;">
                                                @csrf
                                                <button type="submit" class="hr-action" style="color: #16a34a;" title="Setujui">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('sdm.cuti.reject', $r) }}" style="margin:0;">
                                                @csrf
                                                <button type="submit" class="hr-action" style="color: #dc2626;" title="Tolak">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                    @can('delete_absensi')
                                        @if($r->status !== 'approved')
                                            <form method="POST" action="{{ route('sdm.cuti.destroy', $r) }}" style="margin:0;" onsubmit="return confirm('Hapus pengajuan cuti ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="hr-action" title="Hapus">
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
                                <div class="hr-empty">
                                    <div class="hr-empty-icon">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle></svg>
                                    </div>
                                    <div class="hr-empty-title">Tidak ada pengajuan</div>
                                    <div class="hr-empty-text">Belum ada pengajuan cuti di periode ini.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Add --}}
    <div id="addModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; padding:1.5rem;">
        <div style="background:#fff; border-radius:12px; width:100%; max-width:500px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #e5e7eb;">
                <h3 style="margin:0; font-size:1.125rem; font-weight:600;">Form Pengajuan Cuti</h3>
                <button type="button" onclick="closeAddModal()" style="background:none; border:none; cursor:pointer; padding:0.5rem; color:#6b7280;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form action="{{ route('sdm.cuti.store') }}" method="POST">
                @csrf
                <div style="padding:1.5rem;">
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Pilih Karyawan <span style="color:#dc2626;">*</span></label>
                        <select name="user_id" class="hr-select" required style="width:100%;">
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Tipe Izin <span style="color:#dc2626;">*</span></label>
                            <select name="type" class="hr-select" required style="width:100%;">
                                <option value="cuti">CUTI</option>
                                <option value="izin">IZIN</option>
                                <option value="sakit">SAKIT</option>
                            </select>
                        </div>
                        <div style="display:flex; align-items:flex-end; padding-bottom:0.5rem;">
                            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                                <input type="checkbox" name="paid" value="1" checked style="width:18px; height:18px; accent-color:#4f46e5;">
                                <span style="font-size:0.875rem; color:#374151;">Dibayar (Paid)</span>
                            </label>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Mulai <span style="color:#dc2626;">*</span></label>
                            <input type="date" name="start_date" class="hr-input" required style="width:100%;">
                        </div>
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Selesai <span style="color:#dc2626;">*</span></label>
                            <input type="date" name="end_date" class="hr-input" required style="width:100%;">
                        </div>
                    </div>
                    <div>
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Catatan Alasan</label>
                        <textarea name="notes" rows="3" class="hr-input" placeholder="Tulis alasan singkat..." style="width:100%; resize:vertical;"></textarea>
                    </div>
                </div>
                <div style="padding:1rem 1.5rem; border-top:1px solid #e5e7eb; background:#f9fafb; display:flex; justify-content:flex-end; gap:0.75rem;">
                    <button type="button" class="hr-btn hr-btn-ghost" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="hr-btn hr-btn-primary">Simpan Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openAddModal() { document.getElementById('addModal').style.display = 'flex'; }
        function closeAddModal() { document.getElementById('addModal').style.display = 'none'; }
        document.addEventListener('keydown', function(e) { if(e.key === 'Escape') closeAddModal(); });
    </script>
    @endpush
</x-hr-layout>
