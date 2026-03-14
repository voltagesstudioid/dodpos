<x-app-layout>
    <x-slot name="header">Penagihan Piutang Pasgar</x-slot>

    <div class="page-container">

        {{-- Summary Cards --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.25rem; font-weight:800; color:#ef4444;">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Total Piutang Belum Lunas</div>
            </div>
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.5rem; font-weight:800; color:#f59e0b;">{{ $totalBelumLunas }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Tagihan Belum Lunas</div>
            </div>
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.5rem; font-weight:800; color:#10b981;">{{ $totalLunas }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Tagihan Lunas</div>
            </div>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0;">
                <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">💳 Penagihan Piutang Tim Pasgar</h2>
                <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Piutang pelanggan dari transaksi kanvas kredit oleh tim pasgar</p>
            </div>

            {{-- Filter --}}
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-input" style="width:160px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}" class="form-input" style="width:160px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Anggota</label>
                        <select name="user_id" class="form-input" style="width:180px;">
                            <option value="">Semua Anggota</option>
                            @foreach($pasgarUsers as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Status</label>
                        <select name="status" class="form-input" style="width:150px;">
                            <option value="">Semua Status</option>
                            <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Sebagian</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary btn-sm">🔍 Filter</button>
                    <a href="{{ route('pasgar.penagihan.index') }}" class="btn-secondary btn-sm">Reset</a>
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. Kredit</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th>
                            <th style="text-align:right;">Total</th>
                            <th style="text-align:right;">Terbayar</th>
                            <th style="text-align:right;">Sisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($credits as $credit)
                        <tr>
                            <td style="font-weight:600; color:#4f46e5;">{{ $credit->credit_number }}</td>
                            <td>
                                <div style="font-weight:500;">{{ $credit->customer?->name ?? '—' }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">{{ $credit->customer?->phone ?? '' }}</div>
                            </td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($credit->transaction_date)->format('d M Y') }}</td>
                            <td>
                                @if($credit->due_date)
                                    @php $overdue = \Carbon\Carbon::parse($credit->due_date)->isPast() && $credit->status !== 'paid'; @endphp
                                    <span style="color:{{ $overdue ? '#ef4444' : '#475569' }}; font-weight:{{ $overdue ? '700' : '400' }};">
                                        {{ \Carbon\Carbon::parse($credit->due_date)->format('d M Y') }}
                                        @if($overdue) ⚠️ @endif
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td style="text-align:right; font-weight:600;">Rp {{ number_format($credit->amount, 0, ',', '.') }}</td>
                            <td style="text-align:right; color:#10b981; font-weight:600;">Rp {{ number_format($credit->paid_amount, 0, ',', '.') }}</td>
                            <td style="text-align:right; font-weight:700; color:{{ ($credit->amount - $credit->paid_amount) > 0 ? '#ef4444' : '#10b981' }};">
                                Rp {{ number_format($credit->amount - $credit->paid_amount, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($credit->status === 'paid')
                                    <span class="badge-success">Lunas</span>
                                @elseif($credit->status === 'partial')
                                    <span class="badge-indigo">Sebagian</span>
                                @else
                                    <span class="badge-danger">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:3rem; color:#94a3b8;">
                                <div style="font-size:2rem; margin-bottom:0.5rem;">💳</div>
                                <div>Tidak ada data penagihan untuk filter ini.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($credits->hasPages())
            <div style="padding:1rem 1.5rem; border-top:1px solid #f1f5f9;">{{ $credits->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
