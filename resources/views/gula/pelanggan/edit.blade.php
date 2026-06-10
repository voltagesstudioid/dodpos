@extends('layouts.app')

@section('title', 'Edit Pelanggan')
@section('page-title', 'Edit Pelanggan')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap');
    .gpe-page { background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 30%,#fff7ed 70%,#fffbeb 100%); min-height:100vh; font-family:'Plus Jakarta Sans',sans-serif; padding:30px; }
    .gpe-header { background:linear-gradient(135deg,#f59e0b 0%,#d97706 50%,#b45309 100%); border-radius:24px; padding:32px 40px; margin-bottom:24px; position:relative; overflow:hidden; box-shadow:0 15px 40px rgba(245,158,11,0.3); }
    .gpe-header::before { content:''; position:absolute; top:-50%; right:-10%; width:400px; height:400px; background:radial-gradient(circle,rgba(255,255,255,0.15) 0%,transparent 70%); border-radius:50%; }
    .gpe-header::after { content:''; position:absolute; bottom:-30%; left:20%; width:300px; height:300px; background:radial-gradient(circle,rgba(255,255,255,0.1) 0%,transparent 70%); border-radius:50%; }
    .gpe-header-top { display:flex; align-items:center; justify-content:space-between; position:relative; z-index:2; }
    .gpe-header-left { display:flex; align-items:center; gap:20px; }
    .gpe-back { width:48px; height:48px; background:rgba(255,255,255,0.2); border:2px solid rgba(255,255,255,0.3); border-radius:14px; display:flex; align-items:center; justify-content:center; color:white; text-decoration:none; transition:all 0.3s; backdrop-filter:blur(10px); }
    .gpe-back:hover { background:rgba(255,255,255,0.35); transform:translateX(-3px); }
    .gpe-header-title h1 { font-size:28px; font-weight:700; color:white; margin:0; text-shadow:0 2px 10px rgba(0,0,0,0.1); }
    .gpe-header-title p { font-size:14px; color:rgba(255,255,255,0.85); margin:4px 0 0; }
    .gpe-header-right { display:flex; align-items:center; gap:14px; }
    .gpe-code-badge { display:flex; align-items:center; gap:10px; background:rgba(255,255,255,0.2); border:2px solid rgba(255,255,255,0.25); border-radius:16px; padding:12px 20px; backdrop-filter:blur(10px); }
    .gpe-code-badge-icon { width:40px; height:40px; background:rgba(255,255,255,0.25); border-radius:10px; display:flex; align-items:center; justify-content:center; }
    .gpe-code-badge-text { font-family:'JetBrains Mono',monospace; font-size:15px; font-weight:600; color:white; letter-spacing:0.5px; }
    .gpe-div-badge { display:flex; align-items:center; gap:10px; background:rgba(255,255,255,0.2); border:2px solid rgba(255,255,255,0.25); border-radius:16px; padding:12px 20px; backdrop-filter:blur(10px); }
    .gpe-div-badge-icon { width:44px; height:44px; background:rgba(255,255,255,0.25); border-radius:12px; display:flex; align-items:center; justify-content:center; }
    .gpe-div-badge-text { color:white; }
    .gpe-div-badge-text span:first-child { display:block; font-size:11px; opacity:0.8; font-weight:500; }
    .gpe-div-badge-text span:last-child { display:block; font-size:15px; font-weight:700; }

    .gpe-form { max-width:1000px; margin:0 auto; }
    .gpe-card { background:white; border-radius:20px; box-shadow:0 4px 25px rgba(0,0,0,0.06); margin-bottom:20px; overflow:hidden; border:1px solid rgba(245,158,11,0.15); transition:box-shadow 0.3s; }
    .gpe-card:hover { box-shadow:0 8px 35px rgba(0,0,0,0.1); }
    .gpe-card-head { padding:20px 28px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; gap:14px; }
    .gpe-card-head.amber { background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%); }
    .gpe-card-head.green { background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%); }
    .gpe-card-head.purple { background:linear-gradient(135deg,#faf5ff 0%,#f3e8ff 100%); }
    .gpe-card-head-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .gpe-card-head-icon.amber { background:linear-gradient(135deg,#f59e0b,#d97706); }
    .gpe-card-head-icon.green { background:linear-gradient(135deg,#22c55e,#16a34a); }
    .gpe-card-head-icon.purple { background:linear-gradient(135deg,#a855f7,#9333ea); }
    .gpe-card-head-text h3 { font-size:16px; font-weight:700; color:#111827; margin:0; }
    .gpe-card-head-text p { font-size:12px; color:#6b7280; margin:2px 0 0; }
    .gpe-card-body { padding:28px; }

    .gpe-row { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
    .gpe-row.full { grid-template-columns:1fr; }
    .gpe-row.three { grid-template-columns:1fr 1fr 1fr; }
    .gpe-row:last-child { margin-bottom:0; }
    .gpe-field label { display:flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px; }
    .gpe-field label .req { color:#ef4444; }
    .gpe-field label svg { width:16px; height:16px; flex-shrink:0; }
    .gpe-field input, .gpe-field textarea { width:100%; border:2px solid #e5e7eb; border-radius:12px; padding:12px 16px; font-size:14px; font-family:'Plus Jakarta Sans',sans-serif; transition:all 0.2s; background:#fafafa; box-sizing:border-box; }
    .gpe-field input:focus, .gpe-field textarea:focus { outline:none; border-color:#f59e0b; background:white; box-shadow:0 0 0 4px rgba(245,158,11,0.1); }
    .gpe-field input.mono { font-family:'JetBrains Mono',monospace; font-size:13px; }
    .gpe-field .phone-wrap { display:flex; align-items:stretch; }
    .gpe-field .phone-prefix { display:flex; align-items:center; padding:0 14px; background:#f3f4f6; border:2px solid #e5e7eb; border-right:none; border-radius:12px 0 0 12px; font-size:13px; font-weight:600; color:#6b7280; font-family:'JetBrains Mono',monospace; }
    .gpe-field .phone-wrap input { border-radius:0 12px 12px 0; }
    .gpe-field .err { color:#ef4444; font-size:12px; margin-top:6px; }

    .gpe-radio-group { display:flex; flex-direction:column; gap:10px; }
    .gpe-radio-card { display:flex; align-items:center; gap:14px; padding:14px 16px; border:2px solid #e5e7eb; border-radius:14px; cursor:pointer; transition:all 0.2s; background:#fafafa; }
    .gpe-radio-card:hover { border-color:#d1d5db; background:#f9fafb; }
    .gpe-radio-card.selected { background:#fffbeb; border-color:#f59e0b; }
    .gpe-radio-card.selected[data-color="blue"] { background:#eff6ff; border-color:#3b82f6; }
    .gpe-radio-card.selected[data-color="purple"] { background:#faf5ff; border-color:#a855f7; }
    .gpe-radio-card.selected[data-color="orange"] { background:#fff7ed; border-color:#f97316; }
    .gpe-radio-card.selected[data-color="green"] { background:#f0fdf4; border-color:#22c55e; }
    .gpe-radio-card.selected[data-color="gray"] { background:#f9fafb; border-color:#6b7280; }
    .gpe-radio-card.selected[data-color="red"] { background:#fef2f2; border-color:#ef4444; }
    .gpe-radio-card input[type="radio"] { display:none; }
    .gpe-radio-card .rc-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .gpe-radio-card .rc-text { flex:1; }
    .gpe-radio-card .rc-text strong { display:block; font-size:14px; font-weight:600; color:#111827; }
    .gpe-radio-card .rc-text span { font-size:12px; color:#6b7280; }
    .gpe-radio-card .rc-check { width:22px; height:22px; border:2px solid #d1d5db; border-radius:50%; display:flex; align-items:center; justify-content:center; transition:all 0.2s; flex-shrink:0; }
    .gpe-radio-card.selected .rc-check { border-color:currentColor; background:currentColor; }
    .gpe-radio-card.selected .rc-check::after { content:''; width:8px; height:8px; background:white; border-radius:50%; }

    .gpe-actions { display:flex; align-items:center; gap:16px; margin-top:8px; }
    .gpe-btn-submit { display:inline-flex; align-items:center; gap:10px; background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%); color:white; border:none; padding:14px 32px; border-radius:14px; font-size:15px; font-weight:600; cursor:pointer; transition:all 0.3s; box-shadow:0 6px 20px rgba(245,158,11,0.35); font-family:'Plus Jakarta Sans',sans-serif; }
    .gpe-btn-submit:hover { transform:translateY(-2px); box-shadow:0 10px 30px rgba(245,158,11,0.45); }
    .gpe-btn-cancel { display:inline-flex; align-items:center; gap:10px; background:white; color:#6b7280; border:2px solid #e5e7eb; padding:14px 28px; border-radius:14px; font-size:15px; font-weight:600; text-decoration:none; transition:all 0.2s; }
    .gpe-btn-cancel:hover { background:#f9fafb; border-color:#d1d5db; color:#374151; }
    @media(max-width:768px) { .gpe-row { grid-template-columns:1fr; } .gpe-row.three { grid-template-columns:1fr; } .gpe-header-top { flex-direction:column; align-items:flex-start; gap:16px; } }
</style>
@endpush

@section('content')
<div class="gpe-page">
    <div class="gpe-header">
        <div class="gpe-header-top">
            <div class="gpe-header-left">
                <a href="{{ route('gula.pelanggan.index') }}" class="gpe-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                </a>
                <div class="gpe-header-title">
                    <h1>Edit Pelanggan</h1>
                    <p>Ubah data pelanggan yang sudah terdaftar</p>
                </div>
            </div>
            <div class="gpe-header-right">
                <div class="gpe-code-badge">
                    <div class="gpe-code-badge-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    </div>
                    <span class="gpe-code-badge-text">{{ $pelanggan->kode_pelanggan }}</span>
                </div>
                <div class="gpe-div-badge">
                    <div class="gpe-div-badge-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    </div>
                    <div class="gpe-div-badge-text">
                        <span>Divisi</span>
                        <span>Gula</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('gula.pelanggan.update', $pelanggan) }}" method="POST" class="gpe-form">
        @csrf
        @method('PUT')

        {{-- Card 1: Informasi Dasar --}}
        <div class="gpe-card">
            <div class="gpe-card-head amber">
                <div class="gpe-card-head-icon amber">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="gpe-card-head-text">
                    <h3>Informasi Dasar</h3>
                    <p>Data identitas pelanggan dan kontak</p>
                </div>
            </div>
            <div class="gpe-card-body">
                <div class="gpe-row full">
                    <div class="gpe-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            Nama Toko <span class="req">*</span>
                        </label>
                        <input type="text" name="nama_toko" value="{{ old('nama_toko', $pelanggan->nama_toko) }}" required placeholder="Contoh: Toko Maju Jaya">
                        @error('nama_toko')<div class="err">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="gpe-row">
                    <div class="gpe-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Nama Pemilik <span class="req">*</span>
                        </label>
                        <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik', $pelanggan->nama_pemilik) }}" required placeholder="Nama lengkap pemilik">
                        @error('nama_pemilik')<div class="err">{{ $message }}</div>@enderror
                    </div>
                    <div class="gpe-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            No HP / WhatsApp
                        </label>
                        <div class="phone-wrap">
                            <span class="phone-prefix">+62</span>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $pelanggan->no_hp) }}" placeholder="81234567890">
                        </div>
                        @error('no_hp')<div class="err">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="gpe-row full">
                    <div class="gpe-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email', $pelanggan->email) }}" placeholder="email@contoh.com">
                        @error('email')<div class="err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Informasi Lokasi --}}
        <div class="gpe-card">
            <div class="gpe-card-head green">
                <div class="gpe-card-head-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div class="gpe-card-head-text">
                    <h3>Informasi Lokasi</h3>
                    <p>Alamat lengkap dan koordinat pelanggan</p>
                </div>
            </div>
            <div class="gpe-card-body">
                <div class="gpe-row full">
                    <div class="gpe-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            Alamat Lengkap
                        </label>
                        <textarea name="alamat" rows="3" placeholder="Jl. Contoh No. 123, RT/RW" style="resize:none;">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                        @error('alamat')<div class="err">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="gpe-row">
                    <div class="gpe-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            Kecamatan
                        </label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan', $pelanggan->kecamatan) }}" placeholder="Nama kecamatan">
                    </div>
                    <div class="gpe-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/></svg>
                            Kota / Kabupaten
                        </label>
                        <input type="text" name="kota" value="{{ old('kota', $pelanggan->kota) }}" placeholder="Nama kota">
                    </div>
                </div>
                <div class="gpe-row">
                    <div class="gpe-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="22"/><polyline points="4 10 12 2 20 10"/><circle cx="12" cy="18" r="4"/></svg>
                            Latitude
                        </label>
                        <input type="number" step="any" name="latitude" value="{{ old('latitude', $pelanggan->latitude) }}" class="mono" placeholder="-6.123456">
                    </div>
                    <div class="gpe-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="22"/><polyline points="4 10 12 2 20 10"/><circle cx="12" cy="18" r="4"/></svg>
                            Longitude
                        </label>
                        <input type="number" step="any" name="longitude" value="{{ old('longitude', $pelanggan->longitude) }}" class="mono" placeholder="106.123456">
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Pengaturan --}}
        <div class="gpe-card">
            <div class="gpe-card-head purple">
                <div class="gpe-card-head-icon purple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                </div>
                <div class="gpe-card-head-text">
                    <h3>Pengaturan</h3>
                    <p>Tipe pelanggan, status, dan limit hutang</p>
                </div>
            </div>
            <div class="gpe-card-body">
                @php
                    $currentTipe = old('tipe', $pelanggan->tipe);
                    $currentStatus = old('status', $pelanggan->status);
                @endphp
                <div class="gpe-row three">
                    {{-- Tipe Pelanggan --}}
                    <div class="gpe-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            Tipe Pelanggan <span class="req">*</span>
                        </label>
                        <div class="gpe-radio-group" data-radio-group="tipe">
                            <label class="gpe-radio-card {{ $currentTipe == 'eceran' ? 'selected' : '' }}" data-color="blue" onclick="selectRadio(this,'tipe')">
                                <input type="radio" name="tipe" value="eceran" {{ $currentTipe == 'eceran' ? 'checked' : '' }}>
                                <div class="rc-icon" style="background:#dbeafe;"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div>
                                <div class="rc-text"><strong>Eceran</strong><span>Toko kecil / ritel</span></div>
                                <div class="rc-check" style="color:#3b82f6;"></div>
                            </label>
                            <label class="gpe-radio-card {{ $currentTipe == 'grosir' ? 'selected' : '' }}" data-color="purple" onclick="selectRadio(this,'tipe')">
                                <input type="radio" name="tipe" value="grosir" {{ $currentTipe == 'grosir' ? 'checked' : '' }}>
                                <div class="rc-icon" style="background:#f3e8ff;"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#a855f7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
                                <div class="rc-text"><strong>Grosir</strong><span>Pembelian besar</span></div>
                                <div class="rc-check" style="color:#a855f7;"></div>
                            </label>
                            <label class="gpe-radio-card {{ $currentTipe == 'agen' ? 'selected' : '' }}" data-color="orange" onclick="selectRadio(this,'tipe')">
                                <input type="radio" name="tipe" value="agen" {{ $currentTipe == 'agen' ? 'checked' : '' }}>
                                <div class="rc-icon" style="background:#ffedd5;"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                                <div class="rc-text"><strong>Agen</strong><span>Mitra distribusi</span></div>
                                <div class="rc-check" style="color:#f97316;"></div>
                            </label>
                        </div>
                        @error('tipe')<div class="err">{{ $message }}</div>@enderror
                    </div>

                    {{-- Status --}}
                    <div class="gpe-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Status <span class="req">*</span>
                        </label>
                        <div class="gpe-radio-group" data-radio-group="status">
                            <label class="gpe-radio-card {{ $currentStatus == 'aktif' ? 'selected' : '' }}" data-color="green" onclick="selectRadio(this,'status')">
                                <input type="radio" name="status" value="aktif" {{ $currentStatus == 'aktif' ? 'checked' : '' }}>
                                <div class="rc-icon" style="background:#dcfce7;"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                                <div class="rc-text"><strong>Aktif</strong><span>Bisa transaksi</span></div>
                                <div class="rc-check" style="color:#22c55e;"></div>
                            </label>
                            <label class="gpe-radio-card {{ $currentStatus == 'nonaktif' ? 'selected' : '' }}" data-color="gray" onclick="selectRadio(this,'status')">
                                <input type="radio" name="status" value="nonaktif" {{ $currentStatus == 'nonaktif' ? 'checked' : '' }}>
                                <div class="rc-icon" style="background:#f3f4f6;"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg></div>
                                <div class="rc-text"><strong>Nonaktif</strong><span>Sementara stop</span></div>
                                <div class="rc-check" style="color:#6b7280;"></div>
                            </label>
                            <label class="gpe-radio-card {{ $currentStatus == 'blacklist' ? 'selected' : '' }}" data-color="red" onclick="selectRadio(this,'status')">
                                <input type="radio" name="status" value="blacklist" {{ $currentStatus == 'blacklist' ? 'checked' : '' }}>
                                <div class="rc-icon" style="background:#fee2e2;"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg></div>
                                <div class="rc-text"><strong>Blacklist</strong><span>Diblokir</span></div>
                                <div class="rc-check" style="color:#ef4444;"></div>
                            </label>
                        </div>
                        @error('status')<div class="err">{{ $message }}</div>@enderror
                    </div>

                    {{-- Limit Hutang --}}
                    <div class="gpe-field">
                        <label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            Limit Hutang
                        </label>
                        <div style="background:#faf5ff; border:2px solid #e9d5ff; border-radius:14px; padding:16px;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="font-family:'JetBrains Mono',monospace; font-size:13px; font-weight:600; color:#9333ea; background:white; padding:8px 12px; border-radius:8px; border:1px solid #e9d5ff;">Rp</span>
                                <input type="number" name="limit_hutang" value="{{ old('limit_hutang', $pelanggan->limit_hutang) }}" placeholder="0" class="mono" style="flex:1; border:2px solid #e5e7eb; border-radius:10px; padding:10px 14px; font-size:14px; font-family:'JetBrains Mono',monospace; background:white;">
                            </div>
                            <p style="font-size:12px; color:#9ca3af; margin:8px 0 0;">Kosongkan jika tidak ada limit</p>
                        </div>
                        @error('limit_hutang')<div class="err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="gpe-actions">
            <button type="submit" class="gpe-btn-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Update Pelanggan
            </button>
            <a href="{{ route('gula.pelanggan.index') }}" class="gpe-btn-cancel">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function selectRadio(card, name) {
    var group = card.closest('.gpe-radio-group');
    group.querySelectorAll('.gpe-radio-card').forEach(function(c) { c.classList.remove('selected'); });
    card.classList.add('selected');
    card.querySelector('input[type="radio"]').checked = true;
}
</script>
@endpush
