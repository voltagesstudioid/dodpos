@extends('layouts.app', ['title' => 'Dashboard Pasgar'])

@section('content')
<style>
/* Premium Dashboard Styling */
.ds-header {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    border-radius: 16px;
    padding: 28px 36px;
    color: white;
    margin-bottom: 24px;
    box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.3);
    position: relative;
    overflow: hidden;
}
.ds-header::before {
    content: '🦅';
    position: absolute;
    right: -20px;
    top: -40px;
    font-size: 150px;
    opacity: 0.1;
    transform: rotate(15deg);
}
.ds-title { font-size: 1.6rem; font-weight: 700; margin-bottom: 8px; letter-spacing:-0.02em; }
.ds-subtitle { font-size: 0.95rem; opacity: 0.9; font-weight: 400; }

.ds-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}
.ds-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    border: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.ds-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1);
}
.ds-card::after {
    content: '';
    position: absolute;
    top: 0; right: 0; width: 100px; height: 100px;
    border-radius: 0 16px 0 100px;
    opacity: 0.06;
}
.ds-card.indigo::after { background: #4f46e5; }
.ds-card.emerald::after { background: #10b981; }
.ds-card.blue::after { background: #3b82f6; }
.ds-card.amber::after { background: #f59e0b; }

.ds-card-title {
    font-size: 0.8rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 12px;
}
.ds-card-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #0f172a;
    font-family: ui-monospace, 'Cascadia Code', 'Fira Code', monospace;
    letter-spacing: -0.03em;
}
.ds-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 16px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}
.ds-card.indigo .ds-card-icon { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #4338ca; }
.ds-card.emerald .ds-card-icon { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #047857; }
.ds-card.blue .ds-card-icon { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1d4ed8; }
.ds-card.amber .ds-card-icon { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; }

.ds-section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    letter-spacing: -0.01em;
}

.ds-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
}
@media (max-width: 1024px) {
    .ds-layout { grid-template-columns: 1fr; }
}

/* Modern Table */
.ds-box {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03), 0 1px 3px -1px rgba(0,0,0,0.02);
    border: 1px solid #f1f5f9;
    overflow: hidden;
}
.ds-table {
    width: 100%;
    border-collapse: collapse;
}
.ds-table th {
    background: #f8fafc;
    padding: 14px 20px;
    text-align: left;
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e2e8f0;
}
.ds-table td {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.ds-table tr:last-child td { border-bottom: none; }
.ds-table tr:hover td { background: #f8fafc; }

.ds-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.ds-badge.amber { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
.ds-badge.emerald { background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; }
.ds-badge.blue { background: #eff6ff; color: #1e40af; border: 1px solid #dbeafe; }

.ds-empty {
    padding: 48px 20px;
    text-align: center;
    color: #94a3b8;
}

.ds-list-item {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.2s;
}
.ds-list-item:hover { background: #f8fafc; }
.ds-list-item:last-child { border-bottom: none; }

.ds-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 16px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    color: #334155;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.2s;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02);
}
.ds-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}
.ds-btn.primary {
    background: #4f46e5;
    border-color: #4f46e5;
    color: white;
}
.ds-btn.primary:hover {
    background: #4338ca;
    border-color: #4338ca;
    color: white;
}
</style>

<div class="page-container" style="padding-top: 24px; padding-bottom: 40px;">
    <!-- Header -->
    <div class="ds-header">
        <h1 class="ds-title">Dashboard Pasukan Garuda</h1>
        <p class="ds-subtitle">Ringkasan aktivitas operasional dan penjualan lapangan hari ini.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="border-radius:12px; margin-bottom:24px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Key Metrics Grid -->
    <div class="ds-grid">
        <div class="ds-card indigo">
            <div class="ds-card-icon">💰</div>
            <div class="ds-card-title">Penjualan Hari Ini</div>
            <div class="ds-card-value">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</div>
        </div>
        
        <div class="ds-card emerald">
            <div class="ds-card-icon">💵</div>
            <div class="ds-card-title">Setoran Terverifikasi</div>
            <div class="ds-card-value">Rp {{ number_format($totalSetoranHariIni, 0, ',', '.') }}</div>
        </div>

        <div class="ds-card blue">
            <div class="ds-card-icon">📦</div>
            <div class="ds-card-title">Loading Hari Ini</div>
            <div class="ds-card-value">{{ $loadingTodayCount }}</div>
            <div style="position:absolute; bottom:20px; right:24px; font-size:13px; color:#64748b; font-weight:600;">
                <span style="color:#1d4ed8">{{ $salesCount }}</span> Sales Aktif
            </div>
        </div>

        <div class="ds-card amber">
            <div class="ds-card-icon">⏳</div>
            <div class="ds-card-title">Setoran Menunggu</div>
            <div class="ds-card-value">{{ $setoranPendingCount }}</div>
            @if($setoranPendingCount > 0)
            <a href="{{ route('pasgar.setoran.index') }}" style="position:absolute; bottom:20px; right:24px; font-size:12px; color:#d97706; font-weight:700; text-decoration:none; display:flex; align-items:center; gap:4px;">
                Cek <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            @endif
        </div>
    </div>

    <!-- Layout: Left (Table) + Right (Pending Setoran & Actions) -->
    <div class="ds-layout">
        
        <!-- Left Column: Recent Transactions -->
        <div>
            <div class="ds-section-title">
                ⚡ Transaksi Penjualan Terbaru
            </div>
            <div class="ds-box">
                @if($recentTransactions->count() > 0)
                <table class="ds-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Sales</th>
                            <th>Pelanggan</th>
                            <th>Bayar</th>
                            <th style="text-align:right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $trx)
                        <tr>
                            <td>
                                <div style="font-weight:700; color:#0f172a;">{{ $trx->tanggal->format('H:i') }}</div>
                                <div style="font-size:12px; color:#64748b; font-family:monospace; margin-top:2px;">{{ $trx->nomor_transaksi }}</div>
                            </td>
                            <td>
                                <span class="ds-badge blue">{{ $trx->sales->nama ?? '-' }}</span>
                            </td>
                            <td>
                                <div style="font-weight:600; color:#334155; font-size:0.85rem;">
                                    {{ $trx->pelanggan->nama_toko ?? $trx->nama_pelanggan ?? '-' }}
                                </div>
                            </td>
                            <td>
                                @if($trx->metode_bayar === 'kredit')
                                    <span class="ds-badge amber">Kredit</span>
                                @else
                                    <span class="ds-badge emerald">{{ ucfirst($trx->metode_bayar) }}</span>
                                @endif
                            </td>
                            <td style="text-align:right; font-weight:700; color:#0f172a; font-family:monospace; font-size:14px;">
                                {{ number_format($trx->total, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding:14px; text-align:center; border-top:1px solid #f1f5f9; background:#f8fafc;">
                    <a href="{{ route('pasgar.penjualan.index') }}" style="font-size:13px; font-weight:700; color:#4f46e5; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:4px;">
                        Lihat Semua Penjualan <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
                @else
                <div class="ds-empty">
                    <div style="font-size:48px; margin-bottom:16px;">📭</div>
                    <div style="font-weight:700; color:#1e293b; font-size:1.1rem; margin-bottom:4px;">Belum ada penjualan hari ini</div>
                    <div style="font-size:0.9rem;">Transaksi dari tim lapangan akan otomatis muncul di sini.</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Actions & Pending Setoran -->
        <div>
            <!-- Quick Actions -->
            <div class="ds-section-title">🚀 Aksi Cepat</div>
            <div class="ds-box" style="padding:20px; display:flex; flex-direction:column; gap:12px; margin-bottom:28px;">
                <a href="{{ route('pasgar.loading.create') }}" class="ds-btn primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Buat Loading Barang Baru
                </a>
                <a href="{{ route('pasgar.sales.index') }}" class="ds-btn">
                    👥 Kelola Data Sales
                </a>
            </div>

            <div class="ds-section-title">⏱️ Setoran Menunggu Verifikasi</div>
            <div class="ds-box" style="margin-bottom:28px;">
                @if($pendingSetorans->count() > 0)
                    @foreach($pendingSetorans as $setoran)
                    <div class="ds-list-item">
                        <div>
                            <div style="font-weight:700; color:#0f172a; font-size:14px; margin-bottom:2px;">{{ $setoran->sales->nama ?? '-' }}</div>
                            <div style="font-size:12px; color:#64748b; display:flex; align-items:center; gap:4px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                {{ $setoran->tanggal->format('d M Y') }}
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-weight:800; color:#d97706; font-family:monospace; font-size:14px; margin-bottom:4px;">Rp {{ number_format($setoran->total_setor, 0, ',', '.') }}</div>
                            <a href="{{ route('pasgar.setoran.show', $setoran->id) }}" style="font-size:11px; font-weight:700; color:#4f46e5; text-decoration:none; display:inline-flex; align-items:center; gap:2px; background:#eef2ff; padding:2px 8px; border-radius:4px;">
                                Tinjau <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                    @if($setoranPendingCount > 5)
                    <div style="padding:12px; text-align:center; border-top:1px solid #f1f5f9; background:#f8fafc;">
                        <a href="{{ route('pasgar.setoran.index') }}" style="font-size:12px; font-weight:600; color:#64748b; text-decoration:none;">Tampilkan semua ({{ $setoranPendingCount }})</a>
                    </div>
                    @endif
                @else
                    <div class="ds-empty" style="padding:36px 20px;">
                        <div style="font-size:36px; margin-bottom:12px; color:#10b981;">✅</div>
                        <div style="font-weight:600; color:#334155;">Semua beres!</div>
                        <div style="font-size:13px;">Tidak ada setoran yang menunggu.</div>
                    </div>
                @endif
            </div>

            <!-- Reports -->
            <div class="ds-section-title">📊 Laporan & Analitik</div>
            <div class="ds-box" style="padding:10px;">
                <a href="{{ route('pasgar.laporan.penjualan') }}" class="ds-list-item" style="border:none; border-radius:10px;">
                    <div style="display:flex; align-items:center; gap:16px;">
                        <div style="width:40px; height:40px; border-radius:10px; background:#f0fdf4; display:flex; align-items:center; justify-content:center; font-size:18px;">📈</div>
                        <div>
                            <div style="font-weight:700; font-size:13px; color:#1e293b; margin-bottom:2px;">Laporan Penjualan</div>
                            <div style="font-size:11.5px; color:#64748b;">Rekap data penjualan tiap sales</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('pasgar.laporan.setoran') }}" class="ds-list-item" style="border:none; border-radius:10px; margin-top:4px;">
                    <div style="display:flex; align-items:center; gap:16px;">
                        <div style="width:40px; height:40px; border-radius:10px; background:#eff6ff; display:flex; align-items:center; justify-content:center; font-size:18px;">💵</div>
                        <div>
                            <div style="font-weight:700; font-size:13px; color:#1e293b; margin-bottom:2px;">Laporan Setoran</div>
                            <div style="font-size:11.5px; color:#64748b;">Verifikasi nominal dan selisih</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
