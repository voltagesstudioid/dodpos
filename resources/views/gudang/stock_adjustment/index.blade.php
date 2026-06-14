<x-app-layout>
    <x-slot name="header">Penyesuaian Stok</x-slot>
    <style>
        .sa-cr{max-width:1100px;margin:0 auto;padding:1.5rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;}
        .sa-bc{display:flex;align-items:center;gap:0.5rem;font-size:0.8125rem;color:#94a3b8;margin-bottom:1.25rem;}
        .sa-top{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;}
        .sa-top-left h1{font-size:1.5rem;font-weight:800;color:#0f172a;margin:0;}
        .sa-top-left p{font-size:0.8125rem;color:#64748b;margin:0.25rem 0 0;}
        /* Stats */
        .sa-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;}
        .sa-stat{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:1.125rem 1.25rem;display:flex;align-items:center;gap:0.875rem;}
        .sa-stat-icon{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0;}
        .sa-stat-icon.indigo{background:#eef2ff;} .sa-stat-icon.amber{background:#fffbeb;} .sa-stat-icon.emerald{background:#ecfdf5;}
        .sa-stat-val{font-size:1.25rem;font-weight:800;color:#0f172a;margin:0;}
        .sa-stat-label{font-size:0.7rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;}
        /* Filters */
        .sa-filters{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:1rem 1.25rem;margin-bottom:1.25rem;display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;}
        .sa-filter-group{display:flex;flex-direction:column;gap:0.3rem;}
        .sa-filter-label{font-size:0.7rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;}
        .sa-input,.sa-select{height:38px;border:1.5px solid #e2e8f0;border-radius:9px;padding:0 0.75rem;font-size:0.8125rem;outline:none;background:#fff;color:#1e293b;font-weight:500;transition:border-color .2s;}
        .sa-input:focus,.sa-select:focus{border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.08);}
        .sa-select{padding-right:2rem;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;background-size:12px;}
        /* Table */
        .sa-table-wrap{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;}
        .sa-table-head{display:flex;justify-content:space-between;align-items:center;padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;background:#fafbfc;}
        .sa-table-title{font-size:0.9375rem;font-weight:700;color:#1e293b;}
        table.sa-table{width:100%;border-collapse:collapse;}
        table.sa-table thead tr{background:#f8fafc;}
        table.sa-table th{padding:0.625rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #e2e8f0;white-space:nowrap;}
        table.sa-table tbody tr{border-bottom:1px solid #f1f5f9;transition:background .15s;}
        table.sa-table tbody tr:hover{background:#f8fafc;}
        table.sa-table td{padding:0.75rem 1rem;font-size:0.8125rem;color:#334155;vertical-align:middle;}
        table.sa-table tbody tr:last-child{border-bottom:none;}
        /* Badges */
        .badge{display:inline-flex;align-items:center;gap:0.25rem;padding:0.25rem 0.625rem;border-radius:6px;font-size:0.7rem;font-weight:700;}
        .badge-masuk{background:#dcfce7;color:#15803d;} .badge-koreksi{background:#fef9c3;color:#854d0e;}
        .badge-pos{background:#dbeafe;color:#1e40af;} .badge-neg{background:#fee2e2;color:#991b1b;}
        /* Buttons */
        .sa-btn{display:inline-flex;align-items:center;justify-content:center;gap:0.4rem;padding:0.5625rem 1rem;border-radius:9px;font-size:0.8125rem;font-weight:700;cursor:pointer;transition:all .2s;border:1px solid transparent;text-decoration:none;white-space:nowrap;font-family:inherit;}
        .sa-btn-primary{background:#4f46e5;color:#fff;box-shadow:0 2px 8px rgba(79,70,229,.25);}
        .sa-btn-primary:hover{background:#4338ca;transform:translateY(-1px);}
        .sa-btn-outline{border-color:#e2e8f0;background:#fff;color:#475569;}
        .sa-btn-outline:hover{background:#f8fafc;}
        /* Alert */
        .sa-alert{padding:0.875rem 1.125rem;border-radius:10px;display:flex;align-items:flex-start;gap:0.625rem;font-size:0.8125rem;font-weight:600;margin-bottom:1rem;}
        .sa-alert-success{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;}
        .sa-alert-error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
        /* Empty */
        .sa-empty{text-align:center;padding:3.5rem 1rem;color:#94a3b8;}
        .sa-empty-ico{font-size:3rem;margin-bottom:0.75rem;}
        @media(max-width:700px){.sa-stats{grid-template-columns:1fr;} .sa-top{flex-direction:column;}}
    </style>

    <div class="sa-cr">
        {{-- Breadcrumb --}}
        <div class="sa-bc">
            <a href="{{ route('gudang.dashboard') }}" style="color:#4f46e5;text-decoration:none;font-weight:600;">Gudang</a>
            <span>›</span>
            <span>Penyesuaian Stok</span>
        </div>

        {{-- Header --}}
        <div class="sa-top">
            <div class="sa-top-left">
                <h1>📦 Penyesuaian Stok</h1>
                <p>Tambah atau koreksi stok langsung tanpa PO — rekam otomatis di histori pergerakan stok</p>
            </div>
            <a href="{{ route('gudang.stock-adjustment.create') }}" class="sa-btn sa-btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Penyesuaian
            </a>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
        <div class="sa-alert sa-alert-success">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="sa-alert sa-alert-error">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Stats --}}
        <div class="sa-stats">
            <div class="sa-stat">
                <div class="sa-stat-icon indigo">📥</div>
                <div>
                    <div class="sa-stat-val">{{ number_format($stats['total_masuk'], 0, ',', '.') }}</div>
                    <div class="sa-stat-label">Unit Masuk Bulan Ini</div>
                </div>
            </div>
            <div class="sa-stat">
                <div class="sa-stat-icon amber">🔧</div>
                <div>
                    <div class="sa-stat-val">{{ $stats['total_koreksi'] }}</div>
                    <div class="sa-stat-label">Koreksi Bulan Ini</div>
                </div>
            </div>
            <div class="sa-stat">
                <div class="sa-stat-icon emerald">📅</div>
                <div>
                    <div class="sa-stat-val" style="font-size:1rem;">{{ $stats['bulan'] }}</div>
                    <div class="sa-stat-label">Periode Aktif</div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('gudang.stock-adjustment.index') }}">
            <div class="sa-filters">
                <div class="sa-filter-group" style="flex:1;min-width:200px;">
                    <label class="sa-filter-label">Cari Produk / No. Referensi</label>
                    <input class="sa-input" style="width:100%;" name="search" value="{{ request('search') }}" placeholder="Ketik nama produk atau ADJ-...">
                </div>
                <div class="sa-filter-group">
                    <label class="sa-filter-label">Tipe</label>
                    <select class="sa-select" name="tipe" style="width:160px;">
                        <option value="">Semua Tipe</option>
                        <option value="in" {{ request('tipe')=='in'?'selected':'' }}>📥 Stok Masuk</option>
                        <option value="adjustment" {{ request('tipe')=='adjustment'?'selected':'' }}>🔧 Koreksi</option>
                    </select>
                </div>
                <div class="sa-filter-group">
                    <label class="sa-filter-label">Gudang</label>
                    <select class="sa-select" name="warehouse_id" style="width:160px;">
                        <option value="">Semua Gudang</option>
                        @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}" {{ request('warehouse_id')==$wh->id?'selected':'' }}>{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="sa-btn sa-btn-outline" style="align-self:flex-end;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Filter
                </button>
                @if(request()->hasAny(['search','tipe','warehouse_id']))
                <a href="{{ route('gudang.stock-adjustment.index') }}" class="sa-btn sa-btn-outline" style="align-self:flex-end;">✕ Reset</a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="sa-table-wrap">
            <div class="sa-table-head">
                <span class="sa-table-title">Riwayat Penyesuaian Stok</span>
                <span style="font-size:0.8125rem;color:#94a3b8;">{{ $records->total() }} data</span>
            </div>
            @if($records->isEmpty())
            <div class="sa-empty">
                <div class="sa-empty-ico">📦</div>
                <div style="font-size:1rem;font-weight:700;color:#475569;margin-bottom:0.375rem;">Belum ada data penyesuaian</div>
                <div style="font-size:0.8125rem;">Klik "Tambah Penyesuaian" untuk mulai memasukkan stok.</div>
            </div>
            @else
            <table class="sa-table">
                <thead>
                    <tr>
                        <th>No. Referensi</th>
                        <th>Produk</th>
                        <th>Gudang</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Saldo Akhir</th>
                        <th>Dicatat Oleh</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $r)
                    <tr>
                        <td>
                            <code style="font-size:0.75rem;background:#f1f5f9;padding:0.2rem 0.5rem;border-radius:5px;font-weight:700;color:#4f46e5;">{{ $r->reference_number }}</code>
                        </td>
                        <td>
                            <div style="font-weight:700;color:#1e293b;">{{ $r->product?->name ?? '-' }}</div>
                            <div style="font-size:0.7rem;color:#94a3b8;">{{ $r->product?->sku }}</div>
                        </td>
                        <td style="font-weight:600;color:#475569;">{{ $r->warehouse?->name ?? '-' }}</td>
                        <td>
                            @if($r->type === 'in')
                                <span class="badge badge-masuk">📥 Stok Masuk</span>
                            @else
                                <span class="badge badge-koreksi">🔧 Koreksi</span>
                            @endif
                        </td>
                        <td>
                            @php $isPos = $r->quantity >= 0 && $r->type === 'in'; @endphp
                            @if($r->type === 'in')
                                <span class="badge badge-pos">+{{ number_format($r->quantity, 0, ',', '.') }}</span>
                            @else
                                @php $diff = $r->balance - ($r->balance - $r->quantity); @endphp
                                <span class="badge badge-koreksi">{{ number_format($r->quantity, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-weight:800;color:#0f172a;">{{ number_format($r->balance, 0, ',', '.') }}</span>
                            @if($r->unit) <span style="font-size:0.7rem;color:#94a3b8;"> {{ $r->unit->abbreviation }}</span> @endif
                        </td>
                        <td style="font-size:0.8rem;color:#475569;font-weight:600;">{{ $r->user?->name ?? '-' }}</td>
                        <td style="font-size:0.75rem;color:#94a3b8;white-space:nowrap;">{{ $r->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding:1rem 1.25rem;border-top:1px solid #f1f5f9;">
                {{ $records->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
