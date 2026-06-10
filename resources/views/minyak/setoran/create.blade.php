<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .st-page { max-width:42rem; margin:0 auto; padding:1.25rem 1rem 4rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .st-back { display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#94a3b8; text-decoration:none; margin-bottom:1rem; transition:all .2s; }
        .st-back:hover { color:#334155; }
        .st-back:hover svg { transform:translateX(-3px); }
        .st-back svg { transition:transform .2s; }
        .st-hdr { margin-bottom:1.5rem; }
        .st-tag { display:inline-block; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#6366f1; background:#eef2ff; padding:3px 10px; border-radius:20px; margin-bottom:6px; }
        .st-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-.03em; line-height:1.2; }
        .st-sub { font-size:13px; color:#64748b; margin-top:2px; }
        .st-sec { background:#fff; border:1px solid #f1f5f9; border-radius:14px; margin-bottom:14px; box-shadow:0 1px 2px rgba(0,0,0,.03), 0 4px 12px rgba(0,0,0,.02); transition:box-shadow .2s; }
        .st-sec:hover { box-shadow:0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04); }
        .st-sec-hdr { padding:.875rem 1.25rem; display:flex; align-items:center; gap:10px; border-bottom:1px solid #f8fafc; }
        .st-sec-ico { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
        .st-sec-ico.indigo { background:#eef2ff; color:#4f46e5; }
        .st-sec-ico.green { background:#ecfdf5; color:#059669; }
        .st-sec-title { font-size:14px; font-weight:700; color:#0f172a; }
        .st-sec-body { padding:1.125rem 1.25rem; }
        .st-fld { margin-bottom:12px; }
        .st-fld:last-child { margin-bottom:0; }
        .st-lbl { display:flex; align-items:center; gap:4px; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px; }
        .st-req { color:#ef4444; }
        .st-inp { width:100%; padding:10px 14px; border-radius:10px; border:1.5px solid #e2e8f0; background:#fafbfc; font-size:14px; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; }
        .st-inp:focus { border-color:#6366f1; background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,.08); }
        .st-sel { width:100%; padding:10px 36px 10px 14px; border-radius:10px; border:1.5px solid #e2e8f0; background:#fafbfc; font-size:14px; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; background-size:14px; }
        .st-sel:focus { border-color:#6366f1; background-color:#fff; box-shadow:0 0 0 3px rgba(99,102,241,.08); }
        .st-ta { width:100%; padding:10px 14px; border-radius:10px; border:1.5px solid #e2e8f0; background:#fafbfc; font-size:14px; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; resize:vertical; min-height:70px; }
        .st-ta:focus { border-color:#6366f1; background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,.08); }
        .st-money { position:relative; }
        .st-money-pfx { position:absolute; left:14px; top:50%; transform:translateY(-50%); font-size:12px; font-weight:700; color:#94a3b8; pointer-events:none; }
        .st-money .st-inp { padding-left:36px; }
        .st-info-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 14px; font-size:12px; color:#64748b; line-height:1.6; }
        .st-info-box strong { color:#475569; }
        .st-submit-bar { position:sticky; bottom:0; background:linear-gradient(to top,#fff 60%,transparent); padding:16px 0 4px; display:flex; gap:10px; margin-top:8px; z-index:10; }
        .st-btn-cancel { display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:12px 20px; border-radius:12px; font-size:14px; font-weight:600; border:1.5px solid #e2e8f0; cursor:pointer; transition:all .2s; font-family:inherit; background:#fff; color:#64748b; text-decoration:none; white-space:nowrap; }
        .st-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }
        .st-btn-submit { flex:1; display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:12px 24px; border-radius:12px; font-size:14px; font-weight:700; border:none; cursor:pointer; transition:all .25s; font-family:inherit; background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; box-shadow:0 4px 16px rgba(79,70,229,.25); }
        .st-btn-submit:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(79,70,229,.35); }
        .st-btn-submit:active { transform:translateY(0); }
        .st-err { font-size:11px; color:#ef4444; margin-top:4px; font-weight:500; }
        @media(max-width:640px) { .st-submit-bar { flex-direction:column; } }
    </style>
    @endpush

    <div class="st-page">
        <a href="{{ route('minyak.setoran.index') }}" class="st-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Setoran
        </a>

        <div class="st-hdr">
            <div class="st-tag">Setoran Harian</div>
            <div class="st-title">Tambah Setoran</div>
            <div class="st-sub">Catat setoran penjualan harian sales</div>
        </div>

        <form method="POST" action="{{ route('minyak.setoran.store') }}" enctype="multipart/form-data" id="form-setoran">
            @csrf

            {{-- Rekap Penjualan Hari Ini (Sales only) --}}
            @if($isSalesRole && $todaySummary)
            <div class="st-sec" style="border-color:#a7f3d0;">
                <div class="st-sec-hdr" style="background:#f0fdf4;">
                    <div class="st-sec-ico green">📊</div>
                    <div class="st-sec-title">Rekap Penjualan Hari Ini</div>
                </div>
                <div class="st-sec-body">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
                        <div style="background:#f0fdf4; padding:12px; border-radius:10px; border:1px solid #a7f3d0;">
                            <div style="font-size:10px; font-weight:700; color:#065f46; text-transform:uppercase; letter-spacing:.05em;">💵 Penjualan Tunai</div>
                            <div style="font-size:16px; font-weight:800; color:#047857; margin-top:4px;">Rp {{ number_format($todaySummary['total_tunai'], 0, ',', '.') }}</div>
                        </div>
                        <div style="background:#eff6ff; padding:12px; border-radius:10px; border:1px solid #93c5fd;">
                            <div style="font-size:10px; font-weight:700; color:#1e40af; text-transform:uppercase; letter-spacing:.05em;">🏦 Transfer</div>
                            <div style="font-size:16px; font-weight:800; color:#2563eb; margin-top:4px;">Rp {{ number_format($todaySummary['total_transfer'], 0, ',', '.') }}</div>
                        </div>
                        <div style="background:#fff7ed; padding:12px; border-radius:10px; border:1px solid #fdba74;">
                            <div style="font-size:10px; font-weight:700; color:#9a3412; text-transform:uppercase; letter-spacing:.05em;">📄 Hutang Baru</div>
                            <div style="font-size:16px; font-weight:800; color:#c2410c; margin-top:4px;">Rp {{ number_format($todaySummary['total_hutang_baru'], 0, ',', '.') }}</div>
                            <div style="font-size:10px; color:#9a3412; margin-top:2px;">{{ $todaySummary['jumlah_hutang_baru'] }} transaksi</div>
                        </div>
                        <div style="background:#f8fafc; padding:12px; border-radius:10px; border:1px solid #e2e8f0;">
                            <div style="font-size:10px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.05em;">📊 Total Transaksi</div>
                            <div style="font-size:16px; font-weight:800; color:#334155; margin-top:4px;">{{ $todaySummary['jumlah_transaksi'] }}</div>
                            <div style="font-size:10px; color:#64748b; margin-top:2px;">Rp {{ number_format($todaySummary['total_penjualan'], 0, ',', '.') }}</div>
                        </div>
                    </div>

                    @if($todayDebtPayment > 0)
                    <div style="background:#ecfdf5; padding:10px 12px; border-radius:10px; border:1px solid #6ee7b7; margin-bottom:12px;">
                        <div style="font-size:11px; font-weight:700; color:#065f46;">💰 Cicilan Hutang Tunai Diterima Hari Ini</div>
                        <div style="font-size:15px; font-weight:800; color:#047857; margin-top:2px;">Rp {{ number_format($todayDebtPayment, 0, ',', '.') }}</div>
                    </div>
                    @endif

                    <div style="background:#fefce8; padding:12px; border-radius:10px; border:1px solid #fde68a;">
                        <div style="font-size:11px; font-weight:700; color:#854d0e; text-transform:uppercase; letter-spacing:.05em;">⚠️ Yang Harus Disetor</div>
                        <div style="font-size:18px; font-weight:800; color:#92400e; margin-top:4px;">Rp {{ number_format($todaySummary['total_tunai'] + $todayDebtPayment, 0, ',', '.') }}</div>
                        <div style="font-size:10px; color:#92400e; margin-top:4px;">= Tunai (Rp {{ number_format($todaySummary['total_tunai'], 0, ',', '.') }}) + Cicilan Hutang (Rp {{ number_format($todayDebtPayment, 0, ',', '.') }})</div>
                        <div style="font-size:10px; color:#a16207; margin-top:4px;">* Transfer tidak dihitung karena langsung ke rekening perusahaan</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Informasi Setoran --}}
            <div class="st-sec">
                <div class="st-sec-hdr">
                    <div class="st-sec-ico indigo">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="st-sec-title">Informasi Setoran</div>
                </div>
                <div class="st-sec-body">
                    <div class="st-fld">
                        <label class="st-lbl">Tanggal Setoran <span class="st-req">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="st-inp" required>
                        @error('tanggal')<div class="st-err">{{ $message }}</div>@enderror
                    </div>
                    @if(! $isSalesRole)
                    <div class="st-fld">
                        <label class="st-lbl">Sales <span class="st-req">*</span></label>
                        <select name="sales_id" class="st-sel" required>
                            <option value="">Pilih Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ old('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                        @error('sales_id')<div class="st-err">{{ $message }}</div>@enderror
                    </div>
                    @endif
                </div>
            </div>

            {{-- Detail Setoran --}}
            <div class="st-sec">
                <div class="st-sec-hdr">
                    <div class="st-sec-ico green">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="st-sec-title">Jumlah Setoran</div>
                </div>
                <div class="st-sec-body">
                    <div class="st-fld">
                        <label class="st-lbl">Total Setor <span class="st-req">*</span></label>
                        <div class="st-money">
                            <span class="st-money-pfx">Rp</span>
                            <input type="number" name="total_setor" value="{{ old('total_setor') }}" min="0" step="100" class="st-inp" placeholder="0" required>
                        </div>
                        @error('total_setor')<div class="st-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="st-fld">
                        <label class="st-lbl">Catatan</label>
                        <textarea name="catatan_sales" class="st-ta" placeholder="Catatan tambahan (opsional)...">{{ old('catatan_sales') }}</textarea>
                    </div>

                    <div class="st-fld">
                        <label class="st-lbl">Bukti Setoran <span class="st-req">*Wajib foto</span></label>
                        <input type="file" name="bukti_setor" id="inp-bukti-setor" accept="image/*" capture="environment" style="display:none;" required>
                        <div id="bukti-preview-wrap" style="margin-top:8px; display:none;">
                            <img id="bukti-preview" src="" alt="Bukti Setor" style="max-width:100%; max-height:180px; border-radius:10px; border:1.5px solid #6366f1;">
                        </div>
                        <button type="button" id="btn-pilih-bukti" style="width:100%; padding:10px; border:1.5px dashed #94a3b8; border-radius:10px; background:#f8fafc; color:#475569; font-weight:600; font-size:13px; cursor:pointer; margin-top:8px; transition:all 0.2s;">
                            📷 Foto / Pilih Bukti Setoran
                        </button>
                        @error('bukti_setor')<div class="st-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="st-info-box">
                        <strong>Catatan:</strong> Total penjualan, jumlah transaksi, dan data hutang akan dihitung otomatis berdasarkan penjualan pada tanggal yang dipilih. Transfer tidak termasuk setoran fisik.
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="st-submit-bar">
                <a href="{{ route('minyak.setoran.index') }}" class="st-btn-cancel">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </a>
                <button type="submit" class="st-btn-submit">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Setoran
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('form-setoran').addEventListener('submit', function() {
            var btn = this.querySelector('.st-btn-submit');
            btn.style.opacity = '0.7';
            btn.style.cursor = 'wait';
        });

        // Bukti setor upload
        var btnPilih = document.getElementById('btn-pilih-bukti');
        var inpBukti = document.getElementById('inp-bukti-setor');
        var buktiPreview = document.getElementById('bukti-preview');
        var buktiPreviewWrap = document.getElementById('bukti-preview-wrap');

        btnPilih.addEventListener('click', function() {
            inpBukti.click();
        });

        inpBukti.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(ev) {
                buktiPreview.src = ev.target.result;
                buktiPreviewWrap.style.display = 'block';
                btnPilih.textContent = '✅ ' + file.name;
                btnPilih.style.borderColor = '#6366f1';
                btnPilih.style.background = '#eef2ff';
                btnPilih.style.color = '#4f46e5';
            };
            reader.readAsDataURL(file);
        });
    });
    </script>
    @endpush
</x-app-layout>
