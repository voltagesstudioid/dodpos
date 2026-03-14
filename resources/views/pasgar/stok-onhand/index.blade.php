<x-app-layout>
    <x-slot name="header">Stok On-Hand Kendaraan</x-slot>

    <div class="page-container">

        {{-- Filter by Vehicle --}}
        <div class="card" style="padding:1rem 1.5rem; margin-bottom:1.25rem;">
            <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
                <div>
                    <label class="form-label" style="font-size:0.75rem;">Filter Kendaraan</label>
                    <select name="vehicle_id" class="form-input" style="width:260px;" onchange="this.form.submit()">
                        <option value="">-- Semua Kendaraan --</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" {{ $selectedVehicleId == $v->id ? 'selected' : '' }}>
                                {{ $v->license_plate }} — {{ $v->warehouse?->name ?? 'Tanpa Gudang' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($selectedVehicleId)
                    <a href="{{ route('pasgar.stok-onhand.index') }}" class="btn-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>

        @if($selectedVehicleId && $selectedVehicle)
        {{-- Detail stok satu kendaraan --}}
        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">📦 Stok: {{ $selectedVehicle->license_plate }}</h2>
                    <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Gudang: {{ $selectedVehicle->warehouse?->name ?? '—' }}</p>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    @can('create_pasgar_pengembalian')
                    <a href="{{ route('pasgar.pengembalian.create') }}" class="btn-primary btn-sm">↩ Kembalikan Sisa</a>
                    @endcan
                </div>
            </div>

            @if($detailStocks->isEmpty())
                <div style="padding:3rem; text-align:center; color:#94a3b8;">
                    <div style="font-size:2rem; margin-bottom:0.5rem;">📭</div>
                    <div>Tidak ada stok di kendaraan ini.</div>
                </div>
            @else
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th style="text-align:right;">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailStocks as $i => $s)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div style="font-weight:600; color:#1e293b;">{{ $s->product->name }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">SKU: {{ $s->product->sku ?? '—' }}</div>
                            </td>
                            <td class="text-muted">{{ $s->product->category?->name ?? '—' }}</td>
                            <td class="text-muted">{{ $s->product->unit?->name ?? 'pcs' }}</td>
                            <td style="text-align:right;">
                                @if(($maskStock ?? false) === true)
                                    <span style="display:inline-flex; align-items:center; padding:0.2rem 0.55rem; border-radius:999px; background:#fef3c7; color:#92400e; font-weight:900; font-size:0.78rem;">Terkunci</span>
                                @else
                                    <span class="{{ $s->stock <= 5 ? 'text-danger' : 'text-emerald' }}" style="font-size:1.1rem; font-weight:700;">
                                        {{ $s->stock }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8fafc;">
                            <td colspan="4" style="padding:0.875rem 1.25rem; font-weight:700; color:#475569;">Total Jenis Produk</td>
                            <td style="text-align:right; padding:0.875rem 1.25rem; font-weight:700; color:#1e293b;">{{ $detailStocks->count() }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>

        @else
        {{-- Ringkasan semua kendaraan --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:1.25rem;">
            @forelse ($vehicleStocks as $vs)
            <div class="card">
                <div style="padding:1rem 1.25rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between;">
                    <div>
                        <div style="font-weight:700; color:#1e293b; font-size:0.95rem;">🚗 {{ $vs['vehicle']->license_plate }}</div>
                        <div style="font-size:0.75rem; color:#64748b;">{{ $vs['vehicle']->warehouse?->name ?? 'Tanpa Gudang' }}</div>
                    </div>
                    @php $member = $vs['member']; @endphp
                    @if($member)
                        <div style="text-align:right;">
                            <div style="font-size:0.8rem; font-weight:600; color:#4f46e5;">{{ $member->user->name }}</div>
                            <div style="font-size:0.7rem; color:#94a3b8;">{{ $member->area ?? 'Area belum diset' }}</div>
                        </div>
                    @else
                        <span class="badge-danger" style="font-size:0.65rem;">Tanpa Anggota</span>
                    @endif
                </div>

                <div style="padding:1rem 1.25rem;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
                        <div style="background:#f0fdf4; border-radius:8px; padding:0.75rem; text-align:center;">
                            <div style="font-size:1.25rem; font-weight:800; color:#10b981;">{{ $vs['total_items'] }}</div>
                            <div style="font-size:0.7rem; color:#64748b;">Jenis Produk</div>
                        </div>
                        <div style="background:#eff6ff; border-radius:8px; padding:0.75rem; text-align:center;">
                            <div style="font-size:1.25rem; font-weight:800; color:#3b82f6;">{{ $vs['total_qty'] }}</div>
                            <div style="font-size:0.7rem; color:#64748b;">Total Qty</div>
                        </div>
                    </div>

                    {{-- Top 3 produk --}}
                    @foreach($vs['stocks']->take(3) as $s)
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.375rem 0; border-bottom:1px solid #f8fafc; font-size:0.8rem;">
                        <span style="color:#334155; font-weight:500;">{{ $s->product->name }}</span>
                        @if(($maskStock ?? false) === true)
                            <span style="font-weight:900; color:#92400e;">Terkunci</span>
                        @else
                            <span class="{{ $s->stock <= 5 ? 'text-danger' : '' }}" style="font-weight:700;">{{ $s->stock }} {{ $s->product->unit?->name ?? '' }}</span>
                        @endif
                    </div>
                    @endforeach
                    @if($vs['stocks']->count() > 3)
                        <div style="font-size:0.75rem; color:#94a3b8; margin-top:0.5rem;">+{{ $vs['stocks']->count() - 3 }} produk lainnya</div>
                    @endif

                    <div style="margin-top:0.875rem; display:flex; gap:0.5rem;">
                        <a href="{{ route('pasgar.stok-onhand.index', ['vehicle_id' => $vs['vehicle']->id]) }}" class="btn-secondary btn-sm" style="flex:1; justify-content:center;">👁 Lihat Detail</a>
                        @can('create_pasgar_pengembalian')
                        <a href="{{ route('pasgar.pengembalian.create') }}" class="btn-primary btn-sm" style="flex:1; justify-content:center;">↩ Kembalikan</a>
                        @endcan
                    </div>
                </div>
            </div>
            @empty
            <div class="card" style="padding:3rem; text-align:center; color:#94a3b8; grid-column:1/-1;">
                <div style="font-size:2rem; margin-bottom:0.5rem;">🚗</div>
                <div>Tidak ada kendaraan dengan stok aktif.</div>
                <div style="font-size:0.8rem; margin-top:0.5rem;">Lakukan loading barang terlebih dahulu.</div>
            </div>
            @endforelse
        </div>
        @endif

    </div>
</x-app-layout>
