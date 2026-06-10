<x-app-layout>
    <x-slot name="header">Sesi Kasir</x-slot>

    <div class="s-page">
        <div class="s-wrap">

            {{-- ─── HEADER ─── --}}
            <div class="s-head">
                <div>
                    <h1 class="s-title">Sesi Kasir</h1>
                    <p class="s-desc">Pantau kas fisik, mutasi, dan performa sesi kasir eceran &amp; grosir.</p>
                </div>
                @can('view_pos_kasir')
                    <a href="{{ route('kasir.index') }}" class="s-btn-pos">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        Buka Layar POS
                    </a>
                @endcan
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success')) <div class="s-alert s-alert-ok">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="s-alert s-alert-err">{{ session('error') }}</div> @endif

            @if(!$eceranSession && !$grosirSession)
                {{-- ─── NO SESSION AT ALL ─── --}}
                <div class="s-empty">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <h2>Belum Ada Sesi Aktif</h2>
                    <p>Sistem POS terkunci hingga sesi baru dimulai dengan modal awal.</p>
                    @can('view_pos_kasir')
                        <a href="{{ route('kasir.index') }}" class="s-btn-start">Mulai Sesi Sekarang</a>
                    @endcan
                </div>
            @else
                {{-- ─── TAB SWITCHER ─── --}}
                <div class="s-tabs">
                    <button class="s-tab active" data-tab="eceran" onclick="switchTab('eceran')">
                        <span class="s-tab-dot {{ $eceranSession ? 's-dot-on' : 's-dot-off' }}"></span>
                        Eceran
                    </button>
                    <button class="s-tab" data-tab="grosir" onclick="switchTab('grosir')">
                        <span class="s-tab-dot {{ $grosirSession ? 's-dot-on' : 's-dot-off' }}"></span>
                        Grosir
                    </button>
                </div>

                {{-- ═══════════════════════════════════════ --}}
                {{-- ═══ ECERAN PANEL ═══ --}}
                {{-- ═══════════════════════════════════════ --}}
                <div class="s-panel" id="panel-eceran">
                    @if(!$eceranSession)
                        <div class="s-panel-empty">
                            <p>Sesi kasir eceran belum dibuka.</p>
                            @can('view_pos_kasir')
                                <a href="{{ route('kasir.index') }}" class="s-btn-start s-btn-sm">Buka Sesi Eceran</a>
                            @endcan
                        </div>
                    @else
                        @php
                            $s = $eceranSession;
                            $st = $eceranStats;
                            $type = 'eceran';
                            $label = 'Eceran';
                            $closeRoute = 'kasir.close_session';
                            $accent = '#10b981';
                        @endphp
                        @include('kasir._session_panel', compact('s','st','type','label','closeRoute','accent'))
                    @endif
                </div>

                {{-- ═══════════════════════════════════════ --}}
                {{-- ═══ GROSIR PANEL ═══ --}}
                {{-- ═══════════════════════════════════════ --}}
                <div class="s-panel" id="panel-grosir" style="display:none;">
                    @if(!$grosirSession)
                        <div class="s-panel-empty">
                            <p>Sesi kasir grosir belum dibuka.</p>
                            @can('view_pos_kasir')
                                <a href="{{ route('kasir.index') }}" class="s-btn-start s-btn-sm">Buka Sesi Grosir</a>
                            @endcan
                        </div>
                    @else
                        @php
                            $s = $grosirSession;
                            $st = $grosirStats;
                            $type = 'grosir';
                            $label = 'Grosir';
                            $closeRoute = 'kasir.close_session_grosir';
                            $accent = '#3b82f6';
                        @endphp
                        @include('kasir._session_panel', compact('s','st','type','label','closeRoute','accent'))
                    @endif
                </div>
            @endif

        </div>
    </div>

    @push('styles')
    <style>
        .s-page { max-width: 1040px; margin: 0 auto; padding: 1.5rem 1.25rem 3rem; }
        .s-head { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
        .s-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 4px; }
        .s-desc { font-size: 0.85rem; color: #64748b; margin: 0; }
        .s-btn-pos { display: inline-flex; align-items: center; gap: 7px; background: #0f172a; color: #fff; padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 700; font-size: 0.8125rem; text-decoration: none; transition: 0.2s; }
        .s-btn-pos:hover { background: #000; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0,0,0,0.15); }

        .s-alert { padding: 0.75rem 1rem; border-radius: 10px; margin-bottom: 1rem; font-weight: 600; font-size: 0.8125rem; }
        .s-alert-ok { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .s-alert-err { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .s-empty { background: #fff; border: 2px dashed #e2e8f0; border-radius: 16px; padding: 3.5rem 2rem; text-align: center; max-width: 480px; margin: 2.5rem auto; }
        .s-empty h2 { font-size: 1.25rem; font-weight: 800; margin: 1rem 0 0.5rem; }
        .s-empty p { color: #64748b; margin: 0 0 1.5rem; font-size: 0.85rem; }
        .s-btn-start { background: #4f46e5; color: #fff; padding: 0.65rem 1.5rem; border-radius: 10px; font-weight: 700; text-decoration: none; display: inline-block; transition: 0.2s; font-size: 0.85rem; }
        .s-btn-start:hover { background: #4338ca; transform: translateY(-1px); }
        .s-btn-sm { padding: 0.5rem 1.25rem; font-size: 0.8125rem; }

        /* TABS */
        .s-tabs { display: flex; gap: 4px; background: #f1f5f9; border-radius: 10px; padding: 4px; margin-bottom: 1.5rem; width: fit-content; }
        .s-tab { display: flex; align-items: center; gap: 6px; padding: 0.5rem 1.25rem; border-radius: 8px; border: none; background: none; font-size: 0.8125rem; font-weight: 700; color: #64748b; cursor: pointer; transition: 0.2s; font-family: inherit; }
        .s-tab.active { background: #fff; color: #0f172a; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .s-tab-dot { width: 7px; height: 7px; border-radius: 50%; }
        .s-dot-on { background: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,0.2); }
        .s-dot-off { background: #cbd5e1; }

        .s-panel-empty { background: #fff; border: 1px dashed #e2e8f0; border-radius: 12px; padding: 2.5rem; text-align: center; color: #64748b; font-size: 0.85rem; }

        /* ─── HERO ─── */
        .sp-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0f172a 100%); border-radius: 16px; padding: 1.5rem 1.75rem; position: relative; overflow: hidden; margin-bottom: 1.25rem; color: #fff; }
        .sp-hero-glow { position: absolute; top: -50%; right: -10%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
        .sp-hero-content { position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .sp-hero-label { font-size: 0.65rem; font-weight: 700; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; }
        .sp-hero-amount { font-size: 2.25rem; font-weight: 900; font-family: ui-monospace, monospace; letter-spacing: -0.02em; line-height: 1; }
        .sp-hero-amount small { font-size: 0.875rem; opacity: 0.6; margin-right: 2px; }
        .sp-hero-chips { display: flex; gap: 5px; margin-top: 0.75rem; flex-wrap: wrap; }
        .sp-chip { background: rgba(255,255,255,0.08); padding: 2px 8px; border-radius: 5px; font-size: 0.6rem; font-weight: 600; color: rgba(255,255,255,0.6); }
        .sp-chip-op { color: rgba(255,255,255,0.35); font-size: 0.65rem; }
        .sp-hero-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; }
        .sp-status { display: flex; align-items: center; gap: 6px; background: rgba(16,185,129,0.15); padding: 5px 12px; border-radius: 99px; font-size: 0.7rem; font-weight: 800; color: #6ee7b7; }
        .sp-status-dot { width: 6px; height: 6px; background: #10b981; border-radius: 50%; animation: sp-blink 1.5s infinite; }
        .sp-meta { font-size: 0.7rem; color: rgba(255,255,255,0.45); text-align: right; }
        .sp-meta strong { color: rgba(255,255,255,0.8); }

        /* ─── BREAKDOWN ─── */
        .sp-section-title { font-size: 0.875rem; font-weight: 800; margin: 0 0 0.75rem; }
        .sp-bd-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.625rem; margin-bottom: 1.25rem; }
        .sp-bd { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.875rem; display: flex; align-items: center; gap: 0.625rem; position: relative; }
        .sp-bd-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .sp-bd-icon svg { width: 16px; height: 16px; }
        .sp-bd-icon.i-modal { background: #fef3c7; color: #d97706; }
        .sp-bd-icon.i-cash { background: #dcfce7; color: #16a34a; }
        .sp-bd-icon.i-dp { background: #ede9fe; color: #7c3aed; }
        .sp-bd-icon.i-in { background: #dbeafe; color: #2563eb; }
        .sp-bd-icon.i-out { background: #fee2e2; color: #dc2626; }
        .sp-bd-lbl { font-size: 0.6rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.03em; }
        .sp-bd-val { font-size: 0.8125rem; font-weight: 800; font-family: ui-monospace, monospace; color: #0f172a; }
        .sp-bd-sub { font-size: 0.6rem; color: #94a3b8; }
        .sp-bd-sign { position: absolute; top: 6px; right: 8px; font-size: 0.65rem; font-weight: 800; }
        .sp-bd-plus { color: #10b981; }
        .sp-bd-minus { color: #ef4444; }

        /* ─── STATS ─── */
        .sp-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1.25rem; }
        .sp-stat { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem 1.25rem; }
        .sp-stat-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem; }
        .sp-stat-lbl { font-size: 0.65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.03em; }
        .sp-stat-badge { font-size: 0.55rem; font-weight: 700; padding: 2px 7px; border-radius: 5px; background: #f1f5f9; color: #64748b; }
        .sp-stat-badge.live { background: #dcfce7; color: #059669; animation: sp-blink 2s infinite; }
        .sp-stat-amt { font-size: 1.25rem; font-weight: 900; font-family: ui-monospace, monospace; color: #0f172a; }
        .sp-stat-foot { font-size: 0.65rem; color: #94a3b8; margin-top: 2px; }

        /* ─── CLOSE FORM ─── */
        .sp-close-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.25rem; }
        .sp-close-head { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; }
        .sp-close-icon { width: 38px; height: 38px; background: #fee2e2; color: #dc2626; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .sp-close-icon svg { width: 18px; height: 18px; }
        .sp-close-title { font-size: 0.95rem; font-weight: 800; margin: 0; }
        .sp-close-sub { font-size: 0.75rem; color: #64748b; margin: 2px 0 0; }
        .sp-close-grid { display: grid; grid-template-columns: 1fr 1.5fr 1fr; gap: 0.75rem; align-items: end; }
        .sp-field { display: flex; flex-direction: column; gap: 5px; }
        .sp-field label { font-size: 0.6rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.03em; }
        .sp-expected { font-size: 1rem; font-weight: 800; font-family: ui-monospace, monospace; color: #4f46e5; background: #eef2ff; padding: 0.65rem 0.875rem; border-radius: 8px; }
        .sp-input-wrap { display: flex; align-items: center; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; overflow: hidden; transition: 0.2s; }
        .sp-input-wrap:focus-within { border-color: #4f46e5; background: #fff; box-shadow: 0 0 0 3px rgba(79,70,229,0.08); }
        .sp-input-prefix { padding: 0 0.75rem; color: #94a3b8; font-weight: 800; font-size: 0.8125rem; background: #f1f5f9; border-right: 1px solid #e2e8f0; display: flex; align-items: center; min-height: 40px; }
        .sp-input-wrap input { border: none; padding: 0.6rem; width: 100%; font-weight: 800; font-family: ui-monospace, monospace; font-size: 0.875rem; outline: none; background: transparent; }
        .sp-variance { display: flex; flex-direction: column; gap: 3px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 0.875rem; min-height: 40px; justify-content: center; transition: 0.2s; }
        .sp-variance.v-pos { background: #dcfce7; border-color: #86efac; }
        .sp-variance.v-neg { background: #fee2e2; border-color: #fca5a5; }
        .sp-variance.v-zero { background: #f0fdf4; border-color: #86efac; }
        .sp-var-amt { font-size: 0.875rem; font-weight: 800; font-family: ui-monospace, monospace; }
        .sp-var-badge { font-size: 0.55rem; font-weight: 700; padding: 2px 6px; border-radius: 4px; display: inline-block; width: fit-content; background: #f1f5f9; color: #64748b; }
        .sp-var-badge.vb-pos { background: #dcfce7; color: #059669; }
        .sp-var-badge.vb-neg { background: #fee2e2; color: #dc2626; }
        .sp-var-badge.vb-zero { background: #dcfce7; color: #166534; }
        .sp-note-input { border: 2px solid #e2e8f0; border-radius: 8px; padding: 0.6rem 0.875rem; font-size: 0.8125rem; font-family: inherit; outline: none; background: #f8fafc; transition: 0.2s; width: 100%; box-sizing: border-box; }
        .sp-note-input:focus { border-color: #4f46e5; background: #fff; }
        .sp-close-action { margin-top: 1rem; display: flex; justify-content: flex-end; }
        .sp-close-btn { background: #ef4444; color: #fff; border: none; padding: 0.7rem 1.5rem; border-radius: 10px; font-weight: 800; display: flex; align-items: center; gap: 6px; cursor: pointer; transition: 0.2s; font-size: 0.8125rem; }
        .sp-close-btn:hover { background: #dc2626; transform: translateY(-1px); }

        /* ─── MUTASI ─── */
        .sp-mutasi { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; }
        .sp-mutasi-desc { font-size: 0.75rem; color: #94a3b8; margin: 0 0 1rem; }
        .sp-mf { display: flex; gap: 0.625rem; align-items: flex-end; flex-wrap: wrap; margin-bottom: 1rem; }
        .sp-mf-group { display: flex; flex-direction: column; gap: 4px; }
        .sp-mf-group label { font-size: 0.6rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.03em; }
        .sp-mf-grow { flex: 1; min-width: 120px; }
        .sp-mf-grow2 { flex: 2; min-width: 160px; }
        .sp-mf select, .sp-mf input { padding: 0.55rem 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 0.8125rem; font-family: inherit; outline: none; background: #f8fafc; transition: 0.2s; }
        .sp-mf select { appearance: none; padding-right: 1.5rem; }
        .sp-mf select:focus, .sp-mf input:focus { border-color: #4f46e5; background: #fff; }
        .sp-mf-btn { background: #0f172a; color: #fff; border: none; padding: 0.55rem 1.125rem; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 0.8125rem; transition: 0.2s; }
        .sp-mf-btn:hover { background: #000; }

        .sp-mt-wrap { border: 1px solid #e2e8f0; border-radius: 8px; overflow-x: auto; }
        .sp-mt { width: 100%; border-collapse: collapse; min-width: 500px; }
        .sp-mt th { background: #f8fafc; padding: 0.6rem 0.875rem; text-align: left; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; color: #94a3b8; border-bottom: 1px solid #e2e8f0; letter-spacing: 0.03em; }
        .sp-mt td { padding: 0.7rem 0.875rem; border-bottom: 1px solid #f1f5f9; font-size: 0.8rem; }
        .sp-mt tbody tr:hover { background: #fafbfc; }
        .sp-mt .tc { text-align: center; }
        .sp-mt .tr { text-align: right; }
        .sp-mt-time { color: #94a3b8; font-size: 0.7rem; }
        .sp-mt-mono { font-family: ui-monospace, monospace; }
        .sp-tag-in { background: #dcfce7; color: #166534; padding: 2px 7px; border-radius: 5px; font-size: 0.6rem; font-weight: 800; }
        .sp-tag-out { background: #fee2e2; color: #991b1b; padding: 2px 7px; border-radius: 5px; font-size: 0.6rem; font-weight: 800; }
        .sp-mt-empty { text-align: center; padding: 1.5rem; color: #94a3b8; font-size: 0.8rem; border: 1px dashed #e2e8f0; border-radius: 8px; }

        @keyframes sp-blink { 0%,100% { opacity: 1; } 50% { opacity: 0.5; } }

        @media (max-width: 900px) { .sp-bd-grid { grid-template-columns: repeat(3, 1fr); } .sp-close-grid { grid-template-columns: 1fr; } }
        @media (max-width: 640px) { .sp-bd-grid { grid-template-columns: 1fr 1fr; } .sp-stats { grid-template-columns: 1fr; } .sp-mf { flex-direction: column; align-items: stretch; } .sp-mf-grow,.sp-mf-grow2 { min-width: 0; } .sp-hero-amount { font-size: 1.75rem; } }
    </style>
    @endpush

    @push('scripts')
    <script>
        function switchTab(tab) {
            document.querySelectorAll('.s-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === tab));
            document.getElementById('panel-eceran').style.display = tab === 'eceran' ? '' : 'none';
            document.getElementById('panel-grosir').style.display = tab === 'grosir' ? '' : 'none';
        }

        // Duration timers
        document.querySelectorAll('[data-session-start]').forEach(function(el) {
            var start = new Date(el.dataset.sessionStart);
            function tick() {
                var diff = Math.max(0, Math.floor((Date.now() - start) / 1000));
                var h = Math.floor(diff / 3600), m = Math.floor((diff % 3600) / 60), s = diff % 60;
                el.textContent = h + 'j ' + m + 'm ' + s + 'd';
            }
            tick(); setInterval(tick, 1000);
        });

        // Variance calculator (parameterized)
        function calcVariance(type) {
            var expected = parseFloat(document.getElementById('expected-' + type).value) || 0;
            var actual = parseFloat(document.getElementById('actual-' + type).value) || 0;
            var variance = actual - expected;
            var display = document.getElementById('var-amt-' + type);
            var badge = document.getElementById('var-badge-' + type);
            var container = document.getElementById('var-box-' + type);
            var input = document.getElementById('actual-' + type);

            container.className = 'sp-variance';
            badge.className = 'sp-var-badge';

            if (!input.value || actual === 0) {
                display.textContent = 'Rp 0';
                badge.textContent = 'Belum diisi';
                return;
            }
            var abs = Math.abs(variance);
            display.textContent = (variance >= 0 ? '+' : '-') + 'Rp ' + abs.toLocaleString('id-ID');
            if (variance > 0) { container.classList.add('v-pos'); badge.classList.add('vb-pos'); badge.textContent = 'LEBIH'; }
            else if (variance < 0) { container.classList.add('v-neg'); badge.classList.add('vb-neg'); badge.textContent = 'KURANG'; }
            else { container.classList.add('v-zero'); badge.classList.add('vb-zero'); badge.textContent = 'SESUAI'; }
        }

        function confirmClose(type) {
            var expected = parseFloat(document.getElementById('expected-' + type).value) || 0;
            var actual = parseFloat(document.getElementById('actual-' + type).value) || 0;
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
