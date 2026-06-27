<x-hr-layout>
    <x-slot name="eyebrow">Manajemen Waktu</x-slot>
    <x-slot name="title">Kalender Libur & Kerja</x-slot>
    <x-slot name="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
    </x-slot>
    <x-slot name="iconBg">bg-indigo</x-slot>
    <x-slot name="description">Atur pengecualian tanggal libur nasional atau lembur perusahaan agar perhitungan payroll akurat.</x-slot>
    <x-slot name="actions">
        @can('create_absensi')
            <button type="button" class="hr-btn hr-btn-primary" onclick="openAddHolidayModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Manual
            </button>
        @endcan
        <a href="{{ route('sdm.absensi.index') }}" class="hr-btn hr-btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            Ke Absensi
        </a>
    </x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap');

        .hc-wrap { font-family:'Plus Jakarta Sans',sans-serif; }

        /* ── TOOLBAR ── */
        .hc-toolbar { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .hc-toolbar-l { display:flex; align-items:center; gap:0.5rem; background:#fff; padding:0.5rem; border-radius:12px; border:1px solid #e2e8f0; box-shadow:0 2px 4px rgba(0,0,0,0.02); }
        .hc-month-label { font-size:1.125rem; font-weight:800; color:#0f172a; min-width:180px; text-align:center; }
        .hc-nav-btn {
            width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;
            border:none; background:transparent; cursor:pointer; transition:all 0.2s; color:#64748b;
        }
        .hc-nav-btn:hover { background:#f1f5f9; color:#0f172a; }
        .hc-today-btn {
            padding:0.625rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:700;
            border:1px solid #e2e8f0; background:#fff; cursor:pointer; color:#475569; transition:all 0.2s; text-decoration:none;
            box-shadow:0 1px 2px rgba(0,0,0,0.02);
        }
        .hc-today-btn:hover { background:#f8fafc; color:#0f172a; border-color:#cbd5e1; }

        /* ── STATS ROW ── */
        .hc-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:2rem; }
        .hc-stat {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem;
            display:flex; align-items:center; gap:1rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02);
            transition:transform 0.2s, box-shadow 0.2s;
        }
        .hc-stat:hover { transform:translateY(-2px); box-shadow:0 10px 15px -3px rgba(0,0,0,0.04); }
        .hc-stat-ico { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .hc-stat-ico svg { width:22px; height:22px; }
        .hc-stat-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#2563eb; }
        .hc-stat-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#059669; }
        .hc-stat-ico.red { background:linear-gradient(135deg,#fef2f2,#fecaca); color:#dc2626; }
        .hc-stat-ico.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); color:#d97706; }
        .hc-stat-info { display:flex; flex-direction:column; }
        .hc-stat-val { font-size:1.5rem; font-weight:800; color:#0f172a; font-family:'JetBrains Mono',monospace; line-height:1; margin-bottom:0.25rem; }
        .hc-stat-lbl { font-size:0.75rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; }

        /* ── MODE BAR ── */
        .hc-mode-bar { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; padding:1rem 1.5rem; background:linear-gradient(to right, #f8fafc, #fff); border:1px solid #e2e8f0; border-radius:14px; }
        .hc-mode-left { display:flex; align-items:center; gap:1rem; }
        .hc-mode-badge {
            display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:10px;
            font-size:0.75rem; font-weight:700;
        }
        .hc-mode-badge.auto { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .hc-mode-badge.manual { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
        .hc-mode-badge .dot { width:6px; height:6px; border-radius:50%; }
        .hc-mode-badge.auto .dot { background:#059669; }
        .hc-mode-badge.manual .dot { background:#d97706; }
        .hc-mode-info { font-size:0.875rem; color:#475569; font-weight:500; }
        .hc-mode-actions { display:flex; gap:0.5rem; }
        .hc-gen-btn {
            padding:0.5rem 1rem; border-radius:8px; font-size:0.8125rem; font-weight:700;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
        }
        .hc-gen-btn.fill { background:#e0e7ff; color:#4f46e5; }
        .hc-gen-btn.fill:hover { background:#c7d2fe; color:#4338ca; }
        .hc-gen-btn.danger { background:#fef2f2; color:#dc2626; }
        .hc-gen-btn.danger:hover { background:#fecaca; color:#b91c1c; }

        /* ── CALENDAR GRID ── */
        .hc-cal { background:#fff; border:1px solid #e2e8f0; border-radius:20px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.03); margin-bottom:1.5rem; }
        .hc-cal-head { display:grid; grid-template-columns:repeat(7,1fr); background:#f8fafc; border-bottom:1px solid #e2e8f0; }
        .hc-cal-head div { padding:1rem; text-align:center; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; }
        .hc-cal-head div.weekend { color:#dc2626; }
        .hc-cal-body { display:grid; grid-template-columns:repeat(7,1fr); }
        .hc-day {
            min-height:110px; padding:0.75rem; border-right:1px solid #f1f5f9; border-bottom:1px solid #f1f5f9;
            position:relative; cursor:pointer; transition:all 0.2s; display:flex; flex-direction:column; gap:6px;
            background:#fff;
        }
        .hc-day:nth-child(7n) { border-right:none; }
        .hc-day:hover { background:#f8fafc; transform:scale(1.01); z-index:10; box-shadow:0 4px 12px rgba(0,0,0,0.05); border-radius:12px; border-color:transparent; }
        .hc-day.empty { cursor:default; background:#fbfbfc; }
        .hc-day.empty:hover { transform:none; box-shadow:none; border-radius:0; border-color:#f1f5f9; }
        .hc-day.today { background:#eff6ff; }
        .hc-day.today:hover { background:#eff6ff; }

        .hc-day-head { display:flex; justify-content:space-between; align-items:flex-start; }
        .hc-day-num {
            width:30px; height:30px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:0.875rem; font-weight:700; color:#334155; transition:all 0.2s;
        }
        .hc-day.today .hc-day-num { background:#2563eb; color:#fff; box-shadow:0 4px 6px rgba(37,99,235,0.2); }
        .hc-day.sunday .hc-day-num { color:#dc2626; }
        .hc-day.holiday .hc-day-num { background:#fef2f2; color:#dc2626; }
        .hc-day.working .hc-day-num { background:#ecfdf5; color:#059669; }
        .hc-day.today.holiday .hc-day-num { background:#dc2626; color:#fff; box-shadow:0 4px 6px rgba(220,38,38,0.2); }
        .hc-day.today.working .hc-day-num { background:#059669; color:#fff; box-shadow:0 4px 6px rgba(5,150,105,0.2); }

        .hc-day-override { width:8px; height:8px; border-radius:50%; background:#d97706; }

        .hc-day-status {
            font-size:0.625rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em;
            padding:4px 8px; border-radius:6px; display:inline-flex; align-self:flex-start;
        }
        .hc-day.working .hc-day-status { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .hc-day.holiday .hc-day-status { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }

        .hc-day-name { font-size:0.75rem; color:#475569; font-weight:600; line-height:1.3; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; }
        
        .hc-day-actions { position:absolute; bottom:6px; right:6px; display:flex; gap:4px; opacity:0; transition:all 0.2s; transform:translateY(4px); }
        .hc-day:hover .hc-day-actions { opacity:1; transform:translateY(0); }
        .hc-day-btn {
            width:26px; height:26px; border-radius:6px; display:flex; align-items:center; justify-content:center;
            border:none; cursor:pointer; transition:all 0.2s; background:#fff; color:#64748b; box-shadow:0 2px 4px rgba(0,0,0,0.05); border:1px solid #e2e8f0;
        }
        .hc-day-btn:hover { color:#0f172a; border-color:#cbd5e1; }
        .hc-day-btn.del:hover { color:#dc2626; border-color:#fca5a5; background:#fef2f2; }

        /* ── LEGEND ── */
        .hc-legend { display:flex; align-items:center; gap:2rem; padding:1rem 1.5rem; flex-wrap:wrap; background:#fff; border:1px solid #e2e8f0; border-radius:14px; margin-bottom:1.5rem; }
        .hc-legend-item { display:flex; align-items:center; gap:8px; font-size:0.8125rem; color:#475569; font-weight:600; }
        .hc-legend-dot { width:14px; height:14px; border-radius:4px; }
        .hc-legend-dot.work { background:#059669; }
        .hc-legend-dot.off { background:#dc2626; }
        .hc-legend-dot.override { background:#d97706; }
        .hc-legend-dot.today { background:#2563eb; }

        /* ── MODAL STYLES (Glassmorphism) ── */
        .hc-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,0.4); z-index:9999; align-items:center; justify-content:center; padding:1.5rem; backdrop-filter:blur(8px); opacity:0; transition:opacity 0.3s ease; }
        .hc-overlay.show { opacity:1; }
        .hc-modal { background:#fff; border-radius:24px; width:100%; max-width:480px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); transform:scale(0.95); transition:transform 0.3s cubic-bezier(0.34,1.56,0.64,1); }
        .hc-overlay.show .hc-modal { transform:scale(1); }
        .hc-modal-head { display:flex; align-items:center; justify-content:space-between; padding:1.5rem; border-bottom:1px solid #f1f5f9; background:#fff; }
        .hc-modal-title { font-size:1.125rem; font-weight:800; color:#0f172a; }
        .hc-modal-close { background:#f1f5f9; border:none; cursor:pointer; width:32px; height:32px; border-radius:50%; color:#64748b; transition:all 0.2s; display:flex; align-items:center; justify-content:center; }
        .hc-modal-close:hover { color:#0f172a; background:#e2e8f0; }
        .hc-modal-body { padding:1.5rem; }
        .hc-modal-foot { padding:1.25rem 1.5rem; border-top:1px solid #f1f5f9; background:#f8fafc; display:flex; justify-content:flex-end; gap:0.75rem; }
        .hc-form-row { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
        .hc-form-group { display:flex; flex-direction:column; gap:0.5rem; }
        .hc-form-group.full { grid-column:1/-1; }
        .hc-form-label { font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; }
        .hc-form-label .req { color:#ef4444; }
        .hc-form-inp, .hc-form-sel, .hc-form-txt {
            width:100%; padding:0.75rem 1rem; border:1px solid #e2e8f0; border-radius:12px;
            background:#f8fafc; font-family:inherit; font-size:0.875rem; color:#0f172a; font-weight:500;
            transition:all 0.2s; outline:none;
        }
        .hc-form-inp:focus, .hc-form-sel:focus, .hc-form-txt:focus { border-color:#6366f1; background:#fff; box-shadow:0 0 0 4px rgba(99,102,241,0.1); }
        .hc-form-txt { resize:vertical; min-height:80px; line-height:1.5; }

        .hc-type-toggle { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; }
        .hc-type-opt { position:relative; }
        .hc-type-opt input { position:absolute; opacity:0; pointer-events:none; }
        .hc-type-card {
            display:flex; align-items:center; gap:0.5rem; padding:0.75rem 1rem; border-radius:12px;
            border:2px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-size:0.875rem; font-weight:700; color:#64748b; background:#fff;
        }
        .hc-type-card:hover { border-color:#cbd5e1; }
        .hc-type-opt input:checked ~ .hc-type-card.holiday-opt { border-color:#dc2626; background:#fef2f2; color:#dc2626; }
        .hc-type-opt input:checked ~ .hc-type-card.work-opt { border-color:#059669; background:#ecfdf5; color:#059669; }
        .hc-type-dot { width:24px; height:24px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .hc-type-dot.off { background:#fef2f2; color:#dc2626; }
        .hc-type-dot.on { background:#ecfdf5; color:#059669; }

        @media(max-width:768px) {
            .hc-stats { grid-template-columns:1fr 1fr; }
            .hc-day { min-height:80px; padding:0.5rem; }
            .hc-day-status { display:none; }
            .hc-day-name { font-size:0.6875rem; -webkit-line-clamp:1; }
            .hc-cal-head div { font-size:0.625rem; padding:0.75rem 4px; }
            .hc-form-row { grid-template-columns:1fr; }
            .hc-toolbar { flex-direction:column; align-items:stretch; }
            .hc-toolbar-l { justify-content:space-between; }
        }
    </style>
    @endpush

    <div class="hc-wrap">

        {{-- ─── TOOLBAR ─── --}}
        @php
            $prevMonth = \Carbon\Carbon::parse($month . '-01')->subMonth()->format('Y-m');
            $nextMonth = \Carbon\Carbon::parse($month . '-01')->addMonth()->format('Y-m');
            $monthLabel = \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y');
        @endphp
        <div class="hc-toolbar">
            <div class="hc-toolbar-l">
                <a href="{{ route('sdm.libur.index', ['month' => $prevMonth]) }}" class="hc-nav-btn" title="Bulan Sebelumnya">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <div class="hc-month-label">{{ $monthLabel }}</div>
                <a href="{{ route('sdm.libur.index', ['month' => $nextMonth]) }}" class="hc-nav-btn" title="Bulan Berikutnya">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            </div>
            <a href="{{ route('sdm.libur.index') }}" class="hc-today-btn">Kembali ke Hari Ini</a>
        </div>

        {{-- ─── STATS ─── --}}
        <div class="hc-stats">
            <div class="hc-stat">
                <div class="hc-stat-ico blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="hc-stat-info">
                    <span class="hc-stat-val">{{ $stats['total'] }}</span>
                    <span class="hc-stat-lbl">Total Hari</span>
                </div>
            </div>
            <div class="hc-stat">
                <div class="hc-stat-ico green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div class="hc-stat-info">
                    <span class="hc-stat-val">{{ $stats['working'] }}</span>
                    <span class="hc-stat-lbl">Hari Kerja</span>
                </div>
            </div>
            <div class="hc-stat">
                <div class="hc-stat-ico red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div class="hc-stat-info">
                    <span class="hc-stat-val">{{ $stats['holiday'] }}</span>
                    <span class="hc-stat-lbl">Hari Libur</span>
                </div>
            </div>
            <div class="hc-stat">
                <div class="hc-stat-ico amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div class="hc-stat-info">
                    <span class="hc-stat-val">{{ $stats['override'] }}</span>
                    <span class="hc-stat-lbl">Manual Override</span>
                </div>
            </div>
        </div>

        {{-- ─── MODE BAR ─── --}}
        <div class="hc-mode-bar">
            <div class="hc-mode-left">
                <span class="hc-mode-badge {{ $calendarMode }}">
                    <span class="dot"></span>
                    Mode: {{ ucfirst($calendarMode) }}
                </span>
                <span class="hc-mode-info">
                    @if($calendarMode === 'auto')
                        Karyawan otomatis masuk pada {{ $workingDaysMode === 'mon_fri' ? 'Senin–Jumat' : 'Senin–Sabtu' }}.
                    @else
                        Karyawan hanya masuk pada tanggal yang berstatus "Hari Kerja".
                    @endif
                </span>
            </div>
            @if($calendarMode === 'manual')
            <div class="hc-mode-actions">
                @can('create_absensi')
                    <form method="POST" action="{{ route('sdm.libur.generate') }}" style="margin:0;">
                        @csrf
                        <input type="hidden" name="month" value="{{ $month }}">
                        <button type="submit" class="hc-gen-btn fill" onclick="this.innerHTML='Processing...'; this.disabled=true; this.form.submit();">Isi Default</button>
                    </form>
                    <form method="POST" action="{{ route('sdm.libur.generate') }}" style="margin:0;" onsubmit="return confirm('Hapus seluruh pengaturan bulan ini dan buat ulang sesuai default?');">
                        @csrf
                        <input type="hidden" name="month" value="{{ $month }}">
                        <input type="hidden" name="overwrite" value="1">
                        <button type="submit" class="hc-gen-btn danger">Regenerate Ulang</button>
                    </form>
                @endcan
            </div>
            @endif
        </div>

        {{-- ─── CALENDAR GRID ─── --}}
        <div class="hc-cal">
            <div class="hc-cal-head">
                <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div>
                <div class="weekend">Sab</div><div class="weekend">Min</div>
            </div>
            <div class="hc-cal-body">
                @php
                    $firstDay = \Carbon\Carbon::parse($calendar[0]['date']);
                    // Day of week ISO: 1=Mon, 2=Tue, ..., 7=Sun.
                    // We want Monday as column 0, so subtract 1.
                    $startDow = $firstDay->dayOfWeekIso - 1; 
                    for ($i = 0; $i < $startDow; $i++) {
                        echo '<div class="hc-day empty"></div>';
                    }
                @endphp

                @foreach($calendar as $c)
                    @php
                        $row = $c['row'];
                        $isWorking = $c['is_working'];
                        $hasOverride = $row !== null;
                        $classes = [];
                        $classes[] = $isWorking ? 'working' : 'holiday';
                        if ($c['is_today']) $classes[] = 'today';
                        $dowNum = \Carbon\Carbon::parse($c['date'])->dayOfWeek;
                        if ($dowNum === 0) $classes[] = 'sunday';
                    @endphp
                    <div class="hc-day {{ implode(' ', $classes) }}"
                        @can('create_absensi')
                            @if(!$hasOverride)
                                onclick="openAddHolidayModalWithDate('{{ $c['date'] }}')"
                            @endif
                        @endcan
                    >
                        <div class="hc-day-head">
                            <div class="hc-day-num">{{ $c['day'] }}</div>
                            @if($hasOverride)
                                <div class="hc-day-override" title="Override manual aktif"></div>
                            @endif
                        </div>
                        <span class="hc-day-status">{{ $isWorking ? 'Hari Kerja' : 'Libur' }}</span>
                        @if($hasOverride && $row->name)
                            <div class="hc-day-name" title="{{ $row->name }}">{{ $row->name }}</div>
                        @endif

                        @if($hasOverride)
                        <div class="hc-day-actions">
                            @can('edit_absensi')
                                <button type="button" class="hc-day-btn"
                                    data-id="{{ $row->id }}" data-name="{{ $row->name }}"
                                    data-notes="{{ $row->notes }}" data-working="{{ $row->is_working_day ? '1' : '0' }}"
                                    onclick="event.stopPropagation(); openEditHolidayModal(this)" title="Edit Tanggal">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                            @endcan
                            @can('delete_absensi')
                                <form method="POST" action="{{ route('sdm.libur.destroy', $row) }}" style="margin:0;" onsubmit="event.stopPropagation(); return confirm('Hapus pengaturan pada tanggal ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="hc-day-btn del" title="Hapus Override" onclick="event.stopPropagation();">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                        @endif
                    </div>
                @endforeach

                {{-- Fill remaining cells --}}
                @php
                    $totalCells = $startDow + count($calendar);
                    $remaining = (7 - ($totalCells % 7)) % 7;
                    for ($i = 0; $i < $remaining; $i++) {
                        echo '<div class="hc-day empty"></div>';
                    }
                @endphp
            </div>
        </div>

        {{-- ─── LEGEND ─── --}}
        <div class="hc-legend">
            <div class="hc-legend-item"><div class="hc-legend-dot work"></div> Hari Kerja Aktif</div>
            <div class="hc-legend-item"><div class="hc-legend-dot off"></div> Hari Libur / Off</div>
            <div class="hc-legend-item"><div class="hc-legend-dot override"></div> Override Manual</div>
            <div class="hc-legend-item"><div class="hc-legend-dot today"></div> Hari Ini</div>
            <span style="margin-left:auto; font-size:0.8125rem; color:#94a3b8; font-weight:500;">
                <svg width="14" height="14" style="vertical-align:-2px; margin-right:4px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Klik pada tanggal kosong untuk menambahkan event libur/lembur baru.
            </span>
        </div>

    </div>

    {{-- ─── MODAL ADD ─── --}}
    <div id="addHolidayModal" class="hc-overlay">
        <div class="hc-modal">
            <div class="hc-modal-head">
                <div class="hc-modal-title">Atur Tanggal Khusus</div>
                <button type="button" class="hc-modal-close" onclick="closeAddHolidayModal()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form action="{{ route('sdm.libur.store') }}" method="POST">
                @csrf
                <div class="hc-modal-body">
                    <div class="hc-form-row">
                        <div class="hc-form-group">
                            <label class="hc-form-label">Tanggal <span class="req">*</span></label>
                            <input type="date" name="date" id="addHolidayDate" class="hc-form-inp" required>
                        </div>
                        <div class="hc-form-group">
                            <label class="hc-form-label">Tipe Hari</label>
                            <div class="hc-type-toggle">
                                <label class="hc-type-opt">
                                    <input type="radio" name="is_working_day" value="0" checked>
                                    <div class="hc-type-card holiday-opt">
                                        <div class="hc-type-dot off">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </div>
                                        Libur
                                    </div>
                                </label>
                                <label class="hc-type-opt">
                                    <input type="radio" name="is_working_day" value="1">
                                    <div class="hc-type-card work-opt">
                                        <div class="hc-type-dot on">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                        </div>
                                        Kerja
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="hc-form-group" style="margin-bottom:1.25rem;">
                        <label class="hc-form-label">Nama Event</label>
                        <input name="name" class="hc-form-inp" placeholder="Misal: Idul Fitri / Lembur / Cuti Bersama">
                    </div>
                    <div class="hc-form-group">
                        <label class="hc-form-label">Catatan Internal</label>
                        <textarea name="notes" rows="2" class="hc-form-txt" placeholder="Keterangan tambahan opsional..."></textarea>
                    </div>
                </div>
                <div class="hc-modal-foot">
                    <button type="button" class="hr-btn hr-btn-ghost" onclick="closeAddHolidayModal()">Batalkan</button>
                    <button type="submit" class="hr-btn hr-btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Simpan Tanggal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── MODAL EDIT ─── --}}
    <div id="editHolidayModal" class="hc-overlay">
        <div class="hc-modal">
            <div class="hc-modal-head">
                <div class="hc-modal-title">Edit Pengaturan Tanggal</div>
                <button type="button" class="hc-modal-close" onclick="closeEditHolidayModal()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form id="editHolidayForm" method="POST">
                @csrf @method('PATCH')
                <div class="hc-modal-body">
                    <div class="hc-form-group" style="margin-bottom:1.25rem;">
                        <label class="hc-form-label">Status Operasional</label>
                        <div class="hc-type-toggle">
                            <label class="hc-type-opt">
                                <input type="radio" name="is_working_day" value="0" id="editHolidayOff">
                                <div class="hc-type-card holiday-opt">
                                    <div class="hc-type-dot off">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </div>
                                    Libur
                                </div>
                            </label>
                            <label class="hc-type-opt">
                                <input type="radio" name="is_working_day" value="1" id="editHolidayOn">
                                <div class="hc-type-card work-opt">
                                    <div class="hc-type-dot on">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    </div>
                                    Hari Kerja
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="hc-form-group" style="margin-bottom:1.25rem;">
                        <label class="hc-form-label">Nama Event</label>
                        <input name="name" id="editHolidayName" class="hc-form-inp" placeholder="Kosongkan jika tidak ada">
                    </div>
                    <div class="hc-form-group">
                        <label class="hc-form-label">Catatan</label>
                        <textarea name="notes" id="editHolidayNotes" rows="2" class="hc-form-txt" placeholder="Kosongkan jika tidak ada..."></textarea>
                    </div>
                </div>
                <div class="hc-modal-foot">
                    <button type="button" class="hr-btn hr-btn-ghost" onclick="closeEditHolidayModal()">Batal</button>
                    <button type="submit" class="hr-btn hr-btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openAddHolidayModal() { 
            const overlay = document.getElementById('addHolidayModal');
            overlay.style.display = 'flex'; 
            setTimeout(() => overlay.classList.add('show'), 10);
        }
        function closeAddHolidayModal() { 
            const overlay = document.getElementById('addHolidayModal');
            overlay.classList.remove('show');
            setTimeout(() => overlay.style.display = 'none', 300);
        }
        function openAddHolidayModalWithDate(dateStr) {
            const inp = document.getElementById('addHolidayDate');
            if (inp) inp.value = dateStr;
            openAddHolidayModal();
        }
        function openEditHolidayModal(btn) {
            const form = document.getElementById('editHolidayForm');
            form.action = "{{ url('sdm/libur') }}/" + btn.dataset.id;
            const working = btn.dataset.working || '0';
            document.getElementById('editHolidayOff').checked = (working === '0');
            document.getElementById('editHolidayOn').checked = (working === '1');
            document.getElementById('editHolidayName').value = btn.dataset.name || '';
            document.getElementById('editHolidayNotes').value = btn.dataset.notes || '';
            
            const overlay = document.getElementById('editHolidayModal');
            overlay.style.display = 'flex';
            setTimeout(() => overlay.classList.add('show'), 10);
        }
        function closeEditHolidayModal() { 
            const overlay = document.getElementById('editHolidayModal');
            overlay.classList.remove('show');
            setTimeout(() => overlay.style.display = 'none', 300);
        }
        
        // Close on overlay click
        document.querySelectorAll('.hc-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddHolidayModal();
                    closeEditHolidayModal();
                }
            });
        });

        document.addEventListener('keydown', function(e) { 
            if(e.key === 'Escape') { 
                closeAddHolidayModal(); 
                closeEditHolidayModal(); 
            } 
        });
    </script>
    @endpush
</x-hr-layout>
