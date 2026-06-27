<x-app-layout>
    <x-slot name="header">Laporan Pembelian</x-slot>

    {{-- ─── PRINT-ONLY HEADER ─── --}}
    <div class="pb-print-header">
        <div class="pb-print-brand">
            <h1>{{ config('app.name', 'DODPOS') }}</h1>
            <p>Sistem Manajemen Bisnis &amp; Gudang Grosir</p>
        </div>
        <div class="pb-print-title">
            <h2>LAPORAN PEMBELIAN (PURCHASE ORDER)</h2>
            <p>Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</p>
        </div>
        <div class="pb-print-summary">
            <table>
                <tr><td>Total PO</td><td>:</td><td>{{ number_format($totalOrders) }} Dokumen</td></tr>
                <tr><td>Total Nilai PO</td><td>:</td><td>Rp {{ number_format($totalAmount, 0, ',', '.') }}</td></tr>
                <tr><td>Nilai Diterima</td><td>:</td><td>Rp {{ number_format($totalReceived, 0, ',', '.') }}</td></tr>
                <tr><td>Nilai Pending</td><td>:</td><td>Rp {{ number_format($totalPending, 0, ',', '.') }}</td></tr>
            </table>
        </div>
    </div>

    <div class="pb-page">

        {{-- ─── HERO HEADER ─── --}}
        <div class="pb-hero pb-no-print">
            <div class="pb-hero-inner">
                <div class="pb-hero-top">
                    <div>
                        <div class="pb-hero-badge">
                            <span class="pb-hero-dot"></span>
                            Laporan &amp; Analisis
                        </div>
                        <h1 class="pb-hero-title">Ringkasan Pembelian</h1>
                        <p class="pb-hero-sub">Pantau aktivitas Purchase Order berdasarkan periode yang dipilih.</p>
                    </div>
                    <div class="pb-hero-actions">
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'xlsx', 'page' => null]) }}" class="pb-hero-btn pb-hero-btn-green">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Export Excel
                        </a>
                        <button type="button" onclick="window.print()" class="pb-hero-btn pb-hero-btn-blue">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                            Cetak
                        </button>
                    </div>
                </div>
                <div class="pb-hero-stats">
                    <div>
                        <div class="pb-hero-label">Total Nilai PO</div>
                        <div class="pb-hero-amount">
                            <span class="pb-hero-rp">Rp</span>{{ number_format($totalAmount, 0, ',', '.') }}
                        </div>
                        <div class="pb-hero-chip">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ \Carbon\Carbon::parse($dateFrom)->format('d M') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
                        </div>
                    </div>
                    <div class="pb-hero-right">
                        <div>
                            <div class="pb-hero-count">{{ number_format($totalOrders) }}</div>
                            <div class="pb-hero-count-label">Total Dokumen PO</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── STAT CARDS ─── --}}
        <div class="pb-stats pb-no-print">
            <div class="pb-stat pb-stat-indigo">
                <div class="pb-stat-top">
                    <div class="pb-stat-ico pb-ic-indigo">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <span class="pb-stat-tag pb-tag-indigo">Total</span>
                </div>
                <div class="pb-stat-lbl">Total Nilai PO</div>
                <div class="pb-stat-val pb-val-indigo">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
            </div>
            <div class="pb-stat pb-stat-emerald">
                <div class="pb-stat-top">
                    <div class="pb-stat-ico pb-ic-emerald">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span class="pb-stat-tag pb-tag-emerald">Diterima</span>
                </div>
                <div class="pb-stat-lbl">Nilai Diterima Penuh</div>
                <div class="pb-stat-val pb-val-emerald">Rp {{ number_format($totalReceived, 0, ',', '.') }}</div>
            </div>
            <div class="pb-stat pb-stat-amber">
                <div class="pb-stat-top">
                    <div class="pb-stat-ico pb-ic-amber">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <span class="pb-stat-tag pb-tag-amber">Pending</span>
                </div>
                <div class="pb-stat-lbl">Estimasi Nilai Pending</div>
                <div class="pb-stat-val pb-val-amber">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
            </div>
            <div class="pb-stat pb-stat-blue">
                <div class="pb-stat-top">
                    <div class="pb-stat-ico pb-ic-blue">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <span class="pb-stat-tag pb-tag-blue">Dokumen</span>
                </div>
                <div class="pb-stat-lbl">Total Dokumen PO</div>
                <div class="pb-stat-val pb-val-blue">{{ number_format($totalOrders) }} Nota</div>
            </div>
        </div>

        {{-- ─── FILTER CARD ─── --}}
        <div class="pb-card pb-no-print">
            <form method="GET" class="pb-filter">
                <div class="pb-fg">
                    <label class="pb-fl">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="pb-fi">
                </div>
                <div class="pb-fg">
                    <label class="pb-fl">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="pb-fi">
                </div>
                <div class="pb-fg">
                    <label class="pb-fl">Supplier</label>
                    <select name="supplier_id" class="pb-fi">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" @selected(request('supplier_id') == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pb-fg">
                    <label class="pb-fl">Status</label>
                    <select name="status" class="pb-fi">
                        <option value="">Semua Status</option>
                        <option value="draft" @selected(request('status') == 'draft')>Draft</option>
                        <option value="ordered" @selected(request('status') == 'ordered')>Dipesan</option>
                        <option value="partial" @selected(request('status') == 'partial')>Diterima Sebagian</option>
                        <option value="received" @selected(request('status') == 'received')>Diterima Penuh</option>
                        <option value="cancelled" @selected(request('status') == 'cancelled')>Dibatalkan</option>
                    </select>
                </div>
                <div class="pb-filter-btns">
                    <button type="submit" class="pb-btn pb-btn-dark">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('laporan.pembelian') }}" class="pb-btn pb-btn-ghost">Reset</a>
                </div>
            </form>
        </div>

        {{-- ─── MAIN CONTENT GRID ─── --}}
        <div class="pb-grid">

            {{-- LEFT: PO Table --}}
            <div class="pb-main">
                <div class="pb-card pb-print-table-wrap">
                    <div class="pb-card-head pb-no-print">
                        <div>
                            <h3 class="pb-card-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                                Daftar Purchase Order
                            </h3>
                            <div class="pb-card-sub">Menampilkan {{ $orders->total() }} dokumen PO</div>
                        </div>
                        <span class="pb-card-count">{{ $orders->total() }} Data</span>
                    </div>
                    <div class="pb-table-scroll">
                        <table class="pb-table pb-print-table">
                            <thead>
                                <tr>
                                    <th class="th-num">#</th>
                                    <th>No. PO</th>
                                    <th>Supplier</th>
                                    <th>Tanggal</th>
                                    <th class="th-c">Item</th>
                                    <th class="th-c">Status</th>
                                    <th class="th-r">Total (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $i => $order)
                                    @php $s = $order->status_label; @endphp
                                    <tr class="pb-print-row {{ $order->status === 'cancelled' ? 'pb-cancelled' : '' }}">
                                        <td class="td-num">{{ $orders->firstItem() + $i }}</td>
                                        <td>
                                            <a href="{{ route('pembelian.order.show', $order) }}" class="td-po-link">
                                                {{ $order->po_number }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="td-supplier">{{ $order->supplier->name }}</span>
                                        </td>
                                        <td>
                                            <div class="td-date">{{ $order->order_date->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="th-c">
                                            <span class="td-item-badge">{{ $order->items_count }}</span>
                                        </td>
                                        <td class="th-c">
                                            <span class="td-status" style="background:{{ $s['bg'] }};color:{{ $s['color'] }};">
                                                {{ $s['label'] }}
                                            </span>
                                        </td>
                                        <td class="th-r">
                                            <span class="td-amount">{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="td-empty">
                                            <div class="pb-empty">
                                                <div class="pb-empty-ico">
                                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                </div>
                                                <div class="pb-empty-title">Tidak Ada Data</div>
                                                <div class="pb-empty-sub">Tidak ada data pembelian pada periode ini.</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($orders->count() > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="th-r tf-label">Total Keseluruhan</td>
                                        <td class="th-r tf-amount">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    @if($orders->hasPages())
                        <div class="pb-pagination pb-no-print">{{ $orders->links() }}</div>
                    @endif
                </div>
            </div>

            {{-- RIGHT: Sidebar Panels --}}
            <div class="pb-sidebar pb-no-print">

                {{-- Top Suppliers --}}
                <div class="pb-card">
                    <div class="pb-card-head">
                        <h3 class="pb-card-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            Top Supplier
                        </h3>
                        <span class="pb-card-tag">Nilai PO</span>
                    </div>
                    <div class="pb-list">
                        @forelse ($bySupplier as $i => $sup)
                            <div class="pb-list-item">
                                <div class="pb-rank {{ $i < 3 ? 'pb-rank-top' : '' }}">{{ $i + 1 }}</div>
                                <div class="pb-list-body">
                                    <div class="pb-list-name" title="{{ $sup['name'] }}">{{ $sup['name'] }}</div>
                                    <div class="pb-list-meta">{{ $sup['count'] }} PO</div>
                                </div>
                                <div class="pb-list-amount">
                                    Rp {{ number_format($sup['amount'], 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <div class="pb-list-empty">Belum ada data.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Status Breakdown --}}
                <div class="pb-card">
                    <div class="pb-card-head">
                        <h3 class="pb-card-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Sebaran Status
                        </h3>
                    </div>
                    <div class="pb-list">
                        @php
                            $statusList = [
                                ['status' => 'received', 'label' => 'Diterima Penuh', 'color' => '#16a34a', 'bg' => '#dcfce7'],
                                ['status' => 'ordered', 'label' => 'Sedang Dipesan', 'color' => '#2563eb', 'bg' => '#dbeafe'],
                                ['status' => 'partial', 'label' => 'Diterima Sebagian', 'color' => '#d97706', 'bg' => '#fef3c7'],
                                ['status' => 'draft', 'label' => 'Masih Draft', 'color' => '#64748b', 'bg' => '#f1f5f9'],
                                ['status' => 'cancelled', 'label' => 'Dibatalkan', 'color' => '#dc2626', 'bg' => '#fee2e2'],
                            ];
                            $hasStatusData = false;
                        @endphp

                        @foreach($statusList as $st)
                            @php $cnt = $statusCounts[$st['status']] ?? 0; @endphp
                            @if($cnt > 0)
                                @php $hasStatusData = true; @endphp
                                <div class="pb-list-item">
                                    <div class="pb-status-dot" style="background:{{ $st['color'] }};"></div>
                                    <div class="pb-list-body">
                                        <div class="pb-list-name">{{ $st['label'] }}</div>
                                    </div>
                                    <div class="pb-status-count">
                                        {{ $cnt }} <span class="pb-status-unit">Nota</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if(!$hasStatusData)
                            <div class="pb-list-empty">Belum ada data.</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ─── PRINT-ONLY FOOTER ─── --}}
    <div class="pb-print-footer">
        <div class="pb-print-section">
            <h3>Ringkasan Per Status</h3>
            <table class="pb-print-breakdown">
                <thead>
                    <tr><th>Status</th><th>Jumlah</th></tr>
                </thead>
                <tbody>
                    @foreach($statusList as $st)
                        @php $cnt = $statusCounts[$st['status']] ?? 0; @endphp
                        @if($cnt > 0)
                            <tr>
                                <td>{{ $st['label'] }}</td>
                                <td>{{ $cnt }} PO</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pb-sign-box">
            <p>Medan, {{ now()->format('d F Y') }}</p>
            <p>Mengetahui,</p>
            <br><br><br>
            <p class="pb-sign-name">{{ auth()->user()->name ?? 'Administrator' }}</p>
            <p>Admin DodPOS</p>
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        .pb-page{max-width:1280px;margin:0 auto;padding:0 0 3rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:#0f172a;}
        .pb-print-header,.pb-print-footer{display:none;}

        /* ── HERO HEADER ── */
        .pb-hero{background:linear-gradient(135deg,#06090f 0%,#0d1322 35%,#111827 70%,#0a0e1a 100%);border-radius:20px;padding:2rem 2.25rem 3.5rem;margin-bottom:-2rem;position:relative;overflow:hidden;}
        .pb-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 75% 25%,rgba(99,102,241,0.18) 0%,transparent 60%),radial-gradient(ellipse at 20% 75%,rgba(16,185,129,0.08) 0%,transparent 50%);}
        .pb-hero::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,rgba(99,102,241,0.5),rgba(16,185,129,0.3),transparent);}
        .pb-hero-inner{position:relative;z-index:1;}
        .pb-hero-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
        .pb-hero-badge{display:inline-flex;align-items:center;gap:0.5rem;background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.3);padding:0.3rem 0.875rem;border-radius:99px;font-size:0.65rem;font-weight:700;color:rgba(165,180,252,0.9);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.75rem;}
        .pb-hero-dot{width:6px;height:6px;border-radius:50%;background:#818cf8;animation:pb-pulse 2s infinite;}
        @keyframes pb-pulse{0%,100%{opacity:1}50%{opacity:0.4}}
        .pb-hero-title{font-size:1.75rem;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.1;margin:0 0 0.35rem;}
        .pb-hero-sub{font-size:0.8125rem;color:rgba(255,255,255,0.4);margin:0;}
        .pb-hero-actions{display:flex;gap:0.5rem;align-items:center;}
        .pb-hero-btn{display:inline-flex;align-items:center;gap:0.4rem;padding:0.6rem 1.15rem;border-radius:10px;font-size:0.8rem;font-weight:700;cursor:pointer;transition:all .2s;border:none;text-decoration:none;white-space:nowrap;font-family:inherit;}
        .pb-hero-btn svg{width:15px;height:15px;}
        .pb-hero-btn-green{background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;box-shadow:0 4px 14px rgba(22,163,74,0.3);}
        .pb-hero-btn-green:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(22,163,74,0.4);}
        .pb-hero-btn-blue{background:linear-gradient(135deg,#3b82f6,#60a5fa);color:#fff;box-shadow:0 4px 14px rgba(59,130,246,0.3);}
        .pb-hero-btn-blue:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(59,130,246,0.4);}
        .pb-hero-stats{display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1.25rem;}
        .pb-hero-label{font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.12em;margin-bottom:0.5rem;}
        .pb-hero-amount{font-size:2.5rem;font-weight:900;color:#fff;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;letter-spacing:-0.03em;line-height:1;}
        .pb-hero-rp{font-size:1rem;opacity:0.45;margin-right:3px;font-weight:700;}
        .pb-hero-chip{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.08);padding:4px 12px;border-radius:7px;font-size:0.68rem;font-weight:600;color:rgba(255,255,255,0.7);margin-top:0.75rem;}
        .pb-hero-right{display:flex;flex-direction:column;align-items:flex-end;gap:8px;padding-bottom:4px;}
        .pb-hero-count{font-size:2rem;font-weight:900;color:rgba(255,255,255,0.9);font-family:ui-monospace,monospace;line-height:1;}
        .pb-hero-count-label{font-size:0.68rem;font-weight:600;color:rgba(255,255,255,0.35);text-transform:uppercase;letter-spacing:0.08em;}

        /* ── STAT CARDS ── */
        .pb-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:0.875rem;margin-bottom:1.5rem;position:relative;z-index:2;}
        .pb-stat{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:1.15rem 1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.04);transition:all .2s;position:relative;overflow:hidden;}
        .pb-stat:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,0.06);}
        .pb-stat::before{content:'';position:absolute;top:0;right:0;width:60px;height:60px;border-radius:50%;opacity:0.06;transform:translate(15px,-15px);}
        .pb-stat-indigo::before{background:#4f46e5;}
        .pb-stat-emerald::before{background:#059669;}
        .pb-stat-amber::before{background:#d97706;}
        .pb-stat-blue::before{background:#3b82f6;}
        .pb-stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;}
        .pb-stat-ico{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;}
        .pb-ic-indigo{background:#eef2ff;color:#4f46e5;}
        .pb-ic-emerald{background:#ecfdf5;color:#059669;}
        .pb-ic-amber{background:#fffbeb;color:#d97706;}
        .pb-ic-blue{background:#eff6ff;color:#3b82f6;}
        .pb-stat-tag{font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;padding:3px 8px;border-radius:99px;}
        .pb-tag-indigo{background:#eef2ff;color:#4338ca;}
        .pb-tag-emerald{background:#ecfdf5;color:#065f46;}
        .pb-tag-amber{background:#fffbeb;color:#92400e;}
        .pb-tag-blue{background:#eff6ff;color:#1e40af;}
        .pb-stat-lbl{font-size:0.68rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.04em;}
        .pb-stat-val{font-size:1.15rem;font-weight:900;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;letter-spacing:-0.02em;margin-top:0.2rem;}
        .pb-val-indigo{color:#4f46e5;}
        .pb-val-emerald{color:#059669;}
        .pb-val-amber{color:#d97706;}
        .pb-val-blue{color:#3b82f6;}

        /* ── CARD ── */
        .pb-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,0.04);overflow:hidden;margin-bottom:1rem;}
        .pb-card-head{padding:1rem 1.25rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem;border-bottom:1px solid #f1f5f9;background:linear-gradient(180deg,#fafbfc,#fff);}
        .pb-card-title{font-size:0.875rem;font-weight:800;margin:0;display:flex;align-items:center;gap:8px;color:#0f172a;}
        .pb-card-title svg{color:#94a3b8;}
        .pb-card-sub{font-size:0.72rem;color:#64748b;margin-top:2px;font-weight:500;}
        .pb-card-count{font-size:0.72rem;font-weight:700;color:#4f46e5;background:#e0e7ff;padding:3px 10px;border-radius:99px;}
        .pb-card-tag{font-size:0.65rem;font-weight:700;color:#d97706;background:#fef3c7;padding:3px 8px;border-radius:99px;}

        /* ── FILTER ── */
        .pb-filter{display:flex;flex-wrap:wrap;gap:0.65rem;padding:1rem 1.25rem;align-items:flex-end;background:#f8fafc;}
        .pb-fg{display:flex;flex-direction:column;gap:0.25rem;min-width:130px;flex:1;}
        .pb-fl{font-size:0.62rem;font-weight:700;text-transform:uppercase;color:#94a3b8;letter-spacing:0.06em;}
        .pb-fi{padding:0.5rem 0.75rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.8rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;transition:all .15s;background:#fff;color:#0f172a;font-weight:500;}
        .pb-fi:focus{outline:none;border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,0.1);}
        select.pb-fi{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 0.6rem center;padding-right:1.75rem;cursor:pointer;}
        .pb-filter-btns{display:flex;gap:0.4rem;align-self:flex-end;}
        .pb-btn{display:inline-flex;align-items:center;gap:0.35rem;padding:0.5rem 0.875rem;border-radius:8px;font-size:0.78rem;font-weight:700;cursor:pointer;transition:all .15s;border:none;text-decoration:none;white-space:nowrap;font-family:inherit;}
        .pb-btn svg{width:14px;height:14px;}
        .pb-btn-dark{background:#0f172a;color:#fff;}
        .pb-btn-dark:hover{background:#1e293b;}
        .pb-btn-ghost{background:transparent;color:#64748b;border:1.5px solid #e2e8f0;}
        .pb-btn-ghost:hover{background:#f1f5f9;color:#0f172a;border-color:#94a3b8;}

        /* ── GRID LAYOUT ── */
        .pb-grid{display:grid;grid-template-columns:1fr 340px;gap:1.25rem;align-items:start;}
        .pb-main{min-width:0;}

        /* ── TABLE ── */
        .pb-table-scroll{width:100%;overflow-x:auto;}
        .pb-table{width:100%;border-collapse:collapse;min-width:700px;}
        .pb-table thead th{background:linear-gradient(180deg,#f8fafc,#f4f8fc);padding:0.75rem 1rem;text-align:left;font-size:0.62rem;font-weight:700;text-transform:uppercase;color:#94a3b8;border-bottom:2px solid #e2e8f0;letter-spacing:0.04em;}
        .pb-table tbody td{padding:0.8rem 1rem;border-bottom:1px solid #f1f5f9;font-size:0.8125rem;vertical-align:middle;}
        .pb-table tbody tr:last-child td{border-bottom:none;}
        .pb-table tbody tr{transition:background .15s;}
        .pb-table tbody tr:hover{background:#f8fafc;}
        .pb-table tfoot td{padding:0.85rem 1rem;background:#f8fafc;border-top:2px solid #e2e8f0;}
        .th-num{width:40px;text-align:center;}
        .th-c{text-align:center;}
        .th-r{text-align:right;}
        .td-num{text-align:center;color:#94a3b8;font-size:0.78rem;font-weight:600;font-family:ui-monospace,monospace;}
        .td-po-link{font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;font-weight:700;font-size:0.8rem;color:#4f46e5;text-decoration:none;}
        .td-po-link:hover{text-decoration:underline;}
        .td-supplier{font-weight:700;color:#0f172a;font-size:0.8rem;}
        .td-date{font-weight:600;color:#0f172a;font-size:0.8rem;}
        .td-item-badge{font-size:0.72rem;font-weight:700;color:#4f46e5;background:#eef2ff;padding:2px 8px;border-radius:6px;}
        .td-status{font-size:0.65rem;font-weight:700;padding:3px 8px;border-radius:5px;letter-spacing:0.03em;text-transform:uppercase;}
        .td-amount{font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;font-weight:700;font-size:0.8rem;color:#0f172a;white-space:nowrap;}
        .tf-label{font-weight:800;color:#0f172a;font-size:0.88rem;}
        .tf-amount{font-weight:900;font-size:1rem;color:#4f46e5;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;}
        .pb-cancelled td{opacity:0.45;background:#fafafa;}
        .pb-cancelled .td-po-link{text-decoration:line-through;}

        /* ── EMPTY STATE ── */
        .td-empty{text-align:center;padding:3rem 1rem !important;}
        .pb-empty{display:flex;flex-direction:column;align-items:center;}
        .pb-empty-ico{width:56px;height:56px;background:#f1f5f9;border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;}
        .pb-empty-ico svg{color:#94a3b8;}
        .pb-empty-title{font-size:0.9375rem;font-weight:800;color:#64748b;}
        .pb-empty-sub{font-size:0.8rem;color:#94a3b8;margin-top:0.25rem;font-weight:500;}

        /* ── SIDEBAR LISTS ── */
        .pb-list{padding:0;}
        .pb-list-item{display:flex;align-items:center;gap:10px;padding:0.75rem 1.15rem;border-bottom:1px solid #f1f5f9;transition:background .1s;}
        .pb-list-item:last-child{border-bottom:none;}
        .pb-list-item:hover{background:#fafafa;}
        .pb-rank{width:24px;height:24px;border-radius:7px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:800;color:#64748b;flex-shrink:0;}
        .pb-rank-top{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#92400e;}
        .pb-list-body{flex:1;min-width:0;}
        .pb-list-name{font-weight:700;font-size:0.8rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .pb-list-meta{font-size:0.68rem;color:#94a3b8;font-weight:500;}
        .pb-list-amount{font-size:0.78rem;font-weight:800;color:#0f172a;white-space:nowrap;flex-shrink:0;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;}
        .pb-list-empty{padding:1.5rem;text-align:center;font-size:0.82rem;color:#94a3b8;font-style:italic;}
        .pb-status-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;}
        .pb-status-count{font-size:0.88rem;font-weight:800;color:#0f172a;white-space:nowrap;}
        .pb-status-unit{font-size:0.68rem;font-weight:500;color:#94a3b8;}

        /* ── PAGINATION ── */
        .pb-pagination{padding:0.85rem 1.25rem;border-top:1px solid #f1f5f9;}

        /* ── PRINT STYLES ── */
        @@media print{
            @@page{size:A4 landscape;margin:1cm;}
            body{background:white !important;-webkit-print-color-adjust:exact;print-color-adjust:exact;}
            .pb-page{padding:0;max-width:100%;}
            .pb-no-print{display:none !important;}
            header,nav,.pb-sidebar{display:none !important;}
            .pb-print-header{display:block !important;margin-bottom:16px;}
            .pb-print-brand{text-align:center;border-bottom:2px solid #000;padding-bottom:12px;margin-bottom:12px;}
            .pb-print-brand h1{font-size:18pt;font-weight:900;margin:0;text-transform:uppercase;}
            .pb-print-brand p{font-size:9pt;color:#444;margin:2px 0 0;}
            .pb-print-title{text-align:center;margin-bottom:12px;}
            .pb-print-title h2{font-size:13pt;font-weight:800;margin:0 0 4px;text-transform:uppercase;text-decoration:underline;}
            .pb-print-title p{font-size:9pt;color:#444;margin:0;}
            .pb-print-summary{margin-bottom:16px;font-size:9pt;}
            .pb-print-summary table{width:100%;border-collapse:collapse;}
            .pb-print-summary td{padding:2px 4px;}
            .pb-print-summary td:first-child{font-weight:700;width:160px;}
            .pb-print-summary td:nth-child(2){width:12px;}
            .pb-grid{display:block;}
            .pb-print-table-wrap{border:none;box-shadow:none;}
            .pb-print-table{width:100% !important;border-collapse:collapse;font-size:9pt;}
            .pb-print-table th,.pb-print-table td{border:1px solid #000;padding:4px 6px;color:#000;}
            .pb-print-table th{background:#eee;font-size:8pt;}
            .pb-print-table thead{display:table-header-group;}
            .pb-print-table tfoot{display:table-footer-group;}
            .pb-print-row{page-break-inside:avoid;}
            .pb-print-footer{display:block !important;margin-top:24px;}
            .pb-print-section{margin-bottom:20px;}
            .pb-print-section h3{font-size:11pt;font-weight:800;margin:0 0 8px;border-bottom:1px solid #000;padding-bottom:4px;}
            .pb-print-breakdown{width:100%;border-collapse:collapse;font-size:9pt;}
            .pb-print-breakdown th,.pb-print-breakdown td{border:1px solid #000;padding:4px 8px;}
            .pb-print-breakdown th{background:#eee;font-weight:700;}
            .pb-sign-box{text-align:center;width:220px;margin-left:auto;margin-top:30px;font-size:10pt;}
            .pb-sign-box p{margin:2px 0;}
            .pb-sign-name{font-weight:900;text-decoration:underline;}
        }

        /* ── RESPONSIVE ── */
        @@media(max-width:1024px){
            .pb-stats{grid-template-columns:repeat(2,1fr);}
            .pb-grid{grid-template-columns:1fr;}
            .pb-hero-amount{font-size:2rem;}
        }
        @@media(max-width:768px){
            .pb-filter{flex-direction:column;}
            .pb-fg{width:100%;min-width:100%;}
            .pb-hero{padding:1.5rem 1.25rem 3rem;border-radius:14px;}
            .pb-hero-title{font-size:1.35rem;}
            .pb-hero-amount{font-size:1.75rem;}
            .pb-hero-stats{flex-direction:column;align-items:flex-start;}
            .pb-hero-right{align-items:flex-start;flex-direction:row;gap:12px;}
            .pb-hero-actions{width:100%;}
            .pb-hero-btn{flex:1;justify-content:center;}
        }
        @@media(max-width:480px){
            .pb-stats{grid-template-columns:1fr;}
        }
    </style>
    @endpush
</x-app-layout>
