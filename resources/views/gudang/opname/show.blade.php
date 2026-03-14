<x-app-layout>
    <x-slot name="header">Detail Opname Stok</x-slot>

    <div class="page-container" style="max-width:860px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem;">
            <div>
                <h1 style="font-size:1.5rem; font-weight:800; color:#0f172a; margin:0; display:flex; align-items:center; gap:0.5rem;">
                    📄 Detail Opname Stok
                </h1>
                <p style="color:#64748b; font-size:0.875rem; margin:0.35rem 0 0;">Informasi penyesuaian catatan stok fisik terhadap sistem.</p>
            </div>
            <a href="{{ route('gudang.opname') }}" class="btn-secondary" style="white-space:nowrap;">
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
                            <td style="padding:0.75rem 0; color:#64748b; width:140px;">No. Dokumen</td>
                            <td style="padding:0.75rem 0; font-weight:700; color:#92400e;">{{ $opname->reference_number }}</td>
                        </tr>
                        <tr style="border-bottom:1px dashed #e2e8f0;">
                            <td style="padding:0.75rem 0; color:#64748b;">Tanggal / Waktu</td>
                            <td style="padding:0.75rem 0; font-weight:600; color:#1e293b;">{{ $opname->created_at->format('d M Y H:i:s') }}</td>
                        </tr>
                        <tr style="border-bottom:1px dashed #e2e8f0;">
                            <td style="padding:0.75rem 0; color:#64748b;">Dicatat Oleh</td>
                            <td style="padding:0.75rem 0; font-weight:600; color:#1e293b;">{{ $opname->user?->name ?? 'Sistem' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:0.75rem 0; color:#64748b;">Catatan Opname</td>
                            <td style="padding:0.75rem 0; font-weight:500; color:#334155;">{{ $opname->notes ?: '-' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Kolom Kanan -->
                <div>
                    <h3 style="font-size:1rem; font-weight:700; color:#0f172a; margin-bottom:1rem; border-bottom:1px solid #e2e8f0; padding-bottom:0.5rem;">Informasi Penyesuaian</h3>
                    <table style="width:100%; font-size:0.875rem;">
                        <tr style="border-bottom:1px dashed #e2e8f0;">
                            <td style="padding:0.75rem 0; color:#64748b; width:140px;">Produk</td>
                            <td style="padding:0.75rem 0; font-weight:700; color:#1e293b;">
                                {{ $opname->product?->name ?? '-' }}<br>
                                <span style="font-size:0.75rem; color:#94a3b8; font-weight:400;">SKU: {{ $opname->product?->sku ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr style="border-bottom:1px dashed #e2e8f0;">
                            <td style="padding:0.75rem 0; color:#64748b;">Target Lokasi</td>
                            <td style="padding:0.75rem 0; font-weight:600; color:#1e293b;">
                                Gudang: {{ $opname->warehouse?->name ?? '-' }}<br>
                                <span style="font-size:0.75rem; color:#94a3b8; font-weight:400;">Rak: {{ $opname->location?->name ?? 'Semua Rak (Area Umum)' }}</span>
                            </td>
                        </tr>
                        <tr style="border-bottom:1px dashed #e2e8f0;">
                            <td style="padding:0.75rem 0; color:#64748b;">Selisih Stok</td>
                            <td style="padding:0.75rem 0;">
                                @if($opname->quantity > 0)
                                    <span style="display:inline-block; padding:0.3rem 0.65rem; border-radius:99px; background:#d1fae5; color:#065f46; font-weight:800; font-size:0.9rem;">+{{ $opname->quantity }}</span>
                                @elseif($opname->quantity < 0)
                                    <span style="display:inline-block; padding:0.3rem 0.65rem; border-radius:99px; background:#fee2e2; color:#991b1b; font-weight:800; font-size:0.9rem;">{{ $opname->quantity }}</span>
                                @else
                                    <span style="color:#64748b; font-weight:700;">Tidak ada perubahan</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end;">
                <form action="{{ route('gudang.opname.destroy', $opname) }}" method="POST" onsubmit="return confirm('Yakin membatalkan riwayat opname ini? Stok akan kembali ke jumlah sebelum penyesuaian.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" style="display:inline-flex; align-items:center; gap:0.5rem;">
                        🗑️ Batalkan Opname
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
