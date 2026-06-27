@extends('layouts.app', ['title' => 'Profil Sales Belum Lengkap'])

@push('styles')
<style>
    .np-wrap { max-width: 500px; margin: 2rem auto; animation: fadeSlideIn 0.4s ease; }
</style>
@endpush

@section('content')
<div class="np-wrap">
    <div class="panel" style="text-align:center;padding:2.5rem 2rem;">
        <div style="width:72px;height:72px;background:linear-gradient(135deg,#fef3c7,#fde68a);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        </div>
        <h2 style="font-size:1.25rem;font-weight:700;color:#1e293b;margin-bottom:0.5rem;">Profil Sales Belum Ditemukan</h2>
        <p style="font-size:0.875rem;color:#64748b;line-height:1.6;margin-bottom:1.5rem;">
            Akun Anda sudah aktif, tetapi profil sales Pasukan Garuda belum dibuat. Hubungi supervisor untuk mendaftarkan profil Anda.
        </p>
        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:1.25rem;text-align:left;margin-bottom:1.5rem;">
            <div style="font-size:0.75rem;font-weight:700;color:#92400e;margin-bottom:0.75rem;">Langkah selanjutnya:</div>
            <div style="display:flex;align-items:flex-start;gap:0.75rem;margin-bottom:0.625rem;">
                <div style="width:24px;height:24px;background:#f59e0b;color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;">1</div>
                <div style="font-size:0.8125rem;color:#374151;line-height:1.5;">Supervisor membuka menu <strong>Data Sales</strong> di Pasukan Garuda</div>
            </div>
            <div style="display:flex;align-items:flex-start;gap:0.75rem;margin-bottom:0.625rem;">
                <div style="width:24px;height:24px;background:#f59e0b;color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;">2</div>
                <div style="font-size:0.8125rem;color:#374151;line-height:1.5;">Supervisor menambahkan data sales dengan menghubungkan akun Anda</div>
            </div>
            <div style="display:flex;align-items:flex-start;gap:0.75rem;">
                <div style="width:24px;height:24px;background:#f59e0b;color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;">3</div>
                <div style="font-size:0.8125rem;color:#374151;line-height:1.5;">Setelah profil dibuat, refresh halaman ini untuk mengakses dashboard</div>
            </div>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-primary" style="padding:0.625rem 1.5rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
