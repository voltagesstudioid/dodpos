<x-app-layout>
    <x-slot name="header">Detail Kredit #{{ $kredit->credit_number }}</x-slot>

    @push('styles')
    <style>
        .ck-page{max-width:68rem;margin:0 auto;padding:0 1rem 3rem;font-family:'Segoe UI',system-ui,sans-serif;}

        /* ── Back link ── */
        .ck-back{display:inline-flex;align-items:center;gap:0.5rem;font-size:0.8rem;font-weight:600;color:#6b7280;text-decoration:none;padding:0.4rem 0.6rem;border-radius:var(--ck-radius-sm);transition:all .15s;margin-bottom:1rem;}
        .ck-back:hover{background:#f3f4f6;color:#374151;}
        .ck-back svg{width:16px;height:16px;}

        /* ── Grid ── */
        .ck-grid{display:grid;grid-template-columns:1fr 360px;gap:1.5rem;align-items:start;}

        /* ── Card ── */
        .ck-card{background:#fff;border:1px solid #e5e7eb;border-radius:var(--ck-radius);overflow:hidden;margin-bottom:1.25rem;}
        .ck-card-hdr{padding:1rem 1.25rem;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:0.65rem;flex-wrap:wrap;}
        .ck-card-hdr-ico{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .ck-card-hdr-ico svg{width:18px;height:18px;}
        .ck-card-hdr-title{font-size:0.9rem;font-weight:700;color:#111827;}
        .ck-card-hdr-sub{font-size:0.72rem;color:#6b7280;}
        .ck-card-body{padding:1.25rem;}

        /* ── Badges ── */
        .ck-badge{display:inline-flex;align-items:center;gap:0.25rem;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.68rem;font-weight:700;white-space:nowrap;}
        .ck-badge svg{width:12px;height:12px;}
        .ck-badge.unpaid{background:#fef2f2;color:#991b1b;}
        .ck-badge.partial{background:#fef3c7;color:#92400e;}
        .ck-badge.paid{background:#d1fae5;color:#065f46;}
        .ck-badge.overdue{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;}

        /* ── Info grid ── */
        .ck-info{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;}
        .ck-info-lbl{font-size:0.65rem;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;color:#9ca3af;margin-bottom:0.2rem;}
        .ck-info-val{font-size:0.85rem;font-weight:700;color:#111827;}
        .ck-info-val.danger{color:#dc2626;}

        /* ── Description ── */
        .ck-desc{font-size:0.8rem;color:#6b7280;padding:0.7rem 1rem;background:#fafafa;border-radius:var(--ck-radius-sm);margin-top:1rem;display:flex;align-items:flex-start;gap:0.5rem;}
        .ck-desc svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}

        /* ── Progress ── */
        .ck-progress-wrap{margin-top:1.25rem;}
        .ck-progress-labels{display:flex;justify-content:space-between;font-size:0.72rem;color:#6b7280;margin-bottom:0.4rem;}
        .ck-progress-labels strong{color:#111827;}
        .ck-progress-bar{background:#f3f4f6;border-radius:999px;height:10px;overflow:hidden;}
        .ck-progress-fill{height:100%;border-radius:999px;transition:width .6s ease;}
        .ck-progress-amounts{display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem;margin-top:0.875rem;}
        .ck-amt{padding:0.875rem;border-radius:var(--ck-radius-sm);text-align:center;}
        .ck-amt-lbl{font-size:0.6rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;}
        .ck-amt-val{font-size:1.05rem;font-weight:800;margin-top:0.2rem;font-family:'Cascadia Code','Fira Code',monospace;letter-spacing:-0.02em;}

        /* ── Overdue alert ── */
        .ck-overdue{display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:var(--ck-radius-sm);margin-top:1rem;}
        .ck-overdue svg{width:20px;height:20px;color:#dc2626;flex-shrink:0;}
        .ck-overdue-title{font-size:0.8rem;color:#991b1b;font-weight:600;}
        .ck-overdue-sub{font-size:0.72rem;color:#b91c1c;margin-top:1px;}

        /* ── Payment table ── */
        .ck-tbl-wrap{overflow-x:auto;}
        .ck-tbl{width:100%;border-collapse:collapse;font-size:0.8rem;}
        .ck-tbl th{text-align:left;padding:0.65rem 1rem;font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#9ca3af;border-bottom:2px solid #f3f4f6;}
        .ck-tbl td{padding:0.75rem 1rem;border-bottom:1px solid #f9fafb;color:#374151;}
        .ck-tbl tr:last-child td{border-bottom:none;}
        .ck-tbl .mono{font-family:'Cascadia Code','Fira Code',monospace;font-size:0.75rem;}
        .ck-tbl .amt{font-weight:700;color:#059669;font-family:'Cascadia Code','Fira Code',monospace;}
        .ck-tbl tfoot td{border-top:2px solid #f3f4f6;font-weight:700;background:#fafafa;}

        /* ── Delete payment btn ── */
        .ck-del-pay{background:#fef2f2;color:#dc2626;border:none;padding:0.25rem 0.55rem;border-radius:6px;font-size:0.68rem;font-weight:600;cursor:pointer;font-family:inherit;display:inline-flex;align-items:center;gap:0.25rem;transition:all .15s;}
        .ck-del-pay:hover{background:#fee2e2;}
        .ck-del-pay svg{width:12px;height:12px;}

        /* ── Form ── */
        .ck-fg{margin-bottom:0.875rem;}
        .ck-fl{display:block;font-size:0.78rem;font-weight:600;color:#374151;margin-bottom:0.3rem;}
        .ck-fi{width:100%;padding:0.55rem 0.75rem;border:1.5px solid #e5e7eb;border-radius:var(--ck-radius-sm);font-size:0.8rem;font-family:inherit;transition:all .15s;box-sizing:border-box;background:#fff;}
        .ck-fi:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,0.08);}
        .ck-fi.error{border-color:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,0.1);}
        select.ck-fi{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 0.6rem center;padding-right:1.75rem;}

        .ck-max-row{display:flex;justify-content:space-between;align-items:center;margin-top:0.3rem;font-size:0.7rem;}
        .ck-max-lbl{color:#9ca3af;}
        .ck-max-val{color:#3b82f6;font-weight:700;cursor:pointer;font-family:'Cascadia Code','Fira Code',monospace;}
        .ck-max-val:hover{text-decoration:underline;}
        .ck-err{color:#dc2626;font-size:0.7rem;font-weight:600;margin-top:0.3rem;display:none;}

        .ck-quick{display:grid;grid-template-columns:repeat(3,1fr);gap:0.4rem;margin-bottom:0.875rem;}
        .ck-quick-btn{padding:0.45rem;border:1.5px solid #e5e7eb;border-radius:var(--ck-radius-sm);background:#fff;font-size:0.7rem;font-weight:700;color:#374151;cursor:pointer;transition:all .15s;font-family:inherit;text-align:center;}
        .ck-quick-btn:hover{border-color:#3b82f6;color:#3b82f6;background:#eff6ff;}

        .ck-submit{display:flex;align-items:center;justify-content:center;gap:0.5rem;width:100%;padding:0.7rem;border:none;border-radius:var(--ck-radius-sm);font-size:0.82rem;font-weight:700;cursor:pointer;transition:all .15s;font-family:inherit;background:#3b82f6;color:#fff;}
        .ck-submit:hover{background:#2563eb;}
        .ck-submit svg{width:16px;height:16px;}

        /* ── Lunas card ── */
        .ck-lunas{text-align:center;padding:2.5rem 1.25rem;}
        .ck-lunas-ico{width:56px;height:56px;margin:0 auto 0.75rem;background:#d1fae5;border-radius:16px;display:flex;align-items:center;justify-content:center;}
        .ck-lunas-ico svg{width:28px;height:28px;color:#059669;}
        .ck-lunas-title{font-size:1rem;font-weight:800;color:#065f46;}
        .ck-lunas-sub{font-size:0.78rem;color:#6b7280;margin-top:0.2rem;}

        .ck-empty{text-align:center;padding:2.5rem 1rem;color:#9ca3af;font-size:0.8rem;}
        .ck-empty-ico{width:48px;height:48px;margin:0 auto 0.6rem;background:#f3f4f6;border-radius:14px;display:flex;align-items:center;justify-content:center;}
        .ck-empty-ico svg{width:24px;height:24px;color:#9ca3af;}

        /* ── Delete record ── */
        .ck-danger-zone{border:1px solid #fecaca;border-radius:var(--ck-radius-sm);padding:1rem;margin-top:0.75rem;}
        .ck-danger-zone-title{font-size:0.72rem;font-weight:700;color:#991b1b;margin-bottom:0.25rem;display:flex;align-items:center;gap:0.3rem;}
        .ck-danger-zone-title svg{width:14px;height:14px;}
        .ck-danger-zone-sub{font-size:0.68rem;color:#b91c1c;margin-bottom:0.75rem;}
        .ck-danger-btn{display:flex;align-items:center;justify-content:center;gap:0.4rem;width:100%;padding:0.55rem;border:1.5px solid #fca5a5;border-radius:var(--ck-radius-sm);background:#fff;font-size:0.78rem;font-weight:600;color:#dc2626;cursor:pointer;transition:all .15s;font-family:inherit;}
        .ck-danger-btn:hover{background:#fef2f2;}
        .ck-danger-btn svg{width:14px;height:14px;}

        /* ── Alerts ── */
        .ck-alert{padding:0.6rem 1rem;border-radius:var(--ck-radius-sm);font-size:0.78rem;font-weight:600;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;}
        .ck-alert-success{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;}
        .ck-alert-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
        .ck-alert svg{width:15px;height:15px;flex-shrink:0;}

        /* ── Form sticky ── */
        .ck-sidebar{position:sticky;top:1rem;}

        @media(max-width:768px){
            .ck-grid{grid-template-columns:1fr;}
            .ck-info{grid-template-columns:1fr 1fr;}
            .ck-progress-amounts{grid-template-columns:1fr;}
            .ck-sidebar{position:static;}
        }
    </style>
    @endpush

    @php
        $isDebt = $kredit->type === 'debt';
        $isOver = $kredit->isOverdue();
        $remaining = (int) $kredit->remaining_amount;
        $pct = (float) $kredit->amount > 0 ? min(100, ((float) $kredit->paid_amount / (float) $kredit->amount) * 100) : 0;
        $barColor = $pct >= 100 ? '#10b981' : ($pct > 0 ? '#f59e0b' : '#ef4444');
        $hasPayments = $kredit->payments->isNotEmpty();
    @endphp

    <div class="ck-page" style="padding-top:1.5rem;">
        <a href="{{ route('pelanggan.kredit.index') }}" class="ck-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 8H1M8 15l-7-7 7-7"/></svg>
            Kembali ke Daftar Hutang & Piutang
        </a>

        @if(session('success'))
            <div class="ck-alert ck-alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="ck-alert ck-alert-danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="ck-grid">
            {{-- LEFT COLUMN --}}
            <div>
                {{-- Main Info Card --}}
                <div class="ck-card">
                    <div class="ck-card-hdr" style="background:var(--ck-accent-light);">
                        <div class="ck-card-hdr-ico" style="background:var(--ck-accent);color:#fff;">
                            @if($isDebt)
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            @endif
                        </div>
                        <div style="flex:1;">
                            <div class="ck-card-hdr-title" style="font-family:'Cascadia Code','Fira Code',monospace;">{{ $kredit->credit_number }}</div>
                            <div class="ck-card-hdr-sub">{{ $kredit->type_label }} &mdash; <a href="{{ route('pelanggan.show', $kredit->customer) }}" style="color:var(--ck-accent-dark);text-decoration:none;font-weight:600;">{{ $kredit->customer->name }}</a></div>
                        </div>
                        @if($kredit->status === 'paid')
                            <span class="ck-badge paid">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                Lunas
                            </span>
                        @elseif($kredit->status === 'partial')
                            <span class="ck-badge partial">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                Sebagian
                            </span>
                        @else
                            <span class="ck-badge unpaid">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Belum Lunas
                            </span>
                        @endif
                        @if($isOver)
                            <span class="ck-badge overdue">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                Jatuh Tempo
                            </span>
                        @endif
                    </div>
                    <div class="ck-card-body">
                        <div class="ck-info">
                            <div>
                                <div class="ck-info-lbl">Tgl. Transaksi</div>
                                <div class="ck-info-val">{{ $kredit->transaction_date->format('d M Y') }}</div>
                            </div>
                            <div>
                                <div class="ck-info-lbl">Jatuh Tempo</div>
                                <div class="ck-info-val {{ $isOver ? 'danger' : '' }}">
                                    {{ $kredit->due_date ? $kredit->due_date->format('d M Y') : '-' }}
                                </div>
                            </div>
                            <div>
                                <div class="ck-info-lbl">Dibuat</div>
                                <div class="ck-info-val">{{ $kredit->created_at->format('d M Y') }}</div>
                            </div>
                        </div>

                        @if($kredit->description)
                            <div class="ck-desc">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                {{ $kredit->description }}
                            </div>
                        @endif

                        {{-- Progress --}}
                        <div class="ck-progress-wrap">
                            <div class="ck-progress-labels">
                                <span>Progress Pembayaran</span>
                                <strong>{{ number_format($pct, 1) }}%</strong>
                            </div>
                            <div class="ck-progress-bar">
                                <div class="ck-progress-fill" id="progressFill" style="width:0%;background:{{ $barColor }};" data-pct="{{ (int) $pct }}"></div>
                            </div>
                            <div class="ck-progress-amounts">
                                <div class="ck-amt" style="background:#f9fafb;">
                                    <div class="ck-amt-lbl">Total</div>
                                    <div class="ck-amt-val" style="color:#111827;">Rp {{ number_format((float) $kredit->amount, 0, ',', '.') }}</div>
                                </div>
                                <div class="ck-amt" style="background:#f0fdf4;">
                                    <div class="ck-amt-lbl">Terbayar</div>
                                    <div class="ck-amt-val" style="color:#059669;">Rp {{ number_format((float) $kredit->paid_amount, 0, ',', '.') }}</div>
                                </div>
                                <div class="ck-amt" style="background:#fef2f2;">
                                    <div class="ck-amt-lbl">Sisa</div>
                                    <div class="ck-amt-val" style="color:#dc2626;">Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>

                        @if($isOver)
                            @php $daysOverdue = $kredit->due_date->diffInDays(now()); @endphp
                            <div class="ck-overdue">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                <div>
                                    <div class="ck-overdue-title">Sudah jatuh tempo {{ $daysOverdue }} hari</div>
                                    <div class="ck-overdue-sub">Segera lakukan pembayaran untuk menghindari masalah.</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment History --}}
                <div class="ck-card">
                    <div class="ck-card-hdr">
                        <div class="ck-card-hdr-ico" style="background:#ecfdf5;color:#059669;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div class="ck-card-hdr-title">Riwayat Pembayaran</div>
                        @if($hasPayments)
                            <span class="ck-badge paid" style="margin-left:auto;">{{ $kredit->payments->count() }} transaksi</span>
                        @endif
                    </div>
                    @if(!$hasPayments)
                        <div class="ck-empty">
                            <div class="ck-empty-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            </div>
                            Belum ada pembayaran yang tercatat.
                        </div>
                    @else
                        <div class="ck-tbl-wrap">
                            <table class="ck-tbl">
                                <thead>
                                    <tr>
                                        <th style="width:36px;">#</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Referensi</th>
                                        <th>Dibuat Oleh</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kredit->payments as $idx => $p)
                                    <tr>
                                        <td class="mono" style="color:#9ca3af;">{{ $idx + 1 }}</td>
                                        <td>{{ $p->payment_date->format('d M Y') }}</td>
                                        <td class="amt">Rp {{ number_format((float) $p->amount, 0, ',', '.') }}</td>
                                        <td>{{ $p->payment_method_label }}</td>
                                        <td class="mono" style="color:#6b7280;">{{ $p->reference_number ?: '-' }}</td>
                                        <td style="color:#6b7280;font-size:0.72rem;">{{ $p->createdBy->name ?? '-' }}</td>
                                        <td>
                                            @can('delete_hutang_piutang')
                                            <form action="{{ route('pelanggan.kredit.delete_payment', $p) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus pembayaran Rp {{ number_format((float) $p->amount, 0, ',', '.') }} ini? Hutang akan dihitung ulang.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="ck-del-pay" title="Hapus pembayaran">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" style="text-align:right;font-size:0.75rem;color:#6b7280;">Total Terbayar:</td>
                                        <td class="amt" style="font-size:0.85rem;">Rp {{ number_format((float) $kredit->paid_amount, 0, ',', '.') }}</td>
                                        <td colspan="4"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @if($kredit->notes)
                            <div style="padding:0.65rem 1rem;font-size:0.72rem;color:#6b7280;border-top:1px solid #f3f4f6;display:flex;align-items:flex-start;gap:0.4rem;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                {{ $kredit->notes }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="ck-sidebar">
                @if($kredit->status !== 'paid' && $remaining > 0)
                    <div class="ck-card">
                        <div class="ck-card-hdr" style="background:var(--ck-accent-light);">
                            <div class="ck-card-hdr-ico" style="background:var(--ck-accent);color:#fff;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            </div>
                            <div>
                                <div class="ck-card-hdr-title">{{ $isDebt ? 'Catat Pembayaran Hutang' : 'Cairkan Piutang' }}</div>
                                <div class="ck-card-hdr-sub">Sisa: Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="ck-card-body">
                            <form action="{{ route('pelanggan.kredit.pay', $kredit) }}" method="POST" id="paymentForm" novalidate>
                                @csrf
                                <div class="ck-fg">
                                    <label class="ck-fl">Tanggal Bayar</label>
                                    <input type="date" name="payment_date" class="ck-fi" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                    @error('payment_date')<div style="font-size:0.7rem;color:#dc2626;font-weight:600;margin-top:0.2rem;">{{ $message }}</div>@enderror
                                </div>
                                <div class="ck-fg">
                                    <label class="ck-fl">Jumlah (Rp)</label>
                                    <input type="number" name="amount" id="amountInput" class="ck-fi"
                                           min="1"
                                           data-max="{{ $remaining }}"
                                           value="{{ old('amount') }}"
                                           placeholder="Maks: Rp {{ number_format($remaining, 0, ',', '.') }}"
                                           required>
                                    <div class="ck-max-row">
                                        <span class="ck-max-lbl">Maksimal:</span>
                                        <span class="ck-max-val" id="maxBtn" title="Klik untuk mengisi penuh">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="ck-err" id="amountError">Jumlah tidak boleh melebihi sisa hutang</div>
                                    @error('amount')<div style="font-size:0.7rem;color:#dc2626;font-weight:600;margin-top:0.2rem;">{{ $message }}</div>@enderror
                                </div>

                                <div class="ck-quick">
                                    <button type="button" class="ck-quick-btn" data-pct="25">25%</button>
                                    <button type="button" class="ck-quick-btn" data-pct="50">50%</button>
                                    <button type="button" class="ck-quick-btn" data-pct="100">Lunas</button>
                                </div>

                                <div class="ck-fg">
                                    <label class="ck-fl">Metode Bayar</label>
                                    <select name="payment_method" class="ck-fi" required>
                                        <option value="cash" @selected(old('payment_method')=='cash')>Tunai</option>
                                        <option value="transfer" @selected(old('payment_method')=='transfer')>Transfer</option>
                                        <option value="qris" @selected(old('payment_method')=='qris')>QRIS</option>
                                        <option value="other" @selected(old('payment_method')=='other')>Lainnya</option>
                                    </select>
                                </div>
                                <div class="ck-fg">
                                    <label class="ck-fl">No. Referensi</label>
                                    <input type="text" name="reference_number" class="ck-fi" placeholder="No. transfer..." value="{{ old('reference_number') }}">
                                </div>
                                <div class="ck-fg" style="margin-bottom:0;">
                                    <label class="ck-fl">Catatan</label>
                                    <textarea name="notes" class="ck-fi" rows="2" placeholder="Opsional...">{{ old('notes') }}</textarea>
                                </div>

                                <div style="margin-top:1rem;">
                                    <button type="submit" class="ck-submit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        Simpan Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="ck-card">
                        <div class="ck-lunas">
                            <div class="ck-lunas-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div class="ck-lunas-title">Sudah Lunas!</div>
                            <div class="ck-lunas-sub">Semua pembayaran telah selesai.</div>
                        </div>
                    </div>
                @endif

                {{-- Delete Zone — only for unpaid with NO payments --}}
                @if($kredit->status === 'unpaid' && !$hasPayments)
                    <div class="ck-danger-zone">
                        <div class="ck-danger-zone-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            Zona Berbahaya
                        </div>
                        <div class="ck-danger-zone-sub">Hapus catatan ini secara permanen. Tindakan ini tidak dapat dibatalkan.</div>
                        <form action="{{ route('pelanggan.kredit.destroy', $kredit) }}" method="POST" onsubmit="return confirm('Yakin hapus catatan {{ $kredit->credit_number }}? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="ck-danger-btn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                Hapus Catatan
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    (function() {
        // Progress bar animation
        var progressEl = document.getElementById('progressFill');
        if (progressEl) {
            var pct = Math.max(0, Math.min(100, parseInt(progressEl.dataset.pct || '0', 10)));
            requestAnimationFrame(function() { progressEl.style.width = pct + '%'; });
        }

        // Payment form
        var amountInput = document.getElementById('amountInput');
        var maxBtn = document.getElementById('maxBtn');
        var amountError = document.getElementById('amountError');
        var form = document.getElementById('paymentForm');
        var maxAmount = parseInt(amountInput ? amountInput.dataset.max : '0', 10);

        function fmtRp(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function validateAmount() {
            var val = parseInt(amountInput.value, 10);
            if (val > maxAmount) {
                amountInput.classList.add('error');
                if (amountError) { amountError.textContent = 'Jumlah melebihi sisa (maks ' + fmtRp(maxAmount) + ')'; amountError.style.display = 'block'; }
                return false;
            } else if (val < 1) {
                amountInput.classList.add('error');
                if (amountError) { amountError.textContent = 'Jumlah harus lebih dari 0'; amountError.style.display = 'block'; }
                return false;
            } else {
                amountInput.classList.remove('error');
                if (amountError) amountError.style.display = 'none';
                return true;
            }
        }

        if (amountInput && maxAmount > 0) {
            amountInput.addEventListener('input', validateAmount);

            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validateAmount() || isNaN(parseInt(amountInput.value, 10))) {
                        e.preventDefault();
                        amountInput.focus();
                        return false;
                    }
                });
            }
        }

        if (maxBtn && amountInput) {
            maxBtn.addEventListener('click', function() {
                amountInput.value = maxAmount;
                validateAmount();
                amountInput.dispatchEvent(new Event('input'));
            });
        }

        document.querySelectorAll('.ck-quick-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var pctVal = parseInt(this.dataset.pct, 10);
                var val = Math.floor(maxAmount * pctVal / 100);
                if (amountInput) {
                    amountInput.value = val;
                    validateAmount();
                    amountInput.dispatchEvent(new Event('input'));
                }
            });
        });
    })();
    </script>
    @endpush
</x-app-layout>
