<x-app-layout>
    @push('styles')
    <style>
        .pd-page { max-width:64rem; margin:0 auto; padding:0 1rem 2rem; }

        /* ── Hero ── */
        .pd-hero {
            position:relative; border-radius:24px; overflow:hidden; margin-bottom:1.5rem;
            background:linear-gradient(135deg,#0f172a 0%,#1e293b 40%,#334155 100%);
            box-shadow:0 20px 60px rgba(15,23,42,0.25), 0 1px 3px rgba(0,0,0,0.08);
        }
        .pd-hero::before {
            content:''; position:absolute; top:-60%; right:-15%; width:420px; height:420px; border-radius:50%;
            background:radial-gradient(circle,rgba(245,158,11,0.15) 0%,transparent 70%); pointer-events:none;
        }
        .pd-hero::after {
            content:''; position:absolute; bottom:-40%; left:-10%; width:350px; height:350px; border-radius:50%;
            background:radial-gradient(circle,rgba(99,102,241,0.1) 0%,transparent 70%); pointer-events:none;
        }
        .pd-hero-top { position:relative; z-index:1; padding:2rem 2.25rem 1.5rem; display:flex; align-items:flex-start; gap:1.5rem; }
        .pd-hero-back {
            width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            background:rgba(255,255,255,0.08); backdrop-filter:blur(8px); color:rgba(255,255,255,0.6);
            text-decoration:none; transition:all 0.2s; flex-shrink:0; border:1px solid rgba(255,255,255,0.08);
        }
        .pd-hero-back:hover { background:rgba(255,255,255,0.14); color:#fff; transform:translateX(-2px); }

        .pd-avatar {
            width:72px; height:72px; border-radius:20px; display:flex; align-items:center; justify-content:center;
            font-size:2rem; font-weight:800; flex-shrink:0; position:relative;
            background:linear-gradient(135deg,#f59e0b,#ea580c);
            box-shadow:0 8px 24px rgba(234,88,12,0.35);
            color:#fff; letter-spacing:-0.02em;
        }
        .pd-avatar::after {
            content:''; position:absolute; inset:-3px; border-radius:23px; border:2px solid rgba(255,255,255,0.12); pointer-events:none;
        }
        .pd-hero-info { flex:1; min-width:0; }
        .pd-hero-name { font-size:1.625rem; font-weight:800; color:#fff; letter-spacing:-0.03em; line-height:1.2; margin-bottom:0.375rem; }
        .pd-hero-code {
            display:inline-flex; padding:0.2rem 0.625rem; border-radius:6px;
            font-size:0.6875rem; font-weight:700; background:rgba(255,255,255,0.08); color:rgba(255,255,255,0.5);
            letter-spacing:0.05em; font-family:monospace;
        }
        .pd-hero-tags { display:flex; gap:0.5rem; flex-wrap:wrap; }
        .pd-tag {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.35rem 0.875rem; border-radius:99px; font-size:0.6875rem; font-weight:700;
            backdrop-filter:blur(8px); border:1px solid;
        }
        .pd-tag.aktif { background:rgba(16,185,129,0.12); color:#6ee7b7; border-color:rgba(16,185,129,0.2); }
        .pd-tag.nonaktif { background:rgba(148,163,184,0.12); color:#94a3b8; border-color:rgba(148,163,184,0.2); }
        .pd-tag.blacklist { background:rgba(239,68,68,0.12); color:#fca5a5; border-color:rgba(239,68,68,0.2); }
        .pd-tag-dot { width:6px; height:6px; border-radius:50%; }
        .pd-tag-dot.aktif { background:#10b981; }
        .pd-tag-dot.nonaktif { background:#64748b; }
        .pd-tag-dot.blacklist { background:#ef4444; }
        .pd-tag-tipe {
            padding:0.35rem 0.875rem; border-radius:99px; font-size:0.6875rem; font-weight:600;
            background:rgba(255,255,255,0.06); color:rgba(255,255,255,0.45); border:1px solid rgba(255,255,255,0.08);
            text-transform:capitalize;
        }

        /* ── Info Strip (inside hero) ── */
        .pd-info-strip {
            position:relative; z-index:1;
            display:grid; grid-template-columns:repeat(4,1fr); gap:0;
            border-top:1px solid rgba(255,255,255,0.06);
        }
        .pd-info-cell {
            padding:1.125rem 1.5rem; display:flex; align-items:flex-start; gap:0.75rem;
            position:relative;
        }
        .pd-info-cell:not(:last-child)::after {
            content:''; position:absolute; right:0; top:25%; height:50%; width:1px; background:rgba(255,255,255,0.06);
        }
        .pd-info-cell-ico {
            width:32px; height:32px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;
            background:rgba(255,255,255,0.06); color:rgba(255,255,255,0.4);
        }
        .pd-info-cell-lbl { font-size:0.6rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:rgba(255,255,255,0.3); margin-bottom:0.25rem; }
        .pd-info-cell-val { font-size:0.8125rem; font-weight:600; color:rgba(255,255,255,0.85); word-break:break-word; line-height:1.4; }
        .pd-info-cell-val.empty { color:rgba(255,255,255,0.2); font-style:italic; font-weight:400; }
        .pd-info-addr { grid-column:1 / -1; }

        /* ── Stat Pills ── */
        .pd-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:0.75rem; margin-bottom:1.5rem; }
        .pd-pill {
            background:#fff; border:1px solid #e2e8f0; border-radius:18px; padding:1.25rem 1.375rem;
            position:relative; overflow:hidden; transition:all 0.35s cubic-bezier(0.4,0,0.2,1);
        }
        .pd-pill:hover { transform:translateY(-4px); box-shadow:0 16px 48px rgba(0,0,0,0.08); border-color:transparent; }
        .pd-pill-accent {
            position:absolute; top:0; right:0; width:80px; height:80px; border-radius:50%;
            transform:translate(30%,-30%); opacity:0.07; pointer-events:none;
        }
        .pd-pill-accent.blue { background:#3b82f6; }
        .pd-pill-accent.green { background:#10b981; }
        .pd-pill-accent.red { background:#ef4444; }
        .pd-pill-accent.amber { background:#f59e0b; }
        .pd-pill-ico {
            width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            margin-bottom:0.875rem;
        }
        .pd-pill-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#3b82f6; }
        .pd-pill-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#059669; }
        .pd-pill-ico.red { background:linear-gradient(135deg,#fef2f2,#fecaca); color:#dc2626; }
        .pd-pill-ico.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); color:#d97706; }
        .pd-pill-val { font-size:1.625rem; font-weight:800; letter-spacing:-0.03em; line-height:1; margin-bottom:0.25rem; }
        .pd-pill-val.blue { color:#2563eb; }
        .pd-pill-val.green { color:#059669; }
        .pd-pill-val.red { color:#dc2626; }
        .pd-pill-val.amber { color:#d97706; }
        .pd-pill-lbl { font-size:0.6875rem; font-weight:600; color:#94a3b8; }

        /* ── Content Section ── */
        .pd-section {
            background:#fff; border:1px solid #e2e8f0; border-radius:20px; overflow:hidden;
            box-shadow:0 2px 8px rgba(0,0,0,0.03); margin-bottom:1.25rem;
            transition:box-shadow 0.3s;
        }
        .pd-section:hover { box-shadow:0 8px 32px rgba(0,0,0,0.06); }
        .pd-section-hdr {
            padding:1.125rem 1.5rem; display:flex; align-items:center; gap:0.875rem;
            border-bottom:1px solid #f1f5f9;
        }
        .pd-section-ico {
            width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center;
        }
        .pd-section-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#3b82f6; }
        .pd-section-ico.red { background:linear-gradient(135deg,#fef2f2,#fecaca); color:#dc2626; }
        .pd-section-title { font-size:0.9375rem; font-weight:700; color:#0f172a; }
        .pd-section-badge {
            margin-left:auto; padding:0.25rem 0.75rem; border-radius:99px;
            font-size:0.6875rem; font-weight:700; background:#f1f5f9; color:#64748b;
        }

        /* ── Table ── */
        .pd-tbl { width:100%; border-collapse:separate; border-spacing:0; }
        .pd-tbl-head th {
            padding:0.8125rem 1.5rem; font-size:0.625rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.08em; color:#94a3b8; text-align:left; background:#fafbfc; border-bottom:1px solid #f1f5f9;
        }
        .pd-tbl-body td {
            padding:0.9375rem 1.5rem; font-size:0.8125rem; color:#374151; border-bottom:1px solid #f8fafc; vertical-align:middle;
        }
        .pd-tbl-body tr { transition:background 0.15s; }
        .pd-tbl-body tr:last-child td { border-bottom:none; }
        .pd-tbl-body tr:hover td { background:linear-gradient(90deg,#fafcff,#f8fafc); }

        /* ── Inline Elements ── */
        .pd-faktur {
            display:inline-flex; padding:0.2rem 0.625rem; border-radius:6px; font-size:0.6875rem; font-weight:700;
            background:#f0f4ff; color:#4f46e5; letter-spacing:0.02em; font-family:monospace; border:1px solid #e0e7ff;
        }
        .pd-badge {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:600;
        }
        .pd-badge.lunas { background:#ecfdf5; color:#059669; }
        .pd-badge.belum_lunas { background:#fffbeb; color:#d97706; }
        .pd-badge.tunai { background:#f0f9ff; color:#0284c7; }
        .pd-badge-dot { width:5px; height:5px; border-radius:50%; }
        .pd-badge.lunas .pd-badge-dot { background:#10b981; }
        .pd-badge.belum_lunas .pd-badge-dot { background:#f59e0b; }
        .pd-badge.tunai .pd-badge-dot { background:#0284c7; }
        .pd-money { font-weight:700; letter-spacing:-0.01em; text-align:right; font-variant-numeric:tabular-nums; }
        .pd-money.green { color:#059669; }
        .pd-money.red { color:#dc2626; }
        .pd-money.default { color:#1e293b; }
        .pd-sales-name { font-weight:600; color:#1e293b; }
        .pd-date { color:#64748b; font-weight:500; }
        .pd-date-day { font-size:0.6875rem; color:#94a3b8; }

        /* ── Empty State ── */
        .pd-empty { padding:3rem 1.5rem; text-align:center; }
        .pd-empty-ico {
            width:64px; height:64px; margin:0 auto 1rem; border-radius:16px;
            display:flex; align-items:center; justify-content:center; font-size:1.75rem;
            background:#f8fafc; border:1px solid #f1f5f9;
        }
        .pd-empty-title { font-size:0.9375rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .pd-empty-txt { font-size:0.8125rem; color:#94a3b8; }

        @media(max-width:768px) {
            .pd-info-strip { grid-template-columns:1fr 1fr; }
            .pd-info-cell:nth-child(2)::after { display:none; }
            .pd-stats { grid-template-columns:1fr 1fr; }
            .pd-hero-top { flex-wrap:wrap; }
        }
        @media(max-width:640px) {
            .pd-info-strip { grid-template-columns:1fr; }
            .pd-info-cell::after { display:none !important; }
            .pd-stats { grid-template-columns:1fr; }
            .pd-hero-name { font-size:1.375rem; }
            .pd-hero-top { padding:1.5rem; }
        }
    </style>
    @endpush

    <div class="py-4">
        <div class="pd-page">

            {{-- Hero --}}
            <div class="pd-hero">
                <div class="pd-hero-top">
                    <a href="{{ route('minyak.pelanggan.index') }}" class="pd-hero-back">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <div class="pd-avatar">{{ strtoupper(substr($pelanggan->nama_toko, 0, 1)) }}</div>
                    <div class="pd-hero-info">
                        <div class="pd-hero-name">{{ $pelanggan->nama_toko }}</div>
                        <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; margin-top:0.5rem;">
                            <span class="pd-hero-code">{{ $pelanggan->kode_pelanggan }}</span>
                            <div class="pd-hero-tags">
                                <span class="pd-tag {{ $pelanggan->status }}">
                                    <span class="pd-tag-dot {{ $pelanggan->status }}"></span>
                                    {{ ucfirst($pelanggan->status) }}
                                </span>
                                <span class="pd-tag-tipe">{{ ucfirst($pelanggan->tipe) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info Strip --}}
                <div class="pd-info-strip">
                    <div class="pd-info-cell">
                        <div class="pd-info-cell-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <div class="pd-info-cell-lbl">Pemilik</div>
                            <div class="pd-info-cell-val">{{ $pelanggan->nama_pemilik }}</div>
                        </div>
                    </div>
                    <div class="pd-info-cell">
                        <div class="pd-info-cell-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <div class="pd-info-cell-lbl">No HP</div>
                            <div class="pd-info-cell-val {{ !$pelanggan->no_hp ? 'empty' : '' }}">{{ $pelanggan->no_hp ?: 'Belum diisi' }}</div>
                        </div>
                    </div>
                    <div class="pd-info-cell">
                        <div class="pd-info-cell-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <div class="pd-info-cell-lbl">Email</div>
                            <div class="pd-info-cell-val {{ !$pelanggan->email ? 'empty' : '' }}">{{ $pelanggan->email ?: 'Belum diisi' }}</div>
                        </div>
                    </div>
                    @if(! $isSalesRole)
                    <div class="pd-info-cell">
                        <div class="pd-info-cell-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div class="pd-info-cell-lbl">Limit Hutang</div>
                            <div class="pd-info-cell-val">Rp {{ number_format($pelanggan->limit_hutang, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="pd-info-cell pd-info-addr">
                        <div class="pd-info-cell-ico">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <div class="pd-info-cell-lbl">Alamat</div>
                            <div class="pd-info-cell-val {{ !$pelanggan->alamat ? 'empty' : '' }}">
                                @if($pelanggan->alamat)
                                    {{ $pelanggan->alamat }}
                                    <span style="color:rgba(255,255,255,0.35);"> — {{ $pelanggan->kecamatan }}{{ $pelanggan->kecamatan && $pelanggan->kota ? ', ' : '' }}{{ $pelanggan->kota }}{{ ($pelanggan->kecamatan || $pelanggan->kota) && $pelanggan->provinsi ? ', ' : '' }}{{ $pelanggan->provinsi }}</span>
                                @else
                                    Belum diisi
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="pd-stats">
                <div class="pd-pill">
                    <div class="pd-pill-accent blue"></div>
                    <div class="pd-pill-ico blue">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div class="pd-pill-val blue">{{ $pelanggan->penjualans->count() }}</div>
                    <div class="pd-pill-lbl">Total Transaksi</div>
                </div>
                @if(! $isSalesRole)
                <div class="pd-pill">
                    <div class="pd-pill-accent green"></div>
                    <div class="pd-pill-ico green">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="pd-pill-val green">Rp {{ number_format($pelanggan->penjualans->sum('total'), 0, ',', '.') }}</div>
                    <div class="pd-pill-lbl">Total Pembelian</div>
                </div>
                <div class="pd-pill">
                    <div class="pd-pill-accent red"></div>
                    <div class="pd-pill-ico red">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="pd-pill-val red">Rp {{ number_format($pelanggan->total_hutang, 0, ',', '.') }}</div>
                    <div class="pd-pill-lbl">Sisa Hutang</div>
                </div>
                @endif
                <div class="pd-pill">
                    <div class="pd-pill-accent amber"></div>
                    <div class="pd-pill-ico amber">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="pd-pill-val amber">{{ $pelanggan->kunjungans->count() }}</div>
                    <div class="pd-pill-lbl">Kunjungan</div>
                </div>
            </div>

            {{-- Foto Toko --}}
            @if($pelanggan->foto_toko || $pelanggan->foto_toko_dalam)
            <div class="pd-section" style="margin-bottom:1.5rem;">
                <div class="pd-section-hdr">
                    <div class="pd-section-ico" style="background:linear-gradient(135deg,#ec4899,#db2777); color:#fff;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="pd-section-title">Foto Toko</div>
                </div>
                <div style="padding:1.25rem;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        @if($pelanggan->foto_toko)
                        <div style="text-align:center;">
                            <div style="font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:0.05em;">Tampak Depan</div>
                            <img src="{{ asset('storage/' . $pelanggan->foto_toko) }}" alt="Foto Depan {{ $pelanggan->nama_toko }}" style="max-width:100%; max-height:300px; border-radius:12px; border:2px solid #e2e8f0; box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        </div>
                        @endif
                        @if($pelanggan->foto_toko_dalam)
                        <div style="text-align:center;">
                            <div style="font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:0.05em;">Tampak Dalam</div>
                            <img src="{{ asset('storage/' . $pelanggan->foto_toko_dalam) }}" alt="Foto Dalam {{ $pelanggan->nama_toko }}" style="max-width:100%; max-height:300px; border-radius:12px; border:2px solid #e2e8f0; box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Riwayat Penjualan --}}
            <div class="pd-section">
                <div class="pd-section-hdr">
                    <div class="pd-section-ico blue">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    </div>
                    <div class="pd-section-title">Riwayat Penjualan</div>
                    <span class="pd-section-badge">{{ $pelanggan->penjualans->count() }} transaksi</span>
                </div>
                @if($pelanggan->penjualans->count() > 0)
                    <div style="overflow-x:auto;">
                        <table class="pd-tbl">
                            <thead class="pd-tbl-head">
                                <tr>
                                    <th style="padding-left:1.5rem;">No Faktur</th>
                                    <th>Tanggal</th>
                                    <th>Sales</th>
                                    <th style="text-align:right;">Total</th>
                                    <th style="text-align:center; padding-right:1.5rem;">Tipe Bayar</th>
                                </tr>
                            </thead>
                            <tbody class="pd-tbl-body">
                                @foreach($pelanggan->penjualans->sortByDesc('created_at')->take(10) as $p)
                                    <tr>
                                        <td style="padding-left:1.5rem;"><span class="pd-faktur">{{ $p->no_faktur }}</span></td>
                                        <td>
                                            <div class="pd-date">{{ $p->created_at->format('d M Y') }}</div>
                                            <div class="pd-date-day">{{ $p->created_at->isoFormat('dddd') }}</div>
                                        </td>
                                        <td><span class="pd-sales-name">{{ $p->sales->nama ?? '-' }}</span></td>
                                        <td class="pd-money green">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                        <td style="text-align:center; padding-right:1.5rem;">
                                            @php $tb = $p->tipe_bayar ?? 'tunai'; @endphp
                                            <span class="pd-badge {{ $tb }}">
                                                <span class="pd-badge-dot"></span>
                                                {{ ucfirst($tb) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="pd-empty">
                        <div class="pd-empty-ico">🛒</div>
                        <div class="pd-empty-title">Belum Ada Transaksi</div>
                        <div class="pd-empty-txt">Riwayat penjualan akan muncul setelah ada transaksi</div>
                    </div>
                @endif
            </div>

            {{-- Hutang --}}
            @if(! $isSalesRole)
            <div class="pd-section">
                <div class="pd-section-hdr">
                    <div class="pd-section-ico red">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    </div>
                    <div class="pd-section-title">Hutang Piutang</div>
                    <span class="pd-section-badge">{{ $pelanggan->hutangs->count() }} catatan</span>
                </div>
                @if($pelanggan->hutangs->count() > 0)
                    <div style="overflow-x:auto;">
                        <table class="pd-tbl">
                            <thead class="pd-tbl-head">
                                <tr>
                                    <th style="padding-left:1.5rem;">No Faktur</th>
                                    <th>Jatuh Tempo</th>
                                    <th style="text-align:right;">Total</th>
                                    <th style="text-align:right;">Sisa</th>
                                    <th style="text-align:center; padding-right:1.5rem;">Status</th>
                                </tr>
                            </thead>
                            <tbody class="pd-tbl-body">
                                @foreach($pelanggan->hutangs->sortBy('jatuh_tempo') as $h)
                                    @php
                                        $isOverdue = \Carbon\Carbon::parse($h->jatuh_tempo)->isPast() && $h->status == 'belum_lunas';
                                    @endphp
                                    <tr>
                                        <td style="padding-left:1.5rem;"><span class="pd-faktur">{{ $h->penjualan->no_faktur ?? '-' }}</span></td>
                                        <td>
                                            <div class="pd-date" style="{{ $isOverdue ? 'color:#dc2626;font-weight:600;' : '' }}">
                                                {{ \Carbon\Carbon::parse($h->jatuh_tempo)->format('d M Y') }}
                                            </div>
                                            <div class="pd-date-day">{{ \Carbon\Carbon::parse($h->jatuh_tempo)->isoFormat('dddd') }}</div>
                                        </td>
                                        <td class="pd-money default">Rp {{ number_format($h->total_hutang, 0, ',', '.') }}</td>
                                        <td class="pd-money red">Rp {{ number_format($h->sisa, 0, ',', '.') }}</td>
                                        <td style="text-align:center; padding-right:1.5rem;">
                                            <span class="pd-badge {{ $h->status }}">
                                                <span class="pd-badge-dot"></span>
                                                {{ $h->status == 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="pd-empty">
                        <div class="pd-empty-ico">📋</div>
                        <div class="pd-empty-title">Tidak Ada Hutang</div>
                        <div class="pd-empty-txt">Pelanggan ini tidak memiliki catatan hutang</div>
                    </div>
                @endif
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
