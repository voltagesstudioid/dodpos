<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .sc-page { max-width:44rem; margin:0 auto; padding:1.5rem 1rem 4rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .sc-back { display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#94a3b8; text-decoration:none; margin-bottom:1rem; transition:all .2s; }
        .sc-back:hover { color:#334155; }
        .sc-hdr { margin-bottom:1.5rem; }
        .sc-hdr-tag { display:inline-block; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#10b981; background:#ecfdf5; padding:3px 10px; border-radius:20px; margin-bottom:6px; }
        .sc-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-.03em; line-height:1.2; }
        .sc-hdr-sub { font-size:13px; color:#64748b; margin-top:2px; }
        .sc-sec { background:#fff; border:1px solid #f1f5f9; border-radius:14px; margin-bottom:14px; box-shadow:0 1px 2px rgba(0,0,0,.03); }
        .sc-sec-hdr { padding:.875rem 1.25rem; display:flex; align-items:center; gap:10px; border-bottom:1px solid #f8fafc; }
        .sc-sec-ico { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
        .sc-sec-ico.emerald { background:#ecfdf5; color:#059669; }
        .sc-sec-ico.sky { background:#f0f9ff; color:#0284c7; }
        .sc-sec-title { font-size:14px; font-weight:700; color:#0f172a; }
        .sc-sec-body { padding:1.125rem 1.25rem; }
        .sc-fld { margin-bottom:12px; } .sc-fld:last-child { margin-bottom:0; }
        .sc-lbl { display:flex; align-items:center; gap:4px; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px; }
        .sc-req { color:#ef4444; }
        .sc-inp { width:100%; padding:10px 14px; border-radius:10px; border:1.5px solid #e2e8f0; background:#fafbfc; font-size:14px; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; }
        .sc-inp:focus { border-color:#10b981; background:#fff; box-shadow:0 0 0 3px rgba(16,185,129,.08); }
        .sc-inp::placeholder { color:#94a3b8; }
        .sc-sel { width:100%; padding:10px 36px 10px 14px; border-radius:10px; border:1.5px solid #e2e8f0; background:#fafbfc; font-size:14px; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; background-size:14px; }
        .sc-sel:focus { border-color:#10b981; background-color:#fff; box-shadow:0 0 0 3px rgba(16,185,129,.08); }
        .sc-ta { width:100%; padding:10px 14px; border-radius:10px; border:1.5px solid #e2e8f0; background:#fafbfc; font-size:14px; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; resize:vertical; min-height:60px; }
        .sc-ta:focus { border-color:#10b981; background:#fff; box-shadow:0 0 0 3px rgba(16,185,129,.08); }
        .sc-radio-grp { display:flex; gap:8px; }
        .sc-radio-pill { position:relative; flex:1; cursor:pointer; }
        .sc-radio-pill input { position:absolute; opacity:0; width:0; height:0; }
        .sc-radio-face { display:flex; flex-direction:column; align-items:center; gap:4px; padding:14px 8px; border-radius:10px; border:2px solid #e2e8f0; background:#fafbfc; transition:all .2s; text-align:center; }
        .sc-radio-face .ico { font-size:22px; line-height:1; }
        .sc-radio-face .lbl { font-size:12px; font-weight:700; color:#64748b; }
        .sc-radio-face .sub { font-size:10px; color:#94a3b8; margin-top:2px; }
        .sc-radio-pill input:checked + .sc-radio-face { border-color:#10b981; background:linear-gradient(180deg,#ecfdf5,#f0fdf4); box-shadow:0 0 0 3px rgba(16,185,129,.1); }
        .sc-radio-pill input:checked + .sc-radio-face .lbl { color:#065f46; }
        .sc-radio-pill:hover .sc-radio-face { border-color:#a7f3d0; }
        .sc-info { background:#f0f9ff; border:1px solid #bae6fd; border-radius:10px; padding:12px 14px; display:flex; gap:10px; align-items:flex-start; }
        .sc-info-ico { width:20px; height:20px; flex-shrink:0; color:#0284c7; }
        .sc-info-txt { font-size:12px; color:#0c4a6e; line-height:1.6; }
        .sc-info-txt strong { font-weight:700; }
        .sc-stok-box { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:12px 14px; margin-top:10px; }
        .sc-stok-lbl { font-size:11px; font-weight:700; color:#065f46; text-transform:uppercase; letter-spacing:.06em; }
        .sc-stok-val { font-size:1.5rem; font-weight:800; color:#059669; font-family:'JetBrains Mono',monospace; }
        .sc-submit-bar { position:sticky; bottom:0; background:linear-gradient(to top,#fff 60%,transparent); padding:16px 0 4px; display:flex; gap:10px; margin-top:8px; z-index:10; }
        .sc-btn-cancel { display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:12px 20px; border-radius:12px; font-size:14px; font-weight:600; border:1.5px solid #e2e8f0; cursor:pointer; transition:all .2s; font-family:inherit; background:#fff; color:#64748b; text-decoration:none; }
        .sc-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }
        .sc-btn-submit { flex:1; display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:12px 24px; border-radius:12px; font-size:14px; font-weight:700; border:none; cursor:pointer; transition:all .25s; font-family:inherit; background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 4px 16px rgba(5,150,105,.25); }
        .sc-btn-submit:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(5,150,105,.35); }
        .sc-err { font-size:11px; color:#ef4444; margin-top:4px; font-weight:500; }
        @media(max-width:640px) { .sc-radio-grp { flex-direction:column; } .sc-submit-bar { flex-direction:column; } }
    </style>
    @endpush
    <div class="sc-page">
        <a href="{{ route('minyak.stok-masuk.index') }}" class="sc-back"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Kembali</a>
        <div class="sc-hdr"><div class="sc-hdr-tag">Stok Gudang</div><div class="sc-hdr-title">Penerimaan / Koreksi Stok</div><div class="sc-hdr-sub">Tambah stok gudang atau koreksi stok fisik</div></div>
        @if(session('error'))<div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:10px 14px;border-radius:10px;font-size:13px;margin-bottom:14px;">{{ session('error') }}</div>@endif
        <form method="POST" action="{{ route('minyak.stok-masuk.store') }}" id="form-stok">@csrf
            <div class="sc-sec"><div class="sc-sec-hdr"><div class="sc-sec-ico emerald"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div><div class="sc-sec-title">Tipe Transaksi</div></div>
                <div class="sc-sec-body"><div class="sc-fld"><div class="sc-radio-grp">
                    <label class="sc-radio-pill"><input type="radio" name="tipe" value="penerimaan" {{ old('tipe', 'penerimaan') === 'penerimaan' ? 'checked' : '' }}><div class="sc-radio-face"><span class="ico">📥</span><span class="lbl">Penerimaan</span><span class="sub">Beli dari supplier / produksi</span></div></label>
                    <label class="sc-radio-pill"><input type="radio" name="tipe" value="koreksi" {{ old('tipe') === 'koreksi' ? 'checked' : '' }}><div class="sc-radio-face"><span class="ico">🔧</span><span class="lbl">Koreksi Stok</span><span class="sub">Sesuaikan dengan stok fisik</span></div></label>
                </div>@error('tipe')<div class="sc-err">{{ $message }}</div>@enderror</div>
                    <div class="sc-info" id="info-penerimaan"><svg class="sc-info-ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><div class="sc-info-txt"><strong>Penerimaan</strong> — Stok gudang akan <strong>bertambah</strong> sesuai jumlah yang diinput.</div></div>
                    <div class="sc-info" id="info-koreksi" style="display:none;"><svg class="sc-info-ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><div class="sc-info-txt"><strong>Koreksi</strong> — Masukkan jumlah <strong>stok aktual/fisik</strong>. Sistem menghitung selisih otomatis.</div></div>
                </div></div>
            <div class="sc-sec"><div class="sc-sec-hdr"><div class="sc-sec-ico sky"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div><div class="sc-sec-title">Detail Stok</div></div>
                <div class="sc-sec-body">
                    <div class="sc-fld"><label class="sc-lbl">Produk <span class="sc-req">*</span></label>
                        <select name="produk_id" class="sc-sel" id="sel-produk" required><option value="">Pilih Produk</option>@foreach($produks as $p)<option value="{{ $p->id }}" data-stok="{{ $p->stok_gudang }}" data-satuan="{{ $p->satuan }}" {{ old('produk_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }} (Stok: {{ number_format($p->stok_gudang, 0, ',', '.') }} {{ $p->satuan }})</option>@endforeach</select>@error('produk_id')<div class="sc-err">{{ $message }}</div>@enderror</div>
                    <div class="sc-stok-box" id="stok-box" style="display:none;"><span class="sc-stok-lbl">Stok Gudang Saat Ini</span><div class="sc-stok-val" id="stok-val">0</div></div>
                    <div class="sc-fld" style="margin-top:12px;"><label class="sc-lbl" id="lbl-jumlah">Jumlah Diterima <span class="sc-req">*</span></label><input type="number" name="jumlah" id="inp-jumlah" value="{{ old('jumlah') }}" min="0.01" step="0.01" class="sc-inp" required placeholder="Masukkan jumlah...">@error('jumlah')<div class="sc-err">{{ $message }}</div>@enderror</div>
                    <div class="sc-fld"><label class="sc-lbl">Sumber / Supplier</label><input type="text" name="sumber" value="{{ old('sumber') }}" class="sc-inp" placeholder="Contoh: PT. Sawit Jaya">@error('sumber')<div class="sc-err">{{ $message }}</div>@enderror</div>
                    <div class="sc-fld"><label class="sc-lbl">Keterangan</label><textarea name="keterangan" class="sc-ta" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>@error('keterangan')<div class="sc-err">{{ $message }}</div>@enderror</div>
                </div></div>
            <div class="sc-submit-bar"><a href="{{ route('minyak.stok-masuk.index') }}" class="sc-btn-cancel">Batal</a><button type="submit" class="sc-btn-submit"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan</button></div>
        </form></div>
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var sel = document.getElementById('sel-produk'), sBox = document.getElementById('stok-box'), sVal = document.getElementById('stok-val');
        var lbl = document.getElementById('lbl-jumlah'), iP = document.getElementById('info-penerimaan'), iK = document.getElementById('info-koreksi'), inp = document.getElementById('inp-jumlah');
        function fmt(n) { return Number(n).toLocaleString('id-ID'); }
        function updTipe() {
            var t = document.querySelector('input[name="tipe"]:checked').value;
            if (t==='penerimaan') { iP.style.display='flex'; iK.style.display='none'; lbl.innerHTML='Jumlah Diterima <span class="sc-req">*</span>'; inp.placeholder='Berapa liter yang diterima...'; }
            else { iP.style.display='none'; iK.style.display='flex'; lbl.innerHTML='Stok Aktual (Fisik) <span class="sc-req">*</span>'; inp.placeholder='Berapa liter stok fisik saat ini...'; }
        }
        sel.addEventListener('change', function() { var o=this.options[this.selectedIndex]; if(o&&o.value){sBox.style.display='block';sVal.textContent=fmt(parseInt(o.dataset.stok)||0)+' '+(o.dataset.satuan||'Liter');}else{sBox.style.display='none';} });
        document.querySelectorAll('input[name="tipe"]').forEach(function(r){r.addEventListener('change',updTipe);});
        updTipe();
        if(sel.value){var o=sel.options[sel.selectedIndex];sBox.style.display='block';sVal.textContent=fmt(parseInt(o.dataset.stok)||0)+' '+(o.dataset.satuan||'Liter');}
        document.getElementById('form-stok').addEventListener('submit',function(){var b=this.querySelector('.sc-btn-submit');b.style.opacity='0.7';b.style.cursor='wait';});
    });
    </script>
    @endpush
</x-app-layout>
