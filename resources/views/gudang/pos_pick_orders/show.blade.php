<x-app-layout>
    <x-slot name="header">Gudang / Detail Persiapan Barang</x-slot>

    <div class="pk-page">
        <div class="pk-container">

            {{-- ─── HEADER ─── --}}
            <div class="pk-header">
                <a href="{{ route('gudang.pos_pick.index') }}" class="pk-back">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    Kembali
                </a>
                <div class="pk-header-main">
                    <div class="pk-title-group">
                        <h1 class="pk-title">{{ $pickOrder->pick_number }}</h1>
                        <span class="pk-subtitle-text">
                            Persiapan Barang dari POS
                            @if(str_contains($pickOrder->notes ?? '', '[TAMBAHAN]'))
                                <span style="background:#fef3c7;color:#92400e;font-size:11px;font-weight:700;padding:2px 8px;border-radius:4px;margin-left:8px;">+ Tambahan Item</span>
                            @endif
                        </span>
                    </div>
                    @php
                        $statusCfg = [
                            'pending'    => ['bg' => '#fef3c7', 'fg' => '#92400e', 'ring' => '#fcd34d', 'label' => 'Menunggu',   'step' => 1],
                            'processing' => ['bg' => '#dbeafe', 'fg' => '#1e40af', 'ring' => '#93c5fd', 'label' => 'Diproses',   'step' => 2],
                            'ready'      => ['bg' => '#dcfce7', 'fg' => '#166534', 'ring' => '#86efac', 'label' => 'Siap',       'step' => 3],
                            'completed'  => ['bg' => '#f1f5f9', 'fg' => '#475569', 'ring' => '#cbd5e1', 'label' => 'Selesai',    'step' => 4],
                            'cancelled'  => ['bg' => '#fef2f2', 'fg' => '#dc2626', 'ring' => '#fca5a5', 'label' => 'Dibatalkan', 'step' => 0],
                        ];
                        $sc = $statusCfg[$pickOrder->status] ?? $statusCfg['completed'];
                        $currentStep = $sc['step'];
                    @endphp
                    <span class="pk-status-pill" style="background:{{ $sc['bg'] }};color:{{ $sc['fg'] }};box-shadow:0 0 0 2px {{ $sc['ring'] }};">{{ $sc['label'] }}</span>
                </div>
            </div>

            {{-- ─── WORKFLOW STEPPER ─── --}}
            <div class="pk-stepper">
                @php $steps = [
                    1 => ['label' => 'Menunggu',   'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                    2 => ['label' => 'Diproses',   'icon' => '<path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/>'],
                    3 => ['label' => 'Siap',       'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
                    4 => ['label' => 'Selesai',    'icon' => '<polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>'],
                ]; @endphp
                @foreach($steps as $num => $step)
                    @php $isDone = $currentStep > $num; $isCurrent = $currentStep === $num; @endphp
                    @if($num > 1)<div class="pk-step-line {{ $isDone || $isCurrent ? 'pk-step-line-done' : '' }}"></div>@endif
                    <div class="pk-step {{ $isCurrent ? 'pk-step-current' : '' }} {{ $isDone ? 'pk-step-done' : '' }}">
                        <div class="pk-step-circle">
                            @if($isDone)
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            @else
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $step['icon'] !!}</svg>
                            @endif
                        </div>
                        <span class="pk-step-label">{{ $step['label'] }}</span>
                    </div>
                @endforeach
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success'))
                <div class="pk-alert pk-alert-success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="pk-alert pk-alert-danger">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="pk-grid">
                {{-- ═══ LEFT COLUMN ═══ --}}
                <div class="pk-col-left">

                    {{-- Pembeli --}}
                    <div class="pk-card">
                        <div class="pk-card-hdr">
                            <div class="pk-card-ico pk-ico-blue">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <h3>Pembeli</h3>
                        </div>
                        @if($pickOrder->transaction?->customer)
                            <div class="pk-buyer">
                                <div class="pk-buyer-avatar">{{ strtoupper(substr($pickOrder->transaction->customer->name, 0, 1)) }}</div>
                                <div>
                                    <div class="pk-buyer-name">{{ $pickOrder->transaction->customer->name }}</div>
                                    @if($pickOrder->transaction->customer->phone)
                                        <div class="pk-buyer-phone">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                            {{ $pickOrder->transaction->customer->phone }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="pk-buyer-empty">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:#cbd5e1"><path d="M17 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <span>Umum <em>(tanpa data pembeli)</em></span>
                            </div>
                        @endif
                    </div>

                    {{-- Informasi --}}
                    <div class="pk-card">
                        <div class="pk-card-hdr">
                            <div class="pk-card-ico pk-ico-green">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            </div>
                            <h3>Informasi Transaksi</h3>
                        </div>
                        <div class="pk-rows">
                            <div class="pk-row">
                                <span class="pk-row-key">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    Kasir
                                </span>
                                <span class="pk-row-val">{{ $pickOrder->requester?->name ?? '-' }}</span>
                            </div>
                            <div class="pk-row">
                                <span class="pk-row-key">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                    Tipe POS
                                </span>
                                <span class="pk-row-val">
                                    <span class="pk-tag pk-tag-{{ $pickOrder->pos_type }}">{{ $pickOrder->pos_type === 'eceran' ? 'Eceran' : 'Grosir' }}</span>
                                </span>
                            </div>
                            <div class="pk-row">
                                <span class="pk-row-key">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    Gudang
                                </span>
                                <span class="pk-row-val">{{ $pickOrder->warehouse?->name ?? 'Utama' }}</span>
                            </div>
                            <div class="pk-row">
                                <span class="pk-row-key">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    Waktu
                                </span>
                                <span class="pk-row-val">{{ $pickOrder->transaction?->created_at?->format('d M Y, H:i') ?? '-' }}</span>
                            </div>
                            @if($pickOrder->transaction)
                                <div class="pk-row">
                                    <span class="pk-row-key">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        Invoice
                                    </span>
                                    <a href="{{ route('transaksi.show', $pickOrder->transaction) }}" class="pk-row-link">{{ $pickOrder->transaction->invoice_number }}</a>
                                </div>
                                @if($pickOrder->transaction->delivery_status)
                                    @php
                                        $delivMap = [
                                            'pickup'      => 'Ambil di Tempat',
                                            'delivery'    => 'Diantar',
                                            'in_transit'  => 'Sedang Diantar',
                                            'completed'   => 'Selesai',
                                            'pending'     => 'Menunggu Kirim',
                                        ];
                                    @endphp
                                    <div class="pk-row">
                                        <span class="pk-row-key">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                            Pengiriman
                                        </span>
                                        <span class="pk-row-val">
                                            <span class="pk-tag pk-tag-deliv">{{ $delivMap[$pickOrder->transaction->delivery_status] ?? str_replace('_', ' ', ucfirst($pickOrder->transaction->delivery_status)) }}</span>
                                        </span>
                                    </div>
                                @endif
                            @endif
                            @if($pickOrder->processed_by)
                                <div class="pk-row">
                                    <span class="pk-row-key">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                        Diproses Oleh
                                    </span>
                                    <span class="pk-row-val">{{ $pickOrder->processor?->name ?? '-' }}</span>
                                </div>
                            @endif
                            @if($pickOrder->ready_at)
                                <div class="pk-row">
                                    <span class="pk-row-key">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        Siap Pada
                                    </span>
                                    <span class="pk-row-val">{{ $pickOrder->ready_at->format('d M Y, H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Catatan --}}
                    @if($pickOrder->notes)
                        <div class="pk-card">
                            <div class="pk-card-hdr">
                                <div class="pk-card-ico pk-ico-amber">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                </div>
                                <h3>Catatan</h3>
                            </div>
                            <p class="pk-note-text">{{ $pickOrder->notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- ═══ RIGHT COLUMN ═══ --}}
                <div class="pk-col-right">

                    {{-- Items --}}
                    <div class="pk-card">
                        <div class="pk-card-hdr">
                            <div class="pk-card-ico pk-ico-purple">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                            </div>
                            <h3>Barang yang Diminta</h3>
                            <span class="pk-count">{{ $pickOrder->items->count() }} item</span>
                        </div>

                        <div class="pk-items">
                            @foreach($pickOrder->items as $item)
                                <div class="pk-line" @if($item->is_additional) style="background:#fefce8;" @endif>
                                    <div class="pk-line-idx">{{ $loop->iteration }}</div>
                                    <div class="pk-line-info">
                                        <span class="pk-line-name">
                                            {{ $item->product?->name ?? 'Produk #' . $item->product_id }}
                                            @if($item->is_additional)
                                                <span style="font-size:9px;background:#fef3c7;color:#92400e;padding:2px 6px;border-radius:4px;margin-left:4px;font-weight:700;">+ Tambahan</span>
                                            @endif
                                        </span>
                                        <span class="pk-line-sku">SKU: {{ $item->product?->sku ?? '-' }}</span>
                                    </div>
                                    <div class="pk-line-qty">
                                        <span class="pk-qty-pill">{{ $item->unit_qty }} {{ $item->unit_name }}</span>
                                        <span class="pk-qty-sub">{{ $item->quantity }} unit dasar</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ─── Actions ─── --}}
                    @if($isSupervisor)
                        {{-- Supervisor: read-only monitoring --}}
                        <div class="pk-monitor-notice">
                            <div class="pk-monitor-icon">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </div>
                            <h4>Mode Pantau</h4>
                            <p>Supervisor hanya memantau. Proses dilakukan oleh admin gudang.</p>
                        </div>
                    @else
                        @if($pickOrder->status === 'pending')
                            <form action="{{ route('gudang.pos_pick.process', $pickOrder) }}" method="POST" class="pk-form">
                                @csrf @method('PATCH')
                                <button type="submit" class="pk-btn pk-btn-indigo">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    Mulai Proses
                                </button>
                            </form>
                        @endif

                        @if(in_array($pickOrder->status, ['pending', 'processing']))
                            <form action="{{ route('gudang.pos_pick.ready', $pickOrder) }}" method="POST" class="pk-form">
                                @csrf @method('PATCH')
                                <textarea name="notes" rows="2" class="pk-textarea" placeholder="Catatan persiapan (opsional)..."></textarea>
                                <button type="submit" class="pk-btn pk-btn-emerald">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Tandai Siap Diambil
                                </button>
                            </form>
                        @endif

                        @if($pickOrder->status === 'ready')
                            <div class="pk-done-card">
                                <div class="pk-done-icon">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                </div>
                                <h4>Barang Sudah Siap!</h4>
                                <p>Menunggu kasir mengambil di gudang.</p>
                            </div>
                            <form action="{{ route('gudang.pos_pick.complete', $pickOrder) }}" method="POST" class="pk-form" onsubmit="return confirm('Konfirmasi barang sudah diambil kasir?');">
                                @csrf @method('PATCH')
                                <button type="submit" class="pk-btn pk-btn-slate">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                    Konfirmasi Pengambilan
                                </button>
                            </form>
                        @endif

                        @if($pickOrder->status === 'completed')
                            <div class="pk-done-card pk-done-card-muted">
                                <div class="pk-done-icon">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                </div>
                                <h4>Selesai</h4>
                                <p>Barang sudah diambil oleh {{ $pickOrder->confirmer?->name ?? 'kasir' }} pada {{ $pickOrder->confirmed_at?->format('d M Y, H:i') ?? '-' }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .pk-page { background: #f1f5f9; min-height: 100vh; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; padding: 1.25rem 1.5rem 3rem; }
        .pk-container { max-width: 1120px; margin: 0 auto; }

        /* ── Header ── */
        .pk-header { margin-bottom: 1.25rem; }
        .pk-back { display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; color: #64748b; text-decoration: none; font-weight: 600; font-size: 0.82rem; margin-bottom: 0.85rem; transition: all .15s; }
        .pk-back:hover { background: #f8fafc; border-color: #cbd5e1; }
        .pk-header-main { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
        .pk-title-group { display: flex; flex-direction: column; }
        .pk-title { font-size: 1.45rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -.02em; line-height: 1.2; }
        .pk-subtitle-text { font-size: 0.78rem; color: #94a3b8; margin-top: 2px; }
        .pk-status-pill { padding: 5px 16px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }

        /* ── Stepper ── */
        .pk-stepper { display: flex; align-items: center; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 20px; margin-bottom: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,.03); }
        .pk-step { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
        .pk-step-circle { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: #94a3b8; border: 2px solid #e2e8f0; transition: all .3s; }
        .pk-step-current .pk-step-circle { background: #6366f1; color: #fff; border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,.15); }
        .pk-step-done .pk-step-circle { background: #10b981; color: #fff; border-color: #10b981; }
        .pk-step-label { font-size: 0.78rem; font-weight: 600; color: #94a3b8; }
        .pk-step-current .pk-step-label { color: #6366f1; }
        .pk-step-done .pk-step-label { color: #10b981; }
        .pk-step-line { flex: 1; height: 2px; background: #e2e8f0; margin: 0 8px; min-width: 20px; border-radius: 2px; transition: background .3s; }
        .pk-step-line-done { background: #10b981; }

        /* ── Alerts ── */
        .pk-alert { display: flex; align-items: center; gap: 10px; padding: 11px 16px; border-radius: 12px; margin-bottom: 1rem; font-size: 0.85rem; font-weight: 500; }
        .pk-alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .pk-alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── Grid ── */
        .pk-grid { display: grid; grid-template-columns: 350px 1fr; gap: 1.25rem; align-items: start; }

        /* ── Cards ── */
        .pk-card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 1.25rem; margin-bottom: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,.02); }
        .pk-card-hdr { display: flex; align-items: center; gap: 10px; margin-bottom: 1rem; }
        .pk-card-hdr h3 { font-size: 0.88rem; font-weight: 700; color: #0f172a; margin: 0; flex: 1; }
        .pk-card-ico { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .pk-ico-blue { background: #eff6ff; color: #3b82f6; }
        .pk-ico-green { background: #f0fdf4; color: #10b981; }
        .pk-ico-amber { background: #fffbeb; color: #f59e0b; }
        .pk-ico-purple { background: #f5f3ff; color: #8b5cf6; }

        /* ── Buyer ── */
        .pk-buyer { display: flex; align-items: center; gap: 12px; }
        .pk-buyer-avatar { width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg,#6366f1,#8b5cf6); color: #fff; font-weight: 800; font-size: 1rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .pk-buyer-name { font-size: 0.95rem; font-weight: 700; color: #1e293b; }
        .pk-buyer-phone { display: flex; align-items: center; gap: 4px; margin-top: 3px; font-size: 0.78rem; color: #64748b; }
        .pk-buyer-empty { display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 8px 0; color: #94a3b8; font-size: 0.85rem; }
        .pk-buyer-empty em { font-style: normal; font-size: 0.78rem; }

        /* ── Info Rows ── */
        .pk-rows { display: flex; flex-direction: column; }
        .pk-row { display: flex; justify-content: space-between; align-items: center; padding: 9px 0; border-bottom: 1px solid #f1f5f9; }
        .pk-row:last-child { border-bottom: none; }
        .pk-row-key { display: flex; align-items: center; gap: 7px; font-size: 0.8rem; color: #94a3b8; }
        .pk-row-key svg { flex-shrink: 0; opacity: .6; }
        .pk-row-val { font-size: 0.84rem; font-weight: 600; color: #1e293b; text-align: right; }
        .pk-row-link { font-size: 0.84rem; font-weight: 600; color: #6366f1; text-decoration: none; }
        .pk-row-link:hover { text-decoration: underline; }

        .pk-tag { display: inline-block; padding: 2px 10px; border-radius: 6px; font-size: 0.73rem; font-weight: 700; }
        .pk-tag-eceran { background: #fef3c7; color: #92400e; }
        .pk-tag-grosir { background: #e0e7ff; color: #3730a3; }
        .pk-tag-deliv { background: #ecfdf5; color: #065f46; }

        .pk-note-text { color: #64748b; line-height: 1.65; margin: 0; font-size: 0.84rem; }

        /* ── Item Count ── */
        .pk-count { font-size: 0.72rem; font-weight: 700; color: #94a3b8; background: #f1f5f9; padding: 3px 10px; border-radius: 20px; }

        /* ── Items ── */
        .pk-items { display: flex; flex-direction: column; gap: 8px; }
        .pk-line { display: grid; grid-template-columns: 28px 1fr auto; gap: 12px; padding: 14px 16px; background: #f8fafc; border-radius: 12px; align-items: center; border: 1.5px solid #f1f5f9; transition: all .15s; }
        .pk-line:hover { border-color: #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
        .pk-line-warn { background: #fef2f2; border-color: #fecaca; }
        .pk-line-idx { width: 28px; height: 28px; border-radius: 8px; background: #e2e8f0; color: #475569; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .pk-line-name { font-weight: 600; color: #0f172a; font-size: 0.88rem; display: block; }
        .pk-line-sku { font-size: 0.72rem; color: #94a3b8; }
        .pk-line-qty { text-align: center; }
        .pk-qty-pill { display: inline-block; background: #6366f1; color: #fff; padding: 4px 12px; border-radius: 8px; font-weight: 700; font-size: 0.8rem; }
        .pk-qty-sub { display: block; font-size: 0.68rem; color: #94a3b8; margin-top: 3px; }
        .pk-line-stock { text-align: center; }
        .pk-stk { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; }
        .pk-stk-ok { background: #f0fdf4; color: #166534; }
        .pk-stk-low { background: #fef2f2; color: #991b1b; }
        .pk-stk-na { background: #f1f5f9; color: #94a3b8; padding: 4px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; }
        .pk-stk-alert { display: block; font-size: 0.68rem; color: #ef4444; font-weight: 600; margin-top: 3px; }

        /* ── Forms & Buttons ── */
        .pk-form { display: flex; flex-direction: column; gap: 10px; margin-bottom: 8px; }
        .pk-textarea { padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-family: inherit; font-size: 0.84rem; resize: vertical; transition: border-color .2s, box-shadow .2s; background: #fff; }
        .pk-textarea:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
        .pk-textarea::placeholder { color: #94a3b8; }
        .pk-btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 13px 20px; border: none; border-radius: 12px; font-weight: 700; font-size: 0.88rem; cursor: pointer; transition: all .2s; font-family: inherit; letter-spacing: -.01em; }
        .pk-btn-indigo { background: linear-gradient(135deg, #6366f1, #818cf8); color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,.25); }
        .pk-btn-indigo:hover { box-shadow: 0 8px 24px rgba(99,102,241,.35); transform: translateY(-1px); }
        .pk-btn-emerald { background: linear-gradient(135deg, #10b981, #34d399); color: #fff; box-shadow: 0 4px 14px rgba(16,185,129,.25); }
        .pk-btn-emerald:hover { box-shadow: 0 8px 24px rgba(16,185,129,.35); transform: translateY(-1px); }
        .pk-btn-slate { background: linear-gradient(135deg, #334155, #1e293b); color: #fff; box-shadow: 0 4px 14px rgba(30,41,59,.25); }
        .pk-btn-slate:hover { box-shadow: 0 8px 24px rgba(30,41,59,.35); transform: translateY(-1px); }

        /* ── Done Card ── */
        .pk-done-card { text-align: center; padding: 1.75rem 1rem; background: linear-gradient(135deg, #f0fdf4, #ecfdf5); border-radius: 14px; border: 1.5px solid #bbf7d0; margin-bottom: 10px; }
        .pk-done-card-muted { background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-color: #e2e8f0; }
        .pk-done-icon { color: #10b981; margin-bottom: 6px; }
        .pk-done-card-muted .pk-done-icon { color: #94a3b8; }
        .pk-done-card h4 { margin: 6px 0 3px; font-size: 1.1rem; font-weight: 700; color: #166534; }
        .pk-done-card-muted h4 { color: #475569; }
        .pk-done-card p { margin: 0; color: #64748b; font-size: 0.82rem; }

        /* ── Supervisor Monitor Notice ── */
        .pk-monitor-notice { text-align: center; padding: 2rem 1.25rem; background: linear-gradient(135deg, #eff6ff, #f0f9ff); border-radius: 14px; border: 1.5px dashed #93c5fd; margin-bottom: 10px; }
        .pk-monitor-icon { color: #3b82f6; margin-bottom: 8px; }
        .pk-monitor-notice h4 { margin: 0 0 6px; font-size: 1.05rem; font-weight: 700; color: #1e40af; }
        .pk-monitor-notice p { margin: 0; color: #64748b; font-size: 0.82rem; line-height: 1.5; }

        /* ── Responsive ── */
        @media (max-width: 860px) {
            .pk-grid { grid-template-columns: 1fr; }
            .pk-stepper { padding: 10px 12px; overflow-x: auto; }
            .pk-step-label { font-size: 0.7rem; }
        }
        @media (max-width: 640px) {
            .pk-line { grid-template-columns: 1fr; gap: 8px; text-align: center; }
            .pk-line-idx { margin: 0 auto; }
            .pk-line-qty, .pk-line-stock { text-align: center; }
        }
    </style>
    @endpush
</x-app-layout>
