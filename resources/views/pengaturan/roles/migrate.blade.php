<x-app-layout>
    <x-slot name="header">Migrasi Role</x-slot>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">❌ {{ session('error') }}</div>   @endif

        <div class="panel" style="max-width:880px;margin:0 auto;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">🔁 Migrasi Role</div>
                    <div class="panel-subtitle">Pindahkan user dari role lama ke role baru</div>
                </div>
                <div style="display:flex;gap:0.75rem;flex-wrap:wrap;justify-content:flex-end;">
                    <a href="{{ route('pengaturan.roles.index') }}" class="btn-secondary">Kembali</a>
                </div>
            </div>

            <div class="panel-body">
                <form method="POST" action="{{ route('pengaturan.roles.migrate.store') }}">
                    @csrf

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Dari Role (key) <span class="required">*</span></label>
                            <input name="from" value="{{ old('from') }}" class="form-input" placeholder="contoh: admin2" required>
                            @error('from') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ke Role (aktif) <span class="required">*</span></label>
                            <select name="to" class="form-input" required>
                                <option value="" disabled {{ old('to') ? '' : 'selected' }}>Pilih role tujuan...</option>
                                @foreach($roles as $r)
                                    @if($r->active)
                                        <option value="{{ $r->key }}" {{ old('to') === $r->key ? 'selected' : '' }}>
                                            {{ $r->label }} ({{ $r->key }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('to') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group" style="flex-direction:row;align-items:center;gap:0.5rem;">
                        <input type="checkbox" name="include_requested_role" value="1" {{ old('include_requested_role') ? 'checked' : '' }}>
                        <label class="form-label" style="margin:0;">Ikut ubah requested_role</label>
                    </div>

                    <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                        <button type="submit" class="btn-primary" onclick="return confirm('Jalankan migrasi role?');">Jalankan Migrasi</button>
                    </div>
                </form>

                <div style="border-top:1px solid #f1f5f9;margin:1.25rem 0;"></div>

                <div style="display:grid;gap:1rem;">
                    <div>
                        <div style="font-weight:800;color:#0f172a;margin-bottom:0.5rem;">Role user yang tidak ada di master</div>
                        @if(count($unknownInUsersRole) === 0)
                            <div style="color:#94a3b8;">Tidak ada.</div>
                        @else
                            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                                @foreach($unknownInUsersRole as $k)
                                    <span class="badge badge-gray">{{ $k }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div>
                        <div style="font-weight:800;color:#0f172a;margin-bottom:0.5rem;">Requested role yang tidak ada di master</div>
                        @if(count($unknownInRequestedRole) === 0)
                            <div style="color:#94a3b8;">Tidak ada.</div>
                        @else
                            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                                @foreach($unknownInRequestedRole as $k)
                                    <span class="badge badge-gray">{{ $k }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

