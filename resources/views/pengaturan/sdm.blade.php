<x-app-layout>
    <x-slot name="header">Pengaturan SDM/HR</x-slot>

    <style>
        .sd-wrap {
            padding: 1.5rem 1rem;
            max-width: 840px;
            margin: 0 auto;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }
        .sd-back {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #6b7280;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
            transition: color 0.2s;
        }
        .sd-back:hover { color: #6366f1; }

        .sd-head { margin-bottom: 1.75rem; }
        .sd-head h1 { font-size: 1.5rem; font-weight: 800; color: #111827; margin: 0; display: flex; align-items: center; gap: 0.625rem; }
        .sd-head-icon { width: 36px; height: 36px; background: #eef2ff; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #6366f1; }
        .sd-head p { font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0; padding-left: calc(36px + 0.625rem); }

        /* Alerts */
        .sd-alert {
            padding: 0.875rem 1.25rem;
            margin-bottom: 1.25rem;
            border-radius: 0.625rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            animation: sdFade 0.3s ease;
        }
        @keyframes sdFade { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
        .sd-alert-ok { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .sd-alert-err { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* Sections */
        .sd-stack { display: flex; flex-direction: column; gap: 1.25rem; }

        .sd-section {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            overflow: hidden;
        }
        .sd-section-hd {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .sd-section-icon {
            width: 32px; height: 32px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .sd-icon-indigo { background: #eef2ff; color: #6366f1; }
        .sd-icon-amber { background: #fffbeb; color: #d97706; }
        .sd-icon-emerald { background: #ecfdf5; color: #059669; }
        .sd-icon-rose { background: #fff1f2; color: #e11d48; }

        .sd-section-hd h3 { font-size: 0.9375rem; font-weight: 700; color: #111827; margin: 0; }
        .sd-section-hd p { font-size: 0.75rem; color: #9ca3af; margin: 0; }

        .sd-section-body { padding: 1.25rem 1.5rem; }

        .sd-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }
        @media(min-width: 640px) { .sd-grid { grid-template-columns: 1fr 1fr; } }

        .sd-group { display: flex; flex-direction: column; gap: 0.375rem; }
        .sd-label { font-size: 0.8125rem; font-weight: 700; color: #374151; }
        .sd-input, .sd-select {
            width: 100%;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            color: #111827;
            background: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
            font-family: inherit;
        }
        .sd-input:focus, .sd-select:focus { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .sd-hint { font-size: 0.75rem; color: #9ca3af; line-height: 1.4; }
        .sd-error { font-size: 0.75rem; color: #ef4444; font-weight: 600; }

        /* Actions */
        .sd-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            flex-wrap: wrap;
            padding-top: 0.5rem;
        }
        .sd-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 700;
            border-radius: 0.5rem;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            font-family: inherit;
        }
        .sd-btn-primary { background: #6366f1; color: #fff; }
        .sd-btn-primary:hover { background: #4f46e5; box-shadow: 0 2px 8px rgba(99,102,241,0.25); }
        .sd-btn-ghost { background: #fff; color: #6b7280; border-color: #d1d5db; }
        .sd-btn-ghost:hover { background: #f9fafb; }
    </style>

    <div class="sd-wrap">
        @if(session('success'))
            <div class="sd-alert sd-alert-ok">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="sd-alert sd-alert-err">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <a href="{{ route('dashboard') }}" class="sd-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
            Dashboard
        </a>

        <div class="sd-head">
            <h1>
                <span class="sd-head-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
                Pengaturan SDM/HR
            </h1>
            <p>Konfigurasi jam kerja, toleransi keterlambatan, lembur, dan potongan uang makan.</p>
        </div>

        <form method="POST" action="{{ route('pengaturan.sdm.update') }}">
            @csrf
            @method('PUT')

            <div class="sd-stack">
                {{-- Jam Kerja --}}
                <div class="sd-section">
                    <div class="sd-section-hd">
                        <div class="sd-section-icon sd-icon-indigo">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <h3>Jam Kerja</h3>
                            <p>Waktu masuk dan pulang kerja standar</p>
                        </div>
                    </div>
                    <div class="sd-section-body">
                        <div class="sd-grid">
                            <div class="sd-group">
                                <label class="sd-label">Jam Masuk Kerja</label>
                                <input type="time" name="sdm_work_start_time" value="{{ old('sdm_work_start_time', $setting->sdm_work_start_time ?? '08:00') }}" class="sd-input">
                                @error('sdm_work_start_time') <div class="sd-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="sd-group">
                                <label class="sd-label">Jam Pulang Kerja</label>
                                <input type="time" name="sdm_work_end_time" value="{{ old('sdm_work_end_time', $setting->sdm_work_end_time ?? '17:00') }}" class="sd-input">
                                @error('sdm_work_end_time') <div class="sd-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aturan Kehadiran --}}
                <div class="sd-section">
                    <div class="sd-section-hd">
                        <div class="sd-section-icon sd-icon-amber">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div>
                            <h3>Aturan Kehadiran</h3>
                            <p>Toleransi keterlambatan dan perhitungan lembur</p>
                        </div>
                    </div>
                    <div class="sd-section-body">
                        <div class="sd-grid">
                            <div class="sd-group">
                                <label class="sd-label">Toleransi Telat (menit)</label>
                                <input type="number" min="0" max="600" name="sdm_late_grace_minutes" value="{{ old('sdm_late_grace_minutes', $setting->sdm_late_grace_minutes ?? 10) }}" class="sd-input">
                                <div class="sd-hint">Karyawan yang telat dalam batas ini tidak dianggap terlambat.</div>
                                @error('sdm_late_grace_minutes') <div class="sd-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="sd-group">
                                <label class="sd-label">Rate Lembur per Jam (Rp)</label>
                                <input type="number" min="0" step="0.01" name="sdm_overtime_rate_per_hour" value="{{ old('sdm_overtime_rate_per_hour', $setting->sdm_overtime_rate_per_hour ?? 0) }}" class="sd-input">
                                <div class="sd-hint">Tarif per jam untuk perhitungan uang lembur.</div>
                                @error('sdm_overtime_rate_per_hour') <div class="sd-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kalender Kerja --}}
                <div class="sd-section">
                    <div class="sd-section-hd">
                        <div class="sd-section-icon sd-icon-emerald">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div>
                            <h3>Kalender Kerja</h3>
                            <p>Skema hari kerja dan mode kalender</p>
                        </div>
                    </div>
                    <div class="sd-section-body">
                        <div class="sd-grid">
                            <div class="sd-group">
                                <label class="sd-label">Skema Hari Kerja</label>
                                @php $wdm = old('sdm_working_days_mode', $setting->sdm_working_days_mode ?? 'mon_sat'); @endphp
                                <select name="sdm_working_days_mode" class="sd-select">
                                    <option value="mon_sat" {{ $wdm === 'mon_sat' ? 'selected' : '' }}>Senin - Sabtu</option>
                                    <option value="mon_fri" {{ $wdm === 'mon_fri' ? 'selected' : '' }}>Senin - Jumat</option>
                                </select>
                                @error('sdm_working_days_mode') <div class="sd-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="sd-group">
                                <label class="sd-label">Mode Kalender Kerja</label>
                                @php $cm = old('sdm_calendar_mode', $setting->sdm_calendar_mode ?? 'auto'); @endphp
                                <select name="sdm_calendar_mode" class="sd-select">
                                    <option value="auto" {{ $cm === 'auto' ? 'selected' : '' }}>Otomatis (Skema + Override Libur)</option>
                                    <option value="manual" {{ $cm === 'manual' ? 'selected' : '' }}>Manual (Setiap tanggal ditentukan)</option>
                                </select>
                                <div class="sd-hint">Mode Manual: hari kerja ditentukan dari kalender di SDM &amp; Penggajian &rarr; Libur.</div>
                                @error('sdm_calendar_mode') <div class="sd-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Potongan Uang Makan --}}
                <div class="sd-section">
                    <div class="sd-section-hd">
                        <div class="sd-section-icon sd-icon-rose">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3>Potongan Uang Makan saat Telat</h3>
                            <p>Aturan pemotongan uang makan jika karyawan terlambat</p>
                        </div>
                    </div>
                    <div class="sd-section-body">
                        <div class="sd-grid">
                            <div class="sd-group">
                                <label class="sd-label">Mode Potongan</label>
                                @php $lmm = old('sdm_late_meal_cut_mode', $setting->sdm_late_meal_cut_mode ?? 'full'); @endphp
                                <select name="sdm_late_meal_cut_mode" class="sd-select">
                                    <option value="none" {{ $lmm === 'none' ? 'selected' : '' }}>Tidak dipotong</option>
                                    <option value="full" {{ $lmm === 'full' ? 'selected' : '' }}>Potong penuh uang makan harian</option>
                                    <option value="percent" {{ $lmm === 'percent' ? 'selected' : '' }}>Potong persen (%)</option>
                                    <option value="fixed" {{ $lmm === 'fixed' ? 'selected' : '' }}>Potong nominal (Rp)</option>
                                </select>
                                @error('sdm_late_meal_cut_mode') <div class="sd-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="sd-group">
                                <label class="sd-label">Nilai Potongan</label>
                                <input type="number" min="0" step="0.01" name="sdm_late_meal_cut_value" value="{{ old('sdm_late_meal_cut_value', $setting->sdm_late_meal_cut_value ?? 0) }}" class="sd-input">
                                <div class="sd-hint">Dipakai jika mode Persen (mis: 50) atau Nominal (mis: 10000).</div>
                                @error('sdm_late_meal_cut_value') <div class="sd-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="sd-actions">
                    <a href="{{ route('dashboard') }}" class="sd-btn sd-btn-ghost">Batal</a>
                    @can('edit_pengaturan_toko')
                    <button type="submit" class="sd-btn sd-btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                    @endcan
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
