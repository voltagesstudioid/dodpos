@props([
    'title' => '',
    'subtitle' => '',
])

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    :root {
        --color-bg-dark: #050505;
        --color-glass: rgba(18, 18, 20, 0.45);
        --color-glass-border: rgba(255, 255, 255, 0.08);
        --color-primary: #6366f1;
        --color-primary-light: #818cf8;
        --color-secondary: #0ea5e9;
        --color-accent: #f43f5e;
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: var(--color-bg-dark);
        margin: 0;
        color: white;
        background-image: 
            radial-gradient(circle at 15% 50%, rgba(99, 102, 241, 0.15), transparent 25%),
            radial-gradient(circle at 85% 30%, rgba(14, 165, 233, 0.15), transparent 25%);
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        position: relative;
        overflow: hidden;
    }

    /* Ambient Animated Orbs */
    .orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(100px);
        opacity: 0.4;
        animation: orbFloat 15s ease-in-out infinite alternate;
        z-index: 0;
    }
    .orb-1 {
        width: 700px; height: 700px;
        background: radial-gradient(circle, var(--color-primary), #4c1d95);
        top: -150px; left: -100px;
        animation-duration: 20s;
    }
    .orb-2 {
        width: 600px; height: 600px;
        background: radial-gradient(circle, var(--color-secondary), #0369a1);
        bottom: -200px; right: -50px;
        animation-duration: 25s;
        animation-delay: -5s;
    }
    .orb-3 {
        width: 400px; height: 400px;
        background: radial-gradient(circle, var(--color-accent), #881337);
        top: 40%; left: 30%;
        animation-duration: 18s;
        opacity: 0.2;
    }

    @keyframes orbFloat {
        0% { transform: translate(0, 0) scale(1) rotate(0deg); }
        50% { transform: translate(50px, -50px) scale(1.1) rotate(45deg); }
        100% { transform: translate(-30px, 30px) scale(0.9) rotate(-45deg); }
    }

    /* Noise Texture Overlay */
    .noise-overlay {
        position: absolute;
        inset: 0;
        z-index: 1;
        opacity: 0.03;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        pointer-events: none;
    }

    /* Split Screen Layout */
    .branding-panel {
        display: none;
        flex: 1;
        flex-direction: column;
        justify-content: center;
        padding: 5rem;
        position: relative;
        z-index: 10;
        border-right: 1px solid var(--color-glass-border);
        background: linear-gradient(to right, rgba(0,0,0,0.8), transparent);
    }
    @media (min-width: 1024px) {
        .branding-panel { display: flex; }
    }

    .brand-logo-wrap {
        position: absolute;
        top: 3rem;
        left: 4rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .brand-logo-icon {
        width: 52px; height: 52px;
        background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.02));
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .brand-logo-icon svg {
        width: 28px; height: 28px;
        fill: url(#brand-gradient);
    }
    .brand-name {
        font-size: 1.75rem;
        font-weight: 800;
        color: white;
        letter-spacing: -0.03em;
    }
    .brand-name span { 
        background: linear-gradient(to right, #a5b4fc, #7dd3fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .branding-hero {
        max-width: 560px;
    }
    
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 100px;
        padding: 0.5rem 1.25rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #e2e8f0;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 2rem;
        backdrop-filter: blur(10px);
    }
    .hero-badge-dot {
        width: 8px; height: 8px;
        background: #34d399;
        border-radius: 50%;
        box-shadow: 0 0 12px #34d399;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.4); }
    }

    .hero-title {
        font-size: clamp(3rem, 5vw, 4.5rem);
        font-weight: 800;
        line-height: 1.05;
        color: white;
        letter-spacing: -0.04em;
        margin-bottom: 1.5rem;
    }
    .hero-title .gradient-text {
        background: linear-gradient(135deg, white 0%, #a5b4fc 50%, #67e8f9 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .hero-desc {
        font-size: 1.125rem;
        color: #94a3b8;
        line-height: 1.8;
        margin-bottom: 3.5rem;
        font-weight: 300;
    }

    /* Glass Effect UI Elements in Branding */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    .bento-item {
        background: linear-gradient(135deg, rgba(255,255,255,0.06), rgba(255,255,255,0.01));
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        padding: 1.5rem;
        backdrop-filter: blur(20px);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.4s ease;
    }
    .bento-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        border-color: rgba(255,255,255,0.15);
    }
    .bento-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .bento-title {
        font-size: 1rem;
        font-weight: 600;
        color: white;
        margin-bottom: 0.25rem;
    }
    .bento-desc {
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.5;
    }

    .branding-footer {
        position: absolute;
        bottom: 3rem;
        left: 4rem;
        color: #475569;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Login Form Side */
    .form-panel {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
        z-index: 10;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(40px);
        -webkit-backdrop-filter: blur(40px);
    }

    .login-card {
        width: 100%;
        max-width: 440px;
        background: var(--color-glass);
        border: 1px solid var(--color-glass-border);
        border-radius: 32px;
        padding: 3rem;
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.7),
            inset 0 1px 0 rgba(255,255,255,0.1);
        animation: cardEnter 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    @keyframes cardEnter {
        to { opacity: 1; transform: translateY(0); }
    }

    .card-header {
        margin-bottom: 2.5rem;
    }
    .card-title {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        letter-spacing: -0.03em;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }
    .card-subtitle {
        font-size: 1rem;
        color: #94a3b8;
        font-weight: 400;
    }

    /* Form Inputs Overrides */
    .form-group { margin-bottom: 1.5rem; }
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #cbd5e1;
        margin-bottom: 0.6rem;
    }
    .input-wrap { position: relative; }
    .input-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        pointer-events: none;
        transition: color 0.3s;
    }
    .form-input {
        width: 100%;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 1rem 1rem 1rem 3rem;
        font-size: 1rem;
        color: white;
        outline: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-sizing: border-box;
        font-family: inherit;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
    }
    .form-input::placeholder { color: #475569; font-weight: 300; }
    .form-input:hover {
        border-color: rgba(255,255,255,0.15);
        background: rgba(0, 0, 0, 0.4);
    }
    .form-input:focus {
        border-color: var(--color-primary-light);
        background: rgba(0, 0, 0, 0.5);
        box-shadow: 
            inset 0 2px 4px rgba(0,0,0,0.2),
            0 0 0 4px rgba(99, 102, 241, 0.15);
    }
    .form-input:focus ~ .input-icon {
        color: var(--color-primary-light);
    }
    .form-input.has-toggle { padding-right: 3.5rem; }

    .toggle-pw {
        position: absolute;
        right: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #64748b;
        padding: 0;
        display: flex;
        transition: color 0.2s;
    }
    .toggle-pw:hover { color: #e2e8f0; }

    .form-error {
        margin-top: 0.6rem;
        font-size: 0.8rem;
        color: #fb7185;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-weight: 500;
        animation: errorShake 0.4s ease;
    }
    @keyframes errorShake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }

    .form-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1.5rem;
        margin-bottom: 2rem;
    }

    .remember-label {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        cursor: pointer;
        font-size: 0.875rem;
        color: #94a3b8;
        font-weight: 400;
        user-select: none;
    }
    /* Custom Checkbox */
    .remember-label input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 1.25rem; height: 1.25rem;
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 6px;
        cursor: pointer;
        display: grid;
        place-content: center;
        transition: all 0.2s;
    }
    .remember-label input[type="checkbox"]::before {
        content: "";
        width: 0.65rem; height: 0.65rem;
        clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        transform: scale(0);
        background-color: white;
        transition: 0.2s transform ease-in-out;
    }
    .remember-label input[type="checkbox"]:checked {
        background: var(--color-primary);
        border-color: var(--color-primary);
    }
    .remember-label input[type="checkbox"]:checked::before {
        transform: scale(1);
    }

    .forgot-link {
        font-size: 0.875rem;
        color: var(--color-primary-light);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
    }
    .forgot-link:hover { color: white; text-shadow: 0 0 8px rgba(129, 140, 248, 0.5); }

    .submit-btn {
        width: 100%;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, var(--color-primary), #4f46e5);
        border: 1px solid rgba(255,255,255,0.1);
        border-top-color: rgba(255,255,255,0.2);
        border-radius: 16px;
        font-size: 1.05rem;
        font-weight: 600;
        color: white;
        cursor: pointer;
        letter-spacing: 0.02em;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        box-shadow: 
            0 10px 25px -5px rgba(99, 102, 241, 0.5),
            inset 0 1px 0 rgba(255,255,255,0.2);
        font-family: inherit;
    }
    .submit-btn:hover { 
        transform: translateY(-2px); 
        box-shadow: 
            0 15px 35px -5px rgba(99, 102, 241, 0.6),
            inset 0 1px 0 rgba(255,255,255,0.3);
        filter: brightness(1.1);
    }
    .submit-btn:active { transform: translateY(0); }

    .session-status {
        border-radius: 12px;
        padding: 1rem 1.25rem;
        font-size: 0.9rem;
        margin-bottom: 2rem;
        line-height: 1.5;
        backdrop-filter: blur(10px);
    }
    .session-status.success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #34d399;
    }
    .session-status.error {
        background: rgba(244, 63, 94, 0.1);
        border: 1px solid rgba(244, 63, 94, 0.2);
        color: #fb7185;
    }

    .divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 2rem 0;
    }
    .divider-line { flex: 1; height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent); }
    .divider-text { font-size: 0.8rem; color: #64748b; font-weight: 500; }

    .card-footer {
        text-align: center;
        font-size: 0.85rem;
        color: #64748b;
    }
    .card-footer a { color: #94a3b8; transition: color 0.2s; }
    .card-footer a:hover { color: white; }

    /* Mobile adjustments */
    @media (max-width: 1023px) {
        .login-card { padding: 2rem 1.5rem; border-radius: 24px; }
        .card-title { font-size: 1.75rem; }
        .orb { opacity: 0.6; filter: blur(70px); }
        .brand-logo-wrap { display: none; } /* Hide logo on mobile login if preferred, or position it top center */
    }
</style>

<svg width="0" height="0" class="hidden">
    <defs>
        <linearGradient id="brand-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#ffffff" />
            <stop offset="100%" stop-color="#a5b4fc" />
        </linearGradient>
    </defs>
</svg>

<div class="login-wrapper">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="noise-overlay"></div>

    <!-- Left Branding Panel -->
    <div class="branding-panel">
        <div class="brand-logo-wrap">
            <div class="brand-logo-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 3h8v8H3zm10 0h8v8h-8zM3 13h8v8H3zm13 0a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"/>
                </svg>
            </div>
            <span class="brand-name">DOD<span>POS</span></span>
        </div>

        <div class="branding-hero">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                Next-Gen ERP & POS
            </div>
            <h1 class="hero-title">
                Sistem Bisnis<br>
                <span class="gradient-text">Era Digital.</span>
            </h1>
            <p class="hero-desc">
                Tingkatkan efisiensi dan visibilitas operasi Anda. Kelola multi-cabang, pergudangan, armada, hingga analitik data secara terpusat.
            </p>

            <div class="bento-grid">
                <div class="bento-item">
                    <div class="bento-icon" style="background: rgba(99,102,241,0.1); color: #818cf8;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    </div>
                    <div class="bento-title">Smart Warehouse</div>
                    <div class="bento-desc">Otomatisasi lacak stok & PO</div>
                </div>
                <div class="bento-item">
                    <div class="bento-icon" style="background: rgba(14,165,233,0.1); color: #38bdf8;">
                       <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    </div>
                    <div class="bento-title">Live Analytics</div>
                    <div class="bento-desc">Insight data real-time</div>
                </div>
            </div>
        </div>

        <div class="branding-footer">
            &copy; {{ date('Y') }} DODPOS Enterprise. All rights reserved.
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="form-panel">
        <div class="login-card">
            <div class="card-header">
                <h2 class="card-title">{{ $title ?: 'Welcome Back' }}</h2>
                <p class="card-subtitle">{{ $subtitle ?: 'Silakan masuk ke akun Anda untuk melanjutkan.' }}</p>
            </div>

            @if (session('status'))
                <div class="session-status success">{{ session('status') }}</div>
            @endif

            @if (session('error'))
                <div class="session-status error">{{ session('error') }}</div>
            @endif

            {{ $slot }}

            @isset($after)
                {{ $after }}
            @endisset

            <div class="card-footer">
                @isset($footer)
                    {{ $footer }}
                @else
                    Mengalami kendala? <a href="#" style="text-decoration: underline">Hubungi IT Support</a>
                @endisset
            </div>
        </div>
    </div>
</div>

