<x-hr-layout>
    <x-slot name="eyebrow">Manajemen Kepegawaian</x-slot>
    <x-slot name="title">Data Karyawan</x-slot>
    <x-slot name="icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
    </x-slot>
    <x-slot name="iconBg">bg-teal</x-slot>
    <x-slot name="description">Kelola profil, jabatan, gaji pokok, dan akun login karyawan.</x-slot>
    
    <x-slot name="actions">
        @can('view_absensi')
            <a href="{{ route('sdm.absensi.index') }}" class="hr-btn hr-btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                Absensi
            </a>
        @endcan
        @can('view_pengguna')
            <a href="{{ route('pengguna.index') }}" class="hr-btn hr-btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Akun Login
            </a>
        @endcan
        @can('view_karyawan')
            <a href="{{ route('sdm.karyawan.export', request()->query()) }}" class="hr-btn hr-btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Export
            </a>
        @endcan
        @can('create_karyawan')
            <a href="{{ route('sdm.karyawan.create') }}" class="hr-btn hr-btn-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Tambah Karyawan
            </a>
        @endcan
    </x-slot>

    {{-- Stats Overview --}}
    <div class="hr-stats">
        <div class="hr-stat">
            <div class="hr-stat-label">Total Karyawan</div>
            <div class="hr-stat-value">{{ $karyawan->total() }}</div>
            <div class="hr-stat-change">Aktif & Non-aktif</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Dengan Akun Login</div>
            <div class="hr-stat-value">{{ $karyawan->where('user_id', '!=', null)->count() }}</div>
            <div class="hr-stat-change positive">Terhubung sistem</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Belum Punya Akun</div>
            <div class="hr-stat-value">{{ $karyawan->where('user_id', null)->count() }}</div>
            <div class="hr-stat-change negative">Perlu dibuatkan</div>
        </div>
    </div>

    {{-- Filter & Table Card --}}
    <div class="hr-card">
        {{-- Filter Bar --}}
        <div class="hr-filter">
            <div class="hr-filter-group">
                <label class="hr-filter-label">Cari Karyawan</label>
                <div class="hr-search-wrapper">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="q" form="filter-form" value="{{ request('q') }}" placeholder="Nama, email, atau ID..." class="hr-input">
                </div>
            </div>
            <div class="hr-filter-group sm">
                <label class="hr-filter-label">Role</label>
                <select name="role" form="filter-form" class="hr-select">
                    <option value="">Semua</option>
                    @foreach($roles as $r)
                        <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div class="hr-filter-group sm">
                <label class="hr-filter-label">Status Akun</label>
                <select name="has_account" form="filter-form" class="hr-select">
                    <option value="">Semua</option>
                    <option value="yes" {{ request('has_account') === 'yes' ? 'selected' : '' }}>Punya Akun</option>
                    <option value="no" {{ request('has_account') === 'no' ? 'selected' : '' }}>Belum Punya</option>
                </select>
            </div>
            <div class="hr-filter-group sm" style="flex: 0;">
                <label class="hr-filter-label">&nbsp;</label>
                <form id="filter-form" method="GET" action="{{ route('sdm.karyawan.index') }}">
                    <button type="submit" class="hr-btn hr-btn-primary">Filter</button>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="hr-table-wrapper">
            <table class="hr-table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Jabatan</th>
                        <th>Kontak</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawan as $employee)
                        <tr>
                            <td>
                                <div class="hr-user">
                                    <div class="hr-avatar">{{ strtoupper(substr($employee->name, 0, 1)) }}</div>
                                    <div class="hr-user-info">
                                        <div class="hr-user-name">{{ $employee->name }}</div>
                                        <div class="hr-user-meta">ID: {{ $employee->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 500;">{{ $employee->position ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: #6b7280;">Masuk: {{ $employee->join_date?->format('d M Y') ?? '-' }}</div>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem;">{{ $employee->phone ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: #6b7280;">{{ $employee->user?->email ?? 'Belum ada akun' }}</div>
                            </td>
                            <td>
                                @if($employee->user)
                                    <span class="hr-badge hr-badge-blue">{{ $employee->user->role }}</span>
                                @else
                                    <span class="hr-badge hr-badge-gray">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="hr-status">
                                    <span class="hr-status-dot {{ $employee->active ? 'active' : 'inactive' }}"></span>
                                    <span style="font-size: 0.875rem;">{{ $employee->active ? 'Aktif' : 'Non-aktif' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="hr-actions">
                                    <a href="{{ route('sdm.karyawan.show', $employee) }}" class="hr-action" title="Detail">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </a>
                                    @can('edit_karyawan')
                                        <a href="{{ route('sdm.karyawan.edit', $employee) }}" class="hr-action" title="Edit">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="hr-empty">
                                    <div class="hr-empty-icon">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                        </svg>
                                    </div>
                                    <div class="hr-empty-title">Belum ada karyawan</div>
                                    <div class="hr-empty-text">Tambah karyawan baru untuk memulai</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($karyawan->hasPages())
            <div style="padding: 1rem 1.5rem; border-top: 1px solid #f3f4f6;">
                {{ $karyawan->links() }}
            </div>
        @endif
    </div>
</x-hr-layout>