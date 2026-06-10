<x-app-layout>
    <x-slot name="header">Permintaan Barang (PO / Transfer)</x-slot>

    <div class="pr-page">

        {{-- ══════════ HEADER ══════════ --}}
        <div class="pr-header">
            <div class="pr-header-left">
                <div class="pr-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <div>
                    <div class="pr-eyebrow">Approval Workflow</div>
                    <h1 class="pr-title">Permintaan Barang Masuk</h1>
                    <p class="pr-subtitle">Pantau pengajuan Purchase Order baru dan Transfer Cabang dari tim Gudang.</p>
                </div>
            </div>
            <a href="{{ route('gudang.request.create') }}" class="pr-btn pr-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Buat Permintaan Baru
            </a>
        </div>

        {{-- ══════════ ALERTS ══════════ --}}
        @if(session('success'))
        <div class="pr-alert pr-alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="pr-alert pr-alert-danger">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- ══════════ KPI CARDS ══════════ --}}
        <div class="pr-kpi">
            <div class="pr-kpi-card pr-kpi-total">
                <div class="pr-kpi-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div class="pr-kpi-info">
                    <span class="pr-kpi-label">Total Permintaan</span>
                    <span class="pr-kpi-value">{{ $totalCount }}</span>
                </div>
            </div>
            <div class="pr-kpi-card pr-kpi-pending">
                <div class="pr-kpi-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="pr-kpi-info">
                    <span class="pr-kpi-label">Menunggu</span>
                    <span class="pr-kpi-value">{{ $pendingCount }}</span>
                </div>
            </div>
            <div class="pr-kpi-card pr-kpi-approved">
                <div class="pr-kpi-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div class="pr-kpi-info">
                    <span class="pr-kpi-label">Disetujui</span>
                    <span class="pr-kpi-value">{{ $approvedCount }}</span>
                </div>
            </div>
            <div class="pr-kpi-card pr-kpi-rejected">
                <div class="pr-kpi-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div class="pr-kpi-info">
                    <span class="pr-kpi-label">Ditolak</span>
                    <span class="pr-kpi-value">{{ $rejectedCount }}</span>
                </div>
            </div>
            <div class="pr-kpi-card pr-kpi-completed">
                <div class="pr-kpi-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div class="pr-kpi-info">
                    <span class="pr-kpi-label">Selesai</span>
                    <span class="pr-kpi-value">{{ $completedCount }}</span>
                </div>
            </div>
        </div>

        {{-- ══════════ MAIN CARD ══════════ --}}
        <div class="pr-card">

            {{-- FILTER --}}
            <div class="pr-filter">
                <form method="GET" action="{{ route('gudang.request.index') }}" class="pr-filter-form">
                    <div class="pr-search-box">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari pemohon, SKU, produk..." class="pr-search-input">
                    </div>
                    <select name="status" class="pr-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <select name="type" class="pr-select">
                        <option value="">Semua Tipe</option>
                        <option value="po" {{ request('type') === 'po' ? 'selected' : '' }}>PO Baru</option>
                        <option value="transfer" {{ request('type') === 'transfer' ? 'selected' : '' }}>Transfer Cabang</option>
                    </select>
                    <button type="submit" class="pr-btn pr-btn-dark">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    @if(request()->filled('q') || request()->filled('status') || request()->filled('type'))
                    <a href="{{ route('gudang.request.index') }}" class="pr-btn pr-btn-ghost">Reset</a>
                    @endif
                </form>
            </div>

            {{-- TABLE --}}
            <div class="pr-table-wrap">
                <table class="pr-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pemohon</th>
                            <th>Produk</th>
                            <th class="c">Jumlah</th>
                            <th>Tipe / Rute</th>
                            <th>Catatan</th>
                            <th>Status</th>
                            @if($role === 'supervisor')<th class="c" style="width:150px;">Aksi</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td>
                                <div class="pr-date">{{ $req->created_at->format('d M Y') }}</div>
                                <div class="pr-date-sub">{{ $req->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="pr-user">
                                    <span class="pr-avatar">{{ strtoupper(substr($req->user->name ?? '?', 0, 1)) }}</span>
                                    <div>
                                        <div class="pr-user-name">{{ $req->user->name ?? '-' }}</div>
                                        <div class="pr-user-role">{{ strtoupper((string) ($req->user->role ?? '-')) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="pr-prod-name">{{ $req->product->name ?? '-' }}</div>
                                <div class="pr-prod-sku">{{ $req->product->sku ?? '-' }}</div>
                            </td>
                            <td class="c">
                                @php
                                    $unitName = $req->unit?->abbreviation ?? $req->unit?->name ?? $req->product?->unit?->abbreviation ?? $req->product?->unit?->name ?? '';
                                @endphp
                                <span class="pr-qty">
                                    {{ number_format((float)$req->quantity, 0) }}
                                    <span class="pr-qty-unit">{{ $unitName }}</span>
                                </span>
                            </td>
                            <td>
                                @if($req->type === 'po')
                                <span class="pr-type-badge pr-type-po">PO BARU</span>
                                @else
                                <span class="pr-type-badge pr-type-transfer">TRANSFER</span>
                                <div class="pr-route">
                                    <span>{{ $req->fromWarehouse?->name ?? 'Gudang Utama' }}</span>
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                    <span>{{ $req->toWarehouse?->name ?? 'Gudang Cabang' }}</span>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($req->notes)
                                <div class="pr-notes-text" title="{{ $req->notes }}">{{ $req->notes }}</div>
                                @else
                                <span class="pr-muted">—</span>
                                @endif
                                @if($req->transfer_reference)
                                <div class="pr-ref">Ref: {{ $req->transfer_reference }}</div>
                                @endif
                                @if($req->supervisor_notes)
                                <div class="pr-spv-note">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                    {{ $req->supervisor_notes }}
                                </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusMap = [
                                        'pending' => ['label' => 'MENUNGGU', 'class' => 'pr-st-pending'],
                                        'approved' => ['label' => 'DISETUJUI', 'class' => 'pr-st-approved'],
                                        'rejected' => ['label' => 'DITOLAK', 'class' => 'pr-st-rejected'],
                                        'completed' => ['label' => 'SELESAI', 'class' => 'pr-st-completed'],
                                    ];
                                    $st = $statusMap[$req->status] ?? ['label' => strtoupper($req->status), 'class' => 'pr-st-pending'];
                                @endphp
                                <span class="pr-status {{ $st['class'] }}">{{ $st['label'] }}</span>
                            </td>
                            @if($role === 'supervisor')
                            <td class="c">
                                @if($req->status === 'pending')
                                <div class="pr-actions">
                                    <button type="button" class="pr-act-btn pr-act-approve" onclick="openModal({{ $req->id }}, 'approved')" title="Setujui">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    </button>
                                    <button type="button" class="pr-act-btn pr-act-reject" onclick="openModal({{ $req->id }}, 'rejected')" title="Tolak">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </div>
                                @else
                                <span class="pr-done">Diproses</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $role === 'supervisor' ? 8 : 7 }}">
                                <div class="pr-empty">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    <h3>Tidak ada permintaan</h3>
                                    <p>Tidak ada pengajuan Purchase Order atau transfer cabang sesuai filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
            <div class="pr-pagination">
                <div class="pr-pagination-info">Halaman {{ $requests->currentPage() }} dari {{ $requests->lastPage() }}</div>
                <div class="pr-pagination-links">{{ $requests->withQueryString()->links() }}</div>
            </div>
            @endif
        </div>

    </div>

    {{-- ══════════ APPROVAL MODAL ══════════ --}}
    @if($role === 'supervisor')
    <form id="statusForm" method="POST" style="display:none;" data-action="{{ route('gudang.request.update_status', ['productRequest' => '__ID__']) }}">
        @csrf @method('PUT')
        <input type="hidden" name="status" id="statusInput">
        <input type="hidden" name="supervisor_notes" id="notesInput">
    </form>

    <div class="pr-modal" id="actionModal">
        <div class="pr-modal-backdrop" onclick="closeModal()"></div>
        <div class="pr-modal-box">
            <div class="pr-modal-head">
                <h3 id="modalTitle">Tindak Lanjut</h3>
            </div>
            <div class="pr-modal-body">
                <p id="modalDesc" class="pr-modal-desc"></p>
                <label class="pr-modal-label">Catatan Opsional (Alasan / Info)</label>
                <textarea id="modalNotes" class="pr-textarea" placeholder="Tuliskan pesan untuk pemohon..."></textarea>
            </div>
            <div class="pr-modal-foot">
                <button type="button" class="pr-btn pr-btn-ghost" onclick="closeModal()">Batalkan</button>
                <button type="button" class="pr-btn pr-btn-primary" id="modalConfirmBtn">Simpan</button>
            </div>
        </div>
    </div>

    <script>
    let currentReq = null;
    function openModal(id, status) {
        currentReq = { id, status };
        const titleEl = document.getElementById('modalTitle');
        const descEl = document.getElementById('modalDesc');
        const confirmBtn = document.getElementById('modalConfirmBtn');
        document.getElementById('modalNotes').value = '';

        if (status === 'approved') {
            titleEl.textContent = 'Setujui Permintaan';
            descEl.innerHTML = 'Permintaan akan ditandai sebagai <strong>disetujui</strong> dan dapat dilanjutkan ke tahap distribusi/PO.';
            confirmBtn.className = 'pr-btn pr-btn-success';
            confirmBtn.textContent = 'Ya, Setujui';
        } else {
            titleEl.textContent = 'Tolak Permintaan';
            descEl.innerHTML = 'Permintaan akan <strong>ditolak</strong>. Disarankan untuk menuliskan alasan.';
            confirmBtn.className = 'pr-btn pr-btn-danger';
            confirmBtn.textContent = 'Ya, Tolak';
        }
        document.getElementById('actionModal').classList.add('active');
        setTimeout(() => document.getElementById('modalNotes').focus(), 100);
    }
    function closeModal() {
        document.getElementById('actionModal').classList.remove('active');
        currentReq = null;
    }
    document.getElementById('modalConfirmBtn').addEventListener('click', function() {
        if (!currentReq) return;
        const notes = document.getElementById('modalNotes').value.trim();
        if (currentReq.status === 'rejected' && !notes) {
            alert('Catatan/Alasan wajib diisi jika menolak.');
            document.getElementById('modalNotes').focus();
            return;
        }
        this.disabled = true; this.style.opacity = '.6'; this.textContent = 'Memproses...';
        const form = document.getElementById('statusForm');
        document.getElementById('statusInput').value = currentReq.status;
        document.getElementById('notesInput').value = notes;
        form.action = form.dataset.action.replace('__ID__', currentReq.id);
        form.submit();
    });
    </script>
    @endif

    @push('styles')
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    :root {
        --pr-bg: #f1f5f9; --pr-surface: #fff; --pr-border: #e2e8f0; --pr-border-light: #f1f5f9;
        --pr-text: #0f172a; --pr-text2: #475569; --pr-muted: #94a3b8;
        --pr-indigo: #4f46e5; --pr-indigo-hover: #4338ca; --pr-indigo-bg: #eef2ff; --pr-indigo-border: #c7d2fe;
        --pr-green: #10b981; --pr-green-bg: #ecfdf5; --pr-green-text: #065f46; --pr-green-border: #a7f3d0;
        --pr-red: #ef4444; --pr-red-bg: #fef2f2; --pr-red-text: #991b1b; --pr-red-border: #fecaca;
        --pr-amber: #f59e0b; --pr-amber-bg: #fffbeb; --pr-amber-text: #92400e; --pr-amber-border: #fde68a;
        --pr-blue: #0ea5e9; --pr-blue-bg: #e0f2fe; --pr-blue-text: #0369a1; --pr-blue-border: #bae6fd;
        --pr-slate: #64748b; --pr-slate-bg: #f1f5f9;
        --pr-r: 12px; --pr-r-sm: 8px;
    }
    *, *::before, *::after { box-sizing: border-box; }
    .pr-page { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: var(--pr-text); max-width: 1360px; margin: 0 auto; padding: 1.5rem 1.25rem 3rem; background: var(--pr-bg); min-height: 100vh; }

    /* ── HEADER ── */
    .pr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem; }
    .pr-header-left { display: flex; gap: 0.875rem; align-items: flex-start; }
    .pr-icon-box { width: 48px; height: 48px; border-radius: 12px; background: var(--pr-indigo-bg); color: var(--pr-indigo); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .pr-eyebrow { font-size: 0.63rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--pr-indigo); margin-bottom: 0.15rem; }
    .pr-title { font-size: 1.4rem; font-weight: 800; margin: 0; letter-spacing: -0.02em; }
    .pr-subtitle { font-size: 0.82rem; color: var(--pr-text2); margin: 0.2rem 0 0; line-height: 1.5; }

    /* ── BUTTONS ── */
    .pr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.55rem 1.15rem; border-radius: var(--pr-r-sm); font-size: 0.82rem; font-weight: 700; font-family: inherit; cursor: pointer; border: 1px solid transparent; text-decoration: none; transition: all .15s; white-space: nowrap; height: 40px; }
    .pr-btn-primary { background: var(--pr-indigo); color: #fff; box-shadow: 0 2px 8px rgba(79,70,229,.2); }
    .pr-btn-primary:hover { background: var(--pr-indigo-hover); transform: translateY(-1px); }
    .pr-btn-success { background: var(--pr-green); color: #fff; box-shadow: 0 2px 6px rgba(16,185,129,.2); }
    .pr-btn-success:hover { background: #059669; }
    .pr-btn-danger { background: var(--pr-red); color: #fff; box-shadow: 0 2px 6px rgba(239,68,68,.2); }
    .pr-btn-danger:hover { background: #dc2626; }
    .pr-btn-dark { background: var(--pr-text); color: #fff; }
    .pr-btn-dark:hover { background: #000; transform: translateY(-1px); }
    .pr-btn-ghost { background: transparent; border-color: var(--pr-border); color: var(--pr-text2); }
    .pr-btn-ghost:hover { border-color: var(--pr-muted); color: var(--pr-text); }

    /* ── ALERTS ── */
    .pr-alert { display: flex; align-items: center; gap: 10px; padding: 0.85rem 1.125rem; border-radius: var(--pr-r-sm); margin-bottom: 1rem; font-size: 0.84rem; font-weight: 500; border: 1px solid; }
    .pr-alert-success { background: var(--pr-green-bg); color: var(--pr-green-text); border-color: var(--pr-green-border); }
    .pr-alert-danger { background: var(--pr-red-bg); color: var(--pr-red-text); border-color: var(--pr-red-border); }

    /* ── KPI CARDS ── */
    .pr-kpi { display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.75rem; margin-bottom: 1.25rem; }
    .pr-kpi-card { background: var(--pr-surface); border: 1px solid var(--pr-border); border-radius: var(--pr-r); padding: 1rem 1.15rem; display: flex; align-items: center; gap: 0.85rem; box-shadow: 0 1px 2px rgba(0,0,0,.04); transition: box-shadow .15s; }
    .pr-kpi-card:hover { box-shadow: 0 3px 10px rgba(0,0,0,.06); }
    .pr-kpi-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .pr-kpi-info { display: flex; flex-direction: column; gap: 2px; }
    .pr-kpi-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--pr-muted); }
    .pr-kpi-value { font-size: 1.5rem; font-weight: 800; line-height: 1; }
    .pr-kpi-total .pr-kpi-icon { background: var(--pr-indigo-bg); color: var(--pr-indigo); }
    .pr-kpi-total .pr-kpi-value { color: var(--pr-indigo); }
    .pr-kpi-pending .pr-kpi-icon { background: var(--pr-amber-bg); color: var(--pr-amber); }
    .pr-kpi-pending .pr-kpi-value { color: var(--pr-amber); }
    .pr-kpi-approved .pr-kpi-icon { background: var(--pr-green-bg); color: var(--pr-green); }
    .pr-kpi-approved .pr-kpi-value { color: var(--pr-green); }
    .pr-kpi-rejected .pr-kpi-icon { background: var(--pr-red-bg); color: var(--pr-red); }
    .pr-kpi-rejected .pr-kpi-value { color: var(--pr-red); }
    .pr-kpi-completed .pr-kpi-icon { background: var(--pr-slate-bg); color: var(--pr-slate); }
    .pr-kpi-completed .pr-kpi-value { color: var(--pr-slate); }

    /* ── CARD ── */
    .pr-card { background: var(--pr-surface); border: 1px solid var(--pr-border); border-radius: var(--pr-r); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04); }

    /* ── FILTER ── */
    .pr-filter { padding: 1rem 1.25rem; border-bottom: 1px solid var(--pr-border-light); }
    .pr-filter-form { display: flex; gap: 0.65rem; align-items: center; flex-wrap: wrap; }
    .pr-search-box { display: flex; align-items: center; gap: 8px; background: #f8fafc; border-radius: var(--pr-r-sm); padding: 0 0.85rem; border: 1.5px solid var(--pr-border); flex: 1; min-width: 220px; height: 40px; transition: all .15s; }
    .pr-search-box:focus-within { border-color: var(--pr-indigo); background: #fff; box-shadow: 0 0 0 3px rgba(79,70,229,.08); }
    .pr-search-box svg { color: var(--pr-muted); flex-shrink: 0; }
    .pr-search-input { border: none; background: transparent; font-size: 0.84rem; font-family: inherit; color: var(--pr-text); outline: none; width: 100%; }
    .pr-search-input::placeholder { color: var(--pr-muted); }
    .pr-select { padding: 0 0.75rem; padding-right: 1.8rem; border: 1.5px solid var(--pr-border); border-radius: var(--pr-r-sm); font-family: inherit; font-size: 0.82rem; color: var(--pr-text); background: #f8fafc; appearance: none; outline: none; cursor: pointer; height: 40px; min-width: 140px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; background-size: 14px; transition: all .15s; }
    .pr-select:focus { border-color: var(--pr-indigo); background-color: #fff; }

    /* ── TABLE ── */
    .pr-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .pr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 950px; }
    .pr-table thead th { font-size: 0.63rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: var(--pr-muted); padding: 0.75rem 1.125rem; border-bottom: 1.5px solid var(--pr-border); background: #fafbfc; white-space: nowrap; text-align: left; user-select: none; }
    .pr-table tbody tr { transition: background .1s; }
    .pr-table tbody tr:hover { background: #f8fafc; }
    .pr-table tbody td { padding: 0.85rem 1.125rem; font-size: 0.84rem; vertical-align: middle; border-bottom: 1px solid var(--pr-border-light); }
    .pr-table tbody tr:last-child td { border-bottom: none; }
    .pr-table th.c, .pr-table td.c { text-align: center; }

    /* ── CELLS ── */
    .pr-date { font-weight: 700; color: var(--pr-text); font-size: 0.84rem; white-space: nowrap; }
    .pr-date-sub { font-size: 0.7rem; color: var(--pr-muted); margin-top: 2px; font-family: monospace; }
    .pr-user { display: flex; align-items: center; gap: 8px; }
    .pr-avatar { width: 30px; height: 30px; border-radius: 8px; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; flex-shrink: 0; }
    .pr-user-name { font-weight: 700; font-size: 0.82rem; color: var(--pr-text); }
    .pr-user-role { font-size: 0.65rem; color: var(--pr-muted); font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
    .pr-prod-name { font-weight: 700; font-size: 0.84rem; color: var(--pr-text); line-height: 1.35; }
    .pr-prod-sku { font-size: 0.7rem; color: var(--pr-muted); font-family: monospace; font-weight: 600; margin-top: 2px; }

    /* ── QTY WITH UNIT ── */
    .pr-qty { display: inline-flex; align-items: baseline; gap: 3px; font-weight: 800; font-size: 0.95rem; color: var(--pr-text); }
    .pr-qty-unit { font-size: 0.7rem; font-weight: 600; color: var(--pr-text2); }
    .pr-qty-base { font-size: 0.65rem; color: var(--pr-muted); margin-top: 2px; font-weight: 600; font-family: monospace; }

    /* ── TYPE BADGE ── */
    .pr-type-badge { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 999px; font-size: 0.6rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
    .pr-type-po { background: var(--pr-blue-bg); color: var(--pr-blue-text); border: 1px solid var(--pr-blue-border); }
    .pr-type-transfer { background: var(--pr-amber-bg); color: var(--pr-amber-text); border: 1px solid var(--pr-amber-border); }
    .pr-route { display: flex; align-items: center; gap: 5px; margin-top: 4px; font-size: 0.7rem; color: var(--pr-muted); font-weight: 600; }
    .pr-route svg { color: var(--pr-muted); }

    /* ── NOTES ── */
    .pr-notes-text { font-size: 0.8rem; color: var(--pr-text2); max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-style: italic; }
    .pr-muted { color: var(--pr-muted); font-size: 0.8rem; }
    .pr-ref { font-size: 0.7rem; color: var(--pr-muted); font-family: monospace; margin-top: 3px; }
    .pr-spv-note { display: flex; align-items: flex-start; gap: 5px; font-size: 0.72rem; color: var(--pr-indigo); background: var(--pr-indigo-bg); border-left: 2px solid var(--pr-indigo); padding: 0.3rem 0.5rem; border-radius: 4px; margin-top: 6px; font-weight: 500; }
    .pr-spv-note svg { flex-shrink: 0; margin-top: 2px; }

    /* ── STATUS ── */
    .pr-status { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.62rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
    .pr-st-pending { background: var(--pr-amber-bg); color: var(--pr-amber-text); border: 1px solid var(--pr-amber-border); }
    .pr-st-approved { background: var(--pr-green-bg); color: var(--pr-green-text); border: 1px solid var(--pr-green-border); }
    .pr-st-rejected { background: var(--pr-red-bg); color: var(--pr-red-text); border: 1px solid var(--pr-red-border); }
    .pr-st-completed { background: var(--pr-slate-bg); color: var(--pr-slate); border: 1px solid var(--pr-border); }

    /* ── ACTIONS ── */
    .pr-actions { display: flex; gap: 6px; justify-content: center; }
    .pr-act-btn { width: 32px; height: 32px; border-radius: var(--pr-r-sm); border: 1px solid var(--pr-border); background: var(--pr-surface); color: var(--pr-muted); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all .15s; }
    .pr-act-approve:hover { background: var(--pr-green-bg); color: var(--pr-green); border-color: var(--pr-green-border); }
    .pr-act-reject:hover { background: var(--pr-red-bg); color: var(--pr-red); border-color: var(--pr-red-border); }
    .pr-done { font-size: 0.72rem; color: var(--pr-muted); font-style: italic; }

    /* ── EMPTY ── */
    .pr-empty { text-align: center; padding: 4rem 1.5rem; color: var(--pr-muted); }
    .pr-empty svg { opacity: .25; margin-bottom: 0.75rem; }
    .pr-empty h3 { font-size: 1rem; font-weight: 800; color: var(--pr-text); margin: 0 0 0.3rem; }
    .pr-empty p { font-size: 0.84rem; max-width: 380px; margin: 0 auto; line-height: 1.5; }

    /* ── PAGINATION ── */
    .pr-pagination { padding: 0.85rem 1.25rem; border-top: 1px solid var(--pr-border-light); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; }
    .pr-pagination-info { font-size: 0.78rem; color: var(--pr-muted); font-weight: 500; }

    /* ── MODAL ── */
    .pr-modal { position: fixed; inset: 0; z-index: 100; display: none; align-items: center; justify-content: center; padding: 1rem; }
    .pr-modal.active { display: flex; }
    .pr-modal-backdrop { position: absolute; inset: 0; background: rgba(15,23,42,.4); backdrop-filter: blur(2px); }
    .pr-modal-box { position: relative; z-index: 101; width: 100%; max-width: 460px; background: var(--pr-surface); border-radius: var(--pr-r); box-shadow: 0 20px 60px rgba(0,0,0,.15); overflow: hidden; animation: pr-pop .2s cubic-bezier(.16,1,.3,1); }
    @keyframes pr-pop { 0% { opacity: 0; transform: scale(.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
    .pr-modal-head { padding: 1.15rem 1.5rem; border-bottom: 1px solid var(--pr-border-light); }
    .pr-modal-head h3 { font-size: 1.1rem; font-weight: 800; margin: 0; }
    .pr-modal-body { padding: 1.5rem; }
    .pr-modal-desc { font-size: 0.84rem; color: var(--pr-text2); margin: 0 0 1rem; line-height: 1.5; }
    .pr-modal-desc strong { color: var(--pr-text); }
    .pr-modal-label { font-size: 0.75rem; font-weight: 700; color: var(--pr-text); display: block; margin-bottom: 0.35rem; }
    .pr-textarea { width: 100%; padding: 0.75rem; border: 1.5px solid var(--pr-border); border-radius: var(--pr-r-sm); font-family: inherit; font-size: 0.84rem; color: var(--pr-text); background: #f8fafc; outline: none; resize: vertical; min-height: 90px; transition: all .15s; }
    .pr-textarea:focus { border-color: var(--pr-indigo); background: #fff; box-shadow: 0 0 0 3px rgba(79,70,229,.08); }
    .pr-modal-foot { padding: 1.15rem 1.5rem; border-top: 1px solid var(--pr-border-light); display: flex; justify-content: flex-end; gap: 0.65rem; }

    /* ── RESPONSIVE ── */
    @media (max-width: 1100px) { .pr-kpi { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) {
        .pr-header { flex-direction: column; align-items: stretch; }
        .pr-btn-primary { width: 100%; justify-content: center; }
        .pr-kpi { grid-template-columns: repeat(2, 1fr); }
        .pr-filter-form { flex-direction: column; align-items: stretch; }
        .pr-search-box, .pr-select { width: 100%; min-width: auto; }
        .pr-modal-foot { flex-direction: column-reverse; }
        .pr-modal-foot .pr-btn { width: 100%; }
    }
    @media (max-width: 480px) { .pr-kpi { grid-template-columns: 1fr; } }
    </style>
    @endpush
</x-app-layout>
