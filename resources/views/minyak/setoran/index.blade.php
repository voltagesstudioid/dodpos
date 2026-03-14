<x-app-layout>
<x-slot name="header">Minyak — Setoran & Retur</x-slot>

<div class="page-container animate-in">

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    {{-- Filter + Tombol Tambah --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:12px;margin-bottom:1.5rem;">
        <form method="GET" action="{{ route('minyak.setoran.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <div>
                <label style="font-size:0.8rem;font-weight:600;color:#374151;display:block;margin-bottom:3px;">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                    style="padding:8px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:0.9rem;">
            </div>
            <div>
                <label style="font-size:0.8rem;font-weight:600;color:#374151;display:block;margin-bottom:3px;">Sales</label>
                <select name="sales_id" style="padding:8px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:0.9rem;background:#fff;">
                    <option value="">Semua Sales</option>
                    @foreach($salesList as $s)
                        <option value="{{ $s->id }}" {{ $salesId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-top:22px;">
                <button type="submit" style="padding:8px 18px;background:#f97316;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                    🔍 Filter
                </button>
            </div>
        </form>

        <a href="{{ route('minyak.setoran.create') }}"
            style="padding:10px 20px;background:#f97316;color:#fff;border-radius:8px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            ➕ Tambah Setoran
        </a>
    </div>

    {{-- Summary Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
        <div style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;border-radius:12px;padding:1.25rem;">
            <div style="font-size:1.8rem;font-weight:800;">Rp {{ number_format($totalSetoran, 0, ',', '.') }}</div>
            <div style="font-size:0.8rem;opacity:.85;margin-top:4px;">💰 Total Setoran</div>
        </div>
        <div style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border-radius:12px;padding:1.25rem;">
            <div style="font-size:1.8rem;font-weight:800;">Rp {{ number_format($totalRetur, 0, ',', '.') }}</div>
            <div style="font-size:0.8rem;opacity:.85;margin-top:4px;">↩️ Total Retur</div>
        </div>
        <div style="background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border-radius:12px;padding:1.25rem;">
            <div style="font-size:1.8rem;font-weight:800;">Rp {{ number_format($totalSetoran - $totalRetur, 0, ',', '.') }}</div>
            <div style="font-size:0.8rem;opacity:.85;margin-top:4px;">✅ Bersih Diterima</div>
        </div>
        <div style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-radius:12px;padding:1.25rem;">
            <div style="font-size:1.8rem;font-weight:800;">{{ $setorans->count() }}</div>
            <div style="font-size:0.8rem;opacity:.85;margin-top:4px;">📋 Jumlah Transaksi</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="form-card" style="padding:0;">
        <div class="form-card-header" style="border-bottom:1px solid #f1f5f9;">
            <div class="form-card-icon" style="background:#fff7ed;">💰</div>
            <div>
                <div class="form-card-title">Daftar Setoran & Retur</div>
                <div class="form-card-subtitle">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</div>
            </div>
        </div>
        @if($setorans->isEmpty())
            <div style="text-align:center;padding:3rem;color:#94a3b8;">
                <div style="font-size:3rem;">📭</div>
                <div style="margin-top:12px;font-size:1rem;">Belum ada data setoran untuk tanggal ini</div>
                <a href="{{ route('minyak.setoran.create') }}"
                    style="margin-top:16px;display:inline-block;padding:10px 20px;background:#f97316;color:#fff;border-radius:8px;font-weight:600;text-decoration:none;">
                    ➕ Tambah Setoran
                </a>
            </div>
        @else
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sales</th>
                        <th>Kendaraan</th>
                        <th style="text-align:right;">Setoran (Rp)</th>
                        <th style="text-align:right;">Retur (Rp)</th>
                        <th style="text-align:right;">Bersih (Rp)</th>
                        <th>Catatan</th>
                        <th>Dicatat</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($setorans as $i => $s)
                    <tr>
                        <td style="color:#94a3b8;">{{ $i + 1 }}</td>
                        <td style="font-weight:700;">👤 {{ $s->sales_name }}</td>
                        <td style="font-size:0.85rem;color:#374151;">{{ $s->kendaraan ?? '-' }}</td>
                        <td style="text-align:right;font-weight:700;color:#10b981;">
                            {{ number_format($s->jumlah_setoran, 0, ',', '.') }}
                        </td>
                        <td style="text-align:right;color:{{ $s->jumlah_retur > 0 ? '#f59e0b' : '#94a3b8' }};font-weight:{{ $s->jumlah_retur > 0 ? '700' : '400' }};">
                            {{ number_format($s->jumlah_retur, 0, ',', '.') }}
                        </td>
                        <td style="text-align:right;font-weight:700;color:#2563eb;">
                            {{ number_format($s->jumlah_setoran - $s->jumlah_retur, 0, ',', '.') }}
                        </td>
                        <td style="font-size:0.82rem;color:#64748b;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $s->catatan ?: '-' }}
                        </td>
                        <td style="font-size:0.8rem;color:#94a3b8;">
                            {{ \Carbon\Carbon::parse($s->created_at)->format('H:i') }}
                        </td>
                        <td style="text-align:center;">
                            <form action="{{ route('minyak.setoran.destroy', $s->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus data setoran ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="background:#fee2e2;color:#991b1b;border:none;padding:5px 12px;border-radius:6px;cursor:pointer;font-size:0.8rem;font-weight:600;">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#f8fafc;">
                        <td colspan="3" style="font-weight:700;padding:12px 16px;">TOTAL</td>
                        <td style="text-align:right;font-weight:700;color:#10b981;padding:12px 8px;">
                            {{ number_format($totalSetoran, 0, ',', '.') }}
                        </td>
                        <td style="text-align:right;font-weight:700;color:#f59e0b;padding:12px 8px;">
                            {{ number_format($totalRetur, 0, ',', '.') }}
                        </td>
                        <td style="text-align:right;font-weight:700;color:#2563eb;padding:12px 8px;">
                            {{ number_format($totalSetoran - $totalRetur, 0, ',', '.') }}
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
    </div>

</div>
</x-app-layout>
