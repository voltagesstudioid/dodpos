<x-app-layout>
    <x-slot name="header">Rekap Stok Barang</x-slot>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap');
        .sk-page{max-width:82rem;margin:0 auto;padding:0 1rem;font-family:'Plus Jakarta Sans',sans-serif;}

        /* ── Header ── */
        .sk-hdr{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.75rem;}
        .sk-hdr-l{display:flex;align-items:center;gap:1rem;}
        .sk-hdr-ico{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:linear-gradient(135deg,#6366f1,#4f46e5);box-shadow:0 8px 24px rgba(79,70,229,.3);}
        .sk-hdr-title{font-size:1.5rem;font-weight:800;color:#0f172a;letter-spacing:-.03em;line-height:1.2;}
        .sk-hdr-sub{font-size:.8125rem;color:#64748b;margin-top:2px;}
        .sk-hdr-actions{display:flex;gap:.5rem;}
        .sk-btn{display:inline-flex;align-items:center;gap:.375rem;padding:.625rem 1.125rem;border-radius:10px;font-size:.8125rem;font-weight:600;cursor:pointer;transition:all .2s;border:1.5px solid transparent;text-decoration:none;white-space:nowrap;font-family:inherit;}
        .sk-btn-outline{border-color:#e2e8f0;background:#fff;color:#475569;} .sk-btn-outline:hover{background:#f8fafc;border-color:#cbd5e1;}
        .sk-btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border:none;box-shadow:0 4px 14px rgba(79,70,229,.35);}
        .sk-btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(79,70,229,.45);}

        /* ── KPI Cards ── */
        .sk-kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;}
        .sk-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:1.25rem 1.375rem;transition:all .3s;position:relative;overflow:hidden;}
        .sk-kpi::before{content:'';position:absolute;top:0;left:0;bottom:0;width:4px;}
        .sk-kpi:hover{transform:translateY(-3px);box-shadow:0 12px 32px rgba(0,0,0,.07);border-color:transparent;}
        .sk-kpi.blue::before{background:linear-gradient(180deg,#3b82f6,#2563eb);}
        .sk-kpi.purple::before{background:linear-gradient(180deg,#8b5cf6,#7c3aed);}
        .sk-kpi.green::before{background:linear-gradient(180deg,#10b981,#059669);}
        .sk-kpi.red::before{background:linear-gradient(180deg,#ef4444,#dc2626);}
        .sk-kpi-lbl{font-size:.6875rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;}
        .sk-kpi-val{font-size:2rem;font-weight:800;letter-spacing:-.03em;line-height:1;margin-top:.25rem;}
        .sk-kpi-val.blue{color:#2563eb;} .sk-kpi-val.purple{color:#7c3aed;} .sk-kpi-val.green{color:#059669;} .sk-kpi-val.red{color:#dc2626;}
        .sk-kpi-foot{font-size:.72rem;color:#94a3b8;margin-top:.375rem;}

        /* ── Filter Card ── */
        .sk-filter{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:1.125rem 1.375rem;margin-bottom:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,.04);}
        .sk-ff{display:flex;flex-wrap:wrap;align-items:flex-end;gap:.75rem;}
        .sk-fgrp{display:flex;flex-direction:column;}
        .sk-flbl{display:block;font-size:.675rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;margin-bottom:.375rem;}
        .sk-fsel{padding:.625rem 2.25rem .625rem .875rem;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;font-size:.8125rem;color:#1e293b;outline:none;transition:all .2s;font-family:inherit;cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right .5rem center;background-size:16px;min-width:170px;}
        .sk-fsel:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.12);}
        .sk-fsearch{position:relative;flex:1;min-width:200px;}
        .sk-fsearch input{width:100%;padding:.625rem .875rem .625rem 2.25rem;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;font-size:.8125rem;color:#1e293b;outline:none;transition:all .2s;font-family:inherit;box-sizing:border-box;}
        .sk-fsearch input:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.12);}
        .sk-fsearch svg{position:absolute;left:.75rem;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none;}
        .sk-btn-f{padding:.625rem 1.25rem;border-radius:10px;font-size:.8125rem;font-weight:600;border:none;cursor:pointer;transition:all .2s;font-family:inherit;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;box-shadow:0 4px 12px rgba(79,70,229,.25);}
        .sk-btn-f:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(79,70,229,.35);}
        .sk-btn-r{padding:.625rem 1.25rem;border-radius:10px;font-size:.8125rem;font-weight:600;border:1.5px solid #e2e8f0;cursor:pointer;transition:all .2s;font-family:inherit;background:#f8fafc;color:#64748b;text-decoration:none;display:inline-flex;align-items:center;gap:.375rem;}
        .sk-btn-r:hover{background:#f1f5f9;border-color:#cbd5e1;color:#475569;}
        .sk-active-filters{display:flex;gap:.375rem;flex-wrap:wrap;align-items:center;margin-top:.75rem;padding-top:.75rem;border-top:1px solid #f1f5f9;}
        .sk-filter-tag{display:inline-flex;align-items:center;gap:.25rem;background:#eef2ff;color:#4f46e5;font-size:.6875rem;font-weight:700;padding:.25rem .625rem;border-radius:6px;}
        .sk-filter-tag a{color:#4f46e5;text-decoration:none;font-weight:800;margin-left:.25rem;} .sk-filter-tag a:hover{color:#e11d48;}

        /* ── Table Card ── */
        .sk-tbl-wrap{background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04);}
        .sk-tbl-scroll{overflow-x:auto;-webkit-overflow-scrolling:touch;}
        .sk-tbl{width:100%;border-collapse:separate;border-spacing:0;min-width:800px;}
        .sk-tbl thead{background:linear-gradient(180deg,#eef2ff,#e0e7ff);border-bottom:2px solid #a5b4fc;}
        .sk-tbl thead th{padding:.9rem 1.25rem;font-size:.675rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#3730a3;white-space:nowrap;text-align:left;user-select:none;}
        .sk-tbl thead th.sortable{cursor:pointer;transition:color .2s;} .sk-tbl thead th.sortable:hover{color:#4f46e5;}
        .sk-tbl thead th .arr{display:inline-block;margin-left:4px;font-size:.6rem;opacity:.4;} .sk-tbl thead th.active .arr{opacity:1;color:#4f46e5;}
        .sk-tbl thead th.r{text-align:right;}
        .sk-tbl tbody td{padding:.9375rem 1.25rem;border-bottom:1px solid #f1f5f9;font-size:.8125rem;color:#374151;vertical-align:middle;}
        .sk-tbl tbody tr{transition:background .15s;} .sk-tbl tbody tr:hover{background:linear-gradient(90deg,#eef2ff,#faf5ff);}
        .sk-tbl tbody tr:last-child td{border-bottom:none;}

        /* ── Cell Styles ── */
        .sk-pname{font-weight:700;font-size:.8125rem;color:#0f172a;}
        .sk-psku{font-size:.6875rem;color:#94a3b8;font-family:'JetBrains Mono',monospace;margin-top:2px;}
        .sk-cat{display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .625rem;border-radius:6px;font-size:.72rem;font-weight:600;background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;}
        .sk-wh-tag{display:inline-block;background:#eff6ff;color:#3b82f6;font-size:.6875rem;font-weight:700;padding:.125rem .5rem;border-radius:5px;}
        .sk-loc{font-size:.6875rem;color:#94a3b8;margin-top:3px;display:flex;align-items:center;gap:3px;}
        .sk-batch{font-family:'JetBrains Mono',monospace;font-size:.75rem;font-weight:600;color:#1e293b;}
        .sk-exp{font-size:.6875rem;font-weight:600;display:flex;align-items:center;gap:3px;margin-top:2px;}
        .sk-exp.expired{color:#ef4444;} .sk-exp.warning{color:#d97706;} .sk-exp.ok{color:#10b981;}
        .sk-muted{color:#cbd5e1;}

        /* ── Stock Badge ── */
        .sk-stk-box{display:flex;flex-direction:column;align-items:flex-end;gap:6px;}
        .sk-stk-num{font-size:1.25rem;font-weight:900;font-family:'JetBrains Mono',monospace;letter-spacing:-.02em;line-height:1;}
        .sk-stk-num.ok{color:#059669;} .sk-stk-num.low{color:#d97706;} .sk-stk-num.empty{color:#94a3b8;} .sk-stk-num.masked{color:#94a3b8;}
        .sk-stk-pcs{font-size:.625rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.05em;}
        .sk-stk-break{display:flex;flex-wrap:wrap;gap:3px;justify-content:flex-end;}
        .sk-stk-chip{font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:99px;white-space:nowrap;display:inline-flex;align-items:center;gap:3px;}
        .sk-stk-chip.big{background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe;}
        .sk-stk-chip.small{background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;}
        .sk-stk-pcs-tag{font-size:.625rem;background:#f1f5f9;color:#94a3b8;padding:1px 6px;border-radius:4px;font-weight:600;margin-left:2px;}
        .sk-stk-bar{width:110px;height:5px;background:#f1f5f9;border-radius:99px;overflow:hidden;}
        .sk-stk-bar-f{height:100%;border-radius:99px;transition:width .4s;}
        .sk-stk-bar-f.ok{background:#10b981;} .sk-stk-bar-f.low{background:#f59e0b;} .sk-stk-bar-f.crit{background:#ef4444;}
        .sk-stk-mini{font-size:.6rem;color:#94a3b8;text-align:right;}
        .sk-dot{display:inline-block;width:8px;height:8px;border-radius:50%;margin-right:5px;flex-shrink:0;}
        .sk-dot.green{background:#10b981;} .sk-dot.yellow{background:#f59e0b;} .sk-dot.red{background:#ef4444;} .sk-dot.gray{background:#cbd5e1;}
        .sk-row-status{display:flex;align-items:center;font-size:.72rem;font-weight:700;gap:4px;}

        /* ── Empty State ── */
        .sk-empty{text-align:center;padding:3.5rem 1.5rem;}
        .sk-empty-ico{width:72px;height:72px;margin:0 auto 1rem;border-radius:50%;background:linear-gradient(135deg,#eef2ff,#e0e7ff);display:flex;align-items:center;justify-content:center;}
        .sk-empty-title{font-size:1rem;font-weight:700;color:#475569;margin-bottom:.25rem;}
        .sk-empty-sub{font-size:.8125rem;color:#94a3b8;margin-bottom:1.25rem;}

        /* ── Pagination ── */
        .sk-pag{padding:.875rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;background:#fff;}
        .sk-pag-info{font-size:.75rem;color:#94a3b8;font-weight:500;}

        @media(max-width:1024px){.sk-kpis{grid-template-columns:repeat(2,1fr);}}
        @media(max-width:640px){
            .sk-kpis{grid-template-columns:1fr;}
            .sk-hdr{flex-direction:column;align-items:flex-start;}
            .sk-ff{flex-direction:column;align-items:stretch;}
            .sk-fsel,.sk-fsearch{width:100%;min-width:auto;}
            .sk-pag{flex-direction:column;gap:.5rem;text-align:center;}
        }
    </style>
    @endpush

    <div class="py-4">
        <div class="sk-page">

            {{-- Header --}}
            <div class="sk-hdr">
                <div class="sk-hdr-l">
                    <div class="sk-hdr-ico">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    </div>
                    <div>
                        <div class="sk-hdr-title">Rekap Stok Barang</div>
                        <div class="sk-hdr-sub">Detail penyebaran stok produk di seluruh gudang dan lokasi rak</div>
                    </div>
                </div>
                <div class="sk-hdr-actions">
                    <a href="{{ route('gudang.stok.export', request()->only(['warehouse_id','category_id','search'])) }}" class="sk-btn sk-btn-outline">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export CSV
                    </a>
                    <a href="{{ route('gudang.penerimaan.create') }}" class="sk-btn sk-btn-primary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Terima Barang
                    </a>
                </div>
            </div>

            {{-- KPI Cards --}}
            <div class="sk-kpis">
                <div class="sk-kpi blue">
                    <span class="sk-kpi-lbl">Total Record Stok</span>
                    <div class="sk-kpi-val blue">{{ number_format($totalRecords) }}</div>
                    <div class="sk-kpi-foot">Record aktif di semua gudang</div>
                </div>
                <div class="sk-kpi purple">
                    <span class="sk-kpi-lbl">Total Nilai Stok</span>
                    <div class="sk-kpi-val purple">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</div>
                    <div class="sk-kpi-foot">Estimasi berdasarkan harga beli</div>
                </div>
                <div class="sk-kpi green">
                    <span class="sk-kpi-lbl">Gudang Aktif</span>
                    <div class="sk-kpi-val green">{{ $activeWarehouses }}</div>
                    <div class="sk-kpi-foot">Gudang dengan stok tersimpan</div>
                </div>
                <div class="sk-kpi red">
                    <span class="sk-kpi-lbl">Stok Hampir Habis</span>
                    <div class="sk-kpi-val red">{{ $lowStockCount }}</div>
                    <div class="sk-kpi-foot">Di bawah minimum stok</div>
                </div>
            </div>

            {{-- Filter Card --}}
            <div class="sk-filter">
                <form method="GET" action="{{ route('gudang.stok') }}" id="sk-filter-form">
                    <div class="sk-ff">
                        <div class="sk-fgrp">
                            <label class="sk-flbl">Gudang</label>
                            <select name="warehouse_id" class="sk-fsel">
                                <option value="">Semua Gudang</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}" {{ $warehouseId == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sk-fgrp">
                            <label class="sk-flbl">Kategori</label>
                            <select name="category_id" class="sk-fsel">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sk-fgrp" style="flex:1;min-width:200px;">
                            <label class="sk-flbl">Cari Barang</label>
                            <div class="sk-fsearch">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang / SKU..." id="sk-search-input">
                            </div>
                        </div>
                        <div class="sk-fgrp" style="align-self:flex-end;">
                            <button type="submit" class="sk-btn-f">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                Filter
                            </button>
                        </div>
                        @if(request('search') || request('warehouse_id') || request('category_id'))
                        <div class="sk-fgrp" style="align-self:flex-end;">
                            <a href="{{ route('gudang.stok') }}" class="sk-btn-r">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                Reset
                            </a>
                        </div>
                        @endif
                    </div>

                    {{-- Active filter badges --}}
                    @if(request('search') || request('warehouse_id') || request('category_id'))
                    <div class="sk-active-filters">
                        <span style="font-size:.6875rem;color:#94a3b8;font-weight:600;">Filter aktif:</span>
                        @if(request('search'))
                            <span class="sk-filter-tag">Search: "{{ request('search') }}" <a href="{{ route('gudang.stok', request()->except('search')) }}">x</a></span>
                        @endif
                        @if(request('warehouse_id'))
                            @php $whName = $warehouses->firstWhere('id', request('warehouse_id'))?->name ?? '--'; @endphp
                            <span class="sk-filter-tag">Gudang: {{ $whName }} <a href="{{ route('gudang.stok', request()->except('warehouse_id')) }}">x</a></span>
                        @endif
                        @if(request('category_id'))
                            @php $catName = $categories->firstWhere('id', request('category_id'))?->name ?? '--'; @endphp
                            <span class="sk-filter-tag">Kategori: {{ $catName }} <a href="{{ route('gudang.stok', request()->except('category_id')) }}">x</a></span>
                        @endif
                    </div>
                    @endif
                </form>
            </div>

            {{-- Data Table --}}
            <div class="sk-tbl-wrap">
                <div class="sk-tbl-scroll">
                    <table class="sk-tbl">
                        <thead>
                            <tr>
                                @php
                                    $sortUrl = function($col) use ($sort, $dir) {
                                        $newDir = ($sort === $col && $dir === 'asc') ? 'desc' : 'asc';
                                        return route('gudang.stok', array_merge(request()->except(['sort','dir','page']), ['sort' => $col, 'dir' => $newDir]));
                                    };
                                    $arrow = function($col) use ($sort, $dir) {
                                        if ($sort !== $col) return '<span class="arr">&#8693;</span>';
                                        return $dir === 'asc' ? '<span class="arr">&#8593;</span>' : '<span class="arr">&#8595;</span>';
                                    };
                                @endphp
                                <th class="sortable {{ $sort === 'product' ? 'active' : '' }}" onclick="location.href='{{ $sortUrl('product') }}'">Barang / SKU {!! $arrow('product') !!}</th>
                                <th style="width:90px;">Status</th>
                                <th>Gudang &amp; Expired</th>
                                <th class="r sortable {{ $sort === 'stock' ? 'active' : '' }}" onclick="location.href='{{ $sortUrl('stock') }}'" style="width:200px;">Sisa Stok {!! $arrow('stock') !!}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stocks as $stock)
                                @php
                                    $minStk = $stock->product->min_stock ?? 0;
                                    $curStk = (int)$stock->stock;
                                    $isLow = $curStk > 0 && $curStk <= $minStk;
                                    $isCrit = $curStk > 0 && $minStk > 0 && $curStk <= ($minStk / 2);
                                    $isEmpty = $curStk == 0;
                                    $unitName = $stock->product->unit->abbreviation ?? $stock->product->unit->name ?? 'pcs';
                                    // Pecahan per satuan
                                    $pecahan = [];
                                    $sisa = $curStk;
                                    if (!$maskStock && $curStk > 0 && $stock->product->unitConversions->isNotEmpty()) {
                                        $sorted = $stock->product->unitConversions
                                            ->filter(fn($u) => ($u->conversion_factor ?? 0) > 1)
                                            ->sortByDesc('conversion_factor');
                                        foreach ($sorted as $uc) {
                                            $f = (int)$uc->conversion_factor;
                                            $cnt = intdiv($sisa, $f);
                                            if ($cnt > 0) {
                                                $pecahan[] = [$cnt, $uc->unit->abbreviation ?? $uc->unit->name ?? '?', $f];
                                                $sisa = $sisa % $f;
                                            }
                                        }
                                    }
                                    // Stock level for bar
                                    $barPct = $minStk > 0 ? min(100, ($curStk / $minStk) * 100) : 100;
                                    $barClass = $isCrit ? 'crit' : ($isLow ? 'low' : 'ok');
                                    $numClass = $maskStock ? 'masked' : ($isEmpty ? 'empty' : ($isCrit ? 'low' : ($isLow ? 'low' : 'ok')));
                                @endphp
                                <tr>
                                    {{-- Nama + SKU + Kategori --}}
                                    <td>
                                        <div class="sk-pname">{{ $stock->product->name }}</div>
                                        <div class="sk-psku">SKU: {{ $stock->product->sku }}</div>
                                        <div style="margin-top:4px;">
                                            <span class="sk-cat">{{ $stock->product->category->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    {{-- Status Stok --}}
                                    <td>
                                        @if($maskStock)
                                            <div class="sk-row-status"><span class="sk-dot gray"></span>Tersembunyi</div>
                                        @elseif($isEmpty)
                                            <div class="sk-row-status"><span class="sk-dot gray"></span>Kosong</div>
                                        @elseif($isCrit)
                                            <div class="sk-row-status"><span class="sk-dot red"></span>Kritis</div>
                                        @elseif($isLow)
                                            <div class="sk-row-status"><span class="sk-dot yellow"></span>Menipis</div>
                                        @else
                                            <div class="sk-row-status"><span class="sk-dot green"></span>Aman</div>
                                        @endif
                                    </td>
                                    {{-- Gudang --}}
                                    <td>
                                        <span class="sk-wh-tag">{{ $stock->warehouse->name }}</span>
                                        @if($stock->expired_date)
                                            @php
                                                $expDate = \Carbon\Carbon::parse($stock->expired_date);
                                                $daysLeft = (int)now()->diffInDays($expDate, false);
                                            @endphp
                                            <div style="margin-top:3px;font-size:.65rem;{{ $expDate->isPast() ? 'color:#ef4444;font-weight:700;' : ($daysLeft <= 30 ? 'color:#d97706;' : 'color:#94a3b8;') }}">
                                                ⏱ {{ $expDate->format('d/m/Y') }} @if(!$expDate->isPast() && $daysLeft <= 30)({{ $daysLeft }}h)@endif
                                            </div>
                                        @endif
                                    </td>
                                    {{-- Sisa Stok + Pecahan --}}
                                    <td class="r" style="min-width:200px;">
                                        <div class="sk-stk-box">
                                            {{-- Angka besar --}}
                                            <div class="sk-stk-num {{ $numClass }}">
                                                {{ $maskStock ? '***' : number_format($curStk) }}
                                            </div>
                                            <div class="sk-stk-pcs">{{ $unitName }}</div>

                                            {{-- Pecahan satuan --}}
                                            @if(!$maskStock && $curStk > 0 && count($pecahan) > 0)
                                            <div class="sk-stk-break">
                                                @foreach($pecahan as $p)
                                                    <span class="sk-stk-chip big">{{ $p[0] }} {{ $p[1] }}</span>
                                                @endforeach
                                            </div>
                                            @endif

                                            {{-- Progress bar --}}
                                            @if(!$maskStock && $minStk > 0)
                                            <div style="display:flex;align-items:center;gap:6px;">
                                                <div class="sk-stk-bar">
                                                    <div class="sk-stk-bar-f {{ $barClass }}" style="width:{{ $barPct }}%;"></div>
                                                </div>
                                                <span class="sk-stk-mini">min {{ number_format($minStk) }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="sk-empty">
                                            <div class="sk-empty-ico">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#a5b4fc" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                            </div>
                                            <div class="sk-empty-title">Data Stok Kosong</div>
                                            <div class="sk-empty-sub">Tidak ada stok barang yang sesuai dengan kriteria pencarian.</div>
                                            <a href="{{ route('gudang.stok') }}" class="sk-btn sk-btn-primary" style="font-size:.8125rem;">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                                Reset Filter
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($stocks->hasPages())
                <div class="sk-pag">
                    <div class="sk-pag-info">
                        Menampilkan {{ $stocks->firstItem() }}-{{ $stocks->lastItem() }} dari {{ $stocks->total() }} record
                    </div>
                    <div>{{ $stocks->withQueryString()->links() }}</div>
                </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('sk-search-input');
        if (searchInput) {
            let timer;
            searchInput.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(() => document.getElementById('sk-filter-form')?.submit(), 500);
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
