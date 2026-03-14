<x-app-layout>
    <x-slot name="header">Detail Purchase Order</x-slot>

    <style>
        .po-status-badge { display:inline-block; padding:0.4rem 1rem; border-radius:99px; font-size:0.85rem; font-weight:700; letter-spacing:0.02em; }
        .po-status-badge--draft { background:#f1f5f9; color:#94a3b8; }
        .po-status-badge--ordered { background:#dbeafe; color:#2563eb; }
        .po-status-badge--partial { background:#fef3c7; color:#d97706; }
        .po-status-badge--received { background:#dcfce7; color:#16a34a; }
        .po-status-badge--cancelled { background:#fee2e2; color:#dc2626; }

        .po-est-date { font-weight:700; font-size:1.05rem; color:#334155; margin-top:0.3rem; }
        .po-est-date--late { color:#ef4444; }
    </style>

    <div class="page-container" style="max-width:1150px;">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1rem;">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="margin-bottom:1rem;">❌ {{ session('error') }}</div>
        @endif

        @php
            $sl = $order->statusLabel;
            $badgeClass = match ($order->status) {
                'draft' => 'po-status-badge--draft',
                'ordered' => 'po-status-badge--ordered',
                'partial' => 'po-status-badge--partial',
                'received' => 'po-status-badge--received',
                'cancelled' => 'po-status-badge--cancelled',
                default => 'po-status-badge--draft',
            };
        @endphp

        {{-- PO Header Card --}}
        <div class="card" style="padding:2rem; margin-bottom:1.5rem; border-radius:12px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1.5rem;">
                <div style="flex:1;">
                    <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1rem;">
                        <h1 style="font-size:1.75rem; font-weight:800; color:#0f172a; margin:0; font-family:'Menlo', 'Consolas', monospace; letter-spacing:-0.5px;">{{ $order->po_number }}</h1>
                        <span class="po-status-badge {{ $badgeClass }}">{{ $sl['label'] }}</span>
                    </div>
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:1.5rem; margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid #e2e8f0;">
                        <div>
                            <div style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:800; letter-spacing:0.05em;">Supplier</div>
                            <div style="font-weight:800; font-size:1.1rem; color:#1e293b; margin-top:0.3rem;">{{ $order->supplier->name }}</div>
                            <div style="font-size:0.85rem; color:#64748b; margin-top:0.2rem; display:flex; align-items:center; gap:0.3rem;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                {{ $order->supplier->phone ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:800; letter-spacing:0.05em;">Tgl Pesan</div>
                            <div style="font-weight:700; font-size:1.05rem; color:#334155; margin-top:0.3rem;">{{ $order->order_date->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:800; letter-spacing:0.05em;">Est. Tiba</div>
                            @if($order->expected_date)
                                @php $late = $order->expected_date->isPast() && !in_array($order->status, ['received','cancelled']); @endphp
                                <div class="po-est-date {{ $late ? 'po-est-date--late' : '' }}">{{ $late ? '⚠ ' : '' }}{{ $order->expected_date->format('d M Y') }}</div>
                            @else
                                <div style="color:#cbd5e1; font-weight:700; font-size:1.05rem; margin-top:0.3rem;">—</div>
                            @endif
                        </div>
                        <div>
                            <div style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:800; letter-spacing:0.05em;">Dibuat oleh</div>
                            <div style="font-weight:700; font-size:1.05rem; color:#334155; margin-top:0.3rem;">{{ $order->user->name ?? '-' }}</div>
                            <div style="font-size:0.8rem; color:#94a3b8; margin-top:0.2rem;">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
                @if(auth()->user()->role !== 'admin3')
                <div style="text-align:right; background:#f8fafc; padding:1.5rem; border-radius:10px; border:1px solid #e2e8f0; min-width:220px;">
                    <div style="font-size:0.8rem; color:#64748b; text-transform:uppercase; font-weight:800; letter-spacing:0.05em; margin-bottom:0.5rem;">Total Tagihan</div>
                    <div style="font-size:2.25rem; font-weight:800; color:#0f172a; font-family:'Menlo', 'Consolas', monospace; letter-spacing:-1px;">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </div>
                </div>
                @endif
            </div>

            @if($order->notes)
                <div style="margin-top:1.5rem; padding:1rem 1.25rem; background:#fffbeb; border-radius:8px; border-left:4px solid #f59e0b;">
                    <div style="font-size:0.75rem; color:#b45309; font-weight:800; margin-bottom:0.35rem; letter-spacing:0.05em;">📝 CATATAN</div>
                    <div style="font-size:0.95rem; color:#78350f; line-height:1.5;">{{ $order->notes }}</div>
                </div>
            @endif

            @if($order->shortageReports->count() > 0)
                @php $latestShortage = $order->shortageReports->first(); @endphp
                <div style="margin-top:1.25rem; padding:1rem 1.25rem; background:#fff7ed; border-radius:8px; border-left:4px solid #fb923c; border:1px solid #fed7aa;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                        <div>
                            <div style="font-size:0.75rem; color:#9a3412; font-weight:900; margin-bottom:0.35rem; letter-spacing:0.05em;">⚠️ LAPORAN KEKURANGAN (GUDANG)</div>
                            <div style="font-size:0.9rem; color:#7c2d12; font-weight:700;">
                                {{ $latestShortage->reporter->name ?? 'Admin Gudang' }} — {{ $latestShortage->created_at->format('d/m/Y H:i') }}
                            </div>
                            @if($latestShortage->notes)
                                <div style="margin-top:0.35rem; font-size:0.875rem; color:#7c2d12; line-height:1.5;">{{ $latestShortage->notes }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="table-wrapper" style="margin-top:0.75rem;">
                        <table class="data-table" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th style="text-align:center;">Pesan</th>
                                    <th style="text-align:center;">Diterima</th>
                                    <th style="text-align:center;">Kurang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(($latestShortage->items ?? []) as $row)
                                    <tr>
                                        <td>
                                            <div style="font-weight:800;color:#0f172a;">{{ $row['product_name'] ?? '-' }}</div>
                                            @if(!empty($row['sku']))<div style="font-size:0.75rem;color:#64748b;font-family:monospace;">{{ $row['sku'] }}</div>@endif
                                        </td>
                                        <td style="text-align:center;font-weight:800;">{{ $row['qty_ordered'] ?? 0 }} {{ $row['unit'] ?? '' }}</td>
                                        <td style="text-align:center;font-weight:800;">{{ $row['qty_received'] ?? 0 }} {{ $row['unit'] ?? '' }}</td>
                                        <td style="text-align:center;font-weight:900;color:#b91c1c;">{{ $row['qty_missing'] ?? 0 }} {{ $row['unit'] ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(\Illuminate\Support\Facades\Schema::hasTable('purchase_order_receipts') && $order->relationLoaded('receipts') && $order->receipts->count() > 0)
                <div style="margin-top:1.25rem; padding:1rem 1.25rem; background:#f8fafc; border-radius:10px; border:1px solid #e2e8f0;">
                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                        <div>
                            <div style="font-size:0.75rem; color:#64748b; font-weight:900; letter-spacing:0.05em;">📥 HISTORI PENERIMAAN (QC)</div>
                            <div style="margin-top:0.35rem; font-size:0.9rem; color:#475569; line-height:1.5;">Riwayat penerimaan PO oleh gudang, termasuk QC dan foto bukti.</div>
                        </div>
                        <div style="font-size:0.8rem; color:#64748b; font-weight:800;">Total: {{ $order->receipts->count() }} penerimaan</div>
                    </div>

                    <div style="margin-top:0.75rem;">
                        @foreach($order->receipts as $r)
                            @php
                                $rc = $r->status === 'partial' ? ['#fee2e2', '#991b1b'] : ['#dcfce7', '#166534'];
                                $items = $r->items ?? collect();
                                $rejectedCount = $items->where('result', 'rejected')->count();
                                $partialCount = $items->where('result', 'partial')->count();
                                $acceptedCount = $items->where('result', 'accepted')->count();
                                $followup = $r->needs_followup ? ($r->followup_status ?: 'open') : null;
                            @endphp
                            <details style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:0.85rem 1rem; margin-bottom:0.75rem;">
                                <summary style="cursor:pointer; display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap;">
                                    <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
                                        <span style="display:inline-flex; align-items:center; padding:0.25rem 0.65rem; border-radius:99px; font-weight:900; font-size:0.78rem; background:{{ $rc[0] }}; color:{{ $rc[1] }};">
                                            {{ strtoupper($r->status) }}
                                        </span>
                                        @if($followup)
                                            @php
                                                $fuBadge = $followup === 'resolved' ? ['#dcfce7', '#166534', 'FOLLOW-UP RESOLVED'] : ['#fef3c7', '#92400e', 'FOLLOW-UP OPEN'];
                                            @endphp
                                            <span style="display:inline-flex; align-items:center; padding:0.25rem 0.65rem; border-radius:99px; font-weight:900; font-size:0.78rem; background:{{ $fuBadge[0] }}; color:{{ $fuBadge[1] }};">
                                                {{ $fuBadge[2] }}
                                            </span>
                                        @endif
                                        <span style="font-weight:800; color:#0f172a;">{{ $r->created_at->format('d/m/Y H:i') }}</span>
                                        <span style="font-size:0.8rem; color:#64748b;">{{ $r->warehouse?->name ?? '-' }}</span>
                                        <span style="font-size:0.8rem; color:#64748b;">{{ $r->receiver?->name ?? 'Admin Gudang' }}</span>
                                    </div>
                                    <div style="font-size:0.8rem; color:#64748b;">
                                        ✅ {{ $acceptedCount }} · ⚠️ {{ $partialCount }} · ❌ {{ $rejectedCount }}
                                    </div>
                                </summary>

                                @if($r->notes)
                                    <div style="margin-top:0.75rem; color:#475569; font-size:0.9rem;">{{ $r->notes }}</div>
                                @endif

                                @if(is_array($r->photos) && count($r->photos) > 0)
                                    <div style="margin-top:0.75rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
                                        @foreach($r->photos as $p)
                                            <a href="{{ asset('storage/'.$p) }}" target="_blank" style="display:inline-block; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden;">
                                                <img src="{{ asset('storage/'.$p) }}" alt="Foto Bukti" style="width:96px; height:72px; object-fit:cover; display:block;">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                <div style="margin-top:0.75rem; overflow:auto;">
                                    <table class="data-table" style="width:100%; min-width:860px;">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th style="text-align:center;">Sisa Sebelum</th>
                                                <th style="text-align:center;">Qty Terima (PO)</th>
                                                <th style="text-align:center;">Hasil</th>
                                                <th>QC</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(($r->items ?? []) as $it)
                                                @php
                                                    $badge = match($it->result) {
                                                        'rejected' => ['#fee2e2', '#991b1b', 'REJECTED'],
                                                        'partial' => ['#fef3c7', '#92400e', 'PARTIAL'],
                                                        default => ['#dcfce7', '#166534', 'ACCEPTED'],
                                                    };
                                                    $qcBadges = [];
                                                    if (! $it->quality_ok) $qcBadges[] = ['Kualitas', '#fee2e2', '#991b1b'];
                                                    if (! $it->spec_ok) $qcBadges[] = ['Spesifikasi', '#fee2e2', '#991b1b'];
                                                    if (! $it->packaging_ok) $qcBadges[] = ['Kemasan', '#fee2e2', '#991b1b'];
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <div style="font-weight:800;color:#0f172a;">{{ $it->product?->name ?? '-' }}</div>
                                                        <div style="font-size:0.75rem;color:#64748b;font-family:monospace;">{{ $it->product?->sku ?? '-' }}</div>
                                                    </td>
                                                    <td style="text-align:center;font-weight:900;">{{ (int) $it->qty_remaining_before }}</td>
                                                    <td style="text-align:center;font-weight:900;">{{ (int) $it->qty_received_po_unit }}</td>
                                                    <td style="text-align:center;">
                                                        <span style="display:inline-flex; align-items:center; padding:0.2rem 0.55rem; border-radius:99px; font-weight:900; font-size:0.75rem; background:{{ $badge[0] }}; color:{{ $badge[1] }};">
                                                            {{ $badge[2] }}
                                                        </span>
                                                    </td>
                                                    <td style="white-space:nowrap;">
                                                        @if(count($qcBadges) === 0)
                                                            <span style="display:inline-flex; align-items:center; padding:0.2rem 0.55rem; border-radius:99px; font-weight:900; font-size:0.75rem; background:#dcfce7; color:#166534;">OK</span>
                                                        @else
                                                            @foreach($qcBadges as $qb)
                                                                <span style="display:inline-flex; align-items:center; padding:0.2rem 0.55rem; border-radius:99px; font-weight:900; font-size:0.75rem; background:{{ $qb[1] }}; color:{{ $qb[2] }}; margin-right:0.25rem;">
                                                                    {{ $qb[0] }}
                                                                </span>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td style="color:#475569;">{{ $it->notes ?: '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if($followup === 'open')
                                    <div style="margin-top:0.75rem; display:flex; justify-content:flex-end; gap:0.5rem; flex-wrap:wrap;">
                                        @if($r->purchase_return_id)
                                            <a href="{{ route('pembelian.retur.show', $r->purchase_return_id) }}" class="btn-secondary" style="font-size:0.85rem;">
                                                Lihat Draft Retur
                                            </a>
                                        @endif
                                        @if($r->reorder_purchase_order_id)
                                            <a href="{{ route('pembelian.order.edit', $r->reorder_purchase_order_id) }}" class="btn-secondary" style="font-size:0.85rem;">
                                                Lihat Draft Reorder
                                            </a>
                                        @endif
                                        <a href="{{ route('pembelian.receipts_followup.show', $r) }}" class="btn-primary" style="background:#0ea5e9; border-color:#0ea5e9; font-size:0.85rem;">
                                            Tindak Lanjut
                                        </a>
                                    </div>
                                @endif
                            </details>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div style="display:flex; gap:0.5rem; margin-top:1.25rem; padding-top:1.25rem; border-top:1px solid #f1f5f9; flex-wrap:wrap;">
                <a href="{{ route('pembelian.order') }}" class="btn-secondary" style="font-size:0.875rem;">← Daftar PO</a>
                @if(auth()->user()->role !== 'admin3')
                    <a href="{{ route('print.purchase', $order->id) }}" target="_blank" class="btn-primary" style="font-size:0.875rem; background:#3b82f6; border-color:#3b82f6;">🖨️ Cetak Faktur PO</a>
                    <button type="button" onclick="shareWhatsApp()" class="btn-primary" style="font-size:0.875rem; background:#25D366; border-color:#25D366; color:white; display:inline-flex; align-items:center; justify-content:center; gap:0.3rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg> 
                        Kirim via WhatsApp
                    </button>
                @endif

                @if($order->status === 'draft')
                    <a href="{{ route('pembelian.order.edit', $order) }}" class="btn-secondary" style="font-size:0.875rem; background:#fef3c7; color:#92400e; border-color:#fcd34d;">
                        ✏️ Edit PO
                    </a>
                    <form action="{{ route('pembelian.order.status', $order) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="ordered">
                        <button type="submit" class="btn-primary" style="font-size:0.875rem;">
                            📤 Kirim ke Gudang (Admin 3)
                        </button>
                    </form>
                    <form action="{{ route('pembelian.order.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus PO {{ $order->po_number }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-secondary" style="font-size:0.875rem; color:#ef4444; border-color:#fca5a5; background:#fef2f2;">🗑 Hapus</button>
                    </form>
                @endif

                @if(in_array($order->status, ['ordered', 'partial']))
                    <form action="{{ route('pembelian.order.status', $order) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn-secondary" style="font-size:0.875rem; color:#ef4444; border-color:#fca5a5; background:#fef2f2;" onclick="return confirm('Batalkan PO ini?')">❌ Batalkan</button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Items Table --}}
        <div class="card" style="border-radius:12px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05); overflow:hidden;">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0; background:#f8fafc; display:flex; align-items:center; gap:0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                <h2 style="font-size:1.1rem; font-weight:800; color:#1e293b; margin:0;">Daftar Barang <span style="color:#94a3b8; font-weight:600;">({{ $order->items->count() }} item)</span></h2>
            </div>
            <div class="table-wrapper">
                <table class="data-table" style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#fff;">
                            <th style="padding:1rem; font-size:0.75rem; color:#64748b; letter-spacing:0.05em; border-bottom:2px solid #e2e8f0;">#</th>
                            <th style="padding:1rem; font-size:0.75rem; color:#64748b; letter-spacing:0.05em; border-bottom:2px solid #e2e8f0;">PRODUK / SKU</th>
                            <th style="padding:1rem; font-size:0.75rem; color:#64748b; letter-spacing:0.05em; border-bottom:2px solid #e2e8f0;">SATUAN</th>
                            <th style="padding:1rem; font-size:0.75rem; color:#64748b; letter-spacing:0.05em; border-bottom:2px solid #e2e8f0; text-align:center;">QTY PESAN</th>
                            <th style="padding:1rem; font-size:0.75rem; color:#64748b; letter-spacing:0.05em; border-bottom:2px solid #e2e8f0; text-align:center;">QTY DITERIMA</th>
                            <th style="padding:1rem; font-size:0.75rem; color:#64748b; letter-spacing:0.05em; border-bottom:2px solid #e2e8f0; text-align:center;">SISA</th>
                            @if(auth()->user()->role !== 'admin3')
                            <th style="padding:1rem; font-size:0.75rem; color:#64748b; letter-spacing:0.05em; border-bottom:2px solid #e2e8f0; text-align:right;">HARGA BELI</th>
                            <th style="padding:1rem; font-size:0.75rem; color:#64748b; letter-spacing:0.05em; border-bottom:2px solid #e2e8f0; text-align:right;">SUBTOTAL</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $i => $item)
                        @php $remaining = $item->qty_ordered - $item->qty_received; @endphp
                        <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding:1rem; color:#94a3b8; font-size:0.9rem; font-weight:600;">{{ $i+1 }}</td>
                            <td style="padding:1rem;">
                                <div style="font-weight:800; color:#1e293b; font-size:1rem; margin-bottom:0.2rem;">{{ $item->product->name }}</div>
                                <div style="font-size:0.75rem; color:#64748b; font-family:'Menlo', 'Consolas', monospace; background:#f1f5f9; display:inline-block; padding:0.15rem 0.4rem; border-radius:4px;">{{ $item->product->sku }}</div>
                            </td>
                            <td style="padding:1rem; font-size:0.9rem; font-weight:700; color:#475569;">
                                <span style="background:#e2e8f0; padding:0.25rem 0.6rem; border-radius:6px;">{{ $item->unit->abbreviation ?? $item->product->unit->abbreviation ?? '-' }}</span>
                            </td>
                            <td style="padding:1rem; text-align:center; font-weight:800; font-size:1.1rem; color:#0f172a;">{{ $item->qty_ordered }}</td>
                            <td style="padding:1rem; text-align:center;">
                                @if($item->qty_received > 0)
                                    <span style="display:inline-block; padding:0.35rem 0.75rem; border-radius:99px; background:#dcfce7; color:#166534; font-weight:800; font-size:0.9rem; box-shadow:0 1px 2px rgba(22,101,52,0.1);">{{ $item->qty_received }}</span>
                                @else
                                    <span style="color:#cbd5e1; font-weight:800;">—</span>
                                @endif
                            </td>
                            <td style="padding:1rem; text-align:center;">
                                @if($remaining > 0)
                                    <span style="display:inline-block; padding:0.35rem 0.75rem; border-radius:99px; background:#fef3c7; color:#92400e; font-weight:800; font-size:0.9rem; box-shadow:0 1px 2px rgba(146,64,14,0.1);">{{ $remaining }}</span>
                                @else
                                    <span style="display:inline-flex; align-items:center; gap:0.2rem; color:#10b981; font-weight:800; font-size:0.85rem;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> Selesai</span>
                                @endif
                            </td>
                            @if(auth()->user()->role !== 'admin3')
                            <td style="padding:1rem; text-align:right; font-family:'Menlo', 'Consolas', monospace; font-size:0.95rem; font-weight:600; color:#475569;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td style="padding:1rem; text-align:right; font-weight:800; font-family:'Menlo', 'Consolas', monospace; font-size:1.05rem; color:#0f172a; letter-spacing:-0.5px;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    @if(auth()->user()->role !== 'admin3')
                    <tfoot>
                        <tr style="background:#f8fafc; border-top:2px solid #e2e8f0;">
                            <td colspan="6" style="text-align:right; font-weight:800; font-size:0.85rem; color:#64748b; letter-spacing:0.05em; padding:1.25rem 1.5rem;">TOTAL KESELURUHAN</td>
                            <td colspan="2" style="text-align:right; font-weight:800; font-size:1.4rem; font-family:'Menlo', 'Consolas', monospace; color:#0f172a; padding:1.25rem 1.5rem; letter-spacing:-1px;">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

    </div>

    @if(auth()->user()->role !== 'admin3')
        @php
            $waItems = $order->items
                ->map(fn ($item) => [
                    'name' => $item->product->name,
                    'qty' => (int) $item->qty_ordered,
                    'unit' => $item->unit->name ?? $item->product->unit->name ?? '-',
                    'price' => (float) $item->unit_price,
                ])
                ->values();
        @endphp
        <script>
        function shareWhatsApp() {
            const poNumber = "{{ $order->po_number }}";
            const supplierName = "{{ $order->supplier->name }}";
            const supplierPhone = "{{ $order->supplier->phone }}";
            const orderDate = "{{ $order->order_date->format('d M Y') }}";
            const expectedDate = "{{ $order->expected_date ? $order->expected_date->format('d M Y') : '-' }}";
            const totalAmount = "Rp {{ number_format($order->total_amount, 0, ',', '.') }}";
            const notes = "{{ $order->notes ?: '-' }}";
            const items = JSON.parse('{!! addslashes($waItems->toJson()) !!}');
            const fmt = (n) => new Intl.NumberFormat('id-ID').format(n);

            let text = `📦 *PURCHASE ORDER DODPOS*\n`;
            text += `--------------------------------------------------\n`;
            text += `*No. PO:* ${poNumber}\n`;
            text += `*Tanggal:* ${orderDate}\n`;
            text += `*Kepada:* ${supplierName}\n`;
            text += `*Est. Tiba:* ${expectedDate}\n`;
            text += `--------------------------------------------------\n\n`;
            text += `*DAFTAR BARANG YANG DIPESAN:*\n\n`;

            items.forEach((it) => {
                text += `✅ *${it.name}*\n`;
                text += `   ↳ Qty: ${it.qty} ${it.unit}\n`;
                text += `   ↳ Harga: Rp ${fmt(it.price)}\n`;
                text += `\n`;
            });

            text += `--------------------------------------------------\n`;
            text += `*TOTAL TAGIHAN: ${totalAmount}*\n`;
            text += `--------------------------------------------------\n\n`;
            text += `*Catatan:* ${notes}\n\n`;
            text += `Mohon segera konfirmasi jadwal pengiriman barang ini. Terima kasih! 🙏`;

            let phone = supplierPhone ? supplierPhone.replace(/\\D/g,'') : '';
            if (phone.startsWith('0')) {
                phone = '62' + phone.substring(1);
            }

            const url = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        }
        </script>
    @endif
</x-app-layout>
