<x-app-layout>
    <x-slot name="header">Laporan Penjualan</x-slot>

    {{-- ─── PRINT-ONLY HEADER ─── --}}
    <div class="lp-print-header">
        <div class="lp-print-brand">
            <h1>{{ config('app.name', 'DODPOS') }}</h1>
            <p>Sistem Manajemen Bisnis &amp; Gudang Grosir</p>
        </div>
        <div class="lp-print-title">
            <h2>LAPORAN DETAIL PENJUALAN</h2>
            <p>Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</p>
        </div>
        <div class="lp-print-summary">
            <table>
                <tr><td>Total Transaksi</td><td>:</td><td>{{ number_format($totalTrx) }} Nota</td></tr>
                <tr><td>Total Omzet</td><td>:</td><td>Rp {{ number_format($totalOmzet, 0, ',', '.') }}</td></tr>
                <tr><td>Rata-rata / Transaksi</td><td>:</td><td>Rp {{ number_format($avgPerTrx, 0, ',', '.') }}</td></tr>
                <tr><td>Total Item Terjual</td><td>:</td><td>{{ number_format($totalItems) }} Pcs</td></tr>
            </table>
        </div>
    </div>

    <div class="lp-page">

        {{-- ─── HERO HEADER ─── --}}
        <div class="lp-hero lp-no-print">
            <div class="lp-hero-inner">
                <div class="lp-hero-top">
                    <div>
                        <div class="lp-hero-badge">
                            <span class="lp-hero-dot"></span>
                            Laporan &amp; Analisis
                        </div>
                        <h1 class="lp-hero-title">Ringkasan Penjualan</h1>
                        <p class="lp-hero-sub">Analisis performa penjualan berdasarkan periode yang dipilih.</p>
                    </div>
                    <div class="lp-hero-actions">
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'xlsx', 'page' => null]) }}" class="lp-hero-btn lp-hero-btn-green">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Export Excel
                        </a>
                        <button type="button" onclick="window.print()" class="lp-hero-btn lp-hero-btn-blue">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                            Cetak
                        </button>
                    </div>
                </div>
                <div class="lp-hero-stats">
                    <div>
                        <div class="lp-hero-label">Total Omzet</div>
                        <div class="lp-hero-amount">
                            <span class="lp-hero-rp">Rp</span>{{ number_format($totalOmzet, 0, ',', '.') }}
                        </div>
                        <div class="lp-hero-chip">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ \Carbon\Carbon::parse($dateFrom)->format('d M') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
                        </div>
                    </div>
                    <div class="lp-hero-right">
                        <div>
                            <div class="lp-hero-count">{{ number_format($totalTrx) }}</div>
                            <div class="lp-hero-count-label">Total Transaksi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── STAT CARDS ─── --}}
        <div class="lp-stats lp-no-print">
            <div class="lp-stat lp-stat-emerald">
                <div class="lp-stat-top">
                    <div class="lp-stat-ico lp-ic-emerald">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <span class="lp-stat-tag lp-tag-emerald">Omzet</span>
                </div>
                <div class="lp-stat-lbl">Total Omzet</div>
                <div class="lp-stat-val lp-val-emerald">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
            </div>
            <div class="lp-stat lp-stat-blue">
                <div class="lp-stat-top">
                    <div class="lp-stat-ico lp-ic-blue">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <span class="lp-stat-tag lp-tag-blue">Rata-rata</span>
                </div>
                <div class="lp-stat-lbl">Rata-rata / Transaksi</div>
                <div class="lp-stat-val lp-val-blue">Rp {{ number_format($avgPerTrx, 0, ',', '.') }}</div>
            </div>
            <div class="lp-stat lp-stat-amber">
                <div class="lp-stat-top">
                    <div class="lp-stat-ico lp-ic-amber">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <span class="lp-stat-tag lp-tag-amber">Item</span>
                </div>
                <div class="lp-stat-lbl">Total Item Terjual</div>
                <div class="lp-stat-val lp-val-amber">{{ number_format($totalItems) }} Pcs</div>
            </div>
            <div class="lp-stat lp-stat-indigo">
                <div class="lp-stat-top">
                    <div class="lp-stat-ico lp-ic-indigo">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <span class="lp-stat-tag lp-tag-indigo">Nota</span>
                </div>
                <div class="lp-stat-lbl">Total Transaksi</div>
                <div class="lp-stat-val lp-val-indigo">{{ number_format($totalTrx) }} Nota</div>
            </div>
        </div>

        {{-- ─── FILTER CARD ─── --}}
        <div class="lp-card lp-no-print">
            <form method="GET" class="lp-filter">
                <div class="lp-fg">
                    <label class="lp-fl">Periode Awal</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="lp-fi">
                </div>
                <div class="lp-fg">
                    <label class="lp-fl">Periode Akhir</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="lp-fi">
                </div>
                <div class="lp-fg">
                    <label class="lp-fl">Metode</label>
                    <select name="payment_method" class="lp-fi">
                        <option value="">Semua</option>
                        <option value="cash" @selected(request('payment_method') == 'cash')>Tunai</option>
                        <option value="transfer" @selected(request('payment_method') == 'transfer')>Transfer</option>
                        <option value="qris" @selected(request('payment_method') == 'qris')>QRIS</option>
                        <option value="kredit" @selected(request('payment_method') == 'kredit')>Limit</option>
                    </select>
                </div>
                <div class="lp-fg">
                    <label class="lp-fl">Kasir</label>
                    <select name="kasir_id" class="lp-fi">
                        <option value="">Semua Kasir</option>
                        @foreach($kasirs as $k)
                            <option value="{{ $k->id }}" @selected(request('kasir_id') == $k->id)>{{ $k->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lp-filter-btns">
                    <button type="submit" class="lp-btn lp-btn-dark">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('laporan.penjualan') }}" class="lp-btn lp-btn-ghost">Reset</a>
                </div>
            </form>
        </div>

        {{-- ─── MAIN CONTENT GRID ─── --}}
        <div class="lp-grid">

            {{-- LEFT: Transaction Table --}}
            <div class="lp-main">
                <div class="lp-card lp-print-table-wrap">
                    <div class="lp-card-head lp-no-print">
                        <div>
                            <h3 class="lp-card-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                                Daftar Transaksi
                            </h3>
                            <div class="lp-card-sub">Menampilkan {{ $transactions->total() }} transaksi</div>
                        </div>
                        <span class="lp-card-count">{{ $transactions->total() }} Data</span>
                    </div>
                    <div class="lp-table-scroll">
                        <table class="lp-table lp-print-table">
                            <thead>
                                <tr>
                                    <th class="th-num">#</th>
                                    <th>Tanggal/Waktu</th>
                                    <th>Kasir</th>
                                    <th class="th-c">Item</th>
                                    <th class="th-c">Metode</th>
                                    <th class="th-r">Total (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $i => $trx)
                                    @php
                                        $itemCount = $trx->details->count();
                                        foreach ($trx->additionalTransactions as $at) {
                                            $itemCount += $at->details->count();
                                        }
                                    @endphp
                                    <tr class="lp-print-row">
                                        <td class="td-num">{{ $transactions->firstItem() + $i }}</td>
                                        <td>
                                            <div class="td-date">{{ $trx->created_at->format('d/m/Y') }}</div>
                                            <div class="td-time">{{ $trx->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td>
                                            <span class="td-kasir">{{ $trx->user?->name ?? '—' }}</span>
                                        </td>
                                        <td class="th-c">
                                            <span class="td-item-badge">{{ $itemCount }}</span>
                                        </td>
                                        <td class="th-c">
                                            <span class="td-method">{{ strtoupper($trx->payment_method) }}</span>
                                        </td>
                                        <td class="th-r">
                                            <span class="td-amount">{{ number_format($trx->grand_total, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="td-empty">
                                            <div class="lp-empty">
                                                <div class="lp-empty-ico">
                                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="8" y1="15" x2="16" y2="15"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                                                </div>
                                                <div class="lp-empty-title">Tidak Ada Data</div>
                                                <div class="lp-empty-sub">Tidak ada data transaksi pada periode ini.</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($transactions->count() > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="th-r tf-label">Total Keseluruhan</td>
                                        <td class="th-r tf-amount">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</td>
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
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            Produk Terlaris
                        </h3>
                        <span class="lp-card-tag">Top 10</span>
                    </div>
                    <div class="lp-list">
                        @forelse ($topProducts as $i => $tp)
                            <div class="lp-list-item">
                                <div class="lp-rank {{ $i < 3 ? 'lp-rank-top' : '' }}">{{ $i + 1 }}</div>
                                <div class="lp-list-body">
                                    <div class="lp-list-name" title="{{ $tp->product?->name }}">{{ $tp->product?->name ?? 'ID: '.$tp->product_id }}</div>
                                    <div class="lp-list-meta">{{ number_format($tp->total_qty) }} Pcs Terjual</div>
                                </div>
                                <div class="lp-list-amount">
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
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            Metode Pembayaran
                        </h3>
                    </div>
                    <div class="lp-list">
                        @forelse($byPayment as $p)
                            <div class="lp-list-item">
                                <div class="lp-method-ico">
                                    @if(strtolower($p['method']) === 'cash' || strtolower($p['method']) === 'tunai')
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                    @elseif(strtolower($p['method']) === 'transfer' || strtolower($p['method']) === 'qris')
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                    @else
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                    @endif
                                </div>
                                <div class="lp-list-body">
                                    <div class="lp-list-name">{{ $p['label'] }}</div>
                                    <div class="lp-list-meta">{{ $p['count'] }} Trx</div>
                                </div>
                                <div class="lp-list-amount">
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
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                Per Kasir
                            </h3>
                        </div>
                        <div class="lp-list">
                            @foreach($byCashier as $c)
                                <div class="lp-list-item">
                                    <div class="lp-avatar">{{ strtoupper(substr($c['name'], 0, 1)) }}</div>
                                    <div class="lp-list-body">
                                        <div class="lp-list-name">{{ $c['name'] }}</div>
                                        <div class="lp-list-meta">{{ $c['count'] }} Trx</div>
                                    </div>
                                    <div class="lp-list-amount">
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

    {{-- ─── PRINT-ONLY: BREAKDOWN TABLES ─── --}}
    <div class="lp-print-footer">
        <div class="lp-print-section">
            <h3>Ringkasan Per Metode Pembayaran</h3>
            <table class="lp-print-breakdown">
                <thead>
                    <tr><th>Metode</th><th>Jumlah Trx</th><th>Total (Rp)</th></tr>
                </thead>
                <tbody>
                    @foreach($byPayment as $p)
                        <tr>
                            <td>{{ $p['label'] }}</td>
                            <td>{{ $p['count'] }}</td>
                            <td>Rp {{ number_format($p['amount'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($byCashier->isNotEmpty())
        <div class="lp-print-section">
            <h3>Ringkasan Per Kasir</h3>
            <table class="lp-print-breakdown">
                <thead>
                    <tr><th>Kasir</th><th>Jumlah Trx</th><th>Total (Rp)</th></tr>
                </thead>
                <tbody>
                    @foreach($byCashier as $c)
                        <tr>
                            <td>{{ $c['name'] }}</td>
                            <td>{{ $c['count'] }}</td>
                            <td>Rp {{ number_format($c['amount'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

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

        .lp-page{max-width:1280px;margin:0 auto;padding:0 0 3rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:#0f172a;}
        .lp-print-header,.lp-print-footer{display:none;}

        /* ── HERO HEADER ── */
        .lp-hero{background:linear-gradient(135deg,#06090f 0%,#0d1322 35%,#111827 70%,#0a0e1a 100%);border-radius:20px;padding:2rem 2.25rem 3.5rem;margin-bottom:-2rem;position:relative;overflow:hidden;}
        .lp-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 75% 25%,rgba(59,130,246,0.18) 0%,transparent 60%),radial-gradient(ellipse at 20% 75%,rgba(16,185,129,0.08) 0%,transparent 50%);}
        .lp-hero::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,rgba(59,130,246,0.5),rgba(16,185,129,0.3),transparent);}
        .lp-hero-inner{position:relative;z-index:1;}
        .lp-hero-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
        .lp-hero-badge{display:inline-flex;align-items:center;gap:0.5rem;background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);padding:0.3rem 0.875rem;border-radius:99px;font-size:0.65rem;font-weight:700;color:rgba(147,197,253,0.9);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.75rem;}
        .lp-hero-dot{width:6px;height:6px;border-radius:50%;background:#60a5fa;animation:lp-pulse 2s infinite;}
        @keyframes lp-pulse{0%,100%{opacity:1}50%{opacity:0.4}}
        .lp-hero-title{font-size:1.75rem;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.1;margin:0 0 0.35rem;}
        .lp-hero-sub{font-size:0.8125rem;color:rgba(255,255,255,0.4);margin:0;}
        .lp-hero-actions{display:flex;gap:0.5rem;align-items:center;}
        .lp-hero-btn{display:inline-flex;align-items:center;gap:0.4rem;padding:0.6rem 1.15rem;border-radius:10px;font-size:0.8rem;font-weight:700;cursor:pointer;transition:all .2s;border:none;text-decoration:none;white-space:nowrap;font-family:inherit;}
        .lp-hero-btn svg{width:15px;height:15px;}
        .lp-hero-btn-green{background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;box-shadow:0 4px 14px rgba(22,163,74,0.3);}
        .lp-hero-btn-green:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(22,163,74,0.4);}
        .lp-hero-btn-blue{background:linear-gradient(135deg,#3b82f6,#60a5fa);color:#fff;box-shadow:0 4px 14px rgba(59,130,246,0.3);}
        .lp-hero-btn-blue:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(59,130,246,0.4);}
        .lp-hero-stats{display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1.25rem;}
        .lp-hero-label{font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.12em;margin-bottom:0.5rem;}
        .lp-hero-amount{font-size:2.5rem;font-weight:900;color:#fff;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;letter-spacing:-0.03em;line-height:1;}
        .lp-hero-rp{font-size:1rem;opacity:0.45;margin-right:3px;font-weight:700;}
        .lp-hero-chip{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.08);padding:4px 12px;border-radius:7px;font-size:0.68rem;font-weight:600;color:rgba(255,255,255,0.7);margin-top:0.75rem;}
        .lp-hero-right{display:flex;flex-direction:column;align-items:flex-end;gap:8px;padding-bottom:4px;}
        .lp-hero-count{font-size:2rem;font-weight:900;color:rgba(255,255,255,0.9);font-family:ui-monospace,monospace;line-height:1;}
        .lp-hero-count-label{font-size:0.68rem;font-weight:600;color:rgba(255,255,255,0.35);text-transform:uppercase;letter-spacing:0.08em;}

        /* ── STAT CARDS ── */
        .lp-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:0.875rem;margin-bottom:1.5rem;position:relative;z-index:2;}
        .lp-stat{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:1.15rem 1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.04);transition:all .2s;position:relative;overflow:hidden;}
        .lp-stat:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,0.06);}
        .lp-stat::before{content:'';position:absolute;top:0;right:0;width:60px;height:60px;border-radius:50%;opacity:0.06;transform:translate(15px,-15px);}
        .lp-stat-emerald::before{background:#059669;}
        .lp-stat-blue::before{background:#3b82f6;}
        .lp-stat-amber::before{background:#d97706;}
        .lp-stat-indigo::before{background:#4f46e5;}
        .lp-stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;}
        .lp-stat-ico{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;}
        .lp-ic-emerald{background:#ecfdf5;color:#059669;}
        .lp-ic-blue{background:#eff6ff;color:#3b82f6;}
        .lp-ic-amber{background:#fffbeb;color:#d97706;}
        .lp-ic-indigo{background:#eef2ff;color:#4f46e5;}
        .lp-stat-tag{font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;padding:3px 8px;border-radius:99px;}
        .lp-tag-emerald{background:#ecfdf5;color:#065f46;}
        .lp-tag-blue{background:#eff6ff;color:#1e40af;}
        .lp-tag-amber{background:#fffbeb;color:#92400e;}
        .lp-tag-indigo{background:#eef2ff;color:#4338ca;}
        .lp-stat-lbl{font-size:0.68rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.04em;}
        .lp-stat-val{font-size:1.15rem;font-weight:900;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;letter-spacing:-0.02em;margin-top:0.2rem;}
        .lp-val-emerald{color:#059669;}
        .lp-val-blue{color:#3b82f6;}
        .lp-val-amber{color:#d97706;}
        .lp-val-indigo{color:#4f46e5;}

        /* ── CARD ── */
        .lp-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,0.04);overflow:hidden;margin-bottom:1rem;}
        .lp-card-head{padding:1rem 1.25rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem;border-bottom:1px solid #f1f5f9;background:linear-gradient(180deg,#fafbfc,#fff);}
        .lp-card-title{font-size:0.875rem;font-weight:800;margin:0;display:flex;align-items:center;gap:8px;color:#0f172a;}
        .lp-card-title svg{color:#94a3b8;}
        .lp-card-sub{font-size:0.72rem;color:#64748b;margin-top:2px;font-weight:500;}
        .lp-card-count{font-size:0.72rem;font-weight:700;color:#4f46e5;background:#e0e7ff;padding:3px 10px;border-radius:99px;}
        .lp-card-tag{font-size:0.65rem;font-weight:700;color:#d97706;background:#fef3c7;padding:3px 8px;border-radius:99px;}

        /* ── FILTER ── */
        .lp-filter{display:flex;flex-wrap:wrap;gap:0.65rem;padding:1rem 1.25rem;align-items:flex-end;background:#f8fafc;}
        .lp-fg{display:flex;flex-direction:column;gap:0.25rem;min-width:130px;flex:1;}
        .lp-fl{font-size:0.62rem;font-weight:700;text-transform:uppercase;color:#94a3b8;letter-spacing:0.06em;}
        .lp-fi{padding:0.5rem 0.75rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.8rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;transition:all .15s;background:#fff;color:#0f172a;font-weight:500;}
        .lp-fi:focus{outline:none;border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,0.1);}
        select.lp-fi{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 0.6rem center;padding-right:1.75rem;cursor:pointer;}
        .lp-filter-btns{display:flex;gap:0.4rem;align-self:flex-end;}
        .lp-btn{display:inline-flex;align-items:center;gap:0.35rem;padding:0.5rem 0.875rem;border-radius:8px;font-size:0.78rem;font-weight:700;cursor:pointer;transition:all .15s;border:none;text-decoration:none;white-space:nowrap;font-family:inherit;}
        .lp-btn svg{width:14px;height:14px;}
        .lp-btn-dark{background:#0f172a;color:#fff;}
        .lp-btn-dark:hover{background:#1e293b;}
        .lp-btn-ghost{background:transparent;color:#64748b;border:1.5px solid #e2e8f0;}
        .lp-btn-ghost:hover{background:#f1f5f9;color:#0f172a;border-color:#94a3b8;}

        /* ── GRID LAYOUT ── */
        .lp-grid{display:grid;grid-template-columns:1fr 340px;gap:1.25rem;align-items:start;}
        .lp-main{min-width:0;}

        /* ── TABLE ── */
        .lp-table-scroll{width:100%;overflow-x:auto;}
        .lp-table{width:100%;border-collapse:collapse;min-width:620px;}
        .lp-table thead th{background:linear-gradient(180deg,#f8fafc,#f4f8fc);padding:0.75rem 1rem;text-align:left;font-size:0.62rem;font-weight:700;text-transform:uppercase;color:#94a3b8;border-bottom:2px solid #e2e8f0;letter-spacing:0.04em;}
        .lp-table tbody td{padding:0.8rem 1rem;border-bottom:1px solid #f1f5f9;font-size:0.8125rem;vertical-align:middle;}
        .lp-table tbody tr:last-child td{border-bottom:none;}
        .lp-table tbody tr{transition:background .15s;}
        .lp-table tbody tr:hover{background:#f8fafc;}
        .lp-table tfoot td{padding:0.85rem 1rem;background:#f8fafc;border-top:2px solid #e2e8f0;}
        .th-num{width:40px;text-align:center;}
        .th-c{text-align:center;}
        .th-r{text-align:right;}
        .td-num{text-align:center;color:#94a3b8;font-size:0.78rem;font-weight:600;font-family:ui-monospace,monospace;}
        .td-date{font-weight:700;color:#0f172a;font-size:0.8rem;}
        .td-time{font-size:0.68rem;color:#94a3b8;margin-top:1px;font-weight:500;}
        .td-kasir{font-weight:700;color:#0f172a;font-size:0.8rem;}
        .td-item-badge{font-size:0.72rem;font-weight:700;color:#4f46e5;background:#eef2ff;padding:2px 8px;border-radius:6px;}
        .td-method{font-size:0.65rem;font-weight:700;padding:3px 8px;border-radius:5px;background:#f1f5f9;color:#475569;letter-spacing:0.03em;}
        .td-amount{font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;font-weight:700;font-size:0.8rem;color:#0f172a;white-space:nowrap;}
        .tf-label{font-weight:800;color:#0f172a;font-size:0.88rem;}
        .tf-amount{font-weight:900;font-size:1rem;color:#16a34a;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;}

        /* ── EMPTY STATE ── */
        .td-empty{text-align:center;padding:3rem 1rem !important;}
        .lp-empty{display:flex;flex-direction:column;align-items:center;}
        .lp-empty-ico{width:56px;height:56px;background:#f1f5f9;border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;}
        .lp-empty-ico svg{color:#94a3b8;}
        .lp-empty-title{font-size:0.9375rem;font-weight:800;color:#64748b;}
        .lp-empty-sub{font-size:0.8rem;color:#94a3b8;margin-top:0.25rem;font-weight:500;}

        /* ── SIDEBAR LISTS ── */
        .lp-list{padding:0;}
        .lp-list-item{display:flex;align-items:center;gap:10px;padding:0.75rem 1.15rem;border-bottom:1px solid #f1f5f9;transition:background .1s;}
        .lp-list-item:last-child{border-bottom:none;}
        .lp-list-item:hover{background:#fafafa;}
        .lp-rank{width:24px;height:24px;border-radius:7px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:800;color:#64748b;flex-shrink:0;}
        .lp-rank-top{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#92400e;}
        .lp-method-ico{width:30px;height:30px;border-radius:8px;background:#eff6ff;color:#3b82f6;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .lp-avatar{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#eef2ff,#e0e7ff);display:flex;align-items:center;justify-content:center;font-weight:800;color:#4f46e5;font-size:0.72rem;flex-shrink:0;border:1px solid #c7d2fe;}
        .lp-list-body{flex:1;min-width:0;}
        .lp-list-name{font-weight:700;font-size:0.8rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .lp-list-meta{font-size:0.68rem;color:#94a3b8;font-weight:500;}
        .lp-list-amount{font-size:0.78rem;font-weight:800;color:#0f172a;white-space:nowrap;flex-shrink:0;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;}
        .lp-list-empty{padding:1.5rem;text-align:center;font-size:0.82rem;color:#94a3b8;font-style:italic;}

        /* ── PAGINATION ── */
        .lp-pagination{padding:0.85rem 1.25rem;border-top:1px solid #f1f5f9;}

        /* ── PRINT STYLES ── */
        @@media print{
            @@page{size:A4 portrait;margin:1cm;}
            body{background:white !important;-webkit-print-color-adjust:exact;print-color-adjust:exact;}
            .lp-page{padding:0;max-width:100%;}
            .lp-no-print{display:none !important;}
            header,nav,.lp-sidebar{display:none !important;}
            .lp-print-header{display:block !important;margin-bottom:16px;}
            .lp-print-brand{text-align:center;border-bottom:2px solid #000;padding-bottom:12px;margin-bottom:12px;}
            .lp-print-brand h1{font-size:18pt;font-weight:900;margin:0;text-transform:uppercase;}
            .lp-print-brand p{font-size:9pt;color:#444;margin:2px 0 0;}
            .lp-print-title{text-align:center;margin-bottom:12px;}
            .lp-print-title h2{font-size:13pt;font-weight:800;margin:0 0 4px;text-transform:uppercase;text-decoration:underline;}
            .lp-print-title p{font-size:9pt;color:#444;margin:0;}
            .lp-print-summary{margin-bottom:16px;font-size:9pt;}
            .lp-print-summary table{width:100%;border-collapse:collapse;}
            .lp-print-summary td{padding:2px 4px;}
            .lp-print-summary td:first-child{font-weight:700;width:160px;}
            .lp-print-summary td:nth-child(2){width:12px;}
            .lp-grid{display:block;}
            .lp-print-table-wrap{border:none;box-shadow:none;}
            .lp-print-table{width:100% !important;border-collapse:collapse;font-size:9pt;}
            .lp-print-table th,.lp-print-table td{border:1px solid #000;padding:4px 6px;color:#000;}
            .lp-print-table th{background:#eee;font-size:8pt;}
            .lp-print-table thead{display:table-header-group;}
            .lp-print-table tfoot{display:table-footer-group;}
            .lp-print-row{page-break-inside:avoid;}
            .lp-print-footer{display:block !important;margin-top:24px;}
            .lp-print-section{margin-bottom:20px;}
            .lp-print-section h3{font-size:11pt;font-weight:800;margin:0 0 8px;border-bottom:1px solid #000;padding-bottom:4px;}
            .lp-print-breakdown{width:100%;border-collapse:collapse;font-size:9pt;}
            .lp-print-breakdown th,.lp-print-breakdown td{border:1px solid #000;padding:4px 8px;}
            .lp-print-breakdown th{background:#eee;font-weight:700;}
            .lp-sign-box{text-align:center;width:220px;margin-left:auto;margin-top:30px;font-size:10pt;}
            .lp-sign-box p{margin:2px 0;}
            .lp-sign-name{font-weight:900;text-decoration:underline;}
        }

        /* ── RESPONSIVE ── */
        @@media(max-width:1024px){
            .lp-stats{grid-template-columns:repeat(2,1fr);}
            .lp-grid{grid-template-columns:1fr;}
            .lp-hero-amount{font-size:2rem;}
        }
        @@media(max-width:768px){
            .lp-filter{flex-direction:column;}
            .lp-fg{width:100%;min-width:100%;}
            .lp-hero{padding:1.5rem 1.25rem 3rem;border-radius:14px;}
            .lp-hero-title{font-size:1.35rem;}
            .lp-hero-amount{font-size:1.75rem;}
            .lp-hero-stats{flex-direction:column;align-items:flex-start;}
            .lp-hero-right{align-items:flex-start;flex-direction:row;gap:12px;}
            .lp-hero-actions{width:100%;}
            .lp-hero-btn{flex:1;justify-content:center;}
        }
        @@media(max-width:480px){
            .lp-stats{grid-template-columns:1fr;}
        }
    </style>
    @endpush
</x-app-layout>
