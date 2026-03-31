<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('minyak.sales.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Tambah Sales Baru') }}
                </h2>
                <p class="text-sm text-gray-500">Isi data lengkap sales dan kendaraan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('minyak.sales.store') }}">
                @csrf
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Informasi Pribadi -->
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Informasi Pribadi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" value="{{ old('nama') }}" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Masukkan nama lengkap">
                                @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No HP</label>
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: 08123456789">
                                @error('no_hp')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: sales@email.com">
                                @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                <textarea name="alamat" rows="3" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                                @error('alamat')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Kendaraan -->
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            Informasi Kendaraan
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Plat Nomor Kendaraan</label>
                                <input type="text" name="plat_nomor" value="{{ old('plat_nomor') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: B 1234 ABC">
                                @error('plat_nomor')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas Tangki (Liter)</label>
                                <input type="number" name="kapasitas_tangki" value="{{ old('kapasitas_tangki') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: 1000">
                                @error('kapasitas_tangki')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status
                        </h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status Sales <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="status" value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }} required
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Aktif</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="status" value="cuti" {{ old('status') == 'cuti' ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Cuti</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="status" value="nonaktif" {{ old('status') == 'nonaktif' ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">Nonaktif</span>
                                </label>
                            </div>
                            @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex items-center justify-end gap-4">
                    <a href="{{ route('minyak.sales.index') }}" 
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
