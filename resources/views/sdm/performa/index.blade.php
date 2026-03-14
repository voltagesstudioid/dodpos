<x-app-layout>
    <x-slot name="header">SDM / HR - Performa</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER SECTION ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Analitik Karyawan</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                        </div>
                        Laporan Performa Bulanan
                    </h1>
                    <p class="tr-subtitle">Rekapitulasi performa berbasis data kehadiran, lembur, dan komponen slip gaji.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('sdm.penggajian.index', ['month' => $month]) }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        Penggajian
                    </a>
                    <a href="{{ route('sdm.absensi.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Absensi
                    </a>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success')) <div class="tr-alert tr-alert-success">✅ {{ session('success') }}</div> @endif
            @if(session('error')) <div class="tr-alert tr-alert-danger">❌ {{ session('error') }}</div> @endif

            {{-- ─── FILTER SECTION ─── --}}
            <div class="tr-card tr-filter-card">
                <form method="GET" action="{{ route('sdm.performa.index') }}" class="tr-filter-grid">
                    <div class="tr-form-group">
                        <label class="tr-label">Periode Laporan</label>
                        <input type="month" name="month" value="{{ $month }}" class="tr-input tr-input-date">
                    </div>
                    <div class="tr-filter-actions">
                        <button type="submit" class="tr-btn tr-btn-dark">Tampilkan Data</button>
                        <a href="{{ route('sdm.performa.index') }}" class="tr-btn tr-btn-outline">Reset</a>
                    </div>
                </form>
            </div>

            {{-- ─── MAIN REPORT CARD ─── --}}
            <div class="tr-card">
                <div class="tr-card-header">
                    <h2 class="tr-section-title">Rekap Periode: {{ $monthLabel }}</h2>
                    <p class="tr-card-subtitle">Data performa ditarik dari hasil kalkulasi penggajian yang sudah dijalankan.</p>
                </div>

                @if($payrolls->count() > 0)
                <div class="table-responsive">
                    <table class="tr-table-performa">
                        <thead>
                            <tr>
                                <th class="sticky-col">Karyawan</th>
                                <th class="c">Hadir</th>
                                <th class="c">Telat</th>
                                <th class="c text-danger">Alpha</th>
                                <th class="c text-danger">Miss</th>
                                <th class="c text-danger">Unpaid</th>
                                <th class="c">OT (m)</th>
                                <th class="r">Tunj. Makan</th>
                                <th class="r text-danger">Potongan</th>
                                <th class="r">Insentif</th>
                                <th class="r">Bonus</th>
                                <th class="r tr-bg-success-soft">Net Salary (THP)</th>
                                <th class="c">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $p)
                                @php $totalPotongan = ($p->total_deductions ?? 0) + ($p->absence_deduction ?? 0); @endphp
                                <tr>
                                    <td class="sticky-col tr-font-bold">
                                        <div class="tr-user-info">
                                            <span class="name">{{ $p->user?->name ?? '-' }}</span>
                                            <span class="role">{{ $p->user?->role ?? '' }}</span>
                                        </div>
                                    </td>
                                    <td class="c tr-font-bold">{{ (int)($p->present_days ?? 0) }}</td>
                                    <td class="c tr-font-bold {{ $p->late_days > 0 ? 'text-warning' : '' }}">{{ (int)($p->late_days ?? 0) }}</td>
                                    <td class="c tr-font-bold text-danger">{{ (int)($p->absent_days ?? 0) }}</td>
                                    <td class="c tr-font-bold text-danger">{{ (int)($p->missing_days ?? 0) }}</td>
                                    <td class="c tr-font-bold text-danger">{{ (int)($p->unpaid_leave_days ?? 0) }}</td>
                                    <td class="c tr-font-mono">{{ (int)($p->overtime_minutes ?? 0) }}</td>
                                    <td class="r tr-font-mono">Rp{{ number_format((float)($p->total_allowance ?? 0), 0, ',', '.') }}</td>
                                    <td class="r tr-font-mono text-danger">({{ number_format((float)$totalPotongan, 0, ',', '.') }})</td>
                                    <td class="r tr-font-mono">Rp{{ number_format((float)($p->incentive_amount ?? 0), 0, ',', '.') }}</td>
                                    <td class="r tr-font-mono">Rp{{ number_format((float)($p->performance_bonus ?? 0), 0, ',', '.') }}</td>
                                    <td class="r tr-font-mono tr-bg-success-soft text-success tr-font-black">
                                        Rp{{ number_format((float)($p->net_salary ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td class="c">
                                        <a href="{{ route('sdm.penggajian.print', $p) }}" target="_blank" class="tr-btn-icon" title="Cetak Slip">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="tr-empty-state">
                    <div class="tr-empty-icon">📈</div>
                    <h6>Belum ada data performa</h6>
                    <p>Silakan jalankan proses <strong>Hitung Gaji</strong> agar sistem dapat menarik data absensi dan mengkalkulasi performa periode ini.</p>
                    <a href="{{ route('sdm.penggajian.index', ['month' => $month]) }}" class="tr-btn tr-btn-primary">Buka Menu Penggajian</a>
                </div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-indigo: #4f46e5;
            --tr-indigo-light: #e0e7ff;
            --tr-success: #10b981;
            --tr-success-bg: #dcfce7;
            --tr-danger: #ef4444;
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
        .tr-section-title { font-size: 1rem; font-weight: 800; margin: 0; }
        .tr-card-subtitle { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }

        /* FILTER BAR */
        .tr-filter-card { padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
        .tr-filter-grid { display: flex; gap: 1.25rem; align-items: flex-end; flex-wrap: wrap; }
        .tr-form-group { display: flex; flex-direction: column; gap: 5px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); letter-spacing: 0.05em; }
        .tr-input { padding: 0.5rem 0.75rem; border: 1px solid var(--tr-border); border-radius: 8px; font-size: 0.85rem; background: #f8fafc; transition: 0.2s; font-family: inherit; }
        .tr-input-date { font-family: monospace; font-weight: 700; color: var(--tr-indigo); }
        .tr-input:focus { border-color: var(--tr-indigo); outline: none; background: #fff; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        .tr-filter-actions { display: flex; gap: 0.5rem; }

        /* TABLE */
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table-performa { width: 100%; border-collapse: collapse; min-width: 1300px; }
        .tr-table-performa thead th { background: #f8fafc; padding: 0.85rem 1rem; font-size: 0.65rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; border-bottom: 1px solid var(--tr-border); text-align: left; white-space: nowrap; }
        .tr-table-performa tbody td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; vertical-align: middle; white-space: nowrap; }
        .tr-table-performa tbody tr:hover { background: #fafafa; }
        
        .sticky-col { position: sticky; left: 0; background: #fff !important; z-index: 10; border-right: 2px solid var(--tr-border); }
        .tr-table-performa th.sticky-col { background: #f8fafc !important; }

        .tr-table-performa th.c, .tr-table-performa td.c { text-align: center; }
        .tr-table-performa th.r, .tr-table-performa td.r { text-align: right; }

        /* BUTTONS */
        .tr-btn { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1.1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; border: 1px solid transparent; cursor: pointer; transition: 0.2s; text-decoration: none; }
        .tr-btn-primary { background: var(--tr-indigo); color: #fff; }
        .tr-btn-outline { border-color: var(--tr-border); background: #fff; color: var(--tr-text-main); }
        .tr-btn-dark { background: var(--tr-text-main); color: #fff; }
        .tr-btn-icon { width: 30px; height: 30px; border-radius: 8px; border: 1px solid var(--tr-border); background: #fff; display: inline-flex; align-items: center; justify-content: center; color: var(--tr-text-muted); transition: 0.2s; text-decoration: none; }
        .tr-btn-icon:hover { border-color: var(--tr-indigo); color: var(--tr-indigo); background: var(--tr-indigo-light); }

        /* DATA STYLE */
        .tr-user-info { display: flex; flex-direction: column; line-height: 1.2; }
        .tr-user-info .name { font-weight: 800; color: var(--tr-text-main); font-size: 0.9rem; }
        .tr-user-info .role { font-size: 0.7rem; color: var(--tr-text-muted); font-weight: 600; text-transform: uppercase; }
        
        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-weight: 600; }
        .tr-font-bold { font-weight: 700; }
        .tr-font-black { font-weight: 900; }
        .tr-bg-success-soft { background: #ecfdf5 !important; }
        .text-success { color: var(--tr-success); }
        .text-danger { color: var(--tr-danger); }
        .text-warning { color: var(--tr-warning); }
        
        .tr-empty-state { text-align: center; padding: 4rem; color: var(--tr-text-muted); }
        .tr-empty-icon { font-size: 2.5rem; margin-bottom: 0.5rem; }

        .tr-alert { padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem; }
        .tr-alert-success { background: #dcfce7; color: #166534; }
        .tr-alert-danger { background: var(--tr-danger-bg); color: #991b1b; }

        @media (max-width: 768px) {
            .tr-header { flex-direction: column; }
            .tr-header-actions { width: 100%; justify-content: space-between; }
            .tr-filter-grid { flex-direction: column; align-items: stretch; }
        }
    </style>
    @endpush
</x-app-layout>