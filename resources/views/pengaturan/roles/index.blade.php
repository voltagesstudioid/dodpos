<x-app-layout>
    <x-slot name="header">Master Roles</x-slot>

    <style>
        .rl-wrap {
            padding: 1.5rem 1rem;
            max-width: 1100px;
            margin: 0 auto;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        /* Alerts */
        .rl-alert {
            padding: 0.875rem 1.25rem;
            margin-bottom: 1.25rem;
            border-radius: 0.625rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            animation: rlFadeIn 0.3s ease;
        }
        @keyframes rlFadeIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
        .rl-alert-ok { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .rl-alert-err { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* Stats */
        .rl-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.875rem;
            margin-bottom: 1.25rem;
        }
        .rl-stat {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem 1.125rem;
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .rl-stat:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
        .rl-stat::before { content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 100%; }
        .rl-stat-total::before { background: #6366f1; }
        .rl-stat-active::before { background: #10b981; }
        .rl-stat-inactive::before { background: #94a3b8; }

        .rl-stat-label { font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; margin-bottom: 0.375rem; }
        .rl-stat-val { font-size: 1.625rem; font-weight: 700; color: #111827; line-height: 1; font-family: 'JetBrains Mono', monospace; }
        .rl-stat-sub { font-size: 0.6875rem; color: #9ca3af; margin-top: 0.25rem; }

        /* Card */
        .rl-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            overflow: hidden;
        }
        .rl-card-hd {
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.875rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .rl-card-hd h3 { font-size: 1.0625rem; font-weight: 700; color: #111827; margin: 0; }
        .rl-card-hd p { font-size: 0.8125rem; color: #6b7280; margin: 0.125rem 0 0; }
        .rl-hd-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        /* Buttons */
        .rl-btn {
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
            font-family: inherit;
        }
        .rl-btn-primary { background: #6366f1; color: #fff; }
        .rl-btn-primary:hover { background: #4f46e5; box-shadow: 0 2px 8px rgba(99,102,241,0.25); }
        .rl-btn-ghost { background: #fff; color: #374151; border-color: #d1d5db; }
        .rl-btn-ghost:hover { background: #f9fafb; border-color: #9ca3af; }
        .rl-btn-amber { background: #fff; color: #d97706; border-color: #fde68a; }
        .rl-btn-amber:hover { background: #fffbeb; }
        .rl-btn-sm { padding: 0.375rem 0.75rem; font-size: 0.75rem; }
        .rl-btn-danger { background: #fff; color: #dc2626; border-color: #fca5a5; }
        .rl-btn-danger:hover { background: #fef2f2; }

        /* Filters */
        .rl-filters {
            display: flex;
            gap: 0.625rem;
            align-items: center;
            flex-wrap: wrap;
            padding: 1rem 1.5rem;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        .rl-search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 320px; }
        .rl-search-wrap svg { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); pointer-events: none; }
        .rl-input {
            width: 100%;
            padding: 0.5rem 0.75rem 0.5rem 2.25rem;
            font-size: 0.8125rem;
            color: #111827;
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 999px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            height: 38px;
            font-family: inherit;
        }
        .rl-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }

        /* Table */
        .rl-tbl-wrap { overflow-x: auto; }
        .rl-tbl {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .rl-tbl th {
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
        .rl-tbl td {
            padding: 0.875rem 1.5rem;
            font-size: 0.8125rem;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }
        .rl-tbl tbody tr { transition: background 0.1s; }
        .rl-tbl tbody tr:hover { background: #f9fafb; }
        .rl-tbl tbody tr:last-child td { border-bottom: none; }

        /* Key badge */
        .rl-key {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.5rem;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            font-weight: 600;
            color: #475569;
        }

        /* Label */
        .rl-label { font-weight: 600; color: #111827; font-size: 0.875rem; }
        .rl-desc { font-size: 0.75rem; color: #9ca3af; margin-top: 0.125rem; max-width: 300px; }

        /* Status pill */
        .rl-status {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.625rem;
            border-radius: 999px;
        }
        .rl-status::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
        .rl-status-on { background: #dcfce7; color: #16a34a; }
        .rl-status-on::before { background: #16a34a; }
        .rl-status-off { background: #f3f4f6; color: #6b7280; }
        .rl-status-off::before { background: #9ca3af; }

        /* User count badge */
        .rl-users {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            color: #6b7280;
        }
        .rl-users svg { width: 14px; height: 14px; }

        /* Actions */
        .rl-actions { display: flex; gap: 0.375rem; justify-content: center; }

        /* Pagination */
        .rl-pg-footer { padding: 0.875rem 1.5rem; border-top: 1px solid #e5e7eb; background: #fff; }

        /* Empty */
        .rl-empty { padding: 3rem 1.5rem; text-align: center; }
        .rl-empty-icon { width: 48px; height: 48px; margin: 0 auto 0.75rem; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .rl-empty-icon svg { width: 20px; height: 20px; color: #9ca3af; }
        .rl-empty h4 { margin: 0 0 0.25rem; font-size: 0.875rem; font-weight: 600; color: #111827; }
        .rl-empty p { margin: 0; font-size: 0.8125rem; color: #6b7280; }

        /* Supervisor protected row */
        .rl-row-protected { background: #fefce8; }
        .rl-row-protected:hover { background: #fef9c3; }

        @media(max-width: 640px) {
            .rl-stats { grid-template-columns: 1fr; }
            .rl-card-hd { flex-direction: column; align-items: flex-start; }
        }
    </style>

    <div class="rl-wrap">
        {{-- Alerts --}}
        @if(session('success'))
            <div class="rl-alert rl-alert-ok">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rl-alert rl-alert-err">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Stats --}}
        <div class="rl-stats">
            <div class="rl-stat rl-stat-total">
                <div class="rl-stat-label">Total Roles</div>
                <div class="rl-stat-val">{{ $stats['totalRoles'] }}</div>
                <div class="rl-stat-sub">Semua role terdaftar</div>
            </div>
            <div class="rl-stat rl-stat-active">
                <div class="rl-stat-label">Aktif</div>
                <div class="rl-stat-val">{{ $stats['activeRoles'] }}</div>
                <div class="rl-stat-sub">Role yang tersedia</div>
            </div>
            <div class="rl-stat rl-stat-inactive">
                <div class="rl-stat-label">Nonaktif</div>
                <div class="rl-stat-val">{{ $stats['inactiveRoles'] }}</div>
                <div class="rl-stat-sub">Role yang dinonaktifkan</div>
            </div>
        </div>

        {{-- Card --}}
        <div class="rl-card">
            <div class="rl-card-hd">
                <div>
                    <h3>Daftar Role</h3>
                    <p>Kelola hak akses dan peran pengguna dalam sistem.</p>
                </div>
                <div class="rl-hd-actions">
                    <a href="{{ route('pengaturan.roles.migrate') }}" class="rl-btn rl-btn-amber">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Migrasi Role
                    </a>
                    <a href="{{ route('pengaturan.roles.create') }}" class="rl-btn rl-btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Tambah Role
                    </a>
                </div>
            </div>

            {{-- Filter --}}
            <form method="GET" action="{{ route('pengaturan.roles.index') }}" class="rl-filters">
                <div class="rl-search-wrap">
                    <svg width="15" height="15" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari key atau nama role..." class="rl-input">
                </div>
                <button type="submit" class="rl-btn rl-btn-ghost rl-btn-sm">Cari</button>
                @if($search !== '')
                    <a href="{{ route('pengaturan.roles.index') }}" class="rl-btn rl-btn-danger rl-btn-sm">Reset</a>
                @endif
            </form>

            {{-- Table --}}
            <div class="rl-tbl-wrap">
                <table class="rl-tbl">
                    <thead>
                        <tr>
                            <th style="width:40px;text-align:center;">#</th>
                            <th>Key</th>
                            <th>Label</th>
                            <th>Pengguna</th>
                            <th>Status</th>
                            <th style="text-align:center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $i => $role)
                        @php
                            $userCount = \App\Models\User::where('role', $role->key)->count();
                            $isProtected = $role->key === 'supervisor';
                        @endphp
                        <tr class="{{ $isProtected ? 'rl-row-protected' : '' }}">
                            <td style="text-align:center;color:#9ca3af;font-family:'JetBrains Mono',monospace;font-size:0.75rem;">{{ $roles->firstItem() + $i }}</td>
                            <td><span class="rl-key">{{ $role->key }}</span></td>
                            <td>
                                <div class="rl-label">{{ Str::headline($role->label) }}</div>
                                @if($role->description)
                                    <div class="rl-desc">{{ $role->description }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="rl-users">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    {{ $userCount }} pengguna
                                </span>
                            </td>
                            <td>
                                @if($role->active)
                                    <span class="rl-status rl-status-on">Aktif</span>
                                @else
                                    <span class="rl-status rl-status-off">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="rl-actions">
                                    <a href="{{ route('pengaturan.roles.edit', $role) }}" class="rl-btn rl-btn-ghost rl-btn-sm">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Edit
                                    </a>
                                    @if(!$isProtected)
                                    <form action="{{ route('pengaturan.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Hapus role \'{{ $role->key }}\'?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rl-btn rl-btn-danger rl-btn-sm">Hapus</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="rl-empty">
                                    <div class="rl-empty-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <h4>Belum ada role</h4>
                                    <p>Tambahkan role pertama untuk mengelola hak akses pengguna.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($roles->hasPages())
                <div class="rl-pg-footer">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
