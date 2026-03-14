<x-app-layout>
    <x-slot name="header">SDM / HR - Penggajian</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── TOP ACTION BAR ─── --}}
            <div class="tr-top-bar">
                <div class="tr-title-area">
                    <h1 class="tr-main-title">Penggajian Karyawan</h1>
                    <p class="tr-sub-title">Kalkulasi otomatis berdasarkan parameter kehadiran dan tunjangan.</p>
                </div>
                @can('create_penggajian')
                    <form method="POST" action="{{ route('sdm.penggajian.generate') }}" class="tr-m-0">
                        @csrf
                        <input type="hidden" name="month" value="{{ $month }}">
                        <button type="submit" class="tr-btn-primary-lg js-btn-load">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                            Generate Payroll {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}
                        </button>
                    </form>
                @endcan
            </div>

            {{-- ALERTS --}}
            @if(session('success') || session('error'))
                <div class="tr-alert-stack">
                    @if(session('success')) <div class="tr-alert tr-alert-success"><span>{{ session('success') }}</span></div> @endif
                    @if(session('error')) <div class="tr-alert tr-alert-danger"><span>{{ session('error') }}</span></div> @endif
                </div>
            @endif

            {{-- ─── CONTROL CARD (FILTER) ─── --}}
            <div class="tr-card-minimal">
                <form method="GET" action="{{ route('sdm.penggajian.index') }}" class="tr-filter-flex">
                    <div class="tr-input-group">
                        <label>Periode Laporan</label>
                        <input type="month" name="month" value="{{ $month }}" class="tr-input-field">
                    </div>
                    <button type="submit" class="tr-btn-dark">Muat Data</button>
                </form>
            </div>

            {{-- ─── DATA TABLE AREA ─── --}}
            <div class="tr-card-main">
                <div class="tr-card-header">
                    <div class="tr-ch-text">
                        <h3>Daftar Slip Gaji</h3>
                        <span class="tr-tag-info">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</span>
                    </div>
                </div>

                <div class="tr-table-responsive">
                    <table class="tr-table-payroll">
                        <thead>
                            <tr>
                                <th>Profil Karyawan</th>
                                <th class="c">Status</th>
                                <th class="c">Kehadiran</th>
                                <th class="r">Gaji Pokok</th>
                                <th class="r">Tunjangan</th>
                                <th class="r">Potongan</th>
                                <th class="r">Gaji Bersih (THP)</th>
                                <th class="c">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payrolls as $pr)
                                <tr class="{{ $pr->locked_at ? 'is-locked' : '' }}">
                                    <td>
                                        <div class="tr-user-cell">
                                            <div class="tr-avatar-sm">{{ strtoupper(substr($pr->user->name, 0, 1)) }}</div>
                                            <div class="tr-user-meta">
                                                <span class="name">{{ $pr->user->name ?? '-' }}</span>
                                                <span class="role">{{ $pr->user->role ?? '' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="c">
                                        @if($pr->locked_at)
                                            <span class="tr-status-pill locked">Final</span>
                                        @else
                                            <span class="tr-status-pill open">Draft</span>
                                        @endif
                                    </td>
                                    <td class="c">
                                        <div class="tr-att-val">{{ $pr->total_attendance }} <small>Hari</small></div>
                                    </td>
                                    <td class="r tr-mono">Rp{{ number_format($pr->total_basic_salary, 0, ',', '.') }}</td>
                                    <td class="r tr-mono">Rp{{ number_format($pr->total_allowance, 0, ',', '.') }}</td>
                                    @php $totalPotongan = ($pr->total_deductions ?? 0) + ($pr->absence_deduction ?? 0); @endphp
                                    <td class="r tr-mono text-danger">({{ number_format($totalPotongan, 0, ',', '.') }})</td>
                                    <td class="r tr-mono text-success tr-bold tr-net">
                                        Rp{{ number_format($pr->net_salary, 0, ',', '.') }}
                                    </td>
                                    <td class="c">
                                        <div class="tr-btn-group">
                                            <a href="{{ route('sdm.penggajian.print', $pr) }}" target="_blank" class="tr-btn-icon" title="Cetak Slip">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                            </a>
                                            
                                            @can('edit_penggajian')
                                                @if(! $pr->locked_at)
                                                    <button type="button" class="tr-btn-icon js-adjust" 
                                                        data-id="{{ $pr->id }}"
                                                        data-incentive="{{ $pr->incentive_amount ?? 0 }}"
                                                        data-performance="{{ $pr->performance_bonus ?? 0 }}"
                                                        data-override-basic="{{ $pr->override_total_basic_salary ?? '' }}"
                                                        data-override-late-penalty="{{ $pr->override_late_meal_penalty ?? '' }}"
                                                        data-override-absence="{{ $pr->override_absence_deduction ?? '' }}">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    </button>
                                                    <form action="{{ route('sdm.penggajian.lock', $pr) }}" method="POST" class="tr-inline">
                                                        @csrf <button type="submit" class="tr-btn-icon">🔒</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('sdm.penggajian.unlock', $pr) }}" method="POST" class="tr-inline">
                                                        @csrf <button type="submit" class="tr-btn-icon">🔓</button>
                                                    </form>
                                                @endif
                                            @endcan
                                            
                                            @can('delete_penggajian')
                                                <form action="{{ route('sdm.penggajian.destroy', $pr) }}" method="POST" class="tr-inline" onsubmit="return confirm('Hapus data gaji ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="tr-btn-icon text-danger" {{ $pr->locked_at ? 'disabled' : '' }}>
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="tr-empty-row">Belum ada data untuk periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ADJUSTMENT --}}
    <div id="adjustModal" class="tr-modal-wrap">
        <div class="tr-modal-overlay" onclick="closeAdjustModal()"></div>
        <div class="tr-modal-content">
            <div class="tr-modal-header">
                <h3>Penyesuaian Komponen Gaji</h3>
                <button onclick="closeAdjustModal()">&times;</button>
            </div>
            <form id="adjustForm" method="POST">
                @csrf @method('PATCH')
                <div class="tr-modal-body">
                    <div class="tr-field">
                        <label>Override Gaji Pokok</label>
                        <input type="number" name="override_total_basic_salary" id="adjustOverrideBasic" class="tr-input-field">
                    </div>
                    <div class="tr-field-grid">
                        <div class="tr-field">
                            <label>Potongan Telat</label>
                            <input type="number" name="override_late_meal_penalty" id="adjustOverrideLatePenalty" class="tr-input-field">
                        </div>
                        <div class="tr-field">
                            <label>Potongan Alpha</label>
                            <input type="number" name="override_absence_deduction" id="adjustOverrideAbsence" class="tr-input-field">
                        </div>
                    </div>
                    <div class="tr-field-grid">
                        <div class="tr-field">
                            <label>Insentif (Bonus)</label>
                            <input type="number" name="incentive_amount" id="adjustIncentive" class="tr-input-field">
                        </div>
                        <div class="tr-field">
                            <label>Bonus Performa</label>
                            <input type="number" name="performance_bonus" id="adjustPerformance" class="tr-input-field">
                        </div>
                    </div>
                </div>
                <div class="tr-modal-footer">
                    <button type="button" class="tr-btn-ghost" onclick="closeAdjustModal()">Batal</button>
                    <button type="submit" class="tr-btn-primary">Update Slip</button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        :root {
            --tr-indigo: #4f46e5; --tr-success: #10b981; --tr-danger: #ef4444; 
            --tr-border: #e2e8f0; --tr-text: #0f172a; --tr-text-muted: #64748b;
        }

        .tr-page-wrapper { background: #fcfcfd; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text); }
        .tr-page { max-width: 1300px; margin: 0 auto; padding: 2rem 1rem; }

        /* TOP BAR */
        .tr-top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .tr-main-title { font-size: 1.6rem; font-weight: 800; letter-spacing: -0.02em; margin: 0; }
        .tr-sub-title { color: var(--tr-text-muted); font-size: 0.9rem; margin: 4px 0 0; }
        .tr-btn-primary-lg { background: var(--tr-indigo); color: #fff; border: none; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.2s; }
        .tr-btn-primary-lg:hover { background: #4338ca; transform: translateY(-1px); }

        /* CARDS */
        .tr-card-minimal { background: #fff; border: 1px solid var(--tr-border); padding: 1rem 1.5rem; border-radius: 14px; margin-bottom: 1.5rem; }
        .tr-card-main { background: #fff; border: 1px solid var(--tr-border); border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
        .tr-ch-text h3 { margin: 0; font-size: 1rem; font-weight: 800; display: inline-block; }
        .tr-tag-info { background: #e0e7ff; color: #4f46e5; padding: 2px 10px; border-radius: 99px; font-size: 0.75rem; font-weight: 700; margin-left: 8px; }

        /* FILTER */
        .tr-filter-flex { display: flex; gap: 1.5rem; align-items: flex-end; }
        .tr-input-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-input-group label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); }
        .tr-input-field { border: 1px solid var(--tr-border); border-radius: 8px; padding: 8px 12px; font-size: 0.9rem; background: #f8fafc; font-family: inherit; }
        .tr-btn-dark { background: #0f172a; color: #fff; border: none; padding: 9px 18px; border-radius: 8px; font-weight: 700; cursor: pointer; }

        /* TABLE */
        .tr-table-payroll { width: 100%; border-collapse: collapse; min-width: 1000px; }
        .tr-table-payroll thead th { text-align: left; padding: 1rem 1.5rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); border-bottom: 1px solid var(--tr-border); }
        .tr-table-payroll tbody td { padding: 1rem 1.5rem; border-bottom: 1px solid #f8fafc; font-size: 0.85rem; vertical-align: middle; }
        .tr-table-payroll tbody tr:hover { background: #fafbfc; }
        .tr-row-locked { opacity: 0.6; }
        .tr-user-cell { display: flex; align-items: center; gap: 12px; }
        .tr-avatar-sm { width: 32px; height: 32px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #64748b; font-size: 0.85rem; }
        .tr-user-meta .name { display: block; font-weight: 700; color: var(--tr-text); }
        .tr-user-meta .role { font-size: 0.7rem; color: var(--tr-text-muted); }

        /* STATUS BADGE */
        .tr-status-pill { padding: 2px 10px; border-radius: 99px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
        .tr-status-pill.locked { background: #f1f5f9; color: #64748b; }
        .tr-status-pill.open { background: #dcfce7; color: #15803d; }

        /* UTILS */
        .tr-mono { font-family: monospace; font-weight: 600; font-size: 0.95rem; }
        .tr-bold { font-weight: 800; }
        .tr-net { font-size: 1.1rem; }
        .text-success { color: #10b981; } .text-danger { color: #ef4444; } .text-warning { color: #f59e0b; }
        .c { text-align: center; } .r { text-align: right; }
        .tr-btn-icon { width: 30px; height: 30px; border-radius: 6px; border: 1px solid var(--tr-border); background: #fff; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; color: var(--tr-text-muted); transition: 0.2s; }
        .tr-btn-icon:hover { color: var(--tr-indigo); border-color: var(--tr-indigo); }
        .tr-btn-group { display: flex; gap: 4px; justify-content: center; }
        .tr-inline { display: inline-block; margin: 0; }
        .tr-spinner { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.8s linear infinite; display: inline-block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* MODAL */
        .tr-modal-wrap { position: fixed; inset: 0; z-index: 1000; display: none; align-items: center; justify-content: center; padding: 1rem; }
        .tr-modal-wrap[style*="display: flex"] { display: flex !important; }
        .tr-modal-overlay { position: absolute; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); }
        .tr-modal-content { position: relative; background: #fff; width: 100%; max-width: 480px; border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden; }
        .tr-modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .tr-modal-header h3 { margin: 0; font-size: 1.1rem; font-weight: 800; }
        .tr-modal-header button { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--tr-text-muted); }
        .tr-modal-body { padding: 1.5rem; }
        .tr-field { margin-bottom: 1.25rem; display: flex; flex-direction: column; gap: 6px; }
        .tr-field label { font-size: 0.75rem; font-weight: 800; color: var(--tr-text-muted); text-transform: uppercase; }
        .tr-field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .tr-modal-footer { padding: 1.25rem 1.5rem; border-top: 1px solid #f1f5f9; background: #f8fafc; display: flex; justify-content: flex-end; gap: 10px; }
        .tr-btn-ghost { background: transparent; border: none; color: var(--tr-text-muted); font-weight: 700; cursor: pointer; padding: 8px 16px; }

        @media (max-width: 768px) {
            .tr-top-bar { flex-direction: column; align-items: stretch; }
            .tr-filter-flex { flex-direction: column; align-items: stretch; gap: 1rem; }
            .tr-btn-group { flex-wrap: wrap; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function openAdjustModalFromButton(btn) {
            const payrollId = btn.getAttribute('data-id');
            const form = document.getElementById('adjustForm');
            form.action = "{{ url('sdm/penggajian') }}/" + payrollId + "/adjust";
            document.getElementById('adjustIncentive').value = btn.getAttribute('data-incentive') || 0;
            document.getElementById('adjustPerformance').value = btn.getAttribute('data-performance') || 0;
            document.getElementById('adjustOverrideBasic').value = btn.getAttribute('data-override-basic') || '';
            document.getElementById('adjustOverrideLatePenalty').value = btn.getAttribute('data-override-late-penalty') || '';
            document.getElementById('adjustOverrideAbsence').value = btn.getAttribute('data-override-absence') || '';
            document.getElementById('adjustModal').style.display = 'flex';
        }
        function closeAdjustModal() { document.getElementById('adjustModal').style.display = 'none'; }
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-adjust').forEach(btn => {
                btn.addEventListener('click', () => openAdjustModalFromButton(btn));
            });
        });
    </script>
    @endpush
</x-app-layout>