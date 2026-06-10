<x-app-layout>
    @push('styles')
    <style>
        .ps-page { max-width:56rem; margin:0 auto; padding:1.25rem 1rem 3rem; font-family:'Plus Jakarta Sans',system-ui,sans-serif; }
        .ps-back { display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#94a3b8; text-decoration:none; margin-bottom:1rem; transition:all .2s; }
        .ps-back:hover { color:#334155; }
        .ps-back:hover svg { transform:translateX(-3px); }
        .ps-back svg { transition:transform .2s; }

        /* Header */
        .ps-hdr { background:linear-gradient(135deg,#2563eb,#3b82f6); border-radius:16px; padding:1.25rem 1.5rem; margin-bottom:1.25rem; color:#fff; position:relative; overflow:hidden; }
        .ps-hdr::after { content:''; position:absolute; top:-30px; right:-30px; width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,.08); }
        .ps-hdr-tag { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; opacity:.7; }
        .ps-hdr-title { font-size:1.375rem; font-weight:800; margin-top:2px; }
        .ps-hdr-sub { font-size:12px; opacity:.85; margin-top:4px; }
        .ps-hdr-meta { display:flex; gap:8px; margin-top:10px; flex-wrap:wrap; }

        /* Badges */
        .ps-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; }
        .ps-badge::before { content:''; width:6px; height:6px; border-radius:50%; }
        .ps-badge.aktif { background:rgba(255,255,255,.2); color:#fff; }
        .ps-badge.aktif::before { background:#86efac; }
        .ps-badge.nonaktif { background:rgba(255,255,255,.15); color:rgba(255,255,255,.8); }
        .ps-badge.nonaktif::before { background:#fca5a5; }
        .ps-badge.blacklist { background:rgba(239,68,68,.25); color:#fecaca; }
        .ps-badge.blacklist::before { background:#f87171; }
        .ps-badge-tipe { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; background:rgba(255,255,255,.15); color:rgba(255,255,255,.9); }

        /* Cards */
        .ps-card { background:#fff; border:1px solid #f1f5f9; border-radius:14px; margin-bottom:12px; box-shadow:0 1px 2px rgba(0,0,0,.03); }
        .ps-card-hdr { padding:.75rem 1.125rem; display:flex; align-items:center; gap:10px; border-bottom:1px solid #f8fafc; }
        .ps-card-ico { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; }
        .ps-card-ico.blue { background:#eff6ff; color:#2563eb; }
        .ps-card-ico.green { background:#ecfdf5; color:#059669; }
        .ps-card-ico.amber { background:#fffbeb; color:#d97706; }
        .ps-card-ico.purple { background:#f5f3ff; color:#7c3aed; }
        .ps-card-ico.sky { background:#f0f9ff; color:#0284c7; }
        .ps-card-lbl { font-size:13px; font-weight:700; color:#0f172a; }
        .ps-card-count { font-size:11px; font-weight:600; color:#94a3b8; margin-left:auto; }
        .ps-card-body { padding:1rem 1.125rem; }

        /* Grid */
        .ps-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
        .ps-grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
        .ps-item-lbl { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; margin-bottom:3px; }
        .ps-item-val { font-size:13px; font-weight:600; color:#0f172a; word-break:break-word; }
        .ps-item-val.blue { color:#2563eb; }
        .ps-item-val.green { color:#059669; }
        .ps-item-val.red { color:#dc2626; }

        /* KPI Keuangan */
        .ps-kpi { background:#f8fafc; border:1px solid #f1f5f9; border-radius:12px; padding:.875rem 1rem; text-align:center; }
        .ps-kpi-lbl { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; margin-bottom:4px; }
        .ps-kpi-val { font-size:1.125rem; font-weight:800; letter-spacing:-.02em; }
        .ps-kpi-val.blue { color:#2563eb; }
        .ps-kpi-val.green { color:#059669; }
        .ps-kpi-val.red { color:#dc2626; }

        /* Tables */
        .ps-tbl { width:100%; border-collapse:collapse; font-size:12px; }
        .ps-tbl th { text-align:left; padding:8px 10px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; border-bottom:2px solid #f1f5f9; background:#fafbfd; }
        .ps-tbl td { padding:8px 10px; border-bottom:1px solid #f8fafc; color:#334155; }
        .ps-tbl tr:last-child td { border-bottom:none; }
        .ps-tbl tr:hover td { background:#f8fafc; }
        .ps-tbl .txt-right { text-align:right; }
        .ps-tbl .txt-center { text-align:center; }
        .ps-tbl .fw-600 { font-weight:600; }
        .ps-tbl .fw-700 { font-weight:700; }
        .ps-tbl .blue { color:#2563eb; }
        .ps-tbl .red { color:#dc2626; }
        .ps-tbl .green { color:#059669; }

        /* Status badges in tables */
        .ps-st { display:inline-flex; align-items:center; gap:4px; padding:2px 8px; border-radius:999px; font-size:10px; font-weight:700; }
        .ps-st::before { content:''; width:5px; height:5px; border-radius:50%; }
        .ps-st.verified { background:#d1fae5; color:#065f46; }
        .ps-st.verified::before { background:#059669; }
        .ps-st.pending { background:#fef3c7; color:#92400e; }
        .ps-st.pending::before { background:#d97706; }
        .ps-st.lunas { background:#d1fae5; color:#065f46; }
        .ps-st.lunas::before { background:#059669; }
        .ps-st.overdue { background:#fee2e2; color:#991b1b; }
        .ps-st.overdue::before { background:#dc2626; animation:ps-pulse 1.5s infinite; }
        .ps-st.belum { background:#fef3c7; color:#92400e; }
        .ps-st.belum::before { background:#d97706; }
        .ps-st.checkin { background:#dbeafe; color:#1e40af; }
        .ps-st.checkin::before { background:#3b82f6; }
        .ps-st.complete { background:#d1fae5; color:#065f46; }
        .ps-st.complete::before { background:#059669; }
        @keyframes ps-pulse { 0%,100% { opacity:1; } 50% { opacity:.4; } }

        /* Actions */
        .ps-actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:14px; }
        .ps-btn { display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none; border:none; cursor:pointer; transition:all .2s; font-family:inherit; }
        .ps-btn-outline { background:#fff; color:#64748b; border:1.5px solid #e2e8f0; }
        .ps-btn-outline:hover { background:#f8fafc; color:#334155; }
        .ps-btn-edit { background:#eff6ff; color:#2563eb; border:1.5px solid #bfdbfe; }
        .ps-btn-edit:hover { background:#dbeafe; }

        /* Empty state */
        .ps-empty { text-align:center; padding:2rem 1rem; color:#94a3b8; font-size:12px; }
        .ps-empty-ico { font-size:2rem; margin-bottom:8px; opacity:.5; }

        /* Map */
        .ps-map { width:100%; height:180px; border-radius:10px; border:1.5px solid #e2e8f0; overflow:hidden; }

        /* Tabs */
        .ps-tabs { display:flex; gap:4px; margin-bottom:12px; border-bottom:2px solid #f1f5f9; padding-bottom:0; }
        .ps-tab { padding:8px 16px; font-size:12px; font-weight:600; color:#94a3b8; cursor:pointer; border:none; background:none; border-bottom:2px solid transparent; margin-bottom:-2px; transition:all .2s; font-family:inherit; }
        .ps-tab:hover { color:#64748b; }
        .ps-tab.active { color:#2563eb; border-bottom-color:#2563eb; }
        .ps-tab-panel { display:none; }
        .ps-tab-panel.active { display:block; }

        @media(max-width:640px) { .ps-grid { grid-template-columns:1fr; } .ps-grid-3 { grid-template-columns:1fr; } }
    </style>
    @endpush

    <div class="ps-page">
        <a href="{{ route('mineral.pelanggan.index') }}" class="ps-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>

        {{-- Header --}}
        <div class="ps-hdr">
            <div class="ps-hdr-tag">Detail Pelanggan</div>
            <div class="ps-hdr-title">{{ $pelanggan->nama_toko }}</div>
            <div class="ps-hdr-sub">{{ $pelanggan->nama_pemilik }} — {{ $pelanggan->kode_pelanggan }}</div>
            <div class="ps-hdr-meta">
                <span class="ps-badge {{ $pelanggan->status }}">{{ ucfirst($pelanggan->status) }}</span>
                @if($pelanggan->tipe)
                    <span class="ps-badge-tipe">{{ ucfirst($pelanggan->tipe) }}</span>
                @endif
            </div>
        </div>

        {{-- Info Pelanggan --}}
        <div class="ps-card">
            <div class="ps-card-hdr">
                <div class="ps-card-ico blue">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="ps-card-lbl">Informasi Pelanggan</div>
            </div>
            <div class="ps-card-body">
                <div class="ps-grid">
                    <div>
                        <div class="ps-item-lbl">Nama Toko</div>
                        <div class="ps-item-val">{{ $pelanggan->nama_toko }}</div>
                    </div>
                    <div>
                        <div class="ps-item-lbl">Pemilik</div>
                        <div class="ps-item-val">{{ $pelanggan->nama_pemilik }}</div>
                    </div>
                    <div>
                        <div class="ps-item-lbl">No. HP</div>
                        <div class="ps-item-val">{{ $pelanggan->no_hp ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="ps-item-lbl">Email</div>
                        <div class="ps-item-val">{{ $pelanggan->email ?: '-' }}</div>
                    </div>
                    @php
                        $addrParts = array_filter([$pelanggan->alamat, $pelanggan->kecamatan, $pelanggan->kota]);
                        $fullAddr = !empty($addrParts) ? implode(', ', $addrParts) : null;
                        $hasGps = !empty($pelanggan->latitude) && !empty($pelanggan->longitude);
                    @endphp
                    <div style="grid-column:1/-1;">
                        <div class="ps-item-lbl">
                            Alamat
                            @if($fullAddr && $hasGps)
                                <span style="font-size:9px; font-weight:600; background:#dbeafe; color:#1e40af; padding:1px 6px; border-radius:4px; margin-left:4px; letter-spacing:0;">GPS</span>
                            @endif
                        </div>
                        @if($fullAddr)
                            <div class="ps-item-val">{{ $fullAddr }}</div>
                        @else
                            <div style="font-size:12px; color:#94a3b8; font-style:italic;">Belum ada alamat — akan terisi otomatis saat kunjungan pertama</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Keuangan --}}
        <div class="ps-card">
            <div class="ps-card-hdr">
                <div class="ps-card-ico green">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="ps-card-lbl">Keuangan</div>
            </div>
            <div class="ps-card-body">
                <div class="ps-grid-3">
                    <div class="ps-kpi">
                        <div class="ps-kpi-lbl">Limit Hutang</div>
                        <div class="ps-kpi-val blue">Rp {{ number_format((float)$pelanggan->limit_hutang, 0, ',', '.') }}</div>
                    </div>
                    <div class="ps-kpi">
                        <div class="ps-kpi-lbl">Total Hutang</div>
                        @php $hutangColor = (float)$pelanggan->total_hutang > 0 ? 'red' : 'green'; @endphp
                        <div class="ps-kpi-val {{ $hutangColor }}">Rp {{ number_format((float)$pelanggan->total_hutang, 0, ',', '.') }}</div>
                    </div>
                    <div class="ps-kpi">
                        <div class="ps-kpi-lbl">Sisa Limit</div>
                        @php $sisaLimit = max(0, (float)$pelanggan->limit_hutang - (float)$pelanggan->total_hutang); @endphp
                        <div class="ps-kpi-val green">Rp {{ number_format($sisaLimit, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Foto Toko --}}
        @if($pelanggan->foto_toko)
        <div class="ps-card">
            <div class="ps-card-hdr">
                <div class="ps-card-ico" style="background:#fce7f3; color:#db2777;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="ps-card-lbl">Foto Toko</div>
            </div>
            <div class="ps-card-body" style="text-align:center;">
                <a href="{{ asset('storage/' . $pelanggan->foto_toko) }}" target="_blank">
                    <img src="{{ asset('storage/' . $pelanggan->foto_toko) }}" alt="Foto Toko {{ $pelanggan->nama_toko }}" style="max-width:100%; max-height:400px; border-radius:14px; border:2px solid #e2e8f0; box-shadow:0 4px 16px rgba(0,0,0,0.08); cursor:zoom-in;">
                </a>
                <p style="font-size:0.6875rem; color:#94a3b8; margin-top:0.5rem;">Klik gambar untuk melihat ukuran penuh</p>
            </div>
        </div>
        @endif

        {{-- Lokasi --}}
        @if($pelanggan->latitude && $pelanggan->longitude)
        <div class="ps-card">
            <div class="ps-card-hdr">
                <div class="ps-card-ico purple">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="ps-card-lbl">Lokasi</div>
            </div>
            <div class="ps-card-body">
                <div class="ps-grid" style="margin-bottom:10px;">
                    <div>
                        <div class="ps-item-lbl">Latitude</div>
                        <div class="ps-item-val">{{ $pelanggan->latitude }}</div>
                    </div>
                    <div>
                        <div class="ps-item-lbl">Longitude</div>
                        <div class="ps-item-val">{{ $pelanggan->longitude }}</div>
                    </div>
                </div>
                <iframe class="ps-map" src="https://www.google.com/maps?q={{ $pelanggan->latitude }},{{ $pelanggan->longitude }}&z=15&output=embed" loading="lazy" allowfullscreen></iframe>
            </div>
        </div>
        @endif

        {{-- Tabbed History --}}
        @php
            $hasKunjungan = $pelanggan->kunjungans->isNotEmpty();
            $hasHutang = $pelanggan->hutangs->isNotEmpty();
        @endphp

        <div class="ps-card">
            <div class="ps-tabs">
                <button class="ps-tab active" onclick="psSwitchTab('penjualan')">Penjualan ({{ $pelanggan->penjualans->count() }})</button>
                @if($hasKunjungan)
                    <button class="ps-tab" onclick="psSwitchTab('kunjungan')">Kunjungan ({{ $pelanggan->kunjungans->count() }})</button>
                @endif
                @if($hasHutang)
                    <button class="ps-tab" onclick="psSwitchTab('hutang')">Hutang ({{ $pelanggan->hutangs->count() }})</button>
                @endif
            </div>

            {{-- Tab: Penjualan --}}
            <div id="ps-panel-penjualan" class="ps-tab-panel active">
                @if($pelanggan->penjualans->isEmpty())
                    <div class="ps-empty">
                        <div class="ps-empty-ico">🛒</div>
                        Belum ada riwayat penjualan.
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table class="ps-tbl">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>No. Faktur</th>
                                    <th>Produk</th>
                                    <th class="txt-right">Total</th>
                                    <th class="txt-center">Bayar</th>
                                    <th class="txt-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pelanggan->penjualans as $pj)
                                <tr>
                                    <td>{{ $pj->tanggal_jual->format('d M Y') }}</td>
                                    <td class="fw-600">{{ $pj->no_faktur }}</td>
                                    <td>{{ $pj->produk->nama ?? '-' }}</td>
                                    <td class="txt-right fw-700">Rp {{ number_format((float)$pj->total, 0, ',', '.') }}</td>
                                    <td class="txt-center" style="text-transform:capitalize;">{{ $pj->tipe_bayar }}</td>
                                    <td class="txt-center">
                                        @php $stClass = $pj->status === 'terverifikasi' ? 'verified' : 'pending'; @endphp
                                        <span class="ps-st {{ $stClass }}">{{ ucfirst($pj->status) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Tab: Kunjungan --}}
            @if($hasKunjungan)
            <div id="ps-panel-kunjungan" class="ps-tab-panel">
                <div style="overflow-x:auto;">
                    <table class="ps-tbl">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Sales</th>
                                <th class="txt-center">Check In</th>
                                <th class="txt-center">Check Out</th>
                                <th class="txt-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelanggan->kunjungans as $knj)
                            <tr>
                                <td>{{ $knj->waktu_checkin->format('d M Y') }}</td>
                                <td class="fw-600">{{ $knj->sales->nama ?? '-' }}</td>
                                <td class="txt-center">{{ $knj->waktu_checkin->format('H:i') }}</td>
                                <td class="txt-center">{{ $knj->waktu_checkout ? $knj->waktu_checkout->format('H:i') : '-' }}</td>
                                <td class="txt-center">
                                    @php $knjSt = $knj->waktu_checkout ? 'complete' : 'checkin'; @endphp
                                    <span class="ps-st {{ $knjSt }}">{{ $knj->waktu_checkout ? 'Selesai' : 'Check In' }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Tab: Hutang --}}
            @if($hasHutang)
            <div id="ps-panel-hutang" class="ps-tab-panel">
                <div style="overflow-x:auto;">
                    <table class="ps-tbl">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jatuh Tempo</th>
                                <th class="txt-right">Total</th>
                                <th class="txt-right">Sisa</th>
                                <th class="txt-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelanggan->hutangs as $ht)
                            <tr>
                                <td>{{ $ht->created_at->format('d M Y') }}</td>
                                <td>{{ $ht->jatuh_tempo ? $ht->jatuh_tempo->format('d M Y') : '-' }}</td>
                                <td class="txt-right fw-700">Rp {{ number_format((float)$ht->total_hutang, 0, ',', '.') }}</td>
                                @php $sisaColor = (float)$ht->sisa > 0 ? 'red' : 'green'; @endphp
                                <td class="txt-right fw-700 {{ $sisaColor }}">Rp {{ number_format((float)$ht->sisa, 0, ',', '.') }}</td>
                                <td class="txt-center">
                                    @php
                                        if ($ht->status === 'lunas') { $htSt = 'lunas'; $htLbl = 'Lunas'; }
                                        elseif ($ht->status === 'overdue') { $htSt = 'overdue'; $htLbl = 'Overdue'; }
                                        else { $htSt = 'belum'; $htLbl = 'Belum Lunas'; }
                                    @endphp
                                    <span class="ps-st {{ $htSt }}">{{ $htLbl }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="ps-actions">
            <a href="{{ route('mineral.pelanggan.index') }}" class="ps-btn ps-btn-outline">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            @php
                $userRole = auth()->user()->role ?? '';
                $isNotSales = !$userRole || !str_starts_with(strtolower($userRole), 'sales_');
            @endphp
            @if($isNotSales)
            <a href="{{ route('mineral.pelanggan.edit', $pelanggan) }}" class="ps-btn ps-btn-edit">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Pelanggan
            </a>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function psSwitchTab(name) {
            document.querySelectorAll('.ps-tab').forEach(function(t) { t.classList.remove('active'); });
            document.querySelectorAll('.ps-tab-panel').forEach(function(p) { p.classList.remove('active'); });
            event.target.classList.add('active');
            document.getElementById('ps-panel-' + name).classList.add('active');
        }
    </script>
    @endpush
</x-app-layout>
