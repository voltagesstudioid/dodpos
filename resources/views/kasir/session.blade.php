<x-app-layout>
    <x-slot name="header">Sesi Kasir</x-slot>

    @push('styles')
    <style>
        .sk-page { max-width: 1100px; margin: 0 auto; padding: 0 0 3rem; animation: fadeSlideIn 0.35s ease both; }

        .sk-hero {
            background: linear-gradient(135deg, #06090f 0%, #0d1322 35%, #111827 70%, #0a0e1a 100%);
            border-radius: 20px; padding: 2rem 2.25rem 3.25rem;
            margin-bottom: -1.75rem; position: relative; overflow: hidden;
        }
        .sk-hero::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse at 85% 20%, rgba(99,102,241,0.22) 0%, transparent 60%),
                        radial-gradient(ellipse at 15% 80%, rgba(16,185,129,0.1) 0%, transparent 50%);
        }
        .sk-hero::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.5), rgba(16,185,129,0.3), transparent);
        }
        .sk-hero-inner { position: relative; z-index: 1; }
        .sk-hero-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem; }
        .sk-hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3);
            padding: 0.3rem 0.875rem; border-radius: 99px;
            font-size: 0.65rem; font-weight: 700; color: rgba(165,180,252,0.9);
            text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.875rem;
        }
        .sk-hero-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: #818cf8; animation: sk-pulse 2s infinite; }
        .sk-hero-title { font-size: 2rem; font-weight: 900; color: #fff; letter-spacing: -0.04em; line-height: 1.1; margin: 0 0 0.4rem; }
        .sk-hero-subtitle { font-size: 0.8125rem; color: rgba(255,255,255,0.45); margin: 0; }
        .sk-hero-actions { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
        .sk-btn-pos {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
            color: #fff; padding: 0.6rem 1.25rem; border-radius: 10px;
            font-weight: 700; font-size: 0.8125rem; text-decoration: none;
            transition: all 0.2s; backdrop-filter: blur(8px);
        }
        .sk-btn-pos:hover { background: rgba(255,255,255,0.18); border-color: rgba(255,255,255,0.25); transform: translateY(-1px); }
        .sk-btn-pos svg { opacity: 0.8; }

        .sk-alert { padding: 0.75rem 1rem; border-radius: 10px; margin-bottom: 1rem; font-weight: 600; font-size: 0.8125rem; animation: fadeSlideIn 0.3s ease; }
        .sk-alert-ok { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .sk-alert-err { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .sk-empty {
            background: #fff; border: 2px dashed #e2e8f0; border-radius: 20px;
            padding: 4rem 2rem; text-align: center; max-width: 480px;
            margin: 2.5rem auto; box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }
        .sk-empty-icon {
            width: 64px; height: 64px; border-radius: 16px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .sk-empty h2 { font-size: 1.25rem; font-weight: 800; margin: 0 0 0.5rem; color: #0f172a; }
        .sk-empty p { color: #64748b; margin: 0 0 1.5rem; font-size: 0.85rem; line-height: 1.6; }
        .sk-btn-start {
            background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff;
            padding: 0.7rem 1.75rem; border-radius: 10px; font-weight: 700;
            text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
            transition: all 0.2s; font-size: 0.85rem;
            box-shadow: 0 4px 14px rgba(79,70,229,0.35);
        }
        .sk-btn-start:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(79,70,229,0.4); }

        .sk-content { position: relative; z-index: 2; }

        @keyframes sk-pulse { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.3); } }

        @media (max-width: 640px) {
            .sk-hero { padding: 1.5rem 1.25rem 2.5rem; border-radius: 16px; }
            .sk-hero-title { font-size: 1.5rem; }
        }
    </style>
    @endpush

    <div class="sk-page">

        @if(session('success')) <div class="sk-alert sk-alert-ok">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="sk-alert sk-alert-err">{{ session('error') }}</div> @endif

        @if(!$eceranSession)
            <div class="sk-hero">
                <div class="sk-hero-inner">
                    <div class="sk-hero-top">
                        <div>
                            <div class="sk-hero-badge"><span class="sk-hero-badge-dot"></span> Sesi Kasir</div>
                            <h1 class="sk-hero-title">Sesi Kasir</h1>
                            <p class="sk-hero-subtitle">Pantau kas fisik, mutasi, dan performa sesi kasir eceran.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sk-empty" style="margin-top: 3rem;">
                <div class="sk-empty-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <h2>Belum Ada Sesi Aktif</h2>
                <p>Sistem POS terkunci hingga sesi eceran baru dimulai dengan modal awal.</p>
                @can('view_pos_kasir')
                    <a href="{{ route('kasir.index') }}" class="sk-btn-start">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                        Mulai Sesi Sekarang
                    </a>
                @endcan
            </div>
        @else
            <div class="sk-hero">
                <div class="sk-hero-inner">
                    <div class="sk-hero-top">
                        <div>
                            <div class="sk-hero-badge"><span class="sk-hero-badge-dot"></span> Sesi Aktif</div>
                            <h1 class="sk-hero-title">Sesi Kasir</h1>
                            <p class="sk-hero-subtitle">Pantau kas fisik, mutasi, dan performa sesi kasir eceran.</p>
                        </div>
                        <div class="sk-hero-actions">
                            @can('view_pos_kasir')
                                <a href="{{ route('kasir.index') }}" class="sk-btn-pos">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                    Buka Layar Kasir
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <div class="sk-content">
                @php
                    $s = $eceranSession;
                    $st = $eceranStats;
                    $type = 'eceran';
                    $label = 'Eceran';
                    $closeRoute = 'kasir.close_session';
                    $accent = '#10b981';
                @endphp
                @include('kasir._session_panel', compact('s','st','type','label','closeRoute','accent'))
            </div>
        @endif

    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('[data-session-start]').forEach(function(el) {
            var start = new Date(el.dataset.sessionStart);
            function tick() {
                var diff = Math.max(0, Math.floor((Date.now() - start) / 1000));
                var h = Math.floor(diff / 3600), m = Math.floor((diff % 3600) / 60), s = diff % 60;
                el.textContent = h + 'j ' + m + 'm ' + s + 'd';
            }
            tick(); setInterval(tick, 1000);
        });

        function calcVariance(type) {
            var expected = parseInt(parseCurrency(document.getElementById('expected-' + type).value)) || 0;
            var actual = parseInt(parseCurrency(document.getElementById('actual-' + type).value)) || 0;
            var variance = actual - expected;
            var display = document.getElementById('var-amt-' + type);
            var badge = document.getElementById('var-badge-' + type);
            var container = document.getElementById('var-box-' + type);
            var input = document.getElementById('actual-' + type);

            container.className = 'sk-variance';
            badge.className = 'badge';

            if (!input.value || actual === 0) {
                display.textContent = 'Rp 0';
                badge.textContent = 'Belum diisi';
                badge.classList.add('badge-gray');
                return;
            }
            var abs = Math.abs(variance);
            display.textContent = (variance >= 0 ? '+' : '-') + 'Rp ' + abs.toLocaleString('id-ID');
            if (variance > 0) { container.classList.add('v-pos'); badge.classList.add('badge-success'); badge.textContent = 'LEBIH'; }
            else if (variance < 0) { container.classList.add('v-neg'); badge.classList.add('badge-danger'); badge.textContent = 'KURANG'; }
            else { container.classList.add('v-zero'); badge.classList.add('badge-success'); badge.textContent = 'SESUAI'; }
        }

        function confirmClose(type) {
            var expected = parseInt(parseCurrency(document.getElementById('expected-' + type).value)) || 0;
            var actual = parseInt(parseCurrency(document.getElementById('actual-' + type).value)) || 0;
            var variance = actual - expected;
            var msg = 'Apakah Anda yakin ingin menutup sesi kasir ' + type + '?';
            if (variance !== 0) {
                msg += '\n\nTerdapat selisih ' + (variance > 0 ? 'LEBIH' : 'KURANG') + ': Rp ' + Math.abs(variance).toLocaleString('id-ID');
            }
            msg += '\n\nPastikan uang fisik sudah dihitung dengan benar.';
            return confirm(msg);
        }
    </script>
    @endpush
</x-app-layout>
