<x-app-layout>
    <x-slot name="header">Transaksi Penjualan</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── PREMIUM HEADER ─── --}}
            <div class="tr-header animate-fade-in">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Kasir</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-emerald">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                        Transaksi Penjualan
                    </h1>
                    <p class="tr-subtitle">Pantau riwayat seluruh transaksi kasir, detail pendapatan, dan cetak ulang struk.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('kasir.index') }}" class="tr-btn tr-btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        Buka Layar Kasir
                    </a>
                </div>
            </div>

            {{-- ─── SUMMARY STAT CARDS ─── --}}
            <div class="tr-kpi-grid animate-fade-in-up">
                {{-- Pendapatan Hari Ini --}}
                <div class="tr-kpi-card border-emerald">
                    <div class="tr-kpi-header">
                        <div class="tr-kpi-icon bg-emerald"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg></div>
                        <span class="tr-trend-badge up"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"></polyline></svg> Hari Ini</span>
                    </div>
                    <div class="tr-kpi-body">
                        <div class="label">Pendapatan Hari Ini</div>
                        <div class="value text-emerald tr-font-mono">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
                    </div>
                </div>

                {{-- Transaksi Hari Ini --}}
                <div class="tr-kpi-card border-blue">
                    <div class="tr-kpi-header">
                        <div class="tr-kpi-icon bg-blue"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
                        <span class="tr-trend-badge neutral">Hari Ini</span>
                    </div>
                    <div class="tr-kpi-body">
                        <div class="label">Transaksi Sukses</div>
                        <div class="value text-blue">{{ $todayCount }} <small style="font-size:0.8rem;color:var(--tr-text-muted);font-weight:600;">Nota</small></div>
                    </div>
                </div>

                {{-- Total Pendapatan Filter --}}
                <div class="tr-kpi-card border-indigo">
                    <div class="tr-kpi-header">
                        <div class="tr-kpi-icon bg-indigo"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg></div>
                        <span class="tr-trend-badge indigo">Filter Aktif</span>
                    </div>
                    <div class="tr-kpi-body">
                        <div class="label">Total Pendapatan (Filter)</div>
                        <div class="value text-indigo tr-font-mono">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    </div>
                </div>

                {{-- Total Transaksi Filter --}}
                <div class="tr-kpi-card border-amber">
                    <div class="tr-kpi-header">
                        <div class="tr-kpi-icon bg-amber"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg></div>
                        <span class="tr-trend-badge amber">Filter Aktif</span>
                    </div>
                    <div class="tr-kpi-body">
                        <div class="label">Total Transaksi (Filter)</div>
                        <div class="value text-amber">{{ $totalCount }} <small style="font-size:0.8rem;color:var(--tr-text-muted);font-weight:600;">Nota</small></div>
                    </div>
                </div>
            </div>

            {{-- ─── FILTER & DATA TABLE ─── --}}
            <div class="tr-card animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="tr-card-header tr-flex-between" style="border-bottom: none; padding-bottom: 0;">
                    <div>
                        <h2 class="tr-section-title">Riwayat Transaksi</h2>
                        <p class="tr-card-subtitle">Menampilkan <strong>{{ $transactions->total() }}</strong> transaksi ditemukan.</p>
                    </div>
                </div>

                {{-- Filter Bar --}}
                <div class="tr-filter-wrap">
                    <form method="GET" class="tr-filter-form">
                        <div class="tr-form-group search-grp">
                            <label class="tr-label">Pencarian</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Invoice..." class="tr-input">
                        </div>
                        <div class="tr-form-group date-grp">
                            <label class="tr-label">Mulai Tanggal</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="tr-input">
                        </div>
                        <div class="tr-form-group date-grp">
                            <label class="tr-label">Sampai</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="tr-input">
                        </div>
                        <div class="tr-form-group select-grp">
                            <label class="tr-label">Metode</label>
                            <div class="tr-select-wrapper">
                                <select name="payment_method" class="tr-select">
                                    <option value="">Semua Tipe</option>
                                    <option value="cash" @selected(request('payment_method')=='cash')>Tunai</option>
                                    <option value="transfer" @selected(request('payment_method')=='transfer')>Transfer Bank</option>
                                    <option value="qris" @selected(request('payment_method')=='qris')>QRIS</option>
                                </select>
                            </div>
                        </div>
                        <div class="tr-form-group select-grp">
                            <label class="tr-label">Status</label>
                            <div class="tr-select-wrapper">
                                <select name="status" class="tr-select">
                                    <option value="">Semua Status</option>
                                    <option value="completed" @selected(request('status')=='completed')>Selesai</option>
                                    <option value="voided" @selected(request('status')=='voided')>Dibatalkan (Void)</option>
                                </select>
                            </div>
                        </div>
                        <div class="tr-filter-actions">
                            <button type="submit" class="tr-btn tr-btn-dark">Filter</button>
                            <a href="{{ route('transaksi.index') }}" class="tr-btn tr-btn-light">Reset</a>
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>No. Transaksi</th>
                                <th>Waktu</th>
                                <th>Kasir</th>
                                <th class="c">Item</th>
                                <th>Metode Bayar</th>
                                <th class="r">Total (Rp)</th>
                                <th class="r">Diterima (Rp)</th>
                                <th class="r">Kembali (Rp)</th>
                                <th class="c">Status</th>
                                <th class="c" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $trx)
                                <tr class="{{ $trx->status === 'voided' ? 'is-voided' : '' }}">
                                    <td>
                                        <span class="tr-inv-badge">#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td>
                                        <div class="tr-date-main">{{ $trx->created_at->format('d M Y') }}</div>
                                        <div class="tr-date-sub">{{ $trx->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="tr-font-bold">{{ $trx->user?->name ?? '—' }}</td>
                                    <td class="c">
                                        <span class="tr-badge tr-badge-blue">{{ $trx->details->count() }} Pcs</span>
                                    </td>
                                    <td>
                                        @php 
                                            $methodLabel = match($trx->payment_method) {
                                                'cash' => 'Tunai', 
                                                'transfer' => 'Transfer', 
                                                'qris' => 'QRIS', 
                                                default => $trx->payment_method
                                            }; 
                                        @endphp
                                        <span class="tr-method-text">{{ $methodLabel }}</span>
                                    </td>
                                    <td class="r tr-font-mono tr-font-black text-main">
                                        {{ number_format($trx->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="r tr-font-mono text-emerald tr-font-bold">
                                        {{ number_format($trx->paid_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="r tr-font-mono text-amber tr-font-bold">
                                        {{ number_format($trx->change_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="c">
                                        @if($trx->status === 'completed')
                                            <span class="tr-badge tr-badge-success">Selesai</span>
                                        @elseif($trx->status === 'voided')
                                            <span class="tr-badge tr-badge-danger">Void</span>
                                        @else
                                            <span class="tr-badge tr-badge-gray">{{ $trx->status }}</span>
                                        @endif
                                    </td>
                                    <td class="c">
                                        <div class="tr-actions-group">
                                            <a href="{{ route('transaksi.show', $trx) }}" class="tr-action-btn view" title="Lihat Detail">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </a>
                                            @if($trx->status === 'completed')
                                                <a href="{{ route('print.receipt', $trx->id) }}" target="_blank" class="tr-action-btn print" title="Cetak Struk">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                            </div>
                                            <h6>Belum ada transaksi ditemukan</h6>
                                            <p>Data transaksi belum tersedia atau tidak cocok dengan filter pencarian.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="tr-pagination-wrapper">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-emerald: #10b981; --tr-emerald-light: #dcfce7;
            --tr-indigo: #4f46e5; --tr-indigo-light: #e0e7ff;
            --tr-blue: #3b82f6; --tr-blue-light: #dbeafe;
            --tr-amber: #f59e0b; --tr-amber-light: #fef3c7;
            --tr-danger: #ef4444; --tr-danger-light: #fee2e2;
            --tr-bg: #f8fafc; --tr-surface: #ffffff; --tr-border: #e2e8f0;
            --tr-text-main: #0f172a; --tr-text-muted: #64748b;
            --tr-radius: 16px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); padding-bottom: 4rem; }
        .tr-page { max-width: 1300px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* ── ANIMATIONS ── */
        .animate-fade-in { animation: fadeIn 0.5s ease forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease forwards; opacity: 0; transform: translateY(10px); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem; }
        .tr-eyebrow { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-emerald); margin-bottom: 0.5rem; }
        .tr-title { font-size: 1.625rem; font-weight: 900; margin: 0; display: flex; align-items: center; gap: 12px; letter-spacing: -0.02em; }
        .tr-title-icon-box { padding: 8px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .bg-emerald { background: var(--tr-emerald-light); color: var(--tr-emerald); }
        .tr-subtitle { font-size: 0.9rem; color: var(--tr-text-muted); margin-top: 6px; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0.65rem 1.25rem; border-radius: 10px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: 0.2s; border: 1px solid transparent; text-decoration: none; white-space: nowrap; }
        .tr-btn-primary { background: var(--tr-indigo); color: white; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }
        .tr-btn-primary:hover { background: #4338ca; transform: translateY(-1px); }
        .tr-btn-dark { background: var(--tr-text-main); color: white; }
        .tr-btn-dark:hover { background: #000; }
        .tr-btn-light { color: var(--tr-text-muted); background: transparent; }
        .tr-btn-light:hover { background: #f1f5f9; color: var(--tr-text-main); }

        /* ── KPI GRID ── */
        .tr-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
        .tr-kpi-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); padding: 1.25rem; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border-top-width: 4px; transition: 0.2s; }
        .tr-kpi-card:hover { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); transform: translateY(-2px); }
        
        .border-emerald { border-top-color: var(--tr-emerald); }
        .border-blue { border-top-color: var(--tr-blue); }
        .border-indigo { border-top-color: var(--tr-indigo); }
        .border-amber { border-top-color: var(--tr-amber); }

        .tr-kpi-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
        .tr-kpi-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .bg-blue { background: var(--tr-blue-light); color: var(--tr-blue); }
        .bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .bg-amber { background: var(--tr-amber-light); color: var(--tr-amber); }
        
        .tr-trend-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 99px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
        .tr-trend-badge.up { background: var(--tr-emerald-light); color: #065f46; }
        .tr-trend-badge.neutral { background: #f1f5f9; color: var(--tr-text-muted); }
        .tr-trend-badge.indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .tr-trend-badge.amber { background: var(--tr-amber-light); color: #b45309; }

        .tr-kpi-body .label { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; margin-bottom: 4px; }
        .tr-kpi-body .value { font-size: 1.5rem; font-weight: 900; line-height: 1.2; }

        /* ── CARD & FILTER ── */
        .tr-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
        .tr-flex-between { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .tr-section-title { font-size: 1.1rem; font-weight: 800; margin: 0; color: var(--tr-text-main); }
        .tr-card-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin-top: 4px; }

        .tr-filter-wrap { padding: 1.25rem 1.5rem; background: #fafafa; border-bottom: 1px solid var(--tr-border); }
        .tr-filter-form { display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: var(--tr-text-muted); letter-spacing: 0.05em; }
        
        .tr-input, .tr-select { padding: 0.6rem 0.85rem; border: 1px solid var(--tr-border); border-radius: 8px; font-size: 0.85rem; background: #fff; transition: 0.2s; font-family: inherit; color: var(--tr-text-main); font-weight: 500; outline: none; }
        .tr-input:focus, .tr-select:focus { border-color: var(--tr-indigo); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        
        .search-grp { flex-grow: 1; min-width: 200px; }
        .date-grp { width: 140px; }
        .select-grp { width: 150px; }
        .tr-filter-actions { display: flex; gap: 0.5rem; }

        .tr-select-wrapper { position: relative; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        .tr-select { appearance: none; padding-right: 2.5rem; cursor: pointer; width: 100%; }

        /* ── TABLE ── */
        .table-responsive { width: 100%; overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; min-width: 1000px; }
        .tr-table thead th { background: #f8fafc; padding: 1rem 1.25rem; text-align: left; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); border-bottom: 1px solid var(--tr-border); }
        .tr-table tbody td { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; vertical-align: middle; }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table tbody tr:hover { background: #fafafa; }
        .tr-table .c { text-align: center; } .tr-table .r { text-align: right; }
        
        .is-voided td { opacity: 0.5; background-color: #f8fafc; text-decoration: line-through; }
        .is-voided td .tr-inv-badge, .is-voided td .tr-badge { text-decoration: none; display: inline-block; }

        .tr-inv-badge { font-family: ui-monospace, SFMono-Regular, Consolas, monospace; font-weight: 800; font-size: 0.85rem; color: var(--tr-indigo); background: var(--tr-indigo-light); padding: 4px 8px; border-radius: 6px; }
        .tr-date-main { font-weight: 700; font-size: 0.85rem; color: var(--tr-text-main); }
        .tr-date-sub { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; font-weight: 600; }
        .tr-method-text { font-weight: 700; font-size: 0.8rem; color: var(--tr-text-muted); text-transform: uppercase; }

        .tr-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.02em; text-transform: uppercase; }
        .tr-badge-success { background: var(--tr-emerald-light); color: #065f46; }
        .tr-badge-danger { background: var(--tr-danger-light); color: #991b1b; }
        .tr-badge-blue { background: var(--tr-blue-light); color: #1e40af; }
        .tr-badge-gray { background: #f1f5f9; color: var(--tr-text-muted); }

        .tr-actions-group { display: flex; gap: 6px; justify-content: center; }
        .tr-action-btn { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--tr-border); background: white; color: var(--tr-text-muted); transition: 0.2s; cursor: pointer; text-decoration: none; }
        .tr-action-btn.view:hover { color: var(--tr-indigo); border-color: var(--tr-indigo); background: var(--tr-indigo-light); }
        .tr-action-btn.print:hover { color: var(--tr-emerald); border-color: var(--tr-emerald); background: var(--tr-emerald-light); }

        /* ── UTILS ── */
        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
        .tr-font-bold { font-weight: 700; }
        .tr-font-black { font-weight: 900; }
        .text-main { color: var(--tr-text-main); }
        .text-emerald { color: var(--tr-emerald); }
        .text-blue { color: var(--tr-blue); }
        .text-indigo { color: var(--tr-indigo); }
        .text-amber { color: var(--tr-amber); }

        .tr-empty-state { padding: 4rem 2rem; text-align: center; }
        .tr-empty-icon { color: #cbd5e1; margin-bottom: 1rem; }
        .tr-empty-state h6 { font-size: 1.125rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--tr-text-main); }
        .tr-empty-state p { color: var(--tr-text-muted); font-size: 0.9rem; }
        .tr-pagination-wrapper { padding: 1.25rem 1.5rem; border-top: 1px solid #f1f5f9; }

        @media (max-width: 850px) {
            .tr-kpi-grid { grid-template-columns: 1fr 1fr; }
            .tr-filter-form { flex-direction: column; align-items: stretch; }
            .search-grp, .date-grp, .select-grp { width: 100%; }
            .tr-filter-actions { display: grid; grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .tr-header { flex-direction: column; align-items: stretch; }
            .tr-btn { justify-content: center; }
            .tr-kpi-grid { grid-template-columns: 1fr; }
        }
    </style>
    @endpush
</x-app-layout>