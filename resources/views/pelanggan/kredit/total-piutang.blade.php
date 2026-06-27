<x-app-layout>
    <x-slot name="header">Daftar Total Piutang Pelanggan</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .cs-page { max-width:76rem; margin:0 auto; padding:1.5rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .cs-summary { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
        .cs-summary-left { display:flex; align-items:center; gap:1rem; }
        .cs-summary-ico { width:56px; height:56px; border-radius:16px; background:#fef2f2; display:flex; align-items:center; justify-content:center; font-size:1.75rem; }
        .cs-summary-val { font-size:1.5rem; font-weight:800; color:#dc2626; letter-spacing:-0.02em; }
        .cs-summary-lbl { font-size:0.8rem; color:#64748b; font-weight:600; margin-top:0.15rem; }
        .cs-summary-actions { display:flex; gap:0.5rem; }

        .cs-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.5rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:700; text-decoration:none; border:none; cursor:pointer; transition:all 0.2s; font-family:inherit; }
        .cs-btn-primary { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 2px 8px rgba(37,99,235,0.25); }
        .cs-btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(37,99,235,0.35); }
        .cs-btn-secondary { background:#f1f5f9; color:#475569; }
        .cs-btn-secondary:hover { background:#e2e8f0; }
        .cs-btn-sm { padding:0.375rem 0.75rem; font-size:0.75rem; }

        .cs-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
        .cs-card-hdr { padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; }
        .cs-card-title { font-size:1rem; font-weight:700; color:#1e293b; }
        .cs-card-sub { font-size:0.8rem; color:#64748b; margin-top:0.25rem; }

        .cs-tblwrap { overflow-x:auto; }
        .cs-tbl { width:100%; border-collapse:collapse; font-size:0.8125rem; }
        .cs-tbl th { text-align:left; padding:0.75rem 1rem; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; border-bottom:2px solid #f1f5f9; white-space:nowrap; }
        .cs-tbl td { padding:0.875rem 1rem; border-bottom:1px solid #f8fafc; color:#334155; }
        .cs-tbl tr:last-child td { border-bottom:none; }
        .cs-tbl tr:hover { background:#f8fafc; }

        .cs-badge { display:inline-flex; align-items:center; gap:0.25rem; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.6875rem; font-weight:700; white-space:nowrap; }
        .cs-badge.danger { background:#fef2f2; color:#991b1b; }
        .cs-badge.warning { background:#fef3c7; color:#92400e; }
        .cs-badge.success { background:#d1fae5; color:#065f46; }
        .cs-badge.count { background:#f1f5f9; color:#475569; }

        .cs-progress { background:#f1f5f9; border-radius:999px; height:6px; margin-top:0.5rem; overflow:hidden; }
        .cs-progress-fill { height:100%; border-radius:999px; transition:width 0.3s; }

        .cs-empty { text-align:center; padding:3rem 1rem; }
        .cs-empty-ico { font-size:3rem; margin-bottom:0.75rem; }
        .cs-empty-title { font-size:1.125rem; font-weight:700; color:#64748b; }
        .cs-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-top:0.25rem; }

        .cs-alert { padding:0.75rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:600; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; }
        .cs-alert-success { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
        .cs-alert-danger { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

        @media(max-width:768px) {
            .cs-summary { flex-direction:column; text-align:center; }
            .cs-summary-actions { justify-content:center; flex-wrap:wrap; }
        }
    </style>
    @endpush

    <div class="cs-page">
        @if(session('success'))
            <div class="cs-alert cs-alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="cs-alert cs-alert-danger">❌ {{ session('error') }}</div>
        @endif

        {{-- Summary --}}
        <div class="cs-summary">
            <div class="cs-summary-left">
                <div class="cs-summary-ico">💳</div>
                <div>
                    <div class="cs-summary-val">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                    <div class="cs-summary-lbl">Dari seluruh pelanggan</div>
                </div>
            </div>
            <div class="cs-summary-actions">
                <a href="{{ route('pelanggan.kredit.index') }}" class="cs-btn cs-btn-secondary">📋 Detail Transaksi</a>
                <a href="{{ route('pelanggan.kredit.create', ['type'=>'debt']) }}" class="cs-btn cs-btn-primary">+ Catat Hutang Baru</a>
            </div>
        </div>

        {{-- Customer List --}}
        <div class="cs-card">
            <div class="cs-card-hdr">
                <div class="cs-card-title">👥 Daftar Total Piutang Pelanggan</div>
                <div class="cs-card-sub">Klik "Bayar Hutang" untuk melihat detail dan melakukan pembayaran</div>
            </div>

            <div class="cs-tblwrap">
                <table class="cs-tbl">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>No. Telepon</th>
                            <th>Transaksi Aktif</th>
                            <th>Total Hutang</th>
                            <th>Batas Kredit</th>
                            <th>Penggunaan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                        @php
                            $debt = $customer->calculated_debt ?? (float) $customer->current_debt;
                            $limit = (float) $customer->credit_limit;
                            $pct = $limit > 0 ? min(100, ($debt / $limit) * 100) : 0;
                            $barColor = $pct >= 90 ? '#ef4444' : ($pct >= 75 ? '#f59e0b' : '#10b981');
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight:700; color:#1e293b;">{{ $customer->name }}</div>
                                @if($customer->category)
                                    <div style="font-size:0.7rem; color:#64748b; margin-top:2px;">{{ ucfirst($customer->category) }}</div>
                                @endif
                            </td>
                            <td style="color:#64748b;">{{ $customer->phone ?? '-' }}</td>
                            <td>
                                <span class="cs-badge count">{{ $customer->active_debts_count }} transaksi</span>
                            </td>
                            <td style="font-weight:800; color:#dc2626; font-size:0.95rem;">
                                Rp {{ number_format($debt, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($limit > 0)
                                    Rp {{ number_format($limit, 0, ',', '.') }}
                                @else
                                    <span style="color:#94a3b8;">Tidak terbatas</span>
                                @endif
                            </td>
                            <td style="min-width:120px;">
                                @if($limit > 0)
                                    <div style="font-size:0.72rem; font-weight:600; color:#64748b; margin-bottom:0.25rem;">
                                        {{ number_format($pct, 1) }}%
                                    </div>
                                    <div class="cs-progress">
                                        <div class="cs-progress-fill" style="width:{{ $pct }}%; background:{{ $barColor }};"></div>
                                    </div>
                                @else
                                    <span style="color:#94a3b8; font-size:0.75rem;">-</span>
                                @endif
                            </td>
                            <td>
                                @if($pct >= 90)
                                    <span class="cs-badge danger">⚠️ Hampir Penuh</span>
                                @elseif($pct >= 75)
                                    <span class="cs-badge warning">⚡ Perhatian</span>
                                @else
                                    <span class="cs-badge success">✅ Aman</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pelanggan.kredit.customer', $customer) }}" class="cs-btn cs-btn-primary cs-btn-sm">💰 Bayar Hutang</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="cs-empty">
                                    <div class="cs-empty-ico">🎉</div>
                                    <div class="cs-empty-title">Tidak Ada Hutang</div>
                                    <div class="cs-empty-sub">Semua pelanggan tidak memiliki hutang.</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
