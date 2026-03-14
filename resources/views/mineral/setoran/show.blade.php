<x-app-layout>
    <x-slot name="header">
        <div class="ph">
            <div class="ph-left">
                <a href="{{ route('mineral.setoran.index') }}" class="ph-icon slate" style="color:white;text-decoration:none;">←</a>
                <div>
                    <h2 class="ph-title">Detail Setoran Harian (ST-M{{ str_pad($setoran->id, 5, '0', STR_PAD_LEFT) }})</h2>
                    <p class="ph-subtitle">Dilaporkan oleh {{ $setoran->sales->name ?? 'Sales Dihapus' }} pada {{ \Carbon\Carbon::parse($setoran->created_at)->translatedFormat('l, d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="grid-2 mb-3">
        <!-- Rangkuman Finansial -->
        <div class="card p-3">
            <h4 class="font-bold mb-2 text-indigo">Catatan Penjualan Uang</h4>
            <div class="info-row">
                <span class="info-key">Ekspektasi Kas (Sistem)</span>
                <span class="info-val">Rp {{ number_format($setoran->expected_cash, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">Realita Disetor (Cash Tangan)</span>
                <span class="info-val font-bold text-green" style="font-size:1.1rem">Rp {{ number_format($setoran->actual_cash, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">Total Piutang (Tempo)</span>
                <span class="info-val text-red">Rp {{ number_format($setoran->expected_tempo, 0, ',', '.') }}</span>
            </div>
            <div class="form-divider">
                <span class="form-divider-text">Selisih & Status</span>
            </div>
            <div class="info-row">
                <span class="info-key">Selisih Kas Tangan</span>
                @php $selisih = $setoran->actual_cash - $setoran->expected_cash; @endphp
                <span class="info-val font-bold {{ $selisih == 0 ? 'text-green' : ($selisih < 0 ? 'text-red' : 'text-blue') }}">
                    {{ $selisih < 0 ? '-' : ($selisih > 0 ? '+' : '') }} Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-key">Status SPV</span>
                @if($setoran->status == 'pending')
                    <span class="badge badge-warning">Menunggu Validasi Anda</span>
                @else
                    <span class="badge badge-success">Ter-Verifikasi (Selesai)</span>
                @endif
            </div>
        </div>

        <!-- Rangkuman Sisa Stok Kendaraan (Retur Malam) -->
        <div class="card p-3">
            <h4 class="font-bold mb-2 text-rose">Barang Sisa Mobil (Dikembalikan)</h4>
            @if(count($vehicleStocks) == 0)
                <div class="empty-state" style="padding:1rem;">
                    <span class="empty-state-icon" style="font-size:2rem; margin-bottom:0.5rem">🛒</span>
                    <div class="empty-state-title">Tidak ada sisa barang</div>
                    <div class="empty-state-desc">Hari ini semua muatan Mineral Sales Ludes/Habis Terjual. Luar Biasa!</div>
                </div>
            @else
                <table style="width:100%; text-align:left; font-size:0.875rem">
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <th style="padding:0.5rem 0;">Jenis Air Mineral</th>
                        <th style="padding:0.5rem 0; text-align:right">Dus Dikembalikan</th>
                    </tr>
                    @foreach($vehicleStocks as $vStock)
                    <tr style="border-bottom:1px solid #f8fafc;">
                        <td style="padding:0.6rem 0;" class="font-bold text-gray">{{ $vStock->product->name }}</td>
                        <td style="padding:0.6rem 0; text-align:right;" class="font-bold text-rose">{{ $vStock->leftover_qty }} <span style="font-weight:normal;color:#94a3b8;font-size:0.7rem">Dus</span></td>
                    </tr>
                    @endforeach
                </table>
                <div class="mt-2 text-xs text-gray bg-gray-50 p-2 rounded border">
                    * Qty sisa di atas akan otomatis di-insert kembali ke Stok Gudang utama bila Anda mem-validasi tombol Verifikasi di bawah.
                </div>
            @endif
        </div>
    </div>

    @if($setoran->status == 'pending')
        <div class="card p-3 bg-white" style="border:1px solid #e0e7ff; box-shadow:0 10px 15px -3px rgba(79,70,229,0.1)">
            <h4 class="font-bold mb-1">Konfirmasi & Aksi Validasi</h4>
            <p class="text-xs text-gray mb-2">Pastikan uang yang dihitung di meja sama dengan <code style="padding:0.1rem;background:#fef2f2;color:#ef4444">Realita Disetor Cash Tangan</code>. Jika valid, klik Verifikasi untuk menyelesaikan rekonsiliasi hari ini.</p>
            
            <form action="{{ route('mineral.setoran.verify', $setoran->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary w-full" style="justify-content:center; padding:0.75rem; font-size:1rem;" onclick="return confirm('Yakin validasi dan kembalikan sisa truk ke gudang?')">
                    Tarik Sisa Barang & Verifikasi Uang ✅
                </button>
            </form>
        </div>
    @else
        <div class="alert alert-success">
            ✅ Setoran ini telah divalidasi oleh {{ $setoran->verifier->name ?? 'Admin' }} dan seluruh barang sisa (jika ada) sudah berhasil masuk kembali ke gudang utama lewat tabel Mutasi.
        </div>
    @endif
</x-app-layout>
