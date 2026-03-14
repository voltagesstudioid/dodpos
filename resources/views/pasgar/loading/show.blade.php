<x-app-layout>
<x-slot name="header">Pesanan Pasgar — Detail</x-slot>

<div class="page-container animate-in" style="max-width:900px;">
    <div class="ph-breadcrumb">
        <a href="{{ route('pasgar.loadings.index') }}">Pesanan Pasgar</a>
        <span class="ph-breadcrumb-sep">›</span>
        <span>{{ $loading->transfer_number }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">❌ {{ session('error') }}</div>
    @endif

    {{-- Header card --}}
    <div class="form-card" style="margin-bottom:1.25rem;">
        <div class="form-card-header">
            <div class="form-card-icon indigo">📋</div>
            <div style="flex:1;">
                <div class="form-card-title">{{ $loading->transfer_number }}</div>
                <div class="form-card-subtitle">Tanggal: {{ $loading->date->format('d M Y') }}</div>
            </div>
            {{-- Status Badge --}}
            @if($loading->status === 'pending')
                <span style="background:#fef9c3;color:#854d0e;padding:6px 14px;border-radius:20px;font-weight:700;font-size:0.8rem;">⏳ Menunggu</span>
            @elseif($loading->status === 'disiapkan')
                <span style="background:#dbeafe;color:#1d4ed8;padding:6px 14px;border-radius:20px;font-weight:700;font-size:0.8rem;">📦 Disiapkan</span>
            @elseif($loading->status === 'confirmed')
                <span style="background:#dcfce7;color:#166534;padding:6px 14px;border-radius:20px;font-weight:700;font-size:0.8rem;">✅ Cross Check Selesai</span>
            @elseif($loading->status === 'approved')
                <span style="background:#dcfce7;color:#166534;padding:6px 14px;border-radius:20px;font-weight:700;font-size:0.8rem;">✅ Disetujui</span>
            @else
                <span style="background:#fee2e2;color:#991b1b;padding:6px 14px;border-radius:20px;font-weight:700;font-size:0.8rem;">❌ Ditolak</span>
            @endif
        </div>
        <div class="form-card-body">
            <div class="two-col">
                <div>
                    <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;margin-bottom:8px;">Dibuat Oleh (Sales)</div>
                    <div style="font-weight:600;">{{ $loading->creator->name }}</div>
                    <div style="font-size:0.8rem;color:#64748b;">{{ $loading->created_at->format('d M Y H:i') }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#0369a1;font-weight:700;text-transform:uppercase;margin-bottom:8px;">Kendaraan Tujuan</div>
                    <div style="font-weight:600;color:#075985;">{{ $loading->toWarehouse->name }}</div>
                    @if($loading->from_warehouse_id)
                        <div style="font-size:0.8rem;color:#64748b;">Dari Gudang: <strong>{{ $loading->fromWarehouse?->name }}</strong></div>
                    @else
                        <div style="font-size:0.8rem;color:#f59e0b;font-weight:600;">⏳ Gudang belum dipilih admin</div>
                    @endif
                    @if($loading->notes)
                        <div style="font-size:0.8rem;color:#64748b;margin-top:4px;">Catatan: {{ $loading->notes }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Items table --}}
    <div class="form-card" style="margin-bottom:1.25rem;padding:0;">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Produk</th>
                        <th style="text-align:center;">Qty Dipesan</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loading->items as $i => $item)
                    <tr>
                        <td style="color:#94a3b8;">{{ $i+1 }}</td>
                        <td style="font-weight:600;color:#1e293b;">{{ $item->product->name }}</td>
                        <td style="text-align:center;font-weight:700;">
                            {{ $item->quantity }}
                            <span style="font-weight:400;font-size:0.8rem;color:#64748b;">{{ $item->product->unit->name ?? 'pcs' }}</span>
                        </td>
                        <td style="color:#64748b;font-size:0.85rem;">{{ $item->notes ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ====== ACTION AREA ====== --}}

    @if($loading->status === 'pending')
        @php $warehouses = \App\Models\Warehouse::whereDoesntHave('vehicle')->where('active', true)->get(); @endphp

        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:1.25rem;margin-bottom:1.25rem;">
            <div style="font-weight:700;color:#1d4ed8;margin-bottom:6px;">📋 Admin: Tentukan Gudang Asal</div>
            <div style="font-size:0.82rem;color:#3b82f6;line-height:1.7;">
                Pilih gudang asal sebelum menandai pesanan sebagai <strong>Disiapkan</strong>.<br>
                Stok berkurang dari gudang yang dipilih setelah sales konfirmasi cross check.
            </div>
        </div>

        <div style="display:flex;gap:14px;flex-wrap:wrap;padding-top:1rem;border-top:1px solid #e2e8f0;">

            {{-- Tandai Disiapkan (dengan cross check) --}}
            <form action="{{ route('pasgar.loadings.disiapkan', $loading) }}" method="POST"
                  style="flex:2;min-width:260px;">
                @csrf
                <div style="margin-bottom:10px;">
                    <label style="font-size:0.8rem;font-weight:600;color:#374151;display:block;margin-bottom:5px;">
                        Gudang Asal <span style="color:#ef4444;">*</span>
                    </label>
                    <select name="from_warehouse_id" required
                        style="width:100%;padding:10px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:0.9rem;background:#fff;color:#1e293b;">
                        <option value="">-- Pilih Gudang --</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    onclick="return confirm('Tandai Disiapkan dari gudang yang dipilih? Sales akan cross check via aplikasi Pasgar.')"
                    style="width:100%;background:#3b82f6;color:#fff;border:none;padding:0.9rem;border-radius:8px;font-weight:700;cursor:pointer;font-size:0.95rem;">
                    📦 Tandai Disiapkan → Cross Check
                </button>
            </form>

            {{-- Setujui Langsung (tanpa cross check) --}}
            <div style="flex:1;min-width:180px;display:flex;flex-direction:column;gap:10px;">
                <div style="font-size:0.75rem;color:#64748b;font-weight:600;">Atau — tanpa cross check:</div>
                <form action="{{ route('pasgar.loadings.approve', $loading) }}" method="POST">
                    @csrf
                    <div style="margin-bottom:8px;">
                        <label style="font-size:0.75rem;font-weight:600;color:#374151;display:block;margin-bottom:4px;">Gudang Asal</label>
                        <select name="from_warehouse_id" required
                            style="width:100%;padding:8px 10px;border:1.5px solid #d1d5db;border-radius:8px;font-size:0.85rem;background:#fff;color:#1e293b;">
                            <option value="">-- Pilih --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        onclick="return confirm('Setujui langsung? Stok langsung pindah ke kendaraan tanpa cross check.')"
                        style="width:100%;background:#10b981;color:#fff;border:none;padding:0.75rem;border-radius:8px;font-weight:700;cursor:pointer;">
                        ✅ Setujui Langsung
                    </button>
                </form>
                <form>
                    <button type="button" onclick="alert('Fitur tolak masih dalam pengembangan.')"
                        style="width:100%;background:#ef4444;color:#fff;border:none;padding:0.75rem;border-radius:8px;font-weight:700;cursor:pointer;">
                        ❌ Tolak
                    </button>
                </form>
            </div>
        </div>

    @elseif($loading->status === 'disiapkan')
        <div style="margin-top:1.5rem;padding:1.5rem;background:#eff6ff;border-radius:10px;border:1px solid #bfdbfe;text-align:center;">
            <div style="font-size:2.5rem;margin-bottom:0.5rem;">📱</div>
            <div style="font-weight:700;color:#1d4ed8;margin-bottom:6px;">Menunggu Cross Check oleh Sales</div>
            <div style="font-size:0.85rem;color:#3b82f6;">
                Disiapkan dari gudang: <strong>{{ $loading->fromWarehouse?->name ?? '-' }}</strong><br>
                Sales buka aplikasi Pasgar → <strong>Cross Check Barang</strong> → konfirmasi penerimaan.
            </div>
        </div>

    @elseif($loading->status === 'confirmed')
        <div style="margin-top:1.5rem;padding:1.5rem;background:#f0fdf4;border-radius:10px;border:1px solid #bbf7d0;text-align:center;">
            <div style="font-size:2.5rem;margin-bottom:0.5rem;">✅</div>
            <div style="font-weight:700;color:#166534;margin-bottom:6px;">Cross Check Selesai — Stok Masuk Kendaraan</div>
            <div style="font-size:0.85rem;color:#15803d;">
                Diambil dari: <strong>{{ $loading->fromWarehouse?->name ?? '-' }}</strong> →
                {{ $loading->toWarehouse->name }}<br>
                Sales sudah konfirmasi penerimaan. Stok telah berpindah ke kendaraan.
            </div>
            <div style="font-size:0.8rem;color:#64748b;margin-top:0.5rem;">
                Dikonfirmasi oleh: {{ $loading->approver?->name ?? 'Sales' }}
            </div>
        </div>

    @elseif($loading->status === 'approved')
        <div style="margin-top:1.5rem;padding:1.5rem;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;text-align:center;">
            <div style="font-weight:600;color:#334155;margin-bottom:5px;">✅ Disetujui langsung — stok sudah diproses.</div>
            <div style="font-size:0.85rem;color:#64748b;">
                Dari: {{ $loading->fromWarehouse?->name ?? '-' }} &nbsp;|&nbsp;
                Oleh: {{ $loading->approver?->name ?? 'Supervisor' }}
            </div>
        </div>
    @endif
</div>
</x-app-layout>
