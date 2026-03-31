<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Kunjungan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Monitoring Kunjungan Sales</h3>
                <p class="text-gray-600">Fitur monitoring kunjungan sales akan segera hadir.</p>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="font-medium text-blue-800">Total Kunjungan</div>
                        <p class="text-sm text-gray-600 mt-1">Rekapitulasi kunjungan harian</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg">
                        <div class="font-medium text-green-800">Kunjungan Berhasil</div>
                        <p class="text-sm text-gray-600 mt-1">Kunjungan dengan transaksi</p>
                    </div>
                    <div class="p-4 bg-orange-50 rounded-lg">
                        <div class="font-medium text-orange-800">Target Kunjungan</div>
                        <p class="text-sm text-gray-600 mt-1">Pencapaian target harian</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
