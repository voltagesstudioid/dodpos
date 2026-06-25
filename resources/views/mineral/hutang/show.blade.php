<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('mineral.hutang.index') }}" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-900">Detail Hutang</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $hutang->pelanggan->nama_toko ?? 'N/A' }} — {{ $hutang->pelanggan->nama_pemilik ?? '-' }}</p>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .hs-page { max-width:72rem; margin:0 auto; padding:0 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .hs-grid { display:grid; grid-template-columns:1fr 380px; gap:1.5rem; align-items:start; }
        .hs-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,.04); margin-bottom:1.25rem; }
        .hs-card-hdr { padding:1.125rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:.75rem; }
        .hs-card-ico { width:40px; height:40px; border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .hs-card-ico svg { width:20px; height:20px; }
        .hs-card-title { font-size:.9rem; font-weight:700; color:#1e293b; }
        .hs-card-body { padding:1.5rem; }

        /* Badge */
        .hs-badge { display:inline-flex; align-items:center; gap:.35rem; padding:.3rem .75rem; border-radius:999px; font-size:.72rem; font-weight:700; }
        .hs-badge.unpaid { background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
        .hs-badge.overdue { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
        .hs-badge.paid { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }

        /* Info Grid */
        .hs-info { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; }
        .hs-info-lbl { font-size:.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; margin-bottom:.25rem; }
        .hs-info-val { font-size:.875rem; font-weight:700; color:#1e293b; }
        .hs-info-val.danger { color:#dc2626; }

        /* Progress */
        .hs-progress-wrap { margin-top:1.5rem; }
        .hs-progress-labels { display:flex; justify-content:space-between; font-size:.75rem; color:#64748b; margin-bottom:.5rem; }
        .hs-progress-bar { background:#f1f5f9; border-radius:999px; height:14px; overflow:hidden; }
        .hs-progress-fill { height:100%; border-radius:999px; transition:width .6s ease; }
        .hs-amounts { display:grid; grid-template-columns:repeat(3,1fr); gap:.75rem; margin-top:1rem; }
        .hs-amt { padding:1rem; border-radius:14px; text-align:center; }
        .hs-amt-lbl { font-size:.6875rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:.05em; }
        .hs-amt-val { font-size:1.25rem; font-weight:800; margin-top:.25rem; letter-spacing:-.02em; }

        /* Table */
        .hs-tbl-wrap { overflow-x:auto; }
        .hs-tbl { width:100%; border-collapse:collapse; font-size:.8125rem; }
        .hs-tbl th { text-align:left; padding:.8rem 1rem; font-size:.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; border-bottom:2px solid #f1f5f9; background:#f8fafc; }
        .hs-tbl td { padding:.875rem 1rem; border-bottom:1px solid #f8fafc; color:#334155; vertical-align:middle; }
        .hs-tbl tr:last-child td { border-bottom:none; }
        .hs-tbl .amt { font-weight:700; color:#059669; }

        /* Status pills */
        .hs-pill { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:999px; font-size:.67rem; font-weight:700; }
        .hs-pill.confirmed { background:#d1fae5; color:#065f46; }
        .hs-pill.pending { background:#fef3c7; color:#92400e; }
        .hs-pill.rejected { background:#fee2e2; color:#991b1b; }

        /* Payment form */
        .hs-fg { margin-bottom:1rem; }
        .hs-fl { display:block; font-size:.8125rem; font-weight:600; color:#334155; margin-bottom:.375rem; }
        .hs-fi { width:100%; padding:.625rem .875rem; border:1.5px solid #e2e8f0; border-radius:10px; font-size:.8125rem; font-family:inherit; transition:all .2s; box-sizing:border-box; background:#fff; }
        .hs-fi:focus { outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.1); }
        select.hs-fi { cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right .75rem center; padding-right:2.25rem; }
        textarea.hs-fi { resize:vertical; min-height:3rem; }

        .hs-btn { display:flex; align-items:center; justify-content:center; gap:.5rem; width:100%; padding:.75rem 1rem; border:none; border-radius:12px; font-size:.8125rem; font-weight:700; cursor:pointer; transition:all .2s; font-family:inherit; }
        .hs-btn-primary { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 4px 16px rgba(37,99,235,.3); }
        .hs-btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(37,99,235,.4); }
        .hs-btn-primary:disabled { opacity:.6; cursor:not-allowed; transform:none; }

        /* Transfer fields container */
        .hs-transfer-fields { border:1.5px solid #bfdbfe; border-radius:14px; padding:1rem; background:#eff6ff; margin-bottom:1rem; }
        .hs-transfer-fields .hs-fl { color:#1e40af; }

        /* Photo preview */
        .hs-photo-preview { margin-top:.5rem; display:none; }
        .hs-photo-preview img { max-width:100%; max-height:200px; border-radius:10px; border:1px solid #e2e8f0; }

        /* Photo thumbnail in table */
        .hs-thumb { width:40px; height:40px; border-radius:8px; object-fit:cover; border:1px solid #e2e8f0; cursor:pointer; transition:transform .2s; }
        .hs-thumb:hover { transform:scale(1.1); }

        /* Modal */
        .hs-modal { display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,.7); align-items:center; justify-content:center; cursor:zoom-out; }
        .hs-modal img { max-width:90vw; max-height:90vh; border-radius:12px; }
        .hs-modal.active { display:flex; }

        /* Lunas card */
        .hs-lunas { text-align:center; padding:2.5rem 1.5rem; }
        .hs-lunas-title { font-size:1.125rem; font-weight:800; color:#065f46; }
        .hs-lunas-sub { font-size:.8125rem; color:#64748b; margin-top:.25rem; }

        .hs-empty { text-align:center; padding:2.5rem 1rem; color:#94a3b8; font-size:.8125rem; }

        .hs-keterangan { font-size:.8125rem; color:#64748b; padding:.75rem 1rem; background:#f8fafc; border-radius:10px; margin-top:1rem; display:flex; align-items:flex-start; gap:.5rem; }

        .hs-info-box { font-size:.7rem; color:#94a3b8; margin-bottom:.75rem; padding:.625rem .875rem; background:#f8fafc; border-radius:10px; border:1px solid #e2e8f0; display:flex; align-items:flex-start; gap:.5rem; }

        /* Alerts */
        .hs-alert { display:flex; align-items:center; gap:.625rem; padding:.875rem 1.25rem; border-radius:12px; margin-bottom:1.25rem; font-size:.8125rem; font-weight:600; }
        .hs-alert-success { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        .hs-alert-error { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

        /* Confirm/Reject buttons */
        .hs-act-btn { padding:4px 10px; border-radius:8px; font-size:.67rem; font-weight:700; border:1px solid; cursor:pointer; transition:all .15s; font-family:inherit; }
        .hs-act-confirm { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .hs-act-confirm:hover { background:#d1fae5; }
        .hs-act-reject { background:#fef2f2; color:#991b1b; border-color:#fecaca; }
        .hs-act-reject:hover { background:#fee2e2; }

        @media(max-width:1024px) { .hs-grid { grid-template-columns:1fr; } }
        @media(max-width:768px) { .hs-info { grid-template-columns:repeat(2,1fr); } .hs-amounts { grid-template-columns:1fr; } }
    </style>
    @endpush

    <div class="hs-page" style="padding-top:1.5rem;">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="hs-alert hs-alert-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="hs-alert hs-alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="hs-grid">
            {{-- ============ LEFT COLUMN ============ --}}
            <div>
                {{-- Header Card --}}
                <div class="hs-card">
                    @php
                        $isLunas = $hutang->status === 'lunas';
                        $isOverdue = $hutang->status === 'overdue' || ($hutang->status === 'belum_lunas' && $hutang->jatuh_tempo && $hutang->jatuh_tempo->isPast());
                    @endphp
                    <div class="hs-card-hdr" style="background:linear-gradient(135deg,{{ $isLunas ? '#ecfdf5,#f0fdf4' : ($isOverdue ? '#fef2f2,#fff1f2' : '#fefce8,#fffbeb') }});">
                        <div class="hs-card-ico" style="background:linear-gradient(135deg,{{ $isLunas ? '#10b981,#059669' : ($isOverdue ? '#ef4444,#dc2626' : '#f59e0b,#d97706') }});">
                            <svg fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div style="flex:1;">
                            <div class="hs-card-title">{{ $hutang->pelanggan->nama_toko ?? 'N/A' }}</div>
                            <div style="font-size:.75rem; color:#64748b;">{{ $hutang->pelanggan->nama_pemilik ?? '-' }}</div>
                        </div>
                        @if($isLunas)
                            <span class="hs-badge paid">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Lunas
                            </span>
                        @elseif($isOverdue)
                            <span class="hs-badge overdue">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Overdue
                            </span>
                        @else
                            <span class="hs-badge unpaid">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Belum Lunas
                            </span>
                        @endif
                    </div>
                    <div class="hs-card-body">
                        <div class="hs-info">
                            <div>
                                <div class="hs-info-lbl">No. Faktur</div>
                                <div class="hs-info-val">{{ $hutang->penjualan->no_faktur ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="hs-info-lbl">Tanggal Jual</div>
                                <div class="hs-info-val">{{ $hutang->penjualan->tanggal_jual ? \Carbon\Carbon::parse($hutang->penjualan->tanggal_jual)->format('d M Y') : '-' }}</div>
                            </div>
                            <div>
                                <div class="hs-info-lbl">Jatuh Tempo</div>
                                <div class="hs-info-val {{ $hutang->jatuh_tempo && $hutang->jatuh_tempo->isPast() ? 'danger' : '' }}">
                                    {{ $hutang->jatuh_tempo ? $hutang->jatuh_tempo->format('d M Y') : '-' }}
                                </div>
                            </div>
                            <div>
                                <div class="hs-info-lbl">Sales</div>
                                <div class="hs-info-val">{{ $hutang->penjualan->sales->nama ?? '-' }}</div>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        @php
                            $pct = (float) $hutang->total_hutang > 0 ? min(100, ((float) $hutang->dibayar / (float) $hutang->total_hutang) * 100) : 0;
                            $barColor = $pct >= 100 ? '#10b981' : ($pct > 0 ? '#f59e0b' : '#ef4444');
                        @endphp
                        <div class="hs-progress-wrap">
                            <div class="hs-progress-labels">
                                <span>Progress Pembayaran</span>
                                <span style="font-weight:700;">{{ number_format($pct, 1) }}%</span>
                            </div>
                            <div class="hs-progress-bar">
                                <div class="hs-progress-fill" id="progressFill" style="width:0%; background:{{ $barColor }};" data-pct="{{ (int) $pct }}"></div>
                            </div>
                            <div class="hs-amounts">
                                <div class="hs-amt" style="background:#f8fafc;">
                                    <div class="hs-amt-lbl">Total Hutang</div>
                                    <div class="hs-amt-val" style="color:#1e293b;">Rp {{ number_format((float) $hutang->total_hutang, 0, ',', '.') }}</div>
                                </div>
                                <div class="hs-amt" style="background:#f0fdf4;">
                                    <div class="hs-amt-lbl">Terbayar</div>
                                    <div class="hs-amt-val" style="color:#059669;">Rp {{ number_format((float) $hutang->dibayar, 0, ',', '.') }}</div>
                                </div>
                                <div class="hs-amt" style="background:#fef2f2;">
                                    <div class="hs-amt-lbl">Sisa</div>
                                    <div class="hs-amt-val" style="color:#dc2626;">Rp {{ number_format((float) $hutang->sisa, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>

                        @if($hutang->keterangan)
                            <div class="hs-keterangan">
                                <svg width="16" height="16" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                {{ $hutang->keterangan }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment History --}}
                <div class="hs-card">
                    <div class="hs-card-hdr">
                        <div class="hs-card-ico" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);">
                            <svg fill="none" stroke="#059669" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div class="hs-card-title">Riwayat Pembayaran</div>
                    </div>
                    @if($hutang->pembayarans->isEmpty())
                        <div class="hs-empty">
                            <svg width="48" height="48" fill="none" stroke="#cbd5e1" viewBox="0 0 24 24" style="margin:0 auto .75rem;display:block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Belum ada pembayaran yang tercatat.
                        </div>
                    @else
                        <div class="hs-tbl-wrap">
                            <table class="hs-tbl">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>ID Transaksi</th>
                                        <th>Bukti</th>
                                        <th>Status</th>
                                        <th>Petugas</th>
                                        @if(!$isSalesRole)
                                        <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hutang->pembayarans as $bayar)
                                    <tr>
                                        <td>{{ $bayar->tanggal_bayar ? $bayar->tanggal_bayar->format('d M Y') : '-' }}</td>
                                        <td class="amt">Rp {{ number_format((float) $bayar->jumlah, 0, ',', '.') }}</td>
                                        <td>
                                            <span style="text-transform:capitalize; font-weight:600;">{{ $bayar->cara_bayar }}</span>
                                        </td>
                                        <td>
                                            @if($bayar->id_transaksi)
                                                <span style="font-family:monospace; font-size:.75rem; background:#f1f5f9; padding:2px 8px; border-radius:6px;">{{ $bayar->id_transaksi }}</span>
                                            @else
                                                <span style="color:#94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($bayar->bukti_transfer)
                                                <img src="{{ asset('storage/' . $bayar->bukti_transfer) }}" alt="Bukti" class="hs-thumb" onclick="openModal(this.src)">
                                            @else
                                                <span style="color:#94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($bayar->status === 'confirmed')
                                                <span class="hs-pill confirmed">
                                                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    Confirmed
                                                </span>
                                            @elseif($bayar->status === 'pending')
                                                <span class="hs-pill pending">
                                                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Pending
                                                </span>
                                            @elseif($bayar->status === 'rejected')
                                                <span class="hs-pill rejected" title="{{ $bayar->reject_reason }}">
                                                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td style="color:#64748b; font-size:.75rem;">{{ $bayar->creator->name ?? '-' }}</td>
                                        @if(!$isSalesRole)
                                        <td>
                                            @if($bayar->status === 'pending')
                                                <div style="display:flex; gap:4px;">
                                                    <form action="{{ route('mineral.hutang.payment.confirm', [$hutang, $bayar]) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="hs-act-btn hs-act-confirm">Confirm</button>
                                                    </form>
                                                    <button type="button" class="hs-act-btn hs-act-reject" onclick="document.getElementById('reject-{{ $bayar->id }}').style.display='block'; this.style.display='none';">Reject</button>
                                                </div>
                                                <div id="reject-{{ $bayar->id }}" style="display:none; margin-top:6px; padding:8px; background:#fef2f2; border:1px solid #fecaca; border-radius:10px;">
                                                    <form action="{{ route('mineral.hutang.payment.reject', [$hutang, $bayar]) }}" method="POST">
                                                        @csrf
                                                        <input type="text" name="reject_reason" placeholder="Alasan penolakan..." required style="width:100%; padding:5px 8px; border:1px solid #fecaca; border-radius:8px; font-size:.72rem; margin-bottom:6px; font-family:inherit; box-sizing:border-box;">
                                                        <button type="submit" class="hs-act-btn hs-act-reject" style="background:#dc2626; color:#fff; border-color:#dc2626;">Tolak</button>
                                                    </form>
                                                </div>
                                            @else
                                                <span style="color:#94a3b8; font-size:.67rem;">-</span>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============ RIGHT COLUMN: Payment Form ============ --}}
            <div>
                @if($hutang->status !== 'lunas' && (float) $hutang->sisa > 0)
                    @php
                        $pendingTotal = $hutang->pembayarans()->where('status', 'pending')->sum('jumlah');
                        $effectiveSisa = max(0, (float) $hutang->sisa - (float) $pendingTotal);
                    @endphp
                    <div class="hs-card">
                        <div class="hs-card-hdr" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                            <div class="hs-card-ico" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
                                <svg fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div class="hs-card-title">Catat Pembayaran</div>
                        </div>
                        <div class="hs-card-body">
                            @if($effectiveSisa <= 0)
                                <div style="text-align:center; padding:1rem; color:#d97706; font-size:.8125rem; font-weight:600; background:#fffbeb; border-radius:10px; border:1px solid #fde68a;">
                                    Sisa hutang sudah tercover oleh pembayaran pending.
                                </div>
                            @else
                            <form action="{{ route('mineral.hutang.bayar', $hutang) }}" method="POST" enctype="multipart/form-data" id="form-bayar">
                                @csrf
                                <div class="hs-fg">
                                    <label class="hs-fl">Jumlah Bayar <span style="color:#ef4444;">*</span></label>
                                    <input type="text" inputmode="numeric" data-currency name="jumlah" class="hs-fi" value="{{ old('jumlah') }}" placeholder="Maks: Rp {{ number_format($effectiveSisa, 0, ',', '.') }}" required>
                                    @error('jumlah') <div style="font-size:.72rem; color:#ef4444; margin-top:4px;">{{ $message }}</div> @enderror
                                </div>

                                <div class="hs-fg">
                                    <label class="hs-fl">Cara Bayar <span style="color:#ef4444;">*</span></label>
                                    <select name="cara_bayar" id="cara_bayar" class="hs-fi" required>
                                        <option value="tunai" {{ old('cara_bayar') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                                        <option value="transfer" {{ old('cara_bayar') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    </select>
                                    @error('cara_bayar') <div style="font-size:.72rem; color:#ef4444; margin-top:4px;">{{ $message }}</div> @enderror
                                </div>

                                {{-- Transfer-specific fields --}}
                                <div id="transfer-fields" style="display:none;">
                                    <div class="hs-transfer-fields">
                                        <div class="hs-fg" style="margin-bottom:.75rem;">
                                            <label class="hs-fl">
                                                <svg width="14" height="14" fill="none" stroke="#1e40af" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                                ID / No. Transaksi <span style="color:#ef4444;">*</span>
                                            </label>
                                            <input type="text" name="id_transaksi" class="hs-fi" value="{{ old('id_transaksi') }}" placeholder="Contoh: TRX123456789" style="font-family:monospace;">
                                            @error('id_transaksi') <div style="font-size:.72rem; color:#ef4444; margin-top:4px;">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="hs-fg" style="margin-bottom:0;">
                                            <label class="hs-fl">
                                                <svg width="14" height="14" fill="none" stroke="#1e40af" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                Foto Bukti Transfer <span style="color:#ef4444;">*</span>
                                            </label>
                                            <input type="file" name="bukti_transfer" id="bukti-input" class="hs-fi" accept="image/*" capture="environment" style="padding:.5rem;">
                                            <div class="hs-photo-preview" id="bukti-preview">
                                                <img id="bukti-preview-img" src="" alt="Preview">
                                            </div>
                                            @error('bukti_transfer') <div style="font-size:.72rem; color:#ef4444; margin-top:4px;">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="hs-fg">
                                    <label class="hs-fl">Keterangan</label>
                                    <textarea name="keterangan" class="hs-fi" rows="2" placeholder="Catatan tambahan (opsional)...">{{ old('keterangan') }}</textarea>
                                </div>

                                <div class="hs-info-box">
                                    <svg width="14" height="14" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <div><strong style="color:#475569;">Info:</strong> Tunai &lt; Rp 500.000 otomatis konfirmasi. Transfer &amp; tunai ≥ Rp 500.000 menunggu approval supervisor.</div>
                                </div>

                                <button type="submit" class="hs-btn hs-btn-primary" id="btn-submit">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                    Simpan Pembayaran
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="hs-card">
                        <div class="hs-card-body hs-lunas">
                            <svg width="56" height="56" fill="none" stroke="#10b981" viewBox="0 0 24 24" style="margin:0 auto .75rem;display:block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div class="hs-lunas-title">Hutang Lunas!</div>
                            <div class="hs-lunas-sub">Semua pembayaran telah selesai.</div>
                        </div>
                    </div>
                @endif

                <a href="{{ route('mineral.hutang.index') }}" class="hs-btn" style="background:#f1f5f9; color:#475569; margin-top:.75rem; text-decoration:none;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    {{-- Image Modal --}}
    <div class="hs-modal" id="imgModal" onclick="this.classList.remove('active');">
        <img id="modalImg" src="" alt="Preview">
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Progress bar animation
        var el = document.getElementById('progressFill');
        if (el) {
            var pct = Math.max(0, Math.min(100, parseInt(el.dataset.pct || '0', 10)));
            requestAnimationFrame(function() { el.style.width = pct + '%'; });
        }

        // Toggle transfer fields
        var caraBayar = document.getElementById('cara_bayar');
        var transferFields = document.getElementById('transfer-fields');
        var idTransaksi = document.querySelector('input[name="id_transaksi"]');
        var buktiTransfer = document.querySelector('input[name="bukti_transfer"]');

        if (caraBayar && transferFields) {
            function toggle() {
                var isTransfer = caraBayar.value === 'transfer';
                transferFields.style.display = isTransfer ? 'block' : 'none';
                if (idTransaksi) idTransaksi.required = isTransfer;
                if (buktiTransfer) buktiTransfer.required = isTransfer;
            }
            caraBayar.addEventListener('change', toggle);
            toggle();
        }

        // Photo preview for bukti transfer
        var buktiInput = document.getElementById('bukti-input');
        var buktiPreview = document.getElementById('bukti-preview');
        var buktiImg = document.getElementById('bukti-preview-img');
        if (buktiInput && buktiPreview && buktiImg) {
            buktiInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    buktiImg.src = URL.createObjectURL(this.files[0]);
                    buktiPreview.style.display = 'block';
                }
            });
        }

        // Prevent double submit
        var form = document.getElementById('form-bayar');
        if (form) {
            form.addEventListener('submit', function() {
                var btn = document.getElementById('btn-submit');
                if (btn) { btn.style.opacity = '0.7'; btn.style.cursor = 'wait'; btn.disabled = true; }
            });
        }
    });

    // Image modal
    function openModal(src) {
        var modal = document.getElementById('imgModal');
        var img = document.getElementById('modalImg');
        if (modal && img) { img.src = src; modal.classList.add('active'); }
    }
    </script>
    @endpush
</x-app-layout>
