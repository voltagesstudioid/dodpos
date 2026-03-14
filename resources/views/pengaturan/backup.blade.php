<x-app-layout>
    <x-slot name="header">Backup &amp; Restore</x-slot>

    <style>
        .bk-grid { display: grid; grid-template-columns: 1fr; gap: 1rem; }
        @media (min-width: 900px) { .bk-grid { grid-template-columns: 1fr 1fr; } }
        .bk-card { border: 1px solid #e2e8f0; border-radius: 14px; padding: 1rem; background: #fff; }
        .bk-card.danger { border-color: #fecaca; }
        .bk-title { font-weight: 900; color: #0f172a; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem; }
        .bk-title.danger { color: #7f1d1d; }
        .bk-desc { color: #64748b; font-size: 0.875rem; line-height: 1.65; margin-bottom: 0.9rem; }
        .bk-desc.danger { color: #991b1b; }
        .bk-note { margin-top: 0.75rem; color: #94a3b8; font-size: 0.8rem; line-height: 1.6; }
        .bk-helpbox { margin-top: 1rem; padding: 0.95rem 1rem; border: 1px dashed #c7d2fe; border-radius: 14px; background: #eef2ff; color: #3730a3; font-size: 0.875rem; line-height: 1.7; }
        .bk-list { margin: 0.5rem 0 0; padding-left: 1.1rem; color: #475569; font-size: 0.85rem; line-height: 1.65; }
        .bk-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; }
        .bk-steps { display: flex; flex-direction: column; gap: 0.6rem; margin-top: 0.75rem; }
        .bk-step { display: flex; gap: 0.75rem; align-items: flex-start; padding: 0.75rem 0.85rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; }
        .bk-step-num { width: 26px; height: 26px; border-radius: 10px; background: #eef2ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.8rem; flex: 0 0 26px; }
        .bk-step-title { font-weight: 900; color: #0f172a; font-size: 0.85rem; margin-bottom: 0.15rem; }
        .bk-step-desc { color: #64748b; font-size: 0.82rem; line-height: 1.6; }
    </style>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">❌ {{ session('error') }}</div>   @endif

        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">🛡️ Backup &amp; Restore (Pengaturan)</div>
                    <div class="panel-subtitle">Backup/restore untuk pengaturan dan data master (bukan transaksi)</div>
                </div>
            </div>

            <div class="panel-body">
                <div class="bk-grid">
                    <div class="bk-card">
                        <div class="bk-title">⬇️ Download Backup</div>
                        <div class="bk-desc">
                            File ini dipakai untuk memindahkan konfigurasi sistem dan data master antar instalasi.
                        </div>
                        <div class="bk-desc" style="margin-bottom: 0.5rem;">
                            Termasuk:
                            <ul class="bk-list">
                                <li>Pengaturan Toko</li>
                                <li>Kategori, Satuan, Brand</li>
                                <li>Supplier, Gudang, Lokasi</li>
                            </ul>
                        </div>
                        @can('create_backup_restore')
                            <form method="POST" action="{{ route('pengaturan.backup.export') }}">
                                @csrf
                                <div class="bk-actions">
                                    <button type="submit" class="btn-primary">⬇️ Download JSON Backup</button>
                                    <a href="{{ route('pengaturan.toko') }}" class="btn-secondary">🏪 Buka Pengaturan Toko</a>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning" style="margin:0;">
                                Anda memiliki akses lihat, namun tidak punya izin untuk export/restore backup.
                            </div>
                            <div class="bk-actions" style="margin-top:0.75rem;">
                                <a href="{{ route('pengaturan.toko') }}" class="btn-secondary">🏪 Buka Pengaturan Toko</a>
                            </div>
                        @endcan
                        <div class="bk-note">
                            Catatan: file ini tidak menyertakan password user dan tidak menyertakan transaksi.
                        </div>
                        <div class="bk-steps">
                            <div class="bk-step">
                                <div class="bk-step-num">1</div>
                                <div>
                                    <div class="bk-step-title">Download</div>
                                    <div class="bk-step-desc">Unduh file backup dan simpan di lokasi aman.</div>
                                </div>
                            </div>
                            <div class="bk-step">
                                <div class="bk-step-num">2</div>
                                <div>
                                    <div class="bk-step-title">Pindahkan</div>
                                    <div class="bk-step-desc">Salin file ke komputer/server tujuan (instalasi DODPOS lain).</div>
                                </div>
                            </div>
                            <div class="bk-step">
                                <div class="bk-step-num">3</div>
                                <div>
                                    <div class="bk-step-title">Restore</div>
                                    <div class="bk-step-desc">Upload file pada menu restore dan masukkan password supervisor.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bk-card danger">
                        <div class="bk-title danger">♻️ Restore Backup</div>
                        <div class="bk-desc danger">
                            Restore akan melakukan update/insert berdasarkan ID. Gunakan hanya jika paham risikonya.
                        </div>
                        @can('create_backup_restore')
                            <form method="POST" action="{{ route('pengaturan.backup.restore') }}" enctype="multipart/form-data">
                                @csrf

                                <div style="display:flex;flex-direction:column;gap:0.85rem;">
                                    <div>
                                        <label class="form-label">File Backup (JSON) <span class="required">*</span></label>
                                        <input type="file" name="backup_file" accept=".json,.txt" class="form-input" required>
                                        @error('backup_file') <div class="text-danger" style="font-size:0.8rem;margin-top:0.25rem;">{{ $message }}</div> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label">Konfirmasi Password Anda <span class="required">*</span></label>
                                        <input type="password" name="password" class="form-input" required placeholder="Masukkan password Anda">
                                        @error('password') <div class="text-danger" style="font-size:0.8rem;margin-top:0.25rem;">{{ $message }}</div> @enderror
                                        <div class="bk-note">Dipakai untuk memastikan restore dilakukan oleh pengguna yang sedang login.</div>
                                    </div>
                                    <button type="submit" class="btn-danger" onclick="return confirm('Lanjutkan restore? Data master/pengaturan akan diperbarui.')">
                                        ♻️ Restore Sekarang
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning" style="margin:0;">
                                Anda tidak punya izin untuk restore. Hubungi supervisor/admin.
                            </div>
                        @endcan
                    </div>
                </div>

                <div class="bk-helpbox">
                    Untuk backup database penuh (termasuk transaksi), lakukan dari server/database (mysqldump / phpMyAdmin). Lebih aman dan lengkap daripada restore via web.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
