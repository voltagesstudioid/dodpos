<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <form method="POST" action="{{ route('minyak.pelanggan.store') }}" class="p-6">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Toko</label>
                            <input type="text" name="nama_toko" value="{{ old('nama_toko') }}" required
                                class="mt-1 block w-full border rounded-lg px-4 py-2">
                            @error('nama_toko')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Pemilik</label>
                            <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik') }}" required
                                class="mt-1 block w-full border rounded-lg px-4 py-2">
                            @error('nama_pemilik')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">No HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                                class="mt-1 block w-full border rounded-lg px-4 py-2">
                            @error('no_hp')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="mt-1 block w-full border rounded-lg px-4 py-2">
                            @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" rows="3" class="mt-1 block w-full border rounded-lg px-4 py-2">{{ old('alamat') }}</textarea>
                            @error('alamat')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kecamatan</label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan') }}"
                                    class="mt-1 block w-full border rounded-lg px-4 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kota</label>
                                <input type="text" name="kota" value="{{ old('kota') }}"
                                    class="mt-1 block w-full border rounded-lg px-4 py-2">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipe Pelanggan</label>
                            <select name="tipe" required class="mt-1 block w-full border rounded-lg px-4 py-2">
                                <option value="eceran" {{ old('tipe') == 'eceran' ? 'selected' : '' }}>Eceran</option>
                                <option value="grosir" {{ old('tipe') == 'grosir' ? 'selected' : '' }}>Grosir</option>
                                <option value="agen" {{ old('tipe') == 'agen' ? 'selected' : '' }}>Agen</option>
                            </select>
                            @error('tipe')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Limit Hutang (Rp)</label>
                            <input type="number" name="limit_hutang" value="{{ old('limit_hutang') }}"
                                class="mt-1 block w-full border rounded-lg px-4 py-2">
                            @error('limit_hutang')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" required class="mt-1 block w-full border rounded-lg px-4 py-2">
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                <option value="blacklist" {{ old('status') == 'blacklist' ? 'selected' : '' }}>Blacklist</option>
                            </select>
                            @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mt-6 flex gap-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                        <a href="{{ route('minyak.pelanggan.index') }}" class="bg-gray-200 px-6 py-2 rounded-lg hover:bg-gray-300">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
