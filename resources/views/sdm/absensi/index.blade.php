<x-hr-layout>
    <x-slot name="eyebrow">Manajemen Kehadiran</x-slot>
    <x-slot name="title">Absensi Karyawan</x-slot>
    <x-slot name="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
    </x-slot>
    <x-slot name="iconBg">bg-indigo</x-slot>
    <x-slot name="description">Monitoring kehadiran harian dan sinkronisasi mesin fingerprint.</x-slot>
    <x-slot name="actions">
        <a href="{{ route('sdm.karyawan.index') }}" class="att-hbtn att-hbtn-ghost">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Karyawan
        </a>
        <a href="{{ route('sdm.absensi.monthly', ['month' => $month]) }}" class="att-hbtn att-hbtn-ghost">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Rekap Bulanan
        </a>
    </x-slot>

    <style>
        .att-wrap{font-family:'Plus Jakarta Sans',system-ui,sans-serif}
        .att-wrap *,.att-wrap *::before,.att-wrap *::after{box-sizing:border-box}
        .att-wrap .mono{font-family:'JetBrains Mono',monospace}
        .att-hbtn{display:inline-flex;align-items:center;gap:.5rem;padding:.5rem 1rem;border-radius:10px;font-size:.8125rem;font-weight:600;text-decoration:none;transition:all .15s;border:1px solid transparent;cursor:pointer}
        .att-hbtn-ghost{background:rgba(99,102,241,.08);color:#4f46e5}
        .att-hbtn-ghost:hover{background:rgba(99,102,241,.15)}
        .att-dnav{display:flex;align-items:center;gap:.5rem;padding:1rem 1.25rem;background:linear-gradient(135deg,#4f46e5,#6366f1);border-radius:16px;margin-bottom:1.25rem;color:#fff}
        .att-dnav-btn{display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;border:1px solid rgba(255,255,255,.2);background:rgba(255,255,255,.1);color:#fff;cursor:pointer;transition:all .15s;text-decoration:none}
        .att-dnav-btn:hover{background:rgba(255,255,255,.2)}
        .att-dnav-center{flex:1;display:flex;align-items:center;justify-content:center;gap:.75rem}
        .att-dnav-label{font-size:1.0625rem;font-weight:700;letter-spacing:-.01em}
        .att-dnav-input{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:8px;padding:.375rem .625rem;color:#fff;font-size:.8125rem;font-family:inherit;outline:none}
        .att-dnav-input::-webkit-calendar-picker-indicator{filter:invert(1)}
        .att-dnav-today{padding:.375rem .875rem;border-radius:8px;background:rgba(255,255,255,.95);color:#4f46e5;font-size:.8125rem;font-weight:700;text-decoration:none;transition:all .15s}
        .att-dnav-today:hover{background:#fff}
        .att-stats{display:grid;grid-template-columns:repeat(6,1fr);gap:.75rem;margin-bottom:1.25rem}
        @media(max-width:1024px){.att-stats{grid-template-columns:repeat(3,1fr)}}
        @media(max-width:640px){.att-stats{grid-template-columns:repeat(2,1fr)}}
        .att-st{background:#fff;border-radius:14px;padding:1rem 1.125rem;border:1px solid #e5e7eb;transition:all .2s}
        .att-st:hover{border-color:#c7d2fe;box-shadow:0 4px 12px rgba(99,102,241,.08)}
        .att-st-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:.625rem}
        .att-st-val{font-size:1.375rem;font-weight:800;letter-spacing:-.02em;line-height:1}
        .att-st-lbl{font-size:.6875rem;color:#6b7280;margin-top:.25rem;font-weight:500;text-transform:uppercase;letter-spacing:.04em}
        .att-tabs{display:flex;gap:.25rem;background:#f3f4f6;padding:4px;border-radius:12px;margin-bottom:1.25rem;width:fit-content}
        .att-tab{padding:.5rem 1.25rem;border-radius:10px;font-size:.8125rem;font-weight:600;cursor:pointer;border:none;background:transparent;color:#6b7280;transition:all .2s}
        .att-tab.active{background:#fff;color:#4f46e5;box-shadow:0 1px 3px rgba(0,0,0,.08)}
        .att-tab:hover:not(.active){color:#374151}
        .att-tp{display:none}.att-tp.active{display:block}
        .att-abar{display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;flex-wrap:wrap}
        .att-abtn{display:inline-flex;align-items:center;gap:.5rem;padding:.5rem 1rem;border-radius:10px;font-size:.8125rem;font-weight:600;border:1px solid transparent;cursor:pointer;transition:all .15s}
        .att-abtn-p{background:#4f46e5;color:#fff;border-color:#4f46e5}
        .att-abtn-p:hover{background:#4338ca}
        .att-abtn-g{background:#059669;color:#fff;border-color:#059669}
        .att-abtn-g:hover{background:#047857}
        .att-abtn-d{background:#fef2f2;color:#dc2626;border-color:#fecaca}
        .att-abtn-d:hover{background:#fee2e2}
        .att-abtn-s{background:#fff;color:#374151;border-color:#d1d5db}
        .att-abtn-s:hover{background:#f9fafb}
        .att-sec{background:#fff;border-radius:16px;border:1px solid #e5e7eb;overflow:hidden;margin-bottom:1.5rem}
        .att-sec-hd{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid #f3f4f6}
        .att-sec-t{font-size:.9375rem;font-weight:700;color:#111827;margin:0}
        .att-sec-sub{font-size:.75rem;color:#6b7280;margin:.125rem 0 0 0}
        .att-cards{padding:.75rem}
        .att-ec{display:flex;align-items:center;gap:.875rem;padding:.875rem 1rem;border-radius:12px;border:1px solid #f3f4f6;margin-bottom:.5rem;transition:all .2s;background:#fff}
        .att-ec:last-child{margin-bottom:0}
        .att-ec:hover{border-color:#c7d2fe;box-shadow:0 2px 8px rgba(99,102,241,.06)}
        .att-av{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.9375rem;color:#fff;flex-shrink:0}
        .att-einfo{flex:1;min-width:0}
        .att-ename{font-size:.875rem;font-weight:700;color:#111827;line-height:1.25}
        .att-emeta{font-size:.6875rem;color:#9ca3af;margin-top:.125rem}
        .att-etimes{display:flex;align-items:center;gap:.75rem;flex-shrink:0}
        .att-etb{text-align:center;min-width:56px}
        .att-etl{font-size:.5625rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.125rem;font-weight:600}
        .att-etv{font-size:.875rem;font-weight:700}
        .att-ediv{width:1px;height:28px;background:#e5e7eb}
        .att-bdg{display:inline-flex;align-items:center;gap:.25rem;padding:.25rem .625rem;border-radius:8px;font-size:.6875rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap}
        .att-bdg-ok{background:#dcfce7;color:#15803d}
        .att-bdg-late{background:#fef3c7;color:#b45309}
        .att-bdg-abs{background:#fee2e2;color:#dc2626}
        .att-bdg-iz{background:#dbeafe;color:#1d4ed8}
        .att-bdg-belum{background:#f3f4f6;color:#6b7280}
        .att-eacts{display:flex;align-items:center;gap:.375rem;flex-shrink:0}
        .att-eabtn{display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;cursor:pointer;transition:all .15s;color:#6b7280}
        .att-eabtn:hover{border-color:#a5b4fc;color:#4f46e5;background:#eef2ff}
        .att-eabtn.cam:hover{border-color:#86efac;color:#059669;background:#f0fdf4}
        .att-mo{display:none;position:fixed;inset:0;background:rgba(15,23,42,.5);z-index:9999;align-items:center;justify-content:center;padding:1rem}
        .att-mo.show{display:flex}
        .att-md{background:#fff;border-radius:16px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;box-shadow:0 25px 50px -12px rgba(0,0,0,.25)}
        .att-md-hd{display:flex;align-items:center;justify-content:space-between;padding:1.125rem 1.5rem;border-bottom:1px solid #f3f4f6}
        .att-md-t{font-size:1rem;font-weight:700;color:#111827;margin:0}
        .att-md-x{background:none;border:none;cursor:pointer;padding:.375rem;color:#9ca3af;border-radius:8px;transition:all .15s}
        .att-md-x:hover{background:#f3f4f6;color:#374151}
        .att-md-b{padding:1.25rem 1.5rem}
        .att-md-ft{padding:1rem 1.5rem;border-top:1px solid #f3f4f6;display:flex;justify-content:flex-end;gap:.5rem}
        .att-fg{margin-bottom:1rem}
        .att-fl{display:block;font-size:.8125rem;font-weight:600;color:#374151;margin-bottom:.375rem}
        .att-fi{width:100%;padding:.5rem .75rem;border:1px solid #d1d5db;border-radius:10px;font-size:.8125rem;font-family:inherit;outline:none;transition:all .15s;background:#fff}
        .att-fi:focus{border-color:#818cf8;box-shadow:0 0 0 3px rgba(99,102,241,.1)}
        .att-sel{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right .75rem center;padding-right:2rem}
        .att-empty{padding:3rem 1.5rem;text-align:center}
        .att-empty-ic{width:56px;height:56px;border-radius:16px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;margin:0 auto .875rem;color:#9ca3af}
        .att-mtw{overflow-x:auto}
        .att-mt{width:100%;border-collapse:collapse}
        .att-mt th{padding:.625rem 1rem;font-size:.6875rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#6b7280;background:#f9fafb;border-bottom:1px solid #e5e7eb;text-align:left}
        .att-mt td{padding:.75rem 1rem;font-size:.8125rem;border-bottom:1px solid #f3f4f6;color:#374151}
        .att-mt tbody tr:hover{background:#faf5ff}
        .att-mt .num{font-weight:700;text-align:center}
        /* Schedule info bar */
        .att-sched-bar{display:flex;align-items:center;gap:1rem;padding:.75rem 1.25rem;background:#fff;border:1px solid #e5e7eb;border-radius:14px;margin-bottom:1rem;flex-wrap:wrap}
        .att-sched-item{display:flex;align-items:center;gap:.5rem;font-size:.8125rem;color:#374151}
        .att-sched-item svg{color:#4f46e5;flex-shrink:0}
        .att-sched-val{font-weight:700;font-family:'JetBrains Mono',monospace;color:#111827}
        .att-sched-sep{width:1px;height:20px;background:#e5e7eb;flex-shrink:0}
        .att-sched-edit{margin-left:auto;display:inline-flex;align-items:center;gap:.375rem;padding:.375rem .875rem;border-radius:8px;font-size:.75rem;font-weight:600;background:#eef2ff;color:#4f46e5;border:1px solid #c7d2fe;cursor:pointer;transition:all .15s}
        .att-sched-edit:hover{background:#e0e7ff}
        /* Settings panel */
        .att-sched-panel{background:#fff;border:1px solid #c7d2fe;border-radius:14px;margin-bottom:1rem;overflow:hidden;display:none}
        .att-sched-panel.open{display:block}
        .att-sched-ph{display:flex;align-items:center;gap:.625rem;padding:.875rem 1.25rem;border-bottom:1px solid #e5e7eb;background:linear-gradient(135deg,#eef2ff,#e0e7ff)}
        .att-sched-ph-t{font-size:.9375rem;font-weight:700;color:#3730a3;margin:0}
        .att-sched-pb{padding:1.25rem}
        .att-sched-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.875rem}
        @media(max-width:768px){.att-sched-grid{grid-template-columns:1fr 1fr}}
        @media(max-width:480px){.att-sched-grid{grid-template-columns:1fr}}
        .att-sched-fg{display:flex;flex-direction:column;gap:.375rem}
        .att-sched-fl{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#374151}
        .att-sched-fi{padding:.5rem .75rem;border:1px solid #d1d5db;border-radius:10px;font-size:.875rem;font-family:'JetBrains Mono',monospace;outline:none;transition:all .15s;background:#fff;width:100%}
        .att-sched-fi:focus{border-color:#818cf8;box-shadow:0 0 0 3px rgba(99,102,241,.1)}
        .att-sched-hint{font-size:.6875rem;color:#9ca3af;margin-top:.125rem}
        .att-sched-actions{display:flex;justify-content:flex-end;gap:.5rem;margin-top:1rem;padding-top:1rem;border-top:1px solid #f3f4f6}
    </style>

    <div class="att-wrap">
        {{-- Date Navigation --}}
        @php
            $carbon = \Carbon\Carbon::parse($date);
            $prevDate = $carbon->copy()->subDay()->toDateString();
            $nextDate = $carbon->copy()->addDay()->toDateString();
            $isToday = $date === now()->toDateString();
        @endphp
        <div class="att-dnav">
            <a href="{{ route('sdm.absensi.index', ['date' => $prevDate, 'month' => $month]) }}" class="att-dnav-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
            <div class="att-dnav-center">
                <form method="GET" action="{{ route('sdm.absensi.index') }}" id="attDateForm" style="display:flex;align-items:center;gap:.5rem;margin:0">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="date" name="date" value="{{ $date }}" class="att-dnav-input mono" onchange="this.form.submit()">
                </form>
                <span class="att-dnav-label">{{ $carbon->translatedFormat('l, d F Y') }}</span>
            </div>
            <a href="{{ route('sdm.absensi.index', ['date' => $nextDate, 'month' => $month]) }}" class="att-dnav-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            @if(!$isToday)
                <a href="{{ route('sdm.absensi.index', ['date' => now()->toDateString(), 'month' => now()->format('Y-m')]) }}" class="att-dnav-today">Hari Ini</a>
            @endif
        </div>

        {{-- Work Schedule Info Bar --}}
        @php
            $workStart   = $setting->sdm_work_start_time ?? '08:00';
            $workEnd     = $setting->sdm_work_end_time ?? '17:00';
            $graceMins   = (int)($setting->sdm_late_grace_minutes ?? 10);
            $overtimeRate = (float)($setting->sdm_overtime_rate_per_hour ?? 0);
            // Hitung total jam kerja standar
            [$sh, $sm] = explode(':', $workStart);
            [$eh, $em] = explode(':', $workEnd);
            $workMins = ((int)$eh * 60 + (int)$em) - ((int)$sh * 60 + (int)$sm);
            $workHours = $workMins > 0 ? number_format($workMins / 60, 1) : '-';
        @endphp
        <div class="att-sched-bar">
            <div class="att-sched-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Jam Masuk</span>
                <span class="att-sched-val">{{ $workStart }}</span>
            </div>
            <div class="att-sched-sep"></div>
            <div class="att-sched-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                <span>Jam Pulang</span>
                <span class="att-sched-val">{{ $workEnd }}</span>
            </div>
            <div class="att-sched-sep"></div>
            <div class="att-sched-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span>Total</span>
                <span class="att-sched-val">{{ $workHours }} jam</span>
            </div>
            <div class="att-sched-sep"></div>
            <div class="att-sched-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>Toleransi Telat</span>
                <span class="att-sched-val">{{ $graceMins }} mnt</span>
            </div>
            @if($overtimeRate > 0)
            <div class="att-sched-sep"></div>
            <div class="att-sched-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span>Lembur/Jam</span>
                <span class="att-sched-val">Rp {{ number_format($overtimeRate, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($isSupervisor)
            <button type="button" class="att-sched-edit" onclick="attToggleSchedule()" id="attSchedEditBtn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Atur Jadwal
            </button>
            @endif
        </div>

        {{-- Supervisor Schedule Settings Panel --}}
        @if($isSupervisor)
        <div class="att-sched-panel" id="attSchedPanel">
            <div class="att-sched-ph">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3730a3" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                <h3 class="att-sched-ph-t">Pengaturan Jam Kerja Karyawan</h3>
            </div>
            <form method="POST" action="{{ route('sdm.absensi.update_schedule') }}" class="att-sched-pb">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <div class="att-sched-grid">
                    <div class="att-sched-fg">
                        <label class="att-sched-fl">Jam Masuk</label>
                        <input type="time" name="sdm_work_start_time" class="att-sched-fi" value="{{ $workStart }}" required>
                        <div class="att-sched-hint">Batas awal masuk kerja</div>
                    </div>
                    <div class="att-sched-fg">
                        <label class="att-sched-fl">Jam Pulang</label>
                        <input type="time" name="sdm_work_end_time" class="att-sched-fi" value="{{ $workEnd }}" required>
                        <div class="att-sched-hint">Batas akhir jam kerja standar</div>
                    </div>
                    <div class="att-sched-fg">
                        <label class="att-sched-fl">Toleransi Keterlambatan</label>
                        <div style="position:relative">
                            <input type="number" name="sdm_late_grace_minutes" class="att-sched-fi" value="{{ $graceMins }}" min="0" max="120" required style="padding-right:3rem">
                            <span style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);font-size:.75rem;color:#9ca3af;font-weight:600">mnt</span>
                        </div>
                        <div class="att-sched-hint">Maks. 120 menit (0 = tidak ada toleransi)</div>
                    </div>
                    <div class="att-sched-fg">
                        <label class="att-sched-fl">Tarif Lembur / Jam</label>
                        <div style="position:relative">
                            <span style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);font-size:.75rem;color:#9ca3af;font-weight:600">Rp</span>
                            <input type="number" name="sdm_overtime_rate_per_hour" class="att-sched-fi" value="{{ (int)$overtimeRate }}" min="0" style="padding-left:2.5rem">
                        </div>
                        <div class="att-sched-hint">0 = lembur tidak dibayar</div>
                    </div>
                </div>
                <div class="att-sched-actions">
                    <button type="button" class="att-abtn att-abtn-s" onclick="attToggleSchedule()">Batal</button>
                    <button type="submit" class="att-abtn att-abtn-p">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
        @endif


        <div class="att-stats">
            <div class="att-st">
                <div class="att-st-icon" style="background:#eef2ff;color:#4f46e5">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="att-st-val" style="color:#4f46e5">{{ $stats['total_employees'] }}</div>
                <div class="att-st-lbl">Total Karyawan</div>
            </div>
            <div class="att-st">
                <div class="att-st-icon" style="background:#dcfce7;color:#16a34a">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div class="att-st-val" style="color:#16a34a">{{ $stats['present'] }}</div>
                <div class="att-st-lbl">Hadir</div>
            </div>
            <div class="att-st">
                <div class="att-st-icon" style="background:#fef3c7;color:#b45309">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="att-st-val" style="color:#b45309">{{ $stats['late'] }}</div>
                <div class="att-st-lbl">Terlambat</div>
            </div>
            <div class="att-st">
                <div class="att-st-icon" style="background:#dbeafe;color:#1d4ed8">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div class="att-st-val" style="color:#1d4ed8">{{ $stats['izin_sakit'] }}</div>
                <div class="att-st-lbl">Izin / Sakit</div>
            </div>
            <div class="att-st">
                <div class="att-st-icon" style="background:#fee2e2;color:#dc2626">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div class="att-st-val" style="color:#dc2626">{{ $stats['absent'] }}</div>
                <div class="att-st-lbl">Alpha</div>
            </div>
            <div class="att-st">
                <div class="att-st-icon" style="background:#f3f4f6;color:#6b7280">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div class="att-st-val" style="color:#6b7280">{{ $stats['belum'] }}</div>
                <div class="att-st-lbl">Belum Absen</div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="att-tabs">
            <button class="att-tab active" onclick="attSwitch('daily',this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:-2px;margin-right:4px"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Harian
            </button>
            <button class="att-tab" onclick="attSwitch('monthly',this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:-2px;margin-right:4px"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                Ringkasan Bulanan
            </button>
        </div>

        {{-- Daily Tab --}}
        <div id="att-daily" class="att-tp active">
            @can('create_absensi')
            <div class="att-abar">
                <form method="POST" action="{{ route('sdm.absensi.sync') }}" style="margin:0" id="attSyncForm">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <button type="submit" class="att-abtn att-abtn-p" id="attSyncBtn" onclick="this.disabled=true;this.innerHTML='<svg width=\'16\' height=\'16\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' style=\'animation:spin 1s linear infinite\'><path d=\'M23 4v6h-6\'/><path d=\'M1 20v-6h6\'/><path d=\'M3.51 9a9 9 0 0 1 14.85-3.36L23 10\'/></svg> Syncing...';this.form.submit()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"/><path d="M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                        Sync Fingerprint
                    </button>
                </form>
                <button type="button" class="att-abtn att-abtn-g" onclick="document.getElementById('attAddModal').classList.add('show')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Absensi Manual
                </button>
                <form method="POST" action="{{ route('sdm.absensi.generate_absent') }}" style="margin:0" onsubmit="return confirm('Generate alpha untuk karyawan yang belum absen pada tanggal ini?')">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <button type="submit" class="att-abtn att-abtn-d">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Generate Alpha
                    </button>
                </form>
            </div>
            @endcan

            <div class="att-sec">
                <div class="att-sec-hd">
                    <div>
                        <h2 class="att-sec-t">Daftar Kehadiran</h2>
                        <p class="att-sec-sub">{{ $attendances->count() }} record tercatat &middot; {{ $users->count() }} karyawan aktif</p>
                    </div>
                </div>
                <div class="att-cards">
                    @if($users->isEmpty())
                        <div class="att-empty">
                            <div class="att-empty-ic">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            </div>
                            <div style="font-size:.9375rem;font-weight:700;color:#374151">Belum ada karyawan aktif</div>
                            <div style="font-size:.8125rem;color:#6b7280;margin-top:.25rem">Tambahkan karyawan terlebih dahulu.</div>
                        </div>
                    @else
                        @foreach($users as $u)
                            @php
                                $att = $attMap[$u->id] ?? null;
                                $initial = strtoupper(substr($u->name, 0, 1));
                                $avColors = ['#4f46e5','#7c3aed','#2563eb','#0891b2','#059669','#d97706','#dc2626','#db2777'];
                                $avBg = $avColors[crc32($u->name) % count($avColors)];
                            @endphp
                            <div class="att-ec">
                                <div class="att-av" style="background:{{ $avBg }}">{{ $initial }}</div>
                                <div class="att-einfo">
                                    <div class="att-ename">{{ $u->name }}</div>
                                    <div class="att-emeta">
                                        @if($att)
                                            UID: <span class="mono">{{ $att->fingerprint_id ?? '-' }}</span>
                                            @if($att->work_hours)
                                                &middot; {{ number_format($att->work_hours, 1) }} jam
                                            @endif
                                            @if($att->late_minutes && $att->late_minutes > 0)
                                                &middot; <span style="color:#b45309">Telat {{ $att->late_minutes }} mnt</span>
                                            @endif
                                        @else
                                            <span style="color:#9ca3af">Belum ada record hari ini</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="att-etimes">
                                    @if($att)
                                        <div class="att-etb">
                                            <div class="att-etl">Masuk</div>
                                            <div class="att-etv mono" style="color:{{ $att->check_in_time ? '#16a34a' : '#d1d5db' }}">{{ $att->check_in_time ?? '-' }}</div>
                                        </div>
                                        <div class="att-ediv"></div>
                                        <div class="att-etb">
                                            <div class="att-etl">Pulang</div>
                                            <div class="att-etv mono" style="color:{{ $att->check_out_time ? '#dc2626' : '#d1d5db' }}">{{ $att->check_out_time ?? '-' }}</div>
                                        </div>
                                        <div class="att-ediv"></div>
                                    @endif
                                    <div>
                                        @if($att)
                                            @php
                                                $bc = match($att->status) {
                                                    'present' => 'att-bdg-ok', 'late' => 'att-bdg-late',
                                                    'absent' => 'att-bdg-abs', 'izin','sakit' => 'att-bdg-iz',
                                                    default => 'att-bdg-belum'
                                                };
                                            @endphp
                                            <span class="att-bdg {{ $bc }}">{{ strtoupper($att->status) }}</span>
                                        @else
                                            <span class="att-bdg att-bdg-belum">BELUM ABSEN</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="att-eacts">
                                    @if($att && ($att->check_in_selfie_path || $att->check_out_selfie_path))
                                        <button class="att-eabtn cam" title="Lihat Foto" onclick="attSelfie('{{ route('sdm.absensi.selfie', ['attendance' => $att->id, 'type' => $att->check_in_selfie_path ? 'in' : 'out']) }}')">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                        </button>
                                    @endif
                                    @can('edit_absensi')
                                        @if($att)
                                            <button class="att-eabtn" title="Edit" onclick="attEditOpen({{ $att->id }},'{{ $att->status }}','{{ $att->check_in_time }}','{{ $att->check_out_time }}',{{ $att->overtime_minutes ?? 0 }},'{{ route('sdm.absensi.update', $att->id) }}')">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </button>
                                        @else
                                            <button class="att-eabtn" title="Tambah Absensi" onclick="attAddOpen({{ $u->id }},'{{ $u->name }}')">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Monthly Tab --}}
        <div id="att-monthly" class="att-tp">
            <div class="att-sec">
                <div class="att-sec-hd">
                    <div>
                        <h2 class="att-sec-t">Ringkasan: {{ $monthLabel }}</h2>
                        <p class="att-sec-sub">Total kehadiran dan jam kerja per karyawan</p>
                    </div>
                    <a href="{{ route('sdm.absensi.monthly.export', ['month' => $month]) }}" class="att-abtn att-abtn-s">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export CSV
                    </a>
                </div>
                <div class="att-mtw">
                    <table class="att-mt">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th style="text-align:center">Hadir</th>
                                <th style="text-align:center">Terlambat</th>
                                <th style="text-align:center">Izin/Sakit</th>
                                <th style="text-align:center">Alpha</th>
                                <th style="text-align:right">Total Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                                @php
                                    $c = $monthlyCounts[$u->id] ?? [];
                                    $hrs = $monthlyHours[$u->id] ?? 0;
                                    $initial = strtoupper(substr($u->name, 0, 1));
                                    $avColors = ['#4f46e5','#7c3aed','#2563eb','#0891b2','#059669','#d97706','#dc2626','#db2777'];
                                    $avBg = $avColors[crc32($u->name) % count($avColors)];
                                @endphp
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:.625rem">
                                            <div class="att-av" style="width:32px;height:32px;font-size:.6875rem;border-radius:8px;background:{{ $avBg }}">{{ $initial }}</div>
                                            <span style="font-weight:600">{{ $u->name }}</span>
                                        </div>
                                    </td>
                                    <td class="num" style="color:#16a34a">{{ (int)($c['present'] ?? 0) }}</td>
                                    <td class="num" style="color:#b45309">{{ (int)($c['late'] ?? 0) }}</td>
                                    <td class="num">{{ (int)($c['izin'] ?? 0) + (int)($c['sakit'] ?? 0) }}</td>
                                    <td class="num" style="color:#dc2626">{{ (int)($c['absent'] ?? 0) }}</td>
                                    <td style="text-align:right"><span class="mono" style="font-weight:700">{{ number_format($hrs, 1) }}</span> <span style="color:#9ca3af;font-size:.75rem">jam</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Manual Modal --}}
    <div id="attAddModal" class="att-mo" onclick="if(event.target===this)this.classList.remove('show')">
        <div class="att-md">
            <div class="att-md-hd">
                <h3 class="att-md-t">Absensi Manual</h3>
                <button class="att-md-x" onclick="document.getElementById('attAddModal').classList.remove('show')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('sdm.absensi.manual.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <div class="att-md-b">
                    <div class="att-fg">
                        <label class="att-fl">Karyawan</label>
                        <select name="user_id" class="att-fi att-sel" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="att-fg">
                        <label class="att-fl">Status</label>
                        <select name="status" class="att-fi att-sel" id="attAddStatus" onchange="attToggleTime('attAdd')">
                            <option value="present">Hadir (Tepat Waktu)</option>
                            <option value="late">Terlambat</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="absent">Alpha</option>
                        </select>
                    </div>
                    <div id="attAddTimeFields">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                            <div class="att-fg">
                                <label class="att-fl">Jam Masuk</label>
                                <input type="time" name="check_in_time" class="att-fi mono" id="attAddCheckin" oninput="attCalcHours('attAdd')">
                            </div>
                            <div class="att-fg">
                                <label class="att-fl">Jam Pulang</label>
                                <input type="time" name="check_out_time" class="att-fi mono" id="attAddCheckout" oninput="attCalcHours('attAdd')">
                            </div>
                        </div>
                        <div class="att-fg">
                            <label class="att-fl">Lembur (menit)</label>
                            <input type="number" name="overtime_minutes" class="att-fi mono" value="0" min="0">
                        </div>
                        <div id="attAddHours" style="padding:.5rem .75rem;background:#eef2ff;border-radius:8px;font-size:.8125rem;font-weight:600;color:#4f46e5;display:none;margin-bottom:1rem"></div>
                    </div>
                </div>
                <div class="att-md-ft">
                    <button type="button" class="att-abtn att-abtn-s" onclick="document.getElementById('attAddModal').classList.remove('show')">Batal</button>
                    <button type="submit" class="att-abtn att-abtn-p">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="attEditModal" class="att-mo" onclick="if(event.target===this)this.classList.remove('show')">
        <div class="att-md">
            <div class="att-md-hd">
                <h3 class="att-md-t">Edit Absensi</h3>
                <button class="att-md-x" onclick="document.getElementById('attEditModal').classList.remove('show')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form id="attEditForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="att-md-b">
                    <div class="att-fg">
                        <label class="att-fl">Status</label>
                        <select name="status" class="att-fi att-sel" id="attEditStatus" onchange="attToggleTime('attEdit')">
                            <option value="present">Hadir (Tepat Waktu)</option>
                            <option value="late">Terlambat</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="absent">Alpha</option>
                        </select>
                    </div>
                    <div id="attEditTimeFields">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                            <div class="att-fg">
                                <label class="att-fl">Jam Masuk</label>
                                <input type="time" name="check_in_time" class="att-fi mono" id="attEditCheckin" oninput="attCalcHours('attEdit')">
                            </div>
                            <div class="att-fg">
                                <label class="att-fl">Jam Pulang</label>
                                <input type="time" name="check_out_time" class="att-fi mono" id="attEditCheckout" oninput="attCalcHours('attEdit')">
                            </div>
                        </div>
                        <div class="att-fg">
                            <label class="att-fl">Lembur (menit)</label>
                            <input type="number" name="overtime_minutes" class="att-fi mono" id="attEditOvertime" min="0">
                        </div>
                        <div id="attEditHours" style="padding:.5rem .75rem;background:#eef2ff;border-radius:8px;font-size:.8125rem;font-weight:600;color:#4f46e5;display:none;margin-bottom:1rem"></div>
                    </div>
                </div>
                <div class="att-md-ft">
                    <button type="button" class="att-abtn att-abtn-s" onclick="document.getElementById('attEditModal').classList.remove('show')">Batal</button>
                    <button type="submit" class="att-abtn att-abtn-p">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Selfie Modal --}}
    <div id="attSelfieModal" class="att-mo" onclick="if(event.target===this)this.classList.remove('show')">
        <div class="att-md" style="max-width:560px">
            <div class="att-md-hd">
                <h3 class="att-md-t">Foto Absensi</h3>
                <button class="att-md-x" onclick="document.getElementById('attSelfieModal').classList.remove('show')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div style="padding:1rem">
                <img id="attSelfieImg" src="" alt="Foto Absensi" style="width:100%;border-radius:12px">
                <div id="attSelfieErr" style="display:none;padding:.75rem;background:#fef2f2;color:#dc2626;border-radius:8px;margin-top:.75rem;font-size:.8125rem"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <style>@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}</style>
    <script>
    function attSwitch(tab,btn){
        document.querySelectorAll('.att-tp').forEach(function(el){el.classList.remove('active')});
        document.querySelectorAll('.att-tab').forEach(function(el){el.classList.remove('active')});
        document.getElementById('att-'+tab).classList.add('active');
        btn.classList.add('active');
    }
    function attCalcHours(prefix){
        var ci=document.getElementById(prefix+'Checkin').value;
        var co=document.getElementById(prefix+'Checkout').value;
        var box=document.getElementById(prefix+'Hours');
        if(ci&&co){
            var p=ci.split(':'),q=co.split(':');
            var mins=(+q[0]*60+ +q[1])-(+p[0]*60+ +p[1]);
            if(mins>0){box.style.display='block';box.textContent='Jam Kerja: '+(mins/60).toFixed(1)+' jam ('+mins+' menit)';}
            else{box.style.display='none';}
        }else{box.style.display='none';}
    }
    function attToggleTime(prefix){
        var st=document.getElementById(prefix+'Status').value;
        var tf=document.getElementById(prefix+'TimeFields');
        tf.style.display=(st==='present'||st==='late')?'block':'none';
    }
    function attEditOpen(id,status,checkin,checkout,overtime,actionUrl){
        var form=document.getElementById('attEditForm');
        form.action=actionUrl;
        document.getElementById('attEditStatus').value=status;
        document.getElementById('attEditCheckin').value=checkin||'';
        document.getElementById('attEditCheckout').value=checkout||'';
        document.getElementById('attEditOvertime').value=overtime||0;
        attToggleTime('attEdit');attCalcHours('attEdit');
        document.getElementById('attEditModal').classList.add('show');
    }
    function attAddOpen(userId,userName){
        var modal=document.getElementById('attAddModal');
        var sel=modal.querySelector('select[name="user_id"]');
        sel.value=userId;
        document.getElementById('attAddStatus').value='present';
        document.getElementById('attAddCheckin').value='';
        document.getElementById('attAddCheckout').value='';
        attToggleTime('attAdd');attCalcHours('attAdd');
        modal.classList.add('show');
    }
    function attSelfie(url){
        var modal=document.getElementById('attSelfieModal');
        var img=document.getElementById('attSelfieImg');
        var err=document.getElementById('attSelfieErr');
        if(err){err.style.display='none';err.textContent='';}
        img.onerror=function(){if(err){err.textContent='Gagal memuat foto.';err.style.display='block';}};
        img.src=url;modal.classList.add('show');
    }
    document.addEventListener('keydown',function(e){
        if(e.key==='Escape'){document.querySelectorAll('.att-mo.show').forEach(function(m){m.classList.remove('show')});}
    });
    function attToggleSchedule(){
        var panel=document.getElementById('attSchedPanel');
        var btn=document.getElementById('attSchedEditBtn');
        if(!panel) return;
        var isOpen=panel.classList.contains('open');
        panel.classList.toggle('open');
        if(btn){btn.innerHTML=isOpen?
            '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Atur Jadwal':
            '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Tutup';
        }
        if(!isOpen) panel.scrollIntoView({behavior:'smooth',block:'nearest'});
    }
    </script>
    @endpush
</x-hr-layout>
