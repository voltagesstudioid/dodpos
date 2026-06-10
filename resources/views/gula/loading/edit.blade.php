@extends('layouts.app')

@section('title', 'Edit Loading')
@section('page-title', 'Edit Loading')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<style>
    .glc-page { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 30%, #fff7ed 70%, #fffbeb 100%); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; padding: 30px; }
    .glc-header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%); border-radius: 24px; padding: 32px 40px; margin-bottom: 24px; position: relative; overflow: hidden; box-shadow: 0 15px 40px rgba(245,158,11,0.3); }
    .glc-header::before { content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); border-radius: 50%; }
    .glc-header::after { content: ''; position: absolute; bottom: -30%; left: 20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; }
    .glc-header-top { display: flex; align-items: center; justify-content: space-between; position: relative; z-index: 2; }
    .glc-header-left { display: flex; align-items: center; gap: 20px; }
    .glc-back { width: 48px; height: 48px; background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s; backdrop-filter: blur(10px); }
    .glc-back:hover { background: rgba(255,255,255,0.35); transform: translateX(-3px); }
    .glc-header-title h1 { font-size: 28px; font-weight: 700; color: white; margin: 0; text-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .glc-header-title p { font-size: 14px; color: rgba(255,255,255,0.85); margin: 4px 0 0; }
    .glc-header-badge { display: flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.25); border-radius: 16px; padding: 12px 20px; backdrop-filter: blur(10px); }
    .glc-header-badge-icon { width: 44px; height: 44px; background: rgba(255,255,255,0.25); border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .glc-header-badge-text { color: white; }
    .glc-header-badge-text span:first-child { display: block; font-size: 11px; opacity: 0.8; font-weight: 500; }
    .glc-header-badge-text span:last-child { display: block; font-size: 15px; font-weight: 700; }

    .glc-form { max-width: 800px; margin: 0 auto; }
    .glc-card { background: white; border-radius: 20px; box-shadow: 0 4px 25px rgba(0,0,0,0.06); margin-bottom: 20px; overflow: hidden; border: 1px solid rgba(245,158,11,0.15); transition: box-shadow 0.3s; }
    .glc-card:hover { box-shadow: 0 8px 35px rgba(0,0,0,0.1); }
    .glc-card-head { padding: 20px 28px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 14px; }
    .glc-card-head.amber { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); }
    .glc-card-head.green { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); }
    .glc-card-head.purple { background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); }
    .glc-card-head-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .glc-card-head-icon.amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .glc-card-head-icon.green { background: linear-gradient(135deg, #22c55e, #16a34a); }
    .glc-card-head-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .glc-card-head-text h3 { font-size: 16px; font-weight: 700; color: #111827; margin: 0; }
    .glc-card-head-text p { font-size: 12px; color: #6b7280; margin: 2px 0 0; }
    .glc-card-body { padding: 28px; }

    .glc-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .glc-row.full { grid-template-columns: 1fr; }
    .glc-row:last-child { margin-bottom: 0; }
    .glc-field label { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px; }
    .glc-field label .req { color: #ef4444; }
    .glc-field label svg { width: 16px; height: 16px; flex-shrink: 0; }
    .glc-field input,
    .glc-field select,
    .glc-field textarea { width: 100%; border: 2px solid #e5e7eb; border-radius: 12px; padding: 12px 16px; font-size: 14px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s; background: #fafafa; box-sizing: border-box; }
    .glc-field input:focus,
    .glc-field select:focus,
    .glc-field textarea:focus { outline: none; border-color: #f59e0b; background: white; box-shadow: 0 0 0 4px rgba(245,158,11,0.1); }
    .glc-field select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 40px; cursor: pointer; }
    .glc-field input.mono { font-family: 'JetBrains Mono', monospace; font-size: 13px; }
    .glc-field .err { color: #ef4444; font-size: 12px; margin-top: 6px; }
    .glc-field .hint { color: #9ca3af; font-size: 12px; margin-top: 6px; display: flex; align-items: center; gap: 4px; }

    .glc-qty-wrap { position: relative; }
    .glc-qty-unit { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 600; color: #9ca3af; background: #f3f4f6; padding: 4px 10px; border-radius: 6px; }
    .glc-qty-wrap input { padding-right: 60px; }

    .glc-status-group { display: flex; gap: 10px; flex-wrap: wrap; }
    .glc-status-opt { flex: 1; min-width: 100px; }
    .glc-status-opt input { display: none; }
    .glc-status-opt label { display: flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 14px; border-radius: 10px; border: 2px solid #e5e7eb; background: #fafafa; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .glc-status-opt input:checked + label { border-color: #f59e0b; background: #fffbeb; color: #92400e; box-shadow: 0 0 0 3px rgba(245,158,11,0.12); }
    .glc-status-opt label:hover { border-color: #d1d5db; background: #f3f4f6; }
    .glc-status-dot { width: 8px; height: 8px; border-radius: 50%; }
    .glc-status-dot.loading { background: #3b82f6; }
    .glc-status-dot.proses { background: #f59e0b; }
    .glc-status-dot.selesai { background: #10b981; }

    .glc-actions { display: flex; align-items: center; gap: 16px; margin-top: 8px; }
    .glc-btn-submit { display: inline-flex; align-items: center; gap: 10px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; padding: 14px 32px; border-radius: 14px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 6px 20px rgba(245,158,11,0.35); font-family: 'Plus Jakarta Sans', sans-serif; }
    .glc-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(245,158,11,0.45); }
    .glc-btn-cancel { display: inline-flex; align-items: center; gap: 10px; background: white; color: #6b7280; border: 2px solid #e5e7eb; padding: 14px 28px; border-radius: 14px; font-size: 15px; font-weight: 600; text-decoration: none; transition: all 0.2s; }
    .glc-btn-cancel:hover { background: #f9fafb; border-color: #d1d5db; color: #374151; }
    @media (max-width: 768px) {
        .glc-row { grid-template-columns: 1fr; }
        .glc-header-top { flex-direction: column; align-items: flex-start; gap: 16px; }
        .glc-status-group { flex-direction: column; }
    }
</style>
@endpush

@section('content')
<div class="glc-page">
    <div class="glc-header">
        <div class="glc-header-top">
            <div class="glc-header-left">
                <a href="{{ route('gula.loading.show', $loading) }}" class="glc-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                </a>
                <div class="glc-header-title">
                    <h1>Edit Loading</h1>
                    <p>Perbarui data loading untuk {{ $loading->sales->nama ?? 'sales' }}</p>
                </div>
            </div>
            <div class="glc-header-badge">
                <div class="glc-header-badge-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="glc-header-badge-text">
                    <span>Divisi</span>
                    <span>Gula</span>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('gula.loading.update', $loading) }}" method="POST" class="glc-form">
        @csrf
        @method('PUT')

        {{-- Card 1: Informasi Loading --}}
        <div class="glc-card">
            <div class="glc-card-head amber">
                <div class="glc-card-head-icon amber">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="glc-card-head-text">
                    <h3>Informasi Loading</h3>
                    <p>Data distribusi gula ke sales</p>
                </div>
            </div>
            <div class="glc-card-body">
                <div class="glc-row">
                    <div class="glc-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Tanggal <span class="req">*</span>
                        </label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $loading->tanggal->format('Y-m-d')) }}" required>
                        @error('tanggal')<div class="err">{{ $message }}</div>@enderror
                    </div>
                    <div class="glc-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"/><path d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Sales <span class="req">*</span>
                        </label>
                        <select name="sales_id" required>
                            <option value="">Pilih Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ old('sales_id', $loading->sales_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama }}{{ $s->no_kendaraan ? ' ('.$s->no_kendaraan.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('sales_id')<div class="err">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="glc-row full">
                    <div class="glc-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                            Produk <span class="req">*</span>
                        </label>
                        <select name="produk_id" id="sel-produk" required>
                            <option value="">Pilih Produk</option>
                            @foreach($produks as $p)
                                <option value="{{ $p->id }}" data-satuan="{{ $p->satuan }}" {{ old('produk_id', $loading->produk_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} — Stok Gudang: {{ number_format($p->stok_gudang) }} {{ $p->satuan }}
                                </option>
                            @endforeach
                        </select>
                        @error('produk_id')<div class="err">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="glc-row full">
                    <div class="glc-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                            Jumlah Loading <span class="req">*</span>
                        </label>
                        <div class="glc-qty-wrap">
                            <input type="number" name="jumlah_loading" value="{{ old('jumlah_loading', $loading->jumlah_loading) }}" required min="1" placeholder="Contoh: 500" class="mono">
                            <span class="glc-qty-unit" id="qty-unit">—</span>
                        </div>
                        @error('jumlah_loading')<div class="err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Status --}}
        <div class="glc-card">
            <div class="glc-card-head purple">
                <div class="glc-card-head-icon purple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="glc-card-head-text">
                    <h3>Status</h3>
                    <p>Status loading saat ini</p>
                </div>
            </div>
            <div class="glc-card-body">
                <div class="glc-status-group">
                    <div class="glc-status-opt">
                        <input type="radio" name="status" id="status-loading" value="loading" {{ old('status', $loading->status) === 'loading' ? 'checked' : '' }}>
                        <label for="status-loading"><span class="glc-status-dot loading"></span> Loading</label>
                    </div>
                    <div class="glc-status-opt">
                        <input type="radio" name="status" id="status-proses" value="proses" {{ old('status', $loading->status) === 'proses' ? 'checked' : '' }}>
                        <label for="status-proses"><span class="glc-status-dot proses"></span> Proses</label>
                    </div>
                    <div class="glc-status-opt">
                        <input type="radio" name="status" id="status-selesai" value="selesai" {{ old('status', $loading->status) === 'selesai' ? 'checked' : '' }}>
                        <label for="status-selesai"><span class="glc-status-dot selesai"></span> Selesai</label>
                    </div>
                </div>
                @error('status')<div class="err" style="margin-top:8px;">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Card 3: Keterangan --}}
        <div class="glc-card">
            <div class="glc-card-head green">
                <div class="glc-card-head-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div class="glc-card-head-text">
                    <h3>Keterangan</h3>
                    <p>Catatan tambahan untuk loading ini</p>
                </div>
            </div>
            <div class="glc-card-body">
                <div class="glc-row full">
                    <div class="glc-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                            Catatan (Opsional)
                        </label>
                        <textarea name="keterangan" rows="4" placeholder="Catatan tambahan (opsional)..." style="resize: none;">{{ old('keterangan', $loading->keterangan) }}</textarea>
                        @error('keterangan')<div class="err">{{ $message }}</div>@enderror
                        <div class="hint">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            Kosongkan jika tidak ada catatan khusus
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="glc-actions">
            <button type="submit" class="glc-btn-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Update Loading
            </button>
            <a href="{{ route('gula.loading.show', $loading) }}" class="glc-btn-cancel">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Batal
            </a>
        </div>
    </form>
</div>
@push('scripts')
<script>
(function() {
    var sel = document.getElementById('sel-produk');
    var unitEl = document.getElementById('qty-unit');
    sel.addEventListener('change', function() {
        var opt = this.options[this.selectedIndex];
        var satuan = opt && opt.dataset.satuan ? opt.dataset.satuan : '';
        unitEl.textContent = satuan || '—';
    });
    // Init on load with current product's satuan
    if (sel.value) sel.dispatchEvent(new Event('change'));
})();
</script>
@endpush
@endsection
