<x-hr-layout>
    <x-slot name="eyebrow">Manajemen Payroll</x-slot>
    <x-slot name="title">Penggajian Karyawan</x-slot>
    <x-slot name="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
    </x-slot>
    <x-slot name="iconBg">bg-green</x-slot>
    <x-slot name="description">Kalkulasi gaji otomatis berdasarkan kehadiran, tunjangan, dan potongan.</x-slot>
    <x-slot name="actions">
        @can('create_penggajian')
            <form method="POST" action="{{ route('sdm.penggajian.generate') }}" style="margin:0;">
                @csrf
                <input type="hidden" name="month" value="{{ $month }}">
                <button type="submit" class="hr-btn hr-btn-primary" onclick="this.innerHTML='<span class=\'animate-spin\'>↻</span> Generating...'; this.disabled=true; this.form.submit();">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                    Generate {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}
                </button>
            </form>
        @endcan
        @can('view_potongan_gaji')
            <a href="{{ route('sdm.potongan.index') }}" class="hr-btn hr-btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                Potongan & Bonus
            </a>
        @endcan
    </x-slot>

    {{-- Filter --}}
    <div class="hr-card" style="margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('sdm.penggajian.index') }}" class="hr-filter">
            <div class="hr-filter-group">
                <label class="hr-filter-label">Periode Laporan</label>
                <input type="month" name="month" value="{{ $month }}" class="hr-input">
            </div>
            <div class="hr-filter-group" style="flex: 0; min-width: auto;">
                <button type="submit" class="hr-btn hr-btn-primary">Muat Data</button>
            </div>
        </form>
    </div>

    {{-- Stats Summary --}}
    @if($payrolls->count() > 0)
    <div class="hr-stats" style="margin-bottom: 1.5rem;">
        <div class="hr-stat">
            <div class="hr-stat-label">Total Karyawan</div>
            <div class="hr-stat-value">{{ $payrolls->count() }}</div>
            <div class="hr-stat-change">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Draft</div>
            <div class="hr-stat-value" style="color: #ea580c;">{{ $payrolls->whereNull('locked_at')->count() }}</div>
            <div class="hr-stat-change">Belum Final</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Final</div>
            <div class="hr-stat-value" style="color: #16a34a;">{{ $payrolls->whereNotNull('locked_at')->count() }}</div>
            <div class="hr-stat-change positive">Terkunci</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Total THP</div>
            <div class="hr-stat-value" style="font-size: 1.25rem;">Rp {{ number_format($payrolls->sum('net_salary'), 0, ',', '.') }}</div>
            <div class="hr-stat-change">Total THP</div>
        </div>
    </div>
    @endif

    {{-- Table --}}
    <div class="hr-card">
        <div class="hr-card-header">
            <div>
                <h2 class="hr-card-title">Daftar Slip Gaji</h2>
                <span class="hr-badge hr-badge-blue" style="margin-top: 0.5rem; display: inline-block;">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</span>
            </div>
        </div>
        <div class="hr-table-wrapper">
            <table class="hr-table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Kehadiran</th>
                        <th style="text-align: right;">Gaji Pokok</th>
                        <th style="text-align: right;">Tunjangan</th>
                        <th style="text-align: right;">Potongan</th>
                        <th style="text-align: right;">THP</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $pr)
                        <tr style="{{ $pr->locked_at ? 'background: #f9fafb;' : '' }}">
                            <td>
                                <div class="hr-user">
                                    <div class="hr-avatar">{{ strtoupper(substr($pr->user->name, 0, 1)) }}</div>
                                    <div class="hr-user-info">
                                        <div class="hr-user-name">{{ $pr->user->name ?? '-' }}</div>
                                        <div class="hr-user-meta">{{ $pr->user->role ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                @if($pr->locked_at)
                                    <span class="hr-badge hr-badge-gray">Final</span>
                                @else
                                    <span class="hr-badge hr-badge-green">Draft</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div style="font-weight: 600;">{{ $pr->total_attendance }} <span style="font-size: 0.75rem; color: #6b7280;">Hari</span></div>
                            </td>
                            <td style="text-align: right; font-family: monospace; font-weight: 500;">Rp{{ number_format($pr->total_basic_salary, 0, ',', '.') }}</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 500;">Rp{{ number_format(($pr->total_allowance ?? 0) + ($pr->fixed_allowance_total ?? 0), 0, ',', '.') }}</td>
                            @php $totalPotongan = ($pr->total_deductions ?? 0) + ($pr->absence_deduction ?? 0); @endphp
                            <td style="text-align: right; font-family: monospace; font-weight: 500; color: #dc2626;">({{ number_format($totalPotongan, 0, ',', '.') }})</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 700; font-size: 1rem; color: #16a34a;">
                                Rp{{ number_format($pr->net_salary, 0, ',', '.') }}
                            </td>
                            <td style="text-align: center;">
                                <div class="hr-actions">
                                    <a href="{{ route('sdm.penggajian.print', $pr) }}" target="_blank" class="hr-action" title="Cetak Slip">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                    </a>
                                    @can('edit_penggajian')
                                        @if(! $pr->locked_at)
                                            <button type="button" class="hr-action js-adjust" 
                                                data-id="{{ $pr->id }}"
                                                data-incentive="{{ $pr->incentive_amount ?? 0 }}"
                                                data-performance="{{ $pr->performance_bonus ?? 0 }}"
                                                data-override-basic="{{ $pr->override_total_basic_salary ?? '' }}"
                                                data-override-late-penalty="{{ $pr->override_late_meal_penalty ?? '' }}"
                                                data-override-absence="{{ $pr->override_absence_deduction ?? '' }}"
                                                title="Edit">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </button>
                                            <form action="{{ route('sdm.penggajian.lock', $pr) }}" method="POST" style="margin:0; display:inline;">
                                                @csrf
                                                <button type="submit" class="hr-action" title="Kunci" style="color: #16a34a;">🔒</button>
                                            </form>
                                        @else
                                            <form action="{{ route('sdm.penggajian.unlock', $pr) }}" method="POST" style="margin:0; display:inline;">
                                                @csrf
                                                <button type="submit" class="hr-action" title="Buka Kunci" style="color: #dc2626;">🔓</button>
                                            </form>
                                        @endif
                                    @endcan
                                    @can('delete_penggajian')
                                        <form action="{{ route('sdm.penggajian.destroy', $pr) }}" method="POST" style="margin:0; display:inline;" onsubmit="return confirm('Hapus data gaji ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="hr-action" {{ $pr->locked_at ? 'disabled' : '' }} title="Hapus" style="{{ $pr->locked_at ? 'opacity: 0.5; cursor: not-allowed;' : 'color: #dc2626;' }}">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="hr-empty">
                                    <div class="hr-empty-icon">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                    </div>
                                    <div class="hr-empty-title">Belum ada data</div>
                                    <div class="hr-empty-text">Belum ada data payroll untuk periode ini.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Adjustment --}}
    <div id="adjustModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; padding:1.5rem;">
        <div style="background:#fff; border-radius:12px; width:100%; max-width:480px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #e5e7eb;">
                <h3 style="margin:0; font-size:1.125rem; font-weight:600;">Penyesuaian Komponen Gaji</h3>
                <button type="button" onclick="closeAdjustModal()" style="background:none; border:none; cursor:pointer; padding:0.5rem; color:#6b7280;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="adjustForm" method="POST">
                @csrf @method('PATCH')
                <div style="padding:1.5rem;">
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Override Gaji Pokok</label>
                        <input type="number" name="override_total_basic_salary" id="adjustOverrideBasic" class="hr-input" style="width:100%;">
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Potongan Telat</label>
                            <input type="number" name="override_late_meal_penalty" id="adjustOverrideLatePenalty" class="hr-input" style="width:100%;">
                        </div>
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Potongan Alpha</label>
                            <input type="number" name="override_absence_deduction" id="adjustOverrideAbsence" class="hr-input" style="width:100%;">
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Insentif (Bonus)</label>
                            <input type="number" name="incentive_amount" id="adjustIncentive" class="hr-input" style="width:100%;">
                        </div>
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Bonus Performa</label>
                            <input type="number" name="performance_bonus" id="adjustPerformance" class="hr-input" style="width:100%;">
                        </div>
                    </div>
                </div>
                <div style="padding:1rem 1.5rem; border-top:1px solid #e5e7eb; background:#f9fafb; display:flex; justify-content:flex-end; gap:0.75rem;">
                    <button type="button" class="hr-btn hr-btn-ghost" onclick="closeAdjustModal()">Batal</button>
                    <button type="submit" class="hr-btn hr-btn-primary">Update Slip</button>
                </div>
            </form>
        </div>
    </div>

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
        document.addEventListener('keydown', function(e) { if(e.key === 'Escape') closeAdjustModal(); });
    </script>
    @endpush
</x-hr-layout>