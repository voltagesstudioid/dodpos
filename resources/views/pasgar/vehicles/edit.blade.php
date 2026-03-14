<x-app-layout>
<x-slot name="header">Edit Kendaraan Pasgar</x-slot>

<div class="card p-4 mx-auto" style="max-width: 600px;">
    <div class="d-flex align-items-center mb-4" style="gap: 15px;">
        <a href="{{ route('pasgar.vehicles.index') }}" class="btn-primary" style="background: #f1f5f9; color: #475569; text-decoration: none; padding: 5px 12px;">&larr; Kembali</a>
        <h4 class="mb-0" style="font-weight: 700; color: #1e293b;">Edit Kendaraan / Tim Pasgar</h4>
    </div>

    @if($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:1rem;border-radius:8px;margin-bottom:1rem;">
            <ul style="margin:0;padding-left:1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pasgar.vehicles.update', $vehicle) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
            <label class="form-label" style="font-weight:600; color: #334155; margin-bottom: 5px; display: block;">Gudang Virtual Bawaan</label>
            <input type="text" class="form-control" disabled value="{{ $vehicle->warehouse ? $vehicle->warehouse->code . ' - ' . $vehicle->warehouse->name : 'Belum Ada' }}" style="width: 100%; padding: 0.625rem; border: 1px solid #cbd5e1; border-radius: 6px; background: #f8fafc;">
            <div style="font-size: 0.8rem; color: #64748b; margin-top: 5px;">Gudang ini digunakan untuk melacak saldo barang bawaan Pasgar.</div>
        </div>

        <div class="form-group mb-3">
            <label class="form-label" style="font-weight:600; color: #334155; margin-bottom: 5px; display: block;">Plat Nomor Kendaraan <span class="text-danger">*</span></label>
            <input type="text" name="license_plate" class="form-control" placeholder="Contoh: B 1234 CD" value="{{ old('license_plate', $vehicle->license_plate) }}" required style="width: 100%; padding: 0.625rem; border: 1px solid #cbd5e1; border-radius: 6px;">
        </div>

        <div class="form-group mb-3">
            <label class="form-label" style="font-weight:600; color: #334155; margin-bottom: 5px; display: block;">Tipe Kendaraan</label>
            <input type="text" name="type" class="form-control" placeholder="Contoh: Motor / Mobil Box / Grandmax" value="{{ old('type', $vehicle->type) }}" style="width: 100%; padding: 0.625rem; border: 1px solid #cbd5e1; border-radius: 6px;">
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight:600; color: #334155; margin-bottom: 5px; display: block;">Deskripsi & Nama Tim Sales</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Contoh: Tim Budi & Andi (Rute Cengkareng)" style="width: 100%; padding: 0.625rem; border: 1px solid #cbd5e1; border-radius: 6px;">{{ old('description', $vehicle->description) }}</textarea>
            <div style="font-size: 0.8rem; color: #64748b; margin-top: 5px;">Nama Gudang Virtual akan ikut disesuaikan saat Anda mengubah Plat atau Tipe.</div>
        </div>

        <button type="submit" class="btn-primary w-100" style="width: 100%; padding: 0.75rem; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; background: #eab308; color: #fff;">Simpan Perubahan</button>
    </form>
</div>
</x-app-layout>
