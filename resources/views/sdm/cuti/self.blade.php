<x-app-layout>
    <x-slot name="header">Cuti Saya</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Layanan Mandiri SDM</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-teal">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        Pengajuan Cuti & Izin
                    </h1>
                    <p class="tr-subtitle">Ajukan cuti/izin/sakit dan pantau status persetujuan dari atasan Anda secara *real-time*.</p>
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
            @if($errors->any())
                <div class="tr-alert tr-alert-danger tr-alert-block">
                    <div class="tr-alert-head">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <strong>Terdapat kesalahan input:</strong>
                    </div>
                    <ul class="tr-alert-list">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM PENGAJUAN (NEW) --}}
            <div class="tr-card">
                <div class="tr-card-header tr-header-teal">
                    <h2 class="tr-section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        Form Pengajuan Baru
                    </h2>
                    <p class="tr-card-subtitle">Pengajuan akan langsung diteruskan ke Supervisor terkait.</p>
                </div>
                
                <form method="POST" action="{{ route('sdm.cuti.self_store') }}" class="tr-form-pad js-submit-form">
                    @csrf
                    <div class="tr-form-grid">
                        
                        <div class="tr-form-group">
                            <label class="tr-label">Jenis Izin <span class="tr-req">*</span></label>
                            <div class="tr-select-wrapper">
                                <select name="type" class="tr-select" required>
                                    <option value="cuti" {{ old('type') === 'cuti' ? 'selected' : '' }}>🌴 Cuti Tahunan</option>
                                    <option value="izin" {{ old('type') === 'izin' ? 'selected' : '' }}>ธุ Izin Keperluan</option>
                                    <option value="sakit" {{ old('type') === 'sakit' ? 'selected' : '' }}>🤒 Izin Sakit</option>
                                </select>
                            </div>
                        </div>

                        <div class="tr-form-group">
                            <label class="tr-label">Tanggal Mulai <span class="tr-req">*</span></label>
                            <input type="date" name="start_date" class="tr-input" value="{{ old('start_date', now()->toDateString()) }}" required>
                        </div>

                        <div class="tr-form-group">
                            <label class="tr-label">Tanggal Selesai <span class="tr-req">*</span></label>
                            <input type="date" name="end_date" class="tr-input" value="{{ old('end_date', now()->toDateString()) }}" required>
                        </div>

                        <div class="tr-form-group tr-col-stretch">
                            <label class="tr-label">Catatan / Alasan <span class="tr-text-light font-normal">(Opsional)</span></label>
                            <input type="text" name="notes" class="tr-input" value="{{ old('notes') }}" placeholder="Tuliskan keterangan singkat...">
                        </div>

                        <div class="tr-form-group tr-btn-align">
                            <button type="submit" class="tr-btn tr-btn-teal tr-btn-full js-btn-submit">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                Kirim Pengajuan
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            {{-- RIWAYAT PENGAJUAN --}}
            <div class="tr-card">
                <div class="tr-card-header tr-flex-header">
                    <div>
                        <h2 class="tr-section-title">Riwayat Pengajuan Saya</h2>
                        <p class="tr-card-subtitle">Menampilkan riwayat pada periode terpilih.</p>
                    </div>
                    
                    <form method="GET" action="{{ route('sdm.cuti.self_index') }}" class="tr-filter-mini">
                        <input type="month" name="month" value="{{ $month }}" class="tr-input tr-input-sm">
                        <button type="submit" class="tr-btn tr-btn-outline tr-btn-sm">Tampilkan</button>
                    </form>
                </div>

                @if($requests->count() > 0)
                    <div class="table-responsive">
                        <table class="tr-table">
                            <thead>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Jenis</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Pihak Penyetuju</th>
                                    <th class="c" style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $r)
                                    @php
                                        $statusClass = match($r->status) {
                                            'approved' => 'tr-badge-success',
                                            'rejected' => 'tr-badge-danger',
                                            default => 'tr-badge-warning',
                                        };
                                        $statusLabel = match($r->status) {
                                            'approved' => 'DISETUJUI',
                                            'rejected' => 'DITOLAK',
                                            default => 'MENUNGGU',
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="tr-date-main">{{ $r->start_date->format('d M') }} – {{ $r->end_date->format('d M Y') }}</div>
                                            <div class="tr-date-sub">Total: {{ $r->start_date->diffInDays($r->end_date) + 1 }} Hari</div>
                                        </td>
                                        <td>
                                            <div class="tr-font-bold">{{ strtoupper($r->type) }}</div>
                                        </td>
                                        <td>
                                            <div class="tr-notes-text" title="{{ $r->notes ?? '-' }}">
                                                {{ $r->notes ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="tr-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                        </td>
                                        <td>
                                            @if($r->approver)
                                                <div class="tr-user-name">{{ $r->approver->name }}</div>
                                                <div class="tr-user-role">SUPERVISOR</div>
                                            @else
                                                <span class="tr-text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="c">
                                            @if($r->status !== 'approved')
                                                <form method="POST" action="{{ route('sdm.cuti.self_destroy', $r) }}" onsubmit="return confirm('Yakin ingin membatalkan pengajuan cuti/izin ini?');" style="margin:0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="tr-action-btn delete" title="Batalkan Pengajuan">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                    </button>
                                                </form>
                                            @else
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="tr-icon-locked" title="Sudah Disetujui"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="tr-empty-state">
                        <div class="tr-empty-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                        <h6>Tidak Ada Riwayat</h6>
                        <p>Anda belum membuat pengajuan cuti atau izin pada periode <strong>{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</strong>.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Prevent double submission on form
            const forms = document.querySelectorAll('.js-submit-form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const btn = form.querySelector('.js-btn-submit');
                    if(btn && form.checkValidity()) {
                        setTimeout(() => {
                            btn.disabled = true;
                            btn.innerHTML = '<span class="tr-spinner"></span> Mengirim...';
                        }, 10);
                    }
                });
            });
        });
    </script>
    @endpush

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            
            --tr-teal: #0d9488; /* Primary SDM Color */
            --tr-teal-hover: #0f766e;
            --tr-teal-light: #ccfbf1;
            
            --tr-success: #10b981;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            
            --tr-warning: #f59e0b;
            --tr-warning-bg: #fffbeb;
            --tr-warning-text: #b45309;
            
            --tr-danger: #ef4444;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #991b1b;
            
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --tr-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 2rem 1.5rem; max-width: 1100px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { margin-bottom: 2rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-teal); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.6rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-teal { background: var(--tr-teal-light); color: var(--tr-teal); }
        .tr-subtitle { font-size: 0.9rem; color: var(--tr-text-muted); margin: 0; line-height: 1.4; }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: flex-start; gap: 12px; padding: 1.25rem 1.5rem; border-radius: var(--tr-radius-md); margin-bottom: 1.5rem; font-size: 0.9rem; border: 1px solid transparent; line-height: 1.5; }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: #fecaca; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: #bbf7d0; }
        
        .tr-alert-block { flex-direction: column; gap: 8px; }
        .tr-alert-head { display: flex; align-items: center; gap: 8px; font-weight: 700; }
        .tr-alert-icon { flex-shrink: 0; margin-top: 1px; }
        .tr-alert ul { margin: 0; padding-left: 2rem; }

        /* ── CARDS ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; margin-bottom: 1.5rem; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-header-teal { border-left: 4px solid var(--tr-teal); }
        
        .tr-section-title { font-size: 1.05rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.2rem 0; display: flex; align-items: center; gap: 8px; }
        .tr-section-title svg { color: var(--tr-teal); }
        .tr-card-subtitle { font-size: 0.8rem; color: var(--tr-text-muted); margin: 0; }
        
        .tr-flex-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }

        /* ── FORM (INPUT GRID) ── */
        .tr-form-pad { padding: 1.5rem; }
        .tr-form-grid { display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; }
        
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 160px; }
        .tr-col-stretch { flex: 2; min-width: 250px; }
        .tr-btn-align { flex: 0 0 auto; width: 160px; }
        
        .tr-label { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-req { color: var(--tr-danger); }
        .font-normal { font-weight: 500; text-transform: none; letter-spacing: normal; }

        .tr-input, .tr-select {
            width: 100%; padding: 0.6rem 0.85rem; height: 42px;
            border: 1px solid var(--tr-border); border-radius: 6px;
            font-family: inherit; font-size: 0.85rem; color: var(--tr-text-main);
            background: #f8fafc; outline: none; transition: border-color 0.2s;
        }
        .tr-input:focus, .tr-select:focus { border-color: var(--tr-teal); background: #ffffff; box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1); }
        
        .tr-select-wrapper { position: relative; }
        .tr-select { appearance: none; padding-right: 2rem; cursor: pointer; font-weight: 600; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }

        /* Filter Mini */
        .tr-filter-mini { display: flex; gap: 0.5rem; align-items: center; }
        .tr-input-sm { height: 34px; padding: 0.35rem 0.6rem; min-width: 150px; }
        
        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 42px; }
        .tr-btn-sm { height: 34px; padding: 0.35rem 0.85rem; font-size: 0.8rem; }
        .tr-btn-full { width: 100%; }
        
        .tr-btn-teal { background: var(--tr-teal); color: #ffffff; box-shadow: 0 2px 4px rgba(13, 148, 136, 0.2); }
        .tr-btn-teal:hover { background: var(--tr-teal-hover); transform: translateY(-1px); }
        .tr-btn-teal:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); background: #f8fafc; }

        /* Spinner */
        .tr-spinner { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: tr-spin 0.8s linear infinite; }
        @keyframes tr-spin { to { transform: rotate(360deg); } }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 900px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.c, .tr-table td.c { text-align: center; }

        /* Formatting */
        .tr-date-main { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; white-space: nowrap; }
        .tr-date-sub { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }
        .tr-font-bold { font-weight: 800; }
        .tr-notes-text { font-size: 0.8rem; color: var(--tr-text-muted); max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-style: italic; }
        .tr-user-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-user-role { font-size: 0.7rem; color: var(--tr-text-light); letter-spacing: 0.05em; margin-top: 2px; }

        /* Badges */
        .tr-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-badge-success { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-badge-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); }
        .tr-badge-warning { background: var(--tr-warning-bg); color: var(--tr-warning-text); }

        /* Actions */
        .tr-action-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; border: 1px solid transparent; background: var(--tr-bg); color: var(--tr-text-muted); transition: all 0.2s; cursor: pointer; }
        .tr-action-btn.delete:hover { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: #fecaca; }
        .tr-icon-locked { color: var(--tr-text-light); }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-text-light); }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* ── RESPONSIVE ── */
        @media (max-width: 992px) {
            .tr-btn-align { width: 100%; }
        }
        @media (max-width: 640px) {
            .tr-form-grid { flex-direction: column; align-items: stretch; gap: 1rem; }
            .tr-form-group { width: 100%; }
            .tr-flex-header { flex-direction: column; align-items: flex-start; }
            .tr-filter-mini { width: 100%; }
            .tr-filter-mini .tr-input { flex: 1; }
        }
    </style>
    @endpush
</x-app-layout>