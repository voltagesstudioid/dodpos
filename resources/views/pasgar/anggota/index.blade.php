<x-app-layout>
    <x-slot name="header">Daftar Anggota Pasgar</x-slot>

    <div class="page-container">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">❌ {{ session('error') }}</div>
        @endif

        {{-- Summary Cards --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.75rem; font-weight:800; color:#4f46e5;">{{ $totalMembers }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Total Anggota</div>
            </div>
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.75rem; font-weight:800; color:#10b981;">{{ $activeMembers }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Anggota Aktif</div>
            </div>
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.75rem; font-weight:800; color:#f59e0b;">{{ $totalMembers - $activeMembers }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Tidak Aktif</div>
            </div>
        </div>

        <div class="card">
            {{-- Header --}}
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">👥 Data Pasukan Pasgar</h2>
                    <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Kelola anggota tim lapangan / kanvas</p>
                </div>
                @can('create_pasgar_anggota')
                <a href="{{ route('pasgar.anggota.create') }}" class="btn-primary">
                    ＋ Tambah Anggota
                </a>
                @endcan
            </div>

            {{-- Filter --}}
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Cari Nama</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama anggota..." class="form-input" style="width:220px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Status</label>
                        <select name="active" class="form-input" style="width:140px;">
                            <option value="">Semua</option>
                            <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary btn-sm">🔍 Filter</button>
                    <a href="{{ route('pasgar.anggota.index') }}" class="btn-secondary btn-sm">Reset</a>
                </form>
            </div>

            {{-- Table --}}
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Anggota</th>
                            <th>Email</th>
                            <th>Kendaraan</th>
                            <th>Area</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($members as $i => $member)
                        <tr>
                            <td class="text-muted">{{ $members->firstItem() + $i }}</td>
                            <td>
                                <div style="font-weight:600; color:#1e293b;">{{ $member->user->name }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">ID: {{ $member->id }}</div>
                            </td>
                            <td class="text-muted">{{ $member->user->email }}</td>
                            <td>
                                @if($member->vehicle)
                                    <div style="font-weight:500;">{{ $member->vehicle->license_plate }}</div>
                                    <div style="font-size:0.75rem; color:#94a3b8;">{{ $member->vehicle->warehouse?->name ?? '-' }}</div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $member->area ?? '—' }}</td>
                            <td>
                                @if($member->active)
                                    <span class="badge-success">Aktif</span>
                                @else
                                    <span class="badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:0.375rem;">
                                    <a href="{{ route('pasgar.anggota.show', $member) }}" class="btn-secondary btn-sm">👁 Detail</a>
                                    @can('edit_pasgar_anggota')
                                    <a href="{{ route('pasgar.anggota.edit', $member) }}" class="btn-warning btn-sm" style="padding:0.35rem 0.75rem; border-radius:6px; font-size:0.75rem;">✏️</a>
                                    @endcan
                                    @can('delete_pasgar_anggota')
                                    <form method="POST" action="{{ route('pasgar.anggota.destroy', $member) }}" onsubmit="return confirm('Hapus anggota ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger btn-sm" style="padding:0.35rem 0.75rem; border-radius:6px; font-size:0.75rem;">🗑</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:3rem; color:#94a3b8;">
                                <div style="font-size:2rem; margin-bottom:0.5rem;">👥</div>
                                <div>Belum ada anggota pasgar terdaftar.</div>
                                @can('create_pasgar_anggota')
                                <a href="{{ route('pasgar.anggota.create') }}" class="btn-primary btn-sm" style="margin-top:0.75rem; display:inline-flex;">+ Tambah Sekarang</a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($members->hasPages())
            <div style="padding:1rem 1.5rem; border-top:1px solid #f1f5f9;">
                {{ $members->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
