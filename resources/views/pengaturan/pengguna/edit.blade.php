<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna') }}
        </h2>
    </x-slot>

    <!-- Custom Simple & Neat Styles for Forms -->
    <style>
        .page-container {
            padding: 1.5rem 1rem;
            max-width: 800px;
            margin: 0 auto;
            font-family: inherit;
        }

        .alert-box {
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .alert-success { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        .header-title { font-size: 1.25rem; font-weight: 700; color: #111827; display: flex; align-items: center; gap: 0.5rem; }
        .header-subtitle { font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; }

        .card {
            background-color: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            color: #111827;
        }
        .card-body { padding: 1.5rem; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            white-space: nowrap;
            height: 38px;
        }
        .btn-primary { background-color: #2563eb; color: #ffffff; }
        .btn-primary:hover { background-color: #1d4ed8; }
        .btn-secondary { background-color: #ffffff; color: #374151; border-color: #d1d5db; }
        .btn-secondary:hover { background-color: #f3f4f6; color: #111827; }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }
        @media (min-width: 640px) {
            .form-grid.cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        .form-group { margin-bottom: 1.25rem; }
        .form-group:last-child { margin-bottom: 0; }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        .form-label .req { color: #ef4444; }
        .form-label .muted { font-size: 0.75rem; font-weight: normal; color: #9ca3af; }

        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: #111827;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            outline: none;
            transition: border-color 0.2s;
            line-height: 1.5;
            height: 38px;
            box-sizing: border-box;
        }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }
        .form-control.is-invalid { border-color: #ef4444; }
        
        select.form-control { padding-right: 2rem; }
        
        .error-text {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.35rem;
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            cursor: pointer;
        }
        .checkbox-input {
            width: 1rem;
            height: 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .checkbox-input:disabled { background-color: #f3f4f6; cursor: not-allowed; }
        .checkbox-wrap.disabled { cursor: not-allowed; opacity: 0.7; }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f3f4f6;
        }

        .section-divider {
            margin: 2rem 0 1.5rem;
            border-top: 1px dashed #e5e7eb;
            display: flex;
            align-items: flex-start;
        }
        .section-title {
            margin-top: -0.65rem;
            background-color: #ffffff;
            padding-right: 1rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .mvc-helper {
            padding: 1rem 1.25rem;
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            color: #475569;
            margin-top: 2rem;
            line-height: 1.5;
        }
        .mvc-helper strong { color: #1e293b; }
        .mvc-code { background: #ffffff; padding: 0.1rem 0.3rem; border: 1px solid #e2e8f0; border-radius: 3px; font-family: monospace; }
    </style>

    <div class="page-container">
        
        <div class="header-section">
            <div>
                <div class="header-title">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Edit Profil: {{ $pengguna->name }}
                </div>
                <div class="header-subtitle">Perbarui informasi dasar, hak akses, atau kata sandi akun ini.</div>
            </div>
            <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        @if(session('success')) 
            <div class="alert-box alert-success">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div> 
        @endif

        @if($errors->any())
            <div class="alert-box alert-danger">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Terdapat kesalahan pada formulir, mohon periksa kembali.</span>
            </div>
        @endif

        <div class="card">
            <div class="card-header">Detail Akun Staf</div>
            <div class="card-body">
                <form method="POST" action="{{ route('pengguna.update', $pengguna->id) }}">
                    @csrf @method('PUT')
                    
                    <div class="form-grid cols-2">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $pengguna->name) }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
                            @error('name') <div class="error-text">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Induk (NIK) <span class="req">*</span></label>
                            <input type="text" name="nik" value="{{ old('nik', $pengguna->nik) }}" class="form-control @error('nik') is-invalid @enderror" required>
                            @error('nik') <div class="error-text">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email / User ID <span class="req">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $pengguna->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email') <div class="error-text">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Fingerprint <span class="muted">(Opsional)</span></label>
                            <input type="text" name="fingerprint_id" value="{{ old('fingerprint_id', $pengguna->fingerprint_id) }}" class="form-control @error('fingerprint_id') is-invalid @enderror">
                            @error('fingerprint_id') <div class="error-text">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group" style="max-width: 50%;">
                        <label class="form-label">Hak Akses (Role) <span class="req">*</span></label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}" {{ old('role', $pengguna->role) == $r->name ? 'selected' : '' }}>
                                    {{ $r->label ?? strtoupper(str_replace('_', ' ', $r->name)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <div class="error-text">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="margin-top: 1.5rem;">
                        <label class="checkbox-wrap {{ auth()->id() === $pengguna->id ? 'disabled' : '' }}">
                            <input type="checkbox" name="active" value="1" 
                                {{ old('active', $pengguna->active) ? 'checked' : '' }}
                                {{ auth()->id() === $pengguna->id ? 'disabled' : '' }}
                                class="checkbox-input">
                            <span class="form-label" style="margin:0;">
                                Status Akun Aktif
                                @if(auth()->id() === $pengguna->id)
                                    <span class="muted" style="margin-left:0.25rem;">(Tidak dapat menonaktifkan akun sendiri)</span>
                                @endif
                            </span>
                        </label>
                        @if(auth()->id() === $pengguna->id)
                            <input type="hidden" name="active" value="1">
                        @endif
                    </div>

                    <div class="section-divider">
                        <span class="section-title">Pemulihan Keamanan</span>
                    </div>

                    <div class="form-grid cols-2">
                        <div class="form-group">
                            <label class="form-label">Sandi Baru <span class="muted">(Opsional, kosongkan jika tak diganti)</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                            @error('password') <div class="error-text">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ketik Ulang Sandi Baru</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mvc-helper">
            <div style="font-weight: 600; margin-bottom: 0.5rem; display:flex; align-items:center; gap:0.5rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Struktur Berkas Halaman Edit
            </div>
            <div style="margin-bottom:0.25rem;"><strong>File View:</strong> <span class="mvc-code">resources/views/pengaturan/pengguna/edit.blade.php</span></div>
            <div><strong>Controller:</strong> <span class="mvc-code">UserController@edit</span> &amp; <span class="mvc-code">UserController@update</span></div>
        </div>

    </div>
</x-app-layout>
