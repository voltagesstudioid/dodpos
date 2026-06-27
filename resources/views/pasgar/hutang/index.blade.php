@extends('layouts.app', ['title' => 'Hutang Pelanggan - Pasgar'])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .htg-page{font-family:'Plus Jakarta Sans',sans-serif;max-width:64rem;margin:0 auto;padding:1.25rem}
    .htg-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:.75rem}
    .htg-hdr-left{display:flex;align-items:center;gap:1rem}
    .htg-hdr-icon{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#4338ca);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(99,102,241,.25)}
    .htg-hdr-icon svg{width:26px;height:26px;stroke:#fff}
    .htg-hdr h1{font-size:1.35rem;font-weight:800;color:#1e1b4b;margin:0}
    .htg-hdr p{font-size:.8rem;color:#6366f1;margin:0;font-weight:600}

    /* Stats */
    .htg-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:.85rem;margin-bottom:1.5rem}
    .htg-stat{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:1.1rem 1.15rem;display:flex;align-items:center;gap:.85rem;box-shadow:0 1px 3px rgba(0,0,0,.04)}
    .htg-stat-ico{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .htg-stat-ico svg{width:20px;height:20px;stroke:#fff}
    .htg-stat-ico.indigo{background:linear-gradient(135deg,#6366f1,#4338ca)}
    .htg-stat-ico.amber{background:linear-gradient(135deg,#f59e0b,#d97706)}
    .htg-stat-ico.red{background:linear-gradient(135deg,#ef4444,#dc2626)}
    .htg-stat-ico.green{background:linear-gradient(135deg,#10b981,#059669)}
    .htg-stat-val{font-size:1.35rem;font-weight:800;line-height:1}
    .htg-stat-val.indigo{color:#4338ca}
    .htg-stat-val.amber{color:#d97706}
    .htg-stat-val.red{color:#dc2626}
    .htg-stat-val.green{color:#059669}
    .htg-stat-lbl{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#94a3b8;margin-top:2px}

    /* Toolbar */
    .htg-toolbar{display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1.25rem}
    .htg-search{flex:1;min-width:200px;padding:.6rem .85rem;border:1.5px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:.82rem;outline:none;background:#fff}
    .htg-search:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1)}
    .htg-filter-sel{padding:.6rem .85rem;border:1.5px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:.82rem;outline:none;background:#fff;min-width:140px}
    .htg-filter-sel:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1)}
    .htg-toolbar-btn{display:inline-flex;align-items:center;gap:.35rem;padding:.6rem 1rem;border-radius:10px;font-size:.78rem;font-weight:700;border:none;cursor:pointer;transition:all .2s;text-decoration:none;background:#6366f1;color:#fff}
    .htg-toolbar-btn:hover{background:#4f46e5}
    .htg-toolbar-btn.reset{background:#f1f5f9;color:#475569}
    .htg-toolbar-btn.reset:hover{background:#e2e8f0}

    /* Group cards */
    .htg-group{background:#fff;border:1px solid #e2e8f0;border-radius:16px;margin-bottom:.85rem;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04)}
    .htg-group-hdr{padding:.85rem 1.25rem;display:flex;align-items:center;gap:.75rem;cursor:pointer;background:#f8fafc;border-bottom:1px solid #f1f5f9;transition:background .15s}
    .htg-group-hdr:hover{background:#eef2ff}
    .htg-group-arrow{width:20px;height:20px;transition:transform .2s;flex-shrink:0;color:#6366f1}
    .htg-group.open .htg-group-arrow{transform:rotate(90deg)}
    .htg-group-name{font-size:.85rem;font-weight:700;color:#1e1b4b;flex:1}
    .htg-group-meta{font-size:.7rem;color:#94a3b8;font-weight:600}
    .htg-group-badge{font-size:.7rem;font-weight:700;padding:.2rem .65rem;border-radius:8px}
    .htg-group-badge.outstanding{background:#fef3c7;color:#92400e}
    .htg-group-badge.lunas{background:#d1fae5;color:#059669}
    .htg-group-body{display:none}
    .htg-group.open .htg-group-body{display:block}

    /* Hutang rows */
    .htg-row{padding:.85rem 1.25rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:1rem;flex-wrap:wrap}
    .htg-row:last-child{border-bottom:none}
    .htg-row-main{flex:1;min-width:200px}
    .htg-row-nomor{font-size:.78rem;font-weight:700;color:#1e1b4b;font-family:'JetBrains Mono',monospace}
    .htg-row-meta{font-size:.72rem;color:#94a3b8;margin-top:2px;display:flex;align-items:center;gap:.35rem;flex-wrap:wrap}
    .htg-row-jt{display:inline-flex;align-items:center;gap:.25rem;padding:.1rem .45rem;border-radius:5px;font-size:.65rem;font-weight:700}
    .htg-row-jt.overdue{background:#fef2f2;color:#dc2626}
    .htg-row-jt.soon{background:#fef3c7;color:#92400e}
    .htg-row-jt.ok{background:#f0fdf4;color:#059669}
    .htg-row-amounts{display:flex;gap:1.25rem;align-items:center}
    .htg-row-amt{text-align:right}
    .htg-row-amt-label{font-size:.62rem;font-weight:600;text-transform:uppercase;color:#94a3b8;letter-spacing:.04em}
    .htg-row-amt-value{font-size:.85rem;font-weight:800}
    .htg-row-amt-value.sisa{color:#dc2626}
    .htg-row-amt-value.dibayar{color:#059669}

    /* Status badges */
    .htg-status{display:inline-flex;align-items:center;gap:.3rem;padding:.22rem .65rem;border-radius:99px;font-size:.7rem;font-weight:700;border:1px solid}
    .htg-status.belum_lunas{background:#fef3c7;color:#92400e;border-color:#fde68a}
    .htg-status.lunas{background:#d1fae5;color:#059669;border-color:#a7f3d0}
    .htg-status.overdue{background:#fef2f2;color:#dc2626;border-color:#fecaca}

    /* Buttons */
    .htg-btn{display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .85rem;border-radius:8px;font-size:.75rem;font-weight:700;border:none;cursor:pointer;transition:all .2s;text-decoration:none}
    .htg-btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;box-shadow:0 2px 8px rgba(99,102,241,.2)}
    .htg-btn-primary:hover{box-shadow:0 4px 14px rgba(99,102,241,.35);transform:translateY(-1px)}
    .htg-btn-success{background:linear-gradient(135deg,#10b981,#059669);color:#fff;box-shadow:0 2px 8px rgba(16,185,129,.2)}
    .htg-btn-success:hover{box-shadow:0 4px 14px rgba(16,185,129,.35);transform:translateY(-1px)}
    .htg-btn svg{width:14px;height:14px;stroke:currentColor}

    /* Empty */
    .htg-empty{text-align:center;padding:3rem 1rem}
    .htg-empty-ico{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#f1f5f9,#e2e8f0);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem}
    .htg-empty-ico svg{width:40px;height:40px;stroke:#94a3b8}
    .htg-empty h3{font-size:1rem;font-weight:700;color:#475569;margin:0 0 .35rem}
    .htg-empty p{font-size:.8rem;color:#94a3b8;margin:0 0 .75rem;line-height:1.5}
    .htg-empty-hint{display:inline-flex;align-items:center;gap:.4rem;background:#eef2ff;color:#4338ca;padding:.45rem .85rem;border-radius:8px;font-size:.72rem;font-weight:600}

    /* Success/flash */
    .htg-flash{padding:.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:.82rem;font-weight:600}
    .htg-flash.success{background:#d1fae5;border:1px solid #a7f3d0;color:#065f46}
    .htg-flash.error{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}

    @media(max-width:640px){.htg-row{flex-direction:column;align-items:flex-start}.htg-row-amounts{width:100%;justify-content:space-between}.htg-stats{grid-template-columns:repeat(2,1fr)}.htg-toolbar{flex-direction:column}.htg-search{min-width:unset}}

    @media (max-width: 768px) {
        [class$="-grid"], [class*="-grid "] { grid-template-columns: repeat(2, 1fr) !important; }
    }
    @media (max-width: 480px) {
        [class$="-grid"], [class*="-grid "] { grid-template-columns: 1fr !important; }
    }
</style>
@endpush

@section('content')
<div class="htg-page">
    {{-- Header --}}
    <div class="htg-hdr">
        <div class="htg-hdr-left">
            <div class="htg-hdr-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div>
                <h1>Hutang Pelanggan</h1>
                <p>Kelola piutang pelanggan Pasukan Garuda</p>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="htg-flash success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="htg-flash error">{{ session('error') }}</div>
    @endif

    {{-- KPI Stats --}}
    <div class="htg-stats">
        <div class="htg-stat">
            <div class="htg-stat-ico indigo"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
            <div>
                <div class="htg-stat-val indigo">Rp {{ number_format($stats['total_outstanding'], 0, ',', '.') }}</div>
                <div class="htg-stat-lbl">Total Outstanding</div>
            </div>
        </div>
        <div class="htg-stat">
            <div class="htg-stat-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            <div>
                <div class="htg-stat-val amber">{{ $stats['belum_lunas'] }}</div>
                <div class="htg-stat-lbl">Belum Lunas</div>
            </div>
        </div>
        <div class="htg-stat">
            <div class="htg-stat-ico red"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
            <div>
                <div class="htg-stat-val red">{{ $stats['overdue'] }}</div>
                <div class="htg-stat-lbl">Overdue</div>
            </div>
        </div>
        <div class="htg-stat">
            <div class="htg-stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
            <div>
                <div class="htg-stat-val green">{{ $stats['lunas'] }}</div>
                <div class="htg-stat-lbl">Lunas</div>
            </div>
        </div>
    </div>

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('pasgar.hutang.index') }}" class="htg-toolbar">
        <input type="text" name="search" class="htg-search" placeholder="Cari pelanggan (nama, kode, pemilik)..." value="{{ $search ?? request('search') }}">
        <select name="status" class="htg-filter-sel" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="belum_lunas" {{ ($status ?? request('status')) === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
            <option value="overdue" {{ ($status ?? request('status')) === 'overdue' ? 'selected' : '' }}>Overdue</option>
            <option value="lunas" {{ ($status ?? request('status')) === 'lunas' ? 'selected' : '' }}>Lunas</option>
        </select>
        <button type="submit" class="htg-toolbar-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Cari
        </button>
        @if($search || $status)
            <a href="{{ route('pasgar.hutang.index') }}" class="htg-toolbar-btn reset">Reset</a>
        @endif
    </form>

    {{-- Hutang Groups --}}
    @forelse($grouped as $pelangganName => $hutangs)
    <div class="htg-group open" id="grp-{{ \Str::slug($pelangganName) }}">
        <div class="htg-group-hdr" onclick="toggleGroup('grp-{{ \Str::slug($pelangganName) }}')">
            <svg class="htg-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 6 15 12 9 18"/></svg>
            <div class="htg-group-name">{{ $pelangganName }}</div>
            @php
                $groupOutstanding = $hutangs->where('status', '!=', 'lunas')->sum('sisa');
                $groupCount = $hutangs->count();
                $groupOverdue = $hutangs->where('status', 'overdue')->count();
            @endphp
            <span class="htg-group-meta">{{ $groupCount }} transaksi</span>
            @if($groupOverdue > 0)
                <span class="htg-group-badge" style="background:#fef2f2;color:#dc2626">{{ $groupOverdue }} overdue</span>
            @endif
            @if($groupOutstanding > 0)
                <span class="htg-group-badge outstanding">Rp {{ number_format($groupOutstanding, 0, ',', '.') }}</span>
            @else
                <span class="htg-group-badge lunas">Lunas</span>
            @endif
        </div>
        <div class="htg-group-body">
            @foreach($hutangs as $htg)
            <div class="htg-row">
                <div class="htg-row-main">
                    <div class="htg-row-nomor">{{ $htg->penjualan->nomor_transaksi ?? '-' }}</div>
                    <div class="htg-row-meta">
                        <span>{{ $htg->created_at->format('d M Y') }}</span>
                        @if($htg->jatuh_tempo)
                            @php
                                $now = now()->startOfDay();
                                $jt = $htg->jatuh_tempo->copy()->startOfDay();
                                $daysDiff = (int) $now->diffInDays($jt);
                                $isPast = $now->greaterThan($jt);
                                $daysSigned = $isPast ? -$daysDiff : $daysDiff;
                                $jtClass = $daysSigned < 0 ? 'overdue' : ($daysSigned <= 3 ? 'soon' : 'ok');
                                $jtLabel = $daysSigned < 0 ? abs($daysSigned) . ' hari lalu' : ($daysSigned == 0 ? 'Hari ini' : $daysSigned . ' hari lagi');
                            @endphp
                            <span>&middot;</span>
                            <span class="htg-row-jt {{ $jtClass }}">
                                JT: {{ $htg->jatuh_tempo->format('d M') }} ({{ $jtLabel }})
                            </span>
                        @endif
                    </div>
                </div>
                <span class="htg-status {{ $htg->status }}">
                    @if($htg->status === 'overdue')
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    @endif
                    {{ $htg->status === 'belum_lunas' ? 'Belum Lunas' : ($htg->status === 'overdue' ? 'Overdue' : 'Lunas') }}
                </span>
                <div class="htg-row-amounts">
                    <div class="htg-row-amt">
                        <div class="htg-row-amt-label">Total</div>
                        <div class="htg-row-amt-value" style="color:#4338ca">Rp {{ number_format($htg->total_hutang, 0, ',', '.') }}</div>
                    </div>
                    <div class="htg-row-amt">
                        <div class="htg-row-amt-label">Dibayar</div>
                        <div class="htg-row-amt-value dibayar">Rp {{ number_format($htg->dibayar, 0, ',', '.') }}</div>
                    </div>
                    <div class="htg-row-amt">
                        <div class="htg-row-amt-label">Sisa</div>
                        <div class="htg-row-amt-value sisa">Rp {{ number_format($htg->sisa, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div style="display:flex;gap:.4rem">
                    <a href="{{ route('pasgar.hutang.show', $htg->id) }}" class="htg-btn htg-btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Detail
                    </a>
                    @if($htg->sisa > 0)
                    <a href="{{ route('pasgar.hutang.bayar', $htg->id) }}" class="htg-btn htg-btn-success">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        Bayar
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="htg-empty">
        <div class="htg-empty-ico">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <h3>{{ request()->hasAny(['search','status']) ? 'Tidak ada data yang cocok' : 'Belum ada data hutang pelanggan' }}</h3>
        <p>
            @if(request()->hasAny(['search','status']))
                Coba ubah kata kunci pencarian atau filter status.
            @else
                Hutang akan muncul otomatis saat penjualan dengan metode bayar hutang dicatat.
            @endif
        </p>
        @if(request()->hasAny(['search','status']))
            <a href="{{ route('pasgar.hutang.index') }}" class="htg-empty-hint">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                Reset Filter
            </a>
        @endif
    </div>
    @endforelse
</div>

@push('scripts')
<script>
function toggleGroup(id) {
    document.getElementById(id).classList.toggle('open');
}
</script>
@endpush
@endsection
