<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .st-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .st-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .st-hdr-l { display:flex; align-items:center; gap:1rem; }
        .st-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            box-shadow:0 8px 24px rgba(37,99,235,0.3);
        }
        .st-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .st-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .st-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s cubic-bezier(0.4,0,0.2,1); border:none; cursor:pointer;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 4px 14px rgba(37,99,235,0.35);
        }
        .st-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(37,99,235,0.45); }

        /* KPI Row */
        .st-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
        .st-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .st-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .st-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .st-kpi.amber::before  { background:linear-gradient(180deg,#f59e0b,#d97706); }
        .st-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .st-kpi.blue::before   { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .st-kpi-top { display:flex; align-items:center; justify-content:space-between; }
        .st-kpi-left { display:flex; flex-direction:column; gap:0.25rem; }
        .st-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .st-kpi-val { font-size:2.5rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
        .st-kpi-val.amber  { color:#d97706; }
        .st-kpi-val.green  { color:#059669; }
        .st-kpi-val.blue   { color:#2563eb; }
        .st-kpi-foot { font-size:0.72rem; color:#94a3b8; margin-top:0.375rem; }
        .st-kpi-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
        .st-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .st-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .st-kpi-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }

        /* Filter */
        .st-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .st-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .st-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .st-finput {
            width:100%; padding:0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .st-finput:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .st-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .st-fsel:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .st-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 4px 12px rgba(37,99,235,0.25);
        }
        .st-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(37,99,235,0.35); }
        .st-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .st-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        /* Table */
        .st-tbl {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .st-tbl-head { background:linear-gradient(180deg,#eff6ff,#f0f7ff); border-bottom:2px solid #bfdbfe; }
        .st-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#1e40af; white-space:nowrap;
        }
        .st-tbl-body td { padding:0.9375rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .st-tbl-body tr { transition:background 0.15s; }
        .st-tbl-body tr:last-child td { border-bottom:none; }
        .st-tbl-body tr:hover td { background:linear-gradient(90deg,#f8faff,#eff6ff); }

        /* Date cell */
        .st-date { font-size:0.8125rem; font-weight:600; color:#1e293b; }

        /* Sales cell */
        .st-sales { display:flex; align-items:center; gap:0.75rem; }
        .st-sales-av {
            width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center;
            font-size:1rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 4px 12px rgba(37,99,235,0.2);
        }
        .st-sales-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }

        /* Money cells */
        .st-money { font-family:'JetBrains Mono',monospace; font-size:0.8125rem; }
        .st-money.setor { color:#1e293b; font-weight:700; }
        .st-money.penjualan { color:#64748b; }

        /* Selisih badge */
        .st-selisih {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
            font-family:'JetBrains Mono',monospace;
        }
        .st-selisih.pas { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .st-selisih.kurang { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
        .st-selisih.lebih { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }

        /* Status badge */
        .st-status {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .st-status.terverifikasi { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .st-status.pending { background:#fffbeb; color:#d97706; border-color:#fde68a; }
        .st-status.ditolak { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
        .st-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .st-status-dot.terverifikasi { background:#10b981; }
        .st-status-dot.pending { background:#f59e0b; box-shadow:0 0 0 2px rgba(245,158,11,0.2); animation:st-pulse 1.5s infinite; }
        .st-status-dot.ditolak { background:#ef4444; }
        @keyframes st-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Actions */
        .st-act-grp { display:flex; gap:0.375rem; align-items:center; justify-content:center; }
        .st-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.375rem 0.625rem; border-radius:8px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
        }
        .st-act.detail { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .st-act.detail:hover { background:#dbeafe; border-color:#93c5fd; }
        .st-act.verify { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .st-act.verify:hover { background:#d1fae5; }

        /* Empty */
        .st-empty { text-align:center; padding:3.5rem 1.5rem; }
        .st-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#eff6ff,#dbeafe); display:flex; align-items:center; justify-content:center;
        }
        .st-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .st-empty-sub { font-size:0.8125rem; color:#94a3b8; }

        @media(max-width:1024px) { .st-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px)  { .st-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="st-page">

            {{-- Header --}}
            <div class="st-hdr">
                <div class="st-hdr-l">
                    <div class="st-hdr-ico">💰</div>
                    <div>
                        <div class="st-hdr-title">Verifikasi Setoran</div>
                        <div class="st-hdr-sub">Monitoring dan verifikasi setoran dari sales</div>
                    </div>
                </div>
                <a href="{{ route('mineral.setoran.create') }}" class="st-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Setoran
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="st-kpis">
                <div class="st-kpi amber">
                    <div class="st-kpi-top">
                        <div class="st-kpi-left">
                            <span class="st-kpi-lbl">Pending</span>
                            <div>
                                <span class="st-kpi-val amber">{{ $stats['total_pending'] }}</span>
                            </div>
                            <div class="st-kpi-foot">Menunggu verifikasi</div>
                        </div>
                        <div class="st-kpi-ico amber">🕐</div>
                    </div>
                </div>
                <div class="st-kpi green">
                    <div class="st-kpi-top">
                        <div class="st-kpi-left">
                            <span class="st-kpi-lbl">Terverifikasi</span>
                            <div>
                                <span class="st-kpi-val green">{{ $stats['total_terverifikasi'] }}</span>
                            </div>
                            <div class="st-kpi-foot">Setoran telah disetujui</div>
                        </div>
                        <div class="st-kpi-ico green">✅</div>
                    </div>
                </div>
                <div class="st-kpi blue">
                    <div class="st-kpi-top">
                        <div class="st-kpi-left">
                            <span class="st-kpi-lbl">Setoran Hari Ini</span>
                            <div>
                                <span class="st-kpi-val blue">Rp {{ number_format($stats['total_setoran_hari_ini'], 0, ',', '.') }}</span>
                            </div>
                            <div class="st-kpi-foot">Total setoran terverifikasi hari ini</div>
                        </div>
                        <div class="st-kpi-ico blue">💵</div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="st-filter">
                <form method="GET" class="st-ff">
                    <div>
                        <label class="st-flbl">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="st-finput">
                    </div>
                    <div>
                        <label class="st-flbl">Sales</label>
                        <select name="sales_id" class="st-fsel">
                            <option value="">Semua Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="st-flbl">Status</label>
                        <select name="status" class="st-fsel">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <button type="submit" class="st-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('mineral.setoran.index') }}" class="st-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- Table --}}
            <div class="st-tbl">
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:separate; border-spacing:0;">
                        <thead class="st-tbl-head">
                            <tr>
                                <th style="text-align:left;">Tanggal</th>
                                <th style="text-align:left;">Sales</th>
                                <th style="text-align:right;">Total Setor</th>
                                <th style="text-align:right;">Penjualan</th>
                                <th style="text-align:center;">Selisih</th>
                                <th style="text-align:center;">Bukti</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="st-tbl-body">
                            @forelse($setorans as $s)
                            <tr>
                                <td>
                                    <span class="st-date">{{ $s->tanggal->format('d M Y') }}</span>
                                </td>
                                <td>
                                    <div class="st-sales">
                                        <div class="st-sales-av">{{ substr($s->sales->nama, 0, 1) }}</div>
                                        <span class="st-sales-name">{{ $s->sales->nama }}</span>
                                    </div>
                                </td>
                                <td style="text-align:right;">
                                    <span class="st-money setor">Rp {{ number_format($s->total_setor, 0, ',', '.') }}</span>
                                </td>
                                <td style="text-align:right;">
                                    <span class="st-money penjualan">Rp {{ number_format($s->total_penjualan, 0, ',', '.') }}</span>
                                </td>
                                <td style="text-align:center;">
                                    @if($s->selisih == 0)
                                        <span class="st-selisih pas">Pas</span>
                                    @elseif($s->selisih < 0)
                                        <span class="st-selisih kurang">Kurang Rp {{ number_format(abs($s->selisih), 0, ',', '.') }}</span>
                                    @else
                                        <span class="st-selisih lebih">Lebih Rp {{ number_format($s->selisih, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    @if($s->bukti_setor)
                                        <span style="display:inline-flex; align-items:center; gap:0.25rem; color:#059669; font-size:0.6875rem; font-weight:600;" title="Bukti setoran terlampir">📷 Ada</span>
                                    @else
                                        <span style="color:#94a3b8; font-size:0.6875rem;">-</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <span class="st-status {{ $s->status }}">
                                        <span class="st-status-dot {{ $s->status }}"></span>
                                        {{ ucfirst($s->status) }}
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <div class="st-act-grp">
                                        <a href="{{ route('mineral.setoran.show', $s) }}" class="st-act detail">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Detail
                                        </a>
                                        @if($s->status == 'pending')
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="st-empty">
                                        <div class="st-empty-ico">
                                            <svg width="32" height="32" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                        </div>
                                        <div class="st-empty-title">Tidak Ada Data Setoran</div>
                                        <div class="st-empty-sub">Belum ada data setoran dalam periode ini</div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($setorans->hasPages())
                    <div style="padding:0.875rem 1.25rem; border-top:1px solid #f1f5f9;">
                        {{ $setorans->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
