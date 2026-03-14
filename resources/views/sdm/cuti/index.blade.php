<x-app-layout>
    <x-slot name="header">SDM / HR - Pengajuan Cuti</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER SECTION ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Kehadiran</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-teal">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><rect x="18" y="8" width="5" height="5" rx="1"></rect><path d="M18 13v6"></path></svg>
                        </div>
                        Daftar Pengajuan Cuti
                    </h1>
                    <p class="tr-subtitle">Kelola persetujuan cuti, izin, dan sakit karyawan secara terpusat.</p>
                </div>
                <div class="tr-header-actions">
                    @can('create_absensi')
                        <button type="button" class="tr-btn tr-btn-teal" onclick="openAddModal()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Ajukan Cuti
                        </button>
                    @endcan
                    <a href="{{ route('sdm.libur.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        Kalender Libur
                    </a>
                    <a href="{{ route('sdm.absensi.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Data Absensi
                    </a>
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
                <form method="GET" action="{{ route('sdm.cuti.index') }}" class="tr-filter-grid">
                    <div class="tr-form-group">
                        <label class="tr-label">Periode Bulan</label>
                        <input type="month" name="month" value="{{ $month }}" class="tr-input">
                    </div>
                    <div class="tr-form-group">
                        <label class="tr-label">Status</label>
                        <div class="tr-select-wrapper">
                            <select name="status" class="tr-select">
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>⌛ Pending</option>
                                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>✅ Approved</option>
                                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="tr-form-group">
                        <label class="tr-label">Cari Karyawan</label>
                        <div class="tr-select-wrapper">
                            <select name="user_id" class="tr-select">
                                <option value="">Semua Karyawan</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="tr-filter-actions">
                        <button type="submit" class="tr-btn tr-btn-dark">Tampilkan</button>
                        <a href="{{ route('sdm.cuti.index') }}" class="tr-btn tr-btn-outline">Reset</a>
                    </div>
                </form>
            </div>

            {{-- ─── DATA TABLE CARD ─── --}}
            <div class="tr-card">
                <div class="tr-card-header">
                    <h2 class="tr-section-title">Log Pengajuan: {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</h2>
                </div>

                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>Tipe</th>
                                <th>Rentang Tanggal</th>
                                <th class="c">Berbayar</th>
                                <th class="c">Status</th>
                                <th>Catatan</th>
                                <th class="r">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $r)
                                <tr>
                                    <td>
                                        <div class="tr-user-info">
                                            <div class="name">{{ $r->user?->name ?? '-' }}</div>
                                            <div class="meta">{{ $r->user?->email ?? '-' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="tr-type-badge">{{ strtoupper($r->type) }}</span>
                                    </td>
                                    <td>
                                        <div class="tr-date-range">
                                            <span class="start">{{ $r->start_date->format('d/m/Y') }}</span>
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                            <span class="end">{{ $r->end_date->format('d/m/Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="c">
                                        @if($r->paid)
                                            <span class="text-success font-bold">Ya</span>
                                        @else
                                            <span class="text-danger font-bold">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="c">
                                        @php
                                            $st = $r->status;
                                            $cls = $st === 'approved' ? 'tr-badge-success' : ($st === 'rejected' ? 'tr-badge-danger' : 'tr-badge-gray');
                                        @endphp
                                        <span class="tr-badge {{ $cls }}">{{ strtoupper($st) }}</span>
                                    </td>
                                    <td>
                                        <div class="tr-notes-text" title="{{ $r->notes }}">
                                            {{ $r->notes ?: '-' }}
                                        </div>
                                    </td>
                                    <td class="r">
                                        <div class="tr-actions-group">
                                            @can('edit_absensi')
                                                @if($r->status === 'pending')
                                                    <form method="POST" action="{{ route('sdm.cuti.approve', $r) }}" style="margin:0;">
                                                        @csrf
                                                        <button type="submit" class="tr-action-btn acc" title="Setujui">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('sdm.cuti.reject', $r) }}" style="margin:0;">
                                                        @csrf
                                                        <button type="submit" class="tr-action-btn rej" title="Tolak">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
                                            @can('delete_absensi')
                                                @if($r->status !== 'approved')
                                                    <form method="POST" action="{{ route('sdm.cuti.destroy', $r) }}" style="margin:0;" onsubmit="return confirm('Hapus pengajuan cuti ini?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="tr-action-btn del" title="Hapus">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="tr-empty-state">Tidak ada pengajuan cuti di periode ini.</td>
                                </tr>
                            @endforelse
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
                <h3 class="tr-modal-title">Form Pengajuan Cuti</h3>
                <button type="button" class="tr-modal-close" onclick="closeAddModal()">✕</button>
            </div>
            <form action="{{ route('sdm.cuti.store') }}" method="POST">
                @csrf
                <div class="tr-modal-body">
                    <div class="tr-form-group">
                        <label class="tr-label">Pilih Karyawan <span class="tr-req">*</span></label>
                        <div class="tr-select-wrapper">
                            <select name="user_id" class="tr-select" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="tr-form-row-2">
                        <div class="tr-form-group">
                            <label class="tr-label">Tipe Izin <span class="tr-req">*</span></label>
                            <div class="tr-select-wrapper">
                                <select name="type" class="tr-select" required>
                                    <option value="cuti">CUTI</option>
                                    <option value="izin">IZIN</option>
                                    <option value="sakit">SAKIT</option>
                                </select>
                            </div>
                        </div>
                        <div class="tr-form-group tr-flex-end">
                            <label class="tr-checkbox-wrapper">
                                <input type="checkbox" name="paid" value="1" checked>
                                <div class="tr-checkbox-box"></div>
                                <span class="tr-label-text">Dibayar (Paid)</span>
                            </label>
                        </div>
                    </div>

                    <div class="tr-form-row-2">
                        <div class="tr-form-group">
                            <label class="tr-label">Mulai <span class="tr-req">*</span></label>
                            <input type="date" name="start_date" class="tr-input" required>
                        </div>
                        <div class="tr-form-group">
                            <label class="tr-label">Selesai <span class="tr-req">*</span></label>
                            <input type="date" name="end_date" class="tr-input" required>
                        </div>
                    </div>

                    <div class="tr-form-group">
                        <label class="tr-label">Catatan Alasan</label>
                        <textarea name="notes" rows="3" class="tr-textarea" placeholder="Tulis alasan singkat..."></textarea>
                    </div>
                </div>
                <div class="tr-modal-footer">
                    <button type="button" class="tr-btn tr-btn-light" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="tr-btn tr-btn-teal">Simpan Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-teal: #0d9488;
            --tr-teal-hover: #0f766e;
            --tr-indigo: #4f46e5;
            --tr-border: #e2e8f0;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-radius: 12px;
        }

        .tr-page-wrapper { background-color: #f8fafc; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; padding-bottom: 3rem; }
        .tr-page { max-width: 1280px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* HEADER */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-teal); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-teal { background: #ccfbf1; color: var(--tr-teal); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin-top: 4px; }

        /* CARDS */
        .tr-card { background: #fff; border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
        .tr-section-title { font-size: 1rem; font-weight: 800; margin: 0; }

        /* FILTER BAR */
        .tr-filter-card { padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
        .tr-filter-grid { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1.25rem; align-items: flex-end; }
        .tr-form-group { display: flex; flex-direction: column; gap: 5px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); letter-spacing: 0.05em; }
        .tr-input, .tr-select, .tr-textarea { padding: 0.5rem 0.75rem; border: 1px solid var(--tr-border); border-radius: 8px; font-size: 0.85rem; background: #f8fafc; transition: 0.2s; font-family: inherit; }
        .tr-input:focus, .tr-select:focus { border-color: var(--tr-teal); outline: none; background: #fff; box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1); }
        .tr-filter-actions { display: flex; gap: 0.5rem; }

        /* TABLE */
        .table-responsive { overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; }
        .tr-table thead th { background: #f8fafc; padding: 0.75rem 1rem; font-size: 0.7rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; border-bottom: 1px solid var(--tr-border); text-align: left; }
        .tr-table tbody td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; vertical-align: middle; }
        .tr-table tbody tr:hover { background: #fafafa; }
        .tr-table th.c, .tr-table td.c { text-align: center; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        .tr-user-info .name { font-weight: 700; color: var(--tr-text-main); }
        .tr-user-info .meta { font-size: 0.7rem; color: var(--tr-text-muted); }
        .tr-date-range { display: flex; align-items: center; gap: 8px; color: var(--tr-text-main); font-weight: 500; font-size: 0.8rem; }
        .tr-notes-text { font-size: 0.8rem; color: var(--tr-text-muted); font-style: italic; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* BADGES & BUTTONS */
        .tr-badge { padding: 0.2rem 0.6rem; border-radius: 99px; font-size: 0.65rem; font-weight: 800; letter-spacing: 0.02em; }
        .tr-badge-success { background: #dcfce7; color: #15803d; }
        .tr-badge-danger { background: #fee2e2; color: #b91c1c; }
        .tr-badge-gray { background: #f1f5f9; color: #64748b; }

        .tr-btn { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1.1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: 0.2s; text-decoration: none; border: 1px solid transparent; }
        .tr-btn-teal { background: var(--tr-teal); color: #fff; }
        .tr-btn-teal:hover { background: var(--tr-teal-hover); }
        .tr-btn-outline { border-color: var(--tr-border); background: #fff; color: var(--tr-text-main); }
        .tr-btn-dark { background: var(--tr-text-main); color: #fff; }
        .tr-btn-light { background: transparent; color: var(--tr-text-muted); }
        .tr-btn-light:hover { background: #f1f5f9; }

        .tr-actions-group { display: flex; gap: 6px; justify-content: flex-end; }
        .tr-action-btn { width: 30px; height: 30px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--tr-border); background: #fff; cursor: pointer; transition: 0.2s; }
        .tr-action-btn.acc { color: var(--tr-teal); border-color: #99f6e4; }
        .tr-action-btn.acc:hover { background: #f0fdfa; }
        .tr-action-btn.rej { color: var(--tr-danger); border-color: #fecaca; }
        .tr-action-btn.rej:hover { background: #fef2f2; }
        .tr-action-btn.del:hover { background: #f1f5f9; }

        /* MODAL */
        .tr-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(2px); z-index: 9999; align-items: center; justify-content: center; padding: 1.5rem; }
        .tr-modal-content { background: #fff; width: 100%; max-width: 500px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden; animation: tr-modal-pop 0.2s ease-out; }
        @keyframes tr-modal-pop { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .tr-modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .tr-modal-title { font-size: 1.1rem; font-weight: 800; margin: 0; }
        .tr-modal-close { background: none; border: none; font-size: 1.2rem; color: var(--tr-text-light); cursor: pointer; }
        .tr-modal-body { padding: 1.5rem; }
        .tr-modal-footer { padding: 1.25rem 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 0.75rem; background: #f8fafc; }

        .tr-form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
        .tr-flex-end { align-items: flex-end; }
        .tr-checkbox-wrapper { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .tr-checkbox-box { width: 18px; height: 18px; border: 2px solid var(--tr-border); border-radius: 4px; transition: 0.2s; position: relative; }
        input[type="checkbox"]:checked + .tr-checkbox-box { background: var(--tr-teal); border-color: var(--tr-teal); }
        input[type="checkbox"]:checked + .tr-checkbox-box::after { content: '✓'; color: #fff; font-size: 12px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        input[type="checkbox"] { display: none; }

        .tr-select-wrapper { position: relative; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        .tr-select { appearance: none; padding-right: 2.5rem; cursor: pointer; }

        @media (max-width: 992px) {
            .tr-filter-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .tr-header-actions { width: 100%; justify-content: space-between; }
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
