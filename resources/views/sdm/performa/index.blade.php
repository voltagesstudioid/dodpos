<x-hr-layout>
    <x-slot name="eyebrow">Analitik Karyawan</x-slot>
    <x-slot name="title">Laporan Performa Bulanan</x-slot>
    <x-slot name="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
    </x-slot>
    <x-slot name="iconBg">bg-indigo</x-slot>
    <x-slot name="description">Rekapitulasi performa berbasis data kehadiran, lembur, dan komponen slip gaji.</x-slot>
    <x-slot name="actions">
        <a href="{{ route('sdm.penggajian.index', ['month' => $month]) }}" class="hr-btn hr-btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            Penggajian
        </a>
        <a href="{{ route('sdm.absensi.index') }}" class="hr-btn hr-btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            Absensi
        </a>
    </x-slot>

    {{-- Filter --}}
    <div class="hr-card" style="margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('sdm.performa.index') }}" class="hr-filter">
            <div class="hr-filter-group">
                <label class="hr-filter-label">Periode Laporan</label>
                <input type="month" name="month" value="{{ $month }}" class="hr-input">
            </div>
            <div class="hr-filter-group" style="flex: 0; min-width: auto;">
                <button type="submit" class="hr-btn hr-btn-primary">Tampilkan Data</button>
            </div>
            <div class="hr-filter-group" style="flex: 0; min-width: auto;">
                <a href="{{ route('sdm.performa.index') }}" class="hr-btn hr-btn-ghost">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="hr-card">
        <div class="hr-card-header">
            <div>
                <h2 class="hr-card-title">Rekap Periode: {{ $monthLabel }}</h2>
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">Data performa ditarik dari hasil kalkulasi penggajian.</p>
            </div>
        </div>

        @if($payrolls->count() > 0)
        <div class="hr-table-wrapper">
            <table class="hr-table" style="min-width: 1200px;">
                <thead>
                    <tr>
                        <th style="position: sticky; left: 0; background: #f9fafb; z-index: 10;">Karyawan</th>
                        <th style="text-align: center;">Hadir</th>
                        <th style="text-align: center;">Telat</th>
                        <th style="text-align: center; color: #dc2626;">Alpha</th>
                        <th style="text-align: center;">OT (m)</th>
                        <th style="text-align: right;">Tunj. Makan</th>
                        <th style="text-align: right; color: #dc2626;">Potongan</th>
                        <th style="text-align: right;">Insentif</th>
                        <th style="text-align: right;">Bonus</th>
                        <th style="text-align: right; background: #f0fdf4;">Net Salary (THP)</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payrolls as $p)
                        @php $totalPotongan = ($p->total_deductions ?? 0) + ($p->absence_deduction ?? 0); @endphp
                        <tr>
                            <td style="position: sticky; left: 0; background: #fff; z-index: 10; border-right: 1px solid #e5e7eb;">
                                <div class="hr-user">
                                    <div class="hr-avatar">{{ strtoupper(substr($p->user?->name ?? '?', 0, 1)) }}</div>
                                    <div class="hr-user-info">
                                        <div class="hr-user-name">{{ $p->user?->name ?? '-' }}</div>
                                        <div class="hr-user-meta">{{ $p->user?->role ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center; font-weight: 600;">{{ (int)($p->present_days ?? 0) }}</td>
                            <td style="text-align: center; font-weight: 600; {{ $p->late_days > 0 ? 'color: #ea580c;' : '' }}">{{ (int)($p->late_days ?? 0) }}</td>
                            <td style="text-align: center; font-weight: 600; color: #dc2626;">{{ (int)($p->absent_days ?? 0) }}</td>
                            <td style="text-align: center; font-family: monospace;">{{ (int)($p->overtime_minutes ?? 0) }}</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 500;">Rp{{ number_format((float)($p->total_allowance ?? 0), 0, ',', '.') }}</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 500; color: #dc2626;">({{ number_format((float)$totalPotongan, 0, ',', '.') }})</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 500;">Rp{{ number_format((float)($p->incentive_amount ?? 0), 0, ',', '.') }}</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 500;">Rp{{ number_format((float)($p->performance_bonus ?? 0), 0, ',', '.') }}</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 700; font-size: 1rem; color: #16a34a; background: #f0fdf4;">
                                Rp{{ number_format((float)($p->net_salary ?? 0), 0, ',', '.') }}
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('sdm.penggajian.print', $p) }}" target="_blank" class="hr-action" title="Cetak Slip">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="hr-empty">
            <div class="hr-empty-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
            </div>
            <div class="hr-empty-title">Belum ada data performa</div>
            <div class="hr-empty-text">Silakan jalankan proses <strong>Generate Payroll</strong> agar sistem dapat menarik data absensi dan mengkalkulasi performa.</div>
            <a href="{{ route('sdm.penggajian.index', ['month' => $month]) }}" class="hr-btn hr-btn-primary" style="margin-top: 1rem;">Buka Menu Penggajian</a>
        </div>
        @endif
    </div>
</x-hr-layout>