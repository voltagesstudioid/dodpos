<x-app-layout>
    <x-slot name="header">Gaji Saya</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Layanan Mandiri SDM</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-emerald">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                        Informasi Penggajian
                    </h1>
                    <p class="tr-subtitle">Lihat rincian slip gaji, tunjangan, potongan, dan riwayat gaji bulan-bulan sebelumnya.</p>
                </div>
            </div>

            {{-- ALERTS --}}
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
            <div class="tr-card">
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('sdm.penggajian.self_index') }}" class="tr-filter-form">
                        <div class="tr-form-group">
                            <label class="tr-label">Pilih Bulan & Tahun</label>
                            <input type="month" name="month" value="{{ $month }}" class="tr-input tr-input-date">
                        </div>
                        <button type="submit" class="tr-btn tr-btn-dark">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            Tampilkan
                        </button>
                    </form>
                </div>
            </div>

            <div class="tr-grid-layout">
                
                {{-- ─── KOLOM KIRI: SLIP GAJI BULAN INI ─── --}}
                <div class="tr-col-main">
                    <div class="tr-card tr-h-full">
                        <div class="tr-card-header tr-header-emerald">
                            <div>
                                <h2 class="tr-section-title">Slip Gaji</h2>
                                <p class="tr-card-subtitle">Periode: <strong>{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</strong></p>
                            </div>
                        </div>
                        
                        <div class="tr-card-body">
                            @php $pr = $payrolls->first(); @endphp
                            
                            @if($pr)
                                <div class="tr-receipt-box">
                                    <div class="tr-receipt-header">
                                        <div class="tr-receipt-title">Ringkasan Gaji</div>
                                        <div class="tr-receipt-badge">LUNAS</div>
                                    </div>
                                    
                                    <div class="tr-receipt-body">
                                        <div class="tr-receipt-row">
                                            <span class="tr-receipt-label">Total Kehadiran</span>
                                            <span class="tr-receipt-value"><strong>{{ $pr->total_attendance }}</strong> Hari</span>
                                        </div>
                                        <div class="tr-receipt-row">
                                            <span class="tr-receipt-label">Gaji Pokok</span>
                                            <span class="tr-receipt-value">Rp {{ number_format($pr->total_basic_salary, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="tr-receipt-row">
                                            <span class="tr-receipt-label">Uang Makan / Tunjangan</span>
                                            <span class="tr-receipt-value">Rp {{ number_format($pr->total_allowance, 0, ',', '.') }}</span>
                                        </div>
                                        
                                        @php $totalPotongan = ($pr->total_deductions ?? 0) + ($pr->absence_deduction ?? 0); @endphp
                                        <div class="tr-receipt-row tr-row-danger">
                                            <span class="tr-receipt-label">Total Potongan (Absen dll)</span>
                                            <span class="tr-receipt-value">− Rp {{ number_format($totalPotongan, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="tr-receipt-footer">
                                        <span class="tr-total-label">Take Home Pay (THP)</span>
                                        <span class="tr-total-value">Rp {{ number_format($pr->net_salary, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="tr-action-box">
                                    <p>Gunakan tombol di bawah ini untuk mencetak atau mengunduh slip gaji dalam format PDF.</p>
                                    <a href="{{ route('sdm.penggajian.self_print', $pr) }}" target="_blank" class="tr-btn tr-btn-outline tr-btn-full">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                        Cetak Dokumen Slip
                                    </a>
                                </div>
                            @else
                                <div class="tr-empty-state">
                                    <div class="tr-empty-icon">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    </div>
                                    <h6>Slip belum tersedia</h6>
                                    <p>Slip gaji untuk periode ini belum diterbitkan. Hubungi supervisor jika ada kendala.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ─── KOLOM KANAN: RIWAYAT SINGKAT ─── --}}
                <div class="tr-col-side">
                    <div class="tr-card tr-h-full">
                        <div class="tr-card-header">
                            <h2 class="tr-section-title">Riwayat Singkat</h2>
                        </div>
                        
                        @if($history->count() > 0)
                            <div class="table-responsive">
                                <table class="tr-table-simple">
                                    <thead>
                                        <tr>
                                            <th>Periode</th>
                                            <th class="r">Total THP</th>
                                            <th class="c" style="width: 60px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($history as $h)
                                            <tr>
                                                <td class="tr-font-bold">{{ \Carbon\Carbon::createFromDate($h->period_year, $h->period_month, 1)->translatedFormat('M Y') }}</td>
                                                <td class="r tr-text-success tr-font-bold">Rp {{ number_format($h->net_salary, 0, ',', '.') }}</td>
                                                <td class="c">
                                                    <a href="{{ route('sdm.penggajian.self_print', $h) }}" target="_blank" class="tr-action-btn" title="Cetak Slip">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="tr-empty-state" style="padding: 2rem 1.5rem;">
                                <h6>Tidak ada riwayat</h6>
                                <p style="font-size: 0.8rem;">Belum ada riwayat gaji bulan-bulan sebelumnya.</p>
                            </div>
                        @endif
                    </div>
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
            
            --tr-emerald: #10b981; /* Emerald Green */
            --tr-emerald-hover: #059669;
            --tr-emerald-light: #dcfce7;
            
            --tr-success: #10b981;
            --tr-success-text: #166534;
            --tr-danger: #ef4444;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #991b1b;
            
            --tr-radius-lg: 16px;
            --tr-radius-md: 10px;
            --tr-shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --tr-shadow-md: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 2rem 1.5rem; max-width: 1000px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { margin-bottom: 2rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-emerald); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.6rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-emerald { background: var(--tr-emerald-light); color: var(--tr-emerald-hover); }
        .tr-subtitle { font-size: 0.9rem; color: var(--tr-text-muted); margin: 0; line-height: 1.4; }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: flex-start; gap: 12px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600; line-height: 1.4; border: 1px solid transparent; }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: #fecaca; }
        .tr-alert-success { background: var(--tr-emerald-light); color: var(--tr-success-text); border-color: #bbf7d0; }
        .tr-alert-icon { flex-shrink: 0; margin-top: 1px; }

        /* ── CARDS & LAYOUT ── */
        .tr-grid-layout { display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; }
        .tr-col-main { display: flex; flex-direction: column; gap: 1.5rem; }
        .tr-col-side { display: flex; flex-direction: column; gap: 1.5rem; }
        .tr-h-full { height: 100%; }

        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-md); overflow: hidden; margin-bottom: 1.5rem; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-header-emerald { border-left: 4px solid var(--tr-emerald); }
        .tr-card-body { padding: 1.5rem; }
        
        .tr-section-title { font-size: 1.1rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.25rem 0; }
        .tr-card-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0; }

        /* ── FILTER BAR ── */
        .tr-filter-bar { padding: 1rem 1.5rem; }
        .tr-filter-form { display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 200px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; }
        
        .tr-input { width: 100%; padding: 0.6rem 0.85rem; height: 42px; border: 1px solid var(--tr-border); border-radius: 6px; font-family: inherit; font-size: 0.85rem; color: var(--tr-text-main); background: #f8fafc; outline: none; transition: border-color 0.2s; }
        .tr-input:focus { border-color: var(--tr-emerald); background: #ffffff; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
        .tr-input-date { font-family: monospace; font-size: 0.95rem; font-weight: 600; }

        /* ── DIGITAL RECEIPT (SLIP GAJI) ── */
        .tr-receipt-box { background: #f8fafc; border: 1px solid var(--tr-border); border-radius: 12px; margin-bottom: 1.5rem; overflow: hidden; }
        .tr-receipt-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; border-bottom: 1px dashed var(--tr-border); }
        .tr-receipt-title { font-size: 0.85rem; font-weight: 800; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-receipt-badge { font-size: 0.7rem; font-weight: 900; letter-spacing: 0.1em; color: var(--tr-emerald); background: var(--tr-emerald-light); padding: 0.2rem 0.5rem; border-radius: 4px; }
        
        .tr-receipt-body { padding: 1.25rem; display: flex; flex-direction: column; gap: 0.85rem; }
        .tr-receipt-row { display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; }
        .tr-receipt-label { color: var(--tr-text-muted); font-weight: 500; }
        .tr-receipt-value { color: var(--tr-text-main); font-weight: 600; font-family: monospace; font-size: 0.95rem; }
        .tr-receipt-value strong { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.1rem; }
        
        .tr-row-danger .tr-receipt-value { color: var(--tr-danger); font-weight: 700; }
        
        .tr-receipt-footer { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem; background: var(--tr-surface); border-top: 2px dashed var(--tr-border); }
        .tr-total-label { font-size: 1rem; font-weight: 900; color: var(--tr-text-main); }
        .tr-total-value { font-size: 1.5rem; font-weight: 900; font-family: monospace; letter-spacing: -0.02em; color: var(--tr-emerald); }

        /* Action Box */
        .tr-action-box { text-align: center; }
        .tr-action-box p { font-size: 0.8rem; color: var(--tr-text-muted); margin: 0 0 1rem 0; line-height: 1.4; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.85rem; font-family: inherit; font-weight: 700; cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 42px; }
        .tr-btn-dark { background: var(--tr-text-main); color: #ffffff; border-color: var(--tr-text-main); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tr-btn-dark:hover { background: #000000; transform: translateY(-1px); }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-main); background: var(--tr-surface); box-shadow: var(--tr-shadow-sm); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); background: #f8fafc; }
        .tr-btn-full { width: 100%; }

        .tr-action-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; border: 1px solid var(--tr-border); background: var(--tr-bg); color: var(--tr-text-muted); transition: all 0.2s; }
        .tr-action-btn:hover { background: var(--tr-surface); color: var(--tr-text-main); border-color: var(--tr-text-light); }

        /* ── MINI TABLE (RIGHT COLUMN) ── */
        .table-responsive { width: 100%; overflow-x: auto; }
        .tr-table-simple { width: 100%; border-collapse: collapse; }
        .tr-table-simple th { padding: 0.75rem 1rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); border-bottom: 1px solid var(--tr-border-light); background: #f8fafc; text-align: left; }
        .tr-table-simple td { padding: 1rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table-simple tbody tr:last-child td { border-bottom: none; }
        .tr-table-simple tbody tr:hover { background: #f8fafc; }
        .tr-table-simple th.r, .tr-table-simple td.r { text-align: right; }
        .tr-table-simple th.c, .tr-table-simple td.c { text-align: center; }

        .tr-font-bold { font-weight: 700; color: var(--tr-text-main); }
        .tr-text-success { color: var(--tr-emerald); font-family: monospace; font-size: 0.9rem; }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 3rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-text-light); }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* ── RESPONSIVE ── */
        @media (max-width: 992px) {
            .tr-grid-layout { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .tr-filter-form { flex-direction: column; align-items: stretch; }
            .tr-form-group { width: 100%; }
        }
    </style>
    @endpush
</x-app-layout>