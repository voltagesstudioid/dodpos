<x-app-layout>
    <x-slot name="header">Hutang Supplier</x-slot>

    <div class="page-container">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">❌ {{ session('error') }}</div>
        @endif

        {{-- Stats Row --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#fee2e2;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">💳</div>
                <div>
                    <div style="font-size:1.3rem;font-weight:800;color:#ef4444;">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</div>
                    <div style="font-size:0.75rem;color:#64748b;">Total Hutang Tersisa</div>
                </div>
            </div>
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">⚠️</div>
                <div>
                    <div style="font-size:1.3rem;font-weight:800;color:#f59e0b;">Rp {{ number_format($totalOverdue, 0, ',', '.') }}</div>
                    <div style="font-size:0.75rem;color:#64748b;">Jatuh Tempo ({{ $countOverdue }})</div>
                </div>
            </div>
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#dcfce7;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">✅</div>
                <div>
                    <div style="font-size:1.3rem;font-weight:800;color:#16a34a;">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                    <div style="font-size:0.75rem;color:#64748b;">Sudah Dibayar</div>
                </div>
            </div>
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#e0e7ff;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">📋</div>
                <div>
                    <div style="font-size:1.5rem;font-weight:800;color:#4f46e5;">{{ $countUnpaid }}</div>
                    <div style="font-size:0.75rem;color:#64748b;">Transaksi Belum Lunas</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
                <div>
                    <div style="font-size:1rem;font-weight:700;color:#1e293b;">💳 Daftar Hutang Supplier</div>
                    <div style="font-size:0.75rem;color:#64748b;">Pantau & catat pembayaran hutang ke supplier</div>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <a href="{{ route('pembelian.hutang.index', array_merge(request()->query(), ['export' => 'excel'])) }}" class="btn-secondary" title="Export Excel">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:4px;">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Export
                    </a>
                    @can('create_hutang_supplier')
                    <a href="{{ route('pembelian.hutang.create') }}" class="btn-primary">+ Catat Hutang</a>
                    @endcan
                </div>
            </div>

            {{-- Filters --}}
            <div style="padding:1rem 1.5rem; background:#f8fafc; border-bottom:1px solid #f1f5f9;">
                <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. invoice..." class="form-input" style="max-width:180px;">
                    <select name="status" class="form-input" style="max-width:140px;">
                        <option value="">Semua Status</option>
                        <option value="unpaid" @selected(request('status')=='unpaid')>Belum Bayar</option>
                        <option value="partial" @selected(request('status')=='partial')>Sebagian</option>
                        <option value="paid" @selected(request('status')=='paid')>Lunas</option>
                    </select>
                    <select name="supplier_id" class="form-input" style="max-width:180px;">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" @selected(request('supplier_id')==$s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input" style="max-width:135px;" title="Dari Tanggal">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input" style="max-width:135px;" title="Sampai Tanggal">
                    <button type="submit" class="btn-primary btn-sm">Filter</button>
                    @if(request('search') || request('status') || request('supplier_id') || request('date_from') || request('date_to'))
                        <a href="{{ route('pembelian.hutang.index') }}" class="btn-secondary btn-sm">Reset</a>
                    @endif
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Invoice</th>
                            <th>Supplier</th>
                            <th>Tgl. Transaksi</th>
                            <th>Jatuh Tempo</th>
                            <th>Total</th>
                            <th>Terbayar</th>
                            <th>Sisa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($debts as $i => $d)
                        <tr @if($d->isOverdue()) style="background:#fff7f7;" @endif>
                            <td class="text-muted">{{ $debts->firstItem() + $i }}</td>
                            <td style="font-weight:600;">
                                {{ $d->invoice_number }}
                                @if($d->isOverdue()) <span class="badge-danger" style="font-size:0.6rem;">Terlambat</span> @endif
                            </td>
                            <td>{{ $d->supplier->name }}</td>
                            <td>{{ $d->transaction_date->format('d/m/Y') }}</td>
                            <td>{{ $d->due_date ? $d->due_date->format('d/m/Y') : '-' }}</td>
                            <td>Rp {{ number_format($d->total_amount, 0, ',', '.') }}</td>
                            <td style="color:#16a34a;">Rp {{ number_format($d->paid_amount, 0, ',', '.') }}</td>
                            <td style="font-weight:700;color:#ef4444;">Rp {{ number_format($d->remaining_amount, 0, ',', '.') }}</td>
                            <td>{!! $d->status_badge !!}</td>
                            <td>
                                <a href="{{ route('pembelian.hutang.show', $d) }}" class="btn-primary btn-sm">Detail</a>
                                @if($d->status !== 'paid')
                                    @can('edit_hutang_supplier')
                                    <a href="{{ route('pembelian.hutang.show', $d) }}#bayar" class="btn-warning btn-sm">Bayar</a>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="10" style="text-align:center;padding:2rem;color:#94a3b8;">Belum ada data hutang.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($debts->hasPages())
                <div style="padding:1rem 1.5rem;">{{ $debts->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
