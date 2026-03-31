<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Loading Harian') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <form method="POST" action="{{ route('minyak.loading.store') }}" class="p-6">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                                class="mt-1 block w-full border rounded-lg px-4 py-2">
                            @error('tanggal')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sales</label>
                            <select name="sales_id" required class="mt-1 block w-full border rounded-lg px-4 py-2">
                                <option value="">Pilih Sales</option>
                                @foreach($sales as $s)
                                    <option value="{{ $s->id }}" {{ old('sales_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama }} ({{ $s->plat_nomor ?? 'Tanpa Plat' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('sales_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Produk</label>
                            <select name="produk_id" required class="mt-1 block w-full border rounded-lg px-4 py-2">
                                <option value="">Pilih Produk</option>
                                @foreach($produks as $p)
                                    <option value="{{ $p->id }}" {{ old('produk_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }} (Stok: {{ $p->stok_gudang }} {{ $p->satuan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('produk_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Loading (Liter)</label>
                            <input type="number" name="jumlah_loading" value="{{ old('jumlah_loading') }}" required min="1"
                                class="mt-1 block w-full border rounded-lg px-4 py-2">
                            @error('jumlah_loading')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="mt-1 block w-full border rounded-lg px-4 py-2">{{ old('keterangan') }}</textarea>
                            @error('keterangan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mt-6 flex gap-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                        <a href="{{ route('minyak.loading.index') }}" class="bg-gray-200 px-6 py-2 rounded-lg hover:bg-gray-300">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
