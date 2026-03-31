<x-app-layout>
    <x-slot name="header">Pengaturan Toko</x-slot>

    <style>
        .settings-wrap { display: flex; flex-direction: column; gap: 1.5rem; }
        .settings-body { padding: 0; }
        .settings-section { border: none; border-radius: 16px; background: #fff; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .settings-section-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: transparent; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .settings-section-title { font-weight: 800; color: #0f172a; font-size: 1.05rem; display: flex; align-items: center; gap: 0.5rem; }
        .settings-section-sub { font-size: 0.85rem; color: #64748b; margin-top: 0.25rem; }
        .settings-section-content { padding: 1.5rem; }
        .settings-grid-2 { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
        @media (min-width: 900px) { .settings-grid-2 { grid-template-columns: 1fr 1fr; } }
        .settings-help { font-size: 0.8rem; color: #94a3b8; margin-top: 0.4rem; line-height: 1.4; }
        .settings-error { font-size: 0.8rem; margin-top: 0.4rem; color: #dc2626; font-weight: 600; }
        .logo-preview { border: 1px dashed #cbd5e1; border-radius: 16px; padding: 1rem; background: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 160px; }
        .actions-bar { display: flex; gap: 0.75rem; justify-content: flex-end; flex-wrap: wrap; padding-top: 0.5rem; }
        .badge-soft { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.35rem 0.85rem; border-radius: 999px; background: #f1f5f9; color: #475569; font-size: 0.75rem; font-weight: 700; }
        .settings-layout { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
        @media (min-width: 1024px) { .settings-layout { grid-template-columns: 280px 1fr; } }
        .settings-sidenav { border: none; border-radius: 16px; background: #fff; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); height: fit-content; position: sticky; top: 1.5rem; }
        .settings-sidenav-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: transparent; }
        .settings-sidenav-title { font-weight: 800; color: #0f172a; font-size: 1.05rem; }
        .settings-sidenav-items { padding: 0.75rem; display: flex; flex-direction: column; gap: 0.25rem; }
        .settings-sidenav a { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 12px; text-decoration: none; color: #475569; font-weight: 600; font-size: 0.9rem; border: 1px solid transparent; transition: all 0.2s; }
        .settings-sidenav a:hover { background: #f8fafc; color: #0f172a; }
        .settings-sidenav a .muted { font-weight: 600; color: #94a3b8; font-size: 0.75em; margin-left: auto; background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 6px; }
        .settings-sticky-actions { position: sticky; bottom: 1.5rem; z-index: 5; background: rgba(255, 255, 255, 0.9); border: 1px solid #e2e8f0; padding: 1rem 1.5rem; border-radius: 99px; backdrop-filter: blur(12px); display: flex; gap: 0.75rem; justify-content: flex-end; flex-wrap: wrap; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); margin-top: 2rem; }
        .settings-top-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; }
        .settings-anchor { scroll-margin-top: 92px; }
    </style>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">❌ {{ session('error') }}</div>   @endif

        <div class="page-header" style="background: transparent; box-shadow: none; padding: 0 0 1.5rem 0; border-bottom: 1px solid #e2e8f0; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0; letter-spacing: -0.02em;">Pengaturan Toko</h1>
                <div style="color: #64748b; font-size: 0.95rem;">Identitas toko, struk, dan aturan sistem terpusat.</div>
            </div>
            <div class="settings-top-actions">
                <a href="#sec-logo" class="btn-secondary" style="border-radius: 999px;">🖼️ Logo</a>
                @can('edit_pengaturan_toko')
                <button type="submit" form="store-settings-form" class="btn-primary" style="border-radius: 999px;">💾 Simpan</button>
                @endcan
            </div>
        </div>

            <form id="store-settings-form" method="POST" action="{{ route('pengaturan.toko.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="settings-body">
                    <div class="settings-layout">
                        <div class="settings-sidenav">
                            <div class="settings-sidenav-header">
                                <div class="settings-sidenav-title">Navigasi</div>
                                <div class="settings-section-sub">Lompat cepat ke bagian pengaturan.</div>
                            </div>
                            <div class="settings-sidenav-items">
                                <a href="#sec-identitas">🏬 Identitas <span class="muted">Nama/Alamat</span></a>
                                <a href="#sec-transaksi">🧾 Aturan <span class="muted">Pajak/Rounding</span></a>
                                <a href="#sec-struk">🧾 Struk <span class="muted">Header/Footer</span></a>
                                <a href="#sec-fingerprint">👆 Fingerprint <span class="muted">X606-S</span></a>
                                <a href="#sec-logo">🖼️ Logo <span class="muted">Upload</span></a>
                                @can('view_backup_restore')
                                <a href="{{ route('pengaturan.backup') }}">🛡️ Backup <span class="muted">JSON</span></a>
                                @endcan
                                <div style="height:1px;background:#e2e8f0;margin:0.5rem 0;"></div>
                                @can('view_pengguna')
                                <a href="{{ route('pengguna.index') }}">👤 Pengguna <span class="muted">Akun</span></a>
                                @endcan
                                <a href="{{ route('pengaturan.roles.index') }}">🔐 Master Roles <span class="muted">CRUD</span></a>
                                @can('view_log_aktivitas')
                                <a href="{{ route('activity-log.index') }}">🧾 Log Aktivitas <span class="muted">Audit</span></a>
                                @endcan
                            </div>
                        </div>

                        <div class="settings-wrap">
                        <div id="sec-identitas" class="settings-section settings-anchor">
                            <div class="settings-section-header">
                                <div>
                                    <div class="settings-section-title">🏬 Identitas</div>
                                    <div class="settings-section-sub">Data yang muncul di header dan dokumen/struk</div>
                                </div>
                            </div>
                            <div class="settings-section-content">
                                <div class="settings-grid-2">
                                    <div>
                                        <label class="form-label" style="font-weight: 600;">Nama Toko <span class="required" style="color: #ef4444;">*</span></label>
                                        <input name="store_name" value="{{ old('store_name', $setting->store_name) }}" class="form-input" style="background: #f8fafc;" required>
                                        <div class="settings-help">Contoh: DODPOS / Toko Sumber Rejeki</div>
                                        @error('store_name') <div class="settings-error">{{ $message }}</div> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-weight: 600;">Telepon</label>
                                        <input name="store_phone" value="{{ old('store_phone', $setting->store_phone) }}" class="form-input" style="background: #f8fafc;" placeholder="Contoh: 0812xxxxxx">
                                        @error('store_phone') <div class="settings-error">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="settings-grid-2" style="margin-top: 1.5rem;">
                                    <div>
                                        <label class="form-label" style="font-weight: 600;">Email</label>
                                        <input type="email" name="store_email" value="{{ old('store_email', $setting->store_email) }}" class="form-input" style="background: #f8fafc;" placeholder="Contoh: toko@domain.com">
                                        @error('store_email') <div class="settings-error">{{ $message }}</div> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-weight: 600;">Timezone <span class="required" style="color: #ef4444;">*</span></label>
                                        @php $tz = old('timezone', $setting->timezone); @endphp
                                        <select name="timezone" class="form-input" style="background: #f8fafc;" required>
                                            <option value="Asia/Jakarta" {{ $tz === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                            <option value="Asia/Makassar" {{ $tz === 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                            <option value="Asia/Jayapura" {{ $tz === 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                                            <option value="UTC" {{ $tz === 'UTC' ? 'selected' : '' }}>UTC</option>
                                        </select>
                                        <div class="settings-help">Mempengaruhi jam transaksi, laporan, dan nomor dokumen harian.</div>
                                        @error('timezone') <div class="settings-error">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div style="margin-top: 1.5rem;">
                                    <label class="form-label" style="font-weight: 600;">Alamat Lengkap</label>
                                    <textarea name="store_address" class="form-input" style="background: #f8fafc;" rows="3" placeholder="Alamat toko...">{{ old('store_address', $setting->store_address) }}</textarea>
                                    @error('store_address') <div class="settings-error">{{ $message }}</div> @enderror
                                </div>

                                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed #cbd5e1;">
                                    <div style="font-weight: 800; color: #0f172a; margin-bottom: 1rem; font-size: 1.05rem; display: flex; align-items: center; gap: 0.5rem;">💳 Rekening Transfer</div>
                                    <div class="settings-grid-2">
                                        <div>
                                            <label class="form-label" style="font-weight: 600;">Nama Bank</label>
                                            <input name="bank_name" value="{{ old('bank_name', $setting->bank_name) }}" class="form-input" style="background: #f8fafc;" placeholder="Contoh: BCA / Mandiri / BRI">
                                            @error('bank_name') <div class="settings-error">{{ $message }}</div> @enderror
                                        </div>
                                        <div>
                                            <label class="form-label" style="font-weight: 600;">Nomor Rekening</label>
                                            <input name="bank_account_number" value="{{ old('bank_account_number', $setting->bank_account_number) }}" class="form-input" style="background: #f8fafc;" placeholder="Contoh: 1234567890">
                                            @error('bank_account_number') <div class="settings-error">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div style="margin-top: 1.5rem;">
                                        <label class="form-label" style="font-weight: 600;">Atas Nama</label>
                                        <input name="bank_account_holder" value="{{ old('bank_account_holder', $setting->bank_account_holder) }}" class="form-input" style="background: #f8fafc;" placeholder="Contoh: Toko DODPOS">
                                        @error('bank_account_holder') <div class="settings-error">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="settings-help">Ditampilkan saat metode pembayaran Transfer dipilih.</div>
                                </div>
                            </div>
                        </div>

                        <div id="sec-transaksi" class="settings-section settings-anchor">
                            <div class="settings-section-header">
                                <div>
                                    <div class="settings-section-title">🧾 Aturan Transaksi</div>
                                    <div class="settings-section-sub">Simbol mata uang, pajak, dan pembulatan</div>
                                </div>
                                <span class="badge-soft">Disimpan otomatis</span>
                            </div>
                            <div class="settings-section-content">
                                <div class="settings-grid-2">
                                    <div>
                                        <label class="form-label" style="font-weight: 600;">Simbol Mata Uang <span class="required" style="color: #ef4444;">*</span></label>
                                        <input name="currency_symbol" value="{{ old('currency_symbol', $setting->currency_symbol) }}" class="form-input" style="background: #f8fafc;" required placeholder="Rp">
                                        @error('currency_symbol') <div class="settings-error">{{ $message }}</div> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-weight: 600;">PPN / Pajak (%) <span class="required" style="color: #ef4444;">*</span></label>
                                        <input type="number" step="0.01" min="0" max="100" name="tax_rate" value="{{ old('tax_rate', $setting->tax_rate) }}" class="form-input" style="background: #f8fafc;" required>
                                        <div class="settings-help">Isi 0 jika tidak menggunakan pajak.</div>
                                        @error('tax_rate') <div class="settings-error">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div style="margin-top: 1.5rem;">
                                    <label class="form-label" style="font-weight: 600;">Pembulatan Total <span class="required" style="color: #ef4444;">*</span></label>
                                    <select name="rounding_mode" class="form-input" style="background: #f8fafc;" required>
                                        @php $rm = old('rounding_mode', $setting->rounding_mode); @endphp
                                        <option value="none" {{ $rm === 'none' ? 'selected' : '' }}>Tidak ada</option>
                                        <option value="nearest_100" {{ $rm === 'nearest_100' ? 'selected' : '' }}>Ke 100 terdekat</option>
                                        <option value="nearest_500" {{ $rm === 'nearest_500' ? 'selected' : '' }}>Ke 500 terdekat</option>
                                        <option value="nearest_1000" {{ $rm === 'nearest_1000' ? 'selected' : '' }}>Ke 1000 terdekat</option>
                                    </select>
                                    @error('rounding_mode') <div class="settings-error">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div id="sec-struk" class="settings-section settings-anchor">
                            <div class="settings-section-header">
                                <div>
                                    <div class="settings-section-title">🧾 Template Struk</div>
                                    <div class="settings-section-sub">Teks opsional untuk ditampilkan di struk</div>
                                </div>
                            </div>
                            <div class="settings-section-content">
                                <div class="settings-grid-2">
                                    <div>
                                        <label class="form-label" style="font-weight: 600;">Header Struk</label>
                                        <textarea name="receipt_header" class="form-input" style="background: #f8fafc;" rows="4" placeholder="Tampil di atas struk...">{{ old('receipt_header', $setting->receipt_header) }}</textarea>
                                        <div class="settings-help">Contoh teks sambutan atau promosi singkat.</div>
                                        @error('receipt_header') <div class="settings-error">{{ $message }}</div> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-weight: 600;">Footer Struk</label>
                                        <textarea name="receipt_footer" class="form-input" style="background: #f8fafc;" rows="4" placeholder="Tampil di bawah struk...">{{ old('receipt_footer', $setting->receipt_footer) }}</textarea>
                                        <div class="settings-help">Kebijakan retur, jam operasional, pesan terima kasih dsb.</div>
                                        @error('receipt_footer') <div class="settings-error">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="sec-fingerprint" class="settings-section settings-anchor">
                            <div class="settings-section-header">
                                <div>
                                    <div class="settings-section-title">👆 Mesin Fingerprint (Absensi)</div>
                                    <div class="settings-section-sub">Koneksi TCP/IP ke mesin absensi lokal (e.g. Solution X606-S)</div>
                                </div>
                            </div>
                            <div class="settings-section-content">
                                <div class="settings-grid-2">
                                    <div>
                                        <label class="form-label" style="font-weight: 600;">IP Address Mesin</label>
                                        <input name="fingerprint_ip" value="{{ old('fingerprint_ip', $setting->fingerprint_ip) }}" class="form-input" style="background: #f8fafc;" placeholder="Contoh: 192.168.1.201">
                                        <div class="settings-help">Lokasi IP di jaringan lokal. Kosongi jika tidak menggunakan.</div>
                                        @error('fingerprint_ip') <div class="settings-error">{{ $message }}</div> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-weight: 600;">Port Komunikasi Mesin</label>
                                        <input name="fingerprint_port" value="{{ old('fingerprint_port', $setting->fingerprint_port ?? '4370') }}" class="form-input" style="background: #f8fafc;" placeholder="Default: 4370">
                                        <div class="settings-help">Port TCP standar biasanya 4370 (Tipe ZKTeco/Solution).</div>
                                        @error('fingerprint_port') <div class="settings-error">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="sec-logo" class="settings-section settings-anchor">
                            <div class="settings-section-header">
                                <div>
                                    <div class="settings-section-title">🖼️ Logo</div>
                                    <div class="settings-section-sub">Dipakai untuk identitas toko dan dokumen</div>
                                </div>
                            </div>
                            <div class="settings-section-content">
                                <div class="settings-grid-2" style="align-items: start;">
                                    <div>
                                        <label class="form-label">Upload Logo</label>
                                        <input type="file" name="logo" accept=".png,.jpg,.jpeg,.webp" class="form-input">
                                        <div class="settings-help">Format PNG/JPG/WEBP, maksimal 2MB.</div>
                                        @error('logo') <div class="settings-error">{{ $message }}</div> @enderror
                                        @if($setting->logo_path)
                                            <label style="display:flex;align-items:center;gap:0.5rem;margin-top:0.85rem;">
                                                <input type="checkbox" name="remove_logo" value="1">
                                                <span style="font-size:0.875rem;color:#ef4444;font-weight:700;">Hapus logo saat ini</span>
                                            </label>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="form-label">Preview</label>
                                        <div class="logo-preview">
                                            @if($setting->logo_path)
                                                <img src="{{ asset($setting->logo_path) }}" alt="Logo" style="max-height:110px;max-width:100%;object-fit:contain;">
                                            @else
                                                <div style="color:#94a3b8;font-size:0.875rem;">Belum ada logo</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="settings-sticky-actions">
                            <a href="{{ route('dashboard') }}" class="btn-secondary" style="border-radius: 999px;">Batal</a>
                            @can('edit_pengaturan_toko')
                            <button type="submit" class="btn-primary" style="border-radius: 999px; padding: 0.6rem 1.5rem;">💾 Simpan Pengaturan</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @cannot('edit_pengaturan_toko')
        <script>
            (function () {
                const form = document.getElementById('store-settings-form');
                if (!form) return;
                form.querySelectorAll('input, select, textarea, button').forEach((el) => {
                    if (el.tagName === 'BUTTON') return;
                    el.disabled = true;
                });
            })();
        </script>
    @endcannot
</x-app-layout>
