<x-app-layout>
    <x-slot name="header">Transfer Approved (Antrian)</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Alur Persetujuan</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-green">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        Antrian Transfer Disetujui
                    </h1>
                    <p class="tr-subtitle">Proses antrian untuk membuat dokumen pemindahan fisik dan diteruskan ke Admin 4.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.transfer') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Riwayat Transfer
                    </a>
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

            {{-- MAIN CARD --}}
            <div class="tr-card">
                
                {{-- Filter Bar --}}
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('gudang.transfer.requests') }}" class="tr-filter-form" id="filterForm">
                        <div class="tr-search">
                            <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk, SKU, atau pemohon...">
                        </div>
                        <button type="submit" class="tr-btn tr-btn-primary" id="filterBtn">Filter Data</button>
                        
                        @if(request()->filled('search'))
                            <a href="{{ route('gudang.transfer.requests') }}" class="tr-btn tr-btn-danger-outline">Reset</a>
                        @endif
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Tanggal & ID</th>
                                <th>Pemohon</th>
                                <th>Detail Produk</th>
                                <th class="c">Qty</th>
                                <th>Rute Transfer</th>
                                <th>Status</th>
                                <th class="r" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                                <tr>
                                    <td>
                                        <div class="tr-date-main">{{ $req->created_at->format('d M Y') }}</div>
                                        <div class="tr-date-sub">{{ $req->created_at->format('H:i') }} WIB <span class="tr-dot-divider">•</span> ID #{{ $req->id }}</div>
                                    </td>
                                    <td>
                                        <div class="tr-user-name">{{ $req->user?->name ?? '-' }}</div>
                                        <div class="tr-user-role">{{ strtoupper((string) ($req->user?->role ?? '-')) }}</div>
                                    </td>
                                    <td>
                                        <div class="tr-prod-name">{{ $req->product?->name ?? '-' }}</div>
                                        <div class="tr-prod-sku">SKU: <span class="tr-font-mono">{{ $req->product?->sku ?? '-' }}</span></div>
                                    </td>
                                    <td class="c">
                                        <span class="tr-qty-badge">{{ $req->quantity }}</span>
                                    </td>
                                    <td>
                                        <div class="tr-route-box">
                                            <span class="tr-route-wh">{{ $req->fromWarehouse?->name ?? 'Gudang Utama' }}</span>
                                            <svg class="tr-route-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                            <span class="tr-route-wh">{{ $req->toWarehouse?->name ?? 'Gudang Cabang' }}</span>
                                        </div>
                                        @if($req->notes)
                                            <div class="tr-notes-text">"{{ $req->notes }}"</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="tr-badge tr-badge-success">Approved</span>
                                        <div class="tr-status-sub">Siap diproses</div>
                                    </td>
                                    <td class="r">
                                        <form method="POST" action="{{ route('gudang.transfer.process_request', $req) }}" onsubmit="return confirm('Buat dokumen transfer fisik dari permintaan ini?');" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="tr-btn tr-btn-primary js-process-btn" style="padding: 0.4rem 0.8rem;">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                                Proses
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                            </div>
                                            <h6>Tidak ada antrian</h6>
                                            <p>Saat ini tidak ada permintaan transfer baru yang berstatus disetujui (Approved).</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                    <div class="tr-pagination">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

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
            --tr-primary: #3b82f6;
            --tr-primary-hover: #2563eb;
            --tr-primary-light: #eff6ff;
            --tr-success-bg: #ecfdf5;
            --tr-success-text: #059669;
            --tr-success-border: #a7f3d0;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #dc2626;
            --tr-danger-border: #fecaca;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1280px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-success-text); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-green { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0.35rem 0 0 0; }
        
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: center; gap: 10px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 500; border: 1px solid transparent; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: var(--tr-success-border); }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: var(--tr-danger-border); }

        /* ── CARD & FILTER ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-filter-bar { padding: 1rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-filter-form { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .tr-search { display: flex; align-items: center; gap: 8px; background: var(--tr-bg); border-radius: 6px; padding: 0.5rem 1rem; border: 1px solid var(--tr-border); flex: 1; min-width: 280px; transition: border-color 0.2s; }
        .tr-search:focus-within { border-color: var(--tr-primary); background: #ffffff; }
        .tr-search-icon { color: var(--tr-text-light); }
        .tr-search input { border: none; background: transparent; font-size: 0.85rem; font-family: inherit; color: var(--tr-text-main); outline: none; width: 100%; }
        .tr-search input::placeholder { color: var(--tr-text-light); }

        /* ── BUTTONS ── */
        .tr-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600;
            cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent;
        }
        .tr-btn-primary { background: var(--tr-text-main); color: #ffffff; border-color: var(--tr-text-main); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tr-btn-primary:hover { background: #000000; transform: translateY(-1px); }
        .tr-btn-primary:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); }
        .tr-btn-danger-outline { border-color: var(--tr-danger-border); color: var(--tr-danger-text); background: transparent; }
        .tr-btn-danger-outline:hover { background: var(--tr-danger-bg); }

        /* Spinner for Buttons */
        .tr-spinner { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: tr-spin 0.8s linear infinite; }
        @keyframes tr-spin { to { transform: rotate(360deg); } }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 960px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.c, .tr-table td.c { text-align: center; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* ── CELL FORMATTING ── */
        .tr-date-main { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; white-space: nowrap; }
        .tr-date-sub { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }
        .tr-dot-divider { color: var(--tr-border); margin: 0 2px; }
        
        .tr-user-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-user-role { font-size: 0.7rem; color: var(--tr-text-muted); font-weight: 600; letter-spacing: 0.05em; margin-top: 2px; }
        
        .tr-prod-name { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; line-height: 1.3; }
        .tr-prod-sku { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }
        .tr-font-mono { font-family: monospace; background: var(--tr-border-light); padding: 1px 4px; border-radius: 4px; color: var(--tr-text-main); }
        
        .tr-qty-badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.25rem 0.75rem; border-radius: 999px; background: var(--tr-primary-light); color: var(--tr-primary); font-weight: 800; font-size: 0.85rem; }
        
        .tr-route-box { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .tr-route-wh { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-route-arrow { color: var(--tr-text-light); }
        .tr-notes-text { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 6px; font-style: italic; background: var(--tr-bg); padding: 4px 8px; border-radius: 4px; border: 1px solid var(--tr-border-light); display: inline-block; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .tr-badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-badge-success { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-status-sub { font-size: 0.7rem; color: var(--tr-text-muted); font-weight: 500; margin-top: 4px; }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-success-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-success-text); }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* ── PAGINATION ── */
        .tr-pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--tr-border-light); background: #ffffff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-header-actions { width: 100%; }
            .tr-btn { width: 100%; justify-content: center; }
            .tr-filter-form { flex-direction: column; align-items: stretch; }
            .tr-search { width: 100%; min-width: auto; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animasi Loading saat Filter
            const filterForm = document.getElementById('filterForm');
            const filterBtn = document.getElementById('filterBtn');
            if (filterForm && filterBtn) {
                filterForm.addEventListener('submit', function () {
                    filterBtn.disabled = true;
                    filterBtn.innerHTML = '<span class="tr-spinner"></span> Memuat...';
                });
            }

            // Animasi Loading saat pencet tombol Proses Transfer Truk
            document.querySelectorAll('.js-process-btn').forEach(btn => {
                btn.closest('form')?.addEventListener('submit', function () {
                    // Beri jeda sedikit agar konfirmasi bawaan browser selesai dulu
                    setTimeout(() => {
                        btn.disabled = true;
                        btn.innerHTML = '<span class="tr-spinner"></span> Memproses...';
                    }, 10);
                });
            });
        });
    </script>
    @endpush
</x-app-layout>