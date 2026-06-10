<x-app-layout>
    <x-slot name="header">Detail Sales Mineral</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap');

        .mns-page { max-width:72rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* ── HEADER ── */
        .mns-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:2rem; }
        .mns-hdr-l { display:flex; align-items:center; gap:1rem; }
        .mns-back { display:flex; align-items:center; gap:6px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
        .mns-back:hover { color:#2563eb; }
        .mns-avatar {
            width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center;
            font-size:1.375rem; font-weight:800; color:#fff; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            box-shadow:0 8px 24px rgba(37,99,235,0.3);
        }
        .mns-hdr-info { display:flex; flex-direction:column; gap:2px; }
        .mns-name { font-size:1.375rem; font-weight:800; color:#0f172a; letter-spacing:-0.02em; line-height:1.2; }
        .mns-meta { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .mns-code { font-family:'JetBrains Mono',monospace; font-size:0.75rem; font-weight:600; color:#2563eb; background:#eff6ff; padding:2px 8px; border-radius:6px; border:1px solid #bfdbfe; }
        .mns-badge { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; padding:3px 10px; border-radius:8px; }
        .mns-badge-aktif { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .mns-badge-nonaktif { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
        .mns-edit-btn {
            display:inline-flex; align-items:center; gap:8px; padding:0.625rem 1.25rem; border-radius:12px;
            font-size:0.8125rem; font-weight:700; text-decoration:none; transition:all 0.25s;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 4px 14px rgba(37,99,235,0.3); border:none; cursor:pointer;
        }
        .mns-edit-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(37,99,235,0.45); }

        /* ── CARDS ── */
        .mns-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
        .mns-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
        .mns-card-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
        .mns-card-ico { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .mns-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .mns-card-body { padding:1.25rem 1.375rem; }

        .mns-card.blue .mns-card-hdr { background:linear-gradient(135deg,#eff6ff,#f0f7ff); }
        .mns-card.blue .mns-card-ico { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
        .mns-card.green .mns-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
        .mns-card.green .mns-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .mns-card.purple .mns-card-hdr { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .mns-card.purple .mns-card-ico { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }

        /* ── FIELD ROWS ── */
        .mns-fields { display:flex; flex-direction:column; gap:1rem; }
        .mns-field { display:flex; align-items:flex-start; gap:0.75rem; }
        .mns-field-ico { width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:#f8fafc; border:1px solid #f1f5f9; }
        .mns-field-ico svg { width:16px; height:16px; color:#64748b; }
        .mns-field-info { display:flex; flex-direction:column; gap:1px; min-width:0; }
        .mns-field-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
        .mns-field-val { font-size:0.875rem; font-weight:600; color:#0f172a; word-break:break-word; }
        .mns-field-val.mono { font-family:'JetBrains Mono',monospace; font-size:0.8125rem; }
        .mns-field-val.empty { color:#cbd5e1; font-weight:500; font-style:italic; }

        /* ── STAT ITEMS ── */
        .mns-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; }
        .mns-stat { padding:1rem 1.125rem; border-radius:14px; display:flex; align-items:center; gap:0.875rem; border:1px solid transparent; }
        .mns-stat.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); border-color:#bfdbfe; }
        .mns-stat.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); border-color:#a7f3d0; }
        .mns-stat.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); border-color:#c4b5fd; }
        .mns-stat-ico { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .mns-stat.blue .mns-stat-ico { background:#3b82f6; color:#fff; }
        .mns-stat.green .mns-stat-ico { background:#10b981; color:#fff; }
        .mns-stat.purple .mns-stat-ico { background:#8b5cf6; color:#fff; }
        .mns-stat-info { display:flex; flex-direction:column; gap:2px; }
        .mns-stat-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; }
        .mns-stat-val { font-size:1.25rem; font-weight:800; letter-spacing:-0.02em; font-family:'JetBrains Mono',monospace; }
        .mns-stat.blue .mns-stat-val { color:#2563eb; }
        .mns-stat.green .mns-stat-val { color:#059669; }
        .mns-stat.purple .mns-stat-val { color:#7c3aed; }

        /* ── TABLE SECTION ── */
        .mns-tbl-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
        .mns-tbl-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; border-bottom:1px solid #f1f5f9; }
        .mns-tbl-title { font-size:0.875rem; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:8px; }
        .mns-tbl-count { font-size:0.6875rem; font-weight:700; color:#2563eb; background:#eff6ff; padding:2px 8px; border-radius:6px; border:1px solid #bfdbfe; }
        .mns-tbl { width:100%; border-collapse:collapse; }
        .mns-tbl thead th { background:#f8fafc; padding:0.875rem 1.375rem; text-align:left; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; border-bottom:1px solid #e2e8f0; }
        .mns-tbl thead th.r { text-align:right; }
        .mns-tbl thead th.c { text-align:center; }
        .mns-tbl tbody td { padding:1rem 1.375rem; border-bottom:1px solid #f1f5f9; font-size:0.875rem; vertical-align:middle; }
        .mns-tbl tbody tr:last-child td { border-bottom:none; }
        .mns-tbl tbody tr:hover { background:#f8faff; }
        .mns-tbl .r { text-align:right; }
        .mns-tbl .c { text-align:center; }
        .mns-tbl .mono { font-family:'JetBrains Mono',monospace; font-size:0.8125rem; }
        .mns-pill { display:inline-flex; padding:3px 10px; border-radius:8px; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.04em; }
        .mns-pill-ok { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .mns-pill-wait { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
        .mns-plate { display:inline-block; padding:3px 10px; background:#1e293b; color:#fff; border-radius:6px; font-family:'JetBrains Mono',monospace; font-weight:700; font-size:0.8125rem; letter-spacing:0.05em; }
        .mns-empty { padding:3rem 2rem; text-align:center; }
        .mns-empty-ico { width:56px; height:56px; border-radius:50%; background:#f8fafc; display:inline-flex; align-items:center; justify-content:center; margin-bottom:0.75rem; }
        .mns-empty-txt { font-size:0.8125rem; color:#94a3b8; font-weight:500; }
        .mns-target { display:inline-flex; align-items:center; gap:6px; background:linear-gradient(135deg,#eff6ff,#dbeafe); border:1px solid #bfdbfe; border-radius:10px; padding:0.625rem 1rem; margin-top:0.5rem; }
        .mns-target-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#1e40af; }
        .mns-target-val { font-family:'JetBrains Mono',monospace; font-size:0.875rem; font-weight:700; color:#2563eb; }

        @media(max-width:768px) {
            .mns-grid { grid-template-columns:1fr; }
            .mns-stats { grid-template-columns:1fr; }
            .mns-hdr { flex-direction:column; align-items:flex-start; }
        }
    </style>
    @endpush

    <div class="mns-page">

        {{-- ─── HEADER ─── --}}
        <div class="mns-hdr">
            <div class="mns-hdr-l">
                <a href="{{ route('mineral.sales.index') }}" class="mns-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Kembali
                </a>
                <div class="mns-avatar">{{ strtoupper(substr($sales->nama, 0, 1)) }}</div>
                <div class="mns-hdr-info">
                    <div class="mns-name">{{ $sales->nama }}</div>
                    <div class="mns-meta">
                        <span class="mns-code">{{ $sales->kode_sales }}</span>
                        <span class="mns-badge {{ $sales->status === 'aktif' ? 'mns-badge-aktif' : 'mns-badge-nonaktif' }}">
                            {{ ucfirst($sales->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('mineral.sales.edit', $sales->id) }}" class="mns-edit-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Data
            </a>
        </div>

        {{-- ─── CARDS GRID ─── --}}
        <div class="mns-grid">

            {{-- Card: Informasi Kontak (Blue) --}}
            <div class="mns-card blue">
                <div class="mns-card-hdr">
                    <div class="mns-card-ico">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="mns-card-title">Informasi Kontak</div>
                </div>
                <div class="mns-card-body">
                    <div class="mns-fields">
                        <div class="mns-field">
                            <div class="mns-field-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div class="mns-field-info">
                                <span class="mns-field-lbl">No HP</span>
                                <span class="mns-field-val mono">{{ $sales->no_hp ?: '—' }}</span>
                            </div>
                        </div>
                        <div class="mns-field">
                            <div class="mns-field-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div class="mns-field-info">
                                <span class="mns-field-lbl">Email</span>
                                <span class="mns-field-val">{{ $sales->email ?: '—' }}</span>
                            </div>
                        </div>
                        <div class="mns-field">
                            <div class="mns-field-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div class="mns-field-info">
                                <span class="mns-field-lbl">Alamat</span>
                                <span class="mns-field-val">{{ $sales->alamat ?: '—' }}</span>
                            </div>
                        </div>
                        @if($sales->keterangan)
                        <div class="mns-field">
                            <div class="mns-field-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                            </div>
                            <div class="mns-field-info">
                                <span class="mns-field-lbl">Keterangan</span>
                                <span class="mns-field-val">{{ $sales->keterangan }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card: Informasi Kendaraan (Green) --}}
            <div class="mns-card green">
                <div class="mns-card-hdr">
                    <div class="mns-card-ico">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div class="mns-card-title">Informasi Kendaraan</div>
                </div>
                <div class="mns-card-body">
                    <div class="mns-fields">
                        <div class="mns-field">
                            <div class="mns-field-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            </div>
                            <div class="mns-field-info">
                                <span class="mns-field-lbl">Plat Nomor</span>
                                @if($sales->no_kendaraan)
                                    <span class="mns-plate">{{ strtoupper($sales->no_kendaraan) }}</span>
                                @else
                                    <span class="mns-field-val empty">Belum terdaftar</span>
                                @endif
                            </div>
                        </div>
                        <div class="mns-field">
                            <div class="mns-field-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 12l-4-4-4 4"/><path d="M12 16V8"/></svg>
                            </div>
                            <div class="mns-field-info">
                                <span class="mns-field-lbl">Jenis Kendaraan</span>
                                <span class="mns-field-val">{{ $sales->jenis_kendaraan ?: '—' }}</span>
                            </div>
                        </div>
                    </div>
                    @if($sales->target_harian)
                    <div class="mns-target">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                        <span class="mns-target-lbl">Target Harian</span>
                        <span class="mns-target-val">Rp {{ number_format($sales->target_harian, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ─── STATISTIK BULAN INI ─── --}}
        <div class="mns-card purple" style="margin-bottom:1.25rem;">
            <div class="mns-card-hdr">
                <div class="mns-card-ico">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
                <div class="mns-card-title">Statistik Bulan Ini</div>
            </div>
            <div class="mns-card-body">
                <div class="mns-stats">
                    <div class="mns-stat blue">
                        <div class="mns-stat-ico">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        </div>
                        <div class="mns-stat-info">
                            <span class="mns-stat-lbl">Total Loading</span>
                            <span class="mns-stat-val">{{ number_format($sales->loadings->sum('jumlah_loading'), 0, ',', '.') }} L</span>
                        </div>
                    </div>
                    <div class="mns-stat green">
                        <div class="mns-stat-ico">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div class="mns-stat-info">
                            <span class="mns-stat-lbl">Total Penjualan</span>
                            <span class="mns-stat-val">Rp {{ number_format($sales->penjualans->sum('total'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="mns-stat purple">
                        <div class="mns-stat-ico">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
                        </div>
                        <div class="mns-stat-info">
                            <span class="mns-stat-lbl">Jumlah Transaksi</span>
                            <span class="mns-stat-val">{{ number_format($sales->penjualans->count()) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── RIWAYAT SETORAN ─── --}}
        <div class="mns-tbl-card">
            <div class="mns-tbl-hdr">
                <div class="mns-tbl-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    Riwayat Setoran Terakhir
                    @if($sales->setorans->count() > 0)
                        <span class="mns-tbl-count">{{ $sales->setorans->count() }} data</span>
                    @endif
                </div>
            </div>
            @if($sales->setorans->count() > 0)
                <div style="overflow-x:auto;">
                    <table class="mns-tbl">
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
                                        <span class="mns-pill {{ $setoran->status === 'terverifikasi' ? 'mns-pill-ok' : 'mns-pill-wait' }}">
                                            {{ ucfirst($setoran->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mns-empty">
                    <div class="mns-empty-ico">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    </div>
                    <div class="mns-empty-txt">Belum ada riwayat setoran</div>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
