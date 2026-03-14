<x-app-layout>
    <x-slot name="header">SDM / HR - Rekap Bulanan</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER SECTION ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Laporan Kehadiran</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                        </div>
                        Rekap Absensi Bulanan
                    </h1>
                    <p class="tr-subtitle">Melihat akumulasi performa kehadiran karyawan dalam satu periode bulan.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('sdm.absensi.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Absensi Harian
                    </a>
                    <a href="{{ route('sdm.absensi.monthly.export', ['month' => $month, 'user_id' => request('user_id')]) }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Export CSV
                    </a>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success')) <div class="tr-alert tr-alert-success">✅ {{ session('success') }}</div> @endif
            @if(session('error')) <div class="tr-alert tr-alert-danger">❌ {{ session('error') }}</div> @endif

            {{-- ─── FILTER SECTION ─── --}}
            <div class="tr-card tr-filter-card">
                <form method="GET" action="{{ route('sdm.absensi.monthly') }}" class="tr-filter-grid">
                    <div class="tr-form-group">
                        <label class="tr-label">Periode Bulan</label>
                        <input type="month" name="month" value="{{ $month }}" class="tr-input tr-input-date">
                    </div>
                    <div class="tr-form-group">
                        <label class="tr-label">Filter Karyawan</label>
                        <div class="tr-select-wrapper">
                            <select name="user_id" class="tr-select">
                                <option value="">Semua Karyawan</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="tr-filter-actions">
                        <button type="submit" class="tr-btn tr-btn-dark">Tampilkan</button>
                        <a href="{{ route('sdm.absensi.monthly', ['month' => $month]) }}" class="tr-btn tr-btn-outline">Reset</a>
                    </div>
                    <div class="tr-workdays-badge">
                        <span class="label">Hari Kerja Bulan Ini</span>
                        <span class="value">{{ $workingDaysCount }}</span>
                    </div>
                </form>
            </div>

            {{-- ─── RECAP TABLE CARD ─── --}}
            <div class="tr-card">
                <div class="tr-card-header">
                    <h2 class="tr-section-title">Matriks Kehadiran: {{ $monthLabel }}</h2>
                    <p class="tr-card-subtitle">Data dihitung berdasarkan rekam finger dan pengajuan izin/cuti resmi.</p>
                </div>

                <div class="table-responsive">
                    <table class="tr-table-recap">
                        <thead>
                            <tr>
                                <th class="sticky-col">Karyawan</th>
                                <th class="c">W.D</th>
                                <th class="c text-success">Hadir</th>
                                <th class="c text-warning">Telat</th>
                                <th class="c">Izin</th>
                                <th class="c">Sakit</th>
                                <th class="c text-danger">Absent</th>
                                <th class="c">Cuti</th>
                                <th class="c text-danger">Unpaid</th>
                                <th class="c text-danger">Miss</th>
                                <th class="c tr-bg-danger-soft text-danger">Alpha Total</th>
                                <th class="c">Late (m)</th>
                                <th class="c">OT (m)</th>
                                <th class="r">Total Jam</th>
                                <th class="c">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $u)
                                @php
                                    $r = $recap[$u->id] ?? [];
                                    $c = $r['counts'] ?? [];
                                    $lt = $r['leave_type_counts'] ?? ['cuti' => 0, 'izin' => 0, 'sakit' => 0];
                                    $present = (int) ($c['present'] ?? 0);
                                    $late = (int) ($c['late'] ?? 0);
                                    $izin = (int) ($c['izin'] ?? 0);
                                    $sakit = (int) ($c['sakit'] ?? 0);
                                    $absent = (int) ($c['absent'] ?? 0);
                                    $missing = (int) ($r['missing_days'] ?? 0);
                                    $unpaidLeave = (int) ($r['unpaid_leave_days'] ?? 0);
                                    $alphaTotal = (int) ($r['alpha_total'] ?? 0);
                                    $lateMinutes = (int) ($r['late_minutes'] ?? 0);
                                    $overtimeMinutes = (int) ($r['overtime_minutes'] ?? 0);
                                    $workHours = (float) ($r['work_hours'] ?? 0);
                                @endphp
                                <tr>
                                    <td class="sticky-col tr-font-bold">{{ $u->name }}</td>
                                    <td class="c tr-text-muted">{{ (int) ($r['working_days'] ?? 0) }}</td>
                                    <td class="c tr-font-bold text-success">{{ $present + $late }}</td>
                                    <td class="c text-warning">{{ $late }}</td>
                                    <td class="c">{{ $izin }}</td>
                                    <td class="c">{{ $sakit }}</td>
                                    <td class="c text-danger">{{ $absent }}</td>
                                    <td class="c">{{ (int) ($lt['cuti'] ?? 0) }}</td>
                                    <td class="c text-danger">{{ $unpaidLeave }}</td>
                                    <td class="c text-danger">{{ $missing }}</td>
                                    <td class="c tr-bg-danger-soft text-danger tr-font-bold">{{ $alphaTotal }}</td>
                                    <td class="c tr-font-mono">{{ number_format($lateMinutes) }}</td>
                                    <td class="c tr-font-mono">{{ number_format($overtimeMinutes) }}</td>
                                    <td class="r tr-font-mono">{{ number_format($workHours, 1) }} Jm</td>
                                    <td class="c">
                                        @if($u->employee)
                                            <a href="{{ route('sdm.karyawan.show', $u->employee) }}" class="tr-action-btn-circle" title="Lihat Profil">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </a>
                                        @else - @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="15" class="tr-empty-state">Data tidak ditemukan untuk periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tr-footer-hint">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                <span><strong>Alpha Total</strong> dihitung dari hari kerja tanpa rekam absen (Missing) + ketidakhadiran tanpa keterangan (Absent).</span>
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-indigo: #4f46e5;
            --tr-indigo-light: #e0e7ff;
            --tr-success: #10b981;
            --tr-warning: #f59e0b;
            --tr-danger: #ef4444;
            --tr-danger-bg: #fee2e2;
            --tr-border: #e2e8f0;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
        }

        .tr-page-wrapper { background-color: #f8fafc; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); padding-bottom: 4rem; }
        .tr-page { max-width: 1400px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* HEADER */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-indigo); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin-top: 4px; }
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* CARDS */
        .tr-card { background: #fff; border: 1px solid var(--tr-border); border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fff; }
        .tr-card-subtitle { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }

        /* FILTER BAR */
        .tr-filter-card { padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
        .tr-filter-grid { display: flex; gap: 1.25rem; align-items: flex-end; flex-wrap: wrap; }
        .tr-form-group { display: flex; flex-direction: column; gap: 5px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); letter-spacing: 0.05em; }
        .tr-input, .tr-select { padding: 0.5rem 0.75rem; border: 1px solid var(--tr-border); border-radius: 8px; font-size: 0.85rem; background: #f8fafc; height: 40px; min-width: 180px; }
        .tr-input-date { font-family: monospace; font-weight: 700; }
        .tr-filter-actions { display: flex; gap: 0.5rem; }

        .tr-workdays-badge { margin-left: auto; background: var(--tr-indigo-light); padding: 0.5rem 1rem; border-radius: 10px; display: flex; flex-direction: column; align-items: center; border: 1px solid var(--tr-indigo); }
        .tr-workdays-badge .label { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; color: var(--tr-indigo); }
        .tr-workdays-badge .value { font-size: 1.25rem; font-weight: 800; color: var(--tr-indigo); line-height: 1; }

        /* TABLE */
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table-recap { width: 100%; border-collapse: collapse; min-width: 1200px; }
        .tr-table-recap thead th { background: #f8fafc; padding: 0.75rem 1rem; font-size: 0.7rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; border-bottom: 1px solid var(--tr-border); text-align: left; white-space: nowrap; }
        .tr-table-recap tbody td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; vertical-align: middle; white-space: nowrap; }
        .tr-table-recap tbody tr:hover { background: #fafafa; }
        
        .sticky-col { position: sticky; left: 0; background: #fff !important; z-index: 10; border-right: 2px solid var(--tr-border); }
        .tr-table-recap th.sticky-col { background: #f8fafc !important; }

        .tr-table-recap th.c, .tr-table-recap td.c { text-align: center; }
        .tr-table-recap th.r, .tr-table-recap td.r { text-align: right; }

        /* BADGES & BUTTONS */
        .tr-btn { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; border: 1px solid transparent; cursor: pointer; transition: 0.2s; text-decoration: none; }
        .tr-btn-dark { background: var(--tr-text-main); color: #fff; }
        .tr-btn-outline { border-color: var(--tr-border); background: #fff; color: var(--tr-text-main); }
        .tr-btn-outline:hover { background: #f8fafc; }

        .tr-action-btn-circle { width: 30px; height: 30px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--tr-border); background: #fff; color: var(--tr-text-muted); transition: 0.2s; text-decoration: none; }
        .tr-action-btn-circle:hover { color: var(--tr-indigo); border-color: var(--tr-indigo); background: var(--tr-indigo-light); }

        .tr-alert { padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem; }
        .tr-alert-success { background: #dcfce7; color: #166534; }
        .tr-alert-danger { background: var(--tr-danger-bg); color: #991b1b; }

        /* UTILS */
        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-weight: 600; }
        .tr-font-bold { font-weight: 800; }
        .tr-bg-danger-soft { background: #fff5f5 !important; }
        .text-success { color: var(--tr-success); }
        .text-danger { color: var(--tr-danger); }
        .text-warning { color: var(--tr-warning); }
        .tr-text-muted { color: var(--tr-text-muted); }
        .tr-section-title { font-size: 1rem; font-weight: 800; margin: 0; }
        
        .tr-empty-state { text-align: center; padding: 3rem; color: var(--tr-text-muted); font-style: italic; }
        .tr-footer-hint { display: flex; align-items: center; gap: 8px; margin-top: 1rem; font-size: 0.75rem; color: var(--tr-text-muted); }

        .tr-select-wrapper { position: relative; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        .tr-select { appearance: none; padding-right: 2.5rem; cursor: pointer; }

        @media (max-width: 768px) {
            .tr-header { flex-direction: column; }
            .tr-header-actions { width: 100%; justify-content: space-between; }
            .tr-filter-grid { flex-direction: column; align-items: stretch; }
            .tr-filter-actions { display: grid; grid-template-columns: 1fr 1fr; }
            .tr-workdays-badge { margin-left: 0; width: 100%; }
        }
    </style>
    @endpush
</x-app-layout>