<x-app-layout>
    <x-slot name="header">Transaksi Penjualan</x-slot>
    <div class="page-container">

        {{-- Premium Header --}}
        <div class="ph animate-in">
            <div class="ph-left">
                <div class="ph-icon emerald">🧾</div>
                <div>
                    <h1 class="ph-title">Transaksi Penjualan</h1>
                    <p class="ph-subtitle">Riwayat semua transaksi kasir</p>
                </div>
            </div>
            <div class="ph-actions">
                <a href="{{ route('kasir.index') }}" class="btn-primary">➕ Buka Kasir</a>
            </div>
        </div>

        {{-- Summary Stat Cards --}}
        <div class="stat-grid animate-in animate-in-delay-1">
            <div class="stat-card emerald">
                <div class="stat-card-row">
                    <div class="stat-icon emerald">📈</div>
                    <span class="stat-trend up">↑ Hari ini</span>
                </div>
                <div>
                    <div class="stat-label">Pendapatan Hari Ini</div>
                    <div class="stat-value emerald">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="stat-card blue">
                <div class="stat-card-row">
                    <div class="stat-icon blue">🧾</div>
                    <span class="stat-trend neutral">Hari ini</span>
                </div>
                <div>
                    <div class="stat-label">Transaksi Hari Ini</div>
                    <div class="stat-value blue">{{ $todayCount }}</div>
                </div>
            </div>
            <div class="stat-card indigo">
                <div class="stat-card-row">
                    <div class="stat-icon indigo">💰</div>
                    <span class="stat-trend neutral">Filter aktif</span>
                </div>
                <div>
                    <div class="stat-label">Total Pendapatan (Filter)</div>
                    <div class="stat-value indigo">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="stat-card amber">
                <div class="stat-card-row">
                    <div class="stat-icon amber">🏷️</div>
                    <span class="stat-trend neutral">Filter aktif</span>
                </div>
                <div>
                    <div class="stat-label">Total Transaksi (Filter)</div>
                    <div class="stat-value amber">{{ $totalCount }}</div>
                </div>
            </div>
        </div>

        {{-- Table & Filters --}}
        <div class="panel animate-in animate-in-delay-2">
            <div class="tbl-header">
                <div>
                    <div class="tbl-title">📋 Riwayat Transaksi</div>
                    <div class="tbl-meta">{{ $transactions->total() }} transaksi ditemukan</div>
                </div>
            </div>
            <div class="filter-bar">
                <form method="GET" style="display:flex;gap:0.625rem;flex-wrap:wrap;align-items:flex-end;">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:0.68rem;margin-bottom:3px;">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="No. transaksi..." class="form-input" style="width:160px;">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:0.68rem;margin-bottom:3px;">Dari</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input" style="width:140px;">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:0.68rem;margin-bottom:3px;">Sampai</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input" style="width:140px;">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:0.68rem;margin-bottom:3px;">Metode Bayar</label>
                        <select name="payment_method" class="form-input" style="width:130px;">
                            <option value="">Semua</option>
                            <option value="cash" @selected(request('payment_method')=='cash')>💵 Tunai</option>
                            <option value="transfer" @selected(request('payment_method')=='transfer')>🏦 Transfer</option>
                            <option value="qris" @selected(request('payment_method')=='qris')>📱 QRIS</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:0.68rem;margin-bottom:3px;">Status</label>
                        <select name="status" class="form-input" style="width:120px;">
                            <option value="">Semua</option>
                            <option value="completed" @selected(request('status')=='completed')>✅ Selesai</option>
                            <option value="voided" @selected(request('status')=='voided')>❌ Void</option>
                        </select>
                    </div>
                    <div style="display:flex;gap:0.5rem;align-items:flex-end;">
                        <button type="submit" class="btn-primary btn-sm">Filter</button>
                        <a href="{{ route('transaksi.index') }}" class="btn-secondary btn-sm">× Reset</a>
                    </div>
                </form>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Waktu</th>
                            <th>Kasir</th>
                            <th style="text-align:center;">Item</th>
                            <th>Metode</th>
                            <th style="text-align:right;">Total</th>
                            <th style="text-align:right;">Bayar</th>
                            <th style="text-align:right;">Kembali</th>
                            <th style="text-align:center;">Status</th>
                            <th style="text-align:center;width:100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $trx)
                        <tr @if($trx->status === 'voided') style="opacity:0.55;" @endif>
                            <td>
                                <span style="font-weight:700;font-size:0.8125rem;color:#4f46e5;font-family:monospace;">
                                    #{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size:0.8125rem;font-weight:500;">{{ $trx->created_at->format('d/m/Y') }}</div>
                                <div class="td-sub">{{ $trx->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td style="font-size:0.8125rem;">{{ $trx->user?->name ?? '—' }}</td>
                            <td style="text-align:center;">
                                <span class="badge badge-blue">{{ $trx->details->count() }} item</span>
                            </td>
                            <td>
                                @php $methodLabel = match($trx->payment_method) {
                                    'cash' => '💵 Tunai', 'transfer' => '🏦 Transfer', 'qris' => '📱 QRIS', default => $trx->payment_method
                                }; @endphp
                                <span style="font-size:0.8125rem;">{{ $methodLabel }}</span>
                            </td>
                            <td style="text-align:right;font-weight:700;font-size:0.875rem;">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td style="text-align:right;color:#059669;font-size:0.8125rem;">Rp {{ number_format($trx->paid_amount, 0, ',', '.') }}</td>
                            <td style="text-align:right;color:#d97706;font-size:0.8125rem;">Rp {{ number_format($trx->change_amount, 0, ',', '.') }}</td>
                            <td style="text-align:center;">
                                @if($trx->status === 'completed')
                                    <span class="badge badge-success">✅ Selesai</span>
                                @elseif($trx->status === 'voided')
                                    <span class="badge badge-danger">❌ Void</span>
                                @else
                                    <span class="badge badge-warning">{{ $trx->status }}</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <div class="act-grp" style="justify-content:center;">
                                    <a href="{{ route('transaksi.show', $trx) }}" class="act-btn act-btn-view">👁 Detail</a>
                                    @if($trx->status === 'completed')
                                        <a href="{{ route('print.receipt', $trx->id) }}" target="_blank" class="act-btn act-btn-success">🖨</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="10">
                            <div class="empty-state">
                                <span class="empty-state-icon">🧾</span>
                                <div class="empty-state-title">Belum ada transaksi</div>
                                <div class="empty-state-desc">Mulai transaksi baru dari kasir</div>
                                @can('view_pos_kasir')
                                    <a href="{{ route('kasir.index') }}" class="btn-primary btn-sm">🛒 Buka Kasir</a>
                                @endcan
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())<div>{{ $transactions->links() }}</div>@endif
        </div>
    </div>
</x-app-layout>
