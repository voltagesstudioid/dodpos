<x-app-layout>
    <x-slot name="header">Barang Masuk & Keluar</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER & QUICK ACTIONS --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Dashboard Logistik</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-slate">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"></path></svg>
                        </div>
                        Barang Masuk & Keluar
                    </h1>
                    <p class="tr-subtitle">Ringkasan pergerakan masuk (inbound) dan keluar (outbound) serta peringatan stok.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.penerimaan.create') }}" class="tr-btn tr-btn-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Barang Masuk
                    </a>
                    <a href="{{ route('gudang.pengeluaran.create') }}" class="tr-btn tr-btn-danger">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Barang Keluar
                    </a>
                    <a href="{{ route('gudang.transfer') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 3h5v5"></path><path d="M4 20L21 3"></path><path d="M21 16v5h-5"></path><path d="M15 15l6 6"></path><path d="M4 4l5 5"></path></svg>
                        Transfer Stok
                    </a>
                </div>
            </div>

            {{-- PENDING PO ALERT --}}
            @if(isset($pendingPos) && $pendingPos->count())
                <div class="tr-alert-banner">
                    <div class="tr-alert-banner-header">
                        <div>
                            <h3 class="tr-alert-title">PO Menunggu Penerimaan</h3>
                            <p class="tr-alert-subtitle">Daftar Purchase Order terbaru yang belum diterima fisiknya (Maks. 5).</p>
                        </div>
                        <a href="{{ route('gudang.terimapo.index') }}" class="tr-btn tr-btn-primary tr-btn-sm">Buka Daftar PO</a>
                    </div>
                    <div class="tr-alert-grid">
                        @foreach($pendingPos as $po)
                            <a href="{{ route('gudang.terimapo.show', $po) }}" class="tr-po-card">
                                <div>
                                    <div class="tr-po-number">{{ $po->po_number }}</div>
                                    <div class="tr-po-supplier">{{ $po->supplier?->name ?? 'Supplier Umum' }}</div>
                                </div>
                                <div class="r">
                                    <div class="tr-po-date">{{ $po->order_date?->format('d M Y') }}</div>
                                    <div class="tr-po-status">{{ strtoupper($po->status) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- FILTER BAR --}}
            <div class="tr-card" style="margin-bottom: 1.5rem;">
                <div class="tr-filter-bar">
                    <form method="GET" class="tr-filter-form">
                        <div class="tr-form-group">
                            <label class="tr-label">Dari Tanggal</label>
                            <input type="date" name="date_from" value="{{ $dateFrom }}" class="tr-input">
                        </div>
                        <div class="tr-form-group">
                            <label class="tr-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" value="{{ $dateTo }}" class="tr-input">
                        </div>
                        <div class="tr-form-group">
                            <label class="tr-label">Pilih Gudang</label>
                            <div class="tr-select-wrapper">
                                <select name="warehouse_id" class="tr-select">
                                    <option value="">Semua Gudang</option>
                                    @foreach(($warehouses ?? []) as $wh)
                                        <option value="{{ $wh->id }}" @selected(($warehouseId ?? '') == $wh->id)>{{ $wh->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="tr-form-group" style="flex: 1; min-width: 220px;">
                            <label class="tr-label">Pencarian</label>
                            <div class="tr-search">
                                <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="No. Ref / Nama / SKU">
                            </div>
                        </div>
                        <div class="tr-filter-actions">
                            <button type="submit" class="tr-btn tr-btn-dark">Filter</button>
                            @if($dateFrom || $dateTo || $search)
                                <a href="{{ route('gudang.inout') }}" class="tr-btn tr-btn-danger-outline">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- STATS GRID --}}
            <div class="tr-stats-grid">
                <div class="tr-stat-card border-green">
                    <div class="tr-stat-icon bg-green">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
                    </div>
                    <div>
                        <div class="tr-stat-label">Barang Masuk (Periode)</div>
                        <div class="tr-stat-value text-green">{{ number_format($inTotal, 0, ',', '.') }} <span class="tr-stat-unit">Unit</span></div>
                    </div>
                </div>
                <div class="tr-stat-card border-red">
                    <div class="tr-stat-icon bg-red">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                    </div>
                    <div>
                        <div class="tr-stat-label">Barang Keluar (Periode)</div>
                        <div class="tr-stat-value text-red">{{ number_format($outTotal, 0, ',', '.') }} <span class="tr-stat-unit">Unit</span></div>
                    </div>
                </div>
                <div class="tr-stat-card border-warning">
                    <div class="tr-stat-icon bg-warning">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    </div>
                    <div style="flex:1;">
                        <div class="tr-stat-label">Di Bawah Min. Stok</div>
                        <div class="tr-stat-value text-warning">{{ $lowStockCount ?? 0 }} <span class="tr-stat-unit">SKU</span></div>
                    </div>
                    <a href="{{ route('gudang.minstok') }}" class="tr-stat-link">Lihat</a>
                </div>
                <div class="tr-stat-card border-slate">
                    <div class="tr-stat-icon bg-slate">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div style="flex:1;">
                        <div class="tr-stat-label">Kadaluarsa & Near ED</div>
                        <div class="tr-stat-value text-slate">{{ ($nearExpiredCount ?? 0) + ($expiredCount ?? 0) }} <span class="tr-stat-unit">Batch</span></div>
                    </div>
                    <a href="{{ route('gudang.expired') }}" class="tr-stat-link">Detail</a>
                </div>
            </div>

            {{-- TWO COLUMN GRID: IN & OUT TABLES --}}
            <div class="tr-grid-2">
                
                {{-- BARANG MASUK --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <div>
                            <h2 class="tr-card-title">Histori Barang Masuk</h2>
                            <p class="tr-card-subtitle">Pergerakan Inbound</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="tr-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produk & Ref</th>
                                    <th class="c">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ins as $m)
                                <tr>
                                    <td>
                                        <div class="tr-date-main">{{ $m->created_at->format('d M Y') }}</div>
                                        <div class="tr-date-sub">{{ $m->created_at->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="tr-prod-name">{{ $m->product?->name ?? '-' }}</div>
                                        <div class="tr-prod-meta">
                                            {{ $m->warehouse?->name ?? '-' }} <span class="tr-dot-divider">•</span> <span class="tr-font-mono">{{ $m->reference_number ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="c">
                                        <span class="tr-qty-badge tr-bg-green-soft text-green">+{{ $m->quantity }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3"><div class="tr-empty-compact">Belum ada barang masuk</div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($ins->hasPages()) <div class="tr-pagination">{{ $ins->links() }}</div> @endif
                </div>

                {{-- BARANG KELUAR --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <div>
                            <h2 class="tr-card-title">Histori Barang Keluar</h2>
                            <p class="tr-card-subtitle">Pergerakan Outbound</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="tr-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produk & Ref</th>
                                    <th class="c">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($outs as $m)
                                <tr>
                                    <td>
                                        <div class="tr-date-main">{{ $m->created_at->format('d M Y') }}</div>
                                        <div class="tr-date-sub">{{ $m->created_at->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="tr-prod-name">{{ $m->product?->name ?? '-' }}</div>
                                        <div class="tr-prod-meta">
                                            {{ $m->warehouse?->name ?? '-' }} <span class="tr-dot-divider">•</span> <span class="tr-font-mono">{{ $m->reference_number ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="c">
                                        <span class="tr-qty-badge tr-bg-red-soft text-red">−{{ $m->quantity }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3"><div class="tr-empty-compact">Belum ada barang keluar</div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($outs->hasPages()) <div class="tr-pagination">{{ $outs->links() }}</div> @endif
                </div>
            </div>

            {{-- MINIMUM STOCK ALERT & REORDER --}}
            <div class="tr-card" style="margin-top: 1.5rem;">
                <div class="tr-card-header" style="flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <h2 class="tr-card-title">Peringatan Minimum Stok & Saran Reorder</h2>
                        <p class="tr-card-subtitle">Item: {{ $reorderSummary['count'] ?? 0 }} • Total Qty: {{ $reorderSummary['total_qty'] ?? 0 }} • Estimasi: Rp {{ number_format($reorderSummary['total_value'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    @php
                        $qs = '';
                        if (($reorderSuggestions ?? collect())->isNotEmpty()) {
                            $parts = [];
                            foreach($reorderSuggestions as $pp) {
                                $suggest = max(($pp->min_stock ?? 0) - ($pp->stock ?? 0), 0);
                                if ($suggest > 0) $parts[] = 'add[]='.$pp->id.'&qty[]='.$suggest;
                            }
                            $qs = implode('&', $parts);
                        }
                    @endphp
                    @if(!empty($qs))
                        <div class="tr-header-actions">
                            <a href="{{ route('pembelian.order.create') }}?payment_term=credit&due_date={{ now()->addDays(30)->format('Y-m-d') }}&{{ $qs }}" class="tr-btn tr-btn-primary tr-btn-sm">+ Draft PO Baru</a>
                            @if(isset($lastDraftPo) && $lastDraftPo)
                                <a href="{{ route('pembelian.order.append_items', $lastDraftPo) }}?{{ $qs }}" class="tr-btn tr-btn-outline tr-btn-sm">⇢ Masukkan ke PO {{ $lastDraftPo->po_number }}</a>
                            @endif
                        </div>
                    @endif
                </div>
                
                <div class="table-responsive">
                    @if(($lowStockProducts ?? collect())->isEmpty())
                        <div class="tr-empty-compact" style="padding: 3rem;">Stok gudang terpantau aman. Tidak ada produk di bawah batas minimum.</div>
                    @else
                        <form id="reorder-select-form" action="#" onsubmit="return false;">
                        <table class="tr-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align:center;">
                                        <input type="checkbox" id="reorder-check-all" class="tr-checkbox" onclick="document.querySelectorAll('.reorder-check').forEach(cb=>cb.checked=this.checked);">
                                    </th>
                                    <th>Produk & Kategori</th>
                                    <th class="c">Batas Min</th>
                                    <th class="c">Sisa Aktual</th>
                                    <th class="r text-primary">Saran Order</th>
                                    <th class="r">Estimasi Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $p)
                                @php $suggest = max(($p->min_stock ?? 0) - ($p->stock ?? 0), 0); @endphp
                                <tr class="tr-row-warning">
                                    <td class="c">
                                        <input type="checkbox" class="tr-checkbox reorder-check" value="{{ $p->id }}||{{ $suggest }}||{{ (float) ($p->purchase_price ?? 0) }}">
                                    </td>
                                    <td>
                                        <div class="tr-prod-name">{{ $p->name }}</div>
                                        <div class="tr-prod-meta">{{ $p->category?->name ?? '-' }} <span class="tr-dot-divider">•</span> <span class="tr-font-mono">{{ $p->sku }}</span></div>
                                    </td>
                                    <td class="c tr-font-bold">{{ $p->min_stock }}</td>
                                    <td class="c tr-font-bold text-red">{{ $p->stock }}</td>
                                    <td class="r tr-font-bold text-primary">+{{ $suggest }}</td>
                                    <td class="r">Rp {{ number_format($suggest * (float) ($p->purchase_price ?? 0), 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        {{-- PO ACTION BAR BOTTOM --}}
                        <div class="tr-po-action-bar">
                            <div id="reorder-selected-summary" class="tr-po-summary">0 item dipilih • Qty 0 • Est. Rp 0</div>
                            <div class="tr-header-actions">
                                @if(($draftPos ?? collect())->isNotEmpty())
                                    <div class="tr-select-wrapper" style="min-width: 200px;">
                                        <select id="draft-po-select" class="tr-select tr-select-sm">
                                            <option value="">-- Pilih PO Draft --</option>
                                            @foreach($draftPos as $po)
                                                <option value="{{ $po->id }}">{{ $po->po_number }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="tr-btn tr-btn-outline tr-btn-sm" data-qs="{{ $qs }}" id="btn-append-to-selected-po">Sisipkan ke PO Dipilih</button>
                                @endif
                                <button type="button" class="tr-btn tr-btn-primary tr-btn-sm" id="btn-reorder-to-new-po">Buat PO Baru (Item Terpilih)</button>
                            </div>
                        </div>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        (function(){
            function format(n){
                var s = String(Math.round(Number(n) || 0));
                var out = ''; var i = s.length; var c = 0;
                while (i--) {
                    out = s[i] + out; c++;
                    if (c === 3 && i > 0) { out = '.' + out; c = 0; }
                }
                return out || '0';
            }
            function updateSummary(){
                var sel = document.querySelectorAll('.reorder-check:checked');
                var items = 0, qty = 0, val = 0;
                sel.forEach(function(cb){
                    var parts = (cb.value||'').split('||');
                    if(parts.length>=3){
                        var q = parseFloat(parts[1]||0);
                        var price = parseFloat(parts[2]||0);
                        items += 1;
                        qty += (isFinite(q)? q : 0);
                        val += (isFinite(q*price)? q*price : 0);
                    }
                });
                var el = document.getElementById('reorder-selected-summary');
                if(el){ el.innerHTML = `<strong>${items}</strong> item dipilih <span class="tr-dot-divider">•</span> Qty <strong>${qty}</strong> <span class="tr-dot-divider">•</span> Est. <strong>Rp ${format(val)}</strong>`; }
            }
            function buildQueryFromChecks(){
                var sel = document.querySelectorAll('.reorder-check:checked');
                var qs = [];
                sel.forEach(function(cb){
                    var parts = (cb.value||'').split('||');
                    if(parts.length>=2){ qs.push('add[]='+encodeURIComponent(parts[0])+'&qty[]='+encodeURIComponent(parts[1])); }
                });
                return qs.join('&');
            }
            
            var btnNew = document.getElementById('btn-reorder-to-new-po');
            if(btnNew){
                btnNew.addEventListener('click', function(){
                    var query = buildQueryFromChecks();
                    if(!query){ alert('Pilih minimal satu barang.'); return; }
                    var url = "{{ route('pembelian.order.create') }}?payment_term=credit&due_date={{ now()->addDays(30)->format('Y-m-d') }}&"+query;
                    window.location.href = url;
                });
            }
            
            var btnAppendSelectedAll = document.getElementById('btn-append-to-selected-po');
            if(btnAppendSelectedAll){
                btnAppendSelectedAll.addEventListener('click', function(){
                    var sel = document.getElementById('draft-po-select');
                    var id = sel ? sel.value : '';
                    if(!id){ alert('Silakan pilih PO Draft terlebih dahulu dari menu dropdown.'); return; }
                    var query = buildQueryFromChecks();
                    if(!query){ alert('Pilih minimal satu barang dari tabel untuk disisipkan.'); return; }
                    var url = "{{ url('/pembelian/order') }}/"+id+"/append-items?"+query;
                    window.location.href = url;
                });
            }
            
            document.addEventListener('change', function(e){
                if(e.target && e.target.classList.contains('reorder-check') || e.target.id === 'reorder-check-all'){
                    updateSummary();
                }
            });
            updateSummary();
        })();
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
            
            --tr-primary: #3b82f6;
            --tr-primary-hover: #2563eb;
            --tr-primary-light: #eff6ff;
            
            --tr-success: #10b981;
            --tr-success-hover: #059669;
            --tr-success-bg: #ecfdf5;
            
            --tr-danger: #ef4444;
            --tr-danger-hover: #dc2626;
            --tr-danger-bg: #fef2f2;
            
            --tr-warning: #f59e0b;
            --tr-warning-bg: #fffbeb;
            --tr-warning-border: #fde68a;
            
            --tr-slate: #64748b;
            --tr-slate-bg: #f1f5f9;
            
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --tr-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 4rem; }
        .tr-page { padding: 1.5rem; max-width: 1360px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-slate); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.6rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.4rem 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-slate { background: var(--tr-slate-bg); color: var(--tr-slate); }
        .tr-subtitle { font-size: 0.9rem; color: var(--tr-text-muted); margin: 0; font-weight: 500; line-height: 1.4; }
        
        .tr-header-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 38px; }
        .tr-btn-sm { padding: 0.35rem 0.85rem; font-size: 0.8rem; height: 32px; }
        
        .tr-btn-primary { background: var(--tr-primary); color: #ffffff; }
        .tr-btn-primary:hover { background: var(--tr-primary-hover); transform: translateY(-1px); }
        .tr-btn-success { background: var(--tr-success); color: #ffffff; }
        .tr-btn-success:hover { background: var(--tr-success-hover); transform: translateY(-1px); }
        .tr-btn-danger { background: var(--tr-danger); color: #ffffff; }
        .tr-btn-danger:hover { background: var(--tr-danger-hover); transform: translateY(-1px); }
        .tr-btn-dark { background: var(--tr-text-main); color: #ffffff; }
        .tr-btn-dark:hover { background: #000000; transform: translateY(-1px); }
        
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); background: #f8fafc; }
        .tr-btn-danger-outline { border-color: #fecaca; color: var(--tr-danger); background: transparent; }
        .tr-btn-danger-outline:hover { background: var(--tr-danger-bg); }

        /* ── ALERT BANNER (PO) ── */
        .tr-alert-banner { background: #fffbeb; border: 1px solid #fcd34d; border-radius: var(--tr-radius-lg); padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
        .tr-alert-banner-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem; }
        .tr-alert-title { font-size: 1rem; font-weight: 800; color: #92400e; margin: 0 0 0.2rem 0; }
        .tr-alert-subtitle { font-size: 0.8rem; color: #b45309; margin: 0; }
        
        .tr-alert-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 0.75rem; }
        .tr-po-card { display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1rem; background: #ffffff; border: 1px solid #fde68a; border-radius: 8px; text-decoration: none; transition: transform 0.2s; }
        .tr-po-card:hover { transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.1); border-color: #f59e0b; }
        .tr-po-number { font-weight: 800; color: var(--tr-text-main); font-family: monospace; font-size: 0.9rem; }
        .tr-po-supplier { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }
        .tr-po-date { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-po-status { font-size: 0.7rem; color: var(--tr-warning); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px; }

        /* ── STATS GRID ── */
        .tr-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .tr-stat-card { background: var(--tr-surface); padding: 1.25rem; border-radius: var(--tr-radius-md); border: 1px solid var(--tr-border); display: flex; align-items: center; gap: 1rem; box-shadow: var(--tr-shadow-sm); border-left-width: 4px; }
        .tr-stat-card.border-green { border-left-color: var(--tr-success); }
        .tr-stat-card.border-red { border-left-color: var(--tr-danger); }
        .tr-stat-card.border-warning { border-left-color: var(--tr-warning); }
        .tr-stat-card.border-slate { border-left-color: var(--tr-slate); }
        
        .tr-stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .tr-stat-icon.bg-green { background: var(--tr-success-bg); color: var(--tr-success); }
        .tr-stat-icon.bg-red { background: var(--tr-danger-bg); color: var(--tr-danger); }
        .tr-stat-icon.bg-warning { background: var(--tr-warning-bg); color: var(--tr-warning); }
        .tr-stat-icon.bg-slate { background: var(--tr-slate-bg); color: var(--tr-slate); }
        
        .tr-stat-value { font-size: 1.4rem; font-weight: 800; line-height: 1.1; margin-top: 4px; }
        .tr-stat-label { font-size: 0.75rem; color: var(--tr-text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.02em; }
        .tr-stat-unit { font-size: 0.75rem; font-weight: 500; color: var(--tr-text-light); }
        .tr-stat-link { font-size: 0.75rem; font-weight: 700; background: var(--tr-bg); border: 1px solid var(--tr-border); padding: 4px 8px; border-radius: 6px; text-decoration: none; color: var(--tr-text-main); }
        .tr-stat-link:hover { background: var(--tr-border-light); }
        
        .text-green { color: var(--tr-success); }
        .text-red { color: var(--tr-danger); }
        .text-warning { color: var(--tr-warning); }
        .text-slate { color: var(--tr-slate); }

        /* ── CARDS & LAYOUT ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; display: flex; justify-content: space-between; align-items: center; }
        .tr-card-title { font-size: 1.05rem; font-weight: 800; color: var(--tr-text-main); margin: 0; }
        .tr-card-subtitle { font-size: 0.8rem; color: var(--tr-text-muted); margin: 0.25rem 0 0 0; }
        
        .tr-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

        /* ── FILTER BAR ── */
        .tr-filter-bar { padding: 1rem 1.25rem; }
        .tr-filter-form { display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-label { font-size: 0.75rem; font-weight: 600; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; }
        
        .tr-input, .tr-select { padding: 0.5rem 0.85rem; border: 1px solid var(--tr-border); border-radius: 6px; font-family: inherit; font-size: 0.85rem; color: var(--tr-text-main); background: #f8fafc; outline: none; transition: border-color 0.2s; height: 38px; }
        .tr-input:focus, .tr-select:focus { border-color: var(--tr-primary); background: #ffffff; }
        
        .tr-search { display: flex; align-items: center; gap: 8px; background: var(--tr-bg); border-radius: 6px; padding: 0.5rem 0.85rem; border: 1px solid var(--tr-border); height: 38px; transition: border-color 0.2s; }
        .tr-search:focus-within { border-color: var(--tr-primary); background: #ffffff; }
        .tr-search-icon { color: var(--tr-text-light); flex-shrink: 0; }
        .tr-search input { border: none; background: transparent; font-size: 0.85rem; font-family: inherit; color: var(--tr-text-main); outline: none; width: 100%; }
        
        .tr-select-wrapper { position: relative; }
        .tr-select { appearance: none; padding-right: 2rem; cursor: pointer; width: 100%; }
        .tr-select-sm { height: 32px; padding: 0.25rem 0.75rem; padding-right: 2rem; font-size: 0.8rem; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        
        .tr-filter-actions { display: flex; gap: 6px; }

        /* ── TABLES ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody tr.tr-row-warning td { background: var(--tr-warning-bg); }
        .tr-table tbody tr.tr-row-warning:hover td { background: #fef3c7; }
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px dashed var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.c, .tr-table td.c { text-align: center; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* Custom Checkbox */
        .tr-checkbox { width: 16px; height: 16px; accent-color: var(--tr-primary); cursor: pointer; border-radius: 4px; }

        /* Table Formatting */
        .tr-date-main { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; white-space: nowrap; }
        .tr-date-sub { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }
        
        .tr-prod-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; line-height: 1.3; }
        .tr-prod-meta { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 4px; }
        .tr-dot-divider { color: var(--tr-border); margin: 0 4px; }
        .tr-font-mono { font-family: monospace; color: var(--tr-text-main); font-weight: 600; }
        .tr-font-bold { font-weight: 800; }
        
        .tr-qty-badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.25rem 0.6rem; border-radius: 999px; font-weight: 800; font-size: 0.85rem; min-width: 44px; }
        .tr-bg-green-soft { background: var(--tr-success-bg); }
        .tr-bg-red-soft { background: var(--tr-danger-bg); }
        .text-primary { color: var(--tr-primary); }

        .tr-po-action-bar { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid var(--tr-border); flex-wrap: wrap; gap: 1rem; }
        .tr-po-summary { font-size: 0.85rem; color: var(--tr-text-muted); }
        .tr-po-summary strong { color: var(--tr-text-main); }

        .tr-empty-compact { text-align: center; color: var(--tr-text-light); padding: 2rem; font-size: 0.85rem; }
        .tr-pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--tr-border-light); background: #ffffff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .tr-grid-2 { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-header-actions { width: 100%; }
            .tr-header-actions .tr-btn { flex: 1; }
            .tr-filter-form { flex-direction: column; align-items: stretch; }
            .tr-form-group { width: 100%; min-width: auto !important; }
            .tr-po-action-bar { flex-direction: column; align-items: flex-start; }
        }
    </style>
    @endpush
</x-app-layout>