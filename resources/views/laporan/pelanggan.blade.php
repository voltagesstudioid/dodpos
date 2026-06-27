<x-app-layout>
<x-slot name="header">Laporan Pelanggan</x-slot>

<style>
/* ── LAYOUT ──────────────────────────────────────────── */
.plg-wrap {
    width: 100%;
    max-width: 1600px;
    padding: 1.5rem;
    animation: fadeSlideIn .3s ease both;
}

/* ── PAGE HEADER ────────────────────────────────────── */
.plg-hd {
    display: flex; justify-content: space-between; align-items: flex-start;
    gap: 1.25rem; margin-bottom: 1.5rem; flex-wrap: wrap;
}
.plg-hd-l { display: flex; gap: .875rem; align-items: center; }
.plg-hd-icon {
    width: 48px; height: 48px; border-radius: 14px;
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    display: flex; align-items: center; justify-content: center;
    color: #fff; flex-shrink: 0;
    box-shadow: 0 6px 18px rgba(79,70,229,.28);
}
.plg-hd-title {
    font-size: 1.5rem; font-weight: 800; color: #0f172a;
    letter-spacing: -.03em; line-height: 1.15; margin: 0;
}
.plg-hd-sub { font-size: .8125rem; color: #64748b; margin: .15rem 0 0; }
.plg-hd-actions { display: flex; gap: .5rem; flex-wrap: wrap; align-items: center; }

/* ── ACTION BUTTONS ─────────────────────────────────── */
.plg-btn {
    display: inline-flex; align-items: center; gap: .375rem;
    padding: .45rem .85rem; border-radius: 8px;
    font-size: .75rem; font-weight: 700; cursor: pointer;
    text-decoration: none; border: 1px solid; transition: all .18s;
    font-family: inherit; white-space: nowrap; line-height: 1.5;
}
.plg-btn:active { transform: scale(.97); }
.plg-btn-ghost   { background: #fff; border-color: #e2e8f0; color: #475569; }
.plg-btn-ghost:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }
.plg-btn-primary {
    background: #4f46e5; border-color: transparent; color: #fff;
    box-shadow: 0 3px 10px rgba(79,70,229,.25);
}
.plg-btn-primary:hover { background: #4338ca; transform: translateY(-1px); box-shadow: 0 5px 16px rgba(79,70,229,.35); }
.plg-btn-success { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
.plg-btn-success:hover { background: #d1fae5; }

/* ── KPI GRID ────────────────────────────────────────── */
.plg-kpi {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 1100px) { .plg-kpi { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .plg-kpi { grid-template-columns: 1fr; } }

.plg-kpi-card {
    background: #fff; border-radius: var(--radius, 12px);
    border: 1px solid #e2e8f0; padding: 1.125rem 1.25rem;
    display: flex; flex-direction: column; gap: .5rem;
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,.06));
    transition: all .25s;
}
.plg-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg, 0 10px 30px -3px rgba(0,0,0,.1));
}
.plg-kpi-top {
    display: flex; align-items: center; justify-content: space-between;
}
.plg-kpi-ico {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.plg-kpi-badge {
    font-size: .6rem; font-weight: 800; padding: .125rem .5rem;
    border-radius: 5px; letter-spacing: .02em;
}
.plg-kpi-label {
    font-size: .65rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: #94a3b8;
}
.plg-kpi-val {
    font-size: 1.75rem; font-weight: 900; line-height: 1;
    letter-spacing: -.03em;
    animation: countUp .4s ease both;
}
.plg-kpi-foot {
    font-size: .675rem; color: #94a3b8;
    display: flex; align-items: center; gap: .3rem;
}

.kpi-blue  .plg-kpi-ico { background: #eef2ff; color: #4f46e5; }
.kpi-blue  .plg-kpi-val { color: #4f46e5; }
.kpi-blue  .plg-kpi-badge { background: #eef2ff; color: #4338ca; }

.kpi-rose  .plg-kpi-ico { background: #fff1f2; color: #e11d48; }
.kpi-rose  .plg-kpi-val { color: #e11d48; }
.kpi-rose  .plg-kpi-badge { background: #fee2e2; color: #be123c; }

.kpi-amber .plg-kpi-ico { background: #fffbeb; color: #d97706; }
.kpi-amber .plg-kpi-val { color: #d97706; }
.kpi-amber .plg-kpi-badge { background: #fef3c7; color: #92400e; }

.kpi-green .plg-kpi-ico { background: #ecfdf5; color: #059669; }
.kpi-green .plg-kpi-val { color: #059669; }
.kpi-green .plg-kpi-badge { background: #ecfdf5; color: #047857; }

/* ── TABLE CARD ──────────────────────────────────────── */
.plg-card {
    background: #fff; border-radius: var(--radius, 12px);
    border: 1px solid #e2e8f0; overflow: hidden;
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,.06));
}
.plg-card-hd {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex; justify-content: space-between; align-items: center;
    flex-wrap: wrap; gap: .75rem;
}
.plg-card-title {
    font-size: .875rem; font-weight: 700; color: #0f172a; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}

/* ── SEARCH ──────────────────────────────────────────── */
.plg-search {
    display: flex; gap: .5rem; align-items: center;
}
.plg-input-wrap { position: relative; }
.plg-input-wrap svg {
    position: absolute; left: .75rem; top: 50%;
    transform: translateY(-50%); color: #94a3b8; pointer-events: none;
}
.plg-si {
    width: 260px; padding: .5rem .75rem .5rem 2.2rem;
    border-radius: 8px; border: 1.5px solid #e2e8f0;
    background: #fff; color: #1e293b; font-size: .8125rem;
    font-family: inherit; outline: none; transition: all .2s;
}
.plg-si:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }

/* ── CUSTOMER TABLE ──────────────────────────────────── */
.plg-name-wrap { display: flex; flex-direction: column; gap: .2rem; }
.plg-name { font-weight: 600; color: #0f172a; font-size: .875rem; }
.plg-inactive-badge {
    display: inline-block; font-size: .625rem; font-weight: 700;
    background: #fee2e2; color: #991b1b;
    padding: .1rem .4rem; border-radius: 4px; width: fit-content;
}
.plg-contact { font-size: .75rem; color: #64748b; }

/* Debt utilization bar */
.plg-debt-bar-wrap { margin-top: .35rem; }
.plg-debt-bar-track {
    height: 4px; border-radius: 99px; background: #e2e8f0; overflow: hidden;
}
.plg-debt-bar-fill {
    height: 100%; border-radius: 99px; transition: width .4s;
}

/* ── PAGINATION ──────────────────────────────────────── */
.plg-pag { padding: .875rem 1.25rem; border-top: 1px solid #f1f5f9; }

/* ── EMPTY STATE ─────────────────────────────────────── */
.plg-empty {
    text-align: center; padding: 3rem 1.5rem;
}
.plg-empty-ico {
    width: 56px; height: 56px; border-radius: 16px;
    background: #f1f5f9; display: flex; align-items: center;
    justify-content: center; margin: 0 auto 1rem; color: #94a3b8;
}
.plg-empty h3 { font-size: .9375rem; font-weight: 700; color: #1e293b; margin: 0 0 .3rem; }
.plg-empty p  { font-size: .8125rem; color: #64748b; margin: 0; }

/* ── PRINT ──────────────────────────────────────────── */
@media print {
    .plg-hd-actions, .sidebar, .sidebar-overlay, .topbar, .plg-search form { display: none !important; }
    .page-content { padding: 0 !important; }
    body { background: #fff !important; }
    .plg-kpi-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
}

@keyframes countUp { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

@media (max-width: 640px) {
    .plg-wrap { padding: .75rem; }
    .plg-hd-title { font-size: 1.25rem; }
    .plg-hd-icon { width: 40px; height: 40px; }
    .plg-si { width: 100%; }
    .plg-card-hd { flex-direction: column; align-items: stretch; }
    .plg-search { flex-wrap: wrap; }
}
</style>

<div class="plg-wrap">

    @php
        $isPrint = (bool) ($isPrint ?? request()->boolean('print'));
    @endphp

    {{-- PRINT PREVIEW TOOLBAR --}}
    @if($isPrint && request()->boolean('preview'))
        @include('print.partials.preview-toolbar', ['title' => 'Laporan Pelanggan'])
    @endif

    {{-- PRINT HEADER --}}
    @if($isPrint)
        <div style="margin-bottom:1rem; border-bottom:2px solid #e2e8f0; padding-bottom:.75rem;">
            <div style="font-size:1.25rem; font-weight:900; color:#0f172a;">Laporan Pelanggan</div>
            <div style="font-size:.75rem; color:#475569; margin-top:.25rem;">
                @if($search) Pencarian: <strong>{{ $search }}</strong> &bull; @endif
                Dicetak: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
            </div>
        </div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="plg-hd">
        <div class="plg-hd-l">
            <div class="plg-hd-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div>
                <h1 class="plg-hd-title">Laporan Pelanggan</h1>
                <p class="plg-hd-sub">Ringkasan data pelanggan aktif dan status piutang mereka.</p>
            </div>
        </div>

        @if(!$isPrint)
            <div class="plg-hd-actions">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv', 'page' => null]) }}" class="plg-btn plg-btn-ghost">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    CSV
                </a>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'xlsx', 'page' => null]) }}" class="plg-btn plg-btn-success">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Excel
                </a>
                <a href="{{ request()->fullUrlWithQuery(['print' => 1, 'preview' => 1, 'page' => null]) }}" target="_blank" class="plg-btn plg-btn-ghost">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Cetak
                </a>
            </div>
        @endif
    </div>

    {{-- KPI CARDS (4) --}}
    <div class="plg-kpi">
        <div class="plg-kpi-card kpi-blue" style="animation-delay:0s">
            <div class="plg-kpi-top">
                <div class="plg-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    </svg>
                </div>
                <span class="plg-kpi-badge">Total</span>
            </div>
            <div class="plg-kpi-label">Total Pelanggan</div>
            <div class="plg-kpi-val">{{ number_format($totalCustomers ?? 0) }}</div>
            <div class="plg-kpi-foot">
                {{ number_format($activeCustomers ?? 0) }} aktif
                @php $inactivePct = $totalCustomers > 0 ? round(($totalCustomers - $activeCustomers) / $totalCustomers * 100) : 0; @endphp
                &bull; {{ $inactivePct }}% nonaktif
            </div>
        </div>

        <div class="plg-kpi-card kpi-rose" style="animation-delay:.05s">
            <div class="plg-kpi-top">
                <div class="plg-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <span class="plg-kpi-badge">Piutang</span>
            </div>
            <div class="plg-kpi-label">Total Piutang Berjalan</div>
            <div class="plg-kpi-val">Rp {{ number_format($totalDebt ?? 0, 0, ',', '.') }}</div>
            <div class="plg-kpi-foot">Piutang yang belum lunas</div>
        </div>

        <div class="plg-kpi-card kpi-amber" style="animation-delay:.1s">
            <div class="plg-kpi-top">
                <div class="plg-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/>
                    </svg>
                </div>
                <span class="plg-kpi-badge">Berutang</span>
            </div>
            <div class="plg-kpi-label">Memiliki Piutang Aktif</div>
            <div class="plg-kpi-val">{{ $customersWithDebt ?? 0 }}</div>
            <div class="plg-kpi-foot">
                @php $debtorPct = $totalCustomers > 0 ? round(($customersWithDebt ?? 0) / $totalCustomers * 100) : 0; @endphp
                {{ $debtorPct }}% dari total pelanggan
            </div>
        </div>

        <div class="plg-kpi-card kpi-green" style="animation-delay:.15s">
            <div class="plg-kpi-top">
                <div class="plg-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
                    </svg>
                </div>
                <span class="plg-kpi-badge">Rata-rata</span>
            </div>
            <div class="plg-kpi-label">Rata-rata Piutang</div>
            <div class="plg-kpi-val">Rp {{ number_format($avgDebt ?? 0, 0, ',', '.') }}</div>
            <div class="plg-kpi-foot">Per pelanggan yang memiliki piutang</div>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="plg-card">
        <div class="plg-card-hd">
            <h3 class="plg-card-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Daftar Pelanggan
            </h3>
            <div class="plg-search">
                <form action="{{ route('laporan.pelanggan') }}" method="GET" style="display:contents;">
                    @if($isPrint)
                        <input type="hidden" name="print" value="1">
                    @endif
                    @if(request()->has('preview'))
                        <input type="hidden" name="preview" value="1">
                    @endif
                    <div class="plg-input-wrap">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="search" value="{{ $search ?? '' }}" class="plg-si" placeholder="Cari nama atau telepon...">
                    </div>
                    <button type="submit" class="plg-btn plg-btn-primary">Cari</button>
                    @if($search)
                        <a href="{{ route('laporan.pelanggan') }}" class="plg-btn plg-btn-ghost">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:40px;">No</th>
                        <th>Nama Pelanggan</th>
                        <th>Kontak</th>
                        <th style="text-align:right;">Limit (Rp)</th>
                        <th style="text-align:right;">Sisa Limit (Rp)</th>
                        <th style="text-align:right;">Piutang (Rp)</th>
                        @if(!$isPrint)
                            <th style="text-align:center;width:70px;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $i => $customer)
                        @php
                            $utilPct = $customer->credit_limit > 0
                                ? min(100, round($customer->current_debt / $customer->credit_limit * 100))
                                : 0;
                            $utilColor = $utilPct >= 90 ? '#e11d48' : ($utilPct >= 70 ? '#f59e0b' : '#059669');
                        @endphp
                        <tr>
                            <td class="text-muted">{{ $customers->firstItem() + $i }}</td>
                            <td>
                                <div class="plg-name-wrap">
                                    <span class="plg-name">{{ $customer->name }}</span>
                                    @if(!$customer->is_active)
                                        <span class="plg-inactive-badge">Nonaktif</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="plg-contact">{{ $customer->phone ?: '—' }}</div>
                                @if($customer->email)
                                    <div class="plg-contact" style="font-size:.6875rem;">{{ $customer->email }}</div>
                                @endif
                            </td>
                            <td style="text-align:right; color:#334155;">{{ number_format($customer->credit_limit ?? 0, 0, ',', '.') }}</td>
                            <td style="text-align:right; color:#4f46e5; font-weight:600;">{{ number_format($customer->remaining_credit_limit ?? 0, 0, ',', '.') }}</td>
                            <td style="text-align:right; font-weight:700; color:{{ $customer->current_debt > 0 ? '#dc2626' : '#15803d' }};">
                                {{ number_format($customer->current_debt ?? 0, 0, ',', '.') }}
                                @if($customer->credit_limit > 0)
                                    <div class="plg-debt-bar-wrap">
                                        <div class="plg-debt-bar-track">
                                            <div class="plg-debt-bar-fill" style="width:{{ $utilPct }}%; background:{{ $utilColor }};"></div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            @if(!$isPrint)
                                <td style="text-align:center;">
                                    <a href="{{ route('pelanggan.show', $customer->id) }}" class="plg-btn plg-btn-ghost" style="font-size:.675rem;padding:.25rem .55rem;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        Detail
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isPrint ? 6 : 7 }}">
                                <div class="plg-empty">
                                    <div class="plg-empty-ico">
                                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    </div>
                                    @if($search)
                                        <h3>Tidak ada pelanggan ditemukan</h3>
                                        <p>Pencarian "<strong>{{ $search }}</strong>" tidak menghasilkan data.</p>
                                    @else
                                        <h3>Belum ada data pelanggan</h3>
                                        <p>Tambahkan pelanggan terlebih dahulu untuk melihat laporan.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(!$isPrint && $customers->hasPages())
            <div class="plg-pag">{{ $customers->links() }}</div>
        @endif
    </div>

</div>

@if($isPrint && !request()->boolean('preview'))
    <script>window.addEventListener('load', function(){ window.print(); });</script>
@endif

</x-app-layout>