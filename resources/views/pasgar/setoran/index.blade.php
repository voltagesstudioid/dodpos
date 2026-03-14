<x-app-layout>
    <x-slot name="header">Setoran Harian Pasgar</x-slot>

    <div class="page-container">
        @if(session('success'))<div class="alert alert-success">✅ {{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">❌ {{ session('error') }}</div>@endif

        {{-- Summary Cards --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.5rem; font-weight:800; color:#4f46e5;">{{ $totalDeposits }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Total Setoran</div>
            </div>
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.25rem; font-weight:800; color:#10b981;">Rp {{ number_format($totalVerified, 0, ',', '.') }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Total Terverifikasi</div>
            </div>
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.5rem; font-weight:800; color:#f59e0b;">{{ $pendingCount }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Menunggu Verifikasi</div>
            </div>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">💰 Setoran Harian Pasgar</h2>
                    <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Rekap setoran uang hasil penjualan harian tim lapangan</p>
                </div>
                @can('create_pasgar_setoran')
                <a href="{{ route('pasgar.setoran.create') }}" class="btn-primary">＋ Buat Setoran</a>
                @endcan
            </div>

            {{-- Filter --}}
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input" style="width:160px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input" style="width:160px;">
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
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary btn-sm">🔍 Filter</button>
                    <a href="{{ route('pasgar.setoran.index') }}" class="btn-secondary btn-sm">Reset</a>
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. Setoran</th>
                            <th>Anggota</th>
                            <th>Tanggal</th>
                            <th style="text-align:right;">Penjualan</th>
                            <th style="text-align:right;">Penagihan</th>
                            <th style="text-align:right;">Pengeluaran</th>
                            <th style="text-align:right;">Total Setor</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($deposits as $dep)
                        <tr>
                            <td style="font-weight:600; color:#4f46e5;">{{ $dep->deposit_number }}</td>
                            <td>
                                <div style="font-weight:500;">{{ $dep->member->user->name }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">{{ $dep->member->area ?? '—' }}</div>
                            </td>
                            <td class="text-muted">{{ $dep->deposit_date->format('d M Y') }}</td>
                            <td style="text-align:right;">Rp {{ number_format($dep->sales_amount, 0, ',', '.') }}</td>
                            <td style="text-align:right;">Rp {{ number_format($dep->collection_amount, 0, ',', '.') }}</td>
                            <td style="text-align:right; color:#ef4444;">Rp {{ number_format($dep->expense_amount, 0, ',', '.') }}</td>
                            <td style="text-align:right; font-weight:700; color:#10b981;">Rp {{ number_format($dep->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="{{ $dep->status_color }}">{{ $dep->status_label }}</span>
                            </td>
                            <td>
                                <div style="display:flex; gap:0.375rem;">
                                    <a href="{{ route('pasgar.setoran.show', $dep) }}" class="btn-secondary btn-sm">👁 Detail</a>
                                    @if($dep->status === 'pending')
                                        @can('delete_pasgar_setoran')
                                        <form method="POST" action="{{ route('pasgar.setoran.destroy', $dep) }}" onsubmit="return confirm('Hapus setoran ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-danger btn-sm" style="padding:0.35rem 0.6rem; border-radius:6px; font-size:0.75rem;">🗑</button>
                                        </form>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" style="text-align:center; padding:3rem; color:#94a3b8;">
                                <div style="font-size:2rem; margin-bottom:0.5rem;">💰</div>
                                <div>Belum ada data setoran.</div>
                                @can('create_pasgar_setoran')
                                <a href="{{ route('pasgar.setoran.create') }}" class="btn-primary btn-sm" style="margin-top:0.75rem; display:inline-flex;">+ Buat Setoran</a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($deposits->hasPages())
            <div style="padding:1rem 1.5rem; border-top:1px solid #f1f5f9;">{{ $deposits->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
