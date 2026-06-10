<x-app-layout>
    <x-slot name="header">Rekap Stok Barang</x-slot>
    
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        * { box-sizing: border-box; }
        
        .stok-gudang-page {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            padding: 2rem 2.5rem;
            background: #f8fafc;
            min-height: calc(100vh - 64px);
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            padding: 1.5rem;
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .header-text h1 {
            font-size: 1.875rem;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 0.5rem 0;
            display: flex;
            gap: 1rem;
            align-items: center;
            letter-spacing: -0.02em;
        }
        
        .header-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4f46e5;
        }
        
        .header-text p {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
            line-height: 1.5;
        }
        
        .header-status {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        
        .status-badge.masked {
            background: #fef3c7;
            color: #92400e;
            border-color: #fcd34d;
        }
        
        .status-badge.active {
            background: #ecfdf5;
            color: #059669;
            border-color: #a7f3d0;
        }
        
        .header-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 700;
            text-decoration: none;
            border: 1px solid;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }
        
        .btn-outline {
            background: white;
            border-color: #e2e8f0;
            color: #475569;
        }
        
        .btn-outline:hover { 
            background: #f8fafc; 
            color: #0f172a; 
            border-color: #cbd5e1; 
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(79,70,229,0.25);
        }
        
        .btn-primary:hover:not(:disabled) { 
            transform: translateY(-1px); 
            box-shadow: 0 6px 16px rgba(79,70,229,0.35); 
        }
        
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        
        .kpi-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 1.75rem;
            display: flex;
            gap: 1.5rem;
            align-items: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }
        
        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
        }
        
        .kpi-card.blue::before { background: linear-gradient(180deg, #3b82f6, #2563eb); }
        .kpi-card.purple::before { background: linear-gradient(180deg, #8b5cf6, #7c3aed); }
        .kpi-card.green::before { background: linear-gradient(180deg, #10b981, #059669); }
        .kpi-card.red::before { background: linear-gradient(180deg, #ef4444, #dc2626); }
        
        .kpi-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .kpi-card.blue .kpi-icon { background: #eff6ff; color: #2563eb; }
        .kpi-card.purple .kpi-icon { background: #f5f3ff; color: #7c3aed; }
        .kpi-card.green .kpi-icon { background: #ecfdf5; color: #059669; }
        .kpi-card.red .kpi-icon { background: #fef2f2; color: #dc2626; }
        
        .kpi-content { flex: 1; }
        
        .kpi-label {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 0.375rem;
        }
        
        .kpi-value {
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: -0.025em;
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        
        .kpi-card.blue .kpi-value { color: #2563eb; }
        .kpi-card.purple .kpi-value { color: #7c3aed; }
        .kpi-card.green .kpi-value { color: #059669; }
        .kpi-card.red .kpi-value { color: #dc2626; }
        
        .kpi-footnote {
            font-size: 0.75rem;
            color: #94a3b8;
            line-height: 1.4;
        }
        
        .filter-section {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 1.75rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .filter-title {
            font-size: 1.125rem;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .filter-title svg {
            color: #4f46e5;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            align-items: end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .filter-group.full { grid-column: 1 / -1; }
        
        .filter-label {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }
        
        .filter-input,
        .filter-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 0.875rem;
            color: #1e293b;
            outline: none;
            transition: all 0.2s;
            font-family: inherit;
        }
        
        .filter-input:focus,
        .filter-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background: #fafafa;
        }
        
        .filter-search {
            position: relative;
        }
        
        .filter-search svg {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }
        
        .filter-search .filter-input {
            padding-left: 2.75rem;
        }
        
        .filter-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        .btn-filter {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 800;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-filter-apply {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: white;
            box-shadow: 0 4px 12px rgba(79,70,229,0.25);
        }
        
        .btn-filter-apply:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79,70,229,0.35);
            background: linear-gradient(135deg, #4338ca, #3730a3);
        }
        
        .btn-filter-reset {
            background: #f8fafc;
            color: #475569;
            border: 1.5px solid #e2e8f0;
        }
        
        .btn-filter-reset:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #0f172a;
        }
        
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
            align-items: center;
        }
        
        .active-filters-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            background: #eef2ff;
            color: #4f46e5;
        }
        
        .filter-tag button {
            background: none;
            border: none;
            color: #4f46e5;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            font-weight: 900;
        }
        
        .filter-tag button:hover { color: #dc2626; }
        
        .table-section {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .table-scroll {
            overflow-x: auto;
            border-radius: 16px;
        }
        
        .table-wrapper {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 1000px;
        }
        
        .table-wrapper thead {
            background: linear-gradient(180deg, #f8fafc, #f1f5f9);
            border-bottom: 2px solid #e2e8f0;
        }
        
        .table-wrapper th {
            padding: 1.25rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            white-space: nowrap;
            position: relative;
        }
        
        .table-wrapper th.sortable {
            cursor: pointer;
            user-select: none;
            transition: all 0.2s;
        }
        
        .table-wrapper th.sortable:hover {
            color: #4f46e5;
            background: #eef2ff;
        }
        
        .table-wrapper th.text-right { text-align: right; }
        
        .sort-arrow {
            display: inline-block;
            margin-left: 0.5rem;
            font-size: 0.75rem;
            opacity: 0.5;
            transition: opacity 0.2s;
        }
        
        .table-wrapper th.sortable.active .sort-arrow {
            opacity: 1;
            color: #4f46e5;
        }
        
        .table-wrapper td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
            color: #374151;
            vertical-align: middle;
            transition: background 0.15s;
        }
        
        .table-wrapper tbody tr {
            transition: background 0.15s;
        }
        
        .table-wrapper tbody tr:hover {
            background: #f8fafc;
        }
        
        .table-wrapper tbody tr:last-child td {
            border-bottom: none;
        }
        
        .table-wrapper tbody tr:nth-child(even) {
            background: #fafafa;
        }
        
        .table-wrapper tbody tr:nth-child(even):hover {
            background: #f1f5f9;
        }
        
        .product-name {
            font-weight: 700;
            color: #0f172a;
            font-size: 0.875rem;
        }
        
        .product-sku {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.125rem;
            font-family: 'JetBrains Mono', monospace;
        }
        
        .category-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        
        .warehouse-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            background: #eff6ff;
            color: #1d4ed8;
        }
        
        .location-text {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .batch-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #1e293b;
        }
        
        .expiry-date {
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            margin-top: 0.25rem;
        }
        
        .expiry-date.expired { color: #dc2626; }
        .expiry-date.warning { color: #d97706; }
        .expiry-date.ok { color: #059669; }
        
        .stock-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 60px;
            padding: 0.5rem 0.875rem;
            border-radius: 10px;
            font-weight: 900;
            font-size: 1rem;
            position: relative;
            transition: all 0.2s;
        }
        
        .stock-badge.ok { 
            background: #ecfdf5; 
            color: #059669; 
            border: 2px solid #a7f3d0;
        }
        .stock-badge.low { 
            background: #fef3c7; 
            color: #92400e; 
            border: 2px solid #fcd34d;
            animation: pulse 2s infinite;
        }
        .stock-badge.empty { 
            background: #fef2f2; 
            color: #dc2626; 
            border: 2px solid #fca5a5;
        }
        .stock-badge.masked { 
            background: #f1f5f9; 
            color: #64748b; 
            font-size: 0.875rem;
            border: 2px dashed #cbd5e1;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }
        
        .stock-unit {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.25rem;
            text-align: right;
        }
        
        .min-stock-warning {
            font-size: 0.75rem;
            color: #92400e;
            font-weight: 700;
            margin-top: 0.25rem;
            text-align: right;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        
        .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.25rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .empty-title {
            font-size: 1.125rem;
            font-weight: 800;
            color: #475569;
            margin-bottom: 0.5rem;
        }
        
        .empty-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }
        
        .pagination-wrapper {
            padding: 1rem 1.25rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            background: white;
        }
        
        .pagination-info {
            font-size: 0.8125rem;
            color: #64748b;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .stok-gudang-page { padding: 1.5rem 1rem; }
            
            .page-header { flex-direction: column; }
            
            .header-actions { width: 100%; }
            
            .header-actions .btn { flex: 1; justify-content: center; }
            
            .kpi-grid { grid-template-columns: 1fr; }
            
            .filter-actions { width: 100%; }
            
            .filter-actions .btn-filter { flex: 1; justify-content: center; }
            
            .pagination-wrapper { flex-direction: column; text-align: center; }
        }
    </style>
    @endpush

    <div class="stok-gudang-page">
        
        {{-- Page Header --}}
        <div class="page-header">
            <div class="header-text">
                <h1>
                    <div class="header-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                    </div>
                    Rekap Stok Barang
                </h1>
                <p>Detail penyebaran stok produk di seluruh gudang dan lokasi rak</p>
                <div class="header-status">
                    <span class="status-badge active">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        {{ $totalRecords }} record aktif
                    </span>
                    @if($maskStock)
                    <span class="status-badge masked">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2a10 10 0 0 0-10 10c0 5.523 4.477 10 10 10s10-4.477 10-10S17.523 2 12 2z"/>
                            <path d="M8 12a4 4 0 0 0 8 0"/>
                        </svg>
                        Stok disembunyikan (opname aktif)
                    </span>
                    @endif
                    @if($lowStockCount > 0)
                    <span class="status-badge" style="background: #fef3c7; color: #92400e; border-color: #fcd34d;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        {{ $lowStockCount }} stok hampir habis
                    </span>
                    @endif
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('gudang.stok.export', request()->only(['warehouse_id','category_id','search'])) }}" class="btn btn-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Terima Barang
                </a>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="kpi-grid">
            <div class="kpi-card blue">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path d="M9 9h6v6H9z"/>
                    </svg>
                </div>
                <div class="kpi-content">
                    <div class="kpi-label">Total Record Stok</div>
                    <div class="kpi-value">{{ number_format($totalRecords) }}</div>
                    <div class="kpi-footnote">Record aktif di semua gudang</div>
                </div>
            </div>
            
            <div class="kpi-card purple">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <div class="kpi-content">
                    <div class="kpi-label">Total Nilai Stok</div>
                    <div class="kpi-value">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</div>
                    <div class="kpi-footnote">Estimasi berdasarkan harga beli</div>
                </div>
            </div>
            
            <div class="kpi-card green">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                </div>
                <div class="kpi-content">
                    <div class="kpi-label">Gudang Aktif</div>
                    <div class="kpi-value">{{ $activeWarehouses }}</div>
                    <div class="kpi-footnote">Gudang dengan stok tersimpan</div>
                </div>
            </div>
            
            <div class="kpi-card red">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <div class="kpi-content">
                    <div class="kpi-label">Stok Hampir Habis</div>
                    <div class="kpi-value">{{ $lowStockCount }}</div>
                    <div class="kpi-footnote">Di bawah minimum stok</div>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="filter-section">
            <div class="filter-header">
                <div class="filter-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                    Filter Stok Barang
                </div>
                @if(request('search') || request('warehouse_id') || request('category_id'))
                    <a href="{{ route('gudang.stok') }}" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.5rem 1rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Reset Semua
                    </a>
                @endif
            </div>
            
            <form method="GET" action="{{ route('gudang.stok') }}" id="filter-form">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label class="filter-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                            Gudang
                        </label>
                        <select name="warehouse_id" class="filter-select">
                            <option value="">Semua Gudang</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ $warehouseId == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                            </svg>
                            Kategori
                        </label>
                        <select name="category_id" class="filter-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            Cari Barang
                        </label>
                        <div class="filter-search">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang atau SKU..." class="filter-input" id="search-input">
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter btn-filter-apply">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
                
                {{-- Active Filters --}}
                @if(request('search') || request('warehouse_id') || request('category_id'))
                <div class="active-filters">
                    <span class="active-filters-label">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                        </svg>
                        Filter Aktif:
                    </span>
                    
                    @if(request('search'))
                        <span class="filter-tag">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            Pencarian: "{{ request('search') }}"
                            <button type="button" onclick="window.location.href='{{ route('gudang.stok', request()->except('search')) }}'">×</button>
                        </span>
                    @endif
                    
                    @if(request('warehouse_id'))
                        @php $whName = $warehouses->firstWhere('id', request('warehouse_id'))?->name ?? '--'; @endphp
                        <span class="filter-tag">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                            Gudang: {{ $whName }}
                            <button type="button" onclick="window.location.href='{{ route('gudang.stok', request()->except('warehouse_id')) }}'">×</button>
                        </span>
                    @endif
                    
                    @if(request('category_id'))
                        @php $catName = $categories->firstWhere('id', request('category_id'))?->name ?? '--'; @endphp
                        <span class="filter-tag">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                            </svg>
                            Kategori: {{ $catName }}
                            <button type="button" onclick="window.location.href='{{ route('gudang.stok', request()->except('category_id')) }}'">×</button>
                        </span>
                    @endif
                </div>
                @endif
            </form>
        </div>

        {{-- Table Section --}}
        <div class="table-section">
            <div class="table-scroll">
                <table class="table-wrapper">
                    <thead>
                        <tr>
                            @php
                                $sortUrl = function($col) use ($sort, $dir) {
                                    $newDir = ($sort === $col && $dir === 'asc') ? 'desc' : 'asc';
                                    return route('gudang.stok', array_merge(request()->except(['sort','dir','page']), ['sort' => $col, 'dir' => $newDir]));
                                };
                                $arrow = function($col) use ($sort, $dir) {
                                    if ($sort !== $col) return '<span class="sort-arrow">&#8693;</span>';
                                    return $dir === 'asc' ? '<span class="sort-arrow">&#8593;</span>' : '<span class="sort-arrow">&#8595;</span>';
                                };
                            @endphp
                            <th class="sortable {{ $sort === 'product' ? 'active' : '' }}" onclick="location.href='{{ $sortUrl('product') }}'">
                                Barang / SKU {!! $arrow('product') !!}
                            </th>
                            <th class="sortable {{ $sort === 'category' ? 'active' : '' }}" onclick="location.href='{{ $sortUrl('category') }}'">
                                Kategori {!! $arrow('category') !!}
                            </th>
                            <th class="sortable {{ $sort === 'warehouse' ? 'active' : '' }}" onclick="location.href='{{ $sortUrl('warehouse') }}'">
                                Gudang & Lokasi {!! $arrow('warehouse') !!}
                            </th>
                            <th>Batch / Expired</th>
                            <th class="text-right sortable {{ $sort === 'stock' ? 'active' : '' }}" onclick="location.href='{{ $sortUrl('stock') }}'">
                                Sisa Stok {!! $arrow('stock') !!}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                            @php
                                $minStk = $stock->product->min_stock ?? 0;
                                $isLow = $stock->stock > 0 && $stock->stock <= $minStk;
                                $isEmpty = $stock->stock == 0;
                                $displayStock = $maskStock ? '***' : number_format($stock->stock);
                            @endphp
                            <tr>
                                <td>
                                    <div class="product-name">{{ $stock->product->name }}</div>
                                    <div class="product-sku">SKU: {{ $stock->product->sku }}</div>
                                </td>
                                <td>
                                    <span class="category-badge">{{ $stock->product->category->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="warehouse-badge">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                        </svg>
                                        {{ $stock->warehouse->name }}
                                    </span>
                                    @if($stock->location)
                                        <div class="location-text">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                <circle cx="12" cy="10" r="3"/>
                                            </svg>
                                            Rak: {{ $stock->location->name }}
                                        </div>
                                    @else
                                        <div class="location-text">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"/>
                                                <line x1="12" y1="8" x2="12" y2="12"/>
                                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                                            </svg>
                                            Area Umum
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($stock->batch_number)
                                        <div class="batch-text">{{ $stock->batch_number }}</div>
                                    @endif
                                    @if($stock->expired_date)
                                        @php
                                            $expDate = \Carbon\Carbon::parse($stock->expired_date);
                                            $daysLeft = now()->diffInDays($expDate, false);
                                        @endphp
                                        <div class="expiry-date {{ $expDate->isPast() ? 'expired' : ($daysLeft <= 30 ? 'warning' : 'ok') }}">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"/>
                                                <polyline points="12 6 12 12 16 14"/>
                                            </svg>
                                            @if($expDate->isPast())
                                                Kadaluarsa ({{ $expDate->format('d M Y') }})
                                            @else
                                                {{ $expDate->format('d M Y') }}
                                                @if($daysLeft <= 30)
                                                    <span style="font-size: 0.6875rem;">({{ (int)$daysLeft }} hari)</span>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <span style="color: #cbd5e1;">-</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($maskStock)
                                        <span class="stock-badge masked">***</span>
                                    @elseif($isEmpty)
                                        <span class="stock-badge empty">0</span>
                                    @elseif($isLow)
                                        <span class="stock-badge low">{{ $displayStock }}</span>
                                        <div class="min-stock-warning">Min: {{ number_format($minStk) }}</div>
                                    @else
                                        <span class="stock-badge ok">{{ $displayStock }}</span>
                                    @endif
                                    @if(!$maskStock)
                                        <div class="stock-unit">{{ $stock->product->unit->abbreviation ?? '' }}</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#a5b4fc" stroke-width="1.5">
                                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                                <line x1="12" y1="22.08" x2="12" y2="12"/>
                                            </svg>
                                        </div>
                                        <div class="empty-title">Data Stok Kosong</div>
                                        <div class="empty-subtitle">Tidak ada stok barang yang sesuai dengan kriteria pencarian.</div>
                                        <a href="{{ route('gudang.stok') }}" class="btn btn-primary">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="1 4 1 10 7 10"/>
                                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                                            </svg>
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
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Menampilkan {{ $stocks->firstItem() }} - {{ $stocks->lastItem() }} dari {{ $stocks->total() }} record
                    </div>
                    <div>{{ $stocks->withQueryString()->links() }}</div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const filterForm = document.getElementById('filter-form');
            const warehouseSelect = document.querySelector('select[name="warehouse_id"]');
            const categorySelect = document.querySelector('select[name="category_id"]');
            
            // Real-time search dengan debounce
            if (searchInput) {
                let timer;
                searchInput.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => {
                        filterForm.submit();
                    }, 400);
                });
            }
            
            // Auto-submit saat select berubah
            if (warehouseSelect) {
                warehouseSelect.addEventListener('change', () => {
                    filterForm.submit();
                });
            }
            
            if (categorySelect) {
                categorySelect.addEventListener('change', () => {
                    filterForm.submit();
                });
            }
            
            // Highlight row dengan stok rendah
            const highlightLowStockRows = () => {
                const rows = document.querySelectorAll('.table-wrapper tbody tr');
                rows.forEach(row => {
                    const stockBadge = row.querySelector('.stock-badge.low');
                    const emptyBadge = row.querySelector('.stock-badge.empty');
                    
                    if (stockBadge) {
                        row.style.borderLeft = '3px solid #f59e0b';
                        row.style.backgroundColor = '#fffbeb';
                    } else if (emptyBadge) {
                        row.style.borderLeft = '3px solid #ef4444';
                        row.style.backgroundColor = '#fef2f2';
                    }
                });
            };
            
            // Jalankan highlight setelah DOM siap
            setTimeout(highlightLowStockRows, 100);
            
            // Tambahkan loading indicator
            filterForm.addEventListener('submit', () => {
                const submitBtn = filterForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = `
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" stroke-dasharray="15.9 15.9" stroke-dashoffset="0">
                                <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite"/>
                            </circle>
                        </svg>
                        Memproses...
                    `;
                    submitBtn.disabled = true;
                }
            });
            
            // Tambahkan tooltip untuk stok yang dimask
            const addMaskTooltips = () => {
                const maskedBadges = document.querySelectorAll('.stock-badge.masked');
                maskedBadges.forEach(badge => {
                    badge.title = 'Stok disembunyikan karena sedang ada proses opname';
                    badge.style.cursor = 'help';
                });
            };
            
            setTimeout(addMaskTooltips, 100);
        });
    </script>
    @endpush
</x-app-layout>
