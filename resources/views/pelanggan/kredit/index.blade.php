<x-app-layout>
    <x-slot name="header">Hutang & Piutang Pelanggan</x-slot>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">❌ {{ session('error') }}</div>   @endif

        {{-- Stats Row --}}
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:#fee2e2;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">💳</div>
                <div>
                    <div style="font-size:1.25rem;font-weight:800;color:#ef4444;">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                    <div style="font-size:0.72rem;color:#64748b;">Total Hutang Pelanggan</div>
                </div>
            </div>
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:#dcfce7;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">💰</div>
                <div>
                    <div style="font-size:1.25rem;font-weight:800;color:#16a34a;">Rp {{ number_format($totalCredit, 0, ',', '.') }}</div>
                    <div style="font-size:0.72rem;color:#64748b;">Total Piutang / Kredit</div>
                </div>
            </div>
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:1.25rem;">⚠️</div>
                <div>
                    <div style="font-size:1.75rem;font-weight:800;color:#f59e0b;">{{ $overdueCount }}</div>
                    <div style="font-size:0.72rem;color:#64748b;">Transaksi Jatuh Tempo</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; gap:0.75rem; justify-content:space-between; align-items:center; flex-wrap:wrap;">
                <div style="font-size:1rem;font-weight:700;color:#1e293b;">📋 Daftar Kredit / Hutang</div>
                <div style="display:flex;gap:0.5rem;">
                    <a href="{{ route('pelanggan.kredit.create', ['type'=>'debt']) }}" class="btn-primary">+ Catat Hutang</a>
                    <a href="{{ route('pelanggan.kredit.create', ['type'=>'credit']) }}" class="btn-secondary">+ Catat Piutang</a>
                </div>
            </div>

            {{-- Filters --}}
            <div style="padding:1rem 1.5rem; background:#f8fafc; border-bottom:1px solid #f1f5f9;">
                <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / no. kredit..." class="form-input" style="max-width:220px;">
                    <select name="type" class="form-input" style="max-width:160px;">
                        <option value="">Semua Jenis</option>
                        <option value="debt"   @selected(request('type')=='debt')>Hutang Pelanggan</option>
                        <option value="credit" @selected(request('type')=='credit')>Piutang / Kredit</option>
                    </select>
                    <select name="status" class="form-input" style="max-width:150px;">
                        <option value="">Semua Status</option>
                        <option value="unpaid"  @selected(request('status')=='unpaid')>Belum Lunas</option>
                        <option value="partial" @selected(request('status')=='partial')>Sebagian</option>
                        <option value="paid"    @selected(request('status')=='paid')>Lunas</option>
                    </select>
                    <select name="customer_id" class="form-input" style="max-width:200px;">
                        <option value="">Semua Pelanggan</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" @selected(request('customer_id')==$c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-primary btn-sm">Filter</button>
                    <a href="{{ route('pelanggan.kredit.index') }}" class="btn-secondary btn-sm">Reset</a>
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. Kredit</th>
                            <th>Jenis</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th>
                            <th>Jumlah</th>
                            <th>Terbayar</th>
                            <th>Sisa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($credits as $cr)
                        <tr @if($cr->isOverdue()) style="background:#fff7f7;" @endif>
                            <td style="font-weight:600;font-size:0.85rem;">
                                {{ $cr->credit_number }}
                                @if($cr->isOverdue()) <span class="badge-danger" style="font-size:0.6rem;">Telat</span> @endif
                            </td>
                            <td>
                                @if($cr->type === 'debt')
                                    <span style="background:#fee2e2;color:#dc2626;padding:0.15rem 0.5rem;border-radius:999px;font-size:0.7rem;font-weight:600;">Hutang</span>
                                @else
                                    <span style="background:#dcfce7;color:#16a34a;padding:0.15rem 0.5rem;border-radius:999px;font-size:0.7rem;font-weight:600;">Piutang</span>
                                @endif
                            </td>
                            <td style="font-weight:600;">{{ $cr->customer->name }}</td>
                            <td>{{ $cr->transaction_date->format('d/m/Y') }}</td>
                            <td style="color:{{ $cr->isOverdue() ? '#ef4444' : '#64748b' }}">{{ $cr->due_date ? $cr->due_date->format('d/m/Y') : '-' }}</td>
                            <td>Rp {{ number_format($cr->amount, 0, ',', '.') }}</td>
                            <td style="color:#16a34a;">Rp {{ number_format($cr->paid_amount, 0, ',', '.') }}</td>
                            <td style="font-weight:700;color:#ef4444;">Rp {{ number_format($cr->remaining_amount, 0, ',', '.') }}</td>
                            <td>{!! $cr->status_badge !!}</td>
                            <td>
                                <a href="{{ route('pelanggan.kredit.show', $cr) }}" class="btn-primary btn-sm">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="10" style="text-align:center;padding:2rem;color:#94a3b8;">Belum ada data kredit.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($credits->hasPages())
                <div style="padding:1rem 1.5rem;">{{ $credits->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
