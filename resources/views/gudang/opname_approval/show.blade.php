<x-app-layout>
    <x-slot name="header">Review Opname</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Tugas Supervisor</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-info">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </div>
                        Review Sesi Opname
                    </h1>
                    
                    <div class="tr-session-meta">
                        <span class="tr-meta-item">Gudang: <strong>{{ $session->warehouse?->name }}</strong></span>
                        <span class="tr-dot-divider">•</span>
                        <span class="tr-meta-item">Pembuat: <strong>{{ $session->creator?->name }}</strong></span>
                        <span class="tr-dot-divider">•</span>
                        <span class="tr-meta-item">
                            Status: <span class="tr-status-text {{ 'status-' . strtolower($session->status) }}">{{ strtoupper($session->status) }}</span>
                        </span>
                    </div>

                    @if($session->notes)
                        <div class="tr-session-notes">"{{ $session->notes }}"</div>
                    @endif
                </div>

                <div class="tr-header-actions">
                    <a href="{{ route('gudang.opname_approval.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali
                    </a>
                    <a href="{{ route('gudang.opname_sessions.edit', $session) }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                        Buka Sesi (Edit)
                    </a>
                </div>
            </div>

            {{-- ALERTS --}}
            @if(session('success'))
                <div class="tr-alert tr-alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error')) 
                <div class="tr-alert tr-alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    {{ session('error') }}
                </div> 
            @endif

            {{-- TABLE DATA CARD --}}
            <div class="tr-card">
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Item Produk Terdata</th>
                                <th class="r">Qty Sistem</th>
                                <th class="r">Qty Fisik</th>
                                <th class="c">Selisih</th>
                                <th>Catatan Penyesuaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($session->items as $it)
                                @php
                                    $diff = (int) $it->difference_qty;
                                @endphp
                                <tr class="{{ $diff !== 0 ? 'tr-row-highlight' : '' }}">
                                    <td>
                                        <div class="tr-prod-name">{{ $it->product?->name ?? 'Produk Dihapus' }}</div>
                                        <div class="tr-prod-sku">SKU: <span class="tr-font-mono">{{ $it->product?->sku ?? '-' }}</span></div>
                                    </td>
                                    <td class="r tr-qty-col tr-text-muted">{{ (int) $it->system_qty }}</td>
                                    <td class="r tr-qty-col tr-text-main">{{ (int) $it->physical_qty }}</td>
                                    <td class="c">
                                        @if($diff > 0)
                                            <span class="tr-diff-badge plus">+{{ $diff }}</span>
                                        @elseif($diff < 0)
                                            <span class="tr-diff-badge minus">{{ $diff }}</span>
                                        @else
                                            <span class="tr-diff-badge zero">±0</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="tr-notes-text" title="{{ $it->notes ?? '' }}">
                                            {{ $it->notes ?: '-' }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                            </div>
                                            <h6>Tidak ada item</h6>
                                            <p>Data sesi opname ini kosong atau item telah dihapus.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ACTION & APPROVAL AREA --}}
            @if($session->status === 'submitted')
            <div class="tr-approval-box">
                <div class="tr-approval-grid">
                    
                    {{-- Kiri: Notes --}}
                    <div class="tr-approval-form">
                        <div class="tr-approval-head">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="tr-text-warning"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                            <h3 class="tr-approval-title">Keputusan Supervisor</h3>
                        </div>
                        <p class="tr-approval-desc">Berikan catatan khusus untuk Admin Gudang (opsional jika Approve, <strong>wajib jika Reject</strong>).</p>
                        
                        <textarea id="approvalNotes" class="tr-textarea" placeholder="Ketikan catatan review di sini..."></textarea>
                    </div>

                    {{-- Kanan: Buttons --}}
                    <div class="tr-approval-actions">
                        <form id="rejectForm" method="POST" action="{{ route('gudang.opname_approval.reject', $session) }}" onsubmit="return confirm('Apakah Anda yakin menolak (Reject) sesi opname ini?');" class="tr-action-form">
                            @csrf
                            <input type="hidden" name="approval_notes" id="rejectNotesField">
                            <button type="submit" class="tr-btn-massive tr-btn-danger">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                Kembalikan (Reject)
                            </button>
                        </form>

                        <form id="approveForm" method="POST" action="{{ route('gudang.opname_approval.approve', $session) }}" onsubmit="return confirm('Peringatan: Menyetujui opname ini akan langsung menyesuaikan/mengubah stok pada sistem. Lanjutkan?');" class="tr-action-form">
                            @csrf
                            <input type="hidden" name="approval_notes" id="approveNotesField">
                            <button type="submit" class="tr-btn-massive tr-btn-success">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Setujui (Approve)
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const notes = document.getElementById('approvalNotes');
            const approveField = document.getElementById('approveNotesField');
            const rejectField = document.getElementById('rejectNotesField');
            const rejectForm = document.getElementById('rejectForm');
            const approveForm = document.getElementById('approveForm');

            function sync() {
                const v = notes ? notes.value : '';
                if(approveField) approveField.value = v;
                if(rejectField) rejectField.value = v;
            }

            if (notes) {
                notes.addEventListener('input', sync);
                sync();
            }

            // Prevent double click & require notes on Reject
            if (rejectForm) {
                rejectForm.addEventListener('submit', function (e) {
                    const v = (notes ? notes.value : '').trim();
                    if (!v) {
                        e.preventDefault();
                        alert('Catatan Supervisor WAJIB diisi jika menolak (Reject) sesi opname.');
                        if(notes) notes.focus();
                    } else {
                        const btn = rejectForm.querySelector('button');
                        if(btn) { btn.disabled = true; btn.innerHTML = 'Memproses...'; }
                    }
                });
            }

            if(approveForm) {
                approveForm.addEventListener('submit', function(e) {
                    // Timeout to let browser confirm dialog run
                    setTimeout(() => {
                        const btn = approveForm.querySelector('button');
                        if(btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
                    }, 10);
                });
            }
        });
    </script>
    @endpush

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            --tr-primary: #3b82f6;
            --tr-info: #0ea5e9;
            --tr-info-bg: #e0f2fe;
            --tr-success: #10b981;
            --tr-success-hover: #059669;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            --tr-danger: #ef4444;
            --tr-danger-hover: #dc2626;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #b91c1c;
            --tr-warning: #f59e0b;
            --tr-warning-bg: #fffbeb;
            --tr-warning-text: #b45309;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --tr-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 4rem; }
        .tr-page { padding: 1.5rem; max-width: 1280px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-info); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-info { background: var(--tr-info-bg); color: var(--tr-info); }
        
        .tr-session-meta { font-size: 0.85rem; color: var(--tr-text-muted); display: flex; align-items: center; flex-wrap: wrap; gap: 6px; }
        .tr-meta-item strong { color: var(--tr-text-main); font-weight: 700; }
        .tr-dot-divider { color: var(--tr-border); }
        .tr-session-notes { font-size: 0.85rem; color: #475569; margin-top: 0.5rem; font-style: italic; background: #f8fafc; padding: 4px 8px; border-radius: 4px; display: inline-block; border: 1px solid var(--tr-border-light); }

        /* Status Colors */
        .tr-status-text { font-weight: 800; font-size: 0.8rem; letter-spacing: 0.05em; padding: 2px 6px; border-radius: 4px; }
        .status-draft { background: #f1f5f9; color: #334155; }
        .status-submitted { background: var(--tr-info-bg); color: #0284c7; }
        .status-approved { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .status-rejected { background: var(--tr-danger-bg); color: var(--tr-danger-text); }
        .status-cancelled { background: #e2e8f0; color: #475569; }

        .tr-header-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: center; gap: 10px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 500; border: 1px solid transparent; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: #a7f3d0; }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: #fecaca; }

        /* ── HEADER BUTTONS ── */
        .tr-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600;
            cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 38px;
        }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); box-shadow: var(--tr-shadow-sm); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); background: #f8fafc; }

        /* ── CARD & TABLE ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; margin-bottom: 1.5rem; }
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody tr.tr-row-highlight td { background: var(--tr-warning-bg); }
        .tr-table tbody tr.tr-row-highlight:hover td { background: #fef3c7; }
        
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        
        .tr-table th.c, .tr-table td.c { text-align: center; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* Cell Formatting */
        .tr-prod-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; line-height: 1.3; }
        .tr-prod-sku { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 4px; }
        .tr-font-mono { font-family: monospace; font-weight: 600; color: var(--tr-text-main); }
        
        .tr-qty-col { font-weight: 800; font-size: 1rem; }
        .tr-text-muted { color: var(--tr-text-light); }
        .tr-text-main { color: var(--tr-text-main); }
        
        .tr-diff-badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.25rem 0.6rem; border-radius: 999px; font-weight: 800; font-size: 0.85rem; border: 1px solid transparent; min-width: 44px;}
        .tr-diff-badge.plus { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: #a7f3d0; }
        .tr-diff-badge.minus { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: #fecaca; }
        .tr-diff-badge.zero { background: var(--tr-bg); color: var(--tr-text-muted); border-color: var(--tr-border); }

        .tr-notes-text { font-size: 0.8rem; color: var(--tr-text-muted); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* Empty State */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-text-light); }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* ── APPROVAL AREA (BOTTOM) ── */
        .tr-approval-box { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-md); padding: 1.5rem; }
        .tr-approval-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; }
        
        .tr-approval-form { display: flex; flex-direction: column; }
        .tr-approval-head { display: flex; align-items: center; gap: 8px; margin-bottom: 0.5rem; }
        .tr-text-warning { color: var(--tr-warning); }
        .tr-approval-title { font-size: 1.15rem; font-weight: 800; color: var(--tr-text-main); margin: 0; }
        .tr-approval-desc { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 0 1rem 0; line-height: 1.4; }
        
        .tr-textarea {
            width: 100%; padding: 0.85rem; border: 1px solid var(--tr-border); border-radius: var(--tr-radius-md);
            font-family: inherit; font-size: 0.9rem; color: var(--tr-text-main); background: #f8fafc;
            outline: none; transition: border-color 0.2s; resize: vertical; min-height: 100px;
        }
        .tr-textarea:focus { border-color: var(--tr-info); background: #ffffff; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15); }

        .tr-approval-actions { display: flex; flex-direction: column; gap: 0.75rem; justify-content: flex-end; }
        .tr-action-form { width: 100%; }
        
        /* Massive Action Buttons */
        .tr-btn-massive {
            width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;
            padding: 1rem; border-radius: var(--tr-radius-md); font-size: 1.05rem; font-weight: 800;
            cursor: pointer; border: none; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .tr-btn-massive:disabled { opacity: 0.7; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
        
        .tr-btn-success { background: var(--tr-success); color: #ffffff; }
        .tr-btn-success:hover:not(:disabled) { background: var(--tr-success-hover); transform: translateY(-2px); box-shadow: 0 6px 12px -2px rgba(16, 185, 129, 0.3); }
        
        .tr-btn-danger { background: var(--tr-surface); color: var(--tr-danger); border: 2px solid var(--tr-danger); box-shadow: none; }
        .tr-btn-danger:hover:not(:disabled) { background: var(--tr-danger-light); transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.15); }

        /* ── RESPONSIVE ── */
        @media (max-width: 820px) {
            .tr-approval-grid { grid-template-columns: 1fr; gap: 1.5rem; }
            .tr-approval-actions { flex-direction: row; }
        }
        @media (max-width: 640px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-header-actions { width: 100%; }
            .tr-header-actions .tr-btn { width: 100%; justify-content: center; }
            .tr-approval-actions { flex-direction: column-reverse; }
        }
    </style>
    @endpush
</x-app-layout>