<x-app-layout>
    <x-slot name="header">Edit Karyawan</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page tr-page-narrow">

            {{-- ─── HEADER & NAVIGATION ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <a href="{{ route('sdm.karyawan.index') }}" class="tr-back-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali ke Direktori
                    </a>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-teal">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </div>
                        Edit Biodata Karyawan
                    </h1>
                    <p class="tr-subtitle">Perbarui informasi personal, jabatan, dan detail penggajian karyawan.</p>
                </div>
            </div>

            {{-- ─── ERROR ALERTS ─── --}}
            @if($errors->any())
                <div class="tr-alert tr-alert-danger">
                    <div class="tr-alert-head">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <strong>Terdapat kesalahan input:</strong>
                    </div>
                    <ul class="tr-alert-list">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- ─── LINKED ACCOUNT SECTION ─── --}}
            <div class="tr-card tr-card-account">
                <div class="tr-card-header">
                    <h2 class="tr-section-title">Akun Sistem Tertaut</h2>
                </div>
                <div class="tr-card-body">
                    @if($karyawan->user)
                        <div class="tr-account-strip">
                            <div class="tr-acc-info">
                                <div class="tr-acc-name">{{ $karyawan->user->name }}</div>
                                <div class="tr-acc-email">{{ $karyawan->user->email }}</div>
                                <div class="tr-acc-badges">
                                    <span class="tr-badge-sm tr-badge-gray">{{ $karyawan->user->role ?? 'No Role' }}</span>
                                    @if(($karyawan->user->active ?? 1) == 1)
                                        <span class="tr-badge-sm tr-badge-success">Akun Aktif</span>
                                    @else
                                        <span class="tr-badge-sm tr-badge-danger">Akun Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('pengguna.edit', $karyawan->user) }}" class="tr-btn-sm tr-btn-outline">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                                Kelola Login
                            </a>
                        </div>
                    @else
                        <div class="tr-account-empty">
                            <span class="tr-text-muted">Karyawan ini belum memiliki akun untuk login ke sistem.</span>
                            <a href="{{ route('pengguna.create', ['name' => $karyawan->name]) }}" class="tr-btn-sm tr-btn-teal">Buatkan Akun Baru</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ─── BIODATA FORM ─── --}}
            <div class="tr-card">
                <div class="tr-card-header tr-header-teal">
                    <h2 class="tr-section-title">Data Personal & Pekerjaan</h2>
                </div>
                
                <form method="POST" action="{{ route('sdm.karyawan.update', $karyawan) }}" class="tr-form-main">
                    @csrf
                    @method('PUT')

                    <div class="tr-form-stack">
                        {{-- Nama --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Nama Lengkap Karyawan <span class="tr-req">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $karyawan->name) }}" class="tr-input @error('name') is-invalid @enderror" required autofocus>
                            @error('name') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>

                        <div class="tr-grid-2">
                            {{-- No HP --}}
                            <div class="tr-form-group">
                                <label class="tr-label">No. Handphone / WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone', $karyawan->phone) }}" class="tr-input @error('phone') is-invalid @enderror" placeholder="Cth: 081234567xx">
                                @error('phone') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>
                            {{-- Jabatan --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Jabatan Struktural</label>
                                <input type="text" name="position" value="{{ old('position', $karyawan->position) }}" class="tr-input @error('position') is-invalid @enderror" placeholder="Cth: Kasir, Admin Gudang">
                                @error('position') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="tr-grid-2">
                            {{-- Gaji Pokok --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Gaji Pokok <span class="tr-text-light font-normal">(Bulanan)</span></label>
                                <div class="tr-input-prefix-group">
                                    <span class="prefix">Rp</span>
                                    <input type="number" name="basic_salary" value="{{ old('basic_salary', $karyawan->basic_salary) }}" class="tr-input @error('basic_salary') is-invalid @enderror" placeholder="0" min="0">
                                </div>
                                @error('basic_salary') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>
                            {{-- Uang Makan --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Uang Kehadiran <span class="tr-text-light font-normal">(Per Hari)</span></label>
                                <div class="tr-input-prefix-group">
                                    <span class="prefix">Rp</span>
                                    <input type="number" name="daily_allowance" value="{{ old('daily_allowance', $karyawan->daily_allowance) }}" class="tr-input @error('daily_allowance') is-invalid @enderror" placeholder="0" min="0">
                                </div>
                                @error('daily_allowance') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="tr-grid-2">
                            {{-- Tgl Masuk --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Tanggal Mulai Bekerja</label>
                                <input type="date" name="join_date" value="{{ old('join_date', optional($karyawan->join_date)->format('Y-m-d')) }}" class="tr-input @error('join_date') is-invalid @enderror">
                                @error('join_date') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>
                            {{-- Status Aktif --}}
                            <div class="tr-form-group tr-flex-end">
                                <label class="tr-checkbox-wrapper">
                                    <input type="checkbox" name="active" value="1" {{ old('active', $karyawan->active ? '1' : '0') == '1' ? 'checked' : '' }}>
                                    <div class="tr-checkbox-box"></div>
                                    <span class="tr-label-text">Status Karyawan Aktif</span>
                                </label>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Catatan Tambahan / Keterangan</label>
                            <textarea name="notes" rows="3" class="tr-textarea @error('notes') is-invalid @enderror" placeholder="Informasi pendukung mengenai karyawan...">{{ old('notes', $karyawan->notes) }}</textarea>
                            @error('notes') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- SUBMIT AREA --}}
                    <div class="tr-form-actions">
                        <a href="{{ route('sdm.karyawan.index') }}" class="tr-btn tr-btn-outline">Batalkan</a>
                        <button type="submit" class="tr-btn tr-btn-dark">Simpan Perubahan</button>
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

        /* HEADER */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid var(--tr-border); padding-bottom: 1rem; }
        .tr-back-link { display: inline-flex; align-items: center; gap: 4px; font-size: 0.8rem; font-weight: 800; color: var(--tr-text-muted); text-decoration: none; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; transition: color 0.2s; }
        .tr-back-link:hover { color: var(--tr-teal); }
        .tr-title { font-size: 1.5rem; font-weight: 900; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-teal { background: var(--tr-teal-light); color: var(--tr-teal); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin-top: 4px; }

        /* CARD */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 1.5rem; }
        .tr-card-header { padding: 1rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-header-teal { border-left: 4px solid var(--tr-teal); }
        .tr-card-body { padding: 1.5rem; }
        .tr-section-title { font-size: 0.95rem; font-weight: 800; color: var(--tr-text-main); margin: 0; text-transform: uppercase; letter-spacing: 0.05em; }

        /* ACCOUNT STRIP */
        .tr-account-strip { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
        .tr-acc-name { font-weight: 800; font-size: 1rem; color: var(--tr-text-main); }
        .tr-acc-email { font-size: 0.85rem; color: var(--tr-text-muted); margin-top: 2px; }
        .tr-acc-badges { display: flex; gap: 6px; margin-top: 6px; }
        .tr-badge-sm { font-size: 0.65rem; font-weight: 800; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-badge-gray { background: var(--tr-bg); color: var(--tr-text-muted); border: 1px solid var(--tr-border); }
        .tr-badge-success { background: var(--tr-teal-light); color: var(--tr-teal); }
        .tr-badge-danger { background: var(--tr-danger-bg); color: var(--tr-danger); }
        .tr-account-empty { display: flex; justify-content: space-between; align-items: center; width: 100%; font-size: 0.85rem; }

        /* FORM */
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

        /* PREFIX GROUP (RP) */
        .tr-input-prefix-group { display: flex; align-items: stretch; }
        .tr-input-prefix-group .prefix { display: flex; align-items: center; padding: 0 0.75rem; background: var(--tr-border-light); border: 1px solid var(--tr-border); border-right: none; border-radius: 8px 0 0 8px; font-size: 0.85rem; font-weight: 800; color: var(--tr-text-muted); }
        .tr-input-prefix-group .tr-input { border-radius: 0 8px 8px 0; }

        /* CUSTOM CHECKBOX */
        .tr-checkbox-wrapper { display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none; }
        .tr-checkbox-wrapper input { display: none; }
        .tr-checkbox-box { width: 20px; height: 20px; border: 2px solid var(--tr-border); border-radius: 6px; background: #fff; position: relative; transition: all 0.2s; }
        .tr-checkbox-wrapper input:checked + .tr-checkbox-box { background: var(--tr-teal); border-color: var(--tr-teal); }
        .tr-checkbox-wrapper input:checked + .tr-checkbox-box::after { content: ''; position: absolute; left: 6px; top: 2px; width: 5px; height: 10px; border: solid white; border-width: 0 2.5px 2.5px 0; transform: rotate(45deg); }
        .tr-label-text { font-size: 0.85rem; font-weight: 800; color: var(--tr-text-main); }

        /* ACTIONS */
        .tr-form-actions { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--tr-border-light); }
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0.7rem 1.5rem; border-radius: 8px; font-size: 0.85rem; font-family: inherit; font-weight: 700; cursor: pointer; text-decoration: none; transition: 0.2s; border: 1px solid transparent; }
        .tr-btn-dark { background: var(--tr-text-main); color: #fff; }
        .tr-btn-dark:hover { background: #000; transform: translateY(-1px); }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: #fff; }
        .tr-btn-outline:hover { background: #f8fafc; border-color: var(--tr-text-light); }
        .tr-btn-teal { background: var(--tr-teal); color: #fff; }
        .tr-btn-teal:hover { background: var(--tr-teal-hover); }
        .tr-btn-sm { padding: 0.4rem 0.75rem; font-size: 0.75rem; border-radius: 6px; }
        .tr-btn-danger-outline { border-color: var(--tr-danger); color: var(--tr-danger); background: transparent; }

        .is-invalid { border-color: var(--tr-danger) !important; background: #fff5f5 !important; }
        .tr-error-msg { font-size: 0.75rem; color: var(--tr-danger); font-weight: 700; margin-top: 2px; }

        .tr-alert { display: flex; flex-direction: column; gap: 8px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); background: #fff5f5; border: 1px solid #fed7d7; color: #c53030; margin-bottom: 1.5rem; }
        .tr-alert-head { display: flex; align-items: center; gap: 8px; font-size: 0.9rem; }
        .tr-alert-list { margin: 0; padding-left: 2rem; font-size: 0.85rem; }

        @media (max-width: 640px) {
            .tr-grid-2 { grid-template-columns: 1fr; gap: 1rem; }
            .tr-account-strip { flex-direction: column; align-items: flex-start; }
            .tr-account-empty { flex-direction: column; align-items: flex-start; gap: 10px; }
            .tr-form-actions { flex-direction: column-reverse; }
            .tr-btn { width: 100%; }
        }
    </style>
    @endpush
</x-app-layout>