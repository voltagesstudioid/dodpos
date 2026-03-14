<x-app-layout>
    <x-slot name="header">
        Stok Gudang Induk (Gula)
    </x-slot>

    <div class="page-container">
        <div class="page-header">
            <div>
                <div class="page-header-title">Manajemen Master Produk Gula</div>
                <div class="page-header-subtitle">Kelola stok gudang induk, harga, dan status aktif produk</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('gula.dashboard') }}" class="btn-secondary">📊 Dashboard</a>
                <a href="{{ route('gula.stok.create') }}" class="btn-primary">➕ Tambah Produk</a>
            </div>
        </div>

        @if(session('success')) <div class="alert alert-success" role="alert">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div> @endif

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon indigo">🧾</div>
                <div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-value indigo">{{ number_format((int) ($totalProducts ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-gray">Sesuai filter</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">📦</div>
                <div>
                    <div class="stat-label">Stok Karung</div>
                    <div class="stat-value blue">{{ number_format((int) ($totalKarung ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-blue">Karung</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon indigo">📦</div>
                <div>
                    <div class="stat-label">Stok Bal</div>
                    <div class="stat-value indigo">{{ number_format((int) ($totalBal ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-indigo">Bal</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon emerald">🍬</div>
                <div>
                    <div class="stat-label">Stok Eceran</div>
                    <div class="stat-value emerald">{{ number_format((int) ($totalEceran ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-success">Bks (1kg)</span>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Pencarian</div>
                    <div class="panel-subtitle">Cari produk dan filter status</div>
                </div>
            </div>
            <div class="panel-body">
                <form method="GET" action="{{ route('gula.stok.index') }}" class="form-row">
                    <div>
                        <label class="form-label">Kata Kunci</label>
                        <input name="q" value="{{ request('q') }}" class="form-input" placeholder="Contoh: Gula Pasir, Premium">
                    </div>
                    <div>
                        <label class="form-label">Tipe</label>
                        @php $type = request('type'); @endphp
                        <select name="type" class="form-input">
                            <option value="" {{ $type === null || $type === '' ? 'selected' : '' }}>Semua</option>
                            <option value="karungan" {{ $type === 'karungan' ? 'selected' : '' }}>Karungan</option>
                            <option value="eceran" {{ $type === 'eceran' ? 'selected' : '' }}>Eceran</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Aktif</label>
                        @php $active = request('active'); @endphp
                        <select name="active" class="form-input">
                            <option value="" {{ $active === null || $active === '' ? 'selected' : '' }}>Semua</option>
                            <option value="1" {{ $active === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $active === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div style="display:flex;gap:0.5rem;align-items:flex-end;flex-wrap:wrap;">
                        <button type="submit" class="btn-primary">🔎 Terapkan</button>
                        <a href="{{ route('gula.stok.index') }}" class="btn-secondary">↺ Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Daftar Produk</div>
                    <div class="panel-subtitle">Stok gudang induk dan harga jual</div>
                </div>
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
                                <th style="text-align:right;">Harga</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                @php
                                    $stock = $product->warehouseStocks->first();
                                    $qtyKarung = (int) ($stock->qty_karung ?? 0);
                                    $qtyBal = (int) ($stock->qty_bal ?? 0);
                                    $qtyEceran = (int) ($stock->qty_eceran ?? 0);
                                @endphp
                                <tr>
                                    <td>
                                        <div style="display:flex;flex-direction:column;gap:0.35rem;">
                                            <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
                                                <span style="font-weight:900;color:#0f172a;">{{ $product->name }}</span>
                                                @if($product->is_active)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-gray">Nonaktif</span>
                                                @endif
                                            </div>
                                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                                                <span class="badge badge-gray">{{ ucfirst($product->type) }}</span>
                                                <span class="badge badge-indigo">Isi: {{ number_format((int) $product->qty_per_karung, 0, ',', '.') }} bks</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align:right;"><span class="badge badge-blue">{{ number_format($qtyKarung, 0, ',', '.') }}</span></td>
                                    <td style="text-align:right;"><span class="badge badge-indigo">{{ number_format($qtyBal, 0, ',', '.') }}</span></td>
                                    <td style="text-align:right;"><span class="badge badge-success">{{ number_format($qtyEceran, 0, ',', '.') }}</span></td>
                                    <td style="text-align:right;">
                                        <div style="font-weight:900;color:#0f172a;">Rp {{ number_format((float) $product->base_price, 0, ',', '.') }}</div>
                                        <div style="color:#64748b;font-size:0.75rem;line-height:1.2;margin-top:0.25rem;">
                                            Jual K: Rp {{ number_format((float) $product->price_karungan, 0, ',', '.') }}<br>
                                            Jual E: Rp {{ number_format((float) $product->price_eceran, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td style="text-align:right;">
                                        <div style="display:inline-flex;gap:0.5rem;flex-wrap:wrap;justify-content:flex-end;">
                                            <a href="{{ route('gula.stok.edit', $product->id) }}" class="btn-primary">✏️ Edit</a>
                                            <form action="{{ route('gula.stok.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus master produk ini?');" style="margin:0;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger">🗑️ Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding: 2.25rem;">
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                            <div style="font-size:2rem;">📭</div>
                                            <div style="font-weight:900;color:#0f172a;">Belum ada data Master Produk Gula</div>
                                            <div style="font-size:0.875rem;text-align:center;max-width:560px;">
                                                Tambahkan produk gula untuk mulai mencatat stok gudang induk dan loading armada.
                                            </div>
                                            <a href="{{ route('gula.stok.create') }}" class="btn-primary">➕ Tambah Produk</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 1rem;">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
