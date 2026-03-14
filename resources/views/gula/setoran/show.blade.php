<x-app-layout>
    <x-slot name="header">Detail Setoran (Gula)</x-slot>

    <div class="page-container" style="max-width: 1100px;">
        <div class="page-header">
            <div>
                <div class="page-header-title">Validasi Closing Gula</div>
                <div class="page-header-subtitle">Cek kas, piutang, sisa stok, dan transaksi harian</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('gula.setoran.index') }}" class="btn-secondary">← Kembali</a>
                @if($setoran->status !== 'verified')
                    <button type="submit" form="verifySetoranGulaForm" class="btn-primary">✅ Verifikasi</button>
                @endif
            </div>
        </div>

        @if(session('success')) <div class="alert alert-success" role="alert">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div> @endif

        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Ringkasan</div>
                    <div class="panel-subtitle">Informasi closing dan status verifikasi</div>
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
                    <span class="badge badge-gray">Closing: {{ \Carbon\Carbon::parse($setoran->date)->format('d/m/Y') }}</span>
                    <span class="badge badge-gray">Submit: {{ \Carbon\Carbon::parse($setoran->created_at)->format('H:i') }} WIB</span>
                    @if($setoran->status === 'verified')
                        <span class="badge badge-gray">Verifier: {{ $setoran->verifiedBy->name ?? '-' }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="stat-grid" style="margin-top: 1rem;">
            <div class="stat-card">
                <div class="stat-icon emerald">💰</div>
                <div>
                    <div class="stat-label">Uang Kas (Tunai)</div>
                    <div class="stat-value emerald">Rp {{ number_format((float) $setoran->total_cash, 0, ',', '.') }}</div>
                    <span class="badge badge-success">Cash</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon rose">🧾</div>
                <div>
                    <div class="stat-label">Total Piutang</div>
                    <div class="stat-value rose">Rp {{ number_format((float) $setoran->total_piutang, 0, ',', '.') }}</div>
                    <span class="badge badge-danger">Tempo</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">📦</div>
                <div>
                    <div class="stat-label">Sisa Item Armada</div>
                    <div class="stat-value blue">{{ number_format((int) $vehicleStocks->count(), 0, ',', '.') }}</div>
                    <span class="badge badge-blue">Rows</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon indigo">🧾</div>
                <div>
                    <div class="stat-label">Invoice Hari Ini</div>
                    <div class="stat-value indigo">{{ number_format((int) $transactions->count(), 0, ',', '.') }}</div>
                    <span class="badge badge-indigo">Transaksi</span>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Catatan Sales</div>
                    <div class="panel-subtitle">Keterangan yang dikirim dari aplikasi</div>
                </div>
            </div>
            <div class="panel-body">
                @if($setoran->notes)
                    <div class="alert alert-info" role="alert">📝 {{ $setoran->notes }}</div>
                @else
                    <div style="color:#94a3b8;">Tidak ada catatan.</div>
                @endif
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Sisa Stok Fisik (Di Kendaraan)</div>
                    <div class="panel-subtitle">Akan otomatis kembali ke gudang saat diverifikasi</div>
                </div>
                <span class="badge badge-warning">Auto-return</span>
            </div>
            <div class="panel-body">
                <div class="table-wrapper">
                    <table class="data-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th style="text-align:right;">Karung</th>
                                <th style="text-align:right;">Bal</th>
                                <th style="text-align:right;">Eceran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehicleStocks as $vStock)
                                <tr>
                                    <td style="font-weight:900;color:#0f172a;">{{ $vStock->product->name ?? 'Terhapus' }}</td>
                                    <td style="text-align:right;">
                                        @if(($maskStock ?? false) === true)
                                            <span class="badge badge-warning">Terkunci</span>
                                        @elseif((float) $vStock->qty_karung > 0)
                                            <span class="badge badge-blue">{{ number_format((float) $vStock->qty_karung, 0, ',', '.') }}</span>
                                        @else
                                            <span style="color:#94a3b8;">-</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right;">
                                        @if(($maskStock ?? false) === true)
                                            <span class="badge badge-warning">Terkunci</span>
                                        @elseif((float) ($vStock->qty_bal ?? 0) > 0)
                                            <span class="badge badge-indigo">{{ number_format((float) $vStock->qty_bal, 0, ',', '.') }}</span>
                                        @else
                                            <span style="color:#94a3b8;">-</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right;">
                                        @if(($maskStock ?? false) === true)
                                            <span class="badge badge-warning">Terkunci</span>
                                        @elseif((float) $vStock->qty_eceran > 0)
                                            <span class="badge badge-success">{{ number_format((float) $vStock->qty_eceran, 0, ',', '.') }}</span>
                                        @else
                                            <span style="color:#94a3b8;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="padding: 1.25rem;text-align:center;color:#64748b;">Truk dalam kondisi kosong / barang ludes terjual hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Nota Transaksi Hari Ini</div>
                    <div class="panel-subtitle">{{ $transactions->count() }} invoice</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-wrapper">
                    <table class="data-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Toko / Customer</th>
                                <th style="text-align:right;">Total</th>
                                <th style="text-align:center;">Metode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $trx)
                                @php $grandTotal = $trx->grand_total ?? $trx->total ?? 0; @endphp
                                <tr>
                                    <td style="white-space:nowrap;color:#64748b;">{{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }} WIB</td>
                                    <td style="font-weight:900;color:#0f172a;">{{ $trx->customer->name ?? 'Pelanggan Umum' }}</td>
                                    <td style="text-align:right;font-weight:900;color:#0f172a;">Rp {{ number_format((float) $grandTotal, 0, ',', '.') }}</td>
                                    <td style="text-align:center;">
                                        @if($trx->payment_method === 'cash')
                                            <span class="badge badge-success">Tunai</span>
                                        @else
                                            <span class="badge badge-danger">Tempo</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="padding: 1.25rem;text-align:center;color:#64748b;">Belum ada transaksi di database pada hari tersebut.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Keputusan Verifikasi</div>
                    <div class="panel-subtitle">Pastikan uang tunai, nota, dan sisa barang valid</div>
                </div>
            </div>
            <div class="panel-body">
                @if($setoran->status !== 'verified')
                    <form id="verifySetoranGulaForm" action="{{ route('gula.setoran.verify', $setoran->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin setoran ini valid?\n\n- Sisa barang akan dimasukkan ke gudang.\n- Surat jalan dimatikan.');">
                        @csrf
                        <div style="display:flex;justify-content:flex-end;gap:0.75rem;flex-wrap:wrap;">
                            <a href="{{ route('gula.setoran.index') }}" class="btn-secondary">Batal</a>
                            <button type="submit" class="btn-primary">🔐 Verifikasi &amp; Sahkan Setoran</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-success" role="alert">✅ Setoran telah ditutup / selesai.</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
