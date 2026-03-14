<x-app-layout>
    <x-slot name="header">Penerimaan Barang Gudang (Non-PO)</x-slot>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">❌ {{ session('error') }}</div> @endif

        {{-- Header --}}
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.25rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:1.4rem;font-weight:800;color:#0f172a;margin:0;display:flex;align-items:center;gap:.5rem;">
                    <span style="background:#d1fae5;padding:.4rem .6rem;border-radius:10px;font-size:1.2rem;">📥</span>
                    Penerimaan Barang — Gudang
                </h1>
                <p style="color:#64748b;font-size:.8rem;margin:.3rem 0 0;">
                    Stok masuk yang <strong>tidak terkait Purchase Order</strong> (retur, stok awal, koreksi, konsinyasi, transfer)
                </p>
            </div>
            <div style="display:flex;gap:.5rem;">
                <a href="{{ route('gudang.terimapo.index') }}" class="btn-secondary" style="font-size:.8rem;">🛒 Terima dari PO</a>
                @can('create_penerimaan_barang')
                <a href="{{ route('gudang.penerimaan.create') }}" class="btn-primary">+ Terima Barang</a>
                @endcan
            </div>
        </div>

        <div class="card">
            {{-- Filter --}}
            <div style="padding:.875rem 1.25rem;background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end;">
                    <div>
                        <div style="font-size:.7rem;font-weight:600;color:#64748b;margin-bottom:.25rem;">Cari</div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="No. referensi / produk..." class="form-input" style="max-width:220px;">
                    </div>
                    <div>
                        <div style="font-size:.7rem;font-weight:600;color:#64748b;margin-bottom:.25rem;">Sumber</div>
                        <select name="source_type" class="form-input" style="max-width:210px;">
                            <option value="">Semua</option>
                            @foreach($sourceTypes as $k => $label)
                                <option value="{{ $k }}" @selected(request('source_type')==$k)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex;gap:.5rem;align-items:flex-end;">
                        <button type="submit" class="btn-primary btn-sm">Filter</button>
                        <a href="{{ route('gudang.penerimaan') }}" class="btn-secondary btn-sm">Reset</a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>No. Referensi</th>
                            <th>Sumber Penerimaan</th>
                            <th>Produk</th>
                            <th>Gudang</th>
                            <th style="text-align:center;">Qty</th>
                            <th>Dicatat oleh</th>
                            <th>Catatan</th>
                            <th style="text-align:center; width:90px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movements as $m)
                        @php
                            $sourceBadge = match(optional($m)->source_type) {
                                'retur_pelanggan' => ['class'=>'badge-warning', 'label'=>'Retur Pelanggan'],
                                'stok_awal'       => ['class'=>'badge-purple',  'label'=>'Stok Awal'],
                                'koreksi'         => ['class'=>'badge-danger',  'label'=>'Koreksi'],
                                'transfer_masuk'  => ['class'=>'badge-blue',    'label'=>'Transfer Masuk'],
                                'konsinyasi'      => ['class'=>'badge-success', 'label'=>'Konsinyasi'],
                                default           => ['class'=>'badge-gray',    'label'=>'Lainnya'],
                            };
                        @endphp
                        <tr>
                            <td style="white-space:nowrap;font-size:.8rem;">
                                {{ $m->created_at->format('d/m/Y') }}<br>
                                <span style="color:#94a3b8;font-size:.72rem;">{{ $m->created_at->format('H:i') }}</span>
                            </td>
                            <td style="font-family:monospace;font-size:.8rem;color:#4f46e5;">{{ $m->reference_number }}</td>
                            <td>
                                <span class="badge {{ $sourceBadge['class'] }}">{{ $sourceBadge['label'] }}</span>
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:.85rem;">{{ $m->product?->name ?? '-' }}</div>
                                <div style="font-size:.65rem;color:#94a3b8;">{{ $m->product?->sku ?? '' }}</div>
                            </td>
                            <td style="font-size:.8rem;">{{ $m->warehouse?->name ?? '-' }}</td>
                            <td style="text-align:center;font-weight:800;color:#10b981;font-size:1rem;">+{{ $m->quantity }}</td>
                            <td style="font-size:.8rem;">{{ $m->user?->name ?? '-' }}</td>
                            <td style="font-size:.75rem;color:#64748b;max-width:200px;">{{ $m->notes ?? '-' }}</td>
                            <td style="text-align:center;">
                                <div style="display:flex; gap:0.25rem; justify-content:center;">
                                    <a href="{{ route('gudang.penerimaan.show', $m) }}" class="btn-secondary" style="padding:0.25rem 0.5rem; font-size:0.75rem;" title="Detail">
                                        👁️
                                    </a>
                                    @can('delete_penerimaan_barang')
                                    <form action="{{ route('gudang.penerimaan.destroy', $m) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin menghapus riwayat ini? Stok yang masuk akan dikurangi.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" style="padding:0.25rem 0.5rem; font-size:0.75rem;" title="Hapus">
                                            🗑️
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" style="text-align:center;padding:3rem;color:#94a3b8;">Belum ada data penerimaan barang.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($movements->hasPages())
                <div style="padding:1rem 1.25rem;">{{ $movements->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
