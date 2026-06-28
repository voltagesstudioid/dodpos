<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        .pr-page { 
            max-width: 56rem; 
            margin: 0 auto; 
            padding: 2rem 1.5rem 5rem; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #0f172a;
        }

        /* Navigation */
        .pr-nav { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            margin-bottom: 2rem; 
        }
        .pr-nav-btn { 
            width: 42px; 
            height: 42px; 
            border-radius: 14px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: #ffffff; 
            border: 1px solid #e2e8f0; 
            color: #64748b; 
            text-decoration: none; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        .pr-nav-btn:hover { 
            background: #f8fafc; 
            border-color: #cbd5e1; 
            color: #2563eb; 
            transform: translateX(-4px); 
            box-shadow: 0 4px 12px rgba(37,99,235,0.1);
        }
        .pr-nav-path { 
            font-size: 0.875rem; 
            font-weight: 600; 
            color: #64748b; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
        }
        .pr-nav-path span { color: #0f172a; font-weight: 700; }

        /* Header */
        .pr-header {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 2.5rem;
            position: relative;
        }
        .pr-header-icon {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 10px 25px rgba(37,99,235,0.25);
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }
        .pr-header-icon::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
            border-radius: 20px;
        }
        .pr-header-title {
            font-size: 1.875rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 1.2;
            margin-bottom: 4px;
            background: linear-gradient(90deg, #0f172a, #334155);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .pr-header-desc {
            font-size: 0.9375rem;
            color: #64748b;
            font-weight: 500;
        }

        /* Card Base */
        .pr-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03), inset 0 0 0 1px rgba(255,255,255,0.5);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .pr-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02), inset 0 0 0 1px rgba(255,255,255,0.6);
        }
        
        .pr-card-header {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            background: rgba(255,255,255,0.5);
        }
        .pr-card-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .pr-card-icon.blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .pr-card-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
        .pr-card-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        
        .pr-card-title {
            font-size: 1.0625rem;
            font-weight: 700;
            color: #1e293b;
        }
        .pr-card-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }
        .pr-card-body {
            padding: 1.5rem;
        }

        /* Form Layout */
        .pr-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        .pr-col-full { grid-column: 1 / -1; }
        
        @media(max-width: 640px) {
            .pr-grid { grid-template-columns: 1fr; }
        }

        /* Inputs */
        .pr-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            margin-bottom: 0.625rem;
        }
        .pr-req { color: #ef4444; }
        .pr-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            font-size: 0.9375rem;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }
        .pr-input:focus {
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.15);
        }
        select.pr-input {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
            padding-right: 2.75rem;
        }
        .pr-textarea {
            width: 100%;
            padding: 1rem;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            font-size: 0.9375rem;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            transition: all 0.25s;
            font-family: inherit;
            resize: vertical;
            min-height: 5rem;
        }
        .pr-textarea:focus {
            background: #ffffff;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139,92,246,0.15);
        }
        .pr-error-text {
            font-size: 0.75rem;
            color: #ef4444;
            font-weight: 600;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Stock Info */
        .pr-stock-alert {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 1rem;
            animation: fadeIn 0.3s ease;
        }
        .pr-stock-alert.available { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .pr-stock-alert.empty { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .pr-stock-alert.loading { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Dynamic Rows */
        .pr-row-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .pr-row {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.25rem;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .pr-row:hover {
            border-color: #cbd5e1;
            box-shadow: 0 8px 16px rgba(0,0,0,0.04);
            transform: translateY(-2px);
        }
        .pr-row-number {
            position: absolute;
            top: -10px;
            left: 16px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            font-size: 0.6875rem;
            font-weight: 800;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(16,185,129,0.3);
            border: 2px solid #ffffff;
        }
        .pr-row-grid {
            display: grid;
            grid-template-columns: 1fr 180px 48px;
            gap: 1rem;
            align-items: end;
        }
        @media(max-width: 640px) {
            .pr-row-grid { grid-template-columns: 1fr; }
        }
        .pr-btn-remove {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            border: 1.5px solid #fecaca;
            background: #fef2f2;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .pr-btn-remove:hover {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(239,68,68,0.25);
        }

        .pr-btn-add {
            width: 100%;
            padding: 1rem;
            border-radius: 16px;
            border: 2px dashed #93c5fd;
            background: #eff6ff;
            color: #2563eb;
            font-size: 0.9375rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-family: inherit;
            margin-top: 1rem;
        }
        .pr-btn-add:hover {
            background: #dbeafe;
            border-color: #3b82f6;
            transform: translateY(-2px);
        }

        /* Actions */
        .pr-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .pr-btn {
            padding: 1.125rem 2rem;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            text-decoration: none;
        }
        .pr-btn-cancel {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #64748b;
        }
        .pr-btn-cancel:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
        }
        .pr-btn-submit {
            flex: 1;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            box-shadow: 0 10px 25px rgba(37,99,235,0.3);
            position: relative;
            overflow: hidden;
        }
        .pr-btn-submit::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
            border-radius: 16px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .pr-btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(37,99,235,0.4);
        }
        .pr-btn-submit:hover::after { opacity: 1; }

        @media(max-width: 640px) {
            .pr-actions { flex-direction: column-reverse; }
        }
    </style>
    @endpush

    <div class="pr-page">
        <nav class="pr-nav">
            <a href="{{ route('mineral.loading.index') }}" class="pr-nav-btn">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <div class="pr-nav-path">
                Penugasan Kendaraan / <span>Tambah Baru</span>
            </div>
        </nav>

        <div class="pr-header">
            <div class="pr-header-icon">
                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
            </div>
            <div>
                <h1 class="pr-header-title">Permintaan Stok</h1>
                <div class="pr-header-desc">Buat penugasan kendaraan baru untuk distribusi stok.</div>
            </div>
        </div>

        @if(session('error'))
        <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:16px;border-radius:16px;font-size:0.9375rem;margin-bottom:24px;font-weight:600;display:flex;align-items:center;gap:12px;box-shadow:0 4px 12px rgba(220,38,38,0.1);">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('mineral.loading.store') }}" id="form-penugasan">
            @csrf

            <!-- Card 1: Setup -->
            <div class="pr-card">
                <div class="pr-card-header">
                    <div class="pr-card-icon blue">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div>
                        <div class="pr-card-title">Pengaturan Permintaan</div>
                        <div class="pr-card-subtitle">Atur tanggal, produk, dan mobil inti</div>
                    </div>
                </div>
                <div class="pr-card-body">
                    <div class="pr-grid">
                        <div>
                            <label class="pr-label">Tanggal <span class="pr-req">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="pr-input" required>
                            @error('tanggal')<div class="pr-error-text">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="pr-label">Produk <span class="pr-req">*</span></label>
                            <select name="produk_id" id="sel-produk" class="pr-input" required>
                                <option value="">Pilih Produk Mineral</option>
                                @foreach($produks as $p)
                                    <option value="{{ $p->id }}" {{ old('produk_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }} ({{ $p->satuan }})</option>
                                @endforeach
                            </select>
                            @error('produk_id')<div class="pr-error-text">{{ $message }}</div>@enderror
                        </div>
                        <div class="pr-col-full">
                            <label class="pr-label">Mobil Inti (Sumber Stok) <span class="pr-req">*</span></label>
                            <select name="vehicle_inti_id" id="sel-mobil-inti" class="pr-input" required>
                                <option value="">Pilih Kendaraan Inti</option>
                                @foreach($vehiclesInti as $mi)
                                    @php $platInti = $mi->license_plate ?? '-'; $driverInti = $mi->currentAssignment?->sales?->nama; @endphp
                                    <option value="{{ $mi->id }}" data-vehicle-id="{{ $mi->id }}" {{ old('vehicle_inti_id') == $mi->id ? 'selected' : '' }}>{{ $driverInti ? $driverInti.' ('.$platInti.')' : $platInti }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_inti_id')<div class="pr-error-text">{{ $message }}</div>@enderror
                            <div id="stock-info" class="pr-stock-alert" style="display:none;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Sales List -->
            <div class="pr-card">
                <div class="pr-card-header">
                    <div class="pr-card-icon green">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div>
                        <div class="pr-card-title">Daftar Pemohon</div>
                        <div class="pr-card-subtitle">Pilih sales yang meminta stok dari mobil inti ini</div>
                    </div>
                </div>
                <div class="pr-card-body">
                    <div id="sales-list" class="pr-row-container">
                        @if(old('items'))
                            @foreach(old('items') as $i => $item)
                            <div class="pr-row" id="row-{{ $i + 1 }}">
                                <div class="pr-row-number">{{ $i + 1 }}</div>
                                <div class="pr-row-grid">
                                    <div>
                                        <label class="pr-label">Sales Sub <span class="pr-req">*</span></label>
                                        <select name="items[{{ $i + 1 }}][sales_id]" class="pr-input" required>
                                            <option value="">Pilih Sales</option>
                                            @foreach($salesSub as $s)
                                                @php $platSub = $s->no_kendaraan ?: ($s->currentAssignment?->vehicle?->license_plate ?? ''); @endphp
                                                <option value="{{ $s->id }}" {{ ($item['sales_id'] ?? '') == $s->id ? 'selected' : '' }}>{{ $s->nama }} {{ $platSub ? '('.$platSub.')' : '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="pr-label">Volume</label>
                                        <input type="number" name="items[{{ $i + 1 }}][jumlah]" value="{{ $item['jumlah'] ?? '' }}" class="pr-input" min="0.01" step="any" placeholder="0">
                                    </div>
                                    <button type="button" class="pr-btn-remove" onclick="hapusBaris({{ $i + 1 }})" title="Hapus"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="pr-btn-add" id="btn-tambah-sales" onclick="tambahBaris()">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambahkan Sales
                    </button>
                    @error('items')<div class="pr-error-text">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Card 3: Notes -->
            <div class="pr-card">
                <div class="pr-card-header">
                    <div class="pr-card-icon purple">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <div>
                        <div class="pr-card-title">Keterangan Tambahan</div>
                        <div class="pr-card-subtitle">Catatan operasional (opsional)</div>
                    </div>
                </div>
                <div class="pr-card-body">
                    <textarea name="keterangan" class="pr-textarea" placeholder="Tulis catatan atau instruksi khusus di sini...">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="pr-actions">
                <a href="{{ route('mineral.loading.index') }}" class="pr-btn pr-btn-cancel">
                    Batal
                </a>
                <button type="submit" class="pr-btn pr-btn-submit">
                    Ajukan Permintaan Penugasan
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    var barisCount = {{ old('items') ? count(old('items')) : 0 }};
    var subSalesData = @json($salesSub->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama, 'plat' => $s->no_kendaraan ?: ($s->currentAssignment?->vehicle?->license_plate ?? '')]));
    var stockData = {};

    function cekStok() {
        var produkId = document.getElementById('sel-produk').value;
        var mobilInti = document.getElementById('sel-mobil-inti');
        var selected = mobilInti.options[mobilInti.selectedIndex];
        var vehicleId = selected ? selected.getAttribute('data-vehicle-id') : null;
        var info = document.getElementById('stock-info');

        if (!produkId || !vehicleId) {
            info.style.display = 'none';
            return;
        }

        var key = vehicleId + '-' + produkId;
        if (stockData[key] !== undefined) {
            tampilkanStok(info, stockData[key]);
            return;
        }

        info.className = 'pr-stock-alert loading';
        info.style.display = 'flex';
        info.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation: spin 1s linear infinite;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Memeriksa stok...';

        fetch('/mineral/api/vehicle-stock/' + vehicleId + '/' + produkId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                stockData[key] = data.jumlah;
                tampilkanStok(info, data.jumlah);
            })
            .catch(function(e) {
                info.className = 'pr-stock-alert empty';
                info.innerHTML = 'Gagal memuat stok kendaraan.';
            });
    }

    function tampilkanStok(el, jumlah) {
        if (jumlah > 0) {
            el.className = 'pr-stock-alert available';
            el.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Stok tersedia di mobil inti: <strong>' + jumlah.toLocaleString() + '</strong> unit';
        } else {
            el.className = 'pr-stock-alert empty';
            el.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg> Stok kosong — Isi via Penerimaan Barang terlebih dahulu.';
        }
        el.style.display = 'flex';
    }

    document.getElementById('sel-produk').addEventListener('change', cekStok);
    document.getElementById('sel-mobil-inti').addEventListener('change', cekStok);

    function tambahBaris() {
        barisCount++;
        var idx = barisCount;
        var opts = '<option value="">Pilih Sales</option>';
        subSalesData.forEach(function(s) {
            opts += '<option value="' + s.id + '">' + s.nama + (s.plat ? ' (' + s.plat + ')' : '') + '</option>';
        });
        var html = '<div class="pr-row" id="row-' + idx + '" style="opacity:0; transform:translateY(-10px);">' +
            '<div class="pr-row-number">' + idx + '</div>' +
            '<div class="pr-row-grid">' +
                '<div><label class="pr-label">Sales Sub <span class="pr-req">*</span></label>' +
                '<select name="items[' + idx + '][sales_id]" class="pr-input" required>' + opts + '</select></div>' +
                '<div><label class="pr-label">Volume</label>' +
                '<input type="number" name="items[' + idx + '][jumlah]" class="pr-input" min="0.01" step="any" placeholder="0" required></div>' +
                '<button type="button" class="pr-btn-remove" onclick="hapusBaris(' + idx + ')" title="Hapus"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>' +
            '</div></div>';
        
        var container = document.getElementById('sales-list');
        container.insertAdjacentHTML('beforeend', html);
        
        // Trigger animation
        setTimeout(function() {
            var newRow = document.getElementById('row-' + idx);
            if (newRow) {
                newRow.style.opacity = '1';
                newRow.style.transform = 'translateY(0)';
            }
        }, 10);
        
        renumber();
    }

    function hapusBaris(idx) {
        var row = document.getElementById('row-' + idx);
        if (row) {
            row.style.opacity = '0';
            row.style.transform = 'translateY(-10px)';
            setTimeout(function() {
                row.remove();
                renumber();
            }, 300);
        }
    }

    function renumber() {
        var rows = document.querySelectorAll('#sales-list .pr-row');
        rows.forEach(function(r, i) {
            r.querySelector('.pr-row-number').textContent = i + 1;
            var sel = r.querySelector('select');
            var inp = r.querySelector('input[type="number"]');
            if (sel) sel.name = 'items[' + (i + 1) + '][sales_id]';
            if (inp) inp.name = 'items[' + (i + 1) + '][jumlah]';
        });
    }

    if (barisCount === 0) {
        tambahBaris();
    }
    
    // Initial check
    if (document.getElementById('sel-produk').value && document.getElementById('sel-mobil-inti').value) {
        cekStok();
    }
    </script>
    <style>
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
    @endpush
</x-app-layout>
