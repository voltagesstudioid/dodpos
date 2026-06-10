<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .rk-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .rk-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .rk-hdr-l { display:flex; align-items:center; gap:1rem; }
        .rk-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            box-shadow:0 8px 24px rgba(37,99,235,0.3);
        }
        .rk-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .rk-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        /* Filter */
        .rk-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .rk-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .rk-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .rk-finput {
            width:100%; padding:0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .rk-finput:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .rk-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .rk-fsel:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .rk-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 4px 12px rgba(37,99,235,0.25);
        }
        .rk-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(37,99,235,0.35); }

        /* KPI Row */
        .rk-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
        .rk-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem 1.5rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .rk-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .rk-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .rk-kpi.blue::before   { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .rk-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .rk-kpi.purple::before { background:linear-gradient(180deg,#8b5cf6,#7c3aed); }
        .rk-kpi-top { display:flex; align-items:center; justify-content:space-between; }
        .rk-kpi-left { display:flex; flex-direction:column; gap:0.25rem; }
        .rk-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .rk-kpi-val { font-size:2.5rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
        .rk-kpi-val.blue   { color:#2563eb; }
        .rk-kpi-val.green  { color:#059669; }
        .rk-kpi-val.purple { color:#7c3aed; }
        .rk-kpi-foot { font-size:0.72rem; color:#94a3b8; margin-top:0.375rem; }
        .rk-kpi-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
        .rk-kpi-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .rk-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .rk-kpi-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }

        /* Loading Detail Card */
        .rk-detail {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .rk-detail-hdr {
            background:linear-gradient(180deg,#eff6ff,#f0f7ff); border-bottom:2px solid #bfdbfe;
            padding:1rem 1.375rem;
        }
        .rk-detail-title {
            font-size:0.9375rem; font-weight:700; color:#1e40af;
            display:flex; align-items:center; gap:0.5rem;
        }
        .rk-detail-title::before {
            content:''; width:4px; height:16px; border-radius:2px;
            background:linear-gradient(180deg,#3b82f6,#2563eb);
        }

        /* Table */
        .rk-tbl {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .rk-tbl-head { background:linear-gradient(180deg,#eff6ff,#f0f7ff); border-bottom:2px solid #bfdbfe; }
        .rk-tbl-head th {
            padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#1e40af; white-space:nowrap;
        }
        .rk-tbl-body td { padding:0.9375rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .rk-tbl-body tr { transition:background 0.15s; }
        .rk-tbl-body tr:last-child td { border-bottom:none; }
        .rk-tbl-body tr:hover td { background:linear-gradient(90deg,#f8faff,#eff6ff); }

        /* Sales cell */
        .rk-sales { display:flex; align-items:center; gap:0.75rem; }
        .rk-sales-av {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:0.875rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 4px 12px rgba(37,99,235,0.2);
        }
        .rk-sales-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }

        /* Product cell */
        .rk-prod { display:flex; flex-direction:column; gap:0.125rem; }
        .rk-prod-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .rk-prod-type { font-size:0.6875rem; color:#94a3b8; }

        /* Volume badges */
        .rk-vol {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.25rem 0.625rem; border-radius:8px; font-size:0.75rem; font-weight:700;
            font-family:'JetBrains Mono',monospace;
        }
        .rk-vol.loading { background:#eff6ff; color:#1d4ed8; }
        .rk-vol.terjual { background:#ecfdf5; color:#059669; }
        .rk-vol.sisa { background:#fffbeb; color:#d97706; }

        /* Status badge */
        .rk-status {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .rk-status.selesai { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .rk-status.berjalan { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }

        /* Form Section */
        .rk-form {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .rk-form-hdr {
            background:linear-gradient(180deg,#eff6ff,#f0f7ff); border-bottom:2px solid #bfdbfe;
            padding:1.125rem 1.375rem;
        }
        .rk-form-title {
            font-size:0.9375rem; font-weight:700; color:#1e40af;
            display:flex; align-items:center; gap:0.5rem;
        }
        .rk-form-title::before {
            content:''; width:4px; height:16px; border-radius:2px;
            background:linear-gradient(180deg,#3b82f6,#2563eb);
        }
        .rk-form-sub { font-size:0.8125rem; color:#64748b; margin-top:0.375rem; margin-left:1rem; }

        /* Form Input */
        .rk-fisik-input {
            width:5rem; padding:0.5rem 0.625rem; border-radius:8px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; text-align:center;
        }
        .rk-fisik-input:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.15); }

        /* Selisih badge */
        .rk-selisih {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.25rem 0.625rem; border-radius:8px; font-size:0.75rem; font-weight:700;
            font-family:'JetBrains Mono',monospace; transition:all 0.2s;
        }
        .rk-selisih.pas { background:#ecfdf5; color:#059669; }
        .rk-selisih.lebih { background:#eff6ff; color:#1d4ed8; }
        .rk-selisih.kurang { background:#fef2f2; color:#dc2626; }

        /* Keterangan input */
        .rk-ket-input {
            width:100%; padding:0.5rem 0.75rem; border-radius:8px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .rk-ket-input:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }

        /* Submit area */
        .rk-submit { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; }
        .rk-submit-note { font-size:0.8125rem; color:#64748b; display:flex; align-items:center; gap:0.375rem; }
        .rk-submit-note::before { content:'*'; color:#ef4444; font-weight:700; }
        .rk-btn-submit {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.75rem 1.5rem; border-radius:12px; font-size:0.875rem; font-weight:700;
            border:none; cursor:pointer; transition:all 0.25s; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 4px 14px rgba(37,99,235,0.35);
        }
        .rk-btn-submit:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(37,99,235,0.45); }

        @media(max-width:1024px) { .rk-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px)  { .rk-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="rk-page">

            {{-- Header --}}
            <div class="rk-hdr">
                <div class="rk-hdr-l">
                    <div class="rk-hdr-ico">🔄</div>
                    <div>
                        <div class="rk-hdr-title">Rekonsiliasi Stok</div>
                        <div class="rk-hdr-sub">Cocokkan stok fisik dengan sistem</div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="rk-filter">
                <form method="GET" class="rk-ff">
                    <div>
                        <label class="rk-flbl">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="rk-finput">
                    </div>
                    <div>
                        <label class="rk-flbl">Sales</label>
                        <select name="sales_id" class="rk-fsel">
                            <option value="">Semua Sales</option>
                            @foreach($salesList as $s)
                                <option value="{{ $s->id }}" {{ $salesId == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="rk-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                </form>
            </div>

            {{-- KPI Cards --}}
            <div class="rk-kpis">
                <div class="rk-kpi blue">
                    <div class="rk-kpi-top">
                        <div class="rk-kpi-left">
                            <span class="rk-kpi-lbl">Total Loading</span>
                            <div>
                                <span class="rk-kpi-val blue">{{ $stats['total_loading'] }}</span>
                            </div>
                            <div class="rk-kpi-foot">Loading dalam periode ini</div>
                        </div>
                        <div class="rk-kpi-ico blue">📦</div>
                    </div>
                </div>
                <div class="rk-kpi green">
                    <div class="rk-kpi-top">
                        <div class="rk-kpi-left">
                            <span class="rk-kpi-lbl">Sales Aktif</span>
                            <div>
                                <span class="rk-kpi-val green">{{ $stats['total_sales'] }}</span>
                            </div>
                            <div class="rk-kpi-foot">Sales dengan loading hari ini</div>
                        </div>
                        <div class="rk-kpi-ico green">👥</div>
                    </div>
                </div>
                <div class="rk-kpi purple">
                    <div class="rk-kpi-top">
                        <div class="rk-kpi-left">
                            <span class="rk-kpi-lbl">Status</span>
                            <div>
                                @php
                                    $adaSelisih = collect($rekonsiliasi)->where('selisih', '!=', 0)->count();
                                @endphp
                                <span class="rk-kpi-val purple">{{ $adaSelisih > 0 ? 'Ada Selisih' : 'Sesuai' }}</span>
                            </div>
                            <div class="rk-kpi-foot">Hasil rekonsiliasi</div>
                        </div>
                        <div class="rk-kpi-ico purple">
                            {{ $adaSelisih > 0 ? '⚠️' : '✅' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Loading Detail --}}
            @if($loadings->count() > 0)
            <div class="rk-detail">
                <div class="rk-detail-hdr">
                    <div class="rk-detail-title">Detail Loading Hari Ini</div>
                </div>
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:separate; border-spacing:0;">
                        <thead class="rk-tbl-head">
                            <tr>
                                <th style="text-align:left;">Sales</th>
                                <th style="text-align:left;">Produk</th>
                                <th style="text-align:center;">Loading</th>
                                <th style="text-align:center;">Terjual</th>
                                <th style="text-align:center;">Sisa</th>
                                <th style="text-align:center;">Status</th>
                            </tr>
                        </thead>
                        <tbody class="rk-tbl-body">
                            @foreach($loadings as $loading)
                            <tr>
                                <td>
                                    <div class="rk-sales">
                                        <div class="rk-sales-av">{{ substr($loading->sales->nama ?? 'U', 0, 1) }}</div>
                                        <span class="rk-sales-name">{{ $loading->sales->nama ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="rk-prod">
                                        <span class="rk-prod-name">{{ $loading->produk->nama ?? '-' }}</span>
                                        <span class="rk-prod-type">{{ $loading->produk->jenis ?? '-' }}</span>
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    <span class="rk-vol loading">{{ $loading->jumlah_loading }} {{ $loading->produk->satuan ?? 'L' }}</span>
                                </td>
                                <td style="text-align:center;">
                                    <span class="rk-vol terjual">{{ $loading->terjual }} {{ $loading->produk->satuan ?? 'L' }}</span>
                                </td>
                                <td style="text-align:center;">
                                    <span class="rk-vol sisa">{{ $loading->sisa_stok }} {{ $loading->produk->satuan ?? 'L' }}</span>
                                </td>
                                <td style="text-align:center;">
                                    <span class="rk-status {{ $loading->status == 'selesai' ? 'selesai' : 'berjalan' }}">
                                        {{ $loading->status == 'selesai' ? 'Selesai' : 'Berjalan' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Reconciliation Form --}}
            <form method="POST" action="{{ route('mineral.rekonsiliasi.store') }}">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <input type="hidden" name="sales_id" value="{{ $salesId }}">

                <div class="rk-form">
                    <div class="rk-form-hdr">
                        <div class="rk-form-title">Form Rekonsiliasi Stok</div>
                        <div class="rk-form-sub">Masukkan stok fisik yang ada di kendaraan/kanvas</div>
                    </div>

                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:separate; border-spacing:0;">
                            <thead class="rk-tbl-head">
                                <tr>
                                    <th style="text-align:left;">Produk</th>
                                    <th style="text-align:center;">Loading</th>
                                    <th style="text-align:center;">Terjual</th>
                                    <th style="text-align:center;">Sisa Sistem</th>
                                    <th style="text-align:center;">Sisa Fisik</th>
                                    <th style="text-align:center;">Selisih</th>
                                    <th style="text-align:left;">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="rk-tbl-body">
                                @foreach($rekonsiliasi as $index => $r)
                                @if($r['jumlah_loading'] > 0)
                                <tr data-row="{{ $index }}">
                                    <td>
                                        <div class="rk-prod">
                                            <span class="rk-prod-name">{{ $r['produk']->nama }}</span>
                                            <span class="rk-prod-type">{{ $r['produk']->jenis }}</span>
                                        </div>
                                        <input type="hidden" name="rekonsiliasi[{{ $index }}][produk_id]" value="{{ $r['produk']->id }}">
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="rk-vol loading">{{ $r['jumlah_loading'] }} {{ $r['produk']->satuan }}</span>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="rk-vol terjual">{{ $r['terjual'] }} {{ $r['produk']->satuan }}</span>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="rk-vol sisa">{{ $r['sisa_sistem'] }} {{ $r['produk']->satuan }}</span>
                                    </td>
                                    <td style="text-align:center;">
                                        <input type="number"
                                               name="rekonsiliasi[{{ $index }}][sisa_fisik]"
                                               value="{{ $r['sisa_sistem'] }}"
                                               min="0" step="0.01"
                                               class="rk-fisik-input"
                                               onchange="calculateSelisih({{ $index }}, {{ $r['sisa_sistem'] }})"
                                               id="fisik-{{ $index }}">
                                    </td>
                                    <td style="text-align:center;">
                                        <span id="selisih-{{ $index }}" class="rk-selisih pas">0</span>
                                    </td>
                                    <td>
                                        <input type="text"
                                               name="rekonsiliasi[{{ $index }}][keterangan]"
                                               placeholder="Keterangan selisih..."
                                               class="rk-ket-input">
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rk-submit">
                    <div class="rk-submit-note">
                        Isi kolom "Sisa Fisik" dengan stok yang benar-benar ada di kendaraan
                    </div>
                    <button type="submit" class="rk-btn-submit">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Rekonsiliasi
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function calculateSelisih(index, sisaSistem) {
            const fisikInput = document.getElementById('fisik-' + index);
            const selisihDisplay = document.getElementById('selisih-' + index);

            const fisik = parseFloat(fisikInput.value) || 0;
            const selisih = fisik - sisaSistem;

            selisihDisplay.textContent = (selisih > 0 ? '+' : '') + selisih.toFixed(2);

            if (selisih === 0) {
                selisihDisplay.className = 'rk-selisih pas';
            } else if (selisih > 0) {
                selisihDisplay.className = 'rk-selisih lebih';
            } else {
                selisihDisplay.className = 'rk-selisih kurang';
            }
        }
    </script>
</x-app-layout>
