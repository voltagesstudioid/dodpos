<x-app-layout>
    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style>
        .rk-wrap{font-family:'Plus Jakarta Sans',sans-serif}
        .rk-mono{font-family:'JetBrains Mono',monospace}

        .rk-header{background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);border:1px solid #fde68a;border-radius:20px;padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px}
        .rk-header-left{display:flex;align-items:center;gap:16px}
        .rk-header-icon{width:52px;height:52px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:16px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(245,158,11,.35)}
        .rk-header-icon svg{width:26px;height:26px;color:#fff}
        .rk-header h1{font-size:1.375rem;font-weight:800;color:#1f2937;margin:0}
        .rk-header p{font-size:.8rem;color:#92400e;margin:2px 0 0}
        .rk-date-badge{font-size:.75rem;font-weight:600;color:#92400e;background:#fff;border:1px solid #fde68a;padding:6px 14px;border-radius:10px;display:flex;align-items:center;gap:6px}
        .rk-date-badge svg{width:14px;height:14px;color:#d97706}

        .rk-filter{background:#fff;border:1px solid #fde68a;border-radius:16px;padding:16px 20px;margin-bottom:24px;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .rk-filter form{display:flex;flex-wrap:wrap;align-items:center;gap:10px}
        .rk-date-group{display:flex;align-items:center;gap:6px}
        .rk-date-lbl{font-size:.78rem;color:#92400e;font-weight:600;white-space:nowrap}
        .rk-date-input{border:1.5px solid #fde68a;border-radius:12px;padding:9px 12px;font-size:.8125rem;background:#fffbeb;color:#92400e;font-weight:500;outline:none;transition:border-color .2s}
        .rk-date-input:focus{border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.1)}
        .rk-filter select{border:1.5px solid #fde68a;border-radius:12px;padding:9px 14px;font-size:.8125rem;background:#fffbeb;color:#92400e;font-weight:500;outline:none;min-width:160px;transition:border-color .2s;cursor:pointer}
        .rk-filter select:focus{border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.1)}
        .rk-btn-filter{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;border-radius:12px;padding:9px 18px;font-size:.8125rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:7px;transition:opacity .2s}
        .rk-btn-filter:hover{opacity:.88}
        .rk-btn-filter svg{width:15px;height:15px}

        .rk-kpi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px}
        .rk-kpi{background:#fff;border:1px solid #fde68a;border-radius:16px;padding:20px;display:flex;align-items:flex-start;gap:14px;position:relative;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04);transition:box-shadow .2s}
        .rk-kpi:hover{box-shadow:0 4px 14px rgba(245,158,11,.1)}
        .rk-kpi::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px}
        .rk-kpi.blue::before{background:linear-gradient(180deg,#3b82f6,#2563eb)}
        .rk-kpi.green::before{background:linear-gradient(180deg,#10b981,#059669)}
        .rk-kpi.amber::before{background:linear-gradient(180deg,#f59e0b,#d97706)}
        .rk-kpi-icon{width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .rk-kpi-icon svg{width:22px;height:22px}
        .rk-kpi-icon.blue{background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#2563eb}
        .rk-kpi-icon.green{background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#059669}
        .rk-kpi-icon.amber{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706}
        .rk-kpi-val{font-size:1.5rem;font-weight:800;color:#1f2937;line-height:1}
        .rk-kpi-lbl{font-size:.72rem;color:#6b7280;margin-top:4px;font-weight:500}
        .rk-status-tag{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:8px;font-size:.72rem;font-weight:700;margin-top:6px}
        .rk-status-tag.ok{background:#d1fae5;color:#065f46}
        .rk-status-tag.warn{background:#fee2e2;color:#991b1b}
        .rk-status-tag svg{width:13px;height:13px}

        .rk-card{background:#fff;border:1px solid #fde68a;border-radius:20px;overflow:hidden;margin-bottom:24px;box-shadow:0 2px 8px rgba(0,0,0,.05)}
        .rk-card-head{background:linear-gradient(180deg,#fffbeb,#fef9ee);border-bottom:2px solid #fde68a;padding:18px 24px;display:flex;align-items:center;gap:10px}
        .rk-card-head svg{width:18px;height:18px;color:#d97706}
        .rk-card-head h3{font-size:.9rem;font-weight:700;color:#1f2937;margin:0}
        .rk-tbl-wrap{overflow-x:auto}
        .rk-tbl{width:100%;border-collapse:collapse}
        .rk-tbl thead th{background:linear-gradient(180deg,#fffbeb,#fef9ee);border-bottom:2px solid #fde68a;padding:12px 16px;font-size:.7rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.5px;white-space:nowrap}
        .rk-tbl tbody td{padding:13px 16px;border-bottom:1px solid #fef3c7;font-size:.8125rem;color:#374151;vertical-align:middle}
        .rk-tbl tbody tr:last-child td{border-bottom:none}
        .rk-tbl tbody tr{transition:background .15s}
        .rk-tbl tbody tr:hover{background:#fffbeb}

        .rk-sales-nm{font-weight:600;color:#1f2937}
        .rk-prod-name{font-weight:600;color:#1f2937}
        .rk-prod-sub{font-size:.7rem;color:#6b7280;margin-top:1px}
        .rk-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:8px;font-size:.72rem;font-weight:600}
        .rk-badge.loading{background:#dbeafe;color:#1e40af}
        .rk-badge.terjual{background:#d1fae5;color:#065f46}
        .rk-badge.sisa{background:#fef3c7;color:#92400e}
        .rk-badge.selesai{background:#d1fae5;color:#065f46}
        .rk-badge.berjalan{background:#dbeafe;color:#1e40af}
        .rk-badge.default{background:#f3f4f6;color:#6b7280}

        .rk-form-card{background:#fff;border:1px solid #fde68a;border-radius:20px;overflow:hidden;margin-bottom:24px;box-shadow:0 2px 8px rgba(0,0,0,.05)}
        .rk-form-head{background:linear-gradient(180deg,#fffbeb,#fef9ee);border-bottom:2px solid #fde68a;padding:20px 24px}
        .rk-form-head h3{font-size:.95rem;font-weight:700;color:#1f2937;margin:0;display:flex;align-items:center;gap:8px}
        .rk-form-head h3 svg{width:18px;height:18px;color:#d97706}
        .rk-form-head p{font-size:.78rem;color:#92400e;margin:4px 0 0}

        .rk-fisik-input{width:90px;text-align:center;border:1.5px solid #fde68a;border-radius:10px;padding:7px 8px;font-size:.8125rem;font-weight:600;background:#fffbeb;color:#92400e;outline:none;transition:border-color .2s}
        .rk-fisik-input:focus{border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.12)}
        .rk-ket-input{width:100%;border:1.5px solid #fde68a;border-radius:10px;padding:7px 12px;font-size:.8125rem;background:#fffbeb;color:#92400e;outline:none;transition:border-color .2s}
        .rk-ket-input:focus{border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.12)}
        .rk-ket-input::placeholder{color:#d1d5db}

        .rk-selisih{display:inline-flex;align-items:center;padding:4px 10px;border-radius:8px;font-size:.78rem;font-weight:700}
        .rk-selisih.ok{background:#d1fae5;color:#065f46}
        .rk-selisih.plus{background:#dbeafe;color:#1e40af}
        .rk-selisih.minus{background:#fee2e2;color:#991b1b}
        .rk-selisih.neutral{background:#f3f4f6;color:#6b7280}

        .rk-footer{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px}
        .rk-footer-note{font-size:.78rem;color:#6b7280;display:flex;align-items:center;gap:6px}
        .rk-footer-note svg{width:14px;height:14px;color:#d97706}
        .rk-btn-save{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:12px 26px;border-radius:14px;font-size:.875rem;font-weight:700;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(245,158,11,.35);transition:opacity .2s}
        .rk-btn-save:hover{opacity:.88}
        .rk-btn-save svg{width:18px;height:18px}

        @media(max-width:640px){.rk-kpi-grid{grid-template-columns:1fr}}
    </style>
    @endpush

    <div class="rk-wrap" style="padding:24px">

        {{-- Header --}}
        <div class="rk-header">
            <div class="rk-header-left">
                <div class="rk-header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div>
                    <h1>Rekonsiliasi Stok</h1>
                    <p>Cocokkan stok fisik dengan sistem</p>
                </div>
            </div>
            <div class="rk-date-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
            </div>
        </div>

        {{-- Filter --}}
        <div class="rk-filter">
            <form method="GET">
                <div class="rk-date-group">
                    <span class="rk-date-lbl">Tanggal:</span>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="rk-date-input">
                </div>
                <select name="sales_id">
                    <option value="">Semua Sales</option>
                    @foreach($salesList as $s)
                        <option value="{{ $s->id }}" {{ $salesId == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rk-btn-filter">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
            </form>
        </div>

        {{-- KPI Cards --}}
        @php $adaSelisih = collect($rekonsiliasi)->where('selisih', '!=', 0)->count(); @endphp
        <div class="rk-kpi-grid">
            <div class="rk-kpi blue">
                <div class="rk-kpi-icon blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <div class="rk-kpi-val">{{ $stats['total_loading'] }}</div>
                    <div class="rk-kpi-lbl">Total Loading</div>
                </div>
            </div>
            <div class="rk-kpi green">
                <div class="rk-kpi-icon green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <div class="rk-kpi-val">{{ $stats['total_sales'] }}</div>
                    <div class="rk-kpi-lbl">Sales Aktif</div>
                </div>
            </div>
            <div class="rk-kpi amber">
                <div class="rk-kpi-icon amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="rk-kpi-val">{{ $adaSelisih > 0 ? 'Ada Selisih' : 'Sesuai' }}</div>
                    <div class="rk-kpi-lbl">Status Rekonsiliasi</div>
                    @if($adaSelisih > 0)
                        <span class="rk-status-tag warn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
                            {{ $adaSelisih }} produk selisih
                        </span>
                    @else
                        <span class="rk-status-tag ok">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Semua cocok
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Loading Detail --}}
        @if($loadings->count() > 0)
        <div class="rk-card">
            <div class="rk-card-head">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <h3>Detail Loading Hari Ini</h3>
            </div>
            <div class="rk-tbl-wrap">
                <table class="rk-tbl">
                    <thead>
                        <tr>
                            <th style="text-align:left">Sales</th>
                            <th style="text-align:left">Produk</th>
                            <th style="text-align:center">Loading</th>
                            <th style="text-align:center">Terjual</th>
                            <th style="text-align:center">Sisa</th>
                            <th style="text-align:center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loadings as $loading)
                        <tr>
                            <td><span class="rk-sales-nm">{{ $loading->sales->nama ?? '-' }}</span></td>
                            <td>
                                <div class="rk-prod-name">{{ $loading->produk->nama ?? '-' }}</div>
                                <div class="rk-prod-sub">{{ $loading->produk->jenis ?? '-' }}</div>
                            </td>
                            <td style="text-align:center"><span class="rk-badge loading rk-mono">{{ $loading->jumlah_loading }} {{ $loading->produk->satuan ?? 'L' }}</span></td>
                            <td style="text-align:center"><span class="rk-badge terjual rk-mono">{{ $loading->terjual }} {{ $loading->produk->satuan ?? 'L' }}</span></td>
                            <td style="text-align:center"><span class="rk-badge sisa rk-mono">{{ $loading->sisa_stok }} {{ $loading->produk->satuan ?? 'L' }}</span></td>
                            <td style="text-align:center">
                                @if($loading->status == 'selesai')
                                    <span class="rk-badge selesai">Selesai</span>
                                @elseif($loading->status == 'sedang_berjalan')
                                    <span class="rk-badge berjalan">Berjalan</span>
                                @else
                                    <span class="rk-badge default">{{ $loading->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Reconciliation Form --}}
        <form method="POST" action="{{ route('gula.rekonsiliasi.store') }}">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            <input type="hidden" name="sales_id" value="{{ $salesId }}">

            <div class="rk-form-card">
                <div class="rk-form-head">
                    <h3>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Form Rekonsiliasi Stok
                    </h3>
                    <p>Masukkan stok fisik yang ada di kendaraan/kanvas</p>
                </div>
                <div class="rk-tbl-wrap">
                    <table class="rk-tbl">
                        <thead>
                            <tr>
                                <th style="text-align:left">Produk</th>
                                <th style="text-align:center">Loading</th>
                                <th style="text-align:center">Terjual</th>
                                <th style="text-align:center">Sisa Sistem</th>
                                <th style="text-align:center">Sisa Fisik</th>
                                <th style="text-align:center">Selisih</th>
                                <th style="text-align:left">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rekonsiliasi as $index => $r)
                            @if($r['jumlah_loading'] > 0)
                            <tr data-row="{{ $index }}">
                                <td>
                                    <div class="rk-prod-name">{{ $r['produk']->nama }}</div>
                                    <div class="rk-prod-sub">{{ $r['produk']->jenis }}</div>
                                    <input type="hidden" name="rekonsiliasi[{{ $index }}][produk_id]" value="{{ $r['produk']->id }}">
                                </td>
                                <td style="text-align:center"><span class="rk-badge loading rk-mono">{{ $r['jumlah_loading'] }} {{ $r['produk']->satuan }}</span></td>
                                <td style="text-align:center"><span class="rk-badge terjual rk-mono">{{ $r['terjual'] }} {{ $r['produk']->satuan }}</span></td>
                                <td style="text-align:center"><span class="rk-badge sisa rk-mono">{{ $r['sisa_sistem'] }} {{ $r['produk']->satuan }}</span></td>
                                <td style="text-align:center">
                                    <input type="number" name="rekonsiliasi[{{ $index }}][sisa_fisik]" value="{{ $r['sisa_sistem'] }}" min="0" step="0.01" class="rk-fisik-input" onchange="calculateSelisih({{ $index }}, {{ $r['sisa_sistem'] }})" id="fisik-{{ $index }}">
                                </td>
                                <td style="text-align:center">
                                    <span id="selisih-{{ $index }}" class="rk-selisih neutral">0</span>
                                </td>
                                <td>
                                    <input type="text" name="rekonsiliasi[{{ $index }}][keterangan]" placeholder="Keterangan selisih..." class="rk-ket-input">
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rk-footer">
                <div class="rk-footer-note">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Isi kolom "Sisa Fisik" dengan stok yang benar-benar ada di kendaraan
                </div>
                <button type="submit" class="rk-btn-save">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Rekonsiliasi
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function calculateSelisih(index, sisaSistem) {
            const fisikInput = document.getElementById('fisik-' + index);
            const selisihDisplay = document.getElementById('selisih-' + index);
            const fisik = parseFloat(fisikInput.value) || 0;
            const selisih = fisik - sisaSistem;
            selisihDisplay.textContent = (selisih > 0 ? '+' : '') + selisih.toFixed(2);
            if (selisih === 0) {
                selisihDisplay.className = 'rk-selisih ok';
            } else if (selisih > 0) {
                selisihDisplay.className = 'rk-selisih plus';
            } else {
                selisihDisplay.className = 'rk-selisih minus';
            }
        }
    </script>
    @endpush
</x-app-layout>
