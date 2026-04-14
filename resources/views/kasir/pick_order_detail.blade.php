<x-app-layout>
    <x-slot name="header">Kasir / Detail Pengambilan</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-container">
            {{-- ─── HEADER ─── --}}
            <div class="tr-header-bar">
                <a href="{{ route('kasir.pick_orders') }}" class="btn-back">
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

            {{-- ─── STATUS FLOW ─── --}}
            <div class="status-flow">
                <div class="flow-item {{ in_array($pickOrder->status, ['pending', 'processing', 'ready', 'completed']) ? 'active' : '' }}">
                    <div class="flow-dot"></div>
                    <span>Menunggu</span>
                </div>
                <div class="flow-line {{ in_array($pickOrder->status, ['processing', 'ready', 'completed']) ? 'active' : '' }}"></div>
                <div class="flow-item {{ in_array($pickOrder->status, ['processing', 'ready', 'completed']) ? 'active' : '' }}">
                    <div class="flow-dot"></div>
                    <span>Diproses</span>
                </div>
                <div class="flow-line {{ in_array($pickOrder->status, ['ready', 'completed']) ? 'active' : '' }}"></div>
                <div class="flow-item {{ in_array($pickOrder->status, ['ready', 'completed']) ? 'active' : '' }}">
                    <div class="flow-dot"></div>
                    <span>Siap</span>
                </div>
                <div class="flow-line {{ $pickOrder->status === 'completed' ? 'active' : '' }}"></div>
                <div class="flow-item {{ $pickOrder->status === 'completed' ? 'active' : '' }}">
                    <div class="flow-dot"></div>
                    <span>Selesai</span>
                </div>
            </div>

            {{-- ─── READY ALERT ─── --}}
            @if($pickOrder->status === 'ready')
                <div class="ready-alert">
                    <div class="ready-icon">🎉</div>
                    <div class="ready-text">
                        <h4>Barang Sudah Siap!</h4>
                        <p>Silakan ke gudang <strong>{{ $pickOrder->warehouse?->name ?? 'Utama' }}</strong> untuk mengambil barang Anda.</p>
                        @if($pickOrder->processor)
                            <p class="processor-info">Disiapkan oleh: {{ $pickOrder->processor->name }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <div class="tr-grid">
                {{-- ─── LEFT: INFO ─── --}}
                <div class="tr-left">
                    <div class="tr-card info-card">
                        <h3>Informasi</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Waktu Request</span>
                                <span class="info-value">{{ $pickOrder->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Gudang</span>
                                <span class="info-value">{{ $pickOrder->warehouse?->name ?? 'Utama' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Tipe POS</span>
                                <span class="info-value">{{ $pickOrder->pos_type === 'eceran' ? 'Eceran' : 'Grosir' }}</span>
                            </div>
                            @if($pickOrder->transaction)
                                <div class="info-item">
                                    <span class="info-label">Invoice</span>
                                    <a href="{{ route('transaksi.show', $pickOrder->transaction) }}" class="info-link">
                                        {{ $pickOrder->transaction->invoice_number }}
                                    </a>
                                </div>
                            @endif
                            @if($pickOrder->ready_at)
                                <div class="info-item">
                                    <span class="info-label">Siap Sejak</span>
                                    <span class="info-value text-success">{{ $pickOrder->ready_at->diffForHumans() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($pickOrder->notes)
                        <div class="tr-card notes-card">
                            <h3>Catatan dari Gudang</h3>
                            <p>{{ $pickOrder->notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- ─── RIGHT: ITEMS ─── --}}
                <div class="tr-right">
                    <div class="tr-card items-card">
                        <h3>Barang yang Diminta</h3>
                        <div class="items-list">
                            @foreach($pickOrder->items as $item)
                                <div class="item-row">
                                    <div class="item-info">
                                        <span class="item-name">{{ $item->product?->name ?? 'Produk #' . $item->product_id }}</span>
                                        <span class="item-sku">SKU: {{ $item->product?->sku ?? '-' }}</span>
                                    </div>
                                    <div class="item-qty">
                                        <span class="qty-badge">{{ $item->unit_qty }} {{ $item->unit_name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ─── ACTION CARD ─── --}}
                    @if($pickOrder->status === 'ready')
                        <div class="tr-card action-card">
                            <div class="action-info">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                <h4>Barang Sudah Siap Diambil!</h4>
                                <p>Silakan menuju gudang untuk mengambil barang Anda. Setelah diambil, admin gudang akan menandai selesai.</p>
                            </div>
                        </div>
                    @elseif($pickOrder->status === 'pending')
                        <div class="tr-card waiting-card">
                            <div class="waiting-info">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <h4>Menunggu Proses Gudang</h4>
                                <p>Permintaan Anda sedang menunggu untuk diproses oleh admin gudang.</p>
                            </div>
                        </div>
                    @elseif($pickOrder->status === 'processing')
                        <div class="tr-card processing-card">
                            <div class="processing-info">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                </svg>
                                <h4>Sedang Disiapkan</h4>
                                <p>Admin gudang sedang menyiapkan barang Anda.</p>
                                @if($pickOrder->processor)
                                    <p class="processor">Diproses oleh: {{ $pickOrder->processor->name }}</p>
                                @endif
                            </div>
                        </div>
                    @elseif($pickOrder->status === 'completed')
                        <div class="tr-card completed-card">
                            <div class="completed-info">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <h4>Pengambilan Selesai</h4>
                                <p>Barang sudah berhasil diambil dari gudang.</p>
                                @if($pickOrder->confirmed_at)
                                    <p class="confirmed-at">Selesai: {{ $pickOrder->confirmed_at->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .tr-page-wrapper { background: #f8fafc; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; padding-bottom: 3rem; }
        .tr-container { max-width: 900px; margin: 0 auto; padding: 2rem 1.5rem; }

        .tr-header-bar { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
        .btn-back { display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; color: #64748b; text-decoration: none; font-weight: 600; }
        .btn-back:hover { background: #f1f5f9; }
        .header-title { display: flex; align-items: center; gap: 1rem; }
        .header-title h1 { font-size: 1.5rem; font-weight: 800; margin: 0; }

        .status-badge { padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-ready { background: #dcfce7; color: #166534; }
        .status-completed { background: #f1f5f9; color: #64748b; }

        .status-flow { display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; padding: 1.5rem; background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; }
        .flow-item { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; color: #94a3b8; font-size: 0.8rem; font-weight: 600; }
        .flow-item.active { color: #0f172a; }
        .flow-dot { width: 16px; height: 16px; border-radius: 50%; background: #e2e8f0; border: 2px solid #cbd5e1; }
        .flow-item.active .flow-dot { background: #10b981; border-color: #10b981; }
        .flow-line { width: 40px; height: 2px; background: #e2e8f0; }
        .flow-line.active { background: #10b981; }

        .ready-alert { display: flex; align-items: center; gap: 1rem; background: linear-gradient(135deg, #dcfce7, #d1fae5); border: 1px solid #86efac; border-radius: 16px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
        .ready-icon { font-size: 2.5rem; }
        .ready-text h4 { margin: 0 0 0.25rem; font-size: 1.1rem; color: #166534; }
        .ready-text p { margin: 0 0 0.5rem; color: #15803d; font-size: 0.9rem; }
        .processor-info { font-size: 0.8rem; color: #64748b; margin: 0; }

        .tr-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; }
        .tr-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1rem; }
        .tr-card h3 { font-size: 1rem; font-weight: 700; margin: 0 0 1.25rem; color: #0f172a; }

        .info-grid { display: grid; gap: 1rem; }
        .info-item { display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9; }
        .info-item:last-child { border-bottom: none; padding-bottom: 0; }
        .info-label { font-size: 0.875rem; color: #64748b; }
        .info-value { font-size: 0.875rem; font-weight: 600; color: #0f172a; }
        .info-value.text-success { color: #16a34a; }
        .info-link { color: #4f46e5; text-decoration: none; font-weight: 600; }
        .info-link:hover { text-decoration: underline; }

        .notes-card p { color: #64748b; line-height: 1.6; margin: 0; background: #f8fafc; padding: 1rem; border-radius: 8px; }

        .items-list { display: flex; flex-direction: column; gap: 0.75rem; }
        .item-row { display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8fafc; border-radius: 10px; }
        .item-name { display: block; font-weight: 600; color: #0f172a; }
        .item-sku { font-size: 0.8rem; color: #64748b; }
        .qty-badge { display: inline-block; background: #4f46e5; color: #fff; padding: 0.35rem 0.75rem; border-radius: 6px; font-weight: 600; font-size: 0.875rem; }

        .action-card, .waiting-card, .processing-card, .completed-card { text-align: center; }
        .action-info, .waiting-info, .processing-info, .completed-info { padding: 1rem 0; }
        .action-info h4 { color: #16a34a; margin: 0.75rem 0 0.5rem; }
        .waiting-info h4 { color: #d97706; margin: 0.75rem 0 0.5rem; }
        .processing-info h4 { color: #2563eb; margin: 0.75rem 0 0.5rem; }
        .completed-info h4 { color: #6b7280; margin: 0.75rem 0 0.5rem; }
        .action-info p, .waiting-info p, .processing-info p, .completed-info p { color: #64748b; margin: 0; font-size: 0.9rem; }
        .processor { font-size: 0.8rem; color: #94a3b8; margin-top: 0.5rem !important; }
        .confirmed-at { font-size: 0.8rem; color: #94a3b8; margin-top: 0.5rem !important; }

        @media (max-width: 768px) {
            .tr-grid { grid-template-columns: 1fr; }
            .status-flow { flex-wrap: wrap; }
            .flow-line { width: 20px; }
        }
    </style>
    @endpush
</x-app-layout>
