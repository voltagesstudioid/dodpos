<x-app-layout>
    <x-slot name="header">SDM / HR - Kalender Kerja</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER SECTION ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Waktu</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        Kalender Libur & Kerja
                    </h1>
                    <p class="tr-subtitle">Atur pengecualian tanggal libur nasional atau lembur perusahaan agar perhitungan payroll akurat.</p>
                </div>
                <div class="tr-header-actions">
                    @can('create_absensi')
                        <button type="button" class="tr-btn tr-btn-teal" onclick="openAddHolidayModal()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Tambah Manual
                        </button>
                    @endcan
                    <a href="{{ route('sdm.absensi.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Ke Absensi
                    </a>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success')) <div class="tr-alert tr-alert-success">✅ {{ session('success') }}</div> @endif
            @if(session('error')) <div class="tr-alert tr-alert-danger">❌ {{ session('error') }}</div> @endif

            {{-- ─── FILTER & GENERATOR SECTION ─── --}}
            <div class="tr-card tr-filter-card">
                <div class="tr-filter-flex-wrap">
                    <form method="GET" action="{{ route('sdm.libur.index') }}" class="tr-filter-grid">
                        <div class="tr-form-group">
                            <label class="tr-label">Periode Bulan</label>
                            <input type="month" name="month" value="{{ $month }}" class="tr-input tr-input-date">
                        </div>
                        <div class="tr-filter-actions">
                            <button type="submit" class="tr-btn tr-btn-dark">Tampilkan</button>
                            <a href="{{ route('sdm.libur.index') }}" class="tr-btn tr-btn-outline">Reset</a>
                        </div>
                    </form>

                    @if($calendarMode === 'manual')
                    <div class="tr-divider-v"></div>
                    <div class="tr-generator-actions">
                        @can('create_absensi')
                            <form method="POST" action="{{ route('sdm.libur.generate') }}" class="tr-inline-form">
                                @csrf
                                <input type="hidden" name="month" value="{{ $month }}">
                                <button type="submit" class="tr-btn tr-btn-indigo-soft" onclick="this.innerHTML='⏳ Processing...'; this.disabled=true; this.form.submit();">
                                    Generate Default
                                </button>
                            </form>
                            <form method="POST" action="{{ route('sdm.libur.generate') }}" class="tr-inline-form" onsubmit="return confirm('Hapus dan buat ulang data bulan ini?');">
                                @csrf
                                <input type="hidden" name="month" value="{{ $month }}">
                                <input type="hidden" name="overwrite" value="1">
                                <button type="submit" class="tr-btn tr-btn-danger-outline tr-btn-sm">Regenerate (Overwrite)</button>
                            </form>
                        @endcan
                    </div>
                    @endif
                </div>
            </div>

            {{-- ─── TABLE DATA ─── --}}
            <div class="tr-card">
                <div class="tr-card-header tr-flex-between">
                    <div>
                        <h2 class="tr-section-title">
                            {{ $calendarMode === 'manual' ? 'Daftar Hari Kerja' : 'Override Hari Libur' }}
                        </h2>
                        <p class="tr-card-subtitle">Periode: <strong>{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</strong></p>
                    </div>
                    <div class="tr-mode-badge {{ $calendarMode === 'manual' ? 'manual' : 'auto' }}">
                        Mode: {{ ucfirst($calendarMode) }}
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th class="c">Hari</th>
                                <th>Status Operasional</th>
                                <th>Nama Event / Libur</th>
                                <th>Keterangan</th>
                                <th class="r">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calendar as $c)
                                @php
                                    $row = $c['row'];
                                    $dateStr = $c['date'];
                                    $isWorking = $row ? (bool) $row->is_working_day : false;
                                    $isSunday = \Carbon\Carbon::parse($dateStr)->isSunday();
                                @endphp
                                <tr class="{{ $isSunday && !$isWorking ? 'tr-row-muted' : '' }}">
                                    <td class="tr-font-bold">{{ \Carbon\Carbon::parse($dateStr)->format('d M Y') }}</td>
                                    <td class="c tr-text-muted">{{ $c['dow'] }}</td>
                                    <td>
                                        @if($isWorking)
                                            <span class="tr-badge tr-badge-success">HARI KERJA</span>
                                        @else
                                            <span class="tr-badge tr-badge-gray">LIBUR</span>
                                        @endif
                                    </td>
                                    <td><span class="{{ $row?->name ? 'tr-font-semibold' : 'tr-text-light' }}">{{ $row?->name ?: '-' }}</span></td>
                                    <td class="tr-text-muted tr-small">{{ $row?->notes ?: '-' }}</td>
                                    <td class="r">
                                        <div class="tr-actions-group">
                                            @can('edit_absensi')
                                                @if($row)
                                                    <button type="button" class="tr-action-btn-circle" 
                                                        data-id="{{ $row->id }}" data-name="{{ $row->name }}"
                                                        data-notes="{{ $row->notes }}" data-working="{{ $row->is_working_day ? '1' : '0' }}"
                                                        onclick="openEditHolidayModal(this)">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    </button>
                                                @elseif($calendarMode === 'auto')
                                                    <button type="button" class="tr-action-btn-circle tr-text-teal" onclick="openAddHolidayModalWithDate('{{ $dateStr }}')">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                    </button>
                                                @endif
                                            @endcan
                                            
                                            @can('delete_absensi')
                                                @if($row)
                                                    <form method="POST" action="{{ route('sdm.libur.destroy', $row) }}" class="tr-inline" onsubmit="return confirm('Hapus pengaturan tanggal ini?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="tr-action-btn-circle tr-text-danger">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── MODALS ─── --}}
    <div id="addHolidayModal" class="tr-modal-overlay">
        <div class="tr-modal-content">
            <div class="tr-modal-header">
                <h3 class="tr-modal-title">Atur Tanggal Khusus</h3>
                <button type="button" class="tr-modal-close" onclick="closeAddHolidayModal()">✕</button>
            </div>
            <form action="{{ route('sdm.libur.store') }}" method="POST">
                @csrf
                <div class="tr-modal-body">
                    <div class="tr-grid-2">
                        <div class="tr-form-group">
                            <label class="tr-label">Tanggal <span class="tr-req">*</span></label>
                            <input type="date" name="date" id="addHolidayDate" class="tr-input" required>
                        </div>
                        <div class="tr-form-group">
                            <label class="tr-label">Tipe Hari</label>
                            <select name="is_working_day" class="tr-select">
                                <option value="0">Libur (Holiday)</option>
                                <option value="1">Masuk (Working Day)</option>
                            </select>
                        </div>
                    </div>
                    <div class="tr-form-group mt-3">
                        <label class="tr-label">Nama Event</label>
                        <input name="name" class="tr-input" placeholder="Misal: Idul Fitri / Lembur Akhir Tahun">
                    </div>
                    <div class="tr-form-group mt-3">
                        <label class="tr-label">Catatan Internal</label>
                        <textarea name="notes" rows="2" class="tr-textarea" placeholder="Opsional..."></textarea>
                    </div>
                </div>
                <div class="tr-modal-footer">
                    <button type="button" class="tr-btn tr-btn-light" onclick="closeAddHolidayModal()">Batal</button>
                    <button type="submit" class="tr-btn tr-btn-indigo">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editHolidayModal" class="tr-modal-overlay">
        <div class="tr-modal-content">
            <div class="tr-modal-header">
                <h3 class="tr-modal-title">Edit Detail Tanggal</h3>
                <button type="button" class="tr-modal-close" onclick="closeEditHolidayModal()">✕</button>
            </div>
            <form id="editHolidayForm" method="POST">
                @csrf @method('PATCH')
                <div class="tr-modal-body">
                    <div class="tr-form-group">
                        <label class="tr-label">Status Operasional</label>
                        <select name="is_working_day" id="editHolidayWorking" class="tr-select">
                            <option value="0">Libur</option>
                            <option value="1">Hari Kerja</option>
                        </select>
                    </div>
                    <div class="tr-form-group mt-3">
                        <label class="tr-label">Nama Event</label>
                        <input name="name" id="editHolidayName" class="tr-input">
                    </div>
                    <div class="tr-form-group mt-3">
                        <label class="tr-label">Catatan</label>
                        <textarea name="notes" id="editHolidayNotes" rows="2" class="tr-textarea"></textarea>
                    </div>
                </div>
                <div class="tr-modal-footer">
                    <button type="button" class="tr-btn tr-btn-light" onclick="closeEditHolidayModal()">Batal</button>
                    <button type="submit" class="tr-btn tr-btn-indigo">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-indigo: #4f46e5;
            --tr-teal: #0d9488;
            --tr-danger: #ef4444;
            --tr-border: #e2e8f0;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
        }

        .tr-page-wrapper { background-color: #f8fafc; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; padding-bottom: 4rem; }
        .tr-page { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* HEADER */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-indigo); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-indigo { background: #e0e7ff; color: var(--tr-indigo); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin-top: 4px; }

        /* CARDS */
        .tr-card { background: #fff; border: 1px solid var(--tr-border); border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fff; }
        .tr-flex-between { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .tr-section-title { font-size: 1rem; font-weight: 800; margin: 0; }
        .tr-card-subtitle { font-size: 0.75rem; color: var(--tr-text-muted); }

        /* FILTER BAR */
        .tr-filter-card { padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
        .tr-filter-flex-wrap { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1.5rem; }
        .tr-filter-grid { display: flex; gap: 1.25rem; align-items: flex-end; flex-wrap: wrap; }
        .tr-form-group { display: flex; flex-direction: column; gap: 5px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); letter-spacing: 0.05em; }
        .tr-input, .tr-select, .tr-textarea { padding: 0.5rem 0.75rem; border: 1px solid var(--tr-border); border-radius: 8px; font-size: 0.85rem; background: #f8fafc; transition: 0.2s; font-family: inherit; }
        .tr-input:focus { border-color: var(--tr-indigo); outline: none; background: #fff; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        .tr-filter-actions { display: flex; gap: 0.5rem; }

        .tr-divider-v { width: 1px; height: 40px; background: var(--tr-border); align-self: flex-end; }
        .tr-generator-actions { display: flex; gap: 0.5rem; align-items: flex-end; }

        /* TABLE */
        .table-responsive { overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; }
        .tr-table thead th { background: #f8fafc; padding: 0.75rem 1rem; font-size: 0.7rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; border-bottom: 1px solid var(--tr-border); text-align: left; }
        .tr-table tbody td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; vertical-align: middle; }
        .tr-table tbody tr:hover { background: #fafafa; }
        .tr-row-muted { background: #fcfcfc; opacity: 0.8; }
        .tr-table th.c, .tr-table td.c { text-align: center; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* BADGES */
        .tr-badge { padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.65rem; font-weight: 800; letter-spacing: 0.02em; }
        .tr-badge-success { background: #dcfce7; color: #15803d; }
        .tr-badge-gray { background: #f1f5f9; color: #64748b; }
        .tr-mode-badge { padding: 4px 12px; border-radius: 99px; font-size: 0.7rem; font-weight: 700; border: 1px solid transparent; }
        .tr-mode-badge.manual { background: #fffbeb; color: #92400e; border-color: #fef3c7; }
        .tr-mode-badge.auto { background: #f0fdfa; color: #0d9488; border-color: #ccfbf1; }

        /* BUTTONS */
        .tr-btn { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1.1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: 0.2s; text-decoration: none; border: 1px solid transparent; }
        .tr-btn-teal { background: var(--tr-teal); color: #fff; }
        .tr-btn-indigo { background: var(--tr-indigo); color: #fff; }
        .tr-btn-indigo-soft { background: #e0e7ff; color: var(--tr-indigo); }
        .tr-btn-dark { background: var(--tr-text-main); color: #fff; }
        .tr-btn-outline { border-color: var(--tr-border); background: #fff; color: var(--tr-text-main); }
        .tr-btn-danger-outline { border-color: #fecaca; color: var(--tr-danger); background: transparent; }

        .tr-action-btn-circle { width: 30px; height: 30px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--tr-border); background: #fff; cursor: pointer; color: var(--tr-text-muted); transition: 0.2s; }
        .tr-action-btn-circle:hover { color: var(--tr-indigo); border-color: var(--tr-indigo); }
        .tr-actions-group { display: flex; gap: 6px; justify-content: flex-end; }

        /* MODAL */
        .tr-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(2px); z-index: 9999; align-items: center; justify-content: center; padding: 1.5rem; }
        .tr-modal-content { background: #fff; width: 100%; max-width: 500px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden; animation: tr-modal-pop 0.2s ease-out; }
        @keyframes tr-modal-pop { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .tr-modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .tr-modal-title { font-size: 1.1rem; font-weight: 800; margin: 0; }
        .tr-modal-close { background: none; border: none; font-size: 1.2rem; color: var(--tr-text-light); cursor: pointer; }
        .tr-modal-body { padding: 1.5rem; }
        .tr-modal-footer { padding: 1.25rem 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 0.75rem; background: #f8fafc; }
        
        .tr-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .mt-3 { margin-top: 1rem; }

        .tr-font-bold { font-weight: 800; }
        .tr-text-danger { color: var(--tr-danger); }
        .tr-text-teal { color: var(--tr-teal); }

        @media (max-width: 768px) {
            .tr-header-actions { width: 100%; justify-content: space-between; }
            .tr-filter-flex-wrap { flex-direction: column; align-items: stretch; }
            .tr-divider-v { display: none; }
            .tr-generator-actions { justify-content: space-between; }
            .tr-generator-actions form, .tr-generator-actions button { flex: 1; }
            .tr-grid-2 { grid-template-columns: 1fr; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function openAddHolidayModal() { document.getElementById('addHolidayModal').style.display = 'flex'; }
        function closeAddHolidayModal() { document.getElementById('addHolidayModal').style.display = 'none'; }
        function openAddHolidayModalWithDate(dateStr) {
            const inp = document.getElementById('addHolidayDate');
            if (inp) inp.value = dateStr;
            openAddHolidayModal();
        }
        function openEditHolidayModal(btn) {
            const form = document.getElementById('editHolidayForm');
            form.action = "{{ url('sdm/libur') }}/" + btn.dataset.id;
            document.getElementById('editHolidayWorking').value = btn.dataset.working || '0';
            document.getElementById('editHolidayName').value = btn.dataset.name || '';
            document.getElementById('editHolidayNotes').value = btn.dataset.notes || '';
            document.getElementById('editHolidayModal').style.display = 'flex';
        }
        function closeEditHolidayModal() { document.getElementById('editHolidayModal').style.display = 'none'; }
    </script>
    @endpush
</x-app-layout>