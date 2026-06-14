<x-app-layout>
    <x-slot name="header">Permintaan Barang</x-slot>

    <div class="rq-page">

        {{-- HEADER --}}
        <div class="rq-header">
            <div>
                <span class="rq-eyebrow">Manajemen Permintaan</span>
                <h1 class="rq-title">Permintaan Barang</h1>
                <p class="rq-sub">Kelola permintaan Purchase Order dan Transfer Cabang.</p>
            </div>
            @if($role !== 'supervisor')
            <a href="{{ route('gudang.request.create') }}" class="rq-btn rq-btn-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Buat Permintaan
            </a>
            @endif
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="rq-alert rq-alert-ok">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rq-alert rq-alert-err">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- STATS --}}
        <div class="rq-stats">
            @php
                $cur = request('status');
                $items = [
                    [null,      $totalCount,     'Total',     'linear-gradient(135deg,#6366f1,#8b5cf6)', '#fff', true],
                    ['pending',  $pendingCount,   'Menunggu',  '#fff', '#f59e0b', false],
                    ['approved', $approvedCount,  'Disetujui', '#fff', '#10b981', false],
                    ['rejected', $rejectedCount,  'Ditolak',   '#fff', '#ef4444', false],
                    ['completed',$completedCount, 'Selesai',   '#fff', '#64748b', false],
                ];
            @endphp
            @foreach($items as $i)
                @php
                    $active = $cur === $i[0] || (is_null($i[0]) && !$cur);
                    $url = route('gudang.request.index', array_merge(request()->except('status','page'), $i[0] ? ['status'=>$i[0]] : []));
                @endphp
                <a href="{{ $url }}" class="rq-stat {{ $active ? 'rq-stat-on' : '' }} {{ $i[5] ? 'rq-stat-hero' : '' }}"
                   style="--dot: {{ $i[4] }};">
                    <div class="rq-stat-n" {{ $i[5] && $active ? 'style="color:#fff"' : '' }}>{{ number_format($i[1]) }}</div>
                    <div class="rq-stat-l" {{ $i[5] && $active ? 'style="color:rgba(255,255,255,.8)"' : '' }}>{{ $i[2] }}</div>
                    @if($active && !$i[5])
                        <span class="rq-stat-dot"></span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- MAIN CARD --}}
        <div class="rq-card">

            {{-- FILTERS --}}
            <form method="GET" action="{{ route('gudang.request.index') }}" class="rq-filters">
                <div class="rq-search">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk, SKU, pemohon...">
                </div>
                <select name="type" class="rq-sel" onchange="this.form.submit()">
                    <option value="">Semua Tipe</option>
                    <option value="po" {{ request('type')==='po'?'selected':'' }}>PO Baru</option>
                    <option value="transfer" {{ request('type')==='transfer'?'selected':'' }}>Transfer</option>
                </select>
                <div class="rq-dates">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="rq-sel" title="Dari">
                    <span class="rq-date-sep">→</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="rq-sel" title="Sampai">
                </div>
                <button type="submit" class="rq-btn rq-btn-sm rq-btn-dark">Filter</button>
                @if(request()->hasAny(['q','status','type','date_from','date_to']) && (request('q') || request('status') || request('type') || request('date_from') || request('date_to')))
                    <a href="{{ route('gudang.request.index') }}" class="rq-btn rq-btn-sm rq-btn-ghost">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </a>
                @endif
            </form>

            {{-- FILTER INFO --}}
            @php $hasFilter = request()->filled('q') || request()->filled('status') || request()->filled('type') || request()->filled('date_from') || request()->filled('date_to'); @endphp
            @if($hasFilter)
                <div class="rq-filter-info">
                    <strong>{{ number_format($filteredCount) }}</strong> dari <strong>{{ number_format($totalCount) }}</strong> permintaan
                </div>
            @endif

            {{-- TABLE --}}
            <div class="rq-tbl-wrap">
                <table class="rq-tbl">
                    <thead>
                        <tr>
                            <th style="width:110px">Tanggal</th>
                            <th>Pemohon</th>
                            <th>Produk</th>
                            <th class="rq-c" style="width:75px">Qty</th>
                            <th>Tipe</th>
                            <th>Catatan</th>
                            <th style="width:95px">Status</th>
                            <th class="rq-c" style="width:55px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            {{-- Date --}}
                            <td>
                                <div class="rq-d1">{{ $req->created_at->format('d M Y') }}</div>
                                <div class="rq-d2">{{ $req->created_at->format('H:i') }}</div>
                            </td>
                            {{-- Applicant --}}
                            <td>
                                <div class="rq-user">
                                    @php
                                        $name = $req->user->name ?? '?';
                                        $initial = strtoupper(substr($name, 0, 1));
                                        $colors = ['#6366f1','#ec4899','#f59e0b','#10b981','#3b82f6','#8b5cf6'];
                                        $colorIdx = crc32($name) % count($colors);
                                        $avatarBg = $colors[$colorIdx];
                                    @endphp
                                    <span class="rq-avatar" style="background:{{ $avatarBg }}">{{ $initial }}</span>
                                    <div>
                                        <div class="rq-name">{{ $name }}</div>
                                        <div class="rq-d2">{{ strtoupper((string)($req->user->role ?? '')) }}</div>
                                    </div>
                                </div>
                            </td>
                            {{-- Product --}}
                            <td>
                                <div class="rq-name">{{ $req->product->name ?? '-' }}</div>
                                <div class="rq-d2">{{ $req->product->sku ?? '' }}</div>
                            </td>
                            {{-- Qty --}}
                            <td class="rq-c">
                                @php $u = $req->unit?->abbreviation ?? $req->unit?->name ?? $req->product?->unit?->abbreviation ?? ''; @endphp
                                <span class="rq-qty">{{ number_format((float)$req->quantity, 0) }}</span>
                                @if($u)<div class="rq-d2">{{ $u }}</div>@endif
                            </td>
                            {{-- Type --}}
                            <td>
                                @if($req->type === 'po')
                                    <span class="rq-tag rq-tag-blue">PO</span>
                                @else
                                    <span class="rq-tag rq-tag-amber">Transfer</span>
                                    <div class="rq-route">{{ $req->fromWarehouse?->name ?? 'Utama' }} → {{ $req->toWarehouse?->name ?? 'Cabang' }}</div>
                                @endif
                            </td>
                            {{-- Notes --}}
                            <td>
                                @if($req->notes)
                                    <span class="rq-note" title="{{ $req->notes }}">{{ \Illuminate\Support\Str::limit($req->notes, 30) }}</span>
                                @else
                                    <span class="rq-d2">—</span>
                                @endif
                            </td>
                            {{-- Status --}}
                            <td>
                                @php
                                    $b = match($req->status) {
                                        'pending'   => ['rq-b-warn','Menunggu'],
                                        'approved'  => ['rq-b-ok','Disetujui'],
                                        'rejected'  => ['rq-b-err','Ditolak'],
                                        'completed' => ['rq-b-muted','Selesai'],
                                        default     => ['rq-b-muted',strtoupper($req->status)],
                                    };
                                @endphp
                                <span class="rq-badge {{ $b[0] }}">{{ $b[1] }}</span>
                            </td>
                            {{-- Action --}}
                            <td class="rq-c">
                                @if($req->user_id === auth()->id() || $role === 'supervisor')
                                    <form method="POST" action="{{ route('gudang.request.destroy', $req->id) }}" onsubmit="return confirm('Hapus permintaan ini?');" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rq-del" title="Hapus">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="rq-empty">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.35;margin-bottom:.5rem"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    <div class="rq-empty-t">Belum ada permintaan</div>
                                    <div class="rq-empty-s">Buat permintaan baru untuk memulai.</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
                <div class="rq-pag">{{ $requests->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .rq-page{padding:1.5rem;max-width:1280px;margin:0 auto;font-family:'Plus Jakarta Sans',system-ui,sans-serif}

        /* Header */
        .rq-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem}
        .rq-eyebrow{font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#6366f1;margin-bottom:.2rem;display:block}
        .rq-title{font-size:1.35rem;font-weight:800;color:#0f172a;margin:0 0 .3rem}
        .rq-sub{font-size:.8rem;color:#64748b;margin:0}

        /* Alerts */
        .rq-alert{display:flex;align-items:center;gap:.6rem;padding:.75rem 1rem;border-radius:10px;margin-bottom:1.25rem;font-size:.83rem;font-weight:500}
        .rq-alert-ok{background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
        .rq-alert-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}

        /* Stats */
        .rq-stats{display:grid;grid-template-columns:repeat(5,1fr);gap:.65rem;margin-bottom:1.5rem}
        @media(max-width:992px){.rq-stats{grid-template-columns:repeat(3,1fr)}}
        @media(max-width:576px){.rq-stats{grid-template-columns:repeat(2,1fr)}}

        .rq-stat{
            position:relative;border-radius:12px;padding:1rem 1.1rem;text-decoration:none;
            transition:all .18s;cursor:pointer;border:1.5px solid #e2e8f0;background:#fff;overflow:hidden;
        }
        .rq-stat:hover{border-color:var(--dot);box-shadow:0 4px 16px rgba(0,0,0,.06);transform:translateY(-2px)}
        .rq-stat-on{border-color:var(--dot)}
        .rq-stat-on::after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px;background:var(--dot);border-radius:0 0 3px 3px}
        .rq-stat-hero{background:linear-gradient(135deg,#6366f1,#8b5cf6);border-color:transparent}
        .rq-stat-hero .rq-stat-n{color:#fff}
        .rq-stat-hero .rq-stat-l{color:rgba(255,255,255,.75)}
        .rq-stat-hero:hover{border-color:transparent;box-shadow:0 4px 20px rgba(99,102,241,.3)}
        .rq-stat-hero.rq-stat-on::after{background:#fff}
        .rq-stat-n{font-size:1.55rem;font-weight:800;color:#0f172a;line-height:1}
        .rq-stat-l{font-size:.7rem;color:#64748b;margin-top:.25rem;font-weight:600}
        .rq-stat-dot{position:absolute;top:.75rem;right:.75rem;width:8px;height:8px;border-radius:50%;background:var(--dot)}

        /* Card */
        .rq-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden}

        /* Filters */
        .rq-filters{display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;padding:.85rem 1.15rem;background:#f8fafc;border-bottom:1px solid #e2e8f0}
        .rq-search{display:flex;align-items:center;gap:7px;background:#fff;border-radius:8px;padding:.45rem .75rem;border:1px solid #e2e8f0;flex:1;min-width:180px;transition:border-color .15s}
        .rq-search:focus-within{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.08)}
        .rq-search svg{color:#94a3b8;flex-shrink:0}
        .rq-search input{border:none;background:transparent;font-size:.82rem;outline:none;width:100%;color:#0f172a}
        .rq-sel{padding:.45rem .65rem;border-radius:8px;border:1px solid #e2e8f0;font-size:.8rem;background:#fff;color:#0f172a;outline:none;transition:border-color .15s}
        .rq-sel:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.08)}
        .rq-dates{display:flex;align-items:center;gap:.3rem}
        .rq-date-sep{color:#94a3b8;font-size:.75rem}
        .rq-filter-info{padding:.5rem 1.15rem;font-size:.76rem;color:#64748b;background:#fafbfc;border-bottom:1px solid #f1f5f9}

        /* Buttons */
        .rq-btn{display:inline-flex;align-items:center;gap:.35rem;padding:.5rem .85rem;border-radius:8px;font-size:.82rem;font-weight:600;text-decoration:none;border:none;cursor:pointer;transition:all .15s;font-family:inherit}
        .rq-btn-sm{padding:.4rem .65rem;font-size:.78rem}
        .rq-btn-primary{background:#6366f1;color:#fff}
        .rq-btn-primary:hover{background:#4f46e5;transform:translateY(-1px);box-shadow:0 4px 12px rgba(99,102,241,.25)}
        .rq-btn-dark{background:#1e293b;color:#fff}
        .rq-btn-dark:hover{background:#0f172a}
        .rq-btn-ghost{background:transparent;color:#94a3b8;border:1px solid #e2e8f0}
        .rq-btn-ghost:hover{background:#f1f5f9;color:#64748b}

        /* Table */
        .rq-tbl-wrap{overflow-x:auto}
        .rq-tbl{width:100%;border-collapse:collapse}
        .rq-tbl th,.rq-tbl td{padding:.7rem .85rem;text-align:left;font-size:.82rem;border-bottom:1px solid #f1f5f9}
        .rq-tbl th{font-weight:600;color:#94a3b8;background:#fafbfc;font-size:.7rem;text-transform:uppercase;letter-spacing:.04em}
        .rq-tbl tbody tr{transition:background .1s}
        .rq-tbl tbody tr:hover{background:#f8fafc}
        .rq-c{text-align:center}
        .rq-d1{font-weight:600;color:#0f172a;font-size:.82rem}
        .rq-d2{font-size:.7rem;color:#94a3b8}

        /* User avatar */
        .rq-user{display:flex;align-items:center;gap:.55rem}
        .rq-avatar{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.7rem;font-weight:700;flex-shrink:0}
        .rq-name{font-weight:600;color:#0f172a;font-size:.82rem}

        /* Qty */
        .rq-qty{font-weight:800;font-size:1rem;color:#0f172a}

        /* Tags */
        .rq-tag{display:inline-block;padding:.15rem .45rem;border-radius:5px;font-size:.65rem;font-weight:700;letter-spacing:.02em}
        .rq-tag-blue{background:#dbeafe;color:#1e40af}
        .rq-tag-amber{background:#fef3c7;color:#92400e}
        .rq-route{font-size:.68rem;color:#94a3b8;margin-top:2px}
        .rq-note{font-size:.8rem;color:#475569;font-style:italic}

        /* Badges */
        .rq-badge{display:inline-block;padding:.2rem .5rem;border-radius:6px;font-size:.7rem;font-weight:700}
        .rq-b-warn{background:#fef3c7;color:#92400e}
        .rq-b-ok{background:#d1fae5;color:#065f46}
        .rq-b-err{background:#fee2e2;color:#991b1b}
        .rq-b-muted{background:#f1f5f9;color:#64748b}

        /* Delete */
        .rq-del{width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;border:none;cursor:pointer;background:transparent;color:#cbd5e1;transition:all .15s}
        .rq-del:hover{background:#fee2e2;color:#ef4444}

        /* Empty */
        .rq-empty{text-align:center;padding:3rem 1rem;color:#94a3b8}
        .rq-empty-t{font-size:.9rem;font-weight:700;color:#475569;margin-bottom:.2rem}
        .rq-empty-s{font-size:.8rem}

        /* Pagination */
        .rq-pag{padding:.75rem 1.15rem;border-top:1px solid #f1f5f9}

        /* Responsive */
        @media(max-width:768px){
            .rq-page{padding:1rem}
            .rq-header{flex-direction:column}
            .rq-filters{flex-direction:column;align-items:stretch}
            .rq-search{min-width:0}
            .rq-dates{flex-wrap:wrap}
            .rq-tbl{font-size:.76rem}
            .rq-tbl th,.rq-tbl td{padding:.5rem .4rem}
            .rq-avatar{width:24px;height:24px;font-size:.6rem;border-radius:6px}
        }
    </style>
    @endpush
</x-app-layout>
