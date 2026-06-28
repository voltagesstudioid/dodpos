<x-app-layout>
    @push('styles')
    <style>
        .pg { max-width:80rem; margin:0 auto; padding:0 1rem; }

        .hd { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .hd-l { display:flex; align-items:center; gap:1rem; }
        .hd-ico {
            width:48px; height:48px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.35rem; flex-shrink:0;
            background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff;
            box-shadow:0 6px 20px rgba(234,88,12,0.3);
        }
        .hd-t { font-size:1.35rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; }
        .hd-s { font-size:0.75rem; color:#64748b; margin-top:1px; }
        .hd-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.65rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.2s; border:none; cursor:pointer;
            background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff;
            box-shadow:0 4px 16px rgba(234,88,12,0.3);
        }
        .hd-btn:hover { transform:translateY(-1px); box-shadow:0 6px 24px rgba(234,88,12,0.4); }

        .stats { display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem; }
        .stat {
            display:flex; align-items:center; gap:0.625rem;
            padding:0.625rem 1rem; border-radius:10px; background:#fff;
            border:1px solid #e2e8f0; font-size:0.8125rem;
        }
        .stat-n { font-weight:800; color:#0f172a; }
        .stat-l { color:#64748b; }
        .stat-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }

        .flt {
            background:#fff; border:1px solid #e2e8f0; border-radius:12px;
            padding:1rem 1.25rem; margin-bottom:1.25rem;
        }
        .flt-f { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.625rem; }
        .flt-g { flex:1; min-width:160px; }
        .flt-l { font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.3rem; }
        .flt-i {
            width:100%; padding:0.55rem 0.875rem; border-radius:8px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .flt-i:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
        .flt-s {
            padding:0.55rem 2rem 0.55rem 0.75rem; border-radius:8px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.45rem center; background-size:14px;
        }
        .flt-s:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
        .flt-btn {
            padding:0.55rem 1.125rem; border-radius:8px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff;
        }
        .flt-btn:hover { opacity:0.9; }
        .flt-r {
            padding:0.55rem 1.125rem; border-radius:8px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem;
        }
        .flt-r:hover { background:#f1f5f9; }

        .tbl-wrap { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
        .tbl { width:100%; border-collapse:collapse; }
        .tbl th {
            padding:0.75rem 1rem; font-size:0.65rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#64748b; text-align:left; white-space:nowrap;
            background:#f8fafc; border-bottom:1px solid #e2e8f0;
        }
        .tbl td { padding:0.75rem 1rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#334155; vertical-align:middle; }
        .tbl tr:last-child td { border-bottom:none; }
        .tbl tr:hover td { background:#fffbeb; }

        .store { display:flex; align-items:center; gap:0.75rem; }
        .store-av {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:0.875rem; font-weight:800; flex-shrink:0;
            background:linear-gradient(135deg,#fff7ed,#ffedd5); color:#c2410c; border:1px solid #fed7aa;
        }
        .store-n { font-weight:600; color:#0f172a; }
        .store-a { font-size:0.6875rem; color:#94a3b8; }

        .kd {
            display:inline-flex; padding:0.15rem 0.45rem; border-radius:5px; font-size:0.6875rem; font-weight:700;
            background:#fffbeb; color:#92400e; font-family:monospace; border:1px solid #fde68a;
        }
        .hp { font-size:0.8125rem; }
        .hp a { color:#2563eb; text-decoration:none; }
        .hp a:hover { text-decoration:underline; }

        .tp {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.2rem 0.55rem; border-radius:99px; font-size:0.6875rem; font-weight:700; border:1px solid;
        }
        .tp-eceran { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .tp-grosir { background:#f5f3ff; color:#7c3aed; border-color:#ddd6fe; }
        .tp-agen   { background:#fff7ed; color:#c2410c; border-color:#fed7aa; }
        .tp-dot { width:5px; height:5px; border-radius:50%; }
        .tp-eceran .tp-dot { background:#3b82f6; }
        .tp-grosir .tp-dot { background:#8b5cf6; }
        .tp-agen .tp-dot   { background:#ea580c; }

        .rg-badge {
            display:inline-flex; padding:0.15rem 0.5rem; border-radius:5px;
            font-size:0.6875rem; font-weight:600; color:#64748b; background:#f1f5f9;
        }

        .st-badge {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.15rem 0.5rem; border-radius:99px; font-size:0.6875rem; font-weight:600; border:1px solid;
        }
        .st-aktif    { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .st-nonaktif { background:#f1f5f9; color:#64748b; border-color:#e2e8f0; }
        .st-blacklist { background:#fef2f2; color:#991b1b; border-color:#fecaca; }

        .act { display:flex; gap:0.375rem; }
        .act-a {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.3rem 0.55rem; border-radius:6px; font-size:0.6875rem; font-weight:600;
            border:1px solid; text-decoration:none; transition:all 0.15s;
        }
        .act-v { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .act-v:hover { background:#dbeafe; }
        .act-e { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .act-e:hover { background:#d1fae5; }

        .emp { text-align:center; padding:3rem 1rem; }
        .emp-ico {
            width:64px; height:64px; margin:0 auto 0.75rem; border-radius:50%;
            background:#fff7ed; display:flex; align-items:center; justify-content:center;
        }
        .emp-t { font-size:0.9375rem; font-weight:700; color:#475569; }
        .emp-s { font-size:0.8125rem; color:#94a3b8; margin-top:0.25rem; }
        .emp-btn {
            display:inline-flex; align-items:center; gap:0.5rem; margin-top:1rem;
            padding:0.6rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff; text-decoration:none;
            box-shadow:0 4px 16px rgba(234,88,12,0.25); transition:all 0.2s;
        }
        .emp-btn:hover { transform:translateY(-1px); }

        @media(max-width:768px) {
            .tbl th:nth-child(3), .tbl td:nth-child(3),
            .tbl th:nth-child(4), .tbl td:nth-child(4) { display:none; }
        }
        @media(max-width:640px) {
            .tbl th:nth-child(5), .tbl td:nth-child(5) { display:none; }
            .hd-t { font-size:1.125rem; }
        }
    </style>
    @endpush

    <div class="py-4">
        <div class="pg">

            {{-- Header --}}
            <div class="hd">
                <div class="hd-l">
                    <div class="hd-ico">🏪</div>
                    <div>
                        <div class="hd-t">Pelanggan</div>
                        <div class="hd-s">{{ $stats['total'] }} total pelanggan</div>
                    </div>
                </div>
                <a href="{{ route('minyak.pelanggan.create') }}" class="hd-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </a>
            </div>

            {{-- Stats --}}
            <div class="stats">
                <div class="stat">
                    <span class="stat-dot" style="background:#3b82f6;"></span>
                    <span class="stat-n">{{ $stats['total'] }}</span>
                    <span class="stat-l">Total</span>
                </div>
                <div class="stat">
                    <span class="stat-dot" style="background:#10b981;"></span>
                    <span class="stat-n">{{ $stats['aktif'] }}</span>
                    <span class="stat-l">Aktif</span>
                </div>
                <div class="stat">
                    <span class="stat-dot" style="background:#8b5cf6;"></span>
                    <span class="stat-n">{{ $stats['eceran'] }}</span>
                    <span class="stat-l">Eceran</span>
                </div>
                <div class="stat">
                    <span class="stat-dot" style="background:#ea580c;"></span>
                    <span class="stat-n">{{ $stats['grosir'] }}</span>
                    <span class="stat-l">Grosir</span>
                </div>
                @if(!$isSalesRole)
                <div class="stat">
                    <span class="stat-dot" style="background:#dc2626;"></span>
                    <span class="stat-n">Rp {{ number_format($stats['total_hutang'] ?? 0, 0, ',', '.') }}</span>
                    <span class="stat-l">Total Hutang</span>
                </div>
                @endif
            </div>

            {{-- Filter --}}
            <div class="flt">
                <form method="GET" class="flt-f">
                    <div class="flt-g" style="flex:2;">
                        <label class="flt-l">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama toko, pemilik, no HP..." class="flt-i">
                    </div>
                    @if(!$isSalesRole)
                    <div class="flt-g">
                        <label class="flt-l">Regional</label>
                        <select name="regional_id" class="flt-s">
                            <option value="">Semua</option>
                            @foreach($regionals as $r)
                                <option value="{{ $r->id }}" {{ request('regional_id') == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="flt-g">
                        <label class="flt-l">Tipe</label>
                        <select name="tipe" class="flt-s">
                            <option value="">Semua</option>
                            <option value="eceran" {{ request('tipe') == 'eceran' ? 'selected' : '' }}>Eceran</option>
                            <option value="grosir" {{ request('tipe') == 'grosir' ? 'selected' : '' }}>Grosir</option>
                            <option value="agen" {{ request('tipe') == 'agen' ? 'selected' : '' }}>Agen</option>
                        </select>
                    </div>
                    <div class="flt-g">
                        <label class="flt-l">Status</label>
                        <select name="status" class="flt-s">
                            <option value="">Semua</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="blacklist" {{ request('status') == 'blacklist' ? 'selected' : '' }}>Blacklist</option>
                        </select>
                    </div>
                    <button type="submit" class="flt-btn">Filter</button>
                    <a href="{{ route('minyak.pelanggan.index') }}" class="flt-r">Reset</a>
                </form>
            </div>

            {{-- Table --}}
            @php $allPelanggans = $grouped->flatten(1); @endphp
            @if($allPelanggans->isEmpty())
                <div class="tbl-wrap">
                    <div class="emp">
                        <div class="emp-ico">
                            <svg width="28" height="28" fill="none" stroke="#c2410c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div class="emp-t">Belum ada pelanggan</div>
                        <div class="emp-s">Coba ubah filter atau tambah pelanggan baru</div>
                        <a href="{{ route('minyak.pelanggan.create') }}" class="emp-btn">Tambah Pelanggan</a>
                    </div>
                </div>
            @else
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Nama Toko</th>
                                <th>Kode</th>
                                <th>Pemilik</th>
                                <th>No HP</th>
                                <th>Regional</th>
                                <th>Kota</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                @if(!$isSalesRole)
                                <th style="text-align:right;">Hutang</th>
                                @endif
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allPelanggans as $p)
                            <tr>
                                <td>
                                    <div class="store">
                                        <div class="store-av">{{ substr($p->nama_toko, 0, 1) }}</div>
                                        <div>
                                            <div class="store-n">{{ $p->nama_toko }}</div>
                                            <div class="store-a">{{ Str::limit($p->alamat, 40) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="kd">{{ $p->kode_pelanggan }}</span></td>
                                <td>{{ $p->nama_pemilik }}</td>
                                <td>
                                    @if($p->no_hp)
                                        <span class="hp"><a href="tel:{{ $p->no_hp }}">{{ $p->no_hp }}</a></span>
                                    @else
                                        <span style="color:#94a3b8;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->regional)
                                        <span class="rg-badge">{{ $p->regional->nama }}</span>
                                    @else
                                        <span style="color:#94a3b8;">-</span>
                                    @endif
                                </td>
                                <td style="color:#64748b;">{{ $p->kota }}{{ $p->provinsi ? ', ' . $p->provinsi : '' }}</td>
                                <td>
                                    <span class="tp tp-{{ $p->tipe }}">
                                        <span class="tp-dot"></span>
                                        {{ ucfirst($p->tipe) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="st-badge st-{{ $p->status }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                                @if(!$isSalesRole)
                                <td style="text-align:right; font-weight:600; {{ $p->total_hutang > 0 ? 'color:#dc2626;' : 'color:#94a3b8;' }}">
                                    Rp {{ number_format($p->total_hutang, 0, ',', '.') }}
                                </td>
                                @endif
                                <td style="text-align:center;">
                                    <div class="act">
                                        <a href="{{ route('minyak.pelanggan.show', $p) }}" class="act-a act-v">Detail</a>
                                        <a href="{{ route('minyak.pelanggan.edit', $p) }}" class="act-a act-e">Edit</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
