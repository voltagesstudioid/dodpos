<x-app-layout>
    <x-slot name="header">Edit Kategori Operasional</x-slot>
    <div class="page-container" style="max-width:800px;">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.5rem;">
            <a href="{{ route('operasional.kategori.index') }}" style="color:#64748b; text-decoration:none;">← Kembali</a>
            <span style="color:#cbd5e1;">/</span>
            <h1 style="font-size:1.25rem; font-weight:700; color:#1e293b; margin:0;">Edit Kategori</h1>
        </div>

        <div class="card" style="padding:1.75rem;">
            <form action="{{ route('operasional.kategori.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Nama Kategori <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" class="form-input @error('name') input-error @enderror" value="{{ old('name', $kategori->name) }}" required autofocus>
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi (Opsional)</label>
                    <textarea name="description" class="form-input" rows="3">{{ old('description', $kategori->description) }}</textarea>
                    @error('description') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div style="margin-top:2rem; padding-top:1.25rem; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
