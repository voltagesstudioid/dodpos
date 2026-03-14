<x-app-layout>
    <x-slot name="header">SDM / HR - Absensi</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── PAGE HEADER ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Kehadiran</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                        </div>
                        Monitoring Absensi
                    </h1>
                    <p class="tr-subtitle">Rekapitulasi harian dan integrasi mesin fingerprint karyawan.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('sdm.karyawan.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Data Karyawan
                    </a>
                    <a href="{{ route('sdm.absensi.monthly', ['month' => $month]) }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        Rekap Bulanan
                    </a>
                </div>
            </div>

            {{-- ─── FILTER SECTION ─── --}}
            <div class="tr-card tr-filter-card">
                <form method="GET" action="{{ route('sdm.absensi.index') }}" class="tr-filter-grid">
                    <div class="tr-form-group">
                        <label class="tr-label">Tanggal Spesifik</label>
                        <input type="date" name="date" value="{{ $date }}" class="tr-input">
                    </div>
                    <div class="tr-form-group">
                        <label class="tr-label">Bulan Laporan</label>
                        <input type="month" name="month" value="{{ $month }}" class="tr-input">
                    </div>
                    <div class="tr-filter-actions">
                        <button type="submit" class="tr-btn tr-btn-dark">Tampilkan</button>
                        <a href="{{ route('sdm.absensi.index', ['date' => now()->toDateString(), 'month' => now()->format('Y-m')]) }}" class="tr-btn tr-btn-outline">Hari Ini</a>
                    </div>
                </form>
            </div>

            {{-- ─── DAILY RECAP CARD ─── --}}
            <div class="tr-card">
                <div class="tr-card-header tr-flex-between">
                    <div>
                        <h2 class="tr-section-title">Rekapitulasi Harian</h2>
                        <p class="tr-card-subtitle">Tanggal: <strong>{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</strong></p>
                    </div>
                    
                    @can('create_absensi')
                    <div class="tr-header-actions">
                        <form method="POST" action="{{ route('sdm.absensi.sync') }}" style="margin:0;">
                            @csrf
                            <input type="hidden" name="date" value="{{ $date }}">
                            <button type="submit" class="tr-btn tr-btn-primary" onclick="this.disabled=true; this.innerHTML='Syncing...'; this.form.submit();">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                Sync Fingerprint
                            </button>
                        </form>
                        <button type="button" class="tr-btn tr-btn-danger" onclick="document.getElementById('genAlphaForm').submit()">Generate Alpha</button>
                        <form id="genAlphaForm" method="POST" action="{{ route('sdm.absensi.generate_absent') }}" style="display:none;">@csrf<input type="hidden" name="date" value="{{ $date }}"></form>
                        <button type="button" class="tr-btn tr-btn-outline" onclick="openManualModal()">+ Manual</button>
                    </div>
                    @endcan
                </div>

                <div class="tr-card-body">
                    @if(session('success')) <div class="tr-alert tr-alert-success">✅ {{ session('success') }}</div> @endif
                    @if(session('warning')) <div class="tr-alert tr-alert-warning">⚠️ {{ session('warning') }}</div> @endif
                    @if(session('error')) <div class="tr-alert tr-alert-danger">❌ {{ session('error') }}</div> @endif

                    @if(isset($attendances) && $attendances->count() > 0)
                        <div class="table-responsive">
                            <table class="tr-table">
                                <thead>
                                    <tr>
                                        <th>Karyawan</th>
                                        <th class="c">UID Finger</th>
                                        <th class="c">Masuk</th>
                                        <th class="c">Foto Masuk</th>
                                        <th class="c">Pulang</th>
                                        <th class="c">Foto Pulang</th>
                                        <th class="c">Jam Kerja</th>
                                        <th class="c">Status</th>
                                        <th class="r">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $att)
                                        <tr>
                                            <td>
                                                @if($att->user)
                                                    <div class="tr-user-info">
                                                        <div class="name">{{ $att->user->name }}</div>
                                                        <div class="meta">{{ $att->user->email }}</div>
                                                    </div>
                                                @else
                                                    <div class="tr-user-unlinked">
                                                        <span class="text-danger font-bold">Tidak Diketahui</span>
                                                        <button type="button" class="tr-link-btn" onclick="openLinkModal('{{ $att->fingerprint_id }}')">Tautkan Akun &rarr;</button>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="c tr-font-mono">{{ $att->fingerprint_id }}</td>
                                            <td class="c tr-font-bold text-success">{{ $att->check_in_time ?? '-' }}</td>
                                            <td class="c">
                                                @if($att->check_in_selfie_path)
                                                    <button type="button" class="tr-btn-view-img" onclick="openSelfie('{{ route('sdm.absensi.selfie', ['attendance' => $att->id, 'type' => 'in']) }}')">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                                                    </button>
                                                @else
                                                    <span class="tr-text-light">-</span>
                                                @endif
                                            </td>
                                            <td class="c tr-font-bold text-danger">{{ $att->check_out_time ?? '-' }}</td>
                                            <td class="c">
                                                @if($att->check_out_selfie_path)
                                                    <button type="button" class="tr-btn-view-img" onclick="openSelfie('{{ route('sdm.absensi.selfie', ['attendance' => $att->id, 'type' => 'out']) }}')">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                                                    </button>
                                                @else
                                                    <span class="tr-text-light">-</span>
                                                @endif
                                            </td>
                                            <td class="c tr-font-bold">{{ $att->work_hours ? number_format($att->work_hours, 1) . ' Jm' : '-' }}</td>
                                            <td class="c">
                                                @php
                                                    $stCls = match($att->status) {
                                                        'present', 'late' => 'tr-badge-success',
                                                        'izin', 'sakit' => 'tr-badge-info',
                                                        'absent' => 'tr-badge-danger',
                                                        default => 'tr-badge-gray'
                                                    };
                                                @endphp
                                                <span class="tr-badge {{ $stCls }}">{{ strtoupper($att->status) }}</span>
                                            </td>
                                            <td class="r">
                                                @can('edit_absensi')
                                                <button type="button" class="tr-action-btn-circle" title="Edit Data"
                                                    data-id="{{ $att->id }}" data-status="{{ $att->status }}"
                                                    data-checkin="{{ $att->check_in_time }}" data-checkout="{{ $att->check_out_time }}"
                                                    data-overtime="{{ $att->overtime_minutes ?? 0 }}"
                                                    onclick="openEditModalFromButton(this)">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="tr-empty-state">
                            <div class="tr-empty-icon">📡</div>
                            <p>Belum ada data absensi terekam hari ini. Silakan tarik data dari mesin fingerprint.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ─── MONTHLY RECAP AREA ─── --}}
            <div class="tr-card" style="margin-top: 1.5rem;">
                <div class="tr-card-header">
                    <h2 class="tr-section-title">Ringkasan Performa: {{ $monthLabel }}</h2>
                </div>
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th class="c">Present</th>
                                <th class="c">Late</th>
                                <th class="c">Izin/Sakit</th>
                                <th class="c">Absent</th>
                                <th class="r">Total Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                                @php
                                    $c = $monthlyCounts[$u->id] ?? [];
                                @endphp
                                <tr>
                                    <td class="tr-font-bold">{{ $u->name }}</td>
                                    <td class="c">{{ (int)($c['present'] ?? 0) }}</td>
                                    <td class="c text-warning">{{ (int)($c['late'] ?? 0) }}</td>
                                    <td class="c">{{ (int)($c['izin'] ?? 0) + (int)($c['sakit'] ?? 0) }}</td>
                                    <td class="c text-danger">{{ (int)($c['absent'] ?? 0) }}</td>
                                    <td class="r tr-font-mono">{{ number_format($monthlyHours[$u->id] ?? 0, 1) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div id="selfieModal" class="tr-modal-overlay" style="display:none;">
        <div class="tr-modal">
            <div class="tr-modal-header">
                <div class="tr-modal-title">Foto Absensi</div>
                <button type="button" class="tr-modal-close" onclick="closeSelfie()">✕</button>
            </div>
            <div class="tr-modal-body">
                <img id="selfieImg" src="" alt="Selfie Absensi" loading="lazy" />
                <div id="selfieError" class="tr-modal-error" style="display:none;"></div>
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

            if (err) {
                err.style.display = 'none';
                err.textContent = '';
            }

            img.onerror = function () {
                if (err) {
                    err.textContent = 'Gagal memuat foto. Coba refresh halaman.';
                    err.style.display = 'block';
                }
            };
            img.src = url;
            modal.style.display = 'flex';
        }

        function closeSelfie() {
            const modal = document.getElementById('selfieModal');
            const img = document.getElementById('selfieImg');
            const err = document.getElementById('selfieError');
            if (img) img.src = '';
            if (err) {
                err.style.display = 'none';
                err.textContent = '';
            }
            if (modal) modal.style.display = 'none';
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSelfie();
        });
    </script>
    @endpush

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-indigo: #4f46e5;
            --tr-indigo-hover: #4338ca;
            --tr-indigo-light: #e0e7ff;
            --tr-border: #e2e8f0;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
        }

        .tr-page-wrapper { background-color: #f8fafc; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }
        .tr-page { max-width: 1280px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* HEADER */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-indigo); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin-top: 4px; }
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* CARDS */
        .tr-card { background: #fff; border: 1px solid var(--tr-border); border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
        .tr-flex-between { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .tr-section-title { font-size: 1rem; font-weight: 800; }
        .tr-card-subtitle { font-size: 0.75rem; color: var(--tr-text-muted); }
        .tr-card-body { padding: 1.5rem; }

        /* FILTER BAR */
        .tr-filter-card { padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
        .tr-filter-grid { display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; }
        .tr-form-group { display: flex; flex-direction: column; gap: 5px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); }
        .tr-input { padding: 0.5rem 0.75rem; border: 1px solid var(--tr-border); border-radius: 8px; font-size: 0.85rem; background: #f8fafc; transition: 0.2s; }
        .tr-input:focus { border-color: var(--tr-indigo); outline: none; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        .tr-filter-actions { display: flex; gap: 0.5rem; }

        /* BUTTONS */
        .tr-btn { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: 0.2s; text-decoration: none; border: 1px solid transparent; }
        .tr-btn-primary { background: var(--tr-indigo); color: #fff; }
        .tr-btn-primary:hover { background: var(--tr-indigo-hover); }
        .tr-btn-outline { border-color: var(--tr-border); background: #fff; color: var(--tr-text-main); }
        .tr-btn-outline:hover { background: #f8fafc; }
        .tr-btn-dark { background: var(--tr-text-main); color: #fff; }
        .tr-btn-danger { background: #ef4444; color: #fff; }

        /* TABLE */
        .table-responsive { overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; }
        .tr-table thead th { background: #f8fafc; padding: 0.75rem 1rem; font-size: 0.7rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; border-bottom: 1px solid var(--tr-border); text-align: left; }
        .tr-table tbody td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; }
        .tr-table tbody tr:hover { background: #fafafa; }
        .tr-table th.c, .tr-table td.c { text-align: center; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* USER DATA */
        .tr-user-info .name { font-weight: 700; }
        .tr-user-info .meta { font-size: 0.75rem; color: var(--tr-text-muted); }
        .tr-link-btn { background: none; border: none; padding: 0; color: var(--tr-indigo); font-size: 0.75rem; font-weight: 700; cursor: pointer; margin-top: 4px; }

        /* BADGE */
        .tr-badge { padding: 0.2rem 0.6rem; border-radius: 99px; font-size: 0.65rem; font-weight: 800; }
        .tr-badge-success { background: #dcfce7; color: #15803d; }
        .tr-badge-danger { background: #fee2e2; color: #b91c1c; }
        .tr-badge-info { background: #e0f2fe; color: #0369a1; }
        .tr-badge-gray { background: #f1f5f9; color: #64748b; }

        .tr-action-btn-circle { width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--tr-border); background: #fff; cursor: pointer; color: var(--tr-text-muted); transition: 0.2s; }
        .tr-action-btn-circle:hover { color: var(--tr-indigo); border-color: var(--tr-indigo); }

        .tr-text-light { color: #cbd5e1; }
        .tr-btn-view-img { width: 32px; height: 32px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--tr-border); background: #fff; cursor: pointer; color: var(--tr-text-muted); transition: 0.2s; }
        .tr-btn-view-img:hover { color: var(--tr-indigo); border-color: var(--tr-indigo); background: #f8fafc; }

        .tr-modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.55); display: flex; align-items: center; justify-content: center; padding: 1.5rem; z-index: 9999; }
        .tr-modal { width: min(760px, 95vw); background: #fff; border-radius: 14px; border: 1px solid var(--tr-border); box-shadow: 0 25px 60px rgba(0,0,0,0.25); overflow: hidden; }
        .tr-modal-header { display: flex; align-items: center; justify-content: space-between; padding: 0.9rem 1.1rem; border-bottom: 1px solid #f1f5f9; }
        .tr-modal-title { font-weight: 800; }
        .tr-modal-close { width: 34px; height: 34px; border-radius: 10px; border: 1px solid var(--tr-border); background: #fff; cursor: pointer; font-size: 1rem; line-height: 1; }
        .tr-modal-close:hover { background: #f8fafc; }
        .tr-modal-body { padding: 1rem; display: grid; gap: 0.75rem; }
        .tr-modal-body img { width: 100%; height: auto; border-radius: 12px; border: 1px solid var(--tr-border); background: #f8fafc; }
        .tr-modal-error { padding: 0.75rem 0.9rem; border-radius: 12px; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; font-weight: 700; font-size: 0.85rem; }

        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
        .tr-font-bold { font-weight: 700; }
        .tr-empty-state { text-align: center; padding: 3rem; color: var(--tr-text-muted); }
        .tr-empty-icon { font-size: 2.5rem; margin-bottom: 0.5rem; }

        .text-success { color: #10b981; } .text-danger { color: #ef4444; } .text-warning { color: #f59e0b; }

        @media (max-width: 768px) {
            .tr-header-actions { width: 100%; justify-content: space-between; }
            .tr-filter-grid { flex-direction: column; align-items: stretch; }
            .tr-filter-actions { display: grid; grid-template-columns: 1fr 1fr; }
        }
    </style>
    @endpush
</x-app-layout>
