@extends('layouts.app', ['title' => 'Edit Regional - ' . $regional->nama])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .pre-wrap { font-family: 'Plus Jakarta Sans', sans-serif; max-width: 600px; margin: 0 auto; padding: 1.25rem; }
    .pre-nav { display: flex; align-items: center; gap: 0.5rem; font-size: 0.78rem; color: #64748b; margin-bottom: 1.25rem; font-weight: 600; }
    .pre-nav a { color: #6366f1; text-decoration: none; font-weight: 700; }
    .pre-nav a:hover { text-decoration: underline; }
    .pre-nav .sep { color: #cbd5e1; }

    .pre-card { background: #fff; border: 1px solid #e0e7ff; border-radius: 14px; padding: 1.5rem; margin-bottom: 1rem; }
    .pre-card-hdr { display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.25rem; }
    .pre-card-ico { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    .pre-card-ico.indigo { background: #eef2ff; }
    .pre-card-title { font-size: 0.9rem; font-weight: 800; color: #1e1b4b; }
    .pre-code-badge { font-family: 'JetBrains Mono', monospace; font-size: 0.68rem; font-weight: 700; color: #6366f1; background: #eef2ff; padding: 0.2rem 0.6rem; border-radius: 6px; margin-left: auto; }

    .pre-fg { margin-bottom: 1rem; }
    .pre-lbl { display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.4rem; }
    .pre-lbl .req { color: #ef4444; }
    .pre-inp, .pre-sel, .pre-txt { width: 100%; padding: 0.65rem 0.85rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.82rem; font-family: inherit; color: #1e293b; background: #fff; box-sizing: border-box; transition: border-color 0.2s; }
    .pre-inp:focus, .pre-sel:focus, .pre-txt:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    .pre-txt { resize: vertical; min-height: 80px; }
    .pre-err { font-size: 0.7rem; color: #ef4444; margin-top: 0.25rem; }

    .pre-actions { display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem; }
    .pre-btn { padding: 0.7rem 1.5rem; border-radius: 10px; font-size: 0.82rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; }
    .pre-btn-ghost { background: #fff; color: #475569; border: 1px solid #cbd5e1; }
    .pre-btn-ghost:hover { background: #f8fafc; }
    .pre-btn-primary { background: linear-gradient(135deg, #6366f1, #4338ca); color: #fff; box-shadow: 0 3px 10px rgba(79,70,229,0.2); }
    .pre-btn-primary:hover { box-shadow: 0 5px 16px rgba(79,70,229,0.3); transform: translateY(-1px); }
</style>
@endpush

@section('content')
<div class="pre-wrap">

    <div class="pre-nav">
        <a href="{{ route('pasgar.regional.index') }}">Regional</a>
        <span class="sep">/</span>
        <span>Edit: {{ $regional->nama }}</span>
    </div>

    <form action="{{ route('pasgar.regional.update', $regional) }}" method="POST">
        @csrf @method('PUT')

        <div class="pre-card">
            <div class="pre-card-hdr">
                <div class="pre-card-ico indigo">🗺️</div>
                <div class="pre-card-title">Informasi Regional</div>
                <span class="pre-code-badge">{{ $regional->kode_regional }}</span>
            </div>

            <div class="pre-fg">
                <label class="pre-lbl">Nama Regional <span class="req">*</span></label>
                <input type="text" name="nama" class="pre-inp @error('nama') is-invalid @enderror" value="{{ old('nama', $regional->nama) }}" required>
                @error('nama')<div class="pre-err">{{ $message }}</div>@enderror
            </div>

            <div class="pre-fg">
                <label class="pre-lbl">Deskripsi <span style="color:#94a3b8;font-weight:600;">(Opsional)</span></label>
                <textarea name="deskripsi" class="pre-txt @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $regional->deskripsi) }}</textarea>
                @error('deskripsi')<div class="pre-err">{{ $message }}</div>@enderror
            </div>

            <div class="pre-fg">
                <label class="pre-lbl">Status <span class="req">*</span></label>
                <select name="status" class="pre-sel" required>
                    <option value="aktif" {{ old('status', $regional->status)=='aktif'?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $regional->status)=='nonaktif'?'selected':'' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="pre-actions">
            <a href="{{ route('pasgar.regional.index') }}" class="pre-btn pre-btn-ghost">Batal</a>
            <button type="submit" class="pre-btn pre-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection
