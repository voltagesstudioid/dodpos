<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Pembelian') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @php $isPrint = (bool) ($isPrint ?? request()->boolean('print')); @endphp

            @if($isPrint && request()->boolean('preview'))
                <div class="no-print">
                    @include('print.partials.preview-toolbar', ['title' => 'Laporan Pembelian'])
                </div>
            @endif

            {{-- ─── HEADER CETAK (KHUSUS PRINT) ─── --}}
            <div class="print-only-header">
                <div class="text-center mb-4 border-b-2 border-black pb-4">
                    <h1 class="text-2xl font-bold uppercase">{{ config('app.name', 'DODPOS') }}</h1>
                    <p class="text-sm">Sistem Manajemen Bisnis & Gudang Grosir</p>
                </div>
                <div class="text-center mb-6">
                    <h2 class="text-xl font-bold uppercase underline mb-1">LAPORAN PEMBELIAN (PO)</h2>
                    <p class="text-sm">Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</p>
                </div>
            </div>

            {{-- ─── HEADER HALAMAN & TOMBOL CETAK ─── --}}
            <div class="flex justify-between items-center mb-6 no-print">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Ringkasan Pembelian</h3>
                    <p class="text-sm text-gray-500">Pantau aktivitas Purchase Order (PO) berdasarkan filter.</p>
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
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Dari Tanggal</label>
                            <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full rounded border-gray-300 text-sm">
                        </div>
                        <div class="flex-1 min-w-[150px]">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Sampai Tanggal</label>
                            <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full rounded border-gray-300 text-sm">
                        </div>
                        <div class="flex-1 min-w-[180px]">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Supplier</label>
                            <select name="supplier_id" class="w-full rounded border-gray-300 text-sm">
                                <option value="">Semua Supplier</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}" @selected(request('supplier_id') == $s->id)>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 min-w-[150px]">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status</label>
                            <select name="status" class="w-full rounded border-gray-300 text-sm">
                                <option value="">Semua Status</option>
                                <option value="draft" @selected(request('status') == 'draft')>Draft</option>
                                <option value="ordered" @selected(request('status') == 'ordered')>Dipesan</option>
                                <option value="partial" @selected(request('status') == 'partial')>Diterima Sebagian</option>
                                <option value="received" @selected(request('status') == 'received')>Diterima Penuh</option>
                                <option value="cancelled" @selected(request('status') == 'cancelled')>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-black">
                                Filter
                            </button>
                            <a href="{{ route('laporan.pembelian') }}" class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded text-sm font-bold shadow-sm hover:bg-gray-50">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ─── KPI / INFO SINGKAT ─── --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 no-print">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">Total Dokumen PO</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalOrders) }} <span class="text-sm font-normal text-gray-500">Nota</span></h3>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 border-l-4 border-l-indigo-500">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">Total Nilai PO</p>
                    <h3 class="text-2xl font-black text-indigo-600">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 border-l-4 border-l-green-500">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">Nilai Barang Diterima</p>
                    <h3 class="text-2xl font-black text-green-600">Rp {{ number_format($totalReceived, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 border-l-4 border-l-yellow-500">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">Estimasi Nilai Pending</p>
                    <h3 class="text-2xl font-black text-yellow-600">Rp {{ number_format($totalPending, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- ─── TABEL TRANSAKSI PO (KIRI) ─── --}}
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200 print-table-wrapper">
                        <div class="p-4 border-b border-gray-200 bg-gray-50 no-print">
                            <h4 class="font-bold text-gray-800">Daftar Purchase Order</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm print-table">
                                <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-bold">
                                    <tr>
                                        <th class="p-3 border-b">No. PO</th>
                                        <th class="p-3 border-b">Supplier</th>
                                        <th class="p-3 border-b">Tanggal</th>
                                        <th class="p-3 border-b text-center">Item</th>
                                        <th class="p-3 border-b text-center">Status</th>
                                        <th class="p-3 border-b text-right">Total (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        @php $s = $order->status_label; @endphp
                                        <tr class="hover:bg-gray-50 border-b print-row {{ $order->status === 'cancelled' ? 'opacity-50' : '' }}">
                                            <td class="p-3">
                                                <a href="{{ route('pembelian.order.show', $order) }}" class="font-black text-indigo-600 hover:underline">
                                                    {{ $order->po_number }}
                                                </a>
                                            </td>
                                            <td class="p-3 font-bold text-gray-800">{{ $order->supplier->name }}</td>
                                            <td class="p-3 text-gray-800">{{ $order->order_date->format('d/m/Y') }}</td>
                                            <td class="p-3 text-center font-bold">{{ $order->items->count() }} Pcs</td>
                                            <td class="p-3 text-center">
                                                <span class="px-2 py-1 rounded text-xs font-bold uppercase no-print" style="background:{{ $s['bg'] }}; color:{{ $s['color'] }};">
                                                    {{ $s['label'] }}
                                                </span>
                                                <span class="inline hidden print-inline">{{ $s['label'] }}</span>
                                            </td>
                                            <td class="p-3 text-right font-black text-gray-800">
                                                {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-6 text-center text-gray-500 italic">
                                                Tidak ada data pembelian pada periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($orders->count() > 0)
                                    <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-300">
                                        <tr>
                                            <td colspan="5" class="p-3 text-right uppercase text-gray-700">Total Keseluruhan</td>
                                            <td class="p-3 text-right text-lg text-indigo-700 font-black">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ─── SIDEBAR KANAN (SUPPLIER & STATUS) ─── --}}
                <div class="space-y-6 no-print">
                    
                    {{-- TOP SUPPLIERS --}}
                    <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-4 border-b border-gray-200 bg-gray-50">
                            <h4 class="font-bold text-gray-800">Top Supplier (Nilai PO)</h4>
                        </div>
                        <div class="p-0">
                            @forelse ($bySupplier as $sup)
                                <div class="p-4 py-3 border-b last:border-0 hover:bg-gray-50">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="font-bold text-sm text-gray-800 truncate" title="{{ $sup['name'] }}">{{ $sup['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $sup['count'] }} PO</p>
                                    </div>
                                    <div class="text-right font-black text-indigo-600 text-sm">
                                        Rp {{ number_format($sup['amount'], 0, ',', '.') }}
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-sm text-gray-500 italic">Belum ada data.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- SEBARAN STATUS --}}
                    <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-4 border-b border-gray-200 bg-gray-50">
                            <h4 class="font-bold text-gray-800">Sebaran Status PO</h4>
                        </div>
                        <div class="p-0">
                            @php
                                $statusList = [
                                    ['status' => 'received', 'label' => 'Diterima Penuh', 'color' => '#059669', 'bg' => '#dcfce7'],
                                    ['status' => 'ordered', 'label' => 'Sedang Dipesan', 'color' => '#1d4ed8', 'bg' => '#dbeafe'],
                                    ['status' => 'partial', 'label' => 'Diterima Sebagian', 'color' => '#b45309', 'bg' => '#fef3c7'],
                                    ['status' => 'draft', 'label' => 'Masih Draft', 'color' => '#475569', 'bg' => '#f1f5f9'],
                                    ['status' => 'cancelled', 'label' => 'Dibatalkan', 'color' => '#b91c1c', 'bg' => '#fee2e2'],
                                ];
                                $hasData = false;
                            @endphp
                            
                            @foreach($statusList as $st)
                                @php $cnt = $orders->where('status', $st['status'])->count(); @endphp
                                @if($cnt > 0)
                                    @php $hasData = true; @endphp
                                    <div class="p-4 py-3 border-b last:border-0 flex items-center justify-between hover:bg-gray-50">
                                        <span class="px-2 py-1 rounded text-xs font-bold uppercase" style="background:{{ $st['bg'] }}; color:{{ $st['color'] }};">{{ $st['label'] }}</span>
                                        <div class="font-black text-gray-800">{{ $cnt }} <span class="text-xs font-normal text-gray-500">Nota</span></div>
                                    </div>
                                @endif
                            @endforeach
                            
                            @if(!$hasData)
                                <div class="p-4 text-center text-sm text-gray-500 italic">Belum ada data.</div>
                            @endif
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
            .print-only-header, .print-only-footer, .print-inline { display: none; }
            
            @media print {
                @page { size: A4 portrait; margin: 1cm; }
                body { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                .py-6 { padding-top: 0 !important; }
                .max-w-7xl { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
                
                /* Sembunyikan elemen non-cetak */
                header, nav, .no-print { display: none !important; }
                
                /* Tampilkan Header & Footer khusus cetak */
                .print-only-header { display: block; margin-bottom: 20px; }
                .print-only-footer { display: flex !important; margin-top: 80px;}
                .print-inline { display: inline !important; }
                
                /* Reset Container & Table margin */
                .print-table-wrapper { border: none !important; box-shadow: none !important; overflow: visible !important; }
                
                /* Styling Tabel Khusus Print */
                .print-table { width: 100% !important; border-collapse: collapse !important; font-size: 11pt !important; }
                .print-table th, .print-table td { border: 1px solid #000 !important; padding: 6px 8px !important; color: #000 !important; }
                .print-table th { background-color: #f2f2f2 !important; font-weight: bold; }
                
                /* Teks normal pada Print */
                .print-table .text-indigo-600 { color: #000 !important; text-decoration: none !important; font-weight: normal; }
                
                /* Hindari terpotong halaman */
                .print-table thead { display: table-header-group; }
                .print-table tfoot { display: table-footer-group; }
                .print-row { page-break-inside: avoid; }
            }
        </style>
    @endpush
</x-app-layout>