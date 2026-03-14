<x-app-layout>
    <x-slot name="header">SDM / HR - Potongan & Bonus</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER SECTION ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Payroll</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                        Potongan & Bonus
                    </h1>
                    <p class="tr-subtitle">Kelola kasbon, denda, serta tambahan bonus khusus yang mempengaruhi penggajian.</p>
                </div>
                <div class="tr-header-actions">
                    @can('create_potongan_gaji')
                        <button type="button" class="tr-btn tr-btn-primary" onclick="openAddModal()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Tambah Data
                        </button>
                    @endcan
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success')) <div class="tr-alert tr-alert-success">✅ {{ session('success') }}</div> @endif
            @if(session('error')) <div class="tr-alert tr-alert-danger">❌ {{ session('error') }}</div> @endif
            @if($errors->any())
                <div class="tr-alert tr-alert-danger">
                    <ul class="tr-alert-list">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- ─── FILTER SECTION ─── --}}
            <div class="tr-card tr-filter-card">
                <form method="GET" action="{{ route('sdm.potongan.index') }}" class="tr-filter-grid">
                    <div class="tr-form-group">
                        <label class="tr-label">Periode Bulan</label>
                        <input type="month" name="month" value="{{ $month }}" class="tr-input">
                    </div>
                    <div class="tr-form-group">
                        <label class="tr-label">Filter Karyawan</label>
                        <div class="tr-select-wrapper">
                            <select name="user_id" class="tr-select">
                                <option value="">Semua Karyawan</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="tr-filter-actions">
                        <button type="submit" class="tr-btn tr-btn-dark">Tampilkan</button>
                        <a href="{{ route('sdm.potongan.index') }}" class="tr-btn tr-btn-outline">Reset</a>
                    </div>
                </form>
            </div>

            {{-- ─── DATA TABLE CARD ─── --}}
            <div class="tr-card">
                <div class="tr-card-header">
                    <h2 class="tr-section-title">Log Jurnal Finansial: {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</h2>
                </div>

                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Nama Karyawan</th>
                                <th>Keterangan</th>
                                <th class="r">Nominal</th>
                                <th class="r">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- BONUSES --}}
                            @foreach($bonuses as $bon)
                                <tr>
                                    <td><div class="tr-date-box">{{ $bon->date->format('d M Y') }}</div></td>
                                    <td><span class="tr-badge tr-badge-success">BONUS</span></td>
                                    <td class="tr-font-bold">{{ $bon->user->name ?? '-' }}</td>
                                    <td class="tr-text-muted">{{ $bon->description }}</td>
                                    <td class="r tr-font-mono text-success tr-font-bold">+Rp{{ number_format($bon->amount, 0, ',', '.') }}</td>
                                    <td class="r">
                                        @can('delete_potongan_gaji')
                                            <form action="{{ route('sdm.bonus.destroy', $bon) }}" method="POST" class="inline-form" onsubmit="return confirm('Hapus bonus ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="tr-btn-icon-danger">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach

                            {{-- DEDUCTIONS --}}
                            @foreach($deductions as $pot)
                                <tr>
                                    <td><div class="tr-date-box">{{ $pot->date->format('d M Y') }}</div></td>
                                    <td><span class="tr-badge tr-badge-danger">POTONGAN</span></td>
                                    <td class="tr-font-bold">{{ $pot->user->name ?? '-' }}</td>
                                    <td class="tr-text-muted">{{ $pot->description }}</td>
                                    <td class="r tr-font-mono text-danger tr-font-bold">−Rp{{ number_format($pot->amount, 0, ',', '.') }}</td>
                                    <td class="r">
                                        @can('delete_potongan_gaji')
                                            <form action="{{ route('sdm.potongan.destroy', $pot) }}" method="POST" class="inline-form" onsubmit="return confirm('Hapus potongan ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="tr-btn-icon-danger">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach

                            @if($deductions->isEmpty() && $bonuses->isEmpty())
                                <tr>
                                    <td colspan="6" class="tr-empty-state">Tidak ada data potongan atau bonus di periode ini.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- ─── MODAL ADD ─── --}}
    <div id="addModal" class="tr-modal-overlay">
        <div class="tr-modal-content">
            <div class="tr-modal-header">
                <h3 class="tr-modal-title">Input Transaksi SDM</h3>
                <button type="button" class="tr-modal-close" onclick="closeAddModal()">✕</button>
            </div>
            <form action="{{ route('sdm.potongan.store') }}" method="POST">
                @csrf
                <div class="tr-modal-body">
                    <div class="tr-form-group">
                        <label class="tr-label">Pilih Karyawan <span class="tr-req">*</span></label>
                        <div class="tr-select-wrapper">
                            <select name="user_id" class="tr-select" required>
                                <option value="">-- Pilih Nama Karyawan --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="tr-form-row-2">
                        <div class="tr-form-group">
                            <label class="tr-label">Jenis Transaksi <span class="tr-req">*</span></label>
                            <div class="tr-select-wrapper">
                                <select name="type" class="tr-select" required>
                                    <option value="potongan">Denda / Potongan Gaji</option>
                                    <option value="bonus">Bonus / Tambahan Gaji</option>
                                </select>
                            </div>
                        </div>
                        <div class="tr-form-group">
                            <label class="tr-label">Tanggal <span class="tr-req">*</span></label>
                            <input type="date" name="date" class="tr-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="tr-form-group">
                        <label class="tr-label">Nominal Rupiah (Rp) <span class="tr-req">*</span></label>
                        <input type="number" name="amount" class="tr-input tr-font-mono" placeholder="0" min="1" required>
                    </div>

                    <div class="tr-form-group">
                        <label class="tr-label">Keterangan / Deskripsi <span class="tr-req">*</span></label>
                        <textarea name="description" rows="2" class="tr-textarea" placeholder="Contoh: Terlambat 3x / Bonus lembur proyek" required></textarea>
                    </div>
                </div>
                <div class="tr-modal-footer">
                    <button type="button" class="tr-btn tr-btn-light" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="tr-btn tr-btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-indigo: #4f46e5;
            --tr-indigo-light: #e0e7ff;
            --tr-indigo-hover: #4338ca;
            --tr-success: #10b981;
            --tr-danger: #ef4444;
            --tr-border: #e2e8f0;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-radius: 12px;
        }

        .tr-page-wrapper { background-color: #f8fafc; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); padding-bottom: 4rem; }
        .tr-page { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* HEADER */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-indigo); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin-top: 4px; }

        /* CARDS */
        .tr-card { background: #fff; border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fff; }
        .tr-section-title { font-size: 0.95rem; font-weight: 800; margin: 0; color: var(--tr-text-main); }

        /* FILTER BAR */
        .tr-filter-card { padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
        .tr-filter-grid { display: flex; gap: 1.25rem; align-items: flex-end; flex-wrap: wrap; }
        .tr-form-group { display: flex; flex-direction: column; gap: 5px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); letter-spacing: 0.05em; }
        .tr-input, .tr-select, .tr-textarea { padding: 0.6rem 0.85rem; border: 1px solid var(--tr-border); border-radius: 8px; font-size: 0.85rem; background: #f8fafc; transition: 0.2s; font-family: inherit; }
        .tr-input:focus, .tr-select:focus { border-color: var(--tr-indigo); outline: none; background: #fff; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }

        /* TABLE */
        .table-responsive { overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; }
        .tr-table thead th { background: #f8fafc; padding: 0.85rem 1.25rem; font-size: 0.7rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; border-bottom: 1px solid var(--tr-border); text-align: left; }
        .tr-table tbody td { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; vertical-align: middle; }
        .tr-table tbody tr:hover { background: #fafafa; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* BADGES & BUTTONS */
        .tr-badge { padding: 0.25rem 0.6rem; border-radius: 99px; font-size: 0.65rem; font-weight: 800; letter-spacing: 0.02em; }
        .tr-badge-success { background: #dcfce7; color: #15803d; }
        .tr-badge-danger { background: #fee2e2; color: #b91c1c; }

        .tr-btn { display: inline-flex; align-items: center; gap: 6px; padding: 0.55rem 1.25rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: 0.2s; text-decoration: none; border: 1px solid transparent; }
        .tr-btn-primary { background: var(--tr-indigo); color: #fff; }
        .tr-btn-primary:hover { background: var(--tr-indigo-hover); }
        .tr-btn-outline { border-color: var(--tr-border); background: #fff; color: var(--tr-text-main); }
        .tr-btn-dark { background: var(--tr-text-main); color: #fff; }
        .tr-btn-light { background: transparent; color: var(--tr-text-muted); }
        .tr-btn-light:hover { background: #f1f5f9; }

        .tr-btn-icon-danger { width: 30px; height: 30px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #fee2e2; background: #fff; color: var(--tr-danger); cursor: pointer; transition: 0.2s; }
        .tr-btn-icon-danger:hover { background: var(--tr-danger); color: #fff; }

        /* MODAL */
        .tr-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(2px); z-index: 9999; align-items: center; justify-content: center; padding: 1.5rem; }
        .tr-modal-content { background: #fff; width: 100%; max-width: 480px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden; animation: tr-modal-pop 0.2s ease-out; }
        @keyframes tr-modal-pop { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .tr-modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .tr-modal-title { font-size: 1.1rem; font-weight: 800; margin: 0; }
        .tr-modal-close { background: none; border: none; font-size: 1.2rem; color: var(--tr-text-light); cursor: pointer; }
        .tr-modal-body { padding: 1.5rem; }
        .tr-modal-footer { padding: 1.25rem 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 0.75rem; background: #f8fafc; }

        /* UTILS */
        .tr-date-box { font-size: 0.8rem; font-weight: 600; color: var(--tr-text-main); }
        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
        .tr-font-bold { font-weight: 800; }
        .tr-text-muted { color: var(--tr-text-muted); }
        .text-success { color: var(--tr-success); }
        .text-danger { color: var(--tr-danger); }
        .tr-empty-state { text-align: center; padding: 3rem; color: var(--tr-text-muted); font-style: italic; }
        .tr-form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }

        /* SELECT WRAPPER */
        .tr-select-wrapper { position: relative; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        .tr-select { appearance: none; padding-right: 2.5rem; cursor: pointer; width: 100%; }

        @media (max-width: 640px) {
            .tr-header-actions { width: 100%; }
            .tr-btn { width: 100%; justify-content: center; }
            .tr-filter-grid { grid-template-columns: 1fr; }
            .tr-form-row-2 { grid-template-columns: 1fr; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function openAddModal() { document.getElementById('addModal').style.display = 'flex'; }
        function closeAddModal() { document.getElementById('addModal').style.display = 'none'; }
    </script>
    @endpush
</x-app-layout>