<x-app-layout>
    <x-slot name="header">HR & Payroll</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Kepegawaian</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-teal">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        Data Karyawan
                    </h1>
                    <p class="tr-subtitle">Kelola profil, jabatan, gaji pokok, dan akun login karyawan.</p>
                </div>
                
                <div class="tr-header-actions">
                    @can('view_absensi')
                    <a href="{{ route('sdm.absensi.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Absensi
                    </a>
                    @endcan
                    @can('view_pengguna')
                    <a href="{{ route('pengguna.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        Akun Login
                    </a>
                    @endcan
                    @can('view_karyawan')
                    <a href="{{ route('sdm.karyawan.export', request()->query()) }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Export
                    </a>
                    @endcan
                    @can('create_karyawan')
                    <a href="{{ route('sdm.karyawan.create') }}" class="tr-btn tr-btn-teal">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                        Tambah
                    </a>
                    @endcan
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success'))
                <div class="tr-alert tr-alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error')) 
                <div class="tr-alert tr-alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    {{ session('error') }}
                </div> 
            @endif

            {{-- ─── CARD: DIRECTORY & FILTERS ─── --}}
            <div class="tr-card">
                
                {{-- Filter Bar --}}
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('sdm.karyawan.index') }}" class="tr-filter-grid">
                        <div class="tr-form-group">
                            <label class="tr-label">Pencarian</label>
                            <div class="tr-search">
                                <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau ID...">
                            </div>
                        </div>

                        <div class="tr-form-group">
                            <label class="tr-label">Role Akses</label>
                            <div class="tr-select-wrapper">
                                <select name="role" class="tr-select">
                                    <option value="">Semua Role</option>
                                    @foreach($roles as $r)
                                        <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ $r }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="tr-form-group">
                            <label class="tr-label">Status Akun Login</label>
                            <div class="tr-select-wrapper">
                                <select name="has_account" class="tr-select">
                                    <option value="">Semua Karyawan</option>
                                    <option value="yes" {{ request('has_account') === 'yes' ? 'selected' : '' }}>Memiliki Akun</option>
                                    <option value="no" {{ request('has_account') === 'no' ? 'selected' : '' }}>Tidak Punya Akun</option>
                                </select>
                            </div>
                        </div>

                        <div class="tr-filter-actions">
                            <button type="submit" class="tr-btn tr-btn-dark">Terapkan Filter</button>
                            @if(request()->filled('q') || request()->filled('role') || request()->filled('has_account'))
                                <a href="{{ route('sdm.karyawan.index') }}" class="tr-btn tr-btn-danger-outline">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Profil Karyawan</th>
                                <th>Jabatan & Kontak</th>
                                <th>Akses Akun (Login)</th>
                                <th>Role Sistem</th>
                                <th>Status Kerja</th>
                                <th class="r" style="width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($karyawan as $u)
                                <tr>
                                    <td>
                                        <div class="tr-profile-cell">
                                            <div class="tr-avatar">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <a href="{{ route('sdm.karyawan.show', $u) }}" class="tr-user-name">{{ $u->name }}</a>
                                                <div class="tr-user-id">ID: KRY-{{ str_pad($u->id, 4, '0', STR_PAD_LEFT) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="tr-position">{{ $u->position ?: 'Staf' }}</div>
                                        <div class="tr-contact">{{ $u->phone ?: 'Tidak ada kontak' }}</div>
                                    </td>
                                    <td>
                                        @if($u->user)
                                            <div class="tr-account-info">
                                                <span class="tr-badge tr-badge-success">Terkoneksi</span>
                                                <span class="tr-email">{{ $u->user->email }}</span>
                                            </div>
                                        @else
                                            <span class="tr-badge tr-badge-danger">Tanpa Akun</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($u->user)
                                            <span class="tr-role-badge">{{ $u->user->role }}</span>
                                        @else
                                            <span class="tr-text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(($u->active ?? 1) == 1)
                                            <span class="tr-status-dot active"></span> Aktif
                                        @else
                                            <span class="tr-status-dot inactive"></span> Nonaktif
                                        @endif
                                    </td>
                                    <td class="r">
                                        <div class="tr-actions-group">
                                            <a href="{{ route('sdm.karyawan.show', $u) }}" class="tr-action-btn view" title="Lihat Profil">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </a>
                                            
                                            @can('edit_karyawan')
                                            <a href="{{ route('sdm.karyawan.edit', $u) }}" class="tr-action-btn edit" title="Edit Biodata">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            @endcan

                                            @if($u->user)
                                                @can('edit_pengguna')
                                                <a href="{{ route('pengguna.edit', $u->user) }}" class="tr-action-btn account" title="Pengaturan Akun">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                                </a>
                                                @endcan
                                            @else
                                                @can('create_pengguna')
                                                <a href="{{ route('pengguna.create', ['name' => $u->name]) }}" class="tr-action-btn add-account" title="Buatkan Akun Login">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                                </a>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                            </div>
                                            <h6>Tidak ada data karyawan</h6>
                                            <p>Sistem ini belum memiliki data karyawan atau tidak ada yang sesuai dengan filter pencarian Anda.</p>
                                            @can('create_karyawan')
                                                <div style="margin-top: 1rem; display:flex; gap:0.5rem; justify-content:center;">
                                                    <a href="{{ route('sdm.karyawan.create') }}" class="tr-btn tr-btn-teal">Tambah Karyawan</a>
                                                </div>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($karyawan->hasPages())
                    <div class="tr-pagination">
                        {{ $karyawan->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            
            --tr-teal: #0d9488; /* Teal Accent for HR */
            --tr-teal-hover: #0f766e;
            --tr-teal-light: #ccfbf1;
            
            --tr-primary: #3b82f6;
            --tr-success: #10b981;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            --tr-danger: #ef4444;
            --tr-danger-bg: #fee2e2;
            --tr-danger-text: #991b1b;
            
            --tr-radius-lg: 14px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --tr-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1280px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-teal); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 900; color: var(--tr-text-main); margin: 0 0 0.4rem 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-teal { background: var(--tr-teal-light); color: var(--tr-teal); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0; line-height: 1.4; }
        
        .tr-header-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: center; gap: 10px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600; line-height: 1.4; border: 1px solid transparent; }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: #fecaca; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: #bbf7d0; }

        /* ── CARD & FILTER ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-filter-bar { padding: 1rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        
        .tr-filter-grid { display: grid; grid-template-columns: 1fr 180px 180px auto; gap: 1rem; align-items: flex-end; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; }
        
        .tr-search { display: flex; align-items: center; gap: 8px; background: var(--tr-bg); border-radius: 6px; padding: 0.5rem 0.85rem; border: 1px solid var(--tr-border); transition: border-color 0.2s; height: 38px; }
        .tr-search:focus-within { border-color: var(--tr-teal); background: #ffffff; }
        .tr-search-icon { color: var(--tr-text-light); flex-shrink: 0; }
        .tr-search input { border: none; background: transparent; font-size: 0.85rem; font-family: inherit; color: var(--tr-text-main); outline: none; width: 100%; }
        
        .tr-select-wrapper { position: relative; }
        .tr-select { width: 100%; padding: 0.5rem 0.85rem; padding-right: 2rem; border: 1px solid var(--tr-border); border-radius: 6px; font-family: inherit; font-size: 0.85rem; color: var(--tr-text-main); background: var(--tr-bg); appearance: none; outline: none; transition: border-color 0.2s; cursor: pointer; height: 38px; }
        .tr-select:focus { border-color: var(--tr-teal); background: #ffffff; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }

        .tr-filter-actions { display: flex; gap: 6px; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 38px; }
        
        .tr-btn-teal { background: var(--tr-teal); color: #ffffff; box-shadow: 0 2px 4px rgba(13, 148, 136, 0.2); }
        .tr-btn-teal:hover { background: var(--tr-teal-hover); transform: translateY(-1px); }
        
        .tr-btn-dark { background: var(--tr-text-main); color: #ffffff; border-color: var(--tr-text-main); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tr-btn-dark:hover { background: #000000; transform: translateY(-1px); }
        
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); background: #f8fafc; }
        
        .tr-btn-danger-outline { border-color: var(--tr-danger-border); color: var(--tr-danger-text); background: transparent; }
        .tr-btn-danger-outline:hover { background: var(--tr-danger-bg); }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 900px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { padding: 1.1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* ── CELL FORMATTING ── */
        .tr-profile-cell { display: flex; align-items: center; gap: 12px; }
        .tr-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--tr-teal-light); color: var(--tr-teal); font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; border: 1px solid rgba(13, 148, 136, 0.2); }
        .tr-user-name { font-weight: 800; color: var(--tr-text-main); font-size: 0.9rem; text-decoration: none; }
        .tr-user-name:hover { text-decoration: underline; color: var(--tr-teal); }
        .tr-user-id { font-size: 0.7rem; color: var(--tr-text-muted); font-family: monospace; font-weight: 600; margin-top: 2px; }
        
        .tr-position { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-contact { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }

        .tr-account-info { display: flex; flex-direction: column; align-items: flex-start; gap: 4px; }
        .tr-email { font-size: 0.75rem; color: var(--tr-text-muted); }

        .tr-role-badge { display: inline-block; background: var(--tr-bg); border: 1px solid var(--tr-border); color: var(--tr-text-main); padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }

        .tr-status-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 4px; }
        .tr-status-dot.active { background: var(--tr-success); box-shadow: 0 0 0 2px var(--tr-success-bg); }
        .tr-status-dot.inactive { background: var(--tr-text-light); }

        /* Badges */
        .tr-badge { display: inline-flex; align-items: center; padding: 0.2rem 0.55rem; border-radius: 999px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-badge-success { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-badge-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); }

        /* Action Buttons */
        .tr-actions-group { display: flex; gap: 6px; justify-content: flex-end; }
        .tr-action-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; border: 1px solid transparent; background: var(--tr-bg); color: var(--tr-text-muted); transition: all 0.2s; cursor: pointer; text-decoration: none; }
        .tr-action-btn.view:hover { background: var(--tr-teal-light); color: var(--tr-teal); border-color: #99f6e4; }
        .tr-action-btn.edit:hover { background: var(--tr-border-light); color: var(--tr-text-main); border-color: var(--tr-border); }
        .tr-action-btn.account:hover { background: var(--tr-primary-light); color: var(--tr-primary); border-color: #bfdbfe; }
        .tr-action-btn.add-account { background: var(--tr-primary-light); color: var(--tr-primary); border-color: #bfdbfe; }
        .tr-action-btn.add-account:hover { background: var(--tr-primary); color: #fff; }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-teal-light); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-teal); }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* ── PAGINATION ── */
        .tr-pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--tr-border-light); background: #ffffff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 992px) {
            .tr-filter-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-header-actions { width: 100%; }
            .tr-header-actions form { width: 100%; }
            .tr-header-actions .tr-btn { width: 100%; justify-content: center; }
            .tr-filter-grid { grid-template-columns: 1fr; gap: 1rem; align-items: stretch; }
            .tr-filter-actions { flex-direction: row; }
            .tr-filter-actions .tr-btn { flex: 1; }
        }
    </style>
    @endpush
</x-app-layout>