<x-app-layout>
    <x-slot name="header">Gudang / Detail Permintaan</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-container">
            {{-- ─── HEADER ─── --}}
            <div class="tr-header-bar">
                <a href="{{ route('gudang.pos_pick.index') }}" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Kembali
                </a>
                <div class="header-title">
                    <h1>{{ $pickOrder->pick_number }}</h1>
                    <span class="status-badge status-{{ $pickOrder->status }}">
                        {{ $pickOrder->status_label }}
                    </span>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success'))
                <div class="tr-alert success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="tr-alert danger">{{ session('error') }}</div>
            @endif

            <div class="tr-grid">
                {{-- ─── LEFT: INFO CARDS ─── --}}
                <div class="tr-left">
                    {{-- Info Card --}}
                    <div class="tr-card info-card">
                        <h3>Informasi Permintaan</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Kasir</span>
                                <span class="info-value">{{ $pickOrder->requester?->name ?? '-' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Tipe POS</span>
                                <span class="info-value">{{ $pickOrder->pos_type === 'eceran' ? 'Eceran' : 'Grosir' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Gudang Sumber</span>
                                <span class="info-value">{{ $pickOrder->warehouse?->name ?? 'Utama' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Waktu Request</span>
                                <span class="info-value">{{ $pickOrder->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            @if($pickOrder->transaction)
                                <div class="info-item">
                                    <span class="info-label">Invoice Transaksi</span>
                                    <a href="{{ route('transaksi.show', $pickOrder->transaction) }}" class="info-link">
                                        {{ $pickOrder->transaction->invoice_number }}
                                    </a>
                                </div>
                            @endif
                            @if($pickOrder->processed_by)
                                <div class="info-item">
                                    <span class="info-label">Diproses Oleh</span>
                                    <span class="info-value">{{ $pickOrder->processor?->name ?? '-' }}</span>
                                </div>
                            @endif
                            @if($pickOrder->ready_at)
                                <div class="info-item">
                                    <span class="info-label">Siap Pada</span>
                                    <span class="info-value">{{ $pickOrder->ready_at->format('d M Y, H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Notes Card --}}
                    @if($pickOrder->notes)
                        <div class="tr-card notes-card">
                            <h3>Catatan</h3>
                            <p>{{ $pickOrder->notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- ─── RIGHT: ITEMS LIST ─── --}}
                <div class="tr-right">
                    <div class="tr-card items-card">
                        <h3>Barang yang Diminta</h3>

                        <div class="items-list">
                            @foreach($pickOrder->items as $item)
                                @php
                                    $stockCheck = $stockChecks[$item->product_id] ?? null;
                                    $sufficient = $stockCheck['sufficient'] ?? false;
                                @endphp
                                <div class="item-row {{ !$sufficient ? 'insufficient' : '' }}">
                                    <div class="item-info">
                                        <span class="item-name">{{ $item->product?->name ?? 'Produk #' . $item->product_id }}</span>
                                        <span class="item-sku">SKU: {{ $item->product?->sku ?? '-' }}</span>
                                    </div>
                                    <div class="item-qty">
                                        <span class="qty-badge">
                                            {{ $item->unit_qty }} {{ $item->unit_name }}
                                        </span>
                                        <span class="qty-base">({{ $item->quantity }} unit dasar)</span>
                                    </div>
                                    <div class="item-stock">
                                        @if($stockCheck)
                                            <span class="stock-badge {{ $sufficient ? 'ok' : 'low' }}">
                                                Stok: {{ $stockCheck['available'] }}
                                            </span>
                                            @if(!$sufficient)
                                                <span class="stock-warning">Stok tidak cukup!</span>
                                            @endif
                                        @else
                                            <span class="stock-badge unknown">Stok: -</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ─── ACTION BUTTONS ─── --}}
                    @if($pickOrder->status === 'pending')
                        <div class="tr-card actions-card">
                            <form action="{{ route('gudang.pos_pick.process', $pickOrder) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-primary">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                    Proses Permintaan
                                </button>
                            </form>
                        </div>
                    @endif

                    @if(in_array($pickOrder->status, ['pending', 'processing']))
                        <div class="tr-card actions-card">
                            <form action="{{ route('gudang.pos_pick.ready', $pickOrder) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label>Catatan Proses (opsional)</label>
                                    <textarea name="notes" rows="2" placeholder="Contoh: Barang sudah disiapkan di rak A..."></textarea>
                                </div>
                                <button type="submit" class="btn-success">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Tandai Siap Diambil
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($pickOrder->status === 'ready')
                        <div class="tr-card actions-card ready-status">
                            <div class="ready-info">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                <h4>Barang Sudah Siap!</h4>
                                <p>Kasir dapat mengambil barang di gudang.</p>
                            </div>
                            <form action="{{ route('gudang.pos_pick.complete', $pickOrder) }}" method="POST" onsubmit="return confirm('Konfirmasi barang sudah diambil kasir dan kurangi stok?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-complete">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 11 12 14 22 4"></polyline>
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                    </svg>
                                    Konfirmasi Pengambilan & Kurangi Stok
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .tr-page-wrapper { background: #f8fafc; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; padding-bottom: 3rem; }
        .tr-container { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem; }

        .tr-header-bar { display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; }
        .btn-back { display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; color: #64748b; text-decoration: none; font-weight: 600; }
        .btn-back:hover { background: #f1f5f9; }
        .header-title { display: flex; align-items: center; gap: 1rem; }
        .header-title h1 { font-size: 1.5rem; font-weight: 800; margin: 0; }

        .status-badge { padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-ready { background: #dcfce7; color: #166534; }
        .status-completed { background: #f1f5f9; color: #64748b; }

        .tr-alert { padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; }
        .tr-alert.success { background: #dcfce7; color: #166534; }
        .tr-alert.danger { background: #fee2e2; color: #991b1b; }

        .tr-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; }
        .tr-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1rem; }
        .tr-card h3 { font-size: 1rem; font-weight: 700; margin: 0 0 1.25rem; color: #0f172a; }

        .info-grid { display: grid; gap: 1rem; }
        .info-item { display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9; }
        .info-item:last-child { border-bottom: none; padding-bottom: 0; }
        .info-label { font-size: 0.875rem; color: #64748b; }
        .info-value { font-size: 0.875rem; font-weight: 600; color: #0f172a; }
        .info-link { color: #4f46e5; text-decoration: none; font-weight: 600; }
        .info-link:hover { text-decoration: underline; }

        .notes-card p { color: #64748b; line-height: 1.6; margin: 0; }

        .items-list { display: flex; flex-direction: column; gap: 0.75rem; }
        .item-row { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 10px; align-items: center; }
        .item-row.insufficient { background: #fef2f2; border: 1px solid #fecaca; }
        .item-name { display: block; font-weight: 600; color: #0f172a; }
        .item-sku { font-size: 0.8rem; color: #64748b; }
        .qty-badge { display: inline-block; background: #4f46e5; color: #fff; padding: 0.35rem 0.75rem; border-radius: 6px; font-weight: 600; font-size: 0.875rem; }
        .qty-base { display: block; font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; }
        .stock-badge { display: inline-block; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; }
        .stock-badge.ok { background: #dcfce7; color: #166534; }
        .stock-badge.low { background: #fee2e2; color: #991b1b; }
        .stock-badge.unknown { background: #f1f5f9; color: #64748b; }
        .stock-warning { display: block; font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem; font-weight: 600; }

        .actions-card form { display: flex; flex-direction: column; gap: 1rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .form-group label { font-size: 0.875rem; font-weight: 600; color: #0f172a; }
        .form-group textarea { padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; resize: vertical; }
        .form-group textarea:focus { outline: none; border-color: #4f46e5; }

        .btn-primary, .btn-success, .btn-complete { display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.875rem; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .btn-primary { background: #4f46e5; color: #fff; }
        .btn-primary:hover { background: #4338ca; }
        .btn-success { background: #10b981; color: #fff; }
        .btn-success:hover { background: #059669; }
        .btn-complete { background: #0f172a; color: #fff; }
        .btn-complete:hover { background: #000; }

        .ready-status { text-align: center; }
        .ready-info { color: #10b981; margin-bottom: 1.5rem; }
        .ready-info h4 { margin: 0.75rem 0 0.25rem; font-size: 1.25rem; }
        .ready-info p { margin: 0; color: #64748b; }

        @media (max-width: 768px) {
            .tr-grid { grid-template-columns: 1fr; }
            .item-row { grid-template-columns: 1fr; text-align: center; }
            .tr-header-bar { flex-wrap: wrap; }
        }
    </style>
    @endpush
</x-app-layout>
