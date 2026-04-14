<x-app-layout>
    <x-slot name="header">Hutang Pelanggan (Terkonsolidasi)</x-slot>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">❌ {{ session('error') }}</div>   @endif

        {{-- Stats --}}
        <div class="card" style="padding:1.5rem; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
            <div style="display:flex; align-items:center; gap:1rem;">
                <div style="width:56px;height:56px;border-radius:16px;background:#fee2e2;display:flex;align-items:center;justify-content:center;font-size:1.75rem;">💳</div>
                <div>
                    <div style="font-size:1.5rem;font-weight:800;color:#ef4444;">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                    <div style="font-size:0.8rem;color:#64748b;">Total Hutang Semua Pelanggan</div>
                </div>
            </div>
            <div style="display:flex; gap:0.5rem;">
                <a href="{{ route('pelanggan.kredit.index') }}" class="btn-secondary">📋 Lihat Detail Transaksi</a>
                <a href="{{ route('pelanggan.kredit.create', ['type'=>'debt']) }}" class="btn-primary">+ Catat Hutang Baru</a>
            </div>
        </div>

        {{-- Daftar Pelanggan Berhutang --}}
        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                <div style="font-size:1.1rem;font-weight:700;color:#1e293b;">👥 Daftar Pelanggan Berhutang</div>
                <div style="font-size:0.8rem;color:#64748b;margin-top:0.25rem;">Klik pelanggan untuk melihat detail hutang dan melakukan pembayaran</div>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>No. Telepon</th>
                            <th>Jumlah Transaksi Hutang</th>
                            <th>Total Hutang</th>
                            <th>Batas Kredit</th>
                            <th>Sisa Kredit</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                        @php
                            $remainingCredit = $customer->credit_limit - $customer->current_debt;
                            $creditPercentage = $customer->credit_limit > 0 ? ($customer->current_debt / $customer->credit_limit) * 100 : 0;
                        @endphp
                        <tr>
                            <td style="font-weight:700;color:#1e293b;">
                                {{ $customer->name }}
                                @if($customer->category)
                                    <span style="display:block;font-size:0.7rem;font-weight:500;color:#64748b;margin-top:2px;">
                                        {{ ucfirst($customer->category) }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $customer->phone ?? '-' }}</td>
                            <td>
                                <span style="background:#f1f5f9;padding:0.25rem 0.75rem;border-radius:999px;font-size:0.8rem;font-weight:600;">
                                    {{ $customer->active_debts_count }} transaksi
                                </span>
                            </td>
                            <td style="font-weight:800;color:#ef4444;font-size:1.05rem;">
                                Rp {{ number_format($customer->current_debt, 0, ',', '.') }}
                            </td>
                            <td>Rp {{ number_format($customer->credit_limit, 0, ',', '.') }}</td>
                            <td style="color:{{ $remainingCredit > 0 ? '#16a34a' : '#dc2626' }};font-weight:600;">
                                Rp {{ number_format($remainingCredit, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($creditPercentage >= 90)
                                    <span style="background:#fee2e2;color:#dc2626;padding:0.25rem 0.75rem;border-radius:999px;font-size:0.75rem;font-weight:600;">⚠️ Hampir Penuh</span>
                                @elseif($creditPercentage >= 75)
                                    <span style="background:#fef3c7;color:#d97706;padding:0.25rem 0.75rem;border-radius:999px;font-size:0.75rem;font-weight:600;">⚡ Perhatian</span>
                                @else
                                    <span style="background:#dcfce7;color:#16a34a;padding:0.25rem 0.75rem;border-radius:999px;font-size:0.75rem;font-weight:600;">✅ Aman</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pelanggan.kredit.customer', $customer) }}" class="btn-primary btn-sm">💰 Bayar Hutang</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:3rem;color:#94a3b8;">
                                <div style="font-size:3rem;margin-bottom:1rem;">🎉</div>
                                <div style="font-size:1.1rem;font-weight:600;">Tidak Ada Hutang</div>
                                <div style="font-size:0.85rem;margin-top:0.5rem;">Semua pelanggan tidak memiliki hutang</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
