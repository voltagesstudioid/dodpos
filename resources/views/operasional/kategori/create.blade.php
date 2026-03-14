<x-app-layout>
    <x-slot name="header">Tambah Kategori Operasional</x-slot>
    <div class="page-container" style="max-width:800px;">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.5rem;">
            <a href="{{ route('operasional.kategori.index') }}" style="color:#64748b; text-decoration:none;">← Kembali</a>
            <span style="color:#cbd5e1;">/</span>
            <h1 style="font-size:1.25rem; font-weight:700; color:#1e293b; margin:0;">Tambah Kategori Baru</h1>
        </div>

        <div class="card" style="padding:1.75rem;">
            <form action="{{ route('operasional.kategori.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Nama Kategori <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" class="form-input @error('name') input-error @enderror" value="{{ old('name') }}" required autofocus placeholder="Contoh: Listrik, Gaji Karyawan, Transport">
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi (Opsional)</label>
                    <textarea name="description" class="form-input" rows="3" placeholder="Keterangan singkat tentang kategori ini">{{ old('description') }}</textarea>
                    @error('description') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div style="margin-top:2rem; padding-top:1.25rem; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn-primary">Kirim Data</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
