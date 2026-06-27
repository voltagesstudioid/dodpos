@extends('layouts.app', ['title' => 'Opname - Pasgar'])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .op-wrap{font-family:'Plus Jakarta Sans',sans-serif;max-width:1100px;margin:0 auto;padding:1.25rem}
    .op-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:.75rem}
    .op-header-left{display:flex;align-items:center;gap:1rem}
    .op-header-icon{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(245,158,11,.25)}
    .op-header-icon svg{width:26px;height:26px;stroke:#fff}
    .op-header h1{font-size:1.35rem;font-weight:800;color:#78350f;margin:0}
    .op-header p{font-size:.8rem;color:#d97706;margin:0;font-weight:600}
    .op-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.6rem 1.1rem;border-radius:10px;font-size:.78rem;font-weight:700;text-decoration:none;border:none;cursor:pointer;transition:all .2s}
    .op-btn-primary{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;box-shadow:0 3px 10px rgba(245,158,11,.2)}
    .op-btn-primary:hover{box-shadow:0 5px 16px rgba(245,158,11,.3);transform:translateY(-1px)}
    .op-btn svg{width:16px;height:16px;stroke:currentColor}

    /* KPI */
    .op-kpi{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem}
    .op-kpi-card{background:#fff;border:1px solid #fef3c7;border-radius:14px;padding:1.15rem;display:flex;align-items:center;gap:.85rem}
    .op-kpi-ico{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .op-kpi-ico svg{width:22px;height:22px;stroke:#fff}
    .op-kpi-ico.amber{background:linear-gradient(135deg,#f59e0b,#d97706)}
    .op-kpi-ico.green{background:linear-gradient(135deg,#10b981,#059669)}
    .op-kpi-ico.blue{background:linear-gradient(135deg,#3b82f6,#2563eb)}
    .op-kpi-val{font-size:1.5rem;font-weight:800;color:#1e1b4b;line-height:1}
    .op-kpi-lbl{font-size:.72rem;color:#64748b;font-weight:600;margin-top:2px}

    /* Filters */
    .op-filters{display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1.25rem}
    .op-filter-input{padding:.5rem .75rem;border:1px solid #e5e7eb;border-radius:8px;font-size:.78rem;font-family:inherit;background:#fff;min-width:140px}
    .op-filter-input:focus{outline:none;border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.1)}

    /* Table */
    .op-table-wrap{background:#fff;border:1px solid #fef3c7;border-radius:14px;overflow:hidden}
    .op-table{width:100%;border-collapse:collapse}
    .op-table thead{background:#fffbeb}
    .op-table th{padding:.75rem 1rem;font-size:.72rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.5px;text-align:left;border-bottom:1px solid #fef3c7}
    .op-table td{padding:.75rem 1rem;font-size:.82rem;color:#374151;border-bottom:1px solid #f9fafb}
    .op-table tbody tr:hover{background:#fffbeb}
    .op-badge{display:inline-block;padding:.2rem .6rem;border-radius:99px;font-size:.7rem;font-weight:700}
    .op-badge.pending{background:#fef3c7;color:#92400e}
    .op-badge.confirmed{background:#d1fae5;color:#065f46}
    .op-action{display:inline-flex;align-items:center;gap:.3rem;padding:.35rem .65rem;border-radius:8px;font-size:.72rem;font-weight:600;text-decoration:none;border:none;cursor:pointer;transition:all .15s}
    .op-action.view{background:#ede9fe;color:#6d28d9}
    .op-action.view:hover{background:#ddd6fe}
    .op-empty{text-align:center;padding:3rem 1rem;color:#9ca3af;font-size:.85rem}

    @media(max-width:640px){.op-table-wrap{overflow-x:auto}.op-kpi{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<div class="op-wrap">
    @if(session('success'))
    <div style="background:#d1fae5;border:1px solid #a7f3d0;color:#065f46;padding:0.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;font-weight:600;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:0.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;font-weight:600;">{{ session('error') }}</div>
    @endif
    {{-- Header --}}
    <div class="op-header">
        <div class="op-header-left">
            <div class="op-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div>
                <h1>Opname Barang Sisa</h1>
                <p>Rekonsiliasi barang tidak terjual</p>
            </div>
        </div>
        <a href="{{ route('pasgar.opname.create') }}" class="op-btn op-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Buat Opname
        </a>
    </div>

    {{-- KPI --}}
    <div class="op-kpi">
        <div class="op-kpi-card">
            <div class="op-kpi-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            <div><div class="op-kpi-val">{{ $stats['pending'] }}</div><div class="op-kpi-lbl">Pending</div></div>
        </div>
        <div class="op-kpi-card">
            <div class="op-kpi-ico green"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
            <div><div class="op-kpi-val">{{ $stats['confirmed'] }}</div><div class="op-kpi-lbl">Terkonfirmasi</div></div>
        </div>
        <div class="op-kpi-card">
            <div class="op-kpi-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
            <div><div class="op-kpi-val">{{ number_format($stats['total_returned']) }}</div><div class="op-kpi-lbl">Total Barang Dikembalikan</div></div>
        </div>
    </div>

    {{-- Filters --}}
    <form class="op-filters" method="GET" action="{{ route('pasgar.opname.index') }}">
        <input type="text" name="search" class="op-filter-input" placeholder="Cari nomor / sales..." value="{{ request('search') }}">
        <select name="status" class="op-filter-input">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>Terkonfirmasi</option>
        </select>
        <input type="date" name="date" class="op-filter-input" value="{{ request('date') }}">
        <button type="submit" class="op-btn" style="background:#f59e0b;color:#fff;padding:.5rem 1rem">Filter</button>
        @if(request()->hasAny(['search','status','date']))
            <a href="{{ route('pasgar.opname.index') }}" class="op-btn" style="background:#f3f4f6;color:#374151;padding:.5rem 1rem">Reset</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="op-table-wrap">
        <div style="overflow-x: auto; margin-bottom: 1rem;">
<table class="op-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nomor Opname</th>
                    <th>Loading</th>
                    <th>Sales</th>
                    <th>Items</th>
                    <th>Total Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opnames as $op)
                <tr>
                    <td>{{ $op->tanggal->format('d M Y') }}</td>
                    <td style="font-weight:700;color:#92400e">{{ $op->nomor_opname }}</td>
                    <td>{{ $op->loading->nomor_loading ?? '-' }}</td>
                    <td>{{ $op->sales->nama ?? '-' }}</td>
                    <td>{{ $op->items->count() }}</td>
                    <td style="font-weight:700">{{ number_format($op->items->sum('qty_fisik')) }}</td>
                    <td><span class="op-badge {{ $op->status }}">{{ $op->status_label }}</span></td>
                    <td>
                        <a href="{{ route('pasgar.opname.show', $op->id) }}" class="op-action view">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="op-empty">Belum ada data opname.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    @if($opnames->hasPages())
        <div style="margin-top:1.25rem">{{ $opnames->links() }}</div>
    @endif
</div>
@endsection
