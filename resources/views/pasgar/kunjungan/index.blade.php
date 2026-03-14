<x-app-layout>
    <x-slot name="header">Laporan Kunjungan Pasgar</x-slot>

    <div class="page-container">

        {{-- Summary Cards --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:1rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.5rem; font-weight:800; color:#4f46e5;">{{ $totalVisits }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Total Kunjungan</div>
            </div>
        <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.5rem; font-weight:800; color:#10b981;">{{ $totalWithOrder }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Berhasil Order</div>
            </div>
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.5rem; font-weight:800; color:#f59e0b;">{{ $totalNoOrder }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Tidak Order</div>
            </div>
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.5rem; font-weight:800; color:#ef4444;">{{ $totalClosed }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Tutup / Tidak Ditemukan</div>
            </div>
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.1rem; font-weight:800; color:#10b981;">Rp {{ number_format($totalOrderAmount, 0, ',', '.') }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Total Nilai Order</div>
            </div>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">📋 Laporan Kunjungan</h2>
                    <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Rekap hasil kunjungan tim pasgar ke pelanggan</p>
                </div>
                @can('create_pasgar_kunjungan')
                <a href="{{ route('pasgar.kunjungan.create') }}" class="btn-primary">＋ Tambah Laporan</a>
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
                        <select name="status" class="form-input" style="width:160px;">
                            <option value="">Semua Status</option>
                            <option value="order" {{ request('status') === 'order' ? 'selected' : '' }}>Ada Order</option>
                            <option value="no_order" {{ request('status') === 'no_order' ? 'selected' : '' }}>Tidak Order</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Tutup</option>
                            <option value="not_found" {{ request('status') === 'not_found' ? 'selected' : '' }}>Tidak Ditemukan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary btn-sm">🔍 Filter</button>
                    <a href="{{ route('pasgar.kunjungan.index') }}" class="btn-secondary btn-sm">Reset</a>
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Hasil</th>
                            <th style="text-align:right;">Nilai Order</th>
                            <th style="text-align:right;">Penagihan</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $rep)
                        <tr>
                            <td>
                                <div style="font-weight:600;">{{ $rep->member->user->name }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">{{ $rep->member->area ?? '—' }}</div>
                            </td>
                            <td>
                                <div style="font-weight:500;">{{ $rep->customer->name }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">{{ $rep->customer->phone ?? '' }}</div>
                            </td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($rep->visit_date)->format('d M Y') }}</td>
                            <td>
                                @php
                                    $statusMap = [
                                        'order'     => ['label' => '✅ Ada Order',       'class' => 'badge-success'],
                                        'no_order'  => ['label' => '⚪ Tidak Order',     'class' => 'badge-indigo'],
                                        'closed'    => ['label' => '🔒 Tutup',           'class' => 'badge-danger'],
                                        'not_found' => ['label' => '❓ Tidak Ditemukan', 'class' => 'badge-danger'],
                                    ];
                                    $s = $statusMap[$rep->status] ?? ['label' => $rep->status, 'class' => 'badge-indigo'];
                                @endphp
                                <span class="{{ $s['class'] }}">{{ $s['label'] }}</span>
                            </td>
                            <td class="text-right" style="font-weight:600;">
                                <span class="{{ ($rep->order_amount ?? 0) > 0 ? 'text-emerald' : 'text-muted' }}">
                                    Rp {{ number_format($rep->order_amount ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-right" style="font-weight:600;">
                                <span class="{{ ($rep->collection_amount ?? 0) > 0 ? 'text-indigo' : 'text-muted' }}">
                                    Rp {{ number_format($rep->collection_amount ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-muted" style="font-size:0.8rem; max-width:160px; white-space:normal;">{{ $rep->notes ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:3rem; color:#94a3b8;">
                                <div style="font-size:2rem; margin-bottom:0.5rem;">📋</div>
                                <div>Tidak ada laporan kunjungan untuk filter ini.</div>
                                @can('create_pasgar_kunjungan')
                                <a href="{{ route('pasgar.kunjungan.create') }}" class="btn-primary btn-sm" style="margin-top:0.75rem; display:inline-flex;">+ Tambah Laporan</a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reports->hasPages())
            <div style="padding:1rem 1.5rem; border-top:1px solid #f1f5f9;">{{ $reports->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
