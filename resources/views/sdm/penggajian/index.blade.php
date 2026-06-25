<x-hr-layout>
    <x-slot name="eyebrow">Manajemen Payroll</x-slot>
    <x-slot name="title">Penggajian Karyawan</x-slot>
    <x-slot name="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </x-slot>
    <x-slot name="iconBg">bg-green</x-slot>
    <x-slot name="description">Kalkulasi gaji otomatis berdasarkan kehadiran, tunjangan, dan potongan.</x-slot>
    <x-slot name="actions">
        @can('view_potongan_gaji')
            <a href="{{ route('sdm.potongan.index') }}" class="pg-hbtn pg-hbtn-ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Potongan & Bonus
            </a>
        @endcan
    </x-slot>

    <style>
        .pg-wrap{font-family:'Plus Jakarta Sans',system-ui,sans-serif}
        .pg-wrap *,.pg-wrap *::before,.pg-wrap *::after{box-sizing:border-box}
        .pg-wrap .mono{font-family:'JetBrains Mono',monospace}
        .pg-hbtn{display:inline-flex;align-items:center;gap:.5rem;padding:.5rem 1rem;border-radius:10px;font-size:.8125rem;font-weight:600;text-decoration:none;transition:all .15s;border:1px solid transparent;cursor:pointer}
        .pg-hbtn-ghost{background:rgba(5,150,105,.08);color:#059669}
        .pg-hbtn-ghost:hover{background:rgba(5,150,105,.15)}
        .pg-mnav{display:flex;align-items:center;gap:.75rem;padding:1rem 1.25rem;background:linear-gradient(135deg,#059669,#10b981);border-radius:16px;margin-bottom:1.25rem;color:#fff;flex-wrap:wrap}
        .pg-mnav-label{font-size:1.0625rem;font-weight:700;letter-spacing:-.01em;flex:1}
        .pg-mnav-input{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:8px;padding:.375rem .625rem;color:#fff;font-size:.8125rem;font-family:'JetBrains Mono',monospace;outline:none}
        .pg-mnav-input::-webkit-calendar-picker-indicator{filter:invert(1)}
        .pg-mnav-wd{padding:.375rem .75rem;border-radius:8px;background:rgba(255,255,255,.15);font-size:.75rem;font-weight:600}
        .pg-stats{display:grid;grid-template-columns:repeat(5,1fr);gap:.75rem;margin-bottom:1.25rem}
        @media(max-width:1024px){.pg-stats{grid-template-columns:repeat(3,1fr)}}
        @media(max-width:640px){.pg-stats{grid-template-columns:repeat(2,1fr)}}
        .pg-st{background:#fff;border-radius:14px;padding:1rem 1.125rem;border:1px solid #e5e7eb;transition:all .2s}
        .pg-st:hover{border-color:#a7f3d0;box-shadow:0 4px 12px rgba(5,150,105,.08)}
        .pg-st-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:.625rem}
        .pg-st-val{font-size:1.25rem;font-weight:800;letter-spacing:-.02em;line-height:1}
        .pg-st-lbl{font-size:.6875rem;color:#6b7280;margin-top:.25rem;font-weight:500;text-transform:uppercase;letter-spacing:.04em}
        .pg-abar{display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;flex-wrap:wrap}
        .pg-abtn{display:inline-flex;align-items:center;gap:.5rem;padding:.5rem 1rem;border-radius:10px;font-size:.8125rem;font-weight:600;border:1px solid transparent;cursor:pointer;transition:all .15s}
        .pg-abtn-p{background:#059669;color:#fff;border-color:#059669}
        .pg-abtn-p:hover{background:#047857}
        .pg-abtn-s{background:#fff;color:#374151;border-color:#d1d5db}
        .pg-abtn-s:hover{background:#f9fafb}
        .pg-sec{background:#fff;border-radius:16px;border:1px solid #e5e7eb;overflow:hidden;margin-bottom:1.5rem}
        .pg-sec-hd{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid #f3f4f6}
        .pg-sec-t{font-size:.9375rem;font-weight:700;color:#111827;margin:0}
        .pg-sec-sub{font-size:.75rem;color:#6b7280;margin:.125rem 0 0 0}
        .pg-tbl-wrap{overflow-x:auto}
        .pg-tbl{width:100%;border-collapse:collapse}
        .pg-tbl th{padding:.625rem 1rem;font-size:.6875rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#6b7280;background:#f9fafb;border-bottom:1px solid #e5e7eb;text-align:left;white-space:nowrap}
        .pg-tbl td{padding:.75rem 1rem;font-size:.8125rem;border-bottom:1px solid #f3f4f6;color:#374151}
        .pg-tbl tbody tr{transition:background .15s;cursor:pointer}
        .pg-tbl tbody tr:hover{background:#f0fdf4}
        .pg-tbl tbody tr.pg-locked{background:#fafafa}
        .pg-tbl tbody tr.pg-locked:hover{background:#f3f4f6}
        .pg-av{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8125rem;color:#fff;flex-shrink:0}
        .pg-bdg{display:inline-flex;align-items:center;gap:.25rem;padding:.1875rem .5rem;border-radius:6px;font-size:.625rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap}
        .pg-bdg-draft{background:#fef3c7;color:#b45309}
        .pg-bdg-locked{background:#d1fae5;color:#065f46}
        .pg-detail{display:none;background:#f9fafb;border-bottom:2px solid #e5e7eb}
        .pg-detail.show{display:table-row}
        .pg-detail-inner{padding:1rem 1.25rem 1.25rem}
        .pg-dgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
        @media(max-width:768px){.pg-dgrid{grid-template-columns:1fr}}
        .pg-dcol{background:#fff;border-radius:12px;border:1px solid #e5e7eb;padding:1rem}
        .pg-dcol-t{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.75rem;display:flex;align-items:center;gap:.375rem}
        .pg-drow{display:flex;justify-content:space-between;align-items:center;padding:.375rem 0;font-size:.8125rem;border-bottom:1px solid #f9fafb}
        .pg-drow:last-child{border-bottom:none}
        .pg-drow-lbl{color:#6b7280}
        .pg-drow-val{font-weight:600}
        .pg-dtotal{display:flex;justify-content:space-between;align-items:center;padding:.625rem 0;font-size:.9375rem;font-weight:700;border-top:2px solid #e5e7eb;margin-top:.5rem}
        .pg-acts{display:flex;align-items:center;gap:.375rem}
        .pg-act-btn{display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;cursor:pointer;transition:all .15s;color:#6b7280;text-decoration:none}
        .pg-act-btn:hover{border-color:#86efac;color:#059669;background:#f0fdf4}
        .pg-act-btn.danger:hover{border-color:#fca5a5;color:#dc2626;background:#fef2f2}
        .pg-act-btn.lock{color:#059669}.pg-act-btn.unlock{color:#dc2626}
        .pg-mo{display:none;position:fixed;inset:0;background:rgba(15,23,42,.5);z-index:9999;align-items:center;justify-content:center;padding:1rem}
        .pg-mo.show{display:flex}
        .pg-md{background:#fff;border-radius:16px;width:100%;max-width:500px;max-height:90vh;overflow-y:auto;box-shadow:0 25px 50px -12px rgba(0,0,0,.25)}
        .pg-md-hd{display:flex;align-items:center;justify-content:space-between;padding:1.125rem 1.5rem;border-bottom:1px solid #f3f4f6}
        .pg-md-t{font-size:1rem;font-weight:700;color:#111827;margin:0}
        .pg-md-x{background:none;border:none;cursor:pointer;padding:.375rem;color:#9ca3af;border-radius:8px;transition:all .15s}
        .pg-md-x:hover{background:#f3f4f6;color:#374151}
        .pg-md-b{padding:1.25rem 1.5rem}
        .pg-md-ft{padding:1rem 1.5rem;border-top:1px solid #f3f4f6;display:flex;justify-content:flex-end;gap:.5rem}
        .pg-fg{margin-bottom:1rem}
        .pg-fl{display:block;font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.375rem;text-transform:uppercase;letter-spacing:.04em}
        .pg-fi{width:100%;padding:.5rem .75rem;border:1px solid #d1d5db;border-radius:10px;font-size:.8125rem;font-family:'JetBrains Mono',monospace;outline:none;transition:all .15s;background:#fff}
        .pg-fi:focus{border-color:#34d399;box-shadow:0 0 0 3px rgba(5,150,105,.1)}
        .pg-fi-hint{font-size:.6875rem;color:#9ca3af;margin-top:.25rem}
        .pg-empty{padding:3rem 1.5rem;text-align:center}
        .pg-empty-ic{width:56px;height:56px;border-radius:16px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;margin:0 auto .875rem;color:#059669}
        .pg-chevron{transition:transform .2s;color:#d1d5db}
        .pg-chevron.open{transform:rotate(90deg);color:#059669}
        .pg-rp{color:#059669;font-weight:700}
        .pg-rp-red{color:#dc2626;font-weight:600}
        /* Info banner & live preview */
        .pg-info-bar{display:flex;align-items:center;gap:1.25rem;padding:.875rem 1.25rem;background:#f0fdf4;border:1px solid #a7f3d0;border-radius:14px;margin-bottom:1rem;flex-wrap:wrap}
        .pg-info-warn{background:#fffbeb;border-color:#fcd34d}
        .pg-info-item{display:flex;align-items:center;gap:.4rem;font-size:.8125rem;color:#374151}
        .pg-info-item svg{flex-shrink:0}
        .pg-info-val{font-weight:700;font-family:'JetBrains Mono',monospace;color:#065f46}
        .pg-info-warn .pg-info-val{color:#92400e}
        .pg-info-sep{width:1px;height:18px;background:#a7f3d0;flex-shrink:0}
        .pg-info-warn .pg-info-sep{background:#fcd34d}
        .pg-preview-box{margin-top:1rem;padding:.875rem 1rem;background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1px solid #a7f3d0;border-radius:12px}
        .pg-preview-label{font-size:.6875rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#065f46;margin-bottom:.375rem}
        .pg-preview-val{font-size:1.25rem;font-weight:800;font-family:'JetBrains Mono',monospace;color:#059669;letter-spacing:-.02em}
        .pg-preview-breakdown{display:grid;grid-template-columns:1fr 1fr;gap:.25rem;margin-top:.625rem;font-size:.75rem}
        .pg-preview-row{display:flex;justify-content:space-between;color:#374151;padding:.125rem 0}
        .pg-preview-row.minus{color:#dc2626}
        .pg-preview-row.total{border-top:1px solid #a7f3d0;padding-top:.375rem;margin-top:.125rem;font-weight:700}
        @keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}
    </style>

    <div class="pg-wrap">
        {{-- Month Navigation --}}
        @php
            $carbon = \Carbon\Carbon::parse($month . '-01');
            $prevMonth = $carbon->copy()->subMonth()->format('Y-m');
            $nextMonth = $carbon->copy()->addMonth()->format('Y-m');
        @endphp
        <div class="pg-mnav">
            <a href="{{ route('sdm.penggajian.index', ['month' => $prevMonth]) }}" class="pg-hbtn" style="background:rgba(255,255,255,.1);color:#fff;padding:.375rem .625rem">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
            <form method="GET" action="{{ route('sdm.penggajian.index') }}" style="display:flex;align-items:center;gap:.5rem;margin:0">
                <input type="month" name="month" value="{{ $month }}" class="pg-mnav-input" onchange="this.form.submit()">
            </form>
            <span class="pg-mnav-label">{{ $monthLabel }}</span>
            <span class="pg-mnav-wd">{{ $workingDaysCount }} Hari Kerja</span>
            <a href="{{ route('sdm.penggajian.index', ['month' => $nextMonth]) }}" class="pg-hbtn" style="background:rgba(255,255,255,.1);color:#fff;padding:.375rem .625rem">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>

        {{-- Stats --}}
        <div class="pg-stats">
            <div class="pg-st">
                <div class="pg-st-icon" style="background:#ecfdf5;color:#059669">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="pg-st-val" style="color:#059669">{{ $stats['total'] }}</div>
                <div class="pg-st-lbl">Total Slip Gaji</div>
            </div>
            <div class="pg-st">
                <div class="pg-st-icon" style="background:#fef3c7;color:#b45309">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div class="pg-st-val" style="color:#b45309">{{ $stats['draft'] }}</div>
                <div class="pg-st-lbl">Draft</div>
            </div>
            <div class="pg-st">
                <div class="pg-st-icon" style="background:#d1fae5;color:#065f46">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div class="pg-st-val" style="color:#065f46">{{ $stats['locked'] }}</div>
                <div class="pg-st-lbl">Terkunci</div>
            </div>
            <div class="pg-st">
                <div class="pg-st-icon" style="background:#fee2e2;color:#dc2626">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                </div>
                <div class="pg-st-val pg-rp-red" style="font-size:1rem">Rp {{ number_format($stats['total_deductions'], 0, ',', '.') }}</div>
                <div class="pg-st-lbl">Total Potongan</div>
            </div>
            <div class="pg-st">
                <div class="pg-st-icon" style="background:#ecfdf5;color:#059669">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="pg-st-val pg-rp" style="font-size:1rem">Rp {{ number_format($stats['total_net'], 0, ',', '.') }}</div>
                <div class="pg-st-lbl">Total THP</div>
            </div>
        </div>

        {{-- Action Bar --}}
        @php
            $pgWorkStart   = $setting->sdm_work_start_time ?? null;
            $pgWorkEnd     = $setting->sdm_work_end_time ?? null;
            $pgGrace       = (int)($setting->sdm_late_grace_minutes ?? 10);
            $pgOtRate      = (float)($setting->sdm_overtime_rate_per_hour ?? 0);
            $pgScheduleOk  = $pgWorkStart && $pgWorkEnd;
        @endphp

        {{-- Work Schedule Info Banner --}}
        <div class="pg-info-bar {{ $pgScheduleOk ? '' : 'pg-info-warn' }}">
            @if(!$pgScheduleOk)
                <div class="pg-info-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <span style="color:#b45309;font-weight:600">Jam kerja belum dikonfigurasi — harap atur di menu Absensi → Atur Jadwal sebelum Generate.</span>
                </div>
            @else
                <div class="pg-info-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Masuk</span>
                    <span class="pg-info-val">{{ $pgWorkStart }}</span>
                </div>
                <div class="pg-info-sep"></div>
                <div class="pg-info-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                    <span>Pulang</span>
                    <span class="pg-info-val">{{ $pgWorkEnd }}</span>
                </div>
                <div class="pg-info-sep"></div>
                <div class="pg-info-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>Toleransi</span>
                    <span class="pg-info-val">{{ $pgGrace }} mnt</span>
                </div>
                @if($pgOtRate > 0)
                <div class="pg-info-sep"></div>
                <div class="pg-info-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    <span>Lembur/Jam</span>
                    <span class="pg-info-val">Rp {{ number_format($pgOtRate, 0, ',', '.') }}</span>
                </div>
                @endif
                <div style="margin-left:auto">
                    <a href="{{ route('sdm.absensi.index') }}" style="font-size:.75rem;color:#059669;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.25rem">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Ubah Jadwal
                    </a>
                </div>
            @endif
        </div>

        <div class="pg-abar">
            @can('create_penggajian')
                <form method="POST" action="{{ route('sdm.penggajian.generate') }}" style="margin:0" id="pgGenForm">
                    @csrf
                    <input type="hidden" name="month" value="{{ $month }}">
                    <button type="submit" class="pg-abtn pg-abtn-p" id="pgGenBtn" onclick="if(!confirm('Generate/Recalculate semua slip gaji untuk {{ $monthLabel }}?')){return false}this.disabled=true;this.innerHTML='<svg width=\'16\' height=\'16\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' style=\'animation:spin 1s linear infinite\'><path d=\'M23 4v6h-6\'/><path d=\'M1 20v-6h6\'/></svg> Generating...';this.form.submit()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                        Generate {{ $monthLabel }}
                    </button>
                </form>
            @endcan
        </div>

        {{-- Payroll Table --}}
        <div class="pg-sec">
            <div class="pg-sec-hd">
                <div>
                    <h2 class="pg-sec-t">Daftar Slip Gaji</h2>
                    <p class="pg-sec-sub">{{ $payrolls->count() }} karyawan &middot; {{ $monthLabel }} &middot; Klik baris untuk detail</p>
                </div>
            </div>
            @if($payrolls->isEmpty())
                <div class="pg-empty">
                    <div class="pg-empty-ic">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div style="font-size:.9375rem;font-weight:700;color:#374151">Belum ada slip gaji</div>
                    <div style="font-size:.8125rem;color:#6b7280;margin-top:.25rem">Klik tombol <strong>Generate</strong> untuk menghitung gaji otomatis.</div>
                </div>
            @else
                <div class="pg-tbl-wrap">
                    <table class="pg-tbl" id="pgTable">
                        <thead>
                            <tr>
                                <th style="width:32px"></th>
                                <th>Karyawan</th>
                                <th style="text-align:center">Status</th>
                                <th style="text-align:center">Hadir</th>
                                <th style="text-align:right">Gaji Pokok</th>
                                <th style="text-align:right">Tunjangan</th>
                                <th style="text-align:right">Potongan</th>
                                <th style="text-align:right">THP</th>
                                <th style="text-align:center;width:140px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $pr)
                                @php
                                    $totalTunjangan = ($pr->total_allowance ?? 0) + ($pr->fixed_allowance_total ?? 0) + ($pr->overtime_pay ?? 0) + ($pr->incentive_amount ?? 0) + ($pr->performance_bonus ?? 0);
                                    $totalPotongan = ($pr->total_deductions ?? 0) + ($pr->absence_deduction ?? 0);
                                    $initial = strtoupper(substr($pr->user->name ?? '?', 0, 1));
                                    $avColors = ['#059669','#0891b2','#2563eb','#7c3aed','#d97706','#dc2626','#db2777','#4f46e5'];
                                    $avBg = $avColors[crc32($pr->user->name ?? '') % count($avColors)];
                                    $isLocked = (bool) $pr->locked_at;
                                @endphp
                                <tr class="{{ $isLocked ? 'pg-locked' : '' }}" onclick="pgToggle({{ $pr->id }}, this)" data-id="{{ $pr->id }}">
                                    <td>
                                        <svg class="pg-chevron" id="pgChev{{ $pr->id }}" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                    </td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:.625rem">
                                            <div class="pg-av" style="background:{{ $avBg }}">{{ $initial }}</div>
                                            <div>
                                                <div style="font-weight:700;font-size:.8125rem;color:#111827">{{ $pr->user->name ?? '-' }}</div>
                                                <div style="font-size:.6875rem;color:#9ca3af">{{ $pr->user->role ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align:center">
                                        @if($isLocked)
                                            <span class="pg-bdg pg-bdg-locked">Terkunci</span>
                                        @else
                                            <span class="pg-bdg pg-bdg-draft">Draft</span>
                                        @endif
                                    </td>
                                    <td style="text-align:center">
                                        <span style="font-weight:700">{{ $pr->total_attendance }}</span>
                                        <span style="font-size:.6875rem;color:#9ca3af">/{{ $pr->working_days }}</span>
                                    </td>
                                    <td style="text-align:right"><span class="mono">Rp {{ number_format($pr->total_basic_salary, 0, ',', '.') }}</span></td>
                                    <td style="text-align:right"><span class="mono" style="color:#059669">+{{ number_format($totalTunjangan, 0, ',', '.') }}</span></td>
                                    <td style="text-align:right"><span class="mono pg-rp-red">-{{ number_format($totalPotongan, 0, ',', '.') }}</span></td>
                                    <td style="text-align:right"><span class="mono pg-rp" style="font-size:.9375rem">Rp {{ number_format($pr->net_salary, 0, ',', '.') }}</span></td>
                                    <td style="text-align:center" onclick="event.stopPropagation()">
                                        <div class="pg-acts">
                                            <a href="{{ route('sdm.penggajian.print', $pr) }}" target="_blank" class="pg-act-btn" title="Cetak Slip">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                            </a>
                                            @can('edit_penggajian')
                                                @if(!$isLocked)
                                                    <button class="pg-act-btn" title="Penyesuaian" onclick="pgAdjustOpen({{ $pr->id }},{{ $pr->incentive_amount ?? 0 }},{{ $pr->performance_bonus ?? 0 }},'{{ $pr->override_total_basic_salary ?? '' }}','{{ $pr->override_late_meal_penalty ?? '' }}','{{ $pr->override_absence_deduction ?? '' }}',{{ $pr->total_basic_salary ?? 0 }},{{ $pr->total_allowance ?? 0 }},{{ $pr->fixed_allowance_total ?? 0 }},{{ $pr->overtime_pay ?? 0 }},{{ $pr->total_deductions ?? 0 }},{{ $pr->absence_deduction ?? 0 }},{{ $pr->late_meal_penalty ?? 0 }})">
                                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    </button>
                                                    <form action="{{ route('sdm.penggajian.lock', $pr) }}" method="POST" style="margin:0;display:inline">
                                                        @csrf
                                                        <button type="submit" class="pg-act-btn lock" title="Kunci Slip">
                                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('sdm.penggajian.unlock', $pr) }}" method="POST" style="margin:0;display:inline">
                                                        @csrf
                                                        <button type="submit" class="pg-act-btn unlock" title="Buka Kunci">
                                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
                                            @can('delete_penggajian')
                                                <form action="{{ route('sdm.penggajian.destroy', $pr) }}" method="POST" style="margin:0;display:inline" onsubmit="return confirm('Hapus slip gaji ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="pg-act-btn danger" {{ $isLocked ? 'disabled style=opacity:.4;cursor:not-allowed' : '' }} title="Hapus">
                                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                {{-- Detail Row --}}
                                <tr class="pg-detail" id="pgDetail{{ $pr->id }}">
                                    <td colspan="9">
                                        <div class="pg-detail-inner">
                                            <div class="pg-dgrid">
                                                {{-- Pendapatan --}}
                                                <div class="pg-dcol">
                                                    <div class="pg-dcol-t" style="color:#059669">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                                                        Pendapatan
                                                    </div>
                                                    <div class="pg-drow">
                                                        <span class="pg-drow-lbl">Gaji Pokok</span>
                                                        <span class="pg-drow-val mono">Rp {{ number_format($pr->total_basic_salary, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="pg-drow">
                                                        <span class="pg-drow-lbl">Uang Makan ({{ $pr->total_attendance }} hari x Rp {{ number_format($pr->meal_allowance_per_day ?? 0, 0, ',', '.') }})</span>
                                                        <span class="pg-drow-val mono">Rp {{ number_format($pr->meal_allowance_gross ?? 0, 0, ',', '.') }}</span>
                                                    </div>
                                                    @if(($pr->late_meal_penalty ?? 0) > 0)
                                                        <div class="pg-drow">
                                                            <span class="pg-drow-lbl" style="color:#dc2626">Potongan Telat Makan</span>
                                                            <span class="pg-drow-val mono pg-rp-red">-Rp {{ number_format($pr->late_meal_penalty, 0, ',', '.') }}</span>
                                                        </div>
                                                    @endif
                                                    @if(($pr->fixed_allowance_total ?? 0) > 0)
                                                        <div class="pg-drow">
                                                            <span class="pg-drow-lbl">Tunjangan Tetap</span>
                                                            <span class="pg-drow-val mono">Rp {{ number_format($pr->fixed_allowance_total, 0, ',', '.') }}</span>
                                                        </div>
                                                    @endif
                                                    @if(($pr->overtime_pay ?? 0) > 0)
                                                        <div class="pg-drow">
                                                            <span class="pg-drow-lbl">Lembur ({{ $pr->overtime_minutes ?? 0 }} mnt)</span>
                                                            <span class="pg-drow-val mono">Rp {{ number_format($pr->overtime_pay, 0, ',', '.') }}</span>
                                                        </div>
                                                    @endif
                                                    @if(($pr->incentive_amount ?? 0) > 0)
                                                        <div class="pg-drow">
                                                            <span class="pg-drow-lbl">Insentif</span>
                                                            <span class="pg-drow-val mono">Rp {{ number_format($pr->incentive_amount, 0, ',', '.') }}</span>
                                                        </div>
                                                    @endif
                                                    @if(($pr->performance_bonus ?? 0) > 0)
                                                        <div class="pg-drow">
                                                            <span class="pg-drow-lbl">Bonus Performa</span>
                                                            <span class="pg-drow-val mono">Rp {{ number_format($pr->performance_bonus, 0, ',', '.') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                {{-- Potongan --}}
                                                <div class="pg-dcol">
                                                    <div class="pg-dcol-t" style="color:#dc2626">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/></svg>
                                                        Potongan
                                                    </div>
                                                    @if(($pr->absence_deduction ?? 0) > 0)
                                                        <div class="pg-drow">
                                                            <span class="pg-drow-lbl">Alpha ({{ $pr->absent_days + $pr->missing_days + $pr->unpaid_leave_days }} hari)</span>
                                                            <span class="pg-drow-val mono pg-rp-red">-Rp {{ number_format($pr->absence_deduction, 0, ',', '.') }}</span>
                                                        </div>
                                                    @endif
                                                    @if(($pr->total_deductions ?? 0) > 0)
                                                        <div class="pg-drow">
                                                            <span class="pg-drow-lbl">Potongan Lainnya</span>
                                                            <span class="pg-drow-val mono pg-rp-red">-Rp {{ number_format($pr->total_deductions, 0, ',', '.') }}</span>
                                                        </div>
                                                    @endif
                                                    @if(($pr->absence_deduction ?? 0) == 0 && ($pr->total_deductions ?? 0) == 0)
                                                        <div class="pg-drow"><span class="pg-drow-lbl" style="font-style:italic;color:#d1d5db">Tidak ada potongan</span><span class="pg-drow-val mono" style="color:#d1d5db">Rp 0</span></div>
                                                    @endif
                                                </div>
                                                {{-- Kehadiran --}}
                                                <div class="pg-dcol">
                                                    <div class="pg-dcol-t" style="color:#2563eb">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                        Kehadiran
                                                    </div>
                                                    <div class="pg-drow">
                                                        <span class="pg-drow-lbl">Hari Kerja</span>
                                                        <span class="pg-drow-val">{{ $pr->working_days }} hari</span>
                                                    </div>
                                                    <div class="pg-drow">
                                                        <span class="pg-drow-lbl">Hadir</span>
                                                        <span class="pg-drow-val" style="color:#059669">{{ $pr->present_days }} hari</span>
                                                    </div>
                                                    <div class="pg-drow">
                                                        <span class="pg-drow-lbl">Terlambat</span>
                                                        <span class="pg-drow-val" style="color:#b45309">{{ $pr->late_days }} hari</span>
                                                    </div>
                                                    <div class="pg-drow">
                                                        <span class="pg-drow-lbl">Izin</span>
                                                        <span class="pg-drow-val">{{ $pr->izin_days }} hari</span>
                                                    </div>
                                                    <div class="pg-drow">
                                                        <span class="pg-drow-lbl">Sakit</span>
                                                        <span class="pg-drow-val">{{ $pr->sakit_days }} hari</span>
                                                    </div>
                                                    <div class="pg-drow">
                                                        <span class="pg-drow-lbl">Alpha</span>
                                                        <span class="pg-drow-val pg-rp-red">{{ $pr->absent_days + $pr->missing_days }} hari</span>
                                                    </div>
                                                    @if($pr->unpaid_leave_days > 0)
                                                        <div class="pg-drow">
                                                            <span class="pg-drow-lbl">Cuti Tidak Dibayar</span>
                                                            <span class="pg-drow-val pg-rp-red">{{ $pr->unpaid_leave_days }} hari</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="pg-dtotal" style="padding:1rem 0 .5rem">
                                                <span style="font-size:1rem;color:#111827">GAJI BERSIH (THP)</span>
                                                <span class="mono pg-rp" style="font-size:1.125rem">Rp {{ number_format($pr->net_salary, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Adjustment Modal --}}
    <div id="pgAdjustModal" class="pg-mo" onclick="if(event.target===this)this.classList.remove('show')">
        <div class="pg-md">
            <div class="pg-md-hd">
                <h3 class="pg-md-t">Penyesuaian Komponen Gaji</h3>
                <button class="pg-md-x" onclick="document.getElementById('pgAdjustModal').classList.remove('show')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form id="pgAdjustForm" method="POST">
                @csrf @method('PATCH')
                <div class="pg-md-b">
                    <div style="padding:.625rem .875rem;background:#ecfdf5;border-radius:10px;margin-bottom:1rem;font-size:.75rem;color:#065f46;border:1px solid #a7f3d0">
                        <strong>Tip:</strong> Kosongkan field override untuk menggunakan nilai otomatis dari sistem.
                    </div>
                    <div class="pg-fg">
                        <label class="pg-fl">Override Gaji Pokok</label>
                        <input type="text" inputmode="numeric" data-currency name="override_total_basic_salary" id="pgAdjBasic" class="pg-fi" placeholder="Kosongkan = otomatis" oninput="pgPreview()">
                        <div class="pg-fi-hint">Gaji pokok karyawan. Override jika ada penyesuaian khusus.</div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                        <div class="pg-fg">
                            <label class="pg-fl">Insentif / Bonus</label>
                            <input type="text" inputmode="numeric" data-currency name="incentive_amount" id="pgAdjIncentive" class="pg-fi" value="0" oninput="pgPreview()">
                        </div>
                        <div class="pg-fg">
                            <label class="pg-fl">Bonus Performa</label>
                            <input type="text" inputmode="numeric" data-currency name="performance_bonus" id="pgAdjPerformance" class="pg-fi" value="0" oninput="pgPreview()">
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                        <div class="pg-fg">
                            <label class="pg-fl">Override Potongan Telat</label>
                            <input type="text" inputmode="numeric" data-currency name="override_late_meal_penalty" id="pgAdjLatePenalty" class="pg-fi" placeholder="Kosongkan = otomatis" oninput="pgPreview()">
                        </div>
                        <div class="pg-fg">
                            <label class="pg-fl">Override Potongan Alpha</label>
                            <input type="text" inputmode="numeric" data-currency name="override_absence_deduction" id="pgAdjAbsence" class="pg-fi" placeholder="Kosongkan = otomatis" oninput="pgPreview()">
                        </div>
                    </div>
                    {{-- Live Preview THP --}}
                    <div class="pg-preview-box" id="pgPreviewBox">
                        <div class="pg-preview-label">Preview Gaji Bersih (THP)</div>
                        <div class="pg-preview-val" id="pgPreviewVal">Rp 0</div>
                        <div class="pg-preview-breakdown">
                            <div>
                                <div class="pg-preview-row">
                                    <span>Gaji Pokok</span>
                                    <span id="pgPrevBasic">Rp 0</span>
                                </div>
                                <div class="pg-preview-row">
                                    <span>Tunjangan Makan</span>
                                    <span id="pgPrevAllowance">Rp 0</span>
                                </div>
                                <div class="pg-preview-row">
                                    <span>Tunjangan Tetap</span>
                                    <span id="pgPrevFixed">Rp 0</span>
                                </div>
                                <div class="pg-preview-row">
                                    <span>Lembur</span>
                                    <span id="pgPrevOT">Rp 0</span>
                                </div>
                                <div class="pg-preview-row">
                                    <span>Insentif + Bonus</span>
                                    <span id="pgPrevBonus">Rp 0</span>
                                </div>
                            </div>
                            <div>
                                <div class="pg-preview-row minus">
                                    <span>Potongan Telat</span>
                                    <span id="pgPrevLatePen">-Rp 0</span>
                                </div>
                                <div class="pg-preview-row minus">
                                    <span>Potongan Alpha</span>
                                    <span id="pgPrevAbsDed">-Rp 0</span>
                                </div>
                                <div class="pg-preview-row minus">
                                    <span>Potongan Lain</span>
                                    <span id="pgPrevOtherDed">-Rp 0</span>
                                </div>
                                <div class="pg-preview-row total" style="color:#059669">
                                    <span>THP Bersih</span>
                                    <span id="pgPrevTotal" style="font-family:'JetBrains Mono',monospace">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pg-md-ft">
                    <button type="button" class="pg-abtn pg-abtn-s" onclick="document.getElementById('pgAdjustModal').classList.remove('show')">Batal</button>
                    <button type="submit" class="pg-abtn pg-abtn-p">Update Slip</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    // --- Payroll data cache for live preview ---
    var _pgCurrent = {};

    function pgFormatRp(n){
        return 'Rp '+Math.round(n).toLocaleString('id-ID');
    }
    function pgPreview(){
        var basic     = parseInt(parseCurrency(document.getElementById('pgAdjBasic').value))      || _pgCurrent.basic    || 0;
        var incentive = parseInt(parseCurrency(document.getElementById('pgAdjIncentive').value))  || 0;
        var perf      = parseInt(parseCurrency(document.getElementById('pgAdjPerformance').value))|| 0;
        var latePen   = document.getElementById('pgAdjLatePenalty').value !== '' ? parseInt(parseCurrency(document.getElementById('pgAdjLatePenalty').value)) : (_pgCurrent.latePen   || 0);
        var absDed    = document.getElementById('pgAdjAbsence').value    !== '' ? parseInt(parseCurrency(document.getElementById('pgAdjAbsence').value))    : (_pgCurrent.absDed    || 0);
        var allowance = (_pgCurrent.mealGross || 0) - latePen;
        if(allowance < 0) allowance = 0;
        var fixed     = _pgCurrent.fixed   || 0;
        var ot        = _pgCurrent.ot      || 0;
        var otherDed  = _pgCurrent.otherDed|| 0;
        var net = (basic + allowance + fixed + ot + incentive + perf) - (otherDed + absDed);
        if(net < 0) net = 0;
        document.getElementById('pgPreviewVal').textContent  = pgFormatRp(net);
        document.getElementById('pgPrevTotal').textContent   = pgFormatRp(net);
        document.getElementById('pgPrevBasic').textContent   = pgFormatRp(basic);
        document.getElementById('pgPrevAllowance').textContent = pgFormatRp(allowance);
        document.getElementById('pgPrevFixed').textContent   = pgFormatRp(fixed);
        document.getElementById('pgPrevOT').textContent      = pgFormatRp(ot);
        document.getElementById('pgPrevBonus').textContent   = pgFormatRp(incentive + perf);
        document.getElementById('pgPrevLatePen').textContent = '-'+pgFormatRp(latePen);
        document.getElementById('pgPrevAbsDed').textContent  = '-'+pgFormatRp(absDed);
        document.getElementById('pgPrevOtherDed').textContent= '-'+pgFormatRp(otherDed);
    }
    function pgToggle(id, row){
        var detail = document.getElementById('pgDetail' + id);
        var chev = document.getElementById('pgChev' + id);
        if(!detail) return;
        var isOpen = detail.classList.contains('show');
        // Close all
        document.querySelectorAll('.pg-detail.show').forEach(function(d){d.classList.remove('show')});
        document.querySelectorAll('.pg-chevron.open').forEach(function(c){c.classList.remove('open')});
        if(!isOpen){
            detail.classList.add('show');
            if(chev) chev.classList.add('open');
        }
    }
    function pgAdjustOpen(id, incentive, performance, overrideBasic, overrideLate, overrideAbsence, basic, mealGross, fixed, ot, otherDed, absDed, latePen){
        var form = document.getElementById('pgAdjustForm');
        form.action = '{{ url("sdm/penggajian") }}/' + id + '/adjust';
        document.getElementById('pgAdjIncentive').value = formatCurrency(incentive) || '';
        document.getElementById('pgAdjPerformance').value = formatCurrency(performance) || '';
        document.getElementById('pgAdjBasic').value = overrideBasic ? formatCurrency(overrideBasic) : '';
        document.getElementById('pgAdjLatePenalty').value = overrideLate ? formatCurrency(overrideLate) : '';
        document.getElementById('pgAdjAbsence').value = overrideAbsence ? formatCurrency(overrideAbsence) : '';
        // Store current payroll data for preview calculation
        _pgCurrent = {
            basic:    parseFloat(basic)    || 0,
            mealGross:parseFloat(mealGross)|| 0,
            fixed:    parseFloat(fixed)    || 0,
            ot:       parseFloat(ot)       || 0,
            otherDed: parseFloat(otherDed) || 0,
            absDed:   parseFloat(absDed)   || 0,
            latePen:  parseFloat(latePen)  || 0,
        };
        pgPreview();
        document.getElementById('pgAdjustModal').classList.add('show');
    }
    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape'){document.querySelectorAll('.pg-mo.show').forEach(function(m){m.classList.remove('show')});}
    });
    </script>
    @endpush
</x-hr-layout>
