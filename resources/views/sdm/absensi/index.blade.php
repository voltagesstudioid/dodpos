<x-hr-layout>
    <x-slot name="eyebrow">Manajemen Kehadiran</x-slot>
    <x-slot name="title">Absensi Karyawan</x-slot>
    <x-slot name="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
    </x-slot>
    <x-slot name="iconBg">bg-indigo</x-slot>
    <x-slot name="description">Monitoring kehadiran harian dan sinkronisasi mesin fingerprint.</x-slot>
    <x-slot name="actions">
        <a href="{{ route('sdm.karyawan.index') }}" class="hr-btn hr-btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            Karyawan
        </a>
        <a href="{{ route('sdm.absensi.monthly', ['month' => $month]) }}" class="hr-btn hr-btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Rekap Bulanan
        </a>
    </x-slot>

    {{-- Filter Section --}}
    <div class="hr-card" style="margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('sdm.absensi.index') }}" class="hr-filter">
            <div class="hr-filter-group">
                <label class="hr-filter-label">Tanggal Spesifik</label>
                <input type="date" name="date" value="{{ $date }}" class="hr-input">
            </div>
            <div class="hr-filter-group">
                <label class="hr-filter-label">Bulan Laporan</label>
                <input type="month" name="month" value="{{ $month }}" class="hr-input">
            </div>
            <div class="hr-filter-group" style="flex: 0; min-width: auto;">
                <button type="submit" class="hr-btn hr-btn-primary">Tampilkan</button>
            </div>
            <div class="hr-filter-group" style="flex: 0; min-width: auto;">
                <a href="{{ route('sdm.absensi.index', ['date' => now()->toDateString(), 'month' => now()->format('Y-m')]) }}" class="hr-btn hr-btn-secondary">Hari Ini</a>
            </div>
        </form>
    </div>

    {{-- Stats Cards --}}
    @if(isset($attendances) && $attendances->count() > 0)
    <div class="hr-stats" style="margin-bottom: 1.5rem;">
        <div class="hr-stat">
            <div class="hr-stat-label">Total Kehadiran</div>
            <div class="hr-stat-value">{{ $attendances->count() }}</div>
            <div class="hr-stat-change">Hari ini</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Hadir Tepat Waktu</div>
            <div class="hr-stat-value" style="color: #16a34a;">{{ $attendances->where('status', 'present')->count() }}</div>
            <div class="hr-stat-change positive">{{ number_format($attendances->count() > 0 ? ($attendances->where('status', 'present')->count() / $attendances->count()) * 100 : 0, 0) }}%</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Terlambat</div>
            <div class="hr-stat-value" style="color: #ea580c;">{{ $attendances->where('status', 'late')->count() }}</div>
            <div class="hr-stat-change negative">{{ number_format($attendances->count() > 0 ? ($attendances->where('status', 'late')->count() / $attendances->count()) * 100 : 0, 0) }}%</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Tidak Hadir</div>
            <div class="hr-stat-value" style="color: #dc2626;">{{ $attendances->where('status', 'absent')->count() }}</div>
            <div class="hr-stat-change">Alpha</div>
        </div>
    </div>
    @endif

    {{-- Daily Recap Card --}}
    <div class="hr-card" style="margin-bottom: 1.5rem;">
        <div class="hr-card-header">
            <div>
                <h2 class="hr-card-title">Rekapitulasi Harian</h2>
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">Tanggal: <strong>{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</strong></p>
            </div>
            @can('create_absensi')
            <div style="display: flex; gap: 0.5rem;">
                <form method="POST" action="{{ route('sdm.absensi.sync') }}" style="margin:0;">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <button type="submit" class="hr-btn hr-btn-primary" onclick="this.disabled=true; this.innerHTML='<span class=\'animate-spin\'>↻</span> Syncing...'; this.form.submit();">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                        Sync Fingerprint
                    </button>
                </form>
                <button type="button" class="hr-btn hr-btn-secondary" style="background: #fef2f2; color: #dc2626; border-color: #fecaca;" onclick="document.getElementById('genAlphaForm').submit()">Generate Alpha</button>
                <form id="genAlphaForm" method="POST" action="{{ route('sdm.absensi.generate_absent') }}" style="display:none;">@csrf<input type="hidden" name="date" value="{{ $date }}"></form>
            </div>
            @endcan
        </div>

        <div class="hr-table-wrapper">
            @if(isset($attendances) && $attendances->count() > 0)
                <table class="hr-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th style="text-align: center;">UID</th>
                            <th style="text-align: center;">Masuk</th>
                            <th style="text-align: center;">Foto</th>
                            <th style="text-align: center;">Pulang</th>
                            <th style="text-align: center;">Foto</th>
                            <th style="text-align: center;">Jam Kerja</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $att)
                            <tr>
                                <td>
                                    @if($att->user)
                                        <div class="hr-user">
                                            <div class="hr-avatar">{{ strtoupper(substr($att->user->name, 0, 1)) }}</div>
                                            <div class="hr-user-info">
                                                <div class="hr-user-name">{{ $att->user->name }}</div>
                                                <div class="hr-user-meta">{{ $att->user->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div style="color: #dc2626; font-weight: 600;">Tidak Diketahui</div>
                                    @endif
                                </td>
                                <td style="text-align: center; font-family: monospace; font-size: 0.75rem;">{{ $att->fingerprint_id }}</td>
                                <td style="text-align: center; font-weight: 600; color: #16a34a;">{{ $att->check_in_time ?? '-' }}</td>
                                <td style="text-align: center;">
                                    @if($att->check_in_selfie_path)
                                        <button type="button" class="hr-action" onclick="openSelfie('{{ route('sdm.absensi.selfie', ['attendance' => $att->id, 'type' => 'in']) }}')" title="Lihat Foto Masuk">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                                        </button>
                                    @else
                                        <span style="color: #d1d5db;">-</span>
                                    @endif
                                </td>
                                <td style="text-align: center; font-weight: 600; color: #dc2626;">{{ $att->check_out_time ?? '-' }}</td>
                                <td style="text-align: center;">
                                    @if($att->check_out_selfie_path)
                                        <button type="button" class="hr-action" onclick="openSelfie('{{ route('sdm.absensi.selfie', ['attendance' => $att->id, 'type' => 'out']) }}')" title="Lihat Foto Pulang">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                                        </button>
                                    @else
                                        <span style="color: #d1d5db;">-</span>
                                    @endif
                                </td>
                                <td style="text-align: center; font-weight: 600;">{{ $att->work_hours ? number_format($att->work_hours, 1) . ' Jm' : '-' }}</td>
                                <td style="text-align: center;">
                                    @php
                                        $stCls = match($att->status) {
                                            'present' => 'hr-badge-green',
                                            'late' => 'hr-badge-yellow',
                                            'izin', 'sakit' => 'hr-badge-blue',
                                            'absent' => 'hr-badge-red',
                                            default => 'hr-badge-gray'
                                        };
                                    @endphp
                                    <span class="hr-badge {{ $stCls }}">{{ strtoupper($att->status) }}</span>
                                </td>
                                <td style="text-align: right;">
                                    @can('edit_absensi')
                                    <button type="button" class="hr-action" title="Edit Data"
                                        data-id="{{ $att->id }}" data-status="{{ $att->status }}"
                                        data-checkin="{{ $att->check_in_time }}" data-checkout="{{ $att->check_out_time }}"
                                        data-overtime="{{ $att->overtime_minutes ?? 0 }}"
                                        onclick="openEditModalFromButton(this)">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="hr-empty">
                    <div class="hr-empty-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div class="hr-empty-title">Belum ada data absensi</div>
                    <div class="hr-empty-text">Silakan tarik data dari mesin fingerprint.</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Monthly Recap --}}
    <div class="hr-card">
        <div class="hr-card-header">
            <h2 class="hr-card-title">Ringkasan Performa: {{ $monthLabel }}</h2>
        </div>
        <div class="hr-table-wrapper">
            <table class="hr-table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th style="text-align: center;">Present</th>
                        <th style="text-align: center;">Late</th>
                        <th style="text-align: center;">Izin/Sakit</th>
                        <th style="text-align: center;">Absent</th>
                        <th style="text-align: right;">Total Jam</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        @php
                            $c = $monthlyCounts[$u->id] ?? [];
                        @endphp
                        <tr>
                            <td>
                                <div class="hr-user">
                                    <div class="hr-avatar sm">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                                    <div class="hr-user-name">{{ $u->name }}</div>
                                </div>
                            </td>
                            <td style="text-align: center; font-weight: 600;">{{ (int)($c['present'] ?? 0) }}</td>
                            <td style="text-align: center; color: #ea580c;">{{ (int)($c['late'] ?? 0) }}</td>
                            <td style="text-align: center;">{{ (int)($c['izin'] ?? 0) + (int)($c['sakit'] ?? 0) }}</td>
                            <td style="text-align: center; color: #dc2626;">{{ (int)($c['absent'] ?? 0) }}</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 600;">{{ number_format($monthlyHours[$u->id] ?? 0, 1) }} Jm</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Selfie Modal --}}
    <div id="selfieModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; padding:1.5rem;">
        <div style="background:#fff; border-radius:12px; max-width:600px; width:100%; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid #e5e7eb;">
                <h3 style="margin:0; font-size:1rem; font-weight:600;">Foto Absensi</h3>
                <button type="button" onclick="closeSelfie()" style="background:none; border:none; cursor:pointer; padding:0.5rem; color:#6b7280;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div style="padding:1rem;">
                <img id="selfieImg" src="" alt="Selfie Absensi" style="width:100%; border-radius:8px;" />
                <div id="selfieError" style="display:none; padding:0.75rem; background:#fef2f2; color:#dc2626; border-radius:8px; margin-top:0.75rem;"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openSelfie(url) {
            const modal = document.getElementById('selfieModal');
            const img = document.getElementById('selfieImg');
            const err = document.getElementById('selfieError');
            if (!modal || !img) return;
            if (err) { err.style.display = 'none'; err.textContent = ''; }
            img.onerror = function () {
                if (err) { err.textContent = 'Gagal memuat foto.'; err.style.display = 'block'; }
            };
            img.src = url;
            modal.style.display = 'flex';
        }
        function closeSelfie() {
            const modal = document.getElementById('selfieModal');
            const img = document.getElementById('selfieImg');
            if (img) img.src = '';
            if (modal) modal.style.display = 'none';
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSelfie();
        });
    </script>
    @endpush
</x-hr-layout>
