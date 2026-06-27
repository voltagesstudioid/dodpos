@extends('layouts.app', ['title' => 'Detail Loading ' . $loading->nomor_loading])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .ds-wrap { font-family: 'Plus Jakarta Sans', sans-serif; max-width: 900px; margin: 0 auto; padding: 1.25rem; }
    .ds-back { font-size: 0.78rem; color: #6366f1; text-decoration: none; font-weight: 700; margin-bottom: 1rem; display: inline-block; }
    .ds-back:hover { text-decoration: underline; }
    .ds-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .ds-header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #6366f1, #4338ca); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(79,70,229,0.25); flex-shrink: 0; }
    .ds-header-icon svg { width: 26px; height: 26px; stroke: #fff; }
    .ds-header h1 { font-size: 1.2rem; font-weight: 800; color: #1e1b4b; margin: 0; }
    .ds-header p { font-size: 0.78rem; color: #64748b; margin: 0; }
    .ds-status-pill { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.85rem; border-radius: 10px; font-size: 0.75rem; font-weight: 700; margin-left: auto; }
    .ds-status-pill.pending { background: #fef3c7; color: #92400e; }
    .ds-status-pill.preparing { background: #dbeafe; color: #1d4ed8; }
    .ds-status-pill.ready { background: #e0e7ff; color: #4338ca; }
    .ds-status-pill.picked_up { background: #dcfce7; color: #166534; }
    .ds-status-pill.loaded { background: #fef3c7; color: #92400e; }
    .ds-status-pill.completed { background: #f1f5f9; color: #475569; }
    .ds-status-pill.opnamed { background: #e0e7ff; color: #4338ca; }
    .ds-status-pill.rejected { background: #fee2e2; color: #991b1b; }

    .ds-card { background: #fff; border: 1px solid #e0e7ff; border-radius: 14px; padding: 1.25rem; margin-bottom: 1rem; }
    .ds-card-title { font-size: 0.82rem; font-weight: 700; color: #1e1b4b; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .ds-card-title .dot { width: 8px; height: 8px; border-radius: 50%; }
    .ds-card-title .dot.indigo { background: #6366f1; }
    .ds-card-title .dot.green { background: #22c55e; }
    .ds-card-title .dot.amber { background: #f59e0b; }

    .ds-info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 1rem; }
    .ds-info { }
    .ds-info-label { font-size: 0.68rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; }
    .ds-info-value { font-size: 0.85rem; color: #1e1b4b; font-weight: 700; margin-top: 0.15rem; }
    .ds-info-mono { font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: #4338ca; font-weight: 700; }
    .ds-sumber { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.68rem; font-weight: 700; }
    .ds-sumber.toko { background: #fef3c7; color: #92400e; }
    .ds-sumber.gudang { background: #e0e7ff; color: #4338ca; }
    .ds-sumber.grosir { background: #fce7f3; color: #9d174d; }
    .ds-sumber.mixed { background: #f0fdf4; color: #059669; }
    .ds-wh-badge { display: inline-block; padding: 0.15rem 0.45rem; border-radius: 5px; font-size: 0.65rem; font-weight: 700; background: #f0fdf4; color: #059669; }
    .ds-unit { font-size: 0.7rem; color: #94a3b8; font-weight: 600; }

    .ds-table { width: 100%; border-collapse: collapse; }
    .ds-table th { padding: 0.6rem 0.75rem; text-align: left; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.4px; background: #f8fafc; border-bottom: 2px solid #e0e7ff; }
    .ds-table td { padding: 0.65rem 0.75rem; font-size: 0.8rem; color: #334155; border-bottom: 1px solid #f1f5f9; }
    .ds-table tr:last-child td { border-bottom: none; }
    .ds-table .mono { font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #4338ca; }

    .ds-timeline { position: relative; padding-left: 2rem; }
    .ds-timeline::before { content: ''; position: absolute; left: 11px; top: 8px; bottom: 8px; width: 2px; background: #e0e7ff; }
    .ds-step { position: relative; padding-bottom: 1.25rem; }
    .ds-step:last-child { padding-bottom: 0; }
    .ds-dot { position: absolute; left: -2rem; top: 2px; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; }
    .ds-dot.done { background: #6366f1; color: #fff; }
    .ds-dot.active { background: #fff; border: 3px solid #6366f1; }
    .ds-dot.pending { background: #f1f5f9; border: 2px solid #cbd5e1; }
    .ds-dot.rejected { background: #fee2e2; border: 2px solid #fca5a5; }
    .ds-step-title { font-size: 0.82rem; font-weight: 700; color: #1e1b4b; }
    .ds-step-title.done { color: #4338ca; }
    .ds-step-title.pending { color: #94a3b8; }
    .ds-step-meta { font-size: 0.72rem; color: #94a3b8; margin-top: 0.15rem; }

    .ds-action-card { border: 2px solid #e0e7ff; border-radius: 14px; padding: 1.25rem; margin-bottom: 1rem; }
    .ds-action-title { font-size: 0.85rem; font-weight: 700; color: #1e1b4b; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
    .ds-action-title .badge { font-size: 0.65rem; padding: 0.2rem 0.5rem; border-radius: 6px; font-weight: 700; }
    .ds-action-title .badge.supervisor { background: #fef3c7; color: #92400e; }
    .ds-action-title .badge.sales { background: #e0e7ff; color: #4338ca; }
    .ds-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.1rem; border-radius: 10px; font-size: 0.78rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; }
    .ds-btn-primary { background: linear-gradient(135deg, #6366f1, #4338ca); color: #fff; }
    .ds-btn-primary:hover { box-shadow: 0 4px 12px rgba(99,102,241,0.3); }
    .ds-btn-success { background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; }
    .ds-btn-success:hover { box-shadow: 0 4px 12px rgba(34,197,94,0.3); }
    .ds-btn-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .ds-btn-danger:hover { background: #fecaca; }
    .ds-btn-group { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem; }
    .ds-label { display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 0.3rem; }
    .ds-input { width: 100%; padding: 0.55rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.8rem; font-family: inherit; box-sizing: border-box; }
    .ds-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    .ds-textarea { width: 100%; padding: 0.55rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.8rem; font-family: inherit; resize: vertical; min-height: 60px; box-sizing: border-box; }

    .ds-approve-item { display: grid; grid-template-columns: 1fr 100px 100px; gap: 0.5rem; align-items: center; padding: 0.4rem 0; border-bottom: 1px solid #f1f5f9; font-size: 0.8rem; }
    .ds-approve-item:last-child { border-bottom: none; }

    @media (max-width: 768px) { .ds-info-grid { grid-template-columns: repeat(2, 1fr); } }
</style>
@endpush

@section('content')
<div class="ds-wrap">
    <a href="{{ route('pasgar.loading.index') }}" class="ds-back">← Kembali ke Daftar Loading</a>

    <div class="ds-header">
        <div class="ds-header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        </div>
        <div>
            <h1>{{ $loading->nomor_loading }}</h1>
            <p>{{ $loading->sales->nama ?? '-' }} &middot; {{ $loading->tanggal->format('d/m/Y') }}</p>
        </div>
        <span class="ds-status-pill {{ $loading->status }}">{{ $loading->status_icon }} {{ $loading->status_label }}</span>
        @if($loading->status === 'pending')
            @php
                $currentUser = auth()->user();
                $currentSales = $currentUser ? App\Models\PasgarSales::where('user_id', $currentUser->id)->first() : null;
                $canEdit = $currentSales && $loading->sales_id === $currentSales->id;
            @endphp
            @if($canEdit)
                <a href="{{ route('pasgar.loading.edit', $loading->id) }}" class="ds-btn" style="background:#e0e7ff;color:#4338ca;margin-left:0.5rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </a>
            @endif
        @endif
        
        <a href="{{ route('pasgar.loading.print', $loading->id) }}" target="_blank" class="ds-btn" style="background:#f1f5f9;color:#475569;margin-left:0.5rem;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Cetak
        </a>
    </div>

    @if(session('success'))
        <div style="background:#dcfce7;border:1px solid #86efac;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;color:#166534;font-size:0.8rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;color:#991b1b;font-size:0.8rem;">{{ session('error') }}</div>
    @endif

    {{-- Info --}}
    <div class="ds-card">
        <div class="ds-info-grid">
            <div class="ds-info">
                <div class="ds-info-label">Sumber</div>
                <div class="ds-info-value"><span class="ds-sumber {{ $loading->sumber }}">{{ $loading->sumber_label }}</span></div>
            </div>
            <div class="ds-info">
                <div class="ds-info-label">Lokasi</div>
                <div class="ds-info-value">{{ $loading->warehouse->name ?? '-' }}</div>
            </div>
            <div class="ds-info">
                <div class="ds-info-label">Sales</div>
                <div class="ds-info-value">{{ $loading->sales->nama ?? '-' }}</div>
            </div>
            <div class="ds-info">
                <div class="ds-info-label">Jumlah Item</div>
                <div class="ds-info-mono">{{ $loading->items->count() }} produk ({{ $loading->items->sum('qty_diminta') }} {{ $loading->items->first()?->unitConversion?->unit?->name ?? 'pcs' }})</div>
            </div>
        </div>
        @if($loading->catatan)
        <div style="font-size:0.78rem;color:#64748b;padding-top:0.5rem;border-top:1px solid #f1f5f9;"><strong>Catatan:</strong> {{ $loading->catatan }}</div>
        @endif
    </div>

    {{-- Items --}}
    <div class="ds-card">
        <div class="ds-card-title"><div class="dot indigo"></div> Daftar Barang</div>
        <div style="overflow-x:auto">
        <table class="ds-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Sumber</th>
                    <th>Qty Diminta</th>
                    <th>Qty Disetujui</th>
                    <th>Qty Dikirim</th>
                    <th>Qty Terjual</th>
                    <th>Sisa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loading->items as $item)
                @php $unitName = $item->unitConversion?->unit?->name ?? 'pcs'; @endphp
                <tr>
                    <td>
                        <div style="font-weight:700;color:#1e1b4b">{{ $item->product->name ?? '-' }}</div>
                        <div style="font-size:0.68rem;color:#94a3b8">{{ $item->product->sku ?? '' }}</div>
                    </td>
                    <td>
                        <span class="ds-sumber {{ $item->sumber ?? 'gudang' }}">{{ $item->sumber_label }}</span>
                        @if($item->warehouse)
                            <div class="ds-wh-badge">{{ $item->warehouse->name }}</div>
                        @endif
                    </td>
                    <td class="mono">{{ $item->qty_diminta }} <span class="ds-unit">{{ $unitName }}</span></td>
                    <td class="mono">{{ $item->qty_disetujui }} <span class="ds-unit">{{ $unitName }}</span></td>
                    <td class="mono">{{ $item->qty_dikirim }} <span class="ds-unit">{{ $unitName }}</span></td>
                    <td class="mono">{{ $item->qty_terjual }} <span class="ds-unit">{{ $unitName }}</span></td>
                    <td class="mono" style="color:{{ $item->qty_sisa > 0 ? '#d97706' : '#64748b' }}">{{ $item->qty_sisa }} <span class="ds-unit">{{ $unitName }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

    {{-- Workflow Timeline --}}
    <div class="ds-card">
        <div class="ds-card-title"><div class="dot green"></div> Alur Workflow</div>
        <div class="ds-timeline">
            {{-- Step 1: Request --}}
            <div class="ds-step">
                <div class="ds-dot done">✓</div>
                <div class="ds-step-title done">Permintaan Diajukan</div>
                <div class="ds-step-meta">{{ $loading->created_at->format('d/m/Y H:i') }} &middot; {{ $loading->sales->nama ?? 'Sales' }}</div>
            </div>

            {{-- Step 2: Approved/Preparing --}}
            <div class="ds-step">
                @if($loading->approved_at)
                    @if($loading->status === 'rejected')
                        <div class="ds-dot rejected">✗</div>
                        <div class="ds-step-title" style="color:#991b1b;">Ditolak</div>
                        <div class="ds-step-meta">{{ $loading->approved_at->format('d/m/Y H:i') }} &middot; {{ $loading->approver->name ?? 'Admin' }}</div>
                    @else
                        <div class="ds-dot done">✓</div>
                        <div class="ds-step-title done">Disetujui — Mulai Persiapan</div>
                        <div class="ds-step-meta">{{ $loading->approved_at->format('d/m/Y H:i') }} &middot; {{ $loading->approver->name ?? 'Admin' }}</div>
                    @endif
                @else
                    <div class="ds-dot {{ $loading->status === 'pending' ? 'active' : 'pending' }}"></div>
                    <div class="ds-step-title {{ $loading->status === 'pending' ? '' : 'pending' }}">Menunggu Approval</div>
                    <div class="ds-step-meta">Menunggu admin menyetujui permintaan</div>
                @endif
            </div>

            {{-- Step 3: Preparing --}}
            <div class="ds-step">
                @if($loading->prepared_at)
                    <div class="ds-dot done">✓</div>
                    <div class="ds-step-title done">Barang Disiapkan</div>
                    <div class="ds-step-meta">{{ $loading->prepared_at->format('d/m/Y H:i') }} &middot; {{ $loading->preparer->name ?? 'Admin' }}</div>
                @else
                    <div class="ds-dot {{ $loading->status === 'preparing' ? 'active' : 'pending' }}"></div>
                    <div class="ds-step-title {{ $loading->status === 'preparing' ? '' : 'pending' }}">Persiapan Barang</div>
                    <div class="ds-step-meta">Admin menyiapkan barang yang diminta</div>
                @endif
            </div>

            {{-- Step 4: Ready --}}
            <div class="ds-step">
                @if($loading->ready_at)
                    <div class="ds-dot done">✓</div>
                    <div class="ds-step-title done">Siap Dijemput</div>
                    <div class="ds-step-meta">{{ $loading->ready_at->format('d/m/Y H:i') }} &middot; {{ $loading->confirmer->name ?? 'Admin' }}</div>
                @else
                    <div class="ds-dot pending"></div>
                    <div class="ds-step-title pending">Konfirmasi Siap Jemput</div>
                    <div class="ds-step-meta">Admin mengkonfirmasi barang siap dijemput</div>
                @endif
            </div>

            {{-- Step 5: Picked Up --}}
            <div class="ds-step">
                @if($loading->picked_up_at)
                    <div class="ds-dot done">✓</div>
                    <div class="ds-step-title done">Dijemput & Cross-Check</div>
                    <div class="ds-step-meta">{{ $loading->picked_up_at->format('d/m/Y H:i') }} &middot; {{ $loading->pickedUpByUser->name ?? 'Sales' }}</div>
                    @if($loading->cross_check_notes)
                        <div class="ds-step-meta" style="margin-top:0.3rem;"><em>Catatan cross-check: {{ $loading->cross_check_notes }}</em></div>
                    @endif
                @else
                    <div class="ds-dot {{ $loading->status === 'ready' ? 'active' : 'pending' }}"></div>
                    <div class="ds-step-title {{ $loading->status === 'ready' ? '' : 'pending' }}">Penjemputan & Cross-Check</div>
                    <div class="ds-step-meta">Sales menjemput dan memverifikasi barang</div>
                @endif
            </div>

            {{-- Step 6: Loaded into Vehicle --}}
            <div class="ds-step">
                @if($loading->loaded_at)
                    <div class="ds-dot done">✓</div>
                    <div class="ds-step-title done">Dimuat ke Kendaraan</div>
                    <div class="ds-step-meta">{{ $loading->loaded_at->format('d/m/Y H:i') }} &middot; {{ $loading->loadedByUser->name ?? 'Sales' }}</div>
                @else
                    <div class="ds-dot {{ $loading->status === 'picked_up' ? 'active' : 'pending' }}"></div>
                    <div class="ds-step-title {{ $loading->status === 'picked_up' ? '' : 'pending' }}">Muat Barang ke Kendaraan</div>
                    <div class="ds-step-meta">Sales memuat barang ke kendaraan untuk berjualan</div>
                @endif
            </div>

            {{-- Step 7: Completed --}}
            <div class="ds-step">
                @if($loading->status === 'completed' || $loading->status === 'opnamed')
                    <div class="ds-dot done">✓</div>
                    <div class="ds-step-title done">{{ $loading->status === 'opnamed' ? 'Selesai (Opname)' : 'Selesai (Setoran)' }}</div>
                    <div class="ds-step-meta">{{ $loading->status === 'opnamed' ? 'Loading ditutup setelah opname barang sisa' : 'Loading selesai setelah setoran terverifikasi' }}</div>
                @else
                    <div class="ds-dot pending"></div>
                    <div class="ds-step-title pending">Selesai (Setoran/Opname)</div>
                    <div class="ds-step-meta">Menunggu penjualan dan setoran atau opname barang sisa</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Action Panels --}}
    @php
        $user = auth()->user();
        $isSupervisor = in_array($user->role, ['admin4', 'supervisor']);
        $isSalesOwner = $loading->sales && $loading->sales->user_id === $user->id;
    @endphp

    {{-- Supervisor: Approve/Reject (pending) --}}
    @if($isSupervisor && $loading->status === 'pending')
    <div class="ds-action-card">
        <div class="ds-action-title"><span class="badge supervisor">Admin</span> Approval Permintaan</div>
        <p style="font-size:0.78rem;color:#64748b;margin-bottom:0.75rem;">Setujui permintaan dan tentukan jumlah barang yang disetujui, atau tolak.</p>
        <form action="{{ route('pasgar.loading.approve', $loading->id) }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="approve" id="approveAction">
            @foreach($loading->items as $item)
            @php $unitName = $item->unitConversion?->unit?->name ?? 'pcs'; @endphp
            <div class="ds-approve-item">
                <span>{{ $item->product->name ?? '-' }} <span class="ds-unit">({{ $item->sumber_label }}{{ $item->warehouse ? ' — ' . $item->warehouse->name : '' }})</span></span>
                <span style="font-size:0.72rem;color:#94a3b8;">Diminta: {{ $item->qty_diminta }} {{ $unitName }}</span>
                <input type="number" name="items[{{ $item->id }}][qty_disetujui]" class="ds-input" value="{{ $item->qty_diminta }}" min="0" max="{{ $item->qty_diminta }}" placeholder="Setujui">
            </div>
            @endforeach
            <div class="ds-btn-group">
                <button type="submit" class="ds-btn ds-btn-success" onclick="document.getElementById('approveAction').value='approve'">✓ Setujui & Siapkan</button>
                <button type="submit" class="ds-btn ds-btn-danger" onclick="document.getElementById('approveAction').value='reject'">✗ Tolak</button>
            </div>
        </form>
    </div>
    @endif

    {{-- Supervisor: Confirm Ready (preparing) --}}
    @if($isSupervisor && $loading->status === 'preparing')
    <div class="ds-action-card">
        <div class="ds-action-title"><span class="badge supervisor">Admin</span> Konfirmasi Barang Siap</div>
        <p style="font-size:0.78rem;color:#64748b;margin-bottom:0.75rem;">Setelah barang disiapkan, konfirmasi jumlah yang dikirim dan tandai siap dijemput.</p>
        <form action="{{ route('pasgar.loading.confirmReady', $loading->id) }}" method="POST">
            @csrf
            @foreach($loading->items as $item)
            @php $unitName = $item->unitConversion?->unit?->name ?? 'pcs'; @endphp
            <div class="ds-approve-item">
                <span>{{ $item->product->name ?? '-' }} <span class="ds-unit">({{ $item->sumber_label }}{{ $item->warehouse ? ' — ' . $item->warehouse->name : '' }})</span></span>
                <span style="font-size:0.72rem;color:#94a3b8;">Disetujui: {{ $item->qty_disetujui }} {{ $unitName }}</span>
                <input type="number" name="items[{{ $item->id }}][qty_dikirim]" class="ds-input" value="{{ $item->qty_disetujui }}" min="0" max="{{ $item->qty_disetujui }}" placeholder="Qty dikirim">
            </div>
            @endforeach
            <div class="ds-btn-group">
                <button type="submit" class="ds-btn ds-btn-success">✓ Barang Siap Dijemput</button>
            </div>
        </form>
    </div>
    @endif

    {{-- Sales: Pickup (ready) --}}
    @if($isSalesOwner && $loading->status === 'ready')
    <div class="ds-action-card">
        <div class="ds-action-title"><span class="badge sales">Sales</span> Penjemputan & Cross-Check</div>
        <p style="font-size:0.78rem;color:#64748b;margin-bottom:0.75rem;">Verifikasi barang yang diterima sesuai dengan qty dikirim. Sesuaikan jumlah jika ada barang yang kurang.</p>
        <form action="{{ route('pasgar.loading.pickup', $loading->id) }}" method="POST">
            @csrf
            @foreach($loading->items as $item)
            @php $unitName = $item->unitConversion?->unit?->name ?? 'pcs'; @endphp
            <div class="ds-approve-item">
                <span>{{ $item->product->name ?? '-' }}</span>
                <span style="font-size:0.72rem;color:#94a3b8;">Dikirim: {{ $item->qty_dikirim }} {{ $unitName }}</span>
                <input type="number" name="items[{{ $item->id }}][qty_diterima]" class="ds-input" value="{{ $item->qty_dikirim }}" min="0" max="{{ $item->qty_dikirim }}" placeholder="Qty diterima">
            </div>
            @endforeach
            <div style="margin:0.75rem 0;">
                <label class="ds-label">Catatan Cross-Check (opsional)</label>
                <textarea name="cross_check_notes" class="ds-textarea" placeholder="Contoh: Semua barang sesuai, tidak ada yang rusak..."></textarea>
            </div>
            <div class="ds-btn-group">
                <button type="submit" class="ds-btn ds-btn-primary">🚗 Konfirmasi Jemput & Cross-Check</button>
            </div>
        </form>
    </div>
    @endif

    {{-- Sales: Load into Vehicle (picked_up) --}}
    @if($isSalesOwner && $loading->status === 'picked_up')
    <div class="ds-action-card">
        <div class="ds-action-title"><span class="badge sales">Sales</span> Muat Barang ke Kendaraan</div>
        <p style="font-size:0.78rem;color:#64748b;margin-bottom:0.75rem;">Barang sudah diverifikasi. Muat ke kendaraan untuk mulai berjualan.</p>
        <form action="{{ route('pasgar.loading.loadVehicle', $loading->id) }}" method="POST">
            @csrf
            <div style="margin-bottom:0.75rem;">
                <label class="ds-label">Catatan Muatan (opsional)</label>
                <textarea name="catatan_muat" class="ds-textarea" placeholder="Contoh: Semua barang sudah masuk kendaraan, siap berangkat..."></textarea>
            </div>
            <div class="ds-btn-group">
                <button type="submit" class="ds-btn ds-btn-primary">🚚 Konfirmasi Muat ke Kendaraan</button>
            </div>
        </form>
    </div>
    @endif

    {{-- Sales: Create Penjualan (loaded) --}}
    @if($isSalesOwner && $loading->status === 'loaded')
    <div class="ds-action-card" style="border-color:#bbf7d0;">
        <div class="ds-action-title" style="color:#059669;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            Mulai Berjualan
        </div>
        <p style="font-size:0.78rem;color:#64748b;margin-bottom:0.75rem;">Barang sudah dimuat ke kendaraan. Buat transaksi penjualan untuk pelanggan.</p>
        <div class="ds-btn-group">
            <a href="{{ route('pasgar.penjualan.create') }}" class="ds-btn" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Buat Transaksi Penjualan
            </a>
        </div>

        @if($loading->penjualans->count() > 0)
        <div style="margin-top:1rem;border-top:1px solid #e2e8f0;padding-top:0.75rem;">
            <div style="font-size:0.72rem;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:0.5rem;">Riwayat Penjualan</div>
            @foreach($loading->penjualans as $pj)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.4rem 0;border-bottom:1px solid #f1f5f9;">
                <div>
                    <span style="font-family:monospace;font-size:0.72rem;font-weight:700;color:#059669;">{{ $pj->nomor_transaksi }}</span>
                    <span style="font-size:0.68rem;color:#94a3b8;margin-left:0.5rem;">{{ $pj->tanggal->format('d/m/Y H:i') }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <span style="font-weight:700;color:#059669;font-size:0.8rem;">Rp {{ number_format($pj->total, 0, ',', '.') }}</span>
                    <a href="{{ route('pasgar.penjualan.show', $pj->id) }}" style="font-size:0.68rem;color:#059669;text-decoration:none;font-weight:600;">Detail</a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
