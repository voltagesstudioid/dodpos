<x-app-layout>
    @push('styles')
    <style>
        .rg-page { max-width:56rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',system-ui,sans-serif; }
        .rg-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; }
        .rg-hdr-ico { width:48px; height:48px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; background:linear-gradient(135deg,#3b82f6,#2563eb); box-shadow:0 8px 24px rgba(37,99,235,0.3); }
        .rg-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; }
        .rg-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .rg-form { background:#fff; border:1px solid #e2e8f0; border-radius:18px; padding:2rem; box-shadow:0 4px 16px rgba(0,0,0,0.04); }
        .rg-section { margin-bottom:1.75rem; }
        .rg-section-title { font-size:0.8125rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#2563eb; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:2px solid #dbeafe; }
        .rg-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem; }
        .rg-row.full { grid-template-columns:1fr; }
        .rg-field { display:flex; flex-direction:column; }
        .rg-lbl { font-size:0.75rem; font-weight:700; color:#374151; margin-bottom:0.375rem; text-transform:uppercase; letter-spacing:0.04em; }
        .rg-lbl .req { color:#dc2626; }
        .rg-inp { padding:0.6875rem 1rem; border-radius:10px; border:1.5px solid #e2e8f0; font-size:0.875rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit; background:#fff; }
        .rg-inp:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.12); }
        .rg-inp::placeholder { color:#cbd5e1; }
        textarea.rg-inp { resize:vertical; min-height:80px; }
        .rg-hint { font-size:0.6875rem; color:#94a3b8; margin-top:0.25rem; }
        .rg-err { font-size:0.6875rem; color:#dc2626; margin-top:0.25rem; }
        .rg-harga-tbl { width:100%; border-collapse:collapse; }
        .rg-harga-tbl th { text-align:left; padding:0.625rem 0.75rem; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; background:#f8fafc; border-bottom:2px solid #e2e8f0; }
        .rg-harga-tbl td { padding:0.625rem 0.75rem; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
        .rg-harga-tbl tr:hover td { background:#eff6ff; }
        .rg-harga-inp { width:100%; padding:0.5rem 0.75rem; border-radius:8px; border:1.5px solid #e2e8f0; font-size:0.8125rem; text-align:right; outline:none; transition:all 0.2s; font-family:inherit; }
        .rg-harga-inp:focus { border-color:#2563eb; box-shadow:0 0 0 2px rgba(37,99,235,0.12); }
        .rg-harga-default { font-size:0.75rem; color:#94a3b8; }
        .rg-btns { display:flex; gap:0.75rem; justify-content:flex-end; padding-top:1.5rem; border-top:1px solid #e2e8f0; margin-top:1rem; }
        .rg-btn { display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.5rem; border-radius:12px; font-size:0.8125rem; font-weight:600; border:none; cursor:pointer; transition:all 0.25s; font-family:inherit; text-decoration:none; }
        .rg-btn-cancel { background:#f1f5f9; color:#64748b; }
        .rg-btn-cancel:hover { background:#e2e8f0; }
        .rg-btn-save { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 6px 20px rgba(37,99,235,0.3); }
        .rg-btn-save:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(37,99,235,0.4); }
        @media(max-width:640px) { .rg-row { grid-template-columns:1fr; } .rg-hdr-title { font-size:1.25rem; } }
    </style>
    @endpush

    <div class="py-4">
        <div class="rg-page">
            <div class="rg-hdr">
                <div class="rg-hdr-ico">🗺️</div>
                <div>
                    <div class="rg-hdr-title">Tambah Regional</div>
                    <div class="rg-hdr-sub">Buat area kerja baru & atur harga per produk</div>
                </div>
            </div>

            <form method="POST" action="{{ route('mineral.regional.store') }}" class="rg-form">
                @csrf

                <div class="rg-section">
                    <div class="rg-section-title">Informasi Regional</div>
                    <div class="rg-row">
                        <div class="rg-field">
                            <label class="rg-lbl">Nama Regional <span class="req">*</span></label>
                            <input type="text" name="nama" class="rg-inp @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Jakarta Utara" required>
                            @error('nama') <div class="rg-err">{{ $message }}</div> @enderror
                        </div>
                        <div class="rg-field">
                            <label class="rg-lbl">Status <span class="req">*</span></label>
                            <select name="status" class="rg-inp" required>
                                <option value="aktif" {{ old('status')=='aktif'?'selected':'' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="rg-row full">
                        <div class="rg-field">
                            <label class="rg-lbl">Deskripsi</label>
                            <textarea name="deskripsi" class="rg-inp" placeholder="Deskripsi area kerja regional...">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="rg-section">
                    <div class="rg-section-title">Harga Jual per Produk</div>
                    <p class="rg-hint" style="margin-bottom:1rem;">Isi harga khusus untuk regional ini. Kosongkan jika ingin menggunakan harga default produk.</p>

                    @if($produks->count())
                        <table class="rg-harga-tbl">
                            <thead>
                                <tr>
                                    <th style="width:40%;">Produk</th>
                                    <th style="width:20%;">Harga Default</th>
                                    <th style="width:40%;">Harga Regional</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produks as $produk)
                                    <tr>
                                        <td>
                                            <div style="font-weight:600;color:#0f172a;">{{ $produk->nama }}</div>
                                            <div style="font-size:0.6875rem;color:#94a3b8;">{{ $produk->kode_produk }} · {{ $produk->satuan }}</div>
                                        </td>
                                        <td>
                                            <div class="rg-harga-default">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</div>
                                        </td>
                                        <td>
                                            <input type="number" name="harga[{{ $produk->id }}]" class="rg-harga-inp"
                                                placeholder="{{ number_format($produk->harga_jual, 0, ',', '.') }}"
                                                value="{{ old('harga.' . $produk->id) }}" min="0" step="100">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p style="color:#94a3b8;font-size:0.875rem;">Belum ada produk aktif.</p>
                    @endif
                </div>

                <div class="rg-btns">
                    <a href="{{ route('mineral.regional.index') }}" class="rg-btn rg-btn-cancel">Batal</a>
                    <button type="submit" class="rg-btn rg-btn-save">Simpan Regional</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
