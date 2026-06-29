<x-app-layout>
    <x-slot name="header">Barang Terjual</x-slot>

    @push('styles')
    <style>
        :root{--bt-radius:12px;--bt-bg:#fafafa;--bt-surface:#fff;--bt-border:#e5e7eb;--bt-text:#111827;--bt-muted:#6b7280;--bt-emerald:#059669;--bt-blue:#3b82f6;--bt-indigo:#4f46e5;--bt-amber:#d97706;}
        .bt-page{max-width:82rem;margin:0 auto;padding:1.5rem 1rem 3rem;font-family:'Segoe UI',system-ui,sans-serif;}
        .bt-hdr{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
        .bt-eyebrow{font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--bt-emerald);margin-bottom:0.3rem;}
        .bt-title{font-size:1.375rem;font-weight:800;color:var(--bt-text);margin:0;display:flex;align-items:center;gap:0.5rem;}
        .bt-sub{font-size:0.8rem;color:var(--bt-muted);margin-top:0.25rem;}
        .bt-btn{display:inline-flex;align-items:center;gap:0.4rem;padding:0.55rem 1rem;border-radius:8px;font-size:0.8rem;font-weight:600;cursor:pointer;transition:all .15s;border:none;text-decoration:none;white-space:nowrap;}
        .bt-btn svg{width:16px;height:16px;}
        .bt-btn-ghost{background:transparent;color:var(--bt-muted);border:1px solid var(--bt-border);}
        .bt-btn-ghost:hover{background:#f3f4f6;color:var(--bt-text);}
        .bt-btn-sm{padding:0.4rem 0.7rem;font-size:0.72rem;}
        .bt-btn-dark{background:var(--bt-text);color:#fff;}
        .bt-btn-dark:hover{background:#000;}

        /* Stats */
        .bt-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;}
        .bt-stat{background:var(--bt-surface);border:1px solid var(--bt-border);border-radius:var(--bt-radius);padding:1rem 1.25rem;position:relative;overflow:hidden;}
        .bt-stat::before{content:'';position:absolute;top:0;left:0;width:4px;height:100%;border-radius:4px 0 0 4px;}
        .bt-stat.green::before{background:var(--bt-emerald);}
        .bt-stat.blue::before{background:var(--bt-blue);}
        .bt-stat.purple::before{background:var(--bt-indigo);}
        .bt-stat-lbl{font-size:0.68rem;font-weight:600;color:var(--bt-muted);text-transform:uppercase;letter-spacing:0.04em;}
        .bt-stat-val{font-size:1.25rem;font-weight:800;font-family:'Cascadia Code','Fira Code',monospace;letter-spacing:-0.02em;margin-top:0.25rem;}
        .bt-stat.green .bt-stat-val{color:var(--bt-emerald);}
        .bt-stat.blue .bt-stat-val{color:var(--bt-blue);}
        .bt-stat.purple .bt-stat-val{color:var(--bt-indigo);}
        .bt-stat-badge{font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;padding:0.2rem 0.5rem;border-radius:999px;float:right;}
        .bt-stat.green .bt-stat-badge{background:#f0fdf4;color:#065f46;}
        .bt-stat.blue .bt-stat-badge{background:#eff6ff;color:#1e40af;}
        .bt-stat.purple .bt-stat-badge{background:#eef2ff;color:#4338ca;}

        /* Per-Kasir Revenue Cards */
        .bt-perkasir{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-bottom:1.5rem;}
        .bt-pk{background:var(--bt-surface);border:1px solid var(--bt-border);border-radius:var(--bt-radius);padding:1rem 1.25rem;position:relative;overflow:hidden;}
        .bt-pk::before{content:'';position:absolute;top:0;left:0;width:4px;height:100%;border-radius:4px 0 0 4px;}
        .bt-pk.a1::before{background:#3b82f6;}
        .bt-pk.a2::before{background:#8b5cf6;}
        .bt-pk.sv::before{background:#10b981;}
        .bt-pk-name{font-size:0.9rem;font-weight:700;color:var(--bt-text);margin-bottom:0.1rem;}
        .bt-pk-role{font-size:0.65rem;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;padding:0.1rem 0.4rem;border-radius:999px;display:inline-block;margin-bottom:0.5rem;}
        .bt-pk-role.admin1{background:#dbeafe;color:#1e40af;}
        .bt-pk-role.admin2{background:#ede9fe;color:#6b21a8;}
        .bt-pk-role.supervisor{background:#d1fae5;color:#065f46;}
        .bt-pk-row{display:flex;justify-content:space-between;align-items:center;padding:0.25rem 0;}
        .bt-pk-label{font-size:0.72rem;color:var(--bt-muted);}
        .bt-pk-val{font-family:'Cascadia Code','Fira Code',monospace;font-size:0.85rem;font-weight:700;color:var(--bt-text);}
        .bt-pk-val.revenue{color:var(--bt-emerald);}

        /* Card */
        .bt-card{background:var(--bt-surface);border:1px solid var(--bt-border);border-radius:var(--bt-radius);overflow:hidden;}
        .bt-card-hdr{padding:1rem 1.25rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.75rem;}
        .bt-card-title{font-size:0.9375rem;font-weight:700;color:var(--bt-text);}
        .bt-card-sub{font-size:0.75rem;color:var(--bt-muted);margin-top:0.15rem;}

        /* Filter */
        .bt-filter{padding:0.875rem 1.25rem;background:#fafafa;border-bottom:1px solid #f3f4f6;border-top:1px solid #f3f4f6;}
        .bt-filter-form{display:flex;flex-wrap:wrap;gap:0.65rem;align-items:flex-end;}
        .bt-fg{display:flex;flex-direction:column;gap:0.25rem;}
        .bt-fl{font-size:0.65rem;font-weight:700;text-transform:uppercase;color:var(--bt-muted);letter-spacing:0.04em;}
        .bt-fi{padding:0.45rem 0.65rem;border:1.5px solid var(--bt-border);border-radius:8px;font-size:0.78rem;font-family:inherit;transition:all .15s;background:#fff;color:var(--bt-text);}
        .bt-fi:focus{outline:none;border-color:var(--bt-indigo);box-shadow:0 0 0 3px rgba(79,70,229,0.08);}
        select.bt-fi{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 0.5rem center;padding-right:1.5rem;cursor:pointer;}
        .bt-fi-search{min-width:160px;flex:1;max-width:220px;}

        /* Table */
        .bt-tbl-wrap{overflow-x:auto;}
        .bt-tbl{width:100%;border-collapse:collapse;font-size:0.78rem;min-width:800px;}
        .bt-tbl th{text-align:left;padding:0.65rem 0.875rem;font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#9ca3af;border-bottom:2px solid #f3f4f6;white-space:nowrap;}
        .bt-tbl td{padding:0.7rem 0.875rem;border-bottom:1px solid #f9fafb;color:#374151;vertical-align:middle;}
        .bt-tbl tr:last-child td{border-bottom:none;}
        .bt-tbl tr:hover{background:#fafafa;}
        .bt-tbl .c{text-align:center;}
        .bt-tbl .r{text-align:right;}
        .bt-mono{font-family:'Cascadia Code','Fira Code',monospace;font-size:0.73rem;}

        .bt-inv{font-family:'Cascadia Code','Fira Code',monospace;font-weight:700;font-size:0.78rem;color:var(--bt-indigo);background:#eef2ff;padding:0.15rem 0.45rem;border-radius:5px;}
        .bt-badge{display:inline-flex;align-items:center;gap:0.2rem;padding:0.15rem 0.5rem;border-radius:999px;font-size:0.63rem;font-weight:700;white-space:nowrap;}
        .bt-badge-blue{background:#dbeafe;color:#1e40af;}
        .bt-badge-purple{background:#f3e8ff;color:#6b21a8;}
        .bt-badge-teal{background:#ccfbf1;color:#0f766e;}

        .bt-product-name{font-weight:700;color:var(--bt-text);font-size:0.8rem;}
        .bt-product-cat{font-size:0.68rem;color:var(--bt-muted);margin-top:1px;}
        .bt-date{font-weight:600;font-size:0.78rem;color:var(--bt-text);}
        .bt-time{font-size:0.68rem;color:var(--bt-muted);margin-top:1px;}
        .bt-amt{font-family:'Cascadia Code','Fira Code',monospace;font-weight:700;font-size:0.78rem;white-space:nowrap;}
        .bt-kasir{font-weight:600;color:var(--bt-text);font-size:0.78rem;}

        .bt-empty{text-align:center;padding:3rem 1rem;}
        .bt-empty-ico{width:48px;height:48px;margin:0 auto 0.75rem;background:#f3f4f6;border-radius:14px;display:flex;align-items:center;justify-content:center;}
        .bt-empty-ico svg{width:24px;height:24px;color:#9ca3af;}
        .bt-empty-title{font-size:0.9375rem;font-weight:700;color:#6b7280;}
        .bt-empty-sub{font-size:0.8rem;color:#9ca3af;margin-top:0.2rem;}
        .bt-pagination{padding:1rem 1.25rem;display:flex;justify-content:center;}

        @media(max-width:768px){
            .bt-stats{grid-template-columns:1fr;}
            .bt-filter-form{flex-direction:column;}
            .bt-fi-search{max-width:100%;}
            .bt-fg{width:100%;}
            .bt-fi,.bt-fg select{width:100%;}
        }
    </style>
    @endpush

    <div class="bt-page">
        {{-- Header --}}
        <div class="bt-hdr">
            <div>
                <div class="bt-eyebrow">Penjualan</div>
                <h1 class="bt-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    Barang Terjual
                </h1>
                <p class="bt-sub">Laporan detail produk yang terjual dari seluruh transaksi kasir.</p>
            </div>
            <a href="{{ route('transaksi.index') }}" class="bt-btn bt-btn-ghost">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali ke Transaksi
            </a>
        </div>

        {{-- Per-Kasir Revenue Summary (Supervisor Only) --}}
        @if($perKasir->isNotEmpty())
        <div class="bt-perkasir">
            @foreach($perKasir as $pk)
            <div class="bt-pk {{ $pk->role === 'admin1' ? 'a1' : ($pk->role === 'admin2' ? 'a2' : 'sv') }}">
                <div class="bt-pk-name">{{ $pk->name }}</div>
                <span class="bt-pk-role {{ $pk->role }}">{{ ucfirst($pk->role) }}</span>
                <div class="bt-pk-row">
                    <span class="bt-pk-label">Transaksi</span>
                    <span class="bt-pk-val">{{ number_format($pk->trx_count, 0, ',', '.') }}</span>
                </div>
                <div class="bt-pk-row">
                    <span class="bt-pk-label">Pendapatan</span>
                    <span class="bt-pk-val revenue">Rp {{ number_format($pk->revenue, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Stat Cards --}}
        <div class="bt-stats">
            <div class="bt-stat green">
                <span class="bt-stat-badge">Filter Aktif</span>
                <div class="bt-stat-lbl">Total Pendapatan</div>
                <div class="bt-stat-val">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="bt-stat blue">
                <span class="bt-stat-badge">Filter Aktif</span>
                <div class="bt-stat-lbl">Total Qty Terjual</div>
                <div class="bt-stat-val">{{ number_format($totalQty, 0, ',', '.') }} <span style="font-size:0.7rem;color:var(--bt-muted);font-weight:600;">pcs</span></div>
            </div>
            <div class="bt-stat purple">
                <span class="bt-stat-badge">Filter Aktif</span>
                <div class="bt-stat-lbl">Total Baris Item</div>
                <div class="bt-stat-val">{{ number_format($totalRows, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Data Card --}}
        <div class="bt-card">
            <div class="bt-card-hdr">
                <div>
                    <div class="bt-card-title">Daftar Barang Terjual</div>
                    <div class="bt-card-sub">Menampilkan <strong>{{ $items->total() }}</strong> baris item terjual.</div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bt-filter">
                <form method="GET" class="bt-filter-form">
                    <div class="bt-fg">
                        <label class="bt-fl">Cari Produk</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama produk..." class="bt-fi bt-fi-search">
                    </div>
                    <div class="bt-fg" style="width:130px;">
                        <label class="bt-fl">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="bt-fi">
                    </div>
                    <div class="bt-fg" style="width:130px;">
                        <label class="bt-fl">Sampai</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="bt-fi">
                    </div>
                    <div class="bt-fg" style="width:120px;">
                        <label class="bt-fl">Jenis</label>
                        <select name="sale_type" class="bt-fi">
                            <option value="">Semua</option>
                            <option value="eceran" @selected(request('sale_type')=='eceran')>Eceran</option>
                            <option value="grosir" @selected(request('sale_type')=='grosir')>Grosir</option>
                        </select>
                    </div>
                    @if($kasirUsers->isNotEmpty())
                    <div class="bt-fg" style="width:150px;">
                        <label class="bt-fl">Kasir</label>
                        <select name="user_id" class="bt-fi">
                            <option value="">Semua Kasir</option>
                            @foreach($kasirUsers as $ku)
                                <option value="{{ $ku->id }}" @selected(request('user_id') == $ku->id)>
                                    {{ $ku->name }} ({{ ucfirst($ku->role) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div style="display:flex;gap:0.4rem;align-self:flex-end;">
                        <button type="submit" class="bt-btn bt-btn-dark bt-btn-sm">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:12px;height:12px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            Filter
                        </button>
                        <a href="{{ route('transaksi.barang_terjual') }}" class="bt-btn bt-btn-ghost bt-btn-sm">Reset</a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bt-tbl-wrap">
                <table class="bt-tbl">
                    <thead>
                        <tr>
                            <th style="width:36px;">#</th>
                            <th>Produk</th>
                            <th>No. Transaksi</th>
                            <th>Waktu</th>
                            <th>Kasir</th>
                            <th>Gudang</th>
                            <th class="c">Jenis</th>
                            <th class="r">Qty</th>
                            <th class="r">Harga Satuan</th>
                            <th class="r">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $i => $item)
                        @php
                            $trx = $item->transaction;
                            $saleTypeBadge = match($trx?->sale_type) {
                                'grosir' => 'bt-badge-purple',
                                'eceran' => 'bt-badge-blue',
                                default => '',
                            };
                        @endphp
                        <tr>
                            <td class="bt-mono" style="color:#9ca3af;">{{ $items->firstItem() + $i }}</td>
                            <td>
                                <div class="bt-product-name">{{ $item->product?->name ?? '-' }}</div>
                                @if($item->product?->category)
                                    <div class="bt-product-cat">{{ $item->product->category->name ?? '' }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="bt-inv">#{{ str_pad($trx?->id ?? 0, 5, '0', STR_PAD_LEFT) }}</span>
                                @if($trx?->customer?->name)
                                    <div style="margin-top:3px;">
                                        <span class="bt-badge bt-badge-teal">{{ $trx->customer->name }}</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($trx)
                                    <div class="bt-date">{{ $trx->created_at->format('d M Y') }}</div>
                                    <div class="bt-time">{{ $trx->created_at->format('H:i') }} WIB</div>
                                @else
                                    <span style="color:#9ca3af;">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="bt-kasir">{{ $trx?->user?->name ?? '-' }}</div>
                            </td>
                            <td>
                                @if($item->warehouse)
                                    <span class="bt-badge bt-badge-teal" style="font-size:0.7rem;">{{ $item->warehouse->name }}</span>
                                @else
                                    <span style="color:#9ca3af;font-size:0.75rem;">-</span>
                                @endif
                            </td>
                            <td class="c">
                                @if($trx?->sale_type === 'grosir')
                                    <span class="bt-badge bt-badge-purple">Grosir</span>
                                @elseif($trx?->sale_type === 'eceran')
                                    <span class="bt-badge bt-badge-blue">Eceran</span>
                                @else
                                    <span style="color:#9ca3af;">-</span>
                                @endif
                            </td>
                            <td class="r">
                                @php
                                    $displayQty = ($item->unit_qty !== null && $item->unit_qty > 0) ? $item->unit_qty : $item->quantity;
                                    $displayUnit = $item->unit_name ?? 'pcs';
                                    $displayPrice = ($item->unit_qty !== null && $item->unit_qty > 0) 
                                        ? ($item->subtotal / $item->unit_qty) 
                                        : $item->price;
                                @endphp
                                <span class="bt-amt">{{ number_format($displayQty, 0, ',', '.') }}</span>
                                <span style="font-size:0.65rem;color:#9ca3af;">{{ $displayUnit }}</span>
                            </td>
                            <td class="r">
                                <span class="bt-amt" style="color:var(--bt-text);">{{ number_format($displayPrice, 0, ',', '.') }}</span>
                            </td>
                            <td class="r">
                                <span class="bt-amt" style="color:var(--bt-emerald);">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">
                                <div class="bt-empty">
                                    <div class="bt-empty-ico">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    </div>
                                    <div class="bt-empty-title">Tidak Ada Data</div>
                                    <div class="bt-empty-sub">Belum ada barang terjual atau filter tidak cocok.</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($items->hasPages())
                <div class="bt-pagination">{{ $items->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
