<x-app-layout>
    <x-slot name="header">Detail Karyawan</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── BREADCRUMB & QUICK ACTIONS ─── --}}
            <div class="tr-nav-bar">
                <a href="{{ route('sdm.karyawan.index') }}" class="tr-link-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Kembali ke Direktori
                </a>
                <div class="tr-action-group">
                    @if($karyawan->user)
                        <a href="{{ route('pengguna.edit', $karyawan->user) }}" class="tr-btn-icon" title="Pengaturan Akun">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        </a>
                    @endif
                    @can('edit_karyawan')
                        <a href="{{ route('sdm.karyawan.edit', $karyawan) }}" class="tr-btn-primary-sm">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Edit Profil
                        </a>
                    @endcan
                </div>
            </div>

            {{-- ─── MAIN INFO SECTION ─── --}}
            <div class="tr-main-card">
                <div class="tr-profile-box">
                    <div class="tr-avatar-wrapper">
                        <div class="tr-avatar-main">{{ strtoupper(substr($karyawan->name, 0, 1)) }}</div>
                        @if(($karyawan->active ?? 1) == 1)
                            <div class="tr-online-dot" title="Aktif"></div>
                        @endif
                    </div>
                    <div class="tr-user-details">
                        <h1 class="tr-user-name">{{ $karyawan->name }}</h1>
                        <p class="tr-user-pos">{{ $karyawan->position ?: 'Staf Karyawan' }}</p>
                    </div>
                </div>

                <div class="tr-meta-strip">
                    <div class="tr-meta-node">
                        <span class="node-label">Telepon</span>
                        <span class="node-val">{{ $karyawan->phone ?: '-' }}</span>
                    </div>
                    <div class="tr-meta-node">
                        <span class="node-label">Gaji Pokok</span>
                        <span class="node-val tr-mono">Rp{{ number_format($karyawan->basic_salary, 0, ',', '.') }}</span>
                    </div>
                    <div class="tr-meta-node">
                        <span class="node-label">Uang Makan</span>
                        <span class="node-val tr-mono">Rp{{ number_format($karyawan->daily_allowance, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- ─── MONTHLY REPORT SECTION ─── --}}
            <div class="tr-report-section">
                <div class="tr-section-title-bar">
                    <div class="tr-st-left">
                        <h3 class="tr-title-text">Ikhtisar Bulanan</h3>
                        <div class="tr-month-selector">
                            <form method="GET" action="{{ route('sdm.karyawan.show', $karyawan) }}">
                                <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()">
                            </form>
                        </div>
                    </div>
                    <span class="tr-pill-date">{{ $monthLabel }}</span>
                </div>

                @if($karyawan->user_id)
                    @php
                        $c = $stats['counts'] ?? [];
                        $totalHadir = (int)($c['present'] ?? 0) + (int)($c['late'] ?? 0);
                        $totalAlpha = (int)($c['absent'] ?? 0);
                    @endphp

                    {{-- GRID KPI --}}
                    <div class="tr-grid-kpi">
                        <div class="tr-card-kpi success">
                            <span class="kpi-label">Kehadiran</span>
                            <span class="kpi-value">{{ $totalHadir }} <small>Hari</small></span>
                        </div>
                        <div class="tr-card-kpi warning">
                            <span class="kpi-label">Terlambat</span>
                            <span class="kpi-value">{{ (int)($c['late'] ?? 0) }} <small>Hari</small></span>
                        </div>
                        <div class="tr-card-kpi danger">
                            <span class="kpi-label">Tanpa Keterangan</span>
                            <span class="kpi-value">{{ $totalAlpha }} <small>Hari</small></span>
                        </div>
                        <div class="tr-card-kpi primary">
                            <span class="kpi-label">Jam Kerja</span>
                            <span class="kpi-value">{{ number_format($stats['work_hours'] ?? 0, 1) }} <small>Jam</small></span>
                        </div>
                    </div>

                    <div class="tr-grid-content">
                        {{-- DATA AREA (KIRI) --}}
                        <div class="tr-content-left">
                            <div class="tr-card-inner">
                                <div class="tr-inner-header">Kalkulasi Gaji Bersih (THP)</div>
                                <div class="tr-inner-body">
                                    @if($payroll)
                                        <div class="tr-data-row"><span>Gaji Pokok</span><span class="tr-mono">Rp{{ number_format($karyawan->basic_salary, 0, ',', '.') }}</span></div>
                                        <div class="tr-data-row"><span>Uang Makan</span><span class="tr-mono">Rp{{ number_format($payroll->total_allowance, 0, ',', '.') }}</span></div>
                                        <div class="tr-data-row success"><span>Lembur & Bonus</span><span class="tr-mono">+Rp{{ number_format($payroll->incentive_amount + $payroll->overtime_pay, 0, ',', '.') }}</span></div>
                                        <div class="tr-data-row danger"><span>Total Potongan</span><span class="tr-mono">−Rp{{ number_format($payroll->total_deductions + $payroll->absence_deduction, 0, ',', '.') }}</span></div>
                                        <div class="tr-grand-total">
                                            <span class="label">Total Diterima</span>
                                            <span class="value">Rp{{ number_format($payroll->net_salary, 0, ',', '.') }}</span>
                                        </div>
                                    @else
                                        <div class="tr-empty-state-mini">Payroll bulan ini belum dikalkulasi.</div>
                                    @endif
                                </div>
                            </div>

                            {{-- CALENDAR --}}
                            <div class="tr-card-inner">
                                <div class="tr-inner-header">Peta Kehadiran</div>
                                <div class="tr-calendar-mini">
                                    @foreach($calendarDays as $d)
                                        @php
                                            $color = match($d['badge_class']) {
                                                'badge-success' => '#10b981', 'badge-danger' => '#ef4444', 
                                                'badge-warning' => '#f59e0b', default => '#e2e8f0'
                                            };
                                        @endphp
                                        <div class="tr-cal-node" title="{{ $d['status_text'] }}">
                                            <span class="cal-day">{{ $d['day'] }}</span>
                                            <div class="cal-status" style="background: {{ $color }}"></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- HISTORY AREA (KANAN) --}}
                        <div class="tr-content-right">
                            <div class="tr-card-inner tr-h-full">
                                <div class="tr-inner-header">Riwayat Gaji</div>
                                <div class="tr-history-stack">
                                    @forelse($payrollHistory as $p)
                                        <div class="tr-history-line">
                                            <span class="h-month">{{ \Carbon\Carbon::createFromDate($p->period_year, $p->period_month, 1)->translatedFormat('M Y') }}</span>
                                            <span class="h-val">Rp{{ number_format($p->net_salary, 0, ',', '.') }}</span>
                                        </div>
                                    @empty
                                        <div class="tr-empty-state-mini">Belum ada data riwayat.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="tr-no-account-warning">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <span>Karyawan ini belum terhubung dengan akun login sistem.</span>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-teal: #0d9488;
            --tr-bg-subtle: #f8fafc;
            --tr-border-color: #e2e8f0;
            --tr-text-dark: #0f172a;
            --tr-text-light: #64748b;
        }

        .tr-page-wrapper { background: #fdfdfd; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-dark); }
        .tr-page { max-width: 1000px; margin: 0 auto; padding: 1.5rem 1rem; }

        /* NAV & HEAD */
        .tr-nav-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .tr-link-back { display: flex; align-items: center; gap: 6px; text-decoration: none; color: var(--tr-text-light); font-size: 0.85rem; font-weight: 700; }
        .tr-link-back:hover { color: var(--tr-teal); }
        .tr-action-group { display: flex; gap: 10px; align-items: center; }

        /* PROFILE MAIN */
        .tr-main-card { background: #fff; border: 1px solid var(--tr-border-color); border-radius: 24px; padding: 2rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02); margin-bottom: 2.5rem; }
        .tr-profile-box { display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; }
        .tr-avatar-wrapper { position: relative; }
        .tr-avatar-main { width: 80px; height: 80px; background: var(--tr-teal); color: #fff; border-radius: 22px; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; }
        .tr-online-dot { position: absolute; bottom: -4px; right: -4px; width: 18px; height: 18px; background: #10b981; border: 3px solid #fff; border-radius: 50%; }
        .tr-user-name { font-size: 1.75rem; font-weight: 800; margin: 0; letter-spacing: -0.03em; }
        .tr-user-pos { color: var(--tr-text-light); font-weight: 600; margin-top: 2px; font-size: 1rem; }
        
        .tr-meta-strip { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.5rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem; }
        .tr-meta-node .node-label { display: block; font-size: 0.65rem; font-weight: 800; color: var(--tr-text-light); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .tr-meta-node .node-val { font-size: 0.95rem; font-weight: 700; }

        /* REPORT SECTION */
        .tr-section-title-bar { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--tr-text-dark); }
        .tr-sh-left { display: flex; align-items: center; gap: 1rem; }
        .tr-title-text { font-size: 1.25rem; font-weight: 800; margin: 0; }
        .tr-month-picker input { border: none; background: #f1f5f9; padding: 6px 12px; border-radius: 8px; font-weight: 700; color: var(--tr-teal); cursor: pointer; font-family: inherit; }
        .tr-pill-date { background: var(--tr-text-dark); color: #fff; padding: 4px 14px; border-radius: 99px; font-size: 0.75rem; font-weight: 700; }

        /* KPI */
        .tr-grid-kpi { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .tr-card-kpi { background: #fff; border: 1px solid var(--tr-border-color); border-radius: 16px; padding: 1.25rem; display: flex; flex-direction: column; }
        .kpi-label { font-size: 0.7rem; font-weight: 700; color: var(--tr-text-light); text-transform: uppercase; margin-bottom: 4px; }
        .kpi-value { font-size: 1.5rem; font-weight: 800; }
        .kpi-value small { font-size: 0.85rem; font-weight: 600; }

        /* CONTENT GRID */
        .tr-grid-content { display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; }
        .tr-card-inner { background: #fff; border: 1px solid var(--tr-border-color); border-radius: 16px; margin-bottom: 1.5rem; overflow: hidden; }
        .tr-inner-header { background: #fcfcfd; padding: 0.75rem 1.25rem; font-size: 0.8rem; font-weight: 800; border-bottom: 1px solid #f1f5f9; color: var(--tr-text-light); text-transform: uppercase; }
        .tr-inner-body { padding: 1.25rem; }

        .tr-data-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 0.9rem; font-weight: 500; border-bottom: 1px dashed #f1f5f9; }
        .tr-grand-total { margin-top: 1rem; padding: 1rem; background: var(--tr-bg-subtle); border-radius: 12px; display: flex; justify-content: space-between; align-items: center; }
        .tr-grand-total .label { font-weight: 800; font-size: 0.95rem; }
        .tr-grand-total .value { font-size: 1.25rem; font-weight: 800; font-family: monospace; }

        /* CALENDAR */
        .tr-calendar-mini { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; padding: 1.25rem; }
        .tr-cal-node { display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .tr-cal-node .cal-day { font-size: 0.7rem; font-weight: 700; color: var(--tr-text-light); }
        .tr-cal-node .cal-status { width: 100%; height: 6px; border-radius: 2px; }

        /* HISTORY */
        .tr-history-stack { display: flex; flex-direction: column; }
        .tr-history-line { display: flex; justify-content: space-between; padding: 12px 1.25rem; border-bottom: 1px solid #f8fafc; font-size: 0.85rem; }
        .tr-history-line .h-month { font-weight: 700; }
        .tr-history-line .h-val { font-weight: 800; font-family: monospace; }

        /* BUTTONS & UTILS */
        .tr-btn-primary-sm { background: var(--tr-teal); color: #fff; border: none; padding: 8px 16px; border-radius: 10px; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 8px; cursor: pointer; text-decoration: none; }
        .tr-btn-icon { width: 38px; height: 38px; border: 1px solid var(--tr-border-color); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--tr-text-light); text-decoration: none; transition: 0.2s; }
        .tr-btn-icon:hover { background: #f1f5f9; color: var(--tr-text-dark); }
        .tr-btn-text { text-decoration: none; color: var(--tr-teal); font-weight: 800; font-size: 0.85rem; margin-top: 12px; display: inline-block; }
        
        .tr-mono { font-family: monospace; font-weight: 700; }
        .tr-font-bold { font-weight: 800; }
        .text-success { color: #10b981; } .text-danger { color: #ef4444; } .text-warning { color: #f59e0b; } .text-indigo { color: #6366f1; }
        .tr-empty-state-mini { font-size: 0.85rem; color: var(--tr-text-light); text-align: center; padding: 1.5rem; font-style: italic; }
        .tr-no-account-warning { display: flex; align-items: center; gap: 10px; padding: 1rem; background: #fff7ed; border: 1px solid #fed7aa; border-radius: 12px; color: #9a3412; font-size: 0.85rem; font-weight: 600; }

        @media (max-width: 900px) {
            .tr-grid-content { grid-template-columns: 1fr; }
            .tr-grid-kpi { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 600px) {
            .tr-meta-strip { grid-template-columns: 1fr 1fr; }
            .tr-profile-box { flex-direction: column; text-align: center; }
            .tr-nav-bar { flex-direction: column; gap: 1rem; align-items: flex-start; }
            .tr-st-left { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
        }
    </style>
    @endpush
</x-app-layout>