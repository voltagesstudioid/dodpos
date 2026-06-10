@extends('layouts.app')

@section('title', 'Tambah Setoran')
@section('page-title', 'Tambah Setoran')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<style>
    .gsc-page { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 30%, #fff7ed 70%, #fffbeb 100%); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; padding: 30px; }
    .gsc-header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%); border-radius: 24px; padding: 32px 40px; margin-bottom: 24px; position: relative; overflow: hidden; box-shadow: 0 15px 40px rgba(245,158,11,0.3); }
    .gsc-header::before { content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); border-radius: 50%; }
    .gsc-header::after { content: ''; position: absolute; bottom: -30%; left: 20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; }
    .gsc-header-top { display: flex; align-items: center; justify-content: space-between; position: relative; z-index: 2; }
    .gsc-header-left { display: flex; align-items: center; gap: 20px; }
    .gsc-back { width: 48px; height: 48px; background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s; backdrop-filter: blur(10px); }
    .gsc-back:hover { background: rgba(255,255,255,0.35); transform: translateX(-3px); }
    .gsc-header-title h1 { font-size: 28px; font-weight: 700; color: white; margin: 0; text-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .gsc-header-title p { font-size: 14px; color: rgba(255,255,255,0.85); margin: 4px 0 0; }
    .gsc-header-badge { display: flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.25); border-radius: 16px; padding: 12px 20px; backdrop-filter: blur(10px); }
    .gsc-header-badge-icon { width: 44px; height: 44px; background: rgba(255,255,255,0.25); border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .gsc-header-badge-text { color: white; }
    .gsc-header-badge-text span:first-child { display: block; font-size: 11px; opacity: 0.8; font-weight: 500; }
    .gsc-header-badge-text span:last-child { display: block; font-size: 15px; font-weight: 700; }

    .gsc-form { max-width: 800px; margin: 0 auto; }
    .gsc-card { background: white; border-radius: 20px; box-shadow: 0 4px 25px rgba(0,0,0,0.06); margin-bottom: 20px; overflow: hidden; border: 1px solid rgba(245,158,11,0.15); transition: box-shadow 0.3s; }
    .gsc-card:hover { box-shadow: 0 8px 35px rgba(0,0,0,0.1); }
    .gsc-card-head { padding: 20px 28px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 14px; }
    .gsc-card-head.amber { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); }
    .gsc-card-head.green { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); }
    .gsc-card-head-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .gsc-card-head-icon.amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .gsc-card-head-icon.green { background: linear-gradient(135deg, #22c55e, #16a34a); }
    .gsc-card-head-text h3 { font-size: 16px; font-weight: 700; color: #111827; margin: 0; }
    .gsc-card-head-text p { font-size: 12px; color: #6b7280; margin: 2px 0 0; }
    .gsc-card-body { padding: 28px; }

    .gsc-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .gsc-row.full { grid-template-columns: 1fr; }
    .gsc-row:last-child { margin-bottom: 0; }
    .gsc-field label { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px; }
    .gsc-field label .req { color: #ef4444; }
    .gsc-field label svg { width: 16px; height: 16px; flex-shrink: 0; }
    .gsc-field input,
    .gsc-field select,
    .gsc-field textarea { width: 100%; border: 2px solid #e5e7eb; border-radius: 12px; padding: 12px 16px; font-size: 14px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s; background: #fafafa; box-sizing: border-box; }
    .gsc-field input:focus,
    .gsc-field select:focus,
    .gsc-field textarea:focus { outline: none; border-color: #f59e0b; background: white; box-shadow: 0 0 0 4px rgba(245,158,11,0.1); }
    .gsc-field select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 40px; cursor: pointer; }
    .gsc-field input.mono { font-family: 'JetBrains Mono', monospace; font-size: 13px; }
    .gsc-field .err { color: #ef4444; font-size: 12px; margin-top: 6px; }
    .gsc-field .hint { color: #9ca3af; font-size: 12px; margin-top: 6px; display: flex; align-items: center; gap: 4px; }

    .gsc-rupiah-wrap { display: flex; align-items: center; gap: 8px; }
    .gsc-rupiah-prefix { font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 600; color: #d97706; background: #fffbeb; padding: 10px 14px; border-radius: 10px; border: 2px solid #fde68a; flex-shrink: 0; }
    .gsc-rupiah-wrap input { flex: 1; }

    .gsc-info-box { background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 2px solid #fde68a; border-radius: 16px; padding: 20px 24px; }
    .gsc-info-box-title { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #92400e; margin-bottom: 12px; }
    .gsc-info-box-title svg { width: 18px; height: 18px; }
    .gsc-info-box p { font-size: 13px; color: #a16207; margin: 0; line-height: 1.6; }
    .gsc-info-box ul { margin: 8px 0 0; padding-left: 20px; }
    .gsc-info-box ul li { font-size: 13px; color: #a16207; margin-bottom: 4px; }

    .gsc-actions { display: flex; align-items: center; gap: 16px; margin-top: 8px; }
    .gsc-btn-submit { display: inline-flex; align-items: center; gap: 10px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; padding: 14px 32px; border-radius: 14px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 6px 20px rgba(245,158,11,0.35); font-family: 'Plus Jakarta Sans', sans-serif; }
    .gsc-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(245,158,11,0.45); }
    .gsc-btn-cancel { display: inline-flex; align-items: center; gap: 10px; background: white; color: #6b7280; border: 2px solid #e5e7eb; padding: 14px 28px; border-radius: 14px; font-size: 15px; font-weight: 600; text-decoration: none; transition: all 0.2s; }
    .gsc-btn-cancel:hover { background: #f9fafb; border-color: #d1d5db; color: #374151; }
    @media (max-width: 768px) {
        .gsc-row { grid-template-columns: 1fr; }
        .gsc-header-top { flex-direction: column; align-items: flex-start; gap: 16px; }
    }
</style>
@endpush

@section('content')
<div class="gsc-page">
    <div class="gsc-header">
        <div class="gsc-header-top">
            <div class="gsc-header-left">
                <a href="{{ route('gula.setoran.index') }}" class="gsc-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                </a>
                <div class="gsc-header-title">
                    <h1>Tambah Setoran</h1>
                    <p>Setoran harian dari sales gula</p>
                </div>
            </div>
            <div class="gsc-header-badge">
                <div class="gsc-header-badge-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="gsc-header-badge-text">
                    <span>Divisi</span>
                    <span>Gula</span>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('gula.setoran.store') }}" method="POST" class="gsc-form">
        @csrf

        {{-- Card 1: Informasi Setoran --}}
        <div class="gsc-card">
            <div class="gsc-card-head amber">
                <div class="gsc-card-head-icon amber">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="gsc-card-head-text">
                    <h3>Informasi Setoran</h3>
                    <p>Tanggal, sales, dan total setoran</p>
                </div>
            </div>
            <div class="gsc-card-body">
                <div class="gsc-row">
                    <div class="gsc-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Tanggal <span class="req">*</span>
                        </label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')<div class="err">{{ $message }}</div>@enderror
                    </div>
                    <div class="gsc-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"/><path d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Sales <span class="req">*</span>
                        </label>
                        <select name="sales_id" required>
                            <option value="">Pilih Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ old('sales_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama }}{{ $s->no_kendaraan ? ' ('.$s->no_kendaraan.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('sales_id')<div class="err">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="gsc-row full">
                    <div class="gsc-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            Total Setoran <span class="req">*</span>
                        </label>
                        <div class="gsc-rupiah-wrap">
                            <span class="gsc-rupiah-prefix">Rp</span>
                            <input type="number" name="total_setor" value="{{ old('total_setor') }}" required min="0" class="mono" placeholder="0">
                        </div>
                        @error('total_setor')<div class="err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Catatan --}}
        <div class="gsc-card">
            <div class="gsc-card-head green">
                <div class="gsc-card-head-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div class="gsc-card-head-text">
                    <h3>Catatan Sales</h3>
                    <p>Catatan tambahan dari sales (opsional)</p>
                </div>
            </div>
            <div class="gsc-card-body">
                <div class="gsc-row full">
                    <div class="gsc-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                            Catatan (Opsional)
                        </label>
                        <textarea name="catatan_sales" rows="4" placeholder="Catatan tambahan dari sales..." style="resize: none;">{{ old('catatan_sales') }}</textarea>
                        @error('catatan_sales')<div class="err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="gsc-card">
            <div class="gsc-card-body">
                <div class="gsc-info-box">
                    <div class="gsc-info-box-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Informasi Penting
                    </div>
                    <p>Sistem akan otomatis menghitung data berikut berdasarkan penjualan terverifikasi pada tanggal yang dipilih:</p>
                    <ul>
                        <li>Total penjualan dan jumlah transaksi</li>
                        <li>Total hutang baru dan jumlah hutang baru</li>
                        <li>Selisih antara total setor dan total penjualan</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="gsc-actions">
            <button type="submit" class="gsc-btn-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Simpan Setoran
            </button>
            <a href="{{ route('gula.setoran.index') }}" class="gsc-btn-cancel">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
