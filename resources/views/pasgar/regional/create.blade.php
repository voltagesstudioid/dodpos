@extends('layouts.app', ['title' => 'Tambah Regional Pasgar'])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .prc-wrap { font-family: 'Plus Jakarta Sans', sans-serif; max-width: 600px; margin: 0 auto; padding: 1.25rem; }
    .prc-nav { display: flex; align-items: center; gap: 0.5rem; font-size: 0.78rem; color: #64748b; margin-bottom: 1.25rem; font-weight: 600; }
    .prc-nav a { color: #6366f1; text-decoration: none; font-weight: 700; }
    .prc-nav a:hover { text-decoration: underline; }
    .prc-nav .sep { color: #cbd5e1; }

    .prc-card { background: #fff; border: 1px solid #e0e7ff; border-radius: 14px; padding: 1.5rem; margin-bottom: 1rem; }
    .prc-card-hdr { display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.25rem; }
    .prc-card-ico { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    .prc-card-ico.indigo { background: #eef2ff; }
    .prc-card-title { font-size: 0.9rem; font-weight: 800; color: #1e1b4b; }

    .prc-fg { margin-bottom: 1rem; }
    .prc-lbl { display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.4rem; }
    .prc-lbl .req { color: #ef4444; }
    .prc-inp, .prc-sel, .prc-txt { width: 100%; padding: 0.65rem 0.85rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.82rem; font-family: inherit; color: #1e293b; background: #fff; box-sizing: border-box; transition: border-color 0.2s; }
    .prc-inp:focus, .prc-sel:focus, .prc-txt:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    .prc-txt { resize: vertical; min-height: 80px; }
    .prc-hint { font-size: 0.7rem; color: #94a3b8; margin-top: 0.25rem; }
    .prc-err { font-size: 0.7rem; color: #ef4444; margin-top: 0.25rem; }

    .prc-actions { display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem; }
    .prc-btn { padding: 0.7rem 1.5rem; border-radius: 10px; font-size: 0.82rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; }
    .prc-btn-ghost { background: #fff; color: #475569; border: 1px solid #cbd5e1; }
    .prc-btn-ghost:hover { background: #f8fafc; }
    .prc-btn-primary { background: linear-gradient(135deg, #6366f1, #4338ca); color: #fff; box-shadow: 0 3px 10px rgba(79,70,229,0.2); }
    .prc-btn-primary:hover { box-shadow: 0 5px 16px rgba(79,70,229,0.3); transform: translateY(-1px); }
</style>
@endpush

@section('content')
<div class="prc-wrap">

    <div class="prc-nav">
        <a href="{{ route('pasgar.regional.index') }}">Regional</a>
        <span class="sep">/</span>
        <span>Tambah Regional Baru</span>
    </div>

    <form action="{{ route('pasgar.regional.store') }}" method="POST">
        @csrf

        <div class="prc-card">
            <div class="prc-card-hdr">
                <div class="prc-card-ico indigo">🗺️</div>
                <div class="prc-card-title">Informasi Regional</div>
            </div>

            <div class="prc-fg">
                <label class="prc-lbl">Nama Regional <span class="req">*</span></label>
                <input type="text" name="nama" class="prc-inp @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Jakarta Utara" required>
                <div class="prc-hint">Nama area kerja atau wilayah penjualan</div>
                @error('nama')<div class="prc-err">{{ $message }}</div>@enderror
            </div>

            <div class="prc-fg">
                <label class="prc-lbl">Deskripsi <span style="color:#94a3b8;font-weight:600;">(Opsional)</span></label>
                <textarea name="deskripsi" class="prc-txt @error('deskripsi') is-invalid @enderror" placeholder="Deskripsi area regional...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<div class="prc-err">{{ $message }}</div>@enderror
            </div>

            <div class="prc-fg">
                <label class="prc-lbl">Status <span class="req">*</span></label>
                <select name="status" class="prc-sel" required>
                    <option value="aktif" {{ old('status')=='aktif'?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="prc-actions">
            <a href="{{ route('pasgar.regional.index') }}" class="prc-btn prc-btn-ghost">Batal</a>
            <button type="submit" class="prc-btn prc-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Regional
            </button>
        </div>
    </form>

</div>
@endsection
