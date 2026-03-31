<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#070B14">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'DODPOS Sales')</title>
    
    <!-- PWA -->
    <link rel="manifest" href="/manifest-sales.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    
    <style>
        :root {
            --bg-base: #070B14;
            --bg-surface: #0D1117;
            --bg-elevated: #161B27;
            --border: rgba(255,255,255,0.08);
            --accent: #7C3AED;
            --accent-glow: rgba(124,58,237,0.4);
        }
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',-apple-system,BlinkMacSystemFont,sans-serif; -webkit-font-smoothing:antialiased; }
        html, body { height:100%; }
        body { background:var(--bg-base); color:#fff; -webkit-tap-highlight-color:transparent; overscroll-behavior:none; }

        /* Scrollbar */
        ::-webkit-scrollbar { display:none; }
        * { scrollbar-width:none; }

        /* Cards */
        .card { background:var(--bg-elevated); border:1px solid var(--border); border-radius:20px; }
        .card-sm { background:var(--bg-elevated); border:1px solid var(--border); border-radius:16px; }
        .card-glow { background:linear-gradient(145deg,rgba(124,58,237,0.15),rgba(124,58,237,0.05)); border:1px solid rgba(124,58,237,0.3); border-radius:20px; }

        /* Gradients */
        .grad-violet { background:linear-gradient(135deg,#7C3AED,#5B21B6); }
        .grad-emerald { background:linear-gradient(135deg,#059669,#065F46); }
        .grad-amber { background:linear-gradient(135deg,#D97706,#92400E); }
        .grad-rose { background:linear-gradient(135deg,#E11D48,#9F1239); }
        .grad-blue { background:linear-gradient(135deg,#2563EB,#1E40AF); }
        .grad-cyan { background:linear-gradient(135deg,#0891B2,#164E63); }
        .grad-hero { background:linear-gradient(145deg,#1e1b4b 0%,#0f172a 40%,#0c0a14 100%); }
        .grad-text { background:linear-gradient(90deg,#A78BFA,#7C3AED); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

        /* Interactive */
        .tap { transition:transform 0.12s ease,opacity 0.12s ease; cursor:pointer; }
        .tap:active { transform:scale(0.95); opacity:0.85; }
        .tap-sm:active { transform:scale(0.97); }

        /* Nav */
        .nav-bar { background:rgba(7,11,20,0.92); backdrop-filter:blur(24px); -webkit-backdrop-filter:blur(24px); border-top:1px solid var(--border); }
        .nav-active { color:#A78BFA; }
        .nav-inactive { color:rgba(255,255,255,0.3); }
        .nav-dot { width:4px; height:4px; background:#A78BFA; border-radius:50%; margin:0 auto; }

        /* Header */
        .header { background:rgba(7,11,20,0.8); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border-bottom:1px solid var(--border); }

        /* FAB */
        .fab { background:linear-gradient(135deg,#7C3AED,#5B21B6); box-shadow:0 0 24px rgba(124,58,237,0.5),0 0 48px rgba(124,58,237,0.2); border-radius:18px; }

        /* Progress bar */
        .progress-track { background:rgba(255,255,255,0.06); border-radius:99px; overflow:hidden; }
        .progress-fill { background:linear-gradient(90deg,#7C3AED,#A78BFA); border-radius:99px; transition:width 1.2s cubic-bezier(.4,0,.2,1); }

        /* Tag / Badge */
        .badge-green { background:rgba(5,150,105,0.15); color:#34D399; border:1px solid rgba(52,211,153,0.2); border-radius:99px; }
        .badge-red { background:rgba(225,29,72,0.15); color:#FB7185; border:1px solid rgba(251,113,133,0.2); border-radius:99px; }
        .badge-amber { background:rgba(217,119,6,0.15); color:#FCD34D; border:1px solid rgba(252,211,77,0.2); border-radius:99px; }
        .badge-violet { background:rgba(124,58,237,0.15); color:#A78BFA; border:1px solid rgba(167,139,250,0.2); border-radius:99px; }

        /* Toast */
        .toast { position:fixed; bottom:96px; left:50%; transform:translateX(-50%) translateY(20px); opacity:0; transition:all 0.35s cubic-bezier(.4,0,.2,1); z-index:999; white-space:nowrap; pointer-events:none; }
        .toast.show { transform:translateX(-50%) translateY(0); opacity:1; }
        .toast-inner { background:rgba(13,17,23,0.95); backdrop-filter:blur(20px); border:1px solid var(--border); border-radius:14px; padding:12px 20px; display:flex; align-items:center; gap:10px; }

        /* Animations */
        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }
        @keyframes pulse-glow { 0%,100%{box-shadow:0 0 20px rgba(124,58,237,0.4)} 50%{box-shadow:0 0 40px rgba(124,58,237,0.7)} }
        @keyframes shimmer { 0%{background-position:-200% 0} 100%{background-position:200% 0} }
        .anim-up { animation:fadeUp 0.5s ease both; }
        .anim-up-1 { animation:fadeUp 0.5s 0.05s ease both; }
        .anim-up-2 { animation:fadeUp 0.5s 0.1s ease both; }
        .anim-up-3 { animation:fadeUp 0.5s 0.15s ease both; }
        .anim-up-4 { animation:fadeUp 0.5s 0.2s ease both; }
        .anim-up-5 { animation:fadeUp 0.5s 0.25s ease both; }
        .skeleton { background:linear-gradient(90deg,rgba(255,255,255,0.04) 25%,rgba(255,255,255,0.08) 50%,rgba(255,255,255,0.04) 75%); background-size:200% 100%; animation:shimmer 1.5s infinite; border-radius:12px; }

        /* Divider */
        .divider { height:1px; background:var(--border); }

        /* Input */
        .input-dark { background:rgba(255,255,255,0.05); border:1px solid var(--border); border-radius:14px; color:#fff; outline:none; width:100%; }
        .input-dark:focus { border-color:rgba(124,58,237,0.5); box-shadow:0 0 0 3px rgba(124,58,237,0.1); }
        .input-dark::placeholder { color:rgba(255,255,255,0.25); }

        /* Glow orb background decorations */
        .orb { position:absolute; border-radius:50%; filter:blur(60px); pointer-events:none; }
        .orb-violet { background:rgba(124,58,237,0.15); }
        .orb-blue { background:rgba(37,99,235,0.1); }
    </style>
    @stack('styles')
</head>
<body style="background:var(--bg-base)">

    <!-- OFFLINE BANNER -->
    <div id="offline-banner" style="display:none;background:#92400E;padding:8px 16px;text-align:center;font-size:13px;font-weight:500;position:fixed;top:0;left:0;right:0;z-index:100;">
        <span style="opacity:0.8">📡</span> Mode Offline — Data tersimpan di perangkat
    </div>

    <!-- HEADER -->
    <header class="header" style="position:sticky;top:0;z-index:50;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;">
            <div style="display:flex;align-items:center;gap:12px;">
                @yield('back-button')
                <div>
                    <div style="font-size:17px;font-weight:700;color:#fff;line-height:1.2;">@yield('header-title','Sales')</div>
                    <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:1px;">@yield('header-subtitle','')</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <button id="sync-btn" onclick="syncData()" style="display:none;position:relative;width:36px;height:36px;border:1px solid var(--border);background:var(--bg-elevated);border-radius:10px;cursor:pointer;align-items:center;justify-content:center;">
                    <i data-lucide="refresh-cw" style="width:16px;height:16px;color:rgba(255,255,255,0.6);"></i>
                    <span id="pending-count" style="position:absolute;top:-4px;right:-4px;background:#E11D48;color:#fff;font-size:9px;font-weight:700;min-width:16px;height:16px;border-radius:99px;display:flex;align-items:center;justify-content:center;padding:0 4px;">0</span>
                </button>
                <a href="{{ route('sales.menu') }}" style="width:36px;height:36px;border:1px solid var(--border);background:var(--bg-elevated);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i data-lucide="grid-3x3" style="width:16px;height:16px;color:rgba(255,255,255,0.6);"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main style="height:calc(100dvh - 130px);overflow-y:auto;">
        @yield('content')
    </main>

    <!-- BOTTOM NAV -->
    <nav class="nav-bar" style="position:fixed;bottom:0;left:0;right:0;z-index:50;">
        <div style="display:flex;align-items:center;justify-content:space-around;padding:10px 24px 16px;">

            <a href="{{ route('sales.dashboard') }}" class="{{ request()->routeIs('sales.dashboard') ? 'nav-active' : 'nav-inactive' }} tap" style="display:flex;flex-direction:column;align-items:center;gap:4px;text-decoration:none;">
                <i data-lucide="home" style="width:22px;height:22px;"></i>
                <span style="font-size:10px;font-weight:500;">Home</span>
                @if(request()->routeIs('sales.dashboard'))<div class="nav-dot" style="margin-top:2px;"></div>@endif
            </a>

            <a href="{{ route('sales.penjualan.create') }}" class="tap" style="margin-top:-20px;display:flex;flex-direction:column;align-items:center;gap:6px;text-decoration:none;">
                <div class="fab" style="width:56px;height:56px;display:flex;align-items:center;justify-content:center;animation:pulse-glow 3s ease-in-out infinite;">
                    <i data-lucide="plus" style="width:26px;height:26px;color:#fff;"></i>
                </div>
                <span style="font-size:10px;font-weight:500;color:rgba(255,255,255,0.3);">Jual</span>
            </a>

            <a href="{{ route('sales.menu') }}" class="{{ request()->routeIs('sales.menu') ? 'nav-active' : 'nav-inactive' }} tap" style="display:flex;flex-direction:column;align-items:center;gap:4px;text-decoration:none;">
                <i data-lucide="layout-grid" style="width:22px;height:22px;"></i>
                <span style="font-size:10px;font-weight:500;">Menu</span>
                @if(request()->routeIs('sales.menu'))<div class="nav-dot" style="margin-top:2px;"></div>@endif
            </a>
        </div>
    </nav>

    <!-- TOAST -->
    <div id="toast" class="toast">
        <div class="toast-inner">
            <span id="toast-icon" style="font-size:16px;"></span>
            <span id="toast-msg" style="font-size:13px;font-weight:500;color:#fff;"></span>
        </div>
    </div>

    <!-- LOADING -->
    <div id="loading-overlay" style="display:none;position:fixed;inset:0;background:rgba(7,11,20,0.8);backdrop-filter:blur(8px);z-index:200;align-items:center;justify-content:center;">
        <div style="text-align:center;">
            <div style="width:48px;height:48px;border:3px solid rgba(124,58,237,0.3);border-top-color:#7C3AED;border-radius:50%;animation:spin 0.8s linear infinite;margin:0 auto;"></div>
            <div style="margin-top:16px;font-size:13px;color:rgba(255,255,255,0.5);font-weight:500;">Memuat...</div>
        </div>
    </div>

    <style>@keyframes spin{to{transform:rotate(360deg)}}</style>
    <script src="/js/sales-pwa.js"></script>
    <script>
        lucide.createIcons();

        function showToast(msg, type='info') {
            const icons = { success:'✅', error:'❌', info:'💬', warning:'⚠️' };
            document.getElementById('toast-msg').textContent = msg;
            document.getElementById('toast-icon').textContent = icons[type] || '💬';
            const t = document.getElementById('toast');
            t.classList.add('show');
            clearTimeout(t._to);
            t._to = setTimeout(()=>t.classList.remove('show'), 3000);
        }

        function showLoading(show=true) {
            const el = document.getElementById('loading-overlay');
            el.style.display = show ? 'flex' : 'none';
        }

        async function syncData() {
            if(window.salesPWA){ showLoading(true); await window.salesPWA.syncAllData(); showLoading(false); showToast('Sinkronisasi selesai','success'); }
        }

        window.addEventListener('offline',()=>{ document.getElementById('offline-banner').style.display='block'; });
        window.addEventListener('online',()=>{ document.getElementById('offline-banner').style.display='none'; if(window.salesPWA) window.salesPWA.syncAllData(); });
    </script>
    @stack('scripts')
</body>
</html>
