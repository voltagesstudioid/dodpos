<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ─── HEADER CETAK (KHUSUS PRINT) ─── --}}
            <div class="print-only-header">
                <div class="text-center mb-4 border-b-2 border-black pb-4">
                    <h1 class="text-2xl font-bold uppercase">{{ config('app.name', 'DODPOS') }}</h1>
                    <p class="text-sm">Sistem Manajemen Bisnis & Gudang Grosir</p>
                </div>
                <div class="text-center mb-6">
                    <h2 class="text-xl font-bold uppercase underline mb-1">LAPORAN DETAIL PENJUALAN</h2>
                    <p class="text-sm">Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</p>
                </div>
            </div>

            {{-- ─── HEADER HALAMAN & TOMBOL CETAK ─── --}}
            <div class="flex justify-between items-center mb-6 no-print">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Ringkasan Penjualan</h3>
                    <p class="text-sm text-gray-500">Menampilkan data penjualan berdasarkan periode filter.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'xlsx', 'page' => null]) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
                        Export Excel
                    </a>
                    <button type="button" onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
                        Cetak Laporan
                    </button>
                </div>
            </div>

            {{-- ─── FILTER DATA ─── --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 no-print">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <form method="GET" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[150px]">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Periode Awal</label>
                            <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full rounded border-gray-300 text-sm">
                        </div>
                        <div class="flex-1 min-w-[150px]">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Periode Akhir</label>
                            <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full rounded border-gray-300 text-sm">
                        </div>
                        <div class="flex-1 min-w-[150px]">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Metode</label>
                            <select name="payment_method" class="w-full rounded border-gray-300 text-sm">
                                <option value="">Semua</option>
                                <option value="cash" @selected(request('payment_method') == 'cash')>Tunai</option>
                                <option value="transfer" @selected(request('payment_method') == 'transfer')>Transfer</option>
                                <option value="qris" @selected(request('payment_method') == 'qris')>QRIS</option>
                                <option value="debit" @selected(request('payment_method') == 'debit')>Debit</option>
                            </select>
                        </div>
                        <div class="flex-1 min-w-[150px]">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kasir</label>
                            <select name="kasir_id" class="w-full rounded border-gray-300 text-sm">
                                <option value="">Semua Kasir</option>
                                @foreach($kasirs as $k)
                                    <option value="{{ $k->id }}" @selected(request('kasir_id') == $k->id)>{{ $k->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-black">
                                Filter
                            </button>
                            <a href="{{ route('laporan.penjualan') }}" class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded text-sm font-bold shadow-sm hover:bg-gray-50">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ─── KPI / INFO SINGKAT ─── --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 no-print">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">Total Transaksi</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalTrx) }} <span class="text-sm font-normal text-gray-500">Nota</span></h3>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 border-l-4 border-l-green-500">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">Total Omzet</p>
                    <h3 class="text-2xl font-black text-green-600">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">Rata-rata Penjualan</p>
                    <h3 class="text-2xl font-black text-blue-600">Rp {{ number_format($avgPerTrx, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">Total Item Terjual</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalItems) }} <span class="text-sm font-normal text-gray-500">Pcs</span></h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- ─── TABEL TRANSAKSI (KIRI) ─── --}}
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200 print-table-wrapper">
                        <div class="p-4 border-b border-gray-200 bg-gray-50 no-print">
                            <h4 class="font-bold text-gray-800">Daftar Transaksi</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm print-table">
                                <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-bold">
                                    <tr>
                                        <th class="p-3 border-b text-center w-12">#</th>
                                        <th class="p-3 border-b">Tanggal/Waktu</th>
                                        <th class="p-3 border-b">Kasir</th>
                                        <th class="p-3 border-b text-center">Item</th>
                                        <th class="p-3 border-b text-center">Metode</th>
                                        <th class="p-3 border-b text-right">Total (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transactions as $i => $trx)
                                        <tr class="hover:bg-gray-50 border-b print-row">
                                            <td class="p-3 text-center text-gray-500">{{ $i + 1 }}</td>
                                            <td class="p-3">
                                                <div class="font-bold text-gray-800">{{ $trx->created_at->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $trx->created_at->format('H:i') }} WIB</div>
                                            </td>
                                            <td class="p-3">{{ $trx->user?->name ?? '—' }}</td>
                                            <td class="p-3 text-center font-bold">{{ $trx->details->count() }} Pcs</td>
                                            <td class="p-3 text-center uppercase font-bold text-xs text-gray-600">
                                                {{ $trx->payment_method }}
                                            </td>
                                            <td class="p-3 text-right font-black text-gray-800">
                                                {{ number_format($trx->total_amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-6 text-center text-gray-500 italic">
                                                Tidak ada data transaksi pada periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($transactions->count() > 0)
                                    <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-300">
                                        <tr>
                                            <td colspan="5" class="p-3 text-right uppercase text-gray-700">Total Keseluruhan</td>
                                            <td class="p-3 text-right text-lg text-green-700 font-black">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ─── SIDEBAR KANAN (PRODUK & METODE) ─── --}}
                <div class="space-y-6 no-print">
                    
                    {{-- TOP PRODUK --}}
                    <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-4 border-b border-gray-200 bg-gray-50">
                            <h4 class="font-bold text-gray-800">Produk Terlaris</h4>
                        </div>
                        <div class="p-0">
                            @forelse ($topProducts as $i => $tp)
                                <div class="p-4 py-3 border-b last:border-0 flex items-center justify-between hover:bg-gray-50">
                                    <div class="flex-1 pr-4">
                                        <p class="font-bold text-sm text-gray-800 truncate" title="{{ $tp->product?->name }}">{{ $i+1 }}. {{ $tp->product?->name ?? 'ID: '.$tp->product_id }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format($tp->total_qty) }} Pcs Terjual</p>
                                    </div>
                                    <div class="text-right font-bold text-sm text-gray-800">
                                        Rp {{ number_format($tp->total_revenue / 1000, 0, ',', '.') }}K
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-sm text-gray-500 italic">Belum ada data.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- METODE PEMBAYARAN --}}
                    <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-4 border-b border-gray-200 bg-gray-50">
                            <h4 class="font-bold text-gray-800">Metode Pembayaran</h4>
                        </div>
                        <div class="p-0">
                            @forelse($byPayment as $p)
                                <div class="p-4 py-3 border-b last:border-0 flex items-center justify-between hover:bg-gray-50">
                                    <div>
                                        <p class="font-bold text-sm text-gray-800 uppercase">{{ $p['label'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $p['count'] }} Trx</p>
                                    </div>
                                    <div class="text-right font-bold text-sm text-gray-800">
                                        Rp {{ number_format($p['amount'], 0, ',', '.') }}
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-sm text-gray-500 italic">Belum ada data.</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

            {{-- ─── FOOTER CETAK (TTD) ─── --}}
            <div class="print-only-footer mt-10 flex justify-end">
                <div class="text-center w-64">
                    <p class="mb-1 text-sm">Medan, {{ now()->format('d F Y') }}</p>
                    <p class="text-sm">Mengetahui,</p>
                    <br><br><br><br>
                    <p class="font-bold underline text-sm">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-sm text-gray-600">Admin DodPOS</p>
                </div>
            </div>

        </div>
    </div>

    @push('styles')
        <style>
            .print-only-header, .print-only-footer { display: none; }
            
            @media print {
                @page { size: A4 portrait; margin: 1cm; }
                body { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                .py-6 { padding-top: 0 !important; }
                .max-w-7xl { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
                
                /* Sembunyikan elemen non-cetak */
                header, nav, .no-print { display: none !important; }
                
                /* Tampilkan Header & Footer khusus cetak */
                .print-only-header { display: block; margin-bottom: 20px; }
                .print-only-footer { display: flex !important; }
                
                /* Reset Container & Table margin */
                .print-table-wrapper { border: none !important; box-shadow: none !important; overflow: visible !important; }
                
                /* Styling Tabel Khusus Print */
                .print-table { width: 100% !important; border-collapse: collapse !important; font-size: 11pt !important; }
                .print-table th, .print-table td { border: 1px solid #000 !important; padding: 6px 8px !important; color: #000 !important; }
                .print-table th { background-color: #f2f2f2 !important; font-weight: bold; }
                
                /* Hindari terpotong halaman */
                .print-table thead { display: table-header-group; }
                .print-table tfoot { display: table-footer-group; }
                .print-row { page-break-inside: avoid; }
            }
        </style>
    @endpush
</x-app-layout>