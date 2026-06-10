<x-app-layout>
    <x-slot name="header">Tambah Karyawan</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page tr-page-narrow">

            {{-- ─── HEADER ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <a href="{{ route('sdm.karyawan.index') }}" class="tr-back-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali ke Direktori
                    </a>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-teal">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        </div>
                        Tambah Karyawan Baru
                    </h1>
                    <p class="tr-subtitle">Lengkapi biodata karyawan. Akun login dapat dibuatkan setelah data tersimpan.</p>
                </div>
            </div>

            {{-- ─── ERROR ALERTS ─── --}}
            @if($errors->any())
                <div class="tr-alert tr-alert-danger">
                    <div class="tr-alert-head">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <strong>Terdapat kesalahan input:</strong>
                    </div>
                    <ul class="tr-alert-list">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- ─── FORM CARD ─── --}}
            <div class="tr-card">
                <div class="tr-card-header tr-header-teal">
                    <h2 class="tr-section-title">Informasi Personal & Pekerjaan</h2>
                </div>

                <form method="POST" action="{{ route('sdm.karyawan.store') }}" class="tr-form-main">
                    @csrf

                    <div class="tr-form-stack">
                        {{-- Nama --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Nama Lengkap <span class="tr-req">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="tr-input @error('name') is-invalid @enderror" required autofocus placeholder="Masukkan nama lengkap karyawan">
                            @error('name') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>

                        <div class="tr-grid-2">
                            {{-- No HP --}}
                            <div class="tr-form-group">
                                <label class="tr-label">No. Handphone / WA</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="tr-input @error('phone') is-invalid @enderror" placeholder="Cth: 08123456789">
                                @error('phone') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>
                            {{-- Jabatan --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Jabatan</label>
                                <input type="text" name="position" value="{{ old('position') }}" class="tr-input @error('position') is-invalid @enderror" placeholder="Cth: Kasir, Admin Gudang">
                                @error('position') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="tr-salary-section">
                            <div class="tr-salary-label">Kompensasi</div>
                            <div class="tr-grid-2">
                                {{-- Gaji Pokok --}}
                                <div class="tr-form-group">
                                    <label class="tr-label">Gaji Pokok <span class="tr-text-light font-normal">(Bulanan)</span></label>
                                    <div class="tr-input-prefix-group">
                                        <span class="prefix">Rp</span>
                                        <input type="number" name="basic_salary" value="{{ old('basic_salary', 0) }}" class="tr-input" min="0" placeholder="0">
                                    </div>
                                </div>
                                {{-- Uang Kehadiran --}}
                                <div class="tr-form-group">
                                    <label class="tr-label">Uang Kehadiran <span class="tr-text-light font-normal">(Per Hari)</span></label>
                                    <div class="tr-input-prefix-group">
                                        <span class="prefix">Rp</span>
                                        <input type="number" name="daily_allowance" value="{{ old('daily_allowance', 0) }}" class="tr-input" min="0" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tr-grid-2">
                            {{-- Tgl Masuk --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Tanggal Mulai Bekerja</label>
                                <input type="date" name="join_date" value="{{ old('join_date') }}" class="tr-input @error('join_date') is-invalid @enderror">
                                @error('join_date') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>
                            {{-- Status --}}
                            <div class="tr-form-group tr-flex-end">
                                <label class="tr-checkbox-wrapper">
                                    <input type="checkbox" name="active" value="1" {{ old('active','1')=='1' ? 'checked':'' }}>
                                    <div class="tr-checkbox-box"></div>
                                    <span class="tr-label-text">Karyawan Aktif</span>
                                </label>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Catatan Tambahan</label>
                            <textarea name="notes" rows="3" class="tr-textarea @error('notes') is-invalid @enderror" placeholder="Keterangan tambahan jika ada...">{{ old('notes') }}</textarea>
                            @error('notes') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="tr-form-actions">
                        <a href="{{ route('sdm.karyawan.index') }}" class="tr-btn tr-btn-outline">Batalkan</a>
                        <button type="submit" class="tr-btn tr-btn-dark">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            --tr-teal: #0d9488;
            --tr-teal-light: #f0fdfa;
            --tr-danger: #ef4444;
            --tr-danger-bg: #fee2e2;
            --tr-radius-lg: 16px;
            --tr-radius-md: 10px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 4rem; }
        .tr-page { padding: 2rem 1rem; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }
        .tr-page-narrow { max-width: 760px; }

        .tr-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid var(--tr-border); padding-bottom: 1rem; }
        .tr-back-link { display: inline-flex; align-items: center; gap: 4px; font-size: 0.8rem; font-weight: 800; color: var(--tr-text-muted); text-decoration: none; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; transition: color 0.2s; }
        .tr-back-link:hover { color: var(--tr-teal); }
        .tr-title { font-size: 1.5rem; font-weight: 900; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-teal { background: var(--tr-teal-light); color: var(--tr-teal); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin-top: 4px; }

        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 1.5rem; }
        .tr-card-header { padding: 1rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-header-teal { border-left: 4px solid var(--tr-teal); }
        .tr-section-title { font-size: 0.95rem; font-weight: 800; color: var(--tr-text-main); margin: 0; text-transform: uppercase; letter-spacing: 0.05em; }

        .tr-form-main { padding: 1.5rem; }
        .tr-form-stack { display: flex; flex-direction: column; gap: 1.25rem; }
        .tr-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-flex-end { justify-content: flex-end; padding-bottom: 4px; }

        .tr-label { font-size: 0.8rem; font-weight: 800; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-req { color: var(--tr-danger); }
        .font-normal { font-weight: 500; text-transform: none; letter-spacing: 0; }

        .tr-input, .tr-textarea { width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--tr-border); border-radius: 8px; font-family: inherit; font-size: 0.9rem; color: var(--tr-text-main); background: #f8fafc; outline: none; transition: all 0.2s; }
        .tr-input:focus, .tr-textarea:focus { border-color: var(--tr-teal); background: #ffffff; box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1); }
        .tr-textarea { resize: vertical; min-height: 80px; }

        .tr-input-prefix-group { display: flex; align-items: stretch; }
        .tr-input-prefix-group .prefix { display: flex; align-items: center; padding: 0 0.75rem; background: var(--tr-border-light); border: 1px solid var(--tr-border); border-right: none; border-radius: 8px 0 0 8px; font-size: 0.85rem; font-weight: 800; color: var(--tr-text-muted); }
        .tr-input-prefix-group .tr-input { border-radius: 0 8px 8px 0; }

        .tr-salary-section { background: var(--tr-bg); padding: 1.25rem; border-radius: var(--tr-radius-md); border: 1px solid var(--tr-border-light); }
        .tr-salary-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--tr-text-muted); margin-bottom: 1rem; }

        .tr-checkbox-wrapper { display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none; }
        .tr-checkbox-wrapper input { display: none; }
        .tr-checkbox-box { width: 20px; height: 20px; border: 2px solid var(--tr-border); border-radius: 6px; background: #fff; position: relative; transition: all 0.2s; }
        .tr-checkbox-wrapper input:checked + .tr-checkbox-box { background: var(--tr-teal); border-color: var(--tr-teal); }
        .tr-checkbox-wrapper input:checked + .tr-checkbox-box::after { content: ''; position: absolute; left: 6px; top: 2px; width: 5px; height: 10px; border: solid white; border-width: 0 2.5px 2.5px 0; transform: rotate(45deg); }
        .tr-label-text { font-size: 0.85rem; font-weight: 800; color: var(--tr-text-main); }

        .tr-form-actions { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--tr-border-light); }
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0.7rem 1.5rem; border-radius: 8px; font-size: 0.85rem; font-family: inherit; font-weight: 700; cursor: pointer; text-decoration: none; transition: 0.2s; border: 1px solid transparent; }
        .tr-btn-dark { background: var(--tr-text-main); color: #fff; }
        .tr-btn-dark:hover { background: #000; transform: translateY(-1px); }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: #fff; }
        .tr-btn-outline:hover { background: #f8fafc; border-color: var(--tr-text-light); }

        .is-invalid { border-color: var(--tr-danger) !important; background: #fff5f5 !important; }
        .tr-error-msg { font-size: 0.75rem; color: var(--tr-danger); font-weight: 700; margin-top: 2px; }
        .tr-alert { display: flex; flex-direction: column; gap: 8px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); background: #fff5f5; border: 1px solid #fed7d7; color: #c53030; margin-bottom: 1.5rem; }
        .tr-alert-head { display: flex; align-items: center; gap: 8px; font-size: 0.9rem; }
        .tr-alert-list { margin: 0; padding-left: 2rem; font-size: 0.85rem; }

        .tr-text-light { color: var(--tr-text-light); }

        @media (max-width: 640px) {
            .tr-grid-2 { grid-template-columns: 1fr; gap: 1rem; }
            .tr-form-actions { flex-direction: column-reverse; }
            .tr-btn { width: 100%; }
        }
    </style>
    @endpush
</x-app-layout>
