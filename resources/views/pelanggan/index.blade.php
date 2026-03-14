<x-app-layout>
    <x-slot name="header">Data Pelanggan</x-slot>
    <div class="page-container">
        <div class="ph animate-in">
            <div class="ph-left">
                <div class="ph-icon blue">👥</div>
                <div>
                    <h1 class="ph-title">Data Pelanggan</h1>
                    <p class="ph-subtitle">{{ $customers->total() }} pelanggan terdaftar</p>
                </div>
            </div>
            <div class="ph-actions">
                @can('create_pelanggan')
                <a href="{{ route('pelanggan.create') }}" class="btn-primary">＋ Tambah Pelanggan</a>
                @endcan
            </div>
        </div>

        <div class="panel animate-in animate-in-delay-1">
            <div class="filter-bar">
                <form method="GET" style="display:flex;gap:0.625rem;align-items:center;flex-wrap:wrap;">
                    <select name="category" class="form-input" style="width:160px;">
                        <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="pos" {{ request('category') == 'pos' || request('category') == null ? 'selected' : '' }}>Toko / POS</option>
                        <option value="pasgar" {{ request('category') == 'pasgar' ? 'selected' : '' }}>Pasukan Garuda</option>
                        <option value="minyak" {{ request('category') == 'minyak' ? 'selected' : '' }}>Minyak Api</option>
                    </select>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="🔍  Cari nama atau telepon..." class="form-input" style="flex:1;min-width:200px;max-width:300px;">
                    <button type="submit" class="btn-primary btn-sm">Filter</button>
                    @if(request('search') || (request('category') && request('category') !== 'pos'))<a href="{{ route('pelanggan.index') }}" class="btn-secondary btn-sm">× Reset</a>@endif
                </form>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:44px">#</th>
                            <th>Pelanggan</th>
                            <th>Telepon</th>
                            <th style="text-align:right;">Limit Kredit</th>
                            <th style="text-align:right;">Hutang Aktif</th>
                            <th style="text-align:center;">Status</th>
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
                                        <div class="td-main" style="display:flex;align-items:center;gap:0.5rem;">
                                            {{ $c->name }}
                                            @if($c->category === 'pasgar')
                                                <span style="font-size:0.65rem;font-weight:600;padding:2px 6px;background:#fef08a;color:#854d0e;border-radius:4px;">PASGAR</span>
                                            @elseif($c->category === 'minyak')
                                                <span style="font-size:0.65rem;font-weight:600;padding:2px 6px;background:#fed7aa;color:#9a3412;border-radius:4px;">MINYAK</span>
                                            @else
                                                <span style="font-size:0.65rem;font-weight:600;padding:2px 6px;background:#e0e7ff;color:#4338ca;border-radius:4px;">TOKO / POS</span>
                                            @endif
                                        </div>
                                        @if($c->email)<div class="td-sub">{{ $c->email }}</div>@endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $c->phone ?: '—' }}</td>
                            <td style="text-align:right;color:#64748b;">Rp {{ number_format($c->credit_limit, 0, ',', '.') }}</td>
                            <td style="text-align:right;">
                                @if($c->current_debt > 0)
                                    <span style="font-weight:700;color:#ef4444;">Rp {{ number_format($c->current_debt, 0, ',', '.') }}</span>
                                @else
                                    <span style="color:#059669;font-weight:600;">Lunas</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <span class="badge {{ $c->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $c->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <div class="act-grp" style="justify-content:center;">
                                    <a href="{{ route('pelanggan.show', $c) }}" class="act-btn act-btn-view">👁 Detail</a>
                                    @can('edit_pelanggan')
                                    <a href="{{ route('pelanggan.edit', $c) }}" class="act-btn act-btn-edit">✏</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7">
                            <div class="empty-state">
                                <span class="empty-state-icon">👥</span>
                                <div class="empty-state-title">Belum ada pelanggan</div>
                                @can('create_pelanggan')
                                <a href="{{ route('pelanggan.create') }}" class="btn-primary btn-sm">＋ Tambah Pelanggan</a>
                                @endcan
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($customers->hasPages())<div>{{ $customers->links() }}</div>@endif
        </div>
    </div>
</x-app-layout>
