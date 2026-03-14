<x-app-layout>
    <x-slot name="header">Jadwal Kunjungan Pasgar</x-slot>

    <div class="page-container">
        @if(session('success'))<div class="alert alert-success">✅ {{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">❌ {{ session('error') }}</div>@endif

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">📅 Jadwal Kunjungan</h2>
                    <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Rencana kunjungan anggota pasgar ke pelanggan</p>
                </div>
                @can('create_pasgar_jadwal')
                <a href="{{ route('pasgar.jadwal.create') }}" class="btn-primary">＋ Buat Jadwal</a>
                @endcan
            </div>

            {{-- Filter --}}
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-input" style="width:160px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}" class="form-input" style="width:160px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Anggota</label>
                        <select name="member_id" class="form-input" style="width:180px;">
                            <option value="">Semua Anggota</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}" {{ request('member_id') == $m->id ? 'selected' : '' }}>{{ $m->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Status</label>
                        <select name="status" class="form-input" style="width:150px;">
                            <option value="">Semua Status</option>
                            <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                            <option value="visited" {{ request('status') === 'visited' ? 'selected' : '' }}>Sudah Dikunjungi</option>
                            <option value="skipped" {{ request('status') === 'skipped' ? 'selected' : '' }}>Dilewati</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary btn-sm">🔍 Filter</button>
                    <a href="{{ route('pasgar.jadwal.index') }}" class="btn-secondary btn-sm">Reset</a>
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Pelanggan</th>
                            <th>Alamat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schedules as $sch)
                        <tr>
                            <td>
                                <div style="font-weight:600;">{{ $sch->member->user->name }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">{{ $sch->member->area ?? '—' }}</div>
                            </td>
                            <td>
                                <div style="font-weight:600;">{{ $sch->customer->name }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">{{ $sch->customer->phone ?? '' }}</div>
                            </td>
                            <td class="text-muted" style="max-width:180px; white-space:normal; font-size:0.8rem;">{{ $sch->customer->address ?? '—' }}</td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($sch->scheduled_date)->format('d M Y') }}</td>
                            <td>
                                @if($sch->status === 'visited')
                                    <span class="badge-success">✅ Dikunjungi</span>
                                @elseif($sch->status === 'skipped')
                                    <span class="badge-danger">⏭ Dilewati</span>
                                @else
                                    <span class="badge-indigo">📅 Terjadwal</span>
                                @endif
                            </td>
                            <td class="text-muted" style="font-size:0.8rem; max-width:150px; white-space:normal;">{{ $sch->notes ?? '—' }}</td>
                            <td>
                                <div style="display:flex; gap:0.375rem;">
                                    @if($sch->status === 'scheduled')
                                        @can('create_pasgar_kunjungan')
                                        <a href="{{ route('pasgar.kunjungan.create', ['schedule_id' => $sch->id]) }}" class="btn-primary btn-sm">📝 Laporan</a>
                                        @endcan
                                        @can('delete_pasgar_jadwal')
                                        <form method="POST" action="{{ route('pasgar.jadwal.destroy', $sch) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-danger btn-sm" style="padding:0.35rem 0.6rem; border-radius:6px; font-size:0.75rem;">🗑</button>
                                        </form>
                                        @endcan
                                    @elseif($sch->report)
                                        <a href="{{ route('pasgar.kunjungan.index', ['schedule_id' => $sch->id]) }}" class="btn-secondary btn-sm">👁 Laporan</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:3rem; color:#94a3b8;">
                                <div style="font-size:2rem; margin-bottom:0.5rem;">📅</div>
                                <div>Tidak ada jadwal kunjungan untuk filter ini.</div>
                                @can('create_pasgar_jadwal')
                                <a href="{{ route('pasgar.jadwal.create') }}" class="btn-primary btn-sm" style="margin-top:0.75rem; display:inline-flex;">+ Buat Jadwal</a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($schedules->hasPages())
            <div style="padding:1rem 1.5rem; border-top:1px solid #f1f5f9;">{{ $schedules->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
