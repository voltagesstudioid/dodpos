<x-app-layout>
    <x-slot name="header">Hutang & Piutang Pelanggan</x-slot>

    @push('styles')
    <style>
        :root{--hp-accent:#f59e0b;--hp-accent-dark:#d97706;--hp-accent-light:#fef3c7;--hp-radius:12px;--hp-radius-sm:8px;}
        .hp-page{max-width:78rem;margin:0 auto;padding:1.5rem 1rem 3rem;font-family:'Segoe UI',system-ui,sans-serif;}

        /* ── Stat Cards ── */
        .hp-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;}
        .hp-stat{background:#fff;border:1px solid #e5e7eb;border-radius:var(--hp-radius);padding:1.25rem 1.5rem;position:relative;overflow:hidden;}
        .hp-stat::before{content:'';position:absolute;top:0;left:0;width:4px;height:100%;border-radius:4px 0 0 4px;}
        .hp-stat.red::before{background:#ef4444;}
        .hp-stat.green::before{background:#10b981;}
        .hp-stat.amber::before{background:#f59e0b;}
        .hp-stat-top{display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;}
        .hp-stat-ico{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;}
        .hp-stat-ico svg{width:20px;height:20px;}
        .hp-stat.red .hp-stat-ico{background:#fef2f2;color:#ef4444;}
        .hp-stat.green .hp-stat-ico{background:#f0fdf4;color:#10b981;}
        .hp-stat.amber .hp-stat-ico{background:#fffbeb;color:#f59e0b;}
        .hp-stat-lbl{font-size:0.72rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;}
        .hp-stat-val{font-size:1.5rem;font-weight:800;font-family:'Cascadia Code','Fira Code',monospace;letter-spacing:-0.02em;}
        .hp-stat.red .hp-stat-val{color:#dc2626;}
        .hp-stat.green .hp-stat-val{color:#059669;}
        .hp-stat.amber .hp-stat-val{color:#d97706;}
        .hp-stat-sub{font-size:0.72rem;color:#9ca3af;margin-top:0.25rem;}

        /* ── Card ── */
        .hp-card{background:#fff;border:1px solid #e5e7eb;border-radius:var(--hp-radius);overflow:hidden;}
        .hp-card-hdr{padding:1.125rem 1.5rem;border-bottom:1px solid #f3f4f6;display:flex;gap:0.75rem;justify-content:space-between;align-items:center;flex-wrap:wrap;}
        .hp-card-title{font-size:0.9375rem;font-weight:700;color:#111827;display:flex;align-items:center;gap:0.5rem;}
        .hp-card-title svg{width:18px;height:18px;color:#6b7280;}
        .hp-actions{display:flex;gap:0.5rem;flex-wrap:wrap;}

        /* ── Buttons ── */
        .hp-btn{display:inline-flex;align-items:center;gap:0.35rem;padding:0.5rem 0.875rem;border-radius:var(--hp-radius-sm);font-size:0.8rem;font-weight:600;text-decoration:none;border:none;cursor:pointer;transition:all .15s;font-family:inherit;white-space:nowrap;}
        .hp-btn svg{width:15px;height:15px;}
        .hp-btn-primary{background:var(--hp-accent);color:#fff;}
        .hp-btn-primary:hover{background:var(--hp-accent-dark);}
        .hp-btn-debt{background:#3b82f6;color:#fff;}
        .hp-btn-debt:hover{background:#2563eb;}
        .hp-btn-credit{background:#6b7280;color:#fff;}
        .hp-btn-credit:hover{background:#4b5563;}
        .hp-btn-ghost{background:#f3f4f6;color:#374151;}
        .hp-btn-ghost:hover{background:#e5e7eb;}
        .hp-btn-sm{padding:0.35rem 0.65rem;font-size:0.72rem;}
        .hp-btn-detail{background:#eff6ff;color:#2563eb;}
        .hp-btn-detail:hover{background:#dbeafe;}
        .hp-btn-danger{background:#fef2f2;color:#dc2626;}
        .hp-btn-danger:hover{background:#fee2e2;}

        /* ── Filter Bar ── */
        .hp-filter{padding:0.875rem 1.5rem;background:#fafafa;border-bottom:1px solid #f3f4f6;}
        .hp-filter-form{display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;}
        .hp-input{padding:0.45rem 0.75rem;border:1.5px solid #e5e7eb;border-radius:var(--hp-radius-sm);font-size:0.8rem;font-family:inherit;transition:all .15s;box-sizing:border-box;background:#fff;}
        .hp-input:focus{outline:none;border-color:var(--hp-accent);box-shadow:0 0 0 3px rgba(245,158,11,0.1);}
        select.hp-input{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 0.5rem center;padding-right:1.75rem;cursor:pointer;}

        /* ── Table ── */
        .hp-tbl-wrap{overflow-x:auto;}
        .hp-tbl{width:100%;border-collapse:collapse;font-size:0.8rem;}
        .hp-tbl th{text-align:left;padding:0.7rem 1rem;font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#9ca3af;border-bottom:2px solid #f3f4f6;white-space:nowrap;}
        .hp-tbl td{padding:0.75rem 1rem;border-bottom:1px solid #f9fafb;color:#374151;vertical-align:middle;}
        .hp-tbl tr:last-child td{border-bottom:none;}
        .hp-tbl tr:hover{background:#fafafa;}
        .hp-tbl tr.overdue-row{background:#fffbeb;}
        .hp-tbl tr.overdue-row:hover{background:#fef3c7;}
        .hp-mono{font-family:'Cascadia Code','Fira Code',monospace;font-size:0.75rem;}
        .hp-amount{font-family:'Cascadia Code','Fira Code',monospace;font-weight:700;font-size:0.8rem;white-space:nowrap;}
        .hp-amount.danger{color:#dc2626;}
        .hp-amount.success{color:#059669;}
        .hp-amount.muted{color:#6b7280;}

        /* ── Badges ── */
        .hp-badge{display:inline-flex;align-items:center;gap:0.2rem;padding:0.15rem 0.55rem;border-radius:999px;font-size:0.65rem;font-weight:700;white-space:nowrap;}
        .hp-badge.debt{background:#fef2f2;color:#991b1b;}
        .hp-badge.credit{background:#eff6ff;color:#1e40af;}
        .hp-badge.unpaid{background:#fef2f2;color:#991b1b;}
        .hp-badge.partial{background:#fef3c7;color:#92400e;}
        .hp-badge.paid{background:#d1fae5;color:#065f46;}
        .hp-badge.overdue{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;}

        /* ── Progress bar ── */
        .hp-prog{width:60px;height:5px;background:#f3f4f6;border-radius:999px;overflow:hidden;display:inline-block;vertical-align:middle;margin-right:0.4rem;}
        .hp-prog-fill{height:100%;border-radius:999px;transition:width .3s;}

        /* ── Customer cell ── */
        .hp-cust{font-weight:600;color:#111827;}
        .hp-cust-id{font-size:0.65rem;color:#9ca3af;font-family:'Cascadia Code','Fira Code',monospace;}

        /* ── Empty state ── */
        .hp-empty{text-align:center;padding:3.5rem 1rem;}
        .hp-empty-ico{width:56px;height:56px;margin:0 auto 0.75rem;background:#f3f4f6;border-radius:16px;display:flex;align-items:center;justify-content:center;}
        .hp-empty-ico svg{width:28px;height:28px;color:#9ca3af;}
        .hp-empty-title{font-size:0.9375rem;font-weight:700;color:#6b7280;}
        .hp-empty-sub{font-size:0.8rem;color:#9ca3af;margin-top:0.25rem;}

        /* ── Alerts ── */
        .hp-alert{padding:0.65rem 1rem;border-radius:var(--hp-radius-sm);font-size:0.8rem;font-weight:600;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;}
        .hp-alert-success{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;}
        .hp-alert-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
        .hp-alert svg{width:16px;height:16px;flex-shrink:0;}

        .hp-pagination{padding:1rem 1.5rem;display:flex;justify-content:center;}

        /* ── Credit number cell ── */
        .hp-cred-num{display:flex;align-items:center;gap:0.4rem;}
        .hp-cred-link{font-weight:600;color:#111827;text-decoration:none;font-family:'Cascadia Code','Fira Code',monospace;font-size:0.75rem;}
        .hp-cred-link:hover{color:var(--hp-accent-dark);text-decoration:underline;}

        /* ── Date cells ── */
        .hp-date{font-size:0.78rem;color:#6b7280;white-space:nowrap;}
        .hp-date-overdue{color:#dc2626;font-weight:600;}

        /* ── Action cell ── */
        .hp-aksi{display:flex;gap:0.35rem;align-items:center;}

        @media(max-width:768px){
            .hp-stats{grid-template-columns:1fr;}
            .hp-filter-form{flex-direction:column;}
            .hp-filter-form .hp-input{width:100%;}
            .hp-card-hdr{flex-direction:column;align-items:flex-start;}
            .hp-actions{width:100%;}
            .hp-actions .hp-btn{flex:1;justify-content:center;}
        }
    </style>
    @endpush

    <div class="hp-page">
        {{-- Alerts --}}
        @if(session('success'))
            <div class="hp-alert hp-alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="hp-alert hp-alert-danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Stats --}}
        <div class="hp-stats">
            <div class="hp-stat red">
                <div class="hp-stat-top">
                    <div class="hp-stat-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    <div class="hp-stat-lbl">Total Hutang Pelanggan</div>
                </div>
                <div class="hp-stat-val">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                <div class="hp-stat-sub">Dari pelanggan yang belum lunas</div>
            </div>
            <div class="hp-stat green">
                <div class="hp-stat-top">
                    <div class="hp-stat-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div class="hp-stat-lbl">Total Piutang / Limit</div>
                </div>
                <div class="hp-stat-val">Rp {{ number_format($totalCredit, 0, ',', '.') }}</div>
                <div class="hp-stat-sub">Piutang yang belum diterima</div>
            </div>
            <div class="hp-stat amber">
                <div class="hp-stat-top">
                    <div class="hp-stat-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div class="hp-stat-lbl">Transaksi Jatuh Tempo</div>
                </div>
                <div class="hp-stat-val">{{ $overdueCount }}</div>
                <div class="hp-stat-sub">Perlu tindak lanjut segera</div>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="hp-card">
            <div class="hp-card-hdr">
                <div class="hp-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Daftar Hutang & Piutang
                </div>
                <div class="hp-actions">
                    <a href="{{ route('pelanggan.kredit.consolidated') }}" class="hp-btn hp-btn-ghost">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                        Konsolidasi
                    </a>
                    <a href="{{ route('pelanggan.kredit.create', ['type'=>'debt']) }}" class="hp-btn hp-btn-debt">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Hutang Baru
                    </a>
                    <a href="{{ route('pelanggan.kredit.create', ['type'=>'credit']) }}" class="hp-btn hp-btn-credit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Piutang Baru
                    </a>
                </div>
            </div>

            {{-- Filters --}}
            <div class="hp-filter">
                <form method="GET" class="hp-filter-form">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / no. kredit..." class="hp-input" style="min-width:180px;flex:1;max-width:240px;">
                    <select name="type" class="hp-input" style="min-width:120px;">
                        <option value="">Semua Jenis</option>
                        <option value="debt" @selected(request('type')=='debt')>Hutang</option>
                        <option value="credit" @selected(request('type')=='credit')>Piutang</option>
                    </select>
                    <select name="status" class="hp-input" style="min-width:120px;">
                        <option value="">Semua Status</option>
                        <option value="unpaid" @selected(request('status')=='unpaid')>Belum Lunas</option>
                        <option value="partial" @selected(request('status')=='partial')>Sebagian</option>
                        <option value="paid" @selected(request('status')=='paid')>Lunas</option>
                    </select>
                    <select name="customer_id" class="hp-input" style="min-width:160px;">
                        <option value="">Semua Pelanggan</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" @selected(request('customer_id')==$c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="hp-btn hp-btn-primary hp-btn-sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('pelanggan.kredit.index') }}" class="hp-btn hp-btn-ghost hp-btn-sm">Reset</a>
                </form>
            </div>

            {{-- Table --}}
            <div class="hp-tbl-wrap">
                <table class="hp-tbl">
                    <thead>
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>No. Kredit</th>
                            <th>Jenis</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th>
                            <th style="text-align:right;">Jumlah</th>
                            <th style="text-align:right;">Terbayar</th>
                            <th>Sisa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($credits as $i => $cr)
                        @php
                            $isOver = $cr->isOverdue();
                            $remaining = (int) $cr->remaining_amount;
                            $pct = (float) $cr->amount > 0 ? min(100, ((float) $cr->paid_amount / (float) $cr->amount) * 100) : 0;
                            $barColor = $pct >= 100 ? '#10b981' : ($pct > 0 ? '#f59e0b' : '#ef4444');
                        @endphp
                        <tr class="{{ $isOver ? 'overdue-row' : '' }}">
                            <td class="hp-mono" style="color:#9ca3af;">{{ $credits->firstItem() + $i }}</td>
                            <td>
                                <div class="hp-cred-num">
                                    <a href="{{ route('pelanggan.kredit.show', $cr) }}" class="hp-cred-link">{{ $cr->credit_number }}</a>
                                    @if($isOver)
                                        <span class="hp-badge overdue">Telat</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($cr->type === 'debt')
                                    <span class="hp-badge debt">Hutang</span>
                                @else
                                    <span class="hp-badge credit">Piutang</span>
                                @endif
                            </td>
                            <td>
                                <div class="hp-cust">{{ $cr->customer->name ?? '-' }}</div>
                            </td>
                            <td class="hp-date">{{ $cr->transaction_date->format('d/m/Y') }}</td>
                            <td class="hp-date {{ $isOver ? 'hp-date-overdue' : '' }}">
                                {{ $cr->due_date ? $cr->due_date->format('d/m/Y') : '-' }}
                                @if($isOver)
                                    <span style="font-size:0.65rem;color:#b91c1c;">({{ $cr->due_date->diffForHumans(null, true) }} lalu)</span>
                                @endif
                            </td>
                            <td class="hp-amount" style="text-align:right;">Rp {{ number_format((float) $cr->amount, 0, ',', '.') }}</td>
                            <td class="hp-amount success" style="text-align:right;">Rp {{ number_format((float) $cr->paid_amount, 0, ',', '.') }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.4rem;">
                                    <div class="hp-prog">
                                        <div class="hp-prog-fill" style="width:{{ (int) $pct }}%;background:{{ $barColor }};"></div>
                                    </div>
                                    <span class="hp-amount {{ $remaining > 0 ? 'danger' : 'success' }}">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                                </div>
                            </td>
                            <td>
                                @if($cr->status === 'paid')
                                    <span class="hp-badge paid">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:11px;height:11px;"><polyline points="20 6 9 17 4 12"/></svg>
                                        Lunas
                                    </span>
                                @elseif($cr->status === 'partial')
                                    <span class="hp-badge partial">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:11px;height:11px;"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                        Sebagian
                                    </span>
                                @else
                                    <span class="hp-badge unpaid">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:11px;height:11px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="hp-aksi">
                                    <a href="{{ route('pelanggan.kredit.show', $cr) }}" class="hp-btn hp-btn-detail hp-btn-sm">Detail</a>
                                    @can('delete_hutang_piutang')
                                        @if($cr->status === 'unpaid')
                                        <form action="{{ route('pelanggan.kredit.destroy', $cr) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus {{ $cr->credit_number }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="hp-btn hp-btn-danger hp-btn-sm" title="Hapus">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11">
                                <div class="hp-empty">
                                    <div class="hp-empty-ico">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    </div>
                                    <div class="hp-empty-title">Belum Ada Data</div>
                                    <div class="hp-empty-sub">Catat hutang atau piutang pertama Anda untuk mulai melacak.</div>
                                    <div style="margin-top:1rem;display:flex;gap:0.5rem;justify-content:center;">
                                        <a href="{{ route('pelanggan.kredit.create', ['type'=>'debt']) }}" class="hp-btn hp-btn-debt">+ Hutang Baru</a>
                                        <a href="{{ route('pelanggan.kredit.create', ['type'=>'credit']) }}" class="hp-btn hp-btn-credit">+ Piutang Baru</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($credits->hasPages())
                <div class="hp-pagination">{{ $credits->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
