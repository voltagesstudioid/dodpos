<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap');

        .ds-page { max-width:52rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .ds-nav { display:flex; align-items:center; gap:10px; margin-bottom:1.75rem; }
        .ds-back-btn {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            background:#fff; border:1.5px solid #e2e8f0; color:#64748b; text-decoration:none; transition:all 0.2s; flex-shrink:0;
        }
        .ds-back-btn:hover { background:#f8fafc; border-color:#cbd5e1; color:#2563eb; transform:translateX(-2px); }
        .ds-nav-text { font-size:0.8125rem; font-weight:600; color:#94a3b8; }
        .ds-nav-sep { color:#cbd5e1; }
        .ds-nav-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }
        .ds-code { margin-left:auto; font-size:0.75rem; font-weight:700; font-family:'JetBrains Mono',monospace; color:#64748b; background:#f1f5f9; padding:0.375rem 0.75rem; border-radius:8px; border:1px solid #e2e8f0; }

        .ds-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .ds-card-hdr { padding:1rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
        .ds-card-hdr.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .ds-card-hdr.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .ds-card-hdr.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .ds-card-hdr.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .ds-card-ico {
            width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .ds-card-ico svg { width:18px; height:18px; }
        .ds-card-ico.blue { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
        .ds-card-ico.green { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .ds-card-ico.purple { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }
        .ds-card-ico.amber { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
        .ds-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .ds-card-body { padding:1.375rem; }

        .ds-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .ds-grid3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; }
        .ds-item { display:flex; flex-direction:column; gap:0.25rem; }
        .ds-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
        .ds-val { font-size:0.875rem; font-weight:600; color:#0f172a; }
        .ds-val.mono { font-family:'JetBrains Mono',monospace; letter-spacing:0.02em; }
        .ds-val.money { color:#059669; }
        .ds-val.muted { color:#94a3b8; font-weight:500; }

        .ds-status {
            display:inline-flex; align-items:center; gap:6px;
            padding:0.3rem 0.875rem; border-radius:99px; font-size:0.75rem; font-weight:700; border:1px solid;
        }
        .ds-status.loading { background:#eff6ff; color:#2563eb; border-color:#bfdbfe; }
        .ds-status.proses { background:#fffbeb; color:#d97706; border-color:#fde68a; }
        .ds-status.selesai { background:#ecfdf5; color:#059669; border-color:#a7f3d0; }
        .ds-status-dot { width:7px; height:7px; border-radius:50%; }
        .ds-status-dot.loading { background:#3b82f6; }
        .ds-status-dot.proses { background:#f59e0b; }
        .ds-status-dot.selesai { background:#10b981; }

        .ds-desc { font-size:0.8125rem; color:#475569; line-height:1.65; background:#f8fafc; padding:0.875rem 1rem; border-radius:10px; border:1px solid #f1f5f9; }

        .ds-progress { margin-top:1rem; }
        .ds-progress-bar { height:8px; background:#e2e8f0; border-radius:99px; overflow:hidden; }
        .ds-progress-fill { height:100%; border-radius:99px; transition:width 0.5s; }
        .ds-progress-fill.blue { background:linear-gradient(90deg,#3b82f6,#2563eb); }
        .ds-progress-fill.green { background:linear-gradient(90deg,#10b981,#059669); }
        .ds-progress-fill.amber { background:linear-gradient(90deg,#f59e0b,#d97706); }
        .ds-progress-labels { display:flex; justify-content:space-between; margin-top:0.375rem; font-size:0.6875rem; font-weight:600; }
        .ds-progress-labels .sold { color:#059669; }
        .ds-progress-labels .remaining { color:#d97706; }

        .ds-actions { display:flex; gap:0.75rem; justify-content:flex-end; padding-top:0.5rem; }
        .ds-btn {
            display:inline-flex; align-items:center; gap:6px;
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.2s; border:1px solid transparent; font-family:inherit; cursor:pointer;
        }
        .ds-btn svg { width:15px; height:15px; }
        .ds-btn-edit { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 4px 14px rgba(37,99,235,0.25); }
        .ds-btn-edit:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(37,99,235,0.35); }
        .ds-btn-back { background:#fff; border-color:#e2e8f0; color:#64748b; }
        .ds-btn-back:hover { background:#f8fafc; color:#0f172a; }

        @media(max-width:640px) { .ds-grid2 { grid-template-columns:1fr; } .ds-grid3 { grid-template-columns:1fr; } }
    </style>
    @endpush

    <div class="ds-page">

        {{-- Breadcrumb --}}
        <nav class="ds-nav">
            <a href="{{ route('mineral.loading.index') }}" class="ds-back-btn">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <span class="ds-nav-text">Loading Harian</span>
            <span class="ds-nav-sep">/</span>
            <span class="ds-nav-crumb">Detail</span>
            <span class="ds-code">{{ $loading->tanggal->format('d/m/Y') }}</span>
        </nav>

        {{-- Card 1: Informasi Loading --}}
        <div class="ds-card">
            <div class="ds-card-hdr blue">
                <div class="ds-card-ico blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="ds-card-title">Informasi Loading</div>
            </div>
            <div class="ds-card-body">
                <div class="ds-grid2">
                    <div class="ds-item">
                        <span class="ds-lbl">Tanggal</span>
                        <span class="ds-val">{{ $loading->tanggal->format('d M Y') }}</span>
                    </div>
                    <div class="ds-item">
                        <span class="ds-lbl">Status</span>
                        <span class="ds-status {{ $loading->status }}">
                            <span class="ds-status-dot {{ $loading->status }}"></span>
                            {{ ucfirst($loading->status) }}
                        </span>
                    </div>
                    <div class="ds-item">
                        <span class="ds-lbl">Sales</span>
                        <span class="ds-val">{{ $loading->sales->nama ?? '-' }}</span>
                    </div>
                    <div class="ds-item">
                        <span class="ds-lbl">Kendaraan</span>
                        <span class="ds-val mono">{{ $loading->sales->no_kendaraan ?? '-' }}</span>
                    </div>
                    <div class="ds-item">
                        <span class="ds-lbl">Produk</span>
                        <span class="ds-val">{{ $loading->produk->nama ?? '-' }}</span>
                    </div>
                    <div class="ds-item">
                        <span class="ds-lbl">Dibuat Oleh</span>
                        <span class="ds-val">{{ $loading->creator->name ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Volume & Distribusi --}}
        <div class="ds-card">
            <div class="ds-card-hdr green">
                <div class="ds-card-ico green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
                <div class="ds-card-title">Volume & Distribusi</div>
            </div>
            <div class="ds-card-body">
                <div class="ds-grid3">
                    <div class="ds-item">
                        <span class="ds-lbl">Jumlah Loading</span>
                        <span class="ds-val mono" style="color:#2563eb;">{{ number_format($loading->jumlah_loading) }} {{ $loading->produk->satuan ?? '' }}</span>
                    </div>
                    <div class="ds-item">
                        <span class="ds-lbl">Terjual</span>
                        <span class="ds-val mono" style="color:#059669;">{{ number_format($loading->terjual) }} {{ $loading->produk->satuan ?? '' }}</span>
                    </div>
                    <div class="ds-item">
                        <span class="ds-lbl">Sisa Stok</span>
                        <span class="ds-val mono" style="color:#d97706;">{{ number_format($loading->sisa_stok) }} {{ $loading->produk->satuan ?? '' }}</span>
                    </div>
                </div>

                {{-- Progress bar --}}
                @if($loading->jumlah_loading > 0)
                @php
                    $pct = min(100, ($loading->terjual / $loading->jumlah_loading) * 100);
                    $barClass = $pct >= 80 ? 'green' : ($pct >= 40 ? 'blue' : 'amber');
                @endphp
                <div class="ds-progress">
                    <div class="ds-progress-bar">
                        <div class="ds-progress-fill {{ $barClass }}" style="width:{{ $pct }}%"></div>
                    </div>
                    <div class="ds-progress-labels">
                        <span class="sold">Terjual: {{ number_format($pct, 1) }}%</span>
                        <span class="remaining">Sisa: {{ number_format(100 - $pct, 1) }}%</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Card 3: Keterangan --}}
        @if($loading->keterangan)
        <div class="ds-card">
            <div class="ds-card-hdr purple">
                <div class="ds-card-ico purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div class="ds-card-title">Keterangan</div>
            </div>
            <div class="ds-card-body">
                <div class="ds-desc">{{ $loading->keterangan }}</div>
            </div>
        </div>
        @endif

        {{-- Actions --}}
        <div class="ds-actions">
            <a href="{{ route('mineral.loading.index') }}" class="ds-btn ds-btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali
            </a>
            <a href="{{ route('mineral.loading.edit', $loading->id) }}" class="ds-btn ds-btn-edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Loading
            </a>
        </div>

    </div>
</x-app-layout>
