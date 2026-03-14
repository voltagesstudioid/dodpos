<x-app-layout>
    <x-slot name="header">
        <div class="ph">
            <div class="ph-left">
                <div class="ph-icon blue">💧</div>
                <div>
                    <div class="ph-breadcrumb">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <span class="ph-breadcrumb-sep">/</span>
                        <span>Modul Mineral</span>
                    </div>
                    <h2 class="ph-title">Dashboard Mineral</h2>
                    <p class="ph-subtitle">Pantau pergerakan stok Air Mineral kemasan & total penjualan hari ini.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Ringkasan Cepat: Terjual Hari Ini -->
    <div class="stat-grid mb-3">
        <div class="stat-card blue">
            <div class="stat-card-row">
                <div class="stat-icon blue">📦</div>
                <div class="stat-trend up">
                    Hari Ini ({{ \Carbon\Carbon::now()->translatedFormat('d F Y') }})
                </div>
            </div>
            <div class="mt-2">
                <div class="stat-label">Total Mineral Terjual</div>
                <div class="stat-value blue">{{ number_format($soldToday, 0, ',', '.') }} <span style="font-size:1rem;color:#64748b;">Dus</span></div>
            </div>
        </div>
    </div>

    <!-- SISA STOK GUDANG 3 ITEM -->
    <div class="grid-3 mb-3">
        @foreach($products as $prod)
            @php
                // Cari stok dari collection
                $st = $stocks->where('product_id', $prod->id)->first();
                $qty = $st ? $st->qty_dus : 0;
            @endphp
            <div class="stat-card {{ (($maskStock ?? false) === true) ? 'indigo' : ($qty < 50 ? 'rose' : 'emerald') }}">
                <div class="stat-card-row">
                    <div class="stat-icon {{ (($maskStock ?? false) === true) ? 'indigo' : ($qty < 50 ? 'rose' : 'emerald') }}">💧</div>
                    @if(($maskStock ?? false) === true)
                       <span class="badge badge-warning">Terkunci</span>
                    @elseif($qty < 50)
                       <span class="badge badge-danger">Stok Menipis</span>
                    @else
                       <span class="badge badge-success">Aman</span>
                    @endif
                </div>
                <div class="mt-2">
                    <div class="stat-label">{{ $prod->name }}</div>
                    <div class="stat-value {{ (($maskStock ?? false) === true) ? 'indigo' : ($qty < 50 ? 'rose' : 'emerald') }}">
                        @if(($maskStock ?? false) === true)
                            Terkunci
                        @else
                            {{ number_format($qty, 0, ',', '.') }}
                        @endif
                        <span style="font-size:1rem;color:#64748b;">Dus</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- PINTASAN CEPAT -->
    <h3 class="font-bold text-gray mb-1 mt-3" style="text-transform:uppercase; font-size:0.75rem; letter-spacing:0.05em;">Pintasan Aksi Mineral</h3>
    <div class="qa-grid mb-2">
        <a href="{{ route('mineral.stok.index') }}" class="qa-card qa-indigo">
            <div class="qa-card-icon">📦</div>
            <div>
                <div class="qa-card-title">Terima Barang Masuk</div>
                <div class="qa-card-subtitle">Input kedatangan Truk Galon/Dus</div>
            </div>
            <div class="qa-card-arrow">➔</div>
        </a>
        <a href="{{ route('mineral.loading.index') }}" class="qa-card qa-teal">
            <div class="qa-card-icon">🚚</div>
            <div>
                <div class="qa-card-title">Surat Jalan Loading</div>
                <div class="qa-card-subtitle">Oper stok Gudang -> Mobil Sales</div>
            </div>
            <div class="qa-card-arrow">➔</div>
        </a>
        <a href="{{ route('mineral.setoran.index') }}" class="qa-card qa-amber">
            <div class="qa-card-icon">💰</div>
            <div>
                <div class="qa-card-title">Validasi Setoran</div>
                <div class="qa-card-subtitle">Reviu sisa stok & cash (Sore Hari)</div>
            </div>
            <div class="qa-card-arrow">➔</div>
        </a>
    </div>

</x-app-layout>
