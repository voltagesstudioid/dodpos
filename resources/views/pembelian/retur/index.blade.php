<x-app-layout>
    <x-slot name="header">Retur Pembelian</x-slot>

    <div class="page-container">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">❌ {{ session('error') }}</div>
        @endif

        {{-- Stats Row --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#e0e7ff;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">📋</div>
                <div>
                    <div style="font-size:1.75rem;font-weight:800;color:#4f46e5;">{{ number_format($stats['total']) }}</div>
                    <div style="font-size:0.8rem;color:#64748b;">Total Retur</div>
                </div>
            </div>
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">📝</div>
                <div>
                    <div style="font-size:1.75rem;font-weight:800;color:#d97706;">{{ number_format($stats['draft']) }}</div>
                    <div style="font-size:0.8rem;color:#64748b;">Draft</div>
                </div>
            </div>
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">👍</div>
                <div>
                    <div style="font-size:1.75rem;font-weight:800;color:#2563eb;">{{ number_format($stats['approved']) }}</div>
                    <div style="font-size:0.8rem;color:#64748b;">Disetujui</div>
                </div>
            </div>
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#dcfce7;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">✅</div>
                <div>
                    <div style="font-size:1.75rem;font-weight:800;color:#16a34a;">{{ number_format($stats['returned']) }}</div>
                    <div style="font-size:0.8rem;color:#64748b;">Selesai</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
                <div>
                    <div style="font-size:1rem;font-weight:700;color:#1e293b;">📦 Daftar Retur Pembelian</div>
                    <div style="font-size:0.75rem;color:#64748b;">Kelola pengembalian barang ke supplier</div>
                </div>
                @can('create_retur_pembelian')
                <a href="{{ route('pembelian.retur.create') }}" class="btn-primary">+ Buat Retur</a>
                @endcan
            </div>

            {{-- Filters --}}
            <div style="padding:1rem 1.5rem; background:#f8fafc; border-bottom:1px solid #f1f5f9; display:flex; gap:0.75rem; flex-wrap:wrap;">
                <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;width:100%;align-items:center;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor retur..." class="form-input" style="max-width:180px;">
                    <select name="status" class="form-input" style="max-width:140px;">
                        <option value="">Semua Status</option>
                        <option value="draft" @selected(request('status')=='draft')>Draft</option>
                        <option value="approved" @selected(request('status')=='approved')>Disetujui</option>
                        <option value="returned" @selected(request('status')=='returned')>Selesai</option>
                    </select>
                    <select name="supplier_id" class="form-input" style="max-width:180px;">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" @selected(request('supplier_id')==$s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input" style="max-width:135px;" title="Dari Tanggal">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input" style="max-width:135px;" title="Sampai Tanggal">
                    <button type="submit" class="btn-primary btn-sm">Filter</button>
                    @if(request('search') || request('status') || request('supplier_id') || request('date_from') || request('date_to'))
                        <a href="{{ route('pembelian.retur.index') }}" class="btn-secondary btn-sm">Reset</a>
                    @endif
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Retur</th>
                            <th>Supplier</th>
                            <th>PO Ref.</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $i => $r)
                        <tr>
                            <td class="text-muted">{{ $returns->firstItem() + $i }}</td>
                            <td style="font-weight:600;">{{ $r->return_number }}</td>
                            <td>{{ $r->supplier->name }}</td>
                            <td>
                                @if($r->purchaseOrder)
                                    <span class="badge badge-blue">{{ $r->purchaseOrder->po_number }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $r->return_date->format('d/m/Y') }}</td>
                            <td style="font-weight:600;">Rp {{ number_format($r->total_amount, 0, ',', '.') }}</td>
                            <td>{!! $r->status_badge !!}</td>
                                <td>
                                <a href="{{ route('pembelian.retur.show', $r) }}" class="btn-primary btn-sm">Detail</a>
                                @if($r->status === 'draft')
                                    @can('delete_retur_pembelian')
                                    <form action="{{ route('pembelian.retur.destroy', $r) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus retur ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger btn-sm">Hapus</button>
                                    </form>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" style="text-align:center;padding:2rem;color:#94a3b8;">Belum ada data retur.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($returns->hasPages())
                <div style="padding:1rem 1.5rem;">{{ $returns->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
