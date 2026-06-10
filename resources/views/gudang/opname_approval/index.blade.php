<x-app-layout>
    <x-slot name="header">Approval Opname</x-slot>
    @push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --oa-primary: #10b981; --oa-primary-bg: #ecfdf5; --oa-primary-text: #166534; --oa-primary-border: #a7f3d0;
            --oa-info: #0ea5e9; --oa-info-bg: #e0f2fe; --oa-info-text: #0369a1;
            --oa-danger: #ef4444; --oa-danger-bg: #fef2f2; --oa-danger-text: #991b1b; --oa-danger-border: #fecaca;
            --oa-warning: #f59e0b; --oa-warning-bg: #fffbeb; --oa-warning-text: #b45309; --oa-warning-border: #fde68a;
            --oa-bg: #f8fafc; --oa-surface: #ffffff; --oa-border: #e2e8f0; --oa-border-light: #f1f5f9;
            --oa-text: #0f172a; --oa-text-secondary: #64748b; --oa-text-muted: #94a3b8;
            --oa-radius: 10px; --oa-radius-lg: 14px;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        .oa-wrap { max-width: 1280px; margin: 0 auto; padding: 1.5rem 1rem; }

        /* Header */
        .oa-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .oa-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--oa-primary); margin-bottom: 0.25rem; }
        .oa-title { font-size: 1.5rem; font-weight: 800; color: var(--oa-text); margin: 0; display: flex; align-items: center; gap: 0.625rem; }
        .oa-title-icon { width: 36px; height: 36px; background: var(--oa-primary-bg); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--oa-primary); }
        .oa-subtitle { font-size: 0.85rem; color: var(--oa-text-secondary); margin: 0.3rem 0 0; line-height: 1.4; max-width: 560px; }

        /* Alerts */
        .oa-alert { display: flex; align-items: center; gap: 0.625rem; padding: 0.875rem 1.125rem; border-radius: var(--oa-radius); margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 500; border: 1px solid; }
        .oa-alert-success { background: var(--oa-primary-bg); color: var(--oa-primary-text); border-color: var(--oa-primary-border); }
        .oa-alert-danger { background: var(--oa-danger-bg); color: var(--oa-danger-text); border-color: var(--oa-danger-border); }

        /* Card */
        .oa-card { background: var(--oa-surface); border: 1px solid var(--oa-border); border-radius: var(--oa-radius-lg); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .oa-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid var(--oa-border-light); }

        /* Search */
        .oa-search-row { display: flex; gap: 0.75rem; align-items: center; }
        .oa-search { display: flex; align-items: center; gap: 0.5rem; background: var(--oa-bg); border: 1px solid var(--oa-border); border-radius: 8px; padding: 0 0.75rem; flex: 1; transition: border-color 0.2s; }
        .oa-search:focus-within { border-color: var(--oa-primary); background: #fff; }
        .oa-search svg { color: var(--oa-text-muted); flex-shrink: 0; }
        .oa-search input { border: none; background: transparent; padding: 0.5rem 0; font-size: 0.85rem; font-family: inherit; color: var(--oa-text); outline: none; width: 100%; }
        .oa-btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.35rem; padding: 0.5rem 0.875rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600; font-family: inherit; cursor: pointer; border: 1px solid transparent; text-decoration: none; transition: all 0.2s; height: 38px; white-space: nowrap; }
        .oa-btn-dark { background: var(--oa-text); color: #fff; }
        .oa-btn-dark:hover { background: #000; transform: translateY(-1px); }
        .oa-btn-ghost { border-color: var(--oa-danger-border); color: var(--oa-danger-text); background: transparent; }
        .oa-btn-ghost:hover { background: var(--oa-danger-bg); }
        .oa-btn-outline { border-color: var(--oa-border); color: var(--oa-text-secondary); background: var(--oa-surface); }
        .oa-btn-outline:hover { border-color: var(--oa-text-muted); color: var(--oa-text); }

        /* Table */
        .oa-twrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .oa-tbl { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 850px; }
        .oa-tbl thead th { font-size: 0.6875rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--oa-text-secondary); padding: 0.75rem 1.125rem; border-bottom: 1px solid var(--oa-border); background: var(--oa-bg); white-space: nowrap; text-align: left; }
        .oa-tbl thead th.r, .oa-tbl tbody td.r { text-align: right; }
        .oa-tbl tbody tr { transition: background 0.15s; }
        .oa-tbl tbody tr:hover { background: #fafbfc; }
        .oa-tbl tbody td { padding: 0.875rem 1.125rem; font-size: 0.8125rem; vertical-align: middle; border-bottom: 1px solid var(--oa-border-light); }
        .oa-tbl tbody tr:last-child td { border-bottom: none; }

        /* Cells */
        .oa-date { font-weight: 600; color: var(--oa-text); font-size: 0.8125rem; }
        .oa-date-sub { font-size: 0.72rem; color: var(--oa-text-muted); margin-top: 2px; }
        .oa-wh { font-weight: 700; color: var(--oa-text); font-size: 0.8125rem; }
        .oa-wh-id { font-size: 0.6875rem; color: var(--oa-text-muted); font-family: monospace; margin-top: 2px; }
        .oa-user { font-weight: 600; color: var(--oa-text); font-size: 0.8125rem; }
        .oa-user-role { font-size: 0.6875rem; color: var(--oa-text-muted); margin-top: 2px; letter-spacing: 0.02em; }
        .oa-ref { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 6px; background: var(--oa-warning-bg); color: var(--oa-warning-text); border: 1px solid var(--oa-warning-border); font-family: monospace; font-size: 0.75rem; font-weight: 700; }

        /* Items count badge */
        .oa-items-count { display: inline-flex; align-items: center; gap: 4px; padding: 0.15rem 0.5rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; background: var(--oa-info-bg); color: var(--oa-info-text); border: 1px solid #bae6fd; }

        /* Action */
        .oa-act-btn { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.4rem 0.875rem; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; }
        .oa-act-btn.review { background: var(--oa-info); color: #fff; }
        .oa-act-btn.review:hover { background: #0284c7; }

        /* Empty */
        .oa-empty { text-align: center; padding: 3.5rem 1.5rem; }
        .oa-empty-ico { width: 52px; height: 52px; border-radius: 50%; background: var(--oa-primary-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--oa-primary); }
        .oa-empty h6 { font-size: 0.95rem; font-weight: 700; color: var(--oa-text); margin: 0 0 0.25rem; }
        .oa-empty p { font-size: 0.8125rem; color: var(--oa-text-secondary); margin: 0 auto; max-width: 380px; line-height: 1.5; }

        /* Pagination */
        .oa-pag { padding: 0.875rem 1.25rem; border-top: 1px solid var(--oa-border-light); background: var(--oa-surface); display: flex; align-items: center; justify-content: space-between; }
        .oa-pag-info { font-size: 0.75rem; color: var(--oa-text-muted); }

        /* Responsive */
        @media (max-width: 768px) {
            .oa-header { flex-direction: column; align-items: flex-start; }
            .oa-search-row { flex-direction: column; }
            .oa-search { width: 100%; }
            .oa-pag { flex-direction: column; gap: 0.5rem; text-align: center; }
        }
    </style>
    @endpush

    <div class="oa-wrap">
        {{-- Header --}}
        <div class="oa-header">
            <div>
                <div class="oa-eyebrow">Tugas Supervisor</div>
                <h1 class="oa-title">
                    <span class="oa-title-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </span>
                    Approval Opname Stok
                </h1>
                <p class="oa-subtitle">Daftar sesi opname yang sudah disubmit oleh Admin Gudang dan menunggu persetujuan Anda.</p>
            </div>
            <a href="{{ route('gudang.opname_sessions.index') }}" class="oa-btn oa-btn-outline">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                Lihat Semua Sesi
            </a>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
        <div class="oa-alert oa-alert-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="oa-alert oa-alert-danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Main Card --}}
        <div class="oa-card">
            <div class="oa-card-head">
                <form method="GET" action="{{ route('gudang.opname_approval.index') }}" class="oa-search-row">
                    <div class="oa-search">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari referensi / gudang / pembuat...">
                    </div>
                    <button type="submit" class="oa-btn oa-btn-dark">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Cari
                    </button>
                    @if(request('q'))
                    <a href="{{ route('gudang.opname_approval.index') }}" class="oa-btn oa-btn-ghost">Reset</a>
                    @endif
                </form>
            </div>

            <div class="oa-twrap">
                <table class="oa-tbl">
                    <thead>
                        <tr>
                            <th>Waktu Submit</th>
                            <th>Gudang / Lokasi</th>
                            <th>Dibuat Oleh</th>
                            <th>No. Referensi</th>
                            <th class="c">Items</th>
                            <th class="r" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $s)
                        <tr>
                            <td>
                                <div class="oa-date">{{ optional($s->submitted_at)->format('d M Y') ?: $s->created_at->format('d M Y') }}</div>
                                <div class="oa-date-sub">{{ optional($s->submitted_at)->format('H:i') ?: $s->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="oa-wh">{{ $s->warehouse?->name ?? '-' }}</div>
                                <div class="oa-wh-id">ID: {{ $s->warehouse_id }}</div>
                            </td>
                            <td>
                                <div class="oa-user">{{ $s->creator?->name ?? '-' }}</div>
                                <div class="oa-user-role">{{ strtoupper((string) ($s->creator?->role ?? '-')) }}</div>
                            </td>
                            <td>
                                @if($s->reference_number)
                                <span class="oa-ref">{{ $s->reference_number }}</span>
                                @else
                                <span style="color:var(--oa-text-muted);">-</span>
                                @endif
                            </td>
                            <td class="c">
                                <span class="oa-items-count">{{ $s->items_count ?? $s->items->count() }} item</span>
                            </td>
                            <td class="r">
                                <a href="{{ route('gudang.opname_approval.show', $s) }}" class="oa-act-btn review">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="oa-empty">
                                    <div class="oa-empty-ico">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    </div>
                                    <h6>Tidak ada tugas approval</h6>
                                    <p>Saat ini tidak ada sesi opname yang menunggu untuk di-review.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sessions->hasPages())
            <div class="oa-pag">
                <div class="oa-pag-info">
                    Menampilkan {{ $sessions->firstItem() ?? 0 }}-{{ $sessions->lastItem() ?? 0 }} dari {{ $sessions->total() }} sesi
                </div>
                <div>{{ $sessions->withQueryString()->links() }}</div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
