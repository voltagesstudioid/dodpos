<x-app-layout>
    <x-slot name="header">Edit Role</x-slot>

    <style>
        .rl-wrap {
            padding: 1.5rem 1rem;
            max-width: 720px;
            margin: 0 auto;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }
        .rl-back {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #6b7280;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.25rem;
            transition: color 0.2s;
        }
        .rl-back:hover { color: #6366f1; }
        .rl-title { font-size: 1.375rem; font-weight: 800; color: #111827; margin: 0 0 0.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .rl-key-tag { display: inline-flex; padding: 0.2rem 0.5rem; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 0.375rem; font-family: 'JetBrains Mono', monospace; font-size: 0.8125rem; font-weight: 600; color: #475569; }
        .rl-subtitle { font-size: 0.8125rem; color: #6b7280; margin: 0 0 1.5rem; }

        .rl-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; margin-bottom: 1.5rem; }
        .rl-card-hd { padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; border-left: 3px solid #6366f1; }
        .rl-card-hd h3 { font-size: 0.9375rem; font-weight: 700; color: #111827; margin: 0; text-transform: uppercase; letter-spacing: 0.04em; }

        .rl-info-strip {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 0.625rem;
            margin-bottom: 1.25rem;
            font-size: 0.8125rem;
            color: #92400e;
        }
        .rl-info-strip svg { flex-shrink: 0; }

        .rl-form { padding: 1.5rem; }
        .rl-form-stack { display: flex; flex-direction: column; gap: 1.25rem; }
        .rl-group { display: flex; flex-direction: column; gap: 0.375rem; }
        .rl-label { font-size: 0.8rem; font-weight: 700; color: #111827; text-transform: uppercase; letter-spacing: 0.04em; }
        .rl-req { color: #ef4444; }
        .rl-input, .rl-textarea {
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
        .rl-input:focus, .rl-textarea:focus { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .rl-textarea { resize: vertical; min-height: 80px; }
        .rl-hint { font-size: 0.75rem; color: #9ca3af; }
        .rl-error { font-size: 0.75rem; color: #ef4444; font-weight: 600; }

        .rl-check {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.625rem 0.875rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background 0.15s;
        }
        .rl-check:hover { background: #f3f4f6; }
        .rl-check input[type="checkbox"] { width: 18px; height: 18px; accent-color: #6366f1; cursor: pointer; }
        .rl-check span { font-size: 0.875rem; font-weight: 600; color: #111827; }

        .rl-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f3f4f6;
        }
        .rl-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.5rem;
            font-size: 0.8125rem;
            font-weight: 700;
            border-radius: 0.5rem;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            font-family: inherit;
        }
        .rl-btn-primary { background: #6366f1; color: #fff; }
        .rl-btn-primary:hover { background: #4f46e5; box-shadow: 0 2px 8px rgba(99,102,241,0.25); }
        .rl-btn-ghost { background: #fff; color: #6b7280; border-color: #d1d5db; }
        .rl-btn-ghost:hover { background: #f9fafb; }
        .rl-btn-danger { background: #dc2626; color: #fff; }
        .rl-btn-danger:hover { background: #b91c1c; }

        .rl-alert {
            padding: 0.875rem 1.25rem;
            margin-bottom: 1.25rem;
            border-radius: 0.625rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .rl-alert-err { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .rl-alert-ok { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

        .rl-usage-box {
            padding: 1rem 1.5rem;
        }
        .rl-usage-label { font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.5rem; }
        .rl-usage-val { font-size: 1.25rem; font-weight: 700; color: #111827; font-family: 'JetBrains Mono', monospace; }
        .rl-usage-sub { font-size: 0.75rem; color: #9ca3af; }
    </style>

    <div class="rl-wrap">
        @if(session('error'))
            <div class="rl-alert rl-alert-err">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <a href="{{ route('pengaturan.roles.index') }}" class="rl-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
        </a>

        <h1 class="rl-title">Edit Role <span class="rl-key-tag">{{ $role->key }}</span></h1>
        <p class="rl-subtitle">Perbarui informasi dan konfigurasi role ini.</p>

        @php $userCount = \App\Models\User::where('role', $role->key)->count(); @endphp

        @if($userCount > 0)
        <div class="rl-info-strip">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Role ini digunakan oleh <strong>{{ $userCount }}</strong> pengguna. Mengubah key akan diblokir. Gunakan <a href="{{ route('pengaturan.roles.migrate') }}" style="color:#6366f1;font-weight:700;">Migrasi Role</a> untuk memindahkan pengguna.</span>
        </div>
        @endif

        <div class="rl-card">
            <div class="rl-card-hd">
                <h3>Informasi Role</h3>
            </div>

            @if($userCount > 0)
            <div class="rl-usage-box">
                <div class="rl-usage-label">Digunakan oleh</div>
                <div class="rl-usage-val">{{ $userCount }} <span style="font-size:0.875rem;font-weight:500;color:#6b7280;">pengguna</span></div>
                <div class="rl-usage-sub">Key role tidak dapat diubah selama masih digunakan.</div>
            </div>
            @endif

            <form method="POST" action="{{ route('pengaturan.roles.update', $role) }}" class="rl-form">
                @csrf
                @method('PUT')
                <div class="rl-form-stack">
                    <div class="rl-group">
                        <label class="rl-label">Key <span class="rl-req">*</span></label>
                        <input type="text" name="key" value="{{ old('key', $role->key) }}" class="rl-input @error('key') is-invalid @enderror" {{ $userCount > 0 ? 'readonly style=background:#f1f5f9;cursor:not-allowed;' : '' }} required>
                        <div class="rl-hint">Huruf kecil, angka, dan underscore saja (a-z, 0-9, _).</div>
                        @error('key') <div class="rl-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="rl-group">
                        <label class="rl-label">Label <span class="rl-req">*</span></label>
                        <input type="text" name="label" value="{{ old('label', $role->label) }}" class="rl-input @error('label') is-invalid @enderror" required>
                        @error('label') <div class="rl-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="rl-group">
                        <label class="rl-label">Deskripsi</label>
                        <textarea name="description" class="rl-textarea @error('description') is-invalid @enderror" placeholder="Deskripsi singkat role ini (opsional)...">{{ old('description', $role->description) }}</textarea>
                        @error('description') <div class="rl-error">{{ $message }}</div> @enderror
                    </div>

                    <label class="rl-check">
                        <input type="checkbox" name="active" value="1" {{ old('active', $role->active) ? 'checked' : '' }}>
                        <span>Aktif</span>
                    </label>
                </div>

                <div class="rl-actions">
                    <a href="{{ route('pengaturan.roles.index') }}" class="rl-btn rl-btn-ghost">Batal</a>
                    <button type="submit" class="rl-btn rl-btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        {{-- Delete section for non-protected roles --}}
        @if($role->key !== 'supervisor' && $userCount === 0)
        <div class="rl-card" style="border-color:#fca5a5;">
            <div class="rl-card-hd" style="border-left-color:#ef4444;">
                <h3 style="color:#dc2626;">Zona Berbahaya</h3>
            </div>
            <div style="padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-weight:700;color:#111827;font-size:0.875rem;">Hapus Role Ini</div>
                    <div style="font-size:0.8125rem;color:#6b7280;">Tindakan ini tidak dapat dibatalkan.</div>
                </div>
                <form action="{{ route('pengaturan.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Hapus permanen role \'{{ $role->key }}\'?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="rl-btn rl-btn-danger">Hapus Role</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
