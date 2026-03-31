<x-app-layout>
    <x-slot name="header">Dashboard Pembelian</x-slot>

    <div class="page-container">
        {{-- Header --}}
        <div class="ph animate-in">
            <div class="ph-left">
                <div class="ph-icon blue">📊</div>
                <div>
                    <h1 class="ph-title">Dashboard Pembelian</h1>
                    <p class="ph-subtitle">Ringkasan dan analisis data pembelian</p>
                </div>
            </div>
            <div class="ph-actions">
                <form method="GET" style="display:flex; gap:0.5rem;">
                    <select name="period" class="form-input" onchange="this.form.submit()">
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.5rem;">
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="width:56px;height:56px;border-radius:16px;background:#e0e7ff;display:flex;align-items:center;justify-content:center;font-size:1.75rem;">📋</div>
                    <div>
                        <div style="font-size:2rem;font-weight:800;color:#4f46e5;">{{ number_format($stats['total_po']) }}</div>
                        <div style="font-size:0.875rem;color:#64748b;">Total PO</div>
                    </div>
                </div>
            </div>
            <div class="card" style="padding:1.5rem;">
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="width:56px;height:56px;border-radius:16px;background:#dcfce7;display:flex;align-items:center;justify-content:center;font-size:1.75rem;">✅</div>
                    <div>
                        <div style="font-size:2rem;font-weight:800;color:#16a34a;">{{ number_format($stats['completed']) }}</div>
                        <div style="font-size:0.875rem;color:#64748b;">PO Selesai</div>
                    </div>
                </div>
            </div>
            <div class="card" style="padding:1.5rem;">
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="width:56px;height:56px;border-radius:16px;background:#fee2e2;display:flex;align-items:center;justify-content:center;font-size:1.75rem;">⚠️</div>
                    <div>
                        <div style="font-size:2rem;font-weight:800;color:#dc2626;">{{ number_format($stats['late']) }}</div>
                        <div style="font-size:0.875rem;color:#64748b;">PO Terlambat</div>
                    </div>
                </div>
            </div>
            <div class="card" style="padding:1.5rem;">
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="width:56px;height:56px;border-radius:16px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:1.75rem;">💰</div>
                    <div>
                        <div style="font-size:1.5rem;font-weight:800;color:#d97706;">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</div>
                        <div style="font-size:0.875rem;color:#64748b;">Nilai Total</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(350px, 1fr)); gap:1.5rem;">
            {{-- Chart Section --}}
            <div class="card" style="grid-column:span 2;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <div style="font-size:1rem;font-weight:700;color:#1e293b;">📈 Grafik PO per Hari</div>
                    <div style="font-size:0.75rem;color:#64748b;">Jumlah dan nilai PO yang dibuat</div>
                </div>
                <div style="padding:1.5rem; height:300px; position:relative;">
                    @if($chartData->isEmpty())
                        <div style="display:flex; align-items:center; justify-content:center; height:100%; color:#94a3b8;">
                            Tidak ada data untuk periode ini
                        </div>
                    @else
                        <canvas id="poChart"></canvas>
                    @endif
                </div>
            </div>

            {{-- Top Suppliers --}}
            <div class="card">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                    <div style="font-size:1rem;font-weight:700;color:#1e293b;">🏆 Top 5 Supplier</div>
                    <div style="font-size:0.75rem;color:#64748b;">Berdasarkan nilai transaksi</div>
                </div>
                <div style="padding:1rem 0;">
                    @forelse($topSuppliers as $i => $s)
                        <div style="display:flex; align-items:center; gap:1rem; padding:0.75rem 1.5rem; {{ $i < count($topSuppliers) - 1 ? 'border-bottom:1px solid #f1f5f9;' : '' }}">
                            <div style="width:32px;height:32px;border-radius:50%;background:{{ $i < 3 ? '#4f46e5' : '#94a3b8' }};color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.875rem;">
                                {{ $i + 1 }}
                            </div>
                            <div style="flex:1;">
                                <div style="font-weight:600;color:#1e293b;">{{ $s->supplier->name ?? '-' }}</div>
                                <div style="font-size:0.75rem;color:#64748b;">{{ $s->po_count }} PO</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-weight:700;color:#1e293b;">Rp {{ number_format($s->total_value, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:2rem; text-align:center; color:#94a3b8;">
                            Belum ada data supplier
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="card">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-size:1rem;font-weight:700;color:#1e293b;">🛒 PO Terbaru</div>
                        <div style="font-size:0.75rem;color:#64748b;">5 pembelian terakhir</div>
                    </div>
                    <a href="{{ route('pembelian.order') }}" class="btn-secondary btn-sm">Lihat Semua</a>
                </div>
                <div style="padding:0;">
                    @forelse($recentOrders as $order)
                        <div style="display:flex; align-items:center; gap:1rem; padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9;">
                            <div style="flex:1;">
                                <div style="font-weight:600;color:#1e293b;">{{ $order->po_number }}</div>
                                <div style="font-size:0.75rem;color:#64748b;">{{ $order->supplier->name ?? '-' }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-weight:700;color:#1e293b;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                <span style="display:inline-block;padding:0.2rem 0.5rem;border-radius:99px;font-size:0.6875rem;font-weight:600;" style="background:{{ $order->statusLabel['bg'] }};color:{{ $order->statusLabel['color'] }};">
                                    {{ $order->statusLabel['label'] }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div style="padding:2rem; text-align:center; color:#94a3b8;">
                            Belum ada PO
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Late Orders Alert --}}
            <div class="card" style="border-left:4px solid #dc2626;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-size:1rem;font-weight:700;color:#dc2626;">⚠️ PO Terlambat</div>
                        <div style="font-size:0.75rem;color:#64748b;">Perlu ditindaklanjuti</div>
                    </div>
                    <a href="{{ route('pembelian.order', ['status' => 'ordered']) }}" class="btn-secondary btn-sm">Lihat Semua</a>
                </div>
                <div style="padding:0;">
                    @forelse($lateOrders as $order)
                        <div style="display:flex; align-items:center; gap:1rem; padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; background:#fff7f7;">
                            <div style="flex:1;">
                                <div style="font-weight:600;color:#1e293b;">{{ $order->po_number }}</div>
                                <div style="font-size:0.75rem;color:#64748b;">{{ $order->supplier->name ?? '-' }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:0.75rem;color:#dc2626;font-weight:600;">
                                    Est: {{ $order->expected_date->format('d/m/Y') }}
                                </div>
                                <div style="font-size:0.6875rem;color:#dc2626;">
                                    {{ $order->expected_date->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:2rem; text-align:center; color:#16a34a;">
                            ✅ Tidak ada PO terlambat
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Quick Links --}}
        <div style="margin-top:1.5rem;">
            <div style="font-size:1rem;font-weight:700;color:#1e293b; margin-bottom:1rem;">⚡ Akses Cepat</div>
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
                <a href="{{ route('pembelian.order') }}" class="card" style="padding:1.25rem; text-decoration:none; display:flex; align-items:center; gap:1rem; transition:all 0.2s;">
                    <div style="width:48px;height:48px;border-radius:12px;background:#e0e7ff;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🛒</div>
                    <div>
                        <div style="font-weight:700;color:#1e293b;">Purchase Order</div>
                        <div style="font-size:0.75rem;color:#64748b;">Kelola PO & penerimaan</div>
                    </div>
                </a>
                <a href="{{ route('pembelian.hutang.index') }}" class="card" style="padding:1.25rem; text-decoration:none; display:flex; align-items:center; gap:1rem; transition:all 0.2s;">
                    <div style="width:48px;height:48px;border-radius:12px;background:#fee2e2;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">💳</div>
                    <div>
                        <div style="font-weight:700;color:#1e293b;">Hutang Supplier</div>
                        <div style="font-size:0.75rem;color:#64748b;">Pembayaran & histori</div>
                    </div>
                </a>
                <a href="{{ route('pembelian.retur.index') }}" class="card" style="padding:1.25rem; text-decoration:none; display:flex; align-items:center; gap:1rem; transition:all 0.2s;">
                    <div style="width:48px;height:48px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🔄</div>
                    <div>
                        <div style="font-weight:700;color:#1e293b;">Retur Pembelian</div>
                        <div style="font-size:0.75rem;color:#64748b;">Pengembalian barang</div>
                    </div>
                </a>
                <a href="{{ route('pembelian.receipts_followup.index') }}" class="card" style="padding:1.25rem; text-decoration:none; display:flex; align-items:center; gap:1rem; transition:all 0.2s;">
                    <div style="width:48px;height:48px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">⚠️</div>
                    <div>
                        <div style="font-weight:700;color:#1e293b;">QC Follow-up</div>
                        <div style="font-size:0.75rem;color:#64748b;">Tindak lanjut penerimaan</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @if(!$chartData->isEmpty())
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('poChart').getContext('2d');
        const chartData = @json($chartData);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.map(d => {
                    const date = new Date(d.date);
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                }),
                datasets: [{
                    label: 'Jumlah PO',
                    data: chartData.map(d => d.count),
                    backgroundColor: '#4f46e5',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
    @endif
</x-app-layout>
