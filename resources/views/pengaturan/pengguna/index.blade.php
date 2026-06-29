<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <style>
        .us-wrap {
            padding: 1.5rem 1rem;
            max-width: 1320px;
            margin: 0 auto;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        /* Alerts */
        .us-alert {
            padding: 0.875rem 1.25rem;
            margin-bottom: 1.25rem;
            border-radius: 0.625rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            animation: usSlideIn 0.3s ease;
        }
        @keyframes usSlideIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .us-alert-ok { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
        .us-alert-err { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* Stats */
        .us-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.875rem;
            margin-bottom: 1.25rem;
        }
        @media(min-width: 640px) { .us-stats { grid-template-columns: repeat(4, 1fr); } }

        .us-stat {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem 1.125rem;
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .us-stat:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
        .us-stat::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 3px; height: 100%;
        }
        .us-stat-total::before { background: #3b82f6; }
        .us-stat-active::before { background: #10b981; }
        .us-stat-pending::before { background: #f59e0b; }
        .us-stat-inactive::before { background: #ef4444; }

        .us-stat-label {
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6b7280;
            margin-bottom: 0.375rem;
        }
        .us-stat-val {
            font-size: 1.625rem;
            font-weight: 700;
            color: #111827;
            line-height: 1;
            font-family: 'JetBrains Mono', monospace;
        }
        .us-stat-sub {
            font-size: 0.6875rem;
            color: #9ca3af;
            margin-top: 0.25rem;
        }

        /* Card */
        .us-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            overflow: hidden;
        }
        .us-card-hd {
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
            border-bottom: 1px solid #e5e7eb;
        }
        @media(min-width: 640px) {
            .us-card-hd { flex-direction: row; justify-content: space-between; align-items: center; }
        }
        .us-card-hd h3 {
            font-size: 1.0625rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }
        .us-card-hd p {
            font-size: 0.8125rem;
            color: #6b7280;
            margin: 0.25rem 0 0;
        }

        /* Buttons */
        .us-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-radius: 0.5rem;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            white-space: nowrap;
        }
        .us-btn-primary { background: #2563eb; color: #fff; }
        .us-btn-primary:hover { background: #1d4ed8; box-shadow: 0 2px 8px rgba(37,99,235,0.25); }
        .us-btn-ghost { background: #fff; color: #374151; border-color: #d1d5db; }
        .us-btn-ghost:hover { background: #f9fafb; border-color: #9ca3af; }
        .us-btn-danger-ghost { background: #fff; color: #dc2626; border-color: #fca5a5; }
        .us-btn-danger-ghost:hover { background: #fef2f2; }

        /* Filters */
        .us-filters {
            display: flex;
            gap: 0.625rem;
            align-items: center;
            flex-wrap: wrap;
            padding: 1rem 1.5rem;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        .us-input, .us-select {
            padding: 0.5rem 0.75rem;
            font-size: 0.8125rem;
            color: #111827;
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            height: 38px;
        }
        .us-input:focus, .us-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }
        .us-search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 300px; }
        .us-search-wrap svg { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); pointer-events: none; }
        .us-search-wrap .us-input { padding-left: 2.25rem; width: 100%; }

        /* Table */
        .us-tbl-wrap { overflow-x: auto; }
        .us-tbl {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .us-tbl th {
            padding: 0.75rem 1.5rem;
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
            background: #fff;
            white-space: nowrap;
        }
        .us-tbl td {
            padding: 0.875rem 1.5rem;
            font-size: 0.8125rem;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }
        .us-tbl tbody tr { transition: background 0.1s; }
        .us-tbl tbody tr:hover { background: #f9fafb; }
        .us-tbl tbody tr:last-child td { border-bottom: none; }

        /* User cell */
        .us-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .us-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
            border: 2px solid;
        }
        .us-avatar-supervisor { background: #f3e8ff; color: #7c3aed; border-color: #c4b5fd; }
        .us-avatar-admin { background: #dbeafe; color: #2563eb; border-color: #93c5fd; }
        .us-avatar-kasir { background: #dcfce7; color: #16a34a; border-color: #86efac; }
        .us-avatar-gudang { background: #fef9c3; color: #ca8a04; border-color: #fde047; }
        .us-avatar-sales { background: #ccfbf1; color: #0d9488; border-color: #5eead4; }
        .us-avatar-default { background: #f3f4f6; color: #4b5563; border-color: #d1d5db; }

        .us-name { font-weight: 600; color: #111827; font-size: 0.8125rem; }
        .us-email { font-size: 0.6875rem; color: #9ca3af; }

        /* Pills */
        .us-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.625rem;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .us-pill-purple { background: #f3e8ff; color: #7c3aed; }
        .us-pill-blue { background: #dbeafe; color: #2563eb; }
        .us-pill-green { background: #dcfce7; color: #16a34a; }
        .us-pill-teal { background: #ccfbf1; color: #0d9488; }
        .us-pill-yellow { background: #fef9c3; color: #ca8a04; }
        .us-pill-red { background: #fee2e2; color: #dc2626; }
        .us-pill-gray { background: #f3f4f6; color: #4b5563; }

        /* Status badge */
        .us-status {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.625rem;
            border-radius: 999px;
        }
        .us-status::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
        }
        .us-status-active { background: #dcfce7; color: #16a34a; }
        .us-status-active::before { background: #16a34a; }
        .us-status-pending { background: #fef9c3; color: #ca8a04; }
        .us-status-pending::before { background: #ca8a04; animation: usPulse 1.5s infinite; }
        @keyframes usPulse { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }
        .us-status-inactive { background: #fee2e2; color: #dc2626; }
        .us-status-inactive::before { background: #dc2626; }

        /* Action buttons */
        .us-actions { display: flex; gap: 0.25rem; justify-content: center; }
        .us-act {
            width: 30px; height: 30px;
            border-radius: 0.375rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            background: transparent;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.15s;
        }
        .us-act:hover { background: #f3f4f6; color: #111827; }
        .us-act-acc:hover { background: #dcfce7; color: #16a34a; }
        .us-act-rej:hover { background: #fee2e2; color: #dc2626; }

        /* Pending row highlight */
        .us-row-pending { background: #fffbeb; }
        .us-row-pending:hover { background: #fef3c7; }

        /* Pagination */
        .us-pg-footer {
            padding: 0.875rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            background: #fff;
        }

        /* Empty */
        .us-empty {
            padding: 3.5rem 1.5rem;
            text-align: center;
        }
        .us-empty-icon {
            width: 56px; height: 56px;
            margin: 0 auto 1rem;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .us-empty-icon svg { width: 24px; height: 24px; color: #9ca3af; }
        .us-empty h4 { margin: 0 0 0.375rem; font-size: 0.9375rem; font-weight: 600; color: #111827; }
        .us-empty p { margin: 0 0 1.25rem; font-size: 0.8125rem; color: #6b7280; }

        /* You badge */
        .us-you {
            display: inline-flex;
            align-items: center;
            padding: 0.1rem 0.375rem;
            border-radius: 999px;
            font-size: 0.6rem;
            font-weight: 700;
            background: #dbeafe;
            color: #2563eb;
            margin-left: 0.375rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>

    <div class="us-wrap">
        {{-- Alerts --}}
        @if(session('success'))
            <div class="us-alert us-alert-ok">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="us-alert us-alert-err">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Stats --}}
        <div class="us-stats">
            <div class="us-stat us-stat-total">
                <div class="us-stat-label">Total Pengguna</div>
                <div class="us-stat-val">{{ $stats['totalUsers'] }}</div>
                <div class="us-stat-sub">Semua akun terdaftar</div>
            </div>
            <div class="us-stat us-stat-active">
                <div class="us-stat-label">Aktif</div>
                <div class="us-stat-val">{{ $stats['activeUsers'] }}</div>
                <div class="us-stat-sub">Sudah disetujui</div>
            </div>
            <div class="us-stat us-stat-pending">
                <div class="us-stat-label">Pending</div>
                <div class="us-stat-val">{{ $stats['pendingUsers'] }}</div>
                <div class="us-stat-sub">Menunggu persetujuan</div>
            </div>
            <div class="us-stat us-stat-inactive">
                <div class="us-stat-label">Nonaktif</div>
                <div class="us-stat-val">{{ $stats['inactiveUsers'] }}</div>
                <div class="us-stat-sub">Ditolak / dinonaktifkan</div>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="us-card">
            <div class="us-card-hd">
                <div>
                    <h3>Daftar Pengguna</h3>
                    <p>Kelola akses, role, dan status akun pengguna sistem.</p>
                </div>
                @can('create_pengguna')
                <a href="{{ route('pengguna.create') }}" class="us-btn us-btn-primary">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pengguna
                </a>
                @endcan
            </div>

            {{-- Filters --}}
            <form method="GET" action="{{ route('pengguna.index') }}" class="us-filters" id="usFilterForm">
                <div class="us-search-wrap">
                    <svg width="15" height="15" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, NIK..." class="us-input">
                </div>
                <select name="role" class="us-select" style="min-width:150px;">
                    <option value="">Semua Role</option>
                    @foreach($allRoles as $r)
                        <option value="{{ $r->name }}" {{ $role == $r->name ? 'selected' : '' }}>
                            {{ $r->label ?? strtoupper($r->name) }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="us-select" style="min-width:140px;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ $status === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $status === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="us-btn us-btn-ghost">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                @if($search || $role || $status !== null && $status !== '')
                    <a href="{{ route('pengguna.index') }}" class="us-btn us-btn-danger-ghost">Reset</a>
                @endif
            </form>

            {{-- Table --}}
            <div class="us-tbl-wrap">
                <table class="us-tbl">
                    <thead>
                        <tr>
                            <th style="width:40px;text-align:center;">#</th>
                            <th>Profil</th>
                            <th>NIK</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th style="text-align:center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $i => $user)
                        @php
                            $isPending = !$user->active && !$user->approved_at && !$user->rejected_at;
                            $roleColors = [
                                'supervisor' => 'us-pill-purple',
                                'admin1' => 'us-pill-green',
                                'admin2' => 'us-pill-teal',
                                'admin3' => 'us-pill-yellow',
                                'admin4' => 'us-pill-red',
                                'gudang' => 'us-pill-teal',
                                'sales' => 'us-pill-blue',
                                'sales_minyak' => 'us-pill-yellow',
                                'sales_mineral' => 'us-pill-teal',
                            ];
                            $avatarClass = match(true) {
                                $user->role === 'supervisor' => 'us-avatar-supervisor',
                                str_starts_with($user->role, 'admin') => 'us-avatar-admin',
                                $user->role === 'gudang' => 'us-avatar-gudang',
                                str_starts_with($user->role, 'sales') => 'us-avatar-sales',
                                default => 'us-avatar-default',
                            };
                            $label = $roleLabels[$user->role] ?? strtoupper($user->role);
                            $pillColor = $roleColors[$user->role] ?? 'us-pill-gray';
                        @endphp
                        <tr class="{{ $isPending ? 'us-row-pending' : '' }}">
                            <td style="text-align:center;color:#9ca3af;font-family:'JetBrains Mono',monospace;font-size:0.75rem;">{{ $users->firstItem() + $i }}</td>
                            <td>
                                <div class="us-user">
                                    <div class="us-avatar {{ $avatarClass }}">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="us-name">
                                            {{ $user->name }}
                                            @if(auth()->id() === $user->id)
                                                <span class="us-you">You</span>
                                            @endif
                                        </div>
                                        <div class="us-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-family:'JetBrains Mono',monospace;font-size:0.75rem;color:#6b7280;">{{ $user->nik ?? '-' }}</td>
                            <td><span class="us-pill {{ $pillColor }}">{{ $label }}</span></td>
                            <td>
                                @if($user->active)
                                    <span class="us-status us-status-active">Aktif</span>
                                @elseif($isPending)
                                    <span class="us-status us-status-pending">Pending</span>
                                @else
                                    <span class="us-status us-status-inactive">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="us-actions">
                                    @can('edit_pengguna')
                                        @if($isPending)
                                            <form action="{{ route('pengguna.approve', $user->id) }}" method="POST" onsubmit="return confirm('Setujui akun {{ $user->name }}?');">
                                                @csrf
                                                <button type="submit" class="us-act us-act-acc" title="Setujui">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('pengguna.reject', $user->id) }}" method="POST" onsubmit="return confirm('Tolak akun {{ $user->name }}?');">
                                                @csrf
                                                <button type="submit" class="us-act us-act-rej" title="Tolak">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('pengguna.edit', $user->id) }}" class="us-act" title="Edit">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                    @endcan
                                    @can('delete_pengguna')
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('pengguna.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus permanen akun {{ $user->name }}?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="us-act us-act-rej" title="Hapus">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="us-empty">
                                    <div class="us-empty-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <h4>Tidak ada data pengguna</h4>
                                    <p>Belum ada akun staf atau pengguna terdaftar.</p>
                                    @can('create_pengguna')
                                        <a href="{{ route('pengguna.create') }}" class="us-btn us-btn-primary">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                            Tambah Pengguna
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="us-pg-footer">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
