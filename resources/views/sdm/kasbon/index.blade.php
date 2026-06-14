<x-app-layout>
    <x-slot name="header">Approval Kasbon (Cash Advance)</x-slot>

    <div class="kb-page">
        {{-- TOP HEADER --}}
        <div class="kb-header">
            <div>
                <h1 class="kb-title">Approval Kasbon</h1>
                <p class="kb-subtitle">Kelola pengajuan pinjaman karyawan dan integrasikan dengan sistem penggajian.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="kb-alert kb-alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="kb-alert kb-alert-danger">{{ session('error') }}</div>
        @endif

        {{-- DAFTAR KASBON --}}
        <div class="kb-card">
            <div class="kb-card-head">
                <h2 class="kb-card-title">Daftar Pengajuan Kasbon</h2>
            </div>
            <div class="kb-table-wrap">
                <table class="kb-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Keterangan</th>
                            <th class="r">Nominal (Rp)</th>
                            <th class="c">Status</th>
                            <th class="c">Jadwal Potong</th>
                            <th class="c">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kasbons as $kb)
                        <tr class="{{ $kb->status === 'pending' ? 'kb-row-pending' : '' }}">
                            <td>
                                <strong>{{ $kb->user?->name ?? 'User Dihapus' }}</strong>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $kb->user?->employee?->position ?? 'Karyawan' }}</div>
                            </td>
                            <td>{{ $kb->date->format('d M Y') }}</td>
                            <td>{{ Str::limit($kb->purpose, 40) }}</td>
                            <td class="r"><strong>{{ number_format($kb->amount, 0, ',', '.') }}</strong></td>
                            <td class="c">
                                @if($kb->status === 'approved')
                                    <span class="kb-badge kb-badge-success">Disetujui</span>
                                    <div style="font-size: 0.65rem; color: #64748b; margin-top:2px;">oleh {{ explode(' ', $kb->approver?->name ?? '')[0] }}</div>
                                @elseif($kb->status === 'rejected')
                                    <span class="kb-badge kb-badge-danger">Ditolak</span>
                                @else
                                    <span class="kb-badge kb-badge-warning">Pending</span>
                                @endif
                            </td>
                            <td class="c">
                                @if($kb->deduction_month)
                                    <span class="kb-pill">{{ \Carbon\Carbon::createFromFormat('Y-m', $kb->deduction_month)->translatedFormat('F Y') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="c">
                                @if($kb->status === 'pending')
                                    <button class="kb-btn-sm kb-btn-success" onclick="openApproveModal({{ $kb->id }}, '{{ $kb->user?->name }}', {{ $kb->amount }})">Setujui</button>
                                    <form action="{{ route('sdm.kasbon.reject', $kb) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tolak kasbon ini?');">
                                        @csrf
                                        <button type="submit" class="kb-btn-sm kb-btn-danger">Tolak</button>
                                    </form>
                                @else
                                    <span class="text-muted" style="font-size: 0.8rem;">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="kb-empty">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    <h3>Belum ada pengajuan</h3>
                                    <p>Daftar kasbon karyawan masih kosong.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL APPROVE --}}
    <div id="modalApprove" class="kb-modal" style="display:none;">
        <div class="kb-modal-box">
            <h3>Persetujuan Kasbon</h3>
            <p>Tentukan di bulan apa kasbon ini akan dipotong otomatis dari gaji <strong><span id="approveName"></span></strong>.</p>
            <form id="formApprove" action="" method="POST">
                @csrf
                <div class="kb-form-group">
                    <label>Bulan Pemotongan Gaji <span class="text-danger">*</span></label>
                    <input type="month" name="deduction_month" class="kb-input" required value="{{ now()->format('Y-m') }}">
                    <small style="color: #64748b; font-size:0.75rem; display:block; margin-top:4px;">Nominal Rp <span id="approveAmount"></span> akan otomatis masuk ke daftar Potongan Gaji bulan tersebut.</small>
                </div>
                <div class="kb-modal-actions">
                    <button type="button" class="kb-btn kb-btn-ghost" onclick="document.getElementById('modalApprove').style.display='none'">Batal</button>
                    <button type="submit" class="kb-btn kb-btn-primary">Setujui & Jadwalkan Potong</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openApproveModal(id, name, amount) {
            document.getElementById('approveName').textContent = name;
            document.getElementById('approveAmount').textContent = amount.toLocaleString('id-ID');
            document.getElementById('formApprove').action = `/sdm/kasbon/${id}/approve`;
            document.getElementById('modalApprove').style.display = 'flex';
        }
    </script>
    @endpush

    @push('styles')
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .kb-page { font-family: 'Plus Jakarta Sans', sans-serif; max-width: 1200px; margin: 0 auto; padding: 2rem 1rem; color: #0f172a; }
    .kb-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
    .kb-title { font-size: 1.8rem; font-weight: 800; margin: 0 0 0.3rem; letter-spacing: -0.02em; }
    .kb-subtitle { font-size: 0.9rem; color: #64748b; margin: 0; }
    
    .kb-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0.6rem 1.25rem; border-radius: 12px; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; }
    .kb-btn:hover { transform: translateY(-2px); }
    .kb-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; box-shadow: 0 4px 15px rgba(37,99,235,0.3); }
    .kb-btn-ghost { background: #f1f5f9; color: #475569; }
    .kb-btn-ghost:hover { background: #e2e8f0; color: #0f172a; }

    .kb-btn-sm { display: inline-flex; align-items: center; padding: 0.35rem 0.75rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; }
    .kb-btn-sm:hover { transform: translateY(-1px); }
    .kb-btn-success { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .kb-btn-success:hover { background: #10b981; color: #fff; }
    .kb-btn-danger { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .kb-btn-danger:hover { background: #ef4444; color: #fff; }

    .kb-alert { padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 600; font-size: 0.9rem; }
    .kb-alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .kb-alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    .kb-card { background: #fff; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); overflow: hidden; }
    .kb-card-head { padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; background: rgba(248,250,252,0.5); }
    .kb-card-title { font-size: 1.1rem; font-weight: 800; margin: 0; }

    .kb-table-wrap { overflow-x: auto; }
    .kb-table { width: 100%; border-collapse: collapse; min-width: 800px; }
    .kb-table th { padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #64748b; background: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: left; letter-spacing: 0.05em; }
    .kb-table td { padding: 1.25rem 1.5rem; font-size: 0.85rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .kb-table tr:hover td { background: #f8fafc; }
    .kb-row-pending td { background: #fffbeb; }
    .kb-row-pending:hover td { background: #fef3c7; }
    .kb-table th.c, .kb-table td.c { text-align: center; }
    .kb-table th.r, .kb-table td.r { text-align: right; }

    .kb-badge { display: inline-block; padding: 0.3rem 0.75rem; border-radius: 8px; font-size: 0.75rem; font-weight: 800; }
    .kb-badge-success { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .kb-badge-danger { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .kb-badge-warning { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }

    .kb-pill { display: inline-block; padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.75rem; font-weight: 700; background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }

    .kb-empty { text-align: center; padding: 4rem 1rem; color: #94a3b8; }
    .kb-empty svg { opacity: 0.5; margin-bottom: 1rem; }
    .kb-empty h3 { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem; }
    .kb-empty p { font-size: 0.85rem; margin: 0; }

    /* Modal */
    .kb-modal { position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; }
    .kb-modal-box { background: #fff; width: 100%; max-width: 500px; border-radius: 20px; padding: 2.5rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modIn 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes modIn { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .kb-modal-box h3 { font-size: 1.4rem; font-weight: 800; margin: 0 0 0.5rem; }
    .kb-modal-box p { font-size: 0.9rem; color: #64748b; margin: 0 0 1.5rem; }
    .kb-form-group { margin-bottom: 1.25rem; }
    .kb-form-group label { display: block; font-size: 0.8rem; font-weight: 800; color: #475569; margin-bottom: 0.5rem; }
    .kb-input { width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1.5px solid #e2e8f0; font-family: inherit; font-size: 0.9rem; transition: all 0.2s; outline: none; background: #f8fafc; }
    .kb-input:focus { background: #fff; border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37,99,235,0.1); }
    .kb-modal-actions { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 2rem; }
    .text-danger { color: #ef4444; }
    .text-muted { color: #94a3b8; }
    </style>
    @endpush
</x-app-layout>
