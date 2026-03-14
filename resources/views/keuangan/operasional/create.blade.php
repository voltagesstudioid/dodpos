<x-app-layout>
    <x-slot name="header">Catat Pengeluaran</x-slot>

    <div class="page-container">
        <div style="max-width:640px;">
            <div class="page-header">
                <div><div class="page-header-title">💸 Catat Kas Keluar Baru</div><div class="page-header-subtitle">Catat biaya operasional toko harian.</div></div>
                <a href="{{ route('operasional.index') }}" class="btn-secondary">← Kembali</a>
            </div>
            <div class="panel">
                <div class="panel-header"><div class="panel-title">Detail Pengeluaran</div></div>
                <div class="panel-body">

                <form action="{{ route('operasional.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        
                        <!-- Tanggal Pengeluaran -->
                        <div>
                            <label for="expense_date" class="form-label">Tanggal Pengeluaran <span class="text-red-500">*</span></label>
                            <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" class="form-input mt-1 block w-full" required>
                            @error('expense_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kategori Pengeluaran -->
                        <div class="card" style="background:#f8fafc;padding:1rem;margin-bottom:1rem;">
                            <label class="form-label">Kategori Pengeluaran <span class="required">*</span></label>
                            <div class="form-hint">Pilih dari daftar atau buat kategori baru di bawah.</div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                                <div>
                                    <label for="category_id" class="text-sm text-gray-600 dark:text-gray-400">Pilih dari Master</label>
                                    <select name="category_id" id="category_id" class="form-input mt-1 block w-full" onchange="toggleCategoryInput()">
                                        <option value="">-- Pilih --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-center md:text-left flex items-center justify-center pt-6 text-sm text-gray-500 font-medium">
                                    ATAU
                                </div>
                                <div>
                                    <label for="category_name" class="text-sm text-gray-600 dark:text-gray-400">Buat Kategori Baru</label>
                                    <input type="text" name="category_name" id="category_name" value="{{ old('category_name') }}" placeholder="Misal: Biaya Bensin" class="form-input mt-1 block w-full" oninput="toggleCategorySelect()">
                                </div>
                            </div>
                            @if(session('error') && str_contains(session('error'), 'Kategori Pengeluaran'))
                                <p class="text-red-500 text-sm mt-2">{{ session('error') }}</p>
                            @endif
                        </div>

                        <!-- Nominal -->
                        <div>
                            <label for="amount" class="form-label">Nominal (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="1" step="0.01" class="form-input block w-full pl-10" placeholder="0.00" required>
                            </div>
                            @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="description" class="form-label">Keterangan Pengeluaran <span class="text-red-500">*</span></label>
                            <textarea name="description" id="description" rows="3" class="form-input mt-1 block w-full" placeholder="Rincian pengeluaran..." required>{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    <!-- Submit Actions -->
                    <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('operasional.index') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary" id="btnSubmit">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- UI Interaction Scripts -->
    <script>
        const selectCat = document.getElementById('category_id');
        const inputCat = document.getElementById('category_name');

        function toggleCategoryInput() {
            if (selectCat.value) {
                inputCat.value = '';
                inputCat.disabled = true;
                inputCat.classList.add('bg-gray-100', 'dark:bg-gray-700', 'cursor-not-allowed');
            } else {
                inputCat.disabled = false;
                inputCat.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'cursor-not-allowed');
            }
        }

        function toggleCategorySelect() {
            if (inputCat.value.trim().length > 0) {
                selectCat.value = '';
                selectCat.disabled = true;
                selectCat.classList.add('bg-gray-100', 'dark:bg-gray-700', 'cursor-not-allowed');
            } else {
                selectCat.disabled = false;
                selectCat.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'cursor-not-allowed');
            }
        }

        // Initialize state on load
        toggleCategoryInput();
        toggleCategorySelect();
    </script>
</x-app-layout>
