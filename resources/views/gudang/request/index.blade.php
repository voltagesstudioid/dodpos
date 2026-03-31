<x-app-layout>
    <x-slot name="header">Permintaan Barang (PO / Transfer)</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Approval Workflow</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                        Permintaan Barang Masuk
                    </h1>
                    <p class="tr-subtitle">Pantau pengajuan Purchase Order baru dan Transfer Cabang dari tim Gudang.</p>
                </div>
                
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.request.create') }}" class="tr-btn tr-btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Buat Permintaan Baru
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

            {{-- KPI GRID --}}
            <div class="tr-kpi-grid">
                <div class="tr-kpi-card border-blue">
                    <div class="tr-kpi-label">Total Permintaan</div>
                    <div class="tr-kpi-value text-blue">{{ $totalCount }}</div>
                </div>
                <div class="tr-kpi-card border-warning">
                    <div class="tr-kpi-label">Menunggu (Pending)</div>
                    <div class="tr-kpi-value text-warning">{{ $pendingCount }}</div>
                </div>
                <div class="tr-kpi-card border-success">
                    <div class="tr-kpi-label">Disetujui</div>
                    <div class="tr-kpi-value text-success">{{ $approvedCount }}</div>
                </div>
                <div class="tr-kpi-card border-danger">
                    <div class="tr-kpi-label">Ditolak</div>
                    <div class="tr-kpi-value text-danger">{{ $rejectedCount }}</div>
                </div>
                <div class="tr-kpi-card border-slate">
                    <div class="tr-kpi-label">Selesai (Completed)</div>
                    <div class="tr-kpi-value text-slate">{{ $completedCount }}</div>
                </div>
            </div>

            {{-- MAIN CARD --}}
            <div class="tr-card">
                
                {{-- Filter Bar --}}
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('gudang.request.index') }}" class="tr-filter-grid">
                        <div class="tr-form-group">
                            <label class="tr-label">Pencarian</label>
                            <div class="tr-search">
                                <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari pemohon, SKU, produk...">
                            </div>
                        </div>

                        <div class="tr-form-group">
                            <label class="tr-label">Status</label>
                            <div class="tr-select-wrapper">
                                <select name="status" class="tr-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="tr-form-group">
                            <label class="tr-label">Tipe Request</label>
                            <div class="tr-select-wrapper">
                                <select name="type" class="tr-select">
                                    <option value="">Semua Tipe</option>
                                    <option value="po" {{ request('type') === 'po' ? 'selected' : '' }}>PO Baru</option>
                                    <option value="transfer" {{ request('type') === 'transfer' ? 'selected' : '' }}>Transfer Cabang</option>
                                </select>
                            </div>
                        </div>

                        <div class="tr-filter-actions">
                            <button type="submit" class="tr-btn tr-btn-dark">Filter Data</button>
                            @if(request()->filled('q') || request()->filled('status') || request()->filled('type'))
                                <a href="{{ route('gudang.request.index') }}" class="tr-btn tr-btn-danger-outline">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Tanggal Submit</th>
                                <th>Pemohon (Role)</th>
                                <th>Produk Terkait</th>
                                <th class="c">Jumlah</th>
                                <th>Tipe / Rute</th>
                                <th>Catatan Tambahan</th>
                                <th>Status</th>
                                @if($role === 'supervisor')
                                    <th class="r" style="width: 180px;">Aksi Cepat</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                                <tr>
                                    <td>
                                        <div class="tr-date-main">{{ $req->created_at->format('d M Y') }}</div>
                                        <div class="tr-date-sub">{{ $req->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td>
                                        <div class="tr-user-name">{{ $req->user->name ?? '-' }}</div>
                                        <div class="tr-user-role">{{ strtoupper((string) ($req->user->role ?? '-')) }}</div>
                                    </td>
                                    <td>
                                        <div class="tr-prod-name">{{ $req->product->name ?? '-' }}</div>
                                        <div class="tr-prod-sku">SKU: <span class="tr-font-mono">{{ $req->product->sku ?? '-' }}</span></div>
                                    </td>
                                    <td class="c">
                                        <span class="tr-qty-badge">{{ $req->quantity }}</span>
                                    </td>
                                    <td>
                                        @if($req->type === 'po')
                                            <span class="tr-badge tr-badge-blue">PURCHASE ORDER</span>
                                        @else
                                            <span class="tr-badge tr-badge-warning">TRANSFER CABANG</span>
                                            <div class="tr-route-box">
                                                <span class="tr-route-wh">{{ $req->fromWarehouse?->name ?? 'Gudang Utama' }}</span>
                                                <svg class="tr-route-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                                <span class="tr-route-wh">{{ $req->toWarehouse?->name ?? 'Gudang Cabang' }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($req->notes)
                                            <div class="tr-notes-text">"{{ $req->notes }}"</div>
                                        @else
                                            <span class="tr-text-muted">-</span>
                                        @endif

                                        @if($req->transfer_reference)
                                            <div class="tr-doc-ref">Ref: <span class="tr-font-mono">{{ $req->transfer_reference }}</span></div>
                                        @endif

                                        @if($req->supervisor_notes)
                                            <div class="tr-spv-note">
                                                <strong>Balasan SPV:</strong> {{ $req->supervisor_notes }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($req->status) {
                                                'pending' => 'tr-badge-warning',
                                                'approved' => 'tr-badge-success',
                                                'rejected' => 'tr-badge-danger',
                                                'completed' => 'tr-badge-blue',
                                                default => 'tr-badge-gray',
                                            };
                                            $badgeLabel = match($req->status) {
                                                'pending' => 'MENUNGGU',
                                                'approved' => 'DISETUJUI',
                                                'rejected' => 'DITOLAK',
                                                'completed' => 'SELESAI',
                                                default => strtoupper($req->status),
                                            };
                                        @endphp
                                        <span class="tr-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                    </td>
                                    
                                    @if($role === 'supervisor')
                                    <td class="r">
                                        <div class="tr-actions-group">
                                            @if($req->status === 'pending')
                                                <button type="button" class="tr-action-btn-text tr-acc" onclick="openModal({{ $req->id }}, 'approved')">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    Setuju
                                                </button>
                                                <button type="button" class="tr-action-btn-text tr-rej" onclick="openModal({{ $req->id }}, 'rejected')">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                    Tolak
                                                </button>
                                            @else
                                                <span class="tr-status-locked">Sudah Diproses</span>
                                            @endif
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $role === 'supervisor' ? 8 : 7 }}">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                            </div>
                                            <h6>Tidak ada permintaan barang</h6>
                                            <p>Saat ini tidak ada pengajuan Purchase Order baru atau transfer cabang sesuai filter.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                    <div class="tr-pagination">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- MODAL APPROVAL --}}
    @if($role === 'supervisor')
        <form id="statusForm" method="POST" style="display:none;">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" id="statusInput">
            <input type="hidden" name="supervisor_notes" id="notesInput">
        </form>

        <div class="tr-modal" id="actionModal">
            <div class="tr-modal-backdrop" onclick="closeModal()"></div>
            <div class="tr-modal-card">
                <div class="tr-modal-header">
                    <h3 class="tr-modal-title" id="modalTitle">Tindak Lanjut</h3>
                </div>
                <div class="tr-modal-body">
                    <p id="modalDesc" class="tr-modal-desc"></p>
                    <label class="tr-label">Catatan Opsional (Alasan / Info Tambahan)</label>
                    <textarea id="modalNotes" class="tr-textarea" placeholder="Tuliskan pesan untuk admin yang merequest..."></textarea>
                </div>
                <div class="tr-modal-footer">
                    <button type="button" class="tr-btn tr-btn-outline" onclick="closeModal()">Batalkan</button>
                    <button type="button" class="tr-btn tr-btn-primary" id="modalConfirmBtn">Simpan Keputusan</button>
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
                const notesEl = document.getElementById('modalNotes');

                notesEl.value = '';

                if (status === 'approved') {
                    titleEl.textContent = 'Setujui Permintaan';
                    descEl.innerHTML = 'Permintaan akan ditandai sebagai <strong>disetujui</strong>, dan dapat dilanjutkan ke tahap distribusi/PO.';
                    confirmBtn.className = 'tr-btn tr-btn-success';
                    confirmBtn.textContent = 'Ya, Setujui Permintaan';
                } else {
                    titleEl.textContent = 'Tolak Permintaan';
                    descEl.innerHTML = 'Permintaan akan <strong>ditolak</strong>. Sangat disarankan untuk menuliskan alasan pada kolom catatan agar pemohon tahu.';
                    confirmBtn.className = 'tr-btn tr-btn-danger';
                    confirmBtn.textContent = 'Ya, Tolak Permintaan';
                }

                document.getElementById('actionModal').classList.add('active');
                setTimeout(() => notesEl.focus(), 100);
            }

            function closeModal() {
                document.getElementById('actionModal').classList.remove('active');
                currentReq = null;
            }

            document.getElementById('modalConfirmBtn').addEventListener('click', function() {
                if (!currentReq) return;
                
                const notesValue = document.getElementById('modalNotes').value.trim();

                // Validasi jika ditolak
                if (currentReq.status === 'rejected' && !notesValue) {
                    alert('Catatan/Alasan wajib diisi jika Anda menolak permintaan ini.');
                    document.getElementById('modalNotes').focus();
                    return;
                }

                // Loading state
                this.disabled = true;
                this.style.opacity = '0.7';
                this.textContent = 'Memproses...';

                // Submit Form
                const form = document.getElementById('statusForm');
                document.getElementById('statusInput').value = currentReq.status;
                document.getElementById('notesInput').value = notesValue;
                form.action = '/gudang/request/' + currentReq.id + '/status';
                form.submit();
            });
        </script>
    @endif

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
            
            --tr-primary: #4f46e5; /* Indigo */
            --tr-primary-hover: #4338ca;
            --tr-primary-light: #e0e7ff;
            
            --tr-info: #0ea5e9;
            --tr-info-bg: #e0f2fe;
            --tr-success: #10b981;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            --tr-danger: #ef4444;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #b91c1c;
            --tr-warning: #f59e0b;
            --tr-warning-bg: #fffbeb;
            --tr-warning-text: #b45309;
            
            --tr-slate: #64748b;
            --tr-slate-bg: #f1f5f9;
            
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --tr-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.05);
            --tr-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1360px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-primary); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-indigo { background: var(--tr-primary-light); color: var(--tr-primary); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0; line-height: 1.4; }
        
        .tr-header-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: center; gap: 10px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 500; border: 1px solid transparent; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: #a7f3d0; }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: #fecaca; }

        /* ── KPI GRID ── */
        .tr-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .tr-kpi-card { background: var(--tr-surface); border-radius: var(--tr-radius-md); border: 1px solid var(--tr-border); padding: 1.25rem; display: flex; flex-direction: column; gap: 4px; box-shadow: var(--tr-shadow-sm); border-top-width: 3px; }
        .tr-kpi-card.border-blue { border-top-color: var(--tr-primary); }
        .tr-kpi-card.border-warning { border-top-color: var(--tr-warning); }
        .tr-kpi-card.border-success { border-top-color: var(--tr-success); }
        .tr-kpi-card.border-danger { border-top-color: var(--tr-danger); }
        .tr-kpi-card.border-slate { border-top-color: var(--tr-slate); }
        
        .tr-kpi-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); letter-spacing: 0.05em; }
        .tr-kpi-value { font-size: 1.6rem; font-weight: 800; line-height: 1.1; }
        .text-blue { color: var(--tr-primary); }
        .text-warning { color: var(--tr-warning); }
        .text-success { color: var(--tr-success); }
        .text-danger { color: var(--tr-danger); }
        .text-slate { color: var(--tr-slate); }

        /* ── CARD & FILTER ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-filter-bar { padding: 1rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        
        .tr-filter-grid { display: grid; grid-template-columns: 1fr 180px 180px auto; gap: 1rem; align-items: flex-end; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; }
        
        .tr-search { display: flex; align-items: center; gap: 8px; background: var(--tr-bg); border-radius: 6px; padding: 0.5rem 0.85rem; border: 1px solid var(--tr-border); transition: border-color 0.2s; }
        .tr-search:focus-within { border-color: var(--tr-primary); background: #ffffff; }
        .tr-search-icon { color: var(--tr-text-light); flex-shrink: 0; }
        .tr-search input { border: none; background: transparent; font-size: 0.85rem; font-family: inherit; color: var(--tr-text-main); outline: none; width: 100%; }
        
        .tr-select-wrapper { position: relative; }
        .tr-select { width: 100%; padding: 0.5rem 0.85rem; padding-right: 2rem; border: 1px solid var(--tr-border); border-radius: 6px; font-family: inherit; font-size: 0.85rem; color: var(--tr-text-main); background: var(--tr-bg); appearance: none; outline: none; transition: border-color 0.2s; cursor: pointer; height: 38px; }
        .tr-select:focus { border-color: var(--tr-primary); background: #ffffff; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }

        .tr-filter-actions { display: flex; gap: 6px; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 38px; }
        
        .tr-btn-primary { background: var(--tr-primary); color: #ffffff; border-color: var(--tr-primary); box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2); }
        .tr-btn-primary:hover { background: var(--tr-primary-hover); transform: translateY(-1px); }
        
        .tr-btn-success { background: var(--tr-success); color: #ffffff; border-color: var(--tr-success); box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2); }
        .tr-btn-success:hover { background: var(--tr-success-hover); transform: translateY(-1px); }
        
        .tr-btn-danger { background: var(--tr-danger); color: #ffffff; border-color: var(--tr-danger); box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2); }
        .tr-btn-danger:hover { background: var(--tr-danger-hover); transform: translateY(-1px); }
        
        .tr-btn-dark { background: var(--tr-text-main); color: #ffffff; border-color: var(--tr-text-main); }
        .tr-btn-dark:hover { background: #000000; transform: translateY(-1px); }
        
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); background: #f8fafc; }
        
        .tr-btn-danger-outline { border-color: var(--tr-danger-border); color: var(--tr-danger-text); background: transparent; }
        .tr-btn-danger-outline:hover { background: var(--tr-danger-bg); }

        /* Action Text Buttons */
        .tr-actions-group { display: flex; gap: 8px; justify-content: flex-end; }
        .tr-action-btn-text { display: inline-flex; align-items: center; gap: 4px; padding: 0.35rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; font-family: inherit; border: 1px solid transparent; cursor: pointer; background: transparent; transition: all 0.2s; }
        .tr-action-btn-text.tr-acc { color: var(--tr-success); border-color: var(--tr-success-border); }
        .tr-action-btn-text.tr-acc:hover { background: var(--tr-success-bg); }
        .tr-action-btn-text.tr-rej { color: var(--tr-danger); border-color: var(--tr-danger-border); }
        .tr-action-btn-text.tr-rej:hover { background: var(--tr-danger-bg); }
        .tr-status-locked { font-size: 0.75rem; font-weight: 600; color: var(--tr-text-light); font-style: italic; }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 1000px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.c, .tr-table td.c { text-align: center; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* ── CELL FORMATTING ── */
        .tr-date-main { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; white-space: nowrap; }
        .tr-date-sub { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }
        
        .tr-user-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-user-role { font-size: 0.7rem; color: var(--tr-text-light); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px; }
        
        .tr-prod-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; line-height: 1.3; }
        .tr-prod-sku { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 4px; }
        .tr-font-mono { font-family: monospace; background: var(--tr-border-light); padding: 1px 4px; border-radius: 4px; color: var(--tr-text-main); font-weight: 600; }
        
        .tr-qty-badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.25rem 0.6rem; border-radius: 999px; background: var(--tr-bg); border: 1px solid var(--tr-border); color: var(--tr-text-main); font-weight: 800; font-size: 0.85rem; min-width: 44px; }
        
        .tr-route-box { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-top: 4px; }
        .tr-route-wh { font-size: 0.75rem; color: var(--tr-text-muted); font-weight: 600; }
        .tr-route-arrow { color: var(--tr-text-light); }
        
        .tr-notes-text { font-size: 0.8rem; color: var(--tr-text-main); font-style: italic; margin-bottom: 4px; }
        .tr-doc-ref { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 4px; }
        .tr-spv-note { font-size: 0.75rem; color: var(--tr-danger-text); background: var(--tr-danger-bg); border-left: 2px solid var(--tr-danger); padding: 0.35rem 0.5rem; border-radius: 4px; margin-top: 6px; }

        /* Status Badges */
        .tr-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-badge-gray { background: var(--tr-slate-bg); color: var(--tr-slate); }
        .tr-badge-blue { background: var(--tr-info-bg); color: var(--tr-info); }
        .tr-badge-success { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-badge-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); }
        .tr-badge-warning { background: var(--tr-warning-bg); color: var(--tr-warning-text); }

        /* ── MODAL ── */
        .tr-modal { position: fixed; inset: 0; z-index: 100; display: none; align-items: center; justify-content: center; padding: 1rem; }
        .tr-modal.active { display: flex; }
        .tr-modal-backdrop { position: absolute; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(2px); }
        .tr-modal-card { position: relative; z-index: 101; width: 100%; max-width: 480px; background: var(--tr-surface); border-radius: var(--tr-radius-lg); box-shadow: var(--tr-shadow-lg); overflow: hidden; animation: tr-modal-pop 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes tr-modal-pop { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
        
        .tr-modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-modal-title { font-size: 1.15rem; font-weight: 800; color: var(--tr-text-main); margin: 0; }
        
        .tr-modal-body { padding: 1.5rem; background: #ffffff; }
        .tr-modal-desc { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 0 1rem 0; line-height: 1.5; }
        .tr-modal-desc strong { color: var(--tr-text-main); }
        
        .tr-textarea { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--tr-border); border-radius: 6px; font-family: inherit; font-size: 0.85rem; color: var(--tr-text-main); background: var(--tr-bg); outline: none; transition: border-color 0.2s; resize: vertical; min-height: 100px; margin-top: 0.5rem; }
        .tr-textarea:focus { border-color: var(--tr-primary); background: #ffffff; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        
        .tr-modal-footer { padding: 1.25rem 1.5rem; border-top: 1px solid var(--tr-border-light); background: var(--tr-bg); display: flex; justify-content: flex-end; gap: 0.75rem; }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-text-light); }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* ── PAGINATION ── */
        .tr-pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--tr-border-light); background: #ffffff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 992px) {
            .tr-filter-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-header-actions { width: 100%; }
            .tr-header-actions .tr-btn { width: 100%; justify-content: center; }
            .tr-kpi-grid { grid-template-columns: 1fr 1fr; }
            .tr-filter-grid { grid-template-columns: 1fr; gap: 1rem; align-items: stretch; }
            .tr-filter-actions { display: grid; grid-template-columns: 1fr 1fr; }
            .tr-modal-footer { flex-direction: column-reverse; }
            .tr-modal-footer .tr-btn { width: 100%; }
        }
    </style>
    @endpush
</x-app-layout>