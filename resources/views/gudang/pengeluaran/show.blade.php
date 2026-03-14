<x-app-layout>
    <x-slot name="header">Detail Pengeluaran Barang</x-slot>

    <div class="page-container" style="max-width:860px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem;">
            <div>
                <h1 style="font-size:1.5rem; font-weight:800; color:#0f172a; margin:0; display:flex; align-items:center; gap:0.5rem;">
                    📄 Detail Pengeluaran Barang
                </h1>
                <p style="color:#64748b; font-size:0.875rem; margin:0.35rem 0 0;">Informasi lengkap transaksi stok keluar.</p>
            </div>
            <a href="{{ route('gudang.pengeluaran') }}" class="btn-secondary" style="white-space:nowrap;">
                ← Kembali ke Daftar
            </a>
        </div>

        <div class="card" style="padding:2rem;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
                <!-- Kolom Kiri -->
                <div>
                    <h3 style="font-size:1rem; font-weight:700; color:#0f172a; margin-bottom:1rem; border-bottom:1px solid #e2e8f0; padding-bottom:0.5rem;">Informasi Umum</h3>
                    <table style="width:100%; font-size:0.875rem;">
                        <tr style="border-bottom:1px dashed #e2e8f0;">
                            <td style="padding:0.75rem 0; color:#64748b; width:140px;">No. Referensi</td>
                            <td style="padding:0.75rem 0; font-weight:700; color:#4f46e5;">{{ $outbound->reference_number }}</td>
                        </tr>
                        <tr style="border-bottom:1px dashed #e2e8f0;">
                            <td style="padding:0.75rem 0; color:#64748b;">Tanggal / Waktu</td>
                            <td style="padding:0.75rem 0; font-weight:600; color:#1e293b;">{{ $outbound->created_at->format('d M Y H:i:s') }}</td>
                        </tr>
                        <tr style="border-bottom:1px dashed #e2e8f0;">
                            <td style="padding:0.75rem 0; color:#64748b;">Dicatat Oleh</td>
                            <td style="padding:0.75rem 0; font-weight:600; color:#1e293b;">{{ $outbound->user?->name ?? 'Sistem' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Kolom Kanan -->
                <div>
                    <h3 style="font-size:1rem; font-weight:700; color:#0f172a; margin-bottom:1rem; border-bottom:1px solid #e2e8f0; padding-bottom:0.5rem;">Informasi Produk</h3>
                    <table style="width:100%; font-size:0.875rem;">
                        <tr style="border-bottom:1px dashed #e2e8f0;">
                            <td style="padding:0.75rem 0; color:#64748b; width:140px;">Produk</td>
                            <td style="padding:0.75rem 0; font-weight:700; color:#1e293b;">
                                {{ $outbound->product?->name ?? '-' }}<br>
                                <span style="font-size:0.75rem; color:#94a3b8; font-weight:400;">SKU: {{ $outbound->product?->sku ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr style="border-bottom:1px dashed #e2e8f0;">
                            <td style="padding:0.75rem 0; color:#64748b;">Gudang / Lokasi</td>
                            <td style="padding:0.75rem 0; font-weight:600; color:#1e293b;">
                                Gudang: {{ $outbound->warehouse?->name ?? '-' }}<br>
                                <span style="font-size:0.75rem; color:#94a3b8; font-weight:400;">Rak: {{ $outbound->location?->name ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr style="border-bottom:1px dashed #e2e8f0;">
                            <td style="padding:0.75rem 0; color:#64748b;">Kuantitas Keluar</td>
                            <td style="padding:0.75rem 0; font-weight:800; color:#ef4444; font-size:1.1rem;">-{{ $outbound->quantity }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Detail Batch / Expired / Catatan -->
            <h3 style="font-size:1rem; font-weight:700; color:#0f172a; margin-bottom:1rem; border-bottom:1px solid #e2e8f0; padding-bottom:0.5rem;">Detail Lanjutan</h3>
            <table style="width:100%; font-size:0.875rem;">
                <tr style="border-bottom:1px dashed #e2e8f0;">
                    <td style="padding:0.75rem 0; color:#64748b; width:140px;">Nomor Batch</td>
                    <td style="padding:0.75rem 0; font-weight:600; color:#1e293b;">{{ $outbound->batch_number ?: '-' }}</td>
                </tr>
                <tr style="border-bottom:1px dashed #e2e8f0;">
                    <td style="padding:0.75rem 0; color:#64748b;">Tgl Kedaluwarsa</td>
                    <td style="padding:0.75rem 0; font-weight:600; color:#ef4444;">{{ $outbound->expired_date ? \Carbon\Carbon::parse($outbound->expired_date)->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:0.75rem 0; color:#64748b;">Catatan</td>
                    <td style="padding:0.75rem 0; font-weight:500; color:#334155;">{{ $outbound->notes ?: '-' }}</td>
                </tr>
            </table>

            <!-- Actions -->
            <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end;">
                <form action="{{ route('gudang.pengeluaran.destroy', $outbound) }}" method="POST" onsubmit="return confirm('Yakin menghapus riwayat pengeluaran ini? Stok yang keluar akan dikembalikan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" style="display:inline-flex; align-items:center; gap:0.5rem;">
                        🗑️ Hapus Riwayat
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
