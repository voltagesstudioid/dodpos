<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stok Kendaraan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <select name="sales_id" class="border rounded-lg px-4 py-2 w-64">
                        <option value="">Semua Sales</option>
                        @foreach($sales as $s)
                            <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg">Filter</button>
                    <a href="{{ route('minyak.stok.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">Reset</a>
                </form>
            </div>

            <!-- Stok Per Sales -->
            <div class="space-y-6">
                @forelse($stokPerSales as $data)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-4 bg-gray-50 border-b">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold">{{ $data['sales']->nama }}</h3>
                                    <p class="text-sm text-gray-500">{{ $data['sales']->plat_nomor ?? '-' }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Total Sisa Stok</div>
                                    <div class="text-2xl font-bold text-blue-600">{{ number_format($data['total_sisa']) }} L</div>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div class="text-center p-3 bg-blue-50 rounded">
                                    <div class="text-sm text-gray-500">Total Loading</div>
                                    <div class="text-xl font-bold">{{ number_format($data['total_loading']) }} L</div>
                                </div>
                                <div class="text-center p-3 bg-green-50 rounded">
                                    <div class="text-sm text-gray-500">Terjual</div>
                                    <div class="text-xl font-bold">{{ number_format($data['total_terjual']) }} L</div>
                                </div>
                                <div class="text-center p-3 bg-orange-50 rounded">
                                    <div class="text-sm text-gray-500">Sisa</div>
                                    <div class="text-xl font-bold">{{ number_format($data['total_sisa']) }} L</div>
                                </div>
                            </div>
                            
                            @if($data['detail']->count() > 0)
                                <table class="min-w-full mt-4">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Produk</th>
                                            <th class="px-4 py-2 text-right">Loading</th>
                                            <th class="px-4 py-2 text-right">Terjual</th>
                                            <th class="px-4 py-2 text-right">Sisa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['detail'] as $item)
                                            <tr class="border-t">
                                                <td class="px-4 py-2">{{ $item['produk']->nama }}</td>
                                                <td class="px-4 py-2 text-right">{{ number_format($item['loading']) }} L</td>
                                                <td class="px-4 py-2 text-right">{{ number_format($item['terjual']) }} L</td>
                                                <td class="px-4 py-2 text-right font-medium">{{ number_format($item['sisa']) }} L</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-gray-500 text-center py-4">Tidak ada data stok</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-8 rounded-lg shadow-sm text-center text-gray-500">
                        Tidak ada data stok kendaraan
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
