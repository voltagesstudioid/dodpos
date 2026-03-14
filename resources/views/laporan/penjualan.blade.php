<x-app-layout>
    <x-slot name="header">Laporan Penjualan</x-slot>

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
                    <label class="form-label" style="font-size:0.7rem;">Metode Bayar</label>
                    <select name="payment_method" class="form-input" style="min-width:150px;">
                        <option value="">Semua Metode</option>
                        <option value="cash" @selected(request('payment_method')=='cash')>Tunai</option>
                        <option value="transfer" @selected(request('payment_method')=='transfer')>Transfer</option>
                        <option value="qris" @selected(request('payment_method')=='qris')>QRIS</option>
                        <option value="debit" @selected(request('payment_method')=='debit')>Kartu Debit</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="font-size:0.7rem;">Kasir</label>
                    <select name="kasir_id" class="form-input" style="min-width:160px;">
                        <option value="">Semua Kasir</option>
                        @foreach($kasirs as $k)
                            <option value="{{ $k->id }}" @selected(request('kasir_id') == $k->id)>{{ $k->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">🔍 Tampilkan</button>
                <a href="{{ route('laporan.penjualan') }}" class="btn-secondary">Reset</a>
                <button type="button" onclick="window.print()" class="btn-secondary">🖨️ Print</button>
            </form>
        </div>

        {{-- ===== SUMMARY CARDS ===== --}}
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.25rem;">
                <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;font-weight:700;margin-bottom:0.5rem;">Total Transaksi</div>
                <div style="font-size:1.75rem;font-weight:800;color:#1e293b;">{{ number_format($totalTrx) }}</div>
                <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;">{{ \Carbon\Carbon::parse($dateFrom)->format('d M') }} – {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</div>
            </div>
            <div class="card" style="padding:1.25rem;">
                <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;font-weight:700;margin-bottom:0.5rem;">Total Omzet</div>
                <div style="font-size:1.35rem;font-weight:800;color:#10b981;">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;">Pendapatan kotor</div>
            </div>
            <div class="card" style="padding:1.25rem;">
                <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;font-weight:700;margin-bottom:0.5rem;">Rata-rata / Transaksi</div>
                <div style="font-size:1.35rem;font-weight:800;color:#4f46e5;">Rp {{ number_format($avgPerTrx, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;">Nilai belanja rata-rata</div>
            </div>
            <div class="card" style="padding:1.25rem;">
                <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;font-weight:700;margin-bottom:0.5rem;">Total Item Terjual</div>
                <div style="font-size:1.75rem;font-weight:800;color:#f59e0b;">{{ number_format($totalItems) }}</div>
                <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;">Pcs / unit</div>
            </div>
        </div>

        {{-- ===== DAILY CHART ===== --}}
        @if($dailyData->isNotEmpty())
        <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
            <div style="font-weight:700;color:#1e293b;margin-bottom:1.25rem;font-size:0.875rem;">📈 Grafik Penjualan Harian</div>
            @php
                $maxDaily = $dailyData->max('total') ?: 1;
            @endphp
            <div style="display:flex; align-items:flex-end; gap:4px; height:120px; overflow-x:auto;">
                @foreach($dailyData as $date => $day)
                @php $h = max(4, ($day->total / $maxDaily) * 100); @endphp
                <div style="display:flex;flex-direction:column;align-items:center;gap:2px;min-width:28px;" title="{{ $date }}: Rp {{ number_format($day->total, 0, ',', '.') }} ({{ $day->count }} trx)">
                    <div style="font-size:0.5rem;color:#64748b;">{{ $day->count }}</div>
                    <div style="width:22px;height:{{ $h }}px;background:linear-gradient(180deg,#6366f1,#8b5cf6);border-radius:4px 4px 0 0;cursor:default;"
                         title="Rp {{ number_format($day->total, 0, ',', '.') }}"></div>
                    <div style="font-size:0.5rem;color:#94a3b8;white-space:nowrap;\">{{ \Carbon\Carbon::parse($date)->format('d/m') }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div style="display:grid; grid-template-columns:1fr 300px; gap:1.5rem; align-items:start;">

            {{-- ===== MAIN TRANSACTION TABLE ===== --}}
            <div class="card">
                <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                    <div style="font-weight:700;color:#1e293b;">🧾 Riwayat Transaksi</div>
                    <div style="font-size:0.8rem;color:#64748b;">{{ $transactions->count() }} transaksi</div>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal & Waktu</th>
                                <th>Kasir</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Bayar</th>
                                <th>Kembalian</th>
                                <th>Metode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $i => $trx)
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td>
                                    <div style="font-weight:600;">{{ $trx->created_at->format('d/m/Y') }}</div>
                                    <div style="font-size:0.75rem;color:#94a3b8;">{{ $trx->created_at->format('H:i') }}</div>
                                </td>
                                <td>{{ $trx->user ? $trx->user->name : '-' }}</td>
                                <td class="text-muted">{{ $trx->details->count() }} item</td>
                                <td style="font-weight:700;">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($trx->paid_amount, 0, ',', '.') }}</td>
                                <td style="color:#10b981;">Rp {{ number_format($trx->change_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span style="background:#e0e7ff;color:#4338ca;padding:0.15rem 0.5rem;border-radius:999px;font-size:0.7rem;font-weight:600;">
                                        {{ ucfirst($trx->payment_method) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align:center;padding:3rem;color:#94a3b8;">
                                    Tidak ada transaksi pada periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($transactions->count() > 0)
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align:right;font-weight:700;">TOTAL OMZET</td>
                                <td style="font-weight:800;color:#10b981;">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- ===== SIDEBAR ===== --}}
            <div>
                {{-- Top Products --}}
                <div class="card" style="padding:1.25rem; margin-bottom:1rem;">
                    <div style="font-weight:700;color:#1e293b;margin-bottom:1rem;font-size:0.875rem;">🏆 Produk Terlaris</div>
                    @forelse ($topProducts as $i => $tp)
                    @php
                        $maxRev = $topProducts->first()->total_revenue ?: 1;
                        $pct = ($tp->total_revenue / $maxRev) * 100;
                        $medal = $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : ($i+1).'.'));
                    @endphp
                    <div style="margin-bottom:0.875rem;">
                        <div style="display:flex;justify-content:space-between;font-size:0.8rem;margin-bottom:0.2rem;">
                            <span style="font-weight:600;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:150px;">
                                {{ $medal }} {{ $tp->product ? $tp->product->name : 'Produk #'.$tp->product_id }}
                            </span>
                            <span style="color:#64748b;font-size:0.75rem;flex-shrink:0;margin-left:4px;">{{ number_format($tp->total_qty) }} pcs</span>
                        </div>
                        <div style="background:#f1f5f9;border-radius:999px;height:5px;margin-bottom:0.2rem;">
                            <div style="width:{{ $pct }}%;background:linear-gradient(90deg,#10b981,#34d399);height:5px;border-radius:999px;"></div>
                        </div>
                        <div style="font-size:0.72rem;font-weight:700;color:#10b981;">Rp {{ number_format($tp->total_revenue, 0, ',', '.') }}</div>
                    </div>
                    @empty
                    <div style="font-size:0.85rem;color:#94a3b8;text-align:center;padding:1rem 0;">Tidak ada data.</div>
                    @endforelse
                </div>

                {{-- Payment Breakdown --}}
                <div class="card" style="padding:1.25rem; margin-bottom:1rem;">
                    <div style="font-weight:700;color:#1e293b;margin-bottom:1rem;font-size:0.875rem;">💳 Metode Pembayaran</div>
                    @foreach($byPayment as $p)
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.625rem;">
                        <div>
                            <div style="font-size:0.8rem;font-weight:600;color:#1e293b;">{{ $p['label'] }}</div>
                            <div style="font-size:0.7rem;color:#94a3b8;">{{ $p['count'] }} transaksi</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:0.8rem;font-weight:700;color:#4f46e5;">Rp {{ number_format($p['amount'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Per Cashier --}}
                @if($byCashier->count() > 1)
                <div class="card" style="padding:1.25rem;">
                    <div style="font-weight:700;color:#1e293b;margin-bottom:1rem;font-size:0.875rem;">👤 Performa Kasir</div>
                    @foreach($byCashier as $c)
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.625rem;padding:0.5rem;background:#f8fafc;border-radius:8px;">
                        <div>
                            <div style="font-size:0.8rem;font-weight:600;color:#1e293b;">{{ $c['name'] }}</div>
                            <div style="font-size:0.7rem;color:#94a3b8;">{{ $c['count'] }} transaksi</div>
                        </div>
                        <div style="font-weight:700;color:#10b981;font-size:0.8rem;">Rp {{ number_format($c['amount'], 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                </div>
                @endif
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
