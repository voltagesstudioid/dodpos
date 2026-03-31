<x-hr-layout>
    <x-slot name="eyebrow">Manajemen Waktu</x-slot>
    <x-slot name="title">Kalender Libur & Kerja</x-slot>
    <x-slot name="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
    </x-slot>
    <x-slot name="iconBg">bg-indigo</x-slot>
    <x-slot name="description">Atur pengecualian tanggal libur nasional atau lembur perusahaan agar perhitungan payroll akurat.</x-slot>
    <x-slot name="actions">
        @can('create_absensi')
            <button type="button" class="hr-btn hr-btn-primary" onclick="openAddHolidayModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Manual
            </button>
        @endcan
        <a href="{{ route('sdm.absensi.index') }}" class="hr-btn hr-btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            Ke Absensi
        </a>
    </x-slot>

    {{-- Filter & Generator --}}
    <div class="hr-card" style="margin-bottom: 1.5rem;">
        <div style="padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
            <form method="GET" action="{{ route('sdm.libur.index') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
                <div style="display: flex; flex-direction: column; gap: 0.375rem;">
                    <label style="font-size: 0.75rem; font-weight: 600; color: #374151; text-transform: uppercase;">Periode Bulan</label>
                    <input type="month" name="month" value="{{ $month }}" class="hr-input">
                </div>
                <button type="submit" class="hr-btn hr-btn-primary">Tampilkan</button>
                <a href="{{ route('sdm.libur.index') }}" class="hr-btn hr-btn-secondary">Reset</a>
            </form>

            @if($calendarMode === 'manual')
            <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                @can('create_absensi')
                    <form method="POST" action="{{ route('sdm.libur.generate') }}" style="margin:0;">
                        @csrf
                        <input type="hidden" name="month" value="{{ $month }}">
                        <button type="submit" class="hr-btn hr-btn-secondary" style="background: #e0e7ff; color: #4f46e5; border-color: #c7d2fe;" onclick="this.innerHTML='Processing...'; this.disabled=true; this.form.submit();">
                            Generate Default
                        </button>
                    </form>
                    <form method="POST" action="{{ route('sdm.libur.generate') }}" style="margin:0;" onsubmit="return confirm('Hapus dan buat ulang data bulan ini?');">
                        @csrf
                        <input type="hidden" name="month" value="{{ $month }}">
                        <input type="hidden" name="overwrite" value="1">
                        <button type="submit" class="hr-btn hr-btn-secondary" style="background: #fef2f2; color: #dc2626; border-color: #fecaca;">Regenerate</button>
                    </form>
                @endcan
            </div>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="hr-card">
        <div class="hr-card-header">
            <div>
                <h2 class="hr-card-title">{{ $calendarMode === 'manual' ? 'Daftar Hari Kerja' : 'Override Hari Libur' }}</h2>
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">Periode: <strong>{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</strong></p>
            </div>
            <span class="hr-badge {{ $calendarMode === 'manual' ? 'hr-badge-yellow' : 'hr-badge-green' }}" style="font-size: 0.75rem;">
                Mode: {{ ucfirst($calendarMode) }}
            </span>
        </div>
        <div class="hr-table-wrapper">
            <table class="hr-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th style="text-align: center;">Hari</th>
                        <th>Status</th>
                        <th>Nama Event</th>
                        <th>Keterangan</th>
                        <th style="text-align: right;">Aksi</th>
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
                        <tr style="{{ $isSunday && !$isWorking ? 'background: #f9fafb;' : '' }}">
                            <td style="font-weight: 600;">{{ \Carbon\Carbon::parse($dateStr)->format('d M Y') }}</td>
                            <td style="text-align: center; color: #6b7280;">{{ $c['dow'] }}</td>
                            <td>
                                @if($isWorking)
                                    <span class="hr-badge hr-badge-green">HARI KERJA</span>
                                @else
                                    <span class="hr-badge hr-badge-gray">LIBUR</span>
                                @endif
                            </td>
                            <td style="font-weight: {{ $row?->name ? '500' : '400' }}; color: {{ $row?->name ? '#111827' : '#9ca3af' }};">{{ $row?->name ?: '-' }}</td>
                            <td style="color: #6b7280; font-size: 0.875rem;">{{ $row?->notes ?: '-' }}</td>
                            <td style="text-align: right;">
                                <div class="hr-actions">
                                    @can('edit_absensi')
                                        @if($row)
                                            <button type="button" class="hr-action" 
                                                data-id="{{ $row->id }}" data-name="{{ $row->name }}"
                                                data-notes="{{ $row->notes }}" data-working="{{ $row->is_working_day ? '1' : '0' }}"
                                                onclick="openEditHolidayModal(this)" title="Edit">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </button>
                                        @elseif($calendarMode === 'auto')
                                            <button type="button" class="hr-action" style="color: #0d9488;" onclick="openAddHolidayModalWithDate('{{ $dateStr }}')" title="Tambah">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                            </button>
                                        @endif
                                    @endcan
                                    @can('delete_absensi')
                                        @if($row)
                                            <form method="POST" action="{{ route('sdm.libur.destroy', $row) }}" style="margin:0;" onsubmit="return confirm('Hapus pengaturan tanggal ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="hr-action" style="color: #dc2626;" title="Hapus">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
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

    {{-- Modal Add --}}
    <div id="addHolidayModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; padding:1.5rem;">
        <div style="background:#fff; border-radius:12px; width:100%; max-width:500px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #e5e7eb;">
                <h3 style="margin:0; font-size:1.125rem; font-weight:600;">Atur Tanggal Khusus</h3>
                <button type="button" onclick="closeAddHolidayModal()" style="background:none; border:none; cursor:pointer; padding:0.5rem; color:#6b7280;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form action="{{ route('sdm.libur.store') }}" method="POST">
                @csrf
                <div style="padding:1.5rem;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Tanggal <span style="color:#dc2626;">*</span></label>
                            <input type="date" name="date" id="addHolidayDate" class="hr-input" required style="width:100%;">
                        </div>
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Tipe Hari</label>
                            <select name="is_working_day" class="hr-select" style="width:100%;">
                                <option value="0">Libur (Holiday)</option>
                                <option value="1">Masuk (Working)</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Nama Event</label>
                        <input name="name" class="hr-input" placeholder="Misal: Idul Fitri / Lembur" style="width:100%;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Catatan Internal</label>
                        <textarea name="notes" rows="2" class="hr-input" placeholder="Opsional..." style="width:100%; resize:vertical;"></textarea>
                    </div>
                </div>
                <div style="padding:1rem 1.5rem; border-top:1px solid #e5e7eb; background:#f9fafb; display:flex; justify-content:flex-end; gap:0.75rem;">
                    <button type="button" class="hr-btn hr-btn-ghost" onclick="closeAddHolidayModal()">Batal</button>
                    <button type="submit" class="hr-btn hr-btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editHolidayModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; padding:1.5rem;">
        <div style="background:#fff; border-radius:12px; width:100%; max-width:500px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #e5e7eb;">
                <h3 style="margin:0; font-size:1.125rem; font-weight:600;">Edit Detail Tanggal</h3>
                <button type="button" onclick="closeEditHolidayModal()" style="background:none; border:none; cursor:pointer; padding:0.5rem; color:#6b7280;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="editHolidayForm" method="POST">
                @csrf @method('PATCH')
                <div style="padding:1.5rem;">
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Status Operasional</label>
                        <select name="is_working_day" id="editHolidayWorking" class="hr-select" style="width:100%;">
                            <option value="0">Libur</option>
                            <option value="1">Hari Kerja</option>
                        </select>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Nama Event</label>
                        <input name="name" id="editHolidayName" class="hr-input" style="width:100%;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Catatan</label>
                        <textarea name="notes" id="editHolidayNotes" rows="2" class="hr-input" style="width:100%; resize:vertical;"></textarea>
                    </div>
                </div>
                <div style="padding:1rem 1.5rem; border-top:1px solid #e5e7eb; background:#f9fafb; display:flex; justify-content:flex-end; gap:0.75rem;">
                    <button type="button" class="hr-btn hr-btn-ghost" onclick="closeEditHolidayModal()">Batal</button>
                    <button type="submit" class="hr-btn hr-btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

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
        document.addEventListener('keydown', function(e) { if(e.key === 'Escape') { closeAddHolidayModal(); closeEditHolidayModal(); } });
    </script>
    @endpush
</x-hr-layout>