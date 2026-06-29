<x-hr-layout>
    <x-slot name="eyebrow">Manajemen Kepegawaian</x-slot>
    <x-slot name="title">Data Karyawan</x-slot>
    <x-slot name="icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
    </x-slot>
    <x-slot name="iconBg">bg-teal</x-slot>
    <x-slot name="description">Kelola profil, gaji pokok, tunjangan, dan akun login karyawan.</x-slot>
    <x-slot name="actions">
        @can('view_absensi')
            <a href="{{ route('sdm.absensi.index') }}" class="hr-btn hr-btn-secondary"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Absensi</a>
        @endcan
        @can('view_pengguna')
            <a href="{{ route('pengguna.index') }}" class="hr-btn hr-btn-secondary"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Akun Login</a>
        @endcan
        @can('view_karyawan')
            <a href="{{ route('sdm.karyawan.export', request()->query()) }}" class="hr-btn hr-btn-secondary"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>Export</a>
        @endcan
        @can('create_karyawan')
            <a href="{{ route('sdm.karyawan.create') }}" class="hr-btn hr-btn-success"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Tambah Karyawan</a>
        @endcan
    </x-slot>

    <style>
        .kr-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:.875rem;margin-bottom:1.25rem}
        @media(max-width:900px){.kr-stats{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:500px){.kr-stats{grid-template-columns:1fr}}
        .kr-stat{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:1.125rem 1.25rem;position:relative;overflow:hidden;transition:all .2s}
        .kr-stat:hover{border-color:#c7d2fe;box-shadow:0 4px 16px rgba(99,102,241,.08)}
        .kr-stat::before{content:'';position:absolute;top:0;left:0;bottom:0;width:4px}
        .kr-stat.total::before{background:linear-gradient(180deg,#6366f1,#4f46e5)}
        .kr-stat.with::before{background:linear-gradient(180deg,#10b981,#059669)}
        .kr-stat.without::before{background:linear-gradient(180deg,#f59e0b,#d97706)}
        .kr-stat.gaji::before{background:linear-gradient(180deg,#ec4899,#db2777)}
        .kr-stat-lbl{font-size:.6875rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:.375rem}
        .kr-stat-val{font-size:1.75rem;font-weight:800;letter-spacing:-.03em;line-height:1.1}
        .kr-stat-val.blue{color:#4f46e5}
        .kr-stat-val.green{color:#059669}
        .kr-stat-val.amber{color:#d97706}
        .kr-stat-val.pink{color:#db2777}
        .kr-stat-sub{font-size:.7rem;color:#9ca3af;margin-top:.25rem}

        .kr-card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden}
        .kr-filter{display:flex;flex-wrap:wrap;align-items:flex-end;gap:.75rem;padding:1rem 1.25rem;border-bottom:1px solid #f3f4f6;background:#fafbfc}
        .kr-fg{display:flex;flex-direction:column;gap:.3rem}
        .kr-fl{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af}
        .kr-fi{padding:.55rem .75rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.8125rem;font-family:inherit;outline:none;transition:all .2s;background:#fff;color:#1f2937}
        .kr-fi:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1)}
        .kr-search{position:relative;flex:1;min-width:180px}
        .kr-search svg{position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none}
        .kr-search input{padding-left:2.25rem}
        select.kr-fi{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right .6rem center;padding-right:2rem}
        .kr-btn{padding:.55rem 1.25rem;border-radius:10px;font-size:.8125rem;font-weight:700;cursor:pointer;transition:all .2s;font-family:inherit;border:none;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem}
        .kr-btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;box-shadow:0 2px 8px rgba(79,70,229,.25)}
        .kr-btn-primary:hover{box-shadow:0 4px 14px rgba(79,70,229,.35);transform:translateY(-1px)}
        .kr-btn-reset{padding:.55rem 1rem;border:1.5px solid #e5e7eb;background:#fff;color:#6b7280;border-radius:10px;font-size:.75rem;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem}
        .kr-btn-reset:hover{background:#f9fafb;border-color:#d1d5db}

        .kr-table-wrap{overflow-x:auto}
        .kr-table{width:100%;border-collapse:collapse;min-width:750px}
        .kr-table thead{background:linear-gradient(180deg,#f9fafb,#f3f4f6);border-bottom:2px solid #e5e7eb}
        .kr-table th{padding:.8rem 1rem;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;text-align:left;white-space:nowrap}
        .kr-table th.r{text-align:right}
        .kr-table td{padding:.85rem 1rem;border-bottom:1px solid #f9fafb;font-size:.8125rem;color:#374151;vertical-align:middle}
        .kr-table tbody tr{transition:background .15s}
        .kr-table tbody tr:hover{background:#fafbff}

        .kr-avatar{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.9rem;flex-shrink:0}
        .kr-avatar.f{background:#eef2ff;color:#4f46e5}
        .kr-avatar.m{background:#fce7f3;color:#db2777}
        .kr-name{font-weight:700;color:#111827}
        .kr-pos{font-size:.72rem;color:#9ca3af;margin-top:2px}
        .kr-role{display:inline-block;padding:.2rem .6rem;border-radius:6px;font-size:.68rem;font-weight:700;background:#eef2ff;color:#4f46e5}
        .kr-role.supervisor{background:#fce7f3;color:#db2777}
        .kr-role.sales_pasgar{background:#d1fae5;color:#065f46}
        .kr-role.pending{background:#fef3c7;color:#92400e}
        .kr-status{display:flex;align-items:center;gap:.4rem}
        .kr-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
        .kr-dot.on{background:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.15)}
        .kr-dot.off{background:#d1d5db}
        .kr-actions{display:flex;gap:.35rem}
        .kr-act{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .15s;text-decoration:none;border:none;background:transparent}
        .kr-act:hover{background:#f3f4f6}
        .kr-act.view{color:#6366f1}
        .kr-act.edit{color:#10b981}
        .kr-gaji{font-weight:700;font-family:'JetBrains Mono',monospace;font-size:.8rem;color:#111827}
        .kr-gaji-sub{font-size:.65rem;color:#9ca3af;margin-top:2px;font-weight:500}

        .kr-empty{padding:3rem 1.5rem;text-align:center}
        .kr-empty-icon{width:64px;height:64px;margin:0 auto 1rem;background:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center}
        .kr-empty-title{font-size:1rem;font-weight:700;color:#374151;margin-bottom:.25rem}
        .kr-empty-sub{font-size:.8125rem;color:#9ca3af}
        .kr-pag{padding:.85rem 1.25rem;border-top:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;font-size:.75rem;color:#9ca3af}
    </style>

    {{-- Stats --}}
    <div class="kr-stats">
        <div class="kr-stat total">
            <div class="kr-stat-lbl">Total Karyawan</div>
            <div class="kr-stat-val blue">{{ number_format($stats['totalFiltered']) }}</div>
            <div class="kr-stat-sub">Aktif & Non-aktif</div>
        </div>
        <div class="kr-stat with">
            <div class="kr-stat-lbl">Punya Akun</div>
            <div class="kr-stat-val green">{{ number_format($stats['withAccount']) }}</div>
            <div class="kr-stat-sub">Terhubung ke sistem</div>
        </div>
        <div class="kr-stat without">
            <div class="kr-stat-lbl">Belum Punya Akun</div>
            <div class="kr-stat-val amber">{{ number_format($stats['withoutAccount']) }}</div>
            <div class="kr-stat-sub">Perlu dibuatkan akun</div>
        </div>
        <div class="kr-stat gaji">
            <div class="kr-stat-lbl">Rata-rata Gaji</div>
            <div class="kr-stat-val pink">Rp {{ number_format($karyawan->avg('basic_salary') ?? 0, 0, ',', '.') }}</div>
            <div class="kr-stat-sub">Gaji pokok rata-rata</div>
        </div>
    </div>

    {{-- Filter & Table --}}
    <div class="kr-card">
        <div class="kr-filter">
            <div class="kr-fg kr-search">
                <label class="kr-fl">Cari</label>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="q" form="filterForm" value="{{ request('q') }}" placeholder="Nama, jabatan, email..." class="kr-fi" style="width:100%;">
            </div>
            <div class="kr-fg" style="min-width:130px">
                <label class="kr-fl">Role</label>
                <select name="role" form="filterForm" class="kr-fi" style="width:100%">
                    <option value="">Semua Role</option>
                    @foreach($roles as $r)
                        <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div class="kr-fg" style="min-width:130px">
                <label class="kr-fl">Akun</label>
                <select name="has_account" form="filterForm" class="kr-fi" style="width:100%">
                    <option value="">Semua</option>
                    <option value="yes" {{ request('has_account') === 'yes' ? 'selected' : '' }}>Punya Akun</option>
                    <option value="no" {{ request('has_account') === 'no' ? 'selected' : '' }}>Belum Punya</option>
                </select>
            </div>
            <div class="kr-fg" style="align-self:flex-end">
                <form id="filterForm" method="GET" action="{{ route('sdm.karyawan.index') }}" style="display:flex;gap:.4rem;align-items:center">
                    <button type="submit" class="kr-btn kr-btn-primary"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>Filter</button>
                    @if(request('q') || request('role') || request('has_account'))
                        <a href="{{ route('sdm.karyawan.index') }}" class="kr-btn-reset"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></a>
                    @endif
                </form>
            </div>
        </div>

        <div class="kr-table-wrap">
            <table class="kr-table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Jabatan</th>
                        <th>Role</th>
                        <th class="r">Gaji Pokok</th>
                        <th>Status</th>
                        <th style="width:80px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawan as $emp)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:.75rem">
                                    <div class="kr-avatar {{ in_array(substr($emp->name,0,1), ['A','I','U','E','O']) ? 'f' : 'm' }}">{{ strtoupper(substr($emp->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="kr-name">{{ $emp->name }}</div>
                                        <div style="font-size:.7rem;color:#9ca3af;font-family:monospace">{{ $emp->nik ?? 'ID: '.$emp->id }}</div>
                                        @if($emp->user)
                                            <div style="font-size:.65rem;color:#9ca3af;margin-top:1px">{{ $emp->user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:500">{{ $emp->position ?? '-' }}</div>
                                <div style="font-size:.7rem;color:#9ca3af">{{ $emp->join_date ? $emp->join_date->format('d M Y') : '-' }}</div>
                            </td>
                            <td>
                                @if($emp->user)
                                    <span class="kr-role {{ $emp->user->role }}">{{ $emp->user->role }}</span>
                                @else
                                    <span style="font-size:.75rem;color:#9ca3af">-</span>
                                @endif
                            </td>
                            <td class="r">
                                <div class="kr-gaji">Rp {{ number_format($emp->basic_salary ?? 0, 0, ',', '.') }}</div>
                                @if($emp->daily_allowance > 0)
                                    <div class="kr-gaji-sub">+ Rp {{ number_format($emp->daily_allowance, 0, ',', '.') }}/hari</div>
                                @endif
                            </td>
                            <td>
                                <div class="kr-status">
                                    <span class="kr-dot {{ $emp->active ? 'on' : 'off' }}"></span>
                                    <span style="font-size:.8125rem;font-weight:600;color:{{ $emp->active ? '#059669' : '#9ca3af' }}">{{ $emp->active ? 'Aktif' : 'Non-aktif' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="kr-actions">
                                    <a href="{{ route('sdm.karyawan.show', $emp) }}" class="kr-act view" title="Detail"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                                    @can('edit_karyawan')
                                        <a href="{{ route('sdm.karyawan.edit', $emp) }}" class="kr-act edit" title="Edit"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">
                            <div class="kr-empty">
                                <div class="kr-empty-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg></div>
                                <div class="kr-empty-title">Belum ada data karyawan</div>
                                <div class="kr-empty-sub">Tambah karyawan baru atau import dari akun yang sudah ada</div>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($karyawan->hasPages())
            <div class="kr-pag">
                <span>Menampilkan {{ $karyawan->firstItem() }}-{{ $karyawan->lastItem() }} dari {{ $karyawan->total() }} karyawan</span>
                {{ $karyawan->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-hr-layout>
