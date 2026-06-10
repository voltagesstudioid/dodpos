<x-app-layout>
    @push('styles')
    <style>
        .st-page { max-width:80rem; margin:0 auto; padding:0 1rem; }

        /* Header */
        .st-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
        .st-hdr-left { display:flex; align-items:center; gap:1rem; }
        .st-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#f59e0b,#d97706);
            box-shadow:0 8px 24px rgba(217,119,6,0.3);
        }
        .st-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .st-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        /* Create Button */
        .st-btn-add {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.6875rem 1.25rem; border-radius:12px; font-size:0.8125rem; font-weight:700;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; border:none; cursor:pointer;
            box-shadow:0 6px 20px rgba(217,119,6,0.3); transition:all 0.25s; font-family:inherit;
            text-decoration:none;
        }
        .st-btn-add:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(217,119,6,0.4); }
        .st-btn-add:active { transform:translateY(0); }

        /* KPI Row */
        .st-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:0.875rem; margin-bottom:1.5rem; }
        .st-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .st-kpi::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .st-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .st-kpi.amber::before  { background:linear-gradient(90deg,#f59e0b,#d97706); }
        .st-kpi.green::before  { background:linear-gradient(90deg,#10b981,#059669); }
        .st-kpi.sky::before    { background:linear-gradient(90deg,#0ea5e9,#0284c7); }
        .st-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.625rem; }
        .st-kpi-lbl { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .st-kpi-ico {
            width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem;
        }
        .st-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .st-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .st-kpi-ico.sky    { background:linear-gradient(135deg,#f0f9ff,#e0f2fe); }
        .st-kpi-val { font-size:1.75rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
        .st-kpi-val.amber  { color:#d97706; }
        .st-kpi-val.green  { color:#059669; }
        .st-kpi-val.sky    { color:#0284c7; font-size:1.35rem; }
        .st-kpi-foot { font-size:0.7rem; color:#94a3b8; margin-top:0.375rem; }
        .st-kpi-chip {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.125rem 0.375rem; border-radius:99px; font-size:0.625rem; font-weight:700;
        }
        .st-kpi-chip.amber { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
        .st-kpi-chip.green { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .st-kpi-chip.sky   { background:#f0f9ff; color:#0284c7; border:1px solid #bae6fd; }

        /* Filter */
        .st-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .st-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .st-ff-fld { min-width:140px; }
        .st-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .st-finput {
            width:100%; padding:0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .st-finput:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .st-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .st-fsel:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .st-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 4px 12px rgba(217,119,6,0.25);
        }
        .st-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(217,119,6,0.35); }
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
        .st-tbl-head {
            background:linear-gradient(180deg,#fffbeb,#fef9ee); border-bottom:2px solid #fde68a;
        }
        .st-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#92400e; white-space:nowrap;
        }
        .st-tbl-body td {
            padding:0.9375rem 1.25rem; border-bottom:1px solid #fef9ee; font-size:0.8125rem;
            color:#374151; vertical-align:middle;
        }
        .st-tbl-body tr { transition:background 0.15s; }
        .st-tbl-body tr:last-child td { border-bottom:none; }
        .st-tbl-body tr:hover td { background:linear-gradient(90deg,#fffbf3,#fff7e6); }

        /* Date */
        .st-date { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .st-date-sub { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        /* Sales */
        .st-sal { display:flex; align-items:center; gap:0.75rem; }
        .st-sal-av {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:0.875rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#fffbeb,#fef3c7); color:#92400e; border:1.5px solid #fde68a;
        }
        .st-sal-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }

        /* Money */
        .st-money { text-align:right; font-weight:700; letter-spacing:-0.01em; }
        .st-money.deposit  { color:#0284c7; font-size:0.875rem; }
        .st-money.sale     { color:#0f172a; }
        .st-money.surplus  { color:#059669; }
        .st-money.deficit  { color:#dc2626; }
        .st-money-match    { color:#94a3b8; }

        /* Status */
        .st-status {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .st-status.pending       { background:#fffbeb; color:#d97706; border-color:#fde68a; }
        .st-status.terverifikasi { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .st-status.ditolak       { background:#fff1f2; color:#e11d48; border-color:#fecdd3; }
        .st-status-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .st-status-dot.pending       { background:#f59e0b; animation:st-pulse 1.5s infinite; }
        .st-status-dot.terverifikasi { background:#10b981; }
        .st-status-dot.ditolak       { background:#e11d48; }
        @keyframes st-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* Actions */
        .st-act-group { display:flex; align-items:center; justify-content:center; gap:0.375rem; }
        .st-act {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.3125rem 0.625rem; border-radius:7px; font-size:0.6875rem; font-weight:600;
            border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit;
            background:transparent;
        }
        .st-act.blue  { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .st-act.blue:hover  { background:#dbeafe; border-color:#93c5fd; }
        .st-act.green { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .st-act.green:hover { background:#d1fae5; border-color:#6ee7b7; }

        /* Empty */
        .st-empty { text-align:center; padding:3.5rem 1.5rem; }
        .st-empty-ico {
            width:72px; height:72px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#fffbeb,#fef3c7); display:flex; align-items:center; justify-content:center;
        }
        .st-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .st-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .st-empty-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.5rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; border:none; cursor:pointer;
            text-decoration:none; transition:all 0.2s; font-family:inherit;
            box-shadow:0 4px 12px rgba(217,119,6,0.25);
        }
        .st-empty-btn:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(217,119,6,0.35); }

        @media(max-width:1024px) { .st-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px) { .st-hdr { flex-direction:column; align-items:flex-start; } .st-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="st-page">

            {{-- Header --}}
            <div class="st-hdr">
                <div class="st-hdr-left">
                    <div class="st-hdr-ico">💵</div>
                    <div>
                        <div class="st-hdr-title">Verifikasi Setoran</div>
                        <div class="st-hdr-sub">Verifikasi dan approve setoran sales harian</div>
                    </div>
                </div>
                <a href="{{ route('minyak.setoran.create') }}" class="st-btn-add">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Setoran
                </a>
            </div>

            {{-- KPI Cards --}}
            <div class="st-kpis">
                <div class="st-kpi amber">
                    <div class="st-kpi-top">
                        <span class="st-kpi-lbl">Pending</span>
                        <div class="st-kpi-ico amber">⏳</div>
                    </div>
                    <div class="st-kpi-val amber">{{ $stats['total_pending'] }}</div>
                    <div class="st-kpi-foot">Menunggu verifikasi supervisor</div>
                    @if($stats['total_pending'] > 0)
                        <span class="st-kpi-chip amber">
                            <span style="width:5px;height:5px;border-radius:50%;background:#f59e0b;animation:st-pulse 1.5s infinite;"></span>
                            Butuh perhatian
                        </span>
                    @endif
                </div>
                <div class="st-kpi green">
                    <div class="st-kpi-top">
                        <span class="st-kpi-lbl">Terverifikasi</span>
                        <div class="st-kpi-ico green">✅</div>
                    </div>
                    <div class="st-kpi-val green">{{ $stats['total_terverifikasi'] }}</div>
                    <div class="st-kpi-foot">Setoran sudah di-approve</div>
                    <span class="st-kpi-chip green">Approved</span>
                </div>
                <div class="st-kpi sky">
                    <div class="st-kpi-top">
                        <span class="st-kpi-lbl">Setoran Hari Ini</span>
                        <div class="st-kpi-ico sky">💳</div>
                    </div>
                    <div class="st-kpi-val sky">Rp {{ number_format($stats['total_setoran_hari_ini'], 0, ',', '.') }}</div>
                    <div class="st-kpi-foot">Total setoran terverifikasi hari ini</div>
                    <span class="st-kpi-chip sky">Real-time</span>
                </div>
            </div>

            {{-- Filter --}}
            <div class="st-filter">
                <form method="GET" class="st-ff">
                    <div class="st-ff-fld">
                        <label class="st-flbl">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="st-finput">
                    </div>
                    @if(! $isSalesRole)
                    <div>
                        <label class="st-flbl">Sales</label>
                        <select name="sales_id" class="st-fsel">
                            <option value="">Semua Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
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
                    <a href="{{ route('minyak.setoran.index') }}" class="st-btn-r">
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
                                <th style="text-align:right;">Selisih</th>
                                <th style="text-align:center;">Bukti</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="st-tbl-body">
                            @forelse($setorans as $s)
                                @php
                                    $selisihIsZero = $s->selisih == 0;
                                    $selisihClass = $selisihIsZero ? 'st-money-match' : ($s->selisih > 0 ? 'st-money-surplus' : 'st-money-deficit');
                                @endphp
                                <tr>
                                    <td>
                                        <div class="st-date">{{ $s->tanggal->format('d M Y') }}</div>
                                        <div class="st-date-sub">{{ $s->tanggal->isoFormat('dddd') }}</div>
                                    </td>
                                    <td>
                                        <div class="st-sal">
                                            <div class="st-sal-av">{{ substr($s->sales->nama, 0, 1) }}</div>
                                            <div class="st-sal-name">{{ $s->sales->nama }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="st-money deposit">Rp {{ number_format($s->total_setor, 0, ',', '.') }}</div>
                                    </td>
                                    <td>
                                        <div class="st-money sale">Rp {{ number_format($s->total_penjualan, 0, ',', '.') }}</div>
                                    </td>
                                    <td>
                                        <div class="st-money {{ $selisihClass }}">
                                            {{ $selisihIsZero ? '—' : 'Rp ' . number_format(abs($s->selisih), 0, ',', '.') }}
                                            @if(!$selisihIsZero && $s->selisih < 0)
                                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-1px;margin-left:2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                            @elseif(!$selisihIsZero && $s->selisih > 0)
                                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-1px;margin-left:2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                            @endif
                                        </div>
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
                                        <div class="st-act-group">
                                            <a href="{{ route('minyak.setoran.show', $s) }}" class="st-act blue">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="st-empty">
                                            <div class="st-empty-ico">
                                                <svg width="32" height="32" fill="none" stroke="#d97706" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </div>
                                            <div class="st-empty-title">Belum Ada Data Setoran</div>
                                            <div class="st-empty-sub">Data setoran akan muncul setelah sales melakukan setoran harian</div>
                                            <a href="{{ route('minyak.setoran.create') }}" class="st-empty-btn">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                Buat Setoran Baru
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($setorans->hasPages())
                    <div style="padding:0.875rem 1.25rem; border-top:1px solid #fef9ee;">
                        {{ $setorans->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
