<x-app-layout>
    <x-slot name="header">Detail Sales Order</x-slot>

    <div class="page-container">
            
            <!-- Actions -->
            <div class="flex justify-between items-center">
                <a href="{{ route('sales-order.index') }}" class="btn-secondary text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                
                <div class="flex gap-2">
                    <button type="button" onclick="window.print()" class="btn-secondary text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Doc
                    </button>
                    
                    @if(!in_array($salesOrder->status, ['completed', 'cancelled']))
                        <a href="{{ route('sales-order.edit', $salesOrder->id) }}" class="btn-primary text-sm bg-indigo-600">
                            Edit SO
                        </a>
                    @endif
                </div>
            </div>

            <div class="card p-8" id="printableArea">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;padding-bottom:1.25rem;margin-bottom:1.25rem;border-bottom:1px solid #f1f5f9;">
                    <div>
                        <div style="font-size:1.5rem;font-weight:800;color:#0f172a;">SALES ORDER</div>
                        <div style="font-size:1rem;font-weight:700;color:#4f46e5;margin-top:4px;">#{{ $salesOrder->so_number }}</div>
                    </div>
                    <div>
                        @php $statusMap=['draft'=>'badge-gray','confirmed'=>'badge-blue','processing'=>'badge-indigo','completed'=>'badge-success','cancelled'=>'badge-danger']; @endphp
                        <span class="badge {{ $statusMap[$salesOrder->status] ?? 'badge-gray' }}" style="font-size:0.8125rem;padding:0.35rem 0.875rem;">{{ ucfirst($salesOrder->status) }}</span>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Informasi Pelanggan</h3>
                        <p class="font-bold text-gray-900 dark:text-gray-100 text-lg">{{ $salesOrder->customer?->name ?? '-' }}</p>
                        @if($salesOrder->customer?->phone)
                            <p class="text-gray-600 dark:text-gray-400">{{ $salesOrder->customer?->phone }}</p>
                        @endif
                        @if($salesOrder->customer?->address)
                            <p class="text-gray-600 dark:text-gray-400 mt-1 whitespace-pre-line">{{ $salesOrder->customer?->address }}</p>
                        @endif
                    </div>
                    <div>
                        <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                            <div class="text-sm font-bold text-gray-500 uppercase">Tanggal Order</div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($salesOrder->order_date)->translatedFormat('d F Y') }}</div>
                            
                            <div class="text-sm font-bold text-gray-500 uppercase">Estimasi Kirim</div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $salesOrder->delivery_date ? \Carbon\Carbon::parse($salesOrder->delivery_date)->translatedFormat('d F Y') : '-' }}</div>
                            
                            <div class="text-sm font-bold text-gray-500 uppercase">Sales / Kasir</div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $salesOrder->user?->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mb-8">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">
                                <th class="py-3 px-2 font-bold uppercase text-sm w-10">No</th>
                                <th class="py-3 px-2 font-bold uppercase text-sm">Barang</th>
                                <th class="py-3 px-2 font-bold uppercase text-sm text-right">Harga</th>
                                <th class="py-3 px-2 font-bold uppercase text-sm text-center w-24">QTY</th>
                                <th class="py-3 px-2 font-bold uppercase text-sm text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($salesOrder->items as $idx => $item)
                                <tr class="text-gray-800 dark:text-gray-200">
                                    <td class="py-4 px-2">{{ $idx + 1 }}</td>
                                    <td class="py-4 px-2 font-medium">
                                        {{ $item->product?->name ?? ('Produk ID ' . $item->product_id) }}
                                        <div class="text-xs text-gray-500">{{ $item->product?->barcode ?? '-' }}</div>
                                    </td>
                                    <td class="py-4 px-2 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-2 text-center font-bold">{{ $item->quantity }}</td>
                                    <td class="py-4 px-2 text-right font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
                                <td colspan="4" class="py-4 px-2 font-bold text-right text-gray-700 dark:text-gray-300 uppercase">Total:</td>
                                <td class="py-4 px-2 font-bold text-right text-xl text-indigo-600 dark:text-indigo-400">Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($salesOrder->notes)
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-500 uppercase mb-2">Catatan</h3>
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 p-4 rounded border border-yellow-200 dark:border-yellow-700/50 whitespace-pre-line">
                        {{ $salesOrder->notes }}
                    </div>
                </div>
                @endif
                
            </div>
            
            @if(!in_array($salesOrder->status, ['completed', 'cancelled']))
            <div class="flex justify-end mt-4">
                <form action="{{ route('sales-order.destroy', $salesOrder->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Sales Order ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary text-red-600 border-red-200 hover:bg-red-50 hover:text-red-700">Hapus SO</button>
                </form>
            </div>
            @endif
        </div>
    </div>
    
    <style>@media print { body * { visibility: hidden; } #printableArea, #printableArea * { visibility: visible; } #printableArea { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; } }</style>
</x-app-layout>
