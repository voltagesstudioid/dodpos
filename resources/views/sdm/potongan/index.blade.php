<x-hr-layout>
    <x-slot name="eyebrow">Manajemen Payroll</x-slot>
    <x-slot name="title">Potongan & Bonus</x-slot>
    <x-slot name="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
    </x-slot>
    <x-slot name="iconBg">bg-indigo</x-slot>
    <x-slot name="description">Kelola kasbon, denda, serta tambahan bonus khusus yang mempengaruhi penggajian.</x-slot>
    <x-slot name="actions">
        @can('create_potongan_gaji')
            <button type="button" class="hr-btn hr-btn-primary" onclick="openAddModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Data
            </button>
        @endcan
        <a href="{{ route('sdm.penggajian.index', ['month' => $month]) }}" class="hr-btn hr-btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            Penggajian
        </a>
    </x-slot>

    {{-- Stats --}}
    <div class="hr-stats" style="margin-bottom: 1.5rem;">
        <div class="hr-stat">
            <div class="hr-stat-label">Total Bonus</div>
            <div class="hr-stat-value" style="color: #16a34a;">Rp{{ number_format($bonuses->sum('amount'), 0, ',', '.') }}</div>
            <div class="hr-stat-change positive">{{ $bonuses->count() }} transaksi</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Total Potongan</div>
            <div class="hr-stat-value" style="color: #dc2626;">Rp{{ number_format($deductions->sum('amount'), 0, ',', '.') }}</div>
            <div class="hr-stat-change negative">{{ $deductions->count() }} transaksi</div>
        </div>
        <div class="hr-stat">
            <div class="hr-stat-label">Net Adjustment</div>
            <div class="hr-stat-value">Rp{{ number_format($bonuses->sum('amount') - $deductions->sum('amount'), 0, ',', '.') }}</div>
            <div class="hr-stat-change">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="hr-card" style="margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('sdm.potongan.index') }}" class="hr-filter">
            <div class="hr-filter-group">
                <label class="hr-filter-label">Periode Bulan</label>
                <input type="month" name="month" value="{{ $month }}" class="hr-input">
            </div>
            <div class="hr-filter-group">
                <label class="hr-filter-label">Filter Karyawan</label>
                <select name="user_id" class="hr-select">
                    <option value="">Semua Karyawan</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="hr-filter-group" style="flex: 0; min-width: auto;">
                <button type="submit" class="hr-btn hr-btn-primary">Filter</button>
            </div>
            <div class="hr-filter-group" style="flex: 0; min-width: auto;">
                <a href="{{ route('sdm.potongan.index') }}" class="hr-btn hr-btn-ghost">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="hr-card">
        <div class="hr-card-header">
            <h2 class="hr-card-title">Log Jurnal Finansial: {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</h2>
        </div>
        <div class="hr-table-wrapper">
            <table class="hr-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Karyawan</th>
                        <th>Keterangan</th>
                        <th style="text-align: right;">Nominal</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- BONUSES --}}
                    @foreach($bonuses as $bon)
                        <tr>
                            <td style="font-weight: 500;">{{ $bon->date->format('d M Y') }}</td>
                            <td><span class="hr-badge hr-badge-green">BONUS</span></td>
                            <td>
                                <div class="hr-user">
                                    <div class="hr-avatar sm">{{ strtoupper(substr($bon->user->name ?? '?', 0, 1)) }}</div>
                                    <div class="hr-user-name">{{ $bon->user->name ?? '-' }}</div>
                                </div>
                            </td>
                            <td style="color: #4b5563;">{{ $bon->description }}</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 600; color: #16a34a;">+Rp{{ number_format($bon->amount, 0, ',', '.') }}</td>
                            <td style="text-align: right;">
                                @can('delete_potongan_gaji')
                                    <form action="{{ route('sdm.bonus.destroy', $bon) }}" method="POST" style="margin:0; display:inline;" onsubmit="return confirm('Hapus bonus ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="hr-action" style="color: #dc2626;" title="Hapus">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach

                    {{-- DEDUCTIONS --}}
                    @foreach($deductions as $pot)
                        <tr>
                            <td style="font-weight: 500;">{{ $pot->date->format('d M Y') }}</td>
                            <td><span class="hr-badge hr-badge-red">POTONGAN</span></td>
                            <td>
                                <div class="hr-user">
                                    <div class="hr-avatar sm">{{ strtoupper(substr($pot->user->name ?? '?', 0, 1)) }}</div>
                                    <div class="hr-user-name">{{ $pot->user->name ?? '-' }}</div>
                                </div>
                            </td>
                            <td style="color: #4b5563;">{{ $pot->description }}</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 600; color: #dc2626;">−Rp{{ number_format($pot->amount, 0, ',', '.') }}</td>
                            <td style="text-align: right;">
                                @can('delete_potongan_gaji')
                                    <form action="{{ route('sdm.potongan.destroy', $pot) }}" method="POST" style="margin:0; display:inline;" onsubmit="return confirm('Hapus potongan ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="hr-action" style="color: #dc2626;" title="Hapus">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach

                    @if($deductions->isEmpty() && $bonuses->isEmpty())
                        <tr>
                            <td colspan="6">
                                <div class="hr-empty">
                                    <div class="hr-empty-icon">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                    </div>
                                    <div class="hr-empty-title">Tidak ada data</div>
                                    <div class="hr-empty-text">Belum ada data potongan atau bonus di periode ini.</div>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Add --}}
    <div id="addModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; padding:1.5rem;">
        <div style="background:#fff; border-radius:12px; width:100%; max-width:480px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #e5e7eb;">
                <h3 style="margin:0; font-size:1.125rem; font-weight:600;">Input Transaksi SDM</h3>
                <button type="button" onclick="closeAddModal()" style="background:none; border:none; cursor:pointer; padding:0.5rem; color:#6b7280;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form action="{{ route('sdm.potongan.store') }}" method="POST">
                @csrf
                <div style="padding:1.5rem;">
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Pilih Karyawan <span style="color:#dc2626;">*</span></label>
                        <select name="user_id" class="hr-select" required style="width:100%;">
                            <option value="">-- Pilih Nama Karyawan --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Jenis Transaksi <span style="color:#dc2626;">*</span></label>
                            <select name="type" class="hr-select" required style="width:100%;">
                                <option value="potongan">Denda / Potongan</option>
                                <option value="bonus">Bonus / Tambahan</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Tanggal <span style="color:#dc2626;">*</span></label>
                            <input type="date" name="date" class="hr-input" value="{{ date('Y-m-d') }}" required style="width:100%;">
                        </div>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Nominal Rupiah (Rp) <span style="color:#dc2626;">*</span></label>
                        <input type="number" name="amount" class="hr-input" placeholder="0" min="1" required style="width:100%; font-family:monospace;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:#374151; text-transform:uppercase; margin-bottom:0.375rem;">Keterangan <span style="color:#dc2626;">*</span></label>
                        <textarea name="description" rows="2" class="hr-input" placeholder="Contoh: Terlambat 3x / Bonus lembur" required style="width:100%; resize:vertical;"></textarea>
                    </div>
                </div>
                <div style="padding:1rem 1.5rem; border-top:1px solid #e5e7eb; background:#f9fafb; display:flex; justify-content:flex-end; gap:0.75rem;">
                    <button type="button" class="hr-btn hr-btn-ghost" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="hr-btn hr-btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openAddModal() { document.getElementById('addModal').style.display = 'flex'; }
        function closeAddModal() { document.getElementById('addModal').style.display = 'none'; }
        document.addEventListener('keydown', function(e) { if(e.key === 'Escape') closeAddModal(); });
    </script>
    @endpush
</x-hr-layout>