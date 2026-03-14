<x-app-layout>
    <x-slot name="header">Master Roles</x-slot>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">❌ {{ session('error') }}</div>   @endif

        <div class="page-header" style="margin-bottom: 2rem;">
            <div>
                <h1 class="page-header-title">Master Roles</h1>
                <div class="page-header-subtitle">Kelola daftar akses dan hak pengguna (Total: {{ $roles->total() }} role)</div>
            </div>
            <div class="page-header-actions" style="display:flex;gap:0.75rem;flex-wrap:wrap;justify-content:flex-end;">
                <a href="{{ route('pengaturan.roles.migrate') }}" class="btn-secondary">⚙️ Migrasi Role Default</a>
                <a href="{{ route('pengaturan.roles.create') }}" class="btn-primary">➕ Tambah Role</a>
            </div>
        </div>

        <div class="panel" style="margin-bottom: 1rem; border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">

            <div class="panel-header" style="background: #fff; border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem;">
                <form method="GET" action="{{ route('pengaturan.roles.index') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center; width: 100%;">
                    <div style="position: relative; flex: 1; max-width: 320px;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari key atau nama role..." class="form-input" style="padding-left: 36px; width: 100%; border-radius: 999px;">
                    </div>
                    <button type="submit" class="btn-primary" style="border-radius: 999px; padding: 0.5rem 1.25rem;">Cari</button>
                    @if($search !== '')
                        <a href="{{ route('pengaturan.roles.index') }}" class="btn-secondary" style="border-radius: 999px; padding: 0.5rem 1.25rem;">Reset</a>
                    @endif
                </form>
            </div>

            <div class="table-wrapper" style="overflow-x: auto; background: #fff; border-radius: 0 0 12px 12px;">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th>#</th>
                            <th>Key</th>
                            <th>Label</th>
                            <th>Status</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $i => $role)
                        <tr>
                            <td style="padding: 1rem 1.25rem; vertical-align: middle; color: #64748b;">{{ $roles->firstItem() + $i }}</td>
                            <td style="padding: 1rem 1.25rem; vertical-align: middle;">
                                <span class="badge badge-gray" style="font-family: monospace; font-size: 0.85rem;">{{ $role->key }}</span>
                            </td>
                            <td style="padding: 1rem 1.25rem; vertical-align: middle;">
                                <div style="font-weight:700;color:#0f172a;font-size: 1rem;">{{ Str::headline($role->label) }}</div>
                                @if($role->description)
                                    <div style="font-size:0.85rem;color:#64748b;margin-top:0.25rem;line-height:1.4;">{{ $role->description }}</div>
                                @endif
                            </td>
                            <td style="padding: 1rem 1.25rem; vertical-align: middle;">
                                @if($role->active)
                                    <span class="badge badge-success" style="padding: 0.35rem 0.75rem;">Aktif</span>
                                @else
                                    <span class="badge badge-gray" style="padding: 0.35rem 0.75rem;">Nonaktif</span>
                                @endif
                            </td>
                            <td style="padding: 1rem 1.25rem; vertical-align: middle; text-align:center;">
                                <div class="action-btns" style="display:flex;gap:0.5rem;justify-content:center;">
                                    <a href="{{ route('pengaturan.roles.edit', $role) }}" class="btn-warning btn-sm" style="border-radius: 6px;">✏️ Edit</a>
                                    <form action="{{ route('pengaturan.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus role {{ $role->key }}?');" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger btn-sm" style="border-radius: 6px;">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:#94a3b8;padding:1.5rem;">Belum ada role</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap" style="padding: 1.25rem 1.5rem; border-top: 1px solid #f1f5f9; background: #fff; border-radius: 0 0 12px 12px;">
                {{ $roles->links() }}
            </div>
        </div>
    </div>

    <style>
        .page-container { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .data-table th {
            padding: 1rem 1.25rem;
            text-align: left;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
        }
        .data-table tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.2s ease;
        }
        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }
    </style>
</x-app-layout>
