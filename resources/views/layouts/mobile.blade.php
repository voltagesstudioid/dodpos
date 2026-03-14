<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DodPOS Mobile') }}</title>

    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f46e5">

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => console.log('SW Registered', registration))
                    .catch(err => console.log('SW Failed', err));
            });
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Essential Mobile Reset */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            overflow: hidden; /* Prevent pull-to-refresh & double scrollbars */
        }

        #mobile-app {
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 100vw;
        }

        /* Top Header */
        .mobile-header {
            background-color: #4f46e5;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .mobile-header-title {
            font-weight: 700;
            font-size: 1.125rem;
            margin: 0;
        }

        .mobile-header-subtitle {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        /* Scrollable Content Area */
        .mobile-content {
            flex: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            padding: 1rem;
            position: relative;
        }

        /* Bottom Navigation Bar (Optional/App-like feel) */
        .mobile-nav {
            background-color: white;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-around;
            padding: 0.75rem 0;
            padding-bottom: env(safe-area-inset-bottom, 0.75rem); /* iOS safe area */
        }

        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #64748b;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .mobile-nav-item.active {
            color: #4f46e5;
        }

        .mobile-nav-icon {
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }

        /* Global Toast Sync Notification */
        #sync-toast {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%) translateY(-150%);
            background: #10b981;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
        }
        #sync-toast.show {
            transform: translateX(-50%) translateY(0);
        }
    </style>
</head>
<body>

    <div id="mobile-app">
        <!-- Header -->
        <header class="mobile-header">
            <div>
                <h1 class="mobile-header-title">{{ $header ?? 'DodPOS Mobile' }}</h1>
                <div class="mobile-header-subtitle" id="network-status">🟢 Online - Siap</div>
            </div>
            <div style="display:flex; gap:0.5rem; align-items:center;">
                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="background:rgba(255,255,255,0.2); border:none; color:white; padding:0.4rem; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center;">
                        🚪
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mobile-content">
            {{ $slot }}
        </main>

        <!-- Bottom Navigation -->
        <nav class="mobile-nav">
            <a href="{{ route('mobile.pos') }}" class="mobile-nav-item active">
                <span class="mobile-nav-icon">🏪</span>
                Toko
            </a>
            <a href="#" class="mobile-nav-item" onclick="forceSync()">
                <span class="mobile-nav-icon">🔄</span>
                Sinkron
            </a>
            <a href="#" class="mobile-nav-item">
                <span class="mobile-nav-icon">📜</span>
                Riwayat
            </a>
        </nav>
    </div>

    <!-- Sync Toast Notification -->
    <div id="sync-toast">✅ Sedang Sinkronisasi...</div>

    <script>
        // Simple Network Status Monitor
        const networkStatusBadge = document.getElementById('network-status');
        
        function updateOnlineStatus() {
            if (navigator.onLine) {
                networkStatusBadge.innerHTML = "🟢 Online - Tersambung";
                networkStatusBadge.style.color = "#a7f3d0"; // light green
            } else {
                networkStatusBadge.innerHTML = "🔴 OFFLINE - Mode Lokal";
                networkStatusBadge.style.color = "#fecaca"; // light red
            }
        }
        
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
        
        // Initial setup
        updateOnlineStatus();

        // Real Sync Logic
        async function forceSync() {
            const toast = document.getElementById('sync-toast');
            if(!navigator.onLine) {
                toast.textContent = "❌ Gagal. Anda Offline.";
                toast.style.background = "#ef4444";
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 2000);
                return;
            }

            let pendingOrders = JSON.parse(localStorage.getItem('pwa_pending_orders') || '[]');
            if (pendingOrders.length === 0) {
                 toast.textContent = "✅ Semua Data Sudah Tersinkronisasi";
                 toast.style.background = "#10b981";
                 toast.classList.add('show');
                 setTimeout(() => toast.classList.remove('show'), 2000);
                 return;
            }

            toast.textContent = `🔄 Mengirim ${pendingOrders.length} Pesanan...`;
            toast.style.background = "#eab308";
            toast.classList.add('show');

            try {
                const response = await fetch('{{ route("mobile.sync") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ orders: pendingOrders })
                });

                const data = await response.json();
                if (data.success) {
                    // clear offline queue
                    localStorage.removeItem('pwa_pending_orders');
                    toast.textContent = `✅ Berhasil Sinkronisasi ${data.synced} Pesanan!`;
                    toast.style.background = "#10b981";
                } else {
                    throw new Error(data.message);
                }
            } catch (err) {
                console.error(err);
                toast.textContent = "❌ Sinkronisasi Gagal. Coba lagi nanti.";
                toast.style.background = "#ef4444";
            }
            
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    </script>
</body>
</html>
