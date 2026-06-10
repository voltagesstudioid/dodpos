<x-app-layout>
    <x-slot name="header">Laporan Penjualan</x-slot>

    {{-- ─── PRINT-ONLY HEADER ─── --}}
    <div class="lp-print-header">
        <div class="lp-print-brand">
            <h1>{{ config('app.name', 'DODPOS') }}</h1>
            <p>Sistem Manajemen Bisnis & Gudang Grosir</p>
        </div>
        <div class="lp-print-title">
            <h2>LAPORAN DETAIL PENJUALAN</h2>
            <p>Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="lp-page">

        {{-- ─── PAGE HEADER ─── --}}
        <div class="lp-header lp-no-print">
            <div>
                <div class="lp-eyebrow">Laporan & Analisis</div>
                <h1 class="lp-title">
                    <span class="lp-title-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    </span>
                    Ringkasan Penjualan
                </h1>
                <p class="lp-subtitle">Menampilkan data penjualan berdasarkan periode filter.</p>
            </div>
            <div class="lp-header-btns">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'xlsx', 'page' => null]) }}" class="lp-btn lp-btn-green">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Export Excel
                </a>
                <button type="button" onclick="window.print()" class="lp-btn lp-btn-blue">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    Cetak Laporan
                </button>
            </div>
        </div>

        {{-- ─── FILTER CARD ─── --}}
        <div class="lp-card lp-no-print">
            <form method="GET" class="lp-filter">
                <div class="lp-filter-field">
                    <label>Periode Awal</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}">
                </div>
                <div class="lp-filter-field">
                    <label>Periode Akhir</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}">
                </div>
                <div class="lp-filter-field">
                    <label>Metode</label>
                    <select name="payment_method">
                        <option value="">Semua</option>
                        <option value="cash" @selected(request('payment_method') == 'cash')>Tunai</option>
                        <option value="transfer" @selected(request('payment_method') == 'transfer')>Transfer</option>
                        <option value="qris" @selected(request('payment_method') == 'qris')>QRIS</option>
                        <option value="debit" @selected(request('payment_method') == 'debit')>Debit</option>
                    </select>
                </div>
                <div class="lp-filter-field">
                    <label>Kasir</label>
                    <select name="kasir_id">
                        <option value="">Semua Kasir</option>
                        @foreach($kasirs as $k)
                            <option value="{{ $k->id }}" @selected(request('kasir_id') == $k->id)>{{ $k->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lp-filter-btns">
                    <button type="submit" class="lp-btn lp-btn-dark">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        Filter
                    </button>
                    <a href="{{ route('laporan.penjualan') }}" class="lp-btn lp-btn-ghost">Reset</a>
                </div>
            </form>
        </div>

        {{-- ─── STAT CARDS ─── --}}
        <div class="lp-stats lp-no-print">
            <div class="lp-stat lp-stat-gray">
                <div class="lp-stat-icon lp-ic-gray">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                </div>
                <div class="lp-stat-label">Total Transaksi</div>
                <div class="lp-stat-val">{{ number_format($totalTrx) }} <span class="lp-unit">Nota</span></div>
            </div>
            <div class="lp-stat lp-stat-green">
                <div class="lp-stat-icon lp-ic-green">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
                <div class="lp-stat-label">Total Omzet</div>
                <div class="lp-stat-val lp-mono lp-text-green">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
            </div>
            <div class="lp-stat lp-stat-blue">
                <div class="lp-stat-icon lp-ic-blue">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                </div>
                <div class="lp-stat-label">Rata-rata Penjualan</div>
                <div class="lp-stat-val lp-mono lp-text-blue">Rp {{ number_format($avgPerTrx, 0, ',', '.') }}</div>
            </div>
            <div class="lp-stat lp-stat-amber">
                <div class="lp-stat-icon lp-ic-amber">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                </div>
                <div class="lp-stat-label">Total Item Terjual</div>
                <div class="lp-stat-val lp-mono">{{ number_format($totalItems) }} <span class="lp-unit">Pcs</span></div>
            </div>
        </div>

        {{-- ─── MAIN CONTENT GRID ─── --}}
        <div class="lp-grid">

            {{-- LEFT: Transaction Table --}}
            <div class="lp-main">
                <div class="lp-card lp-print-table-wrap">
                    <div class="lp-card-head lp-no-print">
                        <h3 class="lp-card-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                            Daftar Transaksi
                        </h3>
                    </div>
                    <div class="lp-table-scroll">
                        <table class="lp-table lp-print-table">
                            <thead>
                                <tr>
                                    <th class="lp-th-num">#</th>
                                    <th>Tanggal/Waktu</th>
                                    <th>Kasir</th>
                                    <th class="lp-th-c">Item</th>
                                    <th class="lp-th-c">Metode</th>
                                    <th class="lp-th-r">Total (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $i => $trx)
                                    <tr class="lp-print-row">
                                        <td class="lp-num">{{ $i + 1 }}</td>
                                        <td>
                                            <div class="lp-date-main">{{ $trx->created_at->format('d/m/Y') }}</div>
                                            <div class="lp-date-sub">{{ $trx->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td>{{ $trx->user?->name ?? '—' }}</td>
                                        <td class="lp-th-c lp-bold">{{ $trx->details->count() }}</td>
                                        <td class="lp-th-c">
                                            <span class="lp-method-badge">{{ strtoupper($trx->payment_method) }}</span>
                                        </td>
                                        <td class="lp-th-r lp-mono lp-bold">
                                            {{ number_format($trx->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="lp-empty">
                                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="15" x2="16" y2="15"></line><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                                            <p>Tidak ada data transaksi pada periode ini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($transactions->count() > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="lp-th-r lp-bold lp-text-main">Total Keseluruhan</td>
                                        <td class="lp-th-r lp-mono lp-total-foot">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    @if($transactions->hasPages())
                        <div class="lp-pagination lp-no-print">{{ $transactions->links() }}</div>
                    @endif
                </div>
            </div>

            {{-- RIGHT: Sidebar Panels --}}
            <div class="lp-sidebar lp-no-print">

                {{-- Top Products --}}
                <div class="lp-card">
                    <div class="lp-card-head">
                        <h3 class="lp-card-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            Produk Terlaris
                        </h3>
                    </div>
                    <div class="lp-list">
                        @forelse ($topProducts as $i => $tp)
                            <div class="lp-list-item">
                                <div class="lp-list-rank">{{ $i + 1 }}</div>
                                <div class="lp-list-body">
                                    <div class="lp-list-name" title="{{ $tp->product?->name }}">{{ $tp->product?->name ?? 'ID: '.$tp->product_id }}</div>
                                    <div class="lp-list-meta">{{ number_format($tp->total_qty) }} Pcs Terjual</div>
                                </div>
                                <div class="lp-list-amount lp-mono">
                                    Rp {{ number_format($tp->total_revenue / 1000, 0, ',', '.') }}K
                                </div>
                            </div>
                        @empty
                            <div class="lp-list-empty">Belum ada data.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Payment Methods --}}
                <div class="lp-card">
                    <div class="lp-card-head">
                        <h3 class="lp-card-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                            Metode Pembayaran
                        </h3>
                    </div>
                    <div class="lp-list">
                        @forelse($byPayment as $p)
                            <div class="lp-list-item">
                                <div class="lp-list-icon lp-ic-method">
                                    @if(strtolower($p['method']) === 'cash' || strtolower($p['method']) === 'tunai')
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                    @elseif(strtolower($p['method']) === 'transfer' || strtolower($p['method']) === 'qris')
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                                    @else
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                    @endif
                                </div>
                                <div class="lp-list-body">
                                    <div class="lp-list-name">{{ $p['label'] }}</div>
                                    <div class="lp-list-meta">{{ $p['count'] }} Trx</div>
                                </div>
                                <div class="lp-list-amount lp-mono">
                                    Rp {{ number_format($p['amount'], 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <div class="lp-list-empty">Belum ada data.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Cashier Breakdown --}}
                @if($byCashier->isNotEmpty())
                    <div class="lp-card">
                        <div class="lp-card-head">
                            <h3 class="lp-card-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                Per Kasir
                            </h3>
                        </div>
                        <div class="lp-list">
                            @foreach($byCashier as $c)
                                <div class="lp-list-item">
                                    <div class="lp-list-avatar">{{ strtoupper(substr($c['name'], 0, 1)) }}</div>
                                    <div class="lp-list-body">
                                        <div class="lp-list-name">{{ $c['name'] }}</div>
                                        <div class="lp-list-meta">{{ $c['count'] }} Trx</div>
                                    </div>
                                    <div class="lp-list-amount lp-mono">
                                        Rp {{ number_format($c['amount'], 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>

    {{-- ─── PRINT-ONLY FOOTER ─── --}}
    <div class="lp-print-footer">
        <div class="lp-sign-box">
            <p>Medan, {{ now()->format('d F Y') }}</p>
            <p>Mengetahui,</p>
            <br><br><br>
            <p class="lp-sign-name">{{ auth()->user()->name ?? 'Administrator' }}</p>
            <p>Admin DodPOS</p>
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        .lp-page { max-width: 1280px; margin: 0 auto; padding: 1.5rem 1.5rem 3rem; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: #0f172a; }
        .lp-print-header, .lp-print-footer { display: none; }

        /* Header */
        .lp-header { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; }
        .lp-eyebrow { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #3b82f6; margin-bottom: 4px; }
        .lp-title { font-size: 1.4rem; font-weight: 900; margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .lp-title-icon { width: 38px; height: 38px; border-radius: 10px; background: #dbeafe; color: #3b82f6; display: flex; align-items: center; justify-content: center; }
        .lp-subtitle { font-size: 0.82rem; color: #64748b; margin: 4px 0 0; }
        .lp-header-btns { display: flex; gap: 0.5rem; }

        /* Buttons */
        .lp-btn { display: inline-flex; align-items: center; gap: 6px; padding: 0.55rem 1rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: all 0.15s; border: 1px solid transparent; text-decoration: none; font-family: inherit; white-space: nowrap; }
        .lp-btn-green { background: #16a34a; color: #fff; }
        .lp-btn-green:hover { background: #15803d; transform: translateY(-1px); }
        .lp-btn-blue { background: #3b82f6; color: #fff; }
        .lp-btn-blue:hover { background: #2563eb; transform: translateY(-1px); }
        .lp-btn-dark { background: #0f172a; color: #fff; }
        .lp-btn-dark:hover { background: #000; }
        .lp-btn-ghost { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }
        .lp-btn-ghost:hover { background: #f1f5f9; color: #0f172a; }

        /* Card */
        .lp-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); overflow: hidden; }
        .lp-card-head { padding: 0.85rem 1.15rem; background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
        .lp-card-title { font-size: 0.88rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 8px; color: #0f172a; }
        .lp-card-title svg { color: #94a3b8; }

        /* Filter */
        .lp-filter { display: flex; flex-wrap: wrap; gap: 0.75rem; padding: 1rem 1.15rem; align-items: flex-end; }
        .lp-filter-field { display: flex; flex-direction: column; gap: 4px; min-width: 140px; flex: 1; }
        .lp-filter-field label { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; }
        .lp-filter-field input, .lp-filter-field select { padding: 0.55rem 0.7rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.82rem; background: #f8fafc; font-family: inherit; font-weight: 600; color: #0f172a; outline: none; transition: border-color 0.15s; }
        .lp-filter-field input:focus, .lp-filter-field select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.08); }
        .lp-filter-btns { display: flex; gap: 0.5rem; align-self: flex-end; }

        /* Stat Cards */
        .lp-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.85rem; margin-bottom: 1.25rem; }
        .lp-stat { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem 1.1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
        .lp-stat-gray { border-left: 4px solid #94a3b8; }
        .lp-stat-green { border-left: 4px solid #10b981; }
        .lp-stat-blue { border-left: 4px solid #3b82f6; }
        .lp-stat-amber { border-left: 4px solid #f59e0b; }
        .lp-stat-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.5rem; }
        .lp-ic-gray { background: #f1f5f9; color: #64748b; }
        .lp-ic-green { background: #dcfce7; color: #16a34a; }
        .lp-ic-blue { background: #dbeafe; color: #3b82f6; }
        .lp-ic-amber { background: #fef3c7; color: #d97706; }
        .lp-stat-label { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; color: #94a3b8; margin-bottom: 4px; }
        .lp-stat-val { font-size: 1.15rem; font-weight: 900; color: #0f172a; letter-spacing: -0.02em; }
        .lp-unit { font-size: 0.75rem; font-weight: 500; color: #94a3b8; }

        /* Grid Layout */
        .lp-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; }
        .lp-main { min-width: 0; }

        /* Table */
        .lp-table-scroll { width: 100%; overflow-x: auto; }
        .lp-table { width: 100%; border-collapse: collapse; min-width: 600px; }
        .lp-table thead th { background: #f8fafc; padding: 0.7rem 0.85rem; text-align: left; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; color: #64748b; border-bottom: 2px solid #e2e8f0; letter-spacing: 0.03em; }
        .lp-table tbody td { padding: 0.7rem 0.85rem; border-bottom: 1px solid #f1f5f9; font-size: 0.82rem; vertical-align: middle; }
        .lp-table tbody tr:last-child td { border-bottom: none; }
        .lp-table tbody tr:hover { background: #fafafa; }
        .lp-table tfoot td { padding: 0.85rem; background: #f8fafc; border-top: 2px solid #e2e8f0; font-size: 0.88rem; }
        .lp-th-num { width: 40px; text-align: center; }
        .lp-th-c { text-align: center; }
        .lp-th-r { text-align: right; }

        .lp-num { text-align: center; color: #94a3b8; font-size: 0.78rem; font-weight: 600; }
        .lp-date-main { font-weight: 700; color: #0f172a; font-size: 0.82rem; }
        .lp-date-sub { font-size: 0.7rem; color: #94a3b8; }
        .lp-method-badge { font-size: 0.62rem; font-weight: 800; padding: 3px 8px; border-radius: 5px; background: #f1f5f9; color: #475569; letter-spacing: 0.03em; }
        .lp-total-foot { font-weight: 900; font-size: 1rem; color: #16a34a; }
        .lp-empty { text-align: center; padding: 2.5rem 1rem !important; color: #cbd5e1; }
        .lp-empty svg { margin-bottom: 0.5rem; opacity: 0.3; }
        .lp-empty p { margin: 0; font-weight: 600; font-size: 0.85rem; }

        /* Sidebar Lists */
        .lp-list { padding: 0; }
        .lp-list-item { display: flex; align-items: center; gap: 10px; padding: 0.7rem 1rem; border-bottom: 1px solid #f1f5f9; transition: background 0.1s; }
        .lp-list-item:last-child { border-bottom: none; }
        .lp-list-item:hover { background: #fafafa; }
        .lp-list-rank { width: 22px; height: 22px; border-radius: 6px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 800; color: #64748b; flex-shrink: 0; }
        .lp-list-icon { width: 28px; height: 28px; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .lp-ic-method { background: #eff6ff; color: #3b82f6; }
        .lp-list-avatar { width: 28px; height: 28px; border-radius: 7px; background: linear-gradient(135deg, #eff6ff, #dbeafe); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #3b82f6; font-size: 0.72rem; flex-shrink: 0; border: 1px solid #bfdbfe; }
        .lp-list-body { flex: 1; min-width: 0; }
        .lp-list-name { font-weight: 700; font-size: 0.8rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .lp-list-meta { font-size: 0.68rem; color: #94a3b8; font-weight: 500; }
        .lp-list-amount { font-size: 0.78rem; font-weight: 800; color: #0f172a; white-space: nowrap; flex-shrink: 0; }
        .lp-list-empty { padding: 1.5rem; text-align: center; font-size: 0.82rem; color: #94a3b8; font-style: italic; }

        /* Utils */
        .lp-mono { font-family: ui-monospace, 'Cascadia Code', 'Fira Code', Consolas, monospace; }
        .lp-bold { font-weight: 800; }
        .lp-text-green { color: #16a34a; }
        .lp-text-blue { color: #2563eb; }
        .lp-text-main { color: #0f172a; }
        .lp-pagination { padding: 0.85rem 1rem; border-top: 1px solid #f1f5f9; }

        /* Print Styles */
        @@media print {
            @@page { size: A4 portrait; margin: 1cm; }
            body { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .lp-page { padding: 0; max-width: 100%; }
            .lp-no-print { display: none !important; }
            header, nav { display: none !important; }
            .lp-print-header { display: block !important; margin-bottom: 16px; }
            .lp-print-brand { text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 12px; }
            .lp-print-brand h1 { font-size: 18pt; font-weight: 900; margin: 0; text-transform: uppercase; }
            .lp-print-brand p { font-size: 9pt; color: #444; margin: 2px 0 0; }
            .lp-print-title { text-align: center; margin-bottom: 16px; }
            .lp-print-title h2 { font-size: 13pt; font-weight: 800; margin: 0 0 4px; text-transform: uppercase; text-decoration: underline; }
            .lp-print-title p { font-size: 9pt; color: #444; margin: 0; }
            .lp-grid { display: block; }
            .lp-print-table-wrap { border: none; box-shadow: none; }
            .lp-print-table { width: 100% !important; border-collapse: collapse; font-size: 10pt; }
            .lp-print-table th, .lp-print-table td { border: 1px solid #000; padding: 5px 8px; color: #000; }
            .lp-print-table th { background: #eee; }
            .lp-print-table thead { display: table-header-group; }
            .lp-print-table tfoot { display: table-footer-group; }
            .lp-print-row { page-break-inside: avoid; }
            .lp-print-footer { display: block !important; margin-top: 30px; }
            .lp-sign-box { text-align: center; width: 220px; margin-left: auto; font-size: 10pt; }
            .lp-sign-box p { margin: 2px 0; }
            .lp-sign-name { font-weight: 900; text-decoration: underline; }
        }

        /* Responsive */
        @@media (max-width: 992px) {
            .lp-stats { grid-template-columns: repeat(2, 1fr); }
            .lp-grid { grid-template-columns: 1fr; }
        }
        @@media (max-width: 768px) {
            .lp-filter { flex-direction: column; }
            .lp-filter-field { width: 100%; min-width: 100%; }
            .lp-header { flex-direction: column; }
            .lp-header-btns { width: 100%; }
            .lp-header-btns .lp-btn { flex: 1; justify-content: center; }
        }
        @@media (max-width: 480px) { .lp-stats { grid-template-columns: 1fr; } }
    </style>
    @endpush
</x-app-layout>
