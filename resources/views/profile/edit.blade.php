<x-app-layout>
    <x-slot name="header">Profil Saya</x-slot>

    <div class="page-container">
        @php $u = auth()->user(); @endphp

        <div class="page-container" style="max-width: 1000px; margin: 0 auto; padding-top: 1.5rem;">
            <div class="page-header" style="background: transparent; box-shadow: none; padding: 0 0 1.5rem 0; border-bottom: 1px solid #e2e8f0; margin-bottom: 2rem;">
                <div>
                    <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0; letter-spacing: -0.02em;">Profil Saya</h1>
                    <div style="color: #64748b; font-size: 0.95rem;">Kelola informasi akun, email, dan foto profil Anda.</div>
                </div>
                <div class="page-header-actions">
                    <span class="badge badge-indigo" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">Role: {{ ucfirst($u->role ?? 'User') }}</span>
                    @if($u instanceof \Illuminate\Contracts\Auth\MustVerifyEmail)
                        @if($u->hasVerifiedEmail())
                            <span class="badge badge-success" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">✓ Email Verified</span>
                        @else
                            <span class="badge badge-warning" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">⚠️ Email Unverified</span>
                        @endif
                    @endif
                </div>
            </div>

            <div class="profile-grid">
                <div class="profile-sidebar">
                    <div class="panel" style="border: none; box-shadow: 0 10px 30px -5px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04); border-radius: 20px; text-align: center; padding: 2.5rem 1.5rem; background: linear-gradient(to bottom, #ffffff, #f8fafc);">
                        @php
                            $cardPhotoUrl = $u->profile_photo_path ? route('profile.photo', $u->id) : null;
                        @endphp
                        
                        <div style="position: relative; display: inline-block; margin-bottom: 1.5rem;">
                            @if($cardPhotoUrl)
                                <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 4px solid #fff; box-shadow: 0 4px 14px rgba(0,0,0,0.1); margin: 0 auto;">
                                    <img src="{{ $cardPhotoUrl }}" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @else
                                <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #a855f7); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 3rem; border: 4px solid #fff; box-shadow: 0 4px 14px rgba(0,0,0,0.1); margin: 0 auto;">
                                    {{ strtoupper(substr($u->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div style="position: absolute; bottom: 5px; right: 5px; background: #10b981; width: 20px; height: 20px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" title="Active"></div>
                        </div>

                        <h2 style="margin: 0 0 0.5rem 0; font-size: 1.4rem; font-weight: 800; color: #0f172a;">{{ $u->name ?? '-' }}</h2>
                        <div style="color: #64748b; font-size: 0.95rem; margin-bottom: 1.5rem;">{{ $u->email ?? '-' }}</div>

                        <div style="display: flex; justify-content: center; gap: 0.5rem;">
                            <span class="badge badge-gray" style="font-weight: 600;">ID: {{ str_pad($u->id ?? 0, 4, '0', STR_PAD_LEFT) }}</span>
                            <span class="badge badge-blue" style="font-weight: 600;">{{ ucfirst($u->role ?? 'Staff') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="profile-content">
                    <div class="panel" style="border: none; box-shadow: 0 4px 20px -5px rgba(0,0,0,0.05); border-radius: 20px; overflow: hidden;">
                        <div class="panel-header" style="background: transparent; border-bottom: 1px solid #f1f5f9; padding: 1.5rem 1.75rem;">
                            <div>
                                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.5rem;">
                                    <svg xmlns="http://www.w3.org/Dom/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #6366f1;"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    Informasi Personal
                                </h3>
                                <div style="color: #64748b; font-size: 0.9rem; margin-top: 0.25rem;">Perbarui data probadi, email, dan pasang foto terbaik Anda.</div>
                            </div>
                        </div>
                        <div class="panel-body" style="padding: 1.75rem; background: #fff;">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-grid { display: grid; grid-template-columns: 300px 1fr; gap: 2rem; align-items: start; }
        @media (max-width: 900px) { .profile-grid { grid-template-columns: 1fr; } }
        .page-container { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>
