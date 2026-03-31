<x-app-layout>
    <x-slot name="header">QC Follow-up PO</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── NOTIFIKASI ALERTS ─── --}}
            @if(session('success'))
                <div class="tr-alert tr-alert-success animate-fade-in-up">
                    <div class="tr-alert-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <div class="tr-alert-text">{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="tr-alert tr-alert-danger animate-fade-in-up">
                    <div class="tr-alert-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </div>
                    <div class="tr-alert-text">{{ session('error') }}</div>
                </div>
            @endif

            {{-- ─── PREMIUM HEADER ─── --}}
            <div class="tr-header animate-fade-in">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Quality Control</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-amber">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        </div>
                        Tindak Lanjut QC (PO)
                    </h1>
                    <p class="tr-subtitle">Daftar penerimaan PO yang memiliki selisih / reject (QC tidak OK) dan memerlukan tindak lanjut.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('pembelian.order') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        Ke Daftar PO
                    </a>
                </div>
            </div>

            {{-- ─── FILTER CONTROL BAR ─── --}}
            <div class="tr-card tr-filter-card animate-fade-in-up" style="animation-delay: 0.1s;">
                <form method="GET" action="{{ route('pembelian.receipts_followup.index') }}" class="tr-filter-grid">
                    <div class="tr-form-group flex-grow">
                        <label class="tr-label">Pencarian Cepat</label>
                        <input type="text" name="q" value="{{ request('q') }}" class="tr-input" placeholder="Cari No. PO, Supplier, Gudang, atau Nama Penerima...">
                    </div>
                    <div class="tr-form-group select-grp">
                        <label class="tr-label">Status Tindak Lanjut</label>
                        <div class="tr-select-wrapper">
                            <select name="status" class="tr-select">
                                <option value="open" {{ $status === 'open' ? 'selected' : '' }}>🟢 Terbuka (Open)</option>
                                <option value="resolved" {{ $status === 'resolved' ? 'selected' : '' }}>✅ Selesai (Resolved)</option>
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="tr-filter-actions">
                        <button type="submit" class="tr-btn tr-btn-dark">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            Terapkan
                        </button>
                        @if(request()->filled('q') || request()->filled('status') && request('status') !== 'open')
                            <a href="{{ route('pembelian.receipts_followup.index') }}" class="tr-btn tr-btn-light" title="Reset Filter">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ─── DATA TABLE ─── --}}
            <div class="tr-card animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Waktu Terima</th>
                                <th>Ref. PO</th>
                                <th>Data Supplier</th>
                                <th>Gudang & Penerima</th>
                                <th class="c">Status Terima</th>
                                <th class="c">Follow-up</th>
                                <th class="c">Draft Retur</th>
                                <th class="c">Reorder PO</th>
                                <th class="r">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receipts as $r)
                                @php
                                    // Logic status penerimaan
                                    $st = $r->status === 'partial' 
                                        ? ['bg' => 'badge-danger', 'label' => 'TERIMA SEBAGIAN'] 
                                        : ['bg' => 'badge-success', 'label' => 'SELESAI DITERIMA'];
                                    
                                    // Logic status follow-up
                                    $fs = $r->followup_status ?: 'open';
                                    $fsBadge = $fs === 'resolved' 
                                        ? ['bg' => 'badge-success', 'label' => 'Selesai (Resolved)'] 
                                        : ['bg' => 'badge-amber', 'label' => 'Perlu Tindakan (Open)'];
                                @endphp
                                <tr>
                                    <td>
                                        <div class="tr-date-main">{{ $r->created_at->format('d/m/Y') }}</div>
                                        <div class="tr-date-sub">{{ $r->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td>
                                        <a href="{{ route('pembelian.order.show', $r->purchaseOrder) }}" class="tr-link-bold tr-font-mono text-indigo">
                                            {{ $r->purchaseOrder?->po_number ?? '—' }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="tr-font-bold text-main">{{ $r->purchaseOrder?->supplier?->name ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <div class="tr-font-bold text-main">{{ $r->warehouse?->name ?? '—' }}</div>
                                        <div class="tr-date-sub">Oleh: {{ $r->receiver?->name ?? '—' }}</div>
                                    </td>
                                    <td class="c">
                                        <span class="tr-badge {{ $st['bg'] }}">{{ $st['label'] }}</span>
                                    </td>
                                    <td class="c">
                                        <span class="tr-badge {{ $fsBadge['bg'] }}">{{ $fsBadge['label'] }}</span>
                                        @if($r->followup_status === 'resolved' && $r->resolved_at)
                                            <div class="tr-date-sub" style="margin-top: 6px;">{{ $r->resolved_at->format('d/m/y, H:i') }}</div>
                                        @endif
                                    </td>
                                    <td class="c">
                                        @if($r->purchase_return_id)
                                            <a href="{{ route('pembelian.retur.show', $r->purchase_return_id) }}" class="tr-link-bold text-blue" title="Lihat Retur">
                                                Lihat Doc
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="c">
                                        @if($r->reorder_purchase_order_id)
                                            <a href="{{ route('pembelian.order.edit', $r->reorder_purchase_order_id) }}" class="tr-link-bold text-blue" title="Lihat Reorder">
                                                Lihat Doc
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="r">
                                        <a href="{{ route('pembelian.receipts_followup.show', $r) }}" class="tr-btn tr-btn-primary tr-btn-sm">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon text-emerald">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                            </div>
                                            <h6>Kondisi Aman</h6>
                                            <p>Tidak ada data penerimaan barang yang memerlukan tindak lanjut QC pada filter ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($receipts->hasPages())
                    <div class="tr-pagination-wrapper">
                        {{ $receipts->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-indigo: #4f46e5; --tr-indigo-hover: #4338ca; --tr-indigo-light: #eef2ff;
            --tr-emerald: #10b981; --tr-emerald-light: #ecfdf5;
            --tr-blue: #3b82f6; --tr-blue-light: #eff6ff;
            --tr-amber: #f59e0b; --tr-amber-light: #fffbeb;
            --tr-danger: #ef4444; --tr-danger-light: #fef2f2;
            --tr-bg: #f4f7fb; --tr-surface: #ffffff; --tr-border: #e2e8f0;
            --tr-text-main: #0f172a; --tr-text-muted: #64748b;
            --tr-radius: 14px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); padding-bottom: 4rem; }
        .tr-page { max-width: 1280px; margin: 0 auto; padding: 2.5rem 1.5rem; }

        /* ── ANIMATIONS ── */
        .animate-fade-in { animation: fadeIn 0.5s ease forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: center; gap: 12px; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 600; font-size: 0.9rem;}
        .tr-alert-success { background-color: var(--tr-emerald-light); color: #065f46; border: 1px solid #a7f3d0; }
        .tr-alert-danger { background-color: var(--tr-danger-light); color: #991b1b; border: 1px solid #fecaca; }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem; }
        .tr-eyebrow { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: var(--tr-amber); margin-bottom: 0.5rem; }
        .tr-title { font-size: 1.8rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 12px; letter-spacing: -0.02em; }
        .tr-title-icon-box { padding: 10px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .bg-amber { background: var(--tr-amber-light); color: var(--tr-amber); }
        .tr-subtitle { font-size: 0.95rem; color: var(--tr-text-muted); margin-top: 8px; font-weight: 500;}

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0.7rem 1.25rem; border-radius: 10px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: all 0.2s ease; border: 1px solid transparent; text-decoration: none; white-space: nowrap; }
        .tr-btn-sm { padding: 0.5rem 0.85rem; font-size: 0.8rem; }
        .tr-btn-primary { background: var(--tr-blue); color: white; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2); }
        .tr-btn-primary:hover { background: #2563eb; transform: translateY(-1px); box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3); }
        .tr-btn-dark { background: var(--tr-text-main); color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .tr-btn-dark:hover { background: #000; transform: translateY(-1px); }
        .tr-btn-light { color: var(--tr-text-muted); background: transparent; }
        .tr-btn-light:hover { background: #f1f5f9; color: var(--tr-text-main); }
        .tr-btn-outline { border-color: var(--tr-border); background: white; color: var(--tr-text-main); }
        .tr-btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; }

        /* ── FILTER BAR ── */
        .tr-filter-card { padding: 1.25rem 1.5rem; margin-bottom: 2rem; border-radius: var(--tr-radius); }
        .tr-filter-grid { display: flex; gap: 1.25rem; align-items: flex-end; flex-wrap: wrap; }
        .tr-form-group { display: flex; flex-direction: column; gap: 8px; }
        .flex-grow { flex-grow: 1; min-width: 250px;}
        .select-grp { min-width: 180px; }
        
        .tr-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); letter-spacing: 0.05em; }
        .tr-input, .tr-select { padding: 0.7rem 1rem; border: 1px solid var(--tr-border); border-radius: 10px; font-size: 0.9rem; background: #fff; transition: all 0.2s; font-family: inherit; color: var(--tr-text-main); font-weight: 500; outline: none; width: 100%; box-shadow: 0 1px 2px rgba(0,0,0,0.02);}
        .tr-input:focus, .tr-select:focus { border-color: var(--tr-indigo); box-shadow: 0 0 0 3px var(--tr-indigo-light); }
        .tr-filter-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }

        .tr-select-wrapper { position: relative; width: 100%; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 12px; height: 12px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        .tr-select { appearance: none; padding-right: 2.5rem; cursor: pointer; }

        /* ── CARDS & TABLES ── */
        .tr-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden;}
        .table-responsive { width: 100%; overflow-x: auto; }
        
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 900px; }
        .tr-table thead th { background: #f8fafc; padding: 1rem 1.25rem; text-align: left; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); border-bottom: 1px solid var(--tr-border); white-space: nowrap;}
        .tr-table tbody td { padding: 1.1rem 1.25rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; vertical-align: middle; }
        .tr-table tbody tr { transition: background-color 0.2s; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table .c { text-align: center; } .tr-table .r { text-align: right; }
        
        .tr-link-bold { font-weight: 800; text-decoration: none; transition: 0.2s;}
        .tr-link-bold:hover { text-decoration: underline;}

        .tr-date-main { font-weight: 700; font-size: 0.9rem; color: var(--tr-text-main); }
        .tr-date-sub { font-size: 0.8rem; color: var(--tr-text-muted); font-weight: 600; margin-top: 2px; }

        /* ── BADGES ── */
        .tr-badge { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.02em; text-transform: uppercase; white-space: nowrap;}
        .badge-success { background: var(--tr-emerald-light); color: #059669; }
        .badge-danger { background: var(--tr-danger-light); color: #dc2626; }
        .badge-amber { background: var(--tr-amber-light); color: #d97706; }
        .badge-gray { background: #f1f5f9; color: var(--tr-text-muted); }

        /* ── UTILS ── */
        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Consolas, monospace; }
        .tr-font-bold { font-weight: 700; }
        .tr-font-black { font-weight: 900; }
        .text-main { color: var(--tr-text-main); }
        .text-muted { color: var(--tr-text-muted); }
        .text-indigo { color: var(--tr-indigo); }
        .text-blue { color: var(--tr-blue); }
        .text-emerald { color: var(--tr-emerald); }

        .tr-empty-state { padding: 4rem 2rem; text-align: center; }
        .tr-empty-icon { margin-bottom: 1.5rem; }
        .tr-empty-state h6 { font-size: 1.2rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--tr-text-main); }
        .tr-empty-state p { color: var(--tr-text-muted); font-size: 0.95rem; }

        .tr-pagination-wrapper { padding: 1.25rem 1.5rem; border-top: 1px solid #f1f5f9; background: #f8fafc; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: stretch; }
            .tr-header-actions .tr-btn { width: 100%; justify-content: center; }
            .tr-filter-grid { flex-direction: column; align-items: stretch; }
            .tr-filter-actions { display: grid; grid-template-columns: 1fr 1fr; width: 100%;}
            .tr-filter-actions .tr-btn { justify-content: center; width: 100%; }
        }
    </style>
    @endpush
</x-app-layout>