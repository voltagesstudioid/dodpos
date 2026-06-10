@extends('layouts.app')

@section('title', 'Profil Sales Belum Lengkap')
@section('page-title', 'Profil Sales Belum Lengkap')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .np-page { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 30%, #fff7ed 70%, #fffbeb 100%); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; padding: 30px; display: flex; align-items: center; justify-content: center; }
    .np-card { background: white; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); padding: 48px; max-width: 500px; width: 100%; text-align: center; }
    .np-icon { width: 80px; height: 80px; background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
    .np-title { font-size: 22px; font-weight: 700; color: #111827; margin-bottom: 12px; }
    .np-desc { font-size: 14px; color: #6b7280; line-height: 1.6; margin-bottom: 32px; }
    .np-steps { text-align: left; background: #fffbeb; border: 2px solid #fde68a; border-radius: 14px; padding: 20px; margin-bottom: 32px; }
    .np-steps-title { font-size: 13px; font-weight: 700; color: #92400e; margin-bottom: 12px; }
    .np-step { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 10px; }
    .np-step:last-child { margin-bottom: 0; }
    .np-step-num { width: 24px; height: 24px; background: #f59e0b; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex-shrink: 0; }
    .np-step-text { font-size: 13px; color: #374151; line-height: 1.5; padding-top: 2px; }
    .np-btn { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 12px 28px; border-radius: 12px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.3s; box-shadow: 0 4px 14px rgba(245,158,11,0.3); }
    .np-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(245,158,11,0.4); }
</style>
@endpush

@section('content')
<div class="np-page">
    <div class="np-card">
        <div class="np-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        </div>
        <h2 class="np-title">Profil Sales Belum Ditemukan</h2>
        <p class="np-desc">Akun Anda sudah aktif, tetapi profil sales Pasukan Garuda belum dibuat. Hubungi supervisor untuk mendaftarkan profil Anda.</p>
        <div class="np-steps">
            <div class="np-steps-title">Langkah selanjutnya:</div>
            <div class="np-step">
                <div class="np-step-num">1</div>
                <div class="np-step-text">Supervisor membuka menu <strong>Data Sales</strong> di Pasukan Garuda</div>
            </div>
            <div class="np-step">
                <div class="np-step-num">2</div>
                <div class="np-step-text">Supervisor menambahkan data sales dengan menghubungkan akun Anda</div>
            </div>
            <div class="np-step">
                <div class="np-step-num">3</div>
                <div class="np-step-text">Setelah profil dibuat, refresh halaman ini untuk mengakses dashboard</div>
            </div>
        </div>
        <a href="{{ route('dashboard') }}" class="np-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
