<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Minyak') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Laporan Penjualan Minyak</h3>
                <p class="text-gray-600">Fitur laporan detail akan segera hadir.</p>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="font-medium text-blue-800">Laporan Harian</div>
                        <p class="text-sm text-gray-600 mt-1">Ringkasan penjualan harian per sales</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg">
                        <div class="font-medium text-green-800">Laporan Bulanan</div>
                        <p class="text-sm text-gray-600 mt-1">Rekapitulasi penjualan bulanan</p>
                    </div>
                    <div class="p-4 bg-purple-50 rounded-lg">
                        <div class="font-medium text-purple-800">Laporan Hutang</div>
                        <p class="text-sm text-gray-600 mt-1">Daftar hutang pelanggan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
