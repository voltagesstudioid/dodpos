<x-app-layout>
    <x-slot name="header">Pengaturan SDM/HR</x-slot>

    <style>
        .settings-wrap { display: flex; flex-direction: column; gap: 1.5rem; max-width: 800px; margin: 0 auto; }
        .settings-section { border: none; border-radius: 16px; background: #fff; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .settings-section-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: transparent; }
        .settings-section-title { font-weight: 800; color: #0f172a; font-size: 1.05rem; display: flex; align-items: center; gap: 0.5rem; }
        .settings-section-sub { font-size: 0.85rem; color: #64748b; margin-top: 0.25rem; }
        .settings-section-content { padding: 1.5rem; }
        .settings-grid-2 { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
        @media (min-width: 768px) { .settings-grid-2 { grid-template-columns: 1fr 1fr; } }
        .settings-help { font-size: 0.8rem; color: #94a3b8; margin-top: 0.4rem; line-height: 1.4; }
        .settings-error { font-size: 0.8rem; margin-top: 0.4rem; color: #dc2626; font-weight: 600; }
        .form-label { display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem; font-size: 0.9rem; }
        .form-input { width: 100%; padding: 0.65rem 0.9rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; background: #f8fafc; transition: all 0.2s; font-family: inherit; }
        .form-input:focus { outline: none; border-color: #4f46e5; background: #fff; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
        .actions-bar { display: flex; gap: 0.75rem; justify-content: flex-end; flex-wrap: wrap; padding-top: 0.5rem; }
        .btn-primary { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; background: #4f46e5; color: #fff; border: none; border-radius: 10px; font-size: 0.9rem; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .btn-primary:hover { background: #4338ca; }
        .btn-secondary { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.2s; }
        .btn-secondary:hover { background: #e2e8f0; }
    </style>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

        <div class="page-header" style="background: transparent; box-shadow: none; padding: 0 0 1.5rem 0; border-bottom: 1px solid #e2e8f0; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0; letter-spacing: -0.02em;">Pengaturan SDM/HR</h1>
                <div style="color: #64748b; font-size: 0.95rem;">Jam kerja, toleransi telat, lembur, dan potongan uang makan.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('pengaturan.sdm.update') }}">
            @csrf
            @method('PUT')

            <div class="settings-wrap">
                {{-- Jam Kerja --}}
                <div class="settings-section">
                    <div class="settings-section-header">
                        <div>
                            <div class="settings-section-title">🕐 Jam Kerja</div>
                            <div class="settings-section-sub">Waktu masuk dan pulang kerja standar</div>
                        </div>
                    </div>
                    <div class="settings-section-content">
                        <div class="settings-grid-2">
                            <div>
                                <label class="form-label">Jam Masuk Kerja</label>
                                <input type="time" name="sdm_work_start_time" value="{{ old('sdm_work_start_time', $setting->sdm_work_start_time ?? '08:00') }}" class="form-input">
                                @error('sdm_work_start_time') <div class="settings-error">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label">Jam Pulang Kerja</label>
                                <input type="time" name="sdm_work_end_time" value="{{ old('sdm_work_end_time', $setting->sdm_work_end_time ?? '17:00') }}" class="form-input">
                                @error('sdm_work_end_time') <div class="settings-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aturan Kehadiran --}}
                <div class="settings-section">
                    <div class="settings-section-header">
                        <div>
                            <div class="settings-section-title">📋 Aturan Kehadiran</div>
                            <div class="settings-section-sub">Toleransi telat dan perhitungan lembur</div>
                        </div>
                    </div>
                    <div class="settings-section-content">
                        <div class="settings-grid-2">
                            <div>
                                <label class="form-label">Toleransi Telat (menit)</label>
                                <input type="number" min="0" max="600" name="sdm_late_grace_minutes" value="{{ old('sdm_late_grace_minutes', $setting->sdm_late_grace_minutes ?? 10) }}" class="form-input">
                                <div class="settings-help">Karyawan yang telat dalam batas ini tidak dianggap terlambat.</div>
                                @error('sdm_late_grace_minutes') <div class="settings-error">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label">Rate Lembur per Jam (Rp)</label>
                                <input type="number" min="0" step="0.01" name="sdm_overtime_rate_per_hour" value="{{ old('sdm_overtime_rate_per_hour', $setting->sdm_overtime_rate_per_hour ?? 0) }}" class="form-input">
                                <div class="settings-help">Tarif per jam untuk perhitungan lembur.</div>
                                @error('sdm_overtime_rate_per_hour') <div class="settings-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kalender Kerja --}}
                <div class="settings-section">
                    <div class="settings-section-header">
                        <div>
                            <div class="settings-section-title">📅 Kalender Kerja</div>
                            <div class="settings-section-sub">Skema hari kerja dan mode kalender</div>
                        </div>
                    </div>
                    <div class="settings-section-content">
                        <div class="settings-grid-2">
                            <div>
                                <label class="form-label">Skema Hari Kerja</label>
                                @php $wdm = old('sdm_working_days_mode', $setting->sdm_working_days_mode ?? 'mon_sat'); @endphp
                                <select name="sdm_working_days_mode" class="form-input">
                                    <option value="mon_sat" {{ $wdm === 'mon_sat' ? 'selected' : '' }}>Senin–Sabtu</option>
                                    <option value="mon_fri" {{ $wdm === 'mon_fri' ? 'selected' : '' }}>Senin–Jumat</option>
                                </select>
                                @error('sdm_working_days_mode') <div class="settings-error">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label">Mode Kalender Kerja</label>
                                @php $cm = old('sdm_calendar_mode', $setting->sdm_calendar_mode ?? 'auto'); @endphp
                                <select name="sdm_calendar_mode" class="form-input">
                                    <option value="auto" {{ $cm === 'auto' ? 'selected' : '' }}>Otomatis (Skema + Override Libur)</option>
                                    <option value="manual" {{ $cm === 'manual' ? 'selected' : '' }}>Manual (Setiap tanggal ditentukan)</option>
                                </select>
                                <div class="settings-help">Jika Manual, sistem hanya menganggap hari kerja dari kalender di menu SDM/HR → Libur.</div>
                                @error('sdm_calendar_mode') <div class="settings-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Potongan Uang Makan --}}
                <div class="settings-section">
                    <div class="settings-section-header">
                        <div>
                            <div class="settings-section-title">🍱 Potongan Uang Makan saat Telat</div>
                            <div class="settings-section-sub">Aturan pemotongan uang makan jika karyawan terlambat</div>
                        </div>
                    </div>
                    <div class="settings-section-content">
                        <div class="settings-grid-2">
                            <div>
                                <label class="form-label">Mode Potongan</label>
                                @php $lmm = old('sdm_late_meal_cut_mode', $setting->sdm_late_meal_cut_mode ?? 'full'); @endphp
                                <select name="sdm_late_meal_cut_mode" class="form-input">
                                    <option value="none" {{ $lmm === 'none' ? 'selected' : '' }}>Tidak dipotong</option>
                                    <option value="full" {{ $lmm === 'full' ? 'selected' : '' }}>Potong penuh uang makan harian</option>
                                    <option value="percent" {{ $lmm === 'percent' ? 'selected' : '' }}>Potong persen (%)</option>
                                    <option value="fixed" {{ $lmm === 'fixed' ? 'selected' : '' }}>Potong nominal (Rp)</option>
                                </select>
                                @error('sdm_late_meal_cut_mode') <div class="settings-error">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label">Nilai Potongan</label>
                                <input type="number" min="0" step="0.01" name="sdm_late_meal_cut_value" value="{{ old('sdm_late_meal_cut_value', $setting->sdm_late_meal_cut_value ?? 0) }}" class="form-input">
                                <div class="settings-help">Dipakai jika mode Persen (mis: 50) atau Nominal (mis: 10000).</div>
                                @error('sdm_late_meal_cut_value') <div class="settings-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="actions-bar">
                    <a href="{{ route('dashboard') }}" class="btn-secondary">Batal</a>
                    @can('edit_pengaturan_toko')
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    @endcan
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
