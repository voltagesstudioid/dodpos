<x-app-layout>
<x-slot name="header">Minyak — Dashboard Tangki & Penjualan</x-slot>

<div class="page-container animate-in">

    {{-- Date Filter --}}
    <form method="GET" action="{{ route('minyak.dashboard') }}" style="display:flex;align-items:center;gap:12px;margin-bottom:1.5rem;flex-wrap:wrap;">
        <label style="font-weight:600;font-size:0.9rem;color:#374151;">📅 Tanggal</label>
        <input type="date" name="tanggal" value="{{ $tanggal }}"
            style="padding:8px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:0.9rem;"
            onchange="this.form.submit()">
        <span style="font-size:0.85rem;color:#64748b;">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span>
    </form>

    {{-- Summary Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;">

        <div class="stat-card" style="background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;border-radius:14px;padding:1.5rem;box-shadow:0 4px 15px rgba(249,115,22,.3);">
            <div style="font-size:2.2rem;font-weight:800;">{{ number_format($totalTerjualHariIni, 1) }}</div>
            <div style="font-size:0.85rem;opacity:.85;margin-top:4px;">Total Terjual Hari Ini (semua unit)</div>
            <div style="font-size:1.6rem;margin-top:6px;">🛢️</div>
        </div>

        <div class="stat-card" style="background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border-radius:14px;padding:1.5rem;box-shadow:0 4px 15px rgba(59,130,246,.3);">
            <div style="font-size:2.2rem;font-weight:800;">{{ $armada->count() }}</div>
            <div style="font-size:0.85rem;opacity:.85;margin-top:4px;">Jumlah Armada Aktif</div>
            <div style="font-size:1.6rem;margin-top:6px;">🚛</div>
        </div>

        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;border-radius:14px;padding:1.5rem;box-shadow:0 4px 15px rgba(16,185,129,.3);">
            <div style="font-size:2.2rem;font-weight:800;">{{ $penjualanHariIni->count() }}</div>
            <div style="font-size:0.85rem;opacity:.85;margin-top:4px;">Jenis Produk Terjual</div>
            <div style="font-size:1.6rem;margin-top:6px;">🧴</div>
        </div>

    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;flex-wrap:wrap;">

        {{-- Penjualan per Produk --}}
        <div class="form-card" style="grid-column:span 1;">
            <div class="form-card-header">
                <div class="form-card-icon" style="background:#fff3e0;">🛢️</div>
                <div>
                    <div class="form-card-title">Penjualan per Produk</div>
                    <div class="form-card-subtitle">Akumulasi seluruh armada hari ini</div>
                </div>
            </div>
            <div class="form-card-body" style="padding:0;">
                @if($penjualanHariIni->isEmpty())
                    <div style="text-align:center;padding:2rem;color:#94a3b8;">
                        <div style="font-size:2rem;">📭</div>
                        <div style="margin-top:8px;">Belum ada penjualan hari ini</div>
                    </div>
                @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th style="text-align:center;">Terjual</th>
                            <th style="text-align:center;">Armada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualanHariIni as $item)
                        <tr>
                            <td style="font-weight:600;">{{ $item->name }}</td>
                            <td style="text-align:center;font-weight:700;color:#f97316;">
                                @if(($maskStock ?? false) === true)
                                    Terkunci
                                @else
                                    {{ number_format($item->total_qty, 1) }}
                                @endif
                                <span style="font-size:0.75rem;color:#94a3b8;">{{ $item->unit }}</span>
                            </td>
                            <td style="text-align:center;">
                                <span style="background:#dbeafe;color:#1d4ed8;padding:3px 10px;border-radius:12px;font-size:0.8rem;font-weight:600;">
                                    {{ $item->jumlah_armada }} armada
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        {{-- Stok per Kendaraan --}}
        <div class="form-card" style="grid-column:span 1;">
            <div class="form-card-header">
                <div class="form-card-icon" style="background:#e0f2fe;">🚛</div>
                <div>
                    <div class="form-card-title">Stok di Kendaraan</div>
                    <div class="form-card-subtitle">Sisa stok saat ini di tiap armada</div>
                </div>
            </div>
            <div class="form-card-body" style="padding:0;">
                @if($stokKendaraan->isEmpty())
                    <div style="text-align:center;padding:2rem;color:#94a3b8;">
                        <div style="font-size:2rem;">🚫</div>
                        <div style="margin-top:8px;">Tidak ada stok di kendaraan</div>
                    </div>
                @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kendaraan</th>
                            <th>Produk</th>
                            <th style="text-align:center;">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stokKendaraan as $s)
                        <tr>
                            <td style="font-weight:600;font-size:0.85rem;color:#374151;">{{ $s->kendaraan }}</td>
                            <td style="font-size:0.85rem;">{{ $s->produk }}</td>
                            <td style="text-align:center;font-weight:700;">
                                @if(($maskStock ?? false) === true)
                                    Terkunci
                                @else
                                    {{ number_format($s->stock, 1) }}
                                @endif
                                <span style="font-size:0.75rem;color:#94a3b8;">{{ $s->satuan }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

    </div>

    {{-- Ringkasan Armada --}}
    <div class="form-card" style="margin-top:1.5rem;">
        <div class="form-card-header">
            <div class="form-card-icon" style="background:#fce7f3;">⛽</div>
            <div>
                <div class="form-card-title">Ringkasan Armada</div>
                <div class="form-card-subtitle">Status semua kendaraan sales</div>
            </div>
        </div>
        <div class="form-card-body" style="padding:0;">
            @if($armada->isEmpty())
                <div style="text-align:center;padding:2rem;color:#94a3b8;">Tidak ada armada terdaftar</div>
            @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kendaraan</th>
                        <th>Gudang Virtual</th>
                        <th style="text-align:center;">Jenis Produk</th>
                        <th style="text-align:center;">Total Stok</th>
                        <th style="text-align:center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($armada as $a)
                    <tr>
                        <td style="font-weight:700;">🚛 {{ $a->kendaraan }}</td>
                        <td style="font-size:0.85rem;color:#64748b;">{{ $a->gudang_virtual }}</td>
                        <td style="text-align:center;">{{ $a->jenis_produk }}</td>
                        <td style="text-align:center;font-weight:700;">
                            @if(($maskStock ?? false) === true)
                                Terkunci
                            @else
                                {{ number_format($a->total_stok, 1) }}
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($a->total_stok > 0)
                                <span style="background:#dcfce7;color:#166534;padding:3px 12px;border-radius:12px;font-size:0.8rem;font-weight:600;">Beroperasi</span>
                            @else
                                <span style="background:#f1f5f9;color:#94a3b8;padding:3px 12px;border-radius:12px;font-size:0.8rem;font-weight:600;">Kosong</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

</div>
</x-app-layout>
