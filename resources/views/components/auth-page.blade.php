@props([
    'title' => '',
    'subtitle' => '',
])

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    :root {
        --c-purple: #7c3aed;
        --c-pink: #ec4899;
        --c-orange: #f97316;
        --c-teal: #14b8a6;
        --c-yellow: #eab308;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fef9ef;
        min-height: 100vh;
        overflow: hidden;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        position: relative;
    }

    /* ===== FLOATING SHAPES ===== */
    .float-shape {
        position: absolute;
        border-radius: 50%;
        z-index: 0;
        pointer-events: none;
    }
    .shape-1 {
        width: 500px; height: 500px;
        background: linear-gradient(135deg, #a78bfa, #f472b6);
        top: -180px; left: -100px;
        opacity: 0.25;
        animation: floatA 12s ease-in-out infinite;
    }
    .shape-2 {
        width: 350px; height: 350px;
        background: linear-gradient(135deg, #fbbf24, #f97316);
        bottom: -120px; right: -60px;
        opacity: 0.2;
        animation: floatB 15s ease-in-out infinite;
    }
    .shape-3 {
        width: 200px; height: 200px;
        background: linear-gradient(135deg, #34d399, #06b6d4);
        top: 50%; left: 15%;
        opacity: 0.15;
        animation: floatC 10s ease-in-out infinite;
    }
    .shape-4 {
        width: 120px; height: 120px;
        background: linear-gradient(135deg, #f472b6, #fb923c);
        bottom: 30%; right: 10%;
        opacity: 0.2;
        animation: floatA 8s ease-in-out infinite reverse;
    }

    @keyframes floatA {
        0%, 100% { transform: translate(0, 0) scale(1) rotate(0deg); }
        50% { transform: translate(40px, -60px) scale(1.15) rotate(15deg); }
    }
    @keyframes floatB {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-50px, 40px) scale(1.1); }
    }
    @keyframes floatC {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(30px, 30px) scale(1.2); }
    }

    /* ===== DOTS PATTERN ===== */
    .dots-pattern {
        position: absolute;
        inset: 0;
        z-index: 0;
        background-image: radial-gradient(circle, rgba(124,58,237,0.08) 1.5px, transparent 1.5px);
        background-size: 30px 30px;
        pointer-events: none;
    }

    /* ===== BRANDING PANEL ===== */
    .branding-panel {
        display: none;
        flex: 1.2;
        flex-direction: column;
        justify-content: center;
        padding: 4rem;
        position: relative;
        z-index: 5;
        background: linear-gradient(160deg, #2e1065 0%, #4c1d95 30%, #6d28d9 60%, #7c3aed 100%);
        overflow: hidden;
    }
    @media (min-width: 1024px) {
        .branding-panel { display: flex; }
    }

    .branding-panel::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 20% 80%, rgba(236,72,153,0.2) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 20%, rgba(251,191,36,0.15) 0%, transparent 50%),
            radial-gradient(ellipse at 50% 50%, rgba(168,85,247,0.1) 0%, transparent 60%);
        z-index: 0;
    }

    .branding-panel::after {
        content: '';
        position: absolute;
        bottom: 0; right: 0;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(249,115,22,0.12) 0%, transparent 70%);
        z-index: 0;
    }

    .brand-content {
        position: relative;
        z-index: 2;
        max-width: 520px;
    }

    .brand-logo-wrap {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 3rem;
    }
    .brand-logo-icon {
        width: 56px; height: 56px;
        background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05));
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    }
    .brand-logo-icon svg {
        width: 30px; height: 30px;
        fill: white;
    }
    .brand-name {
        font-size: 1.85rem;
        font-weight: 800;
        color: white;
        letter-spacing: -0.04em;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    .brand-name span {
        background: linear-gradient(to right, #fbbf24, #f472b6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .branding-hero-title {
        font-size: clamp(2.5rem, 4.5vw, 3.8rem);
        font-weight: 800;
        line-height: 1.1;
        color: white;
        letter-spacing: -0.04em;
        margin-bottom: 1.2rem;
        text-shadow: 0 2px 20px rgba(0,0,0,0.15);
    }
    .branding-hero-title .highlight {
        background: linear-gradient(135deg, #fbbf24, #f472b6, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .branding-hero-desc {
        font-size: 1.05rem;
        color: rgba(255,255,255,0.7);
        line-height: 1.7;
        margin-bottom: 2.5rem;
        font-weight: 400;
    }

    /* ===== FEATURE PILLS ===== */
    .feature-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .feature-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 100px;
        padding: 0.5rem 1.2rem 0.5rem 0.8rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: rgba(255,255,255,0.85);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    .feature-pill:hover {
        background: rgba(255,255,255,0.14);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .pill-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    .pill-dot.purple { background: #a78bfa; box-shadow: 0 0 10px #a78bfa; }
    .pill-dot.pink { background: #f472b6; box-shadow: 0 0 10px #f472b6; }
    .pill-dot.teal { background: #34d399; box-shadow: 0 0 10px #34d399; }
    .pill-dot.orange { background: #fb923c; box-shadow: 0 0 10px #fb923c; }
    .pill-dot.yellow { background: #facc15; box-shadow: 0 0 10px #facc15; }

    /* ===== BRANDING FOOTER ===== */
    .branding-footer {
        position: absolute;
        bottom: 2.5rem;
        left: 4rem;
        z-index: 2;
        color: rgba(255,255,255,0.4);
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* ===== FORM PANEL ===== */
    .form-panel {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
        z-index: 5;
        background: rgba(254, 249, 239, 0.6);
        backdrop-filter: blur(20px);
    }

    .login-card {
        width: 100%;
        max-width: 420px;
        background: white;
        border-radius: 32px;
        padding: 2.5rem 2.75rem;
        box-shadow:
            0 30px 60px rgba(124, 58, 237, 0.08),
            0 10px 30px rgba(0, 0, 0, 0.04);
        animation: cardSlideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
        transform: translateY(30px);
        position: relative;
        overflow: hidden;
    }
    .login-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, #7c3aed, #ec4899, #f97316, #eab308);
        border-radius: 32px 32px 0 0;
    }
    @keyframes cardSlideUp {
        to { opacity: 1; transform: translateY(0); }
    }

    .card-header {
        margin-bottom: 2rem;
        text-align: center;
    }
    .card-header-icon {
        width: 56px; height: 56px;
        background: linear-gradient(135deg, #ede9fe, #fce7f3);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.25rem;
    }
    .card-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1e1b2e;
        letter-spacing: -0.03em;
        margin-bottom: 0.35rem;
        line-height: 1.2;
    }
    .card-subtitle {
        font-size: 0.9rem;
        color: #94a3b8;
        font-weight: 400;
    }

    /* ===== FORM STYLES ===== */
    .form-group { margin-bottom: 1.25rem; }
    .form-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-icon {
        position: absolute;
        left: 1rem;
        color: #cbd5e1;
        pointer-events: none;
        transition: color 0.3s;
        display: flex;
    }
    .form-input {
        width: 100%;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.85rem 1rem 0.85rem 2.8rem;
        font-size: 0.95rem;
        color: #1e293b;
        outline: none;
        transition: all 0.25s ease;
        font-family: inherit;
        font-weight: 500;
    }
    .form-input::placeholder { color: #94a3b8; font-weight: 400; }
    .form-input:hover {
        border-color: #cbd5e1;
        background: white;
    }
    .form-input:focus {
        border-color: #7c3aed;
        background: white;
        box-shadow: 0 0 0 4px rgba(124,58,237,0.1);
    }
    .form-input:focus ~ .input-icon {
        color: #7c3aed;
    }
    .form-input.has-toggle { padding-right: 3rem; }
    .form-input.error {
        border-color: #f43f5e;
        background: #fff1f2;
    }
    .form-input.error:focus {
        box-shadow: 0 0 0 4px rgba(244,63,94,0.1);
    }

    .toggle-pw {
        position: absolute;
        right: 1rem;
        background: none;
        border: none;
        cursor: pointer;
        color: #94a3b8;
        padding: 0;
        display: flex;
        transition: color 0.2s;
    }
    .toggle-pw:hover { color: #64748b; }

    .form-error {
        margin-top: 0.4rem;
        font-size: 0.78rem;
        color: #f43f5e;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-weight: 500;
        animation: shakeX 0.4s ease;
    }
    @keyframes shakeX {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-5px); }
        40% { transform: translateX(5px); }
        60% { transform: translateX(-3px); }
        80% { transform: translateX(3px); }
    }

    .form-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 1.25rem 0 1.5rem;
    }

    .remember-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
        user-select: none;
    }
    .remember-label input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 1.1rem; height: 1.1rem;
        background: #f1f5f9;
        border: 2px solid #cbd5e1;
        border-radius: 5px;
        cursor: pointer;
        display: grid;
        place-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .remember-label input[type="checkbox"]::before {
        content: "";
        width: 0.55rem; height: 0.55rem;
        clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        transform: scale(0);
        background-color: white;
        transition: 0.15s transform ease-in-out;
    }
    .remember-label input[type="checkbox"]:checked {
        background: #7c3aed;
        border-color: #7c3aed;
    }
    .remember-label input[type="checkbox"]:checked::before {
        transform: scale(1);
    }

    .forgot-link {
        font-size: 0.85rem;
        color: #7c3aed;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }
    .forgot-link:hover {
        color: #6d28d9;
        text-decoration: underline;
    }

    .submit-btn {
        width: 100%;
        padding: 0.9rem 1.5rem;
        background: linear-gradient(135deg, #7c3aed, #a855f7, #ec4899);
        background-size: 200% 200%;
        border: none;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 700;
        color: white;
        cursor: pointer;
        letter-spacing: 0.01em;
        transition: all 0.4s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        font-family: inherit;
        box-shadow:
            0 8px 25px rgba(124,58,237,0.3),
            0 2px 8px rgba(236,72,153,0.2);
        position: relative;
        overflow: hidden;
    }
    .submit-btn::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,0.15) 50%, transparent 60%);
        background-size: 200% 200%;
        transition: background-position 0.6s;
    }
    .submit-btn:hover {
        background-position: 100% 100%;
        transform: translateY(-2px) scale(1.01);
        box-shadow:
            0 12px 35px rgba(124,58,237,0.4),
            0 4px 12px rgba(236,72,153,0.25);
    }
    .submit-btn:hover::after {
        background-position: 100% 100%;
    }
    .submit-btn:active {
        transform: translateY(0) scale(0.99);
    }
    .submit-btn svg {
        transition: transform 0.3s;
    }
    .submit-btn:hover svg {
        transform: translateX(3px);
    }

    .session-status {
        border-radius: 12px;
        padding: 0.85rem 1.1rem;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
        line-height: 1.5;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .session-status.success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #059669;
    }
    .session-status.error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
    }

    .divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 1.5rem 0;
    }
    .divider-line { flex: 1; height: 1px; background: #e2e8f0; }
    .divider-text { font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; }

    .card-footer {
        text-align: center;
        font-size: 0.85rem;
        color: #94a3b8;
        margin-top: 0.5rem;
    }
    .card-footer a {
        color: #7c3aed;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }
    .card-footer a:hover {
        color: #6d28d9;
        text-decoration: underline;
    }

    /* ===== MOBILE LOGO (hidden on desktop) ===== */
    .mobile-logo {
        display: none;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .mobile-logo-icon {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 15px rgba(124,58,237,0.3);
    }
    .mobile-logo-icon svg {
        width: 20px; height: 20px;
        fill: white;
    }
    .mobile-logo-text {
        font-size: 1.4rem;
        font-weight: 800;
        color: #1e1b2e;
        letter-spacing: -0.03em;
    }
    .mobile-logo-text span {
        background: linear-gradient(135deg, #7c3aed, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ===== RESPONSIVE ===== */
    /* Tablet Portrait & Mobile */
    @media (max-width: 1023px) {
        body { overflow: auto; }
        .login-wrapper { min-height: 100dvh; }
        .dots-pattern { display: none; }
        .float-shape { opacity: 0.06; }
        .shape-3 { display: none; }

        .branding-panel { display: none; }
        .mobile-logo { display: flex; }

        .form-panel {
            flex: none;
            width: 100%;
            min-height: 100dvh;
            padding: 2rem 1.25rem;
            background: linear-gradient(160deg, #fef9ef 0%, #fdf2f8 50%, #f0f0ff 100%);
            align-items: center;
        }

        .login-card {
            max-width: 400px;
            padding: 2rem 1.75rem;
            border-radius: 24px;
            box-shadow:
                0 20px 50px rgba(124,58,237,0.08),
                0 5px 20px rgba(0,0,0,0.03);
            animation: cardSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .card-header-icon { width: 48px; height: 48px; }
        .card-header-icon svg { width: 24px; height: 24px; }
        .card-title { font-size: 1.4rem; }
    }

    /* Small phones */
    @media (max-width: 420px) {
        .form-panel { padding: 1.25rem 0.75rem; }
        .login-card {
            padding: 1.5rem 1.25rem;
            border-radius: 20px;
            max-width: 100%;
        }
        .card-title { font-size: 1.25rem; }
        .card-subtitle { font-size: 0.8rem; }
        .form-label { font-size: 0.75rem; }
        .form-input {
            padding: 0.75rem 0.75rem 0.75rem 2.5rem;
            font-size: 0.9rem;
            border-radius: 12px;
        }
        .submit-btn {
            padding: 0.8rem 1rem;
            font-size: 0.9rem;
            border-radius: 12px;
        }
        .form-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .remember-label { font-size: 0.8rem; }
        .forgot-link { font-size: 0.8rem; }
        .card-footer { font-size: 0.78rem; }
        .session-status { font-size: 0.78rem; padding: 0.7rem 0.9rem; }
    }

    /* Tablet landscape (768-1023px) */
    @media (min-width: 768px) and (max-width: 1023px) {
        .form-panel { padding: 3rem 2rem; }
        .login-card {
            max-width: 440px;
            padding: 2.5rem 2.25rem;
        }
    }

    /* Short screens (< 600px height) */
    @media (max-height: 600px) {
        .form-panel { min-height: 100dvh; padding-top: 1.5rem; padding-bottom: 1.5rem; }
        .login-card { padding: 1.5rem 1.5rem; }
        .card-header { margin-bottom: 1.25rem; }
        .card-header-icon { width: 40px; height: 40px; }
        .card-header-icon svg { width: 20px; height: 20px; }
        .card-title { font-size: 1.2rem; }
        .card-subtitle { font-size: 0.8rem; }
        .form-group { margin-bottom: 0.85rem; }
        .form-input { padding: 0.65rem 0.75rem 0.65rem 2.4rem; font-size: 0.85rem; }
        .form-row { margin: 0.75rem 0 1rem; }
        .submit-btn { padding: 0.7rem 1rem; font-size: 0.85rem; }
        .branding-footer { display: none; }
    }

    /* Tall screens - keep branding visible */
    @media (min-width: 1400px) {
        .branding-panel { padding: 5rem 6rem; }
        .form-panel { padding: 3rem; }
        .login-card { max-width: 460px; padding: 3rem; }
    }
</style>

<div class="login-wrapper">
    <!-- Floating Shapes -->
    <div class="float-shape shape-1"></div>
    <div class="float-shape shape-2"></div>
    <div class="float-shape shape-3"></div>
    <div class="float-shape shape-4"></div>
    <div class="dots-pattern"></div>

    <!-- Left Branding Panel -->
    <div class="branding-panel">
        <div class="brand-content">
            <div class="brand-logo-wrap">
                <div class="brand-logo-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 3h8v8H3zm10 0h8v8h-8zM3 13h8v8H3zm13 0a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"/>
                    </svg>
                </div>
                <span class="brand-name">DOD<span>POS</span></span>
            </div>

            <h1 class="branding-hero-title">
                Kelola Bisnismu<br>
                <span class="highlight">Dengan Cerdas</span>
            </h1>
            <p class="branding-hero-desc">
                Platform all-in-one untuk POS, inventaris, HR, dan distribusi. 
                Bikin operasional bisnis makin lancar dan terkelola.
            </p>

            <div class="feature-pills">
                <div class="feature-pill">
                    <span class="pill-dot purple"></span>
                    POS Kasir
                </div>
                <div class="feature-pill">
                    <span class="pill-dot pink"></span>
                    Manajemen Stok
                </div>
                <div class="feature-pill">
                    <span class="pill-dot teal"></span>
                    HR & Payroll
                </div>
                <div class="feature-pill">
                    <span class="pill-dot orange"></span>
                    Sales Distribusi
                </div>
                <div class="feature-pill">
                    <span class="pill-dot yellow"></span>
                    Laporan & Analitik
                </div>
            </div>
        </div>

        <div class="branding-footer">
            &copy; {{ date('Y') }} DODPOS. All rights reserved.
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="form-panel">
        <div class="login-card">
            <!-- Mobile-only logo (hidden on desktop) -->
            <div class="mobile-logo">
                <div class="mobile-logo-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 3h8v8H3zm10 0h8v8h-8zM3 13h8v8H3zm13 0a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"/>
                    </svg>
                </div>
                <span class="mobile-logo-text">DOD<span>POS</span></span>
            </div>
            <div class="card-header">
                <div class="card-header-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                        <path d="M8 14h.01"></path><path d="M12 14h.01"></path><path d="M16 14h.01"></path><path d="M8 18h.01"></path><path d="M12 18h.01"></path><path d="M16 18h.01"></path>
                    </svg>
                </div>
                <h2 class="card-title">{{ $title ?: 'Welcome Back' }}</h2>
                <p class="card-subtitle">{{ $subtitle ?: 'Silakan masuk ke akun Anda untuk melanjutkan.' }}</p>
            </div>

            @if (session('status'))
                <div class="session-status success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="session-status error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}

            @isset($after)
                {{ $after }}
            @endisset

            <div class="card-footer">
                @isset($footer)
                    {{ $footer }}
                @else
                    Mengalami kendala? <a href="#">Hubungi IT Support</a>
                @endisset
            </div>
        </div>
    </div>
</div>
