<x-app-layout>
    <x-slot name="header">Laporan Pembelian</x-slot>

    <div class="page-container">

        {{-- ===== FILTER ROW ===== --}}
        <div class="card" style="padding:1.25rem 1.5rem; margin-bottom:1.5rem;">
            <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="font-size:0.7rem;">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-input">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="font-size:0.7rem;">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="form-input">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="font-size:0.7rem;">Supplier</label>
                    <select name="supplier_id" class="form-input" style="min-width:180px;">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" @selected(request('supplier_id') == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="font-size:0.7rem;">Status</label>
                    <select name="status" class="form-input" style="min-width:160px;">
                        <option value="">Semua Status</option>
                        <option value="draft"     @selected(request('status')=='draft')>Draft</option>
                        <option value="ordered"   @selected(request('status')=='ordered')>Dipesan</option>
                        <option value="partial"   @selected(request('status')=='partial')>Diterima Sebagian</option>
                        <option value="received"  @selected(request('status')=='received')>Diterima Penuh</option>
                        <option value="cancelled" @selected(request('status')=='cancelled')>Dibatalkan</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">🔍 Tampilkan</button>
                <a href="{{ route('laporan.pembelian') }}" class="btn-secondary">Reset</a>
                <button type="button" onclick="window.print()" class="btn-secondary">🖨️ Print</button>
            </form>
        </div>

        {{-- ===== SUMMARY CARDS ===== --}}
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.25rem;">
                <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;font-weight:700;margin-bottom:0.5rem;">Total PO</div>
                <div style="font-size:1.75rem;font-weight:800;color:#1e293b;">{{ number_format($totalOrders) }}</div>
                <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;">Periode {{ \Carbon\Carbon::parse($dateFrom)->format('d M') }} – {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</div>
            </div>
            <div class="card" style="padding:1.25rem;">
                <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;font-weight:700;margin-bottom:0.5rem;">Total Nilai Pembelian</div>
                <div style="font-size:1.35rem;font-weight:800;color:#4f46e5;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;">Semua status</div>
            </div>
            <div class="card" style="padding:1.25rem;">
                <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;font-weight:700;margin-bottom:0.5rem;">Sudah Diterima</div>
                <div style="font-size:1.35rem;font-weight:800;color:#10b981;">Rp {{ number_format($totalReceived, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;">Received / Partial</div>
            </div>
            <div class="card" style="padding:1.25rem;">
                <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;font-weight:700;margin-bottom:0.5rem;">Pending / Belum Terima</div>
                <div style="font-size:1.35rem;font-weight:800;color:#f59e0b;">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;">Draft / Dipesan</div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 280px; gap:1.5rem; align-items:start;">

            {{-- ===== MAIN TABLE ===== --}}
            <div class="card">
                <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                    <div style="font-weight:700;color:#1e293b;">📋 Detail Purchase Order</div>
                    <div style="font-size:0.8rem;color:#64748b;">{{ $orders->count() }} transaksi</div>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No. PO</th>
                                <th>Supplier</th>
                                <th>Tgl. Order</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Jml Item</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                            @php $s = $order->status_label; @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('pembelian.order.show', $order) }}"
                                       style="font-weight:600;color:#4f46e5;text-decoration:none;">
                                        {{ $order->po_number }}
                                    </a>
                                </td>
                                <td>{{ $order->supplier->name }}</td>
                                <td>{{ $order->order_date->format('d/m/Y') }}</td>
                                <td style="font-weight:700;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span style="background:{{ $s['bg'] }};color:{{ $s['color'] }};padding:0.2rem 0.6rem;border-radius:999px;font-size:0.7rem;font-weight:600;">
                                        {{ $s['label'] }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $order->items->count() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center;padding:3rem;color:#94a3b8;">
                                    Tidak ada data untuk periode & filter yang dipilih.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($orders->count() > 0)
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:700;padding:0.875rem 1.25rem;">TOTAL</td>
                                <td style="font-weight:800;color:#4f46e5;padding:0.875rem 1.25rem;">
                                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- ===== SIDEBAR: Top Suppliers ===== --}}
            <div>
                <div class="card" style="padding:1.25rem;">
                    <div style="font-weight:700;color:#1e293b;margin-bottom:1rem;font-size:0.875rem;">🏭 Top Supplier (Periode Ini)</div>
                    @forelse ($bySupplier as $sup)
                    @php
                        $maxAmt = $bySupplier->first()['amount'] ?: 1;
                        $pct = ($sup['amount'] / $maxAmt) * 100;
                    @endphp
                    <div style="margin-bottom:0.875rem;">
                        <div style="display:flex;justify-content:space-between;font-size:0.8rem;margin-bottom:0.25rem;">
                            <span style="font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:150px;">{{ $sup['name'] }}</span>
                            <span style="color:#64748b;font-size:0.75rem;">{{ $sup['count'] }} PO</span>
                        </div>
                        <div style="background:#f1f5f9;border-radius:999px;height:6px;margin-bottom:0.2rem;">
                            <div style="width:{{ $pct }}%;background:linear-gradient(90deg,#6366f1,#8b5cf6);height:6px;border-radius:999px;"></div>
                        </div>
                        <div style="font-size:0.75rem;font-weight:700;color:#4f46e5;">Rp {{ number_format($sup['amount'], 0, ',', '.') }}</div>
                    </div>
                    @empty
                    <div style="font-size:0.85rem;color:#94a3b8;text-align:center;padding:1rem 0;">Tidak ada data.</div>
                    @endforelse
                </div>

                {{-- Status breakdown --}}
                <div class="card" style="padding:1.25rem; margin-top:1rem;">
                    <div style="font-weight:700;color:#1e293b;margin-bottom:1rem;font-size:0.875rem;">📊 Breakdown Status</div>
                    @foreach([
                        ['status' => 'received',  'label' => 'Diterima Penuh',     'color' => '#10b981', 'bg' => '#dcfce7'],
                        ['status' => 'ordered',   'label' => 'Dipesan',            'color' => '#2563eb', 'bg' => '#dbeafe'],
                        ['status' => 'partial',   'label' => 'Diterima Sebagian',  'color' => '#d97706', 'bg' => '#fef3c7'],
                        ['status' => 'draft',     'label' => 'Draft',              'color' => '#64748b', 'bg' => '#f1f5f9'],
                        ['status' => 'cancelled', 'label' => 'Dibatalkan',         'color' => '#dc2626', 'bg' => '#fee2e2'],
                    ] as $st)
                    @php $cnt = $orders->where('status', $st['status'])->count(); @endphp
                    @if($cnt > 0)
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
                        <span style="background:{{ $st['bg'] }};color:{{ $st['color'] }};padding:0.15rem 0.5rem;border-radius:999px;font-size:0.7rem;font-weight:600;">{{ $st['label'] }}</span>
                        <span style="font-weight:700;color:#1e293b;">{{ $cnt }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .sidebar, .topbar, form, button { display: none !important; }
            .main-wrapper { margin-left: 0 !important; }
            .card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        }
    </style>
</x-app-layout>
