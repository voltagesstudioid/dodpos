<x-app-layout>
    <x-slot name="header">Backup &amp; Restore</x-slot>

    <style>
        .bk-wrapper { margin-top: 1.5rem; }
        .bk-grid { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
        @media (min-width: 950px) { .bk-grid { grid-template-columns: 1fr 1fr; } }
        
        /* Premium Card Styles */
        .bk-card { 
            background: #ffffff; 
            border-radius: 16px; 
            border: 1px solid #e2e8f0; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); 
            overflow: hidden; 
            position: relative;
            transition: all 0.3s ease;
        }
        .bk-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04);
            transform: translateY(-2px);
        }
        .bk-card.primary { border-top: 4px solid #4f46e5; }
        .bk-card.danger { border-top: 4px solid #ef4444; }
        
        .bk-header { padding: 1.5rem 1.5rem 1rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 1rem; }
        .bk-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
        .bk-card.primary .bk-icon { background: linear-gradient(135deg, #eef2ff 0%, #c7d2fe 100%); color: #4338ca; box-shadow: 0 4px 10px rgba(67,56,202,0.15); }
        .bk-card.danger .bk-icon { background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%); color: #b91c1c; box-shadow: 0 4px 10px rgba(220,38,38,0.15); }
        
        .bk-title { font-weight: 800; color: #0f172a; font-size: 1.15rem; letter-spacing: -0.01em; margin-bottom: 0.15rem; }
        .bk-subtitle { color: #64748b; font-size: 0.8125rem; line-height: 1.4; }
        
        .bk-body { padding: 1.5rem; }
        
        .bk-info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem; }
        .bk-info-box.danger { background: #fff1f2; border-color: #fecdd3; }
        
        .bk-info-title { font-weight: 700; font-size: 0.85rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .bk-info-box .bk-info-title { color: #334155; }
        .bk-info-box.danger .bk-info-title { color: #9f1239; }
        
        .bk-list { margin: 0; padding-left: 1.25rem; color: #475569; font-size: 0.85rem; line-height: 1.6; }
        .bk-info-box.danger .bk-list { color: #be123c; }
        
        .bk-steps { display: flex; flex-direction: column; gap: 0.85rem; margin-top: 1.5rem; }
        .bk-step { display: flex; gap: 1rem; align-items: flex-start; padding: 1rem; border-radius: 12px; border: 1px solid #f1f5f9; background: #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.02); transition: background 0.2s; }
        .bk-step:hover { background: #f8fafc; }
        .bk-step-num { width: 28px; height: 28px; border-radius: 8px; background: #4f46e5; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex: 0 0 28px; box-shadow: 0 2px 6px rgba(79,70,229,0.25); }
        .bk-step-title { font-weight: 800; color: #1e293b; font-size: 0.9rem; margin-bottom: 0.25rem; }
        .bk-step-desc { color: #64748b; font-size: 0.8125rem; line-height: 1.5; }
        
        .bk-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; }
        
        .bk-form-group { margin-bottom: 1.25rem; }
        .bk-form-label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem; }
        .bk-form-label .required { color: #ef4444; margin-left: 0.25rem; }
        .bk-form-input { width: 100%; padding: 0.65rem 1rem; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; transition: all 0.2s; background: #fff; }
        .bk-form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.1); outline: none; }
        
        .bk-file-input-wrapper { position: relative; overflow: hidden; display: inline-block; width: 100%; }
        .bk-file-input { width: 100%; padding: 0.65rem; border: 2px dashed #cbd5e1; border-radius: 10px; background: #f8fafc; text-align: center; cursor: pointer; transition: all 0.2s; font-size: 0.85rem; color: #64748b; }
        .bk-file-input:hover { border-color: #94a3b8; background: #f1f5f9; }
        .bk-file-input:focus { border-color: #ef4444; box-shadow: 0 0 0 4px rgba(239,68,68,0.1); outline: none; }
        
        .bk-note { margin-top: 0.5rem; color: #94a3b8; font-size: 0.75rem; line-height: 1.5; font-style: italic; }
        
        .bk-helpbox { margin-top: 2rem; padding: 1.25rem 1.5rem; border-radius: 16px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 1px solid #bae6fd; display: flex; gap: 1rem; align-items: flex-start; box-shadow: inset 0 2px 4px rgba(255,255,255,0.5); }
        .bk-helpbox-icon { font-size: 1.75rem; flex-shrink: 0; line-height: 1; }
        .bk-helpbox-content { color: #0369a1; font-size: 0.875rem; line-height: 1.6; }
        .bk-helpbox-content strong { color: #075985; }
    </style>

    <div class="page-container">
        @if(session('success')) 
            <div class="alert alert-success animate-in" style="border-radius: 12px; box-shadow: 0 2px 10px rgba(21,128,61,0.1);">
                <span style="font-size:1.25rem;">✅</span> {{ session('success') }}
            </div> 
        @endif
        @if(session('error'))   
            <div class="alert alert-danger animate-in" style="border-radius: 12px; box-shadow: 0 2px 10px rgba(185,28,28,0.1);">
                <span style="font-size:1.25rem;">❌</span> {{ session('error') }}
            </div>   
        @endif

        <div class="ph animate-in">
            <div class="ph-left">
                <div class="ph-icon slate">🛡️</div>
                <div>
                    <h1 class="ph-title">Backup &amp; Restore (Pengaturan)</h1>
                    <div class="ph-subtitle">Cadangkan dan pulihkan konfigurasi sistem serta data master secara aman.</div>
                </div>
            </div>
        </div>

        <div class="bk-wrapper animate-in animate-in-delay-1">
            <div class="bk-grid">
                
                <!-- KARTU DOWNLOAD -->
                <div class="bk-card primary">
                    <div class="bk-header">
                        <div class="bk-icon">💾</div>
                        <div>
                            <div class="bk-title">Download Backup</div>
                            <div class="bk-subtitle">Unduh konfigurasi saat ini ke dalam file JSON aman.</div>
                        </div>
                    </div>
                    <div class="bk-body">
                        <div class="bk-info-box">
                            <div class="bk-info-title">📋 Termasuk dalam Backup:</div>
                            <ul class="bk-list">
                                <li>Pengaturan Toko Utama</li>
                                <li>Kategori, Satuan, & Brand Produk</li>
                                <li>Data Supplier, Gudang, & Lokasi Rak</li>
                            </ul>
                            <div class="bk-note" style="margin-top: 0.75rem; color: #64748b;">
                                ⚠️ Catatan: File backup ini <strong>tidak</strong> menyertakan data transaksi, riwayat stok, maupun password/akun pengguna.
                            </div>
                        </div>

                        <div class="bk-steps">
                            <div class="bk-step">
                                <div class="bk-step-num">1</div>
                                <div>
                                    <div class="bk-step-title">Unduh (Download)</div>
                                    <div class="bk-step-desc">Tekan tombol export di bawah untuk mengunduh file backup ke komputer Anda.</div>
                                </div>
                            </div>
                            <div class="bk-step">
                                <div class="bk-step-num">2</div>
                                <div>
                                    <div class="bk-step-title">Simpan & Pindahkan</div>
                                    <div class="bk-step-desc">Simpan file tersebut di lokasi aman, flashdisk, atau kirim ke instalasi DODPOS lainnya.</div>
                                </div>
                            </div>
                        </div>

                        @can('create_backup_restore')
                            <form method="POST" action="{{ route('pengaturan.backup.export') }}">
                                @csrf
                                <div class="bk-actions">
                                    <button type="submit" class="btn-primary" style="padding: 0.7rem 1.25rem; font-size: 0.9rem; border-radius: 10px;">
                                        ⬇️ Download JSON Backup
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning" style="margin-top: 1.5rem; margin-bottom: 0; border-radius: 10px;">
                                🔒 Anda memiliki akses lihat, namun tidak punya izin untuk melakukan Export Backup.
                            </div>
                        @endcan
                    </div>
                </div>

                <!-- KARTU RESTORE -->
                <div class="bk-card danger">
                    <div class="bk-header">
                        <div class="bk-icon">♻️</div>
                        <div>
                            <div class="bk-title">Restore Backup</div>
                            <div class="bk-subtitle">Pulihkan sistem menggunakan file JSON yang valid.</div>
                        </div>
                    </div>
                    <div class="bk-body">
                        <div class="bk-info-box danger">
                            <div class="bk-info-title">⚠️ Peringatan Kritis</div>
                            <div style="font-size: 0.8125rem; color: #9f1239; line-height: 1.6;">
                                Proses Restore akan melakukan <strong>Timpa (Update)</strong> atau <strong>Tambah (Insert)</strong> data master secara paksa berdasarkan ID. Gunakan fitur ini <strong>hanya jika Anda memahami risikonya</strong>. Pemulihan ke server produksi dapat mengubah pengaturan toko seketika.
                            </div>
                        </div>

                        @can('create_backup_restore')
                            <form method="POST" action="{{ route('pengaturan.backup.restore') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="bk-form-group">
                                    <label class="bk-form-label">File Backup (Format JSON) <span class="required">*</span></label>
                                    <input type="file" name="backup_file" accept=".json,.txt" class="bk-file-input" required>
                                    @error('backup_file') <div class="text-danger" style="font-size:0.8rem;margin-top:0.35rem;">{{ $message }}</div> @enderror
                                    <div class="bk-note">Pilih file yang sebelumnya diunduh melalui fitur Download Backup.</div>
                                </div>

                                <div class="bk-form-group">
                                    <label class="bk-form-label">Konfirmasi Password Anda <span class="required">*</span></label>
                                    <input type="password" name="password" class="bk-form-input" required placeholder="Ketik password login Anda saat ini...">
                                    @error('password') <div class="text-danger" style="font-size:0.8rem;margin-top:0.35rem;">{{ $message }}</div> @enderror
                                    <div class="bk-note">Diperlukan sebagai langkah otorisasi untuk memastikan bahwa Anda sadar sedang melakukan restore.</div>
                                </div>

                                <div class="bk-actions">
                                    <button type="submit" class="btn-danger" style="padding: 0.7rem 1.25rem; font-size: 0.9rem; border-radius: 10px;" onclick="return confirm('⚠️ APAKAH ANDA YAKIN? Data master/pengaturan akan ditimpa dengan isi file backup ini. Lanjutkan?')">
                                        ♻️ Restore Data Sekarang
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning" style="margin-top: 1rem; margin-bottom: 0; border-radius: 10px;">
                                🔒 Anda tidak punya izin otorisasi untuk melakukan Restore. Hubungi supervisor atau admin utama.
                            </div>
                        @endcan
                    </div>
                </div>

            </div>

            <!-- INFO / HELP BOX -->
            <div class="bk-helpbox">
                <div class="bk-helpbox-icon">💡</div>
                <div class="bk-helpbox-content">
                    <strong>Catatan Developer:</strong> Fitur Backup & Restore di halaman ini dirancang khusus untuk mempermudah sinkronisasi pengaturan (Settings & Master Data) antar cabang/instalasi baru. Jika Anda membutuhkan <strong>Backup Database Penuh</strong> (termasuk riwayat transaksi, absen, dan log), silakan lakukan backup langsung dari panel server (misalnya melalui <em>phpMyAdmin</em> atau <em>mysqldump</em>).
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
