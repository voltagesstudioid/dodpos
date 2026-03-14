<x-app-layout>
    <x-slot name="header">Potongan Saya</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Layanan Mandiri SDM</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-slate">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        </div>
                        Potongan & Bonus Saya
                    </h1>
                    <p class="tr-subtitle">Rincian kasbon, penalti, maupun bonus yang akan mempengaruhi slip gaji Anda di bulan terkait.</p>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success'))
                <div class="tr-alert tr-alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error')) 
                <div class="tr-alert tr-alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    {{ session('error') }}
                </div> 
            @endif

            {{-- ─── FILTER PERIODE ─── --}}
            <div class="tr-card" style="margin-bottom: 1.5rem;">
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('sdm.potongan.self_index') }}" class="tr-filter-form">
                        <div class="tr-form-group">
                            <label class="tr-label">Pilih Periode Bulan</label>
                            <input type="month" name="month" value="{{ $month }}" class="tr-input tr-input-date">
                        </div>
                        <button type="submit" class="tr-btn tr-btn-dark">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            Tampilkan Data
                        </button>
                    </form>
                </div>
            </div>

            {{-- ─── TABEL RINCIAN ─── --}}
            <div class="tr-card">
                <div class="tr-card-header">
                    <div>
                        <h2 class="tr-section-title">Rincian Finansial</h2>
                        <p class="tr-card-subtitle">Periode: <strong>{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</strong></p>
                    </div>
                </div>
                
                <div class="tr-card-body">
                    @if($deductions->count() > 0 || $bonuses->count() > 0)
                        <div class="table-responsive">
                            <table class="tr-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe Jurnal</th>
                                        <th>Keterangan / Alasan</th>
                                        <th class="r">Nominal (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $totalDeduction = 0; 
                                        $totalBonus = 0;
                                    @endphp
                                    
                                    {{-- LOOP BONUSES --}}
                                    @foreach($bonuses as $b)
                                        @php $totalBonus += (float) $b->amount; @endphp
                                        <tr class="tr-row-success">
                                            <td>
                                                <div class="tr-date-main">{{ $b->date->format('d M Y') }}</div>
                                            </td>
                                            <td>
                                                <span class="tr-badge-lg tr-badge-success">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                    BONUS
                                                </span>
                                            </td>
                                            <td>
                                                <div class="tr-desc-text">{{ $b->description }}</div>
                                            </td>
                                            <td class="r">
                                                <span class="tr-amount-success">+{{ number_format($b->amount, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    {{-- LOOP DEDUCTIONS --}}
                                    @foreach($deductions as $d)
                                        @php $totalDeduction += (float) $d->amount; @endphp
                                        <tr class="tr-row-danger">
                                            <td>
                                                <div class="tr-date-main">{{ $d->date->format('d M Y') }}</div>
                                            </td>
                                            <td>
                                                <span class="tr-badge-lg tr-badge-danger">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                    POTONGAN
                                                </span>
                                            </td>
                                            <td>
                                                <div class="tr-desc-text">{{ $d->description }}</div>
                                            </td>
                                            <td class="r">
                                                <span class="tr-amount-danger">−{{ number_format($d->amount, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="tr-footer-row">
                                        <td colspan="3" class="r tr-footer-label">Total Penambahan (Bonus)</td>
                                        <td class="r tr-amount-success tr-footer-val">+{{ number_format($totalBonus, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="tr-footer-row">
                                        <td colspan="3" class="r tr-footer-label">Total Pengurangan (Potongan)</td>
                                        <td class="r tr-amount-danger tr-footer-val">−{{ number_format($totalDeduction, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="tr-empty-state">
                            <div class="tr-empty-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="6" y1="8" x2="6.01" y2="8"></line><line x1="10" y1="8" x2="10.01" y2="8"></line></svg>
                            </div>
                            <h6>Tidak Ada Data Terkait</h6>
                            <p>Bagus! Anda tidak memiliki catatan potongan (kasbon) maupun bonus di periode ini.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            
            --tr-success: #10b981;
            --tr-success-bg: #dcfce7;
            --tr-success-light: #f0fdf4;
            --tr-success-text: #166534;
            
            --tr-danger: #ef4444;
            --tr-danger-bg: #fee2e2;
            --tr-danger-light: #fef2f2;
            --tr-danger-text: #b91c1c;

            --tr-slate: #64748b;
            --tr-slate-bg: #f1f5f9;
            
            --tr-radius-lg: 16px;
            --tr-radius-md: 10px;
            --tr-shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --tr-shadow-md: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 2rem 1.5rem; max-width: 900px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { margin-bottom: 2rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-slate); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.6rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-slate { background: var(--tr-slate-bg); color: var(--tr-slate); }
        .tr-subtitle { font-size: 0.9rem; color: var(--tr-text-muted); margin: 0; line-height: 1.4; }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: flex-start; gap: 12px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600; line-height: 1.4; border: 1px solid transparent; }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: #fecaca; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: #bbf7d0; }
        .tr-alert-icon { flex-shrink: 0; margin-top: 1px; }

        /* ── CARDS ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-card-body { padding: 0; }
        
        .tr-section-title { font-size: 1.1rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.25rem 0; }
        .tr-card-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0; }

        /* ── FILTER BAR ── */
        .tr-filter-bar { padding: 1rem 1.5rem; }
        .tr-filter-form { display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 200px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; }
        
        .tr-input { width: 100%; padding: 0.6rem 0.85rem; height: 42px; border: 1px solid var(--tr-border); border-radius: 6px; font-family: inherit; font-size: 0.85rem; color: var(--tr-text-main); background: #f8fafc; outline: none; transition: border-color 0.2s; }
        .tr-input:focus { border-color: var(--tr-slate); background: #ffffff; box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1); }
        .tr-input-date { font-family: monospace; font-size: 0.95rem; font-weight: 600; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.85rem; font-family: inherit; font-weight: 700; cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 42px; }
        .tr-btn-dark { background: var(--tr-text-main); color: #ffffff; border-color: var(--tr-text-main); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tr-btn-dark:hover { background: #000000; transform: translateY(-1px); }

        /* ── TABLE ── */
        .table-responsive { width: 100%; overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; min-width: 680px; }
        .tr-table th { padding: 0.85rem 1.25rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); border-bottom: 1px solid var(--tr-border-light); background: #f8fafc; text-align: left; }
        .tr-table td { padding: 1.15rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        
        .tr-table tbody tr.tr-row-success:hover td { background: var(--tr-success-light); }
        .tr-table tbody tr.tr-row-danger:hover td { background: var(--tr-danger-light); }
        
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* FOOTER ROW SUMMARY */
        .tr-footer-row td { background: #f8fafc; border-top: 2px dashed var(--tr-border); border-bottom: none; padding: 1rem 1.25rem; }
        .tr-footer-label { font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem; }
        .tr-footer-val { font-size: 1.1rem; }

        /* CELLS FORMATTING */
        .tr-date-main { font-weight: 800; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-desc-text { font-weight: 500; color: var(--tr-text-main); line-height: 1.4; max-width: 320px; }
        
        .tr-amount-success { color: var(--tr-success); font-weight: 900; font-family: monospace; font-size: 1rem; }
        .tr-amount-danger { color: var(--tr-danger); font-weight: 900; font-family: monospace; font-size: 1rem; }

        .tr-badge-lg { display: inline-flex; align-items: center; padding: 0.35rem 0.75rem; border-radius: 999px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-badge-success { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-badge-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 5rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-text-light); }
        .tr-empty-state h6 { font-size: 1.1rem; font-weight: 800; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* ── RESPONSIVE ── */
        @media (max-width: 640px) {
            .tr-filter-form { flex-direction: column; align-items: stretch; }
            .tr-form-group { width: 100%; }
        }
    </style>
    @endpush
</x-app-layout>