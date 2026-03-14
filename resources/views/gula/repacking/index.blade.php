<x-app-layout>
    <x-slot name="header">Repacking & Susut (Gula)</x-slot>

    <div class="page-container">
        <div class="page-header">
            <div>
                <div class="page-header-title">Riwayat Konversi Stok</div>
                <div class="page-header-subtitle">Pembongkaran karung → eceran, termasuk susut/rusak</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('gula.stok.index') }}" class="btn-secondary">📦 Stok Gudang</a>
                <a href="{{ route('gula.repacking.create') }}" class="btn-primary">➕ Proses Repacking</a>
            </div>
        </div>

        @if(session('success')) <div class="alert alert-success" role="alert">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div> @endif

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon indigo">🧾</div>
                <div>
                    <div class="stat-label">Total Aktivitas</div>
                    <div class="stat-value indigo">{{ number_format((int) ($totalCount ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-gray">Sesuai filter</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon rose">📦</div>
                <div>
                    <div class="stat-label">Karung Dibongkar</div>
                    <div class="stat-value rose">{{ number_format((float) ($totalKarung ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-danger">Krg</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon emerald">🍬</div>
                <div>
                    <div class="stat-label">Eceran Dihasilkan</div>
                    <div class="stat-value emerald">{{ number_format((float) ($totalEceran ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-success">Bks</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon amber">⚠️</div>
                <div>
                    <div class="stat-label">Susut / Rusak</div>
                    <div class="stat-value amber">{{ number_format((float) ($totalSusut ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-warning">Bks</span>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Pencarian</div>
                    <div class="panel-subtitle">Filter berdasarkan produk, tanggal, atau operator</div>
                </div>
                <span class="badge badge-blue">{{ number_format((int) ($todayCount ?? 0), 0, ',', '.') }} hari ini</span>
            </div>
            <div class="panel-body">
                <form method="GET" action="{{ route('gula.repacking.index') }}" class="form-row">
                    <div>
                        <label class="form-label">Kata Kunci</label>
                        <input name="q" value="{{ request('q') }}" class="form-input" placeholder="Produk / operator / catatan">
                    </div>
                    <div>
                        <label class="form-label">Produk</label>
                        @php $productId = request('product_id'); @endphp
                        <select name="product_id" class="form-input">
                            <option value="" {{ $productId === null || $productId === '' ? 'selected' : '' }}>Semua</option>
                            @foreach(($productOptions ?? collect()) as $p)
                                <option value="{{ $p->id }}" {{ (string) $productId === (string) $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Dari</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Sampai</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
                    </div>
                    <div style="display:flex;gap:0.5rem;align-items:flex-end;flex-wrap:wrap;">
                        <button type="submit" class="btn-primary">🔎 Terapkan</button>
                        <a href="{{ route('gula.repacking.index') }}" class="btn-secondary">↺ Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Riwayat Repacking</div>
                    <div class="panel-subtitle">Catatan konversi dan susut</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-wrapper">
                    <table class="data-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th style="text-align:right;">Bongkar</th>
                                <th style="text-align:right;">Hasil</th>
                                <th style="text-align:right;">Susut</th>
                                <th>Operator</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($repackings as $log)
                                <tr>
                                    <td style="white-space:nowrap;color:#64748b;">{{ \Carbon\Carbon::parse($log->date)->format('d/m/Y') }}</td>
                                    <td>
                                        <div style="display:flex;flex-direction:column;gap:0.25rem;">
                                            <div style="font-weight:900;color:#0f172a;">{{ $log->product->name ?? '-' }}</div>
                                            @if($log->notes)
                                                <div style="color:#64748b;font-size:0.75rem;line-height:1.2;">{{ $log->notes }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="text-align:right;">
                                        <span class="badge badge-danger">- {{ number_format((float) $log->minus_qty_karung, 0, ',', '.') }} Krg</span>
                                    </td>
                                    <td style="text-align:right;">
                                        <span class="badge badge-success">+ {{ number_format((float) $log->plus_qty_eceran, 0, ',', '.') }} Bks</span>
                                    </td>
                                    <td style="text-align:right;">
                                        @if((float) $log->loss_qty_eceran > 0)
                                            <span class="badge badge-warning">{{ number_format((float) $log->loss_qty_eceran, 0, ',', '.') }} Bks</span>
                                        @else
                                            <span style="color:#94a3b8;">-</span>
                                        @endif
                                    </td>
                                    <td style="color:#64748b;">{{ $log->user->name ?? 'Admin' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding: 2.25rem;">
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                            <div style="font-size:2rem;">✂️</div>
                                            <div style="font-weight:900;color:#0f172a;">Belum ada catatan repacking</div>
                                            <div style="font-size:0.875rem;text-align:center;max-width:560px;">
                                                Proses repacking untuk mengkonversi stok karung menjadi eceran di gudang induk.
                                            </div>
                                            <a href="{{ route('gula.repacking.create') }}" class="btn-primary">➕ Proses Repacking Baru</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 1rem;">
                    {{ $repackings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
