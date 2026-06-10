<x-app-layout>
    <x-slot name="header">Detail Sales Gula</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap');

        .gss-page { max-width:72rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* ── HEADER ── */
        .gss-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:2rem; }
        .gss-hdr-l { display:flex; align-items:center; gap:1rem; }
        .gss-back { display:flex; align-items:center; gap:6px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
        .gss-back:hover { color:#d97706; }
        .gss-avatar {
            width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center;
            font-size:1.375rem; font-weight:800; color:#fff; flex-shrink:0;
            background:linear-gradient(135deg,#f59e0b,#d97706);
            box-shadow:0 8px 24px rgba(245,158,11,0.3);
        }
        .gss-hdr-info { display:flex; flex-direction:column; gap:2px; }
        .gss-name { font-size:1.375rem; font-weight:800; color:#0f172a; letter-spacing:-0.02em; line-height:1.2; }
        .gss-meta { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .gss-code { font-family:'JetBrains Mono',monospace; font-size:0.75rem; font-weight:600; color:#b45309; background:#fffbeb; padding:2px 8px; border-radius:6px; border:1px solid #fde68a; }
        .gss-badge { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; padding:3px 10px; border-radius:8px; }
        .gss-badge-aktif { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .gss-badge-nonaktif { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
        .gss-edit-btn {
            display:inline-flex; align-items:center; gap:8px; padding:0.625rem 1.25rem; border-radius:12px;
            font-size:0.8125rem; font-weight:700; text-decoration:none; transition:all 0.25s;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
            box-shadow:0 4px 14px rgba(245,158,11,0.3); border:none; cursor:pointer;
        }
        .gss-edit-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(245,158,11,0.45); }

        /* ── CARDS ── */
        .gss-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
        .gss-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
        .gss-card-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
        .gss-card-ico { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .gss-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .gss-card-body { padding:1.25rem 1.375rem; }

        .gss-card.amber .gss-card-hdr { background:linear-gradient(135deg,#fffbeb,#fef9ee); }
        .gss-card.amber .gss-card-ico { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
        .gss-card.green .gss-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
        .gss-card.green .gss-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .gss-card.purple .gss-card-hdr { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .gss-card.purple .gss-card-ico { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }

        /* ── FIELD ROWS ── */
        .gss-fields { display:flex; flex-direction:column; gap:1rem; }
        .gss-field { display:flex; align-items:flex-start; gap:0.75rem; }
        .gss-field-ico { width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:#f8fafc; border:1px solid #f1f5f9; }
        .gss-field-ico svg { width:16px; height:16px; color:#64748b; }
        .gss-field-info { display:flex; flex-direction:column; gap:1px; min-width:0; }
        .gss-field-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
        .gss-field-val { font-size:0.875rem; font-weight:600; color:#0f172a; word-break:break-word; }
        .gss-field-val.mono { font-family:'JetBrains Mono',monospace; font-size:0.8125rem; }
        .gss-field-val.empty { color:#cbd5e1; font-weight:500; font-style:italic; }

        /* ── STAT ITEMS ── */
        .gss-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; }
        .gss-stat { padding:1rem 1.125rem; border-radius:14px; display:flex; align-items:center; gap:0.875rem; border:1px solid transparent; }
        .gss-stat.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); border-color:#fde68a; }
        .gss-stat.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); border-color:#a7f3d0; }
        .gss-stat.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); border-color:#c4b5fd; }
        .gss-stat-ico { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .gss-stat.amber .gss-stat-ico { background:#f59e0b; color:#fff; }
        .gss-stat.green .gss-stat-ico { background:#10b981; color:#fff; }
        .gss-stat.purple .gss-stat-ico { background:#8b5cf6; color:#fff; }
        .gss-stat-info { display:flex; flex-direction:column; gap:2px; }
        .gss-stat-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; }
        .gss-stat-val { font-size:1.25rem; font-weight:800; letter-spacing:-0.02em; font-family:'JetBrains Mono',monospace; }
        .gss-stat.amber .gss-stat-val { color:#b45309; }
        .gss-stat.green .gss-stat-val { color:#059669; }
        .gss-stat.purple .gss-stat-val { color:#7c3aed; }

        /* ── TABLE SECTION ── */
        .gss-tbl-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
        .gss-tbl-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; border-bottom:1px solid #f1f5f9; }
        .gss-tbl-title { font-size:0.875rem; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:8px; }
        .gss-tbl-count { font-size:0.6875rem; font-weight:700; color:#b45309; background:#fffbeb; padding:2px 8px; border-radius:6px; border:1px solid #fde68a; }
        .gss-tbl { width:100%; border-collapse:collapse; }
        .gss-tbl thead th { background:#f8fafc; padding:0.875rem 1.375rem; text-align:left; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; border-bottom:1px solid #e2e8f0; }
        .gss-tbl thead th.r { text-align:right; }
        .gss-tbl thead th.c { text-align:center; }
        .gss-tbl tbody td { padding:1rem 1.375rem; border-bottom:1px solid #f1f5f9; font-size:0.875rem; vertical-align:middle; }
        .gss-tbl tbody tr:last-child td { border-bottom:none; }
        .gss-tbl tbody tr:hover { background:#fffaf5; }
        .gss-tbl .r { text-align:right; }
        .gss-tbl .c { text-align:center; }
        .gss-tbl .mono { font-family:'JetBrains Mono',monospace; font-size:0.8125rem; }
        .gss-pill { display:inline-flex; padding:3px 10px; border-radius:8px; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.04em; }
        .gss-pill-ok { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .gss-pill-wait { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
        .gss-plate { display:inline-block; padding:3px 10px; background:#1e293b; color:#fff; border-radius:6px; font-family:'JetBrains Mono',monospace; font-weight:700; font-size:0.8125rem; letter-spacing:0.05em; }
        .gss-empty { padding:3rem 2rem; text-align:center; }
        .gss-empty-ico { width:56px; height:56px; border-radius:50%; background:#f8fafc; display:inline-flex; align-items:center; justify-content:center; margin-bottom:0.75rem; }
        .gss-empty-txt { font-size:0.8125rem; color:#94a3b8; font-weight:500; }
        .gss-target { display:inline-flex; align-items:center; gap:6px; background:linear-gradient(135deg,#fffbeb,#fef3c7); border:1px solid #fde68a; border-radius:10px; padding:0.625rem 1rem; margin-top:0.5rem; }
        .gss-target-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#92400e; }
        .gss-target-val { font-family:'JetBrains Mono',monospace; font-size:0.875rem; font-weight:700; color:#b45309; }

        @media(max-width:768px) {
            .gss-grid { grid-template-columns:1fr; }
            .gss-stats { grid-template-columns:1fr; }
            .gss-hdr { flex-direction:column; align-items:flex-start; }
        }
    </style>
    @endpush

    <div class="gss-page">

        {{-- ─── HEADER ─── --}}
        <div class="gss-hdr">
            <div class="gss-hdr-l">
                <a href="{{ route('gula.sales.index') }}" class="gss-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Kembali
                </a>
                <div class="gss-avatar">{{ strtoupper(substr($sales->nama, 0, 1)) }}</div>
                <div class="gss-hdr-info">
                    <div class="gss-name">{{ $sales->nama }}</div>
                    <div class="gss-meta">
                        <span class="gss-code">{{ $sales->kode_sales }}</span>
                        <span class="gss-badge {{ $sales->status === 'aktif' ? 'gss-badge-aktif' : 'gss-badge-nonaktif' }}">
                            {{ ucfirst($sales->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('gula.sales.edit', $sales->id) }}" class="gss-edit-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Data
            </a>
        </div>

        {{-- ─── CARDS GRID ─── --}}
        <div class="gss-grid">

            {{-- Card: Informasi Kontak (Amber) --}}
            <div class="gss-card amber">
                <div class="gss-card-hdr">
                    <div class="gss-card-ico">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="gss-card-title">Informasi Kontak</div>
                </div>
                <div class="gss-card-body">
                    <div class="gss-fields">
                        <div class="gss-field">
                            <div class="gss-field-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div class="gss-field-info">
                                <span class="gss-field-lbl">No HP</span>
                                <span class="gss-field-val mono">{{ $sales->no_hp ?: '—' }}</span>
                            </div>
                        </div>
                        <div class="gss-field">
                            <div class="gss-field-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div class="gss-field-info">
                                <span class="gss-field-lbl">Email</span>
                                <span class="gss-field-val">{{ $sales->email ?: '—' }}</span>
                            </div>
                        </div>
                        <div class="gss-field">
                            <div class="gss-field-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div class="gss-field-info">
                                <span class="gss-field-lbl">Alamat</span>
                                <span class="gss-field-val">{{ $sales->alamat ?: '—' }}</span>
                            </div>
                        </div>
                        @if($sales->keterangan)
                        <div class="gss-field">
                            <div class="gss-field-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                            </div>
                            <div class="gss-field-info">
                                <span class="gss-field-lbl">Keterangan</span>
                                <span class="gss-field-val">{{ $sales->keterangan }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card: Informasi Kendaraan (Green) --}}
            <div class="gss-card green">
                <div class="gss-card-hdr">
                    <div class="gss-card-ico">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div class="gss-card-title">Informasi Kendaraan</div>
                </div>
                <div class="gss-card-body">
                    <div class="gss-fields">
                        <div class="gss-field">
                            <div class="gss-field-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            </div>
                            <div class="gss-field-info">
                                <span class="gss-field-lbl">Plat Nomor</span>
                                @if($sales->vehicle)
                                    <span class="gss-plate">{{ strtoupper($sales->vehicle->license_plate) }}</span>
                                @else
                                    <span class="gss-field-val empty">Belum ditugaskan</span>
                                @endif
                            </div>
                        </div>
                        <div class="gss-field">
                            <div class="gss-field-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 12l-4-4-4 4"/><path d="M12 16V8"/></svg>
                            </div>
                            <div class="gss-field-info">
                                <span class="gss-field-lbl">Jenis Kendaraan</span>
                                <span class="gss-field-val">{{ $sales->vehicle?->type ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                    @if($sales->target_harian)
                    <div class="gss-target">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                        <span class="gss-target-lbl">Target Harian</span>
                        <span class="gss-target-val">Rp {{ number_format($sales->target_harian, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ─── STATISTIK BULAN INI ─── --}}
        <div class="gss-card purple" style="margin-bottom:1.25rem;">
            <div class="gss-card-hdr">
                <div class="gss-card-ico">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
                <div class="gss-card-title">Statistik Bulan Ini</div>
            </div>
            <div class="gss-card-body">
                <div class="gss-stats">
                    <div class="gss-stat amber">
                        <div class="gss-stat-ico">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        </div>
                        <div class="gss-stat-info">
                            <span class="gss-stat-lbl">Total Loading</span>
                            <span class="gss-stat-val">{{ number_format($sales->loadings->sum('jumlah_loading'), 0, ',', '.') }} L</span>
                        </div>
                    </div>
                    <div class="gss-stat green">
                        <div class="gss-stat-ico">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div class="gss-stat-info">
                            <span class="gss-stat-lbl">Total Penjualan</span>
                            <span class="gss-stat-val">Rp {{ number_format($sales->penjualans->sum('total'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="gss-stat purple">
                        <div class="gss-stat-ico">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
                        </div>
                        <div class="gss-stat-info">
                            <span class="gss-stat-lbl">Jumlah Transaksi</span>
                            <span class="gss-stat-val">{{ number_format($sales->penjualans->count()) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── RIWAYAT SETORAN ─── --}}
        <div class="gss-tbl-card">
            <div class="gss-tbl-hdr">
                <div class="gss-tbl-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    Riwayat Setoran Terakhir
                    @if($sales->setorans->count() > 0)
                        <span class="gss-tbl-count">{{ $sales->setorans->count() }} data</span>
                    @endif
                </div>
            </div>
            @if($sales->setorans->count() > 0)
                <div style="overflow-x:auto;">
                    <table class="gss-tbl">
                        <thead>
                            <tr>
                                <th style="width:60px;" class="c">No</th>
                                <th>Tanggal</th>
                                <th class="r">Total Setor</th>
                                <th class="r">Total Penjualan</th>
                                <th class="c">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales->setorans->take(10) as $i => $setoran)
                                <tr>
                                    <td class="c" style="color:#94a3b8;font-family:'JetBrains Mono',monospace;font-size:0.75rem;">{{ $i + 1 }}</td>
                                    <td>
                                        <span style="font-weight:600;">{{ \Carbon\Carbon::parse($setoran->tanggal)->format('d M Y') }}</span>
                                    </td>
                                    <td class="r">
                                        <span class="mono" style="font-weight:700;color:#059669;">Rp {{ number_format($setoran->total_setor, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="r">
                                        <span class="mono" style="color:#0f172a;">Rp {{ number_format($setoran->total_penjualan ?? 0, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="c">
                                        <span class="gss-pill {{ $setoran->status === 'terverifikasi' ? 'gss-pill-ok' : 'gss-pill-wait' }}">
                                            {{ ucfirst($setoran->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="gss-empty">
                    <div class="gss-empty-ico">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    </div>
                    <div class="gss-empty-txt">Belum ada riwayat setoran</div>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
