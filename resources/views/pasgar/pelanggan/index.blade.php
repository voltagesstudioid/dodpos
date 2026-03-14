<x-app-layout>
    <x-slot name="header">Data Pelanggan Pasgar</x-slot>
    <div class="page-container">
        <div class="ph animate-in">
            <div class="ph-left">
                <div class="ph-icon blue">👥</div>
                <div>
                    <h1 class="ph-title">Data Pelanggan Pasgar</h1>
                    <p class="ph-subtitle">{{ $customers->total() }} pelanggan kanvas terdaftar</p>
                </div>
            </div>
            <div class="ph-actions">
                @can('create_pasgar_pelanggan')
                <a href="{{ route('pasgar.pelanggan.create') }}" class="btn-primary">＋ Tambah Pelanggan</a>
                @endcan
            </div>
        </div>

        <div class="panel animate-in animate-in-delay-1">
            <div class="filter-bar">
                <form method="GET" style="display:flex;gap:0.625rem;align-items:center;flex-wrap:wrap;">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="🔍  Cari nama pelanggan atau telepon..." class="form-input" style="flex:1;min-width:200px;max-width:300px;">
                    <button type="submit" class="btn-primary btn-sm">Filter</button>
                    @if(request('search'))<a href="{{ route('pasgar.pelanggan.index') }}" class="btn-secondary btn-sm">× Reset</a>@endif
                </form>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:44px">#</th>
                            <th>Nama Pelanggan / Toko</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th style="text-align:center;width:130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $i => $c)
                        <tr>
                            <td class="text-muted" style="font-size:0.75rem;">{{ $customers->firstItem() + $i }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.625rem;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#eef2ff,#e0e7ff);display:flex;align-items:center;justify-content:center;font-weight:700;color:#4f46e5;font-size:0.8125rem;flex-shrink:0;">
                                        {{ strtoupper(substr($c->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="td-main">{{ $c->name }}</div>
                                        @if($c->email)<div class="td-sub">{{ $c->email }}</div>@endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $c->phone ?: '—' }}</td>
                            <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $c->address }}">{{ $c->address ?: '—' }}</td>
                            <td>
                                <div style="display:flex;gap:0.25rem;justify-content:center;">
                                    @can('edit_pasgar_pelanggan')
                                    <a href="{{ route('pasgar.pelanggan.edit', $c->id) }}" class="btn-secondary btn-sm" title="Edit Data">✏️</a>
                                    @endcan
                                    @can('delete_pasgar_pelanggan')
                                    <form action="{{ route('pasgar.pelanggan.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Yakin hapus pelanggan ini?');">
                                        @csrf @method('DELETE')
                                        <button class="btn-secondary btn-sm drop-shadow" style="color:#ef4444;" title="Hapus Pelanggan">🗑️</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:3rem 1rem;">
                                <div style="font-size:3rem;margin-bottom:1rem;opacity:0.5;">📭</div>
                                <h3 style="margin:0;color:#1e293b;font-weight:600;">Data Kosong</h3>
                                <p style="color:#64748b;margin-top:0.5rem;font-size:0.875rem;">Belum ada data pelanggan tercatat.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding:1rem;">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
