<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <!-- Custom Simple & Neat Styles -->
    <style>
        .page-container {
            padding: 1.5rem 1rem;
            max-width: 1280px;
            margin: 0 auto;
            font-family: inherit;
        }

        .alert-box {
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .alert-success { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        .card {
            background-color: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        @media(min-width: 640px) {
            .card-header {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .header-info h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
            line-height: 1.2;
        }
        .header-info p {
            margin: 0.25rem 0 0;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            white-space: nowrap;
        }
        .btn-primary { background-color: #2563eb; color: #ffffff; }
        .btn-primary:hover { background-color: #1d4ed8; }
        .btn-secondary { background-color: #ffffff; color: #374151; border-color: #d1d5db; }
        .btn-secondary:hover { background-color: #f3f4f6; color: #111827; }

        .filters {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
            padding: 1rem 1.5rem;
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-control {
            padding: 0.45rem 0.75rem;
            font-size: 0.875rem;
            color: #111827;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            outline: none;
            transition: border-color 0.2s;
            line-height: 1.5;
            height: 38px;
        }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }

        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        .table-simple {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .table-simple th {
            padding: 1rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
            background-color: #ffffff;
            white-space: nowrap;
        }
        .table-simple td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }
        .table-simple tbody tr:hover { background-color: #f9fafb; }
        .table-simple tbody tr:last-child td { border-bottom: none; }

        .user-block { display: flex; align-items: center; gap: 0.875rem; }
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #e5e7eb;
            color: #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
            border: 1px solid #d1d5db;
        }
        .user-name { font-weight: 600; color: #111827; margin-bottom: 0.1rem; }
        .user-email { font-size: 0.75rem; color: #6b7280; }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.6rem;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .pill-blue { background-color: #dbeafe; color: #1e40af; }
        .pill-green { background-color: #dcfce7; color: #166534; }
        .pill-yellow { background-color: #fef9c3; color: #854d0e; }
        .pill-red { background-color: #fee2e2; color: #991b1b; }
        .pill-purple { background-color: #f3e8ff; color: #6b21a8; }
        .pill-gray { background-color: #f3f4f6; color: #374151; }
        .pill-teal { background-color: #ccfbf1; color: #115e59; }

        .status-dot {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.8125rem;
            font-weight: 500;
        }
        .status-dot::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }
        .status-dot.active { color: #059669; }
        .status-dot.active::before { background-color: #10b981; }
        .status-dot.pending { color: #d97706; }
        .status-dot.pending::before { background-color: #f59e0b; }
        .status-dot.inactive { color: #dc2626; }
        .status-dot.inactive::before { background-color: #ef4444; }

        .actions {
            display: flex;
            gap: 0.35rem;
            justify-content: center;
        }
        .btn-act {
            width: 28px;
            height: 28px;
            border-radius: 0.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            background-color: transparent;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-act:hover { color: #111827; background-color: #e5e7eb; }
        .btn-act.acc:hover { color: #059669; background-color: #d1fae5; }
        .btn-act.rej:hover { color: #dc2626; background-color: #fee2e2; }

        .page-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            background-color: #ffffff;
        }

        .empty-state {
            padding: 4rem 1.5rem;
            text-align: center;
            color: #6b7280;
        }
        .empty-state svg {
            width: 48px;
            height: 48px;
            margin: 0 auto 1rem;
            color: #d1d5db;
        }
        .empty-state h4 { margin: 0 0 0.5rem; font-size: 1rem; font-weight: 500; color: #111827; }
        .empty-state p { margin: 0 0 1.5rem; font-size: 0.875rem; }
    </style>

    <div class="page-container">
        @if(session('success')) 
            <div class="alert-box alert-success">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div> 
        @endif
        @if(session('error'))   
            <div class="alert-box alert-danger">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                <span>{{ session('error') }}</span>
            </div>   
        @endif

        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <div class="header-info">
                    <h3>Pusat Pengguna</h3>
                    <p>Kelola data, role, dan status dari <strong>{{ $users->total() }}</strong> akun terdaftar.</p>
                </div>
                @can('create_pengguna')
                <div class="header-actions">
                    <a href="{{ route('pengguna.create') }}" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                        Daftarkan Staf Baru
                    </a>
                </div>
                @endcan
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('pengguna.index') }}" class="filters">
                <div style="position: relative; flex: 1; min-width: 200px; max-width: 300px;">
                    <svg width="16" height="16" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24" style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%);"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari pengguna..." class="form-control" style="padding-left: 2.25rem; width: 100%;">
                </div>
                <select name="role" class="form-control" style="min-width: 160px;">
                    <option value="">Semua Role</option>
                    @foreach($allRoles as $r)
                        <option value="{{ $r->name }}" {{ $role == $r->name ? 'selected' : '' }}>
                            {{ $r->label ?? strtoupper($r->name) }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="form-control" style="min-width: 140px;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending/Menunggu</option>
                    <option value="1" {{ $status === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $status === '0' ? 'selected' : '' }}>Nonaktif/Ditolak</option>
                </select>
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if($search || $role || $status !== '')
                    <a href="{{ route('pengguna.index') }}" class="btn btn-secondary" style="color: #dc2626; border-color: transparent;">Reset</a>
                @endif
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table-simple">
                    <thead>
                        <tr>
                            <th style="width:40px; text-align:center;">#</th>
                            <th>Profil</th>
                            <th>No. Induk (NIK)</th>
                            <th>Akses (Role)</th>
                            <th>Status</th>
                            <th style="text-align:center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $i => $user)
                        <tr>
                            <td style="text-align:center; color:#9ca3af;">{{ $users->firstItem() + $i }}</td>
                            <td>
                                <div class="user-block">
                                    <div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="user-name">
                                            {{ $user->name }}
                                            @if(auth()->id() === $user->id)
                                                <span class="pill pill-blue" style="margin-left:0.25rem; font-size:0.6rem;">You</span>
                                            @endif
                                        </div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-family: inherit; color:#4b5563;">{{ $user->nik ?? '-' }}</td>
                            <td>
                                @php
                                    $roleColors = [
                                        'supervisor' => 'pill-purple',
                                        'admin1' => 'pill-green',
                                        'admin2' => 'pill-teal',
                                        'admin3' => 'pill-yellow',
                                        'admin4' => 'pill-red',
                                        'kasir' => 'pill-blue',
                                        'gudang' => 'pill-teal',
                                        'admin_sales' => 'pill-green',
                                        'sales' => 'pill-blue',
                                    ];
                                    $label = $roleLabels[$user->role] ?? strtoupper($user->role);
                                    $color = $roleColors[$user->role] ?? 'pill-gray';
                                @endphp
                                <span class="pill {{ $color }}">{{ $label }}</span>
                            </td>
                            <td>
                                @if($user->active)
                                    <span class="status-dot active">Aktif</span>
                                @elseif(! $user->approved_at && ! $user->rejected_at)
                                    <span class="status-dot pending">Pending</span>
                                @else
                                    <span class="status-dot inactive">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    @can('edit_pengguna')
                                        @if(! $user->active && ! $user->approved_at && ! $user->rejected_at)
                                            <form action="{{ route('pengguna.approve', $user->id) }}" method="POST" onsubmit="return confirm('ACC akun {{ $user->name }}?');">
                                                @csrf
                                                <button type="submit" class="btn-act acc" title="Setujui Akun">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('pengguna.reject', $user->id) }}" method="POST" onsubmit="return confirm('Tolak akun {{ $user->name }}?');">
                                                @csrf
                                                <button type="submit" class="btn-act rej" title="Tolak Akun">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('pengguna.edit', $user->id) }}" class="btn-act" title="Edit Data">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                    @endcan
                                    @can('delete_pengguna')
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('pengguna.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus permanen akun {{ $user->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-act rej" title="Hapus Akun">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                                <div class="empty-state">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <h4>Tidak ada data pengguna</h4>
                                    <p>Sistem Anda belum memiliki data staf atau pengguna operasional.</p>
                                    @can('create_pengguna')
                                        <a href="{{ route('pengguna.create') }}" class="btn btn-primary">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                                            Tambah Pengguna Baru
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
                <div class="page-footer">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
