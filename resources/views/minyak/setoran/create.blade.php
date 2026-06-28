<x-app-layout>
    @push('styles')
    <style>
        .sr { max-width:44rem; margin:0 auto; padding:1.25rem 1rem 4rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .sr-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .sr-hdr-l { display:flex; align-items:center; gap:1rem; }
        .sr-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#6366f1,#4f46e5);
            box-shadow:0 8px 24px rgba(79,70,229,0.3);
        }
        .sr-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .sr-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .sr-back {
            display:inline-flex; align-items:center; gap:0.375rem; font-size:0.8125rem; font-weight:600;
            color:#94a3b8; text-decoration:none; transition:all .2s;
        }
        .sr-back:hover { color:#334155; }
        .sr-back svg { transition:transform .2s; }
        .sr-back:hover svg { transform:translateX(-3px); }

        .sr-sec {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; margin-bottom:1rem;
            box-shadow:0 1px 3px rgba(0,0,0,0.04); overflow:hidden;
        }
        .sr-sec-hdr {
            padding:1rem 1.375rem; display:flex; align-items:center; gap:0.75rem;
            border-bottom:1px solid #f1f5f9;
        }
        .sr-sec-ico {
            width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0;
        }
        .sr-sec-ico.indigo { background:linear-gradient(135deg,#eef2ff,#e0e7ff); color:#4f46e5; }
        .sr-sec-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#059669; }
        .sr-sec-ico.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); color:#d97706; }
        .sr-sec-ico.rose { background:linear-gradient(135deg,#fff1f2,#ffe4e6); color:#e11d48; }
        .sr-sec-title { font-size:0.9375rem; font-weight:700; color:#0f172a; }
        .sr-sec-body { padding:1.25rem 1.375rem; }

        .sr-grid { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; }
        .sr-stat {
            padding:0.875rem 1rem; border-radius:12px; border:1px solid #e2e8f0;
        }
        .sr-stat-lbl { font-size:0.625rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.25rem; }
        .sr-stat-val { font-size:1.125rem; font-weight:800; letter-spacing:-0.01em; }
        .sr-stat-sub { font-size:0.625rem; margin-top:0.125rem; }
        .sr-stat.green { background:#f0fdf4; border-color:#a7f3d0; }
        .sr-stat.green .sr-stat-lbl { color:#065f46; }
        .sr-stat.green .sr-stat-val { color:#047857; }
        .sr-stat.green .sr-stat-sub { color:#059669; }
        .sr-stat.blue { background:#eff6ff; border-color:#bfdbfe; }
        .sr-stat.blue .sr-stat-lbl { color:#1e40af; }
        .sr-stat.blue .sr-stat-val { color:#2563eb; }
        .sr-stat.blue .sr-stat-sub { color:#3b82f6; }
        .sr-stat.amber { background:#fffbeb; border-color:#fde68a; }
        .sr-stat.amber .sr-stat-lbl { color:#92400e; }
        .sr-stat.amber .sr-stat-val { color:#d97706; }
        .sr-stat.amber .sr-stat-sub { color:#b45309; }
        .sr-stat.slate { background:#f8fafc; border-color:#e2e8f0; }
        .sr-stat.slate .sr-stat-lbl { color:#475569; }
        .sr-stat.slate .sr-stat-val { color:#334155; }
        .sr-stat.slate .sr-stat-sub { color:#64748b; }

        .sr-sum-box {
            background:#fefce8; border:1px solid #fde68a; border-radius:12px; padding:1rem 1.125rem;
        }
        .sr-sum-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#854d0e; }
        .sr-sum-val { font-size:1.5rem; font-weight:800; color:#92400e; margin-top:0.25rem; }
        .sr-sum-sub { font-size:0.6875rem; color:#a16207; margin-top:0.375rem; line-height:1.5; }

        .sr-fld { margin-bottom:1rem; }
        .sr-fld:last-child { margin-bottom:0; }
        .sr-lbl { display:flex; align-items:center; gap:0.25rem; font-size:0.6875rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.375rem; }
        .sr-req { color:#ef4444; }
        .sr-inp {
            width:100%; padding:0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fafbfc; font-size:0.875rem; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; box-sizing:border-box;
        }
        .sr-inp:focus { border-color:#6366f1; background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,0.08); }
        .sr-sel {
            width:100%; padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fafbfc; font-size:0.875rem; color:#0f172a; outline:none; transition:all .2s;
            font-family:inherit; cursor:pointer; appearance:none; box-sizing:border-box;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.625rem center; background-size:0.875rem;
        }
        .sr-sel:focus { border-color:#6366f1; background-color:#fff; box-shadow:0 0 0 3px rgba(99,102,241,0.08); }
        .sr-ta {
            width:100%; padding:0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fafbfc; font-size:0.875rem; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; resize:vertical; min-height:70px; box-sizing:border-box;
        }
        .sr-ta:focus { border-color:#6366f1; background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,0.08); }
        .sr-money { position:relative; }
        .sr-money-pfx { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); font-size:0.75rem; font-weight:700; color:#94a3b8; pointer-events:none; }
        .sr-money .sr-inp { padding-left:2.25rem; }
        .sr-err { font-size:0.6875rem; color:#ef4444; margin-top:0.25rem; font-weight:500; }

        .sr-hint {
            padding:0.625rem 0.875rem; border-radius:10px; font-size:0.75rem; line-height:1.5; margin-top:0.5rem;
        }
        .sr-hint.success { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; }
        .sr-hint.warning { background:#fef3c7; border:1px solid #fde68a; color:#92400e; }
        .sr-hint.danger { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; }

        .sr-photo-box {
            position:relative; border:1.5px dashed #cbd5e1; border-radius:12px; padding:1.5rem 1rem;
            text-align:center; cursor:pointer; transition:all .2s; background:#fafbfc; margin-top:0.5rem;
        }
        .sr-photo-box:hover { border-color:#6366f1; background:#eef2ff; }
        .sr-photo-box.has-file { border-style:solid; border-color:#6366f1; background:#eef2ff; padding:0.75rem; }
        .sr-photo-preview { max-width:100%; max-height:200px; border-radius:8px; display:none; }
        .sr-photo-icon { font-size:2rem; margin-bottom:0.5rem; }
        .sr-photo-label { font-size:0.8125rem; font-weight:600; color:#475569; }
        .sr-photo-sub { font-size:0.6875rem; color:#94a3b8; margin-top:0.25rem; }
        .sr-photo-name { font-size:0.75rem; font-weight:600; color:#4f46e5; margin-top:0.375rem; display:none; }

        .sr-submit-bar {
            position:sticky; bottom:0; background:linear-gradient(to top,#fff 70%,transparent);
            padding:1rem 0 0.25rem; display:flex; gap:0.75rem; margin-top:0.5rem; z-index:10;
        }
        .sr-btn {
            display:inline-flex; align-items:center; justify-content:center; gap:0.5rem;
            padding:0.75rem 1.5rem; border-radius:12px; font-size:0.875rem; font-weight:700;
            border:none; cursor:pointer; transition:all .25s; font-family:inherit; text-decoration:none;
        }
        .sr-btn-primary {
            flex:1; background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
            box-shadow:0 4px 16px rgba(79,70,229,0.25);
        }
        .sr-btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(79,70,229,0.35); }
        .sr-btn-primary:active { transform:translateY(0); }
        .sr-btn-cancel {
            background:#fff; color:#64748b; border:1.5px solid #e2e8f0;
        }
        .sr-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }

        @media(max-width:640px){
            .sr-grid { grid-template-columns:1fr; }
            .sr-submit-bar { flex-direction:column; }
            .sr-hdr-title{font-size:1.25rem}.sr-hdr-ico{width:44px;height:44px;font-size:1.25rem}
        }
        @media(max-width:480px){
            .sr{padding:1rem 0.75rem 3rem}
            .sr-hdr-title{font-size:1.125rem}
            .sr-hdr-l{flex-direction:column;align-items:flex-start;gap:0.5rem}
            .sr-hdr{flex-direction:column;align-items:stretch}
            .sr-sec-body{padding:1rem 1.125rem}
        }
    </style>
    @endpush

    <div class="sr">
        <a href="{{ route('minyak.setoran.index') }}" class="sr-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Setoran
        </a>

        <div class="sr-hdr">
            <div class="sr-hdr-l">
                <div class="sr-hdr-ico">💰</div>
                <div>
                    <div class="sr-hdr-title">Tambah Setoran</div>
                    <div class="sr-hdr-sub">Catat setoran penjualan harian sales</div>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div style="margin-bottom:1rem;padding:0.75rem 1rem;border-radius:10px;font-size:0.8125rem;font-weight:600;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;display:flex;align-items:center;gap:0.5rem;">
                ❌ {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('minyak.setoran.store') }}" enctype="multipart/form-data" id="form-setoran">
            @csrf

            {{-- Rekap Penjualan --}}
            @if($isSalesRole && $summary)
            <div class="sr-sec" style="border-color:#a7f3d0;">
                <div class="sr-sec-hdr" style="background:#f0fdf4;">
                    <div class="sr-sec-ico green">📊</div>
                    <div class="sr-sec-title">Rekap Penjualan Hari Ini</div>
                </div>
                <div class="sr-sec-body">
                    <div class="sr-grid">
                        <div class="sr-stat green">
                            <div class="sr-stat-lbl">💵 Penjualan Tunai</div>
                            <div class="sr-stat-val">Rp {{ number_format($summary['total_tunai'], 0, ',', '.') }}</div>
                        </div>
                        <div class="sr-stat blue">
                            <div class="sr-stat-lbl">🏦 Transfer</div>
                            <div class="sr-stat-val">Rp {{ number_format($summary['total_transfer'], 0, ',', '.') }}</div>
                        </div>
                        <div class="sr-stat amber">
                            <div class="sr-stat-lbl">📄 Hutang Baru</div>
                            <div class="sr-stat-val">Rp {{ number_format($summary['total_hutang_baru'], 0, ',', '.') }}</div>
                            <div class="sr-stat-sub">{{ $summary['jumlah_hutang_baru'] }} transaksi</div>
                        </div>
                        <div class="sr-stat slate">
                            <div class="sr-stat-lbl">📋 Total Transaksi</div>
                            <div class="sr-stat-val">{{ $summary['jumlah_transaksi'] }}</div>
                            <div class="sr-stat-sub">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</div>
                        </div>
                    </div>

                    @if($debtPayment > 0)
                    <div style="background:#ecfdf5;border:1px solid #6ee7b7;border-radius:10px;padding:0.75rem 1rem;margin-top:0.75rem;">
                        <div style="font-size:0.6875rem;font-weight:700;color:#065f46;">💰 Cicilan Hutang Tunai Diterima Hari Ini</div>
                        <div style="font-size:1.125rem;font-weight:800;color:#047857;margin-top:0.125rem;">Rp {{ number_format($debtPayment, 0, ',', '.') }}</div>
                    </div>
                    @endif

                    <div class="sr-sum-box" style="margin-top:0.75rem;">
                        <div class="sr-sum-lbl">⚠️ Yang Harus Disetor</div>
                        <div class="sr-sum-val" id="sr-expected">Rp {{ number_format($summary['total_tunai'] + $debtPayment, 0, ',', '.') }}</div>
                        <div class="sr-sum-sub">
                            = Tunai (Rp {{ number_format($summary['total_tunai'], 0, ',', '.') }}) + Cicilan Hutang (Rp {{ number_format($debtPayment, 0, ',', '.') }})
                            <br>* Transfer tidak dihitung karena langsung ke rekening perusahaan
                        </div>
                    </div>

                    <div id="sr-selisih-warning" style="display:none;margin-top:0.75rem;padding:0.75rem 1rem;border-radius:10px;font-size:0.75rem;font-weight:600;line-height:1.5;"></div>
                </div>
            </div>
            @endif

            {{-- Informasi Setoran --}}
            <div class="sr-sec">
                <div class="sr-sec-hdr">
                    <div class="sr-sec-ico indigo">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="sr-sec-title">Informasi Setoran</div>
                </div>
                <div class="sr-sec-body">
                    <div class="sr-fld">
                        <label class="sr-lbl">Tanggal Setoran <span class="sr-req">*</span></label>
                        <input type="date" name="tanggal" id="sr-tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="sr-inp" required>
                        @error('tanggal')<div class="sr-err">{{ $message }}</div>@enderror
                    </div>
                    @if(!$isSalesRole)
                    <div class="sr-fld">
                        <label class="sr-lbl">Sales <span class="sr-req">*</span></label>
                        <select name="sales_id" id="sr-sales-id" class="sr-sel" required>
                            <option value="">Pilih Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ old('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                        @error('sales_id')<div class="sr-err">{{ $message }}</div>@enderror
                    </div>
                    @endif
                </div>
            </div>

            {{-- Detail Setoran --}}
            <div class="sr-sec">
                <div class="sr-sec-hdr">
                    <div class="sr-sec-ico amber">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="sr-sec-title">Jumlah Setoran</div>
                </div>
                <div class="sr-sec-body">
                    <div class="sr-fld">
                        <label class="sr-lbl">Total Setor <span class="sr-req">*</span></label>
                        <div class="sr-money">
                            <span class="sr-money-pfx">Rp</span>
                            <input type="text" inputmode="decimal" name="total_setor" id="sr-total-setor" value="{{ old('total_setor') }}" min="0" class="sr-inp" placeholder="0" required data-currency>
                        </div>
                        @error('total_setor')<div class="sr-err">{{ $message }}</div>@enderror
                        <div id="sr-selisih-hint" class="sr-hint" style="display:none;"></div>
                    </div>

                    <div class="sr-fld">
                        <label class="sr-lbl">Catatan</label>
                        <textarea name="catatan_sales" class="sr-ta" placeholder="Catatan tambahan (opsional)...">{{ old('catatan_sales') }}</textarea>
                    </div>

                    <div class="sr-fld">
                        <label class="sr-lbl">Bukti Setoran <span class="sr-req">*</span></label>
                        <input type="file" name="bukti_setor" id="sr-bukti" accept="image/*" capture="environment" style="display:none;" required>
                        <div class="sr-photo-box" id="sr-photo-box" onclick="document.getElementById('sr-bukti').click();">
                            <div id="sr-photo-empty">
                                <div class="sr-photo-icon">📷</div>
                                <div class="sr-photo-label">Foto / Pilih Bukti Setoran</div>
                                <div class="sr-photo-sub">Ambil foto atau pilih dari galeri (maks 4MB)</div>
                            </div>
                            <img id="sr-photo-preview" class="sr-photo-preview" src="" alt="Preview">
                            <div class="sr-photo-name" id="sr-photo-name"></div>
                        </div>
                        @error('bukti_setor')<div class="sr-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="sr-hint" style="background:#f8fafc;border:1px solid #e2e8f0;color:#64748b;margin-top:0.75rem;">
                        <strong>Catatan:</strong> Total penjualan, jumlah transaksi, dan data hutang akan dihitung otomatis berdasarkan penjualan pada tanggal yang dipilih. Transfer tidak termasuk setoran fisik.
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="sr-submit-bar">
                <a href="{{ route('minyak.setoran.index') }}" class="sr-btn sr-btn-cancel">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </a>
                <button type="submit" class="sr-btn sr-btn-primary" id="sr-submit-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Setoran
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('form-setoran');
        var inpBukti = document.getElementById('sr-bukti');
        var preview = document.getElementById('sr-photo-preview');
        var photoName = document.getElementById('sr-photo-name');
        var photoBox = document.getElementById('sr-photo-box');
        var photoEmpty = document.getElementById('sr-photo-empty');
        var inpTotal = document.getElementById('sr-total-setor');
        var selisihHint = document.getElementById('sr-selisih-hint');
        var expectedEl = document.getElementById('sr-expected');

        // Submit loading
        form.addEventListener('submit', function() {
            var btn = document.getElementById('sr-submit-btn');
            btn.style.opacity = '0.7';
            btn.style.cursor = 'wait';
        });

        // Photo upload preview
        inpBukti.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(ev) {
                preview.src = ev.target.result;
                preview.style.display = 'block';
                photoEmpty.style.display = 'none';
                photoName.textContent = '✅ ' + file.name;
                photoName.style.display = 'block';
                photoBox.classList.add('has-file');
            };
            reader.readAsDataURL(file);
        });

        // Selisih calculator
        function parseRp(str) {
            if (!str) return 0;
            var cleaned = str.replace(/[^0-9]/g, '');
            return parseInt(cleaned) || 0;
        }

        function hitungSelisih() {
            var totalSetor = parseRp(inpTotal.value) || 0;
            if (!expectedEl) return;
            var expected = parseRp(expectedEl.textContent);

            if (totalSetor <= 0 && expected <= 0) {
                selisihHint.style.display = 'none';
                return;
            }

            if (expected <= 0) {
                selisihHint.style.display = 'none';
                return;
            }

            var selisih = totalSetor - expected;

            if (Math.abs(selisih) < 0.01) {
                selisihHint.className = 'sr-hint success';
                selisihHint.innerHTML = '✅ Setoran <strong>pas</strong> dengan yang seharusnya (Rp ' + Number(expected).toLocaleString('id-ID') + ').';
                selisihHint.style.display = 'block';
            } else if (selisih > 0) {
                selisihHint.className = 'sr-hint warning';
                selisihHint.innerHTML = '⚠️ Setoran <strong>kelebihan</strong> Rp ' + Number(selisih).toLocaleString('id-ID') + ' dari yang seharusnya (Rp ' + Number(expected).toLocaleString('id-ID') + ').';
                selisihHint.style.display = 'block';
            } else {
                selisihHint.className = 'sr-hint danger';
                selisihHint.innerHTML = '❌ Setoran <strong>kurang</strong> Rp ' + Number(Math.abs(selisih)).toLocaleString('id-ID') + ' dari yang seharusnya (Rp ' + Number(expected).toLocaleString('id-ID') + '). Pastikan jumlah setoran sudah benar.';
                selisihHint.style.display = 'block';
            }
        }

        inpTotal.addEventListener('input', hitungSelisih);
        hitungSelisih();

        // Date change note
        var inpTanggal = document.getElementById('sr-tanggal');
        if (inpTanggal && expectedEl) {
            var originalDate = inpTanggal.value;
            inpTanggal.addEventListener('change', function() {
                if (this.value !== originalDate) {
                    // Show a note that summary may not match
                    expectedEl.closest('.sr-sum-box').querySelector('.sr-sum-sub').innerHTML +=
                        '<br><span style="color:#dc2626;font-weight:700;">⚠️ Tanggal diubah, rekap di atas untuk tanggal sebelumnya.</span>';
                }
            });
        }
    });
    </script>
    @endpush
</x-app-layout>