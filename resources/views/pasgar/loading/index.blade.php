@extends('layouts.app', ['title' => 'Loading Barang - Pasgar'])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .li-wrap { font-family: 'Plus Jakarta Sans', sans-serif; max-width: 1100px; margin: 0 auto; padding: 1.25rem; }
    .li-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem; }
    .li-header-left { display: flex; align-items: center; gap: 1rem; }
    .li-header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #6366f1, #4338ca); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(79,70,229,0.25); }
    .li-header-icon svg { width: 26px; height: 26px; stroke: #fff; }
    .li-header h1 { font-size: 1.35rem; font-weight: 800; color: #1e1b4b; margin: 0; }
    .li-header p { font-size: 0.8rem; color: #6366f1; margin: 0; font-weight: 600; }
    .li-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.1rem; border-radius: 10px; font-size: 0.78rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .li-btn-primary { background: linear-gradient(135deg, #6366f1, #4338ca); color: #fff; box-shadow: 0 3px 10px rgba(79,70,229,0.2); }
    .li-btn-primary:hover { box-shadow: 0 5px 16px rgba(79,70,229,0.3); transform: translateY(-1px); }
    .li-btn svg { width: 16px; height: 16px; stroke: currentColor; }

    .li-filters { display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap; }
    .li-filter { padding: 0.4rem 0.9rem; border-radius: 8px; font-size: 0.72rem; font-weight: 700; text-decoration: none; border: 1px solid #e0e7ff; background: #fff; color: #64748b; transition: all 0.15s; }
    .li-filter:hover { border-color: #6366f1; color: #4338ca; }
    .li-filter.active { background: #6366f1; color: #fff; border-color: #6366f1; }

    .li-card { background: #fff; border: 1px solid #e0e7ff; border-radius: 14px; overflow: hidden; }
    .li-table { width: 100%; border-collapse: collapse; }
    .li-table th { padding: 0.85rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; background: #f8fafc; border-bottom: 2px solid #e0e7ff; }
    .li-table td { padding: 0.85rem 1rem; font-size: 0.82rem; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .li-table tr:last-child td { border-bottom: none; }
    .li-table tr:hover td { background: #f8fafc; }

    .li-mono { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; font-weight: 700; color: #4338ca; }
    .li-link { color: #6366f1; text-decoration: none; font-weight: 700; font-size: 0.78rem; }
    .li-link:hover { text-decoration: underline; }

    .li-status { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: 8px; font-size: 0.68rem; font-weight: 700; }
    .li-status.pending { background: #fef3c7; color: #92400e; }
    .li-status.preparing { background: #dbeafe; color: #1d4ed8; }
    .li-status.ready { background: #e0e7ff; color: #4338ca; }
    .li-status.picked_up { background: #dcfce7; color: #166534; }
    .li-status.completed { background: #f1f5f9; color: #475569; }
    .li-status.rejected { background: #fee2e2; color: #991b1b; }

    .li-sumber { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.65rem; font-weight: 700; }
    .li-sumber.toko { background: #fef3c7; color: #92400e; }
    .li-sumber.gudang { background: #e0e7ff; color: #4338ca; }
    .li-sumber.grosir { background: #fce7f3; color: #9d174d; }
    .li-sumber.mixed { background: #f0fdf4; color: #059669; }

    .li-empty { text-align: center; padding: 3rem 1rem; color: #94a3b8; font-size: 0.85rem; }
    .li-pagination { padding: 1rem; display: flex; justify-content: center; }

    @media (max-width: 768px) {
        .li-table th:nth-child(4), .li-table td:nth-child(4) { display: none; }
        .li-table th:nth-child(5), .li-table td:nth-child(5) { display: none; }
    }
</style>
@endpush

@section('content')
<div class="li-wrap">
    <div class="li-header">
        <div class="li-header-left">
            <div class="li-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
            <div>
                <h1>Loading Barang</h1>
                <p>Permintaan & pengambilan barang untuk dijual</p>
            </div>
        </div>
        <a href="{{ route('pasgar.loading.create') }}" class="li-btn li-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Permintaan Baru
        </a>
    </div>

    @if(session('success'))
        <div style="background:#dcfce7;border:1px solid #86efac;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;color:#166534;font-size:0.8rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;color:#991b1b;font-size:0.8rem;">{{ session('error') }}</div>
    @endif

    <div class="li-filters">
        <a href="{{ route('pasgar.loading.index') }}" class="li-filter {{ !request('status') ? 'active' : '' }}">Semua</a>
        <a href="{{ route('pasgar.loading.index', ['status' => 'pending']) }}" class="li-filter {{ request('status') === 'pending' ? 'active' : '' }}">⏳ Menunggu</a>
        <a href="{{ route('pasgar.loading.index', ['status' => 'preparing']) }}" class="li-filter {{ request('status') === 'preparing' ? 'active' : '' }}">📦 Disiapkan</a>
        <a href="{{ route('pasgar.loading.index', ['status' => 'ready']) }}" class="li-filter {{ request('status') === 'ready' ? 'active' : '' }}">✅ Siap Jemput</a>
        <a href="{{ route('pasgar.loading.index', ['status' => 'picked_up']) }}" class="li-filter {{ request('status') === 'picked_up' ? 'active' : '' }}">🚗 Dijemput</a>
        <a href="{{ route('pasgar.loading.index', ['status' => 'completed']) }}" class="li-filter {{ request('status') === 'completed' ? 'active' : '' }}">🎉 Selesai</a>
    </div>

    <div class="li-card">
        <table class="li-table">
            <thead>
                <tr>
                    <th>No. Loading</th>
                    <th>Sales</th>
                    <th>Sumber</th>
                    <th>Items</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($loadings as $ld)
                <tr>
                    <td><span class="li-mono">{{ $ld->nomor_loading }}</span></td>
                    <td>{{ $ld->sales->nama ?? '-' }}</td>
                    <td><span class="li-sumber {{ $ld->sumber }}">{{ $ld->sumber_label }}</span></td>
                    <td>{{ $ld->items->count() }} item</td>
                    <td>{{ $ld->tanggal->format('d/m/Y') }}</td>
                    <td><span class="li-status {{ $ld->status }}">{{ $ld->status_icon }} {{ $ld->status_label }}</span></td>
                    <td><a href="{{ route('pasgar.loading.show', $ld->id) }}" class="li-link">Detail →</a></td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="li-empty">Belum ada data loading.</div></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($loadings->hasPages())
        <div class="li-pagination">{{ $loadings->links() }}</div>
        @endif
    </div>
</div>
@endsection
