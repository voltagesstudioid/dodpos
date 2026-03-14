<x-app-layout>
    <x-slot name="header">Detail Anggota: {{ $anggota->user->name }}</x-slot>

    <div class="page-container">
        <div style="margin-bottom:1rem; display:flex; gap:0.5rem;">
            <a href="{{ route('pasgar.anggota.index') }}" class="btn-secondary btn-sm">← Kembali</a>
            <a href="{{ route('pasgar.anggota.edit', $anggota) }}" class="btn-warning btn-sm" style="padding:0.35rem 0.75rem; border-radius:6px; font-size:0.75rem; display:inline-flex; align-items:center; gap:0.375rem;">✏️ Edit</a>
        </div>

        <div style="display:grid; grid-template-columns:1fr 2fr; gap:1.25rem; align-items:start;">

            {{-- Profile Card --}}
            <div class="card" style="padding:1.5rem; text-align:center;">
                <div style="width:72px; height:72px; background:linear-gradient(135deg,#6366f1,#8b5cf6); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.75rem; font-weight:800; color:white; margin:0 auto 1rem;">
                    {{ strtoupper(substr($anggota->user->name, 0, 1)) }}
                </div>
                <div style="font-size:1.1rem; font-weight:700; color:#1e293b;">{{ $anggota->user->name }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">{{ $anggota->user->email }}</div>
                <div style="margin-top:0.75rem;">
                    @if($anggota->active)
                        <span class="badge-success">✅ Aktif</span>
                    @else
                        <span class="badge-danger">❌ Nonaktif</span>
                    @endif
                </div>

                <div style="margin-top:1.25rem; text-align:left; border-top:1px solid #f1f5f9; padding-top:1rem;">
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">Info Kendaraan</div>
                    @if($anggota->vehicle)
                        <div style="font-size:0.875rem; font-weight:600; color:#1e293b;">🚗 {{ $anggota->vehicle->license_plate }}</div>
                        <div style="font-size:0.8rem; color:#64748b;">{{ $anggota->vehicle->type ?? '-' }}</div>
                        <div style="font-size:0.8rem; color:#64748b;">Gudang: {{ $anggota->vehicle->warehouse?->name ?? '—' }}</div>
                    @else
                        <div style="font-size:0.8rem; color:#94a3b8;">Belum ada kendaraan</div>
                    @endif
                </div>

                @if($anggota->area)
                <div style="margin-top:1rem; text-align:left; border-top:1px solid #f1f5f9; padding-top:1rem;">
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Area Tugas</div>
                    <div style="font-size:0.875rem; color:#1e293b;">📍 {{ $anggota->area }}</div>
                </div>
                @endif

                @if($anggota->notes)
                <div style="margin-top:1rem; text-align:left; border-top:1px solid #f1f5f9; padding-top:1rem;">
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Catatan</div>
                    <div style="font-size:0.8rem; color:#64748b;">{{ $anggota->notes }}</div>
                </div>
                @endif
            </div>

            <div style="display:flex; flex-direction:column; gap:1.25rem;">

                {{-- Summary Cards --}}
                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem;">
                    <div class="card" style="padding:1rem; text-align:center;">
                        <div style="font-size:1.5rem; font-weight:800; color:#10b981;">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                        <div style="font-size:0.75rem; color:#64748b; margin-top:0.25rem;">Penjualan Hari Ini</div>
                    </div>
                    <div class="card" style="padding:1rem; text-align:center;">
                        <div style="font-size:1.5rem; font-weight:800; color:#4f46e5;">{{ $stockOnHand->count() }}</div>
                        <div style="font-size:0.75rem; color:#64748b; margin-top:0.25rem;">Jenis Stok On-Hand</div>
                    </div>
                    <div class="card" style="padding:1rem; text-align:center;">
                        <div style="font-size:1.5rem; font-weight:800; color:#f59e0b;">{{ $recentDeposits->count() }}</div>
                        <div style="font-size:0.75rem; color:#64748b; margin-top:0.25rem;">Setoran Terbaru</div>
                    </div>
                </div>

                {{-- Stok On-Hand --}}
                <div class="card">
                    <div style="padding:1rem 1.25rem; border-bottom:1px solid #e2e8f0; font-weight:700; font-size:0.9rem;">📦 Stok On-Hand Kendaraan</div>
                    @if($stockOnHand->isEmpty())
                        <div style="padding:1.5rem; text-align:center; color:#94a3b8; font-size:0.875rem;">Tidak ada stok di kendaraan ini.</div>
                    @else
                    <div class="table-wrapper">
                        <table class="data-table" style="min-width:400px;">
                            <thead><tr><th>Produk</th><th>Stok</th><th>Satuan</th></tr></thead>
                            <tbody>
                                @foreach($stockOnHand as $s)
                                <tr>
                                    <td style="font-weight:500;">{{ $s->product->name }}</td>
                                    <td>
                                        @if(($maskStock ?? false) === true)
                                            <span style="display:inline-flex; align-items:center; padding:0.2rem 0.55rem; border-radius:999px; background:#fef3c7; color:#92400e; font-weight:900; font-size:0.78rem;">Terkunci</span>
                                        @else
                                            <strong>{{ $s->stock }}</strong>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $s->product->unit?->name ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                {{-- Penjualan Terbaru --}}
                <div class="card">
                    <div style="padding:1rem 1.25rem; border-bottom:1px solid #e2e8f0; font-weight:700; font-size:0.9rem;">🛒 Penjualan Terbaru</div>
                    @if($recentSales->isEmpty())
                        <div style="padding:1.5rem; text-align:center; color:#94a3b8; font-size:0.875rem;">Belum ada penjualan.</div>
                    @else
                    <div class="table-wrapper">
                        <table class="data-table" style="min-width:400px;">
                            <thead><tr><th>No. SO</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($recentSales as $so)
                                <tr>
                                    <td style="font-weight:600; color:#4f46e5;">{{ $so->so_number }}</td>
                                    <td>{{ $so->customer?->name ?? '—' }}</td>
                                    <td class="text-muted">{{ \Carbon\Carbon::parse($so->order_date)->format('d M Y') }}</td>
                                    <td style="font-weight:600;">Rp {{ number_format($so->total_amount, 0, ',', '.') }}</td>
                                    <td><span class="badge-{{ $so->status === 'completed' ? 'success' : 'indigo' }}">{{ $so->status }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                {{-- Setoran Terbaru --}}
                <div class="card">
                    <div style="padding:1rem 1.25rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between;">
                        <span style="font-weight:700; font-size:0.9rem;">💰 Setoran Terbaru</span>
                        @can('create_pasgar_setoran')
                        <a href="{{ route('pasgar.setoran.create') }}" class="btn-primary btn-sm">+ Setoran Baru</a>
                        @endcan
                    </div>
                    @if($recentDeposits->isEmpty())
                        <div style="padding:1.5rem; text-align:center; color:#94a3b8; font-size:0.875rem;">Belum ada setoran.</div>
                    @else
                    <div class="table-wrapper">
                        <table class="data-table" style="min-width:400px;">
                            <thead><tr><th>No. Setoran</th><th>Tanggal</th><th>Total</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($recentDeposits as $dep)
                                <tr>
                                    <td style="font-weight:600; color:#4f46e5;">{{ $dep->deposit_number }}</td>
                                    <td class="text-muted">{{ $dep->deposit_date->format('d M Y') }}</td>
                                    <td style="font-weight:600;">Rp {{ number_format($dep->total_amount, 0, ',', '.') }}</td>
                                    <td><span class="{{ $dep->status_color }}">{{ $dep->status_label }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
