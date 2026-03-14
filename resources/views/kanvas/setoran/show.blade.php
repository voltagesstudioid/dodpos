<x-app-layout>
    <x-slot name="header">Detail Setoran Kanvas</x-slot>

    <div class="page-container" style="max-width: 1100px;">
        <div class="page-header">
            <div>
                <div class="page-header-title">Validasi Closing Kanvas</div>
                <div class="page-header-subtitle">Cek kas aktual vs sistem, lalu tarik sisa stok ke gudang</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('kanvas.setoran.index') }}" class="btn-secondary">← Kembali</a>
                @if($setoran->status === 'pending')
                    <button type="submit" form="verifySetoranForm" class="btn-primary">✅ Verifikasi</button>
                @endif
            </div>
        </div>

        @if(session('success')) <div class="alert alert-success" role="alert">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div> @endif

        @php
            $diff = (float) $setoran->actual_cash - (float) $setoran->expected_cash;
        @endphp

        <div style="display:grid;grid-template-columns:1fr;gap:1rem;">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Ringkasan</div>
                        <div class="panel-subtitle">Sales, waktu submit, dan status validasi</div>
                    </div>
                    @if($setoran->status === 'verified')
                        <span class="badge badge-success">Verified</span>
                    @else
                        <span class="badge badge-warning">Pending</span>
                    @endif
                </div>
                <div class="panel-body">
                    <div style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
                        <span class="badge badge-indigo">Sales: {{ $setoran->sales->name ?? '-' }}</span>
                        <span class="badge badge-gray">Submit: {{ $setoran->created_at?->format('d/m/Y H:i') }}</span>
                        @if($setoran->status === 'verified')
                            <span class="badge badge-gray">Verifier: {{ $setoran->verifier->name ?? '-' }}</span>
                        @endif
                        <span class="badge {{ $diff < 0 ? 'badge-danger' : ($diff > 0 ? 'badge-blue' : 'badge-success') }}">
                            Selisih: Rp {{ number_format($diff, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr;gap:1rem;">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <div class="panel-title">Rincian Finansial</div>
                            <div class="panel-subtitle">Aplikasi vs uang fisik</div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="stat-grid" style="margin:0;">
                            <div class="stat-card">
                                <div class="stat-icon blue">🧮</div>
                                <div>
                                    <div class="stat-label">Ekspektasi Kas (Sistem)</div>
                                    <div class="stat-value blue">Rp {{ number_format((float) $setoran->expected_cash, 0, ',', '.') }}</div>
                                    <span class="badge badge-blue">A</span>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon emerald">💵</div>
                                <div>
                                    <div class="stat-label">Kas Aktual (Setor)</div>
                                    <div class="stat-value emerald">Rp {{ number_format((float) $setoran->actual_cash, 0, ',', '.') }}</div>
                                    <span class="badge badge-success">B</span>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon rose">🧾</div>
                                <div>
                                    <div class="stat-label">Ekspektasi Tempo</div>
                                    <div class="stat-value rose">Rp {{ number_format((float) $setoran->expected_tempo, 0, ',', '.') }}</div>
                                    <span class="badge badge-danger">Piutang</span>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon indigo">Δ</div>
                                <div>
                                    <div class="stat-label">Selisih (B - A)</div>
                                    <div class="stat-value indigo">Rp {{ number_format($diff, 0, ',', '.') }}</div>
                                    <span class="badge badge-indigo">Diff</span>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info" role="alert" style="margin-top: 1rem;">
                            ℹ️ Piutang tempo akan masuk ke daftar piutang toko setelah divalidasi.
                        </div>

                        @if($setoran->status === 'pending')
                            <form id="verifySetoranForm" action="{{ route('kanvas.setoran.verify', $setoran->id) }}" method="POST" style="margin-top: 1rem;" onsubmit="return confirm('Verifikasi dan tarik balik {{ count($leftovers) }} macam sisa barang ke Gudang Induk?');">
                                @csrf
                                <div style="display:flex;justify-content:flex-end;gap:0.75rem;flex-wrap:wrap;">
                                    <a href="{{ route('kanvas.setoran.index') }}" class="btn-secondary">Batal</a>
                                    <button type="submit" class="btn-primary">✅ Uang Cocok, Tarik Sisa Barang</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <div class="panel-title">Logistik: Unloading Sisa Kendaraan</div>
                            <div class="panel-subtitle">Sisa barang yang akan dikembalikan ke gudang</div>
                        </div>
                        <span class="badge badge-gray">{{ number_format((int) count($leftovers), 0, ',', '.') }} item</span>
                    </div>
                    <div class="panel-body">
                        @if(count($leftovers) > 0)
                            <div class="table-wrapper">
                                <table class="data-table" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th style="text-align:right;">Bawaan Pagi</th>
                                            <th style="text-align:right;">Sisa Bongkar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leftovers as $l)
                                            <tr>
                                                <td style="font-weight:900;color:#0f172a;">{{ $l->product->name ?? '-' }}</td>
                                                <td style="text-align:right;">
                                                    @if(($maskStock ?? false) === true)
                                                        Terkunci
                                                    @else
                                                        {{ number_format((int) $l->initial_qty, 0, ',', '.') }}
                                                    @endif
                                                    {{ $l->product->unit ?? '' }}
                                                </td>
                                                <td style="text-align:right;">
                                                    <span class="badge badge-blue">
                                                        @if(($maskStock ?? false) === true)
                                                            Terkunci
                                                        @else
                                                            {{ number_format((int) $l->leftover_qty, 0, ',', '.') }}
                                                        @endif
                                                        {{ $l->product->unit ?? '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div style="display:flex;flex-direction:column;align-items:center;gap:0.5rem;color:#64748b;padding:1rem 0;">
                                <div style="font-size:2rem;">📦</div>
                                <div style="font-weight:900;color:#0f172a;">Armada kosong</div>
                                <div style="font-size:0.875rem;text-align:center;max-width:520px;">
                                    Semua barang terjual habis atau tidak ada muatan yang perlu dikembalikan.
                                </div>
                            </div>
                        @endif

                        <div class="alert alert-warning" role="alert" style="margin-top: 1rem;">
                            ⚠️ Saat diverifikasi, sisa barang akan ditambahkan kembali ke stok Gudang, dan stok kendaraan akan di-nol-kan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
