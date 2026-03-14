<x-app-layout>
    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Gudang Cabang</div>
                    <h1 class="tr-title">Penerimaan Transfer</h1>
                    <p class="tr-subtitle">Cross-check & terima barang dari Gudang Utama</p>
                </div>
                <button type="button" class="tr-btn-refresh" onclick="location.reload()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                    Refresh
                </button>
            </div>

            {{-- MAIN CARD --}}
            <div class="tr-card">
                <div class="tr-card-header">
                    <div class="tr-card-title">
                        <div class="tr-icon-box blue">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </div>
                        Daftar Pengiriman
                    </div>
                    <form action="{{ route('gudang.terima_transfer.index') }}" method="GET" class="tr-search-form">
                        <div class="tr-search">
                            <svg class="tr-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <input type="text" name="search" placeholder="Cari referensi..."
                                   value="{{ request('search') }}">
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>No. Referensi</th>
                                <th>Tanggal Dikirim</th>
                                <th>Status</th>
                                <th>Total Qty</th>
                                <th>Jenis Barang</th>
                                <th class="r">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transfers as $tr)
                                <tr>
                                    <td>
                                        <span class="tr-ref">{{ $tr->reference_number }}</span>
                                    </td>
                                    <td>
                                        <div class="tr-date">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                            {{ $tr->created_at ? \Carbon\Carbon::parse($tr->created_at)->format('d M Y · H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($tr->status === 'pending')
                                            <span class="tr-badge tr-badge-pending">
                                                <span class="tr-badge-dot"></span>
                                                Menunggu Cross-Check
                                            </span>
                                        @elseif($tr->status === 'partial')
                                            <span class="tr-badge tr-badge-partial">
                                                <span class="tr-badge-dot"></span>
                                                Parsial / Selisih
                                            </span>
                                        @else
                                            <span class="tr-badge tr-badge-done">
                                                <span class="tr-badge-dot"></span>
                                                Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="tr-qty">{{ number_format($tr->total_qty) }}</span>
                                    </td>
                                    <td>
                                        <span class="tr-items-pill">{{ $tr->total_items }} SKU</span>
                                        @if($tr->products_preview->isNotEmpty())
                                            <div class="tr-product-preview"
                                                 title="{{ $tr->products_preview->join(', ') }}">
                                                {{ $tr->products_preview->join(', ') }}{{ $tr->total_products > 3 ? '...' : '' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="r">
                                        <a href="{{ route('gudang.terima_transfer.show', $tr->reference_number) }}"
                                           class="tr-btn {{ $tr->status === 'pending' ? 'tr-btn-primary' : 'tr-btn-outline' }}">
                                            @if($tr->status === 'pending')
                                                Review
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"/></svg>
                                            @else
                                                Detail
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="tr-empty">
                                            <div class="tr-empty-icon">
                                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                            </div>
                                            <h6>Belum ada pengiriman baru</h6>
                                            <p>Semua transferan dari Gudang Utama sudah diselesaikan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="tr-pagination">
                    {{ $transfers->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            --tr-primary: #3b82f6;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            --tr-warning-bg: #fef3c7;
            --tr-warning-text: #92400e;
            --tr-danger-bg: #fee2e2;
            --tr-danger-text: #991b1b;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper {
            background-color: var(--tr-bg);
            min-height: 100vh;
        }

        .tr-page {
            padding: 1.5rem; /* Ukuran padding dikecilkan */
            max-width: 1200px; /* Lebar maksimal diturunkan agar lebih pas di layar */
            margin: 0 auto;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--tr-text-main);
        }

        /* ── HEADER ── */
        .tr-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .tr-eyebrow {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--tr-primary);
            margin-bottom: 0.25rem;
        }
        .tr-title {
            font-size: 1.4rem; /* Ukuran judul dikecilkan */
            font-weight: 700;
            color: var(--tr-text-main);
            letter-spacing: -0.02em;
            margin: 0;
        }
        .tr-subtitle {
            font-size: 0.85rem;
            color: var(--tr-text-muted);
            margin: 0.25rem 0 0;
            font-weight: 500;
        }
        
        .tr-btn-refresh {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.4rem 0.8rem;
            background: var(--tr-surface);
            border: 1px solid var(--tr-border);
            border-radius: 6px;
            color: var(--tr-text-muted);
            font-family: inherit;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: var(--tr-shadow-sm);
        }
        .tr-btn-refresh:hover { 
            border-color: var(--tr-text-light); 
            color: var(--tr-text-main); 
        }

        /* ── MAIN CARD ── */
        .tr-card {
            background: var(--tr-surface);
            border-radius: var(--tr-radius-lg);
            border: 1px solid var(--tr-border); /* Ganti bayangan tebal dengan border tipis */
            box-shadow: var(--tr-shadow-sm);
            overflow: hidden;
        }
        .tr-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem; /* Padding area header card dirapatkan */
            border-bottom: 1px solid var(--tr-border-light);
            background: #ffffff;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .tr-card-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--tr-text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .tr-icon-box {
            display: flex; align-items: center; justify-content: center;
            width: 28px; height: 28px;
            border-radius: 6px;
        }
        .tr-icon-box.blue { background: #eff6ff; color: var(--tr-primary); }

        /* ── SEARCH ── */
        .tr-search {
            display: flex;
            align-items: center;
            gap: 6px;
            background: var(--tr-bg);
            border-radius: 6px; /* Sudut search dibuat lebih mengotak (modern) */
            padding: 0.4rem 0.8rem;
            border: 1px solid var(--tr-border);
            width: 220px; /* Lebih ramping */
            transition: border-color 0.2s;
        }
        .tr-search:focus-within {
            border-color: var(--tr-primary);
            background: #ffffff;
        }
        .tr-search-icon { color: var(--tr-text-light); }
        .tr-search input {
            border: none;
            background: transparent;
            font-size: 0.8rem;
            font-family: inherit;
            color: var(--tr-text-main);
            outline: none;
            width: 100%;
        }
        .tr-search input::placeholder { color: var(--tr-text-light); }

        /* ── TABLE RESPONSIVE WRAPPER ── */
        .table-responsive {
            width: 100%;
            overflow-x: auto; /* Kunci agar tidak berantakan di layar kecil */
            -webkit-overflow-scrolling: touch;
        }

        /* ── TABLE ── */
        .tr-table { width: 100%; border-collapse: collapse; min-width: 800px; /* Minimal lebar agar tabel tidak hancur */ }
        .tr-table thead th {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--tr-text-muted);
            padding: 0.75rem 1.25rem; /* Header tabel ditipiskan */
            border-bottom: 1px solid var(--tr-border);
            background: var(--tr-bg);
            white-space: nowrap;
            text-align: left;
        }
        .tr-table thead th.r { text-align: right; }
        
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td {
            padding: 0.85rem 1.25rem; /* Sel tabel dibuat lebih rapat (compact) */
            font-size: 0.8rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--tr-border-light);
        }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table tbody td.r { text-align: right; }

        /* ── CELLS ── */
        .tr-ref {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--tr-text-main);
        }
        .tr-date {
            font-size: 0.8rem;
            color: var(--tr-text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .tr-qty {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--tr-text-main);
        }
        .tr-items-pill {
            background: var(--tr-border-light);
            color: var(--tr-text-muted);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            display: inline-block;
        }
        .tr-product-preview {
            font-size: 0.75rem;
            color: var(--tr-text-light);
            margin-top: 4px;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── BADGES ── */
        .tr-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .tr-badge-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: currentColor;
        }
        .tr-badge-pending { background: var(--tr-warning-bg); color: var(--tr-warning-text); }
        .tr-badge-partial { background: var(--tr-danger-bg); color: var(--tr-danger-text); }
        .tr-badge-done    { background: var(--tr-success-bg); color: var(--tr-success-text); }

        /* ── ACTION BUTTONS ── */
        .tr-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            text-decoration: none;
            transition: all 0.2s;
        }
        .tr-btn-primary { 
            background: var(--tr-text-main); 
            color: #ffffff; 
            border: 1px solid var(--tr-text-main);
        }
        .tr-btn-primary:hover { 
            background: #000000; 
        }
        .tr-btn-outline { 
            background: transparent; 
            border: 1px solid var(--tr-border); 
            color: var(--tr-text-muted); 
        }
        .tr-btn-outline:hover { 
            border-color: var(--tr-text-light); 
            color: var(--tr-text-main); 
            background: #f8fafc;
        }

        /* ── EMPTY STATE ── */
        .tr-empty {
            text-align: center;
            padding: 3rem 1.5rem;
        }
        .tr-empty-icon {
            width: 48px; height: 48px;
            border-radius: 8px;
            background: var(--tr-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--tr-text-light);
        }
        .tr-empty h6 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--tr-text-main);
            margin-bottom: 0.25rem;
        }
        .tr-empty p {
            font-size: 0.8rem;
            color: var(--tr-text-muted);
            margin: 0 auto;
        }

        /* ── PAGINATION ── */
        .tr-pagination {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--tr-border-light);
            background: #ffffff;
        }

        /* ── RESPONSIVE FIXES (MOBILE & TABLET) ── */
        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-card-header { flex-direction: column; align-items: stretch; }
            .tr-search-form, .tr-search { width: 100%; }
        }
    </style>
    @endpush
</x-app-layout>