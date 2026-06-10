<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap');
        .sm-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .sm-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .sm-hdr-l { display:flex; align-items:center; gap:1rem; }
        .sm-hdr-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0; background:linear-gradient(135deg,#f59e0b,#d97706); box-shadow:0 8px 24px rgba(217,119,6,0.3); }
        .sm-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .sm-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .sm-hdr-btn { display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600; text-decoration:none; transition:all 0.25s; border:none; cursor:pointer; background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 4px 14px rgba(217,119,6,0.35); }
        .sm-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(217,119,6,0.45); }
        .sm-kpis { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
        .sm-kpi { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.375rem; transition:all 0.3s; position:relative; overflow:hidden; }
        .sm-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .sm-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .sm-kpi.amber::before { background:linear-gradient(180deg,#f59e0b,#d97706); }
        .sm-kpi.blue::before { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .sm-kpi.purple::before { background:linear-gradient(180deg,#8b5cf6,#7c3aed); }
        .sm-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .sm-kpi-val { font-size:2rem; font-weight:800; letter-spacing:-0.03em; line-height:1; margin-top:0.25rem; }
        .sm-kpi-val.amber { color:#d97706; }
        .sm-kpi-val.blue { color:#2563eb; }
        .sm-kpi-val.purple { color:#7c3aed; }
        .sm-kpi-foot { font-size:0.72rem; color:#94a3b8; margin-top:0.375rem; }
        .sm-filter { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem; margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
        .sm-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .sm-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .sm-finput { width:100%; padding:0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0; background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit; }
        .sm-finput:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .sm-fsel { padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0; background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit; cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px; }
        .sm-fsel:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .sm-btn-f { padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600; border:none; cursor:pointer; transition:all 0.2s; font-family:inherit; background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 4px 12px rgba(217,119,6,0.25); }
        .sm-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(217,119,6,0.35); }
        .sm-btn-r { padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600; border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit; background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem; }
        .sm-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }
        .sm-tbl { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
        .sm-tbl-head { background:linear-gradient(180deg,#fffbeb,#fef3c7); border-bottom:2px solid #fbbf24; }
        .sm-tbl-head th { padding:0.9rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#92400e; white-space:nowrap; }
        .sm-tbl-body td { padding:0.9375rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .sm-tbl-body tr { transition:background 0.15s; }
        .sm-tbl-body tr:last-child td { border-bottom:none; }
        .sm-tbl-body tr:hover td { background:linear-gradient(90deg,#fffbeb,#fffdf5); }
        .sm-ref { display:inline-flex; align-items:center; gap:0.375rem; font-family:'JetBrains Mono',monospace; font-size:0.75rem; font-weight:600; color:#475569; background:#f8fafc; padding:0.375rem 0.625rem; border-radius:8px; border:1px solid #e2e8f0; }
        .sm-tipe { display:inline-flex; align-items:center; gap:0.3rem; padding:0.25rem 0.75rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid; }
        .sm-tipe.penerimaan { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .sm-tipe.koreksi { background:#eff6ff; color:#2563eb; border-color:#bfdbfe; }
        .sm-diff-pos { color:#059669; font-weight:700; }
        .sm-diff-neg { color:#ef4444; font-weight:700; }
        .sm-status { display:inline-flex; align-items:center; gap:0.3rem; padding:0.25rem 0.75rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid; }
        .sm-status.aktif { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .sm-status.batal { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
        .sm-act { display:inline-flex; align-items:center; gap:0.25rem; padding:0.375rem 0.625rem; border-radius:8px; font-size:0.6875rem; font-weight:600; border:1px solid; cursor:pointer; text-decoration:none; transition:all 0.2s; white-space:nowrap; font-family:inherit; }
        .sm-act.detail { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .sm-act.detail:hover { background:#dbeafe; }
        .sm-act.del { background:#fff1f2; color:#be123c; border-color:#fecdd3; }
        .sm-act.del:hover { background:#ffe4e6; }
        .sm-empty { text-align:center; padding:3.5rem 1.5rem; }
        .sm-empty-ico { width:72px; height:72px; margin:0 auto 1rem; border-radius:50%; background:linear-gradient(135deg,#fffbeb,#fef3c7); display:flex; align-items:center; justify-content:center; }
        .sm-empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .sm-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-bottom:1.25rem; }
        .sm-empty-cta { display:inline-flex; align-items:center; gap:0.5rem; padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600; background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; text-decoration:none; box-shadow:0 4px 14px rgba(217,119,6,0.25); transition:all 0.2s; }
        @media(max-width:1024px) { .sm-kpis { grid-template-columns:1fr; } }
        @media(max-width:640px) { .sm-hdr-title { font-size:1.25rem; } .sm-ff { flex-direction:column; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="sm-page">
            <div class="sm-hdr">
                <div class="sm-hdr-l">
                    <div class="sm-hdr-ico">📦</div>
                    <div>
                        <div class="sm-hdr-title">Penerimaan & Koreksi Stok</div>
                        <div class="sm-hdr-sub">Kelola stok masuk gudang dan koreksi stok fisik</div>
                    </div>
                </div>
                <a href="{{ route('gula.stok-masuk.create') }}" class="sm-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Stok
                </a>
            </div>

            <div class="sm-kpis">
                <div class="sm-kpi amber">
                    <span class="sm-kpi-lbl">Total Penerimaan</span>
                    <div class="sm-kpi-val amber">{{ number_format($stats['total_penerimaan'], 0, ',', '.') }}</div>
                    <div class="sm-kpi-foot">Semua waktu</div>
                </div>
                <div class="sm-kpi blue">
                    <span class="sm-kpi-lbl">Total Koreksi</span>
                    <div class="sm-kpi-val blue">{{ number_format($stats['total_koreksi'], 0, ',', '.') }}</div>
                    <div class="sm-kpi-foot">Selisih koreksi</div>
                </div>
                <div class="sm-kpi purple">
                    <span class="sm-kpi-lbl">Bulan Ini</span>
                    <div class="sm-kpi-val purple">{{ number_format($stats['bulan_ini'], 0, ',', '.') }}</div>
                    <div class="sm-kpi-foot">Total stok masuk bulan ini</div>
                </div>
            </div>

            <div class="sm-filter">
                <form method="GET" class="sm-ff">
                    <div>
                        <label class="sm-flbl">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="No. referensi / nama produk..." class="sm-finput">
                    </div>
                    <div>
                        <label class="sm-flbl">Tipe</label>
                        <select name="tipe" class="sm-fsel">
                            <option value="">Semua Tipe</option>
                            <option value="penerimaan" {{ request('tipe') == 'penerimaan' ? 'selected' : '' }}>Penerimaan</option>
                            <option value="koreksi" {{ request('tipe') == 'koreksi' ? 'selected' : '' }}>Koreksi</option>
                        </select>
                    </div>
                    <div>
                        <label class="sm-flbl">Produk</label>
                        <select name="produk_id" class="sm-fsel">
                            <option value="">Semua Produk</option>
                            @foreach($produks as $p)
                                <option value="{{ $p->id }}" {{ request('produk_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="sm-btn-f">Filter</button>
                    <a href="{{ route('gula.stok-masuk.index') }}" class="sm-btn-r">Reset</a>
                </form>
            </div>

            <div class="sm-tbl">
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:separate; border-spacing:0;">
                        <thead class="sm-tbl-head">
                            <tr>
                                <th style="text-align:left;">No. Referensi</th>
                                <th style="text-align:left;">Tanggal</th>
                                <th style="text-align:left;">Produk</th>
                                <th style="text-align:center;">Tipe</th>
                                <th style="text-align:right;">Stok Sebelum</th>
                                <th style="text-align:right;">Perubahan</th>
                                <th style="text-align:right;">Stok Sesudah</th>
                                <th style="text-align:left;">Sumber</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="sm-tbl-body">
                            @forelse($records as $r)
                            <tr>
                                <td><span class="sm-ref">{{ $r->no_referensi }}</span></td>
                                <td>{{ $r->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $r->produk->nama ?? '-' }}</td>
                                <td style="text-align:center;">
                                    <span class="sm-tipe {{ $r->tipe }}">{{ ucfirst($r->tipe) }}</span>
                                </td>
                                <td style="text-align:right; font-family:'JetBrains Mono',monospace; font-size:0.8rem;">
                                    {{ number_format($r->stok_sebelum, 0, ',', '.') }}
                                </td>
                                <td style="text-align:right;">
                                    @if((float)$r->jumlah >= 0)
                                        <span class="sm-diff-pos">+{{ number_format($r->jumlah, 0, ',', '.') }}</span>
                                    @else
                                        <span class="sm-diff-neg">{{ number_format($r->jumlah, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td style="text-align:right; font-family:'JetBrains Mono',monospace; font-size:0.8rem; font-weight:700;">
                                    {{ number_format($r->stok_sesudah, 0, ',', '.') }}
                                </td>
                                <td>{{ $r->sumber ?? '-' }}</td>
                                <td style="text-align:center;">
                                    <span class="sm-status {{ $r->status }}">{{ ucfirst($r->status) }}</span>
                                </td>
                                <td style="text-align:center;">
                                    <div style="display:flex; gap:0.375rem; justify-content:center;">
                                        <a href="{{ route('gula.stok-masuk.show', $r->id) }}" class="sm-act detail">Detail</a>
                                        @if($r->status === 'aktif')
                                        <form action="{{ route('gula.stok-masuk.cancel', $r->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Batalkan data ini? Stok gudang akan dikurangi kembali.')">
                                            @csrf
                                            <button type="submit" class="sm-act del">Batal</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">
                                    <div class="sm-empty">
                                        <div class="sm-empty-ico">
                                            <svg width="32" height="32" fill="none" stroke="#d97706" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </div>
                                        <div class="sm-empty-title">Belum Ada Data</div>
                                        <div class="sm-empty-sub">Mulai catat penerimaan atau koreksi stok gudang</div>
                                        <a href="{{ route('gula.stok-masuk.create') }}" class="sm-empty-cta">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Tambah Stok Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($records->hasPages())
                    <div style="padding:0.875rem 1.25rem; border-top:1px solid #f1f5f9;">
                        {{ $records->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
