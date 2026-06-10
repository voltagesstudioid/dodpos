@extends('layouts.app', ['title' => 'Regional Kerja Pasgar'])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .pr-page { font-family: 'Plus Jakarta Sans', sans-serif; }

    .pr-kpis { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1.5rem; }
    .pr-kpi { background: #fff; border: 1px solid #e0e7ff; border-radius: 14px; padding: 1.1rem 1.25rem; }
    .pr-kpi-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem; }
    .pr-kpi-lbl { font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.4px; }
    .pr-kpi-ico { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; }
    .pr-kpi-ico.indigo { background: #eef2ff; }
    .pr-kpi-ico.green { background: #dcfce7; }
    .pr-kpi-ico.slate { background: #f1f5f9; }
    .pr-kpi-val { font-size: 1.75rem; font-weight: 800; color: #1e1b4b; line-height: 1; margin-bottom: 0.2rem; }
    .pr-kpi-val.indigo { color: #4f46e5; }
    .pr-kpi-val.green { color: #16a34a; }
    .pr-kpi-val.slate { color: #475569; }
    .pr-kpi-foot { font-size: 0.68rem; color: #94a3b8; font-weight: 600; }

    .pr-filter { background: #fff; border: 1px solid #e0e7ff; border-radius: 14px; padding: 1rem 1.25rem; margin-bottom: 1rem; display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap; }
    .pr-filter-fg { display: flex; flex-direction: column; gap: 0.25rem; }
    .pr-filter-lbl { font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px; }
    .pr-filter-inp { padding: 0.5rem 0.75rem; border: 1.5px solid #e0e7ff; border-radius: 9px; font-size: 0.8rem; font-family: inherit; color: #1e293b; background: #f8fafc; }
    .pr-filter-inp:focus { outline: none; border-color: #6366f1; background: #fff; }
    .pr-filter-btn { padding: 0.5rem 1rem; border-radius: 9px; font-size: 0.78rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.15s; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; }
    .pr-filter-btn.search { background: linear-gradient(135deg, #6366f1, #4338ca); color: #fff; }
    .pr-filter-btn.reset { background: #f1f5f9; color: #64748b; }

    .pr-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .pr-row { background: #fff; border: 1px solid #e0e7ff; border-radius: 14px; padding: 1.1rem 1.35rem; display: flex; align-items: center; gap: 1rem; transition: border-color 0.15s, box-shadow 0.15s; }
    .pr-row:hover { border-color: #a5b4fc; box-shadow: 0 4px 16px rgba(99,102,241,0.08); }
    .pr-ico { width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #6366f1, #4338ca); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(99,102,241,0.2); }
    .pr-info { flex: 1; }
    .pr-name { font-size: 1rem; font-weight: 800; color: #1e1b4b; margin-bottom: 0.15rem; }
    .pr-code { font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; font-weight: 700; color: #6366f1; background: #eef2ff; padding: 0.15rem 0.5rem; border-radius: 6px; display: inline-block; }
    .pr-desc { font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; }
    .pr-meta { display: flex; align-items: center; gap: 1rem; flex-shrink: 0; }
    .pr-sales-count { text-align: center; }
    .pr-sales-num { font-size: 1.5rem; font-weight: 800; color: #4f46e5; line-height: 1; }
    .pr-sales-lbl { font-size: 0.65rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; }
    .pr-badge { padding: 0.25rem 0.7rem; border-radius: 99px; font-size: 0.68rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.3rem; }
    .pr-badge.aktif { background: #dcfce7; color: #166534; }
    .pr-badge.nonaktif { background: #f1f5f9; color: #64748b; }
    .pr-badge .dot { width: 6px; height: 6px; border-radius: 50%; }
    .pr-badge.aktif .dot { background: #22c55e; }
    .pr-badge.nonaktif .dot { background: #94a3b8; }
    .pr-act { display: flex; gap: 0.35rem; }
    .pr-act-btn { padding: 0.4rem 0.85rem; border-radius: 8px; font-size: 0.72rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem; transition: all 0.15s; }
    .pr-act-btn.edit { background: #eef2ff; color: #4f46e5; border: 1px solid #c7d2fe; }
    .pr-act-btn.edit:hover { background: #e0e7ff; }
    .pr-act-btn.del { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .pr-act-btn.del:hover { background: #fecaca; }

    .pr-empty { text-align: center; padding: 3rem 1rem; }
    .pr-empty-ico { font-size: 3rem; margin-bottom: 0.5rem; }
    .pr-empty-title { font-size: 1rem; font-weight: 700; color: #64748b; }
    .pr-empty-sub { font-size: 0.8rem; color: #94a3b8; margin-top: 0.25rem; }

    @media (max-width: 768px) { .pr-kpis { grid-template-columns: 1fr 1fr; } .pr-row { flex-wrap: wrap; } .pr-meta { width: 100%; justify-content: space-between; } }
</style>
@endpush

@section('content')
<div class="page-container pr-page">

    {{-- Header --}}
    <div class="ph">
        <div class="ph-left">
            <div class="ph-icon indigo">🗺️</div>
            <div>
                <h1 class="ph-title">Regional Kerja Pasgar</h1>
                <p class="ph-subtitle">Kelola area kerja sales Pasukan Garuda</p>
            </div>
        </div>
        <div class="ph-actions">
            <a href="{{ route('pasgar.regional.create') }}" class="btn-primary">➕ Tambah Regional</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- KPIs --}}
    <div class="pr-kpis">
        <div class="pr-kpi">
            <div class="pr-kpi-top"><span class="pr-kpi-lbl">Total Regional</span><div class="pr-kpi-ico indigo">🗺️</div></div>
            <div class="pr-kpi-val indigo">{{ $stats['total'] }}</div>
            <div class="pr-kpi-foot">Semua area kerja</div>
        </div>
        <div class="pr-kpi">
            <div class="pr-kpi-top"><span class="pr-kpi-lbl">Aktif</span><div class="pr-kpi-ico green">✅</div></div>
            <div class="pr-kpi-val green">{{ $stats['aktif'] }}</div>
            <div class="pr-kpi-foot">Sedang beroperasi</div>
        </div>
        <div class="pr-kpi">
            <div class="pr-kpi-top"><span class="pr-kpi-lbl">Nonaktif</span><div class="pr-kpi-ico slate">⏸️</div></div>
            <div class="pr-kpi-val slate">{{ $stats['nonaktif'] }}</div>
            <div class="pr-kpi-foot">Tidak beroperasi</div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('pasgar.regional.index') }}" class="pr-filter">
        <div class="pr-filter-fg" style="flex:1;">
            <label class="pr-filter-lbl">Cari Regional</label>
            <input type="text" name="search" class="pr-filter-inp" value="{{ request('search') }}" placeholder="Nama atau kode regional...">
        </div>
        <div class="pr-filter-fg">
            <label class="pr-filter-lbl">Status</label>
            <select name="status" class="pr-filter-inp">
                <option value="">Semua</option>
                <option value="aktif" {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                <option value="nonaktif" {{ request('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
            </select>
        </div>
        <button type="submit" class="pr-filter-btn search">Cari</button>
        <a href="{{ route('pasgar.regional.index') }}" class="pr-filter-btn reset">Reset</a>
    </form>

    {{-- List --}}
    @if($regionals->count() > 0)
        <div class="pr-list">
            @foreach($regionals as $rg)
            <div class="pr-row">
                <div class="pr-ico">🗺️</div>
                <div class="pr-info">
                    <div class="pr-name">{{ $rg->nama }}</div>
                    <span class="pr-code">{{ $rg->kode_regional }}</span>
                    @if($rg->deskripsi)<div class="pr-desc">{{ $rg->deskripsi }}</div>@endif
                </div>
                <div class="pr-meta">
                    <div class="pr-sales-count">
                        <div class="pr-sales-num">{{ $rg->sales_count }}</div>
                        <div class="pr-sales-lbl">Sales</div>
                    </div>
                    <span class="pr-badge {{ $rg->status }}"><span class="dot"></span>{{ ucfirst($rg->status) }}</span>
                    <div class="pr-act">
                        <a href="{{ route('pasgar.regional.edit', $rg) }}" class="pr-act-btn edit">✏️ Edit</a>
                        <form method="POST" action="{{ route('pasgar.regional.destroy', $rg) }}" onsubmit="return confirm('Yakin hapus regional ini?');" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="pr-act-btn del">🗑️</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div style="margin-top:1rem;">{{ $regionals->links('pagination::simple-tailwind') }}</div>
    @else
        <div class="pr-empty">
            <div class="pr-empty-ico">🗺️</div>
            <div class="pr-empty-title">Belum ada regional</div>
            <div class="pr-empty-sub">Tambahkan regional kerja untuk mengatur area sales Pasgar.</div>
        </div>
    @endif

</div>
@endsection
